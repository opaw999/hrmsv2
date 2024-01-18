<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Clearance extends CI_Controller
{
    public $today;
    public $systemUser;
    function __construct()
    {
        parent::__construct();
        $this->today        = date('Y-m-d');
        $this->systemUser   = $this->session->userdata('emp_id');
        $empId = $this->session->userdata('emp_id');
        if (!isset($empId)) {

            redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrmsv2/employee');
        }
    }
    public function clearanceList()
    {
        $list = $this->promo_model->clearanceList();
        $data = [];
        foreach ($list as $row) {
            if ($row['current_status'] == 'Active') {
                $span = 'success';
            } else if ($row['current_status'] == 'blacklisted') {
                $span = 'danger';
            } else {
                $span = ($row['status'] == 'Completed') ? 'warning' : 'danger';
            }
            $condition = array('emp_id' => $row['emp_id'], 'scpr_id' => $row['scpr_id']);
            $effectiveDate = $this->promo_model->selectAll_tcR('secure_clearance_promo_details', $condition);
            $column     = [];
            $column[]   = '<a href="' . base_url('promo/page/promo/profile/' . $row['emp_id']) . '" target="_blank">' . ucwords(strtolower($row['name'])) . '</a>';
            $column[]   = date('m/d/Y', strtotime($row['dateAdded']));
            $column[]   = date('m/d/Y', strtotime($effectiveDate['date_effectivity']));
            $column[]   = '<span class="badge bg-' . $span . '">' . $row['current_status']  . '(' . $row['sub_status'] . ')</span>';
            $column[]   = $row['status'];
            $column[]   = $row['promo_type'];
            $column[]   = $row['reason'];
            $column[]   =   '<a href="javascript:;" class="" id="' . $row['scpr_id'] . '" onclick="clearanceDetails(\'' . $row['reason'] . '*' . $row['scpr_id'] . '\')">
                                <i class="bx bx-folder-open me-0" style="font-size: 22px;"></i>
                            </a>';
            $data[]     = $column;
        }

        echo json_encode(array('data' => $data));
    }

    public function clearanceDetails()
    {
        $input              = $this->input->post(NULL, TRUE);
        $data['result']     = $this->promo_model->selectAll_tcA('secure_clearance_promo_details', array('scpr_id' => $input['scpr_id']));
        $data['reason']     = $input['reason'];
        $data['request']    = 'clearanceDetails';

        $this->load->view('promo/page/page_response', $data);
    }

    public function nameSearch()
    {
        $input  = $this->input->post(NULL, TRUE);
        if ($input['process'] == 'reprint') {
            $query = $this->promo_model->rpClearance($input);
            echo '<ul class="list-group">';
            if (count($query) > 0) {
                foreach ($query as $row) {
                    $name       = ucwords(strtolower($row['name']));
                    $emp_id     = $row['emp_id'];
                    $record_no  = $row['record_no'];
                    $data       = $name . '*' . $emp_id . '*' . $record_no;
                    echo '<a href="javascript:;" class="list-group-item" onclick="getName(\'' . $data . '\')">'
                        . $name .
                        '</a>';
                }
            } else {
                echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
            }
            echo    '</ul>';
        } else if ($input['process'] == 'secure') {

            $query  = $this->promo_model->sClearance($input);
            echo    '<ul class="list-group">';
            if (count($query) > 0) {
                foreach ($query as  $row) {

                    $name       = ucwords(strtolower($row['name']));
                    $emp_id     = $row['emp_id'];
                    $record_no  = $row['record_no'];
                    $data       = $name . '*' . $emp_id . '*' . $record_no;
                    echo '<a href="javascript:;" class="list-group-item" onclick="getName(\'' . $data . '\')">'
                        . $name .
                        '</a>';
                }
            } else {
                echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
            }
            echo    '</ul>';
        } else if ($input['process'] == 'upload') {

            $query  = $this->promo_model->uClearance($input);
            echo    '<ul class="list-group">';
            if (count($query) > 0) {
                foreach ($query as  $row) {

                    $name       = ucwords(strtolower($row['name']));
                    $emp_id     = $row['emp_id'];
                    $record_no  = $row['record_no'];
                    $data       = $name . '*' . $emp_id . '*' . $record_no;
                    echo '<a href="javascript:;" class="list-group-item" onclick="getName(\'' . $data . '\')">'
                        . $name .
                        '</a>';
                }
            } else {
                echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
            }
            echo    '</ul>';
        } else if ($input['process'] == 'witness1') {
            $query = $this->promo_model->searchKey_dstd('distinct', 'witness1', 'employment_witness', array('witness1' => $input['str']));
            echo    '<ul class="list-group">';
            if (count($query) > 0) {
                foreach ($query as  $row) {

                    $name    = htmlspecialchars($row['witness1']);
                    echo '<a href="javascript:;" class="list-group-item" onclick="getName(\'' . $name . '\', \'' . $input['process'] . '\')">'
                        . $name .
                        '</a>';
                }
            } else {
                echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
            }
            echo    '</ul>';
        } else if ($input['process'] == 'witness2') {
            $query = $this->promo_model->searchKey_dstd('distinct', 'witness2', 'employment_witness', array('witness2' => $input['str']));
            echo    '<ul class="list-group">';
            if (count($query) > 0) {
                foreach ($query as  $row) {

                    $name    = htmlspecialchars($row['witness2']);
                    echo '<a href="javascript:;" class="list-group-item" onclick="getName(\'' . $name . '\', \'' . $input['process'] . '\')">'
                        . $name .
                        '</a>';
                }
            } else {
                echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
            }
            echo    '</ul>';
        } else if ($input['process'] == 'w1') {
            $query = $this->promo_model->searchKey_dstd('distinct', 'witness1', 'employment_witness', array('witness1' => $input['str']));
            echo    '<ul class="list-group">';
            if (count($query) > 0) {
                foreach ($query as  $row) {

                    $name    = htmlspecialchars($row['witness1']);
                    echo '<a href="javascript:;" class="list-group-item" onclick="getName(\'' . $name . '\', \'' . $input['process'] . '\')">'
                        . $name .
                        '</a>';
                }
            } else {
                echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
            }
            echo    '</ul>';
        } else if ($input['process'] == 'w2') {
            $query = $this->promo_model->searchKey_dstd('distinct', 'witness2', 'employment_witness', array('witness2' => $input['str']));
            echo    '<ul class="list-group">';
            if (count($query) > 0) {
                foreach ($query as  $row) {

                    $name    = htmlspecialchars($row['witness2']);
                    echo '<a href="javascript:;" class="list-group-item" onclick="getName(\'' . $name . '\', \'' . $input['process'] . '\')">'
                        . $name .
                        '</a>';
                }
            } else {
                echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
            }
            echo    '</ul>';
        } else if ($input['process'] == 'extendSearch') {
            $condition = array('hr_location' => 'asc', 'current_status !=' => 'blacklisted');
            $query = $this->promo_model->searchName($input['str'], $condition);
            echo    '<ul class="list-group">';
            if (count($query) > 0) {
                foreach ($query as $row) {
                    $name       = htmlspecialchars($row['name']);
                    $emp_id     = $row['emp_id'];
                    $record_no  = $row['record_no'];
                    echo '<a href="javascript:;" class="list-group-item" onclick="getPromo(\'' . $name . '\',\'' . $emp_id . '\', \'' . $record_no . '\', \'' . $input['process'] . '\')">'
                        . $name .
                        '</a>';
                }
            } else {
                echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
            }
            echo    '</ul>';
        } else if ($input['process'] == 'printPermit') {
            $condition = array('hr_location' => 'asc', 'current_status !=' => 'blacklisted');
            $query = $this->promo_model->searchName($input['str'], $condition);
            echo    '<ul class="list-group">';
            if (count($query) > 0) {
                foreach ($query as $row) {
                    $name       = htmlspecialchars($row['name']);
                    $emp_id     = $row['emp_id'];
                    $record_no  = $row['record_no'];

                    echo '<a href="javascript:;" class="list-group-item" onclick="getPrint(\'' . $name . '\',\'' . $emp_id . '\', \'' . $input['process'] . '\')">'
                        . $name .
                        '</a>';
                }
            } else {
                echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
            }
            echo    '</ul>';
        } else if ($input['process'] == 'printContract') {
            $condition = array('hr_location' => 'asc', 'current_status !=' => 'blacklisted');
            $query = $this->promo_model->searchName($input['str'], $condition);
            echo    '<ul class="list-group">';
            if (count($query) > 0) {
                foreach ($query as $row) {
                    $name       = htmlspecialchars($row['name']);
                    $emp_id     = $row['emp_id'];
                    $record_no  = $row['record_no'];

                    echo '<a href="javascript:;" class="list-group-item" onclick="getPrint(\'' . $name . '\',\'' . $emp_id . '\', \'' . $input['process'] . '\')">'
                        . $name .
                        '</a>';
                }
            } else {
                echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
            }
            echo    '</ul>';
        } else if ($input['process'] == 'transferRate') {
            $condition = array('hr_location' => 'asc', 'current_status !=' => 'blacklisted');
            $query = $this->promo_model->searchName($input['str'], $condition);
            echo    '<ul class="list-group">';
            if (count($query) > 0) {
                foreach ($query as $row) {
                    $name       = htmlspecialchars($row['name']);
                    $emp_id     = $row['emp_id'];
                    $record_no  = $row['record_no'];

                    echo '<a href="javascript:;" class="list-group-item" onclick="getPromoDetails(\'' . $name . '\',\'' . $emp_id . '\', \'' . $input['process'] . '\')">'
                        . $name .
                        '</a>';
                }
            } else {
                echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
            }
            echo    '</ul>';
        } else if ($input['process'] == 'addOutlet') {
            $condition = array('hr_location' => 'asc', 'current_status' => 'Active');
            $query = $this->promo_model->searchName($input['str'], $condition);
            echo    '<ul class="list-group">';
            if (count($query) > 0) {
                foreach ($query as $row) {
                    $name       = htmlspecialchars($row['name']);
                    $emp_id     = $row['emp_id'];
                    $record_no  = $row['record_no'];

                    echo '<a href="javascript:;" class="list-group-item" onclick="getPromoDetails(\'' . $name . '\',\'' . $emp_id . '\', \'' . $input['process'] . '\')">'
                        . $name .
                        '</a>';
                }
            } else {
                echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
            }
            echo    '</ul>';
        } else if ($input['process'] == 'removeOutlet') {
            $condition = array('hr_location' => 'asc', 'current_status' => 'Active', 'promo_type' => 'ROVING');
            $query = $this->promo_model->searchName($input['str'], $condition);
            echo    '<ul class="list-group">';
            if (count($query) > 0) {
                foreach ($query as $row) {
                    $name       = htmlspecialchars($row['name']);
                    $emp_id     = $row['emp_id'];
                    $record_no  = $row['record_no'];

                    echo '<a href="javascript:;" class="list-group-item" onclick="getPromoDetails(\'' . $name . '\',\'' . $emp_id . '\', \'' . $input['process'] . '\')">'
                        . $name .
                        '</a>';
                }
            } else {
                echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
            }
            echo    '</ul>';
        } else if ($input['process'] == 'transferOutlet') {
            $condition = array('hr_location' => 'asc', 'current_status' => 'Active');
            $query = $this->promo_model->searchName($input['str'], $condition);
            echo    '<ul class="list-group">';
            if (count($query) > 0) {
                foreach ($query as $row) {
                    $name       = htmlspecialchars($row['name']);
                    $emp_id     = $row['emp_id'];
                    $record_no  = $row['record_no'];

                    echo '<a href="javascript:;" class="list-group-item" onclick="getPromoDetails(\'' . $name . '\',\'' . $emp_id . '\', \'' . $input['process'] . '\')">'
                        . $name .
                        '</a>';
                }
            } else {
                echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
            }
            echo    '</ul>';
        } else if ($input['process'] == 'tagtoRecruitment') {
            $select     = 'app_id, t1.firstname, t1.lastname, t1.middlename, t1.suffix, t1.status as status1, t2.status as status2, t2.position, t2.app_code';
            $table1     = 'applicant';
            $table2     = 'applicants';
            $join       = 't1.appcode=t2.app_code';
            $left       = 'left';
            $condition  = "(t1.firstname like '%$input[str]%' OR t1.lastname like '%$input[str]%' OR app_id = '$input[str]')";
            $order      = 't1.lastname|ASC';
            $limit      = '50';
            $query      = $this->promo_model->join_ttjcoA($select, $table1, $table2, $join, $left, $condition, $order, $limit);

            echo '<ul class="list-group">';
            if (count($query) > 0) {
                foreach ($query as $row) {
                    $emp_id = $row['app_id'];
                    $name   = '';
                    $status = '';
                    if (!empty($row['status1'])) {
                        $status = $row['status1'];
                    } else if (!empty($row['status2'])) {
                        $status = $row['status2'];
                    } else {
                        $status = $this->promo_model->selectAll_tcR('employee3', array('emp_id' => $emp_id))['current_status'];
                    }
                    $current_status = $this->promo_model->selectAll_tcR('employee3', array('emp_id' => $emp_id))['current_status'];
                    if (!empty($row['suffix'])) {
                        $name = $row['lastname'] . ', ' . $row['firstname'] . ' ' . $row['suffix'] . ', ' . $row['middlename'];
                    } else {
                        $name = $row['lastname'] . ', ' . $row['firstname'] . ' ' . $row['middlename'];
                    }
                    if (!empty($emp_id)) {
                        echo '<a href="javascript:;" class="list-group-item" onclick="getPromoDetails(\'' . $name . '\',\'' . $emp_id . '\', \'' . $input['process'] . '\', \'' . $status . '\', \'' . $current_status . '\')">'
                            . $name .
                            '</a>';
                    }
                }
            } else {
                echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
            }
            echo '</ul>';
        } else if ($input['process'] == 'resignation') {
            $condition = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL') AND hr_location='asc'";
            $query = $this->promo_model->searchName($input['str'], $condition);
            echo    '<ul class="list-group">';
            if (count($query) > 0) {
                foreach ($query as $row) {
                    $name       = htmlspecialchars($row['name']);
                    $emp_id     = $row['emp_id'];
                    $record_no  = $row['record_no'];
                    $status     = $row['current_status'];
                    echo '<a href="javascript:;" class="list-group-item" onclick="getPromoDetails(\'' . $name . '\',\'' . $emp_id . '\', \'' . $input['process'] . '\', \'' . $status . '\')">'
                        . $name .
                        '</a>';
                }
            } else {
                echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
            }
            echo    '</ul>';
        } else if ($input['process'] == 'tagResignation') {
            $select     = '*';
            $table1     = 'users';
            $table2     = 'employee3';
            $join       = 't1.emp_id=t2.emp_id';
            $condition  = "t1.usertype = 'supervisor' AND (name like '%$input[str]%' or t2.emp_id = '$input[str]')";
            $order      = 't2.name|ASC';
            $limit      = '10';
            $query      = $this->promo_model->join_ttjcoA($select, $table1, $table2, $join, null, $condition, $order, $limit);
            echo    '<ul class="list-group">';
            if (count($query) > 0) {
                foreach ($query as $row) {
                    $name       = ucwords(strtolower($row['name']));
                    $emp_id     = $row['emp_id'];
                    $record_no  = $row['record_no'];
                    $status     = $row['current_status'];
                    echo '<a href="javascript:;" class="list-group-item" onclick="getSupDetails(\'' . $name . '\',\'' . $emp_id . '\', \'' . $input['process'] . '\')">'
                        . $name .
                        '</a>';
                }
            } else {
                echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
            }
            echo    '</ul>';
        } else if ($input['process'] == 'userAccount') {
            $condition = "current_status = 'Active' AND hr_location = 'asc' AND (emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
            $query = $this->promo_model->searchName($input['str'], $condition);

            echo    '<ul class="list-group">';
            if (count($query) > 0) {
                foreach ($query as $row) {
                    $name       = ucwords(strtolower($row['name']));
                    $emp_id     = $row['emp_id'];
                    $record_no  = $row['record_no'];
                    $condition  = array('emp_id' => $emp_id, 'usertype' => 'employee');
                    $check      = $this->promo_model->selectAll_tcR('users', $condition);
                    $exist      = false;
                    if (count($check) > 0) {
                        $exist = true;
                    }

                    echo '<a href="javascript:;" class="list-group-item" onclick="getPromoDetails(\'' . $name . '\',\'' . $emp_id . '\', \'' . $input['process'] . '\',\'' . $exist . '\')">'
                        . $name .
                        '</a>';
                }
            } else {
                echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
            }
            echo    '</ul>';
        } else if ($input['process'] == 'inchargeAccount') {
            $select     = '*';
            $table1     = 'users';
            $table2     = 'employee3';
            $join       = 't1.emp_id=t2.emp_id';
            $condition  = "(usertype = 'administrator' OR usertype = 'placement1' OR usertype = 'placement2' OR usertype = 'placement3' OR usertype = 'placement4' OR usertype = 'nesco')
                          AND (name like '%$input[str]%' or t2.emp_id = '$input[str]')";
            $order      = 't2.name|ASC';
            $limit      = '10';
            $query      = $this->promo_model->join_ttjcoA($select, $table1, $table2, $join, null, $condition, $order, $limit);
            echo    '<ul class="list-group">';
            if (count($query) > 0) {
                foreach ($query as $row) {
                    $name       = ucwords(strtolower($row['name']));
                    $emp_id     = $row['emp_id'];
                    $exist      = false;
                    $check      = $this->promo_model->selectAll_tcR('promo_user', array('emp_id' => $emp_id));
                    if (count($check) > 0) {
                        $exist  = true;
                    }
                    echo '<a href="javascript:;" class="list-group-item" onclick="getPromoDetails(\'' . $name . '\',\'' . $emp_id . '\', \'' . $input['process'] . '\',\'' . $exist . '\')">'
                        . $name .
                        '</a>';
                }
            } else {
                echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
            }
            echo    '</ul>';
        } else if ($input['process'] == 'setupSubordinate') {
            $select     = '*';
            $table1     = 'users';
            $table2     = 'employee3';
            $join       = 't1.emp_id=t2.emp_id';
            $condition  = "t1.usertype = 'supervisor' AND (name like '%$input[str]%' or t2.emp_id = '$input[str]')";
            $order      = 't2.name|ASC';
            $limit      = '10';
            $query      = $this->promo_model->join_ttjcoA($select, $table1, $table2, $join, null, $condition, $order, $limit);
            echo    '<ul class="list-group">';
            if (count($query) > 0) {
                foreach ($query as $row) {
                    $name       = ucwords(strtolower($row['name']));
                    $emp_id     = $row['emp_id'];
                    $record_no  = $row['record_no'];
                    $status     = $row['current_status'];
                    echo '<a href="javascript:;" class="list-group-item" onclick="getSupDetails(\'' . $name . '\',\'' . $emp_id . '\', \'' . $input['process'] . '\')">'
                        . $name .
                        '</a>';
                }
            } else {
                echo '<a href="javascript:;" class="list-group-item">No Results Found!</a>';
            }
            echo    '</ul>';
        }
    }

    public function getName_clearance()
    {
        $input          = $this->input->post(NULL, TRUE);
        $bUs            = $this->promo_model->locate_promo_bu('asc');
        $row            = $this->promo_model->selectAll_tcR('employee3', array('emp_id' => $input['emp_id']));
        $nameDetails    = $this->promo_model->promoDetails_wttR(array('e.emp_id' => $input['emp_id']), 'employee3', 'promo_record');
        $current_store  = [];
        foreach ($bUs as $bu) {
            $hasBu = $this->promo_model->empStores('promo_record', $input['emp_id'], $row['record_no'], $bu['bunit_field']);
            if ($hasBu > 0) {
                $current_store[] = $bu['bunit_name'];
            }
        }

        if ($input['process'] == 'reprint') {

            $data   = '<option value="">Select Store</option>';
            $query  = $this->promo_model->selectAll_tcR('secure_clearance_promo', array('emp_id' => $input['emp_id'], 'status' => 'Pending'));
            $store  = $this->promo_model->selectAll_tcA('secure_clearance_promo_details', array('scpr_id' => $query['scpr_id']));
            foreach ($store as $value) {
                $data .= '<option value="' . $value['scdetails_id'] . '">' . $value['store'] . '</option>';
            }

            echo json_encode([
                'message'       => 'success',
                'reason'        => $query['reason'],
                'promo_type'    => $query['promo_type'],
                'stores'        => $data
            ]);
        } else if ($input['process'] == 'secure') {

            $query  = $this->promo_model->selectAll_tcR('secure_clearance_promo', array('emp_id' => $input['emp_id'], 'status' => 'Pending'));
            if (count($query) > 0) {

                $data           = '<option value="">Select Store</option>';
                $clearanDetails = $this->promo_model->selectAll_tcA('secure_clearance_promo_details', array('scpr_id' => $query['scpr_id']));
                foreach ($current_store as $value) {
                    if (in_array($value, array_column($clearanDetails, 'store'))) {
                        $data .= '<option value="" disabled>' . $value . ' - SECURED</option>';
                    } else {
                        $data .= '<option value="' . $value . '">' . $value . '</option>';
                    }
                }

                $missing_values = array_diff($current_store, array_column($clearanDetails, 'store'));
                $secured = (empty($missing_values)) ? true : false;

                echo json_encode([
                    'message'       => 'success',
                    'secured'       => true,
                    'reason'        => $query['reason'],
                    'promo_type'    => $query['promo_type'],
                    'stores'        => $data,
                    'chk'           => $secured,
                    'eocdate'       => $nameDetails['eocdate'],
                ]);
            } else {

                $data = '<option value="">Select Store</option>';
                foreach ($current_store as $value) {
                    if ($nameDetails['promo_type'] == 'STATION') {
                        $data .= '<option value="' . $value . '" selected>' . $value . '</option>';
                    } else {
                        $data .= '<option value="' . $value . '">' . $value . '</option>';
                    }
                }
                echo json_encode([
                    'message'       => 'success',
                    'secured'       => false,
                    'promo_type'    => $nameDetails['promo_type'],
                    'stores'        => $data,
                    'eocdate'       => $nameDetails['eocdate'],
                ]);
            }
        } else {

            $query  = $this->promo_model->selectAll_tcR('secure_clearance_promo', array('emp_id' => $input['emp_id'], 'status' => 'Pending'));
            if (count($query) > 0) {

                $data           = '<option value="">Select Store</option>';
                $clearanDetails = $this->promo_model->selectAll_tcA('secure_clearance_promo_details', array('scpr_id' => $query['scpr_id']));
                foreach ($clearanDetails as $value) {

                    if ($value['clearance_status'] == 'Pending') {

                        $data .= '<option value="' . $value['store'] . '">' . $value['store'] . '</option>';
                    } else {

                        $data .= '<option value="" disabled>' . $value['store'] . ' - UPLOAD COMPLETED</option>';
                    }
                }
                foreach ($current_store as $val) {
                    if (!in_array($val, array_column($clearanDetails, 'store'))) {
                        $data .= '<option value="" disabled>' . $val . ' - NOT SECURED</option>';
                    }
                }

                $stat = ($query['reason'] == 'Termination') ? 'End of Contract (Cleared)' : $query['reason'] . ' (Cleared)';
                echo json_encode([
                    'message'       => 'success',
                    'promo_type'    => $query['promo_type'],
                    'stores'        => $data,
                    'stat'          => $stat,
                    'scpr_id'       => $query['scpr_id'],
                ]);
            }
        }
    }

    public function reprintClearance()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $data = array(
            'sc_id'       => $input['scdetails_id'],
            'reason'      => $input['reasonReprint'],
            'date'        => date('Y-m-d H:i:s'),
            'generatedby' => $this->systemUser,
        );
        $this->promo_model->insert_tdA('secure_clearance_reprint', $data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {

            echo json_encode(['message' => 'failed']);
        } else {
            $data = $input['reason'] . '*' . $input['emp_id'] . '*' . $input['scdetails_id'];

            echo json_encode(['message' => 'success', 'data' => $data]);
        }
    }

    public function secureClearance()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $query  = $this->promo_model->selectAll_tcR('secure_clearance_promo', array('emp_id' => $input['emp_id'], 'status' => 'Pending'));
        $input['pending']   = (count($query) > 0) ? true : false;
        $input['scpr_id']   = $query['scpr_id'];
        $resignation_path   = '';
        if (isset($_FILES['resignLetter']['name'])) {

            $image_name         = addslashes($_FILES['resignLetter']['name']);
            $array              = explode(".", $image_name);
            $filename           = $input['emp_id'] . "=" . date('Y-m-d') . "=" . 'Resignation-Letter' . "=" . date('H-i-s-A') . "." . end($array);
            $filename           = str_replace(' ', '', $filename);
            $resignation_path   = "../hrms/document/resignation/" . $filename; //temp
            if (move_uploaded_file($_FILES['resignLetter']['tmp_name'], $resignation_path)) {
                $resignation_path           = "../document/resignation/" . $filename; //temp
                $input['resignation_path']  = $resignation_path;
            }
        }

        $authorization_path = '';
        if (isset($_FILES['authLetter']['name'])) {

            $image_name         = addslashes($_FILES['authLetter']['name']);
            $array              = explode(".", $image_name);
            $filename           = $input['emp_id'] . "=" . date('Y-m-d') . "=" . 'Authorization-Letter' . "=" . date('H-i-s-A') . "." . end($array);
            $filename           = str_replace(' ', '', $filename);
            $authorization_path = "../hrms/document/authorizationletter/" . $filename; //temp
            if (move_uploaded_file($_FILES['authLetter']['tmp_name'], $authorization_path)) {
                $authorization_path             = "../document/authorizationletter/" . $filename; //temp
                $input['authorization_path']    = $authorization_path;
            }
        }

        $scdetails_id = $this->promo_model->secureClearance($input);

        if ($input['reason'] != 'Deceased' && $input['reason'] != 'Termination') {

            $supervisor = $this->promo_model->selectAll_tcA('leveling_subordinates', array('subordinates_rater' => $input['emp_id']));
            foreach ($supervisor as $row) {

                $column = array('ratee_id' => $input['emp_id'], 'rater_id' =>  $row['ratee'], 'tag_stat' => 'Pending');
                $result = $this->promo_model->selectAll_tcR('tag_for_resignation', $column);
                if (count($result) == 0) {
                    $data = array(
                        'ratee_id'   => $input['emp_id'],
                        'rater_id'   => $row['ratee'],
                        'added_by'   => $this->systemUser,
                        'date_added' => $this->today,
                        'tag_stat'   => 'Pending',
                    );
                    $this->promo_model->insert_tdA('tag_for_resignation', $data);
                }
            }

            $insert = array(
                'emp_id'                => $input['emp_id'],
                'date'                  => $input['resignDate'],
                'remarks'               => 'Secured Clearance for ' . $input['stores'],
                'resignation_letter'    => $resignation_path,
                'added_by'              => $this->systemUser,
                'date_updated'          => $this->today,
            );
            $this->promo_model->insert_tdA('termination', $insert);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {

            echo json_encode(['message' => 'failed']);
        } else {
            $data = $input['reason'] . '*' . $input['emp_id'] . '*' . $scdetails_id;


            echo json_encode(['message' => 'success', 'data' => $data]);
        }
    }

    public function browseEpas()
    {
        $input      = $this->input->post(NULL, TRUE);
        $condition  = array('emp_id' => $input['emp_id'], 'record_no' => $input['record_no'], 'store' => $input['stores']);
        $epas       = $this->promo_model->selectAll_tcR('appraisal_details', $condition);
        if (count($epas) > 0) {

            if ($epas['raterSO'] == 1 && $epas['rateeSO'] == 1) {
                echo json_encode(['message' => 'success', 'numrate' => $epas['numrate'], 'descrate' => $epas['descrate']]);
            } else {
                echo json_encode(['message' => 'failed', 'alert' => 'signoff', 'text' => 'Please signoff EPAS!']);
            }
        } else {
            echo json_encode(['message' => 'failed', 'alert' => 'noepas', 'text' => 'Please secure EPAS!']);
        }
    }

    public function uploadClearance()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $clearance_path   = '';
        if (isset($_FILES['clearance']['name'])) {

            $image_name         = addslashes($_FILES['clearance']['name']);
            $array              = explode(".", $image_name);
            $filename           = $input['emp_id'] . "=" . date('Y-m-d') . "=" . 'Clearance' . "=" . date('H-i-s-A') . "." . end($array);
            $clearance_path     = "../hrms/document/clearance/" . $filename; //temp
            move_uploaded_file($_FILES['clearance']['tmp_name'], $clearance_path);
        }
        $clearance_path           = "../document/clearance/" . $filename; //temp
        $input['clearance_path']  = $clearance_path;

        $this->promo_model->uploadClearance($input);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {

            echo json_encode(['message' => 'failed']);
        } else {

            echo json_encode(['message' => 'success']);
        }
    }
}
