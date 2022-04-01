<div class="modal fade" id="users-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> System User </h4>
            </div>
            <div class="modal-body">

                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="users-form" autocomplete="off">

                    @csrf
                    <input type="hidden" id="id" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-primary">Surname </label>
                                <input type="text" name="surname" placeholder="Enter surname" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="text-primary">Other Name </label>
                                <input type="text" name="othername" placeholder="Enter other name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="text-primary">Email </label>
                                <input type="text" name="email" placeholder="email address" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="text-primary">Phone No </label>
                                <input type="text" name="phone_no" placeholder="phone number" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="text-primary">Alternative Phone No: <span
                                            class="text-danger">(optional)</span></label>
                                <input type="text" name="alternative_no" placeholder="alternative no"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-primary">National Id No <span class="text-danger">(Optional)</span>
                                </label>
                                <input type="text" name="nin" placeholder=" ID no" class="form-control">
                            </div>
                            <div class="password_config">
                                <div class="form-group">
                                    <label class="text-primary">Password(Preferred) </label>
                                    <input type="text" name="password" placeholder="" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="text-primary">Confirm Password </label>
                                    <input type="text" name="password_confirmation" placeholder="" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="text-primary">System User Role( Required) </label>
                                <select id="role" name="role_id" class="form-control" style="width: 100%;"></select>
                            </div>
                            <div class="form-group" id="branch_block">
                                <label class="text-primary">Branch </label>
                                <select id="branch_id" name="branch_id" class="form-control"
                                        style="width: 100%;"></select>
                            </div>
                            <div class="form-group">
                                <label class="text-primary">Is Doctor </label><span class="text-danger">(Please specify if the user the Doctor)</span><br>
                                <input type="radio" name="is_doctor" value="Yes">Yes &nbsp; &nbsp;
                                <input type="radio" name="is_doctor" value="No">No

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


