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

    .searchList {
        position: absolute;
        z-index: 999;
        max-height: 150px;
        width: 100%;
        overflow-y: auto;
    }

    .searchList {
        display: none;
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
                    <h5 class="card-title text-primary">Secure Clearance</h5>
                </div>
                <hr>
                <div class="row">
                    <div class="col">
                        <form id="secureClearance" class="row g-3" autocomplete="off">
                            <input type="hidden" class="form-control" name="clearanceProcess" value="secure">
                            <input type="hidden" class="form-control" name="emp_id">
                            <input type="hidden" class="form-control" name="record_no">
                            <input type="hidden" class="form-control" name="eocdate">
                            <div class="col-md-6 searchContainer">
                                <label class="form-label">Search Employee</label>
                                <input type="search" class="form-control" id="searchInput" name="name" placeholder="Search here..." onkeyup="nameSearch(this.value)">
                                <div class="dropdown-list searchList"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Promo Type</label>
                                <input type="text" class="form-control readonly" name="promo_type" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Store(s)</label>
                                <select class="form-select" name="stores">
                                    <option value="">Select Store</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Reason for Securing Clearance</label>
                                <select class="form-select" name="reason" onchange="reasonRequirement(this.value)">
                                    <option value="">Select Reason</option>
                                    <option value="Ad-Resigned">Advised to Resign</option>
                                    <option value="V-Resigned">Voluntary to Resignation</option>
                                    <option value="Termination">Termination of Contract</option>
                                    <option value="Deceased">Deceased</option>
                                </select>
                            </div>
                            <div class="col-md-6 requirement"></div>
                            <div class="col-md-12 ">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->