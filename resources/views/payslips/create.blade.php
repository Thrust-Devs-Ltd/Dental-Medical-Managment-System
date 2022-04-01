<div class="modal fade" id="scale-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Employee Payslip Form </h4>
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
                    <div class="form-group">
                        <label class="text-primary">Employee </label>
                        <select id="employee" name="employee" class="form-control"
                                style="width: 100%;"></select>
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Payslip Month</label>
                        <input type="text" name="payslip_month" placeholder="yyyy-mm" id="monthsOnly"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Include Allowances</label><br>
                        <input type="radio" name="allowances_include" value="Yes"> Yes
                        <input type="radio" name="allowances_include" checked value="No"> No
                    </div>
                    <div class="form-group">
                        <table class="table table-bordered" id="AllowancesTable">
                            <tr>
                                <th class="text-primary">Allowance</th>
                                <th class="text-primary">Amount</th>
                                <th class="text-primary">Action</th>
                            </tr>
                            <tr>
                                <td>
                                    <select class="form-control" name="addAllowance[0][allowance]">
                                        <option value="House Rent Allowance">House Rent Allowance</option>
                                        <option value="Medical Allowance">Medical Allowance</option>
                                        <option value="Bonus">Bonus</option>
                                        <option value="Dearness Allowance">Dearness Allowance</option>
                                        <option value="Travelling Allowance">Travelling Allowance</option>
                                        <option value="Overtime Allowance">Overtime Allowance</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control"
                                           name="addAllowance[0][allowance_amount]"
                                           placeholder="Enter amount"/>
                                </td>
                                <td>
                                    <button type="button" name="add" id="add_allowance" class="btn btn-info">Add More
                                    </button>
                                </td>
                            </tr>

                        </table>

                    </div>
                    <div class="form-group">
                        <label class="text-primary">Include Deductions</label><br>
                        <input type="radio" name="deductions_include" value="Yes"> Yes
                        <input type="radio" name="deductions_include" checked value="No"> No
                    </div>
                    <div class="form-group">
                        <table class="table table-bordered" id="DeductionsTable">
                            <tr>
                                <th class="text-primary">Deduction</th>
                                <th class="text-primary">Amount</th>
                                <th class="text-primary">Action</th>
                            </tr>
                            <tr>
                                <td>
                                    <select class="form-control" name="addDeduction[0][deduction]">
                                        <option value="Loan">NSSF</option>
                                        <option value="Tax">Payee</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control"
                                           name="addDeduction[0][deduction_amount]"
                                           placeholder="Enter amount"/>
                                </td>
                                <td>
                                    <button type="button" name="add" id="add_deduction" class="btn btn-success">Add
                                        More
                                    </button>
                                </td>
                            </tr>

                        </table>
                    </div>
                </form>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn purple" id="btn-save" onclick="save_data()">Save changes</button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


