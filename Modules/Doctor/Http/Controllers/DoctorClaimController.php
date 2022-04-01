<?php

namespace Modules\Doctor\Http\Controllers;

use App\ClaimRate;
use App\DoctorClaim;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class DoctorClaimController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::table('doctor_claims')
                ->join('appointments', 'appointments.id', 'doctor_claims.appointment_id')
                ->join('patients', 'patients.id', 'appointments.patient_id')
                ->whereNull('doctor_claims.deleted_at')
                ->where('doctor_claims._who_added', Auth::User()->id)
                ->select('doctor_claims.*', 'patients.surname', 'patients.othername')
                ->orderBy('doctor_claims.updated_at', 'desc')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('patient', function ($row) {
                    return $row->surname . " " . $row->othername;
                })
                ->addColumn('insurance_amount', function ($row) {
                    return $this->insurance_claim($row->insurance_amount);
                })
                ->addColumn('cash_amount', function ($row) {
                    return $this->cash_claim($row->cash_amount);
                })
                ->addColumn('total_claim_amount', function ($row) {
                    //calculate claims amount
                    $claim_rate = ClaimRate::where(['doctor_id' => Auth::User()->id, 'status' => 'active'])->first();
                    //insurance claim
                    $insurance = $this->insurance_claim($row->insurance_amount);
                    //cash claim
                    $cash = $this->cash_claim($row->cash_amount);
                    // over all total amount
                    $total_amount = $insurance + $cash;
                    return number_format($total_amount);
                })
                ->addColumn('action', function ($row) {
                    $action_btn='';
                    if ($row->status == "Pending") {
                        $action_btn = '
                       <li>
                                <a href="#" onclick="editRecord(' . $row->id . ')"> Edit </a>
                            </li>
                             <li>
                                <a  href="#" onclick="deleteRecord(' . $row->id . ')"  > Delete </a>
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
                             ' . $action_btn . '
                        </ul>
                    </div>
                    ';
                    return $btn;
                })
                ->rawColumns(['amount', 'action'])
                ->make(true);
        }
        return view('doctor::claims.index');
    }

    private function insurance_claim($insurance_amount)
    {
        $claim_rate = ClaimRate::where(['doctor_id' => Auth::User()->id, 'status' => 'active'])->first();
        return $claim_rate->insurance_rate / 100 * $insurance_amount;

    }


    private function cash_claim($cash_amount)
    {
        $claim_rate = ClaimRate::where(['doctor_id' => Auth::User()->id, 'status' => 'active'])->first();
        return $claim_rate->cash_rate / 100 * $cash_amount;
    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('doctor::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'appointment_id' => 'required',
            'amount' => 'required'
        ])->validate();

        //get the doctor claim rate
        $claim_rate = ClaimRate::where(['doctor_id' => Auth::User()->id, 'status' => 'active'])->first();
        if ($claim_rate == null) {
            return response()->json(['message' => 'Sorry you dont have claim rate in the system, please contact the system admin', 'status' => false]);
        }
        $status = DoctorClaim::create([
            'claim_amount' => $request->amount,
            'appointment_id' => $request->appointment_id,
            'claim_rate_id' => $claim_rate->id,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Claim has been submitted successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again', 'status' => false]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('doctor::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $claim = DoctorClaim::where('id', $id)->first();
        return response()->json($claim);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'amount' => 'required'
        ])->validate();

        $status = DoctorClaim::where('id', $id)->update([
            'claim_amount' => $request->amount,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Claim has been updated successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again', 'status' => false]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $status = DoctorClaim::where('id', $id)->delete();
        if ($status) {
            return response()->json(['message' => 'Claim has been deleted successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again', 'status' => false]);

    }
}
