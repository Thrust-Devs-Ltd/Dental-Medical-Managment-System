<?php

namespace App\Http\Controllers;

use App\BillingEmailNotification;
use App\Http\Helper\FunctionsHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class BillingEmailNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($_GET['search'])) {
                $data = DB::table('billing_email_notifications')
                    ->where('billing_email_notifications.message', 'like', '%' . $request->get('search') . '%')
                    ->select('billing_email_notifications.*', DB::raw('DATE_FORMAT(billing_email_notifications.created_at, "%d-%b-%Y") as created_at'))
                    ->OrderBy('billing_email_notifications.id', 'desc')
                    ->get();
            } else if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                //store filtered dates
                FunctionsHelper::storeDateFilter($request);

                $data = DB::table('billing_email_notifications')
                    ->whereBetween(DB::raw('DATE(billing_email_notifications.created_at)'), array($request->start_date, $request->end_date))
                    ->select('billing_email_notifications.*', DB::raw('DATE_FORMAT(billing_email_notifications.created_at, "%d-%b-%Y") as created_at'))
                    ->OrderBy('billing_email_notifications.id', 'desc')
                    ->get();
            } else {
                $data = DB::table('billing_email_notifications')
                    ->select('billing_email_notifications.*', DB::raw('DATE_FORMAT(billing_email_notifications.created_at, "%d-%b-%Y") as created_at'))
                    ->OrderBy('billing_email_notifications.id', 'desc')
                    ->get();
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('message', function ($row) {
                    return $row->message;
                })
                ->addColumn('notification_type', function ($row) {
                    $type = '';
                    if ($row->notification_type == "Invoice") {
                        $type = '<span class="label label-sm label-danger">' . $row->notification_type . '</span>';
                    } else if ($row->notification_type == "Quotation") {
                        $type = '<span class="label label-sm label-success">' . $row->notification_type . '</span>';
                    } else {
                        $type = '<span class="label label-sm label-info">' . $row->notification_type . '</span>';
                    }
                    return $type;
                })
                ->rawColumns(['message', 'notification_type'])
                ->make(true);
        }
        return view('billing_email_notifications.index');
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
     * @param \App\BillingEmailNotification $billingEmailNotification
     * @return \Illuminate\Http\Response
     */
    public function show(BillingEmailNotification $billingEmailNotification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\BillingEmailNotification $billingEmailNotification
     * @return \Illuminate\Http\Response
     */
    public function edit(BillingEmailNotification $billingEmailNotification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\BillingEmailNotification $billingEmailNotification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BillingEmailNotification $billingEmailNotification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\BillingEmailNotification $billingEmailNotification
     * @return \Illuminate\Http\Response
     */
    public function destroy(BillingEmailNotification $billingEmailNotification)
    {
        //
    }
}
