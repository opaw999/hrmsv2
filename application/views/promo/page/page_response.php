<?php
if ($request == 'update_blacklist') {

    echo    '<div class="row">
                <div class="col-md-12">
                    <label>EmployeeID/Name</label>
                    <input type="text" class="form-control" value="[' . $blacklist['app_id'] . '] ' . $blacklist['name'] . '" disabled>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <label>Reported By</label>
                    <input type="text" class="form-control" name="reportedby" value="' . $blacklist['reportedby'] . '" onkeyup="nameSearch(this.value)">
                    <div id="dropdown-list" class="dropdown-list">

                    </div>
                </div>
                <div class="col-md-6">
                    <label>Date Blacklisted</label>
                    <input type="text" class="form-control datepicker" name="date_blacklisted" value="' . $blacklist['date_blacklisted'] . '">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <label>Birthdate</label>
                    <input type="text" class="form-control datepicker" name="bday" value="' . $blacklist['bday'] . '">
                </div>
                <div class="col-md-6">
                    <label>Address</label>
                    <input type="text" class="form-control" name="address" value="' . $blacklist['address'] . '">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <label for="input1" class="form-label">Reason</label>
                    <textarea class="form-control" name="reason" cols="3" rows="3">' . $blacklist['reason'] . '</textarea>
                </div>
            </div>
            <input type="hidden" class="form-control" name="blacklist_no" value="' . $blacklist['blacklist_no'] . '">';
} else if ($request == 'clearanceDetails') {

    echo    '<table class="table mb-0 table-hover">
                <thead>
                    <tr>
                        <th>Store(s)</th>
                        <th>EPAS</th>
                        <th>Date Secured</th>
                        <th>Date Effective</th>
                        <th>Date Cleared</th>
                        <th>Status</th>
                        <th>Added By</th>';
    echo ($reason != 'Termination') ? '<th class="text-center">Letter</th>' : '';
    echo                '<th class="text-center">Print</th>
                    </tr>
                </thead>
                <tbody>';
    foreach ($result as $row) {
        $data = array('emp_id' => $row['emp_id'], 'record_no' => $row['record_no'], 'store' => $row['store']);
        $numrate = $this->promo_model->selectAll_tcR('appraisal_details', $data);
        $addedBy = $this->promo_model->getName_employee3($row['added_by']);
        $date_cleared = '';
        if ($row['date_cleared'] != '0000-00-00') {
            $date_cleared = date('m/d/Y', strtotime($row['date_cleared']));
        }
        echo        '<tr>
                        <td>' . $row['store'] . '</td>
                        <td>' . $numrate['numrate'] . '</td>
                        <td>' . date('m/d/Y', strtotime($row['date_secure'])) . '</td>
                        <td>' . date('m/d/Y', strtotime($row['date_effectivity'])) . '</td>
                        <td>' . $date_cleared . '</td>
                        <td>' . $row['clearance_status'] . '</td>
                        <td>' . ucwords(strtolower($addedBy)) . '</td>';
        echo ($reason != 'Termination') ? '
                        <td class="text-center">
                            <a href="javascript:;" title="view Letter" id="" onclick=view_letter(\'' . $row['resignation_letter'] . '\')>
                                <i class="bx bx-image-alt me-0" style="font-size: 22px;"></i>
                            </a>
                        </td>' : '';
        echo            '<td class="text-center">
                            <a href="javascript:;" title="print Clearance" id="" onclick="print_clearance(\'' . $reason . '*' . $row['emp_id'] . '*' . $row['scdetails_id'] . '\')">
                                <i class="bx bx-printer me-0" style="font-size: 22px;"></i>
                            </a>
                        </td>       
                    </tr>';
    }

    echo        '</tbody>
            </table>';
} else if ($request == 'uploadClearance') {

    echo    '<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 row-cols-xl-3">
                <input type="hidden" name="emp_id" value="' . $emp_id . '">';

    $bUs = $this->promo_model->locate_promo_bu('asc');
    foreach ($bUs as $bU) {
        $hasBu = $this->promo_model->empStores('promo_record', $emp_id, $record_no, $bU['bunit_field']);
        if ($hasBu > 0) {
            $clrName = $bU['bunit_clearance']; ?>
            <div class="col mx-auto">
                <div class="card radius-10 border shadow-none">
                    <img id="imagePreview_<?= $clrName ?>" src="#" alt="Image Preview" class="card-img-top" style="display: none;">
                    <div class="card-body">
                        <h5 class="card-title text-primary"><?= $bU['bunit_name'] ?></h5>
                        <hr>
                        <div class="d-flex align-items-center gap-2">
                            <input type="file" id="<?= $clrName ?>" name="<?= $clrName ?>" class="form-control form-control-sm" accept="image/*" onchange="imgPreview(this)">
                            <a href="javascript:;" id="clearButton_<?= $clrName ?>" class="btn btn-sm btn-secondary" onclick="clearImageInput('<?= $clrName ?>')" style="display: none;">Clear</a>
                        </div>
                    </div>
                </div>
            </div>
    <?php
        }
    }
    echo    '</div>';
} else if ($request == 'generatePermitForm') { ?>

    <input type="hidden" class="form-control" name="emp_id" value="<?= $row['emp_id'] ?>">
    <input type="hidden" class="form-control" name="record_no" value="<?= $row['record_no'] ?>">
    <input type="hidden" class="form-control" name="contract" value="<?= $contract ?>">

    <div class="col-md-10 mx-auto">
        <div class="row">
            <div class="col-sm-6 mb-2">
                <label>Search Promo to Generate Permit</label>
                <div class="input-group font-22">
                    <i class="input-group-text fadeIn animated lni lni-users"></i>
                    <input type="text" class="form-control" name="printPermit" value="<?= $name ?>" placeholder=" Search here..." autocomplete="off" onkeyup="searhPrint(this.value, this.name)" disabled>
                </div>
                <div class="dropdown-list printPermit"></div>
            </div>
            <div class="col-sm-6 mb-2">
                <label>Store(s)</label>
                <select class="form-select select2" name="store" data-placeholder="Select Store">
                    <option value="">Select Store</option>
                    <?php
                    $bUs = $this->promo_model->locate_promo_bu('asc');
                    foreach ($bUs as $bu) {
                        $hasBu = $this->promo_model->empStores($table2, $row['emp_id'], $row['record_no'], $bu['bunit_field']);
                        if ($hasBu > 0) {
                            $value = $bu['bunit_id'] . '|' . $bu['bunit_dutySched'] . '|' . $bu['bunit_dutyDays'] . '|' . $bu['bunit_specialSched'] . '|' . $bu['bunit_specialDays'];
                            echo '<option value="' . $value . '">' . $bu['bunit_name'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-6 mb-2">
                <label>Cut Off</label>
                <select class="form-select select2" name="cutOff" data-placeholder="Select Cut Off" disabled>
                    <option value="">Select Cut Off</option>
                    <?php
                    $cutoff = $this->promo_model->selectAll_tk('promo_schedule');
                    $statCut = $this->promo_model->selectAll_tcR_tk('promo_sched_emp', array('recordNo' => $row['record_no'], 'empId' => $row['emp_id']));
                    foreach ($cutoff as $value) {
                        $endFC = ($value['endFC'] != '') ? $value['endFC'] : 'last';
                        echo '<option value="' . $value['statCut'] . '"';
                        echo ($value['statCut'] == $statCut['statCut']) ? 'selected' : '';
                        echo '>' . $value['startFC'] . '-' . $endFC . ' / ' . $value['startSC'] . '-' . $value['endSC'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-6 mb-2">
                <label>Day Off</label>
                <select class="form-select select2" name="dayOff" data-placeholder="Select Day Off" onchange="getDutydays(this.value)">
                    <option value="">Select Day Off</option>
                    <?php
                    $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'No Day Off');
                    foreach ($days as $value) {
                        echo '<option value="' . $value . '">' . $value . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-6 mb-2">
                <label>Duty Schedule</label>
                <select class="form-select select2" name="dutySched" data-placeholder="Select Duty Schedule">
                    <option value="">Select Duty Schedule</option>
                    <?php
                    $dutySched = $this->promo_model->selectAll_tk('shiftcodes');
                    foreach ($dutySched as $value) {
                        $shiftCode  = $value['shiftCode'];
                        $In1        = $value['1stIn'];
                        $Out1       = $value['1stOut'];
                        $In2        = $value['2ndIn'];
                        $Out2       = $value['2ndOut'];
                        if ($In2 == "") {

                            echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1 </option>";
                        } else {

                            echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1, $In2-$Out2</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-6 mb-2">
                <label>Duty Days</label>
                <input type="text" class="form-control" name="dutyDays">
            </div>
            <div class="col-sm-6 mb-2">
                <label>Special Schedule</label>
                <select class="form-select select2" name="specialSched" data-placeholder="Select Special Schedule">
                    <option value="">Select Special Schedule</option>
                    <?php
                    $dutySched = $this->promo_model->selectAll_tk('shiftcodes');
                    foreach ($dutySched as $value) {
                        $shiftCode  = $value['shiftCode'];
                        $In1        = $value['1stIn'];
                        $Out1       = $value['1stOut'];
                        $In2        = $value['2ndIn'];
                        $Out2       = $value['2ndOut'];
                        if ($In2 == "") {

                            echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1 </option>";
                        } else {

                            echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1, $In2-$Out2</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-6 mb-2">
                <label>Special Days</label>
                <input type="text" class="form-control" name="specialDays">
            </div>
        </div>
    </div>

<?php
} else if ($request == 'generateContractForm') {
    $witness        = $this->promo_model->selectAll_tcR('employment_witness', array('emp_id' => $row['emp_id'], 'rec_no' => $row['record_no']));
    $issuedAt       = !empty($witness['issuedat']) ? $witness['issuedat'] : (!empty($sss_ctc['cedula_place']) ? $sss_ctc['cedula_place'] : '');
    $issuedOn       = !empty($witness['issuedon']) ? $witness['issuedon'] : (!empty($sss_ctc['cedula_date']) ? $sss_ctc['cedula_date'] : '');
    if ($witness['sss_ctc'] == 'Cedula') {
        $cedulaNo   = !empty($witness['sssno_ctcno']) ? $witness['sssno_ctcno'] : (!empty($sss_ctc['cedula_no']) ? $sss_ctc['cedula_no'] : '');
        $sssNo      = !empty($sss_ctc['sss_no']) ? $sss_ctc['sss_no'] : '';
    } else {
        $sssNo      = !empty($witness['sssno_ctcno']) ? $witness['sssno_ctcno'] : (!empty($sss_ctc['sss_no']) ? $sss_ctc['sss_no'] : '');
        $cedulaNo   = !empty($sss_ctc['cedula_no']) ? $sss_ctc['cedula_no'] : '';
    }
    $bUs            = $this->promo_model->locate_promo_bu('asc');
    $field          = ''; ?>

    <input type="hidden" class="form-control" name="emp_id" value="<?= $row['emp_id'] ?>">
    <input type="hidden" class="form-control" name="record_no" value="<?= $row['record_no'] ?>">
    <input type="hidden" class="form-control" name="contract" value="current">
    <div class="col-md-10 mx-auto">
        <div class="row">
            <div class="col-sm-12 mb-2">
                <label>Search Promo to Generate Contract</label>
                <div class="input-group font-22">
                    <i class="input-group-text fadeIn animated lni lni-users"></i>
                    <input type="text" class="form-control" name="printPermit" value="<?= $row['name'] ?>" placeholder=" Search here..." autocomplete="off" onkeyup="searhPrint(this.value, this.name)" disabled>
                </div>
                <div class="dropdown-list printPermit"></div>
            </div>
            <div class="col-sm-12 mb-2">
                <label>Contract Header</label>
                <select class="form-select form-select-sm" name="contract_header_no" data-placeholder="Select Contract Header">
                    <option value="">Select Contract Header</option>
                    <?php

                    foreach ($bUs as $bU) {
                        $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bU['bunit_field']);
                        if ($hasBu > 0) {
                            $array = array(
                                10 => 'tal_contract',
                                11 => 'pm_contract',
                                12 => 'icm_contract',
                                14 => 'asc_contract',
                                31 => 'tub_contract',
                                32 => 'colc_contract',
                                33 => 'alta_contract',
                                36 => 'fr_panglao_contract',
                                37 => 'fr_tubigon_contract',
                                23 => $bU['bunit_contract'],
                            );

                            $arraykey = array_search($bU['bunit_contract'], $array);
                            if ($arraykey !== false) {
                                $value = $this->promo_model->selectAll_tcR('contract_header', array('ccode_no' => $arraykey));
                                echo '<option value="' . $value['ccode_no'] . '">' . $value['company'] . '</option>';
                            }
                        }
                    }

                    ?>
                </select>
            </div>
            <div class="col-sm-12 mb-2">
                <label>Please choose either to use SSS No. or Cedula (CTC No.)</label>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-check form-check-success">
                            <input class="form-check-input" type="radio" name="sss_ctc[]" id="flexRadioSuccess2" value="sss" onclick="showElement(this.value)">
                            <label class="form-check-label" for="flexRadioSuccess2">
                                SSS No.
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-sm" name="sss" value="<?= $sssNo ?>" style="display: none;" disabled placeholder="SSS No.">
                    </div>
                    <div class="col-sm-6">
                        <div class="form-check form-check-success">
                            <input class="form-check-input" type="radio" name="sss_ctc[]" id="flexRadioSuccess1" value="cedula" onclick="showElement(this.value)">
                            <label class="form-check-label" for="flexRadioSuccess1">
                                Cedula (CTC No.)
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-6 issuedOn">
                        <input type="text" class="form-control form-control-sm" name="ctc" value="<?= $cedulaNo ?>" style="display: none;" disabled placeholder="Cedula (CTC No.)">
                    </div>
                    <div class="col-sm-6 mt-2 issuedat" style="display: none;" style="display: none;">
                        <label>Issued At</label>
                        <input type="text" class="form-control form-control-sm" name="issuedat" value="<?= $issuedAt ?>" disabled>
                    </div>
                    <div class="col-sm-6 mt-2 issuedon" style="display: none;" style="display: none;">
                        <label>Issued On</label>
                        <input type="text" class="form-control form-control-sm datepicker" name="issuedon" value="<?= $issuedOn ?>" placeholder="yyyy/mm/dd" disabled>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 mb-2">
                <!-- <label>Witness 1</label>
                <input type="text" class="form-control form-control-sm readonly" name="w1" value="<?= $witness['witness1'] ?>" readonly> -->
                <label>Witness 1</label>
                <div class="row">
                    <div class="col-sm-12 searchContainer">
                        <input type="text" class="form-control form-control-sm" name="w1" onkeyup="searhPromo(this.value, this.name)">
                        <div class="dropdown-list w1"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 mb-2">
                <!-- <label>Witness 2</label>
                <input type="text" class="form-control form-control-sm readonly" name="w2" value="<?= $witness['witness2'] ?>" readonly> -->
                <label>Witness 2</label>
                <div class="row">
                    <div class="col-sm-12 searchContainer">
                        <input type="text" class="form-control form-control-sm" name="w2" onkeyup="searhPromo(this.value, this.name)">
                        <div class="dropdown-list w2"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <label>Date of Signing the Contract</label>
                <input type="text" class="form-control form-control-sm datepicker" name="date" value="<?= date('Y-m-d') ?>" placeholder="yyyy/mm/dd">
            </div>
        </div>
    </div>
<?php
} else if ($request == 'getPromoDetails') { ?>

    <ul class="list-group list-group-flush">

        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap mt-0">
            <span class="text-primary"><strong class="text-primary">Current Contract</strong></span>
            <span class="text-secondary"><?= $emp_id ?> (<?= $record_no ?>)</span>
        </li>
        <li class="list-group-item justify-content-between" style="display: flex;">
            <span class="text-primary">Agency</span>
            <span class="text-secondary text-truncate ps-5" data-toggle="tooltip" title="<?= $agency ?>">
                <?= $agency ?>
            </span>
        </li>
        <li class="list-group-item justify-content-between" style="display: flex;">
            <span class="text-primary">Company</span>
            <span class="text-secondary text-truncate ps-5" data-toggle="tooltip" title="<?= $company ?>">
                <?= $company ?>
            </span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
            <span class="text-primary">Store(s)</span>
            <span class="text-secondary"><?= $store ?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
            <span class="text-primary">Department</span>
            <span class="text-secondary"><?= $department ?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
            <span class="text-primary">Position</span>
            <span class="text-secondary"><?= $position ?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
            <span class="text-primary">Promo Type</span>
            <span class="text-secondary"><?= $promo_type ?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
            <span class="text-primary">Contract Type</span>
            <span class="text-secondary"><?= $type ?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
            <span class="text-primary">Contract Date</span>
            <span class="text-secondary"><?= $contract ?></span>
        </li>
    </ul>
<?php
} else if ($request == 'transferRateForm') { ?>
    <div class="text-center text-primary">
        <strong class="mb-0">Previous Rate's</strong>
    </div>
    <input type="hidden" name="emp_id" value="<?= $emp_id ?>">
    <div class="table-container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="text-center">No.</th>
                    <th>Store(s)</th>
                    <th>Rate</th>
                    <th>Rater</th>
                    <th>Startdate</th>
                    <th>EOCdate</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($row) > 0) {
                    $no = 0;
                    foreach ($row as $value) {
                        $no++;
                        $condition  = array('emp_id' => $value['emp_id'], 'record_no' => $value['record_no']);
                        $appDetails = $this->promo_model->selectAll_tcA('appraisal_details', $condition);
                        if (count($appDetails) > 0) {
                            foreach ($appDetails as $appVal) {

                                $checkbox   = ($promo_type == 'ROVING') ? 'checkbox' : 'radio';
                                $bu         = $this->promo_model->selectAll_tcR('locate_promo_business_unit', array('bunit_name' => $appVal['store']));
                                $hasBu      = $this->promo_model->empStores('promo_history_record', $value['emp_id'], $value['record_no'], $bu['bunit_field']);
                                $where      = array('emp_id' => $appVal['emp_id'], 'record_no' => $appVal['record_no'], $bu['bunit_epascode'] => '1');
                                $chk        = $this->promo_model->selectAll_tcR('promo_history_record', $where);
                                $disabled   = ($no == 1 && count($chk) > 0) ? '' : 'disabled';

                                echo    '<tr>
                                            <td class="text-center"><span class="badge bg-danger">' . $no . '</span> | 
                                                <input class="form-check-input" name="stores[]" type="' . $checkbox . '" id="' . $appVal['details_id'] . '" value="' . $appVal['details_id'] . '" onclick="chkStore()" ' . $disabled . '>
                    	                    </td>
                                            <td>' . $appVal['store'] . '</td>
                                            <td>' . $appVal['numrate'] . '</td>
                                            <td>' . ucwords(strtolower($this->promo_model->getName_employee3($appVal['rater']))) . '</td>
                                            <td>' . date('m/d/Y', strtotime($value['startdate'])) . '</td>
                                            <td>' . date('m/d/Y', strtotime($value['eocdate'])) . '</td>
                                        </tr>';
                            }
                        } else {
                            echo    '<tr>
                                        <td class="text-center" colspan="6">No Appraisal Rate for Previous Contract No. <span class="badge bg-danger">' . $no . '</span></td>
                                    </tr>';
                        }
                    }
                } else {
                    echo    '<tr>
                                <td class="text-center" colspan="6">No Records Found!</td>
                            </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="d-md-flex d-grid align-items-center gap-1 justify-content-between mt-2">
        <ul class="list-unstyled">
            <li>
                <span class="badge text-secondary">
                    <b>NOTE:&nbsp;</b>Previous Contract No.
                    <span class="badge bg-danger">1</span>
                </span>
            </li>
            <ul>
                <li>
                    <span class="badge text-secondary">
                        Refers to Contract History on the Profile
                    </span>
                </li>
                <li>
                    <span class="badge text-secondary">
                        Only the Previous Contract No. 1 can be transferred
                    </span>
                </li>
                <li>
                    <span class="badge text-secondary">
                        Inactive checkbox is either not Previous Contract No. 1 or epascode is blank
                    </span>
                </li>
            </ul>
        </ul>

        <button type="submit" class="btn btn-primary transferButton">Transfer Rate</button>
    </div>

<?php
} else if ($request == 'addOutletForm') { ?>
    <div class="text-center text-secondary mb-2">
        <h6><strong>Check Store(s) to Add</strong></h6>
    </div>
    <input type="hidden" name="emp_id" value="<?= $row['emp_id'] ?>">
    <input type="hidden" name="record_no" value="<?= $row['record_no'] ?>">
    <input type="hidden" name="eocdate" value="<?= $row['eocdate'] ?>">
    <input type="hidden" name="duration" value="<?= $row['duration'] ?>">
    <div class="row">
        <?php
        $i = 0;
        $bUs = $this->promo_model->locate_promo_bu('asc');
        foreach ($bUs as $bu) {

            $hasBu      = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bu['bunit_field']);
            if ($hasBu > 0) {
                $i++;
                $current_stores = ($i == 1) ? $bu['bunit_name'] : $current_stores . ', ' . $bu['bunit_name'];
            }

            $checked    = ($hasBu > 0) ? 'checked disabled' : '';
            $color      = ($hasBu > 0) ? 'danger' : 'primary';
            $field      = $bu['bunit_id'] . '|' . $bu['bunit_field'];

            echo    '<div class="col-12 col-sm-4 mb-1">
                        <div class="form-check form-check-' . $color . '">
                            <input class="form-check-input" type="checkbox" name="stores[]" value="' . $field . '" ' . $checked . ' id="' . $bu['bunit_id'] . '" onclick="getIntros()">
                            <label class="form-check-label" for="' . $bu['bunit_id'] . '">
                                | <span class="badge bg-' . $color . '" style="width:177px;">' . $bu['bunit_name'] . '</span>
                            </label>
                        </div>
                    </div>';
        } ?>
        <input type="hidden" name="previous_stores" value="<?= $current_stores ?>">
    </div>
    <div class="row">
        <div class="intros"></div>
    </div>
    <div class="row">
        <div class="col-sm-4 mt-2">
            <label class="form-label text-secondary">Effective On</label>
            <div class="input-group">
                <i class="input-group-text fadeIn animated bx bx-calendar font-22 text-primary"></i>
                <input type="text" class="form-control form-control-sm datepicker" name="startdate" placeholder="yyyy/mm/dd" onchange="getDuration(this.value)">
            </div>
        </div>
        <div class="d-md-flex d-grid align-items-center gap-1 justify-content-between mt-2">
            <ul class="list-unstyled">
                <li>
                    <span class="badge text-secondary">
                        <b>NOTE:&nbsp;</b> <span class="badge bg-danger"><i class="fa fa-check"></i></span>
                        &nbsp;|&nbsp;STORE NAME
                    </span>
                </li>
                <ul>
                    <li>
                        <span class="badge text-secondary">
                            Refers to the Current Contract Stores
                        </span>
                    </li>
                    <li>
                        <span class="badge text-secondary">
                            Current Contract Stores cannot be selected
                        </span>
                    </li>
                </ul>
            </ul>
            <button type="submit" class="btn btn-sm btn-primary outletButton">Add Outlet</button>
        </div>
    </div>
<?php
} else if ($request == 'removeOutletForm') { ?>
    <div class="text-center text-secondary mb-2">
        <h6><strong>Check Store(s) to Remove</strong></h6>
    </div>
    <input type="hidden" name="emp_id" value="<?= $row['emp_id'] ?>">
    <input type="hidden" name="record_no" value="<?= $row['record_no'] ?>">

    <div class="row">
        <?php
        $i      = 0;
        $bUs    = $this->promo_model->locate_promo_bu('asc');
        foreach ($bUs as $bu) {

            $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bu['bunit_field']);
            if ($hasBu > 0) {
                $i++;
                $field          = $bu['bunit_id'] . '|' . $bu['bunit_field'];
                $prev_stores    = ($i == 1) ? $bu['bunit_name'] : $prev_stores . ', ' . $bu['bunit_name'];
                $condition      = array('emp_id' => $row['emp_id'], 'record_no' => $row['record_no'], 'store' => $bu['bunit_name']);
                $appDetails     = $this->promo_model->selectAll_tcR('appraisal_details', $condition);
                $checkbox       = '<input class="form-check-input" type="checkbox" disabled> | <span class="badge bg-primary" style="width:150px;">' . $bu['bunit_name'] . '</span>';
                $epas           = '| <span class="badge bg-secondary">none</span>';
                $clearance      = '| <span class="badge bg-secondary">Not Secured</span>';
                $secured        = false;
                $signOff        = false;
                $grade          = false;
                $condition      = array('emp_id' => $row['emp_id'], 'promo_type' => 'ROVING', 'status' => 'Pending');
                $scpr_id        = $this->promo_model->selectAll_tcR('secure_clearance_promo', $condition)['scpr_id'];
                if (!empty($scpr_id)) {
                    $condition  = array('scpr_id' => $scpr_id, 'store' => $bu['bunit_name'], 'clearance_status' => 'Pending');
                    $clrDetails = $this->promo_model->selectAll_tcR('secure_clearance_promo_details', $condition);
                    if ($clrDetails > 0) {
                        $clearance  = '| <span class="badge bg-info">Secured</span>';
                        $secured    = true;
                    }
                }

                if ($appDetails > 0) {

                    $signOff    = ($appDetails['raterSO'] == 1 && $appDetails['rateeSO'] == 1) ? true : false;
                    $grade      = ($appDetails['numrate'] >= 85 && $appDetails['numrate'] <= 100) ? true : false;

                    if ($signOff && $grade) {
                        $color = 'success';
                    } else if (!$signOff && $grade) {
                        $color = 'warning';
                    } else {
                        $color = 'danger';
                    }

                    $epas = ' | <a href="javascript:;" id="' . $appDetails['details_id'] . '" onclick="viewAppraisal(this.id)">
                                <span class="badge bg-' . $color . '">' . $appDetails['numrate'] . '</span>
                            </a>';
                }
                if ($signOff && $grade && $secured) {
                    $checkbox = '<div class="form-check form-check-primary">
                                    <input class="form-check-input" type="checkbox" name="stores[]" value="' . $field . '" id="' . $bu['bunit_id'] . '"><label class="form-check-label" for="' . $bu['bunit_id'] . '">
                                         | <span class="badge bg-primary" style="width:150px;">' . $bu['bunit_name'] . '</span>
                                    </label>
                                </div>';
                }
                echo    '<div class="col-12 col-sm-6 mb-1">
                            <div class="d-md-flex d-grid align-items-center gap-1 justify-content-left">
                            ' . $checkbox . '
                            ' . $epas . '
                            ' . $clearance . '
                            </div>
                        </div>';
            }
        }
        echo '<input type="hidden" name="startdate" value="' . date('Y-m-d') . '">';
        ?>
        <input type="hidden" name="previous_stores" value="<?= $prev_stores ?>">
        <input type="hidden" name="storeCount" value="<?= $i ?>">
    </div>
    <div class="row">
        <div class="d-md-flex d-grid align-items-center gap-1 justify-content-between mt-2">
            <ul class="list-unstyled">
                <li>
                    <span class="badge text-secondary">
                        <b>NOTE:&nbsp;</b> <span class="badge bg-primary"><i class="fa fa-check"></i></span>
                        &nbsp;|&nbsp;STORE NAME&nbsp;|&nbsp;EPAS RATE&nbsp;|&nbsp;CLEARANCE
                    </span>
                </li>
                <ul>
                    <li>
                        <span class="badge text-secondary">
                            Refers to the Current Contract Stores
                        </span>
                    </li>
                    <li>
                        <span class="badge text-secondary">
                            Requires Passing EPAS Rate and Signed Off
                        </span>
                    </li>
                    <li>
                        <span class="badge text-secondary">
                            Requires Uploaded Clearance
                        </span>
                    </li>
                </ul>
            </ul>
            <button type="button" class="btn btn-sm btn-primary mt-4 outletButton" onclick="outletClearance()">Remove Outlet</button>
        </div>
    </div>
<?php
} else if ($request == 'transferOutletForm') { ?>

    <input type="hidden" name="emp_id" value="<?= $row['emp_id'] ?>">
    <input type="hidden" name="record_no" value="<?= $row['record_no'] ?>">
    <div class="row">
        <div class="text-center text-secondary mb-2">
            <h6><strong>Check Store(s) to Transfer From</strong></h6>
        </div>
        <?php
        $i      = 0;
        $bUs    = $this->promo_model->locate_promo_bu('asc');
        foreach ($bUs as $bu) {

            $hasBu  = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bu['bunit_field']);
            $field  = $bu['bunit_id'] . '|' . $bu['bunit_field'];
            if ($hasBu > 0) {
                $i++;
                $prev_stores    = ($i == 1) ? $bu['bunit_name'] : $prev_stores . ', ' . $bu['bunit_name'];
                $condition      = array('emp_id' => $row['emp_id'], 'record_no' => $row['record_no'], 'store' => $bu['bunit_name']);
                $appDetails     = $this->promo_model->selectAll_tcR('appraisal_details', $condition);
                $checkbox       = '<input class="form-check-input" type="checkbox" disabled> | <span class="badge bg-primary" style="width:150px;">' . $bu['bunit_name'] . '</span>';
                $epas           = '| <span class="badge bg-secondary">none</span>';
                $clearance      = '| <span class="badge bg-secondary">Not Secured</span>';
                $secured        = false;
                $signOff        = false;
                $grade          = false;
                $condition      = array('emp_id' => $row['emp_id'], 'status' => 'Pending');
                $scpr_id        = $this->promo_model->selectAll_tcR('secure_clearance_promo', $condition)['scpr_id'];
                if (!empty($scpr_id)) {
                    $condition  = array('scpr_id' => $scpr_id, 'store' => $bu['bunit_name'], 'clearance_status' => 'Pending');
                    $clrDetails = $this->promo_model->selectAll_tcR('secure_clearance_promo_details', $condition);
                    if ($clrDetails > 0) {
                        $clearance  = '| <span class="badge bg-info">Secured</span>';
                        $secured    = true;
                    }
                }

                if ($appDetails > 0) {

                    $signOff    = ($appDetails['raterSO'] == 1 && $appDetails['rateeSO'] == 1) ? true : false;
                    $grade      = ($appDetails['numrate'] >= 85 && $appDetails['numrate'] <= 100) ? true : false;

                    if ($signOff && $grade) {
                        $color = 'success';
                    } else if (!$signOff && $grade) {
                        $color = 'warning';
                    } else {
                        $color = 'danger';
                    }

                    $epas = ' | <a href="javascript:;" id="' . $appDetails['details_id'] . '" onclick="viewAppraisal(this.id)">
                                <span class="badge bg-' . $color . '">' . $appDetails['numrate'] . '</span>
                            </a>';
                }

                if ($signOff && $grade && $secured) {
                    $checkbox = '<div class="form-check form-check-primary">
                                    <input class="form-check-input" type="checkbox" name="stores[]" value="' . $field . '" id="' . $bu['bunit_id'] . '">
                                    <label class="form-check-label" for="' . $bu['bunit_id'] . '">
                                         | <span class="badge bg-primary" style="width:150px;">' . $bu['bunit_name'] . '</span>
                                    </label>
                                </div>';
                }

                echo    '<div class="col-12 col-sm-6 mb-1">
                            <div class="d-md-flex d-grid align-items-center gap-1 justify-content-left">
                            ' . $checkbox . '' . $epas . '' . $clearance . '
                            </div>
                        </div>';
            }
        }
        echo    '<div class="text-center text-secondary mb-2"><hr>
                    <h6><strong>Check Store(s) to Transfer To</strong></h6>
                </div>';

        foreach ($bUs as $bu) {

            $hasBu  = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bu['bunit_field']);
            $field  = $bu['bunit_id'] . '|' . $bu['bunit_field'];
            if ($hasBu == 0) {
                echo    '<div class="col-12 col-sm-4 mb-1">
                            <div class="d-md-flex d-grid align-items-center gap-1 justify-content-left">
                                <div class="form-check form-check-primary">
                                    <input class="form-check-input" type="checkbox" name="transfer[]" value="' . $field . '" id="' . $bu['bunit_id'] . '">
                                    <label class="form-check-label" for="' . $bu['bunit_id'] . '">
                                    | <span class="badge bg-gradient-blues" style="width:150px;">' . $bu['bunit_name'] . '</span>
                                    </label>
                                </div>
                            </div>
                        </div>';
            }
        } ?>
        <input type="hidden" name="previous_stores" value="<?= $prev_stores ?>">
        <input type="hidden" name="storeCount" value="<?= $i ?>">
    </div>
    <div class="row">
        <div class="col-sm-4 mt-2">
            <label class="form-label text-secondary">Effective On</label>
            <div class="input-group">
                <i class="input-group-text fadeIn animated bx bx-calendar font-22 text-primary"></i>
                <input type="text" class="form-control form-control-sm datepicker" name="startdate" placeholder="YYYY-MM-DD">
            </div>
        </div>
        <div class="d-md-flex d-grid align-items-center gap-1 justify-content-between mt-2">
            <ul class="list-unstyled">
                <li>
                    <span class="badge text-secondary">
                        <b>NOTE:&nbsp;</b> <span class="badge bg-primary"><i class="fa fa-check"></i></span>
                        &nbsp;|&nbsp;STORE NAME&nbsp;|&nbsp;EPAS RATE&nbsp;|&nbsp;CLEARANCE
                    </span>
                </li>
                <ul>
                    <li>
                        <span class="badge text-secondary">
                            Transfer From refers to the Current Contract Stores
                        </span>
                    </li>
                    <li>
                        <span class="badge text-secondary">
                            Transfer To refers to the New Store(s)
                        </span>
                    </li>
                    <li>
                        <span class="badge text-secondary">
                            Requires Passing EPAS Rate and Signed Off
                        </span>
                    </li>
                    <li>
                        <span class="badge text-secondary">
                            Requires Uploaded Clearance
                        </span>
                    </li>
                </ul>
            </ul>
            <button type="button" class="btn btn-sm btn-primary mt-4 outletButton" onclick="outletClearance()">Transfer Outlet</button>
        </div>
    </div>
<?php
} else if ($request == 'uploadLetter') { ?>
    <div class="row">
        <div class="col-sm-10 mx-auto">
            <div class="card radius-10 border shadow-none">
                <div class="card-body">
                    <h5 class="card-title text-secondary">Choose Image...</h5>
                    <hr>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" name="resignation_letter" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
} else if ($request == 'addResignationForm') { ?>
    <div class="row">
        <div class="col-sm-10 mx-auto">
            <div class="card radius-10 border shadow-none">
                <div class="card-body">
                    <h5 class="card-title text-secondary">Choose Image...</h5>
                    <hr>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" name="resignation_letter" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
} else if ($request == 'supervisorDetails') { ?>

    <ul class="list-group list-group-flush">

        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap mt-0">
            <span class="text-primary"><strong class="text-primary">Employee ID</strong></span>
            <span class="text-secondary"><?= $emp_id ?></span>
        </li>
        <li class="list-group-item justify-content-between" style="display: flex;">
            <span class="text-primary">Company</span>
            <span class="text-secondary text-truncate ps-5" data-toggle="tooltip" title="<?= $company ?>">
                <?= ucwords(strtolower($company)) ?>
            </span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
            <span class="text-primary">Store</span>
            <span class="text-secondary"><?= ucwords(strtolower($store)) ?></span>
        </li>
        <li class="list-group-item justify-content-between" style="display: flex;">
            <span class="text-primary">Department</span>
            <span class="text-secondary text-truncate ps-5" data-toggle="tooltip" title="<?= $department ?>">
                <?= ucwords(strtolower($department)) ?>
            </span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
            <span class="text-primary">Section</span>
            <span class="text-secondary"><?= ucwords(strtolower($section)) ?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
            <span class="text-primary">Position</span>
            <span class="text-secondary"><?= $position ?></span>
        </li>

    </ul>
<?php
} else if ($request == 'generateQbe') {
    $filename = 'QBE_' . date('Ymd');
    header("Cache-Control: public");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-disposition: attachment; filename=" . $filename . ".xls");
?>
    <i>Date Generated : <?= date('F d, Y') ?></i><br>
    <i>Generated Thru : HRMS - Promo V2</i><br>
    <i>Generated by : <?= $this->promo_model->getName_employee3($systemUser) ?></i><br>
    <i>Report Title : Query by Example</i><br><br><br>
    <style type="text/css">
        th,
        td {
            border: 1px dotted black;
            font-size: 12px;
        }
    </style>
    <table>
        <tr>
            <th>No.</th>
            <th>EmployeeID</th>
            <?php
            $currentStatusTh = '';
            $query = $this->promo_model->field_names();
            if (isset($input['applicant'])) {
                foreach ($query as $key => $value) {

                    foreach ($input['applicant'] as $val) {
                        if ($key == $val) {
                            echo '<th>' . $value . '</th>';
                        }
                    }
                }
            }
            $query = $this->promo_model->field_employee();
            if (isset($input['employee'])) {
                foreach ($query as $key => $value) {

                    foreach ($input['employee'] as $val) {
                        if ($key == $val) {
                            if ($val == 'current_status') {
                                $currentStatusTh = '<th>' . $value . '</th>';
                            } else {
                                echo '<th>' . $value . '</th>';
                            }
                        }
                    }
                }
            }
            $query = $this->promo_model->field_company();
            if (isset($input['company'])) {
                foreach ($query as $key => $value) {

                    foreach ($input['company'] as $val) {
                        if ($key == $val) {
                            echo '<th>' . $value . '</th>';
                        }
                    }
                }
            }
            $query = $this->promo_model->field_benefits();
            if (isset($input['benefits'])) {
                foreach ($query as $key => $value) {

                    foreach ($input['benefits'] as $val) {
                        if ($key == $val) {
                            echo '<th>' . $value . '</th>';
                        }
                    }
                }
            }
            echo $currentStatusTh;
            ?>
        </tr>

        <?php
        $query = $this->promo_model->generateQbe($input);
        $no = 0;
        foreach ($query as $row) {
            $no++;
            echo '<tr>';
            echo '<td>' . $no . '</td>';
            echo '<td>' . $row['emp_id'] . '</td>';
            if (isset($input['applicant'])) {
                foreach ($input['applicant'] as $val) {
                    if ($val == 'birthdate') {
                        $age = '';
                        if (!empty($row[$val])) {
                            $birthdayDate = new DateTime($row[$val]);
                            $currentDate = new DateTime();
                            $diff = $currentDate->diff($birthdayDate)->y;
                            $age = ' / ' . $diff . ' years old';
                        }
                        echo '<td>' . $row[$val] . $age . '</td>';
                    } else {
                        echo '<td>' . $row[$val] . '</td>';
                    }
                }
            }
            $currentStatusTd = '';
            if (isset($input['employee'])) {
                foreach ($input['employee'] as $val) {
                    $c = array('app_id' => $row['emp_id']);
                    $appDetails = $this->promo_model->selectAll_tcR('application_details', $c);
                    if ($val == 'current_status') {
                        $currentStatusTd = '<td>' . $row[$val] . '</td>';
                    } else if ($val == 'aeregular' || $val == 'date_brief') {
                        echo '<td>' . $appDetails[$val] . '</td>';
                    } else if ($val == 'date_hired') {
                        $yrs = '';
                        if (!empty($appDetails[$val])) {
                            $yrsInservice = new DateTime($appDetails[$val]);
                            $currentDate = new DateTime();
                            $diff = $currentDate->diff($yrsInservice)->y;
                            $yrs = ' / ' .  $diff . ' year(s)';
                        }
                        echo '<td>' . $appDetails[$val] .  $yrs . '</td>';
                    } else if ($val == 'username') {
                        $c = array('emp_id' => $row['emp_id']);
                        $username = $this->promo_model->selectAll_tcR('users', $c);
                        echo '<td>' . $username[$val] . '</td>';
                    } else {
                        echo '<td>' . $row[$val] . '</td>';
                    }
                }
            }
            if (isset($input['company'])) {
                foreach ($input['company'] as $val) {
                    if ($val == 'bUs') {
                        $bUs = $this->promo_model->locate_promo_bu('asc');
                        $i = 0;
                        $stores = '';
                        foreach ($bUs as $bu) {
                            $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bu['bunit_field']);
                            if ($hasBu > 0) {
                                $i++;
                                $stores = ($i == 1) ? $bu['bunit_acronym'] : $stores . ', ' . $bu['bunit_acronym'];
                            }
                        }
                        echo '<td>' . $stores . '</td>';
                    } else {
                        echo '<td>' . $row[$val] . '</td>';
                    }
                }
            }
            if (isset($input['benefits'])) {
                $c = array('app_id' => $row['emp_id']);
                $benefits = $this->promo_model->selectAll_tcR('applicant_otherdetails', $c);

                foreach ($input['benefits'] as $val) {
                    echo '<td>' . $benefits[$val] . '</td>';
                }
            }
            echo $currentStatusTd;
            echo '</tr>';
        }
        ?>

    </table>
<?php
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $data = array(
        'activity'  => 'Generated QBE - Report title:' . $filename,
        'date'      => $date,
        'time'      => $time,
        'user'      => $systemUser,
        'username'  => $this->session->userdata('username')
    );
    $this->promo_model->insert_tdA('logs', $data);
} else if ($request == 'generatePromoStat') {
    $filename = 'Promo_Statistics_Report_' . date('Ymd');
    header("Cache-Control: public");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-disposition: attachment; filename=" . $filename . ".xls");
?>
    <i><b>Date Generated :</b> <?= date('F d, Y') ?></i><br>
    <i><b>Generated Thru :</b> HRMS - Promo V2</i><br>
    <i><b>Generated By :</b> <?= ucwords(strtolower($input['preparedBy'])) ?></i><br>
    <i><b>Submitted To :</b> <?= ucwords(strtolower($input['submittedTo'])) ?></i><br>
    <i><b>Report Title :</b> Promo Statistics Report</i><br><br><br>
    <style type="text/css">
        th,
        td {
            border: 1px dotted black;
            font-size: 12px;
        }
    </style>
    <table>
        <tr>
            <th>STORES(s)</th>
            <?php
            $condition = array('dept_name!=' => '', 'dept_name!=' => 'EASY FIX');
            $queryTH = $this->promo_model->selectDistinct('dept_name', 'locate_promo_department', $condition);
            foreach ($queryTH as $th) {
                echo '<th>' . $th['dept_name'] . '</th>';
            }
            ?>
            <th>TOTAL</th>
        </tr>
        <?php
        // by 1s
        $totalSum = 0;
        foreach ($input['stores'] as $field) {
            echo '<tr>';
            $c = '';
            $query = $this->promo_model->locate_promo_bu('asc');
            $td = $this->promo_model->selectAll_tcR('locate_promo_business_unit', array('bunit_field' => $field));
            $i = 0;
            foreach ($query as $key => $bu) {
                $i++;
                $and = ' AND ';
                if ($field == $bu['bunit_field']) {
                    $c .= $bu['bunit_field'] . ' = "T"';
                } else {
                    $c .= $bu['bunit_field'] . ' = ""';
                }
                if ($i < count($query)) {
                    $c .= $and;
                }
            }

            echo '<td>' . $td['bunit_acronym'] . '</td>';

            $condition = array('dept_name!=' => '', 'dept_name!=' => 'EASY FIX');
            $queryTH = $this->promo_model->selectDistinct('dept_name', 'locate_promo_department', $condition);
            $overallTotal = 0;
            foreach ($queryTH as $th) {
                $count = 0;
                if ($th['dept_name'] == 'FIXRITE') {
                    $condition = $c . " AND hr_location = 'asc' AND promo_department='" . $th['dept_name'] . "'";
                    $condition .= ' AND current_status="Active" AND promo_type="STATION"';
                    $condition .= ' AND (emp_type = "Promo" OR emp_type = "Promo-NESCO" OR emp_type = "Promo-EasyL")';
                    $countFR = $this->promo_model->promoDetails_wttA($condition, 'employee3', 'promo_record');

                    $condition = $c . " AND promo_department='EASY FIX'";
                    $condition .= ' AND current_status="Active" AND promo_type="STATION"';
                    $condition .= ' AND (emp_type = "Promo" OR emp_type = "Promo-NESCO" OR emp_type = "Promo-EasyL")';
                    $countEF = $this->promo_model->promoDetails_wttA($condition, 'employee3', 'promo_record');

                    $count = count($countFR) + count($countEF);
                    echo '<td>' . $count . '</td>';
                } else {
                    $condition = $c . " AND hr_location = 'asc' AND promo_department='" . $th['dept_name'] . "'";
                    $condition .= ' AND current_status="Active" AND promo_type="STATION"';
                    $condition .= ' AND (emp_type = "Promo" OR emp_type = "Promo-NESCO" OR emp_type = "Promo-EasyL")';
                    $count = count($this->promo_model->promoDetails_wttA($condition, 'employee3', 'promo_record'));
                    echo '<td>' . $count . '</td>';
                }

                $overallTotal += $count;
            }
            $totalSum += $overallTotal;
            echo '<th>' . $overallTotal . '</th>';
            echo '</tr>';
        }
        // by 2s
        $n = count($input['stores']);
        $t = 0;
        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $t++;
                $c = '';
                $query = $this->promo_model->locate_promo_bu('asc');
                $td1 = $this->promo_model->selectAll_tcR('locate_promo_business_unit', array('bunit_field' => $input['stores'][$i]));
                $td2 = $this->promo_model->selectAll_tcR('locate_promo_business_unit', array('bunit_field' => $input['stores'][$j]));
                $a = 0;
                foreach ($query as $key => $bu) {
                    $a++;
                    $and = ' AND ';

                    if ($input['stores'][$i] == $bu['bunit_field'] || $input['stores'][$j] == $bu['bunit_field']) {
                        $c .= $bu['bunit_field'] . ' = "T"';
                    } else {
                        $c .= $bu['bunit_field'] . ' = ""';
                    }


                    if ($a < count($query)) {
                        $c .= $and;
                    }
                }
                echo '<tr>';
                echo '<td>' . $td1['bunit_acronym'] . ', ' . $td2['bunit_acronym'] . '</td>';
                $condition = array('dept_name!=' => '', 'dept_name!=' => 'EASY FIX');
                $queryTH = $this->promo_model->selectDistinct('dept_name', 'locate_promo_department', $condition);
                $overallTotal = 0;
                foreach ($queryTH as $th) {
                    $count = 0;
                    if ($th['dept_name'] == 'FIXRITE') {
                        $condition = $c . " AND promo_department='" . $th['dept_name'] . "'";
                        $condition .= ' AND current_status="Active" AND promo_type="ROVING"';
                        $condition .= ' AND (emp_type = "Promo" OR emp_type = "Promo-NESCO" OR emp_type = "Promo-EasyL")';
                        $countFR = $this->promo_model->promoDetails_wttA($condition, 'employee3', 'promo_record');

                        $condition = $c . " AND promo_department='EASY FIX'";
                        $condition .= ' AND current_status="Active" AND promo_type="ROVING"';
                        $condition .= ' AND (emp_type = "Promo" OR emp_type = "Promo-NESCO" OR emp_type = "Promo-EasyL")';
                        $countEF = $this->promo_model->promoDetails_wttA($condition, 'employee3', 'promo_record');

                        $count = count($countFR) + count($countEF);
                        echo '<td>' . $count . '</td>';
                    } else {
                        $condition = $c . " AND promo_department='" . $th['dept_name'] . "'";
                        $condition .= ' AND current_status="Active" AND promo_type="ROVING"';
                        $condition .= ' AND (emp_type = "Promo" OR emp_type = "Promo-NESCO" OR emp_type = "Promo-EasyL")';
                        $count = count($this->promo_model->promoDetails_wttA($condition, 'employee3', 'promo_record'));
                        echo '<td>' . $count . '</td>';
                    }

                    $overallTotal += $count;
                }
                $totalSum += $overallTotal;
                echo '<th>' . $overallTotal . '</th>';
                echo '</tr>';
            }
        }
        // by 3s
        $n = count($input['stores']);
        $t = 0;
        for ($i = 0; $i < $n - 2; $i++) {
            for ($j = $i + 1; $j < $n - 1; $j++) {
                for ($k = $j + 1; $k < $n; $k++) {
                    $t++;
                    $c = '';
                    $query = $this->promo_model->locate_promo_bu('asc');
                    $table = 'locate_promo_business_unit';
                    $td1 = $this->promo_model->selectAll_tcR($table, array('bunit_field' => $input['stores'][$i]));
                    $td2 = $this->promo_model->selectAll_tcR($table, array('bunit_field' => $input['stores'][$j]));
                    $td3 = $this->promo_model->selectAll_tcR($table, array('bunit_field' => $input['stores'][$k]));
                    $a = 0;
                    foreach ($query as $key => $bu) {
                        $a++;
                        $and = ' AND ';
                        $field = $bu['bunit_field'];
                        $s1 = $input['stores'][$i];
                        $s2 = $input['stores'][$j];
                        $s3 = $input['stores'][$k];
                        if ($s1 == $field || $s2 == $field || $s3 == $field) {
                            $c .= $field . ' = "T"';
                        } else {
                            $c .= $field . ' = ""';
                        }
                        if ($a < count($query)) {
                            $c .= $and;
                        }
                    }
                    echo '<tr>';
                    echo '<td>' . $td1['bunit_acronym'] . ', ' . $td2['bunit_acronym'] . ', ' . $td3['bunit_acronym'] . '</td>';
                    $condition = array('dept_name!=' => '', 'dept_name!=' => 'EASY FIX');
                    $queryTH = $this->promo_model->selectDistinct('dept_name', 'locate_promo_department', $condition);
                    $overallTotal = 0;
                    foreach ($queryTH as $th) {
                        $count = 0;
                        if ($th['dept_name'] == 'FIXRITE') {
                            $condition = $c . " AND promo_department='" . $th['dept_name'] . "'";
                            $condition .= ' AND current_status="Active" AND promo_type="ROVING"';
                            $condition .= ' AND (emp_type = "Promo" OR emp_type = "Promo-NESCO" OR emp_type = "Promo-EasyL")';
                            $countFR = $this->promo_model->promoDetails_wttA($condition, 'employee3', 'promo_record');

                            $condition = $c . " AND promo_department='EASY FIX'";
                            $condition .= ' AND current_status="Active" AND promo_type="ROVING"';
                            $condition .= ' AND (emp_type = "Promo" OR emp_type = "Promo-NESCO" OR emp_type = "Promo-EasyL")';
                            $countEF = $this->promo_model->promoDetails_wttA($condition, 'employee3', 'promo_record');

                            $count = count($countFR) + count($countEF);
                            echo '<td>' . $count . '</td>';
                        } else {
                            $condition = $c . " AND promo_department='" . $th['dept_name'] . "'";
                            $condition .= ' AND current_status="Active" AND promo_type="ROVING"';
                            $condition .= ' AND (emp_type = "Promo" OR emp_type = "Promo-NESCO" OR emp_type = "Promo-EasyL")';
                            $count = count($this->promo_model->promoDetails_wttA($condition, 'employee3', 'promo_record'));
                            echo '<td>' . $count . '</td>';
                        }

                        $overallTotal += $count;
                    }
                    $totalSum += $overallTotal;
                    echo '<th>' . $overallTotal . '</th>';
                    echo '</tr>';
                }
            }
        }
        // by 4s
        $n = count($input['stores']);
        $t = 0;
        for ($i = 0; $i < $n - 2; $i++) {
            for ($j = $i + 1; $j < $n - 1; $j++) {
                for ($k = $j + 1; $k < $n; $k++) {
                    for ($x = $k + 1; $x < $n; $x++) {
                        $t++;
                        $c = '';
                        $query = $this->promo_model->locate_promo_bu('asc');
                        $table = 'locate_promo_business_unit';
                        $td1 = $this->promo_model->selectAll_tcR($table, array('bunit_field' => $input['stores'][$i]));
                        $td2 = $this->promo_model->selectAll_tcR($table, array('bunit_field' => $input['stores'][$j]));
                        $td3 = $this->promo_model->selectAll_tcR($table, array('bunit_field' => $input['stores'][$k]));
                        $td4 = $this->promo_model->selectAll_tcR($table, array('bunit_field' => $input['stores'][$x]));
                        $a = 0;
                        foreach ($query as $key => $bu) {
                            $a++;
                            $and = ' AND ';
                            $field = $bu['bunit_field'];
                            $s1 = $input['stores'][$i];
                            $s2 = $input['stores'][$j];
                            $s3 = $input['stores'][$k];
                            $s4 = $input['stores'][$x];
                            if ($s1 == $field || $s2 == $field || $s3 == $field || $s4 == $field) {
                                $c .= $field . ' = "T"';
                            } else {
                                $c .= $field . ' = ""';
                            }
                            if ($a < count($query)) {
                                $c .= $and;
                            }
                        }
                        echo '<tr>';
                        echo '<td>'
                            . $td1['bunit_acronym'] . ', '
                            . $td2['bunit_acronym'] . ', '
                            . $td3['bunit_acronym'] . ', '
                            . $td4['bunit_acronym'] .
                            '</td>';
                        $condition = array('dept_name!=' => '', 'dept_name!=' => 'EASY FIX');
                        $queryTH = $this->promo_model->selectDistinct('dept_name', 'locate_promo_department', $condition);
                        $overallTotal = 0;
                        foreach ($queryTH as $th) {
                            $count = 0;
                            if ($th['dept_name'] == 'FIXRITE') {
                                $condition = $c . " AND promo_department='" . $th['dept_name'] . "'";
                                $condition .= ' AND current_status="Active" AND promo_type="ROVING"';
                                $condition .= ' AND (emp_type = "Promo" OR emp_type = "Promo-NESCO" OR emp_type = "Promo-EasyL")';
                                $countFR = $this->promo_model->promoDetails_wttA($condition, 'employee3', 'promo_record');

                                $condition = $c . " AND promo_department='EASY FIX'";
                                $condition .= ' AND current_status="Active" AND promo_type="ROVING"';
                                $condition .= ' AND (emp_type = "Promo" OR emp_type = "Promo-NESCO" OR emp_type = "Promo-EasyL")';
                                $countEF = $this->promo_model->promoDetails_wttA($condition, 'employee3', 'promo_record');

                                $count = count($countFR) + count($countEF);
                                echo '<td>' . $count . '</td>';
                            } else {
                                $condition = $c . " AND promo_department='" . $th['dept_name'] . "'";
                                $condition .= ' AND current_status="Active" AND promo_type="ROVING"';
                                $condition .= ' AND (emp_type = "Promo" OR emp_type = "Promo-NESCO" OR emp_type = "Promo-EasyL")';
                                $count = count($this->promo_model->promoDetails_wttA($condition, 'employee3', 'promo_record'));
                                echo '<td>' . $count . '</td>';
                            }

                            $overallTotal += $count;
                        }
                        $totalSum += $overallTotal;
                        echo '<th>' . $overallTotal . '</th>';
                        echo '</tr>';
                    }
                }
            }
        }
        // by 5s
        $n = count($input['stores']);
        $t = 0;
        for ($i = 0; $i < $n - 2; $i++) {
            for ($j = $i + 1; $j < $n - 1; $j++) {
                for ($k = $j + 1; $k < $n; $k++) {
                    for ($x = $k + 1; $x < $n; $x++) {
                        for ($y = $x + 1; $y < $n; $y++) {
                            $t++;
                            $c = '';
                            $query = $this->promo_model->locate_promo_bu('asc');
                            $table = 'locate_promo_business_unit';
                            $td1 = $this->promo_model->selectAll_tcR($table, array('bunit_field' => $input['stores'][$i]));
                            $td2 = $this->promo_model->selectAll_tcR($table, array('bunit_field' => $input['stores'][$j]));
                            $td3 = $this->promo_model->selectAll_tcR($table, array('bunit_field' => $input['stores'][$k]));
                            $td4 = $this->promo_model->selectAll_tcR($table, array('bunit_field' => $input['stores'][$x]));
                            $td5 = $this->promo_model->selectAll_tcR($table, array('bunit_field' => $input['stores'][$x]));
                            $a = 0;
                            foreach ($query as $key => $bu) {
                                $a++;
                                $and = ' AND ';
                                $field = $bu['bunit_field'];
                                $s1 = $input['stores'][$i];
                                $s2 = $input['stores'][$j];
                                $s3 = $input['stores'][$k];
                                $s4 = $input['stores'][$x];
                                $s5 = $input['stores'][$y];
                                if ($s1 == $field || $s2 == $field || $s3 == $field || $s4 == $field || $s5 == $field) {
                                    $c .= $field . ' = "T"';
                                } else {
                                    $c .= $field . ' = ""';
                                }
                                if ($a < count($query)) {
                                    $c .= $and;
                                }
                            }
                            echo '<tr>';
                            echo '<td>'
                                . $td1['bunit_acronym'] . ', '
                                . $td2['bunit_acronym'] . ', '
                                . $td3['bunit_acronym'] . ', '
                                . $td4['bunit_acronym'] . ', '
                                . $td5['bunit_acronym'] .
                                '</td>';
                            $condition = array('dept_name!=' => '', 'dept_name!=' => 'EASY FIX');
                            $queryTH = $this->promo_model->selectDistinct('dept_name', 'locate_promo_department', $condition);
                            $overallTotal = 0;
                            foreach ($queryTH as $th) {
                                $count = 0;
                                if ($th['dept_name'] == 'FIXRITE') {
                                    $condition = $c . " AND promo_department='" . $th['dept_name'] . "'";
                                    $condition .= ' AND current_status="Active" AND promo_type="ROVING"';
                                    $condition .= ' AND (emp_type = "Promo" OR emp_type = "Promo-NESCO" OR emp_type = "Promo-EasyL")';
                                    $countFR = $this->promo_model->promoDetails_wttA($condition, 'employee3', 'promo_record');

                                    $condition = $c . " AND promo_department='EASY FIX'";
                                    $condition .= ' AND current_status="Active" AND promo_type="ROVING"';
                                    $condition .= ' AND (emp_type = "Promo" OR emp_type = "Promo-NESCO" OR emp_type = "Promo-EasyL")';
                                    $countEF = $this->promo_model->promoDetails_wttA($condition, 'employee3', 'promo_record');

                                    $count = count($countFR) + count($countEF);
                                    echo '<td>' . $count . '</td>';
                                } else {
                                    $condition = $c . " AND promo_department='" . $th['dept_name'] . "'";
                                    $condition .= ' AND current_status="Active" AND promo_type="ROVING"';
                                    $condition .= ' AND (emp_type = "Promo" OR emp_type = "Promo-NESCO" OR emp_type = "Promo-EasyL")';
                                    $count = count($this->promo_model->promoDetails_wttA($condition, 'employee3', 'promo_record'));
                                    echo '<td>' . $count . '</td>';
                                }

                                $overallTotal += $count;
                            }
                            $totalSum += $overallTotal;
                            echo '<th>' . $overallTotal . '</th>';
                            echo '</tr>';
                        }
                    }
                }
            }
        }
        // by 6s
        $n = count($input['stores']);
        $t = 0;
        for ($i = 0; $i < $n - 2; $i++) {
            for ($j = $i + 1; $j < $n - 1; $j++) {
                for ($k = $j + 1; $k < $n; $k++) {
                    for ($x = $k + 1; $x < $n; $x++) {
                        for ($y = $x + 1; $y < $n; $y++) {
                            for ($z = $y + 1; $z < $n; $z++) {
                                $t++;
                                $c = '';
                                $query = $this->promo_model->locate_promo_bu('asc');
                                $table = 'locate_promo_business_unit';
                                $td1 = $this->promo_model->selectAll_tcR($table, array('bunit_field' => $input['stores'][$i]));
                                $td2 = $this->promo_model->selectAll_tcR($table, array('bunit_field' => $input['stores'][$j]));
                                $td3 = $this->promo_model->selectAll_tcR($table, array('bunit_field' => $input['stores'][$k]));
                                $td4 = $this->promo_model->selectAll_tcR($table, array('bunit_field' => $input['stores'][$x]));
                                $td5 = $this->promo_model->selectAll_tcR($table, array('bunit_field' => $input['stores'][$y]));
                                $td6 = $this->promo_model->selectAll_tcR($table, array('bunit_field' => $input['stores'][$z]));
                                $a = 0;
                                foreach ($query as $key => $bu) {
                                    $a++;
                                    $and = ' AND ';
                                    $field = $bu['bunit_field'];
                                    $s1 = $input['stores'][$i];
                                    $s2 = $input['stores'][$j];
                                    $s3 = $input['stores'][$k];
                                    $s4 = $input['stores'][$x];
                                    $s5 = $input['stores'][$y];
                                    $s6 = $input['stores'][$y];
                                    if ($s1 == $field || $s2 == $field || $s3 == $field || $s4 == $field || $s5 == $field || $s6 == $field) {
                                        $c .= $field . ' = "T"';
                                    } else {
                                        $c .= $field . ' = ""';
                                    }
                                    if ($a < count($query)) {
                                        $c .= $and;
                                    }
                                }
                                echo '<tr>';
                                echo '<td>'
                                    . $td1['bunit_acronym'] . ', '
                                    . $td2['bunit_acronym'] . ', '
                                    . $td3['bunit_acronym'] . ', '
                                    . $td4['bunit_acronym'] . ', '
                                    . $td5['bunit_acronym'] . ', '
                                    . $td6['bunit_acronym'] .
                                    '</td>';
                                $condition = array('dept_name!=' => '', 'dept_name!=' => 'EASY FIX');
                                $queryTH = $this->promo_model->selectDistinct('dept_name', 'locate_promo_department', $condition);
                                $overallTotal = 0;
                                foreach ($queryTH as $th) {
                                    $count = 0;
                                    if ($th['dept_name'] == 'FIXRITE') {
                                        $condition = $c . " AND promo_department='" . $th['dept_name'] . "'";
                                        $condition .= ' AND current_status="Active" AND promo_type="ROVING"';
                                        $condition .= ' AND (emp_type = "Promo" OR emp_type = "Promo-NESCO" OR emp_type = "Promo-EasyL")';
                                        $countFR = $this->promo_model->promoDetails_wttA($condition, 'employee3', 'promo_record');

                                        $condition = $c . " AND promo_department='EASY FIX'";
                                        $condition .= ' AND current_status="Active" AND promo_type="ROVING"';
                                        $condition .= ' AND (emp_type = "Promo" OR emp_type = "Promo-NESCO" OR emp_type = "Promo-EasyL")';
                                        $countEF = $this->promo_model->promoDetails_wttA($condition, 'employee3', 'promo_record');

                                        $count = count($countFR) + count($countEF);
                                        echo '<td>' . $count . '</td>';
                                    } else {
                                        $condition = $c . " AND promo_department='" . $th['dept_name'] . "'";
                                        $condition .= ' AND current_status="Active" AND promo_type="ROVING"';
                                        $condition .= ' AND (emp_type = "Promo" OR emp_type = "Promo-NESCO" OR emp_type = "Promo-EasyL")';
                                        $count = count($this->promo_model->promoDetails_wttA($condition, 'employee3', 'promo_record'));
                                        echo '<td>' . $count . '</td>';
                                    }

                                    $overallTotal += $count;
                                }
                                $totalSum += $overallTotal;
                                echo '<th>' . $overallTotal . '</th>';
                                echo '</tr>';
                            }
                        }
                    }
                }
            }
        }
        echo '<tr><th colspan="9">OVERALL TOTAL</th><th>' . $totalSum . '</th></tr>'
        ?>
    </table>
<?php
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $data = array(
        'activity'  => 'Generate Promo Statistic Report for ' . date('F d, Y'),
        'date'      => $date,
        'time'      => $time,
        'user'      => $systemUser,
        'username'  => $this->session->userdata('username')
    );
    $this->promo_model->insert_tdA('logs', $data);
} else if ($request == 'generateMonthlyStat') {
    $filename = 'Monthly_Status_Report_' . date('Ymd');
    header("Cache-Control: public");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-disposition: attachment; filename=" . $filename . ".xls");
?>
    <i><b>Date Generated :</b> <?= date('F d, Y') ?></i><br>
    <i><b>Generated Thru :</b> HRMS - Promo V2</i><br>
    <i><b>Generated By :</b> <?= $this->promo_model->getName_employee3($systemUser) ?></i><br>
    <i><b>Report Title :</b> Monthly Status Report as of <?= date('F d, Y', strtotime($input['dateasof'])) ?></i><br><br><br>
    <style type="text/css">
        th,
        td {
            border: 1px dotted black;
            font-size: 12px;
        }
    </style>
    <table>
        <tr>
            <th>NO.</th>
            <th>EMPLOYEE ID</th>
            <th>NAME</th>
            <th>COMPANY</th>
            <th>STORE(s)</th>
            <th>DEPARTMENT</th>
            <th>POSITION</th>
            <th>PROMO TYPE</th>
            <th>STARTDATE</th>
            <th>EOCDATE</th>
            <th>CURRENT STATUS</th>
        </tr>
        <?php

        $where = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
        if (!empty($input['store'])) {
            if ($input['store'] == 'allbu') {
                $query = $this->promo_model->locate_promo_bu('asc');
                $i = 0;
                $where .= ' AND (';
                foreach ($query as $key => $field) {
                    $i++;
                    $where .= ($i == 1) ? "$field[bunit_field] = 'T'" : " OR $field[bunit_field] = 'T'";
                }
                $where .= ')';
            } else {
                $bunit = explode('|', $input['store']);
                $where .= " AND $bunit[1]= 'T'";
            }
        }
        if (!empty($input['promo_department'])) {
            $where .= " AND promo_department = '$input[promo_department]'";
        } else {
            if ($input['store'] == 'allbu') {
                $query = $this->promo_model->locate_promo_bu('asc');
                $bunit_id = [];
                foreach ($query as $key => $field) {
                    $bunit_id[] = $field['bunit_id'];
                }
                $dept = $this->promo_model->whereIN_stcd('distinct', 'dept_name', 'locate_promo_department', 'bunit_id', $bunit_id);
                $where .= ' AND (';
                $i = 0;
                foreach ($dept as $key => $value) {
                    $i++;
                    $where .= ($i == 1) ? "promo_department = '$value[dept_name]'" : " OR promo_department = '$value[dept_name]'";
                }
                $where .= ')';
            }
        }
        if (!empty($input['promo_company'])) {
            $where .= " AND promo_company = '$input[promo_company]'";
        }
        if (!empty($input['current_status'])) {
            $where .= " AND current_status = '$input[current_status]' AND hr_location = 'asc'";
        }
        if (!empty($input['dateasof'])) {
            $where .= " AND startdate <= '$input[dateasof]'";
        }
        $no = 0;
        $table1 = 'employee3';
        $table2 = 'promo_record';
        $join = 't1.emp_id=t2.emp_id AND t1.record_no=t2.record_no';
        $order = 'name|ASC';
        $query = $this->promo_model->join_ttjcoA('*', $table1, $table2, $join, null, $where, $order, null);
        foreach ($query as $row) {
            $bUs = $this->promo_model->locate_promo_bu('asc');
            $i = 0;
            $stores = '';
            foreach ($bUs as $bu) {
                $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bu['bunit_field']);
                if ($hasBu > 0) {
                    $i++;
                    $stores = ($i == 1) ? $bu['bunit_acronym'] : $stores . ', ' . $bu['bunit_acronym'];
                }
            }
            $no++;
            echo '<tr>';
            echo '<td>' . $no . '</td>';
            echo '<td>' . $row['emp_id'] . '</td>';
            echo '<td>' . ucwords(strtolower($row['name'])) . '</td>';
            echo '<td>' . $row['promo_company'] . '</td>';
            echo '<td>' . $stores . '</td>';
            echo '<td>' . $row['promo_department'] . '</td>';
            echo '<td>' . $row['position'] . '</td>';
            echo '<td>' . $row['promo_type'] . '</td>';
            echo '<td>' . $row['startdate'] . '</td>';
            echo '<td>' . $row['eocdate'] . '</td>';
            echo '<td>' . $row['current_status'] . '</td>';
            echo '</tr>';
        }
        ?>
    </table>
<?php
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $data = array(
        'activity'  => 'Generate Monthly Status Report as of ' . date('F d, Y', strtotime($input['dateasof'])),
        'date'      => $date,
        'time'      => $time,
        'user'      => $systemUser,
        'username'  => $this->session->userdata('username')
    );
    $this->promo_model->insert_tdA('logs', $data);
} else if ($request == 'generateAnnualStat') {
    $filename = 'Annual_Status_Report_' . date('Ymd');
    header("Cache-Control: public");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-disposition: attachment; filename=" . $filename . ".xls");
?>
    <i><b>Date Generated :</b> <?= date('F d, Y') ?></i><br>
    <i><b>Generated Thru :</b> HRMS - Promo V2</i><br>
    <i><b>Generated By :</b> <?= $this->promo_model->getName_employee3($systemUser) ?></i><br>
    <i><b>Report Title :</b> Annual Status Report from <?= date('F d, Y', strtotime($input['datefrom'])) ?> to <?= date('F d, Y', strtotime($input['dateto'])) ?><br><br><br>
        <style type="text/css">
            th,
            td {
                border: 1px dotted black;
                font-size: 12px;
            }
        </style>
        <table>
            <tr>
                <th>NO.</th>
                <th>EMPLOYEE ID</th>
                <th>NAME</th>
                <th>COMPANY</th>
                <th>STORE(s)</th>
                <th>DEPARTMENT</th>
                <th>POSITION</th>
                <th>PROMO TYPE</th>
                <th>STARTDATE</th>
                <th>EOCDATE</th>
                <th>CURRENT STATUS</th>
            </tr>
            <?php

            $where = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
            if (!empty($input['store'])) {
                if ($input['store'] == 'allbu') {
                    $query = $this->promo_model->locate_promo_bu('asc');
                    $i = 0;
                    $where .= ' AND (';
                    foreach ($query as $key => $field) {
                        $i++;
                        $where .= ($i == 1) ? "$field[bunit_field] = 'T'" : " OR $field[bunit_field] = 'T'";
                    }
                    $where .= ')';
                } else {
                    $bunit = explode('|', $input['store']);
                    $where .= " AND $bunit[1]= 'T'";
                }
            }
            if (!empty($input['promo_department'])) {
                $where .= " AND promo_department = '$input[promo_department]'";
            } else {
                if ($input['store'] == 'allbu') {
                    $query = $this->promo_model->locate_promo_bu('asc');
                    $bunit_id = [];
                    foreach ($query as $key => $field) {
                        $bunit_id[] = $field['bunit_id'];
                    }
                    $dept = $this->promo_model->whereIN_stcd('distinct', 'dept_name', 'locate_promo_department', 'bunit_id', $bunit_id);
                    $where .= ' AND (';
                    $i = 0;
                    foreach ($dept as $key => $value) {
                        $i++;
                        $where .= ($i == 1) ? "promo_department = '$value[dept_name]'" : " OR promo_department = '$value[dept_name]'";
                    }
                    $where .= ')';
                }
            }
            if (!empty($input['promo_company'])) {
                $where .= " AND promo_company = '$input[promo_company]'";
            }
            $where .= " AND current_status = '$input[current_status]' AND hr_location = 'asc'";
            $where .= " AND (startdate BETWEEN '$input[datefrom]' AND '$input[dateto]')";

            $no = 0;
            $table1 = 'employee3';
            $table2 = 'promo_record';
            $join = 't1.emp_id=t2.emp_id AND t1.record_no=t2.record_no';
            $order = "name|ASC";
            $query = $this->promo_model->join_ttjcoA('*', $table1, $table2, $join, null, $where, $order, null);
            foreach ($query as $row) {
                $bUs = $this->promo_model->locate_promo_bu('asc');
                $i = 0;
                $stores = '';
                foreach ($bUs as $bu) {
                    $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bu['bunit_field']);
                    if ($hasBu > 0) {
                        $i++;
                        $stores = ($i == 1) ? $bu['bunit_acronym'] : $stores . ', ' . $bu['bunit_acronym'];
                    }
                }
                $no++;
                echo '<tr>';
                echo '<td>' . $no . '</td>';
                echo '<td>' . $row['emp_id'] . '</td>';
                echo '<td>' . ucwords(strtolower($row['name'])) . '</td>';
                echo '<td>' . $row['promo_company'] . '</td>';
                echo '<td>' . $stores . '</td>';
                echo '<td>' . $row['promo_department'] . '</td>';
                echo '<td>' . $row['position'] . '</td>';
                echo '<td>' . $row['promo_type'] . '</td>';
                echo '<td>' . $row['startdate'] . '</td>';
                echo '<td>' . $row['eocdate'] . '</td>';
                echo '<td>' . $row['current_status'] . '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    <?php
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $data = array(
        'activity'  => 'Generate Annual Status Report from ' . date('F d, Y', strtotime($input['datefrom'])) . ' to ' . date('F d, Y', strtotime($input['dateto'])),
        'date'      => $date,
        'time'      => $time,
        'user'      => $systemUser,
        'username'  => $this->session->userdata('username')
    );
    $this->promo_model->insert_tdA('logs', $data);
} else if ($request == 'generateDueContractsExcel') {
    $filename = 'Due_Contracts_Report_' . date('Ymd');
    header("Cache-Control: public");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-disposition: attachment; filename=" . $filename . ".xls");
    ?>
        <i><b>Date Generated :</b> <?= date('F d, Y') ?></i><br>
        <i><b>Generated Thru :</b> HRMS - Promo V2</i><br>
        <i><b>Generated By :</b> <?= $this->promo_model->getName_employee3($systemUser) ?></i><br>
        <i><b>Report Title :</b> Due Contracts Report</i><br><br><br>
        <style type="text/css">
            th,
            td {
                border: 1px dotted black;
                font-size: 12px;
            }
        </style>
        <table>
            <tr>
                <th>NO.</th>
                <th>NAME</th>
                <th>EMPTYPE</th>
                <th>STARTDATE</th>
                <th>EOCDATE</th>
                <th>POSITION</th>
                <th>STORE(S)</th>
                <th>DEPARTMENT</th>
            </tr>
            <?php

            $where = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
            if (!empty($input['store'])) {
                if ($input['store'] == 'allbu') {
                    $query = $this->promo_model->locate_promo_bu('asc');
                    $i = 0;
                    $where .= ' AND (';
                    foreach ($query as $key => $field) {
                        $i++;
                        $where .= ($i == 1) ? "$field[bunit_field] = 'T'" : " OR $field[bunit_field] = 'T'";
                    }
                    $where .= ')';
                } else {
                    $bunit = explode('|', $input['store']);
                    $where .= " AND $bunit[1]= 'T'";
                }
            }
            if (!empty($input['promo_department'])) {
                $where .= " AND promo_department = '$input[promo_department]'";
            } else {
                if ($input['store'] == 'allbu') {
                    $query = $this->promo_model->locate_promo_bu('asc');
                    $bunit_id = [];
                    foreach ($query as $key => $field) {
                        $bunit_id[] = $field['bunit_id'];
                    }
                    $dept = $this->promo_model->whereIN_stcd('distinct', 'dept_name', 'locate_promo_department', 'bunit_id', $bunit_id);
                    $where .= ' AND (';
                    $i = 0;
                    foreach ($dept as $key => $value) {
                        $i++;
                        $where .= ($i == 1) ? "promo_department = '$value[dept_name]'" : " OR promo_department = '$value[dept_name]'";
                    }
                    $where .= ')';
                }
            }
            $where .= " AND current_status = 'Active' AND hr_location = 'asc'";
            $where .= " AND eocdate < '" . date('Y-m-d') . "'";

            $no = 0;
            $table1 = 'employee3';
            $table2 = 'promo_record';
            $join = 't1.emp_id=t2.emp_id AND t1.record_no=t2.record_no';
            $order = "name|ASC";
            $query = $this->promo_model->join_ttjcoA('*', $table1, $table2, $join, null, $where, $order, null);
            foreach ($query as $row) {
                $bUs = $this->promo_model->locate_promo_bu('asc');
                $i = 0;
                $stores = '';
                foreach ($bUs as $bu) {
                    $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bu['bunit_field']);
                    if ($hasBu > 0) {
                        $i++;
                        $stores = ($i == 1) ? $bu['bunit_acronym'] : $stores . ', ' . $bu['bunit_acronym'];
                    }
                }
                $no++;
                echo '<tr>';
                echo '<td>' . $no . '</td>';
                echo '<td>' . ucwords(strtolower($row['name'])) . '</td>';
                echo '<td>' . $row['emp_type'] . '</td>';
                echo '<td>' . $row['startdate'] . '</td>';
                echo '<td>' . $row['eocdate'] . '</td>';
                echo '<td>' . $row['position'] . '</td>';
                echo '<td>' . $stores . '</td>';
                echo '<td>' . $row['promo_department'] . '</td>';
                echo '</tr>';
            }

            ?>
        </table>
    <?php
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $data = array(
        'activity'  => 'Generate Due Contracts Report as of ' . date('F d, Y'),
        'date'      => $date,
        'time'      => $time,
        'user'      => $systemUser,
        'username'  => $this->session->userdata('username')
    );
    $this->promo_model->insert_tdA('logs', $data);
} else if ($request == 'generateDutySched') {
    $filename = 'Duty_Schedule_Report_' . date('Ymd');
    header("Cache-Control: public");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-disposition: attachment; filename=" . $filename . ".xls");
    ?>
        <i><b>Date Generated :</b> <?= date('F d, Y') ?></i><br>
        <i><b>Generated Thru :</b> HRMS - Promo V2</i><br>
        <i><b>Generated By :</b> <?= $this->promo_model->getName_employee3($systemUser) ?></i><br>
        <i><b>Report Title :</b> Duty Schedule Report</i><br><br><br>
        <style type="text/css">
            th,
            td {
                border: 1px dotted black;
                font-size: 12px;
            }
        </style>
        <table>
            <?php
            if (!empty($input['store'])) {

                $store_id = explode('|', $input['store']);
            }
            $department = [];
            if (!empty($input['promo_department'])) {
                $department[] = $input['promo_department'];
            } else {
                $dept = $this->promo_model->whereIN_stcd('distinct', 'dept_name', 'locate_promo_department', 'bunit_id', $store_id[0]);
                foreach ($dept as $value) {
                    $department[] = $value['dept_name'];
                }
            }
            $sched = $this->promo_model->selectAll_tcR('locate_promo_business_unit', array('bunit_id' => $store_id[0]));
            foreach ($department as $deptRow) {
                echo    '<tr>
                        <td colspan="11" style="color: #F11F1F;"><b><i>' . $deptRow . '</i></b></td>
                    </tr>';
                echo    '<tr>
                        <th>NO.</th>
                        <th>NAME</th>
                        <th>COMPANY</th>
                        <th>POSITION</th>
                        <th>DEPLOYMENT</th>
                        <th>SPECIFIC DAYS</th>
                        <th>TIME SCHEDULE</th>
                        <th>DAYOFF</th>
                        <th>CUTOFF</th>
                        <th>INCLUSIVE DATES</th>
                        <th>EOCDATE</th>
                    </tr>';

                $where = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
                if (!empty($input['store'])) {

                    $bunit = explode('|', $input['store']);
                    $where .= " AND $bunit[1]= 'T'";
                }
                $where .= " AND promo_department = '$deptRow'";

                if (!empty($input['promo_company'])) {
                    $where .= " AND promo_company = '$input[promo_company]'";
                }
                if (!empty($input['current_status'])) {
                    $where .= " AND current_status = '$input[current_status]'";
                }
                // echo $where;
                $no = 0;
                $table1 = 'employee3';
                $table2 = 'promo_record';
                $join = 't1.emp_id=t2.emp_id AND t1.record_no=t2.record_no';
                $order = 'name|ASC';
                $query = $this->promo_model->join_ttjcoA('*', $table1, $table2, $join, null, $where, $order, null);
                foreach ($query as $row) {
                    $no++;
                    $bUs = $this->promo_model->locate_promo_bu('asc');
                    $i = 0;
                    $stores = '';
                    foreach ($bUs as $bu) {
                        $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bu['bunit_field']);
                        if ($hasBu > 0) {
                            $i++;
                            $stores = ($i == 1) ? $bu['bunit_acronym'] : $stores . ', ' . $bu['bunit_acronym'];
                        }
                    }
                    if ($row[$sched['bunit_specialDays']] != '') {

                        $dutySched = $row[$sched['bunit_dutySched']] . ' & ' . $row[$sched['bunit_specialSched']];
                        $dutyDays = $row[$sched['bunit_dutyDays']] . ' & ' . $row[$sched['bunit_specialDays']];
                    } else {

                        $dutySched = $row[$sched['bunit_dutySched']];
                        $dutyDays = $row[$sched['bunit_dutyDays']];
                    }
                    echo '<tr>';
                    echo '<td>' . $no . '</td>';
                    echo '<td>' . ucwords(strtolower($row['name'])) . '</td>';
                    echo '<td>' . $row['promo_company'] . '</td>';
                    echo '<td>' . $row['position'] . '</td>';
                    echo '<td>' . $row['promo_type'] . '</td>';
                    echo '<td>' . $dutyDays . '</td>';
                    echo '<td>' . $dutySched . '</td>';
                    echo '<td>' . $row['dayoff'] . '</td>';
                    echo '<td>' . $row['cutoff'] . '</td>';
                    echo '<td>' . date('m/d/Y', strtotime($row['startdate'])) . ' - ' . date('m/d/Y', strtotime($row['eocdate'])) . '</td>';
                    echo '<td>' . date('m/d/Y', strtotime($row['eocdate'])) . '</td>';
                    echo '</tr>';
                }
                echo '<tr></tr><tr></tr>';
            }
            ?>
        </table>
    <?php
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $data = array(
        'activity'  => 'Generate Duty Schedule Report as of ' . date('F d, Y'),
        'date'      => $date,
        'time'      => $time,
        'user'      => $systemUser,
        'username'  => $this->session->userdata('username')
    );
    $this->promo_model->insert_tdA('logs', $data);
} else if ($request == 'dutySchedList') { ?>
        <table id="dutySched_table" class="table table-sm table-hover" width="100%">
            <thead>
                <tr>
                    <th class="act"></th>
                    <th class="name">Name</th>
                    <th class="sd">SpecificDays</th>
                    <th class="ts">TimeSchedule</th>
                    <th class="do">DayOff</th>
                    <th class="co">CutOff</th>
                </tr>
            </thead>
        </table>
    <?php
} else if ($request == 'generateTermRepExcel') {
    $filename = 'Termination_of_Contract_Report_' . date('Ymd');
    header("Cache-Control: public");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-disposition: attachment; filename=" . $filename . ".xls");
    ?>
        <i><b>Date Generated :</b> <?= date('F d, Y') ?></i><br>
        <i><b>Generated Thru :</b> HRMS - Promo V2</i><br>
        <i><b>Generated By :</b> <?= $this->promo_model->getName_employee3($systemUser) ?></i><br>
        <i><b>Report Title :</b> Termination of Contract Report</i><br><br><br>
        <style type="text/css">
            th,
            td {
                border: 1px dotted black;
                font-size: 12px;
            }
        </style>
        <table>
            <?php
            if (!empty($input['month'])) {
                $month = explode('|', $input['month']);
                $y = date('Y') + 1;
                $year = ($month[1] == $y) ? $month[1] : date('Y');
                $m = ($month[1] == $y) ? 'January' : $month[1];

                echo '<tr><th colspan="11">END OF CONTRACT LIST for ' . $m . ' ' . $year . '</th></tr>';
            }
            ?>
            <tr>
                <th>NO.</th>
                <th>EMP ID</th>
                <th>NAME</th>
                <th>COMPANY</th>
                <th>STORE(S)</th>
                <th>DEPARTMENT</th>
                <th>POSITION</th>
                <th>STARTDATE</th>
                <th>EOCDATE</th>
                <th>COMPANY DURATION</th>
                <th>PROMOTYPE</th>
            </tr>
            <?php

            $where = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
            if (!empty($input['store'])) {
                $bunit = explode('|', $input['store']);
                $where .= " AND $bunit[1]= 'T'";
            }
            if (!empty($input['promo_department'])) {
                $where .= " AND promo_department = '$input[promo_department]'";
            }
            if (!empty($input['month'])) {
                $month = explode('|', $input['month']);
                $y = date('Y') + 1;
                if ($month[1] == $y) {
                    $year = $month[1];
                    $date = $year . '-' . $month[0];
                } else {
                    $year = date('Y');
                    $date = $year . '-' . $month[0];
                }
                $where .= " AND eocdate LIKE '%$date%'";
            }
            if (!empty($input['promo_company'])) {
                $where .= " AND promo_company = '$input[promo_company]'";
            }
            $where .= " AND current_status = 'Active' AND hr_location = 'asc'";
            $no = 0;
            $table1 = 'employee3';
            $table2 = 'promo_record';
            $join = 't1.emp_id=t2.emp_id AND t1.record_no=t2.record_no';
            $order = "name|ASC";
            $query = $this->promo_model->join_ttjcoA('*', $table1, $table2, $join, null, $where, $order, null);
            foreach ($query as $row) {
                $no++;
                $bUs = $this->promo_model->locate_promo_bu('asc');
                $i = 0;
                $stores = '';
                foreach ($bUs as $bu) {
                    $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bu['bunit_field']);
                    if ($hasBu > 0) {
                        $i++;
                        $stores = ($i == 1) ? $bu['bunit_name'] : $stores . ', ' . $bu['bunit_name'];
                    }
                }
                if ($row['company_duration'] == '0000-00-00') {

                    $companyDuration = '';
                } else {

                    $companyDuration = date('m/d/Y', strtotime($row['company_duration']));
                }
                echo '<tr>';
                echo '<td>' . $no . '</td>';
                echo '<td>' . $row['emp_id'] . '</td>';
                echo '<td>' . ucwords(strtolower($row['name'])) . '</td>';
                echo '<td>' . $row['promo_company'] . '</td>';
                echo '<td>' . ucwords(strtolower($stores)) . '</td>';
                echo '<td>' . $row['promo_department'] . '</td>';
                echo '<td>' . $row['position'] . '</td>';
                echo '<td>' . date('m/d/Y', strtotime($row['startdate'])) . '</td>';
                echo '<td>' . date('m/d/Y', strtotime($row['eocdate'])) . '</td>';
                echo '<td>' . $companyDuration . '</td>';
                echo '<td>' . $row['promo_type'] . '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    <?php
} else if ($request == 'termRepList') { ?>
        <style type="text/css">
            th,
            td {
                font-size: 12px;
            }

            .sliders {
                position: relative;
                max-height: 380px;

            }

            #terminationRepList thead {
                position: sticky;
                top: 0;
                background-color: #fff;
                z-index: 1;
            }
        </style>
        <div class="sliders">
            <table id="terminationRepList" class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th style="width: 15%;">
                            <input class="form-check-input" name="checkAll" type="checkbox" id="empID" onclick="checkAll()">
                            <label class="form-check-label" for="empID">
                                EmpID
                            </label>
                        </th>
                        <th style="width: 25%;">Name</th>
                        <th style="width: 20%;">Company</th>
                        <th style="width: 15%;">Store(s)</th>
                        <th style="width: 5%;">Department</th>
                        <th style="width: 5%;">Position</th>
                        <th style="width: 5%;">Startdate</th>
                        <th style="width: 5%;">EOCdate</th>
                        <th style="width: 5%;">PromoType</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $where = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
                    if (!empty($input['store'])) {
                        $bunit = explode('|', $input['store']);
                        $where .= " AND $bunit[1]= 'T'";
                    }
                    if (!empty($input['promo_department'])) {
                        $where .= " AND promo_department = '$input[promo_department]'";
                    }
                    if (!empty($input['month'])) {
                        $month = explode('|', $input['month']);
                        $y = date('Y') + 1;
                        if ($month[1] == $y) {
                            $year = $month[1];
                            $date = $year . '-' . $month[0];
                        } else {
                            $year = date('Y');
                            $date = $year . '-' . $month[0];
                        }
                        $where .= " AND eocdate LIKE '%$date%'";
                    }
                    if (!empty($input['promo_company'])) {
                        $where .= " AND promo_company = '$input[promo_company]'";
                    }
                    $where .= " AND current_status = 'Active' AND hr_location = 'asc'";
                    $no = 0;
                    $table1 = 'employee3';
                    $table2 = 'promo_record';
                    $join = 't1.emp_id=t2.emp_id AND t1.record_no=t2.record_no';
                    $order = "name|ASC";
                    $query = $this->promo_model->join_ttjcoA('*', $table1, $table2, $join, null, $where, $order, null);
                    foreach ($query as $row) {
                        $no++;
                        $bUs = $this->promo_model->locate_promo_bu('asc');
                        $i = 0;
                        $stores = '';
                        foreach ($bUs as $bu) {
                            $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bu['bunit_field']);
                            if ($hasBu > 0) {
                                $i++;
                                $stores = ($i == 1) ? $bu['bunit_acronym'] : $stores . ', ' . $bu['bunit_acronym'];
                            }
                        }

                        $chkBox =   '<div class="form-check">
						        	<input class="form-check-input" name="emp_id[]" type="checkbox" value="' . $row['emp_id'] . '" id="' . $row['emp_id'] . '">
						        	<label class="form-check-label" for="' . $row['emp_id'] . '">
                                    ' . $row['emp_id'] . '
						        	</label>
					            </div>';
                        echo '<tr>';
                        echo '<td>' . $chkBox . '</td>';
                        echo '<td>' . ucwords(strtolower($row['name'])) . '</td>';
                        echo '<td>' . $row['promo_company'] . '</td>';
                        echo '<td>' . $stores . '</td>';
                        echo '<td>' . $row['promo_department'] . '</td>';
                        echo '<td>' . $row['position'] . '</td>';
                        echo '<td>' . date('m/d/Y', strtotime($row['startdate'])) . '</td>';
                        echo '<td>' . date('m/d/Y', strtotime($row['eocdate'])) . '</td>';
                        echo '<td>' . $row['promo_type'] . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php
} else if ($request == 'promoStat') {
    $type = '';
    $dept = '';
    if ($input['emp_type'] != 'allPromo') {
        $type = ' | ' . $input['emp_type'];
    }
    if ($input['department'] != 'allDept') {
        $dept = ' | ' . $input['department'];
    }
    $store = $this->promo_model->selectAll_tcR('locate_promo_business_unit', array('bunit_field' => $input['field']))['bunit_name'];
    ?>

        <style type="text/css">
            #statisticRep th,
            td {
                font-size: 12px;
            }
        </style>
        <form action="<?= base_url('promo/reports/generateStatRep') ?>" method="POST" target="_blank">
            <input type="hidden" name="emp_type" value="<?= $input['emp_type'] ?>">
            <input type="hidden" name="department" value="<?= $input['department'] ?>">
            <input type="hidden" name="field" value="<?= $input['field'] ?>">
            <div class="d-flex justify-content-between align-items-center flex-wrap mt-0 mb-2">
                <h5 class="text-center text-danger"><?= $store . $type . $dept ?> </h5>
                <button type="submit" class="btn btn-sm btn-primary">
                    Generate in Excel <i class="fadeIn animated bx bx-table"></i>
                </button>
            </div>
        </form>
        <table id="statisticRep" class="table table-hover" style="width: 100%;">
            <thead>
                <tr>
                    <th>EmployeeID</th>
                    <th class="name">Name</th>
                    <th>PromoType</th>
                    <th>ContractType</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $c = '';
                if ($input['emp_type'] != 'allPromo') {

                    $c .= "emp_type = '$input[emp_type]'";
                }
                if ($input['department'] != 'allDept') {
                    if ($input['emp_type'] == 'allPromo') {
                        $c .= "promo_department = '$input[department]'";
                    } else {
                        $c .= "AND promo_department = '$input[department]'";
                    }
                }
                if ($input['emp_type'] != 'allPromo' || $input['department'] != 'allDept') {
                    $c .= " AND $input[field] = 'T' AND current_status = 'Active' AND hr_location = 'asc'";
                } else {
                    $c .= " $input[field] = 'T' AND current_status = 'Active' AND hr_location = 'asc'";
                    $c .= " AND (emp_type = 'Promo' OR emp_type = 'Promo-NESCO')";
                }


                $select     = '*';
                $table1     = 'employee3';
                $table2     = 'promo_record';
                $join       = 't1.emp_id=t2.emp_id AND t1.record_no=t2.record_no';
                $query      = $this->promo_model->join_ttjcoA($select, $table1, $table2, $join, null, $c, null, null);

                foreach ($query as $row) {
                    $name = '<a href="' . base_url('promo/page/promo/profile/' . $row['emp_id']) . '" target="_blank">' . ucwords(strtolower($row['name'])) . '</a>';
                    echo '<tr>';
                    echo '<td>' . $row['emp_id'] . '</td>';
                    echo '<td>
                            <a href="' . base_url('promo/page/promo/profile/' . $row['emp_id']) . '" target="_blank">
                                ' . ucwords(strtolower($row['name'])) . '
                            </a>
                         </td>';
                    echo '<td>' . $row['promo_type'] . '</td>';
                    echo '<td>' . $row['type'] . '</td>';
                    echo '</tr>';
                }

                ?>
            </tbody>
        </table>

    <?php
} else if ($request == 'generateStatRep') {
    $filename = 'Statistics_Summarty_Report_' . date('Ymd');
    header("Cache-Control: public");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-disposition: attachment; filename=" . $filename . ".xls");
    $type = '';
    $dept = '';
    if ($input['emp_type'] != 'allPromo') {
        $type = ' | ' . $input['emp_type'];
    }
    if ($input['department'] != 'allDept') {
        $dept = ' | ' . $input['department'];
    }
    $store = $this->promo_model->selectAll_tcR('locate_promo_business_unit', array('bunit_field' => $input['field']))['bunit_name'];
    ?>
        <i><b>Date Generated :</b> <?= date('F d, Y') ?></i><br>
        <i><b>Generated Thru :</b> HRMS - Promo V2</i><br>
        <i><b>Generated By :</b> <?= $this->promo_model->getName_employee3($systemUser) ?></i><br>
        <i><b>Report Title :</b> Statistics Summary Report as of <?= date('F d, Y') ?><br><br><br>
            <style type="text/css">
                th,
                td {
                    border: 1px dotted black;
                    font-size: 12px;
                }
            </style>
            <table>
                <thead>
                    <tr>
                        <td colspan="12" style="color: #F11F1F;"><b><i><?= $store . $type . $dept ?></i></b></td>
                    </tr>
                    <tr>
                        <th>NO</th>
                        <th>EMP.ID</th>
                        <th>NAME</th>
                        <th>COMPANY</th>
                        <th>STORE(S)</th>
                        <th>DEPARTMENT</th>
                        <th>POSITION</th>
                        <th>EMP.TYPE</th>
                        <th>PROMO TYPE</th>
                        <th>CONTRACT TYPE</th>
                        <th>STARTDATE</th>
                        <th>EOCDATE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $c = '';
                    if ($input['emp_type'] != 'allPromo') {

                        $c .= "emp_type = '$input[emp_type]'";
                    }
                    if ($input['department'] != 'allDept') {
                        if ($input['emp_type'] == 'allPromo') {
                            $c .= "promo_department = '$input[department]'";
                        } else {
                            $c .= "AND promo_department = '$input[department]'";
                        }
                    }
                    if ($input['emp_type'] != 'allPromo' || $input['department'] != 'allDept') {
                        $c .= " AND $input[field] = 'T' AND current_status = 'Active' AND hr_location = 'asc'";
                    } else {
                        $c .= " $input[field] = 'T' AND current_status = 'Active' AND hr_location = 'asc'";
                        $c .= " AND (emp_type = 'Promo' OR emp_type = 'Promo-NESCO')";
                    }

                    $no = 1;
                    $select     = '*';
                    $table1     = 'employee3';
                    $table2     = 'promo_record';
                    $join       = 't1.emp_id=t2.emp_id AND t1.record_no=t2.record_no';
                    $query      = $this->promo_model->join_ttjcoA($select, $table1, $table2, $join, null, $c, null, null);
                    foreach ($query as $row) {
                        $bUs = $this->promo_model->locate_promo_bu('asc');
                        $i = 0;
                        $stores = '';
                        foreach ($bUs as $bu) {
                            $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bu['bunit_field']);
                            if ($hasBu > 0) {
                                $i++;
                                $stores = ($i == 1) ? $bu['bunit_acronym'] : $stores . ', ' . $bu['bunit_acronym'];
                            }
                        }
                        echo '<tr>';
                        echo '<td>' . $no . '</td>';
                        echo '<td>' . $row['emp_id'] . '</td>';
                        echo '<td>' . ucwords(strtolower($row['name'])) . '</td>';
                        echo '<td>' . $row['promo_company'] . '</td>';
                        echo '<td>' . $stores . '</td>';
                        echo '<td>' . $row['promo_department'] . '</td>';
                        echo '<td>' . $row['position'] . '</td>';
                        echo '<td>' . $row['emp_type'] . '</td>';
                        echo '<td>' . $row['promo_type'] . '</td>';
                        echo '<td>' . $row['type'] . '</td>';
                        echo '<td>' . date('m/d/Y', strtotime($row['startdate'])) . '</td>';
                        echo '<td>' . date('m/d/Y', strtotime($row['eocdate'])) . '</td>';
                        echo '</tr>';
                        $no++;
                    }

                    ?>
                </tbody>
            </table>
        <?php
    }
        ?>
        <script>
            $(document).ready(function() {

                $('.form-check-input:not(:checked)').css('border-color', 'rgb(108 117 125)');
                $('.form-check-input').change(function() {
                    if (!$(this).is(':checked')) {
                        $(this).css('border-color', 'rgb(108 117 125)');
                    } else {
                        $(this).css('border-color', '');
                    }
                });
                $('.datepicker').flatpickr();
                $('.select2').select2({
                    theme: "bootstrap-5",
                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                    placeholder: $(this).data('placeholder'),
                    tags: true,
                });

            });
        </script>