<?php

namespace App\Http\Controllers;

use App\InvoiceItem;
use App\InvoicePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class InsuranceReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {


            if (!empty($_GET['start_date']) && !empty($_GET['end_date']) && !empty($_GET['company'])) {
                $from_date = date('Y-m-d', strtotime($request->start_date));
                $to_date = date('Y-m-d', strtotime($request->end_date));

                $data = DB::table('invoice_payments')
                    ->leftJoin('invoices', 'invoices.id', 'invoice_payments.invoice_id')
                    ->leftJoin('appointments', 'appointments.id', 'invoices.appointment_id')
                    ->leftJoin('patients', 'patients.id', 'appointments.patient_id')
                    ->leftJoin('insurance_companies', 'insurance_companies.id', 'invoice_payments.insurance_company_id')
                    ->whereNull('invoice_payments.deleted_at')
                    ->where('invoice_payments.payment_method', 'Insurance')
                    ->where('invoice_payments.insurance_company_id', $request->get('company'))
                    ->whereBetween(DB::raw('DATE(invoice_payments.created_at)'), array($from_date, $to_date))
                    ->select('invoice_payments.*', 'invoices.invoice_no', 'patients.surname', 'patients.othername', 'insurance_companies.name as insurance_company')
                    ->get();
            } else {
                $data = DB::table('invoice_payments')
                    ->leftJoin('invoices', 'invoices.id', 'invoice_payments.invoice_id')
                    ->leftJoin('appointments', 'appointments.id', 'invoices.appointment_id')
                    ->leftJoin('patients', 'patients.id', 'appointments.patient_id')
                    ->leftJoin('insurance_companies', 'insurance_companies.id', 'invoice_payments.insurance_company_id')
                    ->whereNull('invoice_payments.deleted_at')
                    ->where('invoice_payments.payment_method', 'Insurance')
                    ->select('invoice_payments.*', 'invoices.invoice_no', 'patients.surname', 'patients.othername', 'insurance_companies.name as insurance_company')
                    ->get();
            }


            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('services_provided', function ($row) {
                    //now get the services provided
                    $invoice_items = InvoiceItem::where('invoice_id', $row->invoice_id)->get();
                    $item = '';
                    foreach ($invoice_items as $items) {
                        $item .= $items->medical_service->name . ",";
                    }
                    return $item;
                })
                ->addColumn('patient', function ($row) {
                    return $row->surname . " " . $row->othername;
                })
                ->addColumn('amount', function ($row) {
                    return number_format($row->amount);
                })
                ->addColumn('deleteBtn', function ($row) {

                    $btn = '<a href="#" onclick="deleteRecord(' . $row->id . ')" class="btn btn-danger">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['editBtn', 'deleteBtn'])
                ->make(true);
        }
        return view('insurance_report.index');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
