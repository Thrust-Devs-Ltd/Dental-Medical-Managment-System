<?php

namespace App\Http\Controllers;

use App\Http\Helper\FunctionsHelper;
use App\Jobs\ShareEmailQuotation;
use App\MedicalService;
use App\Quotation;
use PDF;
use App\QuotationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class QuotationController extends Controller
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
                $data = DB::table('quotations')
                    ->join('patients', 'patients.id', 'quotations.patient_id')
                    ->join('users', 'users.id', 'quotations._who_added')
                    ->whereNull("quotations.deleted_at")
                    ->where('patients.surname', 'like', '%' . $request->get('search') . '%')
                    ->orWhere('patients.othername', 'like', '%' . $request->get('search') . '%')
                    ->select('quotations.*', 'patients.surname', 'patients.othername', 'users.othername as addedBy')
                    ->OrderBy('quotations.id', 'desc')
                    ->get();
            } else if (!empty($_GET['quotation_no'])) {
                $data = DB::table('quotations')
                    ->join('patients', 'patients.id', 'quotations.patient_id')
                    ->join('users', 'users.id', 'quotations._who_added')
                    ->whereNull("quotations.deleted_at")
                    ->where('quotations.quotation_no', '=', $request->quotation_no)
                    ->select('quotations.*', 'patients.surname', 'patients.othername', 'users.othername as addedBy')
                    ->OrderBy('quotations.id', 'desc')
                    ->get();
            } else if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

                FunctionsHelper::storeDateFilter($request);
                $data = DB::table('quotations')
                    ->join('patients', 'patients.id', 'quotations.patient_id')
                    ->join('users', 'users.id', 'quotations._who_added')
                    ->whereNull("quotations.deleted_at")
                    ->whereBetween(DB::raw('DATE_FORMAT(quotations.updated_at, \'%Y-%m-%d\')'), array($request->start_date,
                        $request->end_date))
                    ->select('quotations.*', 'patients.surname', 'patients.othername', 'users.othername as addedBy')
                    ->OrderBy('quotations.id', 'desc')
                    ->get();
            } else {
                $data = DB::table('quotations')
                    ->join('patients', 'patients.id', 'quotations.patient_id')
                    ->join('users', 'users.id', 'quotations._who_added')
                    ->whereNull("quotations.deleted_at")
                    ->select('quotations.*', 'patients.surname', 'patients.othername', 'users.othername as addedBy')
                    ->OrderBy('quotations.id', 'desc')
                    ->get();
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('quotation_no', function ($row) {
                    return '<a href="' . url('quotations/' . $row->id) . '">' . $row->quotation_no . '</a>';
                })
                ->addColumn('customer', function ($row) {
                    return $row->surname . " " . $row->othername;
                })
                ->addColumn('amount', function ($row) {
                    return number_format(QuotationItem::where('quotation_id', $row->id)->sum(DB::raw('qty*price')));
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
                                <a href="' . url('quotations/' . $row->id) . '"> View </a>
                            </li>
                             <li>
                                <a target="_blank" href="' . url('print-quotation/' . $row->id) . '"  > Print </a>
                            </li>
                              <li>
                                <a href="#" onclick="shareQuotationView(' . $row->id . ')"  > Share Quotation </a>
                            </li>
                        </ul>
                    </div>
                    ';
                    return $btn;
                })
                ->rawColumns(['quotation_no', 'action', 'status'])
                ->make(true);
        }
        return view('quotations.index');
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
            'patient_id' => 'required'
        ])->validate();

        $quotation_ = Quotation::create([
                'quotation_no' => Quotation::QuotationNo(),
                'patient_id' => $request->patient_id,
                '_who_added' => Auth::User()->id
            ]
        );
        if ($quotation_) {
            foreach ($request->addmore as $key => $value) {
                //get service id
                QuotationItem::create([
                    'qty' => $value['qty'],
                    'price' => $value['price'],
                    'quotation_id' => $quotation_->id,
                    'medical_service_id' => $value['medical_service_id'],
                    'tooth_no' => $value['tooth_no'],
                    '_who_added' => Auth::User()->id,
                ]);
            }
            return response()->json(['message' => 'Quotation has been created successfully', 'status' => true]);
        }

        return response()->json(['message' => 'Oops an error has occurred, please try again later', 'status' => false]);

    }


    /**
     * Display the specified resource.
     *
     * @param \App\Quotation $quotation
     * @return \Illuminate\Http\Response
     */
    public function show($quotation)
    {
        $data['patient'] = DB::table('quotations')
            ->join('patients', 'patients.id', 'quotations.patient_id')
            ->where('quotations.id', $quotation)
            ->select('patients.*')
            ->first();
        $data['quotation_id'] = $quotation;
        $data['quotation'] = Quotation::where('id', $quotation)->first();
        return view('quotations.show.index')->with($data);
    }


    /**
     * @param $quotation_id
     * @return mixed
     */
    public function printQuotation($quotation_id)
    {
        $data['patient'] = DB::table('quotations')
            ->leftJoin('patients', 'patients.id', 'quotations.patient_id')
            ->where('quotations.id', $quotation_id)
            ->select('patients.*')
            ->first();
        $data['quotation'] = Quotation::where('id', $quotation_id)->first();
        $data['quotation_items'] = DB::table('quotation_items')
            ->join('medical_services', 'medical_services.id', 'quotation_items.medical_service_id')
            ->join('users', 'users.id', 'quotation_items._who_added')
            ->whereNull('quotation_items.deleted_at')
            ->where('quotation_items.quotation_id', $quotation_id)
            ->select('quotation_items.*', 'medical_services.name', 'users.othername')
            ->OrderBy('quotation_items.updated_at', 'desc')
            ->get();

        $pdf = PDF::loadView('quotations.print_quotation', $data);
        return $pdf->stream('receipt', array("attachment" => false))->header('Content-Type', 'application/pdf');

    }

    public function QuotationShareDetails(Request $request, $quotation_id)
    {
        $quotations = DB::table('quotations')
            ->join('patients', 'patients.id', 'quotations.patient_id')
            ->join('users', 'users.id', 'quotations._who_added')
            ->whereNull("quotations.deleted_at")
            ->where('quotations.id', '=', $quotation_id)
            ->select('quotations.*', 'patients.surname', 'patients.othername', 'patients.email', 'users.othername as addedBy')
            ->OrderBy('quotations.id', 'desc')
            ->first();
        return response()->json($quotations);
    }

    public function SendQuotation(Request $request)
    {
        Validator::make($request->all(), [
            'quotation_id' => 'required',
            'email' => 'required'
        ])->validate();
        $data['patient'] = DB::table('quotations')
            ->leftJoin('patients', 'patients.id', 'quotations.patient_id')
            ->where('quotations.id', $request->quotation_id)
            ->select('patients.*')
            ->first();
        $data['quotation'] = Quotation::where('id', $request->quotation_id)->first();
        $data['quotation_items'] = DB::table('quotation_items')
            ->join('medical_services', 'medical_services.id', 'quotation_items.medical_service_id')
            ->join('users', 'users.id', 'quotation_items._who_added')
            ->whereNull('quotation_items.deleted_at')
            ->where('quotation_items.quotation_id', $request->quotation_id)
            ->select('quotation_items.*', 'medical_services.name', 'users.othername')
            ->OrderBy('quotation_items.updated_at', 'desc')
            ->get();

        dispatch(new ShareEmailQuotation($data, $request->email, $request->message));
        return response()->json(['message' => 'Quotation has been shared successfully', 'status' => true]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Quotation $quotation
     * @return \Illuminate\Http\Response
     */
    public function edit(Quotation $quotation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Quotation $quotation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quotation $quotation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Quotation $quotation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quotation $quotation)
    {
        //
    }
}
