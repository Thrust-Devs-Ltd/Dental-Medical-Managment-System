$(function () {
    var table = $('#deductions-table').DataTable({
        destroy: true,
        processing: true,
        // serverSide: true,
        ajax: {
            url: "/deductions/" + pay_slip_id,
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
            {data: 'deduction', name: 'deduction'},
            {data: 'amount', name: 'amount'},
            {data: 'added_by', name: 'added_by'},
            {data: 'editBtn', name: 'editBtn', orderable: false, searchable: false},
            {data: 'deleteBtn', name: 'deleteBtn', orderable: false, searchable: false}
        ]
    });


});

function Add_new_deduction() {
    $("#deductions-form")[0].reset();
    $('#deduction_id').val(''); ///always reset hidden form fields
    $('#btn-deduction').attr('disabled', false);
    $('#btn-deduction').text('Save changes');
    $('#deduction_pay_slip_id').val(pay_slip_id);
    $('#deductions-modal').modal('show');
}


function record_deductions() {
    //check save method
    var id = $('#deduction_id').val();
    if (id == "") {
        save_new_deduction();
    } else {
        update_deduction_record();
    }
}

function save_new_deduction() {
    $('.loading').show();
    $('#btn-deduction').attr('disabled', true);
    $('#btn-deduction').text('processing...');
    $.ajax({
        type: 'POST',
        data: $('#deductions-form').serialize(),
        url: "/deductions",
        success: function (data) {
            $('#deductions-modal').modal('hide');
            $('.loading').hide();
            if (data.status) {
                alert_dialog(data.message, "success");
            } else {
                alert_dialog(data.message, "danger");
            }
        },
        error: function (request, status, error) {
            $('.loading').hide();
            $('#btn-deduction').attr('disabled', false);
            $('#btn-deduction').text('Save changes');
            $('#deductions-modal').modal('show');
            json = $.parseJSON(request.responseText);
            $.each(json.errors, function (key, value) {
                $('.alert-danger').show();
                $('.alert-danger').append('<p>' + value + '</p>');
            });
        }
    });
}

function editDeductionRecord(id) {
    $('.loading').show();
    $("#deductions-form")[0].reset();
    $('#deduction_id').val(''); ///always reset hidden form fields
    $('#btn-deduction').attr('disabled', false);
    $.ajax({
        type: 'get',
        url: "/deductions/" + id + "/edit",
        success: function (data) {
            console.log(data);
            $('#deduction_id').val(id);
            $('[name="amount"]').val(data.deduction_amount);
            $('input[name^="deduction"][value="' + data.deduction + '"').prop('checked', true);

            $('.loading').hide();
            $('#btn-deduction').text('Update Record')
            $('#deductions-modal').modal('show');

        },
        error: function (request, status, error) {
            $('.loading').hide();
        }
    });
}

function update_deduction_record() {
    $('.loading').show();

    $('#btn-deduction').attr('disabled', true);
    $('#btn-deduction').text('Updating...');
    $.ajax({
        type: 'PUT',
        data: $('#deductions-form').serialize(),
        url: "/deductions/" + $('#deduction_id').val(),
        success: function (data) {
            $('#deductions-modal').modal('hide');
            if (data.status) {
                alert_dialog(data.message, "success");
            } else {
                alert_dialog(data.message, "danger");
            }
            $('.loading').hide();
        },
        error: function (request, status, error) {
            $('.loading').hide();
            $('#btn-deduction').attr('disabled', false);
            $('#btn-deduction').text('Save changes');
            $('#deductions-modal').modal('show');
            json = $.parseJSON(request.responseText);
            $.each(json.errors, function (key, value) {
                $('.alert-danger').show();
                $('.alert-danger').append('<p>' + value + '</p>');
            });
        }
    });
}

function deleteDeductionRecord(id) {
    swal({
            title: "Are you sure?",
            text: "Your will not be able to recover this deduction!",
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
                url: "/deductions/" + id,
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
