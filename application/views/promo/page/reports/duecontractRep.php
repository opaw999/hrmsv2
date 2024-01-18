<style>
    .required_alert {
        width: 300px;
        font-size: 12px;
    }

    .slider {
        overflow: auto;
        max-height: 400px;
    }
</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="card radius-10">
            <div class="card-body">
                <form id="dueContractsForm" method="POST" target="_blank">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mt-0">
                        <h5 class="card-title text-primary">Due Contracts Report</h5>
                        <span>
                            <button type="button" class="btn btn-sm btn-primary" onclick="generateDueContracts('excel')">Generate in Excel <i class="fadeIn animated bx bx-table"></i></button>
                            <button type="button" class="btn btn-sm btn-primary" onclick="generateDueContracts('pdf')">Generate in PDF <i class="fadeIn animated bx bx-file"></i></button>
                        </span>
                    </div>
                    <hr class="mt-2">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="text-secondary mb-1">Store(s)</label>
                            <select name="store" class="form-select select2" onchange="getDepartment(this.value)" data-placeholder="Select Store">
                                <option value="">Select Store</option>
                                <option value="allbu">All Store(s)</option>
                                <?php
                                $query = $this->promo_model->locate_promo_bu('asc');
                                foreach ($query as $key => $bu) {
                                    echo '<option value="' . $bu['bunit_id'] . '|' . $bu['bunit_field'] . '">' . $bu['bunit_name'] . '</option>';
                                }
                                ?>
                            </select>
                            <label class="text-secondary mb-1 mt-2">Department</label>
                            <select name="promo_department" class="form-select select2" data-placeholder="Select Department">
                                <option value="">Select Department</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end page wrapper -->