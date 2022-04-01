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

    .close-icon {
        border-radius: 50%;
        position: absolute;
        right: 5px;
        top: -10px;
        padding: 5px 8px;
    }
</style>
<div class="note note-success">
    <p class="text-black-50"><a href="{{ url('/medical-cards')}}" class="text-primary">View All Medical Cards</a>
        </a> / @if(isset($patient)) {{ $patient->surname." ".$patient->othername }} ({{ $patient->patient_no }}) @endif
    </p>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <span class="caption-subject"> Medical History / Cards</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6">

                        </div>
                    </div>
                </div>
                @if(session()->has('success'))
                    <div class="alert alert-info">
                        <button class="close" data-dismiss="alert"></button> {{ session()->get('success') }}!
                    </div>
                @endif
                <div class="row">
                    <div class='list-group gallery'>
                        @if($images->count())
                            @foreach($images as $image)
                                <div class='col-sm-4 col-xs-6 col-md-3 col-lg-6'>
                                    <a class="thumbnail fancybox" rel="ligthbox"
                                       href="/uploads/medical_cards/{{ $image->card_photo }}">
                                        <img class="img-responsive" alt=""
                                             src="/uploads/medical_cards/{{ $image->card_photo }}"/>
                                        <div class='text-center'>
                                            {{--                                            <small class='text-muted'>{{ $image->title }}</small>--}}
                                        </div>
                                    </a>


                                    <a href="#" onclick="deleteRecord({{ $image->id }})"
                                       class="close-icon btn green-meadow">Delete Card
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
@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $(".fancybox").fancybox({
                openEffect: "none",
                closeEffect: "none"
            });
        });


        function deleteRecord(id) {
            swal({
                    title: "Are you sure?",
                    text: "Your will not be able to recover this card!",
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
                        url: "/medical-cards-items/" + id,
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

            setTimeout(function () {
                location.reload();
            }, 1900);
        }


    </script>
@endsection

