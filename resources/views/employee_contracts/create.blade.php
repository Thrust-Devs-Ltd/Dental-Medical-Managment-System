<div class="modal fade" id="scale-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Employee Contracts </h4>
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
                                <label class="text-primary">Contract Type</label><br>
                                <input type="radio" name="contract_type" value="Probation"> Probation<br>
                                <input type="radio" name="contract_type" value="Part Time"> Part Time<br>
                                <input type="radio" name="contract_type" value="Full Time"> Full Time<br>
                            </div>
                            <div class="form-group">
                                <label class="text-primary">Contract Start Date</label>
                                <input type="text" name="start_date" placeholder="yyyy-mm-dd" id="datepicker"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="text-primary">Contract Length</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="number" name="contract_length" placeholder="Eg. No of years"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="radio" name="contract_period" value="Months"> Months
                                        <input type="radio" name="contract_period" checked value="Years"> Years
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-primary">Payroll Type</label><br>
                                <input type="radio" name="payroll_type" value="Salary"> Salary<br>
                                <input type="radio" name="payroll_type" value="Commission"> Commission<br>
                            </div>
                            <div class="form-group gross_section">
                                <label class="text-primary">Gross Salary</label>
                                <input type="number" name="gross_salary" placeholder="Enter amount"
                                       class="form-control">
                            </div>
                            <div class="form-group commission_section">
                                <label class="text-primary">Commission Percentage</label>
                                <input type="number" name="commission_percentage" placeholder="Enter percentage %"
                                       class="form-control">
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


