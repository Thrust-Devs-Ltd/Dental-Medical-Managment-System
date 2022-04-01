<?php

namespace App\Http\Controllers;

use App\ChartOfAccountCategory;
use App\Http\Helper\FunctionsHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChartOfAccountCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    }

    /**
     * Display the specified resource.
     *
     * @param \App\ChartOfAccountCategory $chartOfAccountCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ChartOfAccountCategory $chartOfAccountCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\ChartOfAccountCategory $chartOfAccountCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ChartOfAccountCategory $chartOfAccountCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\ChartOfAccountCategory $chartOfAccountCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChartOfAccountCategory $chartOfAccountCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\ChartOfAccountCategory $chartOfAccountCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChartOfAccountCategory $chartOfAccountCategory)
    {
        //
    }
}
