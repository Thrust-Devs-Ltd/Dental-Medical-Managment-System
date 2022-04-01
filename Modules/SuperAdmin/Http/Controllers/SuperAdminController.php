<?php

namespace Modules\SuperAdmin\Http\Controllers;

use App\Appointment;
use App\Charts\MonthlyCashFlows;
use App\Charts\MonthlyExpensesChart;
use App\Charts\MonthlyOverRollIncomeChart;
use App\Charts\MonthlyOverRollIncomeExpenseChart;
use App\ExpensePayment;
use App\InsuranceCompany;
use App\InvoicePayment;
use App\Notifications\UserRegistrationNotification;
use App\Patient;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use Notification;
class SuperAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {


//        $details = [
//
//            'greeting' => 'Hi Artisan',
//
//            'body' => 'This is my first notification from ItSolutionStuff.com',
//
//            'thanks' => 'Thank you for using ItSolutionStuff.com tuto!',
//
//            'actionText' => 'View My Site',
//
//            'actionURL' => url('/'),
//
//            'order_id' => 101
//
//        ];



//
//
//        $user = User::where('id', 10)->first();
//        return Notification::send($user, new UserRegistrationNotification($details));


        $data['today_appointments'] = Appointment::where('start_date', '=', date('Y-m-d'))->count();

        $data['today_cash_amount'] = InvoicePayment::where('payment_method', 'Cash')
            ->whereDate('payment_date', date('Y-m-d'))->sum('amount');


        $data['today_Insurance_amount'] = InvoicePayment::where('payment_method', 'Insurance')
            ->whereDate('payment_date', date('Y-m-d'))->sum('amount');

        $data['today_expense_amount'] = ExpensePayment::whereDate('payment_date', date('Y-m-d'))->sum('amount');
        $data['monthlyCashFlows'] = $this->MonthlyCashFlows();
        $data['monthlyExpenses'] = $this->MonthlyExpenses();
        $data['monthlyOverRollIncome'] = $this->MonthlyOverRollIncomeChart();
        $data['MonthlyOverRollIncomeExpense'] = $this->MonthlyOverRollIncomeExpense();
        return view('superadmin::index')->with($data);
    }


    private function MonthlyCashFlows()
    {
        //daily cash earnings
        $daily_cash = DB::table('invoice_payments')
            ->select(DB::raw('sum(amount) as cash_amount'), DB::raw('date(payment_date) as dates'))
            ->whereNull('deleted_at')
            ->where('payment_method', 'Cash')
            ->groupBy('dates')
            ->orderBy('dates', 'asc')
            ->get();

        $cashFlows_labels = [];
        $cashFlows_data = [];
        foreach ($daily_cash as $item) {
            $cashFlows_labels[] = $item->dates;
            $cashFlows_data[] = $item->cash_amount;
        }
        //daily insurance earnings
        $daily_insurance = DB::table('invoice_payments')
            ->select(DB::raw('sum(amount) as insurance_amount'), DB::raw('date(payment_date) as dates'))
            ->whereNull('deleted_at')
            ->where('payment_method', 'Insurance')
            ->groupBy('dates')
            ->orderBy('dates', 'asc')
            ->get();

        $InsuranceFlows_labels = [];
        $InsuranceFlows_data = [];
        foreach ($daily_insurance as $item) {
            $InsuranceFlows_labels[] = $item->dates;
            $InsuranceFlows_data[] = $item->insurance_amount;
        }

        // generate the graphs
        $monthlyCashFlows = new MonthlyCashFlows;
        //cash data
        $monthlyCashFlows->labels($cashFlows_labels);
        $monthlyCashFlows->dataset('Daily Cash Payments', 'line', $cashFlows_data)->options([
            'fill' => false
        ]);

        //insurance data
        $monthlyCashFlows->labels($InsuranceFlows_labels);
        $monthlyCashFlows->dataset('Daily Insurance Payments', 'line', $InsuranceFlows_data)->options([
//            'backgroundColor' => '#DBF2F2',
        ]);

        return $monthlyCashFlows;
    }

    private function MonthlyExpenses()
    {
        //daily cash earnings
        $daily_expenses = DB::table('expense_payments')
            ->select(DB::raw('sum(amount) as total_amount'), DB::raw('date(payment_date) as dates'))
            ->whereNull('deleted_at')
            ->groupBy('dates')
            ->orderBy('dates', 'asc')
            ->get();

        $labels = [];
        $expense_data = [];
        foreach ($daily_expenses as $item) {
            $labels[] = $item->dates;
            $expense_data[] = $item->total_amount;
        }
        // generate the graph
        $monthlyExpenses = new MonthlyExpensesChart;

        $monthlyExpenses->labels($labels);
        $monthlyExpenses->dataset('Daily Expenses', 'line', $expense_data)->options([
            'backgroundColor' => '#DBF2F2'
        ]);
        return $monthlyExpenses;
    }

    private function MonthlyOverRollIncomeChart()
    {
        $monthly_cash = DB::table('invoice_payments')
            ->whereNull('deleted_at')
            ->where('payment_method', 'Cash')
            ->whereRaw('MONTH(created_at) = ?', [date('m')])
            ->sum('amount');

        $monthly_insurance = DB::table('invoice_payments')
            ->select(DB::raw('sum(amount) as insurance_amount'))
            ->whereNull('deleted_at')
            ->where('payment_method', 'Insurance')
            ->whereRaw('MONTH(created_at) = ?', [date('m')])
            ->sum('amount');
        $monthlyOverRollIncomeChart = new MonthlyOverRollIncomeChart;

        $monthlyOverRollIncomeChart->labels(['Cash', 'Insurance']);
        $monthlyOverRollIncomeChart->dataset('Over Roll ', 'pie', [$monthly_cash, $monthly_insurance])->options([
            'backgroundColor' => ['#3598DC', '#78CC66']
        ]);

        return $monthlyOverRollIncomeChart;
    }


    private function MonthlyOverRollIncomeExpense()
    {
        $monthly_income = DB::table('invoice_payments')
            ->whereNull('deleted_at')
            ->whereRaw('MONTH(created_at) = ?', [date('m')])
            ->sum('amount');

        $monthly_expenses = DB::table('expense_payments')
            ->select(DB::raw('sum(amount) as insurance_amount'))
            ->whereNull('deleted_at')
            ->whereRaw('MONTH(created_at) = ?', [date('m')])
            ->sum('amount');
        $monthlyOverRollIncomeExpenseChart = new MonthlyOverRollIncomeExpenseChart;

        $monthlyOverRollIncomeExpenseChart->labels(['Income', 'Expenditure']);
        $monthlyOverRollIncomeExpenseChart->dataset('Over Roll ', 'doughnut', [$monthly_income, $monthly_expenses])->options([
            'backgroundColor' => ['rgba(233, 180, 195, 0.86)', 'rgba(215, 201, 15, 0.73)']
        ]);

        return $monthlyOverRollIncomeExpenseChart;
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('superadmin::create');
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
        return view('superadmin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('superadmin::edit');
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

