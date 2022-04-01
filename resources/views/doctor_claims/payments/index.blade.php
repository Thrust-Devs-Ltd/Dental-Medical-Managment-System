@extends(\App\Http\Helper\FunctionsHelper::navigation())
@section('content')
@section('css')
    @include('layouts.page_loader')
@endsection

<div class="note note-success">
    <p class="text-black-50"><a href="{{ url('doctor-claims')}}" class="text-primary">View Claims
        </a> / @if(isset($doctor)) {{ $doctor->surname." ".$doctor->othername }} ) @endif
    </p>
</div>

<input type="hidden" value="{{ $claim_id }}" id="global_claim_id">
<div class="row">

    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase">Claim /Payment details</span>
                    &nbsp; &nbsp; &nbsp;

                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-hover" id="sample_1">
                    <thead>
                    <tr>
                        <th> #</th>
                        <th>Payment Date</th>
                        <th>Amount</th>
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

<div class="loading">
    <i class="fa fa-refresh fa-spin fa-2x fa-fw"></i><br/>
    <span>Loading</span>
</div>
@include('doctor_claims.payments.create')
@endsection

@section('js')
    <script src="{{ asset('backend/assets/pages/scripts/page_loader.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(function () {

            let table = $('#sample_1').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/claims-payment/" + $('#global_claim_id').val(),
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
                    {data: 'payment_date', name: 'payment_date'},
                    {data: 'amount', name: 'amount'},
                    {data: 'editBtn', name: 'editBtn', orderable: false, searchable: false},
                    {data: 'deleteBtn', name: 'deleteBtn', orderable: false, searchable: false}
                ]
            });


        });


        function editRecord(id) {
            save = false;
           $.LoadingOverlay("show");
            $("#payment-form")[0].reset();
            $('#btn-save').attr('disabled', false);
            $.ajax({
                type: 'get',
                url: "/claims-payment/" + id + "/edit",
                success: function (data) {
                    console.log(data);
                    $('#claim_id').val(id);
                    $('[name="payment_date"]').val(data.payment_date);
                    $('[name="amount"]').val(data.amount);

                   $.LoadingOverlay("hide");
                    $('#btn-save').text('Update Record')
                    $('#payment-modal').modal('show');

                },
                error: function (request, status, error) {
                   $.LoadingOverlay("hide");
                }
            });
        }

        //update the payment info
        function save_payment_record() {
           $.LoadingOverlay("show");
            $('#btn-save').attr('disabled', true);
            $('#btn-save').text('Updating...');
            $.ajax({
                type: 'PUT',
                data: $('#payment-form').serialize(),
                url: "/claims-payment/" + $('#claim_id').val(),
                success: function (data) {
                    $('#payment-modal').modal('hide');
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
                    text: "Your will not be able to recover this Claim payment!",
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
                        url: "/claims-payment/" + id,
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

            if (status) {
                let oTable = $('#sample_1').dataTable();
                oTable.fnDraw(false);
                swal("Alert!", message, status);
            }
        }
    </script>

@endsection





