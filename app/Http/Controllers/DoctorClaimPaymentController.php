<?php

namespace App\Http\Controllers;

use App\DoctorClaimPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class DoctorClaimPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param $claim_id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request, $claim_id)
    {
        if ($request->ajax()) {

            $data = DB::table('doctor_claim_payments')
                ->whereNull('doctor_claim_payments.deleted_at')
                ->where('doctor_claim_payments.doctor_claim_id', $claim_id)
                ->select('doctor_claim_payments.*')
                ->orderBy('doctor_claim_payments.updated_at', 'desc')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('editBtn', function ($row) {
                    $btn = '<a href="#" onclick="editRecord(' . $row->id . ')" class="btn btn-primary">Edit</a>';
                    return $btn;
                })
                ->addColumn('deleteBtn', function ($row) {

                    $btn = '<a href="#" onclick="deleteRecord(' . $row->id . ')" class="btn btn-danger">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['editBtn', 'deleteBtn'])
                ->make(true);
        }
        $data['claim_id'] = $claim_id;
        $data['doctor'] = DB::table('doctor_claims')
            ->join('users', 'users.id', 'doctor_claims._who_added')
            ->where('doctor_claims.id', $claim_id)
            ->first();
        return view('doctor_claims.payments.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
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
            'amount' => 'required',
            'payment_date' => 'required'
        ])->validate();
        $status = DoctorClaimPayment::create([
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'doctor_claim_id' => $request->claim_id,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Claim payment has been captured successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred,please try again later', 'status' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\DoctorClaimPayment $doctorClaimPayment
     * @return \Illuminate\Http\Response
     */
    public function show(DoctorClaimPayment $doctorClaimPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\DoctorClaimPayment $doctorClaimPayment
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payment = DoctorClaimPayment::where('id', $id)->first();
        return response()->json($payment);
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
            'amount' => 'required',
            'payment_date' => 'required'
        ])->validate();
        $status = DoctorClaimPayment::where('id', $id)->update([
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Claim payment has been updated successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred,please try again later', 'status' => false]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = DoctorClaimPayment::where('id', $id)->delete();
        if ($status) {
            return response()->json(['message' => 'Claim payment has been deleted successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred,please try again later', 'status' => false]);

    }
}
