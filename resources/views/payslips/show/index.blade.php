@extends(\App\Http\Helper\FunctionsHelper::navigation())
@section('content')
@section('css')
    @include('layouts.page_loader')
@endsection
<div class="note note-success">
    <p class="text-black-50"><a href="{{ url('payslips')}}" class="text-primary">Go back to payslips </a>
        /@if(isset($employee)) {{ $employee->surname." ".$employee->othername }} / Payslip Month:
        {{ $employee->payslip_month }}  @endif
    </p>
</div>
<input type="hidden" value="{{ $pay_slip_id }}" id="global_pay_slip_id">
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase">Allowances</span>
                    &nbsp; &nbsp; &nbsp
                    <a class="btn blue btn-outline sbold " href="#"
                       onclick="Add_new_allowance()"> Add New <i
                                class="fa fa-plus"></i> </a>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-hover" id="allowances-table">
                    <thead>
                    <tr>
                        <th> #</th>
                        <th>Added At</th>
                        <th>Allowance</th>
                        <th>Amount</th>
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
                    <span class="caption-subject font-dark bold uppercase">Deductions</span>
                    &nbsp; &nbsp
                    <a class="btn blue btn-outline sbold " href="#"
                       onclick="Add_new_deduction()"> Add New <i
                                class="fa fa-plus"></i> </a>
                </div>
                <div class="actions">
                    <div class="btn-group btn-group-devided">


                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-hover"
                       id="deductions-table">
                    <thead>
                    <tr>

                        <th> #</th>
                        <th>Added At</th>
                        <th>Deduction</th>
                        <th>Amount</th>
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


@include('payslips.show.edit_allowances')
@include('payslips.show.edit_deductions')
@endsection
@section('js')
    <script src="{{ asset('backend/assets/pages/scripts/page_loader.js') }}" type="text/javascript"></script>

    <script src="{{ asset('include_js/allowances.js') }}"></script>
    <script src="{{ asset('include_js/deductions.js') }}"></script>
@endsection





