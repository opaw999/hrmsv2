<?php
$input['emp_id'] = $emp_id;
$row = $this->promo_model->promoDetails_wttR(array('e.emp_id' => $emp_id), 'employee3', 'promo_record');
$app = $this->promo_model->selectAll_tcR('applicant', array('app_id' => $emp_id));
if ($app['photo'] != '') {
    $url        = "http://$_SERVER[SERVER_ADDR]:$_SERVER[SERVER_PORT]/hrms/promoV2/{$app['photo']}";
    $response   = @file_get_contents($url);
    $photo      = ($response !== false) ? $app['photo'] : 'assets/images/promologo.png';
} else {
    $photo      = 'assets/images/promologo.png';
}
$photoLink  = 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promoV2/' . $photo;
$bUs        = $this->promo_model->locate_promo_bu('asc');
$agency     = $this->promo_model->selectAll_tk('promo_locate_agency');
$company    = $this->promo_model->selectAll_tcA_tk('promo_locate_company', array('agency_code' => $row['agency_code']));
$bunit_id   = [];
foreach ($bUs as $bu) {
    $hasBu = $this->promo_model->empStores('promo_record', $emp_id, $row['record_no'], $bu['bunit_field']);
    if ($hasBu > 0) {
        $bunit_id[] = $bu['bunit_id'];
    }
}
if (count($bunit_id) > 0) {
    $department = $this->promo_model->whereIN_stcd('DISTINCT', 'dept_name', 'locate_promo_department', 'bunit_id', $bunit_id);
}
$promo_type     = ['ROVING', 'STATION'];
$contract_type  = ['Contractual', 'Seasonal'];
$product    = $this->promo_model->selectAll_tcA('promo_products', array('record_no' => $row['record_no'], 'emp_id' => $emp_id));
$vendor     = $this->promo_model->selectAll_tcA('promo_vendor_lists', array('department' => $row['promo_department']));
$position   = $this->promo_model->positions();
$emp_type   = $this->promo_model->selectAll('employee_type');
$cutoff     = $this->promo_model->selectAll_tk('promo_schedule');
$statCut    = $this->promo_model->selectAll_tcR_tk('promo_sched_emp', array('recordNo' => $row['record_no'], 'empId' => $emp_id)); ?>

<style>
    .required_alert {
        width: 300px;
        font-size: 12px;
    }

    .readonly[readonly] {
        background-color: #e9ecef;

    }

    .searchContainer {
        position: relative;
        display: inline-block;
    }

    .witness1 {
        position: absolute;
        z-index: 999;
        max-height: 200px;
        width: 100%;
        overflow-y: auto;
    }

    .witness2 {
        position: absolute;
        z-index: 999;
        max-height: 200px;
        width: 100%;
        overflow-y: auto;
    }

    .w1 {
        position: absolute;
        z-index: 999;
        max-height: 200px;
        width: 100%;
        overflow-y: auto;
    }

    .w2 {
        position: absolute;
        z-index: 999;
        max-height: 200px;
        width: 100%;
        overflow-y: auto;
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
</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="card radius-10">
            <div class="card-body">
                <div>
                    <h5 class="card-title text-primary">Contract Renewal Form</h5>
                </div>
                <hr>
                <div class="row">
                    <div class="d-flex align-items-center mb-2">
                        <img src="<?= $photoLink ?>" class="rounded-circle p-1 border" width="60" height="60" alt="...">
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mt-0"><?= $row['name'] ?></h5>
                            <p class="mb-0">
                                <?= $row['emp_id'] ?> | <span class="badge rounded-pill bg-primary"><?= $row['current_status'] . ' (' . $row['sub_status'] . ')' ?></span>
                            </p>
                        </div>
                    </div>
                </div>
                <form id="renewContract" autocomplete="off">
                    <div class="row">
                        <div class="col-lg-6 previousContract border-end">
                            <div>
                                <strong>Previous Contract Details</strong>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 mb-2">
                                    <label>Agency</label>
                                    <select class="form-select form-select-sm  select2" data-placeholder="Select Agency" onchange="getCompany(this.value)">
                                        <option value="">Select Agency</option>
                                        <?php
                                        foreach ($agency as $value) {
                                            echo '<option value="' . $value['agency_code'] . '"';
                                            echo ($value['agency_code'] == $row['agency_code']) ? 'selected' : '';
                                            echo '>' . $value['agency_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Company</label>
                                    <select class="form-select form-select-sm select2" data-placeholder="Select Company" onchange="getProduct(this.value)">
                                        <option value="">Select Company</option>
                                        <?php
                                        foreach ($company as $value) {
                                            echo '<option value="' . $value['company_name'] . '"';
                                            echo ($value['company_name'] == $row['promo_company']) ? 'selected' : '';
                                            echo '>' . $value['company_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Promo Type</label>
                                    <select class="form-select form-select-sm select2" data-placeholder="Select Promo Type" onchange="getStores(this.value,'<?= $row['promo_type'] ?>')">
                                        <option value="">Select Promo Type</option>
                                        <?php
                                        foreach ($promo_type as $value) {
                                            echo '<option value="' . $value . '"';
                                            echo ($value == $row['promo_type']) ? 'selected' : '';
                                            echo '>' . $value . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-12">
                                    <label>Store(s)</label>
                                    <ul class="list-group list-group-flush">
                                        <?php
                                        $type = ($row['promo_type'] == 'ROVING') ? 'checkbox' : 'radio';
                                        foreach ($bUs as $bu) {

                                            $hasBu      = $this->promo_model->empStores('promo_record', $input['emp_id'], $row['record_no'], $bu['bunit_field']);
                                            $checked    = ($hasBu > 0) ? 'checked' : '';
                                            $field      = $bu['bunit_id'] . '|' . $bu['bunit_field'];
                                            echo    '<li class="list-group-item">
                                                        <div class="form-check form-check-success">
                                                            <input type="' . $type . '" class="form-check-input" value="' . $field . '" ' . $checked . ' onclick="getDepartment()">
                                                            <label class="form-check-label" for="' . $field . '">' . $bu['bunit_name'] . '</label>
                                                        </div>
                                                    </li>';
                                        } ?>
                                    </ul>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Department</label>
                                    <select class="form-select form-select-sm select2" data-placeholder="Select Department" onchange="getVendor(this.value)">
                                        <option value="">Select Department</option>
                                        <?php
                                        foreach ($department as $value) {
                                            echo '<option value="' . $value['dept_name'] . '"';
                                            echo ($value['dept_name'] == $row['promo_department']) ? 'selected' : '';
                                            echo '>' . $value['dept_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Vendor</label>
                                    <select class="form-select form-select-sm select2" data-placeholder="Select Vendor">
                                        <option value="">Select Vendor</option>
                                        <?php
                                        foreach ($vendor as $value) {
                                            echo '<option value="' . $value['vendor_code'] . '"';
                                            echo ($value['vendor_code'] == $row['vendor_code']) ? 'selected' : '';
                                            echo '>' . $value['vendor_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Product</label>
                                    <select class="form-select form-select-sm select2" multiple data-placeholder="Select Product">
                                        <option value="">Select Product</option>
                                        <?php
                                        foreach ($product as $value) {
                                            echo '<option value="' . $value['product'] . '" selected>' . $value['product'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Position</label>
                                    <select class="form-select form-select-sm select2" data-placeholder="Select Position">
                                        <option value="">Select Position</option>
                                        <?php
                                        foreach ($position as $value) {
                                            echo '<option value="' . $value['position_title'] . '"';
                                            echo ($value['position_title'] == $row['position']) ? 'selected' : '';
                                            echo '>' . $value['position_title'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Employee Type</label>
                                    <select class="form-select form-select-sm select2" data-placeholder="Select Employee Type">
                                        <option value="">Select Employee Type</option>
                                        <?php
                                        foreach ($emp_type as $value) {
                                            echo '<option value="' . $value['emp_type'] . '"';
                                            echo ($value['emp_type'] == $row['emp_type']) ? 'selected' : '';
                                            echo '>' . $value['emp_type'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Contract Type</label>
                                    <select class="form-select form-select-sm select2" data-placeholder="Select Contract Type">
                                        <option value="">Select Contract Type</option>
                                        <?php
                                        foreach ($contract_type as $value) {
                                            echo '<option value="' . $value . '"';
                                            echo ($value == $row['type']) ? 'selected' : '';
                                            echo '>' . $value . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Startdate</label>
                                    <input type="text" class="form-control form-control-sm datepicker" value="<?= $row['startdate'] ?>" placeholder="yyyy/mm/dd" onchange="getDuration(this.name)">
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>EOCdate</label>
                                    <input type="text" class="form-control form-control-sm datepicker" value="<?= $row['eocdate'] ?>" placeholder="yyyy/mm/dd" onchange="getDuration(this.value)">
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Contract Duration</label>
                                    <input type="text" class="form-control form-control-sm" value="<?= $row['duration'] ?>">
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Cutoff</label>
                                    <select class="form-select form-select-sm select2" data-placeholder="Select Cutoff">
                                        <option value="">Select Cutoff</option>
                                        <?php
                                        foreach ($cutoff as $value) {
                                            $endFC = ($value['endFC'] != '') ? $value['endFC'] : 'last';
                                            echo '<option value="' . $value['statCut'] . '"';
                                            echo ($value['statCut'] == $statCut['statCut']) ? 'selected' : '';
                                            echo '>' . $value['startFC'] . '-' . $endFC . ' / ' . $value['startSC'] . '-' . $value['endSC'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <input type="hidden" name="emp_id" value="<?= $emp_id ?>">
                            <input type="hidden" name="record_no" value="<?= $row['record_no'] ?>">
                            <div>
                                <strong>New Contract Details</strong>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 mb-2">
                                    <label>Agency</label>
                                    <select class="form-select form-select-sm select2clear" name="agency_code" data-placeholder="Select Agency" onchange="getCompany(this.value)">
                                        <option value="">Select Agency</option>
                                        <?php
                                        foreach ($agency as $value) {
                                            echo '<option value="' . $value['agency_code'] . '"';
                                            echo ($value['agency_code'] == $row['agency_code']) ? 'selected' : '';
                                            echo '>' . $value['agency_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Company</label>
                                    <select class="form-select form-select-sm select2clear" name="promo_company" data-placeholder="Select Company" onchange="getProduct(this.value)">
                                        <option value="">Select Company</option>
                                        <?php
                                        foreach ($company as $value) {
                                            echo '<option value="' . $value['company_name'] . '"';
                                            echo ($value['company_name'] == $row['promo_company']) ? 'selected' : '';
                                            echo '>' . $value['company_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Promo Type</label>
                                    <select class="form-select form-select-sm select2" name="promo_type" data-placeholder="Select Promo Type" onchange="getStores(this.value,'<?= $row['promo_type'] ?>')">
                                        <option value="">Select Promo Type</option>
                                        <?php
                                        foreach ($promo_type as $value) {
                                            echo '<option value="' . $value . '"';
                                            echo ($value == $row['promo_type']) ? 'selected' : '';
                                            echo '>' . $value . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-12">
                                    <label>Store(s)</label>
                                    <ul class="list-group list-group-flush stores">
                                        <?php
                                        $type = ($row['promo_type'] == 'ROVING') ? 'checkbox' : 'radio';
                                        foreach ($bUs as $bu) {

                                            $hasBu      = $this->promo_model->empStores('promo_record', $input['emp_id'], $row['record_no'], $bu['bunit_field']);
                                            $checked    = ($hasBu > 0) ? 'checked' : '';
                                            $field      = $bu['bunit_id'] . '|' . $bu['bunit_field'];

                                            echo    '<li class="list-group-item">
                                            <div class="form-check form-check-success">
                                                <input type="' . $type . '" class="form-check-input" name="stores[]"  value="' . $field . '" ' . $checked . ' onclick="getDepartment()">
                                                <label class="form-check-label" for="' . $field . '">' . $bu['bunit_name'] . '</label>
                                            </div>
                                        </li>';
                                        } ?>
                                    </ul>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Department</label>
                                    <select class="form-select form-select-sm select2" name="promo_department" data-placeholder="Select Department" onchange="getVendor(this.value)">
                                        <option value="">Select Department</option>
                                        <?php
                                        foreach ($department as $value) {
                                            echo '<option value="' . $value['dept_name'] . '"';
                                            echo ($value['dept_name'] == $row['promo_department']) ? 'selected' : '';
                                            echo '>' . $value['dept_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Vendor</label>
                                    <select class="form-select form-select-sm select2clear" name="vendor_code" data-placeholder="Select Vendor">
                                        <option value="">Select Vendor</option>
                                        <?php
                                        foreach ($vendor as $value) {
                                            echo '<option value="' . $value['vendor_code'] . '"';
                                            echo ($value['vendor_code'] == $row['vendor_code']) ? 'selected' : '';
                                            echo '>' . $value['vendor_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Product</label>
                                    <select class="form-select form-select-sm select2" name="product[]" multiple data-placeholder="Select Product">
                                        <option value="">Select Product</option>
                                        <?php
                                        foreach ($product as $value) {
                                            echo '<option value="' . $value['product'] . '" selected>' . $value['product'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Position</label>
                                    <select class="form-select form-select-sm select2" name="position" data-placeholder="Select Position">
                                        <option value="">Select Position</option>
                                        <?php
                                        foreach ($position as $value) {
                                            echo '<option value="' . $value['position_title'] . '"';
                                            echo ($value['position_title'] == $row['position']) ? 'selected' : '';
                                            echo '>' . $value['position_title'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Employee Type</label>
                                    <select class="form-select form-select-sm select2" name="emp_type" data-placeholder="Select Employee Type">
                                        <option value="">Select Employee Type</option>
                                        <?php
                                        foreach ($emp_type as $value) {
                                            echo '<option value="' . $value['emp_type'] . '"';
                                            echo ($value['emp_type'] == $row['emp_type']) ? 'selected' : '';
                                            echo '>' . $value['emp_type'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Contract Type</label>
                                    <select class="form-select form-select-sm select2" name="type" data-placeholder="Select Contract Type">
                                        <option value="">Select Contract Type</option>
                                        <?php
                                        foreach ($contract_type as $value) {
                                            echo '<option value="' . $value . '"';
                                            echo ($value == $row['type']) ? 'selected' : '';
                                            echo '>' . $value . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Startdate</label>
                                    <input type="text" class="form-control form-control-sm datepicker" name="startdate" value="" placeholder="yyyy/mm/dd" onchange="getDuration(this.name)">
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>EOCdate</label>
                                    <input type="text" class="form-control form-control-sm datepicker" name="eocdate" value="" placeholder="yyyy/mm/dd" onchange="getDuration(this.value)">
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Contract Duration</label>
                                    <input type="text" class="form-control form-control-sm readonly" name="duration" readonly>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label>Cutoff</label>
                                    <select class="form-select form-select-sm select2" name="statCut" data-placeholder="Select Cutoff">
                                        <option value="">Select Cutoff</option>
                                        <?php
                                        foreach ($cutoff as $value) {
                                            $endFC = ($value['endFC'] != '') ? $value['endFC'] : 'last';
                                            echo '<option value="' . $value['statCut'] . '"';
                                            echo ($value['statCut'] == $statCut['statCut']) ? 'selected' : '';
                                            echo '>' . $value['startFC'] . '-' . $endFC . ' / ' . $value['startSC'] . '-' . $value['endSC'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="intros border-top border-bottom"></div>
                        <div class="col-lg-6">
                            <div class="col-sm-12 mt-2 mb-2">
                                <label>Witness 1</label>
                                <div class="row">
                                    <div class="col-sm-12 searchContainer">
                                        <input type="text" class="form-control form-control-sm" name="witness1" onkeyup="searhPromo(this.value, this.name)">
                                        <div class="dropdown-list witness1"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 mb-2">
                                <label>Comments</label>
                                <textarea class="form-control form-control-sm" name="comments" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="col-sm-12  mt-2 mb-2">
                                <label>Witness 2</label>
                                <div class="row">
                                    <div class="col-sm-12 searchContainer">
                                        <input type="text" class="form-control form-control-sm" name="witness2" onkeyup="searhPromo(this.value, this.name)">
                                        <div class="dropdown-list witness2"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 mb-2">
                                <label>Remarks</label>
                                <textarea class="form-control form-control-sm" name="remarks" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="pull-right mt-2">
                        <button type="submit" class="btn btn-sm btn-primary px-3" id="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->

<div class="modal fade" id="generatePermitForm" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Permit Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="generatePermitForm" autocomplete="off">
                <div class="modal-body">
                    <div class="generatePermitForm"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Generate Permit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="generateContractForm" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Contract Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="generateContractForm" autocomplete="off">
                <div class="modal-body">
                    <div class="generateContractForm"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Generate Contract</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>