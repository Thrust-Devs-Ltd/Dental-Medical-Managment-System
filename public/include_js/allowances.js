let pay_slip_id = $('#global_pay_slip_id').val();
$(function () {
    var table = $('#allowances-table').DataTable({
        destroy: true,
        processing: true,
        // serverSide: true,
        ajax: {
            url: "/allowances/" + pay_slip_id,
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
            {data: 'allowance', name: 'allowance'},
            {data: 'amount', name: 'amount'},
            {data: 'added_by', name: 'added_by'},
            {data: 'editBtn', name: 'editBtn', orderable: false, searchable: false},
            {data: 'deleteBtn', name: 'deleteBtn', orderable: false, searchable: false}
        ]
    });


});

function Add_new_allowance() {
    $("#allowances-form")[0].reset();
    $('#allowance_id').val(''); ///always reset hidden form fields
    $('#btn-allowance').attr('disabled', false);
    $('#btn-allowance').text('Save changes');
    $('#allowance_pay_slip_id').val(pay_slip_id);
    $('#allowances-modal').modal('show');
}


function record_allowances() {
    //check save method
    var id = $('#allowance_id').val();
    if (id == "") {
        save_new_allowance();
    } else {
        update_allowance_record();
    }
}

function save_new_allowance() {
    $('.loading').show();
    $('#btn-allowance').attr('disabled', true);
    $('#btn-allowance').text('processing...');
    $.ajax({
        type: 'POST',
        data: $('#allowances-form').serialize(),
        url: "/allowances",
        success: function (data) {
            $('#allowances-modal').modal('hide');
            $('.loading').hide();
            if (data.status) {
                alert_dialog(data.message, "success");
            } else {
                alert_dialog(data.message, "danger");
            }
        },
        error: function (request, status, error) {
            $('.loading').hide();
            $('#btn-allowance').attr('disabled', false);
            $('#btn-allowance').text('Save changes');
            $('#allowances-modal').modal('show');
            json = $.parseJSON(request.responseText);
            $.each(json.errors, function (key, value) {
                $('.alert-danger').show();
                $('.alert-danger').append('<p>' + value + '</p>');
            });
        }
    });
}

function editAllowanceRecord(id) {
    $('.loading').show();
    $("#allowances-form")[0].reset();
    $('#allowance_id').val(''); ///always reset hidden form fields
    $('#btn-allowance').attr('disabled', false);
    $.ajax({
        type: 'get',
        url: "/allowances/" + id + "/edit",
        success: function (data) {
            console.log(data);
            $('#allowance_id').val(id);
            $('[name="amount"]').val(data.allowance_amount);
            $('input[name^="allowance"][value="' + data.allowance + '"').prop('checked', true);

            $('.loading').hide();
            $('#btn-allowance').text('Update Record')
            $('#allowances-modal').modal('show');

        },
        error: function (request, status, error) {
            $('.loading').hide();
        }
    });
}

function update_allowance_record() {
    $('.loading').show();

    $('#btn-allowance').attr('disabled', true);
    $('#btn-allowance').text('Updating...');
    $.ajax({
        type: 'PUT',
        data: $('#allowances-form').serialize(),
        url: "/allowances/" + $('#allowance_id').val(),
        success: function (data) {
            $('#allowances-modal').modal('hide');
            if (data.status) {
                alert_dialog(data.message, "success");
            } else {
                alert_dialog(data.message, "danger");
            }
            $('.loading').hide();
        },
        error: function (request, status, error) {
            $('.loading').hide();
            $('#btn-allowance').attr('disabled', false);
            $('#btn-allowance').text('Save changes');
            $('#allowances-modal').modal('show');
            json = $.parseJSON(request.responseText);
            $.each(json.errors, function (key, value) {
                $('.alert-danger').show();
                $('.alert-danger').append('<p>' + value + '</p>');
            });
        }
    });
}

function deleteAllowanceRecord(id) {
    swal({
            title: "Are you sure?",
            text: "Your will not be able to recover this allowance!",
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
                url: "/allowances/" + id,
                success: function (data) {
                    if (data.status) {
                        alert_dialog(data.message, "success");
                    } else {
                        alert_dialog(data.message, "danger");
                    }
                    $('.loading').hide();
                },
                error: function (request, status, error) {
                    $('.loading').hide();

                }
            });

        });

}


function alert_dialog(message, status) {
    swal("Alert!", message, status);

    setTimeout(function () {
        location.reload();
    }, 1900);
}
