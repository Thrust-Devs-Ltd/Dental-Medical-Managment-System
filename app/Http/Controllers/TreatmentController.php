<?php

namespace App\Http\Controllers;

use App\Treatment;
use App\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class TreatmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param $patient_id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request, $patient_id)
    {
        if ($request->ajax()) {

            $data = DB::table('treatments')
                ->leftJoin('appointments', 'appointments.id', 'treatments.appointment_id')
                ->leftJoin('users', 'users.id', 'treatments._who_added')
                ->whereNull('treatments.deleted_at')
                ->where('appointments.patient_id', $patient_id)
                ->orderBy('treatments.updated_at','desc')
                ->select('treatments.*', 'users.surname as added_by')
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('editBtn', function ($row) {
                    $btn = '<a href="#" onclick="editTreatment(' . $row->id . ')" class="btn btn-primary">Edit</a>';
                    return $btn;
                })
                ->addColumn('editBtn', function ($row) {
                    $btn = '<a href="#" onclick="editTreatment(' . $row->id . ')" class="btn btn-primary">Edit</a>';
                    return $btn;
                })
                ->addColumn('deleteBtn', function ($row) {

                    $btn = '<a href="#" onclick="deleteTreatment(' . $row->id . ')" class="btn btn-danger">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['editBtn', 'deleteBtn'])
                ->make(true);
        }
    }

    public function TreatmentHistory(Request $request, $patient_id)
    {
        if ($request->ajax()) {

            $data = DB::table('treatments')
                ->join('appointments', 'appointments.id', 'treatments.appointment_id')
                ->join('users', 'users.id', 'treatments._who_added')
                ->where('appointments.patient_id', $patient_id)
                ->select('treatments.*', 'users.surname', 'users.othername')
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('doctor', function ($row) {
                    return $row->surname . " " . $row->othername;
                })
                ->make(true);
        }
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
        Validator::make($request->all(), [
            'clinical_notes' => 'required',
            'treatment' => 'required'
        ])->validate();
        $status = Treatment::create([
            'clinical_notes' => $request->clinical_notes,
            'treatment' => $request->treatment,
            'appointment_id' => $request->appointment_id,
            '_who_added' => Auth::User()->id
        ]);

        if ($status) {
            return response()->json(['message' => 'treatment has been captured successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred', 'status' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Treatment $treatment
     * @return \Illuminate\Http\Response
     */
    public function show(Treatment $treatment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Treatment $treatment
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $treatment = Treatment::where('id', $id)->first();
        return response()->json($treatment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Treatment $treatment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'clinical_notes' => 'required',
            'treatment' => 'required',
            'appointment_id' => 'required'
        ])->validate();
        $status = Treatment::where('id', $id)->update([
            'clinical_notes' => $request->clinical_notes,
            'treatment' => $request->treatment,
            'appointment_id' => $request->appointment_id,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'treatment has been updated successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred', 'status' => false]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Treatment $treatment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = Treatment::where('id', $id)->delete();
        if ($status) {
            return response()->json(['message' => 'treatment has been deleted successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred', 'status' => false]);

    }
}
