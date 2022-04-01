<?php

namespace App\Http\Controllers;

use App\Http\Helper\FunctionsHelper;
use App\leaveRequest;
use App\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::table('leave_requests')
                ->leftJoin('leave_types', 'leave_types.id', 'leave_requests.leave_type_id')
                ->whereNull('leave_requests.deleted_at')
                ->where('leave_requests._who_added', '=', Auth::User()->id)
                ->select(['leave_requests.*', 'leave_types.name'])
                ->OrderBy('leave_requests.id', 'desc')
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
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
        return view('leave_requests.index');
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
            'leave_type' => 'required',
            'start_date' => 'required',
            'duration' => 'required'
        ])->validate();

        $success = leaveRequest::create([
            'leave_type_id' => $request->leave_type,
            'start_date' => $request->start_date,
            'duration' => $request->duration,
            '_who_added' => Auth::User()->id
        ]);
        return FunctionsHelper::messageResponse("Your leave request has been sent successfully", $success);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\leaveRequest $leaveRequest
     * @return \Illuminate\Http\Response
     */
    public function show(leaveRequest $leaveRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return void
     */
    public function edit($id)
    {
        $leaveRequest = DB::table('leave_requests')
            ->leftJoin('leave_types', 'leave_types.id', 'leave_requests.leave_type_id')
            ->where('leave_requests.id', '=', $id)
            ->select(['leave_requests.*', 'leave_types.name'])
            ->OrderBy('leave_requests.id', 'desc')
            ->first();
        return response()->json($leaveRequest);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'leave_type' => 'required',
            'start_date' => 'required',
            'duration' => 'required'
        ])->validate();

        $success = leaveRequest::where('id', $id)->update([
            'leave_type_id' => $request->leave_type,
            'start_date' => $request->start_date,
            'duration' => $request->duration,
            '_who_added' => Auth::User()->id
        ]);
        return FunctionsHelper::messageResponse("Your leave request has been updated successfully", $success);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return void
     */
    public function destroy($id)
    {
        $success = leaveRequest::where('id', $id)->delete();
        return FunctionsHelper::messageResponse("Leave Request has been deleted successfully", $success);
    }
}
