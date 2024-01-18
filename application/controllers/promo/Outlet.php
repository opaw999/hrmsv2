<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Outlet extends CI_Controller
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

    public function changeOutletHistory()
    {
        $select = '*';
        $table1 = 'change_outlet_record';
        $table2 = 'promo_record';
        $join   = 't1.emp_id=t2.emp_id';
        $order  = 'change_no|DESC';
        $list   = $this->promo_model->join_ttjcoA($select, $table1, $table2, $join, null, null, $order, null);
        $data   = [];
        foreach ($list as $row) {
            $name       = $this->promo_model->getName_employee3($row['emp_id']);
            $column     = [];
            $column[]   = '<a href="' . base_url('promo/page/promo/profile/' . $row['emp_id']) . '" target="_blank">' . $row['emp_id'] . '</a>';
            $column[]   = $name;
            $column[]   = date('m/d/Y', strtotime($row['effectiveon']));
            $column[]   = $row['changefrom'];
            $column[]   = $row['changeto'];
            $data[]     = $column;
        }
        echo json_encode(array('data' => $data));
    }

    public function changeOutletForm()
    {
        $input          = $this->input->post(NULL, TRUE);
        $condition      = array('e.emp_id' => $input['emp_id']);
        $data['row']    = $this->promo_model->promoDetails_wttR($condition, 'employee3', 'promo_record');

        $data['request']    = $input['process'];
        $this->load->view('promo/page/page_response', $data);
    }

    public function outletClearance()
    {
        $input  = $this->input->post(NULL, TRUE);
        $emp3   = $this->promo_model->selectAll_tcR('employee3', array('emp_id' => $input['emp_id']));
        echo    '<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 row-cols-xl-3">
                <input type="hidden" name="emp_id" value="' .  $input['emp_id'] . '">
                <input type="hidden" name="record_no" value="' . $input['record_no'] . '">
                <input type="hidden" name="process" value="' . $input['process'] . '">
                <input type="hidden" name="previous_stores" value="' . $input['previous_stores'] . '">
                <input type="hidden" name="startdate" value="' . $input['startdate'] . '">
                <input type="hidden" name="eocdate" value="' . $emp3['eocdate'] . '">
                <input type="hidden" name="duration" value="' . $emp3['duration'] . '">';
        foreach ($input['stores'] as $value) {
            $split      = explode('|', $value);
            $condition  = array('bunit_id' => $split[0]);
            $bu         = $this->promo_model->selectAll_tcR('locate_promo_business_unit', $condition);
            echo    '<div class="col mx-auto">
                        <div class="card radius-10 border shadow-none">
                            <div class="card-body">
                                <h5 class="card-title text-primary">' . $bu['bunit_name'] . '</h5>
                                <hr>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="file" name="' . $bu['bunit_clearance'] . '" class="form-control form-control-sm" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>';
        }
        echo    '</div>';
    }

    public function changeOutlet()
    {
        $input = $this->input->post(NULL, TRUE);

        $this->db->trans_start();

        if ($input['process'] == 'removeOutlet' || $input['process'] == 'transferOutlet') {
            $this->outletClearanceUpload($input);
        }

        $this->promo_model->changeOutlet($input);

        if ($input['process'] == 'addOutlet') {
            $this->outletIntroUpload($input);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {

            echo json_encode(['message' => 'failed']);
        } else {

            echo json_encode(['message' => 'success']);
        }
    }

    public function outletIntroUpload($input)
    {
        foreach ($input['stores'] as $value) {

            $split      = explode('|', $value);
            $bU         = $this->promo_model->selectAll_tcR('locate_promo_business_unit', array('bunit_id' => $split[0]));
            $file_path  = '';
            if (isset($_FILES[$bU['bunit_intro']]['name'])) {

                $image_name = addslashes($_FILES[$bU['bunit_intro']]['name']);
                $array      = explode(".", $image_name);
                $filename   = $input['emp_id'] . "=" . date('Y-m-d') . "=" . $bU['bunit_intro'] . "=" . date('H-i-s-A') . "." . end($array);
                $file_path  = '../hrms/document/final_requirements/others/' . $filename; //temp

                if (move_uploaded_file($_FILES[$bU['bunit_intro']]['tmp_name'], $file_path)) {
                    $intro_path = '../document/final_requirements/others/' . $filename; //temp
                    $where      = array('emp_id' => $input['emp_id']);
                    $data       = array($bU['bunit_intro'] => $intro_path);
                    $this->promo_model->update_twdA('promo_record', $where, $data);
                }
            }
        }
    }

    public function outletClearanceUpload($input)
    {
        $condition  =  array('emp_id' => $input['emp_id'], 'status' => 'Pending');
        $clearance  = $this->promo_model->selectAll_tcR('secure_clearance_promo',  $condition);
        $stores     = [];

        foreach ($input['stores'] as $value) {
            $stores[]   = $value;
            $split      = explode('|', $value);
            $bU         = $this->promo_model->selectAll_tcR('locate_promo_business_unit', array('bunit_id' => $split[0]));
            $file_path  = '';
            if (isset($_FILES[$bU['bunit_clearance']]['name'])) {

                $image_name = addslashes($_FILES[$bU['bunit_clearance']]['name']);
                $array      = explode(".", $image_name);
                $filename   = $input['emp_id'] . "=" . date('Y-m-d') . "=" . $bU['bunit_clearance'] . "=" . date('H-i-s-A') . "." . end($array);
                $file_path  = '../hrms/document/clearance/' . $filename; //temp

                if (move_uploaded_file($_FILES[$bU['bunit_clearance']]['tmp_name'], $file_path)) {
                    $input['clearance_path']    = '../document/clearance/' . $filename; //temp
                    $input['stores']            = $bU['bunit_name'];
                    $input['record_no']         = $input['record_no'];
                    $input['scpr_id']           = $clearance['scpr_id'];
                    $where                      = array('emp_id' => $input['emp_id']);
                    $data                       = array($bU['bunit_clearance'] => $input['clearance_path']);
                    $this->promo_model->uploadClearance($input);
                    $this->promo_model->update_twdA('promo_record', $where, $data);
                }
            }
        }
    }
}
