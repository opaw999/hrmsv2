<style>
    .required_alert {
        width: 300px;
        font-size: 12px;
    }

    ul.list-group a:hover {
        background-color: #008cff;
        color: #fff;
    }

    .searchContainer {
        position: relative;
        display: inline-block;
    }

    .setupSubordinate {
        position: absolute;
        z-index: 999;
        max-height: 150px;
        width: 100%;
        overflow-y: auto;
    }

    .setupSubordinateForm {
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
                    <h5 class="card-title text-primary">Setup Subordinates</h5>
                </div>
                <hr>
                <input type="hidden" name="emp_id" readonly>
                <div class="row">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12 mb-2 searchContainer">
                                <label class="form-label text-secondary">Search Supervisor</label>
                                <div class="input-group font-22">
                                    <i class="input-group-text fadeIn animated lni lni-users"></i>
                                    <input type="text" class="form-control" name="setupSubordinate" placeholder="Search here..." onkeyup="searhPromo(this.value, this.name)" autocomplete="off">
                                </div>
                                <div class="dropdown-list setupSubordinate"></div>
                            </div>
                            <div class="supDetails"></div>
                            <div class="buttonsSubordinate mt-3 d-flex justify-content-between gap-1 align-items-center flex-wrap"></div>
                        </div>
                    </div>
                    <div class="col-md-8 setupSubordinateForm"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->