let expense_id = $('#global_expense_id').val();
let expense_categories_arry = [];
$(function () {
    var table = $('#expense_items_table').DataTable({
        destroy: true,
        processing: true,
        // serverSide: true,
        ajax: {
            url: "/expense-items/" + expense_id,
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
            {data: 'name', name: 'name'},
              {data: 'description', name: 'description'},
            {data: 'qty', name: 'qty'},
            {data: 'price', name: 'price'},
            {data: 'total_amount', name: 'total_amount'},
            {data: 'added_by', name: 'added_by'},
            {data: 'editBtn', name: 'editBtn', orderable: false, searchable: false},
            {data: 'deleteBtn', name: 'deleteBtn', orderable: false, searchable: false}
        ]
    });


});

function Add_new_item() {
    $("#expense-form")[0].reset();
    $('#item_id').val(''); ///always reset hidden form fields
    $('#btn-save').attr('disabled', false);
    $('#btn-save').text('Save changes');
    $('#item_expense_id').val(expense_id);
    $('#expense-modal').modal('show');
}

$(document).ready(function () {

    //get expense items array
    $.ajax({
        type: 'get',
        url: "/expense-categories-array",
        success: function (data) {
            expense_categories_arry = JSON.parse(data);
        }
    }).done(function () {

        $("#item").typeahead({
            source: expense_categories_arry,
            minLength: 1
        });
    });

    $('#qty').on('keyup change', function () {
        if ($(this).val() && $('#price-single-unit').val()) {
            $('#total_amount').val(structureMoney("" + $(this).val() * ($('#price-single-unit').val().replace(/,/g, ""))))
            console.log($('#total_amount').val())
        } else if (!$(this).val()) {
            $('#total_amount').val("")
        }

    });

    $('#price-single-unit').on('keyup change', function () {
        if ($(this).val() && $('#qty').val()) {
            $('#total_amount').val(structureMoney("" + ($(this).val().replace(/,/g, "")) * $('#qty').val()))
        } else if (!$(this).val()) {
            $('#total_amount').val("")
        }
    });

});

function structureMoney(value) {
    return value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


function save_item() {
    //check save method
    var id = $('#item_id').val();
    if (id == "") {
        save_new_item();
    } else {
        update_item_record();
    }
}

function save_new_item() {
    $('.loading').show();
    $('#btn-save').attr('disabled', true);
    $('#btn-save').text('processing...');
    $.ajax({
        type: 'POST',
        data: $('#expense-form').serialize(),
        url: "/expense-items",
        success: function (data) {
            $('#expense-modal').modal('hide');
            $('.loading').hide();
            if (data.status) {
                alert_dialog(data.message, "success");
            } else {
                alert_dialog(data.message, "danger");
            }
        },
        error: function (request, status, error) {
            $('.loading').hide();
            $('#btn-save').attr('disabled', false);
            $('#btn-save').text('Save changes');
            $('#expense-modal').modal('show');
            json = $.parseJSON(request.responseText);
            $.each(json.errors, function (key, value) {
                $('.alert-danger').show();
                $('.alert-danger').append('<p>' + value + '</p>');
            });
        }
    });
}

function editItemRecord(id) {
    $('.loading').show();
    $("#expense-form")[0].reset();
    $('#item_id').val(''); ///always reset hidden form fields
    $('#btn-save').attr('disabled', false);
    $.ajax({
        type: 'get',
        url: "/expense-items/" + id + "/edit",
        success: function (data) {
            console.log(data);
            $('#item_id').val(id);
            $('[name="item"]').val(data.name);
            $('[name="price"]').val(data.price);
            $('[name="qty"]').val(data.qty);
            $('#total_amount').val(structureMoney("" + ($('#price-single-unit').val().replace(/,/g, "")) * $('#qty').val()))
            $('.loading').hide();
            $('#btn-save').text('Update Record')
            $('#expense-modal').modal('show');

        },
        error: function (request, status, error) {
            $('.loading').hide();
        }
    });
}

function update_item_record() {
    $('.loading').show();

    $('#btn-save').attr('disabled', true);
    $('#btn-save').text('Updating...');
    $.ajax({
        type: 'PUT',
        data: $('#expense-form').serialize(),
        url: "/expense-items/" + $('#item_id').val(),
        success: function (data) {
            $('#expense-modal').modal('hide');
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
            $('#btn-save').text('Save changes');
            $('#expense-modal').modal('show');
            json = $.parseJSON(request.responseText);
            $.each(json.errors, function (key, value) {
                $('.alert-danger').show();
                $('.alert-danger').append('<p>' + value + '</p>');
            });
        }
    });
}

function deleteItemRecord(id) {
    swal({
            title: "Are you sure?",
            text: "Your will not be able to recover this expense item!",
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
                url: "/expense-items/" + id,
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
