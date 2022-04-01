let global_patient_id = $('#global_patient_id').val();
$("#surgical_History_tab_link").on("click", function () {
    var table = $('#surgical_table').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "/surgeries/" + global_patient_id,
            data: function (d) {
            }
        },
        dom: 'Bfrtip',
        buttons: {
            buttons: [
                {extend: 'pdfHtml5', className: 'pdfButton'},
                {extend: 'excelHtml5', className: 'excelButton'},

            ]
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'created_at', name: 'created_at'},
            {data: 'surgery', name: 'surgery'},
            {data: 'surgery_date', name: 'surgery_date'},
            {data: 'description', name: 'description'},
            {data: 'added_by', name: 'added_by'},
            {data: 'editBtn', name: 'editBtn', orderable: false, searchable: false},
            {data: 'deleteBtn', name: 'deleteBtn', orderable: false, searchable: false}
        ]
    });
});


function AddSurgery() {
    $("#surgery-form")[0].reset();
    $('#surgery_id').val(''); ///always reset hidden form fields
    $('#btn-surgery').attr('disabled', false);
    $('#btn-surgery').text('Save Changes');
    $('[name="patient_id"]').val(global_patient_id);
    $('#surgery-modal').modal('show');

}


function save_surgery() {
    //check save method
    var id = $('#surgery_id').val();
    if (id == "") {
        save_surgery_record();
    } else {
        update_surgery_record();
    }
}

function save_surgery_record() {
    $('.loading').show();
    $('#btn-surgery').attr('disabled', true);
    $('#btn-surgery').text('processing...');
    $.ajax({
        type: 'POST',
        data: $('#surgery-form').serialize(),
        url: "/surgeries",
        success: function (data) {
            $('#surgery-modal').modal('hide');
            $('.loading').hide();
            if (data.status) {
                alert_surgery(data.message, "success");
            } else {
                alert_surgery(data.message, "danger");
            }
        },
        error: function (request, status, error) {
            $('.loading').hide();
            $('#btn-surgery').attr('disabled', false);
            $('#btn-surgery').text('Save Record');
            json = $.parseJSON(request.responseText);
            $.each(json.errors, function (key, value) {
                $('.alert-danger').show();
                $('.alert-danger').append('<p>' + value + '</p>');
            });
        }
    });
}

function editSurgery(id) {
    $('.loading').show();
    $("#surgery-form")[0].reset();
    $('#surgery_id').val(''); ///always reset hidden form fields
    $('#btn-surgery').attr('disabled', false);
    $.ajax({
        type: 'get',
        url: "/surgeries/" + id + "/edit",
        success: function (data) {
            console.log(data);
            $('#surgery_id').val(id);
            $('[name="surgery"]').val(data.surgery);
            $('[name="surgery_date"]').val(data.surgery_date);
            $('[name="description"]').val(data.description);

            $('.loading').hide();
            $('#btn-surgery').text('Update Record')
            $('#surgery-modal').modal('show');

        },
        error: function (request, status, error) {
            $('.loading').hide();
        }
    });
}

function update_surgery_record() {
    $('.loading').show();

    $('#btn-surgery').attr('disabled', true);
    $('#btn-surgery').text('Updating...');
    $.ajax({
        type: 'PUT',
        data: $('#surgery-form').serialize(),
        url: "/surgeries/" + $('#surgery_id').val(),
        success: function (data) {
            $('#surgery-modal').modal('hide');
            if (data.status) {
                alert_surgery(data.message, "success");
            } else {
                alert_surgery(data.message, "danger");
            }
            $('.loading').hide();
        },
        error: function (request, status, error) {
            $('.loading').hide();
            $('#btn-surgery').attr('disabled', false);
            $('#btn-surgery').text('Update Record');
            json = $.parseJSON(request.responseText);
            $.each(json.errors, function (key, value) {
                $('.alert-danger').show();
                $('.alert-danger').append('<p>' + value + '</p>');
            });
        }
    });
}

function deleteSurgery(id) {
    swal({
            title: "Are you sure?",
            text: "Your will not be able to recover this Surgery!",
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
                url: "/surgeries/" + id,
                success: function (data) {
                    if (data.status) {
                        alert_surgery(data.message, "success");
                    } else {
                        alert_surgery(data.message, "danger");
                    }
                    $('.loading').hide();
                },
                error: function (request, status, error) {
                    $('.loading').hide();

                }
            });

        });

}


function alert_surgery(message, status) {
    swal("Alert!", message, status);

    let oTable = $('#surgical_table').dataTable();
    oTable.fnDraw(true);

}

