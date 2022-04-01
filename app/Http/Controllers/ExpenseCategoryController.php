<?php

namespace App\Http\Controllers;

use App\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ExpenseCategoryController extends Controller
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

            $data = ExpenseCategory::OrderBy('updated_at', 'DESC')->get();


            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('addedBy', function ($row) {
                    return $row->AddedBy->othername;
                })
                ->addColumn('expense_account', function ($row) {
                    return $row->ExpenseAccount->name;
                })
                ->addColumn('editBtn', function ($row) {
                    $btn = '<a href="#" onclick="editRecord(' . $row->id . ')" class="btn btn-primary">Edit</a>';
                    return $btn;
                })
                ->addColumn('deleteBtn', function ($row) {

                    $btn = '<a href="#" onclick="deleteRecord(' . $row->id . ')" class="btn btn-danger">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['editBtn', 'deleteBtn'])
                ->make(true);
        }
        $data['expense_accounts'] = DB::table('chart_of_account_items')
            ->leftJoin('chart_of_account_categories', 'chart_of_account_categories.id',
                'chart_of_account_items.chart_of_account_category_id')
            ->leftJoin('accounting_equations', 'accounting_equations.id',
                'chart_of_account_categories.accounting_equation_id')
            ->whereNull('chart_of_account_items.deleted_at')
            ->where('accounting_equations.name', 'Expenses')
            ->select('chart_of_account_items.*')
            ->get();
        return view('expense_categories.index')->with($data);
    }

    public function filterExpenseCategories(Request $request)
    {
        $search = $request->get('term');

        $result = ExpenseCategory::leftjoin('chart_of_account_items', 'chart_of_account_items.id',
            'expense_categories.chart_of_account_item_id')
            ->select('expense_categories.*')->get();
        $data = [];
        foreach ($result as $row) {
            $data[] = $row->name;
        }
        echo json_encode($data);
    }

    public function SearchCategory(Request $request)
    {
        $data = [];
        $name = $request->q;

        if ($name) {
            $search = $name;
            $data =
                ExpenseCategory::where('name', 'LIKE', "%$search%")->get();

            $formatted_tags = [];
            foreach ($data as $tag) {
                $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->name];
            }
            return \Response::json($formatted_tags);
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
            'name' => 'required',
            'expense_account' => 'required'
        ])->validate();
        $status = ExpenseCategory::create([
            'name' => $request->name,
            'chart_of_account_item_id' => $request->expense_account,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Expense category has been added successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again', 'status' => false]);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\ExpenseCategory $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ExpenseCategory $expenseCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\ExpenseCategory $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = ExpenseCategory::where('id', $id)
            ->first();
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\ExpenseCategory $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'expense_account' => 'required'
        ])->validate();
        $status = ExpenseCategory::where('id', $id)->update([
            'name' => $request->name,
            'chart_of_account_item_id' => $request->expense_account,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Expense category has been updated successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again', 'status' => false]);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\ExpenseCategory $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = ExpenseCategory::where('id', $id)->delete();
        if ($status) {
            return response()->json(['message' => 'Expense category has been deleted successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again', 'status' => false]);

    }
}
