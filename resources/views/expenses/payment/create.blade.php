<div class="modal fade" id="payment-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Record a payment for this Expense </h4>
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
                    <input type="hidden" id="expense_id" name="expense_id">
                    <div class="form-group">
                        <label class="text-primary">Payment Date </label>
                        <input type="text" placeholder="yyy-mm-dd" name="payment_date" id="datepicker2"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Payment Method </label><br>
                        <input type="radio" name="payment_method" value="Cash"> Cash<br>
                        <input type="radio" name="payment_method" value="Mobile Money"> Mobile Money<br>
                        <input type="radio" name="payment_method" value="Cheque"> Cheque<br>
                        <input type="radio" name="payment_method" value="Bank Wire Transfer"> Bank Wire Transfer<br>
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Amount </label>
                        <input type="number" name="amount" placeholder="Enter amount" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Payment Account </label><br>
                        <select class="form-control" name="payment_account">
                            <option value="">Choose payment account</option>
                            @foreach($payment_accts as $acct)
                                <option value="{{ $acct->id }}">{{ $acct->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" id="btnSave" onclick="save_payment_record()">Save
                    Record
                </button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


