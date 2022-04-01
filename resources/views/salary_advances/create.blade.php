<div class="modal fade" id="scale-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Employee Salary payment Form </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="scale-form" autocomplete="off">

                    @csrf
                    <input type="hidden" id="id" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-primary">Employee </label>
                                <select id="employee" name="employee" class="form-control"
                                        style="width: 100%;"></select>
                            </div>
                            <div class="form-group">
                                <label class="text-primary">Payment Month</label>
                                <input type="text" name="advance_month" placeholder="yyyy-mm" id="monthsOnly"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="text-primary">Payment Classification</label><br>
                                <input type="radio" name="payment_classification" value="Salary"> Salary<br>
                                <input type="radio" name="payment_classification" value="Advance"> Advance<br>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-primary">Amount</label>
                                <input type="number" name="amount" placeholder="Enter amount" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="text-primary">payment Date</label>
                                <input type="text" name="payment_date" placeholder="yyyy-mm-dd" id="datepicker"
                                       class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="text-primary">Payment Method</label><br>
                                <input type="radio" name="payment_method" value="Cash"> Cash<br>
                                <input type="radio" name="payment_method" value="Bank Transfer"> Bank Transfer<br>
                                <input type="radio" name="payment_method" value="Cheque"> Cheque<br>
                                <input type="radio" name="payment_method" value="Mobile Money"> Mobile Money<br>
                                <input type="radio" name="payment_method" value="Online Wallet"> Online Wallet<br>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" id="btn-save" onclick="save_data()">Save changes</button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


