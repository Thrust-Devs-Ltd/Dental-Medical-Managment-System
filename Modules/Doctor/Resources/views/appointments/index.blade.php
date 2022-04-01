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
                                            <span class="caption-subject"> Appointments Mgt/ Doctor Appointments</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="table-toolbar">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="btn-group">
{{--                                                        <a class="btn blue btn-outline sbold" href="#"--}}
{{--                                                           onclick="createRecord()"> Add New <i--}}
{{--                                                                    class="fa fa-plus"></i> </a>--}}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="btn-group pull-right">
{{--                                                        <a href="{{ url('export-appointments') }}" class="text-danger">--}}
{{--                                                            <i class="icon-cloud-download"></i> Download Excel Report--}}
{{--                                                        </a>--}}
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
                                               id="doctor-appointments-table">
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Appointment No</th>
                                                <th>Appointment Date</th>
                                                <th>Appointment Time</th>
                                                <th>Patient</th>
                                                <th>Treatment</th>
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
@include('doctor::appointments.create_claim')
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

            var table = $('#doctor-appointments-table').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/doctor-appointments/') }}",
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
                    {data: 'appointment_no', name: 'appointment_no'},
                    {data: 'start_date', name: 'start_date'},
                    {data: 'start_time', name: 'start_time'},
                    {data: 'patient', name: 'patient'},
                    {data: 'treatment', name: 'treatment'}
                ]
            });
        });
        $('#customFilterBtn').click(function () {
            $('#doctor-appointments-table').DataTable().draw(true);
        });

        function CreateClaim(appointment_id) {
            $("#claims-form")[0].reset();
            $('#appointment_id').val(appointment_id);
            $('#claims-modal').modal('show');
        }


        function save_data() {
            $.LoadingOverlay("show");
            $('#btnSave').attr('disabled', true);
            $('#btnSave').text('processing...');
            $.ajax({
                type: 'POST',
                data: $('#claims-form').serialize(),
                url: "/claims",
                success: function (data) {
                    $('#claims-modal').modal('hide');
                    $.LoadingOverlay("hide");
                    if (data.status) {
                        alert_dialog(data.message, "success");
                    } else {
                        swal("Warning!", data.message);
                    }
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


        function alert_dialog(message, status) {

            if (status) {
                let oTable = $('#doctor-appointments-table').dataTable();
                oTable.fnDraw(false);
                swal("Alert!", message, status);
            }
        }


    </script>

    {{--load appointment calender script--}}
    {!! $calendar->script() !!}
@endsection





