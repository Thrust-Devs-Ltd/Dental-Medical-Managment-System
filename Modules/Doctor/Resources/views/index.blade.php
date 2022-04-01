@extends(\App\Http\Helper\FunctionsHelper::navigation())

@section('content')

    <div class="note note-success">
        <p class="text-black-50"><a href="{{ url('profile') }}" class="text-primary">My Profile</a>
            / {{ Auth::User()->surname." ".Auth::User()->othername }} <span class="text-primary">[ {{  Auth::User()->UserRole->name }}  ]</span>
        </p>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 blue" href="#">
                <div class="visual">
                    <i class="fa fa-comments"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="{{ $appointments }}">{{ $appointments }}</span>
                    </div>
                    <div class="desc"> Today's Appointments</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 green" href="#">
                <div class="visual">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup"
                              data-value="{{ $pending_appointments }}">{{ $pending_appointments }}</span>
                    </div>
                    <div class="desc"> Pending Appointments</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 red" href="#">
                <div class="visual">
                    <i class="fa fa-bar-chart-o"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="{{ $new_patients }}">{{ $new_patients }}</span>
                    </div>
                    <div class="desc"> Today New Patients</div>
                </div>
            </a>
        </div>

    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-6 col-xs-12 col-sm-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-bar-chart font-dark hide"></i>
                        <span class="caption-subject font-dark bold uppercase">Monthly Appointments</span>
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body">
                    {!! $monthly_appointments->container() !!}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xs-12 col-sm-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-share font-red-sunglo hide"></i>
                        <span
                            class="caption-subject font-dark bold uppercase">Monthly Appointments Classification</span>

                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body">
                    {!! $monthly_appointments_classification->container() !!}
                </div>
            </div>
        </div>
    </div>

    {!! $monthly_appointments->script() !!}
    {!! $monthly_appointments_classification->script() !!}
@endsection
