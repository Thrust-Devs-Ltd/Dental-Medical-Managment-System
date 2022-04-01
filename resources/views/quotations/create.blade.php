<div class="modal fade" id="quotation-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Quotation Form </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="quotation-form">

                    @csrf
                    <div class="form-group">
                        <label class="text-primary">Patient</label>
                        <select id="patient" name="patient_id" class="form-control" style="width: 100%;"></select>
                    </div>
                    <br>

                    <table class="table table-bordered" id="QuotationItemsTable">
                        <tr>
                            <th class="text-primary">Procedure</th>
                            <th class="text-primary">Tooth Numbers<span class="text-danger">Optional</span></th>
                            <th class="text-primary">Qty</th>
                            <th class="text-primary">Unit Price</th>
                            <th class="text-primary">Total Amount</th>
                            <th class="text-primary">Action</th>
                        </tr>
                        <tr>
                            <td>
                                <select id="service" name="addmore[0][medical_service_id]" class="form-control"
                                        style="width: 100%;"></select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="addmore[0][tooth_no]"
                                       placeholder="Enter tooth no"/>
                            </td>
                            <td>
                                <input type="number" id="procedure_qty" class="form-control" name="addmore[0][qty]"
                                       placeholder="Enter qty"/>
                            </td>
                            <td>
                                <input type="number" id="procedure_price" class="form-control" name="addmore[0][price]"
                                       placeholder="Enter price"/>
                            </td>
                            <td>
                                <input type="text" readonly="" id="total_amount" class="form-control"
                                       placeholder=""/>
                            </td>
                            <td>
                                <button type="button" name="add" id="addQuotationItem" class="btn btn-info">Add More
                                </button>
                            </td>
                        </tr>

                    </table>
                    <br><br>

                </form>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-success" id="btnSave" onclick="save_quotation()">Generate Quotation
                </button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


