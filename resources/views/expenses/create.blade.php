<div class="modal fade" id="purchase-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Purchases Form </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="purchase-form" autocomplete="off">

                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label class="text-primary">Purchase Date </label>
                        <input type="text" name="purchase_date" id="datepicker" placeholder="yyy-mm-dd"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Supplier Name </label>
                        <input type="text" name="supplier" id="supplier" placeholder="Enter supplier name"
                               class="form-control">
                    </div>
                    <table class="table table-bordered" id="purchasesTable">
                        <tr>
                            <th class="text-primary">Item</th>
                             <th class="text-primary">Description</th>
                            <th class="text-primary">Expense Category</th>
                            <th class="text-primary">Quantity</th>
                            <th class="text-primary">Unit Price</th>
                            <th class="text-primary">Total Amount</th>
                            <th class="text-primary">Action</th>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" id="item" class="form-control" name="addmore[0][item]"
                                       placeholder="Enter item"/>
                            </td>
                             <td>
                                <input type="text" id="description" class="form-control" name="addmore[0][description]"
                                       placeholder="Enter description (optional)"/>
                            </td>
                            <td>
                                <select id="select2-single-input-group-sm"
                                        class="form-control select2 expense_categories" name="addmore[0][expense_category]">
                                    <option value="null">Choose Expense Category</option>
                                    @foreach($chart_of_accts as $cat)
                                        <option value="{{$cat->id}}">{{$cat->name}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" id="qty" class="form-control" name="addmore[0][qty]"
                                       placeholder="Enter Quantity"/>
                            </td>
                            <td>
                                <input type="number" id="price-single-unit" class="form-control"
                                       name="addmore[0][price]"
                                       placeholder="Enter unit price"/>
                            </td>
                            <td>
                                <input type="text" id="total_amount" class="form-control"
                                       placeholder="Total amount" readonly/>
                            </td>
                            <td>
                                <button type="button" name="add" id="add" class="btn btn-info">Add More</button>
                            </td>
                        </tr>

                    </table>
                    <br><br>

                </form>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-success" id="btn-save" onclick="save_purchase()">Save
                    Purchase
                </button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


