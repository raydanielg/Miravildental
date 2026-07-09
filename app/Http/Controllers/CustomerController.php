<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ClinicSetting;
use App\Models\Patient;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:customer');
    }

    public function dashboard()
    {
        $user = auth()->user();
        $patient = Patient::with(['appointments', 'clinicalRecords'])->where('user_id', $user->id)->first();

        $appointments = $patient
            ? $patient->appointments()->with(['doctor', 'service', 'room'])->latest('appointment_date')->limit(10)->get()
            : collect();

        $upcoming = $appointments->where('appointment_date', '>=', today())->whereNotIn('status', [Appointment::STATUS_COMPLETED, Appointment::STATUS_CANCELLED, Appointment::STATUS_NO_SHOW])->sortBy('appointment_date');
        $past = $appointments->where('appointment_date', '<', today())->sortByDesc('appointment_date');

        $services = Service::where('is_active', true)->get();
        $doctors = User::where('role', 'doctor')->get();
        $settings = ClinicSetting::current();

        return view('customer.dashboard', compact('user', 'patient', 'upcoming', 'past', 'services', 'doctors', 'settings'));
    }

    public function bookAppointment(Request $request)
    {
        $user = auth()->user();
        $patient = Patient::where('user_id', $user->id)->first();

        if (!$patient) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Patient profile not found.'], 422);
            }
            return redirect()->route('customer.dashboard')->with('error', 'Patient profile not found.');
        }

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        $settings = ClinicSetting::current();
        $duration = $settings->default_appointment_duration ?? 30;
        $start = \Carbon\Carbon::parse($validated['appointment_date'] . ' ' . $validated['start_time']);
        $end = $start->copy()->addMinutes($duration);

        $validated['patient_id'] = $patient->id;
        $validated['end_time'] = $end->format('H:i');
        $validated['status'] = Appointment::STATUS_BOOKED;
        $validated['cost'] = Service::where('id', $validated['service_id'])->value('price') ?? 0;
        $validated['booked_by'] = $user->id;

        $appointment = Appointment::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Appointment requested successfully.',
                'appointment' => [
                    'id' => $appointment->id,
                    'date' => $appointment->appointment_date->format('M d, Y'),
                    'time' => $appointment->start_time->format('H:i'),
                    'service' => $appointment->service?->name,
                ],
            ]);
        }

        return redirect()->route('customer.dashboard')->with('status', 'Appointment requested successfully.');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $patient = Patient::where('user_id', $user->id)->first();

        $validated = $request->validate([
            'phone' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
        ]);

        $user->update(['phone' => $validated['phone'] ?? $user->phone]);

        if ($patient) {
            $patient->update($validated);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Profile updated successfully.']);
        }

        return redirect()->route('customer.dashboard')->with('status', 'Profile updated successfully.');
    }
}
