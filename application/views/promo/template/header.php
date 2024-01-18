<!doctype html>

<html lang="en">

<head>
    <!-- Original URL: https://codervent.com/rocker/demo/vertical/index.html
		Date Downloaded: 9/14/2023 3:49:51 PM !-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!--favicon-->
    <link rel="icon" href="<?= base_url('assets/promo_assets/images/promologo.png') ?>" type="image/png" />
    <!--plugins-->
    <link href="<?= base_url('assets/promo_assets/plugins/simplebar/css/simplebar.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/promo_assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/promo_assets/plugins/metismenu/css/metisMenu.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/promo_assets/plugins/datatable/css/dataTables.bootstrap5.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/promo_assets/css/flatpickr.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/promo_assets/plugins/sweetalert2/sweetalert2.min.css') ?>" rel="stylesheet" />
    <!-- loader-->
    <link href="<?= base_url('assets/promo_assets/css/pace.min.css') ?>" rel="stylesheet" />
    <script src="<?= base_url('assets/promo_assets/js/pace.min.js') ?>"></script>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/promo_assets/css/bootstrap.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/promo_assets/css/bootstrap-extended.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/promo_assets/css/css2_864ea98.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/promo_assets/css/app.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/promo_assets/css/icons.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/promo_assets/boxicons-master/css/animations.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/promo_assets/boxicons-master/css/boxicons.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/promo_assets/boxicons-master/css/boxicons.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/promo_assets/boxicons-master/css/transformations.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/promo_assets/font-awesome/css/font-awesome.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/promo_assets/plugins/select2/css/select2.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/promo_assets/plugins/select2/css/select2-bootstrap-5-theme.min.css') ?>" rel="stylesheet" />
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/promo_assets/css/dark-theme.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/promo_assets/css/semi-dark.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/promo_assets/css/header-colors.css') ?>" />
    <title>PROMO V2 | HRMS </title>
</head>
<?php
$m = date('m');
$d = date('d');
if ($m == 12 && $d == 25) { ?>
    <style type="text/css">
        /* customizable snowflake styling */
        .snowflake {
            /* color: #2bb1db; */
            font-size: 2em;
            font-family: Arial, sans-serif;
            text-shadow: 0 0 0px;
        }

        @-webkit-keyframes snowflakes-fall {
            0% {
                top: -10%
            }

            100% {
                top: 100%
            }
        }

        @-webkit-keyframes snowflakes-shake {

            0%,
            100% {
                -webkit-transform: translateX(0);
                transform: translateX(0)
            }

            50% {
                -webkit-transform: translateX(80px);
                transform: translateX(80px)
            }
        }

        @keyframes snowflakes-fall {
            0% {
                top: -10%
            }

            100% {
                top: 100%
            }
        }

        @keyframes snowflakes-shake {

            0%,
            100% {
                transform: translateX(0)
            }

            50% {
                transform: translateX(80px)
            }
        }

        .snowflake {
            position: fixed;
            top: -10%;
            z-index: 9999;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            cursor: default;
            -webkit-animation-name: snowflakes-fall, snowflakes-shake;
            -webkit-animation-duration: 10s, 3s;
            -webkit-animation-timing-function: linear, ease-in-out;
            -webkit-animation-iteration-count: infinite, infinite;
            -webkit-animation-play-state: running, running;
            animation-name: snowflakes-fall, snowflakes-shake;
            animation-duration: 10s, 3s;
            animation-timing-function: linear, ease-in-out;
            animation-iteration-count: infinite, infinite;
            animation-play-state: running, running
        }

        .snowflake:nth-of-type(0) {
            left: 1%;
            -webkit-animation-delay: 0s, 0s;
            animation-delay: 0s, 0s
        }

        .snowflake:nth-of-type(1) {
            left: 10%;
            -webkit-animation-delay: 1s, 1s;
            animation-delay: 1s, 1s
        }

        .snowflake:nth-of-type(2) {
            left: 20%;
            -webkit-animation-delay: 6s, .5s;
            animation-delay: 6s, .5s
        }

        .snowflake:nth-of-type(3) {
            left: 30%;
            -webkit-animation-delay: 4s, 2s;
            animation-delay: 4s, 2s
        }

        .snowflake:nth-of-type(4) {
            left: 40%;
            -webkit-animation-delay: 2s, 2s;
            animation-delay: 2s, 2s
        }

        .snowflake:nth-of-type(5) {
            left: 50%;
            -webkit-animation-delay: 8s, 3s;
            animation-delay: 8s, 3s
        }

        .snowflake:nth-of-type(6) {
            left: 60%;
            -webkit-animation-delay: 6s, 2s;
            animation-delay: 6s, 2s
        }

        .snowflake:nth-of-type(7) {
            left: 70%;
            -webkit-animation-delay: 2.5s, 1s;
            animation-delay: 2.5s, 1s
        }

        .snowflake:nth-of-type(8) {
            left: 80%;
            -webkit-animation-delay: 1s, 0s;
            animation-delay: 1s, 0s
        }

        .snowflake:nth-of-type(9) {
            left: 90%;
            -webkit-animation-delay: 3s, 1.5s;
            animation-delay: 3s, 1.5s
        }

        .snowflake:nth-of-type(10) {
            left: 25%;
            -webkit-animation-delay: 2s, 0s;
            animation-delay: 2s, 0s
        }

        .snowflake:nth-of-type(11) {
            left: 65%;
            -webkit-animation-delay: 4s, 2.5s;
            animation-delay: 4s, 2.5s
        }

        .snowflake:nth-of-type(12) {
            left: 25%;
            -webkit-animation-delay: 2s, 0s;
            animation-delay: 2s, 0s
        }

        /* Array of 12 random colors */
        .snowflake:nth-of-type(1) {
            color: #cce5ff;

        }

        .snowflake:nth-of-type(2) {
            color: #99ccff;

        }

        .snowflake:nth-of-type(3) {
            color: #66b3ff;

        }

        .snowflake:nth-of-type(4) {
            color: #cce5ff;

        }

        .snowflake:nth-of-type(5) {
            color: #99ccff;

        }

        .snowflake:nth-of-type(6) {
            color: #66b3ff;

        }

        .snowflake:nth-of-type(7) {
            color: #66b3ff;

        }

        .snowflake:nth-of-type(8) {
            color: #99ccff;

        }

        .snowflake:nth-of-type(9) {
            color: #cce5ff;

        }

        .snowflake:nth-of-type(10) {
            color: #66b3ff;

        }

        .snowflake:nth-of-type(11) {
            color: #99ccff;

        }

        .snowflake:nth-of-type(12) {
            color: #cce5ff;

        }
    </style>
    <div class="snowflakes" aria-hidden="true">
        <div class="snowflake">❅</div>
        <div class="snowflake">❅</div>
        <div class="snowflake">❅</div>
        <div class="snowflake">❅</div>
        <div class="snowflake">❅</div>
        <div class="snowflake">❅</div>
        <div class="snowflake">❅</div>
        <div class="snowflake">❅</div>
        <div class="snowflake">❅</div>
        <div class="snowflake">❅</div>
        <div class="snowflake">❅</div>
        <div class="snowflake">❅</div>
    </div>
<?php } ?>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--start header -->
        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand gap-3">
                    <div class="mobile-toggle-menu">
                        <i class='bx bx-menu'></i>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative input-icon">
                            <input type="text" class="form-control" name="searchPromo" placeholder="Search Promo here..." autocomplete="off">
                            <span class="position-absolute top-50 translate-middle-y d-flex"><i class="bx bx-search"></i></span>
                        </div>
                    </div>

                    <div class="top-menu ms-auto">
                        <ul class="navbar-nav align-items-center gap-1">
                            <li class="nav-item">
                                <a class="nav-link" style="width: auto;border-radius:0;" href="<?= base_url('placement') ?>">Placement
                                </a>
                            </li>

                            <li class="nav-item dark-mode d-none d-sm-flex">
                                <a class="nav-link dark-mode-icon" href="javascript:;"><i class='bx bx-moon'></i>
                                </a>
                            </li>

                        </ul>
                    </div>
                    <?php
                    $userPhoto = $user['photo'];
                    ?>
                    <div class="user-box dropdown px-3">
                        <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="javascript:;" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="http://<?php echo $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo/' . $userPhoto; ?>" class="user-img" alt="user avatar" />
                            <div class="user-info">
                                <p class="user-name mb-0"><?php echo ucwords(strtolower($user['name'])); ?></p>
                                <p class="designattion mb-0"><?= $user['position']; ?></p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item d-flex align-items-center" href="<?php echo base_url('promo/page/user/userProfile'); ?>"><i class="bx bx-user fs-5"></i><span>User Profile </span></a>
                            </li>
                            <li><a class="dropdown-item d-flex align-items-center" href="<?php echo base_url('promo/logout'); ?>"><i class="bx bx-log-out-circle fs-5"></i><span>Logout </span></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <!--end header -->