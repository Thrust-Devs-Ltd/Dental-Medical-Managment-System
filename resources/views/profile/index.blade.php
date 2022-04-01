@extends(\App\Http\Helper\FunctionsHelper::navigation())
@section('content')
@section('css')
    @include('layouts.page_loader')
@endsection
<div class="row">
    <div class="col-md-12">
        <div class="profile-sidebar">
            <div class="portlet light profile-sidebar-portlet bordered">
                <div class="profile-userpic">
                    @if(\Illuminate\Support\Facades\Auth::User()->photo !=null)
                        <img src="{{ asset('uploads/users/'.\Illuminate\Support\Facades\Auth::User()->photo) }}"
                             class="img-responsive" style="height: 250px !important; width: 250px !important;"
                             alt="">
                    @else
                        <img src="{{ asset('backend/assets/pages/media/profile/profile_user.jpg') }}"
                             class="img-responsive"
                             alt="">
                    @endif

                </div>
                <div class="profile-usertitle">
                    <div class="profile-usertitle-name"> {{ $user->surname." ".$user->othername }} </div>
                    <div class="profile-usertitle-job"> Profile</div>
                </div>

                <div class="profile-usermenu">
                    <ul class="nav">
                        <li class="active">
                            <a href="#"> Account Settings </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="profile-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-title tabbable-line">
                            <div class="caption caption-md">
                                <i class="icon-globe theme-font hide"></i>
                                <span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
                            </div>
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab_1_1" data-toggle="tab">Personal Info</a>
                                </li>
                                <li>
                                    <a href="#tab_1_2" data-toggle="tab">Change Avatar</a>
                                </li>
                                <li>
                                    <a href="#tab_1_3" data-toggle="tab">Change Password</a>
                                </li>
                            </ul>
                        </div>
                        <div class="portlet-body">
                            <div class="tab-content">
                                <div class="alert alert-danger" style="display:none">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="tab-pane active" id="tab_1_1">

                                    <form role="form" action="#" id="bio_data">
                                        @csrf
                                        <div class="form-group">
                                            <label class="control-label">First Name</label>
                                            <input type="text" placeholder="Enter first name" class="form-control"
                                                   name="surname" value="{{ $user->surname }}"/>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Last Name</label>
                                            <input type="text" name="othername" placeholder="Enter last name"
                                                   value="{{ $user->othername }}" class="form-control"/>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Email</label>
                                            <input type="text" name="email" placeholder="Enter Email address"
                                                   class="form-control" value="{{ $user->email }}"/>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Mobile Number</label>
                                            <input type="text" name="phone_number" placeholder="Enter phone number"
                                                   class="form-control" value="{{ $user->phone_no }}"/>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Alternative Number</label>
                                            <input type="text" name="alternative_no" placeholder="Enter phone number"
                                                   class="form-control" value="{{ $user->alternative_no }}"/>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">National Id</label>
                                            <input type="text" name="national_id" placeholder="Enter national Id"
                                                   class="form-control" value="{{ $user->nin }}"/>
                                        </div>
                                        <div class="margiv-top-10">
                                            <a href="#" onclick="Update_Biodata();" class="btn green"> Save Changes </a>
                                            <a href="javascript:;" class="btn default"> Cancel </a>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="tab_1_2">
                                    <p>if you want to change the current profile photo,Please attach a new photo and
                                        click submit
                                    </p>
                                    <form action="{{ url('update-avatar') }}" method="post" role="form" id=""
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail"
                                                     style="width: 200px; height: 150px;">
                                                    <img
                                                            src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"
                                                            alt=""/>
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail"
                                                     style="max-width: 200px; max-height: 150px;"></div>
                                                <div>
                                       <span class="btn default btn-file">
                                       <span class="fileinput-new"> Select image </span>
                                       <span class="fileinput-exists"> Change </span>
                                       <input type="file" name="avatar"> </span>
                                                    <a href="javascript:;" class="btn default fileinput-exists"
                                                       data-dismiss="fileinput"> Remove </a>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="margin-top-10">
                                            <input type="submit" value="Upload profile" class="btn btn-primary">
                                            {{--                                            <a href="#" class="btn green"> upload image </a>--}}
                                            <a href="javascript:;" class="btn default"> Cancel </a>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="tab_1_3">
                                    <div class="alert alert-danger" style="display:none">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <form action="#" id="passwords_form">
                                        @csrf
                                        <div class="form-group">
                                            <label class="control-label">Current Password</label>
                                            <input type="password" name="old_password"
                                                   placeholder="Enter current password" class="form-control"/>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">New Password</label>
                                            <input type="password" name="new_password" placeholder="Enter new password"
                                                   class="form-control"/>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Re-type New Password</label>
                                            <input type="password" name="confirm_password"
                                                   placeholder="Re-type New password" class="form-control"/>
                                        </div>
                                        <div class="margin-top-10">
                                            <a href="#" onclick="Change_Password();" class="btn green"> Change
                                                Password </a>
                                            <a href="javascript:;" class="btn default"> Cancel </a>
                                        </div>
                                    </form>
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
@endsection
@section('js')
    <script src="{{ asset('backend/assets/pages/scripts/page_loader.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        //update the bio data of the user
        function Update_Biodata() {
            if (confirm("Are you sure want to save the changes?")) {

                $.LoadingOverlay("show");
                $.ajax({
                    type: 'POST',
                    data: $('#bio_data').serialize(),
                    url: "update-bio",
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
                        json = $.parseJSON(request.responseText);
                        $.each(json.errors, function (key, value) {
                            $('.alert-danger').show();
                            $('.alert-danger').append('<p>' + value + '</p>');
                        });
                    }
                });
            }
        }

        //update profile picture
        function Update_Avatar() {
            if (confirm("Are you sure want to save the changes?")) {

                $.LoadingOverlay("show");
                $.ajax({
                    type: 'POST',
                    data: $('#avatar_form').serialize(),
                    url: "update-avatar",
                    success: function (data) {
                        if (data.status) {
                            alert_dialog(data.message, "success");
                        } else {
                            alert_dialog(data.message, "success");
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
        }

        //update password
        function Change_Password() {
            if (confirm("Are you sure want to save the changes?")) {

                $.LoadingOverlay("show");
                $.ajax({
                    type: 'POST',
                    data: $('#passwords_form').serialize(),
                    url: "update-password",
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
                        json = $.parseJSON(request.responseText);
                        $.each(json.errors, function (key, value) {
                            $('.alert-danger').show();
                            $('.alert-danger').append('<p>' + value + '</p>');
                        });
                    }
                });
            }
        }

        //general alert dialog
        function alert_dialog(message, status) {
            toastr[status](message);
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "7000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
            setTimeout(function () {
                location.reload();
            }, 1500);
        }
    </script>
@endsection
