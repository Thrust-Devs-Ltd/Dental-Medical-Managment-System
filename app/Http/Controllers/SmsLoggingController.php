<?php

namespace App\Http\Controllers;

use App\Http\Helper\FunctionsHelper;
use App\SmsLogging;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use ExcelReport;


class SmsLoggingController extends Controller
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
                $data = DB::table('sms_loggings')
                    ->leftJoin('patients', 'patients.id', 'sms_loggings.patient_id')
                    ->where('patients.surname', 'like', '%' . $request->get('search') . '%')
                    ->orWhere('patients.othername', 'like', '%' . $request->get('search') . '%')
                    ->select('sms_loggings.*', 'patients.surname', 'patients.othername')
                    ->OrderBy('sms_loggings.id', 'desc')
                    ->get();
            } else if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                //store filtered dates
                FunctionsHelper::storeDateFilter($request);

                $data = DB::table('sms_loggings')
                    ->leftJoin('patients', 'patients.id', 'sms_loggings.patient_id')
                    ->whereBetween(DB::raw('DATE(sms_loggings.created_at)'), array($request->start_date, $request->end_date))
                    ->select('sms_loggings.*', 'patients.surname', 'patients.othername')
                    ->OrderBy('sms_loggings.id', 'desc')
                    ->get();
            } else {
                $data = DB::table('sms_loggings')
                    ->leftJoin('patients', 'patients.id', 'sms_loggings.patient_id')
                    ->select('sms_loggings.*', 'patients.surname', 'patients.othername')
                    ->OrderBy('sms_loggings.id', 'desc')
                    ->get();
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('message_receiver', function ($row) {
                    return $row->surname . " " . $row->othername;
                })
                ->addColumn('type', function ($row) {
                    $type = '';
                    if ($row->type == "Reminder") {
                        $type = '<span class="label label-sm label-danger">' . $row->type . '</span>';
                    } else {
                        $type = '<span class="label label-sm label-success">' . $row->type . '</span>';
                    }
                    return $type;
                })
                ->rawColumns(['message_receiver', 'type'])
                ->make(true);
        }
        return view('outbox_sms.index');
    }

    public function exportReport(Request $request)
    {
        if ($request->session()->get('from') != '' && $request->session()->get('to') != '') {
            $queryBuilder = DB::table('sms_loggings')
                ->whereBetween(DB::raw('DATE(sms_loggings.created_at)'), array($request->session()->get('from'),
                    $request->session()->get('to')))
                ->select('sms_loggings.*')
                ->OrderBy('sms_loggings.id', 'desc');
        } else {
            $queryBuilder = DB::table('sms_loggings')
                ->select('sms_loggings.*')
                ->OrderBy('sms_loggings.id', 'desc');
        }


        $columns = ['created_at', 'phone_number', 'message', 'cost', 'status'];

        return ExcelReport::of(null,
            [
                'SMS Report ' => "From:   " . $request->session()->get('from') . "    To:    " . $request->session()
                        ->get('to'),
            ], $queryBuilder, $columns)
            ->simple()
            ->download('sms-logging-report' . date('Y-m-d H:m:s'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public
    function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public
    function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\SmsLogging $smsLogging
     * @return \Illuminate\Http\Response
     */
    public
    function show(SmsLogging $smsLogging)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\SmsLogging $smsLogging
     * @return \Illuminate\Http\Response
     */
    public
    function edit(SmsLogging $smsLogging)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\SmsLogging $smsLogging
     * @return \Illuminate\Http\Response
     */
    public
    function update(Request $request, SmsLogging $smsLogging)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\SmsLogging $smsLogging
     * @return \Illuminate\Http\Response
     */
    public
    function destroy(SmsLogging $smsLogging)
    {
        //
    }
}
