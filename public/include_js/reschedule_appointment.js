function RescheduleAppointment(id) {
    $("#reschedule-appointment-form")[0].reset();
    $('#reschedule_appointment_id').val(''); ///always reset hidden form fields
    $('#BtnSave').attr('disabled', false);
    $('#BtnSave').text('Save changes');

    $.LoadingOverlay("show");
    $.ajax({
        type: 'get',
        url: "appointments/" + id + "/edit",
        success: function (data) {
            $('#reschedule_appointment_id').val(id);
            $('#reschedule_patient').val(data.surname + " " + data.othername);

            $.LoadingOverlay("hide");
            $('#reschedule-appointment-modal').modal('show');

        },
        error: function (request, status, error) {
            $.LoadingOverlay("hide");
        }
    });


}

function save_scheduler() {
    $.LoadingOverlay("show");
    $('#BtnSave').attr('disabled', true);
    $('#BtnSave').text('processing...');
    $.ajax({
        type: 'POST',
        data: $('#reschedule-appointment-form').serialize(),
        url: "/appointments-reschedule",
        success: function (data) {
            $('#reschedule-appointment-modal').modal('hide');
            $.LoadingOverlay("hide");
            if (data.status) {
                alert_dialog(data.message, "success");
            } else {
                alert_dialog(data.message, "danger");
            }
        },
        error: function (request, status, error) {
            $.LoadingOverlay("hide");
            $('#BtnSave').attr('disabled', false);
            $('#BtnSave').text('Save changes');
            $('#reschedule-appointment-modal').modal('show');
            json = $.parseJSON(request.responseText);
            $.each(json.errors, function (key, value) {
                $('.alert-danger').show();
                $('.alert-danger').append('<p>' + value + '</p>');
            });
        }
    });
}