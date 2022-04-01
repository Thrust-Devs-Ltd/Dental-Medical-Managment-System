$(function () {
    let invoice_id = $('#global_invoice_id').val();

    var table = $('#sample_2').DataTable({
        destroy: true,
        processing: true,
        // serverSide: true,
        ajax: {
            url: "/invoice-items/" + invoice_id,
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
            {data: 'qty', name: 'qty'},
            {data: 'price', name: 'price'},
            {data: 'total_amount', name: 'total_amount'},
            {data: 'procedure_doctor', name: 'procedure_doctor'},
            {data: 'editBtn', name: 'editBtn', orderable: false, searchable: false},
            {data: 'deleteBtn', name: 'deleteBtn', orderable: false, searchable: false}
        ]
    });


});


function editItem(id) {
    $('.loading').show();
    $("#invoice-form")[0].reset();
    $('#invoice_item_id').val(''); ///always reset hidden form fields
    $('#btn-save').attr('disabled', false);
    $.LoadingOverlay("show");
    $.ajax({
        type: 'get',
        url: "/invoice-items/" + id + "/edit",
        success: function (data) {
            console.log(data);
            $('#invoice_item_id').val(id);
            $('[name="qty"]').val(data.qty);
            $('[name="price"]').val(data.price);
            $('[name="total_amount"]').val(data.price * data.qty);
            $('[name="tooth_no"]').val(data.tooth_no);

            let service_data = {
                id: data.medical_service_id,
                text: data.name
            };
            let newOption2 = new Option(service_data.text, service_data.id, true, true);
            $('#medical_service_id').append(newOption2).trigger('change');

            let doctor_data = {
                id: data.doctor_id,
                text: data.surname + " " + data.othername
            };
            let newOption3 = new Option(doctor_data.text, doctor_data.id, true, true);
            $('#doctor_id').append(newOption3).trigger('change');

            $.LoadingOverlay("hide");
            $('.loading').hide();
            $('#btn-save').text('Update Record')
            $('#invoice-modal').modal('show');
        },
        error: function (request, status, error) {
            $('.loading').hide();
        }
    });
}

$(document).ready(function () {

    $('#procedure_qty').on('keyup change', function () {
        if ($(this).val() && $('#procedure_price').val()) {
            $('#total_amount').val(structureMoney("" + $(this).val() * ($('#procedure_price').val().replace(/,/g, ""))))
            console.log($('#total_amount').val())
        } else if (!$(this).val()) {
            $('#total_amount').val("")
        }

    });

    $('#procedure_price').on('keyup change', function () {
        if ($(this).val() && $('#procedure_qty').val()) {
            $('#total_amount').val(structureMoney("" + ($(this).val().replace(/,/g, "")) * $('#procedure_qty').val()))
        } else if (!$(this).val()) {
            $('#total_amount').val("")
        }
    });
});


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


$('#doctor_id').select2({
    placeholder: "Procedure done by...",
    minimumInputLength: 2,
    ajax: {
        url: '/search-doctor',
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

function save_invoice_update() {
    $('.loading').show();

    $('#btn-save').attr('disabled', true);
    $('#btn-save').text('Updating...');
    $.ajax({
        type: 'PUT',
        data: $('#invoice-form').serialize(),
        url: "/invoice-items/" + $('#invoice_item_id').val(),
        success: function (data) {
            $('#invoice-modal').modal('hide');
            if (data.status) {
                alert_dialog(data.message, "success");
            } else {
                alert_dialog(data.message, "danger");
            }
            $('.loading').hide();
        },
        error: function (request, status, error) {
            $('.loading').hide();
            $('#btn-save').attr('disabled', false);
            $('#btn-save').text('save changes');
            $('#invoice-modal').modal('show');
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

function structureMoney(value) {
    return value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function alert_dialog(message, status) {
    swal("Alert!", message, status);

    setTimeout(function () {
        location.reload();
    }, 1900);
}

