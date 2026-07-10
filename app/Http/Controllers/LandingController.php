<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ClinicSetting;
use App\Models\Patient;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LandingController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('landing.index', compact('services'));
    }

    public function privacy()
    {
        return view('landing.privacy');
    }

    public function terms()
    {
        return view('landing.terms');
    }

    public function about()
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();
        return view('landing.pages.about', compact('services'));
    }

    public function services()
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();
        return view('landing.pages.services', compact('services'));
    }

    public function booking()
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();
        return view('landing.pages.booking', compact('services'));
    }

    public function contact()
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();
        return view('landing.pages.contact', compact('services'));
    }

    public function bookAppointment(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:50',
            'appointment_date' => 'required|date|after_or_equal:today',
            'service_id' => 'required|exists:services,id',
            'message' => 'nullable|string|max:1000',
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $doctor = User::whereIn('role', ['doctor', 'admin'])->first();

        // Find or create patient by phone so returning clients keep one record
        $patient = Patient::firstOrCreate(
            ['phone' => $validated['phone']],
            [
                'file_number' => $this->generateFileNumber(),
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'new_patient' => true,
                'registered_by' => $doctor?->id,
            ]
        );

        // Update name/email if patient already exists and details changed
        if ($patient->wasRecentlyCreated === false) {
            $patient->update([
                'name' => $validated['name'],
                'email' => $validated['email'] ?? $patient->email,
            ]);
        }

        $settings = ClinicSetting::current();
        $duration = $service->duration_minutes ?? $settings?->default_appointment_duration ?? 30;
        $start = Carbon::parse($validated['appointment_date'])->setTime(9, 0);
        $end = $start->copy()->addMinutes($duration);

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor?->id,
            'service_id' => $service->id,
            'appointment_date' => $validated['appointment_date'],
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'status' => Appointment::STATUS_BOOKED,
            'notes' => $validated['message'] ?? null,
            'cost' => $service->price ?? 0,
            'booked_by' => $doctor?->id,
        ]);

        $message = 'Your appointment request has been received. Our reception team will review and approve it shortly.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'appointment' => [
                    'id' => $appointment->id,
                    'service' => $service->name,
                    'date' => $appointment->appointment_date->format('M d, Y'),
                    'status' => $appointment->status,
                ],
            ]);
        }

        return redirect()->route('landing', ['#appointment'])
            ->with('status', $message);
    }

    private function generateFileNumber(): string
    {
        $latest = Patient::orderByDesc('id')->value('file_number');
        $number = 1;

        if ($latest && preg_match('/MV-(\d+)/', $latest, $matches)) {
            $number = (int) $matches[1] + 1;
        }

        return 'MV-'.str_pad((string) $number, 4, '0', STR_PAD_LEFT);
    }
}
