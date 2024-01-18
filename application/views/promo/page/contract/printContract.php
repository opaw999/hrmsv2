<style>
    .required_alert {
        width: 300px;
        font-size: 12px;
    }

    .searchContainer {
        position: relative;
        display: inline-block;
    }

    .printContract {
        position: absolute;
        z-index: 999;
        max-height: 200px;
        width: 100%;
        overflow-y: auto;
    }

    .w1 {
        position: absolute;
        z-index: 999;
        max-height: 150px;
        width: 100%;
        overflow-y: auto;
    }

    .w2 {
        position: absolute;
        z-index: 999;
        max-height: 150px;
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

    .table-container {
        max-height: 200px;
        overflow-y: auto;
    }

    .table-container table {
        width: 100%;
    }
</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="card radius-10">
            <div class="card-body">
                <div>
                    <h5 class="card-title text-primary">Printing of Contract</h5>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card radius-10 border shadow-none">
                            <div class="card-body">
                                <div>
                                    <h5 class="card-title">Generating Contract of Employment</h5>
                                </div>
                                <p class="card-text">Allows printing of current contract of employments.</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#contractModal">Proceed to Contract Generation</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->

<div class="modal fade" id="contractModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Contract Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="generateContractForm" autocomplete="off">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10 mx-auto mb-2 searchContainer">
                            <label>Search promo to generate contract</label>
                            <div class="input-group font-22">
                                <i class="input-group-text fadeIn animated lni lni-users"></i>
                                <input type="text" class="form-control" name="printContract" placeholder=" Search here..." autocomplete="off" onkeyup="searhPromo(this.value, this.name)">
                            </div>
                            <div class="dropdown-list printContract"></div>
                        </div>
                        <div class="contracts"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Generate Contract</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>