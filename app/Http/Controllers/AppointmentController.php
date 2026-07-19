<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Room;
use App\Models\Service;
use App\Models\User;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,doctor,reception');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $date = $request->get('date', today()->toDateString());

        $query = Appointment::with(['patient', 'doctor', 'service', 'room'])
            ->whereDate('appointment_date', $date)
            ->orderBy('start_time');

        if ($user->isDoctor()) {
            $query->where('doctor_id', $user->id);
        }

        $appointments = $query->paginate(20)->withQueryString();

        return view('appointments.index', compact('appointments', 'date'));
    }

    public function create(Request $request)
    {
        $patients = Patient::orderBy('name')->get();
        $doctors = User::where('role', 'doctor')->get();
        $services = Service::where('is_active', true)->get();
        $rooms = Room::where('is_active', true)->get();

        $preselectedPatient = null;
        if ($request->get('patient_id')) {
            $preselectedPatient = Patient::find($request->get('patient_id'));
        } elseif ($request->get('user_id')) {
            $preselectedPatient = Patient::where('user_id', $request->get('user_id'))->first();
        }

        $statuses = Appointment::STATUS_LABELS;

        return view('appointments.create', compact('patients', 'doctors', 'services', 'rooms', 'preselectedPatient', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:users,id',
            'service_id' => 'nullable|exists:services,id',
            'room_id' => 'nullable|exists:rooms,id',
            'appointment_date' => 'required|date',
            'start_time' => 'required',
            'status' => ['required', Rule::in(Appointment::STATUSES)],
            'notes' => 'nullable|string',
        ]);

        $validated['service_id'] = $validated['service_id'] ?? null;
        $validated['room_id'] = $validated['room_id'] ?? null;
        $validated['booked_by'] = auth()->id();

        $appointment = $this->buildAppointment($validated);

        $conflict = $this->hasConflict($appointment);
        if ($conflict) {
            return back()->withInput()->withErrors(['start_time' => 'This slot conflicts with an existing appointment for the selected doctor or room.']);
        }

        $appt = Appointment::create($appointment);
        $appt->load(['patient', 'service']);

        if ($appt->patient && $appt->patient->phone) {
            app(SmsService::class)->sendToPatient($appt->patient, 'booking_confirmation', [
                'date' => $appt->appointment_date->format('d/m/Y'),
                'time' => $appt->start_time?->format('H:i'),
                'service' => $appt->service?->name ?? 'dental service',
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Miadi imesajiliwa kwa mafanikio.',
                'appointment' => [
                    'id' => $appt->id,
                    'time' => $appt->start_time?->format('H:i') . ' - ' . $appt->end_time?->format('H:i'),
                    'patient' => $appt->patient?->name,
                    'service' => $appt->service?->name ?? '-',
                    'doctor' => $appt->doctor?->name ?? '-',
                    'room' => $appt->room?->name ?? '-',
                    'status' => $appt->status,
                    'statusLabel' => $appt->statusLabel(),
                    'statusColor' => $appt->statusColor(),
                    'show_url' => route('appointments.show', $appt),
                    'edit_url' => route('appointments.edit', $appt),
                    'edit_form_url' => route('appointments.edit-form', $appt),
                    'update_url' => route('appointments.update', $appt),
                    'delete_url' => route('appointments.destroy', $appt),
                ],
            ]);
        }

        return redirect()->route('appointments.index', ['date' => $appointment['appointment_date']])
            ->with('status', 'Appointment booked successfully.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor', 'service', 'room', 'clinicalRecord']);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $patients = Patient::orderBy('name')->get();
        $doctors = User::where('role', 'doctor')->get();
        $services = Service::where('is_active', true)->get();
        $rooms = Room::where('is_active', true)->get();
        $statuses = Appointment::STATUS_LABELS;

        return view('appointments.edit', compact('appointment', 'patients', 'doctors', 'services', 'rooms', 'statuses'));
    }

    public function editForm(Appointment $appointment)
    {
        $patients = Patient::orderBy('name')->get();
        $doctors = User::where('role', 'doctor')->get();
        $services = Service::where('is_active', true)->get();
        $rooms = Room::where('is_active', true)->get();
        $statuses = Appointment::STATUS_LABELS;
        return view('appointments.edit-form', compact('appointment', 'patients', 'doctors', 'services', 'rooms', 'statuses'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:users,id',
            'service_id' => 'nullable|exists:services,id',
            'room_id' => 'nullable|exists:rooms,id',
            'appointment_date' => 'required|date',
            'start_time' => 'required',
            'status' => ['required', Rule::in(Appointment::STATUSES)],
            'notes' => 'nullable|string',
        ]);

        $validated['service_id'] = $validated['service_id'] ?? null;
        $validated['room_id'] = $validated['room_id'] ?? null;

        $oldDate = $appointment->appointment_date?->format('Y-m-d');
        $oldTime = $appointment->start_time?->format('H:i:s');

        $data = $this->buildAppointment($validated, $appointment->id);

        $conflict = $this->hasConflict($data, $appointment->id);
        if ($conflict) {
            return back()->withInput()->withErrors(['start_time' => 'This slot conflicts with an existing appointment for the selected doctor or room.']);
        }

        $appointment->update($data);
        $appointment->load(['patient', 'service', 'doctor', 'room']);

        $dateChanged = $oldDate !== $appointment->appointment_date?->format('Y-m-d')
            || $oldTime !== $appointment->start_time?->format('H:i:s');

        if ($dateChanged && $appointment->patient && $appointment->patient->phone) {
            app(SmsService::class)->sendToPatient($appointment->patient, 'reschedule', [
                'date' => $appointment->appointment_date->format('d/m/Y'),
                'time' => $appointment->start_time?->format('H:i'),
                'service' => $appointment->service?->name ?? 'dental service',
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Miadi imesasishwa.',
                'appointment' => [
                    'id' => $appointment->id,
                    'time' => $appointment->start_time?->format('H:i') . ' - ' . $appointment->end_time?->format('H:i'),
                    'patient' => $appointment->patient?->name,
                    'service' => $appointment->service?->name ?? '-',
                    'doctor' => $appointment->doctor?->name ?? '-',
                    'room' => $appointment->room?->name ?? '-',
                    'status' => $appointment->status,
                    'statusLabel' => $appointment->statusLabel(),
                    'statusColor' => $appointment->statusColor(),
                ],
            ]);
        }

        return redirect()->route('appointments.index', ['date' => $data['appointment_date']])
            ->with('status', 'Appointment updated successfully.');
    }

    public function destroy(Request $request, Appointment $appointment)
    {
        $appointment->load(['patient', 'service']);

        if ($appointment->patient && $appointment->patient->phone) {
            app(SmsService::class)->sendToPatient($appointment->patient, 'cancellation', [
                'date' => $appointment->appointment_date->format('d/m/Y'),
                'time' => $appointment->start_time?->format('H:i'),
                'service' => $appointment->service?->name ?? 'dental service',
            ]);
        }

        $appointment->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Miadi imefutwa na ujumbe umetumwa kwa mgonjwa.']);
        }

        return redirect()->route('appointments.index')->with('status', 'Appointment deleted and SMS sent to patient.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => ['required', Rule::in(Appointment::STATUSES)],
        ]);

        $oldStatus = $appointment->status;
        $appointment->update(['status' => $request->status]);
        $appointment->load(['patient', 'service']);

        if ($request->status === Appointment::STATUS_COMPLETED && $oldStatus !== Appointment::STATUS_COMPLETED
            && $appointment->patient && $appointment->patient->phone) {
            app(SmsService::class)->sendToPatient($appointment->patient, 'follow_up', [
                'date' => $appointment->appointment_date->format('d/m/Y'),
                'time' => $appointment->start_time?->format('H:i'),
                'service' => $appointment->service?->name ?? 'dental service',
            ]);
        }

        if ($request->status === Appointment::STATUS_CANCELLED && $oldStatus !== Appointment::STATUS_CANCELLED
            && $appointment->patient && $appointment->patient->phone) {
            app(SmsService::class)->sendToPatient($appointment->patient, 'cancellation', [
                'date' => $appointment->appointment_date->format('d/m/Y'),
                'time' => $appointment->start_time?->format('H:i'),
                'service' => $appointment->service?->name ?? 'dental service',
            ]);
        }

        return redirect()->back()->with('status', 'Appointment status updated to ' . $appointment->statusLabel() . '.');
    }

    /**
     * List online (website) bookings pending approval.
     */
    public function online(Request $request)
    {
        $status = $request->get('status', Appointment::STATUS_BOOKED);

        $appointments = Appointment::with(['patient', 'doctor', 'service', 'room'])
            ->where('status', $status)
            ->whereDate('appointment_date', '>=', today()->subDays(30))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('appointments.online', compact('appointments', 'status'));
    }

    /**
     * Approve an online booking, confirm the appointment and send SMS.
     */
    public function approve(Request $request, Appointment $appointment, SmsService $smsService)
    {
        if ($appointment->status !== Appointment::STATUS_BOOKED) {
            return redirect()->back()->with('error', 'Only booked appointments can be approved.');
        }

        $appointment->update(['status' => Appointment::STATUS_CONFIRMED]);

        // Mark patient as no longer new after first approved appointment
        if ($appointment->patient) {
            $appointment->patient->update(['new_patient' => false]);

            $smsService->sendToPatient($appointment->patient, 'appointment_approved', [
                'date' => $appointment->appointment_date->format('d/m/Y'),
                'time' => $appointment->start_time?->format('H:i'),
                'service' => $appointment->service?->name ?? 'dental service',
            ]);
        }

        return redirect()->route('appointments.online')
            ->with('status', 'Appointment approved and SMS notification sent to ' . ($appointment->patient?->phone ?? 'patient') . '.');
    }

    private function buildAppointment(array $validated, ?int $excludeId = null): array
    {
        $service = $validated['service_id'] ? Service::find($validated['service_id']) : null;
        $duration = $service?->duration_minutes ?? 30;
        $start = Carbon::parse($validated['start_time']);
        $end = $start->copy()->addMinutes($duration);
        $cost = $service?->price ?? 0;

        return array_merge($validated, [
            'end_time' => $end->format('H:i:s'),
            'cost' => $cost,
        ]);
    }

    private function hasConflict(array $data, ?int $excludeId = null): bool
    {
        $start = Carbon::parse($data['start_time'])->format('H:i:s');
        $end = $data['end_time'];

        $query = Appointment::where('appointment_date', $data['appointment_date'])
            ->where(function ($q) use ($start, $end) {
                $q->where(function ($sub) use ($start, $end) {
                    $sub->where('start_time', '<', $end)->where('end_time', '>', $start);
                });
            })
            ->where(function ($q) use ($data) {
                $q->where('doctor_id', $data['doctor_id'] ?? null)
                  ->orWhere('room_id', $data['room_id'] ?? null);
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
