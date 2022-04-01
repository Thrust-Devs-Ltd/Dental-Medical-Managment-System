<?php

namespace App\Http\Controllers;

use App\ChronicDisease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ChronicDiseasesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $patient_id)
    {
        if ($request->ajax()) {

            $data = ChronicDisease::where('patient_id', $patient_id)
                ->OrderBy('updated_at','desc')
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
                    $btn = '<a href="#" onclick="editIllness(' . $row->id . ')" class="btn btn-primary">Edit</a>';
                    return $btn;
                })
                ->addColumn('deleteBtn', function ($row) {

                    $btn = '<a href="#" onclick="deleteIllness(' . $row->id . ')" class="btn btn-danger">Delete</a>';
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
            'disease' => 'required',
            'status' => 'required'
        ])->validate();
        $status = ChronicDisease::create([
            'disease' => $request->disease,
            'status' => $request->status,
            'patient_id' => $request->patient_id,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Chronic Disease has been captured successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred please try again', 'status' => false]);
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
        $illness = ChronicDisease::where('id', $id)->first();
        return response()->json($illness);
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
        Validator::make($request->all(), [
            'disease' => 'required',
            'status' => 'required'
        ])->validate();
        $status = ChronicDisease::where('id', $id)->update([
            'disease' => $request->disease,
            'status' => $request->status,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Chronic Disease has been updated successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred please try again', 'status' => false]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = ChronicDisease::where('id', $id)->delete();
        if ($status) {
            return response()->json(['message' => 'Chronic Disease has been deleted successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred please try again', 'status' => false]);

    }
}
