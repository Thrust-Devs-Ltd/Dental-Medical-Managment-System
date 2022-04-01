<div class="modal fade" id="expense-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Expense Item Form </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="expense-form" autocomplete="off">
                    @csrf
                    <input type="hidden" id="item_id" name="id">
                    <input type="hidden" id="item_expense_id" name="expense_id">

                    <div class="form-group">
                        <label class="text-primary">Item </label>
                        <input type="text" name="item" id="item" placeholder="" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="text-primary">Quantity </label>
                        <input type="number" id="qty" name="qty" placeholder="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Unit price </label>
                        <input type="number" id="price-single-unit" name="price" placeholder="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Total Amount </label>
                        <input type="text" id="total_amount" readonly placeholder="" class="form-control">
                    </div>
                </form>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" id="btn-save" onclick="save_item()">Save changes</button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


