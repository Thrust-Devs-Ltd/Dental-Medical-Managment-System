<div class="modal fade" id="prescription-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"> Prescription Form </h4>
            </div>
            <div class="modal-body">

                <form action="#" id="prescription-form">

                    @csrf
                    <input type="hidden" name="appointment_id" id="prescription_appointment_id">
                    <table class="table table-bordered" id="prescriptionsTable">
                        <tr>
                            <th class="text-primary">Drug</th>
                            <th class="text-primary">ml/mg</th>
                            <th class="text-primary">Directions</th>
                            <th class="text-primary">Action</th>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" id="drug_name" class="form-control" name="addmore[0][drug]"
                                       placeholder="Enter drug"/>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="addmore[0][qty]"
                                       placeholder="Enter ml/mg"/>
                            </td>
                            <td>
                                <textarea class="form-control" name="addmore[0][directions]"></textarea>
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

                <button type="button" class="btn btn-success" id="btn-save" onclick="save_prescription()">Save
                    Prescription
                </button>
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


