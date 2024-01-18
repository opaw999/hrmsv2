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
    <link href="<?= base_url(); ?>assets/medium-editor/medium-editor.css" rel="stylesheet" />
     <link href="<?= base_url(); ?>assets/medium-editor/default.css" rel="stylesheet" />
     <link href="<?= base_url(); ?>assets/summernote/summernote-bs4.css" rel="stylesheet" />
     <link href="<?= base_url(); ?>assets/highlightjs/github.css" rel="stylesheet" />
    <!-- <link href="<?= base_url(); ?>assets/css/bootstrap-datepicker1.min.css" rel="stylesheet" /> -->
    <!-- <link href="<?= base_url(); ?>assets/css/jquery-ui.css" rel="stylesheet" /> -->

    <!-- Amanda CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/amanda.css" />

    
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


    <div class="am-header">
        <div class="am-header-left">
            <a id="naviconLeft" href="#" class="am-navicon d-none d-lg-flex"><i class="icon ion-navicon-round"></i></a>
            <a id="naviconLeftMobile" href="#" class="am-navicon d-lg-none"><i class="icon ion-navicon-round"></i></a>
            <a href="#" class="am-logo">
                <img src="<?= base_url(); ?>assets/images/hrms.png" rel="stylesheet" width='22' height='22' /> <?= $title; ?>
            </a>
        </div><!-- am-header-left -->

        <div class="am-header-right">
            
            <div class="dropdown dropdown-profile">
                <a href="index.html" class="nav-link nav-link-profile" data-toggle="dropdown">
                    <img src="#" class="wd-32 rounded-circle" alt="" />
                    <span class="logged-name"><span class="hidden-xs-down"><?= $this->session->userdata('employee_name'); ?> </span> <i class="fa fa-angle-down mg-l-3"></i></span>
                </a>

                <div class="dropdown-menu wd-200">
                    <ul class="list-unstyled user-profile-nav">
                        <li><a href="<?= base_url(); ?>employee/profile"><i class="icon ion-ios-person-outline"></i> Profile </a></li>
                        <li><a href="<?= base_url(); ?>employee/accountsettings"><i class="icon ion-ios-gear-outline"></i> Account Settings </a></li>
                        <li><a href="<?= base_url() . '' . $this->session->userdata('sessionType'); ?>/logout"><i class="icon ion-power"></i> Sign Out </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

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
                            <a href="#" class="nav-link active">
                                <i class="icon ion-ios-home-outline"></i>
                                <span>Dashboard </span>
                            </a>
                        </li>  
                    <?php
                    /** Note:
                     * Temporary for menus.
                     * This will be dynamically displayed from a table
                     */
                    $search = array("Search Employees"      => "placement/search/employee",
                                    "Search Applicant"      => "placement/search/applicant");

                    $masterfile = array("Active Employees"  => "placement/masterfile/employees",
                                    "Blacklisted"           => "placement/masterfile/blacklisted_employees", 
                                    "Promotions"            => "placement/masterfile/",
                                    "Job Transfers"         => "placement/masterfile/employee_jobtransfer",
                                    "Resignations"          => "placement/masterfile/", 
                                    "Solo Parent"           => "placement/masterfile/solo_parent",                  
                                    "Position Leveling (New)"   => "placement/masterfile/position_leveling",
                                    "List of Positions (OLD)"   => "placement/masterfile/position_old",
                                    "List of Positions (UPDATED)"   => "placement/masterfile/position_new_updated");

                    $creates = array("Create New Job Transfer"  => "placement/creates/form_jobtransfer", 
                                    "Create New Promotion"  => "placement/creates/",                                    
                                    "Create New Blacklist"  => "placement/creates/form_add_blacklist",            
                                    "Create New Eligibilities"  => "placement/creates/",
                                    "Create New Solo Parent"    => "placement/creates/createSoloparent",  
                                    "Create Kra"    => "placement/creates/createKra",  
                                    "Create Loyalty Awardees"    => "placement/creates/CreateLoyaltyAwardees");   
                                
                    $reports = array(
                                    "Query By Example"      => "placement/reports/",
                                    "Statistics Report"     => "placement/reports/",
                                    "Job Transfer Report"   => "placement/reports/",
                                    "Promotion Report"      => "placement/reports/",                                   
                                    "Blacklist Report"      => "placement/reports/",
                                    "Subordinates Report"   => "placement/reports/",
                                    "Loyalty Awardees Reports" => "placement/reports/",
                                    "Positions and Leveling Reports" => "placement/reports/",
                                    "Birthday Celebrants Report" => "placement/reports/birthdayCelebrants");

                    $transactions = array(
                                    "Reprint Permit/Contracts" => "placement/transactions/",
                                    "Reprint Contracts"     => "placement/transactions/formContract",
                                    "Reprint Guard Posting" => "placement/transactions/",
                                    "Process Probi to Regular" => "placement/transactions/",
                                    "Process Contractual to Probi"  => "placement/transactions/",
                                    "Employee Regularization"  => "placement/transactions/",
                                    "Regularization for PRA" => "placement/transactions/",
                                    "Renew Solo Parent"     => "placement/transactions/",
                                    "SIL Updating"          => "placement/transactions/"); 
                                    
                    $setup = array("Setup New Employee"     => "placement/setup/",
                                    "Setup Position Level"  => "placement/setup/",
                                    "Setup Subordinates"    => "placement/setup/");                

                    $menu       = array("Search"=>$search,"Masterfile" =>$masterfile,"Creates"=>$creates,"Transactions"=>$transactions,
                                        "Setup"=>$setup,"Reports"=>$reports);                   
                                    
                    foreach($menu as $key => $arr_val)
                    {
                        ?>
                        <li class="nav-item">
                            <a href="#" class="nav-link with-sub">
                            <?php
                                switch($key){
                                    case "Masterfile"   : echo '<i class="ion-ios-person"></i>'; break;
                                    case "Creates"      : echo '<i class="ion-ios-compose-outline"></i>'; break;
                                    case "Search"       : echo '<i class="ion-ios-search"></i>'; break;
                                    case "Transactions" : echo '<i class="icon ion-ios-list-outline"></i>'; break;
                                    case "Setup"        : echo '<i class="icon ion-ios-gear-outline"></i>'; break;
                                    case "Reports"      : echo '<i class="icon ion-document-text"></i>';  break;
                                }?>
                                <span> <?= $key;?> </span>
                            </a>
                            <ul class="nav-sub">
                                <?php
                                foreach($arr_val as $keys => $value) { ?>
                                    <li class="nav-item">
                                        <a href="<?= base_url().$value;?>" class="nav-link">
                                            <?= $keys;?>
                                        </a>
                                    </li> <?php 
                                } ?>
                            </ul>
                        </li> <?php 
                    } ?>              

                </ul>
            </div><!-- #mainMenu -->
            <div id="infoMenu" class="tab-pane">

                <ul class="nav am-sideleft-menu">
                    <li class="nav-item">
                        <a href="<?= base_url() ?>employee/about" >
                            <i class="icon ion-ios-filing-outline"></i>
                            <span> About Us </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url() ?>employee/contactus" >
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

            <h5 class="am-title" style='color:grey'> </h5>

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
                </ul>
            </div>
        </div>