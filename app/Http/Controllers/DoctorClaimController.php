<?php

namespace App\Http\Controllers;

use App\ClaimRate;
use App\DoctorClaim;
use App\DoctorClaimPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class DoctorClaimController extends Controller
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

            $data = DB::table('doctor_claims')
                ->join('appointments', 'appointments.id', 'doctor_claims.appointment_id')
                ->join('patients', 'patients.id', 'appointments.patient_id')
                ->join('users', 'users.id', 'doctor_claims._who_added')
                ->whereNull('doctor_claims.deleted_at')
                ->select('doctor_claims.*', 'patients.surname', 'patients.othername', 'users.othername as doctor')
                ->orderBy('doctor_claims.updated_at', 'desc')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('created_at', function ($row) {
                    return date('d/m/Y', strtotime($row->created_at));
                })
                ->addColumn('patient', function ($row) {
                    return $row->surname . " " . $row->othername;
                })
                ->addColumn('claim_amount', function ($row) {
                    return number_format($row->claim_amount);
                })
                ->addColumn('insurance_amount', function ($row) {
                    return number_format($row->insurance_amount);
                })
                ->addColumn('cash_amount', function ($row) {
                    return number_format($row->cash_amount);
                })
                ->addColumn('total_claim_amount', function ($row) {
                    return '<span class="text-primary">' . number_format($this->getTotalClaims($row)) . '</span>';
                })
                ->addColumn('payment_balance', function ($row) {
                    //get total claims amount
                    $claims_amount = $this->getTotalClaims($row);

                    //get the amount paid
                    $paid_amount = DoctorClaimPayment::where('doctor_claim_id', $row->id)->sum('amount');
                    $remaining_balance = $claims_amount - $paid_amount;
                    $action_balance = '';
                    if ($remaining_balance > 0) {
                        $action_balance = "<br>(<a href=\"#\" class=\"text-danger\" onclick=\"record_payment('" . $row->id . "','" . $remaining_balance . "')\">Make Payment</a>)";
                    }
                    return '<span class="text-primary">' . number_format($remaining_balance) . '</span>' . $action_balance;
                })
                ->addColumn('action', function ($row) {
                    $action = '';
                    $claim = '';
                    if ($row->status == "Pending") {
                        $claim = " <a href=\"#\" onclick=\"Approve_Claim('" . $row->id . "','" . $row->claim_amount .
                            "')\">Approve Claim </a>";

                    } else {
                        $action = '
                             <li>
                                <a  href="#" onclick="editRecord(' . $row->id . ')"> Edit Claim </a>
                            </li>
                        ';
                    }

                    $btn = '
                      <div class="btn-group">
                        <button class="btn blue dropdown-toggle" type="button" data-toggle="dropdown"
                                aria-expanded="false"> Action
                            <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                           <li>
                               ' . $claim . '
                            </li>
                              ' . $action . '
                                <li>
                                <a  href="' . url('/claims-payment/' . $row->id) . '"> View Payment </a>
                            </li>
                        </ul>
                    </div>
                    ';
                    return $btn;
                })
                ->rawColumns(['total_claim_amount', 'payment_balance', 'action'])
                ->make(true);
        }
        return view('doctor_claims.index');
    }

    private function getTotalClaims($row)
    {
        //calculate claims amount
        $claim_rate = ClaimRate::where(['doctor_id' => Auth::User()->id, 'status' => 'active'])->first();
        //insurance claim
        $insurance = $this->insurance_claim($row->insurance_amount, $row->_who_added);
        //cash claim
        $cash = $this->cash_claim($row->cash_amount, $row->_who_added);
        // over all total amount
        $total_amount = $insurance + $cash;
        return $total_amount;
    }


    private function insurance_claim($insurance_amount, $doctor_id)
    {
        $claim_rate = ClaimRate::where(['doctor_id' => $doctor_id, 'status' => 'active'])->first();
        return $claim_rate->insurance_rate / 100 * $insurance_amount;

    }


    private function cash_claim($cash_amount, $doctor_id)
    {
        $claim_rate = ClaimRate::where(['doctor_id' => $doctor_id, 'status' => 'active'])->first();
        return $claim_rate->cash_rate / 100 * $cash_amount;
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
            'insurance_amount' => 'required',
            'cash_amount' => 'required'
        ])->validate();
        $status = DoctorClaim::where('id', $request->id)->update([
            'insurance_amount' => $request->insurance_amount,
            'cash_amount' => $request->cash_amount,
            'status' => 'Approved',
            'approved_by' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Doctor claim has been approved successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again later', 'status' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\DoctorClaim $doctorClaim
     * @return \Illuminate\Http\Response
     */
    public function show(DoctorClaim $doctorClaim)
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
        $claim = DoctorClaim::where('id', $id)->first();
        return response()->json($claim);
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
            'insurance_amount' => 'required',
            'cash_amount' => 'required'
        ])->validate();
        $status = DoctorClaim::where('id', $id)->update([
            'insurance_amount' => $request->insurance_amount,
            'cash_amount' => $request->cash_amount,
            'status' => 'Approved',
            'approved_by' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Doctor claim has been updated successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again later', 'status' => false]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\DoctorClaim $doctorClaim
     * @return \Illuminate\Http\Response
     */
    public function destroy(DoctorClaim $doctorClaim)
    {
        //
    }
}
