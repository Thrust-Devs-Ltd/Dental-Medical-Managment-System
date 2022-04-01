@extends(\App\Http\Helper\FunctionsHelper::navigation())
@section('content')
@section('css')
    @include('layouts.page_loader')
@endsection
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">

            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-4">
                                        <table>
                                            <tr>
                                                <td><span style="font-size: 18px;">Invoice No:24434344</span></td>
                                            </tr>
                                            <tr>
                                                <td><span style="font-size: 16px;">Customer: Namanya abert</span></td>
                                            </tr>
                                            <tr>
                                                <td>Phone No:</td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                            </tr>
                                        </table>

                                    </div>
                                    <div class="col-md-4">

                                    </div>
                                    <div class="col-md-4">
                                        <img class="text-right" src="{{ asset('images/logo.png') }}"
                                             style="height: 85px;width: 100px">
                                        <table>
                                            <tr>
                                                <td><span style="font-size: 18px;">{{ config('app.name', 'Laravel') }}</span></td>
                                            </tr>
                                            <tr>
                                                <td>{{env("CompanyAddress",null)}}</td>
                                            </tr>
                                            <tr>
                                                <td>Phone No: {{env("companyMobile",null)}} / {{env("companyMobileOther",null)}}</td>
                                            </tr>
                                            <tr>
                                                <td>Email: {{ env("companyOfficalEmail",null)}}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <br>

                                    <div class="col-md-12">

                                        <br> <br>
                                        <table class="table table-hover table-bordered">
                                            <thead>
                                            <tr>
                                                <td>#</td>
                                                <td>Procedure</td>
                                                <td>Price</td>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @php $no = 1;
                                             $total_amount=0;
                                            @endphp
                                            @foreach ($invoice_items as $detail)
                                                @php $total_amount+=$detail->amount; @endphp
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $detail->medical_service->name }}</td>
                                                    <td>{{ number_format($detail->amount) }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4">
                                            <table class="table table-hover table-bordered">
                                                <tr>
                                                    <td>Sub Total</td>
                                                    <td>:</td>
                                                    <td>{{ $total_amount}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Total</td>
                                                    <td>:</td>
                                                    <td>{{ $total_amount  }}</td>
                                                </tr>
                                            </table>
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
</div>
@endsection





