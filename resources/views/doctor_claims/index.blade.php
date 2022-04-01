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
                    <span class="caption-subject"> Payroll Management /Claims</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6">

                        </div>
                    </div>
                </div>
                @if(session()->has('success'))
                    <div class="alert alert-info">
                        <button class="close" data-dismiss="alert"></button> {{ session()->get('success') }}!
                    </div>
                @endif
                <table class="table table-striped table-bordered table-hover table-checkable order-column"
                       id="sample_1">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Treatment Amount</th>
                        <th>Insurance Claim</th>
                        <th>cash Claim</th>
                        <th>Total Claim Amount</th>
                        <th>Payment Balance</th>
                        <th>status</th>
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
@include('doctor_claims.create')
@include('doctor_claims.payments.create')
@endsection
@section('js')

    <script src="{{ asset('backend/assets/pages/scripts/page_loader.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        let save = false;
        $(function () {

            let table = $('#sample_1').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/doctor-claims/') }}",
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
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', visible: true},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'patient', name: 'patient'},
                    {data: 'doctor', name: 'doctor'},
                    {data: 'claim_amount', name: 'claim_amount'},
                    {data: 'insurance_amount', name: 'insurance_amount'},
                    {data: 'cash_amount', name: 'cash_amount'},
                    {data: 'total_claim_amount', name: 'total_claim_amount'},
                    {data: 'payment_balance', name: 'payment_balance'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });


        });

        function Approve_Claim(id, claim_amount) {
            save = true;
            $("#claims-form")[0].reset();
            $('#id').val(''); ///always reset hidden form fields
            $('#btn-save').attr('disabled', false);
            $('#id').val(id);
            $('#claim_amount').val(claim_amount);
            $('#insurance_amount').val(0);
            $('#cash_amount').val(0);

            $('#btn-save').text('Save changes');
            $('#claims-modal').modal('show');
        }

        function save_data() {

            //check save method
            if (save) {
                //check the amount
                var insurance = $('#insurance_amount').val();
                var cash = $('#cash_amount').val();
                var amount = Number(insurance) + Number(cash);

                if (amount == $('#claim_amount').val()) {
                    save_new_record();
                } else {
                    $('#claims-modal').modal('hide');
                    swal('Alert message !', 'Total insurance & Cash Amounts are not matching with the treatment amount');
                }

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
                data: $('#claims-form').serialize(),
                url: "/doctor-claims",
                success: function (data) {
                    $('#claims-modal').modal('hide');
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
                    $('#rate-modal').modal('show');

                    json = $.parseJSON(request.responseText);
                    $.each(json.errors, function (key, value) {
                        $('.alert-danger').show();
                        $('.alert-danger').append('<p>' + value + '</p>');
                    });
                }
            });
        }


        function editRecord(id) {
            save = false;
           $.LoadingOverlay("show");
            $("#claims-form")[0].reset();
            $('#id').val(''); ///always reset hidden form fields
            $('#btn-save').attr('disabled', false);
            $.ajax({
                type: 'get',
                url: "doctor-claims/" + id + "/edit",
                success: function (data) {
                    console.log(data);
                    $('#id').val(id);
                    $('[name="insurance_amount"]').val(data.insurance_amount);
                    $('[name="cash_amount"]').val(data.cash_amount);
                    $('[name="claim_amount"]').val(data.claim_amount);
                   $.LoadingOverlay("hide");
                    $('#btn-save').text('Update Record')
                    $('#claims-modal').modal('show');

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
                data: $('#claims-form').serialize(),
                url: "/doctor-claims/" + $('#id').val(),
                success: function (data) {
                    $('#claims-modal').modal('hide');
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
                    text: "Your will not be able to recover this Claim!",
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
                        url: "/claims/" + id,
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


        function record_payment(id, amount) {
            $("#payment-form")[0].reset();
            $('#id').val(''); ///always reset hidden form fields
            $('#btn-save').attr('disabled', false);
            $('#claim_id').val(id);
            $('#amount').val(amount);
            $('#btn-save').text('Save changes');
            $('#payment-modal').modal('show');
        }

        function save_payment_record() {
           $.LoadingOverlay("show");
            $('#btn-save').attr('disabled', true);
            $('#btn-save').text('processing...');
            $.ajax({
                type: 'POST',
                data: $('#payment-form').serialize(),
                url: "/claims-payment",
                success: function (data) {
                    $('#payment-modal').modal('hide');
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
                    $('#payment-modal').modal('show');

                    json = $.parseJSON(request.responseText);
                    $.each(json.errors, function (key, value) {
                        $('.alert-danger').show();
                        $('.alert-danger').append('<p>' + value + '</p>');
                    });
                }
            });
        }

        function alert_dialog(message, status) {

            if (status) {
                let oTable = $('#sample_1').dataTable();
                oTable.fnDraw(false);
                swal("Alert!", message, status);
            }
        }
    </script>
@endsection





