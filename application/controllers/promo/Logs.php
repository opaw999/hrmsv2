<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Logs extends CI_Controller
{
    public $today;
    public $systemUser;
    public $adminUser;
    function __construct()
    {
        parent::__construct();
        $this->adminUser    = array('06359-2013', '01186-2023');
        $this->today        = date('Y-m-d');
        $this->systemUser   = $this->session->userdata('emp_id');
        $empId = $this->session->userdata('emp_id');
        if (!isset($empId)) {

            redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrmsv2/employee');
        }
    }

    public function logs()
    {
        $list = $this->promo_model->selectAll_tcA('logs', array('user' => $this->systemUser));
        $data = [];
        foreach ($list as $row) {
            $column     = [];
            $column[]   = $row['log_no'];
            $column[]   = $row['activity'];
            $column[]   = date('m/d/Y', strtotime($row['date']));
            $column[]   = date('h:i:s A', strtotime($row['time']));;
            $column[]   = $row['user'];
            $column[]   = $row['username'];
            $data[]     = $column;
        }

        echo json_encode(array('data' => $data));
    }

    public function logsAdmin()
    {
        $input = $this->input->post(NULL, TRUE);
        $list = $this->promo_model->selectAll_tcA('logs', array('date' => $input['day']));
        $data = [];
        foreach ($list as $row) {
            $check = $this->promo_model->selectAll_tcR('promo_user', array('emp_id' => $row['user'], 'usertype !=' => 'administrator'));
            if (count($check) > 0) {
                $column     = [];
                $column[]   = $row['log_no'];
                $column[]   = $row['activity'];
                $column[]   = date('m/d/Y', strtotime($row['date']));
                $column[]   = date('h:i:s A', strtotime($row['time']));;
                $column[]   = $row['user'];
                $column[]   = $row['username'];
                $data[]     = $column;
            }
        }

        echo json_encode(array('data' => $data));
    }
}
