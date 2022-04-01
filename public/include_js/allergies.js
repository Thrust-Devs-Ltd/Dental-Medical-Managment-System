$("#allergies_tab_link").on("click", function () {
    load_patient_allergies();
});


function load_patient_allergies() {
    var table = $('#allergies_table').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "/allergies/" + global_patient_id,
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
            {data: 'body_reaction', name: 'body_reaction'},
            {data: 'created_at', name: 'created_at'},
            {data: 'editBtn', name: 'editBtn', orderable: false, searchable: false},
            {data: 'deleteBtn', name: 'deleteBtn', orderable: false, searchable: false}
        ]
    });


}

function AddAllergy(id) {
    $("#Allergy-form")[0].reset();
    $('#allergy_id').val(''); ///always reset hidden form fields
    $('#btn-allergy').attr('disabled', false);
    $('#btn-allergy').text('Save Changes');

    $('#allergy_patient_id').val(id);
    $('#Allergy-modal').modal('show');
}


function save_allergy() {
    //check save method
    var id = $('#allergy_id').val();
    if (id == "") {
        save_allergy_record();
    } else {
        update_allergy_record();
    }
}

function save_allergy_record() {
    $('.loading').show();
    $('#btn-allergy').attr('disabled', true);
    $('#btn-allergy').text('processing...');
    $.ajax({
        type: 'POST',
        data: $('#Allergy-form').serialize(),
        url: "/allergies",
        success: function (data) {
            $('#Allergy-modal').modal('hide');
            $('.loading').hide();
            if (data.status) {
                alert_allergies(data.message, "success");
            } else {
                alert_allergies(data.message, "danger");
            }
        },
        error: function (request, status, error) {
            $('.loading').hide();
            $('#btn-allergy').attr('disabled', false);
            $('#btn-allergy').text('Save Record');
            $('#Allergy-modal').modal('show');
            json = $.parseJSON(request.responseText);
            $.each(json.errors, function (key, value) {
                $('.alert-danger').show();
                $('.alert-danger').append('<p>' + value + '</p>');
            });
        }
    });
}

function editAllergy(id) {
    $('.loading').show();
    $("#Allergy-form")[0].reset();
    $('#allergy_id').val(''); ///always reset hidden form fields
    $('#btn-allergy').attr('disabled', false);
    $.ajax({
        type: 'get',
        url: "/allergies/" + id + "/edit",
        success: function (data) {
            console.log(data);
            $('#allergy_id').val(id);
            $('[name="body_reaction"]').val(data.body_reaction);

            $('.loading').hide();
            $('#btn-allergy').text('Update Record')
            $('#Allergy-modal').modal('show');

        },
        error: function (request, status, error) {
            $('.loading').hide();
        }
    });
}

function update_allergy_record() {
    $('.loading').show();
    $('#btn-allergy').attr('disabled', true);
    $('#btn-allergy').text('Updating...');
    $.ajax({
        type: 'PUT',
        data: $('#Allergy-form').serialize(),
        url: "/allergies/" + $('#allergy_id').val(),
        success: function (data) {
            $('#Allergy-modal').modal('hide');
            if (data.status) {
                alert_allergies(data.message, "success");
            } else {
                alert_allergies(data.message, "danger");
            }
            $('.loading').hide();
        },
        error: function (request, status, error) {
            $('.loading').hide();
            $('#btn-allergy').attr('disabled', false);
            $('#btn-allergy').text('Update Record');
            $('#Allergy-modal').modal('show');
            json = $.parseJSON(request.responseText);
            $.each(json.errors, function (key, value) {
                $('.alert-danger').show();
                $('.alert-danger').append('<p>' + value + '</p>');
            });
        }
    });
}

function deleteAllergy(id) {
    swal({
            title: "Are you sure?",
            text: "Your will not be able to recover this Allergy!",
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
                url: "/allergies/" + id,
                success: function (data) {
                    if (data.status) {
                        alert_allergies(data.message, "success");
                    } else {
                        alert_allergies(data.message, "danger");
                    }
                    $('.loading').hide();
                },
                error: function (request, status, error) {
                    $('.loading').hide();

                }
            });

        });

}


function alert_allergies(message, status) {
    swal("Alert!", message, status);
    let oTable = $('#allergies_table').dataTable();
    oTable.fnDraw(true);
}

