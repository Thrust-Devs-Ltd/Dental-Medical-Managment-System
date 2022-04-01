<?php

namespace App\Http\Controllers;

use App\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use PDF;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        if ($request->ajax()) {

            $data = Prescription::where('appointment_id', $id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('editBtn', function ($row) {
                    $btn = '<a href="#" onclick="editPrescription(' . $row->id . ')" class="btn btn-primary">Edit</a>';
                    return $btn;
                })
                ->addColumn('deleteBtn', function ($row) {

                    $btn = '<a href="#" onclick="deletePrescription(' . $row->id . ')" class="btn btn-danger">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['editBtn', 'deleteBtn'])
                ->make(true);
        }
    }

    public function filterDrugs(Request $request)
    {
        $search = $request->get('term');

        $result = Prescription::select('drug')->get();
        $data = [];
        foreach ($result as $row) {
            $data[] = $row->drug;
        }
        echo json_encode($data);
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
        foreach ($request->addmore as $key => $value) {
            Prescription::create([
                'drug' => $value['drug'],
                'qty' => $value['qty'],
                'directions' => $value['directions'],
                'appointment_id' => $request->appointment_id,
                '_who_added' => Auth::User()->id,
            ]);
        }
        return response()->json(['message' => 'Prescription has been added successfully', 'status' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Prescription $prescription
     * @return \Illuminate\Http\Response
     */
    public function show(Prescription $prescription)
    {
        //
    }

    public function PrintPrescription($appointment_id)
    {
        $data['patient'] = DB::table('appointments')
            ->leftJoin('patients', 'patients.id', 'appointments.patient_id')
            ->where('appointments.id', $appointment_id)
            ->select('patients.*')
            ->first();
        $data['prescriptions'] = Prescription::where('appointment_id', $appointment_id)->get();
        $data['prescribed_by'] = DB::table('prescriptions')->join('users', 'users.id', 'prescriptions._who_added')
            ->whereNull('prescriptions.deleted_at')
            ->where('prescriptions.appointment_id', $appointment_id)
            ->select('users.*')
            ->first();

        $pdf = PDF::loadView('medical_treatment.prescriptions.print_out', $data);
        return $pdf->stream('medium', array("attachment" => false))->header('Content-Type', 'application/pdf');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Prescription $prescription
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $prescription = Prescription::where('id', $id)->first();
        return response()->json($prescription);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Prescription $prescription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $status = Prescription::where('id', $id)->update([
            'drug' => $request->drug,
            'qty' => $request->qty,
            'directions' => $request->directions,
            '_who_added' => Auth::User()->id,
        ]);
        if ($status) {
            return response()->json(['message' => 'prescription has been updated successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again later', 'status' => false]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Prescription $prescription
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = Prescription::where('id', $id)->delete();
        if ($status) {
            return response()->json(['message' => 'prescription has been deleted successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again later', 'status' => false]);

    }
}
