<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contract extends CI_Controller
{
    public $today;
    public $adminUser;
    public $systemUser;
    function __construct()
    {
        parent::__construct();

        $empId              = $this->session->userdata('emp_id');
        $this->today        = date('Y-m-d');
        $this->adminUser    = array('06359-2013', '01186-2023');
        $this->systemUser   = $this->session->userdata('emp_id');
        if (!isset($empId)) {

            redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrmsv2/employee');
        }
    }

    public function eocList()
    {
        $order  = array('name', 'startdate', 'eocdate', null, null);
        $list   = $this->promo_model->make_datatables('eocList', $order, $order[0]);
        $data   = [];
        foreach ($list as $row) {
            $name       = '<a href="' . base_url('promo/page/promo/profile/' . $row['emp_id']) . '" target="_blank">' . ucwords(strtolower($row['name'])) . '</a>';
            $condition  = array('appraisal_status' => 'active', 'status' => 'active', 'hrd_location' => 'asc');
            $bUs        = $this->promo_model->selectAll_tcA('locate_promo_business_unit', $condition);
            $storeCol   = '';
            $action     = '';
            $actionCol  = true;
            $i          = 0;
            foreach ($bUs as $bU) {
                $i++;
                $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bU['bunit_field']);
                if ($hasBu > 0) {
                    $appData = array('emp_id' => $row['emp_id'], 'record_no' => $row['record_no'], 'store' => $bU['bunit_name'],);
                    $app = $this->promo_model->selectAll_tcR('appraisal_details', $appData);
                    if (count($app) > 0) {

                        if ($app['numrate'] >= 85) {
                            $appChk = ($app['raterSO'] == 1 && $app['rateeSO'] == 1) ? 'success' : 'warning';
                            if ($app['raterSO'] == 0 || $app['rateeSO'] == 0) {
                                $actionCol = false;
                            }
                        } else {
                            $appChk = 'danger';
                            $actionCol = false;
                        }
                        $strNumrate =   '<a href="javascript:;" id="' . $app['details_id'] . '" onclick="viewAppraisal(this.id)">
                                            <span class="badge bg-' . $appChk . '">' . $bU['bunit_acronym'] . ' | ' . $app['numrate'] . '</span>
                                        </a>';
                    } else {
                        $actionCol = false;
                        $strNumrate = '<span class="badge bg-secondary">' . $bU['bunit_acronym'] . ' | none</span>';
                    }

                    $storeCol = ($i == 1) ? $strNumrate : $storeCol . ' ' . $strNumrate;
                }
            }
            if ($actionCol) {
                $action = '<a href="javascript:;" id="' . $row['emp_id'] . '" onclick="proceed(this.id)"><span class="badge bg-primary">Renew</span></a>';
            }
            $column     = [];
            $column[]   = $name;
            $column[]   = date('m/d/Y', strtotime($row['startdate']));
            $column[]   = date('m/d/Y', strtotime($row['eocdate']));
            $column[]   = $storeCol;
            $column[]   = $action;
            $data[]     = $column;
        }
        $output = array(
            'draw'              => intval($_POST['draw']),
            'recordsTotal'      => $this->promo_model->get_all_data('eocList'),
            'recordsFiltered'   => $this->promo_model->get_filtered_data('eocList', $order, $order[0]),
            'data'              => $data
        );
        echo json_encode($output);
    }

    public function proceed()
    {
        $input              = $this->input->post(NULL, TRUE);
        $row                = $this->promo_model->selectAll_tcR('employee3', array('emp_id' => $input['emp_id']));
        $data['record_no']  = $row['record_no'];
        $data['emp_id']     = $input['emp_id'];
        $data['request']    = 'uploadClearance';
        $this->load->view('promo/page/page_response', $data);
    }

    public function save_uploadClearance()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();
        $row        = $this->promo_model->selectAll_tcR('employee3', array('emp_id' => $input['emp_id']));
        $data       =  array('emp_id' => $input['emp_id'], 'status' => 'Pending');
        $clearance  = $this->promo_model->selectAll_tcR('secure_clearance_promo',  $data);
        $bUs        = $this->promo_model->locate_promo_bu('asc');
        foreach ($bUs as $bU) {
            $hasBu = $this->promo_model->empStores('promo_record', $input['emp_id'], $row['record_no'], $bU['bunit_field']);
            if ($hasBu > 0) {
                $file_path   = '';
                if (isset($_FILES[$bU['bunit_clearance']]['name'])) {

                    $image_name = addslashes($_FILES[$bU['bunit_clearance']]['name']);
                    $array      = explode(".", $image_name);
                    $filename   = $input['emp_id'] . "=" . date('Y-m-d') . "=" . $bU['bunit_clearance'] . "=" . date('H-i-s-A') . "." . end($array);
                    $file_path  = '../hrms/document/clearance/' . $filename; //temp

                    if (move_uploaded_file($_FILES[$bU['bunit_clearance']]['tmp_name'], $file_path)) {
                        $input['clearance_path']    = '../document/clearance/' . $filename; //temp
                        $input['stores']            = $bU['bunit_name'];
                        $input['record_no']         = $row['record_no'];
                        $input['scpr_id']           = $clearance['scpr_id'];
                        $where                      = array('emp_id' => $input['emp_id']);
                        $data                       = array($bU['bunit_clearance'] => $input['clearance_path']);
                        $this->promo_model->uploadClearance($input);
                        $this->promo_model->update_twdA('promo_record', $where, $data);
                    }
                }
            }
        }
        $this->session->set_userdata('renewID', $input['emp_id']);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {

            echo json_encode(['message' => 'failed']);
        } else {

            echo json_encode(['message' => 'success', 'data' => $input['emp_id']]);
        }
    }

    public function renewContract()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $intros = [];
        foreach ($input['stores'] as $value) {
            $split = explode('|', $value);
            $bU = $this->promo_model->selectAll_tcR('locate_promo_business_unit', array('bunit_id' => $split[0]));
            $file_path   = '';
            if (isset($_FILES[$bU['bunit_intro']]['name'])) {

                $image_name = addslashes($_FILES[$bU['bunit_intro']]['name']);
                $array      = explode(".", $image_name);
                $filename   = $input['emp_id'] . "=" . date('Y-m-d') . "=" . $bU['bunit_intro'] . "=" . date('H-i-s-A') . "." . end($array);
                $file_path  = '../hrms/document/final_requirements/others/' . $filename; //temp

                if (move_uploaded_file($_FILES[$bU['bunit_intro']]['tmp_name'], $file_path)) {
                    $path = '../document/final_requirements/others/' . $filename; //temp
                    $data = array(
                        'app_id'                => $input['emp_id'],
                        'requirement_name'      => 'intro',
                        'filename'              => $path,
                        'date_time'             => $this->today,
                        'requirement_status'    => 'passed',
                        'receiving_staff'       => $this->systemUser,
                    );
                    $this->promo_model->insert_tdA('application_otherreq', $data);
                    $intros[$bU['bunit_intro']] = $path;
                }
            }
        }
        $input['intros'] = $intros;

        $record_no = $this->promo_model->renewContract($input);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {

            echo json_encode(['message' => 'failed']);
        } else {
            $this->session->set_userdata('printID', $input['emp_id']);
            echo json_encode(['message' => 'success', 'emp_id' => $input['emp_id'], 'record_no' => $record_no, 'contract' => 'current']);
        }
    }

    public function setSession()
    {
        $input = $this->input->post(NULL, TRUE);
        if (isset($input['emp_id'])) {
            $this->session->set_userdata('renewID', $input['emp_id']);
        }

        if (isset($input['printID'])) {
            $this->session->set_userdata('printID', $input['printID']);
        }

        if (isset($input['unset'])) {
            $this->session->unset_userdata($input['unset']);
        }
    }

    public function generatePermitForm()
    {
        $input              = $this->input->post(NULL, TRUE);
        $table1             = ($input['contract'] == 'current') ? 'employee3' : 'employmentrecord_';
        $table2             = ($input['contract'] == 'current') ? 'promo_record' : 'promo_history_record';
        $where              = array('e.emp_id' => $input['emp_id'], 'e.record_no' => $input['record_no']);
        $data['name']       = $this->promo_model->getName_employee3($input['emp_id']);
        $data['row']        = $this->promo_model->promoDetails_wttR($where, $table1, $table2);
        $data['contract']   = $input['contract'];
        $data['table1']     = $table1;
        $data['table2']     = $table2;
        $data['request']    = 'generatePermitForm';

        $this->load->view('promo/page/page_response', $data);
    }

    public function generateContractForm()
    {
        $input              = $this->input->post(NULL, TRUE);
        $data['row']        = $this->promo_model->promoDetails_wttR(array('e.emp_id' => $input['emp_id']), 'employee3', 'promo_record');
        $data['sss_ctc']    = $this->promo_model->selectAll_tcR('applicant_otherdetails', array('app_id' => $input['emp_id']));
        $data['request']    = 'generateContractForm';

        $this->load->view('promo/page/page_response', $data);
    }

    public function savePermit()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();
        $table  = ($input['contract'] === 'current') ? 'promo_record' : 'promo_history_record';
        $store  = explode('|', $input['store']);
        $getCO  = $this->promo_model->selectAll_tcR_tk('promo_schedule', array('statCut' => $input['cutOff']));
        $endFC  = ($getCO['endFC'] != '') ? $getCO['endFC'] : 'last';
        $cutOff = $getCO['startFC'] . '-' . $endFC . ' / ' . $getCO['startSC'] . '-' . $getCO['endSC'];

        $dSched = $this->promo_model->selectAll_tcR_tk('shiftcodes', array('shiftcode' => $input['dutySched']));
        $In1    = $dSched['1stIn'];
        $Out1   = $dSched['1stOut'];
        $In2    = $dSched['2ndIn'];
        $Out2   = $dSched['2ndOut'];
        $promoSched = '';
        if (count($dSched) > 0) {
            if ($In2 == '') {
                $promoSched = "$In1-$Out1";
            } else {
                $promoSched = "$In1-$Out1, $In2-$Out2";
            }
        }

        $sSched = $this->promo_model->selectAll_tcR_tk('shiftcodes', array('shiftcode' => $input['specialSched']));
        $In1    = $sSched['1stIn'];
        $Out1   = $sSched['1stOut'];
        $In2    = $sSched['2ndIn'];
        $Out2   = $sSched['2ndOut'];
        $specialSched = '';
        if (count($sSched) > 0) {
            if ($In2 == '') {
                $specialSched = "$In1-$Out1";
            } else {
                $specialSched = "$In1-$Out1, $In2-$Out2";
            }
        }

        $where  = array('emp_id' => $input['emp_id'], 'record_no' => $input['record_no'],);
        $data   = array(
            $store[1] => $promoSched,
            $store[2] => $input['dutyDays'],
            $store[3] => $specialSched,
            $store[4] => $input['specialDays'],
            'dayoff'  => $input['dayOff'],
            'cutoff'  => $cutOff,
        );
        $this->promo_model->update_twdA($table, $where, $data);

        $c      = ($input['contract'] === 'current') ? '1' : '0';
        $link   = $input['emp_id'] . '_' . $input['record_no'] . '_' . $store[0] . '_' . $c;

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success', 'data' => $link]);
        }
    }

    public function saveContract()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        foreach ($input['sss_ctc'] as $value) {
            if ($value == 'sss') {
                $data1 = array(
                    'witness1'              => $input['w1'],
                    'witness2'              => $input['w2'],
                    'contract_header_no'    => $input['contract_header_no'],
                    'sss_ctc'               => 'SSS',
                    'sssno_ctcno'           => $input['sss'],
                    'issuedat'              => $input['issuedat'],
                );
                $data2 = array('sss_no' => $input['sss']);
            } else {
                $data1 = array(
                    'witness1'              => $input['w1'],
                    'witness2'              => $input['w2'],
                    'contract_header_no'    => $input['contract_header_no'],
                    'sss_ctc'               => 'Cedula',
                    'sssno_ctcno'           => $input['ctc'],
                    'issuedat'              => $input['issuedat'],
                    'issuedon'              => $input['issuedon'],
                );
                $data2 = array(
                    'cedula_no'             => $input['ctc'],
                    'cedula_date'           => $input['issuedon'],
                    'cedula_place'          => $input['issuedat'],
                );
            }
            $chk = $this->promo_model->selectAll_tcR('employment_witness', array('rec_no' => $input['record_no'], 'emp_id' => $input['emp_id']));
            if (count($chk) > 0) {
                $condition  = array('rec_no' => $input['record_no'], 'emp_id' => $input['emp_id']);
                $this->promo_model->update_twdA('employment_witness', $condition, $data1);
            } else {
                $additionalData = array(
                    'rec_no'            => $input['record_no'],
                    'emp_id'            => $input['emp_id'],
                    'date_generated'    => $this->today,
                    'generated_by'      => $this->systemUser
                );
                $data1 = array_merge($data1, $additionalData);
                $this->promo_model->insert_tdA('employment_witness', $data1);
            }

            $this->promo_model->update_twdA('applicant_otherdetails', array('app_id' => $input['emp_id']), $data2);
        }

        $c      = ($input['contract'] === 'current') ? '1' : '0';
        $link   = $input['emp_id'] . '_' . $input['record_no'] . '_' . $input['contract_header_no'] . '_' . $c;
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success', 'data' => $link]);
        }
    }

    public function transferRateForm()
    {
        $input          = $this->input->post(NULL, TRUE);
        $condition      = array('e.emp_id' => $input['emp_id']);
        $query          = $this->promo_model->promoDetails_wttR($condition, 'employee3', 'promo_record');
        $data['row']    = $this->promo_model->promoDetails_wttA($condition, 'employmentrecord_', 'promo_history_record');
        $data['emp_id']         = $input['emp_id'];
        $data['promo_type']     = $query['promo_type'];


        $data['request']    = 'transferRateForm';
        $this->load->view('promo/page/page_response', $data);
    }

    public function checkStores()
    {
        $input      = $this->input->post(NULL, TRUE);
        $condition  = array('e.emp_id' => $input['emp_id']);
        $query      = $this->promo_model->promoDetails_wttR($condition, 'employee3', 'promo_record');
        $bUs        = $this->promo_model->locate_promo_bu('asc');
        $stores     = [];
        $exist      = [];
        foreach ($bUs as $bu) {
            $hasBu = $this->promo_model->empStores('promo_record', $query['emp_id'], $query['record_no'], $bu['bunit_field']);
            if ($hasBu > 0) {
                $stores[] = $bu['bunit_name'];
            }
        }
        if (isset($input['storeCheck'])) {
            foreach ($input['storeCheck'] as $details_id) {
                $appraisal = $this->promo_model->selectAll_tcR('appraisal_details', array('details_id' => $details_id));
                if (count($appraisal) > 0) {

                    if (in_array($appraisal['store'], $stores)) {
                        $exist[] =  $details_id;
                    }
                }
            }
        }
        echo json_encode(['message' => 'success', 'data' => $exist]);
    }

    public function transferRateSave()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $current = $this->promo_model->selectAll_tcR('employee3', array('emp_id' => $input['emp_id']));
        foreach ($input['stores'] as $details_id) {

            $where      = array('details_id' => $details_id);
            $appraisal  = $this->promo_model->selectAll_tcR('appraisal_details', $where);
            $field      = $this->promo_model->selectAll_tcR('locate_promo_business_unit', array('bunit_name' => $appraisal['store']));

            $this->promo_model->update_twdA(
                'promo_history_record',
                array('emp_id' => $input['emp_id'], 'record_no' => $appraisal['record_no']),
                array($field['bunit_epascode'] => '')
            );

            $this->promo_model->update_twdA(
                'promo_record',
                array('emp_id' => $input['emp_id'], 'record_no' => $current['record_no']),
                array($field['bunit_epascode'] => '1')
            );

            $data = array('record_no' => $current['record_no']);
            $this->promo_model->update_twdA('appraisal_details', $where, $data);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success']);
        }
    }
}
