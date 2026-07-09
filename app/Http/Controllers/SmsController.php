<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\SmsLog;
use App\Models\SmsTemplate;
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

    public function store(Request $request)
    {
        $this->authorizeSms();

        $validated = $request->validate([
            'phone' => 'required|string|max:50',
            'message' => 'required|string|max:1600',
            'patient_id' => 'nullable|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
        ]);

        $log = SmsLog::create([
            'patient_id' => $validated['patient_id'] ?? null,
            'appointment_id' => $validated['appointment_id'] ?? null,
            'phone' => $validated['phone'],
            'message' => $validated['message'],
            'trigger' => 'manual',
            'status' => 'sent',
            'sent_by' => auth()->id(),
        ]);

        // TODO: integrate actual SMS provider here
        // $this->sendViaProvider($log);

        return redirect()->route('sms.logs')->with('status', 'SMS queued successfully.');
    }

    public function logs(Request $request)
    {
        $this->authorizeSms();

        $logs = SmsLog::with(['patient', 'appointment', 'sender'])
            ->latest()
            ->paginate(25);

        return view('sms.logs', compact('logs'));
    }

    public function templates()
    {
        $this->authorizeRole('admin');

        $templates = SmsTemplate::all();
        return view('sms.templates', compact('templates'));
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
