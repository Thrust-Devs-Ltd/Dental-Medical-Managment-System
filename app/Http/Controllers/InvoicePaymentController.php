<?php

namespace App\Http\Controllers;

use App\InvoicePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class InvoicePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param $invoice_id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request, $invoice_id)
    {
        if ($request->ajax()) {

            $data = InvoicePayment::where('invoice_id', $invoice_id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('amount', function ($row) {
                    return number_format($row->amount);
                })
                ->addColumn('added_by', function ($row) {
                    return $row->addedBy->surname . " " . $row->addedBy->othername;
                })
                ->addColumn('editBtn', function ($row) {
                    $btn = '<a href="#" onclick="edit_Payment(' . $row->id . ')" class="btn btn-primary">Edit</a>';
                    return $btn;
                })
                ->addColumn('deleteBtn', function ($row) {
                    $btn = '<a href="#" onclick="delete_payment(' . $row->id . ')" class="btn btn-danger">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['editBtn', 'deleteBtn'])
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
        Validator::make($request->all(), [
            'amount' => 'required',
            'payment_date' => 'required',
            'payment_method' => 'required',
            'invoice_id' => 'required'
        ])->validate();

        $status = InvoicePayment::create([
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'cheque_no' => $request->cheque_no,
            'account_name' => $request->account_name,
            'bank_name' => $request->bank_name,
            'invoice_id' => $request->invoice_id,
            'insurance_company_id' => $request->insurance_company_id,
            'self_account_id' => $request->self_account_id,
            'branch_id' => Auth::User()->branch_id,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Payment has been captured successfully', 'status' => true]);
        }
        return response()->json(['message', 'Oops error has occurred, please try again later', 'status' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\InvoicePayment $invoicePayment
     * @return \Illuminate\Http\Response
     */
    public function show(InvoicePayment $invoicePayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\InvoicePayment $invoicePayment
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payment = DB::table('invoice_payments')
            ->leftJoin('insurance_companies', 'insurance_companies.id',
                'invoice_payments.insurance_company_id')
            ->where('invoice_payments.id', $id)
            ->select('invoice_payments.*', 'insurance_companies.name')
            ->first();
        return response()->json($payment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\InvoicePayment $invoicePayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        Validator::make($request->all(), [
            'amount' => 'required',
            'payment_date' => 'required',
            'payment_method' => 'required'
        ])->validate();

        $status = InvoicePayment::where('id', $id)->update([
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'cheque_no' => $request->cheque_no,
            'account_name' => $request->account_name,
            'bank_name' => $request->bank_name,
            'insurance_company_id' => $request->insurance_company_id,
            'self_account_id' => $request->self_account_id,
            'branch_id' => Auth::User()->branch_id,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Payment has been updated successfully', 'status' => true]);
        }
        return response()->json(['message', 'Oops error has occurred, please try again later', 'status' => false]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\InvoicePayment $invoicePayment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = InvoicePayment::where('id', $id)->delete();
        if ($status) {
            return response()->json(['message' => 'Payment has been deleted successfully', 'status' => true]);
        }
        return response()->json(['message', 'Oops error has occurred, please try again later', 'status' => false]);

    }
}
