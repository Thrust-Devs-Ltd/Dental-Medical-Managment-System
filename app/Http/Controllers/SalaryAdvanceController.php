<?php

namespace App\Http\Controllers;

use App\SalaryAdvance;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SalaryAdvanceController extends Controller
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

            $data = DB::table('salary_advances')
                ->leftJoin('users', 'users.id', 'salary_advances.employee_id')
                ->leftJoin('users as LoggedInUser', 'LoggedInUser.id', 'salary_advances._who_added')
                ->whereNull('salary_advances.deleted_at')
                ->select('salary_advances.*', 'users.surname', 'users.othername', 'LoggedInUser.othername as LoggedInUser')
                ->orderBy('salary_advances.id', 'desc')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('employee', function ($row) {
                    return $row->surname . " " . $row->othername;
                })
                ->addColumn('addedBy', function ($row) {
                    return $row->LoggedInUser;
                })
                ->addColumn('amount', function ($row) {
                    return '<span class="text-primary">' . number_format($row->advance_amount) . '</span>';
                })
                ->addColumn('editBtn', function ($row) {
                    $btn = '<a href="#" onclick="editRecord(' . $row->id . ')" class="btn btn-primary">Edit</a>';
                    return $btn;
                })
                ->addColumn('deleteBtn', function ($row) {

                    $btn = '<a href="#" onclick="deleteRecord(' . $row->id . ')" class="btn btn-danger">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['amount','editBtn', 'deleteBtn'])
                ->make(true);
        }
        return view('salary_advances.index');
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
            'payment_classification' => 'required',
            'employee' => 'required',
            'advance_month' => 'required',
            'amount' => 'required',
            'payment_method' => 'required',
            'payment_date' => 'required'
        ])->validate();
        $status = SalaryAdvance::create([
            'payment_classification' => $request->payment_classification,
            'employee_id' => $request->employee,
            'advance_month' => $request->advance_month,
            'advance_amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Employee Payment has been captured successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops an error has occurred, please try again', 'status' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\SalaryAdvance $salaryAdvance
     * @return Response
     */
    public function show(SalaryAdvance $salaryAdvance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\SalaryAdvance $salaryAdvance
     * @return Response
     */
    public function edit($id)
    {
        $advance = DB::table('salary_advances')
            ->join('users', 'users.id', 'salary_advances.employee_id')
            ->where('salary_advances.id', $id)
            ->select('salary_advances.*', 'users.surname', 'users.othername')
            ->first();
        return response()->json($advance);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param \App\SalaryAdvance $salaryAdvance
     * @return Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'payment_classification' => 'required',
            'employee' => 'required',
            'advance_month' => 'required',
            'amount' => 'required',
            'payment_method' => 'required',
            'payment_date' => 'required'
        ])->validate();
        $status = SalaryAdvance::where('id', $id)->update([
            'payment_classification' => $request->payment_classification,
            'employee_id' => $request->employee,
            'advance_month' => $request->advance_month,
            'advance_amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Employee Payment has been updated successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops an error has occurred, please try again', 'status' => false]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\SalaryAdvance $salaryAdvance
     * @return Response
     */
    public function destroy($id)
    {
        $status = SalaryAdvance::where('id', $id)->delete();
        if ($status) {
            return response()->json(['message' => 'Employee Advance has been deleted successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops an error has occurred, please try again', 'status' => false]);

    }
}
