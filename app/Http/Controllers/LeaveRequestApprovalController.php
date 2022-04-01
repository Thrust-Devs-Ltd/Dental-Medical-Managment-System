<?php

namespace App\Http\Controllers;

use App\Http\Helper\FunctionsHelper;
use App\leaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class LeaveRequestApprovalController extends Controller
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
                ->leftJoin('users', 'users.id', 'leave_requests._who_added')
                ->whereNull('leave_requests.deleted_at')
                ->select(['leave_requests.*', 'leave_types.name', 'users.surname', 'users.othername'])
                ->OrderBy('leave_requests.id', 'desc')
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('addedBy', function ($row) {
                    return $row->surname . " " . $row->othername;
                })
                ->addColumn('action', function ($row) {
                    $btn = '
                      <div class="btn-group">
                        <button class="btn blue dropdown-toggle" type="button" data-toggle="dropdown"
                                aria-expanded="false"> Action
                            <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="#" onclick="approveRequest(' . $row->id . ')"> Approve Leave </a>
                            </li>
                             <li>
                                <a href="#" onclick="rejectRequest(' . $row->id . ')" > Reject Leave </a>
                            </li>
                        </ul>
                    </div>
                    ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('leave_requests_approval.index');
    }


    public function approveRequest($id)
    {
        $success = LeaveRequest::where('id', $id)->update([
            'action_date' => date('yyy-mm-dd'),
            'status' => 'Approved',
            '_approved_by' => Auth::User()->id
        ]);
        return FunctionsHelper::messageResponse("You have successfully approved this leave request.", $success);
    }

    public function rejectRequest($id)
    {
        $success = LeaveRequest::where('id', $id)->update([
            'action_date' => date('yyy-mm-dd'),
            'status' => 'Rejected',
            '_approved_by' => Auth::User()->id
        ]);
        return FunctionsHelper::messageResponse("You have successfully rejected this leave request", $success);
    }

}
