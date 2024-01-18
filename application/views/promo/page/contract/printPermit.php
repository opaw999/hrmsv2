<?php

?>
<style>
    .required_alert {
        width: 300px;
        font-size: 12px;
    }

    .searchContainer {
        position: relative;
        display: inline-block;
    }

    .printPermit {
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
                    <h5 class="card-title text-primary">Printing of Work Permit</h5>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card radius-10 border shadow-none">
                            <div class="card-body">
                                <div>
                                    <h5 class="card-title">Generating of Permit</h5>
                                </div>
                                <p class="card-text">Allows printing of current and previous contract work permits.</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#permitModal">Proceed to Permit Generation</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->

<div class="modal fade" id="permitModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Search and select to Generate Permit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 searchContainer">
                        <label>Search promo to generate permit</label>
                        <div class="input-group font-22">
                            <i class="input-group-text fadeIn animated lni lni-users"></i>
                            <input type="text" class="form-control" name="printPermit" placeholder=" Search here..." autocomplete="off" onkeyup="searhPromo(this.value, this.name)">
                        </div>
                        <div class="dropdown-list printPermit"></div>
                    </div>
                    <div class="contracts"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="selectPermit" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Permit Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="generatePermitForm" autocomplete="off">
                <div class="modal-body">
                    <div class="selectPermit"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Generate Permit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>