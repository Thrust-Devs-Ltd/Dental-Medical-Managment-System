$(function () {
    let invoice_id = $('#global_invoice_id').val();

    var table = $('#sample_3').DataTable({
        destroy: true,
        processing: true,
        // serverSide: true,
        ajax: {
            url: "/payments/" + invoice_id,
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
            {data: 'amount', name: 'amount'},
            {data: 'payment_method', name: 'payment_method'},
            {data: 'added_by', name: 'added_by'},
            {data: 'editBtn', name: 'editBtn', orderable: false, searchable: false},
            {data: 'deleteBtn', name: 'deleteBtn', orderable: false, searchable: false}
        ]
    });


});


$(document).ready(function () {
    //hide the insurance companies
    $('#company').val([]).trigger('change');
    $("#company").select2("val", "");
    $('.insurance_company').hide();

    //hide self account
    $('#self_account_id').val([]).trigger('change');
    $("#self_account_id").select2("val", "");
    $('.self_account').hide();

    //hide the cheque payment fields
    $('#cheque_payment').hide();
    $('[name="cheque_no"]').val("");
    $('[name="account_name"]').val("");
    $('[name="bank_name"]').val("");

    $("input[type=radio][name=payment_method]").on("change", function () {
        let action = $("input[type=radio][name=payment_method]:checked").val();

        if (action == "Self Account") {
            //show the select
            $('.self_account').show();
            $('#self_account_id').next(".select2-container").show();
            //now  hide insurance view
            $('.insurance_company').hide();
            $('#company').next(".select2-container").hide();
            //change the value back to default
            $('#company').val([]).trigger('change');

            //hide the cheque payment fields
            $('#cheque_payment').hide();
            $('[name="cheque_no"]').val("");
            $('[name="account_name"]').val("");
            $('[name="bank_name"]').val("");

        } else if (action == "Insurance") {
            //show the select
            $('.insurance_company').show();
            $('#company').next(".select2-container").show();
            //hide  self account
            $('.self_account').hide();
            $('#self_account_id').next(".select2-container").hide();
            //change the value back to default
            $('#self_account_id').val([]).trigger('change');

            //hide the cheque payment fields
            $('#cheque_payment').hide();
            $('[name="cheque_no"]').val("");
            $('[name="account_name"]').val("");
            $('[name="bank_name"]').val("");

        } else if (action == "Cheque") {
            //show the cheque payment fields
            $('#cheque_payment').show();

            //change the value back to default
            $('#company').val([]).trigger('change');
            //now  hide insurance view
            $('.insurance_company').hide();
            $('#company').next(".select2-container").hide();

            //hide also self account
            $('.self_account').hide();
            $('#self_account_id').next(".select2-container").hide();
            $('#self_account_id').val([]).trigger('change');

        } else {
            //change the value back to default
            $('#company').val([]).trigger('change');
            //now  hide insurance view
            $('.insurance_company').hide();
            $('#company').next(".select2-container").hide();

            //hide also self account
            $('.self_account').hide();
            $('#self_account_id').next(".select2-container").hide();
            $('#self_account_id').val([]).trigger('change');

            //hide the cheque payment fields
            $('#cheque_payment').hide();
            $('[name="cheque_no"]').val("");
            $('[name="account_name"]').val("");
            $('[name="bank_name"]').val("");
        }

    });

    ///
});

//filter insurance companies
$('#company').select2({
    placeholder: "Choose insurance company...",
    minimumInputLength: 2,
    ajax: {
        url: '/search-insurance-company',
        dataType: 'json',
        data: function (params) {
            return {
                q: $.trim(params.term)
            };
        },
        processResults: function (data) {
            console.log(data);
            return {
                results: data
            };
        },
        cache: true
    }
});


function edit_Payment(id) {
    $('.loading').show();
    $("#payment-form")[0].reset();
    $('#receipt_id').val(''); ///always reset hidden form fields
    $('#btnSave').attr('disabled', false);
    $.ajax({
        type: 'get',
        url: "/payments/" + id + "/edit",
        success: function (data) {
            console.log(data);
            $('#receipt_id').val(id);
            $('[name="payment_date"]').val(data.payment_date);
            $('[name="amount"]').val(data.amount);
            $('input[name^="payment_method"][value="' + data.payment_method + '"').prop('checked', true);

            if (data.payment_method == "Insurance") {

                //show the select
                let company_data = {
                    id: data.insurance_company_id,
                    text: data.name
                };
                let newOption = new Option(company_data.text, company_data.id, true, true);
                $('#company').append(newOption).trigger('change');

                $('.insurance_company').show();
                $('#company').next(".select2-container").show();
                $('#cheque_payment').hide();

            } else if (data.payment_method == "Cheque") {
                $('#cheque_payment').show();
                $('[name="cheque_no"]').val(data.cheque_no);
                $('[name="account_name"]').val(data.account_name);
                $('[name="bank_name"]').val(data.bank_name);
            } else {
                //change the value back to default
                $('#company').val([]).trigger('change');
                //now hide the view
                $('.insurance_company').hide();
                $('#company').next(".select2-container").hide();

                $('#cheque_payment').hide();

            }


            $('.loading').hide();
            $('#btnSave').text('Update Record')
            $('#payment-modal').modal('show');
        },
        error: function (request, status, error) {
            $('.loading').hide();
        }
    });
}


$('#medical_service_id').select2({
    placeholder: "Select service...",
    minimumInputLength: 2,
    ajax: {
        url: '/search-medical-service',
        dataType: 'json',
        data: function (params) {
            return {
                q: $.trim(params.term)
            };
        },
        processResults: function (data) {
            console.log(data);
            return {
                results: data
            };
        },
        cache: true
    }
});

function update_payment_record() {
    $('.loading').show();

    $('#btnSave').attr('disabled', true);
    $('#btnSave').text('Updating...');
    $.ajax({
        type: 'PUT',
        data: $('#payment-form').serialize(),
        url: "/payments/" + $('#receipt_id').val(),
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
            $('#btnSave').attr('disabled', false);
            $('#btnSave').text('Save Record');
            json = $.parseJSON(request.responseText);
            $.each(json.errors, function (key, value) {
                $('.alert-danger').show();
                $('.alert-danger').append('<p>' + value + '</p>');
            });
        }
    });
}

function delete_payment(id) {
    swal({
            title: "Are you sure?",
            text: "Your will not be able to recover this Payment!",
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
                url: "/payments/" + id,
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

