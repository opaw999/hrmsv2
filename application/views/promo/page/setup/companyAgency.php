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
                    <h5 class="card-title text-primary">Company for Agency List</h5>
                </div>
                <hr>
                <div class="row mb-2 d-flex justify-content-between align-items-center flex-wrap">
                    <div class="col-md-9">
                        <select name="ac" class="form-select form-select-sm select2" onchange="getCompany(this.value)" data-placeholder="Select Agency to setup...">
                            <option value="">Select Agency to setup...</option>
                            <?php
                            $agency = $this->promo_model->selectAll_tk('promo_locate_agency');
                            foreach ($agency as  $row) {
                                echo '<option value="' . $row['agency_code'] . '">' . $row['agency_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-primary px-2" onclick="companyAgencyTable()">Company for Agency List</button>
                        </div>
                    </div>
                </div>
                <div class="row compAgencyTable">
                    <table id="compAgencyTable" class="table table-hove">
                        <thead>
                            <tr>
                                <th>Agency/Supplier Name</th>
                                <th>Company name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->