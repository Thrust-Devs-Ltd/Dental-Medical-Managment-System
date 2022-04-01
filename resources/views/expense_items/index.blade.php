@extends(\App\Http\Helper\FunctionsHelper::navigation())
@section('content')
@section('css')
    @include('layouts.page_loader')
@endsection
<div class="note note-success">
    <p class="text-black-50"><a href="{{ url('expenses')}}" class="text-primary">View Expenses </a>
        /@if(isset($purchase_details)) {{ $purchase_details->supplier_name }} / Expense
        No: {{ $purchase_details->purchase_no }} Date:
        ({{ \Carbon\Carbon::parse($purchase_details->purchase_date)->format('d/m/Y')}}) @endif
    </p>
</div>
<input type="hidden" value="{{ $expense_id }}" id="global_expense_id">
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase">Expense Items</span>
                    &nbsp; &nbsp; &nbsp
                    <a class="btn blue btn-outline sbold " href="#"
                       onclick="Add_new_item()"> Add New <i
                                class="fa fa-plus"></i> </a>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-hover" id="expense_items_table">
                    <thead>
                    <tr>
                        <th> #</th>
                        <th>Item</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Unit price</th>
                        <th>Total Amount</th>
                        <th>Added By</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-dark bold uppercase">Expense Payments</span>
                </div>
                <div class="actions">
                    <div class="btn-group btn-group-devided">


                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-hover"
                       id="expense_payments_table">
                    <thead>
                    <tr>

                        <th> #</th>
                        <th>Payment Date</th>
                        <th>Payment Account</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Added By</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@include('expense_items.edit_item')
@include('expense_items.edit_payment')
@endsection
@section('js')
    <script src="{{ asset('backend/assets/pages/scripts/page_loader.js') }}" type="text/javascript"></script>

    <script src="{{ asset('include_js/expense_items.js') }}"></script>
    <script src="{{ asset('include_js/expense_payments.js') }}"></script>
@endsection





