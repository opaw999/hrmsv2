<style>
    .required_alert {
        width: 300px;
        font-size: 12px;
    }

    .searchContainer {
        position: relative;
        display: inline-block;
    }

    .extendSearch {
        position: absolute;
        z-index: 999;
        max-height: 200px;
        width: 100%;
        overflow-y: auto;
    }

    ul.list-group a:hover {
        color: #fff;
    }

    html.dark-theme .list-group-item:hover {
        background-color: #008cff;
    }

    html.light-theme .list-group-item:hover {
        background-color: #008cff;
    }
</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="card radius-10">
            <div class="card-body">
                <div>
                    <h5 class="card-title text-primary">Contract Renewal</h5>
                </div>
                <hr>
                <div class="row row-cols-3">
                    <div class="col">
                        <div class="card radius-10 border shadow-none">
                            <div class="card-body">
                                <div>
                                    <h5 class="card-title">End of Contract Process</h5>
                                </div>
                                <p class="card-text">List of Employees for renewal.</p>
                                <a href="<?= base_url('promo/page/contract/eoclist'); ?>" class="btn btn-primary">Renewal of Contract</a>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 border shadow-none">
                            <div class="card-body">
                                <div>
                                    <h5 class="card-title">Extending of Contract Process</h5>
                                </div>
                                <p class="card-text">Extending of Contract for all employees.</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#extendModal">Extend Contract</button>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 border shadow-none">
                            <div class="card-body">
                                <div>
                                    <h5 class="card-title">Manual Process</h5>
                                </div>
                                <p class="card-text">This is for ABENSON OUTLET only!</p>
                                <a href="javascript:;" class="btn btn-secondary">Manual Renewal</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->

<div class="modal fade" id="extendModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Extend Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 searchContainer">
                        <label>Search Promo to Extend Contract</label>
                        <div class="input-group font-22">
                            <i class="input-group-text fadeIn animated lni lni-users"></i>

                            <input type="text" class="form-control" name="extendSearch" placeholder="Search here..." autocomplete="off" onkeyup="searhPromo(this.value, this.name)">
                        </div>
                        <div class="dropdown-list extendSearch"></div>
                        <input type="hidden" class="form-control" name="emp_id">
                        <input type="hidden" class="form-control" name="record_no">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="proceedExtend()">Extend</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>