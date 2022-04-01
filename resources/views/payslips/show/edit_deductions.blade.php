<div class="modal fade" id="deductions-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> deductions Form </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="deductions-form" autocomplete="off">

                    @csrf
                    <input type="hidden" id="deduction_id" name="id">
                    <input type="hidden" id="deduction_pay_slip_id" name="pay_slip_id">
                    <div class="form-group">
                        <label class="text-primary">Deduction </label><br>
                        <input type="radio" name="deduction" value="Payee"> Payee<br>
                        <input type="radio" name="deduction" value="NSSF"> NSSF<br>
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Amount </label>
                        <input type="number" name="amount" placeholder="enter amount here" class="form-control">
                    </div>

                </form>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" id="btn-deduction" onclick="record_deductions()">Save
                    Record
                </button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


