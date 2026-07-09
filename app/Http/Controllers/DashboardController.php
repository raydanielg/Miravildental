<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ClinicalRecord;
use App\Models\Patient;
use App\Models\SmsLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,doctor,reception');
    }

    public function index()
    {
        $user = auth()->user();

        $patientQuery = Patient::query();
        $appointmentQuery = Appointment::query();
        $recordQuery = ClinicalRecord::query();

        if ($user->isDoctor()) {
            $appointmentQuery->where('doctor_id', $user->id);
            $recordQuery->where('doctor_id', $user->id);
        }

        // Core counts
        $totalPatients = $patientQuery->count();
        $newPatientsThisWeek = Patient::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $appointmentsToday = with(clone $appointmentQuery)->today()->count();
        $appointmentsThisWeek = with(clone $appointmentQuery)->whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $treatmentsDone = with(clone $appointmentQuery)->where('status', Appointment::STATUS_COMPLETED)->count();
        $treatmentTarget = 120;

        // Revenue (from completed appointments this week)
        $revenueToday = with(clone $appointmentQuery)
            ->whereDate('appointment_date', today())
            ->where('status', Appointment::STATUS_COMPLETED)
            ->sum('cost') ?? 0;
        $revenueThisWeek = with(clone $appointmentQuery)
            ->whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', Appointment::STATUS_COMPLETED)
            ->sum('cost') ?? 0;

        // Today's appointment list
        $todayAppointments = with(clone $appointmentQuery)
            ->today()
            ->with(['patient', 'doctor', 'service', 'room'])
            ->orderBy('start_time')
            ->get();

        // Recent patients
        $recentPatients = Patient::with('latestAppointment')
            ->latest()
            ->limit(5)
            ->get();

        // Schedule for reception (today's appointments)
        $receptionSchedule = with(clone $appointmentQuery)
            ->today()
            ->with(['patient', 'service'])
            ->orderBy('start_time')
            ->get()
            ->map(function ($appt) {
                $appt->checkin_status = match ($appt->status) {
                    Appointment::STATUS_ARRIVED, Appointment::STATUS_IN_TREATMENT, Appointment::STATUS_COMPLETED => 'checked-in',
                    Appointment::STATUS_CONFIRMED => 'waiting',
                    default => 'upcoming',
                };
                return $appt;
            });

        // Weekly revenue bar chart data
        $revenueDays = collect();
        $dayLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $amount = Appointment::query()
                ->when($user->isDoctor(), fn ($q) => $q->where('doctor_id', $user->id))
                ->whereDate('appointment_date', $date)
                ->where('status', Appointment::STATUS_COMPLETED)
                ->sum('cost') ?? 0;
            $revenueDays->push((int) $amount);
            $dayLabels[] = $date->format('D');
        }

        // Performance rings
        $completedCount = with(clone $appointmentQuery)->where('status', Appointment::STATUS_COMPLETED)->count();
        $totalFinishedCount = with(clone $appointmentQuery)->whereIn('status', [Appointment::STATUS_COMPLETED, Appointment::STATUS_NO_SHOW, Appointment::STATUS_CANCELLED])->count();
        $successRate = $totalFinishedCount > 0 ? round(($completedCount / $totalFinishedCount) * 100) : 96;

        $todaySlots = max($todayAppointments->count(), 1);
        $occupied = $todayAppointments->whereIn('status', [Appointment::STATUS_ARRIVED, Appointment::STATUS_IN_TREATMENT, Appointment::STATUS_COMPLETED])->count();
        $occupancyRate = round(($occupied / $todaySlots) * 100);
        $treatmentTargetPct = $treatmentTarget > 0 ? round(($treatmentsDone / $treatmentTarget) * 100) : 0;

        // SMS count (admin + reception only)
        $smsSentToday = $user->isDoctor() ? 0 : SmsLog::whereDate('created_at', today())->count();

        // No-show count for admin
        $noShows = $user->isDoctor() ? 0 : Appointment::where('status', Appointment::STATUS_NO_SHOW)->count();

        return view('dashboard', compact(
            'user',
            'totalPatients',
            'newPatientsThisWeek',
            'appointmentsToday',
            'appointmentsThisWeek',
            'treatmentsDone',
            'treatmentTarget',
            'revenueToday',
            'revenueThisWeek',
            'todayAppointments',
            'recentPatients',
            'receptionSchedule',
            'revenueDays',
            'dayLabels',
            'successRate',
            'occupancyRate',
            'treatmentTargetPct',
            'smsSentToday',
            'noShows'
        ));
    }
}
