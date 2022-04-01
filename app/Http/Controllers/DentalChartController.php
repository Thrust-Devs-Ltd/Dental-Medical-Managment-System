<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\DentalChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DentalChartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dental_chart.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @param $appointment_id
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $appointment_id)
    {
        $data['patient'] = DB::table('patients')
            ->leftJoin('appointments', 'patients.id', 'appointments.patient_id')
            ->whereNull('patients.deleted_at')
            ->where('appointments.id', $appointment_id)
            ->select('patients.*')
            ->first();
        $data['appointment_id'] = $appointment_id;
        return view('dental_chart.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        get the patient
        $appointment = Appointment::where('id', $request->appointment_id)->first();
        // delete all the previous patient dental chart record
        $delete_old_chart = DB::table('dental_charts')
            ->leftJoin('appointments', 'appointments.id', 'dental_charts.appointment_id')
            ->where([
                'appointments.patient_id' => $appointment->patient_id,
//                'tooth' => $request->tooth,
//                'position' => $request->position,
//                'color' => $request->color,
//                'kind' => $request->kind,
            ])
            ->delete();

        foreach ($request->data as $key => $value) {
            //now insert refresh data from the UI
            DentalChart::create([
                'tooth' => $value['tooth'],
                'position' => $value['position'],
                'color' => $value['color'],
                'kind' => $value['kind'],
                'appointment_id' => $request->appointment_id,
                '_who_added' => Auth::User()->id
            ]);
        }


        return response()->json(['message' => 'dental Chart changes have been captured successfully', 'success' =>
            true]);
//        if ($delete_old_chart) {
//
//        }
//        return response()->json(['message' => 'Sorry an error has just occurred,please try again', 'success' =>
//            true]);

    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //get the patient
        $appointment = Appointment::where('id', $id)->first();
        //return all the patient dental chart
        $data = DB::table('dental_charts')
            ->leftJoin('appointments', 'appointments.id', 'dental_charts.appointment_id')
            ->whereNull('dental_charts.deleted_at')
            ->where('appointments.patient_id', $appointment->patient_id)
            ->select('dental_charts.*')
            ->get();
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\DentalChart $dentalChart
     * @return \Illuminate\Http\Response
     */
    public function edit(DentalChart $dentalChart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\DentalChart $dentalChart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DentalChart $dentalChart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\DentalChart $dentalChart
     * @return \Illuminate\Http\Response
     */
    public function destroy(DentalChart $dentalChart)
    {
        //
    }

    private function checkDentalExists(Request $request, $appointment_id)
    {
        $appointment = Appointment::where('id', $appointment_id)->first();
        $check = DB::table('dental_charts')
            ->leftJoin('appointments', 'appointments.id', 'dental_charts.appointment_id')
            ->whereNull('dental_charts.deleted_at')
            ->where([
                'treatment' => $request->treatment,
                'tooth' => $request->tooth,
                'section' => $request->section,
                'color' => $request->color,
                'patient_id' => $appointment->patient_id
            ])->count();
        return $check;
    }


}
