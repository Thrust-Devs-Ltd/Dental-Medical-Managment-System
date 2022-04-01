@extends(\App\Http\Helper\FunctionsHelper::navigation())
@section('content')
@section('css')
    @include('layouts.page_loader')
@endsection
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <span class="caption-subject"> Receivables Report</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group">

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="btn-group pull-right">
                                <a href="{{ url('debtors-export') }}"
                                   class="text-danger">
                                    <i class="icon-cloud-download"></i>
                                    Download Excel
                                    Report </a>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="col-md-12">
                    <form action="#" class="form-horizontal hidden">
                        <div class="form-body">

                            <div class="row">
                                <div class="col-md-6">

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">payment Method</label>
                                        <div class="col-md-9">
                                            <select class="form-control" id="period_selector">
                                                <option>All</option>
                                                <option value="Cash">Insurance</option>
                                                <option value="Credit">Credit</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Start Date</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control start_date"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">End Date</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control end_date">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="button" id="customFilterBtn" class="btn purple-intense">Filter
                                                Invoices
                                            </button>
                                            <button type="button" class="btn default">Clear</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <br>

                <table class="table table-striped table-bordered table-hover table-checkable order-column"
                       id="payment-report">
                    <thead>
                    <tr>
                        <th>Invoice No</th>
                        <th>Invoice Date</th>
                        <th>First Name</th>
                        <th>Last name</th>
                        <th>Phone No:</th>
                        <th>Invoice Amount</th>
                        <th>Paid Amount</th>
                        <th>OutStanding Balance
                        </th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="loading">
    <i class="fa fa-refresh fa-spin fa-2x fa-fw"></i><br/>
    <span>Loading</span>
</div>
@endsection
@section('js')
    <script type="text/javascript">
        $(function () {
            $('#payment-report').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/debtors",
                    data: function (d) {
                    }
                },
                dom: 'Bfrtip',
                buttons: {
                    buttons: [
                        // {extend: 'pdfHtml5', className: 'pdfButton'},
                        // {extend: 'excelHtml5', className: 'excelButton'},

                    ]
                },
                columns: [
                    {
                        data: 'invoice_no',
                        name: 'invoice_no'
                    }, {
                        data: 'invoice_date',
                        name: 'invoice_date'
                    },
                    {
                        data: 'surname',
                        name: 'surname'
                    },
                    {
                        data: 'othername',
                        name: 'othername'
                    },
                    {
                        data: 'phone_no',
                        name: 'phone_no'
                    },
                    {
                        data: 'invoice_amount',
                        name: 'invoice_amount'
                    },
                    {
                        data: 'amount_paid',
                        name: 'amount_paid'
                    },
                    {
                        data: 'outstanding_balance',
                        name: 'outstanding_balance'
                    }
                ]
            });


        });
        $('#customFilterBtn').click(function () {
            $('#payment-report').DataTable().draw(true);
        });


    </script>
@endsection





