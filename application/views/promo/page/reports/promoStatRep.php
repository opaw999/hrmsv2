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
                <form action="<?php echo base_url('promo/reports/generatePromoStat') ?>" method="POST" autocomplete="off" target="_blank" onSubmit="return generatePromoStat()">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mt-0">
                        <h5 class="card-title text-primary">Promo Statistics Report</h5>
                        <button type="submit" class="btn btn-sm btn-primary">Generate in Excel <i class="fadeIn animated bx bx-table"></i></button>
                    </div>
                    <hr class="mt-2">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-secondary mb-1">Store(s)</label>
                            <select name="stores[]" class="form-select select2" id="storeSelect" multiple data-placeholder="Select Store(s)">
                                <option value="AllBu">All Stores</option>
                                <?php
                                $condition = array('hrd_location' => 'asc', 'tk_status' => 'active', 'appraisal_status' => 'active');
                                $query = $this->promo_model->selectAll_tcA('locate_promo_business_unit', $condition);
                                foreach ($query as $key => $bu) {
                                    echo '<option value="' . $bu['bunit_field'] . '">' . $bu['bunit_name'] . '</option>';
                                }
                                ?>
                            </select>
                            <label class="text-secondary mb-1 mt-2">Prepared By</label>
                            <input type="text" name="preparedBy" class="form-control" style="text-transform: capitalize;">
                            <label class="text-secondary mb-1 mt-2">Submitted To</label>
                            <input type="text" name="submittedTo" class="form-control" style="text-transform: capitalize;">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end page wrapper -->