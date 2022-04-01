@extends(\App\Http\Helper\FunctionsHelper::navigation())
@section('content')
@section('css')
    @include('layouts.page_loader')
@endsection

<style type="text/css">
    .gallery {
        display: inline-block;
        margin-top: 20px;
    }
</style>
<div class="note note-success">
    <p class="text-black-50"><a href="{{ url('doctor-appointments')}}" class="text-primary">View Appointments
        </a> / Medical History
        / @if(isset($patient)) {{ $patient->surname." ".$patient->othername  }}  @endif </p>
</div>
<input type="hidden" id="global_patient_id" value="{{ $patient->id }}">
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase">Treatment History</span>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover table-checkable order-column"
                       id="sample_10">
                    <thead>
                    <tr>
                        <th> #</th>
                        <th>Date</th>
                        <th>Clinical Notes</th>
                        <th>Treatment</th>
                        <th>Doctor</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase">Surgical History</span>
                    <a class="btn  blue btn-outline btn-circle btn-sm" href="#"
                       onclick="AddSurgery({{ $patient->id  }})">
                        Add Surgery
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover table-checkable order-column"
                       id="sample_2">
                    <thead>
                    <tr>
                        <th> #</th>
                        <th>surgery</th>
                        <th>surgery date</th>
                        <th>Notes</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-dark bold uppercase">Chronic diseases</span>
                    <a class="btn dark btn-outline btn-circle btn-sm" href="#"
                       onclick="AddIllness({{ $patient->id  }})">
                        Add Illness
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover table-checkable order-column"
                       id="sample_3">
                    <thead>
                    <tr>

                        <th> #</th>
                        <th>Illness</th>
                        <th>Status</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <!-- END BORDERED TABLE PORTLET-->
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase">Drug Allergies</span>
                    <a class="btn  blue btn-outline btn-circle btn-sm" href="#"
                       onclick="AddAllergy({{ $patient->id  }})">
                        Add Allergies
                    </a>
                </div>
            </div>
            <div class="portlet-body">

                <table class="table table-striped table-bordered table-hover table-checkable order-column"
                       id="sample_4">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Drug</th>
                        <th>Reaction</th>
                        <th>status</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-dark bold uppercase">Medical cards</span>
                </div>
                <div class="actions">
                    <div class="btn-group">

                    </div>
                </div>
            </div>
            <div class="portlet-body">

                <div class="row">
                    <div class='list-group gallery'>
                        @if($medical_cards->count())
                            @foreach($medical_cards as $image)
                                <div class='col-sm-4 col-xs-6 col-md-3 col-lg-6'>
                                    <a class="thumbnail fancybox" rel="ligthbox"
                                       href="/uploads/medical_cards/{{ $image->card_photo }}">
                                        <img class="img-responsive" alt=""
                                             src="/uploads/medical_cards/{{ $image->card_photo }}"/>
                                        <div class='text-center'>
                                            {{--                                            <small class='text-muted'>{{ $image->title }}</small>--}}
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        @endif
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
@include('medical_history.surgery.create')
@include('medical_history.chronic_diseases.create')
@include('medical_history.allergies.create')
@endsection
@section('js')
    <script src="{{ asset('backend/assets/pages/scripts/page_loader.js') }}" type="text/javascript"></script>
    <script src="{{ asset('include_js/treatment_history.js') }}"></script>
    <script src="{{ asset('include_js/surgery.js') }}"></script>
    <script src="{{ asset('include_js/chronic_diseases.js') }}"></script>
    <script src="{{ asset('include_js/allergies.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".fancybox").fancybox({
                openEffect: "none",
                closeEffect: "none"
            });
        });
    </script>
@endsection





