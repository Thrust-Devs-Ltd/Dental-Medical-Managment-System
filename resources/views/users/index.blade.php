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
                    <span class="caption-subject"> User mgt/ Users</span>
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
                       id="users_table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Surname</th>
                        <th>Othername</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Role</th>
                        <th>Branch</th>
                        <th>Is Doctor</th>
                        <th>Edit</th>
                        <th>Delete</th>
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
@include('users.create')
@endsection
@section('js')

    <script src="{{ asset('backend/assets/pages/scripts/page_loader.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function (e) {
            $('#branch_block').hide();
        });
        $(function () {

            var table = $('#users_table').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/users/') }}",
                    data: function (d) {
                        // d.email = $('.searchEmail').val()
                        d.search = $('input[type="search"]').val()
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
                    {data: 'surname', name: 'surname'},
                    {data: 'othername', name: 'othername'},
                    {data: 'email', name: 'email', 'visible': true},
                    {data: 'phone_no', name: 'phone_no'},
                    {data: 'user_role', name: 'user_role'},
                    {data: 'branch', name: 'branch'},
                    {data: 'is_doctor', name: 'is_doctor'},
                    {data: 'editBtn', name: 'editBtn', orderable: false, searchable: false},
                    {data: 'deleteBtn', name: 'deleteBtn', orderable: false, searchable: false}
                ]
            });


        });

        function createRecord() {
            $("#users-form")[0].reset();
            $('#id').val(''); ///always reset hidden form fields
            $('#btn-save').attr('disabled', false);
            $('#btn-save').text('save record');
            $('#users-modal').modal('show');
        }


        //filter roles
        $('#role').select2({
            placeholder: "Choose role...",
            minimumInputLength: 2,
            ajax: {
                url: '/search-role',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).on('change', function (e) {
            let selectedRole = $("#role option:selected").text();
            if (selectedRole == "Super Administrator") {
                $('#branch_block').hide();
            } else {
                $('#branch_block').show();
            }
        });


        //filter branches
        $('#branch_id').select2({
            placeholder: "Choose Branch...",
            minimumInputLength: 2,
            ajax: {
                url: '/search-branch',
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
                data: $('#users-form').serialize(),
                url: "/users",
                success: function (data) {
                    $('#users-modal').modal('hide');
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
            $("#users-form")[0].reset();
            $('#id').val(''); ///always reset hidden form fields
            $('#btn-save').attr('disabled', false);
            $.ajax({
                type: 'get',
                url: "users/" + id + "/edit",
                success: function (data) {
                    // console.log(data);
                    $('#id').val(id);
                    $('[name="surname"]').val(data.surname);
                    $('[name="othername"]').val(data.othername);
                    $('[name="email"]').val(data.email);
                    $('[name="phone_no"]').val(data.phone_no);
                    $('[name="alternative_no"]').val(data.alternative_no);
                    $('[name="nin"]').val(data.nin);
                    $('input[name^="is_doctor"][value="' + data.is_doctor + '"').prop('checked', true);
                    $('.password_config').hide();
                    let role_data = {
                        id: data.role_id,
                        text: data.user_role
                    };
                    let newOption = new Option(role_data.text, role_data.id, true, true);
                    $('#role').append(newOption).trigger('change');


                    let branch_data = {
                        id: data.branch_id,
                        text: data.branch
                    };

                    if (data.branch_id == null) {
                        $('#branch_id').val([]).trigger('change');
                    } else {
                        let branchOption = new Option(branch_data.text, branch_data.id, true, true);
                        $('#branch_id').append(branchOption).trigger('change');
                    }


                    $.LoadingOverlay("hide");
                    $('#btn-save').text('Update Record')
                    $('#users-modal').modal('show');

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
                data: $('#users-form').serialize(),
                url: "/users/" + $('#id').val(),
                success: function (data) {
                    $('#users-modal').modal('hide');
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
                    text: "Your will not be able to recover this User!",
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
                        url: "users/" + id,
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
                let oTable = $('#users_table').dataTable();
                oTable.fnDraw(false);
            }
        }


    </script>
@endsection





