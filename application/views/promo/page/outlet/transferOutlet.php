<style>
    .required_alert {
        width: 300px;
        font-size: 12px;
    }

    ul.list-group a:hover {
        color: #fff;
    }

    html.dark-theme a.list-group-item:hover {
        background-color: #008cff;
    }

    html.light-theme a.list-group-item:hover {
        background-color: #008cff;
    }

    .searchContainer {
        position: relative;
        display: inline-block;
    }

    .transferOutlet {
        position: absolute;
        z-index: 999;
        max-height: 150px;
        width: 100%;
        overflow-y: auto;
    }

    .transferOutletForm {
        display: none;
    }
</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="card radius-10">
            <div class="card-body">
                <div>
                    <h5 class="card-title text-primary">Transfer Outlet</h5>
                </div>
                <hr>
                <form id="transferOutletForm" autocomplete="off">
                    <input type="hidden" name="process" value="transferOutlet">
                    <div class="row">
                        <div class="col-md-4 mb-2 searchContainer">
                            <label class="form-label text-secondary">Search Promo</label>
                            <div class="input-group font-22">
                                <i class="input-group-text fadeIn animated lni lni-users"></i>
                                <input type="text" class="form-control" name="transferOutlet" placeholder=" Search here..." autocomplete="off" onkeyup="searhPromo(this.value, this.name)">
                            </div>
                            <div class="dropdown-list transferOutlet"></div>
                            <div class="promoDetails"></div>
                        </div>
                        <div class="col-md-8 border-start transferOutletForm"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->

<div class="modal fade" id="viewAppraisal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Appraisal Details </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="viewAppraisal"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary print" onclick="printEpas(this.id)">Print</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadClearance" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Clearance </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="save_uploadClearance" autocomplete="off">
                <div class="modal-body">
                    <div class="uploadClearance"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="transferOutletSelect" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transfer Outlet Form </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="save_transferOutletForm" autocomplete="off">
                <div class="modal-body">
                    <div class="transferOutletSelect"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Transfer</button>
                </div>
            </form>
        </div>
    </div>
</div>