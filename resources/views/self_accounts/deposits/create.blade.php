<div class="modal fade" id="deposit-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Self Account Deposit Form</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="deposit-form" autocomplete="off">
                    @csrf
                    <input type="hidden" id="deposit_id" name="deposit_id">
                    <input type="hidden" id="self_account_id" name="self_account_id">
                    <div class="form-group">
                        <label class="text-primary">Payment Date </label>
                        <input type="text" name="payment_date" placeholder="yyy-mm-dd" id="datepicker"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Amount </label>
                        <input type="text" name="amount" placeholder="enter amount here" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Payment Method </label><br>
                        <input type="radio" name="payment_method" value="Cash">Cash<br>
                        <input type="radio" name="payment_method" value="Mobile Money">Mobile Money<br>
                        <input type="radio" name="payment_method" value="Cheque">Cheque<br>
                    </div>
                </form>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" id="btn-deposit" onclick="save_deposit()">Save
                    changes
                </button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


