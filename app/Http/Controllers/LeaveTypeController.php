<?php

namespace App\Http\Controllers;

use App\Http\Helper\FunctionsHelper;
use App\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::table('leave_types')
                ->leftJoin('users', 'users.id', 'leave_types._who_added')
                ->whereNull('leave_types.deleted_at')
                ->select(['leave_types.*', 'users.surname'])
                ->OrderBy('leave_types.id', 'desc')
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('addedBy', function ($row) {
                    return $row->surname;
                })
                ->addColumn('editBtn', function ($row) {
                    if ($row->deleted_at == null) {
                        return '<a href="#" onclick="editRecord(' . $row->id . ')" class="btn btn-primary">Edit</a>';
                    }
                })
                ->addColumn('deleteBtn', function ($row) {
                    return '<a href="#" onclick="deleteRecord(' . $row->id . ')" class="btn btn-danger">Delete</a>';
                })
                ->rawColumns(['editBtn', 'deleteBtn'])
                ->make(true);
        }
        return view('leave_types.index');
    }

    public function filter(Request $request)
    {
        $name = $request->q;

        if ($name) {
            $search = $name;
            $data = LeaveType::where('name', 'LIKE', "%$search%")->get();

            $formatted_tags = [];
            foreach ($data as $tag) {
                $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->name];
            }
            return \Response::json($formatted_tags);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public
    function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public
    function store(Request $request)
    {
        Validator::make($request->all(),
            [
                'name' => 'required',
                'max_days' => 'required'
            ])->validate();

        $success = LeaveType::create(
            [
                'name' => $request->name,
                'max_days' => $request->max_days,
                '_who_added' => Auth::User()->id
            ]);
        return FunctionsHelper::messageResponse("Leave type has been added successfully", $success);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\LeaveType $leaveType
     * @return \Illuminate\Http\Response
     */
    public
    function show(LeaveType $leaveType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return void
     */
    public
    function edit($id)
    {
        $leaveType = LeaveType::where('id', $id)->first();
        return response()->json($leaveType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public
    function update(Request $request, $id)
    {
        Validator::make($request->all(),
            [
                'name' => 'required',
                'max_days' => 'required'
            ])->validate();

        $success = LeaveType::where('id', $id)->update(
            [
                'name' => $request->name,
                'max_days' => $request->max_days,
                '_who_added' => Auth::User()->id
            ]);
        return FunctionsHelper::messageResponse("Leave type has been updated successfully", $success);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        $success = LeaveType::where('id', $id)->delete();
        return FunctionsHelper::messageResponse("Leave type has been deleted successfully", $success);
    }
}
