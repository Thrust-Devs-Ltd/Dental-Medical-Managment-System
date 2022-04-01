<?php

namespace App\Http\Controllers;

use App\Http\Helper\FunctionsHelper;
use App\SmsTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SmsTransactionController extends Controller
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
            if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                //store filtered dates
                FunctionsHelper::storeDateFilter($request);

                $data = DB::table('sms_transactions')
                    ->leftJoin('users', 'users.id', 'sms_transactions._who_added')
                    ->whereNull('sms_transactions.deleted_at')
                    ->where('type','topup')
                    ->whereBetween(DB::raw('DATE(sms_transactions.created_at)'), array($request->start_date, $request->end_date))
                    ->select('sms_transactions.*', 'users.surname', 'users.othername')
                    ->OrderBy('sms_transactions.id', 'desc')
                    ->get();
            } else {
                $data = DB::table('sms_transactions')
                    ->leftJoin('users', 'users.id', 'sms_transactions._who_added')
                    ->whereNull('sms_transactions.deleted_at')
                    ->where('type','topup')
                    ->select('sms_transactions.*', 'users.surname', 'users.othername')
                    ->OrderBy('sms_transactions.id', 'desc')
                    ->get();
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('amount', function ($row) {
                    return number_format($row->amount);
                })
                ->addColumn('addedBy', function ($row) {
                    return $row->othername;
                })
                ->rawColumns(['message_receiver'])
                ->make(true);
        }
        //total amount loaded on the system
        $current_balance = $this->CreditLoaded() - $this->CreditUsed();
        $data['current_balance'] = number_format($current_balance);
        return view('sms_transactions.index')->with($data);
    }

    private function CreditLoaded()
    {
        $loaded_credit = SmsTransaction::where('type', 'topup')->sum('amount');
        return $loaded_credit;
    }

    private function CreditUsed()
    {
        $credit_used = SmsTransaction::where('type', '!=', 'topup')->sum('amount');
        return $credit_used;
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
     * @param \App\SmsTransaction $smsTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(SmsTransaction $smsTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\SmsTransaction $smsTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(SmsTransaction $smsTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\SmsTransaction $smsTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SmsTransaction $smsTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\SmsTransaction $smsTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(SmsTransaction $smsTransaction)
    {
        //
    }
}
