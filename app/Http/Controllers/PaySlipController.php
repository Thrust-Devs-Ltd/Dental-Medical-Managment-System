<?php

namespace App\Http\Controllers;

use App\EmployeeContract;
use App\Http\Helper\FunctionsHelper;
use App\InvoiceItem;
use App\PaySlip;
use App\SalaryAdvance;
use App\SalaryAllowance;
use App\SalaryDeduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PaySlipController extends Controller
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

            $data = DB::table('pay_slips')
                ->leftJoin('users', 'users.id', 'pay_slips.employee_id')
                ->leftJoin('employee_contracts', 'employee_contracts.id', 'pay_slips.employee_contract_id')
                ->whereNull('pay_slips.deleted_at')
                ->select('pay_slips.*', 'payroll_type', 'gross_salary', 'commission_percentage', 'users.surname', 'users.othername')
                ->orderBy('pay_slips.id', 'desc')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('addedBy', function ($row) {
                    return '';
                })
                ->addColumn('employee', function ($row) {
                    return $row->surname . " " . $row->othername;
                })
                ->addColumn('basic_salary', function ($row) {
                    $wage = $this->CalculateWage($row);
                    return '<span class="text-primary">' . number_format($wage) . '</span>';
                })
                ->addColumn('total_advances', function ($row) {
                    $advances = $this->employeeAdvances($row);
                    return '<span class="text-primary">' . number_format($advances) . '</span>';
                })
                ->addColumn('total_allowances', function ($row) {
                    $allowances = $this->employeeAllowances($row);
                    return '<span class="text-primary">' . number_format($allowances) . '</span>';
                })
                ->addColumn('total_deductions', function ($row) {
                    $deductions = $this->employeeDeductions($row);
                    return '<span class="text-primary">' . number_format($deductions) . '</span>';
                })
                ->addColumn('due_balance', function ($row) {
                    $deductions = $this->employeeDeductions($row);
                    $allowance = $this->employeeAllowances($row);
                    $advances = $this->employeeAdvances($row);
                    $wage = $this->CalculateWage($row);
                    $balance = ($allowance + $wage) - ($deductions + $advances);
                    return '<span class="text-primary">' . number_format($balance) . '</span>';
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
                                <a href="' . url('payslips/' . $row->id) . '"> Preview </a>
                            </li>
                              <li>
                                <a href="#" onclick="deleteRecord(' . $row->id . ')"> Delete </a>
                            </li>
                        </ul>
                    </div>
                    ';
                    return $btn;
                })
                ->rawColumns(['basic_salary', 'total_advances', 'total_allowances', 'total_deductions', 'due_balance', 'action'])
                ->make(true);
        }
        return view('payslips.index');
    }


    private function employeeAdvances($row)
    {
        return SalaryAdvance::where(['advance_month' => $row->payslip_month,
            'employee_id' => $row->employee_id])->sum('advance_amount');
    }

    private function employeeAllowances($row)
    {
        return SalaryAllowance::where('pay_slip_id', $row->id)->sum('allowance_amount');
    }

    private function employeeDeductions($row)
    {
        return SalaryDeduction::where('pay_slip_id', $row->id)->sum('deduction_amount');
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
            'employee' => 'required',
            'payslip_month' => 'required'
        ])->validate();
        //get employee contract
        $employee = EmployeeContract::where(['employee_id' => $request->employee, 'status' => 'Active'])->first();
        if ($employee == null) {
            return response()->json(['message' => 'Employee does not have a contract, please first add the employee contract', 'status' => false]);
        }

        $payslip = PaySlip::create([
            'payslip_month' => $request->payslip_month,
            'employee_id' => $request->employee,
            'employee_contract_id' => $employee->id,
            '_who_added' => Auth::User()->id
        ]);
        if ($payslip) {
            //now add the allowances of the payslip for this month selected
            foreach ($request->addAllowance as $key => $value) {
                //check if amount is not null
                if ($value['allowance_amount'] != null) {
                    SalaryAllowance::create([
                        'allowance' => $value['allowance'],
                        'allowance_amount' => $value['allowance_amount'],
                        'pay_slip_id' => $payslip->id,
                        '_who_added' => Auth::User()->id
                    ]);
                }
            }
            //add the deductions of the pay slip of the month selected
            foreach ($request->addDeduction as $key => $value) {
                //check if amount is not null
                if ($value['deduction_amount'] != null) {
                    SalaryDeduction::create([
                        'deduction' => $value['deduction'],
                        'deduction_amount' => $value['deduction_amount'],
                        'pay_slip_id' => $payslip->id,
                        '_who_added' => Auth::User()->id
                    ]);
                }
            }
            return response()->json(['message' => 'Payslip has been generated successfully', 'status' => true]);
        }

        return response()->json(['message' => 'Oops an error has occurred, please try again later', 'status' => false]);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\PaySlip $paySlip
     * @return \Illuminate\Http\Response
     */
    public function show($pay_slip_id)
    {
        $data['employee'] = DB::table('pay_slips')
            ->join('users', 'users.id', 'pay_slips.employee_id')
            ->where('pay_slips.id', $pay_slip_id)
            ->select('pay_slips.*', 'users.surname', 'users.othername')
            ->first();
        $data['pay_slip_id'] = $pay_slip_id;
        return view('payslips.show.index')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\PaySlip $paySlip
     * @return \Illuminate\Http\Response
     */
    public function edit(PaySlip $paySlip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\PaySlip $paySlip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaySlip $paySlip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return void
     */
    public function destroy($id)
    {
        $success = PaySlip::where('id', $id)->delete();
        return FunctionsHelper::messageResponse('Payslip has been deleted successfully', $success);
    }

    private function CalculateWage($row)
    {
        //check payroll type
        if ($row->payroll_type == "Salary") {
            return $row->gross_salary;
        } else {
            //get how much the doctor has been thru invoices
            return $this->fetchDoctorInvoices($row->employee_id, $row->payslip_month, $row->commission_percentage);
        }
    }

    //helper for calculating the employee  commission amount
    private function fetchDoctorInvoices($doctor_id, $month, $commission_percentage)
    {
        //get all doctor invoices
        $invoices = DB::table('invoices')
            ->leftJoin('appointments', 'appointments.id', 'invoices.appointment_id')
            ->whereNull('invoices.deleted_at')
            ->where('appointments.doctor_id', $doctor_id)
            ->whereBetween(DB::raw('DATE_FORMAT(invoices.updated_at, \'%Y-%m\')'), array($month,
                $month))
            ->select('invoices.id')
            ->get();
        $total_amount = 0;
        foreach ($invoices as $item) {
            // get the invoice items amount sum
            $sum_items_amount = InvoiceItem::where('invoice_id', $item->id)->sum(DB::raw('price*qty'));
            $total_amount = $total_amount + $sum_items_amount;
        }

        //get the commission
        $commission = ($commission_percentage / 100) * $total_amount;
        return $commission;
    }
}
