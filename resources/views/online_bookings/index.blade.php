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
                    <span class="caption-subject"> Appointments / Online Bookings</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">

                    <form action="#" class="form-horizontal">
                        <div class="form-body">

                            <div class="row">
                                <div class="col-md-6">

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
                                                    class="btn purple-intense">Filter Bookings
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
                       id="bookings-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Booking Date</th>
                        <th>Patient</th>
                        <th>Phone No</th>
                        <th>Email</th>
                        <th>Preferred appointment Date</th>
                        <th>Preferred appointment Time</th>
                        <th>Is New Patient</th>
                        <th>Status</th>
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
@include('online_bookings.preview_booking')
@endsection
@section('js')

    <script src="{{ asset('backend/assets/pages/scripts/page_loader.js') }}" type="text/javascript"></script>
    <script src="{{ asset('include_js/DatesHelper.js') }}" type="text/javascript"></script>
    <script type="text/javascript">

        function default_todays_data() {
            // initially load today's date filtered data
            $('.start_date').val(formatDate(thisMonth()));
            $('.end_date').val(todaysDate());
            $("#period_selector").val('This Month');
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
            var table = $('#bookings-table').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/online-bookings/') }}",
                    data: function (d) {
                        d.start_date = $('.start_date').val();
                        d.end_date = $('.end_date').val();
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
                    {data: 'booking_date', name: 'booking_date'},
                    {data: 'full_name', name: 'full_name'},
                    {data: 'phone_no', name: 'phone_no'},
                    {data: 'email', name: 'email'},
                    {data: 'start_date', name: 'start_date'},
                    {data: 'start_time', name: 'start_time'},
                    {data: 'visit_history', name: 'visit_history'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });


        });

        $('#customFilterBtn').click(function () {
            $('#bookings-table').DataTable().draw(true);
        });


        //filter insurance companies
        $('#company').select2({
            placeholder: "Choose insurance company...",
            minimumInputLength: 2,
            ajax: {
                url: '/search-insurance-company',
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


        function ViewMessage(id) {
            $.LoadingOverlay("show");
            $('#id').val(''); ///always reset hidden form fields
            $('#btn-save').attr('disabled', false);
            $.ajax({
                type: 'get',
                url: "online-bookings/" + id,
                success: function (data) {
                    $('#id').val(id);
                    $('[name="full_name"]').val(data.full_name);
                    $('[name="phone_number"]').val(data.phone_no);
                    $('[name="email"]').val(data.email);
                    $('[name="appointment_date"]').val(data.start_date);
                    $('[name="appointment_time"]').val(data.start_time);
                    $('[name="visit_reason"]').val(data.message);
                    $('input[name^="visit_history"][value="' + data.visit_history + '"').prop('checked', true);
                    //check if the patient has medical isurance
                    if (data.insurance_company_id != null) {
                        let company_data = {
                            id: data.insurance_company_id,
                            text: data.name
                        };
                        let newOption = new Option(company_data.text, company_data.id, true, true);
                        $('#company').append(newOption).trigger('change');
                    } else {
                        $('#company').val([]).trigger('change');
                    }

                    //check if the booking has been accepts or rejected
                    if (data.status != "Waiting") {
                        $('.action_btns').hide();
                        $('.doctor_id_field').hide();

                    } else {
                        $('.action_btns').show();
                        $('.doctor_id_field').show();
                    }

                    $.LoadingOverlay("hide");
                    $('#booking-preview-modal').modal('show');

                },
                error: function (request, status, error) {
                    $.LoadingOverlay("hide");
                }
            });
        }

        function AcceptBooking() {
            if (confirm("Are you sure? You want to accept this booking!")) {
                $.LoadingOverlay("show");
                $('#acceptBtn').attr('disabled', true);
                $('#acceptBtn').text('Approving Booking...');
                $.ajax({
                    type: 'PUT',
                    data: $('#booking-preview-form').serialize(),
                    url: "/online-bookings/" + $('#id').val(),
                    success: function (data) {
                        $('#booking-preview-modal').modal('hide');
                        if (data.status) {
                            alert_dialog(data.message, "success");
                        } else {
                            alert_dialog(data.message, "danger");
                        }
                        $.LoadingOverlay("hide");
                    },
                    error: function (request, status, error) {
                        $.LoadingOverlay("hide");
                        $('#acceptBtn').attr('disabled', false);
                        $('#acceptBtn').text('Accept Booking');
                        json = $.parseJSON(request.responseText);
                        $.each(json.errors, function (key, value) {
                            $('.alert-danger').show();
                            $('.alert-danger').append('<p>' + value + '</p>');
                        });
                    }
                });
            }
        }

        function RejectBooking() {
            if (confirm("Are you sure? Your to reject this booking")) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.LoadingOverlay("show");

                $('#rejectBtn').attr('disabled', true);
                $('#rejectBtn').text('Processing...');
                $.ajax({
                    type: 'delete',
                    data: {
                        _token: CSRF_TOKEN
                    },
                    url: "online-bookings/" + $('#id').val(),
                    success: function (data) {
                        $.LoadingOverlay("hide");
                        $('#rejectBtn').attr('disabled', false);
                        $('#rejectBtn').text('Reject Booking');
                        $('#booking-preview-modal').modal('hide');
                        if (data.status) {
                            alert_dialog(data.message, "success");
                        } else {
                            alert_dialog(data.message, "danger");
                        }
                        $.LoadingOverlay("hide");
                    },
                    error: function (request, status, error) {
                        $.LoadingOverlay("hide");
                        $('#rejectBtn').attr('disabled', false);
                        $('#rejectBtn').text('Reject Booking');
                    }
                });
            }
        }


        function alert_dialog(message, status) {
            swal("Alert!", message, status);
            if (status) {
                let oTable = $('#bookings-table').dataTable();
                oTable.fnDraw(false);
            }
        }


    </script>
@endsection





