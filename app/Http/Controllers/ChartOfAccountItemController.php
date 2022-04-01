<?php

namespace App\Http\Controllers;

use App\ChartOfAccountItem;
use App\Http\Helper\FunctionsHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ChartOfAccountItemController extends Controller
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
        Validator::make($request->all(), [
            'name' => 'required',
            'account_type' => 'required'
        ])->validate();
        $success = ChartOfAccountItem::create([
            'name' => $request->name,
            'description' => $request->description,
            'chart_of_account_category_id' => $request->account_type,
            '_who_added' => Auth::User()->id
        ]);
        if ($success) {
            return FunctionsHelper::messageResponse("Chart account has been added successfully", $success);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\ChartOfAccountItem $chartOfAccountItem
     * @return \Illuminate\Http\Response
     */
    public function show(ChartOfAccountItem $chartOfAccountItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return void
     */
    public function edit($id)
    {
        $data = DB::table('chart_of_account_items')
            ->leftJoin('chart_of_account_categories', 'chart_of_account_categories.id',
                'chart_of_account_items.chart_of_account_category_id')
            ->whereNull('chart_of_account_items.deleted_at')
            ->where('chart_of_account_items.id', $id)
            ->select(['chart_of_account_items.*', 'chart_of_account_categories.name as category_name'])
            ->first();
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'account_type' => 'required'
        ])->validate();
        $success = ChartOfAccountItem::where('id', $id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'chart_of_account_category_id' => $request->account_type,
            '_who_added' => Auth::User()->id
        ]);
        if ($success) {
            return FunctionsHelper::messageResponse("Chart account has been updated successfully", $success);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\ChartOfAccountItem $chartOfAccountItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChartOfAccountItem $chartOfAccountItem)
    {
        //
    }
}
