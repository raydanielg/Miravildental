<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ClinicalRecord;
use App\Models\Patient;
use App\Models\SmsLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,doctor');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $start = $request->get('start', now()->startOfMonth()->toDateString());
        $end = $request->get('end', now()->endOfMonth()->toDateString());

        $appointmentQuery = Appointment::query()->whereBetween('appointment_date', [$start, $end]);
        $recordQuery = ClinicalRecord::query()->whereBetween('record_date', [$start, $end]);

        if ($user->isDoctor()) {
            $appointmentQuery->where('doctor_id', $user->id);
            $recordQuery->where('doctor_id', $user->id);
        }

        $totalAppointments = (clone $appointmentQuery)->count();
        $completed = (clone $appointmentQuery)->where('status', Appointment::STATUS_COMPLETED)->count();
        $cancelled = (clone $appointmentQuery)->where('status', Appointment::STATUS_CANCELLED)->count();
        $noShows = (clone $appointmentQuery)->where('status', Appointment::STATUS_NO_SHOW)->count();
        $revenue = (clone $appointmentQuery)->where('status', Appointment::STATUS_COMPLETED)->sum('cost') ?? 0;
        $newPatients = Patient::whereBetween('created_at', [$start, $end])->count();

        $appointmentsByDay = (clone $appointmentQuery)
            ->select(DB::raw('appointment_date as date'), DB::raw('count(*) as count'))
            ->groupBy('appointment_date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(fn ($row) => [$row->date => $row->count]);

        $servicesByCount = (clone $appointmentQuery)
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->select('services.name', DB::raw('count(*) as count'), DB::raw('sum(appointments.cost) as revenue'))
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('count')
            ->get();

        $smsSent = $user->isDoctor() ? 0 : SmsLog::whereBetween('created_at', [$start, $end])->count();

        return view('reports.index', compact(
            'start',
            'end',
            'totalAppointments',
            'completed',
            'cancelled',
            'noShows',
            'revenue',
            'newPatients',
            'appointmentsByDay',
            'servicesByCount',
            'smsSent'
        ));
    }
}
