<div class="modal fade" id="edit-prescription-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Prescriptions Form </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="edit-prescription-form" autocomplete="off">

                    @csrf
                    <input type="hidden" id="prescription_id" name="prescription_id">
                    <div class="form-group">
                        <label class="text-primary">Drug </label>
                        <input type="text" name="drug" placeholder="Enter drug" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="text-primary">qty </label>
                        <input type="text" name="qty" placeholder="Enter qty" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Directions </label>
                        <textarea name="directions" class="form-control"></textarea>
                    </div>
                </form>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" id="btn-save" onclick="update_prescription_record()">Save changes</button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


