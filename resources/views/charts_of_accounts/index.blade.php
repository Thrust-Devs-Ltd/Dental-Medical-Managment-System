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
                    <span class="caption-subject"> Chart Of Accounts</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <div class="btn-group pull-right">
                                <a class="btn blue btn-outline sbold" href="#"
                                   onclick="createRecord()"> Add New Account</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="tabbable-line col-md-12">
                        <ul class="nav nav-tabs ">
                            @if(isset($AccountingEquations))
                                @foreach($AccountingEquations as $row)

                                    <li class="@if($row->active_tab=="yes") active @endif">
                                        <a href="#{{ $row->id."_tab" }}" data-toggle="tab"> {{ $row->name }} </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                        <div class="tab-content">
                            @if(isset($AccountingEquations))
                                @foreach($AccountingEquations as $row)
                                    <div class="tab-pane @if($row->active_tab=="yes") active @endif"
                                         id="{{ $row->id."_tab" }}">
                                        @foreach($row->Categories as $cat)
                                            <div class="portlet ">
                                                <div class="portlet-body">
                                                    <div class="mt-element-list">
                                                        <div class="mt-list-head list-default ext-1 bg-grey">
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <div class="list-head-title-container">
                                                                        <h3 class="list-title">{{ $cat->name }}</h3>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-list-container list-default ext-1">
                                                            <ul>
                                                                @foreach($cat->Items as $item)
                                                                    <li class="mt-list-item done">
                                                                        <div class="list-icon-container">
                                                                            <a href="javascript:;">
                                                                                <i class="icon-check"></i>
                                                                            </a>
                                                                        </div>
                                                                        <div class="list-datetime">
                                                                            <a href="#"
                                                                               onclick="editRecord('{{ $item->id }}')">Edit</a>
                                                                        </div>
                                                                        <div class="list-item-content">
                                                                            <h3 class="uppercase">
                                                                                <a href="javascript:;">{{ $item->name }}</a>
                                                                            </h3>
                                                                            <p>{{ $item->description }}</p>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                                <a href="">Add New </a>

                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                @endforeach
                            @endif
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
@include('charts_of_accounts.create')
@endsection
@section('js')

    <script src="{{ asset('backend/assets/pages/scripts/page_loader.js') }}" type="text/javascript"></script>
    <script type="text/javascript">


        function createRecord() {
            $("#chart_of_accounts-form")[0].reset();
            $('#id').val(''); ///always reset hidden form fields
            $('#btn-save').attr('disabled', false);
            $('#btn-save').text('save record');
            $('#chart_of_accounts-modal').modal('show');
        }

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
                data: $('#chart_of_accounts-form').serialize(),
                url: "/charts-of-accounts-items",
                success: function (data) {
                    $('#chart_of_accounts-modal').modal('hide');
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
                    $('#btn-save').text('save record');
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
            $("#chart_of_accounts-form")[0].reset();
            $('#id').val(''); ///always reset hidden form fields
            $('#btn-save').attr('disabled', false);
            $.ajax({
                type: 'get',
                url: "charts-of-accounts-items/" + id + "/edit",
                success: function (data) {
                    console.log($(".account_type").val());
                    $('#id').val(id);
                    // you'll have to implement this
                    $('[name="name"]').val(data.name);
                    $('[name="description"]').val(data.description);

                    $(".account_type").find("option").each(function () {
                        if ($(this).val() == data.chart_of_account_category_id) {
                            $(this).prop("selected", "selected");
                        }
                    });

                    $.LoadingOverlay("hide");
                    $('#btn-save').text('Update Record')
                    $('#chart_of_accounts-modal').modal('show');

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
                data: $('#chart_of_accounts-form').serialize(),
                url: "/charts-of-accounts-items/" + $('#id').val(),
                success: function (data) {
                    $('#chart_of_accounts-modal').modal('hide');
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
                    $('#btn-save').text('Update record');
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
                    text: "Your will not be able to recover this Branch!",
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
                        url: "charts-of-accounts-items/" + id,
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
                setTimeout(function () {
                    location.reload();
                }, 1900);
            }
        }


    </script>
@endsection





