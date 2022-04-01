<?php

namespace App\Http\Controllers;

use App\SelfAccountDeposit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SelfAccountDepositController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param $self_account_id
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request, $self_account_id)
    {
        if ($request->ajax()) {

            $data = DB::table('self_account_deposits')
                ->leftJoin('users', 'users.id', 'self_account_deposits._who_added')
                ->whereNull('self_account_deposits.deleted_at')
                ->where('self_account_deposits.self_account_id', $self_account_id)
                ->select('self_account_deposits.*', 'users.surname as  added_by')
                ->orderBy('self_account_deposits.updated_at', 'DESC')
                ->get();


            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {

                })
                ->addColumn('amount', function ($row) {
                    return number_format($row->amount);
                })
                ->addColumn('editBtn', function ($row) {
                    $btn = '<a href="#" onclick="editDeposit(' . $row->id . ')" class="btn btn-primary">Edit</a>';
                    return $btn;
                })
                ->addColumn('deleteBtn', function ($row) {

                    $btn = '<a href="#" onclick="deleteDeposit(' . $row->id . ')" class="btn btn-danger">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['editBtn', 'deleteBtn'])
                ->make(true);
        }
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
            'payment_date' => 'required',
            'amount' => 'required',
            'payment_method' => 'required'
        ])->validate();
        $status = SelfAccountDeposit::create([
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            'self_account_id' => $request->self_account_id,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Money has been deposited successfully on the self account',
                'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurried please try again later',
            'status' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\SelfAccountDeposit $selfAccountDeposit
     * @return Response
     */
    public function show(SelfAccountDeposit $selfAccountDeposit)
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
        $record = SelfAccountDeposit::where('id', $id)->first();
        return response()->json($record);
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
            'payment_date' => 'required',
            'amount' => 'required',
            'payment_method' => 'required'
        ])->validate();
        $status = SelfAccountDeposit::where('id', $id)->update([
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Record has been updated successfully',
                'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurried please try again later',
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
        $status = SelfAccountDeposit::where('id', $id)->delete();
        if ($status) {
            return response()->json(['message' => 'Record has been deleted successfully',
                'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurried please try again later',
            'status' => false]);
    }
}
