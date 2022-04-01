<div class="modal fade" id="share-invoice-modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title">Share Invoice On Email</h3>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <form action="#" id="share-invoice-form" class="form-horizontal" autocomplete="off">
                    @csrf
                    <input type="hidden" value="" name="invoice_id"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Invoice No</label>
                            <div class="col-md-9">
                                <input name="invoice_no" placeholder="Invoice No" readonly class="form-control"
                                       type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3"> Patient Name</label>
                            <div class="col-md-9">
                                <input name="name" readonly="" placeholder="Name" class="form-control" type="text">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Email Address</label>
                            <div class="col-md-9">
                                <input name="email" placeholder="Email" autocomplete="off" class="form-control"
                                       type="email" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Message (optional)</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="message" rows="5"
                                          placeholder="please enter custom message here"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn-share" onclick="sendInvoice()" class="btn btn-primary">Share Invoice
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
