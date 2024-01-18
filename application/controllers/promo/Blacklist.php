<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Blacklist extends CI_Controller
{
    public $systemUser;
    public $today;
    function __construct()
    {
        parent::__construct();
        $this->db           = $this->load->database('default', TRUE);
        $this->systemUser   = $this->session->userdata('emp_id');
        $this->today        = date('Y-m-d');
        $empId = $this->session->userdata('emp_id');
        if (!isset($empId)) {

            redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrmsv2/employee');
        }
    }
    public function getBlacklist()
    {
        $order  = array('app_id', 'b.name', null, null, null, null);
        $list   = $this->promo_model->make_datatables('getBlacklist', $order, $order[1]);
        $data   = [];
        foreach ($list as $row) {

            $dateBL = ($row['date_blacklisted'] == '0000-00-00' || $row['date_blacklisted'] == null) ? '' : date('m/d/Y', strtotime($row['date_blacklisted']));
            $action =   '<a href="javascript:;" class="bl" title="edit Blacklist info" id="' . $row['blacklist_no'] . '" onclick="updateBl(this.id)">
                            <i class="bx bx-edit me-0" style="font-size: 22px;"></i>
                        </a>';
            $column     = [];
            $column[]   = $row['app_id'];
            $column[]   = $row['name'];
            $column[]   = $row['reportedby'];
            $column[]   = $dateBL;
            $column[]   = $row['reason'];
            $column[]   = $action;
            $data[]     = $column;
        }
        $output = array(
            'draw'              => intval($_POST['draw']),
            'recordsTotal'      => $this->promo_model->get_all_data('getBlacklist'),
            'recordsFiltered'   => $this->promo_model->get_filtered_data('getBlacklist', $order, $order[1]),
            'data'              => $data
        );
        echo json_encode($output);
    }

    public function update_blacklist()
    {
        $input              = $this->input->post(NULL, TRUE);
        $data['blacklist']  = $this->promo_model->selectAll_tcR('blacklist', array('blacklist_no' => $input['id']));
        $data['request']    = 'update_blacklist';

        $this->load->view('promo/page/page_response', $data);
    }

    public function save_bl_update()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $data = array(
            'reportedby'        => $input['reportedby'],
            'date_blacklisted'  => $input['date_blacklisted'],
            'bday'              => $input['bday'],
            'address'           => $input['address'],
            'reason'            => $input['reason'],
        );
        $this->promo_model->update_twdA('blacklist', array('blacklist_no' => $input['blacklist_no']), $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {

            echo json_encode(['message' => 'failed']);
        } else {

            echo json_encode(['message' => 'success']);
        }
    }

    public function addCheckBl()
    {
        $input  = $this->input->post(NULL, TRUE);
        $order  = array('lastname', null, null);
        $list   = $this->promo_model->make_datatables('checkBl', $order, $order[0], $input);
        $data   = [];
        foreach ($list as $row) {

            if ($row['name'] != '') {
                $name = ucwords(strtolower($row['name']));
            } else {
                $name = ucwords(strtolower($row['lastname'] . ', ' . $row['firstname']));
            }
            $app_id = '';
            if ($row['app_id'] != '') {
                $app_id = '<a href="' . base_url('promo/page/promo/profile/' . $row['app_id']) . '" target="_blank">' . $name . '</a>';
            } else {
                $app_id = $name;
            }
            $action = '';

            $check  = $this->promo_model->selectAll_tcR('blacklist', array('app_id' => $row['app_id']));
            if (count($check) == 0) {
                $action =   '<a href="javascript:;" title="add to Blacklist" id="' . $row['app_id'] . '" onclick="addBl(this.id)">
                                <i class="bx bx-list-plus me-0" style="font-size: 22px;"></i>
                            </a>';
            }

            if ($row['current_status'] == 'blacklisted') {
                $status = '<span class="badge bg-danger">' . $row['current_status'] . '</span>';
            } else if ($row['current_status'] == 'Active') {
                $status = '<span class="badge bg-success">' . $row['current_status'] . '</span>';
            } else {
                $status = '<span class="badge bg-warning">' . $row['current_status'] . '</span>';
            }

            $column     = [];
            $column[]   = $app_id;
            $column[]   = $status;
            $column[]   = $action;
            $data[]     = $column;
        }

        $output = array(
            'draw'              => intval($_POST['draw']),
            'recordsTotal'      => $this->promo_model->get_all_data('checkBl', $input),
            'recordsFiltered'   => $this->promo_model->get_filtered_data('checkBl', $order, $order[0], $input),
            'data'              => $data
        );
        echo json_encode($output);
    }

    public function addCheckBlt()
    {
        $input  = $this->input->post(NULL, TRUE);
        $order  = array('name', null);
        $list   = $this->promo_model->make_datatables('checkBlt', $order, $order[0], $input);
        $data   = [];
        foreach ($list as $row) {

            $name   = ucwords(strtolower($row['name']));
            $app_id = $name;

            if ($row['app_id'] != '') {
                $app_id = '<a href="' . base_url('promo/page/promo/profile/' . $row['app_id']) . '" target="_blank">' . $name . '</a>';
            }

            $column     = [];
            $column[]   = $app_id;
            $column[]   = '<span class="badge bg-danger">Blacklisted</span>';
            $data[]     = $column;
        }

        $output = array(
            'draw'              => intval($_POST['draw']),
            'recordsTotal'      => $this->promo_model->get_all_data('checkBlt', $input),
            'recordsFiltered'   => $this->promo_model->get_filtered_data('checkBlt', $order, $order[0], $input),
            'data'              => $data
        );
        echo json_encode($output);
    }

    public function addBl()
    {
        $input  = $this->input->post(NULL, TRUE);
        $result = $this->promo_model->addBl($input);

        $name = ucwords(strtolower($result['lastname'] . ', ' . $result['firstname'] . ' ' . $result['middlename']));

        if ($result['birthdate'] != '1900-01-01' || $result['birthdate'] != '0000-00-00') {
            $bdate = '';
        } else {
            $bdate = $result['birthdate'];
        }

        echo    '<div class="row">
                    <div class="col-md-12">
                        <label for="input1" class="form-label">EmployeeID/Name</label>
                        <input type="text" class="form-control" value="[' . $result['app_id'] . '] ' . $name . '" disabled>
                        <input type="hidden" class="form-control" name="emp_id" value="' . $result['app_id'] . '">
                        <input type="hidden" class="form-control" name="name" value="' . $name . '">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label for="input2" class="form-label">Reported By</label>
                        <input type="text" class="form-control" name="reportedby" onkeyup="nameSearch(this.value)">
                        <div id="dropdown-list" class="dropdown-list"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="input2" class="form-label">Date Blacklisted</label>
                        <input type="text" class="form-control datepicker" name="date_blacklisted" value="' . date('Y-m-d') . '">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label for="input2" class="form-label">Birthdate</label>
                        <input type="text" class="form-control datepicker" name="bday" value="' . $bdate . '" placeholder="yyyy-mm-dd">
                    </div>
                    <div class="col-md-6">
                        <label for="input2" class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" value="' . $result['home_address'] . '">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label for="input1" class="form-label">Reason</label>
                        <textarea class="form-control" name="reason" cols="3" rows="3"></textarea>
                    </div>
                </div>';
    }

    public function addManualBl()
    {
        $input  = $this->input->post(NULL, TRUE);
        $fn     = '';
        $ln     = '';
        $mn     = '';

        if ($input['fn'] != '') {
            $fn = ucwords(strtolower($input['fn']));
        }
        if ($input['ln'] != '') {
            $ln = ucwords(strtolower($input['ln']));
        }
        if ($input['mn'] != '') {
            $mn = ucwords(strtolower($input['mn']));
        }
        $name = ucwords(strtolower($ln . ', ' . $fn . ' ' . $mn));

        echo    '<div class="row">
                    <div class="col-md-12">
                    <label class="form-label">FullName</label>
                        <input type="text" class="form-control" name="name" value="' . $name . '" disabled>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Birthdate</label>
                        <input type="text" class="form-control datepicker" name="bday" placeholder="yyyy-mm-dd">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="address">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Date Blacklisted</label>
                        <input type="text" class="form-control datepicker" name="date_blacklisted" value="' . date('Y-m-d') . '">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Reported By</label>
                        <input type="text" class="form-control" name="reportedby" onkeyup="nameSearch(this.value)" placeholder="Search here...">
                        <div id="dropdown-list" class="dropdown-list"></div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label class="form-label">Reason</label>
                        <textarea class="form-control" name="reason" cols="3" rows="3"></textarea>
                    </div>
                </div>';
    }

    public function reportedbyBl()
    {
        $input  = $this->input->post(NULL, TRUE);
        $query  = $this->promo_model->reportedbyBl($input);
        echo    '<ul class="list-group">';
        if (count($query) > 0) {
            foreach ($query as $value) {
                echo '<a href="javascript:;" class="list-group-item" onclick="getName(\'' . htmlspecialchars($value['name']) . '\')">' . htmlspecialchars($value['name']) . '</a>';
            }
        } else {
            echo '<a href="javascript:;" class="list-group-item">No results found!</a>';
        }
        echo    '</ul>';
    }
    public function save_bl()
    {
        $input  = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $data = array(
            'app_id'            => $input['emp_id'],
            'name'              => $input['name'],
            'date_blacklisted'  => $input['date_blacklisted'],
            'date_added'        => $this->today,
            'reportedby'        => $input['reportedby'],
            'reason'            => $input['reason'],
            'status'            => 'blacklisted',
            'staff'             => $this->systemUser,
            'bday'              => $input['bday'],
            'address'           => $input['address'],
        );

        $this->promo_model->insert_tdA('blacklist', $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {

            echo json_encode(['message' => 'failed']);
        } else {

            echo json_encode(['message' => 'success']);
        }
    }
}
