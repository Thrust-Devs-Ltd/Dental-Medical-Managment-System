<?php

namespace App\Http\Controllers;

use App\ChartOfAccountCategory;
use App\ChartOfAccountItem;
use App\Expense;
use App\ExpenseCategory;
use App\ExpenseItem;
use App\ExpensePayment;
use App\Http\Helper\FunctionsHelper;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

use Haruncpi\LaravelIdGenerator\IdGenerator;
use ExcelReport;
use Excel;

class ExpenseController extends Controller
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
                $data = DB::table('expenses')
                    ->join('suppliers', 'suppliers.id', 'expenses.supplier_id')
                    ->join('users', 'users.id', 'expenses._who_added')
                    ->whereNull('expenses.deleted_at')
                    ->where('suppliers.name', 'like', '%' . $request->get('search') . '%')
                    ->select('expenses.*', 'suppliers.name as supplier_name', 'users.surname', 'users.othername')
                    ->OrderBy('expenses.updated_at', 'desc')
                    ->get();
            } else if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                FunctionsHelper::storeDateFilter($request);
                $data = DB::table('expenses')
                    ->join('suppliers', 'suppliers.id', 'expenses.supplier_id')
                    ->join('users', 'users.id', 'expenses._who_added')
                    ->whereNull('expenses.deleted_at')
                    ->whereBetween(DB::raw('DATE(expenses.created_at)'), array($request->start_date,
                        $request->end_date))
                    ->select('expenses.*', 'suppliers.name as supplier_name', 'users.surname', 'users.othername')
                    ->OrderBy('expenses.updated_at', 'desc')
                    ->get();
            } else {
                $data = DB::table('expenses')
                    ->join('suppliers', 'suppliers.id', 'expenses.supplier_id')
                    ->join('users', 'users.id', 'expenses._who_added')
                    ->whereNull('expenses.deleted_at')
                    ->select('expenses.*', 'suppliers.name as supplier_name', 'users.surname', 'users.othername')
                    ->OrderBy('expenses.updated_at', 'desc')
                    ->get();
            }


            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('purchase_no', function ($row) {
                    return '<a href="' . url('expenses/' . $row->id) . '">' . $row->purchase_no . '</a>';
                })
                ->addColumn('amount', function ($row) {
                    return number_format($this->TotalAmount($row->id));
                })
                //1,065,000
                ->addColumn('paid_amount', function ($row) {
                    $paid_amount = $this->TotalAmount($row->id) - $this->PurchaseBalance($row->id);
                    return number_format($paid_amount);
                })
                ->addColumn('due_amount', function ($row) {
                    //check if purchase is fully paid
                    if ($this->PurchaseBalance($row->id) <= 0) {
                        return '<span class="text-primary">' . number_format($this->PurchaseBalance($row->id)) . '</span>';
                    }
                    return number_format($this->PurchaseBalance($row->id)) . '<br>
                    <a href="#" onclick="RecordPayment(' . $row->id . ')" class="text-primary">Record a payment</a> ';
                })
                ->addColumn('added_by', function ($row) {
                    return $row->othername;
                })
                ->addColumn('action', function ($row) {
                    $btn = '
                      <div class="btn-group">
                        <button class="btn blue dropdown-toggle" type="button" data-toggle="dropdown"
                                aria-expanded="false"> Action
                            <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="' . url('expenses/' . $row->id) . '"> View Purchase</a>
                            </li>
                             <li>
                                <a  href="#"  onclick="deleteRecord(' . $row->id . ')" > Delete </a>
                            </li>
                        </ul>
                    </div>
                    ';
                    return $btn;
                })
                ->rawColumns(['purchase_no', 'status', 'action', 'due_amount'])
                ->make(true);
        }
        $data['chart_of_accts'] = ChartOfAccountItem::leftJoin('chart_of_account_categories', 'chart_of_account_categories.id',
            'chart_of_account_items.chart_of_account_category_id')
            ->leftJoin('accounting_equations', 'accounting_equations.id',
                'chart_of_account_categories.accounting_equation_id')
            ->whereNull('chart_of_account_items.deleted_at')
            ->where('accounting_equations.name', '!=', 'Income')
            ->where('chart_of_account_categories.name', '!=', 'Cash and Bank')
            ->select('chart_of_account_items.*')
            ->get();

        //use the accounts to clear the payment
        $data['payment_accts'] = ChartOfAccountItem::leftJoin('chart_of_account_categories', 'chart_of_account_categories.id',
            'chart_of_account_items.chart_of_account_category_id')
            ->leftJoin('accounting_equations', 'accounting_equations.id',
                'chart_of_account_categories.accounting_equation_id')
            ->whereNull('chart_of_account_items.deleted_at')
            ->where('chart_of_account_categories.name', '=', 'Cash and Bank')
            ->select('chart_of_account_items.*')
            ->get();

        return view('expenses.index')->with($data);
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
            'purchase_date' => 'required',
            'supplier' => 'required'
        ])->validate();
        //check if the supplier already exists or create new new

        $supplier = $this->createOrGetSupplier($request->supplier);
        //first create the purchase
        $expense = Expense::create([
            'purchase_no' => Expense::PurchaseNo(),
            'supplier_id' => $supplier,
            'purchase_date' => $request->purchase_date,
            'branch_id' => Auth::User()->branch_id,
            '_who_added' => Auth::User()->id
        ]);
        //now insert purchase items
        if ($expense) {
            foreach ($request->addmore as $key => $value) {
                //expense item category
                $item_category = $this->createOrGetExpenseCategory($value['item'], $value['expense_category']); // chech if the item
                // category
                // exits or create new item category
                ExpenseItem::create([
                    'expense_category_id' => $item_category,
                    'description' => $value['description'],
                    'qty' => $value['qty'],
                    'price' => $value['price'],
                    'expense_id' => $expense->id,
                    '_who_added' => Auth::User()->id
                ]);
            }
            return response()->json(['message' => 'Expense has been captured successfully', 'status' => true]);
        }

        return response()->json(['message' => 'Oops an error has occurred, please try again later', 'status' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Expense $expense
     * @return \Illuminate\Http\Response
     */
    public function show($expense_id)
    {
        $data['purchase_details'] = DB::table('expenses')
            ->join('suppliers', 'suppliers.id', 'expenses.supplier_id')
            ->where('expenses.id', $expense_id)
            ->select('expenses.*', 'suppliers.name as supplier_name')
            ->first();
        $data['expense_id'] = $expense_id;
        //use the accounts to clear the payment
        $data['payment_accts'] = ChartOfAccountItem::leftJoin('chart_of_account_categories', 'chart_of_account_categories.id',
            'chart_of_account_items.chart_of_account_category_id')
            ->leftJoin('accounting_equations', 'accounting_equations.id',
                'chart_of_account_categories.accounting_equation_id')
            ->whereNull('chart_of_account_items.deleted_at')
            ->where('chart_of_account_categories.name', '=', 'Cash and Bank')
            ->select('chart_of_account_items.*')
            ->get();
        return view('expense_items.index')->with($data);
    }


    private function PurchaseBalance($purchase_id)
    {
        $invoice_amount = ExpenseItem::where('expense_id', $purchase_id)->sum(DB::raw('qty * price'));

        $amount_paid = ExpensePayment::where('expense_id', $purchase_id)->sum('amount');
        //remaining balance
        $balance = $invoice_amount - $amount_paid;
        return $balance;
    }


    public function exportReport(Request $request)
    {

        if ($request->session()->get('from') != '' && $request->session()->get('to') != '') {
            $queryBuilder = DB::table('expense_items')
                ->join('expenses', 'expenses.id', 'expense_items.expense_id')
                ->join('expense_categories', 'expense_categories.id', 'expense_items.expense_category_id')
                ->join('chart_of_account_items', 'chart_of_account_items.id', 'expense_categories.chart_of_account_item_id')
                ->whereNull('expense_items.deleted_at')
                ->whereNull('expenses.deleted_at')
                ->whereBetween(DB::raw('DATE(expense_items.created_at)'), array($request->session()->get('from'),
                    $request->session()->get('to')))
                ->select('expense_items.*', 'expense_categories.name as item_name', 'chart_of_account_items.name as budget_line')
                ->OrderBy('expense_items.id', 'ASC')
                ->get();

        } else {
            $queryBuilder = DB::table('expense_items')
                ->join('expenses', 'expenses.id', 'expense_items.expense_id')
                ->join('expense_categories', 'expense_categories.id', 'expense_items.expense_category_id')
                ->join('chart_of_account_items', 'chart_of_account_items.id', 'expense_categories.chart_of_account_item_id')
                ->whereNull('expense_items.deleted_at')
                ->whereNull('expenses.deleted_at')
                ->select('expense_items.*', 'expense_categories.name as item_name', 'chart_of_account_items.name as budget_line')
                ->OrderBy('expense_items.id', 'ASC')
                ->get();
        }


        $excel_file_name = "expenses-report- " . time();
        $sheet_title = "From " . date('d-m-Y', strtotime($request->session()->get('from'))) . " To " .
            date('d-m-Y', strtotime($request->session()->get('to')));

        return Excel::create($excel_file_name, function ($excel) use ($queryBuilder, $sheet_title) {

            $excel->sheet($sheet_title, function ($sheet) use ($queryBuilder) {
                $payload = [];
                $count_rows = 2;
                $grand_total = 0;
                $counter = 1;

                foreach ($queryBuilder as $row) {
                    $payload[] = array('ID' => $counter,
                        'Purchase Date' => date('d-M-Y', strtotime($row->created_at)),
                        'Item Name' => $row->item_name,
                        'Budget Line' => $row->budget_line,
                        'Quantity' => $row->qty,
                        'Unit Price' => $row->price,
                        'Total Amount' => $row->qty * $row->price);
                    $count_rows++;
                    $counter++;
                    $grand_total = $grand_total + ($row->qty * $row->price);
                }
                //general invoices totals
                $sheet->cell('G' . $count_rows, function ($cell) use ($grand_total) {
                    $cell->setValue('Total= ' . number_format($grand_total));
                    $cell->setFontWeight('bold');
                });

                $sheet->fromArray($payload);
            });

        })->download('xls');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Expense $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Expense $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Expense $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = Expense::where('id', $id)->delete();
        if ($status) {
            return response()->json(['message' => 'Expense has been deleted successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops error has occurred, please try again later', 'status' => false]);
    }

    private function createOrGetSupplier($supplier)
    {
        $existing_supplier = Supplier::where('name', $supplier)->first();
        if ($existing_supplier != null) {
            return $existing_supplier->id;
        } else {
            // insert new supplier
            $new_supplier = Supplier::create(['name' => $supplier, '_who_added' => Auth::User()->id]);
            return $new_supplier->id;
        }
    }

    private function createOrGetExpenseCategory($expense_category, $chart_of_account_item_id)
    {
        $existing_item = ExpenseCategory::where('name', $expense_category)->first();
        if ($existing_item != null) {
            return $existing_item->id;
        } else {
            // insert new expense item category
            $new_item = ExpenseCategory::create(
                [
                    'name' => $expense_category,
                    'chart_of_account_item_id' => $chart_of_account_item_id,
                    '_who_added' => Auth::User()->id
                ]);
            return $new_item->id;
        }
    }

    private function TotalAmount($id)
    {
        return ExpenseItem::where('expense_id', $id)->sum(DB::raw('qty * price'));
    }
}
