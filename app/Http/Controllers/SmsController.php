<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\SmsLog;
use App\Models\SmsTemplate;
use App\Services\SmsService;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function send(Request $request)
    {
        $this->authorizeSms();

        $patients = Patient::orderBy('name')->get();
        $templates = SmsTemplate::where('is_active', true)->pluck('name', 'trigger');
        $preselectedPatient = $request->get('patient_id') ? Patient::find($request->get('patient_id')) : null;
        $preselectedAppointment = $request->get('appointment_id') ? Appointment::find($request->get('appointment_id')) : null;

        return view('sms.send', compact('patients', 'templates', 'preselectedPatient', 'preselectedAppointment'));
    }

    public function campaign()
    {
        $this->authorizeSms();

        $patients = Patient::orderBy('name')->get();
        $templates = SmsTemplate::where('is_active', true)->pluck('name', 'trigger');
        $groups = [
            'all' => 'All patients',
            'new' => 'New patients',
            'returning' => 'Returning patients',
            'today' => 'Patients with appointment today',
            'upcoming' => 'Patients with upcoming appointments',
        ];

        return view('sms.campaign', compact('patients', 'templates', 'groups'));
    }

    public function sendCampaign(Request $request)
    {
        $this->authorizeSms();

        $validated = $request->validate([
            'group' => 'required|in:all,new,returning,today,upcoming',
            'message' => 'required|string|max:1600',
            'template' => 'nullable|string',
        ]);

        $query = Patient::query();

        switch ($validated['group']) {
            case 'new':
                $query->where('new_patient', true);
                break;
            case 'returning':
                $query->where('new_patient', false);
                break;
            case 'today':
                $query->whereHas('appointments', fn ($q) => $q->today());
                break;
            case 'upcoming':
                $query->whereHas('appointments', fn ($q) => $q->upcoming());
                break;
        }

        $patients = $query->whereNotNull('phone')->get();
        $sent = 0;

        foreach ($patients as $patient) {
            app(SmsService::class)->sendRaw(
                $patient->phone,
                $validated['message'],
                $patient,
                'campaign',
                auth()->id()
            );
            $sent++;
        }

        $message = "SMS campaign imetumwa kwa mafanikio kwa watu {$sent}.";

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'sent' => $sent,
            ]);
        }

        return redirect()->route('sms.logs')->with('status', "SMS campaign queued to {$sent} patient(s).");
    }

    public function store(Request $request)
    {
        $this->authorizeSms();

        $validated = $request->validate([
            'phone' => 'required|string|max:50',
            'message' => 'required|string|max:1600',
            'patient_id' => 'nullable|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
        ]);

        $patient = ! empty($validated['patient_id']) ? Patient::find($validated['patient_id']) : null;

        $log = app(SmsService::class)->sendRaw(
            $validated['phone'],
            $validated['message'],
            $patient,
            'manual',
            auth()->id()
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'SMS imetumwa kwa mafanikio.',
                'log' => [
                    'phone' => $log->phone,
                    'message' => \Illuminate\Support\Str::limit($log->message, 50),
                    'status' => $log->status,
                    'time' => $log->created_at->format('M d, H:i'),
                ],
            ]);
        }

        return redirect()->route('sms.logs')->with('status', 'SMS queued successfully.');
    }

    public function test(Request $request)
    {
        $this->authorizeSms();

        $validated = $request->validate([
            'phone' => 'required|string|max:50',
            'message' => 'required|string|max:1600',
        ]);

        $log = app(SmsService::class)->sendRaw(
            $validated['phone'],
            $validated['message'],
            null,
            'manual',
            auth()->id()
        );

        if ($request->ajax() || $request->wantsJson()) {
            $response = json_decode($log->response, true);
            $msgInfo = $response['messages'][0] ?? null;

            return response()->json([
                'success' => $log->status === 'sent',
                'message' => $log->status === 'sent'
                    ? 'SMS imetumwa kwa mafanikio!'
                    : 'SMS imeshindwa kutumwa.',
                'log' => [
                    'phone' => $log->phone,
                    'message' => $log->message,
                    'status' => $log->status,
                    'time' => $log->created_at->format('M d, Y H:i'),
                    'provider_reference' => $log->provider_reference,
                    'response' => $log->response,
                    'status_name' => $msgInfo['status']['name'] ?? null,
                    'status_description' => $msgInfo['status']['description'] ?? null,
                    'price' => $msgInfo['price'] ?? null,
                    'sms_count' => $msgInfo['smsCount'] ?? null,
                ],
            ]);
        }

        return redirect()->route('settings.sms')->with('status', 'Test SMS sent.');
    }

    public function logs(Request $request)
    {
        $this->authorizeSms();

        $logs = SmsLog::with(['patient', 'appointment', 'sender'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('sms.logs', compact('logs'));
    }

    public function templates(Request $request)
    {
        $this->authorizeRole('admin');

        $category = $request->get('category', 'all');
        $templates = SmsTemplate::query()
            ->when($category !== 'all', fn ($q) => $q->where('category', $category))
            ->orderBy('category')
            ->orderBy('name')
            ->get();
        $categories = SmsTemplate::distinct()->pluck('category');
        return view('sms.templates', compact('templates', 'category', 'categories'));
    }

    public function storeTemplate(Request $request)
    {
        $this->authorizeRole('admin');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'trigger' => 'required|string|max:100|unique:sms_templates,trigger',
            'category' => 'required|string|max:50',
            'body' => 'required|string|max:1600',
            'is_active' => 'boolean',
            'send_before_hours' => 'nullable|integer|min:0',
            'send_after_days' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        SmsTemplate::create($validated);

        return redirect()->route('sms.templates')->with('status', 'Template added successfully.');
    }

    public function updateTemplate(Request $request, SmsTemplate $template)
    {
        $this->authorizeRole('admin');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'body' => 'required|string|max:1600',
            'is_active' => 'boolean',
            'send_before_hours' => 'nullable|integer|min:0',
            'send_after_days' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $template->update($validated);

        return redirect()->route('sms.templates')->with('status', 'Template updated successfully.');
    }

    private function authorizeSms(): void
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isReception()) {
            abort(403);
        }
    }

    private function authorizeRole(string $role): void
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
    }
}
