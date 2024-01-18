<?php
if ($request != 'notPromo') {
    $message = '';
    if ($app['birthdate'] != '0000-00-00' && $app['birthdate'] != 'NULL' && $app['birthdate'] != '') {

        $birthdate          = $app['birthdate'];
        $currentDate        = date('Y-m-d');
        $age                = floor((strtotime($currentDate) - strtotime($birthdate)) / 31556926);
        $birthdateArray     = explode('-', $birthdate);
        $currentDateArray   = explode('-', $currentDate);
        $isBirthday         = ($currentDateArray[1] == $birthdateArray[1] && $currentDateArray[2] == $birthdateArray[2]);

        if ($isBirthday) {
            $message = '<i class="bx bx-cake"></i> ' . $age . ' years old.';
        }
    }

    if ($app['photo'] != '') {

        $url        = "http://$_SERVER[SERVER_ADDR]:$_SERVER[SERVER_PORT]/hrms/promoV2/{$app['photo']}";
        $response   = @file_get_contents($url);
        $photo      = ($response !== false) ? $app['photo'] : 'assets/images/promologo.png';
    } else {

        $photo      = 'assets/images/promologo.png';
    }

    $photoLink      = 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promoV2/' . $photo;
    $name           = ucwords(strtolower($app['lastname'] . ', ' . $app['firstname'] . ' ' . substr($app['middlename'], 0, 1) . '. ' . $app['suffix']));
    $position       = $row['position'];
    $emp_id         = $row['emp_id'];
    $agency         = 'No Agency';
    if ($row['agency_code'] != 0 && $row['agency_code'] != '') {
        $get_agency = $this->promo_model->selectAll_tcR_tk('promo_locate_agency', array('agency_code' => $row['agency_code']));
        $agency     = $get_agency['agency_name'];
    }

    $company        = $row['promo_company'];
    $department     = $row['promo_department'];
    $type           = $row['emp_type'] . ' (' . $row['type'] . ')';
    $contract       = date('m/d/Y', strtotime($row['startdate']))  . '-' . date('m/d/Y', strtotime($row['eocdate']));
    $status         = $row['current_status'];
    if ($status == 'Active') {
        $stat       = 'success';
    } else if ($status == 'blacklisted') {
        $stat       = 'danger';
    } else {
        $stat       = 'warning';
    }
    $i              = 0;
    $current_store  = '';
    foreach ($store as $value) {
        $i++;
        $current_store = ($i == 1) ? $value : $current_store . ' | ' . $value;
        $currentStore = ($i == 1) ? $value : $currentStore . ', ' . $value;
    }
    $bloodtype_array    = array('A', 'A+', 'A-', 'B', 'B+', 'B-', 'O', 'O+', 'O-', 'AB', 'AB+', 'AB-');
    $cs_array           = array('Single', 'Married', 'Widowed', 'Separated', 'Anulled', 'Divorced');
}
if ($request == 'basicInfo') { ?>
    <form id="basicInfo" autocomplete="off">
        <div class="row">
            <input type="hidden" name="appcode" value="<?= $app['appcode'] ?>">
            <input type="hidden" name="emp_id" value="<?= $emp_id ?>">
            <div class="col-lg-4 border-end">
                <div class="d-flex flex-column align-items-center text-center">
                    <a href="javascript:;" id="<?= $photoLink ?>" onclick="profilePic(this.id)">
                        <img src="<?= $photoLink ?>" alt="img" class="rounded-circle shadow" width="150" height="150">
                    </a>
                    <div class="mt-1">
                        <h4 class="mb-0"><?= $name ?></h4>
                        <p class="text-secondary mb-0"><?= $position ?></p>
                        <p class="text-danger mb-0"><?= $message ?></p>
                        <span class="badge mb-1 bg-<?= $stat ?>"><?= $status ?></span>
                    </div>
                </div>
                <hr class="mt-0 mb-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap mt-0">
                        <i class="fadeIn animated bx bx-id-card font-22 text-primary"></i>
                        <span class="text-secondary"><?= $emp_id ?></span>
                    </li>
                    <li class="list-group-item justify-content-between" style="display: flex;">
                        <i class="fadeIn animated bx bx-user-pin font-22 text-primary"></i>
                        <span class="text-secondary text-truncate ps-5" data-toggle="tooltip" title="<?= $agency ?>">
                            <?= $agency ?>
                        </span>
                    </li>
                    <li class="list-group-item justify-content-between" style="display: flex;">
                        <i class="fadeIn animated bx bx-buildings font-22 text-primary"></i>
                        <span class="text-secondary text-truncate ps-5" data-toggle="tooltip" title="<?= $company ?>">
                            <?= $company ?>
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <i class="fadeIn animated bx bx-store font-22 text-primary"></i>
                        <span class="text-secondary"><?= $current_store ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <i class="fadeIn animated bx bx-cart-alt font-22 text-primary"></i>
                        <span class="text-secondary"><?= $department ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <i class="fadeIn animated bx bx-file font-22 text-primary"></i>
                        <span class="text-secondary"><?= $type ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <i class="fadeIn animated bx bx-calendar font-22 text-primary"></i>
                        <span class="text-secondary"><?= $contract ?></span>
                    </li>
                </ul>
            </div>
            <div class="col-lg-8">
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Firstname</label>
                        <input type="text" class="form-control" name="fn" value="<?= $app['firstname'] ?>" style="text-transform: capitalize;" disabled>
                    </div>
                    <div class="col-sm-6">
                        <label>Lastname</label>
                        <input type="text" class="form-control" name="ln" value="<?= $app['lastname'] ?>" style="text-transform: capitalize;" disabled>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Middlename</label>
                        <input type="text" class="form-control" name="mn" value="<?= $app['middlename'] ?>" style="text-transform: capitalize;" disabled>
                    </div>
                    <div class="col-sm-6">
                        <label>Suffix</label>
                        <input type="text" class="form-control" name="suffix" value="<?= $app['suffix'] ?>" style="text-transform: capitalize;" disabled>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Birthdate</label>
                        <div class="input-group">
                            <i class="input-group-text fadeIn animated bx bxs-cake font-22"></i>
                            <input type="date" class="form-control datepicker" name="birthdate" value="<?= $app['birthdate'] ?>" disabled>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label>Citizenship</label>
                        <div class="input-group">
                            <i class="input-group-text fadeIn animated bx bxs-flag-alt font-22"></i>
                            <input type="text" class="form-control" name="citizenship" value="<?= $app['citizenship'] ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Gender</label>

                        <div class="input-group">
                            <?php
                            $genderIcon = ($app['gender'] == 'Male') ? 'male' : 'female';
                            ?>
                            <i class="input-group-text fadeIn animated bx bx-<?= $genderIcon ?> font-22"></i>
                            <select class="form-select select2" name="gender" data-placeholder="Select Gender" disabled>
                                <option value="">Select Gender</option>
                                <option value="Male" <?= ($app['gender'] == "Male") ? 'selected' : '' ?>>Male</option>
                                <option value="Female" <?= ($app['gender'] == "Female") ? 'selected' : '' ?>>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label>Civil Status</label>
                        <div class="input-group">
                            <i class="input-group-text fadeIn animated bx bx-male-female font-22"></i>
                            <select class="form-select select2" name="civilstatus" data-placeholder="Select Civil Status" disabled>
                                <option value="">Select Civil Status</option>
                                <?php
                                foreach ($cs_array as $value) {
                                    echo '<option value="' . $value . '"';
                                    echo ($value == $app['civilstatus']) ? 'selected' : '';
                                    echo '>' . $value . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Religion</label>
                        <div class="input-group">
                            <i class="input-group-text fadeIn animated bx bxs-church font-22"></i>
                            <select class="form-select select2" name="religion" data-placeholder="Select Religion" disabled>
                                <option value="">Select Religion</option>
                                <?php
                                foreach ($religion as $value) {
                                    echo '<option value="' . $value['religion'] . '"';
                                    echo ($value['religion'] == $app['religion']) ? 'selected' : '';
                                    echo '>' . $value['religion'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label>Bloodtype</label>
                        <div class="input-group">
                            <i class="input-group-text fadeIn animated bx bxs-droplet font-22"></i>
                            <select class="form-select select2" name="bloodtype" data-placeholder="Select Bloodtype" disabled>
                                <option value="">Select Bloodtype</option>
                                <?php
                                foreach ($bloodtype_array as $value) {
                                    echo '<option value="' . $value . '"';
                                    echo ($value == $app['bloodtype']) ? 'selected' : '';
                                    echo '>' . $value . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Weight in kilogram</label>
                        <div class="input-group">
                            <i class="input-group-text fadeIn animated bx bxs-tachometer font-22"></i>
                            <select class="form-select select2" name="weight" data-placeholder="Select Weight" disabled>
                                <option value="">Select Weight</option>
                                <?php
                                foreach ($weight as $value) {
                                    echo '<option value="' . $value['kilogram'] . ' / ' . $value['pounds'] . '"';
                                    echo ($value['kilogram'] . ' / ' . $value['pounds'] == $app['weight']) ? 'selected' : '';
                                    echo '>' . $value['kilogram'] . ' / ' . $value['pounds'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label>Height in centimeter</label>
                        <div class="input-group">
                            <i class="input-group-text fadeIn animated bx bxs-ruler font-22"></i>
                            <select class="form-select select2" name="height" data-placeholder="Select Height" disabled>
                                <option value="">Select Height</option>
                                <?php
                                foreach ($height as $value) {
                                    echo '<option value="' . $value['cm'] . ' / ' . $value['feet'] . '"';
                                    echo ($value['cm'] . ' / ' . $value['feet'] == $app['height']) ? 'selected' : '';
                                    echo '>' . $value['cm'] . ' / ' . $value['feet'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-2 justify-content-end">
                            <button type="button" class="btn btn-sm btn-primary" id="edit" onclick="profileData_edit(this.id, '<?= $request ?>')">
                                <i class="bx bx-edit"></i>Edit
                            </button>
                            <button type="submit" class="btn btn-sm btn-primary" style="display: none;" id="submit"><i class="bx bx-save"></i>Update</button>
                            <button type="button" class="btn btn-sm btn-secondary" style="display: none;" id="cancel" onclick="profileData_edit(this.id, '<?= $request ?>')">
                                <i class="bx bx-x"></i>Cancel
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
<?php
} else if ($request == 'contactInfo') { ?>
    <form id="contactInfo" autocomplete="off">
        <div class="row">
            <input type="hidden" name="emp_id" value="<?= $emp_id ?>">
            <div class="col-lg-12">
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Home Address</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bxs-home font-22"></i>
                            <select class="form-select select2" name="home_address" data-placeholder="Select Home Address" disabled>
                                <option value="">Select Home Address</option>
                                <?php
                                foreach ($address as $value) {
                                    echo '<option value="' . $value['brgy_name'] . ', ' . $value['town_name'] . ', ' . $value['prov_name'] . '"';
                                    echo ($value['brgy_name'] . ', ' . $value['town_name'] . ', ' . $value['prov_name']   == $app['home_address']) ? 'selected' : '';
                                    echo '>' . $value['brgy_name'] . ', ' . $value['town_name'] . ', ' . $value['prov_name'] .  '</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label>City Address</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bxs-buildings font-22"></i>
                            <select class="form-select select2" name="city_address" data-placeholder="Select City Address" disabled>
                                <option value="">Select City Address</option>
                                <?php
                                foreach ($address as $value) {
                                    echo '<option value="' . $value['brgy_name'] . ', ' . $value['town_name'] . ', ' . $value['prov_name'] . '"';
                                    echo ($value['brgy_name'] . ', ' . $value['town_name'] . ', ' . $value['prov_name']   == $app['city_address']) ? 'selected' : '';
                                    echo '>' . $value['brgy_name'] . ', ' . $value['town_name'] . ', ' . $value['prov_name'] .  '</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Cellphone Number</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bx-mobile font-22"></i>
                            <input type="text" class="form-control" name="contactno" value="<?= $app['contactno'] ?>" disabled>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label>Telephone Number</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bxs-phone font-22"></i>
                            <input type="text" class="form-control" name="telno" value="<?= $app['telno'] ?>" disabled>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-4">
                        <label>Email Address</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bxs-envelope font-22"></i>
                            <input type="text" class="form-control" name="email" value="<?= $app['email'] ?>" disabled>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label>Facebook Account</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bxl-facebook font-22"></i>
                            <input type="text" class="form-control" name="facebookAcct" value="<?= $app['facebookAcct'] ?>" disabled>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label>Twitter Account</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bxl-twitter font-22"></i>
                            <input type="text" class="form-control" name="twitterAcct" value="<?= $app['twitterAcct'] ?>" disabled>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-sm-4">
                        <label>Contact Person</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bxs-contact font-22"></i>
                            <input type="text" class="form-control" name="contact_person" value="<?= $app['contact_person'] ?>" style="text-transform: capitalize;" disabled>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label>Contact Person Address</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bxs-home font-22"></i>
                            <select class="form-select select2" name="contact_person_address" data-placeholder="Select Home Address" disabled>
                                <option value="">Select Home Address</option>
                                <?php
                                foreach ($address as $value) {
                                    echo '<option value="' . $value['brgy_name'] . ', ' . $value['town_name'] . ', ' . $value['prov_name'] . '"';
                                    echo ($value['brgy_name'] . ', ' . $value['town_name'] . ', ' . $value['prov_name']   == $app['contact_person_address']) ? 'selected' : '';
                                    echo '>' . $value['brgy_name'] . ', ' . $value['town_name'] . ', ' . $value['prov_name'] .  '</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label>Contact Person Cellphone Number</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bx-mobile font-22"></i>
                            <input type="text" class="form-control" name="contact_person_number" value="<?= $app['contact_person_number'] ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-2 justify-content-end">
                            <button type="button" class="btn btn-sm btn-primary" id="edit" onclick="profileData_edit(this.id, '<?= $request ?>')">
                                <i class="bx bx-edit"></i>Edit
                            </button>
                            <button type="submit" class="btn btn-sm btn-primary" style="display: none;" id="submit"><i class="bx bx-save"></i>Update</button>
                            <button type="button" class="btn btn-sm btn-secondary" style="display: none;" id="cancel" onclick="profileData_edit(this.id, '<?= $request ?>')">
                                <i class="bx bx-x"></i>Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php
} else if ($request == 'fesc') { ?>
    <form id="fesc" autocomplete="off">
        <div class="row">
            <input type="hidden" name="emp_id" value="<?= $emp_id ?>">
            <div class="col-lg-12">
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Mother</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bx-female font-22"></i>
                            <input type="text" class="form-control" name="mother" value="<?= $app['mother'] ?>" style="text-transform: capitalize;" disabled>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label>Father</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bx-male font-22"></i>
                            <input type="text" class="form-control" name="father" value="<?= $app['father'] ?>" style="text-transform: capitalize;" disabled>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label>Guardian</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bxs-user-voice font-22"></i>
                            <input type="text" class="form-control" name="guardian" value="<?= $app['guardian'] ?>" style="text-transform: capitalize;" disabled>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label>Spouse</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bxs-heart font-22"></i>
                            <input type="text" class="form-control" name="spouse" value="<?= $app['spouse'] ?>" style="text-transform: capitalize;" disabled>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label>Hobbies</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text bx bx-cycling font-22"></i>
                            <input type="text" class="form-control" name="hobbies" value="<?= $app['hobbies'] ?>" disabled>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label>Special Skills/Talents</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text bx bx-glasses-alt font-22"></i>
                            <input type="text" class="form-control" name="specialSkills" value="<?= $app['specialSkills'] ?>" disabled>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-sm-4">
                        <label>Educational Attainment</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text bx bxs-graduation font-22"></i>
                            <select class="form-select select2" name="attainment" data-placeholder="Select Educational Attainment" disabled>
                                <option value="">Select Educational Attainment</option>
                                <?php
                                foreach ($attainment as $value) {
                                    echo '<option value="' . $value['attainment'] . '"';
                                    echo ($value['attainment']   == $app['attainment']) ? 'selected' : '';
                                    echo '>' . $value['attainment'] .  '</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label>School</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bxs-school font-22"></i>
                            <select class="form-select select2" name="school" data-placeholder="Select School" disabled>
                                <option value="">Select School</option>
                                <?php
                                foreach ($school as $value) {
                                    echo '<option value="' . $value['school_name'] . '"';
                                    echo ($value['school_name']   == $app['school']) ? 'selected' : '';
                                    echo '>' . $value['school_name'] .  '</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label>Course</label>
                        <div class="input-group mb-3 font-22">
                            <i class="input-group-text lni lni-thought"></i>
                            <select class="form-select select2" name="course" data-placeholder="Select Course" disabled>
                                <option value="">Select Course</option>
                                <?php
                                foreach ($course as $value) {
                                    echo '<option value="' . $value['course_name'] . '"';
                                    echo ($value['course_name']   == $app['course']) ? 'selected' : '';
                                    echo '>' . $value['course_name'] .  '</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-2 justify-content-end">
                            <button type="button" class="btn btn-sm btn-primary" id="edit" onclick="profileData_edit(this.id, '<?= $request ?>')">
                                <i class="bx bx-edit"></i>Edit
                            </button>
                            <button type="submit" class="btn btn-sm btn-primary" style="display: none;" id="submit"><i class="bx bx-save"></i>Update</button>
                            <button type="button" class="btn btn-sm btn-secondary" style="display: none;" id="cancel" onclick="profileData_edit(this.id, '<?= $request ?>')">
                                <i class="bx bx-x"></i>Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php
} else if ($request == 'training') { ?>

    <div class="row">
        <input type="hidden" name="emp_id" value="<?= $emp_id ?>">

        <div class="col-lg-12 slider mb-1">
            <table id="training" class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th class="text-center">Certificate</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($training as $value) {
                        echo    '<tr>
                                    <td>' . $value['name'] . '</td>
                                    <td>' . $value['dates'] . '</td>
                                    <td>' . $value['location'] . '</td>
                                    <td class="text-center">
                                        <a href="javascript:;" title="view Certificate" class="badge bg-light-info text-primary" onclick=view_img("' . $value['sem_certificate'] . '")>
                                            <i class="bx bx-image-alt me-0 font-22"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:;" title="edit" id="edit" class="badge bg-light-info text-primary" onclick="modal_form(this.id, ' . $value['no'] . ', \'' . $request . '\')">
                                            <i class="bx bx-edit me-0 font-22"></i>
                                        </a>
                                    </td>
                                </tr>';
                    } ?>
                </tbody>
            </table>
        </div>
        <div>
            <a href="javascript:;" class="btn btn-sm btn-outline-primary radius-30 pull-right" id="add" onclick="modal_form(this.id,'<?= $emp_id ?>','<?= $request ?>')">
                <i class="fadeIn animated bx bx-plus-circle font-22"></i>Add
            </a>
        </div>
    </div>

<?php
} else if ($request == 'characterRef') { ?>

    <div class="row">
        <input type="hidden" name="emp_id" value="<?= $emp_id ?>">
        <div class="col-lg-12 slider mb-1">
            <table id="characterRef" class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Contact No.</th>
                        <th>Company/Location</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($character as $value) {
                        echo    '<tr>
                                    <td>' . $value['name'] . '</td>
                                    <td>' . $value['position'] . '</td>
                                    <td>' . $value['contactno'] . '</td>
                                    <td>' . $value['company'] . '</td>
                                    <td class="text-center">
                                        <a href="javascript:;" title="edit" id="edit" onclick="modal_form(this.id, ' . $value['no'] . ', \'' . $request . '\')">
                                            <i class="bx bx-edit me-0 font-22"></i>
                                        </a>
                                    </td>
                                </tr>';
                    } ?>
                </tbody>
            </table>
        </div>
        <div>
            <a href="javascript:;" class="btn btn-sm btn-outline-primary radius-30 pull-right" id="add" onclick="modal_form(this.id,'<?= $emp_id ?>','<?= $request ?>')">
                <i class="fadeIn animated bx bx-plus-circle font-22"></i>Add
            </a>
        </div>
    </div>

<?php
} else if ($request == 'eocAppraisal') { ?>

    <div class="row">
        <div class="col-lg-12 slider">
            <table id="eocAppraisal" class="table table-hover">
                <thead>
                    <tr>
                        <th>Startdate</th>
                        <th>EOCdate</th>
                        <th>Rater's Name</th>
                        <th class="text-center">NumRate</th>
                        <th class="text-center">DescRate</th>
                        <th>Store</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($eocApp_c as $value) {
                        echo    '<tr>
                                    <td>' . date('m-d-Y', strtotime($value['startdate'])) . '</td>
                                    <td>' . date('m-d-Y', strtotime($value['eocdate'])) . '</td>
                                    <td>' . ucwords(strtolower($this->promo_model->getName_employee3($value['rater']))) . '</td>
                                    <td class="text-center">' . $value['numrate'] . '</td>
                                    <td class="text-center">' . $value['descrate'] . '</td>
                                    <td>' . $value['store'] . '</td>
                                    <td class="text-center">
                                        <a href="javascript:;" title="view Appraisal Details" id="' . $value['details_id'] . '" onclick="viewAppraisal(this.id)">
                                            <i class="bx bx-folder-open me-0 font-22"></i>
                                        </a>
                                    </td>
                                </tr>';
                    }
                    foreach ($eocApp_p as $value) {
                        $storeEpas  = '';
                        $i          = 0;
                        $bUs        = $this->promo_model->locate_promo_bu('asc');
                        foreach ($bUs as $bu) {
                            $hasBu = $this->promo_model->empStores('promo_history_record', $emp_id, $value['record_no'], $bu['bunit_field']);
                            if ($hasBu > 0) {
                                $i++;
                                if ($i == 1) {
                                    $storeEpas = ' (' . $bu['bunit_epascode'] . ' = "1"';
                                } else {
                                    $storeEpas .= ' OR ' . $bu['bunit_epascode'] . ' = "1"';
                                }
                            }
                        }
                        $storeEpas .= ')';
                        if ($storeEpas == ')') {
                            $storeEpas = '';
                        }

                        $chk = $this->promo_model->eocApp_p_chk($emp_id, $value['record_no'], $storeEpas);
                        if ($chk > 0) {
                            $appDetails = $this->promo_model->selectAll_tcA('appraisal_details', array('emp_id' => $emp_id, 'record_no' => $value['record_no']));
                            foreach ($appDetails as $appVal) {
                                echo    '<tr>
                                            <td>' . date('m-d-Y', strtotime($value['startdate'])) . '</td>
                                            <td>' . date('m-d-Y', strtotime($value['eocdate'])) . '</td>
                                            <td>' . ucwords(strtolower($this->promo_model->getName_employee3($appVal['rater']))) . '</td>
                                            <td class="text-center">' . $appVal['numrate'] . '</td>
                                            <td class="text-center">' . $appVal['descrate'] . '</td>
                                            <td>' . $appVal['store'] . '</td>
                                            <td class="text-center">
                                                <a href="javascript:;" title="view Appraisal Details" id="' . $appVal['details_id'] . '" onclick="viewAppraisal(this.id)">
                                                    <i class="bx bx-folder-open me-0 font-22"></i>
                                                </a>
                                            </td>
                                        </tr>';
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

<?php
} else if ($request == 'appHistory') { ?>
    <form id="appHistory" autocomplete="off">
        <div class="row">
            <input type="hidden" name="emp_id" value="<?= $emp_id ?>">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Position Applied</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bx-chair font-22"></i>
                            <select class="form-select select2" name="position_applied" data-placeholder="Select Position Applied" disabled>
                                <option value="">Select Position Applied</option>
                                <?php
                                foreach ($positions as $value) {
                                    echo '<option value="' . $value['position_title'] . '"';
                                    echo ($application['position_applied'] == $value['position_title']) ? 'selected' : '';
                                    echo '>' . $value['position_title'] .  '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label>Date Applied</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bx-calendar-event font-22"></i>
                            <input type="text" class="form-control datepicker" name="date_applied" value="<?= $application['date_applied'] ?>" disabled>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label>Date of Exam</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bx-calendar-edit font-22"></i>
                            <input type="text" class="form-control datepicker" name="date_examined" value="<?= $application['date_examined'] ?>" disabled>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label>Exam Result</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bx-news font-22"></i>
                            <input type="text" class="form-control" name="exam_results" value="<?= $application['exam_results'] ?>" disabled>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label>Date Briefed</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text bx bx-calendar font-22"></i>
                            <input type="text" class="form-control datepicker" name="date_brief" value="<?= $application['date_brief'] ?>" disabled>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label>Date Hired</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text bx bx-calendar-check font-22"></i>
                            <input type="text" class="form-control datepicker" name="date_hired" value="<?= $application['date_hired'] ?>" disabled>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label>Recommended by (Alturas Employee)</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text bx bx-user-check font-22"></i>
                            <input type="text" class="form-control" name="aeregular" value="<?= $application['aeregular'] ?>" style="text-transform: capitalize;" disabled>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <br>
                        <div class="d-md-flex d-grid align-items-center gap-2 justify-content-end mt-2">
                            <button type="button" class="btn btn-sm btn-primary" id="edit" onclick="profileData_edit(this.id, '<?= $request ?>')">
                                <i class="bx bx-edit"></i>Edit
                            </button>
                            <button type="submit" class="btn btn-sm btn-primary" style="display: none;" id="submit"><i class="bx bx-save"></i>Update</button>
                            <button type="button" class="btn btn-sm btn-secondary" style="display: none;" id="cancel" onclick="profileData_edit(this.id, '<?= $request ?>')">
                                <i class="bx bx-x"></i>Cancel
                            </button>
                        </div>
                    </div>
                    <hr>
                    <div class="col-sm-12">
                        <div class="d-md-flex d-grid align-items-center gap-2 justify-content-center">
                            <button type="button" class="btn btn-sm btn-primary" id="<?= $emp_id ?>" onclick="viewExam_history(this.id)">View Examination History</button>
                            <button type="button" class="btn btn-sm btn-primary" id="<?= $emp_id ?>" onclick="viewApp_details(this.id)">View Application Details</button>
                            <button type="button" class="btn btn-sm btn-primary" id="<?= $emp_id ?>" onclick="viewInt_details(this.id)">View Interview Details</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
<?php
} else if ($request == 'contractHistory') { ?>
    <style>
        .sliders {
            position: relative;
            max-height: 380px;

        }

        #contract_list thead {
            position: sticky;
            top: 0;
            z-index: 1;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 sliders">
            <table id="contract_list" class="table table-hover" style="font-size: 13px;width:100%;">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Position</th>
                        <th>Company</th>
                        <th>Store(s)</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Startdate</th>
                        <th>EOCdate</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($current > 0) {
                        $bUs = $this->promo_model->locate_promo_bu();
                        foreach ($current as $rowCurrent) {
                            $c = 0;
                            $currentStore = '';
                            foreach ($bUs as $bu) {
                                $hasBu = $this->promo_model->empStores('promo_record', $rowCurrent['emp_id'], $rowCurrent['record_no'], $bu['bunit_field']);
                                if ($hasBu > 0) {
                                    $c++;
                                    $currentStore = ($c == 1) ? $bu['bunit_acronym'] : $currentStore . ', ' . $bu['bunit_acronym'];
                                }
                            }

                            echo    '<tr>
                                        <td><span class="badge bg-success">C</span></td>
                                        <td>' . $rowCurrent['position'] . '</td>
                                        <td class="text-truncate" style="max-width: 150px;" title="' . $rowCurrent['promo_company'] . '">
                                            ' . $rowCurrent['promo_company'] . '
                                        </td>
                                        <td>' . $currentStore . '</td>
                                        <td>' . $rowCurrent['promo_department'] . '</td>
                                        <td>' . $rowCurrent['current_status'] . '</td>
                                        <td>' . date('m/d/Y', strtotime($rowCurrent['startdate'])) . '</td>
                                        <td>' . date('m/d/Y', strtotime($rowCurrent['eocdate'])) . '</td>
                                        <td class="text-center">
                                            <div class="d-md-flex d-grid align-items-center gap-1 justify-content-center">
                                                <a href="javascript:;" id="current|' . $emp_id . '|' . $rowCurrent['record_no'] . '" onclick="viewContract(this.id)">
                                                    <i class="fadeIn animated bx bx-info-circle font-18"></i>
                                                </a>
                                                <a href="javascript:;" id="current|' . $emp_id . '|' . $rowCurrent['record_no'] . '" onclick="editContract(this.id)">
                                                    <i class="fadeIn animated bx bx-edit font-18"></i>
                                                </a>
                                                <a href="javascript:;" id="current|' . $emp_id . '|' . $rowCurrent['record_no'] . '" onclick="uploadContract(this.id)">
                                                    <i class="fadeIn animated bx bx-upload font-18"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>';
                        }
                    }


                    if ($previous > 0) {
                        $i = 0;
                        $bUs = $this->promo_model->locate_promo_bu();
                        foreach ($previous as $rowPrev) {
                            $i++;
                            $p = 0;
                            $previousStore = '';
                            foreach ($bUs as $bu) {
                                $hasBu = $this->promo_model->empStores('promo_history_record', $rowPrev['emp_id'], $rowPrev['record_no'], $bu['bunit_field']);
                                if ($hasBu > 0) {
                                    $p++;
                                    $previousStore = ($p == 1) ? $bu['bunit_acronym'] : $previousStore . ', ' . $bu['bunit_acronym'];
                                }
                            }

                            echo    '<tr>
                                        <td><span class="badge bg-danger">' . $i . '</span></td>
                                        <td>' . $rowPrev['position'] . '</td>
                                        <td class="text-truncate" style="max-width: 150px;" title="' . $rowPrev['promo_company'] . '">
                                            ' . $rowPrev['promo_company'] . '
                                        </td>
                                        <td>' . $previousStore . '</td>
                                        <td>' . $rowPrev['promo_department'] . '</td>
                                        <td>' . $rowPrev['current_status'] . '</td>
                                        <td>' . date('m/d/Y', strtotime($rowPrev['startdate'])) . '</td>
                                        <td>' . date('m/d/Y', strtotime($rowPrev['eocdate'])) . '</td>
                                        <td class="text-center">
                                            <div class="d-md-flex d-grid align-items-center gap-1 justify-content-center">
                                                <a href="javascript:;" id="previous|' . $emp_id . '|' . $rowPrev['record_no'] . '" onclick="viewContract(this.id)">
                                                    <i class="fadeIn animated bx bx-info-circle font-18"></i>
                                                </a>
                                                <a href="javascript:;" id="previous|' . $emp_id . '|' . $rowPrev['record_no'] . '" onclick="editContract(this.id)">
                                                    <i class="fadeIn animated bx bx-edit font-18"></i>
                                                </a>
                                                <a href="javascript:;" id="previous|' . $emp_id . '|' . $rowPrev['record_no'] . '" onclick="uploadContract(this.id)">
                                                    <i class="fadeIn animated bx bx-upload font-18"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="d-md-flex d-grid align-items-center gap-1 justify-content-left mt-1">
            <span class="badge text-secondary"><b>LEGEND:</b></span>
            <span class="badge bg-success">C</span><span class="badge text-secondary">current</span>
            <span class="badge bg-danger">1</span><span class="badge text-secondary">previous</span>
            <i class="fadeIn animated bx bx-info-circle font-18 text-primary"></i><span class="badge text-secondary">view</span>
            <i class="fadeIn animated bx bx-edit font-18 text-primary"></i><span class="badge text-secondary">edit</span>
            <i class="fadeIn animated bx bx-upload font-18 text-primary"></i><span class="badge text-secondary">upload</span>
        </div>

    </div>
<?php
} else if ($request == 'empHistory') { ?>

    <div class="row">
        <input type="hidden" name="emp_id" value="<?= $emp_id ?>">
        <div class="col-lg-12 slider">
            <table id="empHistory" class="table table-hover">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Position</th>
                        <th>Date Start</th>
                        <th>Date End</th>
                        <th>Address/Location</th>
                        <th class="text-center">Certificate</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($empHistory as $value) {
                        echo    '<tr>
                                    <td>' . $value['company'] . '</td>
                                    <td>' . $value['position'] . '</td>
                                    <td>' . $value['yr_start'] . '</td>
                                    <td>' . $value['yr_ends'] . '</td>
                                    <td>' . $value['address'] . '</td>
                                    <td class="text-center">
                                        <a href="javascript:;" title="view Certificate" id="" onclick=view_img("' . $value['emp_certificate'] . '")>
                                            <i class="bx bx-image-alt me-0 font-22"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:;" title="edit Certificate" id="edit" onclick="modal_form(this.id, ' . $value['no'] . ', \'' . $request . '\')">
                                            <i class="bx bx-edit me-0 font-22"></i>
                                        </a>
                                    </td>
                                </tr>';
                    } ?>
                </tbody>
            </table>
        </div>
        <div>
            <a href="javascript:;" class="btn btn-sm btn-outline-primary radius-30 pull-right" id="add" onclick="modal_form(this.id,'<?= $emp_id ?>','<?= $request ?>')">
                <i class="fadeIn animated bx bx-plus-circle font-22"></i>Add
            </a>
        </div>
    </div>

<?php
} else if ($request == 'transferHistory') { ?>

    <div class="row">
        <input type="hidden" name="emp_id" value="<?= $emp_id ?>">
        <div class="col-lg-12 slider">
            <table id="clearanceHistory" class="table table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Effectivity</th>
                        <th>TransferFrom</th>
                        <th>TransferTo</th>
                        <th>OldPosition</th>
                        <th>NewPosition</th>
                        <th>DirectSupervisor</th>
                        <th>JobTrans</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($transfer as $value) {
                        $i++;
                        echo    '<tr>
                                    <td>' . $i . '.</td>
                                    <td>' . $value['effectiveon'] . '</td>
                                    <td>' . $value['old_location'] . '</td>
                                    <td>' . $value['new_location'] . '</td>
                                    <td>' . $value['old_position'] . '</td>
                                    <td>' . $value['position'] . '</td>
                                    <td>' . $value['supervision'] . '</td>
                                    <td></td>
                                <tr>';
                    } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php
} else if ($request == 'blacklistHistory') { ?>

    <div class="row">
        <input type="hidden" name="emp_id" value="<?= $emp_id ?>">
        <div class="col-lg-12 slider">
            <table id="blacklistHistory" class="table table-hover">
                <thead>
                    <tr>
                        <th>DateBlacklisted</th>
                        <th>ReportedBy</th>
                        <th>Reason</th>
                        <th>DateAdded</th>
                        <th>Status</th>
                        <th>Action</th>
                </thead>
                <tbody>
                    <?php
                    foreach ($blacklist as $value) {
                        echo    '<tr>
                                    <td>' . $value['date_blacklisted'] . '</td>
                                    <td>' . $value['reportedby'] . '</td>
                                    <td>' . $value['reason'] . '</td>
                                    <td>' . $value['date_added'] . '</td>
                                    <td>' . $value['status'] . '</td>
                                    <td></td>
                                <tr>';
                    } ?>
                </tbody>
            </table>
        </div>
        <!-- <div>
            <a href="javascript:;" class="btn btn-sm btn-outline-primary radius-30 pull-right" id="add" onclick="modal_form(this.id,'<?= $emp_id ?>','<?= $request ?>')">
                <i class="fadeIn animated bx bx-plus-circle font-22"></i>Add
            </a>
        </div> -->
    </div>

<?php
} else if ($request == 'clearanceHistory') { ?>

    <div class="row">
        <input type="hidden" name="emp_id" value="<?= $emp_id ?>">
        <div class="col-lg-12 slider">
            <table id="clearanceHistory" class="table table-hover">
                <thead>
                    <tr>
                        <th>Reason</th>
                        <th>Store</th>
                        <th>Date Secured</th>
                        <th>Date Effective</th>
                        <th class="text-center">Resignation Letter</th>
                        <th class="text-center">Status</th>
                        <th>Added By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($clearance as $value) {
                        $name = $this->promo_model->getName_employee3($value['added_by']);
                        echo    '<tr>
                                    <td>' . $value['reason'] . '</td>
                                    <td>' . $value['store'] . '</td>
                                    <td>' . $value['date_secure'] . '</td>
                                    <td>' . $value['date_effectivity'] . '</td>
                                    <td class="text-center">';
                        if ($value['reason'] != 'Termination') {
                            echo        '<a href="javascript:;" title="view Letter" class="badge bg-light-info text-primary" onclick=view_img("' . $value['resignation_letter'] . '")>
                                            <i class="bx bx-file me-0 font-22"></i>
                                        </a>';
                        }
                        echo        '</td>
                                    <td class="text-center">' . $value['clearance_status'] . '</td>
                                    <td>' . ucwords(strtolower($name)) . '</td>
                                </tr>';
                    } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php
} else if ($request == 'benefits') { ?>
    <form id="benefits" autocomplete="off">
        <div class="row">
            <input type="hidden" name="emp_id" value="<?= $emp_id ?>">
            <div class="col-lg-12">
                <div class="row mb-3">
                    <div class="col-sm-4">
                        <label>Philhealth No.</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bx-id-card font-22"></i>
                            <input type="text" class="form-control" name="philhealth" value="<?= $benefits['philhealth'] ?>" disabled>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label>SSS No.</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bx-id-card font-22"></i>
                            <input type="text" class="form-control" name="sss_no" value="<?= $benefits['sss_no'] ?>" disabled>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label>SSS Card No.</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bx-id-card font-22"></i>
                            <input type="text" class="form-control" name="card_no" value="<?= $benefits['card_no'] ?>" disabled>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label>Pag-ibig No.</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bx-id-card font-22"></i>
                            <input type="text" class="form-control" name="pagibig" value="<?= $benefits['pagibig'] ?>" disabled>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label>Pag-ibig RTN</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bx-id-card font-22"></i>
                            <input type="text" class="form-control" name="pagibig_tracking" value="<?= $benefits['pagibig_tracking'] ?>" disabled>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label>TIN No.</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bx-id-card font-22"></i>
                            <input type="text" class="form-control" name="tin_no" value="<?= $benefits['tin_no'] ?>" disabled>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-sm-4">
                        <label>Cedula No.</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bx-id-card font-22"></i>
                            <input type="text" class="form-control" name="cedula_no" value="<?= $benefits['cedula_no'] ?>" disabled>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label>Cedula Date</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bx-calendar font-22"></i>
                            <input type="text" class="form-control datepicker" name="cedula_date" value="<?= $benefits['cedula_date'] ?>" disabled>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label>Cedula Address</label>
                        <div class="input-group mb-3">
                            <i class="input-group-text fadeIn animated bx bx-id-card font-22"></i>
                            <input type="text" class="form-control" name="cedula_place" value="<?= $benefits['cedula_place'] ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-2 justify-content-end">
                            <button type="button" class="btn btn-sm btn-primary" id="edit" onclick="profileData_edit(this.id, '<?= $request ?>')">
                                <i class="bx bx-edit"></i>Edit
                            </button>
                            <button type="submit" class="btn btn-sm btn-primary" style="display: none;" id="submit"><i class="bx bx-save"></i>Update</button>
                            <button type="button" class="btn btn-sm btn-secondary" style="display: none;" id="cancel" onclick="profileData_edit(this.id, '<?= $request ?>')">
                                <i class="bx bx-x"></i>Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php
} else if ($request == '201doc') {
    echo    '<div class="row mb-2 slider">';
    foreach ($docFile as $value) {

        $total = 0;
        if ($value['no'] != 27) {
            if ($value['no'] == 15) {

                $query = $this->promo_model->get201_file($value, $emp_id, null, $value['no']);
            } else {
                $query = $this->promo_model->get201_file($value, $emp_id);
            }
        } else {
            $query = $this->promo_model->get201_resignation($emp_id);
        }
        $total = count($query);
        echo    '<div class="col-12 col-lg-3">
                    <div class="card border shadow-none radius-15 bg-light-danger">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="fm-icon-box radius-15 bg-gradient-ibiza text-white">
                                    <i class="bx bxs-folder-open"></i>
                                </div>
                                <div class="dropdown ms-auto">
                                    <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-horizontal-rounded font-30 text-option"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end docFile">';
        if ($total != 0) {
            echo                        '<li>
                                            <a class="dropdown-item text-center" href="javascript:;" onclick="docFile_view(\'' . $value['201_name'] . '\',\'' . $emp_id . '\', \'' . $value['no'] . '\')">View ' . $value['201_name'] . ' </a>
                                        </li>';
        }
        if ($total != 0 && $value['no'] != 27) {
            echo                        '<li>
                                            <hr class="dropdown-divider">
                                        </li>';
        }
        if ($value['no'] != 27) {

            echo                        '<li>
                                            <form id="docFile_upload" autocomplete="off">
                                                <a class="dropdown-item text-center">
                                                    Upload ' . $value['201_name'] . '</br>
                                                    <input type="hidden" name="no" value="' . $value['no'] . '">
                                                    <input type="hidden" name="emp_id" value="' . $emp_id . '">
                                                    <input type="file" class="form-control form-control-sm mb-2" name="' . str_replace(' ', '', $value['201_name']) . '">
                                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                                </a>
                                            </form>
                                        </li>';
        }
        echo                        '</ul>
                                </div>
                            </div>
                            <h6 class="mt-2 mb-0"><em><strong>' . $value['201_name'] . '</strong></em></h6>
                            <p class="mb-0 mt-1 text-danger"><span>' . $total . ' Files</span></p>
                        </div>
                    </div>                   
                </div>';
    }
    echo    '</div>';
} else if ($request == 'supervisor') { ?>

    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4 slider">
        <?php
        foreach ($supervisor as $value) {
            $row    = $this->promo_model->selectAll_tcR('employee3', array('emp_id' => $value['ratee']));
            $app    = $this->promo_model->selectAll_tcR('applicant', array('app_id' => $value['ratee']));
            $name   = ucwords(strtolower($app['lastname'])) . ', ' .  ucwords(strtolower($app['firstname'] . ' ' . substr($app['middlename'], 0, 1) . '. ' . $app['suffix']));
            $color  = ($row['current_status'] == 'Active') ? 'success' : 'warning';
            if ($app['photo'] != '') {
                $url        = "http://$_SERVER[SERVER_ADDR]:$_SERVER[SERVER_PORT]/hrms/promoV2/{$app['photo']}";
                $response   = @file_get_contents($url);
                $photo      = ($response !== false) ? $app['photo'] : 'assets/images/promologo.png';
            } else {
                $photo      = 'assets/images/promologo.png';
            }
            $photoLink      = 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promoV2/' . $photo; ?>

            <div class="col">
                <div class="card radius-15 bg-gradient-blues">
                    <div class="card-body text-center">
                        <span class="badge rounded-pill px-5 bg-<?= $color ?>"><?= $row['current_status'] ?></span>
                        <div class="p-1 radius-15">
                            <img src="<?= $photoLink ?>" width="110" height="110" class="rounded-circle shadow p-1 bg-white" alt="">
                            <h5 class="mt-1 mb-0 text-white text-truncate" data-toggle="tooltip" style="max-width: auto;" title="<?= $name ?>"><?= $name ?></h5>
                            <p class="text-white mt-0 text-truncate px-5" data-toggle="tooltip" style="max-width: auto;" title="<?= $row['position'] ?>">
                                <?= $row['position'] ?>
                            </p>
                            <div class="d-grid">
                                <a href="javascript:;" id="remove" onclick="modal_form(this.id, '<?= $value['record_no'] ?>', '<?= $request ?>')" style="pointer-events: none;">
                                    <i class="fadeIn animated bx bx-x-circle text-light font-22" style="pointer-events: auto;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        } ?>
    </div>
    <div class="row mt-1">
        <div>
            <a href="javascript:;" class="btn btn-sm btn-outline-primary radius-30 pull-right" id="add" onclick="modal_form(this.id,'<?= $emp_id ?>','<?= $request ?>')">
                <i class="fadeIn animated bx bx-plus-circle font-22"></i>Add
            </a>
        </div>
    </div>
<?php
} else if ($request == 'remarks') { ?>
    <form id="remarks" autocomplete="off">
        <div class="row">
            <input type="hidden" name="emp_id" value="<?= $emp_id ?>">
            <div class="col-lg-12">
                <div class="row mb-1">
                    <div class="col-sm-12">
                        <div class="input-group mb-1">
                            <textarea name="remarks" class="form-control" cols="30" rows="13" disabled><?= $remarks['remarks'] ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-2 justify-content-end">
                            <button type="button" class="btn btn-sm btn-primary" id="edit" onclick="profileData_edit(this.id, '<?= $request ?>')">
                                <i class="bx bx-edit"></i>Edit
                            </button>
                            <button type="submit" class="btn btn-sm btn-primary" style="display: none;" id="submit"><i class="bx bx-save"></i>Update</button>
                            <button type="button" class="btn btn-sm btn-secondary" style="display: none;" id="cancel" onclick="profileData_edit(this.id, '<?= $request ?>')">
                                <i class="bx bx-x"></i>Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php
} else if ($request == 'userAccount') { ?>

    <div class="row">
        <input type="hidden" name="emp_id" value="<?= $emp_id ?>">
        <div class="col-lg-12 slider">
            <table id="empHistory" class="table table-hover">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>User Type</th>
                        <th class="text-center">isLogIn</th>
                        <th>Date Created</th>
                        <th class="text-center">Account Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($userAccount as $value) {

                        $loginBadge     = ($value['login'] == 'yes') ? 'badge bg-success' : 'badge bg-danger';
                        $statusBadge    = ($value['user_status'] == 'active') ? 'badge bg-success' : 'badge bg-danger';
                        $update         = ($value['user_status'] == 'active') ? 'deactivate' : 'activate';
                        $bulbColor      = ($value['user_status'] == 'active') ? 'warning' : 'secondary';
                        echo    '<tr>
                                    <td>' . $value['username'] . '</td>
                                    <td>' . strtoupper($value['usertype']) . '</td>
                                    <td class="text-center"><span class="' . $loginBadge . '">' . $value['login'] . '</span></td>
                                    <td>' . date('F j, Y', strtotime($value['date_created'])) . '</td>
                                    <td class="text-center"><span class="' . $statusBadge . '">' . $value['user_status'] . '</span></td>
                                    <td class="text-center">
                                        <a href="javascript:;" data-toggle="tooltip" title="' . $update . ' account" id="' . $update . '" onclick="userAccount(this.id, ' . $value['user_no'] . ', \'' . $request . '\')">
                                            <i class="bx bx-bulb me-0 font-22 text-' . $bulbColor . '"></i>
                                        </a>
                                        <a href="javascript:;" data-toggle="tooltip" title="reset account" id="reset" onclick="userAccount(this.id, ' . $value['user_no'] . ',\'' . $request . '\')">
                                            <i class="fadeIn animated bx bx-reset font-22"></i>
                                        </a>';
                        if (in_array($this->systemUser, $this->adminUser)) {
                            echo        '<a href="javascript:;" data-toggle="tooltip" title="delete account" id="remove" onclick="userAccount(this.id,' . $value['user_no'] . ',\'' . $request . '\')">
                                            <i class="fadeIn animated bx bx-trash font-22 text-danger"></i>
                                        </a>';
                        }
                        echo        '</td>
                                </tr>';
                    } ?>
                </tbody>
            </table>
        </div>
        <?php
        if (count($userAccount) == 0) {
        ?>
            <div>
                <a href="javascript:;" class="btn btn-sm btn-outline-primary radius-30 pull-right" id="add" onclick="userAccount(this.id,'<?= $emp_id ?>','<?= $request ?>')">
                    <i class="fadeIn animated bx bx-plus-circle font-22"></i>Add
                </a>
            </div>
        <?php
        }
        ?>
    </div>
<?php
} ?>

<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        $('.datepicker').flatpickr();
        $('.select2').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            tags: true,
        });

        $('#editContract').on('shown.bs.modal', function() {
            $(".datepicker").flatpickr();
            $('.select2').select2({
                theme: "bootstrap-5",
                width: '100%',
                placeholder: $(this).data('placeholder'),
                dropdownParent: $('.editContract'),
            });

            $('.select2clear').select2({
                theme: "bootstrap-5",
                width: '100%',
                placeholder: $(this).data('placeholder'),
                dropdownParent: $('.editContract'),
                allowClear: true,
            });
            // $('.select2clear').nextAll('.select2.select2-container.select2-container--bootstrap-5:eq(1)').remove();
            $('.select2clear').each(function() {
                $(this).nextAll('.select2.select2-container.select2-container--bootstrap-5:eq(1)').remove();
            });

            $('.form-check-input:not(:checked)').css('border-color', '#aaa');
            $('.form-check-input').change(function() {
                if (!$(this).is(':checked')) {
                    $(this).css('border-color', '#aaa');
                } else {
                    $(this).css('border-color', '');
                }
            });
        });

        $('#uploadContract').on('shown.bs.modal', function() {
            contractFile('clearance')
        });

        $('#modal_form').on('shown.bs.modal', function() {
            $(".datepicker").flatpickr();
            $('.select2').select2({
                theme: "bootstrap-5",
                width: '100%',
                placeholder: $(this).data('placeholder'),
                dropdownParent: $('.modal_form'),
            });
        });

        $('#supervisor_form').on('shown.bs.modal', function() {
            $(".datepicker").flatpickr();
            $('.select2').select2({
                theme: "bootstrap-5",
                width: '100%',
                placeholder: $(this).data('placeholder'),
                dropdownParent: $('.supervisor_form'),
            });
        });

        $("form#basicInfo").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var fields = [];
            let notRequired = ['suffix', 'mn']
            for (let entry of formData.entries()) {

                if (!notRequired.includes(entry[0])) {

                    fields.push(entry[0]);
                }
            }
            console.log(fields)
            var fieldsComplete = true;
            var errMessage = false;
            for (var i = 0; i < fields.length; i++) {

                var field = fields[i];
                var value = $("input[name='" + field + "'],select[name='" + field + "']").val().trim();

                if (value === '') {
                    if (!errMessage) {
                        alertRequired();
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("input[name='" + field + "'],select[name='" + field + "']").css("border-color", "#dd4b39");
                    $("select[name='" + field + "']").next().find(".select2-selection").css("border-color", "#dd4b39");
                }

                $("input[name='" + field + "'],select[name='" + field + "'],.select2-selection").on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
            if (fieldsComplete) {

                Swal.fire({
                    title: 'Update?',
                    text: 'Update Basic Information?',
                    icon: 'question',
                    iconColor: '#ffc107',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    customClass: 'required_alert'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "<?= site_url('promo/basicInfo') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {

                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Basic Information update successful!',
                                        icon: 'success',
                                        iconColor: '#15ca20',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok',
                                        customClass: 'required_alert',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            profileData('basicInfo')
                                            // location.reload();
                                        }
                                    });
                                } else {
                                    alert(data);
                                }
                            },
                            async: false,
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }
                })
            }

        });

        $("form#contactInfo").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var fields = [];
            let notRequired = ['suffix', 'mn']
            for (let entry of formData.entries()) {

                if (!notRequired.includes(entry[0])) {

                    fields.push(entry[0]);
                }
            }
            console.log(fields)
            var fieldsComplete = true;
            var errMessage = false;
            for (var i = 0; i < fields.length; i++) {

                var field = fields[i];
                var value = $("input[name='" + field + "'],select[name='" + field + "']").val().trim();

                if (value === '') {
                    if (!errMessage) {
                        alertRequired();
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("input[name='" + field + "'],select[name='" + field + "']").css("border-color", "#dd4b39");
                    $("select[name='" + field + "']").next().find(".select2-selection").css("border-color", "#dd4b39");
                }

                $("input[name='" + field + "'],select[name='" + field + "'],.select2-selection").on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
            if (fieldsComplete) {

                Swal.fire({
                    title: 'Update?',
                    text: 'Update Contact Information?',
                    icon: 'question',
                    iconColor: '#ffc107',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    customClass: 'required_alert'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "<?= site_url('promo/contactInfo') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {

                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Contact Information update successful!',
                                        icon: 'success',
                                        iconColor: '#15ca20',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok',
                                        customClass: 'required_alert',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            profileData('contactInfo')
                                            // location.reload();
                                        }
                                    });
                                } else {
                                    alert(data);
                                }
                            },
                            async: false,
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }
                })
            }
        });

        $("form#fesc").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var fields = [];
            let notRequired = ['suffix', 'mn']
            for (let entry of formData.entries()) {

                if (!notRequired.includes(entry[0])) {

                    fields.push(entry[0]);
                }
            }
            console.log(fields)
            var fieldsComplete = true;
            var errMessage = false;
            for (var i = 0; i < fields.length; i++) {

                var field = fields[i];
                var value = $("input[name='" + field + "'],select[name='" + field + "']").val().trim();

                if (value === '') {
                    if (!errMessage) {
                        alertRequired();
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("input[name='" + field + "'],select[name='" + field + "']").css("border-color", "#dd4b39");
                    $("select[name='" + field + "']").next().find(".select2-selection").css("border-color", "#dd4b39");
                }

                $("input[name='" + field + "'],select[name='" + field + "'],.select2-selection").on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
            if (fieldsComplete) {

                Swal.fire({
                    title: 'Update?',
                    text: 'Update Family/Educational Background & Skills/Competencies?',
                    icon: 'question',
                    iconColor: '#ffc107',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    customClass: 'required_alert'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "<?= site_url('promo/famEducBackground') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {

                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Family/Educational Background & Skills/Competencies update successful!',
                                        icon: 'success',
                                        iconColor: '#15ca20',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok',
                                        customClass: 'required_alert',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            profileData('fesc')
                                            // location.reload();
                                        }
                                    });
                                } else {
                                    alert(data);
                                }
                            },
                            async: false,
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }
                })
            }
        });

        $("form#appHistory").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var fields = [];
            let notRequired = ['suffix', 'mn']
            for (let entry of formData.entries()) {

                if (!notRequired.includes(entry[0])) {

                    fields.push(entry[0]);
                }
            }
            console.log(fields)
            var fieldsComplete = true;
            var errMessage = false;
            for (var i = 0; i < fields.length; i++) {

                var field = fields[i];
                var value = $("input[name='" + field + "'],select[name='" + field + "']").val().trim();

                if (value === '') {
                    if (!errMessage) {
                        alertRequired();
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("input[name='" + field + "'],select[name='" + field + "']").css("border-color", "#dd4b39");
                    $("select[name='" + field + "']").next().find(".select2-selection").css("border-color", "#dd4b39");
                }

                $("input[name='" + field + "'],select[name='" + field + "'],.select2-selection").on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
            if (fieldsComplete) {

                Swal.fire({
                    title: 'Update?',
                    text: 'Update Application History?',
                    icon: 'question',
                    iconColor: '#ffc107',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    customClass: 'required_alert'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "<?= site_url('promo/appHistory') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {

                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Application History update successful!',
                                        icon: 'success',
                                        iconColor: '#15ca20',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok',
                                        customClass: 'required_alert',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            profileData('appHistory')
                                        }
                                    });
                                } else {
                                    alert(data);
                                }
                            },
                            async: false,
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }
                })
            }
        });

        $("form#benefits").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var fields = [];
            let notRequired = ['suffix', 'mn']
            for (let entry of formData.entries()) {

                if (!notRequired.includes(entry[0])) {

                    fields.push(entry[0]);
                }
            }
            console.log(fields)
            var fieldsComplete = true;
            var errMessage = false;
            for (var i = 0; i < fields.length; i++) {

                var field = fields[i];
                var value = $("input[name='" + field + "']").val().trim();

                if (value === '') {
                    if (!errMessage) {
                        alertRequired();
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("input[name='" + field + "']").css("border-color", "#dd4b39");
                }

                $("input[name='" + field + "']").on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
            if (fieldsComplete) {

                Swal.fire({
                    title: 'Update?',
                    text: 'Update Benefits Information?',
                    icon: 'question',
                    iconColor: '#ffc107',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    customClass: 'required_alert'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "<?= site_url('promo/benefits') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {

                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Benefits Information update successful!',
                                        icon: 'success',
                                        iconColor: '#15ca20',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok',
                                        customClass: 'required_alert',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            profileData('benefits')
                                        }
                                    });
                                } else {
                                    alert(data);
                                }
                            },
                            async: false,
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }
                })
            }

        });

        $("form#docFile_upload").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var fields = [];
            let notRequired = ['suffix', 'mn']
            for (let entry of formData.entries()) {

                if (!notRequired.includes(entry[0])) {

                    fields.push(entry[0]);
                }
            }
            console.log(fields)
            var fieldsComplete = true;
            var errMessage = false;
            for (var i = 0; i < fields.length; i++) {

                var field = fields[i];
                var value = $("input[name='" + field + "']").val().trim();

                if (value === '') {
                    if (!errMessage) {
                        alertRequired();
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("input[name='" + field + "']").css("border-color", "#dd4b39");
                }

                $("input[name='" + field + "']").on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
            if (fieldsComplete) {

                Swal.fire({
                    title: 'Upload?',
                    text: 'Upload 201 document?',
                    icon: 'question',
                    iconColor: '#ffc107',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    customClass: 'required_alert'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "<?= site_url('promo/docFile_upload') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {

                                    Swal.fire({
                                        title: 'Success!',
                                        text: '201 Document upload successful!',
                                        icon: 'success',
                                        iconColor: '#15ca20',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok',
                                        customClass: 'required_alert',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            profileData('201doc')
                                        }
                                    });
                                } else {
                                    alert(data);
                                }
                            },
                            async: false,
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }
                })
            }
        });

        $("form#remarks").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var remarks = $('textarea[name="remarks"]').val().trim();
            if (remarks == '') {
                $("textarea[name='remarks']").css("border-color", "#dd4b39");
                $("textarea[name='remarks']").on("focus", function() {
                    $(this).css("border-color", "");
                });
                alertRequired()
            } else {

                Swal.fire({
                    title: 'Update?',
                    text: 'Update Remarks?',
                    icon: 'question',
                    iconColor: '#ffc107',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    customClass: 'required_alert'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "<?= site_url('promo/remarks') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {

                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Remarks update successful!',
                                        icon: 'success',
                                        iconColor: '#15ca20',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok',
                                        customClass: 'required_alert',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            profileData('remarks')
                                        }
                                    });
                                } else {
                                    alert(data);
                                }
                            },
                            async: false,
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }
                })
            }
        });

        $("form#save_userAccount").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var fields = [];
            let notRequired = ['suffix', 'mn']
            for (let entry of formData.entries()) {

                if (!notRequired.includes(entry[0])) {

                    fields.push(entry[0]);
                }
            }
            console.log(fields)
            var fieldsComplete = true;
            var errMessage = false;
            for (var i = 0; i < fields.length; i++) {

                var field = fields[i];
                var value = $("input[name='" + field + "']").val().trim();

                if (value === '') {
                    if (!errMessage) {
                        alertRequired();
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("input[name='" + field + "']").css("border-color", "#dd4b39");
                }

                $("input[name='" + field + "']").on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
            if (fieldsComplete) {

                Swal.fire({
                    title: 'Add?',
                    text: 'Add user account?',
                    icon: 'question',
                    iconColor: '#ffc107',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    customClass: 'required_alert'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "<?= site_url('promo/save_userAccount') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {
                                    if (response.checkUser) {
                                        Swal.fire({
                                            title: 'Error!',
                                            text: 'Username not available!',
                                            icon: 'error',
                                            iconColor: '#fd3550',
                                            confirmButtonText: 'Ok',
                                            confirmButtonColor: '#0d6efd',
                                            customClass: 'required_alert'
                                        });
                                        $('input[name="username"]').css('border-color', '#dd4b39');
                                        $('input[name="username"]').prop('readonly', false);
                                    } else {
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'User account successfully added!',
                                            icon: 'success',
                                            iconColor: '#15ca20',
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'Ok',
                                            customClass: 'required_alert',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $("div#userAccount").modal("hide");
                                                profileData('userAccount')
                                            }
                                        });
                                    }
                                } else {
                                    alert(data);
                                }
                            },
                            async: false,
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }
                })
            }

        });
    })
</script>