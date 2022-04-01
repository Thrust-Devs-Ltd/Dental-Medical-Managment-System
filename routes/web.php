<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/book-appointment', 'OnlineBookingController@frontend');
Route::post('request-appointment', 'OnlineBookingController@store');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/home', 'HomeController@index')->name('home');

    // get the todays report
    Route::get('todays-cash', 'InvoicingReportsController@TodaysCash');
    Route::get('todays-insurance', 'InvoicingReportsController@TodaysInsurance');

    Route::get('todays-expenses', 'InvoicingReportsController@TodaysExpenses');
    Route::resource('roles', 'RoleController');
    Route::get('search-role', 'RoleController@filterRoles');
    Route::resource('users', 'UsersController');
    //current user profile
    Route::get('/profile', 'ProfileController@index');
    Route::post('update-bio', 'ProfileController@Update_Bio');
    Route::post('update-avatar', 'ProfileController@Update_Avatar');
    Route::post('update-password', 'ProfileController@ChangePassword');

    Route::get('search-doctor', 'UsersController@filterDoctor');
    Route::resource('insurance-companies', 'InsuranceCompaniesController');
    Route::get('search-insurance-company', 'InsuranceCompaniesController@filterCompanies');
    //self accounts
    Route::resource('self-accounts', 'SelfAccountController');

    Route::get('search-self-account', 'SelfAccountController@filterAccounts');

    Route::get('self-account-deposits/{self_account_id}', 'SelfAccountDepositController@index');
    Route::resource('self-account-deposits', 'SelfAccountDepositController');

    //self account bills/invoices
    Route::get('self-account-bills/{self_account_id}', 'SelfAccountBillPayment@index');


    Route::resource('patients', 'PatientController');
    Route::get('patients/{patientId}/medicalHistory', 'PatientController@patientMedicalHistory');

    Route::get('export-patients', 'PatientController@exportPatients');
    Route::get('search-patient', 'PatientController@filterPatients');
    Route::resource('appointments', 'AppointmentsController');
    Route::post('appointments-reschedule', 'AppointmentsController@SendReschedule');

    Route::get('export-appointments', 'AppointmentsController@exportAppointmentReport');

    Route::resource('online-bookings', 'OnlineBookingController');

    Route::get('medical-history/{id}', 'MedicalHistoryController@index');

    Route::get('surgeries/{id}', 'SurgeriesController@index');
    Route::resource('surgeries', 'SurgeriesController');

    Route::get('chronic-diseases/{id}', 'ChronicDiseasesController@index');
    Route::resource('chronic-diseases', 'ChronicDiseasesController');

    Route::get('allergies/{patient_id}', 'AllergyController@index');
    Route::resource('allergies', 'AllergyController');

    Route::resource('medical-cards', 'MedicalCardController');
    Route::resource('medical-cards-items', 'MedicalCardItemController');
    Route::get('individual-medical-cards/{id}', 'MedicalCardController@IndividualMedicalCards');

    Route::resource('clinic-services', 'MedicalServiceController');

    Route::get('search-medical-service', 'MedicalServiceController@filterServices');
    Route::get('services-array', 'MedicalServiceController@ServicesArray');

    Route::resource('invoices', 'InvoiceController');
    Route::get('export-invoices-report', 'InvoiceController@exportReport');
    Route::get('invoices-preview/{id}', 'InvoiceController@PreviewInvoice');
    Route::get('invoice-amount/{id}', 'InvoiceController@InvoiceAmount');
    Route::get('invoice-procedures/{invoice_id}','InvoiceController@InvoiceProceduresToJson');

    //share invoice on email
    Route::get('share-invoice-details/{id}', 'InvoiceController@invoiceShareDetails');
    Route::post('share-invoice', 'InvoiceController@SendInvoice');

    Route::get('payments/{id}', 'InvoicePaymentController@index');
    Route::resource('payments', 'InvoicePaymentController');
    Route::get('print-receipt/{id}', 'InvoiceController@printReceipt');

    Route::get('invoice-items/{id}', 'InvoiceItemController@index');
    Route::resource('invoice-items', 'InvoiceItemController');
    //doctor invoicing items
    Route::get('appointment-invoice-items/{id}', 'InvoiceItemController@AppointmentInvoiceItems');


    //quotations
    Route::resource('quotations', 'QuotationController');
    Route::get('quotation-items/{id}', 'QuotationItemController@index');
    Route::resource('quotation-items', 'QuotationItemController');
    Route::get('print-quotation/{id}', 'QuotationController@printQuotation');
    //sharing Quotation
    Route::get('share-quotation-details/{id}', 'QuotationController@QuotationShareDetails');
    Route::post('share-quotation', 'QuotationController@SendQuotation');


    //sent out billing email notifications
    Route::get('billing-notifications', 'BillingEmailNotificationController@index');


    //listing treatment and prescriptions capture
    Route::get('medical-treatment/{id}', 'MedicalTreatmentController@index');

    Route::get('treatments/{patient_id}', 'TreatmentController@index');
    Route::resource('treatments', 'TreatmentController');
    Route::get('treatments-history/{id}', 'TreatmentController@TreatmentHistory');

    Route::get('prescriptions/{id}', 'PrescriptionController@index');
    Route::resource('prescriptions', 'PrescriptionController');
    //filter existing drugs
    Route::get('filter-drugs', 'PrescriptionController@filterDrugs')->name('filter-drugs');
    Route::get('print-prescription/{id}', 'PrescriptionController@PrintPrescription');
    //expenses

    Route::resource('expense-categories', 'ExpenseCategoryController');
    Route::get('expense-categories-array', 'ExpenseCategoryController@filterExpenseCategories'); //populate expense
    // categories array

    Route::get('search-expense-category', 'ExpenseCategoryController@SearchCategory');
    Route::resource('expenses', 'ExpenseController');
    Route::get('export-expenses', 'ExpenseController@exportReport');
    Route::resource('suppliers', 'SupplierController');
    Route::get('filter-suppliers', 'SupplierController@filterSuppliers');

    Route::get('expense-items/{expense_id}', 'ExpenseItemController@index');
    Route::resource('expense-items', 'ExpenseItemController');

    Route::get('expense-payments/{expense_id}', 'ExpensePaymentController@index');
    Route::get('purchase-balance/{id}', 'ExpensePaymentController@Supplier_balance');
    Route::resource('expense-payments', 'ExpensePaymentController');

    //reports
    Route::get('invoice-payments-report', 'InvoicingReportsController@InvoicePaymentReport');
    Route::get('export-invoice-payments-report', 'InvoicingReportsController@ExportInvoicePayments');
    Route::get('insurance-reports', 'InsuranceReportsController@index');
    Route::get('insurance-claims', 'InsuranceReportsController@Claims');


    Route::get('doctor-performance-report', 'DoctorPerformanceReport@index');
    Route::get('download-performance-report', 'DoctorPerformanceReport@downloadPerformanceReport');

    Route::get('procedure-income-report', 'ProceduresReportController@index');
    Route::get('export-procedure-sales-report', 'ProceduresReportController@downloadProcedureSalesReport');

    Route::get('budget-line-report', 'BudgetLineReportController@index');
    Route::get('export-budget-line', 'BudgetLineReportController@ExportBudgetLineReport');

    //end reports


    Route::resource('dental-charting', 'DentalChartController');
    //payroll management
    Route::resource('employee-contracts', 'EmployeeContractController');
    Route::get('search-employee', 'UsersController@filterEmployees');
    Route::resource('salary-advances', 'SalaryAdvanceController');
    Route::resource('payslips', 'PaySlipController');
    Route::get('individual-payslips', 'PaySlipController@IndividualPaySlip');

    Route::get('allowances/{payslip_id}', 'SalaryAllowanceController@index');
    Route::resource('allowances', 'SalaryAllowanceController');

    Route::get('deductions/{payslip_id}', 'SalaryDeductionController@index');
    Route::resource('deductions', 'SalaryDeductionController');

    //doctor claims
    Route::resource('claim-rates', 'ClaimRateController');
    Route::resource('doctor-claims', 'DoctorClaimController');

    Route::get('claims-payment/{claim_id}', 'DoctorClaimPaymentController@index');
    Route::resource('claims-payment', 'DoctorClaimPaymentController');

    Route::resource('branches', 'BranchController');
    Route::get('search-branch', 'BranchController@filterBranches');

    //leave mgt
    Route::resource('holidays', 'HolidayController');
    Route::resource('leave-types', 'LeaveTypeController');
    Route::get('search-leave-type', 'LeaveTypeController@filter'); //search leave type
    Route::resource('leave-requests', 'LeaveRequestController');
    //leave approval
    Route::get('leave-requests-approval', 'LeaveRequestApprovalController@index');
    Route::get('approve-leave-request/{id}', 'LeaveRequestApprovalController@approveRequest');
    Route::get('reject-leave-request/{id}', 'LeaveRequestApprovalController@rejectRequest');

    //sms manager
    Route::resource('outbox-sms', 'SmsLoggingController');
    Route::get('export-sms-report', 'SmsLoggingController@exportReport');
    Route::resource('sms-transactions', 'SmsTransactionController');
    Route::resource('birthday-wishes', 'BirthDayMessageController');


    //accounting
    Route::get('charts-of-accounts', 'AccountingEquationController@index');
    Route::resource('charts-of-accounts-items', 'ChartOfAccountItemController');

    Route::resource('permissions', 'PermissionController');
    Route::resource('role-permissions', 'RolePermissionController');
    Route::get('/backup', function () {
        \Illuminate\Support\Facades\Artisan::call('backup:run');
        return 'Successful backup!';
    });

    //debtors report
    Route::get('/debtors', 'DebtorsReportController@index');
    Route::get('/debtors-export', 'DebtorsReportController@exportReport');

});
