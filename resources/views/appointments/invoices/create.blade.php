<div class="modal fade" id="New-invoice-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Invoice Form </h4>
            </div>
            <div class="modal-body">

                <form action="#" id="New-invoice-form">

                    @csrf
                    <input type="hidden" name="appointment_id" id="invoicing_appointment_id">
                    <table class="table table-bordered" id="InvoicesTable">
                        <tr>
                            <th class="text-primary">Procedure</th>
                            <th class="text-primary">Tooth Numbers<span class="text-danger">(optional)</span></th>
                            <th class="text-primary">Qty</th>
                            <th class="text-primary">Unit price</th>
                            <th class="text-primary">Total Amount</th>
                            <th class="text-primary">Choose Doctor</th>
                            <th class="text-primary">Action</th>
                        </tr>
                        <tr>
                            <td>

                                <select id="service" name="addmore[0][medical_service_id]" class="form-control"
                                        style="width: 100%;"></select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="addmore[0][tooth_no]"
                                       placeholder="Enter Tooth No"/>
                            </td>
                            <td>
                                <input type="number" id="procedure_qty" class="form-control" name="addmore[0][qty]"
                                       placeholder="Enter qty"/>
                            </td>
                            <td>
                                <input type="number" id="procedure_price" class="form-control" name="addmore[0][price]"
                                       placeholder="Enter unit price"/>
                            </td>
                            <td>
                                <input type="text" id="total_amount" class="form-control" readonly/>
                            </td>
                            <td>

                                <select id="doctor_id" name="addmore[0][doctor_id]" class="form-control"
                                        style="width: 100%;"></select>
                            </td>
                            <td>
                                <button type="button" name="add" id="addInvoiceItem" class="btn btn-info">Add More
                                </button>
                            </td>
                        </tr>

                    </table>
                    <br><br>

                </form>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-success" id="btnSave" onclick="save_invoice()">Generate Invoice
                </button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


