<div class="modal fade" id="quotation-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Quotation Item Form </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="quotation-form" autocomplete="off">

                    @csrf
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="quotation_id" name="quotation_id">

                    <div class="form-group">
                        <label class="text-primary">Procedure </label>
                        <select id="medical_service_id" name="medical_service_id" class="form-control"
                                style="width: 100%;"></select>
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Tooth Numbers </label>
                        <input type="text" name="tooth_no" placeholder="Enter tooth no" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Qty </label>
                        <input type="text" name="qty" id="procedure_qty" placeholder="Enter qty here"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Unit Price </label>
                        <input type="text" name="price" id="procedure_price" placeholder="Enter price here"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Total Amount </label>
                        <input type="text" name="total_amount" id="total_amount" readonly
                               class="form-control">
                    </div>
                </form>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" id="btn-save" onclick="save_data()">Save
                    changes
                </button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


