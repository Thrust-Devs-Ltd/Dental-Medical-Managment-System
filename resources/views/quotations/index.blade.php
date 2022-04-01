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
                    <span class="caption-subject"> Billing /  Quotations</span>
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
                            <div class="btn-group pull-right hidden">
                                <a href="{{ url('export-appointments') }}" class="text-danger">
                                    <i class="icon-cloud-download"></i> Download Excel Report
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="col-md-12">
                    <form action="#" class="form-horizontal">
                        <div class="form-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Quotation No</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" placeholder="Enter Quotation No"
                                                   name="quotation_no" id="quotation_no">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Period</label>
                                        <div class="col-md-9">
                                            <select class="form-control" id="period_selector">
                                                <option>All</option>
                                                <option value="Today">Today</option>
                                                <option value="Yesterday">Yesterday</option>
                                                <option value="This week">This week</option>
                                                <option value="Last week">Last week</option>
                                                <option value="This Month">This Month</option>
                                                <option value="Last Month">Last Month</option>
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
                                                Quotations
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
                       id="quotations-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Quotation No</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Added By</th>
                        <th>Actions</th>
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
@include('quotations.create')
@include('quotations.share_quotation')
@endsection
@section('js')

    <script src="{{ asset('backend/assets/pages/scripts/page_loader.js') }}" type="text/javascript"></script>
    <script src="{{ asset('include_js/DatesHelper.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        function default_todays_data() {
            // initially load today's date filtered data
            $('.start_date').val(todaysDate());
            $('.end_date').val(todaysDate());
            $("#period_selector").val('Today');
        }

        $('#period_selector').on('change', function () {
            switch (this.value) {
                case'Today':
                    $('.start_date').val(todaysDate());
                    $('.end_date').val(todaysDate());
                    break;
                case'Yesterday':
                    $('.start_date').val(YesterdaysDate());
                    $('.end_date').val(YesterdaysDate());
                    break;
                case'This week':
                    $('.start_date').val(thisWeek());
                    $('.end_date').val(todaysDate());
                    break;
                case'Last week':
                    lastWeek();
                    break;
                case'This Month':
                    $('.start_date').val(formatDate(thisMonth()));
                    $('.end_date').val(todaysDate());
                    break;
                case'Last Month':
                    lastMonth();
                    break;
            }
        });

        $(function () {
            default_todays_data();  //filter  date
            var table = $('#quotations-table').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/quotations/') }}",
                    data: function (d) {
                        d.start_date = $('.start_date').val();
                        d.end_date = $('.end_date').val();
                        d.quotation_no = $('#quotation_no').val();
                        d.search = $('input[type="search"]').val();
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
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', 'visible': true},
                    {data: 'quotation_no', name: 'quotation_no', orderable: false},
                    {data: 'created_at', name: 'created_at', orderable: false},
                    {data: 'customer', name: 'customer', orderable: false},
                    {data: 'amount', name: 'amount', orderable: false},
                    {data: 'addedBy', name: 'addedBy', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });


        });
        $('#customFilterBtn').click(function () {
            $('#quotations-table').DataTable().draw(true);
        });

        function createRecord() {
            $("#quotation-form")[0].reset();
            $('#quotation-modal').modal('show');
            $('#btnSave').attr('disabled', false);
            $('#btnSave').text('Save changes');
        }


        //filter patients
        $('#patient').select2({
            placeholder: "Choose patient",
            minimumInputLength: 2,
            ajax: {
                url: '/search-patient',
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


        let i = 0;
        $("#addQuotationItem").click(function () {
            ++i;

            $("#QuotationItemsTable").append('<tr>' +
                '<td><select id="service_append' + i + '" name="addmore[' + i + '][medical_service_id]" class="form-control"\n' +
                '                                        style="width: 100%;border: 1px solid #a29e9e;"></select></td>' +
                '<td> <input type="text" name="addmore[' + i + '][tooth_no]" placeholder="Enter tooth no"\n' +
                '                                       class="form-control"/></td>' +
                '<td> <input type="number" onkeyup="QTYKeyChange(' + i + ')" id="procedure_qty' + i + '" name="addmore[' + i + '][qty]" placeholder="Enter qty"\n' +
                '                                       class="form-control"/></td>' +
                '<td> <input type="number"  onkeyup="PriceKeyChange(' + i + ')"  id="procedure_price' + i + '" name="addmore[' + i + '][price]" placeholder="Enter price"\n' +
                '                                       class="form-control"/></td>' +
                '<td> <input type="text" readonly id="total_amount' + i + '"  class="form-control"/></td>' +
                '<td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>');

            $('#service_append' + i).select2({
                placeholder: "select procedure",
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
                        // console.log(data);
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            }).on("select2:select", function (e) {
                let price = e.params.data.price;
                if (price != "" || price != 0) {
                    $('#procedure_price' + i).val(price);
                    $('#procedure_qty' + i).val(1);
                    let amount = ($('#procedure_price' + i).val().replace(/,/g, "")) * $('#procedure_qty' + i).val();
                    $('#total_amount' + i).val(structureMoney("" + amount));
                } else {
                    $('#procedure_price' + i).val('');
                    $('#procedure_qty' + i).val('');
                }

            });

        });
        $(document).on('click', '.remove-tr', function () {

            $(this).parents('tr').remove();

        });

        //filter Procedures
        $('#service').select2({
            placeholder: "Select Procedure",
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
                    // console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).on("select2:select", function (e) {
            let price = e.params.data.price;
            if (price != "" || price != 0) {
                $('#procedure_qty').val(1)
                $('#procedure_price').val(price);
                let amount = ($('#procedure_price').val().replace(/,/g, "")) * $('#procedure_qty').val();
                $('#total_amount').val(structureMoney("" + amount));
            } else {
                $('#procedure_price').val('');
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


        function QTYKeyChange(position) {
            if ($('#procedure_qty' + position).val() && $('#procedure_price' + position).val()) {
                $('#total_amount' + position).val(structureMoney("" + $('#procedure_qty' + position).val() * ($('#procedure_price' + position).val().replace(/,/g, ""))))
            } else if (!$('#procedure_qty' + position).val()) {
                $('#total_amount' + position).val("")
            }
        }

        function PriceKeyChange(position) {
            if ($('#procedure_price' + position).val() && $('#procedure_qty' + position).val()) {
                $('#total_amount' + position).val(structureMoney("" + $('#procedure_price' + position).val() * ($('#procedure_qty' + position).val().replace(/,/g, ""))))
            } else if (!$('#procedure_price' + position).val()) {
                $('#total_amount' + position).val("")
            }
        }


        function save_quotation() {
            $.LoadingOverlay("show");
            $('#btnSave').attr('disabled', true);
            $('#btnSave').text('processing...');
            $.ajax({
                type: 'POST',
                data: $('#quotation-form').serialize(),
                url: "/quotations",
                success: function (data) {
                    $('#quotation-modal').modal('hide');
                    $.LoadingOverlay("hide");
                    if (data.status) {
                        alert_dialog(data.message, "success");
                    } else {
                        alert_dialog(data.message, "danger");
                    }
                },
                error: function (request, status, error) {
                    $.LoadingOverlay("hide");
                    $('#quotation-modal').modal('show');
                    $('#btnSave').attr('disabled', false);
                    $('#btnSave').text('Generate quotation');

                    json = $.parseJSON(request.responseText);
                    $.each(json.errors, function (key, value) {
                        $('.alert-danger').show();
                        $('.alert-danger').append('<p>' + value + '</p>');
                    });
                }
            });
        }


        function shareQuotationView(quotation_id) {
            $.LoadingOverlay("show");
            $("#share-quotation-form")[0].reset();
            $('#btn-share').attr('disabled', false);
            $('#btn-share').text('Share quotation');
            $.ajax({
                type: 'GET',
                url: "/share-quotation-details/" + quotation_id,
                success: function (data) {
                    console.log(data)
                    $.LoadingOverlay("hide");
                    $('[name="quotation_id"]').val(data.id);
                    $('[name="quotation_no"]').val(data.quotation_no);
                    $('[name="name"]').val(data.surname + " " + data.othername);
                    $('[name="email"]').val(data.email);
                    $('#share-quotation-modal').modal('show');


                },
                error: function (xhr, status, error) {
                    alert(error);
                }
            });


        }

        function sendQuotation() {
            $.LoadingOverlay("show");
            $('#btn-share').attr('disabled', true);
            $('#btn-share').text('Processing....');
            $.ajax({
                type: 'POST',
                data: $('#share-quotation-form').serialize(),
                url: "/share-quotation",
                success: function (data) {
                    $.LoadingOverlay("hide");
                    $('#share-quotation-modal').modal('hide');
                    alert_dialog(data.message, "success");
                },
                error: function (xhr, status, error) {
                    $('#btn-share').attr('disabled', false);
                    $('#btn-share').text('Share quotation');
                    $('#share-quotation-modal').modal('show');
                    json = $.parseJSON(request.responseText);
                    $.each(json.errors, function (key, value) {
                        $('.alert-danger').show();
                        $('.alert-danger').append('<p>' + value + '</p>');
                    });
                }
            });

        }


        function structureMoney(value) {
            return value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function alert_dialog(message, status) {
            swal("Alert!", message, status);
            if (status) {
                let oTable = $('#quotations-table').dataTable();
                oTable.fnDraw(false);
            }
        }


    </script>
@endsection





