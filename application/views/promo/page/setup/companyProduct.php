<style>
    .required_alert {
        width: 300px;
        font-size: 12px;
    }
</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="card radius-10">
            <div class="card-body">
                <div>
                    <h5 class="card-title text-primary">Product for Company List</h5>
                </div>
                <hr>
                <div class="row mb-2 d-flex justify-content-between align-items-center flex-wrap">
                    <div class="col-md-9">
                        <select name="company" class="form-select form-select-sm select2" onchange="getProduct(this.value)" data-placeholder="Select Company to setup...">
                            <option value="">Select Company to setup...</option>
                            <?php
                            $agency = $this->promo_model->selectAll('locate_promo_company');
                            foreach ($agency as  $row) {
                                echo '<option value="' . $row['pc_name'] . '">' . $row['pc_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-primary px-2" onclick="productCompanyTable()">Product for Company List</button>
                        </div>
                    </div>
                </div>
                <div class="row productCompanyTable">
                    <table id="productCompanyTable" class="table table-hove">
                        <thead>
                            <tr>
                                <th>Company name</th>
                                <th>Product name</th>
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