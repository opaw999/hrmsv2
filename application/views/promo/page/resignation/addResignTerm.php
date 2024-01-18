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

    .resignation {
        position: absolute;
        z-index: 999;
        max-height: 150px;
        width: 100%;
        overflow-y: auto;
    }

    .resignationForm {
        display: none;
    }
</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="card radius-10">
            <div class="card-body">
                <div>
                    <h5 class="card-title text-primary">Add Resignation/Termination</h5>
                </div>
                <hr>
                <form id="resignationForm" autocomplete="off">
                    <input type="hidden" name="emp_id">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="row">
                                <div class="col-md-12 mb-2 searchContainer">
                                    <label class="form-label text-secondary">Search Promo</label>
                                    <div class="input-group font-22">
                                        <i class="input-group-text fadeIn animated lni lni-users"></i>
                                        <input type="text" class="form-control" name="resignation" placeholder=" Search here..." onkeyup="searhPromo(this.value, this.name)">
                                    </div>
                                    <div class="dropdown-list resignation"></div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label text-secondary">Date Effective</label>
                                    <input type="text" class="form-control datepicker" name="date" value="<?= date('Y-m-d') ?>" disabled>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label text-secondary">Status</label>
                                    <select name="status" class="form-select" onchange="addResignation(this.value)" disabled>
                                        <option value="">Select Status</option>
                                        <option value="End of Contract">End of Contract</option>
                                        <option value="Resigned">Resigned</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="form-label text-secondary">Remarks</label>
                                    <textarea name="remarks" class="form-control" rows="5" disabled></textarea>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary px-5">Submit</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 addResignationForm"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->