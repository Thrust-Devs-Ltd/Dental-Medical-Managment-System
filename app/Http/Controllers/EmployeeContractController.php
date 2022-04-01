<?php

namespace App\Http\Controllers;

use App\EmployeeContract;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class EmployeeContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::table('employee_contracts')
                ->leftJoin('users', 'users.id', 'employee_contracts.employee_id')
                ->leftJoin('users as loggedInUser', 'loggedInUser.id', 'employee_contracts._who_added')
                ->whereNull('employee_contracts.deleted_at')
                ->select('employee_contracts.*', 'users.surname', 'users.othername', 'loggedInUser.othername as loggedInName')
                ->orderBy('employee_contracts.id', 'desc')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('addedBy', function ($row) {
                    return $row->loggedInName;
                })
                ->addColumn('employee', function ($row) {
                    return $row->surname . " " . $row->othername;
                })
                ->addColumn('contract_validity', function ($row) {
                    return $row->contract_length . " " . $row->contract_period;
                })
                ->addColumn('contract_end_date', function ($row) {
                    if ($row->contract_period == "Months") {
                        $days = 30; //month will have 30 days
                        return $this->calculateContractEndDate($days, $row->start_date, $row->contract_length);
                    } else {
                        $days = 365; //days in a year
                        return $this->calculateContractEndDate($days, $row->start_date, $row->contract_length);
                    }
                })
                ->addColumn('amount', function ($row) {
                    if ($row->payroll_type == "Salary") {
                        return '<span class="text-primary">' . number_format($row->gross_salary) . '<br></span>Gross Salary';
                    } else if ($row->payroll_type == "Commission") {
                        return '<span class="text-primary">' . number_format($row->commission_percentage) . '%<br></span>Commission';
                    }
//                    return '<span class="text-primary">' . number_format("100000") . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if ($row->status == 'Active') {
                        $btn = '
                      <div class="btn-group">
                        <button class="btn blue dropdown-toggle" type="button" data-toggle="dropdown"
                                aria-expanded="false"> Action
                            <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="#" onclick="editRecord(' . $row->id . ')"> Edit </a>
                            </li>
                              <li>
                                <a href="#" onclick="deleteRecord(' . $row->id . ')"> Delete </a>
                            </li>
                        </ul>
                    </div>
                    ';
                    }
                    return $btn;
                })
                ->rawColumns(['amount', 'action'])
                ->make(true);
        }
        return view('employee_contracts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'employee' => 'required',
            'contract_type' => 'required',
            'start_date' => 'required',
            'contract_length' => 'required',
            'contract_period' => 'required',
            'payroll_type' => 'required'

        ])->validate();
        //check if the employee already has a contract
        $this->hasContract($request->employee);
        //now create new contract
        $status = EmployeeContract::create([
            'employee_id' => $request->employee,
            'contract_type' => $request->contract_type,
            'start_date' => $request->start_date,
            'contract_length' => $request->contract_length,
            'contract_period' => $request->contract_period,
            'payroll_type' => $request->payroll_type,
            'gross_salary' => $request->gross_salary,
            'commission_percentage' => $request->commission_percentage,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Employee contract  has been captured successfully',
                'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred,please try again later',
            'status' => false]);
    }

    private function hasContract($employee_id)
    {
        $contract = EmployeeContract::where('employee_id', $employee_id)->count();
        if ($contract > 0) {
            //deactivate the old contract
            EmployeeContract::where('employee_id', $employee_id)->update(['status' => 'Expired']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\EmployeeContract $employeeContract
     * @return Response
     */
    public function show(EmployeeContract $employeeContract)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\EmployeeContract $employeeContract
     * @return Response
     */
    public function edit($id)
    {
        $contract = DB::table('employee_contracts')
            ->join('users', 'users.id', 'employee_contracts.employee_id')
            ->where('employee_contracts.id', $id)
            ->select('employee_contracts.*', 'users.surname', 'users.othername')
            ->first();
        return response()->json($contract);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'employee' => 'required',
            'contract_type' => 'required',
            'start_date' => 'required',
            'contract_length' => 'required',
            'contract_period' => 'required',
            'payroll_type' => 'required'

        ])->validate();

        $status = EmployeeContract::where('id', $id)->update([
            'employee_id' => $request->employee,
            'contract_type' => $request->contract_type,
            'start_date' => $request->start_date,
            'contract_length' => $request->contract_length,
            'contract_period' => $request->contract_period,
            'payroll_type' => $request->payroll_type,
            'gross_salary' => $request->gross_salary,
            'commission_percentage' => $request->commission_percentage,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Employee contract  has been updated successfully',
                'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred,please try again later',
            'status' => false]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function destroy($id)
    {
        $status = EmployeeContract::where('id', $id)->delete();
        if ($status) {
            return response()->json(['message' => 'Employee contract  has been deleted successfully',
                'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred,please try again later',
            'status' => false]);
    }

    private function calculateContractEndDate($days, $start_date, $contract_length)
    {
        $total_days = $days * $contract_length;
        $future_date = date('Y-m-d', strtotime($start_date . ' + ' . $total_days . ' days'));
        return $future_date;
    }


}
