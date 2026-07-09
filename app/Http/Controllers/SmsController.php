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
            SmsLog::create([
                'patient_id' => $patient->id,
                'phone' => $patient->phone,
                'message' => $validated['message'],
                'trigger' => 'campaign',
                'status' => 'sent',
                'sent_by' => auth()->id(),
            ]);
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

        $log = SmsLog::create([
            'phone' => $validated['phone'],
            'message' => $validated['message'],
            'trigger' => 'test',
            'status' => 'sent',
            'sent_by' => auth()->id(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ujumbe wa kupima umetumwa.',
                'log' => [
                    'phone' => $log->phone,
                    'message' => \Illuminate\Support\Str::limit($log->message, 50),
                    'status' => $log->status,
                    'time' => $log->created_at->format('M d, H:i'),
                ],
            ]);
        }

        return redirect()->route('settings.sms')->with('status', 'Test SMS queued.');
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
