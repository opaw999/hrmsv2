<style>
    .required_alert {
        width: 300px;
        font-size: 12px;
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

    .searchContainer {
        position: relative;
        display: inline-block;
    }

    .tagResignation {
        position: absolute;
        z-index: 999;
        max-height: 150px;
        width: 100%;
        overflow-y: auto;
    }

    .tagResignationForm {
        display: none;
    }
</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="card radius-10">
            <div class="card-body">
                <div>
                    <h5 class="card-title text-primary">Tag for Resignation</h5>
                </div>
                <hr>
                <input type="hidden" name="emp_id">
                <div class="row">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12 mb-2 searchContainer">
                                <label class="form-label text-secondary">Search Supervisor</label>
                                <div class="input-group font-22">
                                    <i class="input-group-text fadeIn animated lni lni-users"></i>
                                    <input type="text" class="form-control" name="tagResignation" autocomplete="off" placeholder=" Search here..." onkeyup="searhPromo(this.value, this.name)">
                                </div>
                                <div class="dropdown-list tagResignation"></div>
                            </div>
                            <strong class="text-secondary mb-2">Legend</strong>
                            <div class="d-md-flex d-grid align-items-center gap-1 justify-content-left mb-1">
                                <i class="fadeIn animated bx bx-check-circle text-success font-22"></i>
                                <span class="badge text-secondary">- Done EPAS Rating</span>
                            </div>
                            <div class="d-md-flex d-grid align-items-center gap-1 justify-content-left mb-1">
                                <i class="fadeIn animated bx bx-purchase-tag-alt text-primary font-22"></i>
                                <span class="badge text-secondary">- Tag for Resignation</span>
                            </div>
                            <div class="d-md-flex d-grid align-items-center gap-1 justify-content-left mb-1">
                                <i class="fadeIn animated bx bx-x-circle text-danger font-22"></i>
                                <span class="badge text-secondary">- Untag Pending Resignation</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 tagResignationTable"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->