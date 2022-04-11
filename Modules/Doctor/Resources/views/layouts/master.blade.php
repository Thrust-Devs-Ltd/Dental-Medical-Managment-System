<!DOCTYPE html>

<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<head>
    <meta charset="utf-8"/>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('head')
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="clinic management system" name="description"/>
    <meta content="" name="author"/>
    <link href="{{ asset('backend/assets/global/plugins/font-awesome/css/fontawesome.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('backend/assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ asset('backend/assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('backend/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}"
          rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('backend/assets/pages/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>

    <link href="{{ asset('backend/assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet"
          type="text/css"/>

    <link href="{{ asset('backend/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}"
          rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('backend/assets/global/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('backend/assets/global/plugins/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('backend/assets/global/plugins/jqvmap/jqvmap/jqvmap.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('backend/assets/global/css/components.min.css') }}" rel="stylesheet" id="style_components"
          type="text/css"/>
    <link href="{{ asset('backend/assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('backend/assets/layouts/layout3/css/layout.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('backend/assets/layouts/layout3/css/themes/default.min.css') }}" rel="stylesheet"
          type="text/css"
          id="style_color"/>
    <link href="{{ asset('backend/assets/layouts/layout3/css/custom.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('backend/assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('backend/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ asset('backend/assets/pages/css/profile.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('backend/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ asset('backend/assets/global/plugins/bootstrap-toastr/toastr.min.css') }}" rel="stylesheet"
          type="text/css"/>

    <link href="{{ asset('backend/assets/global/css/bootstrap-datepicker.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('backend/assets/global/css/clockface.css') }}"
          rel="stylesheet" type="text/css"/>
    <link rel="stylesheet"
          href="{{ asset('backend/assets/global/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/global/css/jquery.fancybox.min.css') }}" media="screen">
    <link rel="shortcut icon" href="favicon.ico"/>
    @yield('css')
</head>

<body class="page-container-bg-solid">
<div class="page-wrapper">
    <div class="page-wrapper-row">
        <div class="page-wrapper-top">
            <!-- BEGIN HEADER -->
            <div class="page-header">
                <!-- BEGIN HEADER TOP -->
                <div class="page-header-top">
                    <div class="container">
                        <div class="page-logo">
                            <span>
                                <img src="{{ asset('images/logo.png') }}"
                                     style="height: 100px; margin: 16px 10px 0 0px;"
                                     alt="logo"
                                     class="logo-default">
                            </span>


                        </div>

                        <a href="javascript:;" class="menu-toggler"></a>
                        <div class="top-menu">
                            <ul class="nav navbar-nav pull-right">

                                <li class="dropdown dropdown-user dropdown-dark">
                                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"
                                       data-hover="dropdown" data-close-others="true">
                                        @if(\Illuminate\Support\Facades\Auth::User()->photo !=null)
                                            <img src="{{ asset('uploads/users/'.\Illuminate\Support\Facades\Auth::User()->photo) }}"
                                                 class="img-circle"
                                                 alt="">
                                        @else
                                            <img src="{{ asset('backend/assets/pages/media/profile/profile_user.jpg') }}"
                                                 class="img-circle"
                                                 alt="">
                                        @endif
                                        <span
                                                class="username username-hide-mobile">{{ Auth::User()->surname." ".Auth::User()->othername }}</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-default">
                                        <li>
                                            <a href="{{ url('profile') }}">
                                                <i class="icon-user"></i> My Profile </a>
                                        </li>

                                        @auth
                                            <li>
                                                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                                                    <i class="icon-key"></i> Log Out </a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                      style="display: none;">
                                                    {{ csrf_field() }}
                                                </form>
                                            </li>
                                        @endauth
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="page-header-menu">
                    <div class="container">

                        <div class="hor-menu  ">


                            {{--                             //navigation menu--}}


                            <ul class="nav navbar-nav">
                                <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown active">
                                    <a href="{{ url('home') }}"> Dashboard
                                        <span class="arrow"></span>
                                    </a>
                                </li>
                                <li aria-haspopup="true">
                                    <a href="{{ url('patients') }}"> Patients
                                        <span class="arrow"></span>
                                    </a>
                                </li>

                                <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                                    <a href="{{ url('appointments') }}"> Appointments
                                        <span class="arrow"></span>
                                    </a>
                                </li>
                                <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                                    <a href="javascript:;"> Invoicing
                                        <span class="arrow"></span>
                                    </a>
                                    <ul class="dropdown-menu pull-left">
                                        <li aria-haspopup="true" class=" ">
                                            <a href="{{ url('invoices') }}" class="nav-link  ">Invoices </a>
                                        </li>
                                        <li aria-haspopup="true" class=" ">
                                            <a href="{{ url('quotations') }}" class="nav-link  ">Quotations </a>
                                        </li>
                                    </ul>
                                </li>
                                {{-- <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                                    <a href="javascript:;"> Payroll Management
                                        <span class="arrow"></span>
                                    </a>
                                    <ul class="dropdown-menu pull-left">
                                        <li aria-haspopup="true" class=" ">
                                            <a href="{{ url('individual-payslips') }}" class="nav-link"> My PaySlips
                                            </a>
                                        </li> --}}
                                        {{--                                        <li aria-haspopup="true" class=" ">--}}
                                        {{--                                            <a href="{{ url('claims') }}" class="nav-link  ">My Claims </a>--}}
                                        {{--                                        </li>--}}
                                    {{-- </ul>
                                </li> --}}

                                <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                                    <a href="{{ url('leave-requests') }}"> Leave Requests
                                        <span class="arrow"></span>
                                    </a>
                                </li>

                            </ul>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">

                    <div class="page-head">
                        <div class="container">
                            <!-- BEGIN PAGE TITLE -->
                            <div class="page-title">
                                <h4><a href="{{ url('profile') }}">My Profile</a>/
                                    {{ Auth::User()->surname." ".Auth::User()->othername }} /
                                    [ {{  Auth::User()->UserRole->name }} ]
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="page-content">
                        <div class="container">
                            {{--                            <ul class="page-breadcrumb breadcrumb">--}}
                            {{--                                <li>--}}
                            {{--                                    <a href="#">Home</a>--}}
                            {{--                                    <i class="fa fa-circle"></i>--}}
                            {{--                                </li>--}}
                            {{--                                <li>--}}
                            {{--                                    <span>Dashboard</span>--}}
                            {{--                                </li>--}}
                            {{--                            </ul>--}}

                            @if(isset($breadcrum)) {!! $breadcrum !!} @endif

                            <div class="page-content-inner">
                                <div class="mt-content-body">
                                    {{--                                  //main content--}}
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="javascript:;" class="page-quick-sidebar-toggler">
                    <i class="icon-login"></i>
                </a>

            </div>
        </div>
    </div>
    <div class="page-wrapper-row">
        <div class="page-wrapper-bottom">
            <div class="page-prefooter">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12 footer-block hidden">

                        </div>
                        <div class="col-md-3 col-sm-6 col-xs12 footer-block hidden">

                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12 footer-block hidden">


                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12 footer-block hidden">

                        </div>
                    </div>
                </div>
            </div>
            <div class="page-footer">
                <div class="container"> 2020 &copy; Developed By
                    <a target="_blank" href="http://www.thrust-devs.com/">Powered By Thrust-devs</a> &nbsp;|&nbsp;
                </div>
            </div>
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
        </div>
    </div>
</div>

<div class="quick-nav-overlay"></div>
<script src="{{ asset('backend/assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('backend/assets/global/plugins/bootstrap/js/bootstrap.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('backend/assets/global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('backend/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('backend/assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('backend/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('backend/assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('backend/assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('backend/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('backend/assets/global/plugins/fullcalendar/fullcalendar.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('backend/assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('backend/assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('backend/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('backend/assets/layouts/layout3/scripts/layout.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('backend/assets/layouts/layout3/scripts/demo.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('backend/assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('backend/assets/layouts/global/scripts/quick-nav.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('backend/assets/global/plugins/clockface.js') }}"
        type="text/javascript"></script>
{{--<script src="{{ asset('backend/assets/pages/scripts/profile.min.js') }}" type="text/javascript"></script>--}}
<script src="{{ asset('backend/assets/global/plugins/bootstrap-toastr/toastr.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('backend/assets/pages/scripts/ui-toastr.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('backend/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}"
        type="text/javascript"></script>

<script src="{{ asset('backend/assets/global/plugins/bootstrap-datepicker.js') }}" type="text/javascript"></script>

<script src="{{ asset('backend/assets/pages/scripts/select2.min.js') }}" type="text/javascript"></script>

<script type="text/javascript"
        src="{{ asset('backend/assets/global/plugins/jquery.magnific-popup.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('backend/assets/pages/scripts/bootstrap3-typeahead.min.js') }}"></script>
{{-- dashboard staticts charts library--}}
<script src="{{ asset('backend/assets/global/scripts/Chart.min.js') }}" charset="utf-8"></script>

<script src="{{ asset('backend/assets/global/scripts/jquery.fancybox.min.js') }}"></script>
<script src="{{ asset('backend/assets/global/scripts/loadingoverlay.js') }}"></script>
<script src="{{ asset('backend/assets/global/scripts/loadingoverlay.min.js') }}"></script>
<script type="text/javascript">
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


    $('#datepicker2').datepicker({
        autoclose: true,
        todayHighlight: true,
    });

    $('.start_date').datepicker({
        autoclose: true,
        todayHighlight: true,
    });

    $('.end_date').datepicker({
        autoclose: true,
        todayHighlight: true,
    });


    function formatAMPM(date) {
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? 'pm' : 'am';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0' + minutes : minutes;
        var strTime = hours + ':' + minutes + '' + ampm;
        return strTime;
    }

    let time_plus_6 = new Date(new Date().getTime());
    // $('#start_time').timepicker();
    // $('#start_time').timepicker().on('changeTime.timepicker', function (e) {
    //     var hours = e.time.hours, //Returns an integer
    //         min = e.time.minutes,
    //         merdian = e.time.meridian;
    //     if (hours < 10) {
    //         $(e.currentTarget).val('0' + hours + ':' + min + ' ' + merdian);
    //     }
    // });

    // $('#start_time').timepicker('setTime', '12:45 AM');
    $('#start_time').clockface();

    $('#appointment_time').clockface();


    $('#monthsOnly').datepicker({
        autoclose: true,
        format: 'yyyy-mm',
        todayHighlight: true,
    });

</script>
@yield('js')
</body>


</html>
