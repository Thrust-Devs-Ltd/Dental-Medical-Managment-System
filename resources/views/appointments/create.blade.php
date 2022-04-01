<div class="modal fade" id="appointment-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Appointment Form </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="appointment-form" autocomplete="off">

                    @csrf
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label class="text-primary">Patient</label>
                        <select id="patient" name="patient_id" class="form-control" style="width: 100%;"></select>
                    </div>

                    <div class="form-group">
                        <label class="text-primary"> Doctor </label>
                        <select id="doctor" name="doctor_id" class="form-control" style="width: 100%;"></select>
                    </div>
                    <div class="form-group" id="visit_info_section">
                        <label class="text-primary"> Visit Information </label><br>
                        <input type="radio" value="walk_in" name="visit_information"> Walk In<br>
                        <input type="radio" value="appointment" name="visit_information"> Appointment<br>
                    </div>
                    <div class="appointment_section">
                        <div class="form-group">
                            <label class="text-primary">Appointment Date </label>
                            <input class="form-control appointment_date" placeholder="yyyy-mm-dd" type="text"
                                   id="datepicker"
                                   name="appointment_date">
                        </div>
                        <div class="form-group">
                            <label class="text-primary">Appointment Time </label>
                            <input class="form-control" id="appointment_time" data-format="hh:mm A" placeholder="HH:mm"
                                   type="text" name="appointment_time">
                        </div>
                    </div>

                    <div class="form-group hidden">
                        <label class="text-primary">General Notes(Optional) </label>
                        <textarea class="form-control" rows="5" name="notes"
                                  placeholder="Enter general notes here (if any)"></textarea>
                    </div>

                    <div class="form-group hidden">
                        <label class="text-primary">Appointment status </label>
                        <input type="text" id="appointment_status" name="appointment_status">
                    </div>
                    {{--         reactivated appointment check status             --}}
                    <input type="hidden" value="no" id="reactivated_appointment" name="reactivated_appointment">

                </form>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" id="btn-save" onclick="save_data()">Save changes</button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


