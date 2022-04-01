<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SelfAccountBillPayment extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param $self_account_id
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request, $self_account_id)
    {
        if ($request->ajax()) {

            $data = DB::table('invoice_payments')
                ->leftJoin('invoices', 'invoices.id', 'invoice_payments.invoice_id')
                ->leftJoin('appointments', 'appointments.id', 'invoices.appointment_id')
                ->leftJoin('patients', 'patients.id', 'appointments.patient_id')
                ->leftJoin('users', 'users.id', 'invoice_payments._who_added')
                ->whereNull('invoice_payments.deleted_at')
                ->where('invoice_payments.self_account_id', $self_account_id)
                ->select('invoice_payments.*', 'invoices.invoice_no', 'patients.surname', 'patients.othername', 'users.surname as  added_by')
                ->orderBy('invoice_payments.updated_at', 'DESC')
                ->get();


            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {

                })
                ->addColumn('patient', function ($row) {
                    return $row->surname . " " . $row->othername;
                })->addColumn('amount', function ($row) {
                    return number_format($row->amount);
                })
                ->rawColumns([])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
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
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
