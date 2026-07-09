<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ClinicalRecord;
use App\Models\Patient;
use App\Models\SmsLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function export(Request $request)
    {
        $user = auth()->user();
        $start = $request->get('start', now()->startOfMonth()->toDateString());
        $end = $request->get('end', now()->endOfMonth()->toDateString());

        $appointmentQuery = Appointment::query()->whereBetween('appointment_date', [$start, $end]);
        if ($user->isDoctor()) $appointmentQuery->where('doctor_id', $user->id);

        $totalAppointments = (clone $appointmentQuery)->count();
        $completed = (clone $appointmentQuery)->where('status', Appointment::STATUS_COMPLETED)->count();
        $cancelled = (clone $appointmentQuery)->where('status', Appointment::STATUS_CANCELLED)->count();
        $noShows = (clone $appointmentQuery)->where('status', Appointment::STATUS_NO_SHOW)->count();
        $revenue = (clone $appointmentQuery)->where('status', Appointment::STATUS_COMPLETED)->sum('cost') ?? 0;
        $newPatients = Patient::whereBetween('created_at', [$start, $end])->count();

        $services = (clone $appointmentQuery)
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->select('services.name', DB::raw('count(*) as count'), DB::raw('sum(appointments.cost) as revenue'))
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('count')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="report-' . $start . '-to-' . $end . '.csv"',
        ];

        $callback = function () use ($start, $end, $totalAppointments, $completed, $cancelled, $noShows, $revenue, $newPatients, $services) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Report Period', $start . ' to ' . $end]);
            fputcsv($file, ['Total Appointments', $totalAppointments]);
            fputcsv($file, ['Completed', $completed]);
            fputcsv($file, ['Cancelled', $cancelled]);
            fputcsv($file, ['No Shows', $noShows]);
            fputcsv($file, ['Revenue (TZS)', $revenue]);
            fputcsv($file, ['New Patients', $newPatients]);
            fputcsv($file, []);
            fputcsv($file, ['Service', 'Count', 'Revenue (TZS)']);
            foreach ($services as $service) {
                fputcsv($file, [$service->name, $service->count, $service->revenue]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function email(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = auth()->user();
        $start = $request->get('start', now()->startOfMonth()->toDateString());
        $end = $request->get('end', now()->endOfMonth()->toDateString());

        $appointmentQuery = Appointment::query()->whereBetween('appointment_date', [$start, $end]);
        if ($user->isDoctor()) $appointmentQuery->where('doctor_id', $user->id);

        $summary = [
            'period' => $start . ' to ' . $end,
            'totalAppointments' => (clone $appointmentQuery)->count(),
            'completed' => (clone $appointmentQuery)->where('status', Appointment::STATUS_COMPLETED)->count(),
            'cancelled' => (clone $appointmentQuery)->where('status', Appointment::STATUS_CANCELLED)->count(),
            'noShows' => (clone $appointmentQuery)->where('status', Appointment::STATUS_NO_SHOW)->count(),
            'revenue' => (clone $appointmentQuery)->where('status', Appointment::STATUS_COMPLETED)->sum('cost') ?? 0,
            'newPatients' => Patient::whereBetween('created_at', [$start, $end])->count(),
        ];

        $services = (clone $appointmentQuery)
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->select('services.name', DB::raw('count(*) as count'), DB::raw('sum(appointments.cost) as revenue'))
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('count')
            ->get();

        Mail::send('emails.report', compact('summary', 'services'), function ($message) use ($request, $start, $end) {
            $message->to($request->email)
                ->subject('Ripoti ya Miadi - ' . $start . ' hadi ' . $end);
        });

        return redirect()->route('reports.index', ['start' => $start, 'end' => $end])
            ->with('status', 'Ripoti imetumwa kwa email.');
    }
}
