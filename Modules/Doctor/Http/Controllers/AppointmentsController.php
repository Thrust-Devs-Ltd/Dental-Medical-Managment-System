<?php

namespace Modules\Doctor\Http\Controllers;

use App\Appointment;
use App\DoctorClaim;
use App\Http\Helper\FunctionsHelper;
use App\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;
use Yajra\DataTables\DataTables;

class AppointmentsController extends Controller
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

            if (!empty($_GET['search'])) {
                $data = DB::table('appointments')
                    ->join('patients', 'patients.id', 'appointments.patient_id')
                    ->whereNull('appointments.deleted_at')
                    ->where('appointments.doctor_id', Auth::User()->id)
                    ->where('patients.surname', 'like', '%' . $request->get('search') . '%')
                    ->orWhere('patients.othername', 'like', '%' . $request->get('search') . '%')
                    ->select('appointments.*', 'patients.surname', 'patients.othername')
                    ->orderBy('appointments.sort_by', 'desc')
                    ->get();
            } else if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                FunctionsHelper::storeDateFilter($request);
                $data = DB::table('appointments')
                    ->join('patients', 'patients.id', 'appointments.patient_id')
                    ->whereNull('appointments.deleted_at')
                    ->where('appointments.doctor_id', Auth::User()->id)
                    ->whereBetween(DB::raw('DATE_FORMAT(appointments.sort_by, \'%Y-%m-%d\')'), array($request->start_date,
                        $request->end_date))
                    ->select('appointments.*', 'patients.surname', 'patients.othername', DB::raw('DATE_FORMAT(appointments.start_date, "%d-%b-%Y") as start_date'))
                    ->orderBy('appointments.sort_by', 'desc')
                    ->get();

            } else {
                $data = DB::table('appointments')
                    ->join('patients', 'patients.id', 'appointments.patient_id')
                    ->whereNull('appointments.deleted_at')
                    ->where('appointments.doctor_id', Auth::User()->id)
                    ->select('appointments.*', 'patients.surname', 'patients.othername')
                    ->orderBy('appointments.sort_by', 'desc')
                    ->get();
            }


            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('patient', function ($row) {
                    return $row->surname . " " . $row->othername;
                })
                ->addColumn('Medical_History', function ($row) {
                    //medical history goes with the patient ID
                    $btn = '<a href="' . url('medical-history/' . $row->patient_id) . '"  class="btn btn-info">Medical History</a>';
                    return $btn;
                })
                ->addColumn('treatment', function ($row) {
                    //use the appointment ID
                    $btn = '<a href="' . url('medical-treatment/' . $row->id) . '"  class="btn btn-info">Treatment</a>';
                    return $btn;
                })
                ->addColumn('doctor_claim', function ($row) {
                    //check if the appointment already has a claim
                    $claim = DoctorClaim::where('appointment_id', $row->id)->first();
                    $btn = '';
                    if ($claim == "") {
                        $btn = '<a href="#" onclick="CreateClaim(' . $row->id . ')"  class="btn green-meadow">Create Claim</a>';
                    } else {
                        $btn = '<span class="text-primary">Claim already generated</span>';
                    }
                    return $btn;
                })
                ->
                rawColumns(['Medical_History', 'treatment', 'doctor_claim'])
                ->make(true);
        }
        $incoming = [];
        $appointment_data = DB::table('appointments')
            ->join('patients', 'patients.id', 'appointments.patient_id')
            ->join('users', 'users.id', 'appointments.doctor_id')
            ->whereNull('appointments.deleted_at')
            ->where('appointments.doctor_id', Auth::User()->id)
            ->select('appointments.*', 'patients.surname', 'patients.othername', 'users.surname as 
                    d_surname', 'users.othername as d_othername')
            ->orderBy('appointments.sort_by', 'desc')
            ->get();
//        if ($appointment_data->count()) {
        foreach ($appointment_data as $key => $value) {
            $incoming[] = Calendar::event(
                $value->surname . " " . $value->othername, //event title
                false,
                date_format(date_create($value->sort_by), "Y-m-d H:i:s"),
                date_format(date_create($value->sort_by), "Y-m-d H:i:s"),
                null,
                // Add color
                [
//                        'color' => '#000000',
                    'textColor' => '#ffffff',
                ]
            );
        }
//        }
        $calendar = Calendar::addEvents($incoming);
        return view('doctor::appointments.index', compact('calendar'));
    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public
    function create()
    {
        return view('doctor::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public
    function store(Request $request)
    {

        Validator::make($request->all(), [
            'patient_id' => 'required',
        ])->validate();
        $status = Appointment::create([
            'appointment_no' => Appointment::AppointmentNo(),
            'patient_id' => $request->patient_id,
            'doctor_id' => Auth::User()->id,
            'notes' => $request->notes,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Appointment has been created successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again later', 'status' => false]);

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public
    function show($id)
    {
        return view('doctor::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public
    function edit($id)
    {
        $appointment = DB::table("appointments")
            ->join('users', 'users.id', 'appointments.doctor_id')
            ->join('patients', 'patients.id', 'appointments.patient_id')
            ->where('appointments.id', $id)
            ->whereNull('appointments.deleted_at')
            ->select('appointments.*', 'users.surname as d_surname', 'users.othername as d_othername', 'patients.surname', 'patients.othername')
            ->first();
        return response()->json($appointment);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public
    function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'patient_id' => 'required'
        ])->validate();
        $status = Appointment::where('id', $id)->update([
            'patient_id' => $request->patient_id,
            'notes' => $request->notes,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Appointment has been updated successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again later', 'status' => false]);

    }

    public function updateAppointmentStatus(Request $request)
    {
        Validator::make($request->all(), [
            'appointment_id' => 'required',
            'appointment_status' => 'required'
        ])->validate();
        //now update the appointment as done
        $success = Appointment::where('id', $request->appointment_id)->update(['status' => $request->appointment_status]);
        return FunctionsHelper::messageResponse("Appointment has been save as " . $request->appointment_status, $success);
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public
    function destroy($id)
    {
        $status = Appointment::where('id', $id)->delete();
        if ($status) {
            return response()->json(['message' => 'Appointment has been deleted successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again later', 'status' => false]);


    }
}
