<style>
    .required_alert {
        width: 300px;
        font-size: 12px;
    }

    .dropdown-list {
        position: absolute;
        z-index: 999;
        max-height: 150px;
        background-color: #565e64;
        width: 46.5%;
        overflow-y: auto;
    }
</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="card radius-10">
            <div class="card-body">
                <div>
                    <h5 class="card-title text-primary">Blacklisted Employees</h5>
                </div>
                <hr>
                <div class="row">
                    <div class="row blacklist_list"></div>
                    <div class="col blacklist">
                        <table id="blacklist" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th width="5%">EmployeeID</th>
                                    <th>Name</th>
                                    <th>ReportedBy</th>
                                    <th width="10%">BlacklistDate</th>
                                    <th width="30%">Reason</th>
                                    <th width="6%" style="text-align: center;">Action</th>
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

<div class="modal fade" id="update_bl_form" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Blacklist Information </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="save_bl_update" autocomplete="off">
                <div class="modal-body">
                    <div class="bl_form"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>