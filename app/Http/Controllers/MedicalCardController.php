<?php

namespace App\Http\Controllers;

use App\MedicalCard;
use App\MedicalCardItem;
use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class MedicalCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::table('medical_cards')
                ->join('patients', 'patients.id', 'medical_cards.patient_id')
                ->join('users', 'users.id', 'medical_cards._who_added')
                ->whereNull('medical_cards.deleted_at')
                ->select('medical_cards.*', 'patients.surname', 'patients.othername', 'users.othername as added_by')
                ->orderBY('created_at', 'desc')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {

                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['surname'] . " " . $row['othername']), Str::lower($request->get('search'))
                            )) {
                                return true;
                            }
                            return false;
                        });
                    }
                })
                ->addColumn('patient', function ($row) {
                    return $row->surname . " " . $row->othername;
                })
                ->addColumn('added_by', function ($row) {
                    return $row->added_by;
                })
                ->addColumn('view_cards', function ($row) {

                    $btn = '<a href="' . url('medical-cards/' . $row->id) . '" class="btn btn-primary">View Cards</a>';
                    return $btn;
                })
                ->addColumn('deleteBtn', function ($row) {

                    $btn = '<a href="#" onclick="deleteRecord(' . $row->id . ')" class="btn btn-danger">Delete</a>';
                    return $btn;
                })
                ->addColumn('checkbox', '<input type="checkbox" name="student_checkbox[]" class="student_checkbox" value="{{$id}}" />')
                ->rawColumns(['view_cards', 'deleteBtn', 'checkbox'])
                ->make(true);
        }
        return view('medical_cards.index');
    }

    public function IndividualMedicalCards(Request $request, $patient_id)
    {
        if ($request->ajax()) {

            $data = DB::table('medical_cards')
                ->join('patients', 'patients.id', 'medical_cards.patient_id')
                ->whereNull('medical_cards.deleted_at')
                ->where('medical_cards.patient_id', $patient_id)
                ->select('medical_cards.*', 'patients.surname', 'patients.othername')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('patient', function ($row) {
                    return $row->surname . " " . $row->othername;
                })
                ->addColumn('card_link', function ($row) {
                    $btn = '
                    <a class="image-popup-vertical-fit" target="_blank"  href="' . asset('uploads/medical_cards/' .
                            $row->card_link) . '">
	                   <img src="' . asset('uploads/medical_cards/' . $row->card_link) . '" width="75" height="75">
                   </a>
                    ';
                    return $btn;
                })
                ->rawColumns(['card_link'])
                ->make(true);
        }
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
        request()->validate([
            'patient_id' => 'required',
            'card_type' => 'required',
            'uploadFile' => 'required',
        ]);
//        |image|mimes:jpeg,png,jpg,gif,svg|max:2048

        // first insert the cards details
        $status = MedicalCard::create([
            'card_type' => $request->card_type,
            'patient_id' => $request->patient_id,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            foreach ($request->file('uploadFile') as $key => $value) {
                $imageName = time() . $key . '.' . $value->getClientOriginalExtension();
                $value->move('uploads/medical_cards', $imageName);
                //now insert the card details into the medical cards
                MedicalCardItem::create([
                    'medical_card_id' => $status->id,
                    'card_photo' => $imageName,
                    '_who_added' => Auth::User()->id
                ]);
            }
        }
        if ($status) {
            return redirect('/medical-cards/' . $status->id);
        }
        return Response()->json(["message" => 'Oops an error has occurred please try again later', "status" => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\MedicalCard $medicalCard
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['images'] = MedicalCardItem::where('medical_card_id', $id)->get();
        $data['patient'] = DB::table('medical_cards')
            ->join('patients', 'patients.id', 'medical_cards.patient_id')
            ->whereNull('medical_cards.deleted_at')
            ->where('medical_cards.id', $id)
            ->select('patients.*')
            ->first();
        return view('medical_cards.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\MedicalCard $medicalCard
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $card = DB::table('medical_cards')
            ->join('patients', 'patients.id', 'medical_cards.patient_id')
            ->where('medical_cards.id', $id)
            ->select('medical_cards.*', 'patients.surname', 'patients.othername')
            ->first();
        return response()->json($card);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\MedicalCard $medicalCard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MedicalCard $medicalCard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\MedicalCard $medicalCard
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = MedicalCard::where('id', $id)->delete();
        if ($status) {
            return Response()->json(["message" => 'Image Has been deleted successfully', "status" => true
            ]);
        }
        return Response()->json(["message" => 'Oops an error has occurred please try again later', "status" => true]);

    }

    function massremove(Request $request)
    {
        $student_id_array = $request->input('id');
        $student = Student::whereIn('id', $student_id_array);
        if ($student->delete()) {
            echo 'Data Deleted';
        }
    }
}
