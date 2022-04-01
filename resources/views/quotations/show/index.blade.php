@extends(\App\Http\Helper\FunctionsHelper::navigation())
@section('content')
@section('css')
    @include('layouts.page_loader')
@endsection
<div class="note note-success">
    <p class="text-black-50">
        <a href="{{ url('quotations')}}" class="text-primary">Go Back to Quotations</a> /
        @if(isset($patient))  {{ $patient->surname." ".$patient->othername  }} @endif
    </p>
</div>
<input type="hidden" value="{{ $quotation_id }}" id="global_quotation_id">
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase">Quotation</span>
                    &nbsp; &nbsp; &nbsp

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
                        <div class="col-md-6">

                        </div>
                    </div>
                </div>
                <table class="table table-hover" id="quotation-items-table">
                    <thead>
                    <tr>
                        <th> #</th>
                        <th>Procedure</th>
                        <th>Tooth No</th>
                        <th>Qty</th>
                        <th>unit Price</th>
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
</div>


@include('quotations.show.edit_quotation')
@endsection
@section('js')
    <script src="{{ asset('backend/assets/pages/scripts/page_loader.js') }}" type="text/javascript"></script>

    <script>
        $(function () {
            let quotation_id = $('#global_quotation_id').val();

            var table = $('#quotation-items-table').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/quotation-items/" + quotation_id,
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
                    {data: 'service', name: 'service'},
                    {data: 'tooth_no', name: 'tooth_no'},
                    {data: 'qty', name: 'qty'},
                    {data: 'price', name: 'price'},
                    {data: 'total_amount', name: 'total_amount'},
                    {data: 'added_by', name: 'added_by'},
                    {data: 'editBtn', name: 'editBtn', orderable: false, searchable: false},
                    {data: 'deleteBtn', name: 'deleteBtn', orderable: false, searchable: false}
                ]
            });


        });

        function createRecord() {
            $("#quotation-form")[0].reset();
            $('#btn-save').attr('disabled', false);
            //global quotation id
            let id = $('#global_quotation_id').val();
            //set assign
            $('#quotation_id').val(id);
            $('#btn-save').text('save record');
            $('#quotation-modal').modal('show');
        }

        function editItem(id) {
            $('.loading').show();
            $("#quotation-form")[0].reset();
            $('#btn-save').attr('disabled', false);
            $.LoadingOverlay("show");
            $.ajax({
                type: 'get',
                url: "/quotation-items/" + id + "/edit",
                success: function (data) {
                    console.log(data);
                    $.LoadingOverlay("hide");
                    $('#id').val(id);
                    $('[name="qty"]').val(data.qty);
                    $('[name="price"]').val(data.price);
                    $('[name="total_amount"]').val(data.price * data.qty);

                    $('[name="tooth_no"]').val(data.tooth_no);

                    let service_data = {
                        id: data.medical_service_id,
                        text: data.name
                    };
                    let newOption2 = new Option(service_data.text, service_data.id, true, true);
                    $('#medical_service_id').append(newOption2).trigger('change');

                    $('.loading').hide();
                    $('#btn-save').text('Update Record')
                    $('#quotation-modal').modal('show');
                },
                error: function (request, status, error) {
                    $('.loading').hide();
                }
            });
        }


        $('#medical_service_id').select2({
            placeholder: "Select Procedure...",
            minimumInputLength: 2,
            ajax: {
                url: '/search-medical-service',
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
        }).on("select2:select", function (e) {
            let price = e.params.data.price;
            if (price != "" || price != 0) {
                $('#procedure_price').val(price);
                $('#procedure_qty').val(1);
                let amount = ($('#procedure_price').val().replace(/,/g, "")) * $('#procedure_qty').val();
                $('#total_amount').val(structureMoney("" + amount));
            } else {
                $('#procedure_price').val('');
                $('#procedure_qty').val('');
            }

        });


        $(document).ready(function () {

            $('#procedure_qty').on('keyup change', function () {
                if ($(this).val() && $('#procedure_price').val()) {
                    $('#total_amount').val(structureMoney("" + $(this).val() * ($('#procedure_price').val().replace(/,/g, ""))))
                    console.log($('#total_amount').val())
                } else if (!$(this).val()) {
                    $('#total_amount').val("")
                }

            });

            $('#procedure_price').on('keyup change', function () {
                if ($(this).val() && $('#procedure_qty').val()) {
                    $('#total_amount').val(structureMoney("" + ($(this).val().replace(/,/g, "")) * $('#procedure_qty').val()))
                } else if (!$(this).val()) {
                    $('#total_amount').val("")
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
            $('.loading').show();

            $('#btn-save').attr('disabled', true);
            $('#btn-save').text('Updating...');
            $.ajax({
                type: 'post',
                data: $('#quotation-form').serialize(),
                url: "/quotation-items/" + $('#id').val(),
                success: function (data) {
                    $('#quotation-modal').modal('hide');
                    if (data.status) {
                        alert_dialog(data.message, "success");
                    } else {
                        alert_dialog(data.message, "danger");
                    }
                    $('.loading').hide();
                },
                error: function (request, status, error) {
                    $('.loading').hide();
                    $('#btn-save').attr('disabled', false);
                    $('#btn-save').text('save changes');
                    $('#quotation-modal').modal('show');
                    json = $.parseJSON(request.responseText);
                    $.each(json.errors, function (key, value) {
                        $('.alert-danger').show();
                        $('.alert-danger').append('<p>' + value + '</p>');
                    });
                }
            });
        }

        function update_record() {
            $.LoadingOverlay("show");
            $('#btn-save').attr('disabled', true);
            $('#btn-save').text('Updating...');
            $.ajax({
                type: 'PUT',
                data: $('#quotation-form').serialize(),
                url: "/quotation-items/" + $('#id').val(),
                success: function (data) {
                    $('#quotation-modal').modal('hide');
                    if (data.status) {
                        alert_dialog(data.message, "success");
                    } else {
                        alert_dialog(data.message, "danger");
                    }
                    $.LoadingOverlay("hide");
                },
                error: function (request, status, error) {
                    $.LoadingOverlay("hide");
                    $('#btn-save').attr('disabled', false);
                    $('#btn-save').text('update record');

                    json = $.parseJSON(request.responseText);
                    $.each(json.errors, function (key, value) {
                        $('.alert-danger').show();
                        $('.alert-danger').append('<p>' + value + '</p>');
                    });
                }
            });
        }

        function deleteItem(id) {
            swal({
                    title: "Are you sure?",
                    text: "Your will not be able to recover this Quotation Item!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function () {

                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $('.loading').show();
                    $.ajax({
                        type: 'delete',
                        data: {
                            _token: CSRF_TOKEN
                        },
                        url: "/quotation-items/" + id,
                        success: function (data) {
                            if (data.status) {
                                alert_dialog(data.message, "success");
                            } else {
                                alert_dialog(data.message, "danger");
                            }
                            $('.loading').hide();
                        },
                        error: function (request, status, error) {
                            $('.loading').hide();

                        }
                    });

                });

        }

        function structureMoney(value) {
            return value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function alert_dialog(message, status) {
            swal("Alert!", message, status);

            swal("Alert!", message, status);
            if (status) {
                let oTable = $('#quotation-items-table').dataTable();
                oTable.fnDraw(false);
            }
        }


    </script>

@endsection





