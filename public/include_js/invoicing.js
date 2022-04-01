let _appointment_id = $('#global_appointment_id').val();
$("#dental_billing_tab_link").on("click", function () {
    load_dental_billing();
});

function load_dental_billing() {
    var table = $('#dental_billing_table').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "/appointment-invoice-items/" + _appointment_id,
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
            {data: 'service', name: 'service'},
            {data: 'tooth_no', name: 'tooth_no'},
            {data: 'amount', name: 'amount'},
            {data: 'editBtn', name: 'editBtn', orderable: false, searchable: false},
            {data: 'deleteBtn', name: 'deleteBtn', orderable: false, searchable: false}
        ]
    });
}

function AddInvoice(appointment_id) {
    $('#invoicing_appointment_id').val(appointment_id);
    $('#New-invoice-modal').modal('show');
}

$(document).on('click', '.remove-tr-item', function () {

    $(this).parents('tr').remove();

});


let x = 0;
$("#addInvoiceItem").click(function () {
    ++i;

    $("#InvoicesTable").append('<tr>' +
        '<td><select id="service_append' + i + '" name="addmore[' + i + '][medical_service_id]" class="form-control"\n' +
        '                                        style="width: 100%;border: 1px solid #a29e9e;"></select></td>' +
        '<td> <input type="text" name="addmore[' + i + '][tooth_no]" placeholder="Enter tooth number"\n' +
        '                                       class="form-control"/></td>' +
        '<td> <input type="number"  id="procedure_price' + i + '" name="addmore[' + i + '][amount]" placeholder="Enter amount"\n' +
        '                                       class="form-control"/></td>' +
        '<td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>');

    $('#service_append' + i).select2({
        placeholder: "select procedure",
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
                // console.log(data);
                return {
                    results: data
                };
            },
            cache: true
        }
    }).on("select2:select", function (e) {
        let price = e.params.data.price;
        if (price != "" || price != 0) {
            $('#procedure_price' + i).val(price);
        } else {
            $('#procedure_price' + i).val('');
        }
    });

});

function save_invoice() {
    $('.loading').show();
    $('#btnSave').attr('disabled', true);
    $('#btnSave').text('processing...');
    $.ajax({
        type: 'POST',
        data: $('#New-invoice-form').serialize(),
        url: "/invoices",
        success: function (data) {
            $('#New-invoice-modal').modal('hide');
            $('.loading').hide();
            if (data.status) {
                alert_dental_billing(data.message, "success");
            } else {
                alert_dental_billing(data.message, "danger");
            }
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


function editItem(id) {
    $('.loading').show();
    $.ajax({
        type: 'get',
        url: "/invoice-items/" + id + "/edit",
        success: function (data) {
            console.log(data);
            $('#invoice_item_id').val(id);
            $('[name="amount"]').val(data.amount);
            $('[name="tooth_no"]').val(data.tooth_no);

            let service_data = {
                id: data.medical_service_id,
                text: data.name
            };
            let newOption2 = new Option(service_data.text, service_data.id, true, true);
            $('#medical_service_id').append(newOption2).trigger('change');

            $('.loading').hide();
            $('#btn-save').text('Update Record')
            $('#invoice-modal').modal('show');
        },
        error: function (request, status, error) {
            $('.loading').hide();
        }
    });
}

//filter Procedures
$('#service').select2({
    placeholder: "Select Procedure",
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
            // console.log(data);
            return {
                results: data
            };
        },
        cache: true
    }
}).on("select2:select", function (e) {
    let price = e.params.data.price;
    if (price != "" || price != 0) {
        $('#procedure_price').val(price);
    } else {
        $('#procedure_price').val('');
    }

});
$('#medical_service_id').select2({
    placeholder: "Select Procedure",
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
            // console.log(data);
            return {
                results: data
            };
        },
        cache: true
    }
});


function save_invoice_update() {
    $('.loading').show();

    $('#btnSave').attr('disabled', true);
    $('#btnSave').text('Updating...');
    $.ajax({
        type: 'PUT',
        data: $('#invoice-form').serialize(),
        url: "/invoice-items/" + $('#invoice_item_id').val(),
        success: function (data) {
            $('#invoice-modal').modal('hide');
            if (data.status) {
                alert_dental_billing(data.message, "success");
            } else {
                alert_dental_billing(data.message, "danger");
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

function deleteItem(id) {
    swal({
            title: "Are you sure?",
            text: "Your will not be able to recover this Invoice Item!",
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
                url: "/invoice-items/" + id,
                success: function (data) {
                    if (data.status) {
                        alert_dental_billing(data.message, "success");
                    } else {
                        alert_dental_billing(data.message, "danger");
                    }
                    $('.loading').hide();
                },
                error: function (request, status, error) {
                    $('.loading').hide();

                }
            });

        });

}

function alert_dental_billing(message, status) {
    swal("Alert!", message, status);

    let oTable = $('#dental_billing_table').dataTable();
    oTable.fnDraw(true);
}

