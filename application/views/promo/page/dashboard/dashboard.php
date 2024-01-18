<style>
    .cont {
        display: flex;
        align-items: center;
        justify-content: center;

    }
</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-12 col-lg-7 col-xl-8 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-header bg-transparent">
                        <div class="d-flex align-items-center">
                            <div class="parent-icon font-22">Number of Active Promo per Store</div>
                            <div class="dropdown ms-auto">
                                <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                                    <i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li style="width: 200px;">
                                        <a class="dropdown-item" href="<?= base_url('promo/page/reports/statisticRep') ?>">
                                            Statistics Report
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?= (count($failedEpas) == 0) ? '#' : base_url('promo/page/reports/failedEpas') ?>">
                                            Failed EPAS <span class="float-end badge bg-danger rounded-pill"><?= count($failedEpas) ?></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?= (count($bday) == 0) ? '#' : base_url('promo/page/reports/bdayToday') ?>">
                                            Birthdays Today <span class="float-end badge bg-danger rounded-pill"><?= count($bday) ?></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-7 col-xl-7 cont">
                                <div class="chart-container-1">
                                    <canvas id="chart4"></canvas>
                                </div>
                            </div>
                            <div class="col-lg-5 col-xl-5">
                                <ul class="list-group list-group-flush">
                                    <?php
                                    $bUs = $this->promo_model->locate_promo_bu('asc');
                                    $i = 0;
                                    foreach ($bUs as $value) {

                                        $count = $this->promo_model->active_promoStore($value['bunit_field']);
                                        echo    '<li class="list-group-item d-flex bg-transparent align-items-center" style="font-size: 11px;">
                                                    <i class="bx bxs-circle me-1" style="color: #' . $color[$i] . '"></i>
                                                    ' . $value['bunit_name'] . ' 
                                                    <span class="ms-auto"><b>' . $count['num'] . '</b></span>
                                                </li>';
                                        $i++;
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5 col-xl-4 d-flex">
                <div class="card w-100 radius-10">
                    <div class="card-body">
                        <a href="<?= (count($newPromo) == 0) ? '#' : base_url('promo/page/reports/newPromo') ?>">
                            <div class="card radius-10 border shadow-none">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">New Promo </p>
                                            <h4 class="my-1"><?= count($newPromo) ?></h4>
                                            <p class="mb-0 font-13">tagged from last month. </p>
                                        </div>
                                        <div class="widgets-icons-2 bg-gradient-blues text-white ms-auto"><i class='bx bx-user-plus'></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="<?= (count($total) == 0) ? '#' : base_url('promo/page/promo/masterfile') ?>">
                            <div class="card radius-10 border shadow-none">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">Active Promo </p>
                                            <h4 class="my-1"><?= count($total) ?></h4>
                                            <p class="mb-0 font-13">as of <?= date("F j, Y") ?>. </p>
                                        </div>
                                        <div class="widgets-icons-2 bg-gradient-ohhappiness text-white ms-auto"><i class='bx bxs-group'></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="<?= (count($eoc) == 0) ? '#' : base_url('promo/page/contract/eoclist'); ?>">
                            <div class="card radius-10 border shadow-none">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">End of Contract</p>
                                            <h4 class="my-1"><?= count($eoc) ?></h4>
                                            <p class="mb-0 font-13">today <?= date("F j, Y") ?>. </p>
                                        </div>
                                        <div class="widgets-icons-2 bg-gradient-kyoto text-white ms-auto"><i class='bx bx-calendar-exclamation'></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div><!--end row-->
    </div>
</div>
<!--end page wrapper -->