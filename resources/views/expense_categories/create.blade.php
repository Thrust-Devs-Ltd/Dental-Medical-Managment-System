<div class="modal fade" id="category-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
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
                <form action="#" id="category-form" autocomplete="off">

                    @csrf
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label class="text-primary">Expense Item </label>
                        <input type="text" name="name" placeholder="Enter name here" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Expense Account </label>
                        <select name="expense_account" id="expense_account" class="form-control">
                            <option value="">Please choose account</option>
                            @foreach($expense_accounts as $acct)
                                <option value="{{$acct->id}}">{{$acct->name}}</option>
                            @endforeach
                        </select>
                    </div>

                </form>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" id="btn-save" onclick="save_data()">Save changes</button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


