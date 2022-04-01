<div class="modal fade" id="claims-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Doctor Claim Approval Form </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="claims-form" autocomplete="off">

                    @csrf
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label class="">Treatment Amount</label>
                        <input type="number" id="claim_amount" class="form-control" readonly placeholder="Enter amount"
                               name="claim_amount"/>
                    </div>
                    <div class="form-group">
                        <label class="">Insurance Amount</label>
                        <input type="number" id="insurance_amount" class="form-control" placeholder="Enter amount"
                               name="insurance_amount"/>
                    </div>

                    <div class="form-group">
                        <label class="">Cash Amount</label>
                        <input type="number" id="cash_amount" class="form-control" placeholder="Enter amount"
                               name="cash_amount"/>
                    </div>

                </form>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" id="btn-save" onclick="save_data()">save changes
                </button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


