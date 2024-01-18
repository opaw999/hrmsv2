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
                    <h5 class="card-title text-primary">Masterfile</h5>
                </div>
                <hr>

                <?php
                $bUs    = $this->promo_model->locate_promo_bu('asc');
                $company = $this->promo_model->selectAll_tcA('locate_promo_company', array('pc_name !=' => ''));
                ?>
                <div class="row mb-2">
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-4">
                                <select name="store" class="form-select form-select-sm" onchange="filterDepartment(this.value)">
                                    <option value="">Store...</option>
                                    <?php
                                    foreach ($bUs as $bu) {
                                        $val = $bu['bunit_id'] . '|' . $bu['bunit_field'];
                                        echo '<option value="' . $val . '">' . $bu['bunit_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="promo_department" class="form-select form-select-sm">
                                    <option value="">Department...</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="promo_type" class="form-select form-select-sm">
                                    <option value="">Promo Type...</option>
                                    <option value="ROVING">ROVING</option>
                                    <option value="STATION">STATION</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="promo_company" class="form-select form-select-sm select2">
                            <option value="">Company...</option>
                            <?php
                            foreach ($company as $value) {
                                echo '<option value="' . $value['pc_name'] . '">' . $value['pc_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm btn-primary px-2" onclick="filterMasterfile()">Filter</button>
                    </div>
                </div>
                <div class="row">
                    <table id="masterfile" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Store(s)</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>EmpType</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->