@extends(\App\Http\Helper\FunctionsHelper::navigation())
@section('content')
@section('css')
    @include('layouts.page_loader')
@endsection
<div class="note note-success">
    <p class="text-black-50"><a href="{{ url('self-accounts')}}" class="text-primary">View Self Accounts </a> /
        @if(isset($account_info))  {{ $account_info->account_holder." / ".$account_info->account_no  }} @endif
    </p>
</div>
<input type="hidden" value="@if(isset($account_info)) {{ $account_info->id }} @endif" id="global_self_account_id">
<div class="row">

    <div class="col-md-12">
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-dark bold uppercase">Self Account Deposits</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <a class="btn blue btn-outline sbold" href="#"
                                   onclick="AddDeposit()"> Add New <i
                                            class="fa fa-plus"></i> </a>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered table-hover table-checkable order-column"
                       id="self_account_deposits_table">
                    <thead>
                    <tr>

                        <th> #</th>
                        <th>Payment Date</th>
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
    <div class="col-md-12">
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-dark bold uppercase">Self Account Bill Payments</span>
                </div>
                <div class="actions">

                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover table-checkable order-column"
                       id="self_account_bills_table">
                    <thead>
                    <tr>

                        <th> #</th>
                        <th>Invoice No</th>
                        <th>patient</th>
                        <th>Payment Date</th>
                        <th>Amount</th>
                        <th>Added By</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@include('self_accounts.deposits.create')
@endsection
@section('js')
    <script src="{{ asset('backend/assets/pages/scripts/page_loader.js') }}" type="text/javascript"></script>

    <script src="{{ asset('include_js/self_account_deposits.js') }}"></script>
    <script src="{{ asset('include_js/self_account_bills.js') }}"></script>
@endsection





