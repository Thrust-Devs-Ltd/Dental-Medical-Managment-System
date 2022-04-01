<div class="modal fade" id="card-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Medical Cards Upload </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="{{ route('medical-cards.store') }}" method="post" id="card-form" autocomplete="off"
                      enctype="multipart/form-data">

                    @csrf
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label class="text-primary">Patient</label>
                        <select id="patient" name="patient_id" class="form-control" style="width: 100%;"></select>
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Image Category </label><br>
                        <input type="radio" name="card_type" value="X-ray"> X-ray
                        <input type="radio" name="card_type" value="Medical Card"> Medical Card
                    </div>
                    <div class="form-group">
                        <input type="file" id="uploadFile" name="uploadFile[]" multiple/>
                    </div>
                    <div id="image_preview"></div>
                    <br>
                    <input type="submit" id="btn-save" value="Save Changes" class="btn btn-primary">

                </form>

            </div>

        </div>
    </div>
</div>


