<style>
    .required_alert {
        width: 300px;
        font-size: 12px;
    }

    .readonly[readonly] {
        background-color: #e9ecef;

    }
</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <?php
        $username   = $this->session->userdata('username');
        $userPhoto  = $user['photo'];
        $name       = ucwords(strtolower($user['lastname'] . ', ' . $user['firstname'] . ' ' . substr($user['middlename'], 0, 1) . '. ' . $user['suffix']));
        $status     = $user['current_status'];
        if ($status == 'Active') {
            $stat   = 'success';
        } else if ($status == 'blacklisted') {
            $stat   = 'danger';
        } else {
            $stat   = 'warning';
        }
        $birthdate = date('F j, Y', strtotime($user['birthdate']));
        ?>
        <div class="row">
            <div class="col-12 col-lg-7 col-xl-4">
                <div class="card radius-15 bg-gradient-blues">
                    <div class="card-body">
                        <div>
                            <h5 class="card-title text-white">User Profile</h5>
                        </div>
                        <hr class="text-white mb-0">
                        <div class="p-4 radius-15 text-center">
                            <img src="http://<?php echo $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo/' . $userPhoto; ?>" width="150" height="150" class="rounded-circle shadow p-1 bg-white" alt="">
                            <h5 class="mb-0 mt-1 text-white"><?= $name ?></h5>
                            <p class="mb-0 text-white"><?= $user['emp_id'] ?></p>
                            <p class="mb-0 text-white"><?= $user['position'] ?></p>
                            <p class="mb-0 text-white"> Born <?= $birthdate ?></p>
                            <span class="badge mb-1 bg-<?= $stat ?>"><?= $status ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-7 col-xl-4 d-flex">
                <div class="card radius-15" style="width: 100%;">
                    <div class="card-body">
                        <div>
                            <h5 class="card-title">Update Username</h5>
                        </div>
                        <hr>
                        <input type="hidden" class="form-control" name="emp_id" value="<?= $user['emp_id'] ?>">
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <label>Current Username</label>
                                <div class="input-group">
                                    <i class="input-group-text fadeIn animated bx bx-user font-22"></i>
                                    <input type="text" class="form-control readonly" name="username" value="<?= $username ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <label>New Username</label>
                                <input type="text" class="form-control" name="username1" pattern="\w+" autocomplete="off">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <label>Re-Type New Username</label>
                                <input type="text" class="form-control" name="username2" pattern="\w+" autocomplete="off">
                            </div>
                        </div>
                        <button type="submit" id="username" onclick="updateUserAccount(this.id)" class="btn btn-primary pull-right">Submit</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-7 col-xl-4 d-flex">
                <div class="card radius-15" style="width: 100%;">
                    <div class="card-body">
                        <div>
                            <h5 class="card-title">Update Password</h5>
                        </div>
                        <hr>
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <label>Current Password</label>
                                <div class="input-group">
                                    <i class="input-group-text fadeIn animated bx bx-key font-22"></i>
                                    <input type="password" class="form-control" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <label>New Password</label>
                                <input type="password" class="form-control" name="password1" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" autocomplete="off">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <label>Re-Type New Password</label>
                                <input type="password" class="form-control" name="password2" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" autocomplete="off">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="viewPassword" id="flexCheckDefault" onclick="passwordFunc(this.name)" style="border-color: rgb(108, 117, 125);">
                                    <label class="form-check-label text-secondary" for="flexCheckDefault">
                                        Show Password
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <button type="submit" id="password" onclick="updateUserAccount(this.id)" class="btn btn-primary pull-right">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->