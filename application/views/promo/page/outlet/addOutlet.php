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

    .addOutlet {
        position: absolute;
        z-index: 999;
        max-height: 150px;
        width: 100%;
        overflow-y: auto;
    }

    .addOutletForm {
        display: none;
    }
</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="card radius-10">
            <div class="card-body">
                <div>
                    <h5 class="card-title text-primary">Add Outlet</h5>
                </div>
                <hr>
                <form id="addOutletForm" autocomplete="off">
                    <input type="hidden" name="process" value="addOutlet">
                    <div class="row">
                        <div class="col-md-4 mb-2 searchContainer">
                            <label class="form-label text-secondary">Search Promo</label>
                            <div class="input-group font-22">
                                <i class="input-group-text fadeIn animated lni lni-users"></i>
                                <input type="text" class="form-control" name="addOutlet" placeholder=" Search here..." autocomplete="off" onkeyup="searhPromo(this.value, this.name)">
                            </div>
                            <div class="dropdown-list addOutlet"></div>
                            <div class="promoDetails"></div>
                        </div>
                        <div class="col-md-8 border-start addOutletForm"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->