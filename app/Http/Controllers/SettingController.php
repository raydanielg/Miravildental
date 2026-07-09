<?php

namespace App\Http\Controllers;

use App\Models\ClinicSetting;
use App\Models\User;
use App\Models\WorkingHour;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function clinic()
    {
        $settings = ClinicSetting::current() ?? new ClinicSetting();
        return view('settings.clinic', compact('settings'));
    }

    public function updateClinic(Request $request)
    {
        $validated = $request->validate([
            'clinic_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'currency' => 'required|string|max:10',
            'timezone' => 'required|string|max:50',
        ]);

        ClinicSetting::updateOrCreate(['id' => 1], $validated);

        return redirect()->route('settings.clinic')->with('status', 'Clinic profile updated successfully.');
    }

    public function workingHours()
    {
        $doctors = User::where('role', 'doctor')->get();
        $globalHours = WorkingHour::whereNull('doctor_id')->get()->keyBy('day');
        $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];

        return view('settings.working-hours', compact('doctors', 'globalHours', 'days'));
    }

    public function updateWorkingHours(Request $request)
    {
        $validated = $request->validate([
            'hours' => 'required|array',
            'hours.*.start_time' => 'required',
            'hours.*.end_time' => 'required',
            'hours.*.is_open' => 'boolean',
        ]);

        foreach ($validated['hours'] as $day => $data) {
            WorkingHour::updateOrCreate(
                ['doctor_id' => null, 'day' => $day],
                [
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'is_open' => $data['is_open'] ?? false,
                ]
            );
        }

        return redirect()->route('settings.working-hours')->with('status', 'Working hours updated successfully.');
    }

    public function sms()
    {
        $settings = ClinicSetting::current() ?? new ClinicSetting();
        return view('settings.sms', compact('settings'));
    }

    public function updateSms(Request $request)
    {
        $validated = $request->validate([
            'sender_id' => 'required|string|max:20',
            'default_appointment_duration' => 'required|integer|min:5',
            'reminder_24h_before' => 'required|integer|min:0',
            'reminder_2h_before' => 'required|integer|min:0',
            'recall_after_days' => 'required|integer|min:0',
        ]);

        ClinicSetting::updateOrCreate(['id' => 1], $validated);

        return redirect()->route('settings.sms')->with('status', 'SMS settings updated successfully.');
    }
}
