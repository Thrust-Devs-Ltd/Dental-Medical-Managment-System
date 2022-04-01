<div class="modal fade" id="new-claim-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="renew_title text-primary"> New Salary Scale </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="new-claim-form" autocomplete="off">

                    @csrf
                    <input type="hidden" id="doctor_id" name="doctor_id">
                    <div class="form-group">
                        <label class="text-primary">Cash Rate(New %)</label>
                        <input type="number" name="cash_rate" placeholder="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Insurance Rate(New %)</label>
                        <input type="number" name="insurance_rate" placeholder="" class="form-control">
                    </div>
                </form>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" id="btn-save" onclick="save_new_rate()">Save changes
                </button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


