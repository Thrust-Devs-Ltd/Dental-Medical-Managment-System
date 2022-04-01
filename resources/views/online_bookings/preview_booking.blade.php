<div class="modal fade" id="booking-preview-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Online Booking Form </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="booking-preview-form" autocomplete="off" readonly="">

                    @csrf
                    <input type="hidden" id="id" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name <span>*</span></label>
                                <input type="text" class="form-control" name="full_name" placeholder="Full Name">
                            </div>
                            <div class="form-group">
                                <label>Phone Number <span>*</span></label>
                                <input type="text" class="form-control" name="phone_number" placeholder="Phone Number">
                            </div>
                            <div class="form-group">
                                <label>Email <span>*</span></label>
                                <input type="text" class="form-control" name="email"
                                       placeholder="Email Address">
                            </div>
                            <div class="form-group">
                                <label>Appointment Date <span>*</span></label>
                                <input type="text" class="form-control" readonly id="datepicker" name="appointment_date"
                                       placeholder="dd/mm/yyyy">
                            </div>
                            <div class="form-group">
                                <label>Appointment Time <span>*</span></label>
                                <input type="text" class="form-control" id="appointment_time" name="appointment_time"
                                       placeholder="Visit time">
                            </div>
                            <div class="form-group">
                                <label>Medical Insurance Provider</label>
                                <select id="company" name="insurance_company_id" class="form-control"
                                        style="width: 100%;"></select>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group"><br>
                                <label>Have you ever visited {{ env('CompanyName',null) }} <span>*</span></label><br>
                                <input type="radio" name="visit_history" value="Yes"> Yes<br>
                                <input type="radio" name="visit_history" value="No"> No<br>
                            </div>
                            <div class="form-group">
                                <label>Reason for visit <span>*</span></label>
                                <textarea class="form-control" name="visit_reason" rows="7"></textarea>
                            </div>
                            <div class="form-group doctor_id_field">
                                <label>Doctor<span class="text-danger"> (Please choose the doctor to approve the booking)</span></label>
                                <select id="doctor" name="doctor_id" class="form-control" style="width: 100%;"></select>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <div class="action_btns">
                    <button type="button" id="acceptBtn" class="btn btn-primary" onclick="AcceptBooking();">Accept
                        Booking
                    </button>
                    <button type="button" id="rejectBtn" class="btn btn-danger" onclick="RejectBooking();">Reject
                        Booking
                    </button>
                </div>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


