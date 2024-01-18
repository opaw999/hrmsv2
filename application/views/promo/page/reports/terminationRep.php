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
                <form id="terReportForm" method="POST" target="_blank">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mt-0">
                        <h5 class="card-title text-primary">Termination of Contract Report</h5>
                        <span>
                            <button type="button" class="btn btn-sm btn-primary" onclick="generateTermRep('excel')">Generate in Excel <i class="fadeIn animated bx bx-table"></i></button>
                            <button type="button" class="btn btn-sm btn-primary" onclick="generateTermRep('pdf')">Generate in PDF <i class="fadeIn animated bx bx-file"></i></button>
                        </span>
                    </div>
                    <hr class="mt-2">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="text-secondary mb-1">Store(s)</label>
                            <select name="store" class="form-select select2" onchange="getDepartment(this.value)">
                                <option value="">All Stores</option>
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
                            <label class="text-secondary mb-1 mt-2">Month</label>
                            <select name="month" class="form-select select2" data-placeholder="Select Month">
                                <option value="">Select Month</option>
                                <?php
                                $query = $this->promo_model->months();
                                foreach ($query as $key => $value) {
                                    echo '<option value="' . $key . '|' . $value . '">' . $value . '</option>';
                                }
                                $y = date('Y') + 1;
                                echo "<option value='01|$y'>January " . $y . "</option>";
                                ?>
                            </select>
                            <div class="mt-3">
                                <button type="button" class="btn btn-sm btn-secondary" onclick="generateTermRep('list')">Generate List</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end page wrapper -->

    <div class="modal fade" id="termRepList" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="termContract('forStore')">Generate for Store</button>
                    <button type="button" class="btn btn-primary" onclick="termContract('forCompany')">Generate for Company</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>