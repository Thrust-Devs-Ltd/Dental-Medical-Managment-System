@extends(\App\Http\Helper\FunctionsHelper::navigation())
@section('content')
@section('css')
    @include('layouts.page_loader')
@endsection
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="tabbable tabbable-tabdrop">
                    <ul class="nav nav-pills">

                        <li class="active" id="appointments_tab_link">
                            <a href="#appointments_tab" data-toggle="tab" aria-expanded="true">Appointments</a>
                        </li>
                        <li class="" id="appointment_calender_tab_link">
                            <a href="#appointment_calender_tab" data-toggle="tab" aria-expanded="false">Appointments
                                Calender
                            </a>
                        </li>


                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="appointments_tab">
                            <div class="row">
                                <div class="portlet light">
                                    <div class="portlet-title">
                                        <div class="caption font-dark">
                                            <span class="caption-subject"> Appointments Mgt</span>
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
                                                    <div class="btn-group pull-right">
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
                                                                <label class="control-label col-md-3">Appointment
                                                                    No</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control"
                                                                           placeholder="Enter appointment No"
                                                                           name="appointment_no"
                                                                           id="appointment_no_filter">
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
                                                                    <input type="text" class="form-control start_date">
                                                                </div>
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
                                                                    <button type="button" id="customFilterBtn"
                                                                            class="btn purple-intense">Filter
                                                                        Appointments
                                                                    </button>
                                                                    <button type="button" class="btn default">Clear
                                                                    </button>
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
                                               id="appointments-table">
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Appointment Date</th>
                                                <th>Appointment Time</th>
                                                <th>Patient</th>
                                                <th>Doctor</th>
                                                <th>Appointment Category</th>
                                                <th>Invoice status</th>
                                                <th>Action</th>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>


                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane" id="appointment_calender_tab">
                            <div class="row">
                                <div class="portlet light">
                                    <div class="portlet-title">
                                        <div class="caption font-dark">
                                            <span class="caption-subject"> Appointments mgt/ Calender</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        {!! $calendar->calendar() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<div class="loading">
    <i class="fa fa-refresh fa-spin fa-2x fa-fw"></i><br/>
    <span>Loading</span>
</div>
@include('appointments.create')
@include('appointments.invoices.create')
@include('appointments.reschedule_appointment')
@endsection
@section('js')

    <script src="{{ asset('backend/assets/pages/scripts/page_loader.js') }}" type="text/javascript"></script>
    <script src="{{ asset('include_js/DatesHelper.js') }}" type="text/javascript"></script>
    <script src="{{ asset('include_js/reschedule_appointment.js') }}" type="text/javascript"></script>
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


        let services_arry = [];
        $(function () {
            //hide appointment date time inputs
            $('.appointment_section').hide();

            default_todays_data();  //filter  date
            var table = $('#appointments-table').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/appointments/') }}",
                    data: function (d) {
                        d.start_date = $('.start_date').val();
                        d.end_date = $('.end_date').val();
                        d.appointment_no = $('#appointment_no_filter').val();
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
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'start_date', name: 'start_date'},
                    {data: 'start_time', name: 'start_time'},
                    {data: 'patient', name: 'patient'},
                    {data: 'doctor', name: 'doctor'},
                    {data: 'visit_information', name: 'visit_information'},
                    {data: 'invoice_status', name: 'invoice_status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });


        });

        $('#customFilterBtn').click(function () {
            $('#appointments-table').DataTable().draw(true);
        });

        function createRecord() {
            $("#appointment-form")[0].reset();
            $('#id').val(''); ///always reset hidden form fields
            $('#appointment-modal').modal('show');
            //still set the current time and date even on the walkin
            $('.appointment_date').val(todaysDate());
            $('#appointment_time').val(currentTimeSelect());

            //reset the patient information
            $('#patient').val([]).trigger('change');
            $("#patient").select2("val", "");

            $('#doctor').val([]).trigger('change');
            $("#doctor").select2("val", "");


            $('#btn-save').attr('disabled', false);
            $('#btn-save').text('Save changes');
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

        //filter doctor
        $('#doctor').select2({
            placeholder: "Choose doctor",
            minimumInputLength: 2,
            ajax: {
                url: '/search-doctor',
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

        //invoice filter doctors

        $('#doctor_id').select2({
            placeholder: "Procedure done by..",
            minimumInputLength: 2,
            ajax: {
                url: '/search-doctor',
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
                data: $('#appointment-form').serialize(),
                url: "/appointments",
                success: function (data) {
                    $('#appointment-modal').modal('hide');
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
                    $('#appointment-modal').modal('show');
                    json = $.parseJSON(request.responseText);
                    $.each(json.errors, function (key, value) {
                        $('.alert-danger').show();
                        $('.alert-danger').append('<p>' + value + '</p>');
                    });
                }
            });
        }

        function editRecord(id) {
            $("#appointment-form")[0].reset();
            $('#id').val(''); ///always reset hidden form fields
            $('#btn-save').attr('disabled', false);
            $('#btn-save').text('Save changes');
            $.LoadingOverlay("show");
            $.ajax({
                type: 'get',
                url: "appointments/" + id + "/edit",
                success: function (data) {
                    console.log(data);
                    $('#id').val(id);
                    let patient_data = {
                        id: data.patient_id,
                        text: data.surname + " " + data.othername
                    };
                    let newOption = new Option(patient_data.text, patient_data.id, true, true);
                    $('#patient').append(newOption).trigger('change');

                    let doctor_data = {
                        id: data.doctor_id,
                        text: data.d_surname + " " + data.d_othername
                    };
                    let newOption2 = new Option(doctor_data.text, doctor_data.id, true, true);
                    $('#doctor').append(newOption2).trigger('change');
                    $('input[name^="visit_information"][value="' + data.visit_information + '"').prop('checked', true);
                    $('[name="notes"]').val(data.notes);
                    $('#appointment_status').val(data.status);
                    $('.appointment_date').val(data.start_date);
                    $('#appointment_time').val(data.start_time);

                    $.LoadingOverlay("hide");
                    $('#btn-save').text('Update Record')
                    $('#appointment-modal').modal('show');

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
                data: $('#appointment-form').serialize(),
                url: "/appointments/" + $('#id').val(),
                success: function (data) {
                    $('#appointment-modal').modal('hide');
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
                    $('#btn-save').text('Save changes');
                    $('#appointment-modal').modal('show');
                    json = $.parseJSON(request.responseText);
                    $.each(json.errors, function (key, value) {
                        $('.alert-danger').show();
                        $('.alert-danger').append('<p>' + value + '</p>');
                    });
                }
            });
        }

        //reactivate treatment of the patient
        function ReactivateAppointment(id) {
            $("#appointment-form")[0].reset()
            $('#id').val(''); ///always reset hidden form fields
            $('#btn-save').attr('disabled', false);
            $('.modal-title').text("Re-Activate Appointment")
            $.LoadingOverlay("show");
            $.ajax({
                type: 'get',
                url: "appointments/" + id + "/edit",
                success: function (data) {
                    console.log(data);
                    $('#id').val(id);
                    let patient_data = {
                        id: data.patient_id,
                        text: data.surname + " " + data.othername
                    };
                    let newOption = new Option(patient_data.text, patient_data.id, true, true);
                    $('#patient').append(newOption).trigger('change');

                    let doctor_data = {
                        id: data.doctor_id,
                        text: data.d_surname + " " + data.d_othername
                    };
                    let newOption2 = new Option(doctor_data.text, doctor_data.id, true, true);
                    $('#doctor').append(newOption2).trigger('change');
                    $('input[name^="visit_information"][value="' + data.visit_information + '"').prop('checked', true);
                    $('[name="notes"]').val(data.notes);
                    $('#appointment_status').val(data.status);

                    // $('#visit_info_section').hide();
                    $('#reactivated_appointment').val("yes")
                    $.LoadingOverlay("hide");
                    $('#btn-save').text('Reactivate Appointment')
                    $('#appointment-modal').modal('show');

                },
                error: function (request, status, error) {
                    $.LoadingOverlay("hide");
                }
            });
        }


        function deleteRecord(id) {
            swal({
                    title: "Are you sure?",
                    text: "Your will not be able to recover this Appointment!",
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
                        url: "appointments/" + id,
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

        // system admin and receptionists Invoice generation section

        function RecordPayment(id) {
            $("#New-invoice-form")[0].reset();
            $('#btnSave').attr('disabled', false);
            $('#btnSave').text('Generate Invoice');

            $('#invoicing_appointment_id').val(id);
            $('#New-invoice-modal').modal('show');
        }

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
                $('#procedure_price').val(price);
                $('#procedure_qty').val(1);
                let amount = ($('#procedure_price').val().replace(/,/g, "")) * $('#procedure_qty').val();
                $('#total_amount').val(structureMoney("" + amount));
            } else {
                $('#procedure_price').val('');
                $('#procedure_qty').val('');
            }

        });


        //get all the services in an array
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


            //show appointment date and time section

            $("input[type=radio][name=visit_information]").on("change", function () {
                let action = $("input[type=radio][name=visit_information]:checked").val();

                if (action == "walk_in") {
                    //hide appointment date time inputs
                    $('.appointment_section').hide();
                } else {
                    $('.appointment_date').val(todaysDate());
                    $('#appointment_time').val(currentTimeSelect());

                    //show appointment date time inputs
                    $('.appointment_section').show();
                }

            });


        });


        let i = 0;
        $("#addInvoiceItem").click(function () {
            ++i;

            $("#InvoicesTable").append('<tr>' +
                '<td><select id="service_append' + i + '" name="addmore[' + i + '][medical_service_id]" class="form-control"\n' +
                '                                        style="width: 100%;border: 1px solid #a29e9e;"></select></td>' +
                '<td> <input type="text" name="addmore[' + i + '][tooth_no]" placeholder="Enter tooth number"\n' +
                '                                       class="form-control"/></td>' +
                '<td> <input type="number" onkeyup="QTYKeyChange(' + i + ')" id="procedure_qty' + i + '" name="addmore[' + i + '][qty]" placeholder="Enter qty"\n' +
                '                                       class="form-control"/></td>' +
                '<td> <input type="number" onkeyup="PriceKeyChange(' + i + ')"  id="procedure_price' + i + '" name="addmore[' + i + '][price]" placeholder="Enter unit price"\n' +
                '                                       class="form-control"/></td>' +
                '<td> <input type="text"  id="total_amount' + i + '"  class="form-control" readonly/></td>' +
                '<td><select id="doctor_id_append' + i + '" name="addmore[' + i + '][doctor_id]" class="form-control"\n' +
                '                                        style="width: 100%;border: 1px solid #a29e9e;"></select></td>' +
                '<td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>');

            //append procedure doctor
            $('#doctor_id_append' + i).select2({
                placeholder: "Procedure done by..",
                minimumInputLength: 2,
                ajax: {
                    url: '/search-doctor',
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
                    $('#procedure_qty' + i).val('')
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


        function save_invoice() {
            $.LoadingOverlay("show");
            $('#btnSave').attr('disabled', true);
            $('#btnSave').text('processing...');
            $.ajax({
                type: 'POST',
                data: $('#New-invoice-form').serialize(),
                url: "/invoices",
                success: function (data) {
                    $('#New-invoice-modal').modal('hide');
                    $.LoadingOverlay("hide");
                    if (data.status) {
                        alert_dialog(data.message, "success");
                    } else {
                        alert_dialog(data.message, "danger");
                    }
                },
                error: function (request, status, error) {
                    $.LoadingOverlay("hide");
                    $('#New-invoice-modal').modal('show');
                    $('#btnSave').attr('disabled', false);
                    $('#btnSave').text('Generate Invoice');

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

                setTimeout(function () {
                    location.reload();
                }, 1900);
                // let oTable = $('#appointments-table').dataTable();
                // oTable.fnDraw(false);
            }
        }

    </script>
    {{--load appointment calender script--}}
    {!! $calendar->script() !!}
@endsection





