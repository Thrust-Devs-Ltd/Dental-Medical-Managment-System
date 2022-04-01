<div class="modal fade" id="allowances-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Allowances Form </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="allowances-form" autocomplete="off">

                    @csrf
                    <input type="hidden" id="allowance_id" name="id">
                    <input type="hidden" id="allowance_pay_slip_id" name="pay_slip_id">
                    <div class="form-group">
                        <label class="text-primary">Allowance </label><br>
                        <input type="radio" name="allowance" value="House Rent Allowance"> House Rent Allowance<br>
                        <input type="radio" name="allowance" value="Medical Allowance"> Medical Allowance<br>
                        <input type="radio" name="allowance" value="Bonus"> Bonus<br>
                        <input type="radio" name="allowance" value="Dearness Allowance"> Dearness Allowance<br>
                        <input type="radio" name="allowance" value="Travelling Allowance"> Travelling Allowance<br>
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Amount </label>
                        <input type="number" name="amount" placeholder="enter amount here" class="form-control">
                    </div>

                </form>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" id="btn-allowance" onclick="record_allowances()">Save
                    Record
                </button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


