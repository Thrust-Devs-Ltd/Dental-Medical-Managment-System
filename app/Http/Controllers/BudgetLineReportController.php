<?php

namespace App\Http\Controllers;

use App\Http\Helper\FunctionsHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use ExcelReport;
use Excel;

class BudgetLineReportController extends Controller
{
    protected $budget_line_arry;

    /**
     * BudgetLineReportController constructor.
     */
    public function __construct()
    {
        $this->budget_line_arry = [];
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                FunctionsHelper::storeDateFilter($request);

                $data = DB::table('expense_items')
                    ->join('expense_categories', 'expense_categories.id', 'expense_items.expense_category_id')
                    ->join('chart_of_account_items', 'chart_of_account_items.id', 'expense_categories.chart_of_account_item_id')
                    ->whereNull('expense_items.deleted_at')
                    ->whereBetween(DB::raw('DATE_FORMAT(expense_items.created_at, \'%Y-%m-%d\')'), array
                    ($request->start_date, $request->end_date))
                    ->select('expense_items.*', DB::raw('sum(price*qty) as product_price'),
                        DB::raw('sum(qty) as total_qty'), 'chart_of_account_items.name as budget_line')
                    ->groupBy('expense_categories.chart_of_account_item_id')
                    ->get();
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                })
                ->addColumn('total_qty', function ($row) {
                    return $row->total_qty . '<span class="text-primary"> (Items)</span>';
                })
                ->addColumn('product_price', function ($row) {
                    return number_format($row->product_price);
                })
                ->rawColumns(['total_qty'])
                ->make(true);
        }
        return view('reports.budget_line_report.index');
    }


    public function exportBudgetLIneReport(Request $request)
    {
        if ($request->session()->get('from') != '' && $request->session()->get('to') != '') {
            $queryBuilder = DB::table('expense_items')
                ->join('expense_categories', 'expense_categories.id', 'expense_items.expense_category_id')
                ->join('chart_of_account_items', 'chart_of_account_items.id', 'expense_categories.chart_of_account_item_id')
                ->whereNull('expense_items.deleted_at')
                ->whereBetween(DB::raw('DATE_FORMAT(expense_items.created_at, \'%Y-%m-%d\')'), array
                ($request->session()->get('from'),
                    $request->session()->get('to')))
                ->select('expense_items.*', DB::raw('sum(price*qty) as product_price'),
                    DB::raw('sum(qty) as total_qty'), 'chart_of_account_items.name as budget_line',
                    'chart_of_account_items.name as budget_line',
                    'expense_categories.chart_of_account_item_id')
                ->groupBy('expense_categories.chart_of_account_item_id')
                ->get();
        }


        $excel_file_name = "budget-lines-report-" . time();
        $sheet_title = "From " . date('d-m-Y', strtotime($request->session()->get('from'))) . " To " .
            date('d-m-Y', strtotime($request->session()->get('to')));

        Excel::create($excel_file_name, function ($excel) use ($queryBuilder, $sheet_title, $request) {

            $excel->sheet($sheet_title, function ($sheet) use ($queryBuilder) {
                $payload = [];
                $count_rows = 2;
                $grand_total = 0;
                $counter = 1;

                foreach ($queryBuilder as $row) {
                    $payload[] = array('ID' => $counter,
                        'Budget Line' => $row->budget_line,
                        'Total Items' => $row->total_qty,
                        'Amount' => $row->product_price
                    );
                    //check if not empty
                    if (!empty($this->budget_line_arry)) {
                        //check if the payment method already exists
                        $key_values = array_column($this->budget_line_arry, 'budget_line_id');
                        if (!in_array($row->chart_of_account_item_id, $key_values)) {
                            $this->budget_line_arry[] = array(
                                'budget_line_id' => $row->chart_of_account_item_id,
                                'budget_line' => $row->budget_line
                            );
                        }
                    } else {
                        //refresh adding of the data
                        $this->budget_line_arry[] = array(
                            'budget_line_id' => $row->chart_of_account_item_id,
                            'budget_line' => $row->budget_line
                        );
                    }
                    $count_rows++;
                    $counter++;
                    $grand_total = $grand_total + $row->product_price;
                }
                //general invoices totals
                $sheet->cell('D' . $count_rows, function ($cell) use ($grand_total) {
                    $cell->setValue('Total= ' . number_format($grand_total));
                    $cell->setFontWeight('bold');
                });
                $sheet->fromArray($payload);
            });
            //create new sheets for the payment methods used
            foreach ($this->budget_line_arry as $value) {


                //now re-query budget line items
                $BudgetLineQueryBuilder = DB::table('expense_items')
                    ->join('expense_categories', 'expense_categories.id', 'expense_items.expense_category_id')
                    ->whereNull('expense_items.deleted_at')
                    ->whereBetween(DB::raw('DATE_FORMAT(expense_items.created_at, \'%Y-%m-%d\')'), array
                    ($request->session()->get('from'),
                        $request->session()->get('to')))
                    ->where('expense_categories.chart_of_account_item_id', $value['budget_line_id'])
                    ->select('expense_items.*', 'expense_categories.name as product_name')
                    ->get();

                //create New sheet
                $excel->sheet($value['budget_line'], function ($sheet) use ($BudgetLineQueryBuilder, $value) {
                    $payload = [];
                    $count_rows = 2;
                    $grand_total = 0;
                    $counter = 1;
                    foreach ($BudgetLineQueryBuilder as $row) {
                        $payload[] = array('ID' => $counter,
                            'Purchase Date' => date('d-M-Y', strtotime($row->created_at)),
                            'Item' => $row->product_name,
                            'Description' => $row->description,
                            'Qty' => $row->qty,
                            'Price' => $row->price,
                            'Total Amount' => $row->qty * $row->price
                        );
                        $count_rows++;
                        $counter++;
                        $grand_total = $grand_total + ($row->qty * $row->price);
                    }
                    $sheet->cell('G' . $count_rows, function ($cell) use ($grand_total) {
                        $cell->setValue('Total= ' . number_format($grand_total));
                        $cell->setFontWeight('bold');
                    });
                    $sheet->fromArray($payload);
                });
            }
        })->download('xls');
    }
}
