$(function () {
    let global_patient_id = $('#global_patient_id').val();

    var table = $('#sample_10').DataTable({
        destroy: true,
        processing: true,
        // serverSide: true,
        ajax: {
            url: "/treatments-history/" + global_patient_id,
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
            {data: 'created_at', name: 'created_at'},
            {data: 'clinical_notes', name: 'clinical_notes'},
            {data: 'treatment', name: 'treatment'},
            {data: 'doctor', name: 'doctor'}
        ]
    });


});
