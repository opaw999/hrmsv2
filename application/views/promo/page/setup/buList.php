<style>
    .required_alert {
        width: 300px;
        font-size: 12px;
    }

    .readonly[readonly] {
        background-color: #e9ecef;
    }
</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="card radius-10">
            <div class="card-body">
                <div>
                    <h5 class="card-title text-primary">Business Unit List</h5>
                </div>
                <hr>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-primary px-2" onclick="addFormBu('addFormBu')">Add Business Unit</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="buListTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Business Unit Name</th>
                                    <th>Status</th>
                                    <th>TK Status</th>
                                    <th>Appraisal Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->


<div class="modal fade" id="addFormBu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Business Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="saveBuForm" autocomplete="off">
                <div class="modal-body addFormBu">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary addFormButton">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="updateForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Business Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body updateForm">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveBu()">Submit</button>
            </div>
        </div>
    </div>
</div>