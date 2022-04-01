<div class="modal fade" id="reschedule-appointment-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Reschedule Appointment Form </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="reschedule-appointment-form" autocomplete="off">

                    @csrf
                    <input type="hidden" id="reschedule_appointment_id" name="id">
                    <div class="form-group">
                        <label class="text-primary">Patient</label>
                        <input type="text" class="form-control" name="patient" id="reschedule_patient" readonly/>
                    </div>

                    <div class="form-group">
                        <label class="text-primary">Appointment Date </label>
                        <input class="form-control" placeholder="yyyy-mm-dd" type="text"
                               id="datepicker2"
                               name="appointment_date">
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Appointment Time </label>
                        <input class="form-control" id="start_time" data-format="hh:mm A"
                               placeholder="HH:mm"
                               type="text" name="appointment_time">
                    </div>
                </form>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" id="BtnSave" onclick="save_scheduler()">Save changes
                </button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


