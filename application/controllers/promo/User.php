<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
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

    public function save_userAccount()
    {
        $input  = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $condition  = array('username' => $input['username']);
        $check      = $this->promo_model->selectAll_tcR('users', $condition);
        $data       = array(
            'emp_id'        => $input['emp_id'],
            'username'      => $input['username'],
            'password'      => md5($input['password']),
            'usertype'      => $input['usertype'],
            'user_status'   => 'inactive',
            'login'         => 'no',
            'date_created'  => date('Y-m-d H:i:s'),
            'user_id'       => '4',
        );
        if (count($check) > 0) {
            $exist = true;
        } else {
            $this->promo_model->insert_tdA('users', $data);
            $exist = false;
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success', 'checkUser' => $exist]);
        }
    }

    public function promoUserAccessRoles()
    {
        $list = $this->promo_model->selectAll('promoV2_submenu');
        $data = [];
        foreach ($list as $row) {

            $name = $this->promo_model->selectAll_tcR('promoV2_menu', array('menu_id' => $row['menu_id']))['menu_name'];
            $promoIncharge =    '<select class="form-select form-select-sm mx-auto" style="width: 70px;" name="promo1" onchange="updatePromoUserAccessRoles(this.name,this.value)">
                                    <option value="yes|' . $row['submenu_id'] . '" ' . ($row['promo1'] == 'yes' ? 'selected' : '') . '>yes</option>
                                    <option value="no|' . $row['submenu_id'] . '" ' . ($row['promo1'] == 'no' ? 'selected' : '') . '>no</option>
                                </select>';

            $encoder =  '<select class="form-select form-select-sm mx-auto" style="width: 70px;" name="promo2" onchange="updatePromoUserAccessRoles(this.name,this.value)">
                            <option value="yes|' . $row['submenu_id'] . '" ' . ($row['promo2'] == 'yes' ? 'selected' : '') . '>yes</option>
                            <option value="no|' . $row['submenu_id'] . '" ' . ($row['promo2'] == 'no' ? 'selected' : '') . '>no</option>
                        </select>';
            $column     = [];
            $column[]   = $name;
            $column[]   = $row['submenu_name'];
            $column[]   = '<span class="badge bg-success px-2 py-1">' . $row['administrator'] . '</span>';
            $column[]   = $promoIncharge;
            $column[]   = $encoder;
            $data[]     = $column;
        }

        echo json_encode(array('data' => $data));
    }

    public function accessRoles()
    {
        $input  = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $split      = explode('|', $input['value']);
        $condition  = array('submenu_id' => $split[1]);
        $data       = array($input['name'] => $split[0]);
        $this->promo_model->update_twdA('promoV2_submenu', $condition, $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success']);
        }
    }

    public function managePromoUserAccounts()
    {
        $select     = '*';
        $condition  = "usertype = 'employee' AND hr_location = 'asc' AND (emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
        $list       = $this->promo_model->promoUserAccounts($select, $condition);

        $data = [];
        foreach ($list as $row) {

            $name       = '<a href="' . base_url('promo/page/promo/profile/' . $row['emp_id']) . '" target="_blank">' . ucwords(strtolower($row['name'])) . '</a>';
            $condition  = array('emp_id' => $row['emp_id'], 'record_no' => $row['record_no']);
            $action     = '';
            if ($row['user_status'] == 'active') {
                $status =   '<span class="badge bg-success">' . $row['user_status'] . '</span>';
                $action .=   '<a href="javascript:;" id="deactivate" onclick="userAccount(this.id, \'' . $row['user_no'] . '\')" title="Deactivate Account">
                                <i class="bx bxs-bulb me-0 font-22 text-warning"></i>
                            </a>';
            } else {
                $status = '<span class="badge bg-danger">' . $row['user_status'] . '</span>';
                $action .=   '<a href="javascript:;" id="activate" onclick="userAccount(this.id, \'' . $row['user_no'] . '\')" title="Activate Account">
                                <i class="bx bxs-bulb me-0 font-22 text-secondary"></i>
                            </a>';
            }

            $action .=   '<a href="javascript:;" id="reset" onclick="userAccount(this.id, \'' . $row['user_no'] . '\')" title="Reset Password">
                            <i class="fadeIn animated bx bx-reset font-22"></i>
                        </a>';
            if (in_array($this->systemUser, $this->adminUser)) {
                $action .=  '<a href="javascript:;" id="remove" onclick="userAccount(this.id, \'' . $row['user_no'] . '\')" title="Delete Account">
                            <i class="fadeIn animated bx bx-trash font-22 text-danger"></i>
                        </a>';
            }

            $column     = [];
            $column[]   = $name;
            $column[]   = $row['username'];
            $column[]   = $row['usertype'];
            $column[]   = $row['login'];
            $column[]   = $status;
            $column[]   = $action;
            $data[]     = $column;
        }

        echo json_encode(array('data' => $data));
    }

    public function userAccount()
    {
        $input  = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $condition  = array('user_no' => $input['id']);
        $message    = '';
        if ($input['process'] == 'deactivate') {
            $data = array('user_status' => 'inactive', 'date_updated' => date('Y-m-d H:i:s'));
            $this->promo_model->update_twdA('users', $condition, $data);
            $message    = 'User Account has been deactivated!';
        } else if ($input['process'] == 'activate') {
            $data = array('user_status' => 'active', 'date_updated' => date('Y-m-d H:i:s'));
            $this->promo_model->update_twdA('users', $condition, $data);
            $message    = 'User Account has been activated!';
        } else if ($input['process'] == 'reset') {
            $data = array('password' => md5('Hrms2014'), 'date_updated' => date('Y-m-d H:i:s'));
            $this->promo_model->update_twdA('users', $condition, $data);
            $message    = 'User Account Password has been resetted!';
        } else {
            $this->promo_model->delete_tcr_row('users', $condition);
            $message    = 'User Account has been deleted!';
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success', 'report' => $message]);
        }
    }

    public function managePromoInchargeAccounts()
    {
        $select     = '*,t1.date_updated';
        $table1     = 'promo_user';
        $table2     = 'employee3';
        $join       = 't1.emp_id=t2.emp_id';
        $condition  = array('usertype !=' => 'administrator');
        $order      = 't2.name|ASC';
        $list      = $this->promo_model->join_ttjcoA($select, $table1, $table2, $join, null, $condition, $order);

        $data = [];
        foreach ($list as $row) {

            $emp_id       = '<a href="' . base_url('promo/page/promo/profile/' . $row['emp_id']) . '" target="_blank">' . $row['emp_id']  . '</a>';
            $date_updated = '';
            if (!empty($row['date_updated']) && $row['date_updated'] != '0000-00-00') {
                $date_updated = date("m/d/Y", strtotime($row['date_updated']));
            }

            $userType =    '<select class="form-select form-select-sm mx-auto" style="width: 150px;" name="usertype" onchange="updatePromoInchargeAccounts(this.name,this.value)">
                                    <option value="promo1|' . $row['user_no'] . '" ' . ($row['usertype'] == 'promo1' ? 'selected' : '') . '>Promo Incharge</option>
                                    <option value="promo2|' . $row['user_no'] . '" ' . ($row['usertype'] == 'promo2' ? 'selected' : '') . '>Encoder</option>
                                </select>';

            $status =  '<select class="form-select form-select-sm mx-auto" style="width: 100px;" name="user_status" onchange="updatePromoInchargeAccounts(this.name,this.value)">
                            <option value="active|' . $row['user_no'] . '" ' . ($row['user_status'] == 'active' ? 'selected' : '') . '>Active</option>
                            <option value="inactive|' . $row['user_no'] . '" ' . ($row['user_status'] == 'inactive' ? 'selected' : '') . '>InActive</option>
                        </select>';

            $column     = [];
            $column[]   = $emp_id;
            $column[]   = ucwords(strtolower($row['name']));
            $column[]   = $userType;
            $column[]   = $status;
            $column[]   = date("m/d/Y", strtotime($row['date_created']));
            $column[]   = $date_updated;
            $data[]     = $column;
        }

        echo json_encode(array('data' => $data));
    }

    public function updatePromoInchargeAccounts()
    {
        $input  = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $split      = explode('|', $input['value']);
        $condition  = array('user_no' => $split[1]);
        $data       = array($input['name'] => $split[0], 'date_updated' => date("Y-m-d H:i:s"));
        $this->promo_model->update_twdA('promo_user', $condition, $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success']);
        }
    }

    public function addPromoInchargeAccount()
    {
        $input  = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $data       = array(
            'emp_id'        => $input['emp_id'],
            'usertype'      => $input['usertype'],
            'user_status'   => $input['user_status'],
            'date_created'  => date('Y-m-d H:i:s'),

        );
        $this->promo_model->insert_tdA('promo_user', $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success']);
        }
    }

    public function updateUserAccount()
    {
        $input  = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $chk        = '';
        $condition  = array('username' => $input['username'], 'emp_id' => $input['emp_id']);
        $usersChk   = $this->promo_model->selectAll_tcR('users', $condition);

        if ($input['new1'] == $input['new2']) {
            if (count($usersChk) > 0) {
                if ($input['process'] == 'username') {
                    $c      = array('username' => $input['new1'], 'emp_id !=' => $input['emp_id']);
                    $check  = $this->promo_model->selectAll_tcR('users', $c);
                    if (count($check) > 0) {
                        $chk = 'exist';
                    } else {
                        $this->promo_model->update_twdA('users', $condition, array('username' => $input['new1'],));
                    }
                } else {
                    if ($usersChk['password'] == md5($input['password'])) {
                        $this->promo_model->update_twdA('users', $condition, array('password' => md5($input['new1'])));
                    } else {
                        $chk = 'oldPasswordError';
                    }
                }
            } else {
                $chk = 'User Not Found!';
            }
        } else {
            $chk = 'mismatch';
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success', 'report' => $chk]);
        }
    }
}
