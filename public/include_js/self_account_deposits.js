let global_self_account_id = $('#global_self_account_id').val();
$(function () {
    var table = $('#self_account_deposits_table').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "/self-account-deposits/" + global_self_account_id,
            data: function (d) {
            }
        },
        dom: 'Bfrtip',
        buttons: {
            buttons: []
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'payment_date', name: 'payment_date'},
            {data: 'amount', name: 'amount'},
            {data: 'payment_method', name: 'payment_method'},
            {data: 'added_by', name: 'added_by'},
            {data: 'editBtn', name: 'editBtn', orderable: false, searchable: false},
            {data: 'deleteBtn', name: 'deleteBtn', orderable: false, searchable: false}
        ]
    });


});


function AddDeposit() {
    $("#deposit-form")[0].reset();
    $('#deposit_id').val(''); ///always reset hidden form fields
    $('#btn-deposit').attr('disabled', false);
    $('#btn-deposit').text('Save Changes');
    $('#self_account_id').val(global_self_account_id);
    $('#deposit-modal').modal('show');
}


function save_deposit() {
    //check save method
    var id = $('#deposit_id').val();
    if (id == "") {
        save_deposit_record();
    } else {
        update_deposit_record();
    }
}

function save_deposit_record() {
    $('.loading').show();
    $('#btn-deposit').attr('disabled', true);
    $('#btn-deposit').text('processing...');
    $.ajax({
        type: 'POST',
        data: $('#deposit-form').serialize(),
        url: "/self-account-deposits",
        success: function (data) {
            $('#deposit-modal').modal('hide');
            $('.loading').hide();
            if (data.status) {
                alert_deposit_message(data.message, "success");
            } else {
                alert_deposit_message(data.message, "danger");
            }
        },
        error: function (request) {

            $('.loading').hide();

            $('#btn-deposit').attr('disabled', false);
            $('#btn-deposit').text('Save Record');
            $('#deposit-modal').modal('show');

            json = $.parseJSON(request.responseText);
            $.each(json.errors, function (key, value) {
                $('.alert-danger').show();
                $('.alert-danger').append('<p>' + value + '</p>');
            });
        }
    });
}

function editDeposit(id) {
    $('.loading').show();
    $("#deposit-form")[0].reset();
    $('#deposit_id').val(''); ///always reset hidden form fields
    $('#btn-deposit').attr('disabled', false);
    $.ajax({
        type: 'get',
        url: "/self-account-deposits/" + id + "/edit",
        success: function (data) {
            console.log(data);
            $('#deposit_id').val(id);
            $('[name="payment_date"]').val(data.payment_date);
            $('[name="amount"]').val(data.amount);
            $('input[name^="payment_method"][value="' + data.payment_method + '"').prop('checked', true);
            $('#btn-deposit').text('Update Record')
            $('#deposit-modal').modal('show');
            $('.loading').hide();

        },
        error: function (request, status, error) {
            $('.loading').hide();
        }
    });
}

function update_deposit_record() {
    $('.loading').show();
    $('#btn-deposit').attr('disabled', true);
    $('#btn-deposit').text('Updating...');
    $.ajax({
        type: 'PUT',
        data: $('#deposit-form').serialize(),
        url: "/self-account-deposits/" + $('#deposit_id').val(),
        success: function (data) {
            $('#deposit-modal').modal('hide');
            if (data.status) {
                alert_deposit_message(data.message, "success");
            } else {
                alert_deposit_message(data.message, "danger");
            }
            $('.loading').hide();
        },
        error: function (request, status, error) {
            $('.loading').hide();

            $('#btn-deposit').attr('disabled', false);
            $('#btn-deposit').text('Update Record');
            $('#deposit-modal').modal('show');

            json = $.parseJSON(request.responseText);
            $.each(json.errors, function (key, value) {
                $('.alert-danger').show();
                $('.alert-danger').append('<p>' + value + '</p>');
            });
        }
    });
}

function deleteDeposit(id) {
    swal({
            title: "Are you sure?",
            text: "Your will not be able to recover this Deposit Record!",
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
                url: "/self-account-deposits/" + id,
                success: function (data) {
                    if (data.status) {
                        alert_deposit_message(data.message, "success");
                    } else {
                        alert_deposit_message(data.message, "danger");
                    }
                    $('.loading').hide();
                },
                error: function (request, status, error) {
                    $('.loading').hide();

                }
            });

        });

}

function alert_deposit_message(message, status) {
    swal("Alert!", message, status);
    let oTable = $('#self_account_deposits_table').dataTable();
    oTable.fnDraw(true);
}

