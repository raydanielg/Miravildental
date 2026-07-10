<?php

namespace App\Services;

use App\Models\ClinicSetting;
use App\Models\Patient;
use App\Models\SmsLog;
use App\Models\SmsTemplate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send an SMS using a template to a patient.
     */
    public function sendToPatient(Patient $patient, string $trigger, array $extra = []): ?SmsLog
    {
        if (! $patient->phone) {
            return null;
        }

        $template = SmsTemplate::where('trigger', $trigger)->where('is_active', true)->first();

        if (! $template) {
            return null;
        }

        $message = $this->parseTemplate($template->body, $patient, $extra);

        $log = SmsLog::create([
            'patient_id' => $patient->id,
            'phone' => $patient->phone,
            'message' => $message,
            'trigger' => $trigger,
            'status' => 'pending',
            'sent_by' => auth()->id() ?? $patient->registered_by,
        ]);

        $this->dispatchToProvider($log);

        return $log->fresh();
    }

    /**
     * Replace template placeholders with patient and clinic data.
     */
    public function parseTemplate(string $body, Patient $patient, array $extra = []): string
    {
        $settings = ClinicSetting::current();

        $replacements = [
            '{{name}}' => $patient->name,
            '{{first_name}}' => $this->firstName($patient->name),
            '{{file_number}}' => $patient->file_number,
            '{{phone}}' => $patient->phone,
            '{{email}}' => $patient->email ?? '',
            '{{clinic_name}}' => $settings?->clinic_name ?? config('app.name', 'Hospitali Yetu'),
            '{{clinic_phone}}' => $settings?->phone ?? '',
            '{{clinic_email}}' => $settings?->email ?? '',
            '{{clinic_address}}' => $settings?->address ?? '',
        ];

        foreach ($extra as $key => $value) {
            $replacements['{{'.$key.'}}'] = $value;
        }

        return strtr($body, $replacements);
    }

    /**
     * Send the automatic welcome SMS for newly registered patients.
     */
    public function sendWelcome(Patient $patient): ?SmsLog
    {
        return $this->sendToPatient($patient, 'welcome');
    }

    /**
     * Send a raw SMS directly.
     */
    public function sendRaw(string $phone, string $message, ?Patient $patient = null, ?string $trigger = null, ?int $sentBy = null): SmsLog
    {
        $log = SmsLog::create([
            'patient_id' => $patient?->id,
            'phone' => $phone,
            'message' => $message,
            'trigger' => $trigger ?? 'manual',
            'status' => 'pending',
            'sent_by' => $sentBy,
        ]);

        $this->dispatchToProvider($log);

        return $log->fresh();
    }

    /**
     * Dispatch a queued SMS log to the configured NextSMS provider.
     */
    public function dispatchToProvider(SmsLog $log): void
    {
        $settings = ClinicSetting::current();

        if (! $settings || $settings->sms_provider !== 'nextsms') {
            $log->update(['status' => 'failed', 'response' => 'NextSMS provider not configured']);

            return;
        }

        $url = $settings->sms_api_url;
        $username = $settings->sms_api_username;
        $password = $settings->sms_api_password;
        $sender = $settings->sender_id;

        if (! $url || ! $username || ! $password || ! $sender) {
            $log->update(['status' => 'failed', 'response' => 'NextSMS credentials incomplete']);

            return;
        }

        $to = $this->normalizePhone($log->phone);

        if (! $to) {
            $log->update(['status' => 'failed', 'response' => 'Invalid phone number: '.$log->phone]);

            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic '.base64_encode($username.':'.$password),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($url, [
                'from' => $sender,
                'to' => $to,
                'text' => $log->message,
            ]);

            $body = $response->body();

            if ($response->successful()) {
                $log->update([
                    'status' => 'sent',
                    'response' => $body,
                    'provider_reference' => $this->extractReference($body),
                ]);
            } else {
                $log->update([
                    'status' => 'failed',
                    'response' => $response->status().' | '.$body,
                ]);
                Log::error('NextSMS send failed', ['phone' => $to, 'response' => $body]);
            }
        } catch (\Throwable $e) {
            $log->update(['status' => 'failed', 'response' => $e->getMessage()]);
            Log::error('NextSMS exception', ['phone' => $to, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Convert phone numbers to NextSMS 255... format.
     */
    public function normalizePhone(string $phone): ?string
    {
        $digits = preg_replace('/\D/', '', $phone);

        if (empty($digits)) {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '255'.substr($digits, 1);
        }

        if (str_starts_with($digits, '7') || str_starts_with($digits, '6') || str_starts_with($digits, '5')) {
            $digits = '255'.$digits;
        }

        if (! str_starts_with($digits, '255') || strlen($digits) < 12) {
            return null;
        }

        return $digits;
    }

    private function extractReference(string $body): ?string
    {
        $data = json_decode($body, true);

        return $data['messageId'] ?? $data['reference'] ?? $data['id'] ?? null;
    }

    private function firstName(string $name): string
    {
        return explode(' ', trim($name))[0];
    }
}
