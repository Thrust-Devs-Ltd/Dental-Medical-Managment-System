<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Http\Helper\FunctionsHelper;
use App\InsuranceCompany;
use App\Jobs\SendAppointmentSms;
use App\OnlineBooking;
use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class OnlineBookingController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function frontend()
    {
        $data['insurance_providers'] = InsuranceCompany::all();
        return view('frontend.index')->with($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return void
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($_GET['search'])) {
                $data = DB::table('online_bookings')
                    ->whereNull('online_bookings.deleted_at')
                    ->where('online_bookings.full_name', 'like', '%' . $request->get('search') . '%')
                    ->orWhere('online_bookings.email', 'like', '%' . $request->get('search') . '%')
                    ->orWhere('online_bookings.phone_no', 'like', '%' . $request->get('search') . '%')
                    ->select('online_bookings.*', DB::raw('DATE_FORMAT(online_bookings.start_date, "%d-%b-%Y") as start_date'),DB::raw('DATE_FORMAT(online_bookings.created_at, "%d-%b-%Y") as booking_date'))
                    ->orderBy('sort_by', 'desc')
                    ->get();
            } else if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                FunctionsHelper::storeDateFilter($request);
                $data = DB::table('online_bookings')
                    ->whereNull('online_bookings.deleted_at')
                    ->whereBetween(DB::raw('DATE_FORMAT(sort_by, \'%Y-%m-%d\')'), array($request->start_date,
                        $request->end_date))
                    ->select('online_bookings.*', DB::raw('DATE_FORMAT(online_bookings.start_date, "%d-%b-%Y") as start_date'),DB::raw('DATE_FORMAT(online_bookings.created_at, "%d-%b-%Y") as booking_date'))
                    ->orderBy('sort_by', 'desc')
                    ->get();
            } else {
                $data = DB::table('online_bookings')
                    ->whereNull('appointments.deleted_at')
                    ->select('online_bookings.*', DB::raw('DATE_FORMAT(online_bookings.start_date, "%d-%b-%Y") as start_date'),DB::raw('DATE_FORMAT(online_bookings.created_at, "%d-%b-%Y") as booking_date'))
                    ->orderBy('sort_by', 'desc')
                    ->get();
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == "Rejected") {
                        $btn = '<span class="label label-sm label-danger"> ' . $row->status . ' </span>';
                    } else if ($row->status == "Waiting") {
                        $btn = '<span class="label label-sm label-info"> ' . $row->status . ' </span>';
                    } else {
                        $btn = '<span class="label label-sm label-success"> ' . $row->status . ' </span>';
                    }
                    return $btn;
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
                                <a href="#" onclick="ViewMessage(' . $row->id . ')"> View Message </a>
                            </li>
                        </ul>
                    </div>
                    ';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('online_bookings.index');
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
        $honeypot = FALSE;
        if (!empty($request->contact_me_by_fax_only) && (bool)$request->contact_me_by_fax_only == TRUE) {
            $honeypot = TRUE;
            return $this->formResponse();
        }
        Validator::make($request->all(), [
            'full_name' => 'required',
            'phone_number' => 'required',
            'appointment_date' => 'required',
            'appointment_time' => 'required',
            'visit_history' => 'required',
            'visit_reason' => 'required'
        ])->validate();

        $success = OnlineBooking::create([
            'full_name' => $request->full_name,
            'phone_no' => $request->phone_number,
            'email' => $request->email,
            'start_date' => FunctionsHelper::convert_date($request->appointment_date),
            'end_date' => FunctionsHelper::convert_date($request->appointment_date),
            'start_time' => $request->appointment_time,
            'message' => $request->visit_reason,
            'insurance_company_id' => $request->insurance_provider,
            'visit_history' => $request->visit_history
        ]);
        if ($success) {
            return $this->formResponse();
        }
    }

    protected function formResponse()
    {
        return response()->json(['message' => 'Thank you, Your appointment request has been submitted successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $onlineBooking = DB::table('online_bookings')
            ->leftJoin('insurance_companies', 'insurance_companies.id', 'online_bookings.insurance_company_id')
            ->whereNull('online_bookings.deleted_at')
            ->where('online_bookings.id', '=', $id)
            ->select('online_bookings.*', 'insurance_companies.name')->first();
        return response()->json($onlineBooking);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\OnlineBooking $onlineBooking
     * @return \Illuminate\Http\Response
     */
    public function edit(OnlineBooking $onlineBooking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'full_name' => 'required',
            'phone_number' => 'required',
            'appointment_date' => 'required',
            'appointment_time' => 'required',
            'doctor_id' => 'required'
        ])->validate();

        //accept the booking
        $success = OnlineBooking::where('id', $id)->update(['status' => 'Accepted']);
        if ($success) {
            //check if the patient exists in the system
            $patient_id = $this->PatientExists($request);
            if ($patient_id != null) {
                //now generate the appointment for the patient
                $status = $this->generateAppointment($request, $patient_id);
                if ($status) {
                    //now generate the message to send to the patient
                    $message = 'Hello, ' . $request->full_name . " Your appointment at ".env('CompanyName',null)." has been scheduled for " . $request->appointment_date . " at " . $request->appointment_time;
                    //sms/email to the patient
                    if ($request->phone_number != null) {
                        $sendJob = new SendAppointmentSms($request->phone_number, $message,"Appointment");
                        $this->dispatch($sendJob);
                    }
                    return FunctionsHelper::messageResponse("Appointment booking has been approved successfully",
                        $status);
                }
            }
        }
    }

    private function generateAppointment(Request $request, $patient_id)
    {
        $time_24_hours = date("H:i:s", strtotime($request->appointment_time));

        $success = Appointment::create([
            'appointment_no' => Appointment::AppointmentNo(),
            'patient_id' => $patient_id,
            'doctor_id' => $request->doctor_id,
            'start_date' => $request->appointment_date,
            'end_date' => $request->appointment_date,
            'start_time' => $request->appointment_time,
            'branch_id' => Auth::User()->branch_id,
            'sort_by' => $request->appointment_date . " " . $time_24_hours,
            '_who_added' => Auth::User()->id
        ]);
        return $success;
    }

    private function PatientExists(Request $request)
    {
        $patient = Patient::where('phone_no', $request->phone_number)->Orwhere('email', $request->email)->first();
        if ($patient != null) {
            return $patient->id;
        } else {
            $has_insurance = "No";
            if ($request->insurance_company_id != null) {
                $has_insurance = "Yes";
            }
            //create new patient
            $new_patient = Patient::create([
                'patient_no' => Patient::PatientNumber(),
                'surname' => $request->full_name,
                //'othername' => $request->othername,// To...do
                'email' => $request->email,
                'phone_no' => $request->phone_number,
                'has_insurance' => $has_insurance,
                'insurance_company_id' => $request->insurance_company_id,
                '_who_added' => Auth::User()->id
            ]);
            return $new_patient->id; //return the newly generated patient
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return void
     */
    public function destroy($id) //use delete to reject the booking
    {
        $reject = OnlineBooking::where('id', $id)->update(['status' => 'Rejected']);
        return FunctionsHelper::messageResponse("Booking has been rejected successfully", $reject);
    }
}
