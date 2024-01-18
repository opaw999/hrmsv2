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
                <form action="<?php echo base_url('promo/reports/generateMonthlyStat') ?>" method="POST" target="_blank" onSubmit="return generateMonthlyStat()">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mt-0">
                        <h5 class="card-title text-primary">Monthly Status Report</h5>
                        <button type="submit" class="btn btn-sm btn-primary">Generate in Excel <i class="fadeIn animated bx bx-table"></i></button>
                    </div>
                    <hr class="mt-2">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-secondary mb-1">Store(s)</label>
                            <select name="store" class="form-select select2" onchange="getDepartment(this.value)" data-placeholder="Select Store">

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
                            <label class="text-secondary mb-1 mt-2">Company</label>
                            <select name="promo_company" class="form-select select2" data-placeholder="Select Company">
                                <option value="">Select Company</option>
                                <?php
                                $query = $this->promo_model->selectAll('locate_promo_company');
                                foreach ($query as $key => $value) {
                                    echo '<option value="' . $value['pc_name'] . '">' . $value['pc_name'] . '</option>';
                                }
                                ?>
                            </select>
                            <label class="text-secondary mb-1 mt-2">Current Status</label>
                            <select name="current_status" class="form-select select2" data-placeholder="Select Current Status">
                                <option value="">Select Current Status</option>
                                <?php
                                $condition = array('current_status !=' => '');
                                $query = $this->promo_model->selectDistinct('current_status', 'employee3', $condition);
                                foreach ($query as $key => $value) {
                                    echo '<option value="' . $value['current_status'] . '">' . $value['current_status'] . '</option>';
                                }
                                ?>
                            </select>
                            <label class="text-secondary mb-1 mt-2">Date as of</label>
                            <input type="text" name="dateasof" class="form-control datepicker" placeholder="yyyy/mm/dd">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end page wrapper -->