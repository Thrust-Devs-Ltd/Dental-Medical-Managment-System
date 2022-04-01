@extends(\App\Http\Helper\FunctionsHelper::navigation())

@section('content')
    <div class="note note-success">
        <p class="text-black-50"><a href="{{ url('profile') }}" class="text-primary">My Profile</a>
            / {{ Auth::User()->surname." ".Auth::User()->othername }} <span class="text-primary">[ {{  Auth::User()->UserRole->name }}  ]</span>
        </p>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 blue" href="{{ url('appointments') }}">
                <div class="visual">
                    <i class="fa fa-comments"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup"
                              data-value="{{ $today_appointments }}">{{ $today_appointments }}</span>
                    </div>
                    <div class="desc"> Today's Appointments</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 yellow" href="{{ url('todays-cash') }}">
                <div class="visual">
                    <i class="fa fa-comments"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup"
                              data-value="{{ number_format($today_cash_amount) }}">{{ number_format($today_cash_amount) }}</span>
                    </div>
                    <div class="desc"> Today's Cash (Amount)</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 blue-chambray" href="{{ url('todays-insurance') }}">
                <div class="visual">
                    <i class="fa fa-bar-chart-o"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup"
                              data-value="{{ number_format($today_Insurance_amount) }}">{{ number_format($today_Insurance_amount)  }}</span>
                    </div>
                    <div class="desc"> Today's Insurance (Amount)</div>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 green-seagreen" href="{{ url('todays-expenses') }}">
                <div class="visual">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup"
                              data-value="{{ number_format($today_expense_amount) }}">{{ number_format($today_expense_amount) }}</span>
                    </div>
                    <div class="desc">Today's Expenses (Amount)</div>
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
                        <span class="caption-subject font-dark bold uppercase">Monthly Income Chart (Cash In)</span>
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body">
                    {!! $monthlyCashFlows->container() !!}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xs-12 col-sm-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-share font-red-sunglo hide"></i>
                        <span class="caption-subject font-dark bold uppercase">Monthly Expenses Chart (Cash out)</span>
                    </div>
                    <div class="actions">
                        <div class="btn-group">

                        </div>
                    </div>
                </div>
                <div class="portlet-body">
                    {!! $monthlyExpenses->container() !!}
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-6 col-xs-12 col-sm-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-bar-chart font-dark hide"></i>
                        <span
                            class="caption-subject font-dark bold uppercase">Monthly Overall Income Chart</span>
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body">
                    {!! $monthlyOverRollIncome->container() !!}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xs-12 col-sm-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-share font-red-sunglo hide"></i>
                        <span
                            class="caption-subject font-dark bold uppercase">Monthly Overall Chart (Income & Expenditure)</span>
                    </div>
                    <div class="actions">
                        <div class="btn-group">

                        </div>
                    </div>
                </div>
                <div class="portlet-body">
                    {!! $MonthlyOverRollIncomeExpense->container() !!}
                </div>
            </div>
        </div>
    </div>
    {!! $monthlyCashFlows->script() !!}
    {!! $monthlyExpenses->script() !!}
    {!! $monthlyOverRollIncome->script() !!}
    {!! $MonthlyOverRollIncomeExpense->script() !!}
@endsection

