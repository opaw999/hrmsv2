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

    .inchargeAccount {
        position: absolute;
        z-index: 999;
        max-height: 150px;
        width: 100%;
        overflow-y: auto;
    }

    .inchargeAccountForm {
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
                    <h5 class="card-title text-primary">Add Promo Incharge Accounts</h5>
                </div>
                <hr>
                <form id="inchargeAccountForm" autocomplete="off">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="row">
                                <div class="col-md-12 mb-2 searchContainer">
                                    <label class="form-label text-secondary">Search HR Staff</label>
                                    <div class="input-group font-22">
                                        <i class="input-group-text fadeIn animated lni lni-users"></i>
                                        <input type="text" class="form-control" name="inchargeAccount" placeholder=" Search here..." onkeyup="searhPromo(this.value, this.name)">
                                    </div>
                                    <div class="dropdown-list inchargeAccount"></div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="form-label text-secondary">Employee ID</label>
                                    <input type="text" class="form-control readonly" name="emp_id" readonly>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label text-secondary">User Type</label>
                                    <select name="usertype" class="form-select">
                                        <option value="">Select User Type</option>
                                        <option value="promo1">Promo Incharge</option>
                                        <option value="promo2">Encoder</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label text-secondary">User Status</label>
                                    <select name="user_status" class="form-select">
                                        <option value="">Select User Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">InActive</option>
                                    </select>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->