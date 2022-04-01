<?php

namespace App\Http\Controllers;

use App\ExpenseCategory;
use App\ExpenseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ExpenseItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param $expense_id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request, $expense_id)
    {
        if ($request->ajax()) {

            $data = DB::table('expense_items')
                ->join('expense_categories', 'expense_categories.id', 'expense_items.expense_category_id')
                ->join('users', 'users.id', 'expense_items._who_added')
                ->whereNull('expense_items.deleted_at')
                ->where('expense_items.expense_id', '=', $expense_id)
                ->select('expense_items.*', 'expense_categories.name', 'users.othername as added_by')
                ->OrderBy('expense_items.updated_at', 'desc')
                ->get();


            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('price', function ($row) {
                    return number_format($row->price);
                })
                ->addColumn('total_amount', function ($row) {
                    return '<span class="bold">' . number_format($row->qty * $row->price) . '</span>';
                })
                ->addColumn('editBtn', function ($row) {
                    $btn = '<a href="#" onclick="editItemRecord(' . $row->id . ')" class="btn btn-primary">Edit</a>';
                    return $btn;
                })
                ->addColumn('deleteBtn', function ($row) {

                    $btn = '<a href="#" onclick="deleteItemRecord(' . $row->id . ')" class="btn btn-danger">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['total_amount', 'editBtn', 'deleteBtn'])
                ->make(true);
        }
        return view('expense_items.index');
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
            'item' => 'required',
            'qty' => 'required',
            'price' => 'required'
        ])->validate();
        $item_category = $this->createOrGetExpenseCategory($request->item); // chech if the item category// exits or create new item category

        $status = ExpenseItem::create([
            'expense_category_id' => $item_category,
            'qty' => $request->qty,
            'price' => $request->price,
            'expense_id' => $request->expense_id,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Expense item has been added successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred please try again later', 'status' => false]);
    }


    private function createOrGetExpenseCategory($expense_category)
    {
        $existing_item = ExpenseCategory::where('name', $expense_category)->first();
        if ($existing_item != null) {
            return $existing_item->id;
        } else {
            // insert new expense item category
            $new_item = ExpenseCategory::create(['name' => $expense_category, '_who_added' => Auth::User()->id]);
            return $new_item->id;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\ExpenseItem $expenseItem
     * @return \Illuminate\Http\Response
     */
    public function show(ExpenseItem $expenseItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\ExpenseItem $expenseItem
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expenseItem = DB::table('expense_items')
            ->leftJoin('expense_categories', 'expense_categories.id', 'expense_items.expense_category_id')
            ->whereNull('expense_items.deleted_at')
            ->where('expense_items.id', $id)
            ->select('expense_items.*', 'expense_categories.name')
            ->first();
        return response()->json($expenseItem);
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
            'item' => 'required',
            'qty' => 'required',
            'price' => 'required'
        ])->validate();

        $item_category = $this->createOrGetExpenseCategory($request->item); // chech if the item category// exits or create new item category

        $status = ExpenseItem::where('id', $id)->update([
            'expense_category_id' => $item_category,
            'qty' => $request->qty,
            'price' => $request->price,
            '_who_added' => Auth::User()->id
        ]);
        if ($status) {
            return response()->json(['message' => 'Expense item has been updated successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred please try again later', 'status' => false]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\ExpenseItem $expenseItem
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = ExpenseItem::where('id', $id)->delete();
        if ($status) {
            return response()->json(['message' => 'Expense item has been deleted successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred please try again later', 'status' => false]);


    }
}
