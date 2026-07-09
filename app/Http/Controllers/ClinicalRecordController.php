<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ClinicalRecord;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Http\Request;

class ClinicalRecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,doctor');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->get('search');

        $records = ClinicalRecord::with(['patient', 'appointment', 'service'])
            ->when($user->isDoctor(), fn ($q) => $q->where('doctor_id', $user->id))
            ->when($search, function ($q) use ($search) {
                $q->whereHas('patient', fn ($sub) => $sub->where('name', 'like', "%{$search}%"));
            })
            ->latest('record_date')
            ->paginate(20);

        $patients = Patient::orderBy('name')->get();
        $services = Service::where('is_active', true)->get();

        return view('clinical-records.index', compact('records', 'search', 'patients', 'services'));
    }

    public function create(Request $request)
    {
        $patients = Patient::orderBy('name')->get();
        $services = Service::where('is_active', true)->get();
        $appointmentId = $request->get('appointment_id');
        $appointment = $appointmentId ? Appointment::with('patient')->find($appointmentId) : null;

        return view('clinical-records.create', compact('patients', 'services', 'appointment'));
    }

    public function createFromAppointment(Appointment $appointment)
    {
        $patients = Patient::orderBy('name')->get();
        $services = Service::where('is_active', true)->get();

        return view('clinical-records.create', [
            'patients' => $patients,
            'services' => $services,
            'appointment' => $appointment,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'service_id' => 'nullable|exists:services,id',
            'record_date' => 'required|date',
            'chief_complaint' => 'nullable|string',
            'clinical_notes' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'prescription' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['doctor_id'] = auth()->id();
        $validated['service_id'] = $validated['service_id'] ?? null;
        $validated['appointment_id'] = $validated['appointment_id'] ?? null;

        $record = ClinicalRecord::create($validated);

        if ($validated['appointment_id']) {
            Appointment::where('id', $validated['appointment_id'])->update(['status' => Appointment::STATUS_COMPLETED]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Rekodi ya matibabu imehifadhiwa.', 'record' => ['id' => $record->id, 'patient' => $record->patient?->name]]);
        }

        return redirect()->route('clinical-records.index')->with('status', 'Treatment record saved successfully.');
    }

    public function show(ClinicalRecord $clinicalRecord)
    {
        $clinicalRecord->load(['patient', 'doctor', 'appointment', 'service']);
        return view('clinical-records.show', compact('clinicalRecord'));
    }

    public function edit(ClinicalRecord $clinicalRecord)
    {
        $patients = Patient::orderBy('name')->get();
        $services = Service::where('is_active', true)->get();
        return view('clinical-records.edit', compact('clinicalRecord', 'patients', 'services'));
    }

    public function update(Request $request, ClinicalRecord $clinicalRecord)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'service_id' => 'nullable|exists:services,id',
            'record_date' => 'required|date',
            'chief_complaint' => 'nullable|string',
            'clinical_notes' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'prescription' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['service_id'] = $validated['service_id'] ?? null;
        $validated['appointment_id'] = $validated['appointment_id'] ?? null;

        $clinicalRecord->update($validated);

        return redirect()->route('clinical-records.index')->with('status', 'Treatment record updated successfully.');
    }

    public function destroy(Request $request, ClinicalRecord $clinicalRecord)
    {
        $clinicalRecord->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Rekodi imefutwa.']);
        }

        return redirect()->route('clinical-records.index')->with('status', 'Treatment record deleted successfully.');
    }
}
