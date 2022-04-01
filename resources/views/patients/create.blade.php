<div class="modal fade" id="patients-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Patient Form</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="patient-form" class="form-horizontal" autocomplete="off">
                    @csrf
                    <input type="hidden" id="patient_id" name="patient_id">
                    <input type="hidden" id="id" name="id">
                    <div class="form-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 text-primary"> Surname</label>
                                            <div class="col-md-9">
                                                <input type="text" name="surname" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 text-primary"> Other Name</label>
                                            <div class="col-md-9">
                                                <input type="text" name="othername" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 text-primary"> Gender</label><br>
                                            <div class="col-md-9">
                                                <input type="radio" name="gender" value="Male"> Male<br>
                                                <input type="radio" name="gender" value="Female"> Female<br>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 text-primary"> Data of Birth</label>
                                            <div class="col-md-9">
                                                <input type="text" name="dob" placeholder="yyyy-mm-dd"
                                                       class="form-control" id="datepicker">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 text-primary">Email<span
                                                        class="text-danger">
                                                   (Optional)
                                                </span></label>
                                            <div class="col-md-9">
                                                <input type="text" name="email" placeholder="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 text-primary"> Phone No</label>
                                            <div class="col-md-9">
                                                <input type="text" id="telephone" name="telephone" class="form-control">
                                            </div>
                                            <input type="hidden" id="phone_number" name="phone_no" class="form-control">
                                        </div>

                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 text-primary"> Alternative Phone
                                                No: <span class="text-danger">
                                                (Optional)
                                                </span></label>
                                            <div class="col-md-9">
                                                <input type="text" name="alternative_no" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="col-md-6">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 text-primary">Address <span
                                                        class="text-danger">
                                                </span></label>
                                            <div class="col-md-9">
                                                <input type="text" name="address" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 hidden">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 text-primary">National Id <span
                                                        class="text-danger">
                                                (Optional)
                                                </span></label>
                                            <div class="col-md-9">
                                                <input type="text" name="nin" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 text-primary"> Profession / place of
                                                work</label>
                                            <div class="col-md-9">
                                                <input type="text" name="profession" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 text-primary"> Next Of Kin</label>
                                            <div class="col-md-9">
                                                <input type="text" name="next_of_kin" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 text-primary"> Next Of Kin (Phone
                                                No)</label>
                                            <div class="col-md-9">
                                                <input type="text" name="next_of_kin_no" class="form-control">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-12 hidden">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 text-primary"> Next Of Kin
                                                Address <span class="text-danger">
                                                   (Optional)
                                                </span></label>
                                            <div class="col-md-9">
                                                <input type="text" name="next_of_kin_address" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 text-primary">Patient Has Medical
                                                Insurance</label>
                                            <div class="col-md-9">
                                                <input type="radio" id="has_insurance" name="has_insurance" value="Yes">
                                                Yes<br>
                                                <input type="radio" id="" name="has_insurance" value="No"> No<br>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 insurance_company">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 text-primary">Medical Insurance
                                                Company</label>
                                            <div class="col-md-9">
                                                <select id="company" name="insurance_company_id" class="form-control"
                                                        style="width: 100%;"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                        </div>

                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" class="btn green" onclick="save_data()">Save changes</button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
