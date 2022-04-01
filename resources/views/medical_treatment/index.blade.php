@extends(\App\Http\Helper\FunctionsHelper::navigation())
@section('content')
@section('css')
    @include('layouts.page_loader')
@endsection

<link href="{{ asset('odontogram/css/estilosOdontograma.css') }}" rel="stylesheet" type="text/css"/>

<div class="note note-success">
    <div class="row">
        <div class="col-md-6">
            <p class="text-black-50"><a href="{{ url('appointments')}}" class="text-primary">View Appointments
                </a> / @if(isset($patient)) {{ $patient->surname." ".$patient->othername }} ({{ $patient->patient_no
                }}) @endif
            </p>
        </div>
        <div class="col-md-6">
            <div class="float-right">
                <form action="#" id="appointment-status-form" autocomplete="off">
                    @csrf
                    <select name="appointment_status">
                        <option value="null">select appointment action</option>
                        <option value="Treatment Complete">Treatment Complete</option>
                        <option value="Treatment Incomplete">Treatment Incomplete</option>
                    </select>
                    <input type="hidden" name="appointment_id" value="{{ $appointment_id }}">
                    <button type="button" class="btn-sm btn-primary" id="btn-appointment-status"
                            onclick="save_appointment_status();">save
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<input type="hidden" value="{{ $appointment_id }}" id="global_appointment_id">
<input type="hidden" value="@if(isset($patient)) {{ $patient->id }} @endif" id="global_patient_id">
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="tabbable-line">
                    <ul class="nav nav-tabs ">
                        <li class="active" id="dental_tab_link">
                            <a href="#dental_tab" data-toggle="tab"> Dental Treatment </a>
                        </li>

                        <li id="chronic_diseases_tab_link">
                            <a href="#chronic_diseases_tab" data-toggle="tab"> Medical History </a>
                        </li>
                        <li id="allergies_tab_link">
                            <a href="#allergies_tab" data-toggle="tab"> ALLERGIES </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="dental_tab">
                            <div class="tabbable tabbable-tabdrop">
                                <ul class="nav nav-pills">

                                    <li class="active" id="dental_charting_tab_link">
                                        <a href="#dental_charting_tab" data-toggle="tab" aria-expanded="true">Dental
                                            Charting</a>
                                    </li>
                                    <li class="" id="dental_notes_tab_link">
                                        <a href="#dental_notes_tab" data-toggle="tab" aria-expanded="false">Dental
                                            Notes</a>
                                    </li>
                                    <li class="" id="prescriptions_tab_link">
                                        <a href="#prescriptions_tab" data-toggle="tab" aria-expanded="false">Prescriptions
                                        </a>
                                    </li>
                                    <li class=" hidden" id="dental_billing_tab_link">
                                        <a href="#dental_billing_tab" data-toggle="tab" aria-expanded="false">Dental
                                            Billing</a>
                                    </li>


                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="dental_charting_tab">
                                        <div class="row">
                                            <div class="portlet light">
                                                <div class="portlet-title">

                                                </div>
                                                <div class="portlet-body">
                                                    <div ng-app="app">
                                                        <odontogramageneral></odontogramageneral>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane" id="dental_notes_tab">
                                        <div class="row">
                                            <div class="portlet light">
                                                <div class="portlet-title">

                                                    <a class="btn  blue btn-outline btn-circle btn-sm" href="#"
                                                       onclick="AddTreatment({{ $appointment_id  }})">
                                                        Add Clinical Notes
                                                    </a>
                                                </div>
                                                <div class="portlet-body">
                                                    <table class="table table-hover" id="dental_treatment_table">
                                                        <thead>
                                                        <tr>
                                                            <th> #</th>
                                                            <th>Created At</th>
                                                            <th>Clinical Notes</th>
                                                            <th>Treatment</th>
                                                            <th>Added By</th>
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
                                    </div>
                                    <div class="tab-pane" id="prescriptions_tab">
                                        <div class="row">
                                            <div class="portlet light">
                                                <div class="portlet-title">

                                                    <div class="caption">
                                                        <span class="caption-subject font-dark bold uppercase">Prescription</span>
                                                        &nbsp; &nbsp; &nbsp; <a
                                                                class="btn  blue btn-outline btn-circle btn-sm"
                                                                href="#"
                                                                onclick="AddPrescription({{ $appointment_id  }})">
                                                            Add Prescription
                                                        </a>
                                                    </div>
                                                    <div class="actions">
                                                        <div class="btn-group btn-group-devided">

                                                            <a href="{{ url('print-prescription/'.$appointment_id) }}"
                                                               class="btn grey-salsa btn-sm"
                                                               target="_blank"> <i
                                                                        class="fa fa-print"></i>Print Prescription</a>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <table class="table table-striped table-bordered table-hover table-checkable order-column"
                                                           id="prescriptions_table">
                                                        <thead>
                                                        <tr>
                                                            <th> #</th>
                                                            <th>Drug</th>
                                                            <th>Quantity</th>
                                                            <th>Directions</th>
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
                                    </div>
                                    <div class="tab-pane" id="dental_billing_tab">
                                        <div class="row">
                                            <div class="portlet light">
                                                <div class="portlet-title">

                                                    <a class="btn blue btn-outline btn-circle btn-sm" href="#"
                                                       onclick="AddInvoice({{ $appointment_id  }})">
                                                        Create Invoice
                                                    </a>
                                                </div>
                                                <div class="portlet-body">
                                                    <table class="table table-striped table-bordered table-hover table-checkable order-column"
                                                           id="dental_billing_table">
                                                        <thead>
                                                        <tr>
                                                            <th> #</th>
                                                            <th>Procedure</th>
                                                            <th>Tooth Numbers</th>
                                                            <th>Amount</th>
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
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="chronic_diseases_tab">

                            <div class="row">
                                <div class="portlet light">
                                    <div class="portlet-title">

                                        <a class="btn dark btn-outline btn-circle btn-sm" href="#"
                                           onclick="AddIllness(<?php if (isset($patient->id)) {
                                               /** @var TYPE_NAME $patient */
                                               echo $patient->id;
                                           } ?>)">
                                            Add Illness
                                        </a>
                                    </div>
                                    <div class="portlet-body">
                                        <table class="table table-striped table-bordered table-hover table-checkable order-column"
                                               id="chronic_diseases_table">
                                            <thead>
                                            <tr>
                                                <th> #</th>
                                                <th>Illness</th>
                                                <th>status</th>
                                                <th>Created At</th>
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


                        </div>
                        <div class="tab-pane" id="allergies_tab">

                            <div class="row">
                                <div class="portlet light">
                                    <div class="portlet-title">

                                        <a class="btn dark btn-outline btn-circle btn-sm" href="#"
                                           onclick="AddAllergy(<?php if (isset($patient->id)) {
                                               /** @var TYPE_NAME $patient */
                                               echo $patient->id;
                                           } ?>)">
                                            Add Allergies
                                        </a>
                                    </div>
                                    <div class="portlet-body">
                                        <table class="table table-striped table-bordered table-hover table-checkable order-column"
                                               id="allergies_table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Allergies</th>
                                                <th>Created At</th>
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
@include('medical_history.chronic_diseases.create')
@include('medical_history.allergies.create')

@include('medical_treatment.prescriptions.create')
@include('medical_treatment.prescriptions.edit')
{{--//dental treatment--}}
@include('medical_treatment.treatment.create')

{{--//dental invoicing--}}
@include('appointments.invoices.create')
@include('invoices.show.edit_invoice')

@endsection
@section('js')
    <script>
        let global_patient_id = $('#global_patient_id').val();
    </script>
    <script src="{{ asset('backend/assets/pages/scripts/page_loader.js') }}" type="text/javascript"></script>
    <script src="{{ asset('include_js/chronic_diseases.js') }}"></script>
    <script src="{{ asset('include_js/allergies.js') }}"></script>
    <script src="{{ asset('include_js/prescriptions.js') }}"></script>
    {{--    //dental treatment--}}
    <script src="{{ asset('include_js/treatment.js') }}"></script>

    {{--    //dental invoicing--}}
    <script src="{{ asset('include_js/invoicing.js') }}"></script>
    {{--dental charting plugins--}}
    <script src="{{ asset('odontogram/scripts/angular.js') }}"></script>
    <!-- Angular Modulos-->
    <script type="text/javascript" src="{{ asset('odontogram/scripts/modulos/app.js') }}"></script>
    <!-- Angular Controsideres-->
    <script type="text/javascript" src="{{ asset('odontogram/scripts/controladores/controller.js') }}"></script>

    <script type="text/javascript" src="{{ asset('odontogram/scripts/jquery-odontograma.js') }}"></script>
    <!--Angular Directives-->
    <script type="text/javascript" src="{{ asset('odontogram/scripts/directivas/canvasodontograma.js') }}"></script>
    <script type="text/javascript" src="{{ asset('odontogram/scripts/directivas/opcionescanvas.js') }}"></script>
    <script type="text/javascript" src="{{ asset('odontogram/scripts/directivas/odontogramaGeneral.js') }}"></script>

    <script type="text/javascript">
        //save appointment status
        function save_appointment_status() {
            swal({
                    title: "Are you sure,you want to save this action?",
                    // text: "This record was deleted before by the user",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn green-meadow",
                    confirmButtonText: "Yes, save !",
                    closeOnConfirm: false
                },
                function () {
                    $.LoadingOverlay("show");
                    $('#btn-appointment-status').attr('disabled', true);
                    $('#btn-appointment-status').text('processing...');
                    $.ajax({
                        type: 'POST',
                        data: $('#appointment-status-form').serialize(),
                        url: "/appointment-status",
                        success: function (data) {
                            $.LoadingOverlay("hide");
                            swal("Alert!", data.message, "success");
                            setTimeout(function () {
                                location.replace('/doctor-appointments');
                            }, 1900);
                        },
                        error: function (error) {
                            $.LoadingOverlay("hide");
                            $('#btn-appointment-status').attr('disabled', false);
                            $('#btn-appointment-status').text('save');
                        }
                    });
                });
        }


    </script>
@endsection





