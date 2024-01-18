<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setup extends CI_Controller
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

    public function supervisorDetails()
    {
        $input  = $this->input->post(NULL, TRUE);
        $row    = $this->promo_model->selectAll_tcR('employee3', array('emp_id' => $input['emp_id']));
        $cc     = $row['company_code'];
        $bc     = $row['company_code'] . $row['bunit_code'];
        $dc     = $row['company_code'] . $row['bunit_code'] . $row['dept_code'];
        $sc     = $row['company_code'] . $row['bunit_code'] . $row['dept_code'] . $row['section_code'];
        $data['emp_id']     = $input['emp_id'];
        $data['company']    = $this->promo_model->selectAll_tcR('locate_company', array('company_code' => $cc))['company'];
        $data['store']      = $this->promo_model->selectAll_tcR('locate_business_unit', array('bcode' => $bc))['business_unit'];
        $data['department'] = $this->promo_model->selectAll_tcR('locate_department', array('dcode' => $dc))['dept_name'];
        $data['section']    = $this->promo_model->selectAll_tcR('locate_section', array('scode' => $sc))['section_name'];
        $data['position']   = $row['position'];
        $data['posLevel']   = $row['positionlevel'];
        $data['empType']    = $row['emp_type'];
        $data['request']    = 'supervisorDetails';

        $this->load->view('promo/page/page_response', $data);
    }

    public function subordinates()
    {
        $input      = $this->input->post(NULL, TRUE);
        $select     = '*,t1.record_no,t2.record_no as sub_id';
        $table1     = 'employee3';
        $table2     = 'leveling_subordinates';
        $join       = 't1.emp_id=t2.subordinates_rater';
        $condition  = "ratee = '$input[emp_id]' AND (emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
        $order      = 'name|ASC';
        $query      = $this->promo_model->join_ttjcoA($select, $table1, $table2, $join, null, $condition, $order, null);

        echo    '<div class="row border-start">
                    <label class="form-label text-secondary text-center">SUBORDINATES LIST</label>
                    <table id="setupSubordinateTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>EmpID</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>';

        foreach ($query as $row) {
            $condition  = array('emp_id' => $row['emp_id'], 'record_no' => $row['record_no']);
            $check      = $this->promo_model->selectAll_tcR('promo_record', $condition);
            if (count($check) > 0) {
                $profile    = '<a href="' . base_url('promo/page/promo/profile/' . $row['emp_id']) . '" target="_blank">' . $row['emp_id'] . '</a>';

                if ($row['current_status'] == 'Active') {
                    $color = 'success';
                } else if ($row['current_status'] == 'blacklisted') {
                    $color = 'danger';
                } else {
                    $color = 'warning';
                }
                $stat = '<span class="badge bg-' . $color . '">' . $row['current_status'] . '</span>';
                echo    '<tr>
                            <td>
                            <a href="javascript:;" class="bl" title="remove" id="remove" onclick="updateSub(this.id, \'' . $row['sub_id'] . '\')">
                                <i class="bx bx-x-circle text-danger me-0" style="font-size: 22px;"></i>
                            </a>
                            </td>
                            <td>' . $profile . '</td>
                            <td>' . ucwords(strtolower($row['name'])) . '</td>
                            <td>' . $row['position'] . '</td>
                            <td>' . $stat . '</td>
                        </tr>';
            }
        }
        echo            '</tbody>
                    </table>
                </div>';
    }

    public function addSubordinatesForm()
    {
        $bUs = $this->promo_model->locate_promo_bu('asc');
        echo    '<label class="form-label text-secondary">Employee Type</label>
                <select class="form-select" name="emp_type">
                    <option value="">Select Employee Type</option>
                    <option value="Promo">Promo</option>
                    <option value="Promo-NESCO">Promo-NESCO</option>
                </select>
                <label class="form-label text-secondary mt-2">Store</label>
                <select class="form-select" name="store" onchange="getDepartment(this.value)">
                    <option value="">Select Store</option>';
        foreach ($bUs as $bu) {
            echo    '<option value="' . $bu['bunit_id'] . '|' . $bu['bunit_field'] . '">' . $bu['bunit_name'] . '</option>';
        }
        echo    '</select>
                <label class="form-label text-secondary mt-2">Department</label>
                <select class="form-select" name="promo_department">
                    <option value="">Select Department</option>
                </select>';
    }

    public function generateSub()
    {
        $input = $this->input->post(NULL, TRUE);
        $field = explode('|', $input['store']);
        $condition = array('emp_type' => $input['emp_type'], $field[1] => 'T', 'promo_department' => $input['promo_department']);
        $query = $this->promo_model->promoDetails_wttA($condition, 'employee3', 'promo_record');
        echo    '<div class="row border-start">
                    <label class="form-label text-secondary text-center">SUBORDINATES LIST</label>
                    <table id="generateSubordinateTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th style="width:100px;">EmpID</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>';
        foreach ($query as $row) {
            $condition = array('ratee' => $input['emp_id'], 'subordinates_rater' => $row['emp_id']);
            $check = $this->promo_model->selectAll_tcR('leveling_subordinates', $condition);

            if (count($check) == 0) {
                $profile    = '<a href="' . base_url('promo/page/promo/profile/' . $row['emp_id']) . '" target="_blank">' . $row['emp_id'] . '</a>';

                if ($row['current_status'] == 'Active') {
                    $color = 'success';
                } else if ($row['current_status'] == 'blacklisted') {
                    $color = 'danger';
                } else {
                    $color = 'warning';
                }
                $stat = '<span class="badge bg-' . $color . '">' . $row['current_status'] . '</span>';
                echo    '<tr>
                            <td>
                                <a href="javascript:;" class="bl" title="add" id="add" onclick="updateSub(this.id, \'' . $input['emp_id'] . '\', \'' . $row['emp_id'] . '\')">
                                    <i class="bx bx-plus-circle text-primary me-0" style="font-size: 22px;"></i>
                                </a>
                            </td>
                            <td>' . $profile . '</td>
                            <td>' . ucwords(strtolower($row['name'])) . '</td>
                            <td>' . $row['position'] . '</td>
                            <td>' . $stat . '</td>
                        </tr>';
            }
        }
        echo            '</tbody>
                    </table>
                </div>';
    }

    public function updateSubordinates()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        if ($input['process'] == 'remove') {
            $supID = $this->promo_model->selectAll_tcR('leveling_subordinates', array('record_no' => $input['id']))['ratee'];
            $this->promo_model->delete_tcr_row('leveling_subordinates', array('record_no' => $input['id']));
        } else {
            $data = array(
                'ratee' => $input['id'],
                'subordinates_rater' => $input['emp_id'],
                'date_added' => $this->today
            );
            $supID = $input['id'];
            $this->promo_model->insert_tdA('leveling_subordinates', $data);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success', 'emp_id' => $supID]);
        }
    }

    public function companyAgency()
    {
        $list = $this->promo_model->selectAll_tk('promo_locate_agency');
        $data = [];
        foreach ($list as $row) {

            $condition = array('agency_code' => $row['agency_code']);
            $company = $this->promo_model->selectAll_tcA_tk('promo_locate_company', $condition);
            foreach ($company as $comp) {
                $column     = [];
                $column[]   = $row['agency_name'];
                $column[]   = $comp['company_name'];
                $column[]   =   '<a href="javascript:;" title="remove" id="remove" onclick="updateCompanyAgency(this.id, \'' . $comp['company_code'] . '\')">
                                    <i class="bx bx-x-circle text-danger me-0" style="font-size: 22px;"></i>
                                </a>';
                $data[]     = $column;
            }
        }

        echo json_encode(array('data' => $data));
    }

    public function updateCompanyAgency()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        if ($input['process'] == 'remove') {
            $this->promo_model->delete_tc_tk('promo_locate_company', array('company_code' => $input['cc']));
        } else {
            $data = array(
                'agency_code' => $input['ac'],
                'company_name' => $input['cc'],
                'created_at' =>  date('Y-m-d H:i:s')
            );

            $this->promo_model->insert_tdA_tk('promo_locate_company', $data);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success']);
        }
    }

    public function companyAgencyList()
    {
        $input  = $this->input->post(NULL, TRUE);
        $list   = $this->promo_model->selectAll('locate_promo_company');
        $data   = [];
        foreach ($list as $row) {

            $condition = array('agency_code' => $input['agency_code'], 'company_name' => $row['pc_name']);
            $check = $this->promo_model->selectAll_tcR_tk('promo_locate_company', $condition);
            if (count($check) == 0) {
                $column     = [];
                $column[]   = $row['pc_name'];
                $column[]   = $row['pc_name'];
                $column[]   =   '<a href="javascript:;" title="add" id="add" onclick="updateCompanyAgency(this.id, \'' . $row['pc_name'] . '\')">
                                    <i class="bx bx-plus-circle text-primary me-0" style="font-size: 22px;"></i>
                                </a>';
                $data[]     = $column;
            }
        }

        echo json_encode(array('data' => $data));
    }

    public function agencyList()
    {
        $list   = $this->promo_model->selectAll_tk('promo_locate_agency');
        $data   = [];
        foreach ($list as $row) {
            $action =   '<a href="javascript:;" title="edit" id="edit" onclick="updateForm(this.id, \'' . $row['agency_code'] . '\', \'agency\')">
                            <i class="bx bx-edit text-primary me-0" style="font-size: 22px;"></i>
                        </a>';
            if ($row['status'] == 1) {
                $action .=   '<a href="javascript:;" title="deactivate" id="deactivate" onclick="updateAgency(this.id, \'' . $row['agency_code'] . '\')">
                                <i class="bx bx-bulb text-warning me-0" style="font-size: 22px;"></i>
                            </a>';
            } else {
                $action .=   '<a href="javascript:;" title="activate" id="activate" onclick="updateAgency(this.id, \'' . $row['agency_code'] . '\')">
                                <i class="bx bx-bulb text-secondary me-0" style="font-size: 22px;"></i>
                            </a>';
            }

            if (in_array($this->systemUser, $this->adminUser)) {
                $action .=   '<a href="javascript:;" title="delete" id="delete" onclick="updateAgency(this.id, \'' . $row['agency_code'] . '\')">
                                <i class="bx bx-x-circle text-danger me-0" style="font-size: 22px;"></i>
                            </a>';
            }
            $column     = [];
            $column[]   = $row['agency_name'];
            $column[]   = $action;
            $data[]     = $column;
        }

        echo json_encode(array('data' => $data));
    }

    public function updateForm()
    {
        $input = $this->input->post(NULL, TRUE);
        if ($input['table'] == 'agency') {
            $agency_name = $this->promo_model->selectAll_tcR_tk('promo_locate_agency', array('agency_code' => $input['id']))['agency_name'];
            echo    '<input type="hidden" name="agency_code" value="' . $input['id'] . '">
                    <input type="hidden" name="process" value="edit">
                    <div class="form-group">
                        <label>Agency Name</label>
                        <input type="text" class="form-control" name="agency_name" value="' . $agency_name . '" autocomplete="off">
                    </div>';
        } else if ($input['table'] == 'company') {
            $pc_name = $this->promo_model->selectAll_tcR('locate_promo_company', array('pc_code' => $input['id']))['pc_name'];
            echo    '<input type="hidden" name="pc_code" value="' . $input['id'] . '">
                    <input type="hidden" name="process" value="edit">
                    <div class="form-group">
                        <label>Company Name</label>
                        <input type="text" class="form-control" name="pc_name" value="' . $pc_name . '" autocomplete="off">
                    </div>';
        } else if ($input['table'] == 'product') {
            $product = $this->promo_model->selectAll_tcR('locate_promo_product', array('id' => $input['id']))['product'];
            echo    '<input type="hidden" name="id" value="' . $input['id'] . '">
                    <input type="hidden" name="process" value="edit">
                    <div class="form-group">
                        <label>Company Name</label>
                        <input type="text" class="form-control" name="product" value="' . $product . '" autocomplete="off">
                    </div>';
        } else if ($input['table'] == 'bu') {
            $bu = $this->promo_model->selectAll_tcR('locate_promo_business_unit', array('bunit_id' => $input['id']));
            echo    '<input type="hidden" name="bunit_id" value="' . $input['id'] . '">
                    <input type="hidden" name="process" value="edit">
                    <div class="row">
				    	<div class="col-md-12 mb-2">
				    		<div class="form-group">
				    			<label for="">Business Unit</label>
				    			<input type="text" name="business_unit" value="' . $bu['business_unit'] . '" class="form-control" style="text-transform: uppercase;" autocomplete="off">
				    		</div>
				    	</div>
				    	<div class=" col-md-12 mb-2">
				    		<div class="form-group">
				    			<label for="">Business Unit Name</label>
				    			<input type="text" name="bunit_name" value="' . $bu['bunit_name'] . '" class="form-control" style="text-transform: uppercase;" autocomplete="off">
				    		</div>
                        </div>
				    	<div class=" col-md-6 mb-2">
				    		<div class="form-group">
				    			<label for="">Acronym</label>
				    			<input type="text" name="bunit_acronym" value="' . $bu['bunit_acronym'] . '" class="form-control" style="text-transform: uppercase;" autocomplete="off">
				    		</div>
                        </div>
                        <div class=" col-md-6 mb-2">
				    		<div class="form-group">
				    			<label for="">Column Field</label>
				    			<input type="text" name="bunit_field" value="' . $bu['bunit_field'] . '" class="form-control" style="text-transform: lowercase;" disabled>
				    		</div>
                        </div>
				    </div>';
        } else if ($input['table'] == 'addFormBu') {
            echo    '<input type="hidden" name="process" value="add">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label for="">Business Unit</label>
                                <input type="text" name="business_unit" class="form-control" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class=" col-md-12 mb-2">
                            <div class="form-group">
                                <label for="">Business Unit Name</label>
                                <input type="text" name="bunit_name" class="form-control" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class=" col-md-6 mb-2">
                            <div class="form-group">
                                <label for="">Acronym</label>
                                <input type="text" name="bunit_acronym" class="form-control" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class=" col-md-6 mb-2">
                            <div class="form-group">
                                <label for="">Column Field</label>
                                <input type="text" name="bunit_field" class="form-control" style="text-transform: lowercase;">
                            </div>
                        </div>
                    </div>';
        }
    }

    public function updateAgency()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $message = '';
        if ($input['process'] == 'add' || $input['process'] == 'edit') {

            $condition = array('agency_name' => $input['agency_name']);
            if ($input['process'] == 'edit') {
                $condition['pc_code !='] = $input['id'];
            }
            $check = $this->promo_model->selectAll_tcA_tk('promo_locate_agency', $condition);
            if (count($check) > 0) {
                $message = 'Agency name already exists!';
                $this->db->trans_rollback();
                echo json_encode(['message' => 'failed', 'report' => $message]);
                return;
            }
        }

        if ($input['process'] != 'add') {
            $condition = array('agency_code' => $input['id']);
        }

        if ($input['process'] == 'activate') {

            $message = 'activated';
            $data = array('status' => 1, 'updated_at' => date('Y-m-d H:i:s'));
            $this->promo_model->update_twdA_tk('promo_locate_agency', $condition, $data);
        } else if ($input['process'] == 'deactivate') {
            $message = 'deactivated';
            $data = array('status' => 0, 'updated_at' => date('Y-m-d H:i:s'));
            $this->promo_model->update_twdA_tk('promo_locate_agency', $condition, $data);
        } else if ($input['process'] == 'edit') {
            $message = 'updated';
            $data = array(
                'agency_name'   => $input['agency_name'],
                'updated_at'    => date('Y-m-d H:i:s')
            );
            $this->promo_model->update_twdA_tk('promo_locate_agency', $condition, $data);
        } else if ($input['process'] == 'delete') {
            $message = 'deleted';
            $this->promo_model->delete_tc_tk('promo_locate_agency', $condition);
        } else if ($input['process'] == 'add') {
            $message = 'added';
            $data = array(
                'agency_name'   => $input['agency_name'],
                'status'        => 1,
                'created_at'    => date('Y-m-d H:i:s')
            );
            $this->promo_model->insert_tdA_tk('promo_locate_agency', $data);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success', 'report' => $message]);
        }
    }

    public function companyList()
    {
        $list   = $this->promo_model->selectAll('locate_promo_company');
        $data   = [];
        foreach ($list as $row) {
            $action =   '<a href="javascript:;" title="edit" id="edit" onclick="updateForm(this.id, \'' . $row['pc_code'] . '\', \'company\')">
                            <i class="bx bx-edit text-primary me-0" style="font-size: 22px;"></i>
                        </a>';
            if ($row['status'] == 1) {
                $action .=   '<a href="javascript:;" title="deactivate" id="deactivate" onclick="updateCompany(this.id, \'' . $row['pc_code'] . '\')">
                                <i class="bx bx-bulb text-warning me-0" style="font-size: 22px;"></i>
                            </a>';
            } else {
                $action .=   '<a href="javascript:;" title="activate" id="activate" onclick="updateCompany(this.id, \'' . $row['pc_code'] . '\')">
                                <i class="bx bx-bulb text-secondary me-0" style="font-size: 22px;"></i>
                            </a>';
            }

            if (in_array($this->systemUser, $this->adminUser)) {
                $action .=   '<a href="javascript:;" title="delete" id="delete" onclick="updateCompany(this.id, \'' . $row['pc_code'] . '\')">
                                <i class="bx bx-x-circle text-danger me-0" style="font-size: 22px;"></i>
                            </a>';
            }
            $column     = [];
            $column[]   = $row['pc_name'];
            $column[]   = $action;
            $data[]     = $column;
        }

        echo json_encode(array('data' => $data));
    }

    public function updateCompany()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $message = '';
        if ($input['process'] == 'add' || $input['process'] == 'edit') {

            $condition = array('pc_name' => $input['pc_name']);
            if ($input['process'] == 'edit') {
                $condition['pc_code !='] = $input['id'];
            }
            $check = $this->promo_model->selectAll_tcA('locate_promo_company', $condition);
            if (count($check) > 0) {
                $message = 'Company name already exists!';
                $this->db->trans_rollback();
                echo json_encode(['message' => 'failed', 'report' => $message]);
                return;
            }
        }


        if ($input['process'] != 'add') {
            $condition = array('pc_code' => $input['id']);
        }

        if ($input['process'] == 'activate') {

            $message = 'activated';
            $data = array('status' => 1, 'updated_at' => date('Y-m-d H:i:s'));
            $this->promo_model->update_twdA('locate_promo_company', $condition, $data);
        } else if ($input['process'] == 'deactivate') {
            $message = 'deactivated';
            $data = array('status' => 0, 'updated_at' => date('Y-m-d H:i:s'));
            $this->promo_model->update_twdA('locate_promo_company', $condition, $data);
        } else if ($input['process'] == 'edit') {
            $message = 'updated';
            $data = array(
                'pc_name' => $input['pc_name'],
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->promo_model->update_twdA('locate_promo_company', $condition, $data);
        } else if ($input['process'] == 'delete') {
            $message = 'deleted';
            $this->promo_model->delete_tcr_row('locate_promo_company', $condition);
        } else if ($input['process'] == 'add') {
            $message = 'added';
            $data = array(
                'pc_name'       => $input['pc_name'],
                'status'        => 1,
                'created_at'    => date('Y-m-d H:i:s')
            );
            $this->promo_model->insert_tdA('locate_promo_company', $data);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success', 'report' => $message]);
        }
    }

    public function productList()
    {
        $list   = $this->promo_model->selectAll('locate_promo_product');
        $data   = [];
        foreach ($list as $row) {
            $action =   '<a href="javascript:;" title="edit" id="edit" onclick="updateForm(this.id, \'' . $row['id'] . '\', \'product\')">
                            <i class="bx bx-edit text-primary me-0" style="font-size: 22px;"></i>
                        </a>';
            if ($row['status'] == 1) {
                $action .=   '<a href="javascript:;" title="deactivate" id="deactivate" onclick="updateProduct(this.id, \'' . $row['id'] . '\')">
                                <i class="bx bx-bulb text-warning me-0" style="font-size: 22px;"></i>
                            </a>';
            } else {
                $action .=   '<a href="javascript:;" title="activate" id="activate" onclick="updateProduct(this.id, \'' . $row['id'] . '\')">
                                <i class="bx bx-bulb text-secondary me-0" style="font-size: 22px;"></i>
                            </a>';
            }

            if (in_array($this->systemUser, $this->adminUser)) {
                $action .=   '<a href="javascript:;" title="delete" id="delete" onclick="updateProduct(this.id, \'' . $row['id'] . '\')">
                                <i class="bx bx-x-circle text-danger me-0" style="font-size: 22px;"></i>
                            </a>';
            }
            $column     = [];
            $column[]   = $row['product'];
            $column[]   = $action;
            $data[]     = $column;
        }

        echo json_encode(array('data' => $data));
    }

    public function updateProduct()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $message = '';
        if ($input['process'] == 'add' || $input['process'] == 'edit') {

            $condition = array('product' => $input['product']);
            if ($input['process'] == 'edit') {
                $condition['id !='] = $input['id'];
            }
            $check = $this->promo_model->selectAll_tcA('locate_promo_product', $condition);
            if (count($check) > 0) {
                $message = 'Product name already exists!';
                $this->db->trans_rollback();
                echo json_encode(['message' => 'failed', 'report' => $message]);
                return;
            }
        }

        if ($input['process'] != 'add') {
            $condition = array('id' => $input['id']);
        }

        if ($input['process'] == 'activate') {

            $message = 'activated';
            $data = array('status' => 1, 'updated_at' => date('Y-m-d H:i:s'));
            $this->promo_model->update_twdA('locate_promo_product', $condition, $data);
        } else if ($input['process'] == 'deactivate') {
            $message = 'deactivated';
            $data = array('status' => 0, 'updated_at' => date('Y-m-d H:i:s'));
            $this->promo_model->update_twdA('locate_promo_product', $condition, $data);
        } else if ($input['process'] == 'edit') {
            $message = 'updated';
            $data = array(
                'product' => $input['product'],
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->promo_model->update_twdA('locate_promo_product', $condition, $data);
        } else if ($input['process'] == 'delete') {
            $message = 'deleted';
            $this->promo_model->delete_tcr_row('locate_promo_product', $condition);
        } else if ($input['process'] == 'add') {
            $message = 'added';
            $data = array(
                'product'       => $input['product'],
                'status'        => 1,
                'created_at'    => date('Y-m-d H:i:s')
            );
            $this->promo_model->insert_tdA('locate_promo_product', $data);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success', 'report' => $message]);
        }
    }

    public function productCompany()
    {
        $list = $this->promo_model->selectAll('promo_company_products');
        $data = [];
        foreach ($list as $row) {

            $column     = [];
            $column[]   = $row['company'];
            $column[]   = $row['product'];
            $column[]   =   '<a href="javascript:;" title="remove" id="remove" onclick="updateProductCompany(this.id, \'' . $row['id'] . '\')">
                                <i class="bx bx-x-circle text-danger me-0" style="font-size: 22px;"></i>
                            </a>';
            $data[]     = $column;
        }

        echo json_encode(array('data' => $data));
    }

    public function productCompanyList()
    {
        $input  = $this->input->post(NULL, TRUE);
        $list   = $this->promo_model->selectAll('locate_promo_product');
        $data   = [];
        foreach ($list as $row) {

            $condition = array('company' => $input['company'], 'product' => $row['product']);
            $check = $this->promo_model->selectAll_tcR('promo_company_products', $condition);
            if (count($check) == 0) {
                $column     = [];
                $column[]   = $row['product'];
                $column[]   = $row['product'];
                $column[]   =   '<a href="javascript:;" title="add" id="add" onclick="updateProductCompany(this.id, \'' . $row['product'] . '\')">
                                    <i class="bx bx-plus-circle text-primary me-0" style="font-size: 22px;"></i>
                                </a>';
                $data[]     = $column;
            }
        }

        echo json_encode(array('data' => $data));
    }

    public function updateProductCompany()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        if ($input['process'] == 'remove') {
            $this->promo_model->delete_tcr_row('promo_company_products', array('id' => $input['id']));
        } else {
            $data = array(
                'company' => $input['company'],
                'product' => $input['id'],
                'created_at' =>  date('Y-m-d H:i:s')
            );

            $this->promo_model->insert_tdA('promo_company_products', $data);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success']);
        }
    }

    public function departmentList()
    {
        $c      = array('status' => 'active', 'hrd_location' => 'asc');
        $list   = $this->promo_model->selectAll_tcA('locate_promo_business_unit', $c);
        $data   = [];
        foreach ($list as $row) {

            $condition = array('bunit_id' =>  $row['bunit_id']);
            $dept = $this->promo_model->selectAll_tcA('locate_promo_department', $condition);
            foreach ($dept as $val) {
                $status =  '<select class="form-select form-select-sm" style="width: 100px;" id="update" onchange="updateDepartment(this.id,this.value)">
                                <option value="active|' . $val['dept_id'] . '" ' . ($val['status'] == 'active' ? 'selected' : '') . '>Active</option>
                                <option value="inactive|' . $val['dept_id'] . '" ' . ($val['status'] == 'inactive' ? 'selected' : '') . '>InActive</option>
                            </select>';

                $column     = [];
                $column[]   = $row['bunit_name'];
                $column[]   = $val['dept_name'];
                $column[]   = $status;
                $column[]   =   '<a href="javascript:;" title="remove" id="remove" onclick="updateDepartment(this.id, \'' . $val['dept_id'] . '\')">
                                    <i class="bx bx-x-circle text-danger me-0" style="font-size: 22px;"></i>
                                </a>';
                $data[]     = $column;
            }
        }

        echo json_encode(array('data' => $data));
    }

    public function updateDepartment()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        if ($input['process'] == 'remove') {

            $this->promo_model->delete_tcr_row('locate_promo_department', array('dept_id' => $input['id']));
            $report = 'removed';
        } else if ($input['process'] == 'add') {

            $c = array('bunit_id' => $input['bunit_id'], 'dept_name' => $input['dept_name']);
            $check = $this->promo_model->selectAll_tcR('locate_promo_department', $c);
            if (count($check) == 0) {

                $data = array(
                    'bunit_id'  => $input['bunit_id'],
                    'dept_name' => $input['dept_name'],
                    'status'    =>  'active'
                );

                $this->promo_model->insert_tdA('locate_promo_department', $data);
                $report = 'added';
            } else {
                $report = 'exist';
            }
        } else {

            $split  = explode('|', $input['id']);
            $data   = array('status' => $split[0]);
            $c      = array('dept_id' => $split[1]);
            $this->promo_model->update_twdA('locate_promo_department', $c, $data);
            $report = 'updated';
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success', 'report' => $report]);
        }
    }

    public function buList()
    {
        $list   = $this->promo_model->selectAll_tcA('locate_promo_business_unit', array('hrd_location' => 'asc'));
        $data   = [];
        foreach ($list as $row) {

            $status =  '<select class="form-select form-select-sm" style="width: 100px;" id="update" name="status" onchange="updateBu(this.id,this.name,this.value)">
                            <option value="active|' . $row['bunit_id'] . '" ' . ($row['status'] == 'active' ? 'selected' : '') . '>Active</option>
                            <option value="inactive|' . $row['bunit_id'] . '" ' . ($row['status'] == 'inactive' ? 'selected' : '') . '>InActive</option>
                        </select>';

            $tk_status =    '<select class="form-select form-select-sm" style="width: 100px;" id="update" name="tk_status" onchange="updateBu(this.id,this.name,this.value)">
                                <option value="active|' . $row['bunit_id'] . '" ' . ($row['tk_status'] == 'active' ? 'selected' : '') . '>Active</option>
                                <option value="inactive|' . $row['bunit_id'] . '" ' . ($row['tk_status'] == 'inactive' ? 'selected' : '') . '>InActive</option>
                             </select>';

            $app_status =  '<select class="form-select form-select-sm" style="width: 100px;" id="update" name="appraisal_status" onchange="updateBu(this.id,this.name,this.value)">
                                <option value="active|' . $row['bunit_id'] . '" ' . ($row['appraisal_status'] == 'active' ? 'selected' : '') . '>Active</option>
                                <option value="inactive|' . $row['bunit_id'] . '" ' . ($row['appraisal_status'] == 'inactive' ? 'selected' : '') . '>InActive</option>
                            </select>';

            $column     = [];
            $column[]   = $row['bunit_name'];
            $column[]   = $status;
            $column[]   = $tk_status;
            $column[]   = $app_status;
            $column[]   =   '<a href="javascript:;" title="edit" id="edit" onclick="updateForm(this.id, \'' . $row['bunit_id'] . '\',\'bu\')">
                                <i class="bx bx-edit text-primary me-0" style="font-size: 22px;"></i>
                            </a>';
            $data[]     = $column;
        }

        echo json_encode(array('data' => $data));
    }

    public function updateBu()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        if ($input['process'] == 'update') {

            $split  = explode('|', $input['value']);
            $data   = array($input['column'] => $split[0]);
            $c      = array('bunit_id' => $split[1]);
            $this->promo_model->update_twdA('locate_promo_business_unit', $c, $data);
            $report = 'updated';
        } else if ($input['process'] == 'add') {
            $report = '';
            $exist  = false;
            $check  = $this->promo_model->selectAll('locate_promo_business_unit');
            foreach ($check as $row) {
                if ($row['business_unit'] == strtoupper($input['business_unit'])) {
                    $report = 'business_unit';
                    $exist = true;
                }
                if ($row['bunit_name'] == strtoupper($input['bunit_name'])) {
                    $report = 'bunit_name';
                    $exist = true;
                }
                if ($row['bunit_acronym'] == strtoupper($input['bunit_acronym'])) {
                    $report = 'bunit_acronym';
                    $exist = true;
                }
                if ($row['bunit_field'] == strtolower($input['bunit_field'])) {
                    $report = 'bunit_field';
                    $exist = true;
                }
            }

            if (!$exist) {

                $data = array(
                    'business_unit'         => strtoupper($input['business_unit']),
                    'bunit_name'            => strtoupper($input['bunit_name']),
                    'bunit_acronym'         => strtoupper($input['bunit_acronym']),
                    'bunit_field'           => strtolower($input['bunit_field']),
                    'bunit_epascode'        => strtolower($input['bunit_field']) . '_epascode',
                    'bunit_contract'        => strtolower($input['bunit_field']) . '_contract',
                    'bunit_permit'          => strtolower($input['bunit_field']) . '_permit',
                    'bunit_clearance'       => strtolower($input['bunit_field']) . '_clearance',
                    'bunit_intro'           => strtolower($input['bunit_field']) . '_intro',
                    'bunit_dutySched'       => strtolower($input['bunit_field']) . '_sched',
                    'bunit_dutyDays'        => strtolower($input['bunit_field']) . '_days',
                    'bunit_specialSched'    => strtolower($input['bunit_field']) . '_special_sched',
                    'bunit_specialDays'     => strtolower($input['bunit_field']) . '_special_days',
                    'hrd_location'          => 'asc',
                    'statuts'               => 'inactive',
                    'tk_status'             => 'inactive',
                    'appraisal_status'      => 'inactive',
                );
                // $bu = $this->promo_model->insert_tdA('locate_promo_business_unit', $data);
                // promo_record
                // promo_history_record
                $report = 'added';
            }
        } else if ($input['process'] == 'edit') {

            $report = '';
            $exist  = false;
            $check  = $this->promo_model->selectAll_tcA('locate_promo_business_unit', array('bunit_id !=' => $input['bunit_id']));
            foreach ($check as $row) {
                if ($row['business_unit'] == strtoupper($input['business_unit'])) {
                    $report = 'business_unit';
                    $exist = true;
                }
                if ($row['bunit_name'] == strtoupper($input['bunit_name'])) {
                    $report = 'bunit_name';
                    $exist = true;
                }
                if ($row['bunit_acronym'] == strtoupper($input['bunit_acronym'])) {
                    $report = 'bunit_acronym';
                    $exist = true;
                }
            }

            if (!$exist) {
                $data = array(
                    'business_unit' => strtoupper($input['business_unit']),
                    'bunit_name'    => strtoupper($input['bunit_name']),
                    'bunit_acronym' => strtoupper($input['bunit_acronym'])
                );
                $c = array('bunit_id' => $input['bunit_id']);
                $this->promo_model->update_twdA('locate_promo_business_unit', $c, $data);
                $report = 'updated';
            }
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success', 'report' => $report]);
        }
    }
}
