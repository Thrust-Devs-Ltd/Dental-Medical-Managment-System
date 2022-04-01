<div class="modal fade" id="payment-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Record a payment for this invoice </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="payment-form" autocomplete="off">

                    @csrf
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="receipt_id" name="receipt_id">
                    <div class="form-group">
                        <label class="text-primary">Payment Date </label>
                        <input type="text" name="payment_date" id="datepicker" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Amount </label>
                        <input type="text" name="amount" placeholder="enter amount here" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Payment Method </label><br>
                        <input type="radio" name="payment_method" value="Cash"> Cash<br>
                        <input type="radio" name="payment_method" value="Insurance"> Insurance<br>
                        <input type="radio" name="payment_method" value="Online Wallet"> Online Wallet<br>
                        <input type="radio" name="payment_method" value="Mobile Money"> Mobile Money<br>
                        <input type="radio" name="payment_method" value="Cheque"> Cheque<br>
                        <input type="radio" name="payment_method" value="Self Account"> Self Account<br>
                    </div>
                    <div id="cheque_payment">
                        <div class="form-group">
                            <label class="text-primary">Cheque No </label>
                            <input type="text" name="cheque_no" id="cheque_no" placeholder="Enter cheque no here"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="text-primary">Account Name </label>
                            <input type="text" name="account_name" placeholder="Enter account name here"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="text-primary">Bank Name </label>
                            <input type="text" name="bank_name" placeholder="Enter bank name here"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="insurance_company">
                        <div class="form-group">
                            <label class="text-primary">Insurance </label>
                            <select id="company" name="insurance_company_id" class="form-control"
                                    style="width: 100%;"></select>
                        </div>
                    </div>


                </form>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" id="btnSave" onclick="update_payment_record()">Save
                    changes
                </button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


