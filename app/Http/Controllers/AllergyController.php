<?php

namespace App\Http\Controllers;

use App\Allergy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AllergyController extends Controller
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

            $data = Allergy::where('patient_id', $patient_id)
                ->OrderBy('updated_at', 'DESC')
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == "Active") {
                        $btn = '<span class="label label-sm label-danger"> ' . $row->status . ' </span>';
                    } else {
                        $btn = '<span class="label label-sm label-success"> ' . $row->status . ' </span>';
                    }
                    return $btn;
                })
                ->addColumn('editBtn', function ($row) {
                    $btn = '<a href="#" onclick="editAllergy(' . $row->id . ')" class="btn btn-primary">Edit</a>';
                    return $btn;
                })
                ->addColumn('deleteBtn', function ($row) {
                    $btn = '<a href="#" onclick="deleteAllergy(' . $row->id . ')" class="btn btn-danger">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['status', 'editBtn', 'deleteBtn'])
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
            'body_reaction' => 'required'
        ])->validate();
        $status = Allergy::create([
            'body_reaction' => $request->body_reaction,
            'patient_id' => $request->patient_id,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Patient Allergy has been captured successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again later', 'status' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Allergy $allergy
     * @return \Illuminate\Http\Response
     */
    public function show(Allergy $allergy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Allergy $allergy
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $allergy = Allergy::where('id', $id)->first();
        return response()->json($allergy);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Allergy $allergy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'body_reaction' => 'required'
        ])->validate();
        $status = Allergy::where('id', $id)->update([
            'body_reaction' => $request->body_reaction,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Patient Allergy has been updated successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again later', 'status' => false]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Allergy $allergy
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = Allergy::where('id', $id)->delete();
        if ($status) {
            return response()->json(['message' => 'Patient Allergy has been deleted successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again later', 'status' => false]);

    }
}
