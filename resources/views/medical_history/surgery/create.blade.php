<div class="modal fade" id="surgery-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Surgery Form </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="surgery-form" autocomplete="off">

                    @csrf
                    <input type="hidden" id="surgery_id" name="surgery_id">
                    <input type="hidden" id="patient_id" name="patient_id">
                    <div class="form-group">
                        <label class="text-primary">Surgery </label>
                        <input type="text" name="surgery" placeholder="Enter Surgery" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="text-primary">Surgery Date </label>
                        <input type="text" id="datepicker" placeholder="yyyy-mm-dd" name="surgery_date" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="text-primary">Notes (optional) </label>
                        <textarea class="form-control" name="description" rows="8"></textarea>
                    </div>
                </form>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" id="btn-surgery" onclick="save_surgery()">Save
                    changes
                </button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


