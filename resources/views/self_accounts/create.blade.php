<div class="modal fade" id="company-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Self Account Form </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="company-form" autocomplete="off">

                    @csrf
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label class="text-primary">Account Name </label>
                        <input type="text" name="name" placeholder="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Phone No: <span class="text-danger">(Optional)</span></label>
                        <input type="text" name="phone_no" placeholder="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Email <span class="text-danger">(Optional)</span></label>
                        <input type="text" name="email" placeholder="" class="form-control">
                    </div>
                    <div class="form-group hidden">
                        <label class="text-primary">Address <span class="text-danger">Optional</span></label>
                        <input type="text" name="address" placeholder="" class="form-control">
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


