<?php

namespace App\Http\Controllers;

use App\AccountingEquation;
use Illuminate\Http\Request;

class AccountingEquationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['AccountingEquations'] = AccountingEquation::OrderBy('order_by')->get();
        return view('charts_of_accounts.index')->with($data);
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
     * @param \App\AccountingEquation $accountingEquation
     * @return \Illuminate\Http\Response
     */
    public function show(AccountingEquation $accountingEquation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\AccountingEquation $accountingEquation
     * @return \Illuminate\Http\Response
     */
    public function edit(AccountingEquation $accountingEquation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\AccountingEquation $accountingEquation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccountingEquation $accountingEquation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\AccountingEquation $accountingEquation
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountingEquation $accountingEquation)
    {
        //
    }
}
