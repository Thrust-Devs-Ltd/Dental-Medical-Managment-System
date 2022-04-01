$("#chronic_diseases_tab_link").on("click", function () {
    load_chronic_diseases();
});

function load_chronic_diseases() {
    let global_patient_id = $('#global_patient_id').val();
    var table = $('#chronic_diseases_table').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "/chronic-diseases/" + global_patient_id,
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
            {data: 'disease', name: 'disease'},
            {data: 'status', name: 'status'},
            {data: 'created_at', name: 'created_at'},
            {data: 'editBtn', name: 'editBtn', orderable: false, searchable: false},
            {data: 'deleteBtn', name: 'deleteBtn', orderable: false, searchable: false}
        ]
    });

}

function AddIllness(id) {
    $("#chronic-form")[0].reset();
    $('#illness_id').val(''); ///always reset hidden form fields
    $('#btn-chronic').attr('disabled', false);
    $('#btn-chronic').text('Save Changes');

    $('#chronic_patient_id').val(id);
    $('#chronic-modal').modal('show');
}


function save_illness() {
    //check save method
    var id = $('#illness_id').val();
    if (id == "") {
        save_illness_record();
    } else {
        update_illness_record();
    }
}

function save_illness_record() {
    $('.loading').show();
    $('#btn-chronic').attr('disabled', true);
    $('#btn-chronic').text('processing...');
    $.ajax({
        type: 'POST',
        data: $('#chronic-form').serialize(),
        url: "/chronic-diseases",
        success: function (data) {
            $('#chronic-modal').modal('hide');
            $('.loading').hide();
            if (data.status) {
                alert_chronic_disease(data.message, "success");
            } else {
                alert_chronic_disease(data.message, "danger");
            }
        },
        error: function (request, status, error) {
            $('.loading').hide();
            $('#btn-chronic').attr('disabled', false);
            $('#btn-chronic').text('Save Record');
            json = $.parseJSON(request.responseText);
            $.each(json.errors, function (key, value) {
                $('.alert-danger').show();
                $('.alert-danger').append('<p>' + value + '</p>');
            });
        }
    });
}

function editIllness(id) {
    $('.loading').show();
    $("#chronic-form")[0].reset();
    $('#illness_id').val(''); ///always reset hidden form fields
    $('#btn-chronic').attr('disabled', false);
    $.ajax({
        type: 'get',
        url: "/chronic-diseases/" + id + "/edit",
        success: function (data) {
            console.log(data);
            $('#illness_id').val(id);
            $('[name="disease"]').val(data.disease);
            $('input[name^="status"][value="' + data.status + '"').prop('checked', true);

            $('.loading').hide();
            $('#btn-chronic').text('Update Record')
            $('#chronic-modal').modal('show');

        },
        error: function (request, status, error) {
            $('.loading').hide();
        }
    });
}

function update_illness_record() {
    $('.loading').show();

    $('#btn-chronic').attr('disabled', true);
    $('#btn-chronic').text('Updating...');
    $.ajax({
        type: 'PUT',
        data: $('#chronic-form').serialize(),
        url: "/chronic-diseases/" + $('#illness_id').val(),
        success: function (data) {
            $('#chronic-modal').modal('hide');
            if (data.status) {
                alert_chronic_disease(data.message, "success");
            } else {
                alert_chronic_disease(data.message, "danger");
            }
            $('.loading').hide();
        },
        error: function (request, status, error) {
            $('.loading').hide();
            $('#btn-chronic').attr('disabled', false);
            $('#btn-chronic').text('Update Record');
            json = $.parseJSON(request.responseText);
            $.each(json.errors, function (key, value) {
                $('.alert-danger').show();
                $('.alert-danger').append('<p>' + value + '</p>');
            });
        }
    });
}

function deleteIllness(id) {
    swal({
            title: "Are you sure?",
            text: "Your will not be able to recover this Illness!",
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
                url: "/chronic-diseases/" + id,
                success: function (data) {
                    if (data.status) {
                        alert_chronic_disease(data.message, "success");
                    } else {
                        alert_chronic_disease(data.message, "danger");
                    }
                    $('.loading').hide();
                },
                error: function (request, status, error) {
                    $('.loading').hide();

                }
            });

        });

}


function alert_chronic_disease(message, status) {
    swal("Alert!", message, status);
    let oTable = $('#chronic_diseases_table').dataTable();
    oTable.fnDraw(true);
}
