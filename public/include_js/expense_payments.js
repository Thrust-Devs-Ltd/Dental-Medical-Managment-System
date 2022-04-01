$(function () {
    let expense_id = $('#global_expense_id').val();
    var table = $('#expense_payments_table').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "/expense-payments/" + expense_id,
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
            {data: 'payment_date', name: 'payment_date'},
            {data: 'payment_acct', name: 'payment_acct'},
            {data: 'amount', name: 'amount'},
            {data: 'payment_method', name: 'payment_method'},
            {data: 'added_by', name: 'added_by'},
            {data: 'editBtn', name: 'editBtn', orderable: false, searchable: false},
            {data: 'deleteBtn', name: 'deleteBtn', orderable: false, searchable: false}
        ]
    });


});

function editPaymentRecord(id) {
    $('.loading').show();
    $("#payment-form")[0].reset();
    $('#payment_id').val(''); ///always reset hidden form fields
    $('#btnSave').attr('disabled', false);
    $.ajax({
        type: 'get',
        url: "/expense-payments/" + id + "/edit",
        success: function (data) {

            $('#payment_id').val(id);
            $('[name="amount"]').val(data.amount);
            $('[name="payment_date"]').val(data.payment_date);
            $('input[name^="payment_method"][value="' + data.payment_method + '"').prop('checked', true);

            $(".payment_account").find("option").each(function () {
                if ($(this).val() == data.payment_account_id) {
                    $(this).prop("selected", "selected");
                }
            });

            $('.loading').hide();
            $('#btn-save').text('Update Record')
            $('#payment-modal').modal('show');

        },
        error: function (request, status, error) {
            $('.loading').hide();
        }
    });
}

function update_payment_record() {
    $('.loading').show();
    $('#btnSave').attr('disabled', true);
    $('#btnSave').text('Updating...');
    $.ajax({
        type: 'PUT',
        data: $('#payment-form').serialize(),
        url: "/expense-payments/" + $('#payment_id').val(),
        success: function (data) {
            $('#payment-modal').modal('hide');
            if (data.status) {
                alert_dialog(data.message, "success");
            } else {
                alert_dialog(data.message, "danger");
            }
            $('.loading').hide();
        },
        error: function (request, status, error) {
            $('.loading').hide();
            json = $.parseJSON(request.responseText);
            $.each(json.errors, function (key, value) {
                $('.alert-danger').show();
                $('.alert-danger').append('<p>' + value + '</p>');
            });
        }
    });
}

function deletePaymentRecord(id) {
    swal({
            title: "Are you sure?",
            text: "Your will not be able to recover this payment!",
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
                url: "/expense-payments/" + id,
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
