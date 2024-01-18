<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Promo_model extends CI_Model
{
    public $db;
    public $tk;
    // public $tk_tal;
    // public $tk_tub;
    public $usertype;
    public $systemUser;
    public $today;
    public $emp3Fields;
    function __construct()
    {
        parent::__construct();
        $this->db           = $this->load->database('default', TRUE);
        $this->tk           = $this->load->database('timekeeping', TRUE);
        // $this->tk_tal       = $this->load->database('talibon', TRUE);
        // $this->tk_tub       = $this->load->database('tubigon', TRUE);
        $this->systemUser   = $this->session->userdata('emp_id');
        $this->usertype     = $this->get_usertype();
        $this->today        = date('Y-m-d');
        $this->emp3Fields   = array(
            'record_no', 'tag_as', 'date_added', 'added_by', 'Details', 'DepCode',
            'Branch', 'Branchcode', 'tag_request', 'sub_status'
        );
    }

    public function get_usertype()
    {
        $query = $this->db->select('usertype')
            ->get_where('promo_user', array('emp_id' => $this->systemUser, 'user_status' => 'active'));
        return $query->row_array()['usertype'];
    }

    public function user_info($emp_id)
    {
        $query = $this->db->select('*')
            ->from('applicant')
            ->join('employee3', 'applicant.app_id = employee3.emp_id')
            ->where('employee3.emp_id', $emp_id)
            ->get();
        return $query->row_array();
    }

    public function promo_menu()
    {
        $this->db->select('*')
            ->order_by('menu_name');
        $query = $this->db->get_where('promoV2_menu', array($this->usertype => 'yes', 'menu_status' => 'active'));
        return $query->result_array();
    }

    public function promo_submenu($menu_id)
    {
        $query = $this->db->get_where('promoV2_submenu', array('menu_id' => $menu_id, $this->usertype => 'yes', 'submenu_status' => 'active'));
        return $query->result_array();
    }

    public function locate_promo_bu($hrd_location = null)
    {
        $this->db->select('*')
            ->from('locate_promo_business_unit');
        if ($hrd_location !== null) {
            $this->db->where('hrd_location', $hrd_location);
        }
        $this->db->where('status', 'active');
        $this->db->order_by('bunit_name');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function empStores($table, $emp_id, $record_no, $field)
    {
        $query = $this->db->select('COUNT(promo_id) AS num')
            ->get_where($table, array('emp_id' => $emp_id, 'record_no' => $record_no, $field => 'T'));
        return $query->row_array()['num'];
    }

    public function selectAll($table)
    {
        $query = $this->db->get_where($table);
        return $query->result_array();
    }

    public function selectAll_tk($table)
    {
        $query = $this->tk->get_where($table);
        return $query->result_array();
    }

    public function selectAll_tcA($table, $array)
    {
        $query = $this->db->get_where($table, $array);
        return $query->result_array();
    }

    public function selectAll_tcR($table, $array)
    {
        $query = $this->db->get_where($table, $array);
        return $query->row_array();
    }

    public function selectAll_tcA_tk($table, $array)
    {
        $query = $this->tk->get_where($table, $array);
        return $query->result_array();
    }

    public function selectAll_tcR_tk($table, $array)
    {
        $query = $this->tk->get_where($table, $array);
        return $query->row_array();
    }

    public function update_twdA($table, $where, $data)
    {
        $this->db->where($where)
            ->update($table, $data);
    }

    public function update_twdA_tk($table, $where, $data)
    {
        $this->tk->where($where)
            ->update($table, $data);
    }

    public function insert_tdA($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function insert_tdA_tk($table, $data)
    {
        $this->tk->insert($table, $data);
    }

    public function delete_tcr_row($table, $array)
    {
        $this->db->delete($table, $array);
    }

    public function delete_tc_tk($table, $array)
    {
        $this->tk->delete($table, $array);
    }

    public function join_ttjcoA($select, $table1, $table2, $join, $left = null, $condition = null, $order = null, $limit = null)
    {
        $this->db->select($select)
            ->from($table1 . ' as t1');
        if ($left !== null) {
            $this->db->join($table2 . ' as t2', $join, $left);
        } else {
            $this->db->join($table2 . ' as t2', $join);
        }
        if ($condition !== null) {
            $this->db->where($condition);
        }
        if ($order !== null) {
            list($orderColumn, $orderDirection) = explode('|', $order);
            $this->db->order_by($orderColumn, $orderDirection);
        }
        if ($limit !== null) {
            $this->db->limit($limit);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function whereIN_stcd($distinct, $selected, $table, $column, $data)
    {
        if (!empty($distinct)) {
            $this->db->distinct();
        }
        $this->db->select($selected)
            ->from($table)
            ->where_in($column, $data);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectDistinct($selected, $table, $condition)
    {
        $this->db->distinct()
            ->select($selected)
            ->from($table)
            ->where($condition);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchKey_dstd($distinct = null, $selected, $table, $data)
    {
        if ($distinct !== null) {
            $this->db->distinct();
        }
        $this->db->select($selected)
            ->from($table)
            ->like($data, 'both')
            ->order_by($selected)
            ->limit(10);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function positions()
    {
        $poslevel_no = ['458', '459', '522', '523', '525', '599', '524'];
        $this->db->select('*')
            ->from('position_leveling')
            ->order_by('position_title');
        foreach ($poslevel_no as $value) {
            $this->db->or_where('poslevel_no', $value);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function addressBoholCebu()
    {
        $query = $this->db->select('brgy_name, town_name, prov_name')
            ->from('barangay')
            ->join('town', 'barangay.town_id = town.town_id')
            ->join('province', 'town.prov_id = province.prov_id')
            ->get();
        return $query->result_array();
    }

    public function masterfile($input)
    {
        $this->db->select('*')
            ->from('employee3 as e')
            ->join('promo_record as p', 'p.emp_id = e.emp_id and p.record_no = e.record_no')
            ->where('current_status', 'Active')
            ->where('hr_location', 'asc');
        if (isset($input['store'])) {
            $split = explode('|', $input['store']);
            $this->db->where($split[1], 'T');
        }
        if (isset($input['promo_department']) && $input['promo_department'] != '') {
            $this->db->where('promo_department', $input['promo_department']);
        }
        if (isset($input['promo_type']) && $input['promo_type'] != '') {
            $this->db->where('promo_type', $input['promo_type']);
        }
        if (isset($input['promo_company']) && $input['promo_company'] != '') {
            $this->db->where('promo_company', $input['promo_company']);
        }
        $this->db->where_in('emp_type', array('Promo', 'Promo-NESCO', 'Promo-EasyL'))
            ->order_by('name');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function active_promoStore($field)
    {
        $query = $this->db->select('count(e.record_no) as num')
            ->from('employee3 as e')
            ->join('promo_record as p', 'p.emp_id = e.emp_id and p.record_no = e.record_no')
            ->where('current_status', 'Active')
            ->where('hr_location', 'asc')
            ->where_in('emp_type', array('Promo', 'Promo-NESCO'))
            ->where($field, 'T')
            ->get();
        return $query->row_array();
    }

    public function activeTotal()
    {
        $query = $this->db->select('*')
            ->from('employee3 as e')
            ->join('promo_record as p', 'p.emp_id = e.emp_id and p.record_no = e.record_no')
            ->where('current_status', 'Active')
            ->where('hr_location', 'asc')
            ->where_in('emp_type', array('Promo', 'Promo-NESCO'))
            ->get();
        return $query->result_array();
    }

    public function newPromo()
    {
        $query = $this->db->select('*')
            ->from('employee3 as e')
            ->join('promo_record as p', 'p.emp_id = e.emp_id AND p.record_no = e.record_no')
            ->where('startdate >= DATE_SUB(NOW(), INTERVAL 1 MONTH)')
            ->where('startdate <= NOW()')
            ->where('current_status', 'Active')
            ->where('hr_location', 'asc')
            ->where('tag_as', 'new')
            ->where_in('emp_type', array('Promo', 'Promo-NESCO'))
            ->get();
        return $query->result_array();
    }

    public function failedEpas()
    {
        $this->db->select('*')
            ->from('employee3 as e')
            ->join('promo_record as p', 'p.emp_id=e.emp_id and p.record_no=e.record_no')
            ->join('appraisal_details as a', 'a.emp_id=e.emp_id and a.record_no=e.record_no')
            ->where('numrate <', 85)
            ->where('hr_location', 'asc')
            ->where_in('emp_type', array('Promo', 'Promo-NESCO'))
            ->where_in('current_status', array('Active', 'End of Contract'))
            ->where('eocdate <=', date('Y-m-d', strtotime('+5 month')));
        $query = $this->db->get();
        return $query->result_array();
    }

    public function eocToday()
    {
        $query = $this->db->select('*')
            ->from('employee3 as e')
            ->join('promo_record as p', 'p.emp_id = e.emp_id AND p.record_no = e.record_no')
            ->where('eocdate', $this->today)
            ->where('hr_location', 'asc')
            ->where_in('emp_type', array('Promo', 'Promo-NESCO'))
            ->get();
        return $query->result_array();
    }

    public function getName_employee3($emp_id)
    {
        $query = $this->db->select('name')
            ->from('employee3')
            ->where('emp_id', $emp_id)
            ->get();
        return $query->row_array()['name'];
    }

    public function birthdayToday($month_day)
    {
        $this->db->select('*')
            ->from('employee3 AS e')
            ->join('promo_record AS p', 'p.emp_id=e.emp_id AND p.record_no=e.record_no')
            ->join('applicant as a', 'a.app_id = e.emp_id')
            ->where_in('e.emp_type', array('Promo', 'Promo-NESCO'))
            ->where('hr_location', 'asc')
            ->where('current_status', 'Active')
            ->like('birthdate', $month_day, 'before');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchPromo($key)
    {
        $this->db->select('*')
            ->from('employee3 AS e')
            ->join('promo_record AS p', 'p.emp_id=e.emp_id AND p.record_no=e.record_no')
            ->join('applicant as a', 'a.app_id = e.emp_id')
            ->where('hr_location', 'asc')
            ->where_in('e.emp_type', array('Promo', 'Promo-NESCO'))
            ->group_start()
            ->like('e.name', $key, 'both')
            ->or_like('e.emp_id', $key, 'both')
            ->group_end();
        $query = $this->db->get();
        return $query->result_array();
    }

    public function generateQbe($input)
    {
        $this->db->select('*')
            ->from('employee3 AS e')
            ->join('promo_record AS p', 'p.emp_id=e.emp_id AND p.record_no=e.record_no')
            ->join('applicant as a', 'a.app_id = e.emp_id');
        if (isset($input['name']) && $input['name'] != '') {
            $this->db->group_start()
                ->like('firstname', $input['name'], 'both')
                ->or_like('lastname', $input['name'], 'both')
                ->or_like('name', $input['name'], 'both')
                ->group_end();
        }
        if (isset($input['home_address']) && $input['home_address'] != '') {
            $this->db->group_start()
                ->like('home_address', $input['home_address'], 'both')
                ->group_end();
        }
        if (isset($input['gender']) && $input['gender'] != '') {
            $this->db->where('gender', $input['gender']);
        }
        if (isset($input['religion']) && $input['religion'] != '') {
            $this->db->where('religion', $input['religion']);
        }
        if (isset($input['civilstatus']) && $input['civilstatus'] != '') {
            $this->db->where('civilstatus', $input['civilstatus']);
        }
        if (isset($input['school']) && $input['school'] != '') {
            $this->db->where('school', $input['school']);
        }
        if (isset($input['attainment']) && $input['attainment'] != '') {
            $this->db->where('attainment', $input['attainment']);
        }
        if (isset($input['course']) && $input['course'] != '') {
            $this->db->where('course', $input['course']);
        }
        if (isset($input['height']) && $input['height'] != '') {
            $this->db->where('height', $input['height']);
        }
        if (isset($input['weight']) && $input['weight'] != '') {
            $this->db->where('weight', $input['weight']);
        }
        if (isset($input['bloodtype']) && $input['bloodtype'] != '') {
            $this->db->where('bloodtype', $input['bloodtype']);
        }
        if (isset($input['position']) && $input['position'] != '') {
            $this->db->where('position', $input['position']);
        }

        if (isset($input['agency_code']) && $input['agency_code'] != '') {
            $this->db->where('agency_code', $input['agency_code']);
        }
        if (isset($input['promo_company']) && $input['promo_company'] != '') {
            $this->db->where('promo_company', $input['promo_company']);
        }
        if (isset($input['store']) && $input['store'] != '') {
            $field = explode('|', $input['store']);
            $this->db->where($field[1], 'T');
        }
        if (isset($input['promo_department']) && $input['promo_department'] != '') {
            $this->db->where('promo_department', $input['promo_department']);
        }
        if (isset($input['promo_type']) && $input['promo_type'] != '') {
            $this->db->where('promo_type', $input['promo_type']);
        }
        if (isset($input['emp_type']) && $input['emp_type'] != '') {
            $this->db->where('emp_type', $input['emp_type']);
        }
        if (isset($input['current_status']) && $input['current_status'] != '') {
            $this->db->where('current_status', $input['current_status']);
        }

        $this->db->where('hr_location', 'asc');
        $this->db->order_by('name');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get201_file($input, $emp_id, $page = null, $others = null)
    {
        $this->db->select('*')
            ->from($input['tableName']);
        if ($others !== null) {
            $this->db->where('requirement_name !=', 'Regularization')
                ->where('requirement_name !=', 'Job Transfers');
        } else {
            $this->db->where('requirement_name', $input['requirementName']);
        }
        $this->db->where($input['empField'], $emp_id);
        if ($page !== null) {
            $this->db->limit(1, $page);
        }
        $query = $this->db->get();
        return ($page !== null) ? $query->row_array() : $query->result_array();
    }

    public function get201_resignation($emp_id, $page = null)
    {
        $this->db->select('*')
            ->from('secure_clearance_promo as scp')
            ->join('secure_clearance_promo_details as scpd', 'scp.scpr_id=scpd.scpr_id')
            ->where('scp.emp_id', $emp_id)
            ->group_start()
            ->where('reason', 'V-Resigned')
            ->or_where('reason', 'Ad-Resigned')
            ->group_end();
        if ($page !== null) {
            $this->db->limit(1, $page);
        }
        $query = $this->db->get();
        return ($page !== null) ? $query->row_array() : $query->result_array();
    }

    public function addBl($input)
    {
        $query = $this->db->select('*')
            ->from('applicant as a')
            ->join('employee3 AS e', 'e.emp_id=a.app_id', 'left')
            ->where('app_id', $input['emp_id'])
            ->get();
        return $query->row_array();
    }

    public function reportedbyBl($input)
    {
        $query = $this->db->select('employee3.emp_id, name')
            ->from('employee3')
            ->join('users', 'employee3.emp_id = users.emp_id')
            ->group_start()
            ->where('usertype', 'administrator')
            ->or_where('usertype', 'placement1')
            ->or_where('usertype', 'placement2')
            ->or_where('usertype', 'placement3')
            ->or_where('usertype', 'placement4')
            ->or_where('usertype', 'nesco')
            ->or_where('usertype', 'franchise')
            ->group_end()
            ->group_start()
            ->like('name', $input['str'], 'both')
            ->or_like('employee3.emp_id', $input['str'])
            ->group_end()
            ->group_by('users.emp_id')
            ->order_by('name', 'ASC')
            ->limit(10)
            ->get();
        return $query->result_array();
    }

    public function clearanceList()
    {
        $query = $this->db->select('*, s.date_added as dateAdded')
            ->from('secure_clearance_promo as s')
            ->join('employee3 as e', 'e.emp_id=s.emp_id')
            ->join('promo_record as p', 'p.emp_id=s.emp_id')
            ->where('hr_location', 'asc')
            ->order_by('scpr_id', 'DESC')
            ->get();
        return $query->result_array();
    }

    public function clearanceHistory($emp_id)
    {
        $query = $this->db->select('*')
            ->from('secure_clearance_promo as scp')
            ->join('secure_clearance_promo_details as scpd', 'scp.scpr_id=scpd.scpr_id')
            ->where('scp.emp_id', $emp_id)
            ->get();
        return $query->result_array();
    }

    public function promoDetails_wttA($condition, $empTable, $recordTable)
    {
        $query = $this->db->select('*')
            ->from($empTable . ' as e')
            ->join($recordTable . ' as p', 'p.emp_id=e.emp_id and p.record_no=e.record_no')
            ->where($condition)
            ->order_by('startdate', 'DESC')
            ->get();
        return $query->result_array();
    }

    public function promoDetails_wttR($condition, $empTable, $recordTable)
    {
        $query = $this->db->select('*')
            ->from($empTable . ' as e')
            ->join($recordTable . ' as p', 'p.emp_id=e.emp_id and p.record_no=e.record_no')
            ->where($condition)
            ->get();
        return $query->row_array();
    }

    public function rpClearance($input)
    {
        $query = $this->db->select('*')
            ->from('secure_clearance_promo as s')
            ->join('employee3 as e', 'e.emp_id=s.emp_id')
            ->join('promo_record as p', 'p.emp_id=s.emp_id')
            ->where('hr_location', 'asc')
            ->where('status', 'Pending')
            ->group_start()
            ->like('name', $input['str'], 'both')
            ->or_like('e.emp_id', $input['str'])
            ->group_end()
            ->order_by('name')
            ->limit(10)
            ->get();
        return $query->result_array();
    }

    public function sClearance($input)
    {
        $query = $this->db->select('*')
            ->from('employee3 as e')
            ->join('promo_record as p', 'p.emp_id=e.emp_id and p.record_no=e.record_no')
            ->where('hr_location', 'asc')
            ->where('sub_status !=', 'Cleared')
            ->where('current_status !=', 'blacklisted')
            ->group_start()
            ->like('name', $input['str'], 'both')
            ->or_like('e.emp_id', $input['str'])
            ->group_end()
            ->order_by('name')
            ->limit(10)
            ->get();
        return $query->result_array();
    }

    public function uClearance($input)
    {
        $query = $this->db->select('*')
            ->from('secure_clearance_promo as s')
            ->join('employee3 as e', 'e.emp_id=s.emp_id')
            ->join('promo_record as p', 'p.emp_id=s.emp_id')
            ->where('hr_location', 'asc')
            ->where('status', 'Pending')
            ->group_start()
            ->like('name', $input['str'], 'both')
            ->or_like('e.emp_id', $input['str'])
            ->group_end()
            ->order_by('name')
            ->limit(10)
            ->get();
        return $query->result_array();
    }

    public function searchName($string, $condition)
    {
        $query = $this->db->select('*')
            ->from('employee3 as e')
            ->join('promo_record as p', 'p.emp_id=e.emp_id and p.record_no=e.record_no')
            ->where($condition)
            ->group_start()
            ->like('name', $string, 'both')
            ->or_like('e.emp_id', $string)
            ->group_end()
            ->order_by('name')
            ->limit(10)
            ->get();
        return $query->result_array();
    }

    public function supervisorList($input)
    {
        $list = $this->selectAll_tcA('leveling_subordinates', array('subordinates_rater' => $input['emp_id']));
        $this->db->distinct()
            ->select('name, e.*')
            ->from('employee3 as e')
            ->join('leveling_subordinates as l', 'e.emp_id=l.ratee')
            ->where('current_status', 'Active');
        foreach ($list as $value) {
            $this->db->where('l.ratee !=', $value['ratee']);
        }
        if (isset($input['cc'])) {
            $this->db->where('e.company_code', $input['cc']);
        }
        if (isset($input['bc'])) {
            $this->db->where('e.bunit_code', $input['bc']);
        }
        if (isset($input['dc'])) {
            $this->db->where('e.dept_code', $input['dc']);
        }
        if (isset($input['sc'])) {
            $this->db->where('e.section_code', $input['sc']);
        }
        if (isset($input['ssc'])) {
            $this->db->where('e.sub_section_code', $input['ssc']);
        }
        if (isset($input['uc'])) {
            $this->db->where('e.unit_code', $input['uc']);
        }
        $this->db->order_by('e.name');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function eocApp_c($input, $condition = null)
    {
        $this->db->select('*')
            ->from('employee3 as e')
            ->join('promo_record as p', 'p.emp_id=e.emp_id and p.record_no=e.record_no')
            ->join('appraisal_details as a', 'a.emp_id=e.emp_id and a.record_no=e.record_no')
            ->where('e.emp_id', $input['emp_id']);
        if ($condition !== null) {
            $this->db->where($condition);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function eocApp_p_chk($emp_id, $record_no, $condition)
    {
        $this->db->select('promo_id')
            ->from('promo_history_record')
            ->where('emp_id', $emp_id)
            ->where('record_no', $record_no);
        if (!empty($condition)) {
            $this->db->where($condition);
        }
        $query = $this->db->get();
        return $query->row_array();
    }

    public function promoUserAccounts($selected, $condition)
    {
        $this->db->select($selected)
            ->from('employee3 as e')
            ->join('promo_record as p', 'p.emp_id=e.emp_id and p.record_no=e.record_no')
            ->join('users as u', 'u.emp_id=e.emp_id');
        if ($condition !== null) {
            $this->db->where($condition);
        }
        $this->db->order_by('name');
        $query = $this->db->get();
        return $query->result_array();
    }

    // **************** datatables serverside ****************
    public function eocList()
    {
        $monthplus = date('Y-m-d', strtotime('+3 month'));
        $monthminus = date('Y-m-d', strtotime('-3 month'));
        $this->db->select('*')
            ->from('employee3 as e')
            ->join('promo_record as p', 'p.emp_id=e.emp_id and p.record_no=e.record_no')
            ->where_in('emp_type', array('Promo', 'Promo-NESCO', 'Promo-EasyL'))
            ->where('hr_location', 'asc')
            ->where('current_status !=', 'blacklisted')
            ->where('eocdate <=', $monthplus)
            ->where('eocdate >=', $monthminus);
    }

    public function getBlacklist()
    {
        $this->db->select('b.*')
            ->from('blacklist as b')
            ->join('employee3 as e', 'e.emp_id = b.app_id', 'left');
    }

    public function checkBlt($input)
    {
        $mn = '';
        if (!empty($input['mn'])) {
            $mn = ' ' . $input['mn'];
        }
        $name1 = $input['ln'] . ' ' . $input['fn'] . $mn;
        $name2 = $input['ln'] . ', ' . $input['fn'] . $mn;
        $name3 = $input['ln'] . ',' . $input['fn'] . $mn;
        $this->db->select('*')
            ->from('blacklist')
            ->group_start()
            ->or_like('name', $name1)
            ->or_like('name', $name2)
            ->or_like('name', $name3)
            ->or_like('name', $input['ln'])
            ->group_end();
    }

    public function checkBl($input)
    {

        $this->db->select('*')
            ->from('applicant as a')
            ->join('employee3 as e', 'e.emp_id = a.app_id', 'left')
            ->where('lastname', $input['ln'])
            ->like('firstname', $input['fn'], 'both');
        if (!empty($input['mn'])) {
            $this->db->like('middlename', $input['mn'], 'both');
        }
    }

    public function make_datatables($functionName, $order, $var, $input = null)
    {
        $this->$functionName($input);
        $this->filter_data($order, $var);
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    public function filter_data($order, $var)
    {
        if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
            $this->db->like($var, $_POST['search']['value']);
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by($var, 'ASC');
        }
    }
    public function get_filtered_data($functionName, $order, $var, $input = null)
    {
        $this->$functionName($input);
        $this->filter_data($order, $var);
        return $this->db->count_all_results();
    }
    public function get_all_data($functionName, $input = null)
    {
        $this->$functionName($input);
        return $this->db->count_all_results();
    }
    // **************** datatables serverside ****************

    public function secureClearance($input)
    {
        $scpr_id                = $input['scpr_id'];
        $current_status         = $input['reason'];
        $substatus              = 'Uncleared';
        $dateforactiveresign    = '';
        $dateuncleared          = '';

        if ($input['reason'] != 'Deceased') {

            if ($this->today <= $input['resignDate']) {

                $current_status         = 'Active';
                $substatus              = ($input['reason'] == 'Termination') ? 'For End of Contract' : 'For Resignation';
                $dateforactiveresign    = $this->today;
                $dateuncleared          = '';
            } else {

                if ($input['reason'] == 'Termination') {
                    $current_status = 'End of Contract';
                } else if ($input['reason'] == 'V-Resigned') {
                    $current_status = 'V-Resigned';
                } else if ($input['reason'] == 'Ad-Resigned') {
                    $current_status = 'Ad-Resigned';
                }
                $substatus                 = 'Uncleared';
                $dateforactiveresign       = '';
                $dateuncleared             = $this->today;
            }
        }

        $data = array('current_status' => $current_status, 'sub_status' => $substatus);
        $this->update_twdA('employee3', array('emp_id' => $input['emp_id']), $data);

        if (!$input['pending']) {

            $scp_insert                 = [];
            $scp_insert['emp_id']       = $input['emp_id'];
            $scp_insert['promo_type']   = $input['promo_type'];
            $scp_insert['reason']       = $input['reason'];
            $scp_insert['date_added']   = date('Y-m-d H:i:s');
            $scp_insert['added_by']     = $this->systemUser;
            $scp_insert['status']       = 'Pending';
            $this->db->insert('secure_clearance_promo', $scp_insert);
            $scpr_id = $this->db->insert_id();

            if ($input['reason'] == 'Deceased') {

                $scd_insert = array(
                    'sc_id'                  => $scpr_id,
                    'emp_id'                 => $input['emp_id'],
                    'claimant'               => $input['claimant'],
                    'relation'               => $input['relationship'],
                    'dateofdeath'            => $input['resignDate'],
                    'causeofdeath'           => $input['deathCause'],
                    'authorization_letter'   => $input['authorization_path'],
                    'death_certificate'      => $input['resignation_path'],
                    'secure_clearance'       => '',
                    'date_secured_clearance' => $this->today,
                );
                $this->insert_tdA('secure_clearance_deceased', $scd_insert);
            }
        }

        $scpd_insert                            = [];
        $scpd_insert['scpr_id']                 = $scpr_id;
        $scpd_insert['record_no']               = $input['record_no'];
        $scpd_insert['emp_id']                  = $input['emp_id'];
        $scpd_insert['store']                   = $input['stores'];
        $scpd_insert['date_activefor_resign']   = $dateforactiveresign;
        $scpd_insert['date_secure']             = $this->today;
        $scpd_insert['date_effectivity']        = $input['resignDate'];
        $scpd_insert['date_uncleared']          = $dateuncleared;
        $scpd_insert['added_by']                = $this->systemUser;
        $scpd_insert['clearance_status']        = 'Pending';
        if (isset($input['resignation_path'])) {
            $scpd_insert['resignation_letter']      = $input['resignation_path'];
        }
        $this->db->insert('secure_clearance_promo_details', $scpd_insert);
        $scdetails_id = $this->db->insert_id();

        return $scdetails_id;
    }

    public function uploadClearance($input)
    {
        $scpd_update = array('clearance_status' => 'Completed', 'date_cleared' => $this->today);
        $scpd_where  = array('emp_id' => $input['emp_id'], 'store' => $input['stores'], 'scpr_id' => $input['scpr_id'], 'clearance_status' => 'Pending');
        $this->update_twdA('secure_clearance_promo_details', $scpd_where, $scpd_update);
        $lbpu = $this->selectAll_tcR('locate_promo_business_unit', array('bunit_name' => $input['stores']));

        if (count($lbpu) > 0) {
            $data = array($lbpu['bunit_clearance'] => $input['clearance_path']);
            $this->update_twdA('promo_record', array('emp_id' => $input['emp_id'], 'record_no' => $input['record_no']), $data);
        }

        $pending = $this->selectAll_tcA('secure_clearance_promo_details', array('scpr_id' => $input['scpr_id'], 'clearance_status' => 'Pending'));

        if (count($pending) == 0) {
            $scp_update = array('status' => 'Completed');
            $scp_where = array('emp_id' => $input['emp_id'], 'scpr_id' => $input['scpr_id'], 'status' => 'Pending');
            $this->update_twdA('secure_clearance_promo', $scp_where, $scp_update);
            $scp                = $this->selectAll_tcR('secure_clearance_promo', array('emp_id' => $input['emp_id'], 'scpr_id' => $input['scpr_id']));
            $current_status     = ($scp['reason'] == 'Termination') ? 'End of Contract' : $scp['reason'];
            $sub_status         = 'Cleared';
            $employee3_update   = array('current_status' => $current_status, 'sub_status' => $sub_status);
            $this->update_twdA('employee3', array('emp_id' => $input['emp_id'], 'record_no' => $input['record_no']), $employee3_update);
        }
    }

    public function save_editContract($input)
    {
        $empTable       = ($input['contract'] == 'current') ? 'employee3' : 'employmentrecord_';
        $recordTable    = ($input['contract'] == 'current') ? 'promo_record' : 'promo_history_record';
        $updatedby      = ($input['contract'] == 'current') ? 'updated_by' : 'updatedby';
        $statCut        = $this->selectAll_tcR_tk('promo_sched_emp', array('empId' => $input['emp_id'], 'recordNo' => $input['record_no']));
        $bUs            = $this->locate_promo_bu('asc');
        $stores         = [];
        foreach ($input['stores'] as $value) {
            $str        = explode('|', $value);
            $stores[]   = $str[1];
        }

        $update_emp = array(
            'startdate'     => $input['startdate'],
            'eocdate'       => $input['eocdate'],
            'emp_type'      => $input['emp_type'],
            'duration'      => $input['duration'],
            'position'      => $input['position'],
            'date_updated'  => $this->today,
            $updatedby      => $this->systemUser,
            'remarks'       => $input['remarks'],
        );

        $update_rec = [];
        $update_rec['agency_code']      = $input['agency_code'];
        $update_rec['promo_company']    = $input['promo_company'];
        $update_rec['promo_department'] = $input['promo_department'];
        $update_rec['vendor_code']      = $input['vendor_code'];
        $update_rec['promo_type']       = $input['promo_type'];
        $update_rec['type']             = $input['type'];
        foreach ($bUs as $bu) {
            $update_rec[$bu['bunit_field']] = in_array($bu['bunit_field'], $stores) ? 'T' : '';
        }

        $this->update_twdA($empTable, array('record_no' => $input['record_no'], 'emp_id' => $input['emp_id']), $update_emp);
        $this->update_twdA($recordTable, array('record_no' => $input['record_no'], 'emp_id' => $input['emp_id']), $update_rec);
        $this->db->delete('promo_products', array('emp_id' => $input['emp_id'], 'record_no' => $input['record_no']));
        if (isset($input['product'])) {
            $insert_product                 = [];
            $insert_product['emp_id']       = $input['emp_id'];
            $insert_product['record_no']    = $input['record_no'];
            $insert_product['created_at']   = date('Y-m-d H:i:s');
            foreach ($input['product'] as  $value) {
                $insert_product['product']  = $value;
            }
            $this->db->insert('promo_products', $insert_product);
        }

        $cutoffData = array(
            'recordNo'      => $input['record_no'],
            'empId'         => $input['emp_id'],
            'statCut'       => $input['statCut'],
            'date_setup'    => $this->today
        );
        $whereCutoff = array('empId' => $input['emp_id'], 'recordNo' => $input['record_no']);
        $chkServer  = [];
        foreach ($stores as $value) {
            if ($stores == 'al_tal') {
                $server = 'talibon';
            } else if ($stores == 'al_tub') {
                $server = 'tubigon';
            } else if ($stores == 'colm' || $stores == 'colc') {
                $server = 'colon';
            } else {
                $server = 'corporate';
            }
            if (!in_array($server, $chkServer)) {
                if ($statCut > 0) {
                    $this->promoCutoff_update($server, $whereCutoff, array('statCut' => $input['statCut']));
                } else {
                    $this->promoCutoff_insert($server, $cutoffData);
                }
                $chkServer[] = $server;
            }
        }
        if (!in_array('corporate', $chkServer)) {
            if ($statCut > 0) {
                $this->promoCutoff_update('corporate', $whereCutoff, array('statCut' => $input['statCut']));
            } else {
                $this->promoCutoff_insert('corporate', $cutoffData);
            }
        }
    }

    public function renewContract($input)
    {
        $whereER    = array('emp_id' => $input['emp_id'], 'record_no' => $input['record_no']);
        $emp3       = $this->selectAll_tcR('employee3', $whereER);
        $insert1    = [];
        foreach ($emp3 as $field => $value) {
            $fields = $this->emp3Fields;;
            if (!in_array($field, $fields)) {

                if ($field == 'name') {
                    $field = 'names';
                } else if ($field == 'position_desc') {
                    $field = 'pos_desc';
                } else if ($field == 'updated_by') {
                    $field = 'updatedby';
                }
                $insert1[$field] = $value;
            }
        }
        $insert1['current_status'] = 'End of Contract';
        $this->db->insert('employmentrecord_', $insert1);
        $record_no_ = $this->db->insert_id();

        $promoR     = $this->selectAll_tcR('promo_record', $whereER);
        $insert2    = [];
        foreach ($promoR as $field => $value) {
            $fields = array('promo_id');
            if (!in_array($field, $fields)) {

                if ($field == 'record_no') {
                    $insert2[$field] = $record_no_;
                } else {
                    $insert2[$field] = $value;
                }
            }
        }
        $this->db->insert('promo_history_record', $insert2);

        $insert3 = array(
            'emp_id'            => $input['emp_id'],
            'emp_no'            => $emp3['emp_no'],
            'emp_pins'          => $emp3['emp_pins'],
            'barcodeId'         => $emp3['barcodeId'],
            'bioMetricId'       => $emp3['bioMetricId'],
            'payroll_no'        => $emp3['payroll_no'],
            'name'              => $emp3['name'],
            'startdate'         => $input['startdate'],
            'eocdate'           => $input['eocdate'],
            'emp_type'          => $input['emp_type'],
            'position'          => $input['position'],
            'comments'          => $input['comments'],
            'remarks'           => $input['remarks'],
            'duration'          => $input['duration'],
            'current_status'    => 'Active',
            'date_added'        => $this->today,
            'added_by'          => $this->systemUser,

        );
        $this->db->insert('employee3', $insert3);
        $record_no = $this->db->insert_id();

        $insert4 = [];
        $insert4['emp_id']           = $input['emp_id'];
        $insert4['record_no']        = $record_no;
        $insert4['agency_code']      = $input['agency_code'];
        $insert4['promo_company']    = $input['promo_company'];
        $insert4['promo_department'] = $input['promo_department'];
        $insert4['vendor_code']      = $input['vendor_code'];
        $insert4['promo_type']       = $input['promo_type'];
        $insert4['type']             = $input['type'];
        $insert4['hr_location']      = 'asc';
        foreach ($input['stores'] as $value) {
            $split = explode('|', $value);
            $insert4[$split[1]] = 'T';
        }
        foreach ($input['intros'] as $field => $value) {
            $insert4[$field] = $value;
        }
        $this->db->insert('promo_record', $insert4);

        $this->db->delete('employee3', $whereER);
        $this->db->delete('promo_record', $whereER);

        if (isset($input['product'])) {
            $insert5                 = [];
            $insert5['emp_id']       = $input['emp_id'];
            $insert5['record_no']    = $record_no;
            $insert5['created_at']   = date('Y-m-d H:i:s');
            foreach ($input['product'] as  $value) {
                $insert5['product']  = $value;
            }
            $this->db->insert('promo_products', $insert5);
        }

        $update1 = array('rec_no' => $record_no, 'witness1' => $input['witness1'], 'witness2' => $input['witness2']);
        $this->update_twdA('employment_witness', array('rec_no' => $input['record_no'], 'emp_id' => $input['emp_id']), $update1);
        $this->update_twdA('appraisal_details', $whereER, array('record_no' => $record_no_));
        $this->update_twdA('promo_products', $whereER, array('record_no' => $record_no_));
        $this->update_twdA('secure_clearance_promo_details', $whereER, array('record_no' => $record_no_));

        $cutoffData = array(
            'recordNo'      => $record_no,
            'empId'         => $input['emp_id'],
            'statCut'       => $input['statCut'],
            'date_setup'    => $this->today
        );
        $whereCutoff = array('empId' => $input['emp_id'], 'recordNo' => $input['record_no']);
        $chkServer  = [];
        foreach ($input['stores'] as $value) {
            $store = explode('|', $value);
            if ($store[1] == 'al_tal') {
                $server = 'talibon';
            } else if ($store[1] == 'al_tub') {
                $server = 'tubigon';
            } else if ($store[1] == 'colm' || $store[1] == 'colc') {
                $server = 'colon';
            } else {
                $server = 'corporate';
            }
            if (!in_array($server, $chkServer)) {
                $this->promoCutoff_update($server, $whereCutoff, array('recordNo' => $record_no_));
                $this->promoCutoff_insert($server, $cutoffData);
                $chkServer[] = $server;
            }
        }
        if (!in_array('corporate', $chkServer)) {
            $this->promoCutoff_update('corporate', $whereCutoff, array('recordNo' => $record_no_));
            $this->promoCutoff_insert('corporate', $cutoffData);
        }

        $activity   = "Added a new Contract of Employment of " . $emp3['name'] . " Record No:" . $record_no;
        $logs       = array(
            'activity'  => $activity,
            'date'      => $this->today,
            'time'      => date("H:i:s"),
            'user'      => $this->systemUser,
            'username'  => $this->session->userdata('username'),
        );
        $this->db->insert('logs', $logs);

        return $record_no;
    }

    public function promoCutoff_update($server, $where, $data)
    {
        if ($server == 'talibon') {
            // $this->tk_tal->where($where)
            //     ->update('promo_sched_emp', $data);
        } else if ($server == 'tubigon') {
            // $this->tk_tub->where($where)
            //     ->update('promo_sched_emp', $data);
        } else if ($server == 'colon') {
        } else if ($server == 'corporate') {
            $this->update_twdA_tk('promo_sched_emp', $where, $data);
        }
    }

    public function promoCutoff_insert($server, $insert)
    {
        if ($server == 'talibon') {
            // $this->tk_tal->insert('promo_sched_emp', $insert);
        } else if ($server == 'tubigon') {
            // $this->tk_tub->insert('promo_sched_emp', $insert);
        } else if ($server == 'colon') {
        } else if ($server == 'corporate') {
            $this->tk->insert('promo_sched_emp', $insert);
        }
    }

    public function changeOutlet($input)
    {
        $condition  = array('emp_id' => $input['emp_id'], 'record_no' => $input['record_no']);
        $emp3       = $this->selectAll_tcR('employee3', $condition);
        $insert1    = [];
        foreach ($emp3 as $field => $value) {
            $fields = $this->emp3Fields;
            if (!in_array($field, $fields)) {

                if ($field == 'name') {
                    $field = 'names';
                } else if ($field == 'position_desc') {
                    $field = 'pos_desc';
                } else if ($field == 'updated_by') {
                    $field = 'updatedby';
                }
                $insert1[$field] = $value;
            }
        }
        $insert1['current_status'] = 'End of Contract';
        $this->db->insert('employmentrecord_', $insert1);
        $record_no_ = $this->db->insert_id();

        $promoR     = $this->selectAll_tcR('promo_record', $condition);
        $insert2    = [];
        foreach ($promoR as $field => $value) {
            $fields = array('promo_id');
            if (!in_array($field, $fields)) {

                if ($field == 'record_no') {
                    $insert2[$field] = $record_no_;
                } else {
                    $insert2[$field] = $value;
                }
            }
        }
        $this->db->insert('promo_history_record', $insert2);

        $i = 0;
        $store_field = [];
        $selected_stores = '';
        foreach ($input['stores'] as $value) {
            $i++;
            $split  = explode('|', $value);
            $bu     = $this->selectAll_tcR('locate_promo_business_unit', array('bunit_id' => $split[0]))['bunit_name'];
            $selected_stores    = ($i == 1) ? $bu : $selected_stores . ', ' . $bu;
            $store_field[]      = $split[1];
        }
        $i = 0;
        $transfer_field = [];
        $transfer_stores = '';
        if ($input['process'] == 'transferOutlet') {
            foreach ($input['transfer'] as $value) {
                $i++;
                $split  = explode('|', $value);
                $bu     = $this->selectAll_tcR('locate_promo_business_unit', array('bunit_id' => $split[0]))['bunit_name'];
                $transfer_stores    = ($i == 1) ? $bu : $transfer_stores . ', ' . $bu;
                $transfer_field[]   = $split[1];
            }
        }


        if ($input['process'] == 'addOutlet') {
            $remarks = 'Added Store(s): ' . $selected_stores;
        } else if ($input['process'] == 'removeOutlet') {
            $remarks = 'Removed Store(s): ' . $selected_stores;
        } else {
            $remarks = 'Transfer Store(s): From [' . $selected_stores . '] To [' . $transfer_stores . ']';
        }

        $insert3    = [];
        foreach ($emp3 as $field => $value) {
            $fields = array('record_no', 'sub_status');
            if (!in_array($field, $fields)) {

                if ($field == 'current_status') {
                    $insert3[$field] = 'Active';
                } else if ($field == 'startdate') {
                    $insert3[$field] = $input['startdate'];
                } else if ($field == 'duration') {
                    $insert3[$field] = $input['duration'];
                } else if ($field == 'remarks') {
                    $insert3[$field] = $remarks;
                } else if ($field == 'date_added') {
                    $insert3[$field] = $this->today;
                } else if ($field == 'added_by') {
                    $insert3[$field] = $this->systemUser;
                } else {
                    $insert3[$field] = $value;
                }
            }
        }
        $this->db->insert('employee3', $insert3);
        $record_no = $this->db->insert_id();

        $insert4 = [];
        foreach ($promoR as $field => $value) {
            $fields = array('promo_id');
            if (!in_array($field, $fields)) {

                if ($field == 'record_no') {
                    $insert4[$field] = $record_no;
                } else if ($field == 'emp_id') {
                    $insert4[$field] = $value;
                } else if ($field == 'agency_code') {
                    $insert4[$field] = $value;
                } else if ($field == 'promo_company') {
                    $insert4[$field] = $value;
                } else if ($field == 'promo_department') {
                    $insert4[$field] = $value;
                } else if ($field == 'vendor_code') {
                    $insert4[$field] = $value;
                } else if ($field == 'type') {
                    $insert4[$field] = $value;
                } else if ($field == 'hr_location') {
                    $insert4[$field] = $value;
                } else if ($field == 'promo_type') {
                    if ($input['process'] == 'addOutlet') {
                        $promo_type = 'ROVING';
                    } else {
                        $prev_stores = explode(', ', $input['previous_stores']);
                        $num = (count($prev_stores) - count($input['stores'])) + (($input['process'] == 'transferOutlet') ? count($input['transfer']) : 0);
                        $promo_type = ($num > 1) ? 'ROVING' : 'STATION';
                    }
                    $insert4[$field] = $promo_type;
                }
            }
        }

        $bUs = $this->locate_promo_bu('asc');
        foreach ($bUs as $bu) {
            $hasBu = $this->empStores('promo_record', $input['emp_id'], $input['record_no'], $bu['bunit_field']);
            if ($hasBu > 0) {
                $insert4[$bu['bunit_field']] = 'T';
            }
        }
        if ($input['process'] == 'transferOutlet') {
            foreach ($transfer_field as $value) {
                $insert4[$value] = 'T';
            }
        }
        foreach ($store_field as $value) {
            $insert4[$value] = ($input['process'] == 'addOutlet') ? 'T' : '';
        }
        $this->db->insert('promo_record', $insert4);

        $this->db->delete('employee3', $condition);
        $this->db->delete('promo_record', $condition);

        $update1 = array('rec_no' => $record_no);
        $this->update_twdA('employment_witness', array('rec_no' => $input['record_no'], 'emp_id' => $input['emp_id']), $update1);
        $this->update_twdA('promo_products', $condition, array('record_no' => $record_no_));

        if ($input['process'] == 'addOutlet') {
            $this->update_twdA('appraisal_details', $condition, array('record_no' => $record_no_));
        }

        $splitP = explode(', ', $input['previous_stores']);
        foreach ($splitP as $value) {
            $split = explode(', ', $selected_stores);
            if ($input['process'] == 'removeOutlet' || $input['process'] == 'transferOutlet') {
                $c      = array('emp_id' => $input['emp_id'], 'record_no' => $input['record_no'], 'store' => $value);
                $recNo  = (in_array($value, $split)) ?  $record_no_ : $record_no;
                $this->update_twdA('appraisal_details', $c, array('record_no' => $recNo));
                $this->update_twdA('secure_clearance_promo_details', $c, array('record_no' => $recNo));
            }
        }

        $whereCutoff    = array('empId' => $input['emp_id'], 'recordNo' => $input['record_no']);
        $cuttOff        = $this->selectAll_tcR_tk('promo_sched_emp', $whereCutoff);
        $cutoffData     = array(
            'recordNo'      => $record_no,
            'empId'         => $input['emp_id'],
            'statCut'       => $cuttOff['statCut'],
            'date_setup'    => $this->today
        );
        $chkServer  = [];
        $count      = 0;
        $changeto   = '';
        $bUs        = $this->locate_promo_bu('asc');
        foreach ($bUs as $bu) {
            $hasBu = $this->empStores('promo_record', $input['emp_id'], $record_no, $bu['bunit_field']);
            if ($hasBu > 0) {
                $count++;
                $changeto = ($count == 1) ? $bu['bunit_name'] : $changeto . ', ' . $bu['bunit_name'];
                if ($bu['bunit_field'] == 'al_tal') {
                    $server = 'talibon';
                } else if ($bu['bunit_field'] == 'al_tub') {
                    $server = 'tubigon';
                } else if ($bu['bunit_field'] == 'colm' || $bu['bunit_field'] == 'colc') {
                    $server = 'colon';
                } else {
                    $server = 'corporate';
                }
                if (!in_array($server, $chkServer)) {
                    $this->promoCutoff_update($server, $whereCutoff, array('recordNo' => $record_no_));
                    $this->promoCutoff_insert($server, $cutoffData);
                    $chkServer[] = $server;
                }
            }
        }
        if (!in_array('corporate', $chkServer)) {
            $this->promoCutoff_update('corporate', $whereCutoff, array('recordNo' => $record_no_));
            $this->promoCutoff_insert('corporate', $cutoffData);
        }

        $hist = array(
            'emp_id'        => $input['emp_id'],
            'changefrom'    => $input['previous_stores'],
            'changeto'      => $changeto,
            'effectiveon'   => $input['startdate'],
        );
        $this->db->insert('change_outlet_record', $hist);

        return $record_no;
    }

    public function months()
    {
        return array(
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        );
    }

    public function field_names()
    {
        return array(
            "payroll_no"                =>    "Payroll No",
            "emp_no"                    =>    "Employee No",
            "emp_pins"                  =>    "Employee PINS",
            "name"                      =>    "Full Name",
            "lastname"                  =>    "Last Name",
            "firstname"                 =>    "First Name",
            "middlename"                =>    "Middle Name",
            "suffix"                    =>    "Suffix",
            "birthdate"                 =>    "Birthday/Age",
            "home_address"              =>    "Home Address",
            "city_address"              =>    "City Address",
            "religion"                  =>    "Religion",
            "civilstatus"               =>    "Civil Status",
            "spouse"                    =>    "Spouse",
            "noofsiblings"              =>    "No of Siblings",
            "siblingOrder"              =>    "Sibling Order",
            "gender"                    =>    "Gender",
            "school"                    =>    "School",
            "attainment"                =>    "Attainment",
            "course"                    =>    "Course",
            "contactno"                 =>    "Contact No",
            "telno"                     =>    "Tel No",
            "email"                     =>    "Email",
            "facebookAcct"              =>    "Facebook Acct",
            "twitterAcct"               =>    "Twitter Acct",
            "citizenship"               =>    "Citizenship",
            "bloodtype"                 =>    "Bloodtype",
            "weight"                    =>    "Weight",
            "height"                    =>    "Height",
            "mother"                    =>    "Mother",
            "father"                    =>    "Father",
            "mother_bdate"              =>    "Mother Birthday",
            "father_bdate"              =>    "Father Birthday",
            "spouse_bdate"              =>    "Spouse Birthday",
            "contact_person"            =>    "Contact Person",
            "contact_person_address"    =>    "Contact Person Address",
            "contact_person_number"     =>    "Contact Person Number",
            "guardian"                  =>    "Guardian",
            "hobbies"                   =>    "Hobbies",
            "specialSkills"             =>    "Special Skills"
        );
    }

    public function field_employee()
    {
        return array(
            "startdate"         => "Startdate",
            "eocdate"           => "EOCDate",
            "emp_type"          => "Employee Type",
            "reg_class"         => "Regular Type",
            "current_status"    => "CurrentStatus",
            "job_cat"           => "JobCategory",
            "emp_cat"           => "EmpCategory",
            "poslevel"          => "PositionLevel",
            "position"          => "Position",
            "lodging"           => "Lodging",
            "comments"          => "Comments",
            "remarks"           => "Remarks",
            "pcc"               => "PCC",
            "aeregular"         => "Recommended By",
            "username"          => "Username",
            "date_brief"        => "Date Briefed",
            "date_hired"        => "Date Hired/YearsInService"
        );
    }

    public function field_company()
    {
        return array(
            'promo_company'       => "Company",
            'promo_department'    => "Department",
            'bUs'                 => "Store(s)"
        );
    }

    public function field_benefits()
    {
        return array(
            "cedula_no"         => "Cedula No",
            "sss_no"            => "SSS No",
            "pagibig_tracking"  => "Pagibig RTN",
            "pagibig"           => "Pagibig MID No",
            "philhealth"        => "Philhealth",
            "tin_no"            => "TIN No"
        );
    }
}
