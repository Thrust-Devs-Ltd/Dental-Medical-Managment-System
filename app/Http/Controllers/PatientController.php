<?php

namespace App\Http\Controllers;

use App\ChronicDisease;
use App\Http\Helper\FunctionsHelper;
use App\InsuranceCompany;
use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use ExcelReport;

class PatientController extends Controller
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
            if (!empty($_GET['search'])) {
                $data = DB::table('patients')
                    ->leftJoin('insurance_companies', 'insurance_companies.id', 'patients.insurance_company_id')
                    ->leftJoin('users', 'users.id', 'patients._who_added')
                    ->whereNull('patients.deleted_at')
                    ->where('patients.surname', 'like', '%' . $request->get('search') . '%')
                    ->orWhere('patients.othername', 'like', '%' . $request->get('search') . '%')
                    ->select('patients.*', 'patients.surname', 'patients.othername',
                        'insurance_companies.name', 'users.surname as addedBy')
                    ->OrderBy('patients.id', 'desc')
                    ->get();
            } else if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                //store filtered dates
                FunctionsHelper::storeDateFilter($request);

                $data = DB::table('patients')
                    ->leftJoin('insurance_companies', 'insurance_companies.id', 'patients.insurance_company_id')
                    ->leftJoin('users', 'users.id', 'patients._who_added')
                    ->whereNull('patients.deleted_at')
                    ->whereBetween(DB::raw('DATE(patients.created_at)'), array($request->start_date, $request->end_date))
                    ->select('patients.*', 'patients.surname', 'patients.othername',
                        'insurance_companies.name', 'users.surname as addedBy')
                    ->OrderBy('patients.id', 'desc')
                    ->get();
            } else {
                $data = DB::table('patients')
                    ->leftJoin('insurance_companies', 'insurance_companies.id', 'patients.insurance_company_id')
                    ->leftJoin('users', 'users.id', 'patients._who_added')
                    ->whereNull('patients.deleted_at')
                    ->select('patients.*', 'patients.surname', 'patients.othername',
                        'insurance_companies.name', 'users.surname as addedBy')
                    ->OrderBy('patients.id', 'desc')
                    ->get();
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('patient_no', function ($row) {
                    return '<a href="#"> ' . $row->patient_no . '</a>';
                })
                ->addColumn('medical_insurance', function ($row) {

                    if ($row->has_insurance == "Yes" && $row->insurance_company_id != null) {
                        $btn = $row->name;
                    } else {
                        $btn = $row->has_insurance;
                    }
                    return $btn;
                })
                ->addColumn('Medical_History', function ($row) {
                    $btn = '<a href="' . url('/medical-history/' . $row->id) . '" class="btn btn-success">Medical History</a>';
                    return $btn;
                })
                ->addColumn('addedBy', function ($row) {
                    return $row->addedBy;
                })
                ->addColumn('status', function ($row) {
                    if ($row->deleted_at != null) {
                        return '<span class="text-danger">Inactive</span>';
                    } else {
                        return '<span class="text-primary">Active</span>';
                    }
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
                                <a href="#" onclick="editRecord(' . $row->id . ')">Patient Profile</a>
                            </li>
                             <li>
                               <a href="#" onclick="getPatientMedicalHistory(' . $row->id . ')" >Patient History</a>
                            </li> 
                             <li>
                               <a href="#" onclick="deleteRecord(' . $row->id . ')" >Delete Patient</a>
                            </li> 
                        </ul>
                    </div>
                    ';
                    return $btn;
                })
                ->rawColumns(['patient_no', 'medical_insurance', 'Medical_History', 'status', 'action'])
                ->make(true);
        }
        return view('patients.index');
    }


    public function exportPatients(Request $request)
    {
        if ($request->session()->get('from') != '' && $request->session()->get('to') != '') {
            $queryBuilder = DB::table('patients')
                ->leftJoin('insurance_companies', 'insurance_companies.id', 'patients.insurance_company_id')
                ->whereBetween(DB::raw('DATE(patients.created_at)'), array($request->session()->get('from'),
                    $request->session()->get('to')))
                ->select('patients.*', 'insurance_companies.name as insurance_company')
                ->orderBy('created_at', 'ASC');
        } else {
            $queryBuilder = DB::table('patients')
                ->leftJoin('insurance_companies', 'insurance_companies.id', 'patients.insurance_company_id')
                ->select('patients.*', 'insurance_companies.name as insurance_company')
                ->orderBy('created_at', 'ASC');
        }

        $columns = ['surname', 'othername', 'gender', 'dob', 'phone_no', 'alternative_no', 'address', 'profession', 'next_of_kin', 'has_insurance', 'insurance_company'];

        return ExcelReport::of(null,
            [
                'Patients Registered Report ' => "From:   " . $request->session()->get('from') . "    To:    " .
                    $request->session()
                        ->get('to'),
            ], $queryBuilder, $columns)
            ->simple()
            ->download('patients' . date('Y-m-d H:m:s'));
    }

    public function filterPatients(Request $request)
    {
        $data = [];
        $name = $request->q;

        if ($name) {
            $search = $name;
            $data = Patient::where('surname', 'LIKE', "%$search%")
                ->Orwhere('othername', 'LIKE', "%$search%")
                ->Orwhere('phone_no', 'LIKE', "%$search%")
                ->Orwhere('email', 'LIKE', "%$search%")
                ->get();

            $formatted_tags = [];
            foreach ($data as $tag) {
                $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->surname . " " . $tag->othername];
            }
            return \Response::json($formatted_tags);
        }
    }

    public function patientMedicalHistory($patientId)
    {
        $medicalHistory = DB::table('treatments')
            ->leftJoin('appointments', 'appointments.id', 'treatments.appointment_id')
            ->leftJoin('users', 'users.id', 'treatments._who_added')
            ->whereNull('treatments.deleted_at')
            ->where('appointments.patient_id', $patientId)
            ->orderBy('treatments.updated_at', 'desc')
            ->select('treatments.id', 'clinical_notes', 'treatment', 'treatments.created_at')
            ->get();
        $patient = Patient::findOrfail($patientId);
        return Response()->json(['patientInfor' => $patient, 'treatmentHistory' => $medicalHistory]);
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
            'surname' => 'required',
            'othername' => 'required',
            'gender' => 'required',
            'has_insurance' => 'required'
        ])->validate();
//        return  Patient::PatientNumber();

        $status = Patient::create([
            'patient_no' => Patient::PatientNumber(),
            'surname' => $request->surname,
            'othername' => $request->othername,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'alternative_no' => $request->alternative_no,
            'address' => $request->address,
            'nin' => $request->nin,
            'profession' => $request->profession,
            'next_of_kin' => $request->next_of_kin,
            'next_of_kin_no' => $request->next_of_kin_no,
            'next_of_kin_address' => $request->next_of_kin_address,
            'has_insurance' => $request->has_insurance,
            'insurance_company_id' => $request->insurance_company_id,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Patient has been added successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again', 'status' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Patient $patient
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Patient::all();
        foreach ($data as $row) {
            if ($row->phone_no != null) {
                $output = preg_replace("/^0/", "+256", $row->phone_no);
                // update phone numbers
                Patient::where('id', $row->id)->update(['phone_no' => $output]);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Patient $patient
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = '';
        $patient = Patient::where('id', $id)->first();
        if ($patient->insurance_company_id != null) {
            //now get the insurance company
            $row = InsuranceCompany::where('id', $patient->insurance_company_id)->first();
            $company = $row->name;
        } else {
            $company = '';
        }
        return response()->json(['patient' => $patient, 'company' => $company]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Patient $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'surname' => 'required',
            'othername' => 'required',
            'gender' => 'required',
            'has_insurance' => 'required'
        ])->validate();

        $status = Patient::where('id', $id)->update([
            'surname' => $request->surname,
            'othername' => $request->othername,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'alternative_no' => $request->alternative_no,
            'address' => $request->address,
            'nin' => $request->nin,
            'profession' => $request->profession,
            'next_of_kin' => $request->next_of_kin,
            'next_of_kin_no' => $request->next_of_kin_no,
            'next_of_kin_address' => $request->next_of_kin_address,
            'has_insurance' => $request->has_insurance,
            'insurance_company_id' => $request->insurance_company_id,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Patient has been updated successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again', 'status' => false]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = Patient::where('id', $id)->delete();
        if ($status) {
            return response()->json(['message' => 'Patient has been deleted successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again', 'status' => false]);

    }


}
