<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<head>
    <meta charset="utf-8"/>
    <title>{{ config('app.name','laravel') }}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="Thrust Dental Systems " name="description"/>
    <meta content="" name="author"/>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('backend/assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('backend/assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}"
          rel="stylesheet" type="text/css"/>

    <link href="{{ asset('backend/assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('backend/assets/global/css/components.min.css')}}" rel="stylesheet" id="style_components"
          type="text/css"/>
    <link href="{{ asset('backend/assets/pages/css/login.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('backend/assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('backend/assets/global/css/bootstrap-datepicker.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('backend/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('backend/assets/global/css/intlTelInput.css') }}" media="screen">
    <link rel="shortcut icon" href="favicon.ico"/>
    <style>
        .login .content .form-title {
            color: #fff !important;
        }

        label {
            font-weight: 600;
            color: #e0e0e0;
            font-size: 18px;
            font-family: "Open Sans", sans-serif;
        }

        .radio_ {
            color: #fff;
            /*font-size: 18px;*/
            /*font-family: Roboto;*/
        }

        input {
            color: #000 !important;
        }

        textarea {
            color: #000 !important;
        }

        select {
            color: #000 !important;
        }

        .login {
            background-color: #000 !important;
            background-image: url({{asset('images/booking_bg.png')}});
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
        }

        .login .content {
            opacity: 0.9;
            background-color: #696666;
            -webkit-border-radius: 7px;
            -moz-border-radius: 7px;
            -ms-border-radius: 7px;
            -o-border-radius: 7px;
            border-radius: 7px;
            width: 920px;
            margin: 40px auto 10px;
            padding: 10px 30px 30px;
            overflow: hidden;
            position: relative;
        }

        .login .content .form-control {
            color: #676767;
        }

        textarea {
            min-height: 100px;
        }
    </style>
</head>

<body class=" login">

<div class="content">

    <form class="appointment-form" action="#">
        @csrf
        <h3 class="form-title font-green">APPOINTMENT BOOKING FORM</h3>
        <div class="alert alert-danger" style="display: none;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <div class="form-group" style="display: none;">
            <label for="faxonly">Fax Only
                <input type="checkbox" name="contact_me_by_fax_only" value="1" style="display:none !important"
                       tabindex="-1" autocomplete="off">
            </label>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Name <span>*</span></label>
                    <input type="text" class="form-control" name="full_name" placeholder="Full Name">
                </div>
                <div class="form-group">
                    <label>Phone Number <span>*</span></label><br>
                    <input type="text" id="telephone" name="telephone" class="form-control" style="width: 370px">
                    <input type="hidden" id="phone_number" name="phone_number" class="form-control">
                </div>
                <div class="form-group">
                    <label>Preferred appointment Date <span>*</span></label>
                    <input type="text" class="form-control" readonly id="datepicker" name="appointment_date"
                           placeholder="yyyy-mm-dd">
                </div>
                <div class="form-group">
                    <label>Preferred appointment Time <span>*</span></label>
                    <input type="text" class="form-control" id="appointment_time" name="appointment_time"
                           placeholder="Visit time">
                </div>

            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Email <span>*</span></label>
                    <input type="text" class="form-control" name="email"
                           placeholder="Email Address">
                </div>
                <div class="form-group">
                    <label>Have you ever visited {{ env('CompanyName',null)}} <span>*</span></label><br>
                    <input type="radio" name="visit_history" value="Yes"> <span class="radio_">Yes</span><br>
                    <input type="radio" name="visit_history" value="No"> <span class="radio_">No</span><br>
                </div>
                <div class="form-group">
                    <label>Do you have Medical Insurance <span class="text-danger">(Optional field)</span></label>
                    <select class="form-control" name="insurance_provider">
                        <option value="">select your insurance provider</option>
                        @foreach($insurance_providers as $provider)
                            <option value="{{ $provider->id }}">{{$provider->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Reason for visit <span>*</span></label>
                    <textarea class="form-control" name="visit_reason" rows="14"></textarea>
                </div>
                <button type="button" class="btn btn-primary" id="BookBtn" onclick="sendBooking()">Book Appointment
                </button>
            </div>
            <br>

        </div>
    </form>
</div>
<script src="{{ asset('backend/assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('backend/assets/global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('backend/assets/global/plugins/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('backend/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('backend/assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('backend/assets/global/scripts/loadingoverlay.js') }}"></script>
<script src="{{ asset('backend/assets/global/scripts/loadingoverlay.min.js') }}"></script>
<script src="{{ asset('backend/assets/global/scripts/intlTelInput.min.js') }}"></script>
<script>
    let input = document.querySelector("#telephone");
    window.intlTelInput(input, {
        preferredCountries: ["ug", "us"],
        autoPlaceholder: "off",
        utilsScript: "{{ asset('backend/assets/global/scripts/utils.js') }}",
    });
    var iti = window.intlTelInputGlobals.getInstance(input);

    function sendBooking() {
        $.LoadingOverlay("show");
        $('#BookBtn').attr('disabled', true);
        $('#BookBtn').text('processing...');
        //update the country code phone number
        let number = iti.getNumber();
        $('#phone_number').val(number);
        $.ajax({
            type: 'POST',
            data: $('.appointment-form').serialize(),
            url: "/request-appointment",
            success: function (data) {
                console.log(data)
                $.LoadingOverlay("hide");
                $(".appointment-form")[0].reset();
                $('#BookBtn').attr('disabled', false);
                $('#BookBtn').text('Book Appointment');

                swal("Alert!", data.message, "success");
            },
            error: function (request, status, error) {
                $.LoadingOverlay("hide");
                $('#BookBtn').attr('disabled', false);
                $('#BookBtn').text('Book Appointment');
                json = $.parseJSON(request.responseText);
                $.each(json.errors, function (key, value) {
                    $('.alert-danger').show();
                    $('.alert-danger').append('<p>' + value + '</p>');
                });

                $(".alert-danger").fadeTo(2000, 500).slideUp(500, function () {
                    $(".alert-danger").slideUp(500);
                });

            }
        });
    }

    $(document).ready(function () {
        $.LoadingOverlay("show"); // Show full page LoadingOverlay

        $(window).load(function (e) {
            $.LoadingOverlay("hide"); // after page loading hide the progress bar
        });

    });
    $('#datepicker').datepicker({
        autoclose: true,
        todayHighlight: true,
    });
    $('#appointment_time').timepicker();
</script>


</body>
</html>
