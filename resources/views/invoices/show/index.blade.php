@extends(\App\Http\Helper\FunctionsHelper::navigation())
@section('content')
@section('css')
    @include('layouts.page_loader')
@endsection
<div class="note note-success">
    <p class="text-black-50">
        <a href="{{ url('invoices')}}" class="text-primary">Go Back to Invoices</a> /
        @if(isset($patient)) {{ $patient->surname." ".$patient->othername  }} @endif
    </p>
</div>
<input type="hidden" value="{{ $invoice_id }}" id="global_invoice_id">
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase">Invoice</span>
                    &nbsp; &nbsp; &nbsp

                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-hover" id="sample_2">
                    <thead>
                    <tr>
                        <th> #</th>
                        <th>Procedure</th>
                        <th>Tooth Numbers</th>
                        <th>Qty</th>
                        <th>Unit price</th>
                        <th>Total Amount</th>
                        <th>Procedure Doctor</th>
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
                    <span class="caption-subject font-dark bold uppercase">Receipts</span>
                </div>
                <div class="actions">
                    <div class="btn-group btn-group-devided">

                        <a href="{{ url('print-receipt/'.$invoice_id) }}" class="btn grey-salsa btn-sm"
                           target="_blank"> <i
                                    class="fa fa-print"></i>Print Receipt</a>
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover table-checkable order-column"
                       id="sample_3">
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
</div>


@include('invoices.show.edit_invoice')
@include('invoices.show.edit_receipt')
@endsection
@section('js')
    <script src="{{ asset('backend/assets/pages/scripts/page_loader.js') }}" type="text/javascript"></script>

    <script src="{{ asset('include_js/invoices.js') }}"></script>
    <script src="{{ asset('include_js/receipts.js') }}"></script>
@endsection





