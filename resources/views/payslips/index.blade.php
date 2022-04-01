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
                    <span class="caption-subject"> Payroll Mgt/ Employee Payslips</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <a class="btn blue btn-outline sbold" href="#"
                                   onclick="createRecord()"> Add New <i
                                            class="fa fa-plus"></i> </a>
                            </div>
                        </div>
                    </div>
                </div>
                @if(session()->has('success'))
                    <div class="alert alert-info">
                        <button class="close" data-dismiss="alert"></button> {{ session()->get('success') }}!
                    </div>
                @endif
                <table class="table table-striped table-bordered table-hover table-checkable order-column"
                       id="payslips-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Employee</th>
                        <th>Month</th>
                        <th>Gross/Commission</th>
                        <th>Allowance</th>
                        <th>Deductions</th>
                        <th>Paid</th>
                        <th>Outstanding</th>
                        <th>Added By</th>
                        <th>Action</th>
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
@include('payslips.create')
@endsection
@section('js')

    <script src="{{ asset('backend/assets/pages/scripts/page_loader.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(function () {

            let table = $('#payslips-table').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/payslips/') }}",
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
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'employee', name: 'employee'},
                    {data: 'payslip_month', name: 'payslip_month'},
                    {data: 'basic_salary', name: 'basic_salary'},
                    {data: 'total_allowances', name: 'total_allowances'},
                    {data: 'total_deductions', name: 'total_deductions'},
                    {data: 'total_advances', name: 'total_advances'},
                    {data: 'due_balance', name: 'due_balance'},
                    {data: 'addedBy', name: 'addedBy'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });


        });

        function createRecord() {
            $("#scale-form")[0].reset();
            $('#id').val(''); ///always reset hidden form fields
            $('#btn-save').attr('disabled', false);
            $('#btn-save').text('Save changes');
            $('#scale-modal').modal('show');
        }

        $(document).on('click', '.remove-tr', function () {

            $(this).parents('tr').remove();

        });


        let i = 0;
        $("#add_allowance").click(function () {
            ++i;

            $("#AllowancesTable").append(
                '<tr>' +
                '<td>  <select class="form-control" name="addAllowance[' + i + '][allowance]">\n' +
                '                                        <option value="House Rent Allowance">House Rent Allowance</option>\n' +
                '                                        <option value="Medical Allowance">Medical Allowance</option>\n' +
                '                                        <option value="Bonus">Bonus</option>\n' +
                '                                        <option value="Dearness Allowance">Dearness Allowance</option>\n' +
                '                                        <option value="Travelling Allowance">Travelling Allowance</option>\n' +
                '                                        <option value="Overtime Allowance">Overtime Allowance</option>\n' +
                '                                    </select></td>' +
                '<td> <input type="number"  name="addAllowance[' + i + '][allowance_amount]" placeholder="Enter amount" class="form-control"/></td>' +
                '<td><button type="button" class="btn btn-danger remove-tr">Remove</button></td>' +
                '</tr>');
        });


        let x = 0;
        $("#add_deduction").click(function () {
            ++x;
            $("#DeductionsTable").append(
                '<tr>' +
                '<td>  <select class="form-control" name="addDeduction[' + x + '][deduction]">\n' +
                ' <option value="Loan">Loan</option>\n' +
                ' <option value="Tax">Tax</option>' +
                '</select></td>' +
                '<td> <input type="number"  name="addDeduction[' + x + '][deduction_amount]" placeholder="Enter amount" class="form-control"/></td>' +
                '<td><button type="button" class="btn btn-danger remove-tr">Remove</button></td>' +
                '</tr>');
        });


        //filter employee
        $('#employee').select2({
            placeholder: "Choose employee...",
            minimumInputLength: 2,
            ajax: {
                url: '/search-employee',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $(document).ready(function () {
            //first hide the allowances and deductions fields
            $('#AllowancesTable').hide();
            $('#DeductionsTable').hide();
            //now first handle allowances
            $("input[type=radio][name=allowances_include]").on("change", function () {
                let action = $("input[type=radio][name=allowances_include]:checked").val();

                if (action == "No") {
                    //now hide the view
                    $('#AllowancesTable').hide();
                } else {
                    //show allowances table fields
                    $('#AllowancesTable').show();
                }
            });

            //handle deductions
            $("input[type=radio][name=deductions_include]").on("change", function () {
                let action = $("input[type=radio][name=deductions_include]:checked").val();

                if (action == "No") {
                    //now hide the view
                    $('#DeductionsTable').hide();
                } else {
                    //show allowances table fields
                    $('#DeductionsTable').show();
                }
            });


        });


        function save_data() {
            //check save method
            var id = $('#id').val();
            if (id == "") {
                save_new_record();
            } else {
                update_record();
            }
        }

        function save_new_record() {
            $.LoadingOverlay("show");
            $('#btn-save').attr('disabled', true);
            $('#btn-save').text('processing...');
            $.ajax({
                type: 'POST',
                data: $('#scale-form').serialize(),
                url: "/payslips",
                success: function (data) {
                    $('#scale-modal').modal('hide');
                    $.LoadingOverlay("hide");

                    if (data.status) {
                        alert_dialog(data.message, "success");
                    } else {
                        alert_dialog(data.message, "danger");
                    }
                },
                error: function (request, status, error) {
                    $.LoadingOverlay("hide");
                    $('#btn-save').attr('disabled', false);
                    $('#btn-save').text('Save changes');
                    $('#scale-modal').modal('show');

                    json = $.parseJSON(request.responseText);
                    $.each(json.errors, function (key, value) {
                        $('.alert-danger').show();
                        $('.alert-danger').append('<p>' + value + '</p>');
                    });
                }
            });
        }

        function editRecord(id) {
            $.LoadingOverlay("show");
            $("#scale-form")[0].reset();
            $('#id').val(''); ///always reset hidden form fields
            $('#btn-save').attr('disabled', false);
            $.ajax({
                type: 'get',
                url: "payslips/" + id + "/edit",
                success: function (data) {
                    console.log(data);
                    $('#id').val(id);
                    $('[name="amount"]').val(data.advance_amount);
                    $('[name="advance_month"]').val(data.advance_month);
                    $('[name="payment_date"]').val(data.payment_date);
                    let employee_data = {
                        id: data.employee_id,
                        text: data.surname + " " + data.othername
                    };
                    let newOption = new Option(employee_data.text, employee_data.id, true, true);
                    $('#employee').append(newOption).trigger('change');
                    $.LoadingOverlay("hide");
                    $('#btn-save').text('Update Record')
                    $('#scale-modal').modal('show');

                },
                error: function (request, status, error) {
                    $.LoadingOverlay("hide");
                }
            });
        }

        function update_record() {
            $.LoadingOverlay("show");
            $('#btn-save').attr('disabled', true);
            $('#btn-save').text('Updating...');
            $.ajax({
                type: 'PUT',
                data: $('#scale-form').serialize(),
                url: "/payslips/" + $('#id').val(),
                success: function (data) {
                    $('#scale-modal').modal('hide');
                    if (data.status) {
                        alert_dialog(data.message, "success");
                    } else {
                        alert_dialog(data.message, "danger");
                    }
                    $.LoadingOverlay("hide");
                },
                error: function (request, status, error) {
                    $.LoadingOverlay("hide");
                    json = $.parseJSON(request.responseText);
                    $.each(json.errors, function (key, value) {
                        $('.alert-danger').show();
                        $('.alert-danger').append('<p>' + value + '</p>');
                    });
                }
            });
        }

        function deleteRecord(id) {
            swal({
                    title: "Are you sure?",
                    text: "Your will not be able to recover this payslip!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function () {

                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.LoadingOverlay("show");
                    $.ajax({
                        type: 'delete',
                        data: {
                            _token: CSRF_TOKEN
                        },
                        url: "/payslips/" + id,
                        success: function (data) {
                            if (data.status) {
                                alert_dialog(data.message, "success");
                            } else {
                                alert_dialog(data.message, "danger");
                            }
                            $.LoadingOverlay("hide");
                        },
                        error: function (request, status, error) {
                            $.LoadingOverlay("hide");

                        }
                    });

                });

        }


        function alert_dialog(message, status) {
            swal("Alert!", message, status);
            if (status) {
                let oTable = $('#payslips-table').dataTable();
                oTable.fnDraw(false);
            }
        }


    </script>
@endsection





