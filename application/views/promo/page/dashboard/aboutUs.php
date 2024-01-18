<style>

</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="card bg-danger radius-10 text-white">
            <div class="card-body">
                <h5 class="card-title text-center">About Us</h5>
                <hr>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <blockquote class="blockquote text-center">
                            <p>
                                <strong>HRMS</strong> is an online system used by the HRD staff in managing the records of all employees.
                                One significant module presented is the Promo Module which comprises namely: Blacklisted Employee's, Contract,
                                Outlet, Promo Employee's Masterfile, Reports, Resignation/Termination, Setups, User Accounts, and Utilities.
                            </p>
                        </blockquote>
                    </div>
                </div>
                <div class="row">
                    <h5 class="card-title text-center">Contact Us</h5>
                    <hr>
                    <div class="col-md-4">
                        <p class="text-center mb-0" style="font-size: 50px;">
                            <i class="fadeIn animated bx bx-home"></i>
                        </p>
                        <div class="company-details">
                            <div class="text-center">
                                AGC Corporate Center, North Wing ICM Bldg., Dampas District, Tagbilaran City, Bohol
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <p class="text-center mb-0" style="font-size: 50px;">
                            <i class="fadeIn animated bx bx-phone"></i>
                        </p>
                        <div class="company-details">
                            <div class="d-flex justify-content-between align-items-center">
                                GC
                                <span>1819</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                EBM, GO
                                <span>1821</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                TMS, BR, Institutional, CWO, Leasing/PMS
                                <span>1822</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                HRMS, Timekeeping, CMS <span>1844</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                FARMS, ATP <span>1847</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <p class="text-center mb-0" style="font-size: 50px;">
                            <i class="fadeIn animated bx bx-envelope"></i>
                        </p>
                        <div class="company-details">
                            <div class="text-center">
                                <a href="mailto:itsysdev@alturasbohol.com">
                                    <p class="text-white">itsysdev@alturasbohol.com</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <h5 class="card-title text-center">Our Team</h5>
                    <hr>
                    <div class="row justify-content-center mx-auto">
                        <?php
                        $team = array(
                            'head'          => '43864-2013',
                            'supervisor'    => '21114-2013',
                            'current_sa'    => '02897-2023',
                            'current_prog'  => '01186-2023',
                            'previous_sa'   => '01476-2015',
                            'previous_prog' => '06359-2013'
                        );
                        $i = 0;
                        foreach ($team as $key => $emp_id) {
                            $i++;
                            $app    = $this->promo_model->selectAll_tcR('applicant', array('app_id' => $emp_id));
                            $emp3   = $this->promo_model->selectAll_tcR('employee3', array('emp_id' => $emp_id));
                            $name   = $app['lastname'] . ', ' . $app['firstname'] . ' ' . substr($app['middlename'], 0, 1) . '. ' . $app['suffix'];
                            $photo = "http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}/hrms/promo/{$app['photo']}";
                            $col    = ($i == 1 || $i == 2) ? '12' : '6';

                        ?>
                            <div class="col-md-<?= $col ?> mt-2 mb-2">
                                <div class=" text-center">
                                    <div class="p-1">
                                        <img src="<?= $photo  ?>" width="110" height="110" class="rounded-circle shadow p-1 bg-white" alt="">
                                        <h5 class="mb-0 mt-3 text-white"><?= ucwords(strtolower($name)) ?></h5>
                                        <p class="text-white"><?= $emp3['position'] ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php  }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->