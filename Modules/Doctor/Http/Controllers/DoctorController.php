<?php

namespace Modules\Doctor\Http\Controllers;

use App\Appointment;
use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Doctor\Charts\MonthlyAppointmentsChart;
use Modules\Doctor\Charts\MonthlyAppointmentsClassificationChart;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data['appointments'] = Appointment::where(['doctor_id' => Auth::User()->id, 'sort_by' => Carbon::today()])
            ->count();
        $data['pending_appointments'] = Appointment::where(['doctor_id' => Auth::User()->id, 'status' => 'Waiting'])
            ->count();
        $data['new_patients'] = Patient::where('created_at', Carbon::today())->count();
        $data['monthly_appointments'] = $this->MonthlyAppointments();
        $data['monthly_appointments_classification'] = $this->MonthlyAppointmentsClassification();
        return view('doctor::index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('doctor::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('doctor::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('doctor::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    private function MonthlyAppointments()
    {
        $daily_appointments = DB::table('appointments')
            ->select(DB::raw('count(id) as daily_appointments'), DB::raw('date(sort_by) as dates'))
            ->whereNull('deleted_at')
            ->where('doctor_id', Auth::User()->id)
            ->groupBy('dates')
            ->orderBy('dates', 'asc')
            ->get();

        $labels = [];
        $appointments_data = [];
        foreach ($daily_appointments as $item) {
            $labels[] = $item->dates;
            $appointments_data[] = $item->daily_appointments;
        }

        // generate the graphs
        $monthly_appointments = new MonthlyAppointmentsChart;
        //cash data
        $monthly_appointments->labels($labels);
        $monthly_appointments->dataset('Daily Appointments', 'line', $appointments_data)->options([
            'fill' => false
        ]);

        return $monthly_appointments;
    }


    private function MonthlyAppointmentsClassification()
    {
        $single_appointments = DB::table('appointments')
            ->whereNull('deleted_at')
            ->where('visit_information', 'Single Treatment')
            ->whereRaw('MONTH(created_at) = ?', [date('m')])
            ->where('doctor_id', Auth::User()->id)
            ->count('id');

        $review_appointments = DB::table('appointments')
            ->whereNull('deleted_at')
            ->where('visit_information', 'Review Treatment')
            ->whereRaw('MONTH(created_at) = ?', [date('m')])
            ->where('doctor_id', Auth::User()->id)
            ->count('id');
        $monthly_appointments_classification = new  MonthlyAppointmentsClassificationChart;

        $monthly_appointments_classification->labels(['Single Treatment', 'Review Treatment']);
        $monthly_appointments_classification->dataset('Daily Appointments', 'pie', [$single_appointments, $review_appointments])
            ->options([
                'backgroundColor' => ['#3598DC', '#78CC66']
            ]);

        return $monthly_appointments_classification;
    }
}
