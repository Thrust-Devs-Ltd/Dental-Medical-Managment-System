<?php

namespace App\Http\Controllers;

use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicalTreatmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param $appointment_id
     * @return \Illuminate\Http\Response
     */
    //appointment id
//    to..do
    public function index(Request $request, $appointment_id)
    {
        $patient = DB::table('appointments')
            ->join('patients', 'patients.id', 'appointments.patient_id')
            ->where('appointments.id', $appointment_id)
            ->select('patients.*')
            ->first();
        $patient_id = '';
        if ($patient != null) {
            $patient_id = $patient->id;
        }
        //patient medical cards
        $data['medical_cards'] = DB::table('medical_card_items')
            ->join('medical_cards', 'medical_cards.id', 'medical_card_items.medical_card_id')
            ->whereNull('medical_card_items.deleted_at')
            ->where('medical_cards.patient_id', $patient_id)
            ->get();

        $data['patient'] = $patient;

        $data['appointment_id'] = $appointment_id;
        return view('medical_treatment.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
