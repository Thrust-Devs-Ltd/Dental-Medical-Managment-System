<?php

namespace App\Http\Controllers;

use App\Http\Helper\FunctionsHelper;
use App\SalaryDeduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SalaryDeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param $pay_slip_id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request, $pay_slip_id)
    {
        if ($request->ajax()) {

            $data = DB::table('salary_deductions')
                ->join('users', 'users.id', 'salary_deductions._who_added')
                ->whereNull('salary_deductions.deleted_at')
                ->where('salary_deductions.pay_slip_id', '=', $pay_slip_id)
                ->select('salary_deductions.*', 'users.othername as added_by')
                ->OrderBy('salary_deductions.updated_at', 'desc')
                ->get();


            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('amount', function ($row) {
                    return number_format($row->deduction_amount);
                })
                ->addColumn('editBtn', function ($row) {
                    $btn = '<a href="#" onclick="editDeductionRecord(' . $row->id . ')" class="btn btn-primary">Edit</a>';
                    return $btn;
                })
                ->addColumn('deleteBtn', function ($row) {

                    $btn = '<a href="#" onclick="deleteDeductionRecord(' . $row->id . ')" class="btn btn-danger">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['total_amount', 'editBtn', 'deleteBtn'])
                ->make(true);
        }
        return view('payslips.show.index');
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
            'deduction' => 'required',
            'amount' => 'required'
        ])->validate();
        $success = SalaryDeduction::create([
            'deduction' => $request->deduction,
            'deduction_amount' => $request->amount,
            'pay_slip_id' => $request->pay_slip_id,
            '_who_added' => Auth::User()->id
        ]);
        return FunctionsHelper::messageResponse("Employee salary deduction  has been added successfully", $success);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\SalaryDeduction $salaryDeduction
     * @return \Illuminate\Http\Response
     */
    public function show(SalaryDeduction $salaryDeduction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $deduction = SalaryDeduction::where('id', $id)->first();
        return response()->json($deduction);
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
            'deduction' => 'required',
            'amount' => 'required'
        ])->validate();
        $success = SalaryDeduction::where('id', $id)->update([
            'deduction' => $request->deduction,
            'deduction_amount' => $request->amount,
            '_who_added' => Auth::User()->id
        ]);
        return FunctionsHelper::messageResponse("Employee salary deduction  has been updated successfully", $success);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $success = SalaryDeduction::where('id', $id)->delete();
        return FunctionsHelper::messageResponse("Employee salary deduction has been deleted successfully",$success);
    }
}
