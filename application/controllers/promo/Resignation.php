<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Resignation extends CI_Controller
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

    public function resignationList()
    {
        $select     = '*, t1.remarks,t1.date_updated,t1.added_by';
        $table1     = 'termination';
        $table2     = 'employee3';
        $join       = 't1.emp_id=t2.emp_id';
        $condition  = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
        $order      = 'date|DESC';
        $list       = $this->promo_model->join_ttjcoA($select, $table1, $table2, $join, null, $condition, $order, null);
        $data       = [];
        foreach ($list as $row) {
            $name       = '<a class="text-truncate" href="' . base_url('promo/page/promo/profile/' . $row['emp_id']) . '" target="_blank">' . ucwords(strtolower($row['name'])) . '</a>';
            $addedBy    = $this->promo_model->getName_employee3($row['added_by']);
            if (!empty($row['resignation_letter'])) {
                $button = '<a href="javascript:;" id="' . $row['resignation_letter'] . '" onclick="view_letter(this.id)">
                            <i class="fadeIn animated bx bx-show-alt font-22" title="view"></i>
                          </a>';
            } else {
                $button = '<a href="javascript:;" id="' . $row['termination_no'] . '" onclick="uploadLetter(this.id,\'' . $row['emp_id'] . '\')">
                            <i class="fadeIn animated bx bx-upload font-22 text-secondary" title="upload"></i>
                          </a>';
            }
            $column     = [];
            $column[]   = $name;
            $column[]   = date('m/d/Y', strtotime($row['date']));
            $column[]   = ucwords(strtolower($addedBy));
            $column[]   = date('m/d/Y', strtotime($row['date_updated']));
            $column[]   = $row['remarks'];
            $column[]   = $button;
            $data[]     = $column;
        }

        echo json_encode(array('data' => $data));
    }

    public function uploadLetter()
    {
        $input              = $this->input->post(NULL, TRUE);
        $data['row']        = $this->promo_model->selectAll_tcR('termination', array('termination_no' => $input['termination_no']));
        $data['request']    = 'uploadLetter';

        $this->load->view('promo/page/page_response', $data);
    }

    public function save_uploadLetter()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $file_path   = '';
        if (isset($_FILES['resignation_letter']['name'])) {

            $image_name = addslashes($_FILES['resignation_letter']['name']);
            $array      = explode('.', $image_name);
            $filename   = $input['emp_id'] . '=' . date('Y-m-d') . '=' . 'Resignation-Letter' . '=' . date('H-i-s-A') . '.' . end($array);
            $file_path  = '../hrms/document/resignation/' . $filename; //temp
            if (move_uploaded_file($_FILES['resignation_letter']['tmp_name'], $file_path)) {
                $temp_path  = '../document/resignation/' . $filename; //temp
                $condition  = array('termination_no' => $input['termination_no']);
                $update     = array('resignation_letter' => $temp_path);
                $this->promo_model->update_twdA('termination', $condition, $update);
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {

            echo json_encode(['message' => 'failed']);
        } else {

            echo json_encode(['message' => 'success']);
        }
    }

    public function addResignationForm()
    {
        $input = $this->input->post(NULL, TRUE);
        $bUs = $this->promo_model->locate_promo_bu('asc');
        $row = $this->promo_model->selectAll_tcR('employee3', array('emp_id' => $input['emp_id']));
        echo '<div class="row">';
        foreach ($bUs as $bU) {
            $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bU['bunit_field']);
            if ($hasBu > 0) {
                echo    '<div class="col-sm-6 mt-0 mb-0 mx-auto">
                            <div class="card radius-10 border shadow-none">
                                <div class="card-body">
                                    <p class="card-title text-primary">Clearance: ' . $bU['bunit_name'] . '</p>
                                    <hr class="mt-0 mb-2">
                                    <input type="file" name="' . $bU['bunit_clearance'] . '" class="form-control form-control-sm" accept="image/*">
                                </div>
                            </div>
                        </div>';
            }
        }

        if ($input['status'] == 'Resigned') {
            echo    '<div class="col-sm-6 mt-0 mb-0 mx-auto">
                        <div class="card radius-10 border shadow-none">
                            <div class="card-body">
                                <p class="card-title text-primary">Resignation Letter</p>
                                <hr class="mt-0 mb-2">
                                <input type="file" name="resignation_letter" class="form-control form-control-sm" accept="image/*">
                            </div>
                        </div>
                    </div>';
        }
        echo '</div>';
    }

    public function save_addResignation()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $bUs = $this->promo_model->locate_promo_bu('asc');
        $row = $this->promo_model->selectAll_tcR('employee3', array('emp_id' => $input['emp_id']));

        foreach ($bUs as $bU) {
            $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bU['bunit_field']);
            if ($hasBu > 0) {
                $file_path   = '';
                if (isset($_FILES[$bU['bunit_clearance']]['name'])) {

                    $image_name = addslashes($_FILES[$bU['bunit_clearance']]['name']);
                    $array      = explode('.', $image_name);
                    $filename   = $input['emp_id'] . '=' . date('Y-m-d') . '=' . 'Clearance' . '=' . date('H-i-s-A') . '.' . end($array);
                    $file_path  = '../hrms/document/clearance/' . $filename; //temp
                    if (move_uploaded_file($_FILES[$bU['bunit_clearance']]['tmp_name'], $file_path)) {
                        $temp_path  = '../document/clearance/' . $filename; //temp
                        $condition  = array('emp_id' => $row['emp_id'], 'record_no' => $row['record_no']);
                        $update     = array($bU['bunit_clearance'] => $temp_path);
                        $this->promo_model->update_twdA('promo_record', $condition, $update);
                    }
                }
            }
        }

        $temp_path = '';
        if ($input['status'] == 'Resigned') {
            $file_path = '';
            if (isset($_FILES['resignation_letter']['name'])) {
                $image_name = addslashes($_FILES['resignation_letter']['name']);
                $array      = explode('.', $image_name);
                $filename   = $input['emp_id'] . '=' . date('Y-m-d') . '=' . 'Resignation-Letter' . '=' . date('H-i-s-A') . '.' . end($array);
                $file_path  = '../hrms/document/resignation/' . $filename; //temp
                if (move_uploaded_file($_FILES['resignation_letter']['tmp_name'], $file_path)) {
                    $temp_path  = '../document/resignation/' . $filename; //temp
                }
            }
        }

        $insert = array(
            'emp_id'                => $input['emp_id'],
            'date'                  => $input['date'],
            'remarks'               => $input['remarks'],
            'resignation_letter'    => $temp_path,
            'added_by'              => $this->systemUser,
            'date_updated'          => $this->today
        );
        $this->promo_model->insert_tdA('termination', $insert);

        $condition  = array('emp_id' => $row['emp_id'], 'record_no' => $row['record_no']);
        $update     = array('current_status' => $input['status'], 'remarks' => $input['remarks']);
        $this->promo_model->update_twdA('employee3', $condition, $update);

        $condition  = array('emp_id' => $row['emp_id']);
        $update     = array('user_status' => 'inactive');
        $this->promo_model->update_twdA('users', $condition, $update);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {

            echo json_encode(['message' => 'failed']);
        } else {

            echo json_encode(['message' => 'success']);
        }
    }

    public function tagResignationTable()
    {
        $input = $this->input->post(NULL, TRUE);
        $select     = '*,t1.record_no';
        $table1     = 'employee3';
        $table2     = 'leveling_subordinates';
        $join       = 't1.emp_id=t2.subordinates_rater';
        $condition  = "ratee = '$input[emp_id]' AND  current_status IN ('Active','End of Contract','V-Resigned') 
                      AND (emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
        $order      = 'name|ASC';
        $query      = $this->promo_model->join_ttjcoA($select, $table1, $table2, $join, null, $condition, $order, null);

        echo    '<div class="row border-start">
                    <table id="tagResignationTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>EmpID</th>
                                <th>Name</th>
                                <th>EmpType</th>
                                <th>Position</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>';

        foreach ($query as $row) {
            $condition  = array('emp_id' => $row['emp_id'], 'record_no' => $row['record_no']);
            $check      = $this->promo_model->selectAll_tcR('promo_record', $condition);
            if (count($check) > 0) {
                $profile    = '<a href="' . base_url('promo/page/promo/profile/' . $row['emp_id']) . '" target="_blank">' . $row['emp_id'] . '</a>';
                $condition  = array('rater_id' => $input['emp_id'], 'ratee_id' => $row['emp_id']);
                $tag_stat   = $this->promo_model->selectAll_tcR('tag_for_resignation', $condition)['tag_stat'];

                echo    '<tr>
                            <td>' . $profile . '</td>
                            <td>' . ucwords(strtolower($row['name'])) . '</td>
                            <td>' . $row['emp_type'] . '</td>
                            <td>' . $row['position'] . '</td>
                            <td>';
                if ($tag_stat == 'Pending') {
                    echo    '<a href="javascript:;" class="text-danger" onclick="tagResignation(\'untag\',\'' . $input['emp_id'] . '\', \'' . $row['emp_id'] . '\')">
                                <i class="fadeIn animated bx bx-x-circle font-22" data-toggle="tooltip" title="Untag for Resignation"></i>
                            </a>';
                } else if ($tag_stat == 'Done') {
                    echo    '<a href="javascript:;" class="text-success">
                                <i class="fadeIn animated bx bx-check-circle font-22" data-toggle="tooltip" title="Done"></i>
                            </a>';
                } else {
                    echo    '<a href="javascript:;" class="text-primary" onclick="tagResignation(\'tag\',\'' . $input['emp_id'] . '\', \'' . $row['emp_id'] . '\')">
                                <i class="fadeIn animated bx bx-purchase-tag-alt font-22" data-toggle="tooltip" title="Tag for Resignation"></i>
                            </a>';
                }
                echo        '</td>
                        </tr>';
            }
        }

        echo            '</tbody>
                    </table>
                </div>';
    }

    public function tagResignation()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        if ($input['tag_stat'] == 'tag') {
            $insert = array(
                'ratee_id'      => $input['ratee_id'],
                'rater_id'      => $input['rater_id'],
                'added_by'      => $this->systemUser,
                'date_added'    => $this->today,
                'tag_stat'      => 'Pending',
            );
            $this->promo_model->insert_tdA('tag_for_resignation', $insert);
        } else {

            $condition = array(
                'ratee_id'      => $input['ratee_id'],
                'rater_id'      => $input['rater_id'],
            );
            $this->promo_model->delete_tcr_row('tag_for_resignation', $condition);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {

            echo json_encode(['message' => 'failed']);
        } else {

            echo json_encode(['message' => 'success']);
        }
    }
}
