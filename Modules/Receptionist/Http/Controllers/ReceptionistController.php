<?php

namespace Modules\Receptionist\Http\Controllers;

use App\Appointment;
use App\ExpensePayment;
use App\InsuranceCompany;
use App\InvoicePayment;
use App\Patient;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;

class ReceptionistController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
         $data['total_patients'] = Patient::count();
        $data['total_users'] = User::count();
        $data['total_insurance_company'] = InsuranceCompany::count();

        $data['today_appointments'] = Appointment::where('updated_at', '>', Carbon::today())->count();

        $data['today_cash_amount'] = InvoicePayment::where('payment_method', 'Cash')
            ->whereDate('payment_date',date('Y-m-d'))->sum('amount');


        $data['today_Insurance_amount'] = InvoicePayment::where('payment_method', 'Insurance')
            ->whereDate('payment_date', date('Y-m-d'))->sum('amount');

        $data['today_expense_amount'] = ExpensePayment::whereDate('payment_date',date('Y-m-d'))->sum('amount');
      
        return view('receptionist::index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('receptionist::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('receptionist::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('receptionist::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
