<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title> <?= $title; ?></title>
    <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/hrmsv2.png" />
    <!-- vendor css -->
    <link href="<?= base_url(); ?>assets/css/font-awesome.css" rel="stylesheet" />
    <link href="<?= base_url(); ?>assets/css/ionicons.css" rel="stylesheet" />
    <link href="<?= base_url(); ?>assets/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="<?= base_url(); ?>assets/css/toggles-full.css" rel="stylesheet" />
    <link href="<?= base_url(); ?>assets/css/rickshaw.min.css" rel="stylesheet" />
    <link href="<?= base_url(); ?>assets/css/jquery.dataTables.css" rel="stylesheet" />
    <link href="<?= base_url(); ?>assets/css/toastr.min.css" rel="stylesheet" />
    <link href="<?= base_url(); ?>assets/css/sweetalert2.min.css" rel="stylesheet" />
    <link href="<?= base_url(); ?>assets/css/select2.min.css" rel="stylesheet" />
    <link href="<?= base_url(); ?>assets/css/bootstrap-dialog.min.css" rel="stylesheet" />
    <link href="<?= base_url(); ?>assets/css/doc.min.css" rel="stylesheet" />
    <!-- <link href="<?= base_url(); ?>assets/confetti/style.css" rel="stylesheet" /> -->
    <!-- <link href="<?= base_url(); ?>assets/css/bootstrap-datepicker1.min.css" rel="stylesheet" /> -->
    <!-- <link href="<?= base_url(); ?>assets/css/jquery-ui.css" rel="stylesheet" /> -->

    <!-- Amanda CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/amanda.css" />

    <!-- Scroll top css -->
    <style>
        .scroll-to-top-btn {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            width: 40px;
            height: 40px;
            border: none;
            background-color: #FF5733;
            color: #fff;
            cursor: pointer;
            border-radius: 50%;
            text-align: center;
            padding: 5px;
            transition: opacity 0.3s;
        }

        .scroll-to-top-btn i {
            font-size: 20px;
        }

        .loading {
            background-image: url('<?= base_url(); ?>assets/images/icons/loading19.gif');
            background-repeat: no-repeat;
            background-position: right;
        }

        .ok {
            background-image: url('<?= base_url(); ?>assets/images/icons/icn_active.gif');
            background-repeat: no-repeat;
            background-position: right;
        }

        .notok {
            background-image: url('<?= base_url(); ?>assets/images/icons/icon-close-circled.png');
            background-repeat: no-repeat;
            background-position: right;
        }

        #image-container {
            display: flex;
            flex-wrap: wrap;
        }
    </style>

</head>

<body>

    <div class="modal fade" id="modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-modal" onsubmit="event.preventDefault(); return validateModalForm()" novalidate="novalidate">
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="button-cancel" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="form-button" button-message="Saving...">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="appraisalModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="button-cancel" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="am-header">
        <div class="am-header-left">
            <a id="naviconLeft" href="<?= base_url() . '' . $this->session->userdata('sessionType'); ?>" class="am-navicon d-none d-lg-flex"><i class="icon ion-navicon-round"></i></a>
            <a id="naviconLeftMobile" href="<?= base_url() . '' . $this->session->userdata('sessionType'); ?>" class="am-navicon d-lg-none"><i class="icon ion-navicon-round"></i></a>
            <a href="<?= base_url() . '' . $this->session->userdata('sessionType'); ?>" class="am-logo">
                <img src="<?= base_url(); ?>assets/images/hrms.png" rel="stylesheet" width='22' height='22' /> <?= $title; ?>
            </a>
        </div><!-- am-header-left -->

        <div class="am-header-right">

            <?php
            $result = $this->dbmodel
                ->get_row(
                    'access_assignment',
                    'count(assign_id) as num',
                    array('field1' => 'access_code', 'field2' => 'access_id', 'field3' => 'status'),
                    array("019", $this->session->userdata('accessid'), 1)
                );

            if ($result->num == 1) { ?>
                <form action="<?= base_url(); ?>employee/payroll/searchs" method='post'>
                    <div class="form-control-wrapper">
                        <input type="search" name="payrollno" style='border-radius:50px; margin-top:-7px' class="form-control bd-0" autocomplete="off" maxlength='6' placeholder="Search Payroll No." />
                    </div>
                </form>
            <?php } ?>
            <!--
            <div class="dropdown dropdown-notification">
                <a href="<?= base_url() . '' . $this->session->userdata('sessionType'); ?>" class="nav-link pd-x-7 pos-relative" data-toggle="dropdown">
                    <i class="icon ion-ios-bell-outline tx-24"></i>                   
                    <span class="square-8 bg-danger pos-absolute t-15 r-0 rounded-circle"></span>                    
                </a>
                <div class="dropdown-menu wd-300 pd-0-force">
                    <div class="dropdown-menu-header">
                        <label>Notifications </label>
                        <a href="index.html">Mark All as Read </a>
                    </div>

                    <div class="media-list">
                        <a href="index.html" class="media-list-link read">
                            <div class="media pd-x-20 pd-y-15">
                                <img src="../img/img8.jpg" class="wd-40 rounded-circle" alt="" />
                                <div class="media-body">
                                    <p class="tx-13 mg-b-0"><strong class="tx-medium">Suzzeth Bungaos </strong> tagged you and 18 others in a post. </p>
                                    <span class="tx-12">October 03, 2017 8:45am </span>
                                </div>
                            </div>
                        </a>
                        
                        <a href="index.html" class="media-list-link read">
                            <div class="media pd-x-20 pd-y-15">
                                <img src="../img/img9.jpg" class="wd-40 rounded-circle" alt="" />
                                <div class="media-body">
                                    <p class="tx-13 mg-b-0"><strong class="tx-medium">Mellisa Brown </strong> appreciated your work <strong class="tx-medium">The Social Network </strong></p>
                                    <span class="tx-12">October 02, 2017 12:44am </span>
                                </div>
                            </div>
                        </a>
                        <div class="media-list-footer">
                            <a href="index.html" class="tx-12"><i class="fa fa-angle-down mg-r-5"></i> Show All Notifications </a>
                        </div>
                    </div>
                </div>
            </div>-->
            <div class="dropdown dropdown-profile">
                <a href="index.html" class="nav-link nav-link-profile <?= ($menu == "account") ? "active" : ""; ?>" data-toggle="dropdown">
                    <img src="http://172.16.161.100/hrms/employee/<?= $this->session->userdata('photo'); ?>" class="wd-32 rounded-circle" alt="" />
                    <span class="logged-name"><span class="hidden-xs-down"><?= $this->session->userdata('employee_name'); ?> </span> <i class="fa fa-angle-down mg-l-3"></i></span>
                </a>

                <div class="dropdown-menu wd-200">
                    <ul class="list-unstyled user-profile-nav">
                        <li><a href="<?= base_url(); ?>employee/profile"><i class="icon ion-ios-person-outline"></i> Profile </a></li>

                        <li><a href="<?= base_url(); ?>employee/accountsettings"><i class="icon ion-ios-gear-outline <?= ($submenu == "accountsettings") ? "active" : ""; ?>"></i> Account Settings </a></li>
                        <!-- <li><a href="index.html"><i class="icon ion-ios-download-outline"></i> Downloads </a></li>
                        <li><a href="index.html"><i class="icon ion-ios-folder-outline"></i> Collections </a></li> -->
                        <li><a href="<?= base_url() . '' . $this->session->userdata('sessionType'); ?>/logout"><i class="icon ion-power"></i> Sign Out </a></li>
                    </ul>
                </div><!-- dropdown-menu -->
            </div><!-- dropdown -->
        </div><!-- am-header-right -->
    </div><!-- am-header -->

    <div class="am-sideleft">
        <ul class="nav am-sideleft-tab">
            <li class="nav-item">
                <a href="#mainMenu" class="nav-link active"><i class="icon ion-ios-home-outline tx-24"></i></a>
            </li>
            <li class="nav-item">
                <a href="#infoMenu" class="nav-link"><i class="icon ion-information-circled tx-24"></i></a>
            </li>
            <li class="nav-item">
                <a href="#chatMenu" class="nav-link"><i class="fa fa-bars tx-24"> </i></a>
            </li>
            <li class="nav-item">
                <a href="#settingMenu" class="nav-link"><i class="icon ion-ios-gear-outline tx-24"></i></a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="mainMenu" class="tab-pane active">
                <ul class="nav am-sideleft-menu">

                    <li class="nav-item">
                        <a href="<?= base_url() . $this->session->userdata('usertype'); ?>/dashboard" class="nav-link <?= ($menu == "dashboard") ? "active" : ""; ?>">
                            <i class="icon ion-ios-home-outline"></i>
                            <span>Dashboard </span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url() ?>employee/epas" class="nav-link <?= ($menu == "myepas") ? "active" : ""; ?>">
                            <i class="ion-ios-compose-outline"></i>
                            <span> My EPAS </span> &nbsp; <?php echo (@$rate == 1) ? '<span class="badge badge-pill badge-danger"> Comment here  </span>' : ""; ?>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url(); ?>employee/fgt" class="nav-link <?= ($menu == "Family") ? "active" : ""; ?>">
                            <i class="icon ion-ios-list-outline"></i>
                            <span> FGT </span>
                        </a>
                    </li>

                    <?php if ($this->session->userdata('usertype') == 'supervisor') { ?>
                        <li class="nav-item">
                            <a href="#" class="nav-link with-sub <?= ($menu == "epas") ? "active show-sub" : ""; ?>">
                                <i class="ion-ios-person"></i>
                                <span> EPAS </span>
                            </a>
                            <ul class="nav-sub">
                                <li class="nav-item"><a href="<?= base_url() ?>supervisor/epas/resignations" class="nav-link <?= ($submenu == "resigns") ? "active" : ""; ?>"> For Resignation/Retired </a></li>
                                <li class="nav-item"><a href="<?= base_url() ?>supervisor/epas/eoc" class="nav-link <?= ($submenu == "eoc") ? "active" : ""; ?>"> For EOC's (AE/NESCO)</a></li>
                                <li class="nav-item"><a href="<?= base_url() ?>employee/removesub" class="nav-link <?= ($submenu == "siepas") ? "active" : ""; ?>"> For Promo EOC's </a></li>
                            </ul>
                        </li><!-- nav-item -->
                        <li class="nav-item">
                            <a href="<?= base_url() ?>supervisor/interview/lists" class="nav-link <?= ($menu == "interview") ? "active" : ""; ?>">
                                <i class="icon ion-ios-list-outline"></i>
                                <span> Applicants for Interview </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link with-sub">
                                <i class="ion-ios-person"></i>
                                <span> Personnel Request (PR) </span>
                            </a>
                            <ul class="nav-sub">
                                <li class="nav-item">
                                    <a href="<?= base_url() ?>supervisor/pr/request" class="nav-link">
                                        File Request<? echo $submenu; ?>
                                    </a>

                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url() ?>employee/removesub" class="nav-link">
                                        Request for Approval
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link with-sub <?= ($menu == "subordinates") ? "active show-sub" : ""; ?>">
                                <i class="ion-ios-person"></i>
                                <span> Subordinates </span>
                            </a>
                            <ul class="nav-sub">
                                <li class="nav-item"><a href="<?= base_url() ?>supervisor/subordinates" class="nav-link <?= ($submenu == "lists") ? "active" : ""; ?>"> Lists </a></li>
                                <li class="nav-item"><a href="<?= base_url() ?>supervisor/removesubordinates" class="nav-link <?= ($submenu == "removesubordinates") ? "active" : ""; ?>">Remove Subordinates</a></li>
                            </ul>


                        </li>
                    <?php } ?>


                    <li class="nav-item">
                        <a href="#" class="nav-link with-sub <?= ($menu == "si") ? "active show-sub" : ""; ?>">
                            <i class="ion-arrow-graph-up-right"></i>
                            <span> Salary Increase </span>
                        </a>
                        <ul class="nav-sub">
                            <li class="nav-item"><a href="<?= base_url() ?>employee/si/epas" class="nav-link <?= ($submenu == "siepas") ? "active" : ""; ?>"> My Appraisal </a></li>
                            <?php if ($this->session->userdata('usertype') == 'supervisor') { ?>
                                <li class="nav-item"><a href="<?= base_url() ?>employee/siwizard" class="nav-link <?= ($submenu == "siwizard") ? "active" : ""; ?>"> P.A. Wizard </a></li>
                                <li class="nav-item"><a href="http://172.16.161.100/hrms/managersv2/" class="nav-link <?= ($submenu == "simanager") ? "active" : ""; ?>"> Amount Increase Adjustment (For Managers)</a></li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php

                    //BENEFITS MENU
                    $result = $this->dbmodel
                        ->get_row(
                            'access_assignment',
                            'count(assign_id) as num',
                            array('field1' => 'access_code', 'field2' => 'access_id', 'field3' => 'status'),
                            array("020", $this->session->userdata('accessid'), 1)
                        );

                    if ($result->num == 1) {   ?>
                        <li class="nav-item">
                            <a href="index.html" class="nav-link with-sub <?= ($menu == "benefits") ? "active show-sub" : ""; ?>">
                                <i class="icon ion-document-text"></i>
                                <span> Benefits </span>
                            </a>
                            <ul class="nav-sub">
                                <li class="nav-item"><a href="<?= base_url(); ?>employee/search_employee" class="nav-link <?= ($submenu == "search_employee") ? "active" : ""; ?>">Search Employee</a></li>
                                <li class="nav-item"><a href="<?= base_url(); ?>employee/final_completion" class="nav-link <?= ($submenu == "finalcompletion") ? "active" : ""; ?>">Final Completion</a></li>
                                <li class="nav-item"><a href="<?= base_url(); ?>employee/all_employees" class="nav-link <?= ($submenu == "all_employees") ? "active" : ""; ?>">All Employees</a></li>
                                <li class="nav-item"><a href="<?= base_url(); ?>employee/new_employees" class="nav-link <?= ($submenu == "new_employees") ? "active" : ""; ?>">New Employees</a></li>
                                <li class="nav-item"><a href="<?= base_url(); ?>employee/blacklisted_employees" class="nav-link <?= ($submenu == "blacklisted_employees") ? "active" : ""; ?>">Blacklisted Employees</a></li>
                                <li class="nav-item"><a href="<?= base_url(); ?>employee/jobtransfer" class="nav-link <?= ($submenu == "jobtransfer") ? "active" : ""; ?>">Job Transfer</a></li>
                                <li class="nav-item"><a href="<?= base_url(); ?>employee/inactive_employees" class="nav-link <?= ($submenu == "inactive_employees") ? "active" : ""; ?>">Inactive Employee</a></li>
                                <li class="nav-item"><a href="<?= base_url(); ?>employee/qbe_reports" class="nav-link <?= ($submenu == "qb_reports") ? "active" : ""; ?>">Reports</a></li>
                            </ul>
                        </li>
                    <?php
                    }

                    //PAYROLL MENU
                    $result = $this->dbmodel
                        ->get_row(
                            'access_assignment',
                            'count(assign_id) as num',
                            array('field1' => 'access_code', 'field2' => 'access_id', 'field3' => 'status'),
                            array("019", $this->session->userdata('accessid'), 1)
                        );

                    if ($result->num == 1) {   ?>
                        <li class="nav-item">
                            <a href="index.html" class="nav-link with-sub <?= ($menu == "payroll") ? "active show-sub" : ""; ?>">
                                <i class="icon ion-document-text"></i>
                                <span> Payroll </span>
                            </a>
                            <ul class="nav-sub">
                                <li class="nav-item"><a href="<?= base_url(); ?>employee/payroll/searches/" class="nav-link <?= ($submenu == "search") ? "active" : ""; ?>"> Search Employees </a></li>
                                <li class="nav-item"><a href="<?= base_url(); ?>employee/payroll/upload" class="nav-link <?= ($submenu == "upload") ? "active" : ""; ?>"> Upload Remittances </a></li>
                                <li class="nav-item"><a href="<?= base_url(); ?>employee/payroll/new" class="nav-link <?= ($submenu == "new") ? "active" : ""; ?>"> New Employees </a></li>
                                <li class="nav-item"><a href="<?= base_url(); ?>employee/payroll/employees" class="nav-link <?= ($submenu == "employees") ? "active" : ""; ?>"> Active Employees </a></li>
                                <li class="nav-item"><a href="<?= base_url(); ?>employee/payroll/transfer" class="nav-link <?= ($submenu == "transfer") ? "active" : ""; ?>"> Job Transfer </a></li>
                                <li class="nav-item"><a href="<?= base_url(); ?>employee/payroll/blacklisted" class="nav-link <?= ($submenu == "blacklisted") ? "active" : ""; ?>"> Blacklisted Employees </a></li>
                                <li class="nav-item"><a href="<?= base_url(); ?>employee/payroll/poslevel" class="nav-link <?= ($submenu == "poslevel") ? "active" : ""; ?>"> Position Leveling </a></li>
                                <li class="nav-item"><a href="<?= base_url(); ?>employee/payroll/pcc" class="nav-link <?= ($submenu == "pcc") ? "active" : ""; ?>"> PCC </a></li>
                            </ul>
                        </li>
                    <?php
                    }

                    //ACOUNTING MENU
                    $result = $this->dbmodel
                        ->get_row(
                            'access_assignment',
                            'count(assign_id) as num',
                            array('field1' => 'access_code', 'field2' => 'access_id', 'field3' => 'status'),
                            array("021", $this->session->userdata('accessid'), 1)
                        );

                    if ($result->num == 1) {
                    ?>
                        <li class="nav-item">
                            <a href="index.html" class="nav-link with-sub <?= ($menu == "accounting") ? "active show-sub" : ""; ?>">
                                <i class="icon ion-document-text"></i>
                                <span> Accounting </span>
                            </a>
                            <ul class="nav-sub">
                                <li class="nav-item"><a href="<?= base_url(); ?>employee/accounting/masterfile" class="nav-link <?= ($submenu == "masterfile") ? "active" : ""; ?>"> Employees </a></li>
                            </ul>
                        </li> <?php } ?>

                    <!-- nav-item -->
                </ul>
            </div><!-- #mainMenu -->
            <div id="infoMenu" class="tab-pane">

                <ul class="nav am-sideleft-menu">
                    <li class="nav-item">
                        <a href="<?= base_url() ?>employee/about" class="nav-link <?= ($menu == "about") ? "active" : ""; ?>">
                            <i class="icon ion-ios-filing-outline"></i>
                            <span> About Us </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url() ?>employee/contactus" class="nav-link <?= ($menu == "contactus") ? "active" : ""; ?>">
                            <i class="icon ion-ios-paperplane-outline"></i>
                            <span> Contact Us </span>
                        </a>
                    </li>

                </ul>
            </div><!-- #emailMenu -->
            <div id="chatMenu" class="tab-pane">
                <div class="chat-search-bar">
                    <input type="search" class="form-control wd-150" placeholder="Search chat..." />
                    <button class="btn btn-secondary"><i class="fa fa-search"></i></button>
                </div>

                <label class="pd-x-15 tx-uppercase tx-11 mg-t-20 tx-orange mg-b-10 tx-medium">Recent Chat History </label>
                <div class="list-group list-group-chat">
                    <a href="#" class="list-group-item">
                        <div class="d-flex align-items-center">
                            <img src="../img/img6.jpg" class="wd-32 rounded-circle" alt="" />
                            <div class="mg-l-10">
                                <h6>Russell M. Evans </h6>
                                <span>Tuesday, 10:33am </span>
                            </div>
                        </div>
                        <p>Nor again is there ______ who loves or pursues __ desires to obtain pain __ itself, because it is ____... </p>
                    </a>
                </div>
                <span class="d-block pd-15 tx-12">Loading messages... </span>
            </div>
            <div id="settingMenu" class="tab-pane">
                <div class="pd-x-15">
                    <label class="tx-uppercase tx-11 mg-t-10 tx-orange mg-b-15 tx-medium">Quick Settings </label>
                    <div class="bd pd-15">
                        <h6 class="tx-13 tx-normal tx-gray-800">Daily Newsletter </h6>
                        <p class="tx-12">Get notified when someone ____ is trying to access ____ account. </p>
                        <div class="toggle toggle-light warning"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="am-mainpanel">
        <div class="am-pagetitle">

            <h5 class="am-title" style='color:grey'> <?= ucwords(strtolower($menu)); ?> <?= ($submenu == '') ? "" : " <i class='icon ion-forward'></i> ";
                                                                                        echo ucwords(strtolower($submenu)); ?> </h5>


            <div class="pd-10">
                <ul class="nav nav-gray-700 flex-column flex-sm-row" role="tablist">
                    <?php
                    if ($this->session->userdata('emp_id') == '01186-2023') {
                        echo '<li class="nav-item"><a class="nav-link" href="' . base_url('promo') . '" >Promo</a></li>';
                    }

                    ?>

                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#" role="tab">Timekeeping</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#" role="tab">Employee Benefits</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#" role="tab">Violations Monitoring</a></li>
                    <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#" role="tab">Feedback</a></li>    -->
                </ul>
            </div>
        </div>