let appointment_id = $('#global_appointment_id').val();
$("#dental_notes_tab_link").on("click", function () {
    load_dental_treatment();
});

function load_dental_treatment() {
    var table = $('#dental_treatment_table').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "/treatments/" + global_patient_id,
            data: function (d) {
            }
        },
        dom: 'Bfrtip',
        buttons: {
            buttons: [
                // {extend: 'pdfHtml5', className: 'pdfButton'},
                // {extend: 'excelHtml5', className: 'excelButton'},

            ]
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'created_at', name: 'created_at'},
            {data: 'clinical_notes', name: 'clinical_notes'},
            {data: 'treatment', name: 'treatment'},
            {data: 'added_by', name: 'added_by'},
            {data: 'editBtn', name: 'editBtn', orderable: false, searchable: false},
            {data: 'deleteBtn', name: 'deleteBtn', orderable: false, searchable: false}
        ]
    });

}

//get all the services in an array
$(document).ready(function () {
    $.ajax({
        type: 'get',
        url: "/services-array",
        success: function (data) {
            services_arry = JSON.parse(data);
        }
    }).done(function () {

        $("#procedure_id").typeahead({
            source: services_arry,
            minLength: 1
        });
    });
});

function add_new_tooth() {

    let tooth_no = "";
    let procedure = $('#procedure_id').val();

    if ($('#tooth_number').val() == "null") {
        tooth_no = "";
    } else {
        tooth_no = $('#tooth_number').val();
    }

    //get the old value of the textarea
    let old_data = $('#treatment').val();

    $('#treatment').val(old_data + procedure + " " + tooth_no + ", ")
    //now clear the procedure
    $('#procedure_id').val("")
    $('#tooth_number').prop('selectedIndex', 0);
}


function AddTreatment(id) {
    $("#treatment-form")[0].reset();
    $('#treatment_id').val(''); ///always reset hidden form fields
    $('#btn-treatment').attr('disabled', false);
    $('#btn-treatment').text('save Changes');

    $('#treatment_appointment_id').val(id);
    $('#treatment-modal').modal('show');

}


function save_treatment() {
    //check save method
    var id = $('#treatment_id').val();
    if (id == "") {
        save_treatment_record();
    } else {
        update_treatment_record();
    }
}

function save_treatment_record() {
    $('.loading').show();
    $('#btn-treatment').attr('disabled', true);
    $('#btn-treatment').text('processing...');
    $.ajax({
        type: 'POST',
        data: $('#treatment-form').serialize(),
        url: "/treatments",
        success: function (data) {
            $('#treatment-modal').modal('hide');
            $('.loading').hide();
            if (data.status) {
                alert_dental_dialog(data.message, "success");
            } else {
                alert_dental_dialog(data.message, "danger");
            }
        },
        error: function (request, status, error) {
            $('.loading').hide();
            $('#btn-treatment').attr('disabled', false);
            $('#btn-treatment').text('Save changes');
            json = $.parseJSON(request.responseText);
            $.each(json.errors, function (key, value) {
                $('.treatment_alert').show();
                $('.treatment_alert').append('<p>' + value + '</p>');
            });
        }
    });
}

function editTreatment(id) {
    $('.loading').show();
    $("#treatment-form")[0].reset();
    $('#treatment_id').val(''); ///always reset hidden form fields
    $('#btn-treatment').attr('disabled', false);
    $.ajax({
        type: 'get',
        url: "/treatments/" + id + "/edit",
        success: function (data) {
            console.log(data);
            $('#treatment_id').val(id);
            let appointment_id = $('#global_appointment_id').val();
            $('#treatment_appointment_id').val(appointment_id);
            $('[name="clinical_notes"]').val(data.clinical_notes);
            $('[name="treatment"]').val(data.treatment);

            $('.loading').hide();
            $('#btn-treatment').text('Update Record')
            $('#treatment-modal').modal('show');
        },
        error: function (request, status, error) {
            $('.loading').hide();
        }
    });
}

function update_treatment_record() {
    $('.loading').show();

    $('#btn-treatment').attr('disabled', true);
    $('#btn-treatment').text('Updating...');
    $.ajax({
        type: 'PUT',
        data: $('#treatment-form').serialize(),
        url: "/treatments/" + $('#treatment_id').val(),
        success: function (data) {
            $('#treatment-modal').modal('hide');
            if (data.status) {
                alert_dental_dialog(data.message, "success");
            } else {
                alert_dental_dialog(data.message, "danger");
            }
            $('.loading').hide();
        },
        error: function (request, status, error) {
            $('.loading').hide();
            $('#btn-treatment').attr('disabled', false);
            $('#btn-treatment').text('Update Record');
            json = $.parseJSON(request.responseText);
            $.each(json.errors, function (key, value) {
                $('.treatment_alert').show();
                $('.treatment_alert').append('<p>' + value + '</p>');
            });
        }
    });
}

function deleteTreatment(id) {
    swal({
            title: "Are you sure?",
            text: "Your will not be able to recover this Treatment!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        },
        function () {

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $('.loading').show();
            $.ajax({
                type: 'delete',
                data: {
                    _token: CSRF_TOKEN
                },
                url: "/treatments/" + id,
                success: function (data) {
                    if (data.status) {
                        alert_dental_dialog(data.message, "success");
                    } else {
                        alert_dental_dialog(data.message, "danger");
                    }
                    $('.loading').hide();
                },
                error: function (request, status, error) {
                    $('.loading').hide();

                }
            });

        });

}


function alert_dental_dialog(message, status) {
    swal("Alert!", message, status);
    let oTable = $('#dental_treatment_table').dataTable();
    oTable.fnDraw(true);
}

