<div class="modal fade" id="chart_of_accounts-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Chart Of Accounts Form </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="chart_of_accounts-form" autocomplete="off">

                    @csrf
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label class="text-primary">Account type </label>
                        <select id="select2-single-input-group-sm"
                                class="form-control select2 account_type" name="account_type">
                            <option value="null">Choose one</option>
                            @foreach($AccountingEquations as $row)
                                <optgroup label="{{$row->name}}">
                                    @foreach($row->Categories as $cat)
                                        <option value="{{$cat->id}}">{{$cat->name}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Account Name </label>
                        <input type="text" name="name" class="form-control" placeholder="Enter account name">
                    </div>
                    <div class="form-group">
                        <label class="text-primary">Description (optional)</label>
                        <textarea class="form-control" name="description" rows="7"></textarea>
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


