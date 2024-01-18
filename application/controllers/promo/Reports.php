<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends CI_Controller
{
    public $today;
    public $systemUser;
    public $adminUser;
    function __construct()
    {
        parent::__construct();
        $this->load->library('ppdf');
        $this->adminUser    = array('06359-2013', '01186-2023');
        $this->today        = date('Y-m-d');
        $this->systemUser   = $this->session->userdata('emp_id');
        $empId = $this->session->userdata('emp_id');
        if (!isset($empId)) {

            redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrmsv2/employee');
        }
    }

    public function generateQbe()
    {
        $data['input'] = $this->input->post(NULL, TRUE);
        $data['systemUser'] = $this->systemUser;
        $data['request'] = 'generateQbe';

        $this->load->view('promo/page/page_response', $data);
    }

    public function generatePromoStat()
    {
        $data['input'] = $this->input->post(NULL, TRUE);
        $data['systemUser'] = $this->systemUser;
        $data['request'] = 'generatePromoStat';

        $this->load->view('promo/page/page_response', $data);
    }

    public function generateMonthlyStat()
    {
        $data['input'] = $this->input->post(NULL, TRUE);
        $data['systemUser'] = $this->systemUser;
        $data['request'] = 'generateMonthlyStat';

        $this->load->view('promo/page/page_response', $data);
    }

    public function generateAnnualStat()
    {
        $data['input'] = $this->input->post(NULL, TRUE);
        $data['systemUser'] = $this->systemUser;
        $data['request'] = 'generateAnnualStat';

        $this->load->view('promo/page/page_response', $data);
    }

    public function generateDueContractsExcel()
    {
        $data['input'] = $this->input->post(NULL, TRUE);
        $data['systemUser'] = $this->systemUser;
        $data['request'] = 'generateDueContractsExcel';

        $this->load->view('promo/page/page_response', $data);
    }

    public function generateDutySched()
    {
        $data['input'] = $this->input->post(NULL, TRUE);
        $data['systemUser'] = $this->systemUser;
        $data['request'] = 'generateDutySched';

        $this->load->view('promo/page/page_response', $data);
    }

    public function dutySchedList()
    {
        $data['input'] = $this->input->post(NULL, TRUE);
        $data['systemUser'] = $this->systemUser;
        $data['request'] = 'dutySchedList';

        $this->load->view('promo/page/page_response', $data);
    }

    public function dutySchedListData()
    {
        $input = $this->input->post(NULL, TRUE);

        $where = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
        if (!empty($input['store'])) {

            $bunit = explode('|', $input['store']);
            $where .= " AND $bunit[1]= 'T'";
        }
        if (!empty($input['promo_department'])) {
            $where .= " AND promo_department = '$input[promo_department]'";
        } else {

            $dept = $this->promo_model->whereIN_stcd('distinct', 'dept_name', 'locate_promo_department', 'bunit_id', $bunit[0]);
            $where .= ' AND (';
            $i = 0;
            foreach ($dept as $key => $value) {
                $i++;
                $where .= ($i == 1) ? "promo_department = '$value[dept_name]'" : " OR promo_department = '$value[dept_name]'";
            }
            $where .= ')';
        }
        if (!empty($input['promo_company'])) {
            $where .= " AND promo_company = '$input[promo_company]'";
        }
        if (!empty($input['current_status'])) {
            $where .= " AND current_status = '$input[current_status]'";
        }
        $data = [];
        $sched = $this->promo_model->selectAll_tcR('locate_promo_business_unit', array('bunit_id' => $bunit[0]));
        $no = 0;
        $table1 = 'employee3';
        $table2 = 'promo_record';
        $join = 't1.emp_id=t2.emp_id AND t1.record_no=t2.record_no';
        $order = 'name|ASC';
        $query = $this->promo_model->join_ttjcoA('*', $table1, $table2, $join, null, $where, $order, null);
        foreach ($query as $row) {
            $no++;
            $bUs = $this->promo_model->locate_promo_bu('asc');
            $i = 0;
            $stores = '';
            foreach ($bUs as $bu) {
                $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bu['bunit_field']);
                if ($hasBu > 0) {
                    $i++;
                    $stores = ($i == 1) ? $bu['bunit_acronym'] : $stores . ', ' . $bu['bunit_acronym'];
                }
            }
            if ($row[$sched['bunit_specialDays']] != '') {

                $dutySched = $row[$sched['bunit_dutySched']] . ' & ' . $row[$sched['bunit_specialSched']];
                $dutyDays = $row[$sched['bunit_dutyDays']] . ' & ' . $row[$sched['bunit_specialDays']];
            } else {

                $dutySched = $row[$sched['bunit_dutySched']];
                $dutyDays = $row[$sched['bunit_dutyDays']];
            }

            $sub_array = array();
            $sub_array[] = '<a class="text-truncate" href="' . base_url('promo/page/promo/profile/' . $row['emp_id']) . '" target="_blank">' .  ucwords(strtolower($row['name'])) . '</a>';
            $sub_array[] = strtoupper($dutyDays);
            $sub_array[] = $dutySched;
            $sub_array[] = $row['dayoff'];
            $sub_array[] = $row['cutoff'];
            $sub_array[] = $stores;
            $sub_array[] = $row['promo_department'];
            $sub_array[] = $row['promo_company'];
            $sub_array[] = $row['position'];
            $sub_array[] = $row['promo_type'];
            $sub_array[] = date('m/d/Y', strtotime($row['startdate'])) . " - " . date('m/d/Y', strtotime($row['eocdate']));
            $data[] = $sub_array;
        }
        echo json_encode(array("data" => $data));
    }

    public function generateTermRepExcel()
    {
        $data['input'] = $this->input->post(NULL, TRUE);
        $data['systemUser'] = $this->systemUser;
        $data['request'] = 'generateTermRepExcel';

        $this->load->view('promo/page/page_response', $data);
    }

    public function termRepList()
    {
        $data['input'] = $this->input->post(NULL, TRUE);
        $data['systemUser'] = $this->systemUser;
        $data['request'] = 'termRepList';

        $this->load->view('promo/page/page_response', $data);
    }

    public function promoStat()
    {
        $data['input'] = $this->input->post(NULL, TRUE);
        $data['systemUser'] = $this->systemUser;
        $data['request'] = 'promoStat';

        $this->load->view('promo/page/page_response', $data);
    }

    public function generateStatRep()
    {
        $data['input'] = $this->input->post(NULL, TRUE);
        $data['systemUser'] = $this->systemUser;
        $data['request'] = 'generateStatRep';

        $this->load->view('promo/page/page_response', $data);
    }

    public function newPromo()
    {

        $query = $this->promo_model->newPromo();
        $data = [];
        foreach ($query as $row) {

            $bUs = $this->promo_model->locate_promo_bu('asc');
            $i = 0;
            $stores = '';
            foreach ($bUs as $bu) {
                $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bu['bunit_field']);
                if ($hasBu > 0) {
                    $i++;
                    $stores = ($i == 1) ? $bu['bunit_acronym'] : $stores . ', ' . $bu['bunit_acronym'];
                }
            }

            $sub_array = array();
            $sub_array[] = ucwords(strtolower($row['name']));
            $sub_array[] = $row['position'];
            $sub_array[] = $row['emp_type'];
            $sub_array[] = $stores;
            $sub_array[] = $row['promo_department'];
            $sub_array[] = date('m/d/Y', strtotime($row['startdate']));
            $sub_array[] = date('m/d/Y', strtotime($row['eocdate']));
            $data[] = $sub_array;
        }
        echo json_encode(array("data" => $data));
    }

    public function failedEpas()
    {
        $query = $this->promo_model->failedEpas();
        $data = [];
        foreach ($query as $row) {

            $bUs = $this->promo_model->locate_promo_bu('asc');
            $stores = '';
            foreach ($bUs as $bu) {
                $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bu['bunit_field']);
                if ($hasBu > 0) {
                    if ($row['store'] == $bu['bunit_name']) {

                        $stores =   '<a href="javascript:;" id="' . $row['details_id'] . '" onclick="viewAppraisal(this.id)">
                                        <span class="badge bg-danger">' . $bu['bunit_name'] . ' | ' . $row['numrate'] . '</span>
                                    </a>';
                    }
                }
            }

            $supervisor = $this->promo_model->getName_employee3($row['rater']);

            $sub_array = array();
            $sub_array[] = ucwords(strtolower($row['name']));
            $sub_array[] = date('m/d/Y', strtotime($row['startdate']));
            $sub_array[] = date('m/d/Y', strtotime($row['eocdate']));
            $sub_array[] = $stores;
            $sub_array[] = ucwords(strtolower($supervisor));
            $data[] = $sub_array;
        }
        echo json_encode(array("data" => $data));
    }
}
