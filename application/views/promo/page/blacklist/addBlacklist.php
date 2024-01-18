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

    table {
        font-size: 12px;
    }
</style>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="card radius-10">
            <div class="card-body">
                <div>
                    <h5 class="card-title text-primary">Add Blacklist</h5>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-2">
                        <label class="form-label">Search Employee...</label>
                        <div class="row row-cols-1 g-3 align-items-center">
                            <div class="col">
                                <input type="search" class="form-control" name="lastname" autocomplete="off" placeholder="Lastname">
                            </div>
                            <div class="col">
                                <input type="search" class="form-control" name="firstname" autocomplete="off" placeholder="Firstname">
                            </div>
                            <div class="col">
                                <input type="search" class="form-control" name="middlename" autocomplete="off" placeholder="Middlename">
                            </div>
                            <div class="col text-center">
                                <button type="button" class="btn btn-sm btn-primary px-3 mb-2" onclick="checkBl()">Browse</button>
                                <button type="button" class="btn btn-sm btn-primary addManual" onclick="addManual()" style="display: none;">Add Manually</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 checkBl" style="display: none;">
                        <div class="text-center">
                            <p class="mb-0"><strong>Records Match on applicant and employee3 tables.</strong></p>
                        </div>
                        <table class="table table-hover" id="checkBl_table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Current Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="col-md-5 checkBlt" style="display: none;">
                        <div class="text-center">
                            <p class="mb-0"><strong>Records Match on blacklist table.</strong></p>
                        </div>
                        <table class="table table-hover" id="checkBlt_table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Current Status</th>
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

<div class="modal fade" id="add_bl_form" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Blacklist Form </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="save_bl" autocomplete="off">
                <div class="modal-body">
                    <div class="bl_form"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>