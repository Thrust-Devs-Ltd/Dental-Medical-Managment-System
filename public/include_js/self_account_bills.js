$(function () {
    var table = $('#self_account_bills_table').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "/self-account-bills/" + global_self_account_id,
            data: function (d) {
            }
        },
        dom: 'Bfrtip',
        buttons: {
            buttons: []
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'invoice_no', name: 'invoice_no'},
            {data: 'patient', name: 'patient'},
            {data: 'payment_date', name: 'payment_date'},
            {data: 'amount', name: 'amount'},
            {data: 'added_by', name: 'added_by'}
        ]
    });


});
