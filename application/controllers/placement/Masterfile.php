<?php

defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Manila');

/** NOTE:
 * NAME EACH FUNCTION AS TO ITS FUNCTIONALITY
 * IF IT SHOW A FORM, NAME IT LIKE => form_functionName()
 * IF IT LOADS A PAGE OR LIST OF TABLE, NAME IT LIKE => list_functionName()
 * IF IT LOADS WITH FILTERS, NAME IT LIKE => list_filter_functionName()
 * IF IT IS A MODAL, NAME IT LIKE => modal_functionName()
 * IF IT SAVE, NAME IT LIKE => insert_functionName()
 * IF IT UPDATES, NAME IT LIKE => update_functionName()
 * IF IT DELETES, NAME IT LIKE => delete_functionName()
 * IF IT RETURNS SOMETHING OR LIKE REUSABLE FUNCTION WITHIN THE CONTROLLER, get_functionName()
 *
 * DONT FORGET TO ADD COMMENTS!!!
**/

class Masterfile extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //$this->checkEmployeeLogin();
        $this->load->model('DB_model', 'dbmodel');
        $this->load->library('Custom');
        $this->load->library('ppdf');

        $this->title = "HRMS [ Placement ]";
        $this->url   = "http://172.16.161.100/hrms/employee/";

    }

    // private function checkEmployeeLogin()
    // {
    // 	if(! $this->session->userdata('hrmsEmployeeSession')){
    // 		redirect('/'.$this->session->userdata('usertype'));
    // 	}
    // }

    public function index()
    {

        $this->page_load("dashboard/dashboard", "dashboard", "");

        // $data['title']  = "Dashboard";
        // $this->load->view('template/header_placement', $data);
        // $this->load->view('placement/dashboard/dashboard', $data);
        // $this->load->view('template/footer', $data);
    }

    public function page_load($page, $menu = null, $datas = null)
    {

        $data['title']  = $this->title;
        $data['url']    = $this->url;
        $data['menu']   = $menu;
        $data['submenu'] = "";
        $data['tabMenu'] = "";

        $this->load->view('template/header_placement', $data);
        $this->load->view('placement/' . $page, $data);
        $this->load->view('template/footer', $data);
    }

    public function employees()
    {

        $data['title']  = $this->title;
        $data['url']    = $this->url;
        $data['tblApi']     = "masterfile/listemployees";
        $data['submenu'] = "";
        $data['tabMenu'] = "";


        $this->load->view('template/header_placement', $data);
        $this->load->view('placement/masterfile/list_employees');
        $this->load->view('template/footer', $data);
    }


    public function list_employees()
    {
        $columns = array(
                    0 => 'emp_id',
                    1 => 'name',
                    1 => 'emp_type',

                );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir   = $this->input->post('order')[0]['dir'];

        $totalData =  $this->dbmodel->count_data(
            "employee3",
            "current_status = 'Active' AND company_code != '07'"
        );

        $totalFiltered = $totalData;

        if(empty($this->input->post('search')['value'])) {
            // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
            $datas = $this->dbmodel->get_all_data(
                'employee3',   //table
                '*',    // field selected
                "ASC", // order by ASC or DESC
                "name", // order by field
                $limit, // per page
                $start,  // start of the page
                null,
                "current_status = 'Active' AND company_code != '07'"
            );
        } else {
            $search = $this->input->post('search')['value'];
            $datas  =  $this->dbmodel->dt_get_where_like(
                'employee3',   // table
                '*',    // field selected
                array('field1' => 'emp_id','field2' => 'name','field3' => 'emp_type'), // like field
                array($search,$search,$search,$search), // like field value
                array('field1' => 'emp_id','field2' => 'name','field3' => 'emp_type'), // like field
                array($search,$search,$search,$search), // or like field value
                array('field1' => 'emp_id','field2' => 'name','field3' => 'emp_type'),
                array($search,$search,$search,$search),
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start, // start of the page
                null,
                null,
                null,
                null,
                "current_status = 'Active' AND company_code != '07'"
            );

            $totalFiltered = count($datas);
        }
        // $c      = 1;
        $data   = array();
        if(!empty($datas)) {
            foreach ($datas as $list) {

                $bunit = $this->dbmodel
                ->get_row(
                    'locate_business_unit',
                    'business_unit,acroname',
                    array( 'field1' => 'company_code',
                    'field2' => 'bunit_code'),
                    array(@$list['company_code'],@$list['bunit_code'])
                );

                $dept = $this->dbmodel
                ->get_row(
                    'locate_department',
                    'dept_name,acroname',
                    array( 'field1' => 'company_code',
                    'field2' => 'bunit_code',
                    'field3' => 'dept_code'),
                    array(@$list['company_code'],@$list['bunit_code'],@$list['dept_code'])
                );


                $businessunit = @$bunit->acroname;
                $department = @$dept->dept_name;
                // $bday=$app->birthdate;

                // $n = $c++;
                // $nestedData['No']                = "<center>$n</center>";
                $nestedData['Name']              = '<a href="' . base_url() . 'supervisor/profile/' . $list['emp_id'] . '" target="_blank">' . ucwords(strtolower($list['name'])) . '</a>';
                $nestedData['Business Unit']      =  @$businessunit;
                $nestedData['Department']             = @$department;
                $nestedData['Position']         = $list['position'];
                $nestedData['Emptype']          = $list['emp_type'];
                $nestedData['Startdate']        = $list['startdate'];
                $nestedData['Eocdate']        = $list['eocdate'];
                $nestedData['Status']        = $list['current_status'];
                $data[] = $nestedData;
            }
        } else {
            $nestedData['No']           = "No Record Found";
            $nestedData['Name']         = "No Record Found";
            $nestedData['Birthdate']    = "No Record Found";
            $nestedData['BU/Dept']      = "No Record Found";
            $nestedData['SSSno']        = "No Record Found";
            $nestedData['Philheath']    = "No Record Found";
            $nestedData['Pagibig RTN']  = "No Record Found";
            $nestedData['Pagibig NO']   = "No Record Found";
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        echo json_encode($json_data);
    }


    public function modal_employees()
    {
        $data['company'] = $this->dbmodel
        ->get_all_data(
            'locate_company',
            '*',
            'ASC',
            'company',
            null,
            null,
            null,
            'status = "Active"'
        );
        $this->load->view('placement/masterfile/modal_employees', $data);
    }

    public function filter_employees($code, $eType)
    {
        $data['code']       = $code;
        $data['emptype']       = $eType;
        $data['title']      = $this->title;
        $data['menu']       = "benefits";
        $data['tblApi']     = "masterfile/filter_employes/" . $code . "/" . $eType;
        $data['submenu']    = "all_employees";

        $this->load->view('template/header_placement', $data);
        $this->load->view('placement/masterfile/filter_employees', $data);
        $this->load->view('template/footer', $data);
    }

    public function list_filter_employees($code, $eType)
    {

        if($eType != '') {
            switch($eType) {
                case 'All': $emptype = "and (emp_type='Regular Partimer' or emp_type='Partimer' or emp_type='PTA' or emp_type='PTP'  or emp_type='Contractual' or emp_type='Regular' or emp_type='NESCO' or emp_type ='NESCO-PTA' or emp_type='NESCO-PTP' or emp_type='NESCO Regular' or emp_type='NESCO Contractual' or emp_type='NESCO Partimer' or emp_type='NESCO Regular Partimer' or emp_type='NESCO Probationary' or emp_type='Probationary')";
                    break;
                case 'Partimer':	$emptype = "and (emp_type='Regular Partimer' or emp_type='Partimer' or emp_type='PTA' or emp_type='PTP')";
                    break;
                case 'NESCO':	$emptype = "and (emp_type='NESCO' or emp_type ='NESCO-PTA' or emp_type='NESCO-PTP' or emp_type='NESCO Regular' or emp_type='NESCO Contractual' or emp_type='NESCO Partimer' or emp_type='NESCO Regular Partimer' or emp_type='NESCO Probationary')";
                    break;
                default: 	$emptype = "and emp_type='" . $eType . "'";
                    break;
            }
        } else {
            $emptype = '';
        }


        $ncode  = explode(".", $code);
        $cc	   	= @$ncode[0];
        $bc		= @$ncode[1];
        $dc		= @$ncode[2];
        $sc		= @$ncode[3];
        $ssc	= @$ncode[4];

        if($cc != '') {
            $e = "employee3";
            if($ssc != '') {
                @$loc = "and $e.company_code = '$cc' and $e.bunit_code = '$bc' and $e.dept_code = '$dc' and $e.section_code = '$sc' and $e.sub_section_code = '$ssc' ";
            } elseif($sc != '') {
                @$loc = "and $e.company_code = '$cc' and $e.bunit_code = '$bc' and $e.dept_code = '$dc' and $e.section_code = '$sc' ";
            } elseif($dc != '') {
                @$loc = "and $e.company_code = '$cc' and $e.bunit_code = '$bc' and $e.dept_code = '$dc' ";
            } elseif($bc != '') {
                @$loc = "and $e.company_code = '$cc' and $e.bunit_code = '$bc' ";
            } elseif($cc != '') {
                @$loc = "and $e.company_code = '$cc'";
            } else {
                $loc = '';
            }
        }

        $columns = array(
            0 => 'emp_id',
            1 => 'name',
            1 => 'emp_type',
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir   = $this->input->post('order')[0]['dir'];

        $totalData =  $this->dbmodel->count_data(
            "employee3",
            "current_status = 'Active' AND company_code != '07'  
                           $emptype  $loc"
        );

        $totalFiltered = $totalData;

        if(empty($this->input->post('search')['value'])) {
            // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
            $datas = $this->dbmodel->get_all_data(
                'employee3',   //table
                '*',    // field selected
                "ASC", // order by ASC or DESC
                "name", // order by field
                $limit, // per page
                $start,  // start of the page
                null,
                "current_status = 'Active' AND company_code != '07'  
                                    $emptype  $loc"
            );

        } else {
            $search = $this->input->post('search')['value'];
            $datas  =  $this->dbmodel->dt_get_where_like(
                'employee3',   // table
                '*',    // field selected
                array('field1' => 'emp_id','field2' => 'name','field3' => 'emp_type'), // like field
                array($search,$search,$search,$search), // like field value
                array('field1' => 'emp_id','field2' => 'name','field3' => 'emp_type'), // like field
                array($search,$search,$search,$search), // or like field value
                array('field1' => 'emp_id','field2' => 'name','field3' => 'emp_type'),
                array($search,$search,$search,$search),
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start, // start of the page
                null,
                null,
                null,
                null,
                "current_status = 'Active' AND company_code != '07'  
                                     $emptype $loc"
            );

            $totalFiltered = count($datas);
        }

        $data = array();

        if(!empty($datas)) {
            // $c = 1;

            foreach ($datas as $list) {

                $bunit = $this->dbmodel
                ->get_row(
                    'locate_business_unit',
                    'business_unit,acroname',
                    array( 'field1' => 'company_code',
                    'field2' => 'bunit_code'),
                    array(@$list['company_code'],@$list['bunit_code'])
                );

                $dept = $this->dbmodel
                ->get_row(
                    'locate_department',
                    'dept_name,acroname',
                    array( 'field1' => 'company_code',
                    'field2' => 'bunit_code',
                    'field3' => 'dept_code'),
                    array(@$list['company_code'],@$list['bunit_code'],@$list['dept_code'])
                );

                $businessunit = @$bunit->acroname;
                $department = @$dept->dept_name;



                // $bday=$app->birthdate;

                // $nestedData['No']                = "<center>".$c++."</center>";
                $nestedData['Name']              = '<a href="' . base_url() . 'supervisor/profile/' . $list['emp_id'] . '" target="_blank">' . ucwords(strtolower($list['name'])) . '</a>';
                $nestedData['Business Unit']      =  $businessunit;
                $nestedData['Department']             = $department;
                $nestedData['Position']         = $list['position'];
                $nestedData['Emptype']          = $list['emp_type'];
                $nestedData['Startdate']        = $list['startdate'];
                $nestedData['Eocdate']        = $list['eocdate'];
                $nestedData['Status']        = $list['current_status'];

                $data[] = $nestedData;
            }
        } else {
            $nestedData['No']           = "No Record Found";
            $nestedData['Name']         = "No Record Found";
            $nestedData['Birthdate']    = "No Record Found";
            $nestedData['BU/Dept']      = "No Record Found";
            $nestedData['SSSno']        = "No Record Found";
            $nestedData['Philheath']    = "No Record Found";
            $nestedData['Pagibig RTN']  = "No Record Found";
            $nestedData['Pagibig NO']   = "No Record Found";
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        echo json_encode($json_data);
    }

    //FILTER FORM BU
    public function filter_bunit()
    {
        $company = $this->input->post('id');
        $result = $this->dbmodel->get_all_data(
            'locate_business_unit',
            'bunit_code, business_unit',
            'ASC',
            'business_unit',
            null,
            null,
            null,
            'company_code = "' . $company . '" and status = "active" '
        );

        if(count($result) > 0) {
            foreach($result as $res) {
                $data['data'][]	=	array(
                    "id"     => $company . "." . $res['bunit_code'],
                    "location_name"   => $res['business_unit']
                );
            }
        } else {
            $data['data'][]	=	array(
                "id"     => "",
                "location_name"   => ""
            );
        }
        echo json_encode($data);
    }

    //FILTER FORM DEPT
    public function filter_dept()
    {
        $id     = explode('.', $this->input->post('id'));
        $company    = @$id[0];
        $bunit      = @$id[1];

        $result = $this->dbmodel->get_all_data(
            'locate_department',
            'dept_code, dept_name',
            'ASC',
            'dept_name',
            null,
            null,
            null,
            'company_code = "' . @$company . '" and bunit_code = "' . @$bunit . '" and status = "active" '
        );

        if(count($result) > 0) {
            foreach($result as $res) {
                $data['data'][]	=	array(
                    "id"     => $company . "." . $bunit . "." . $res['dept_code'],
                    "location_name"   => $res['dept_name']
                );
            }
        } else {
            $data['data'][]	=	array(
                "id"     => "",
                "location_name"   => ""
            );
        }
        echo json_encode($data);
    }

    //FILTER FORM SECTION
    public function filter_sect()
    {
        $id = explode('.', $this->input->post('id'));
        $company = @$id[0];
        $bunit  = @$id[1];
        $dept   = @$id[2];

        $result = $this->dbmodel->get_all_data(
            'locate_section',
            'section_code, section_name',
            'ASC',
            'section_name',
            null,
            null,
            null,
            'company_code = "' . $company . '" and bunit_code = "' . $bunit . '" and dept_code = "' . $dept . '"'
        );

        if(count($result) > 0) {
            foreach($result as $res) {
                $data['data'][]	=	array(
                    "id"     => $company . "." . $bunit . "." . $dept . "." . $res['section_code'],
                    "location_name"   => $res['section_name']
                );
            }
        } else {
            $data['data'][]	=	array(
                "id"     => "",
                "location_name"   => ""
            );
        }
        echo json_encode($data);
    }

    //FILTER FORM SUB SECTION
    public function filter_sub_sect()
    {
        $id = explode('.', $this->input->post('id'));
        $company    = @$id[0];
        $bunit      = @$id[1];
        $dept       = @$id[2];
        $sect       = @$id[3];

        $result = $this->dbmodel->get_all_data(
            'locate_sub_section',
            'sub_section_code, sub_section_name',
            'ASC',
            'sub_section_name',
            null,
            null,
            null,
            'company_code = "' . $company . '" and bunit_code = "' . $bunit . '" and dept_code = "' . $dept . '" and section_code = "' . $sect . '" '
        );

        if(count($result) > 0) {
            foreach($result as $res) {
                $data['data'][]	=	array(
                    "id"     => $company . "." . $bunit . "." . $dept . "." . $sect . "." . $res['sub_section_code'],
                    "location_name"   => $res['sub_section_name']
                );
            }
        } else {
            $data['data'][]	=	array(
                "id"     => "",
                "location_name"   => ""
            );
        }
        echo json_encode($data);
    }

    public function excel_all_employees()
    {
        $this->load->view('placement/excel/xls_all_employees');
    }

    public function excel_all_employee_per_bu()
    {

        $code	= $this->input->get('xcode');
        $ec	 	= explode(".", $code);
        $cc	   	= @$ec[0];
        $bc		= @$ec[1];
        $dc		= @$ec[2];
        $sc		= @$ec[3];

        $data['bunit'] = $this->dbmodel
            ->get_row(
                'locate_business_unit',
                'business_unit',
                array( 'field1' => 'company_code',
                'field2' => 'bunit_code'),
                array(@$ec[0],@$ec[1])
            );

        $data['dept'] = $this->dbmodel
            ->get_row(
                'locate_department',
                'dept_name',
                array( 'field1' => 'company_code',
                'field2' => 'bunit_code',
                'filed3' => 'dept_code'),
                array(@$ec[0],@$ec[1],@$ec[2])
            );
        $this->load->view('placement/excel/xls_all_employee_per_bu', $data);
    }

    public function blacklisted_employees()
    {

        $data['title']  = $this->title;
        $data['url']    = $this->url;
        $data['tblApi']     = "placement/masterfile/list_blacklisted_employees";
        $data['submenu'] = "";
        $data['tabMenu'] = "";


        $this->load->view('template/header_placement', $data);
        $this->load->view('placement/masterfile/list_blacklists');
        $this->load->view('template/footer', $data);
    }

    public function list_blacklisted_employees()
    {
        $columns = array(
            0 => 'name'
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir   = $this->input->post('order')[0]['dir'];

        $totalData =  $this->dbmodel->count_data('blacklist');
        $totalFiltered = $totalData;

        if(empty($this->input->post('search')['value'])) {
            // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
            $datas = $this->dbmodel->get_all_data(
                'blacklist',   //table
                '*',    // field selected
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start  // start of the page
            );
        } else {
            $search = $this->input->post('search')['value'];

            $datas =  $this->dbmodel->dt_get_where_like(
                'blacklist',   // table
                '*',            // field seleted
                array('field1' => 'name'), // like field
                array($search), // like field value
                array('field1' => 'name'),   // or like field
                array($search), // or like field value
                null,
                null,
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start  // start of the page
            );

            $totalFiltered = $this->dbmodel->dt_get_where_count(
                'blacklist',
                '*',
                array('field1' => 'name'),
                array($search),
                array('field1' => 'name'),
                array($search)
            );
        }

        $data = array();
        if(!empty($datas)) {
            foreach ($datas as $list) {

                $appid = $list['app_id'];

                $checkAppId = (!empty($list['app_id'])) ? $list['app_id'] : '0000-0000';
                $width = 60;
                $break = "</br>";

                $nestedData['BLNO']   = $list['blacklist_no'];
                $nestedData['EMP&nbsp;NO']   =  $appid;
                $nestedData['NAME']     = '<a href="' . base_url() . 'supervisor/profile/' . $list['app_id'] . '" target="_blank">' . ucwords(strtolower($list['name'])) . '</a>';
                $nestedData['REPORTED&nbsp;BY']     = ucwords(strtolower($list['reportedby']));
                $nestedData['BLACKLIST&nbsp;DATE']   = $list['date_blacklisted'];
                $nestedData['DATE&nbsp;ADDED']   = $list['date_added'];
                $nestedData['REASON']   = wordwrap($list['reason'], $width, $break);
                $nestedData['ADDED&nbsp;BY']   = $list['staff'];
                $nestedData['STATUS']   = $list['status'];
                $nestedData['ACTION']   = (!empty($list['app_id'])) ? '<center>
                                                <button
                                                    class="btn btn-sm btn-success" 
                                                    type="button"
                                                    modal-size="modal-lg"
                                                    modal-route="masterfile/blacklistupdate"
                                                    modal-form="masterfile/modal_blacklist/' . $appid . '"
                                                    modal-skeleton="0"
                                                    modal-id=""
                                                    modal-atype="POST"
                                                    modal-title="Edit Blacklist" 
                                                    onclick="modal(event)">
                                                    EDIT
                                                </button>
                                            </center>' : '';

                $data[] = $nestedData;

            }
        } else {
            $nestedData['subsidiary']   = "No Record Found";
            $nestedData['Name']         = "No Record Found";
            $nestedData['Date']         = "No Record Found";
            $nestedData['Reason']       = "No Record Found";
            $nestedData['Status']       = "No Record Found";

            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }

    public function modal_blacklisted_employees($appid)
    {
        $datas = $this->dbmodel->get_all_data(
            'blacklist',   //table
            '*',    // field selected
            null,   // order by field
            null, // order by ASC or DESC
            null,
            null,
            null, // per page
            'app_id = "' . $appid . '"'    // start of the page
        );

        foreach ($datas as $list) {

            $data['name'] = $list['name'];
            $data['reason'] = $list['reason'];
            $data['dateblacklisted'] = $list['date_blacklisted'];
            $data['reportedby'] = $list['reportedby'];
            $data['birthday'] = $list['bday'];
            $data['address'] = $list['address'];

        }

        $data['appid'] = $appid;
        $this->load->view('placement/masterfile/modal_blacklisted_emp', $data);

    }

    public function update_blacklist()
    {

        $data = array(
                    'reason' => $this->input->post('reason'),
                    'date_blacklisted' => $this->input->post('dateblacklisted'),
                    'reportedby' => $this->input->post('reportedby'),
                    'bday' => $this->input->post('birthday'),
                    'address' => $this->input->post('address'),
                );

        $update = $this->dbmodel->update(
            "blacklist",
            $data,
            "app_id = '" . $this->input->post('appid') . "'"
        );

        if($update) {
            echo json_encode([
                'status' => 200,
                'response' => "success",
                'modal_close'   => "true",
                'response_message'  => "The Blacklist Information was successfully updated.",
            ]);
        } else {
            echo json_encode([
                'status' => 401,
                'response'	=> "error",
                'response_message'	=> "Unable to update the Blacklist Information."
            ]);
        }

    }

    public function employee_jobtransfer()
    {

        $data['title']  = $this->title;
        $data['url']    = $this->url;
        $data['tblApi']     = "placement/masterfile/list_jobtransfer";
        $data['submenu'] = "";
        $data['tabMenu'] = "";

        $this->load->view('template/header_placement', $data);
        $this->load->view('placement/masterfile/list_job_transfer');
        $this->load->view('template/footer', $data);

    }

    public function list_jobtransfer()
    {

        $yeartoday = date('Y-m-d');

        $columns = array(
                0 => 'name',
            );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir   = $this->input->post('order')[0]['dir'];

        $totalData =  $this->dbmodel->count_data(
            'employee3 as emp1',
            null,
            array("table1" => "employee_transfer_details as etd1"),
            array("emp1.emp_id = etd1.emp_id"),
            array(
                'field1' => 'process',
                'field2' => 'YEAR(etd1.effectiveon)'
            ),
            array(
                  'no',
                  $yeartoday
                )
        );

        $totalFiltered = $totalData;

        if(empty($this->input->post('search')['value'])) {
            // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
            $datas = $this->dbmodel->get_data_by_join(
                "employee3 as emp1",
                array("table1" => "employee_transfer_details as etd1"),
                array("emp1.emp_id = etd1.emp_id"),
                "emp1.name,
                            emp1.emp_id, 
                            etd1.effectiveon, 
                            etd1.old_location, 
                            etd1.new_location,
                            etd1.position, 
                            etd1.process,
                            etd1.transfer_no, 
                            etd1.supervision",
                array(
                    'field1' => 'YEAR(etd1.effectiveon)',
                     'field2' => 'process'
                ),
                array(
                    $yeartoday,
                    'no'
                ),
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start  // start of the page
            );
        } else {
            $search = $this->input->post('search')['value'];
            $datas  =  $this->dbmodel->dt_get_where_like(
                "employee3 as emp1",
                "emp1.name, 
                            emp1.emp_id, 
                            etd1.effectiveon, 
                            etd1.old_location, 
                            etd1.new_location,
                            etd1.position,  
                            etd1.process,
                            etd1.transfer_no,
                            etd1.supervision",
                array('field1' => 'emp1.name'), // like field
                array($search), // like field value
                null, // like field
                null, // like field value
                array('field2' => 'emp1.name'),   // or like field
                array($search), // or like field value
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start,  // start of the page
                array("table1" => "employee_transfer_details as etd1"),
                array("emp1.emp_id = etd1.emp_id"),
                null,
                null,
                "YEAR(etd1.effectiveon) = $yeartoday"
            );

            $totalFiltered = count($datas);
        }

        $data = array();
        if(!empty($datas)) {
            $c = 1;
            foreach ($datas as $list) {
                $empid = $list['emp_id'];
                $transno = $list['transfer_no'];
                $nl = explode('-', $list['new_location']);
                $ol = explode('-', $list['old_location']);

                // print_r($ol);
                // print_r($nl);

                $bunit = $this->dbmodel
                ->get_row(
                    'locate_business_unit',
                    'business_unit',
                    array( 'field1' => 'company_code',
                    'field2' => 'bunit_code'),
                    array($nl[0],$nl[1])
                );

                $bunitold = $this->dbmodel
                ->get_row(
                    'locate_business_unit',
                    'business_unit',
                    array( 'field1' => 'company_code',
                    'field2' => 'bunit_code'),
                    array($ol[0],@$ol[1])
                );

                $dept = $this->dbmodel
                ->get_row(
                    'locate_department',
                    'dept_name',
                    array( 'field1' => 'company_code',
                    'field2' => 'bunit_code',
                    'filed3' => 'dept_code'),
                    array($nl[0],$nl[1],@$nl[2])
                );

                $deptold = $this->dbmodel
                ->get_row(
                    'locate_department',
                    'dept_name',
                    array( 'field1' => 'company_code',
                    'field2' => 'bunit_code',
                    'filed3' => 'dept_code'),
                    array($ol[0],@$ol[1],@$ol[2])
                );

                $bunit_new = @$bunit->business_unit;
                $bunit_old = @$bunitold->business_unit;
                $dept_new  = @$dept->dept_name;
                $dept_old  = @$deptold->dept_name;

                
                if($list['process'] == 'no') {
                    $action = '<center><a
                            class="btn btn-sm btn-success" 
                            type="button" href ="edit_jobtransfer/' . $empid . '"
                            ">Edit</a>  <b>|</b>
                           <a class="btn btn-sm btn-danger" type="button" 
                           data-swal-route="placement/masterfile/delete_jobstransfer" 
                           data-swal-id="'.$transno.'" 
                           data-swal-message="Are you sure do you want to delete this record?" 
                           onclick="certify(event)" href="javascript:void(0)">Delete</a></center>';
                } else {
                    $action = '';
                }


                $nestedData['Name']               = '<a href="' . base_url() . 'supervisor/profile/' . $list['emp_id'] . '" target="_blank">' . ucwords(strtolower($list['name'])) . '</a>';
                $nestedData['Effective']          = $list['effectiveon'];
                $nestedData['Transfer&nbsp;From']      = $bunit_old . "-" . $dept_old;
                $nestedData['Transfer To']        =  $bunit_new . "-" . $dept_new;
                $nestedData['New Position']       = $list['position'];
                $nestedData['Direct Sup']         = $list['supervision'];
                $nestedData['Transfer']           =  $action;
                $data[] = $nestedData;
            }
        } else {
            $nestedData['Name']             = "No Record Found";
            $nestedData['Effective']        = "No Record Found";
            $nestedData['Transfer From']    = "No Record Found";
            $nestedData['Transder To']      = "No Record Found";
            $nestedData['New Position']     = "No Record Found";
            $nestedData['Direct Sup']       = "No Record Found";
            $nestedData['Transfer']         = "No Record Found";
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);

    }

    public function delete_jobstransfer()
    {
        
    // $query = mysql_query("DELETE FROM employee_transfer_details where transfer_no = '$transno' and emp_id = '$empid' ") or die
        $transno = $this->input->post('id');
        $delete = $this->dbmodel->delete(
            "employee_transfer_details",
            "transfer_no = '" . $transno . "'"
        );

        if($delete) {
            echo json_encode([
                'status' => 200,
                'response' => "success",
                 'dtable'  => true,
                'response_message'  => "Jobtrans Details was successfully updated.",
            ]);
        } else {
            echo json_encode([
                'status' => 401,
                'response'	=> "error",
                'response_message'	=> "Unable to delete the Jobtrans Details."
            ]);
        }


    }

    public function filter_by_year($year)
    {

        if ($year) {
            $yr = $year;
        } else {
            $yr = date('Y');
        }

        $columns = array(
                        0 => 'name',
                    );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir   = $this->input->post('order')[0]['dir'];

        $totalData =  $this->dbmodel->count_data(
            'employee_transfer_details as etd1',
             "YEAR(etd1.effectiveon) like '$yr%'",
            array("table1" => "employee3 as emp1"),
            array("etd1.emp_id = emp1.emp_id"),
            null,
            null
        );

        $totalFiltered = $totalData;

        if(empty($this->input->post('search')['value'])) {
            // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
            $datas = $this->dbmodel->get_data_by_join(
                "employee3 as emp1",
                array("table1" => "employee_transfer_details as etd1"),
                array("emp1.emp_id = etd1.emp_id"),
                "emp1.name,
                emp1.emp_id, 
                etd1.effectiveon, 
                etd1.old_location, 
                etd1.new_location,
                etd1.position,  
                etd1.process,
                etd1.transfer_no,
                etd1.supervision",
                null,
                null,
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start,  // start of the page
                "YEAR(etd1.effectiveon) like '$yr%'"
            );
        } else {
            $search = $this->input->post('search')['value'];
            $datas  =  $this->dbmodel->dt_get_where_like(
                "employee3 as emp1",
                "emp1.name, 
                                        emp1.emp_id, 
                                        etd1.effectiveon, 
                                        etd1.old_location, 
                                        etd1.new_location,
                                        etd1.position, 
                                        etd1.process, 
                                        etd1.transfer_no,
                                        etd1.supervision",
                array('field1' => 'emp1.name'), // like field
                array($search), // like field value
                null, // like field
                null, // like field value
                array('field2' => 'emp1.name'),   // or like field
                array($search), // or like field value
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start,  // start of the page
                array("table1" => "employee_transfer_details as etd1"),
                array("emp1.emp_id = etd1.emp_id"),
                null,
                null,
                "YEAR(etd1.effectiveon) like '$yr%'"
            );

            $totalFiltered = count($datas);
        }

        $data = array();
        if(!empty($datas)) {
            $c = 1;
            foreach ($datas as $list) {
                $empid = $list['emp_id'];
                $transno = $list['transfer_no'];

                $nl = explode('-', $list['new_location']);
                $ol = explode('-', $list['old_location']);

                // print_r($ol);
                // print_r($nl);

                $bunit = $this->dbmodel
                ->get_row(
                    'locate_business_unit',
                    'business_unit',
                    array( 'field1' => 'company_code',
                    'field2' => 'bunit_code'),
                    array(@$nl[0],@$nl[1])
                );

                $bunitold = $this->dbmodel
                ->get_row(
                    'locate_business_unit',
                    'business_unit',
                    array( 'field1' => 'company_code',
                    'field2' => 'bunit_code'),
                    array(@$ol[0],@$ol[1])
                );

                $dept = $this->dbmodel
                ->get_row(
                    'locate_department',
                    'dept_name',
                    array( 'field1' => 'company_code',
                    'field2' => 'bunit_code',
                    'filed3' => 'dept_code'),
                    array(@$nl[0],@$nl[1],@$nl[2])
                );

                $deptold = $this->dbmodel
                ->get_row(
                    'locate_department',
                    'dept_name',
                    array( 'field1' => 'company_code',
                    'field2' => 'bunit_code',
                    'filed3' => 'dept_code'),
                    array(@$ol[0],@$ol[1],@$ol[2])
                );

                $bunit_new = @$bunit->business_unit;
                $bunit_old = @$bunitold->business_unit;
                $dept_new  = @$dept->dept_name;
                $dept_old  = @$deptold->dept_name;

                
                if($list['process'] === 'no') {
                    $action = '<center><a
                                            class="btn btn-sm btn-success" 
                                            type="button" href ="edit_jobtransfer/' . $empid . '"
                                            ">Edit</a>  <b>|</b>
                                             <a class="btn btn-sm btn-danger" type="button" 
                                                data-swal-route="placement/masterfile/delete_jobstransfer" 
                                                data-swal-id="'.$transno.'" 
                                                data-swal-message="Are you sure do you want to delete this record?" 
                                                onclick="certify(event)" href="javascript:void(0)">Delete</a></center>';
                                            } else {
                                                $action = '';
                                            }


                $nestedData['Name']               = '<a href="' . base_url() . 'supervisor/profile/' . $list['emp_id'] . '" target="_blank">' . ucwords(strtolower($list['name'])) . '</a>';
                $nestedData['Effective']          = $list['effectiveon'];
                $nestedData['Transfer From']      = $bunit_old . "-" . $dept_old;
                $nestedData['Transfer To']        =  $bunit_new . "-" . $dept_new;
                $nestedData['New Position']       = $list['position'];
                $nestedData['Direct Sup']         = $list['supervision'];
                $nestedData['Transfer']           = $action;


                $data[] = $nestedData;
            }
        } else {
            $nestedData['Name']             = "No Record Found";
            $nestedData['Effective']        = "No Record Found";
            $nestedData['Transfer From']    = "No Record Found";
            $nestedData['Transder To']      = "No Record Found";
            $nestedData['New Position']     = "No Record Found";
            $nestedData['Direct Sup']       = "No Record Found";
            $nestedData['Transfer']         = "No Record Found";
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);

    }

    public function edit_jobtransfer($empid)
    {
  
        $data['title']  = $this->title;
        $data['url']    = $this->url;
        // $data['tblApi']     = "placement/masterfile/list_jobtransfer";
        $data['submenu'] = "";
        $data['tabMenu'] = "";
        $data['empId'] = $empid;

        $datas = $this->dbmodel->get_data_by_join(
            "employee3 as emp1",
            array("table1" => "employee_transfer_details as etd1"),
            array("emp1.emp_id = etd1.emp_id"),
            "emp1.name,
            emp1.company_code,
            emp1.bunit_code,
            emp1.dept_code,
            emp1.section_code,
            emp1.sub_section_code,
            emp1.unit_code,
            emp1.positionlevel,
            emp1.payroll_no,
            emp1.poslevel, 
            emp1.lodging, 
            emp1.startdate, 
            emp1.eocdate, 
            emp1.current_status, 
            emp1.emp_type,
            emp1.record_no,
            etd1.carbon_copy,
            etd1.effectiveon, 
            etd1.old_location, 
            etd1.new_location,
            etd1.position, 
            etd1.old_position, 
            etd1.old_level,
            etd1.supervision,
            etd1.assignedfrom,
            etd1.entry_date,
            etd1.transfer_no,
           etd1.type_of_transfer,
            etd1.level",
              array(
                'field1' => 'emp1.current_status',
                'field2' => 'emp1.emp_id'
                    ),
              array(
                'Active',
                $empid
                ),
            null,   // order by field
            null, // order by ASC or DESC
            null, // per page
            null  // start of the page
        );

        foreach ($datas as $row )
        {
           $data['name']=$row['name'];
           $data['positionlevel']=$row['positionlevel'];
           $data['poslevel']=$row['poslevel'];
           $data['lodging']=$row['lodging'];
           $data['startdate']=$row['startdate'];
           $data['payrollno'] = $row['payroll_no'];
           $data['recordno']=$row['record_no'];
           $data['transno']=$row['transfer_no'];
           $data['eocdate']=$row['eocdate'];
           $data['current_status']=$row['current_status'];
           $data['effectiveon']=$row['effectiveon'];
           $data['position']=$row['position'];
           $data['emptype'] = $row['emp_type'];
           $data['supervision'] = $row['supervision']; 
           $data['oldposition'] = $row['old_position'];
           $data['level'] = $row['level'];
           $data['assignedfrom'] = $row['assignedfrom'];
           $data['entrydate'] = $row['entry_date'];
           $data['effectiveon'] = $row['effectiveon'];
           $data['transtype'] = $row['type_of_transfer'];
           $data['oldlevel'] = $row['old_level'];
           $old = $row['old_location'];
           $new= $row['new_location'];
           $carboncopy = $row['carbon_copy'];
        }
        //CARBON COPY
        $ccopy = explode('$', $carboncopy);
        $data['cc1'] =  $ccopy[0];
        $data['cc2'] =  $ccopy[1];
        $data['cc3'] =  $ccopy[2];
        $data['cc4'] =  $ccopy[3];
        $data['cc5'] =  $ccopy[4];
        $data['cc6'] =  $ccopy[5];

        // OLD FOR INSERTING
        $ol = explode('-', $old);
        $data['oldcc'] = @$ol['0'];
        $data['oldbc'] = @$ol['1'];
        $data['olddc'] = @$ol['2'];
        $data['oldsc'] = @$ol['3'];
        $data['oldssc'] = @$ol['4'];
        $data['olduc'] = @$ol['5'];

        

        //OLD FOR VIEWING
        $ol = explode('-', $old);
        $oldcc = @$ol['0'];
        $oldbc = @$ol['1'];
        $olddc = @$ol['2'];
        $oldsc = @$ol['3'];
        $oldssc = @$ol['4'];
        $olduc = @$ol['5'];

        $data['company'] = $this->get_company($oldcc);
        $data['bussinessunit'] = $this->get_bu($oldcc,$oldbc);
        $data['department'] = $this->get_dept($oldcc, $oldbc,$olddc);
        $data['section'] = $this->get_section($oldcc, $oldbc, $olddc,$oldsc);
        $data['subsection'] = $this->get_sub_section($oldcc, $oldbc, $olddc, $oldsc,$oldssc);
        $data['units'] = $this->get_unit($oldcc, $oldbc, $oldsc, $olddc, $oldssc, $olduc);

        //NEW
        $nl = explode('-', $new);

        $data['n_cc']= @$nl['0'];
        $data['n_bc']= @$nl['1'];
        $data['n_dc']= @$nl['2'];
        $data['n_sc']= @$nl['3'];
        $data['n_ssc']= @$nl['4'];

        
    $data['comp'] = $this->dbmodel
        ->get_all_data(
            'locate_company',
            '*',
            'ASC',
            'company',
            null,
            null,
            null,
            'status = "Active"'
        ); 

        
     $data['bunit'] = $this->dbmodel
        ->get_all_data(
            'locate_business_unit',
            '*',
           'ASC',
            'business_unit',
            null,
            null,
            null,
            'status = "Active" and company_code = "'.$data['n_cc'].'"'
        );

        
        $data['dept'] = $this->dbmodel
        ->get_all_data(
            'locate_department',
            '*',
            'ASC',
            'dept_name',
            null,
            null,
            null,
            'status = "Active" and company_code = "'.$data['n_cc'].'" and bunit_code = "'.$data['n_bc'].'"'
        );

        
        $data['sect'] = $this->dbmodel
         ->get_all_data(
          'locate_section',
          '*',
          'ASC',
          'section_name',
          null,
          null,
          null,
          'status = "Active" and company_code = "'.$data['n_cc'].'" and bunit_code = "'.$data['n_bc'].'" and dept_code = "'.$data['n_dc'].'"'
        );

        
        $data['subsect'] = $this->dbmodel
         ->get_all_data(
             'locate_sub_section',
             '*',
             'ASC',
             'sub_section_name',
             null,
             null,
             null,
             'status = "Active" and company_code = "'.$data['n_cc'].'" and bunit_code = "'.$data['n_bc'].'" and dept_code = "'.$data['n_dc'].'" and section_code = "'.$data['n_sc'].'"'
         );

         
        $data['unit'] = $this->dbmodel
        ->get_all_data(
            'locate_unit',
            '*',
            'ASC',
            'unit_name',
            null,
            null,
            null,
            'status = "Active"'
        );


        $this->load->view('template/header_placement', $data);
        $this->load->view('placement/masterfile/edit_jobtrans',$data);
        $this->load->view('template/footer', $data);

    }


    public function get_comp($cc)
    {
        $result = $this->dbmodel
            ->get_row(
                'locate_company',
                'company,acroname',
                array( 
                    'field1' => 'company_code'
                ),
                array(@$cc)
            );
        return $result->acroname;   
    }

    public function get_company($cc)
    {
        $result = $this->dbmodel
            ->get_row(
                'locate_company',
                'company',
                array( 
                    'field1' => 'company_code'
                ),
                array(@$cc)
            );
        return $result->company;   
    }


     public function get_bu($cc,$bc)
    {
        $result = $this->dbmodel
            ->get_row(
                'locate_business_unit',
                'business_unit',
                array( 
                    'field1' => 'company_code',
                    'field2' => 'bunit_code'
                ),
                array($cc, $bc)
            );
        return $result->business_unit;   
    }

     public function get_bunit($cc,$bc)
    {
        $result = $this->dbmodel
            ->get_row(
                'locate_business_unit',
                'business_unit,acroname',
                array( 
                    'field1' => 'company_code',
                    'field2' => 'bunit_code'
                ),
                array($cc, $bc)
            );
        return $result->acroname;   
    }

    
    
    // public function get_bunit($cc, $bc)
    // {
    //     $result = $this->dbmodel
    //         ->get_row(
    //             'locate_business_unit',
    //             'business_unit',
    //             array( 
    //                 'field1' => 'company_code',
    //                 'field2' => 'bunit_code'
    //             ),
    //             array($cc, $bc)
    //         );
        
    //     if ($result !== null && is_object($result)) {
    //         return $result->business_unit;
    //     } else {
    //         $error_message = "Unable to retrieve business unit information for company code $cc and business code $bc.";
    //         // You can log the error or return it as a response, depending on your application's requirements.
    //         // In this example, we're just returning the error message.
    //         return $error_message;
    //     }
    // }
     
    public function get_dept($cc,$bc,$dc)
    {
        $result = $this->dbmodel
            ->get_row(
                'locate_department',
                'acroname, dept_name',
                array( 
                    'field1' => 'company_code',
                    'field2' => 'bunit_code',
                    'field3' => 'dept_code'
                ),
                array( $cc, $bc, $dc)
            );
        if(!$dc){
            $dept = "";
        }else if($result->acroname !=''){		
            $dept = $result->acroname;
        }else{
            $dept = $result->dept_name;	
        }
        return $dept;  
    }

    public function get_section($cc, $bc, $dc, $sc)
    {
        //return "SEction ".$cc. $bc. $dc. $sc;
        if($sc){
            $result = $this->dbmodel
            ->get_row(
                'locate_section',
                'section_name',
                array( 
                    'field1' => 'company_code',
                    'field2' => 'bunit_code',
                    'field3' => 'dept_code',
                    'field4' => 'section_code'
                ),
                array( $cc, $bc, $dc, $sc )
            );
            return "-".$result->section_name;	
        }else{
            return;
        }        
    }

    public function get_sect($cc, $bc, $dc, $sc)
    {
        //return "SEction ".$cc. $bc. $dc. $sc;
        if($sc){
            $result = $this->dbmodel
            ->get_row(
                'locate_section',
                'section_name',
                array( 
                    'field1' => 'company_code',
                    'field2' => 'bunit_code',
                    'field3' => 'dept_code',
                    'field4' => 'section_code'
                ),
                array( $cc, $bc, $dc, $sc )
            );
            return $result->section_name;	
        }else{
            return;
        }        
    }

        public function get_sub_section($cc, $bc, $dc, $sc,$ssc)
    {
        //return "SEction ".$cc. $bc. $dc. $sc;
        if($sc){
            $result = $this->dbmodel
            ->get_row(
                'locate_sub_section',
                'sub_section_name',
                array( 
                    'field1' => 'company_code',
                    'field2' => 'bunit_code',
                    'field3' => 'dept_code',
                    'field4' => 'section_code',
                    'field5' => 'sub_section_code'
                ),
                array( $cc, $bc, $dc, $sc ,$ssc )
            );
            return "-".@$result->sub_section_name;	
        }else{
            return;
        }        
    }

      public function get_sub_sect($cc, $bc, $dc, $sc,$ssc)
    {
        //return "SEction ".$cc. $bc. $dc. $sc;
        if($sc){
            $result = $this->dbmodel
            ->get_row(
                'locate_sub_section',
                'sub_section_name',
                array( 
                    'field1' => 'company_code',
                    'field2' => 'bunit_code',
                    'field3' => 'dept_code',
                    'field4' => 'section_code',
                    'field5' => 'sub_section_code'
                ),
                array( $cc, $bc, $dc, $sc ,$ssc )
            );
            return @$result->sub_section_name;	
        }else{
            return;
        }        
    }


        public function get_unit($cc, $bc, $dc, $sc,$ssc,$uc)
    {
        //return "SEction ".$cc. $bc. $dc. $sc;
        if($sc){
            $result = $this->dbmodel
            ->get_row(
                'locate_unit',
                'unit_name',
                array( 
                    'field1' => 'company_code',
                    'field2' => 'bunit_code',
                    'field3' => 'dept_code',
                    'field4' => 'section_code',
                    'field5' => 'sub_section_code',
                    'field6' => 'unit_code'
                ),
                array( $cc, $bc, $dc, $sc ,$ssc , $uc)
            );
            return "-".@$result->unit_name;	
        }else{
            return;
        }        
    }

      public function get_un($cc, $bc, $dc, $sc,$ssc,$uc)
    {
        //return "SEction ".$cc. $bc. $dc. $sc;
        if($sc){
            $result = $this->dbmodel
            ->get_row(
                'locate_unit',
                'unit_name',
                array( 
                    'field1' => 'company_code',
                    'field2' => 'bunit_code',
                    'field3' => 'dept_code',
                    'field4' => 'section_code',
                    'field5' => 'sub_section_code',
                    'field6' => 'unit_code'
                ),
                array( $cc, $bc, $dc, $sc ,$ssc , $uc)
            );
            return @$result->unit_name;	
        }else{
            return;
        }        
    }

     public function findEmployeeSupervisor()

    {  
        $key = addslashes($this->input->post('str'));
        $val = "";

        $datas = $this->dbmodel->get_data_by_join(
            "employee3 as emp1",
            array("table1" => "users as us1"),
            array("emp1.emp_id = us1.emp_id"),
                "emp1.name,
                    emp1.emp_id,
                    emp1.current_status,
                    ",
                null,
                null,
                null,   // order by field
                null, // order by ASC or DESC
                10, // per page
                null, // start of the page
                'current_status = "Active" AND usertype = "supervisor" AND (emp1.name like "%' . $key . '%" or emp1.emp_id = "' . $key . '")'
            );
             foreach ($datas as $n) {
            $empId = $n['emp_id'];
            $name  = ucwords(strtolower($n['name']));
            $status = $n['current_status'];

            if($val != $empId) {
                echo "<a class = \"nameFind\" href = \"javascript:void\" onclick='getEmpId_2(\"$empId*$name*$status\")'>[ " . $empId . " ] = " . $name . "</a></br>";
            } else {
                echo "<a class = \"afont\" href = \"javascript:void\">No Result Found</a></br>";
            }
        }
    }

     public function getLevel()
     {
         $position = $this->input->post('position');
         $level= $this->dbmodel->get_field("lvlno", "position_leveling", "position_title = '".$position."' ");
         echo $level['lvlno'];
     }

     function update_jobtransfer()
     {

        $cc1     = addslashes(@$this->input->post('cc1'));
        $cc2     = addslashes(@$this->input->post('cc2'));
        $cc3     = addslashes(@$this->input->post('cc3'));
        $cc4     = addslashes(@$this->input->post('cc4'));
        $cc5     = addslashes(@$this->input->post('cc5'));
        $cc6     = addslashes(@$this->input->post('cc6'));
        $ccopy   = $cc1 . "$" . $cc2 . "$" . $cc3 . "$" . $cc4 . "$" . $cc5 . "$" . $cc6;

        //NEW 
        if($this->input->post('unit_code')) {
            $new = $this->input->post('unit_code');
        } elseif($this->input->post('ssec_code')) {
            $new = $this->input->post('ssec_code');
        } elseif($this->input->post('sec_code')) {
            $new = $this->input->post('sec_code');
        } elseif($this->input->post('dept_code')) {
            $new = $this->input->post('dept_code');
        } elseif($this->input->post('bunit_code')) {
            $new = $this->input->post('bunit_code');
        } elseif($this->input->post('comp_code')) {
            $new = $this->input->post('comp_code');
        }
        $new = str_replace(".", "-", $new);
        //OLD LOCATION

        
        if($this->input->post('unit')) {
            $old = $this->input->post('company') . "-" . $this->input->post('businessunit') . "-" . $this->input->post('department') . "-" . $this->input->post('section') . "-" . $this->input->post('subsection') . "-" . $this->input->post('unit');
        } elseif($this->input->post('subsection')) {
            $old = $this->input->post('company') . "-" . $this->input->post('businessunit') . "-" . $this->input->post('department') . "-" . $this->input->post('section') . "-" . $this->input->post('subsection');
        } elseif($this->input->post('section')) {
            $old = $this->input->post('company') . "-" . $this->input->post('businessunit') . "-" . $this->input->post('department') . "-" . $this->input->post('section');
        } elseif($this->input->post('department')) {
            $old = $this->input->post('company') . "-" . $this->input->post('businessunit') . "-" . $this->input->post('department');
        } elseif($this->input->post('businessunit')) {
            $old = $this->input->post('company') . "-" . $this->input->post('businessunit');
        } elseif($this->input->post('company')) {
            $old = $this->input->post('company');
        }

        $currentstatus  = "Active";
        $status         = "transferred";
        $rec            = $this->input->post('rec');
        $empid          = $this->input->post('empid');
        $transno        = $this->input->post('transno');
        $effectiveon    =  $this->input->post('effectiveon');
        $from           = $this->input->post('from');
        $supervision    = $this->input->post('supervision');
        $supervice = explode('*', $supervision);
        // $re             = $this->input->post('company')$_POST['re'];
        $pos            = $this->input->post('contract_position');
        $prev_pos       = $this->input->post('prev_pos');
        $oldpos         = $this->input->post('oldpos');
        $oldlevel       = $this->input->post('oldlevel');
        $newlevel       = $this->input->post('newlevel');
        $entrydate		= $this->input->post('dates');
        $entryby		= $this->session->userdata('emp_id');
        $payrollno		= $this->input->post('payrollno');
        $transtype 		= $this->input->post('transfer_type');
        $emptype 		= $this->input->post('emptype');

        $data = array(
            'effectiveon' => $effectiveon,
            'old_position' => $oldpos,
            'position' => $pos,
            'old_level' => $oldlevel,
            'level' => $newlevel,
            'old_location' => $old,
            'new_location' => $new,
            'carbon_copy' => $ccopy,
            'assignedfrom' => $from,
            'supervision' => $supervision,
            'status' => $status,
            'file' => '',
            'old_payroll_no' => $payrollno,
            'process' => 'no',
            'type_of_transfer' => $transtype,
            'transfer_to_emptype' => $emptype
        );

        $update = $this->dbmodel->update(
            "employee_transfer_details",
            $data,
            "emp_id = '" . $empid . "' AND  record_no = '" . $rec . "' AND transfer_no 	= '" . $transno . "' "
        );

        if($update) {

              if($transtype == "nescotoae") {
                    $loc = "placement/masterfile/pdf_nescotoae?rec=" . $rec . "&empid=" . $empid . "&transno=" . $transno . "&dates=" . $entrydate;
                } else {
                    $loc = "placement/masterfile/pdf_newtransfer?rec=" . $rec . "&empid=" . $empid . "&transno=" . $transno . "&dates=" . $entrydate;
                }


            echo json_encode([
                'status' => 200,
                'response' => "success",
                'modal_close'   => "true",
                'redirect'   => $loc,
                'response_message'  => "The Job Transfer Information was successfully updated.",
            ]);
        } else {
            echo json_encode([
                'status' => 401,
                'response'	=> "error",
                'response_message'	=> "Unable to update the Job Transfer  Information."
            ]);
        }

    }

    public function pdf_nescotoae()
        {
             $this->load->view('placement/pdf/pdf_nescotoae');
        }

    public function pdf_newtransfer()
    {
        $this->load->view('placement/pdf/pdf_newtransfer');
    }

    public function kra()
    {
        $data['title']  = $this->title;
        $data['url']    = $this->url;
        $data['tblApi']     = "placement/masterfile/list_of_Kra";
        $data['submenu'] = "";
        $data['tabMenu'] = "";


        $this->load->view('template/header_placement', $data);
        $this->load->view('placement/masterfile/list_kra');
        $this->load->view('template/footer', $data);
    }

    public function list_of_Kra()
    {
        
        $columns = array(
                        0 => 'position',
                    );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir   = $this->input->post('order')[0]['dir'];

        $totalData =  $this->dbmodel->count_data(
            'kra'
        );

        $totalFiltered = $totalData;

        if(empty($this->input->post('search')['value'])) {
            // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);   
        $datas = $this->dbmodel->get_all_data(
            'kra',   //table
            '*',    // field selected
            "ASC", // order by ASC or DESC
            "position", // order by field
            $limit, // per page
            $start,  // start of the page
            null,
            null
        ); 
        } else {
            $search = $this->input->post('search')['value'];
            $datas  =  $this->dbmodel->dt_get_where_like(
                'kra',   // table
                '*',    // field selected
                array('field1' => 'position'), // like field
                array($search), // like field value
                array('field1' => 'position'), // like field
                array($search), // or like field value
                array('field1' => 'position'),
                array($search),
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start, // start of the page
                null,
                null,
                null,
                null,
                null
             );

            $totalFiltered = count($datas);
        }

        $data = array();
        if(!empty($datas)) {
            $c = 1;
            foreach ($datas as $list) {

                $kc = $list['kra_code'];
                @$bu = $this->get_bu($list['company_code'],$list['bunit_code']);
               
                @$dep = $this->get_dept($list['company_code'], $list['bunit_code'], $list['dept_code']);
                @$sec = $this->get_sect($list['company_code'], $list['bunit_code'], $list['dept_code'], $list['section_code']);
                @$ssec = $this->get_sub_sect($list['company_code'], $list['bunit_code'], $list['dept_code'], $list['section_code'], $list['subsection_code']);
                @$unit =$this->get_un($list['company_code'], $list['bunit_code'], $list['dept_code'], $list['section_code'], $list['subsection_code'], $list['unit_code']);

                $nestedData['POSITION']               = $list['position'];
                $nestedData['BUSINESS UNIT']          = $bu;
                $nestedData['DEPARTMENT']             = $dep;
                $nestedData['SECTION']                = $sec;
                $nestedData['SUB-SECTION']            = $ssec;
                $nestedData['UNIT']                   = $unit;
                $nestedData['KRA']                    = '<center><a
                                                        class="btn btn-sm btn-success" 
                                                        type="button" href ="view_kra/' .  $kc . '"
                                                        ">View</a></center>';


                $data[] = $nestedData;
            }
        } else {
            $nestedData['Name']             = "No Record Found";
            $nestedData['Effective']        = "No Record Found";
            $nestedData['Transfer From']    = "No Record Found";
            $nestedData['Transder To']      = "No Record Found";
            $nestedData['New Position']     = "No Record Found";
            $nestedData['Direct Sup']       = "No Record Found";
            $nestedData['Transfer']         = "No Record Found";
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);


    }

     public function view_kra($kc)
    {
  
        $data['title']  = $this->title;
        $data['url']    = $this->url;
        // $data['tblApi']     = "placement/masterfile/list_jobtransfer";
        $data['submenu'] = "";
        $data['tabMenu'] = "";

        
        $datas = $this->dbmodel->get_all_data(
            'kra',   //table
            '*',    // field selected
            "ASC", // order by ASC or DESC
            "position", // order by field
            null, // per page
            null,  // start of the page
            null,
            "kra_code = '$kc'"
        );

        foreach ($datas as $list) {
            $data['position'] = $list['position'];
            @$uc = $list['unit_code'];
            @$ssc = $list['subsection_code'];
            @$sc= $list['section_code'];
            @$dc = $list['dept_code'];
            @$bc = $list['bunit_code'];
            @$cc = $list['company_code'];
            $data['summary'] = $list['summary'];
            $data['jobdesc'] = $list['description'];
            $addedby = $list['addedby'];
            $data['dateadded'] = $list['date_added'];
            $updatedby = $list['updatedby'];
            $data['dateupdated'] = $list['date_updated'];
            $data['kc'] = $list['kra_code'];
        }

        
        $data['addedby'] = $this->dbmodel
            ->get_row(
                'employee3',
                'name',
                array(
                    'field1' => 'emp_id'
                ),
                array(
                        $addedby
                )
            );

            
        $data['updatedby'] = $this->dbmodel
                ->get_row(
                'employee3',
                'name',
                array(
                    'field1' => 'emp_id'
                ),
                array(
                        $updatedby
                )
            );

        $data['company'] = @$this->get_company($cc);
        $data['bunit']	= @$this->get_bu($cc, $bc);
        $data['dept'] 	= @$this->get_dept($cc, $bc, $dc);
        $data['section'] = @$this->get_sect($cc, $bc, $dc, $sc);
        $data['subsec'] = @$this->get_sub_sect($cc, $bc, $dc, $sc, $ssc);
        $data['unit']	= @$this->get_un($cc, $bc, $dc, $sc, $ssc, $uc);


        $this->load->view('template/header_placement',$data);
        $this->load->view('placement/masterfile/view_kra',$data);
        $this->load->view('template/footer',$data);

    }

    public function editkra()
    {
       $kc = $this->input->get('kc');

            
        $datas = $this->dbmodel->get_all_data(
            'kra',   //table
            '*',    // field selected
            "ASC", // order by ASC or DESC
            "position", // order by field
            null, // per page
            null,  // start of the page
            null,
            "kra_code = '$kc'"
        );

        foreach ($datas as $list) {
            $data['position'] = $list['position'];
            $data['uc'] = $list['unit_code'];
            $data['ssc'] = $list['subsection_code'];
            $data['sec'] = $list['section_code'];
            $data['dec'] = $list['dept_code'];
            $data['bc'] = $list['bunit_code'];
            $data['cc'] = $list['company_code'];
            $data['summary'] = $list['summary'];
            $data['jobdesc'] = $list['description'];
            $addedby = $list['addedby'];
            $data['dateadded'] = $list['date_added'];
            $updatedby = $list['updatedby'];
            $data['dateupdated'] = $list['date_updated'];
            $data['kc'] = $list['kra_code'];
        }

        
        $data['comp'] = $this->dbmodel
            ->get_all_data(
                'locate_company',
                '*',
                'ASC',
                'company',
                null,
                null,
                null,
                'status = "Active"'
            );


        $data['bunit'] = $this->dbmodel
        ->get_all_data(
            'locate_business_unit',
            '*',
            'ASC',
            'business_unit',
            null,
            null,
            null,
            'status = "Active" and company_code = "' . $data['cc'] . '"'
        );


        $data['dept'] = $this->dbmodel
        ->get_all_data(
            'locate_department',
            '*',
            'ASC',
            'dept_name',
            null,
            null,
            null,
            'status = "Active" and company_code = "' . $data['cc'] . '" and bunit_code = "' . $data['bc'] . '"'
        );


        $data['sect'] = $this->dbmodel
        ->get_all_data(
            'locate_section',
            '*',
            'ASC',
            'section_name',
            null,
            null,
            null,
            'status = "Active" and company_code = "' . $data['cc'] . '" and bunit_code = "' . $data['bc'] . '" and dept_code = "' . $data['dec'] . '"'
        );


        $data['subsect'] = $this->dbmodel
        ->get_all_data(
            'locate_sub_section',
            '*',
            'ASC',
            'sub_section_name',
            null,
            null,
            null,
            'status = "Active" and company_code = "' . $data['cc'] . '" and bunit_code = "' . $data['bc'] . '" and dept_code = "' . $data['dec'] . '" and section_code = "' . $data['sec'] . '"'
        );


        $data['unit'] = $this->dbmodel
        ->get_all_data(
            'locate_unit',
            '*',
            'ASC',
            'unit_name',
            null,
            null,
            null,
            'status = "Active"'
        );



        $data['title']  = $this->title;
        $data['url']    = $this->url;
        // $data['tblApi']     = "placement/masterfile/list_jobtransfer";
        $data['submenu'] = "";
        $data['tabMenu'] = "";

        $this->load->view('template/header_placement',$data);
        $this->load->view('placement/masterfile/edit_kra',$data);
        $this->load->view('template/footer',$data);

    }

    public function update_kra()

    {

       $position = $this->input->post('position');
       $summary = $this->input->post('summary');
       $desc = $this->input->post('desc');
       $cc = $this->input->post('comp_code');
       $bc = $this->input->post('bc'); 
       $dc = $this->input->post('dec');
       $sc = $this->input->post('sec');
       $ssc = $this->input->post('ssc');
       $uc = $this->input->post('uc');
       $dateupdated = date('Y-m-d');
       $updatedby =  $this->session->userdata('emp_id');
       $kc  = $this->input->post('kc');
        $data = array(
            'position' => $position,
            'summary' =>   $summary,
            'description' =>   $desc,
            'company_code' => $cc,
            'bunit_code' => $bc,
            'dept_code' => $dc,
            'section_code' => $sc,
            'subsection_code' => $ssc,
            'unit_code' => $uc,
            'date_updated' => $dateupdated,
            'updatedby' => $updatedby,
        );

        $update = $this->dbmodel->update(
            "kra",
            $data,
            "kra_code = '" . $kc . "'"
        );

        if($update) {
            echo json_encode([
                'status' => 200,
                'response' => "success",
                'modal_close'   => "true",
                'response_message'  => "The KRA Information was successfully updated.",
            ]);
        } else {
            echo json_encode([
                'status' => 401,
                'response'	=> "error",
                'response_message'	=> "Unable to update the KRA Information."
            ]);
        }

    }
    
    public function add_kra()
    {
       
        $data['title']  = $this->title;
        $data['url']    = $this->url;
        // $data['tblApi']     = "placement/masterfile/list_jobtransfer";
        $data['submenu'] = "";
        $data['tabMenu'] = "";

        
        $data['comp'] = $this->dbmodel
               ->get_all_data(
               'locate_company',
               '*',
               'ASC',
               'company',
               null,
               null,
               null,
               'status = "Active"'
           );


        $this->load->view('template/header_placement',$data);
        $this->load->view('placement/masterfile/add_kra',$data);
        $this->load->view('template/footer',$data);

    }

    public function insert_kra()
    {
        $position = $this->input->post('position');
        $summary = $this->input->post('summary');
        $desc = $this->input->post('desc');
        $cc = $this->input->post('comp_code');
        $bc = $this->input->post('bunit_code');
        $dc = $this->input->post('dept_code');
        $sc = $this->input->post('sec_code');
        $ssc = $this->input->post('ssec_code');
        $uc = $this->input->post('unit_code');

        if($uc != ""){ 		$code = $uc; }
        else if($ssc !=""){ 	$code = $ssc;}
        else if($sc !=""){ 	$code = $sc; }
        else if($dc !=""){ 	$code = $dc; }
        else if($bc !=""){ 	$code = $bc; }
        else if($cc != ""){ 	$code = $cc; }
        else{ $code = '';}
        
        
        $cd = explode(".",$code);

         @$company = $cd[0];
         @$bunit = $cd[1];
         @$dept = $cd[2];
         @$sec = ($cd[3])?$cd[3]: '';
         @$ssec = ($cd[4])?$cd[4]:'';
         @$unit = ($cd[5])?$cd[5]:'';
        
        $dateadded = date('Y-m-d');
        $dateupdated = date('Y-m-d');
        $addedby =  $this->session->userdata('emp_id');
        $updatedby =  $this->session->userdata('emp_id');
        $kc  = $this->input->post('kc');

        $check = $this->db->query("SELECT kra_code from kra where position='$position' and company_code = '$company' and bunit_code='$bunit' and dept_code='$dept' and section_code='$sec' and subsection_code='$ssec' and unit_code='$unit' ");
		
		$checkposition =$this->db->query("SELECT position from positions where position = '$position' ");
		
		if($checkposition->num_rows() == 0){
			$this->db->query("INSERT INTO positions (`position`) values('$position') ");
		}
		if($check->num_rows() > 0){
			echo "The KRA of the position $position ALREADY EXIST. Kindly add another.";
		}else{

            $data = array(
                'position' => $position,
                'summary' =>   $summary,
                'description' =>   $desc,
                'company_code' => $company,
                'bunit_code' => $bunit,
                'dept_code' => $dept,
                'section_code' => $sec,
                'subsection_code' => $ssec,
                'unit_code' => $unit,
                'date_added' => $dateadded,
                'date_updated' => $dateupdated,
                'addedby' =>  $addedby,
                'updatedby' => $updatedby,
            );

            $insert = $this->dbmodel->add(
                "kra",
                $data
            );

            if($insert) {
                       
                echo json_encode([
                    'status' => 200,
                    'response' => "success",
                    'redirect' => 'placement/masterfile/kra',
                    'modal_close'   => "true",
                    'response_message'  => "The KRA Information was successfully Inserted.",
                ]);
            } else {
                echo json_encode([
                    'status' => 401,
                    'response'	=> "error",
                    'response_message'	=> "Unable to insert the KRA Information."
                ]);
            }
        }

    }


    
    public function msword_kra()
    {
       $kc = $this->input->get('kc');

            
        $datas = $this->dbmodel->get_all_data(
            'kra',   //table
            '*',    // field selected
            "ASC", // order by ASC or DESC
            "position", // order by field
            null, // per page
            null,  // start of the page
            null,
            "kra_code = '$kc'"
        );

        foreach ($datas as $list) {
            $data['position'] = $list['position'];
            $uc = $list['unit_code'];
            $ssc = $list['subsection_code'];
            $sec = $list['section_code'];
            $dec = $list['dept_code'];
            $bc = $list['bunit_code'];
            $cc = $list['company_code'];
            $data['summary'] = $list['summary'];
            $data['jobdesc'] = $list['description'];
            $data['kc'] = $list['kra_code'];
        }

        $data['company'] = @$this->get_company($cc);
        $data['bunit']	= @$this->get_bu($cc, $bc);
        $data['dept'] 	= @$this->get_dept($cc, $bc, $dec);
        $data['section'] = @$this->get_sect($cc, $bc, $dec, $sec);
        $data['subsec'] = @$this->get_sub_sect($cc, $bc, $dec, $sec, $ssc);
        $data['unit']	= @$this->get_un($cc, $bc, $dec, $sec, $ssc, $uc);
        
        $this->load->view('placement/msword/msword_kra',$data);
        
    }

     public function loyalty_awardees()
        {

            $data['title']  = $this->title;
            $data['url']    = $this->url;
            // $data['tblApi']     = "masterfile/listemployees";
            $data['submenu'] = "";
            $data['tabMenu'] = "";


            $this->load->view('template/header_placement', $data);
            $this->load->view('placement/masterfile/loyalty_awardees');
            $this->load->view('template/footer', $data);
        }

          public function loyalty_entry()
            {
            
                $this->load->view('placement/masterfile/loyalty_entry');
                
            }


            public function findEmployee()
               
            {  
                $key = addslashes($this->input->post('str'));
                $val = "";

                
                $datas = $this->dbmodel->get_all_data(
                    'employee3',   //table
                    '*',    // field selected
                    "ASC", // order by ASC or DESC
                    "name", // order by field
                    10, // per page
                    null,  // start of the page
                    null,
                   'current_status = "Active"  AND (name like "%' . $key . '%" or emp_id = "' . $key . '")'
                );

                    foreach ($datas as $n) {
                    $empId = $n['emp_id'];
                    $name  = ucwords(strtolower($n['name']));
                    if($val != $empId) {
                        echo "<a class = \"nameFind\" href = \"javascript:void\" onclick='getEmpId(\"$empId*$name\")'>[ " . $empId . " ] = " . $name . "</a></br>";
                    } else {
                        echo "<a class = \"afont\" href = \"javascript:void\">No Result Found</a></br>";
                    }
                }
            }

            // public function findEmployee_loyalty()
               
            // {  
            //     $key = addslashes($this->input->post('str'));
            //     $val = "";

                                
            //     $datas = $this->dbmodel->get_data_by_join(
            //         "loyalty_awardees as l",
            //         array("table1" => "employee3 as e"),
            //         array("e.emp_id = l.emp_id"),
            //         "e.emp_id,
            //         e.name,
            //         e.current_status",
            //         null,
            //         null,
            //         null,   // order by field
            //         null, // order by ASC or DESC
            //         10, // per page
            //         null,  // start of the page
            //         'e.current_status = "Active"  AND (e.name like "%' . $key . '%" or e.emp_id = "' . $key . '")'
            //     );

            //     foreach ($datas as $n) {
            //     $empId = $n['emp_id'];
            //     $name  = ucwords(strtolower($n['name']));
            //     if($val != $empId) {
            //         echo "<a class = \"nameFind\" href = \"javascript:void\" onclick='getEmpId(\"$empId*$name\")'>[ " . $empId . " ] = " . $name . "</a></br>";
            //     } else {
            //         echo "<a class = \"afont\" href = \"javascript:void\">No Result Found</a></br>";
            //     }
            //     }
            // }

        public function insert_loyalty_entries()
        {
                    $empId = $this->input->post('empid');
                    $id = explode("*", $empId);
                    $empid = $id[0];
                    $yrsinservice = $this->input->post('yrsinservice');
                    $yrawarded = $this->input->post('yrawarded');
                    $dateadded= date('Y-m-d H:i:s');
                    $addedby = $this->session->userdata('emp_id');

                    $data = array(
                            'emp_id' => $empid,
                            'yrsinservice' =>   $yrsinservice,
                            'year' =>   $yrawarded,
                            'addedby' => $addedby,
                            'dateadded' => $dateadded,
                        );

                    $insert = $this->dbmodel->add(
                        "loyalty_awardees",
                        $data
                    );

                    if($insert) {
                        echo json_encode([
                            'status' => 200,
                            'response' => "success",
                            'redirect' => "placement/masterfile/loyalty_awardees",
                            'modal_close'   => "true",
                            'response_message'  => "The New Loyalty Awardee was successfully updated.",
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 401,
                            'response'	=> "error",
                            'response_message'	=> "Unable to Insert the New Loyalty Awardee."
                        ]);
                    }

                }


        public function loyalty_list()

        {
                
            // $data['title']  = $this->title;
            // $data['url']    = $this->url;
            $data['tblApi']     = "placement/masterfile/list_loyalty";
            // $data['submenu'] = "";
            // $data['tabMenu'] = "";

           
           
            // $this->load->view('placement/masterfile/loyalty_list',$data);
            // $this->load->view('template/header_placement', $data);
            $this->load->view('placement/masterfile/loyalty_list',$data);
            // $this->load->view('template/footer', $data);
                
         }
         
        public function list_loyalty()
        {

            $year = date('Y')-1;

            $columns = array(
            0 => 'name',
            );

            $limit = $this->input->post('length');
            $start = $this->input->post('start');
            $order = @$columns[$this->input->post('order')[0]['column']];
            $dir   = $this->input->post('order')[0]['dir'];

            
                $totalData =  $this->dbmodel->count_data(
                    'loyalty_awardees as ly1',
                    null,
                    array("table1" => "employee3 as emp1"),
                    array("ly1.emp_id = emp1.emp_id"),
                    array(
                    'field1' => 'ly1.year'
                     ),
                    array(
                    $year
                    )
                );

            $totalFiltered = $totalData;

            if(empty($this->input->post('search')['value'])) {
                // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
            $datas = $this->dbmodel->get_data_by_join(
                "loyalty_awardees as ly1",
                array("table1" => "employee3 as emp1"),
                array("ly1.emp_id = emp1.emp_id"),
                "emp1.emp_id, 
                emp1.name, 
                emp1.position, 
                emp1.poslevel, 
                emp1.current_status, 
                emp1.company_code, 
                emp1.bunit_code, 
                emp1.dept_code, 
                emp1.section_code, 
                ly1.yrsinservice, 
                ly1.year, 
                ly1.ringsize, 
                ly1.l_no",
                null,
                null,
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start,  // start of the page
                "year = $year"
            );

            } else {
                $search = $this->input->post('search')['value'];

                $datas  =  $this->dbmodel->dt_get_where_like(
                    "loyalty_awardees as ly1",
                    "emp1.emp_id, 
                    emp1.name, 
                    emp1.position, 
                    emp1.poslevel, 
                    emp1.current_status, 
                    emp1.company_code, 
                    emp1.bunit_code, 
                    emp1.dept_code, 
                    emp1.section_code, 
                    ly1.yrsinservice, 
                    ly1.year, 
                    ly1.ringsize, 
                    ly1.l_no",
                    array('field1' => 'emp1.name'), // like field
                    array($search), // like field value
                    null, // like field
                    null, // like field value
                    array('field2' => 'emp1.name'),   // or like field
                    array($search), // or like field value
                    $dir,   // order by field
                    $order, // order by ASC or DESC
                    $limit, // per page
                    $start,  // start of the page
                    array("table1" => "employee3 as emp1"),
                    array("ly1.emp_id = emp1.emp_id"),
                    null,
                    null,
                    "year = $year"
                );

                $totalFiltered = count($datas);
            }

            $data = array();
            if(!empty($datas)) {
                $c = 1;
                foreach ($datas as $list) {
                    $empId = $list['emp_id'];
                    $lno = $list['l_no'];
                    @$comp = $this->get_comp($list['company_code']);
                    @$bu = $this->get_bunit($list['company_code'], $list['bunit_code']);
                    @$dep = $this->get_dept($list['company_code'], $list['bunit_code'], $list['dept_code']);
                    $datehired= $this->dbmodel->get_field("date_hired","application_details","app_id = '$list[emp_id]' ")['date_hired'];
                    
                    $nestedData['NAME']                   = ucwords(strtolower($list['name']));
                    $nestedData['DEPARTMENT']                   = $comp."/".$bu."/".$dep;
                    $nestedData['POSITION']               = $list['position'];
                    $nestedData['STATUS']                 = $list['current_status'];
                    $nestedData['DATEHIRED']              = $datehired;
                    $nestedData['YRSINSERVICE']                 = $list['yrsinservice'];
                    $nestedData['YEAR']                   = $list['year'];
                    $nestedData['ACTION']                 = '<center><a
                                                                type="button"
                                                                modal-size=""
                                                                modal-route="placement/masterfile/update_loyalty_awardees"
                                                                modal-form="placement/masterfile/edit_loyalty_awardees/' . $lno . '"
                                                                modal-skeleton="0"
                                                                modal-id=""
                                                                modal-atype="POST"
                                                                modal-title="UPDATE ENTRY ON LOYALTY AWARDEES " 
                                                                onclick="modal(event)"><i class="ion-edit tx-20 text-danger">
                                                            </a></center>';

                    $data[] = $nestedData;
                }
            } else {
                $nestedData['NAME']             = "No Record Found";
                $nestedData['DEPARTMENT']        = "No Record Found";
                $nestedData['POSITION']    = "No Record Found";
                $nestedData['STATUS']      = "No Record Found";
                $nestedData['DATEHIRED']     = "No Record Found";
                $nestedData['YRSINSERVICE']       = "No Record Found";
                $nestedData['YEAR']         = "No Record Found";
                $nestedData['ACTION']         = "No Record Found";
                $data[] = $nestedData;
            }

            $json_data = array(
                "draw"            => intval($this->input->post('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data
            );

            echo json_encode($json_data);

        }

     public function filter_by_year_loyalty($year)
    {

            if ($year) {
                $yr = $year;
            } else {
                $yr = date('Y')-1;
            }

            $columns = array(
            0 => 'name',
            );

            $limit = $this->input->post('length');
            $start = $this->input->post('start');
            $order = @$columns[$this->input->post('order')[0]['column']];
            $dir   = $this->input->post('order')[0]['dir'];


            $totalData =  $this->dbmodel->count_data(
                'loyalty_awardees as ly1',
                null,
                array("table1" => "employee3 as emp1"),
                array("ly1.emp_id = emp1.emp_id"),
                array(
                'field1' => 'ly1.year'
                ),
                array(
                $yr
                )
            );

            $totalFiltered = $totalData;

            if(empty($this->input->post('search')['value'])) {
                // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
                $datas = $this->dbmodel->get_data_by_join(
                    "loyalty_awardees as ly1",
                    array("table1" => "employee3 as emp1"),
                    array("ly1.emp_id = emp1.emp_id"),
                    "emp1.emp_id, 
                            emp1.name, 
                            emp1.position, 
                            emp1.poslevel, 
                            emp1.current_status, 
                            emp1.company_code, 
                            emp1.bunit_code, 
                            emp1.dept_code, 
                            emp1.section_code, 
                            ly1.yrsinservice, 
                            ly1.year, 
                            ly1.ringsize, 
                            ly1.l_no",
                    null,
                    null,
                    $dir,   // order by field
                    $order, // order by ASC or DESC
                    $limit, // per page
                    $start,  // start of the page
                    "year = $yr"
                );

            } else {
                $search = $this->input->post('search')['value'];

                $datas  =  $this->dbmodel->dt_get_where_like(
                    "loyalty_awardees as ly1",
                    "emp1.emp_id, 
                                emp1.name, 
                                emp1.position, 
                                emp1.poslevel, 
                                emp1.current_status, 
                                emp1.company_code, 
                                emp1.bunit_code, 
                                emp1.dept_code, 
                                emp1.section_code, 
                                ly1.yrsinservice, 
                                ly1.year, 
                                ly1.ringsize, 
                                ly1.l_no",
                    array('field1' => 'emp1.name'), // like field
                    array($search), // like field value
                    null, // like field
                    null, // like field value
                    array('field2' => 'emp1.name'),   // or like field
                    array($search), // or like field value
                    $dir,   // order by field
                    $order, // order by ASC or DESC
                    $limit, // per page
                    $start,  // start of the page
                    array("table1" => "employee3 as emp1"),
                    array("ly1.emp_id = emp1.emp_id"),
                    null,
                    null,
                    "year = $yr"
                );

                $totalFiltered = count($datas);
            }

            $data = array();
            if(!empty($datas)) {
                $c = 1;
                foreach ($datas as $list) {
                    $empId = $list['emp_id'];
                    $lno = $list['l_no'];
                    @$comp = $this->get_comp($list['company_code']);
                    @$bu = $this->get_bunit($list['company_code'], $list['bunit_code']);
                    @$dep = $this->get_dept($list['company_code'], $list['bunit_code'], $list['dept_code']);
                    $datehired = $this->dbmodel->get_field("date_hired", "application_details", "app_id = '$list[emp_id]' ")['date_hired'];

                    $nestedData['NAME']                   = ucwords(strtolower($list['name']));
                    $nestedData['DEPARTMENT']                   = $comp . "/" . $bu . "/" . $dep;
                    $nestedData['POSITION']               = $list['position'];
                    $nestedData['STATUS']                 = $list['current_status'];
                    $nestedData['DATEHIRED']              = $datehired;
                    $nestedData['YRSINSERVICE']                 = $list['yrsinservice'];
                    $nestedData['YEAR']                   = $list['year'];
                    $nestedData['ACTION']                 = '<center><a
                                                                type="button"
                                                                modal-size=""
                                                                modal-route="placement/masterfile/update_loyalty_awardees"
                                                                modal-form="placement/masterfile/edit_loyalty_awardees/'. $lno .'"
                                                                modal-skeleton="0"
                                                                modal-id=""
                                                                modal-atype="POST"
                                                                modal-title="UPDATE ENTRY ON LOYALTY AWARDEES " 
                                                                onclick="modal(event)"><i class="ion-edit tx-20 text-danger">
                                                            </a></center>';
                    $data[] = $nestedData;
                }
            } else {
                $nestedData['NAME']             = "No Record Found";
                $nestedData['DEPARTMENT']        = "No Record Found";
                $nestedData['POSITION']    = "No Record Found";
                $nestedData['STATUS']      = "No Record Found";
                $nestedData['DATEHIRED']     = "No Record Found";
                $nestedData['YRSINSERVICE']       = "No Record Found";
                $nestedData['YEAR']         = "No Record Found";
                $nestedData['ACTION']         = "No Record Found";
                $data[] = $nestedData;
            }

            $json_data = array(
                "draw"            => intval($this->input->post('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data
            );

            echo json_encode($json_data);

    }

    public function edit_loyalty_awardees($lno)

    {
        
        $datas = $this->dbmodel->get_data_by_join(
            "loyalty_awardees as ly1",
            array("table1" => "employee3 as emp1"),
            array("emp1.emp_id = ly1.emp_id"),
            "emp1.emp_id, 
            emp1.name, 
            ly1.yrsinservice, 
            ly1.year,
            ly1.emp_id",
            null,
            null,
            null,   // order by field
            null, // order by ASC or DESC
            null, // per page
           null,  // start of the page
            "l_no = '$lno'"
        );

        foreach($datas as $list)
        {
            $data['name'] = $list['name'];
            $data['service'] = $list['yrsinservice'];

            // echo $data['service'];
            $data['awarded'] = $list['year'];
        }
          $data['lno'] = $lno;
        
        $this->load->view('placement/masterfile/modal_loyalty',$data);

    }

    public function update_loyalty_awardees()
    {
        
            $data = array(
                'yrsinservice' => $this->input->post('yrsinservice'),
                'year' => $this->input->post('yrawarded'),
            );

            $update = $this->dbmodel->update(
                "loyalty_awardees",
                $data,
                "l_no = '" . $this->input->post('lno') . "'"
            );

            if($update) {
                echo json_encode([
                    'status' => 200,
                    'response' => "success",
                    'modal_close'   => "true",
                    'response_message'  => "The Loyalty Awardees was successfully updated.",
                ]);
            } else {
                echo json_encode([
                    'status' => 401,
                    'response'	=> "error",
                    'response_message'	=> "Unable to update the Loyalty Awardees."
                ]);
            }


    }


        
    public function loyalty_search()
        {
        
            $this->load->view('placement/masterfile/loyalty_search');
            
        }

    public function viewresult_loyalty()
    {
          $empid = $this->input->post('empid');
          $empId= explode("*",$empid);

          
        $data['loyal'] = $this->dbmodel->get_data_by_join(
            "employee3 as e",
            array("table1" => "loyalty_awardees as l"),
            array("e.emp_id = l.emp_id"),
            "e.emp_id,
            e.name,
            e.position,
            e.poslevel, 
            e.emp_type, 
            e.current_status, 
            e.company_code, 
            e.bunit_code, 
            e.dept_code, 
            e.section_code, 
            l.yrsinservice, 
            l.year",
            null,
            null,
            null,   // order by field
            null, // order by ASC or DESC
            null, // per page
            null,  // start of the page
            "e.emp_id = '$empId[0]'"
        );
        $this->load->view('placement/masterfile/loyalty_search_result',$data);

    }

    public function loyalty_report()
    {
    
        $this->load->view('placement/masterfile/loyalty_report');
        
    }

     public function loyalty_report_xls()
    {
      $yrinservice = $this->input->get('yrsinservice');
      $yrfrom = $this->input->get('yrfrom');
      $yrto = $this->input->get('yrto');  
        $data['yrsinservice'] = $this->input->get('yrsinservice');
        $data['yrfrom'] = $this->input->get('yrfrom');
        $data['yrto'] = $this->input->get('yrto');
        
    $data['loyalreport'] = $this->dbmodel->get_data_by_join(
        "employee3 as e",
        array("table1" => "loyalty_awardees as l"),
        array("e.emp_id = l.emp_id"),
        "e.emp_id,
                e.name,
                e.position,
                e.poslevel, 
                e.emp_type, 
                e.current_status, 
                e.company_code, 
                e.bunit_code, 
                e.dept_code, 
                e.section_code, 
                l.yrsinservice, 
                l.year",
                null,
                null,
                null,   // order by field
                null, // order by ASC or DESC
                null, // per page
                null,  // start of the page
                "l.yrsinservice = '$yrinservice' AND l.year BETWEEN '$yrfrom' AND '$yrto'  ORDER BY e.name,l.year, e.company_code, e.bunit_code, e.dept_code "
            );
        $this->load->view('placement/excel/xls_loyalty',$data);
        
    }


    public function solo_parent()

    {
    
        $data['title']  = $this->title;
        $data['url']    = $this->url;
        $data['tblApi']     = "placement/masterfile/list_solo_parent";
        $data['submenu'] = "";
        $data['tabMenu'] = "";



        $this->load->view('template/header_placement', $data);
        $this->load->view('placement/masterfile/list_solo_parent', $data);
        $this->load->view('template/footer', $data);

    }


    public function list_solo_parent ()
    {
           $year = date('Y');
            
            $columns = array(
            0 => 'name',
            );

            $limit = $this->input->post('length');
            $start = $this->input->post('start');
            $order = @$columns[$this->input->post('order')[0]['column']];
            $dir   = $this->input->post('order')[0]['dir'];
            

            $totalData =  $this->dbmodel->count_data(
                'solo_parent as s1',
                "YEAR(s1.date_entry) like '$year%'",
                array("table1" => "employee3 as e1"),
                array("e1.emp_id = s1.emp_id"),
                null,
                null
            );


            $totalFiltered = $totalData;

            if(empty($this->input->post('search')['value'])) {
               
                $datas = $this->dbmodel->get_data_by_join(
                    "solo_parent as s1",
                    array("table1" => "employee3 as e1"),
                    array("e1.emp_id = s1.emp_id"),
                    "e1.name,
                    e1.emp_id, 
                    e1.position, 
                    e1.emp_type, 
                    e1.current_status,
                    s1.entry_by, 
                    s1.date_entry,
                    s1.date_expiry, 
                    s1.emp_id,
                    s1.solo_id,
                    s1.status",
                    null,
                    null,
                    $dir,   // order by field
                    $order, // order by ASC or DESC
                    $limit, // per page
                    $start,  // start of the page
                    "YEAR(s1.date_entry) like '$year%'"
                );

            } else {
                $search = $this->input->post('search')['value'];

                
                $datas  =  $this->dbmodel->dt_get_where_like(
                    "solo_parent as s1",
                    "e1.name,
                    e1.emp_id, 
                    e1.position, 
                    e1.emp_type, 
                    e1.current_status,
                    s1.entry_by, 
                    s1.date_entry,
                    s1.date_expiry, 
                    s1.emp_id,
                    s1.solo_id,
                    s1.status",
                    array('field1' => 'e1.name'), // like field
                    array($search), // like field value
                    null, // like field
                    null, // like field value
                    array('field2' => 'e1.name'),   // or like field
                    array($search), // or like field value
                    $dir,   // order by field
                    $order, // order by ASC or DESC
                    $limit, // per page
                    $start,  // start of the page
                    array("table1" => "employee3 as e1"),
                    array("e1.emp_id = s1.emp_id"),
                    null,
                    null,
                    "YEAR(s1.date_entry) like '$year%'"
                );

                $totalFiltered = count($datas);
            }

            $data = array();
            if(!empty($datas)) {
                $c = 1;
                foreach ($datas as $list) {
                    $entryby	= $this->dbmodel->get_field("name", "employee3", " emp_id = '$list[entry_by]' ")['name'];
                    $date_entry	=date("m/d/Y", strtotime($list['date_entry']));
                    $date_expiry = date("m/d/Y", strtotime($list['date_expiry']));
                    $cstatus = $list['current_status'];
                    $empId = $list['emp_id'];
                    $soloid = $list['solo_id'];

                    
                                    
                    switch ($cstatus) {
                        case 'Active':
                            $cstatus = '<span class="badge badge-sm badge-success"> ' . $cstatus . ' </span>';
                            break;
                        case 'Resigned':
                        case 'V-Resigned':
                        case 'Ad-Resigned':
                        case 'End of Contract':
                            $cstatus = '<span class="badge badge-sm badge-warning"> ' . $cstatus . ' </span>';
                            break;
                        case 'blacklisted':
                            $cstatus = '<span class="badge badge-sm badge-danger"> ' . $cstatus . ' </span>';
                            break;
                        default:
                            $cstatus = $cstatus;
                            break;
                    }

                    switch ($list['status']) {
                        case 'active':
                            $status = '<span class="badge badge-sm badge-success">' . $list['status'] . '</span>';
                            break;
                        case 'inactive':
                            $status = '<span class="badge badge-sm badge-danger">' . $list['status'] . '</span>';
                            break;
                    }

                            $action = '
                                <div class="btn-group" role="group" aria-label="Basic example">
                            ';
                            if(strtotime($list['date_expiry']) < strtotime(date('Y-m-d'))){  
                                $action.= '
                                            <button
                                                type="button"
                                                class="btn btn-primary btn-sm"
                                                modal-size=""
                                                modal-route="placement/masterfile/update_solo_parent"
                                                modal-form="placement/masterfile/renew_solo_parent/'.$empId.'"
                                                modal-skeleton="0"
                                                modal-id=""
                                                modal-atype="POST"
                                                modal-title="Renew Solo Parent" 
                                                onclick="modal(event)">
                                               Renew?
                                            </button>';

                                if($list['status'] == 'active') {
                                        $action.= '
                                                    <button
                                                        type="button"
                                                        class="btn btn-danger btn-sm"
                                                        data-swal-route="placement/masterfile/solo_parent_change_status" 
                                                        data-swal-id="'.$soloid.'" 
                                                        data-swal-message="Are you sure to inactive this Solo Parent record?" 
                                                        onclick="certify(event)">
                                                        Inactive
                                                    </button>';
                                }

                            } else {
                                $action = '';
                            }

                            $action.= '</div>';



                   
                        $nestedData['EMPID']                   = $list['emp_id'];
                        $nestedData['NAME']                   = ucwords(strtolower($list['name']));
                        $nestedData['EMPTYPE']               = $list['emp_type'];
                        $nestedData['POSITION']                 = $list['position'];
                        $nestedData['C&nbsp;STATUS']              = '<center>'.$cstatus.'</center>';
                        $nestedData['DATE&nbsp;ENTRY']                 = $date_entry;
                        $nestedData['DATE&nbsp;EXPIRY']                   = $date_expiry;
                        $nestedData['ENTRY&nbsp;BY']                   = $entryby;
                        $nestedData['STATUS']                   = '<center>'.$status.'</center>';
                        $nestedData['ACTION']                 =  $action;
                        $data[] = $nestedData;

                            }
                        } else {
                            $nestedData['EMPID']             = "No Record Found";
                            $nestedData['NAME']              = "No Record Found";
                            $nestedData['EMPTYPE']           = "No Record Found";
                            $nestedData['POSITION']          = "No Record Found";
                            $nestedData['CURRENT STATUS']    = "No Record Found";
                            $nestedData['DATE ENTRY']        = "No Record Found";
                            $nestedData['DATE EXPIRY']       = "No Record Found";
                            $nestedData['ENTRY BY']          = "No Record Found";
                            $nestedData['STATUS']            = "No Record Found";
                            $nestedData['ACTION']            = "No Record Found";
                            $data[] = $nestedData;
                        }

                        $json_data = array(
                            "draw"            => intval($this->input->post('draw')),
                            "recordsTotal"    => intval($totalData),
                            "recordsFiltered" => intval($totalFiltered),
                            "data"            => $data
                        );

                        echo json_encode($json_data);

    }

    public function filter_solo_parent($year)

        {
              
                $columns = array(
                        0 => 'name',
                        );

                $limit = $this->input->post('length');
                $start = $this->input->post('start');
                $order = @$columns[$this->input->post('order')[0]['column']];
                $dir   = $this->input->post('order')[0]['dir'];


                $totalData =  $this->dbmodel->count_data(
                    'solo_parent as s1',
                    "YEAR(s1.date_entry) like '$year%'",
                    array("table1" => "employee3 as e1"),
                    array("e1.emp_id = s1.emp_id"),
                    null,
                    null
                );


                $totalFiltered = $totalData;

                if(empty($this->input->post('search')['value'])) {

                    $datas = $this->dbmodel->get_data_by_join(
                        "solo_parent as s1",
                        array("table1" => "employee3 as e1"),
                        array("e1.emp_id = s1.emp_id"),
                        "e1.name,
                                    e1.emp_id, 
                                    e1.position, 
                                    e1.emp_type, 
                                    e1.current_status,
                                    s1.entry_by, 
                                    s1.date_entry,
                                    s1.date_expiry, 
                                    s1.emp_id,
                                    s1.solo_id,
                                    s1.status",
                        null,
                        null,
                        $dir,   // order by field
                        $order, // order by ASC or DESC
                        $limit, // per page
                        $start,  // start of the page
                        "YEAR(s1.date_entry) like '$year%'"

                    );

                } else {
                    $search = $this->input->post('search')['value'];


                    $datas  =  $this->dbmodel->dt_get_where_like(
                        "solo_parent as s1",
                        "e1.name,
                                    e1.emp_id, 
                                    e1.position, 
                                    e1.emp_type, 
                                    e1.current_status,
                                    s1.entry_by, 
                                    s1.date_entry,
                                    s1.date_expiry, 
                                    s1.emp_id,
                                    s1.solo_id,
                                    s1.status",
                        array('field1' => 'e1.name'), // like field
                        array($search), // like field value
                        null, // like field
                        null, // like field value
                        array('field2' => 'e1.name'),   // or like field
                        array($search), // or like field value
                        $dir,   // order by field
                        $order, // order by ASC or DESC
                        $limit, // per page
                        $start,  // start of the page
                        array("table1" => "employee3 as e1"),
                        array("e1.emp_id = s1.emp_id"),
                        null,
                        null,
                        "YEAR(s1.date_entry) like '$year%'"
                    );

                    $totalFiltered = count($datas);
                }

                $data = array();
                if(!empty($datas)) {
                    $c = 1;
                    foreach ($datas as $list) {
                        $entryby	= $this->dbmodel->get_field("name", "employee3", " emp_id = '$list[entry_by]' ")['name'];
                        $date_entry	= date("m/d/Y", strtotime($list['date_entry']));
                        $date_expiry = date("m/d/Y", strtotime($list['date_expiry']));
                        $cstatus = $list['current_status'];
                        $empId = $list['emp_id'];
                        $soloid = $list['solo_id'];



                        switch ($cstatus) {
                            case 'Active':
                                $cstatus = '<span class="badge badge-sm badge-success"> ' . $cstatus . ' </span>';
                                break;
                            case 'Resigned':
                            case 'V-Resigned':
                            case 'Ad-Resigned':
                            case 'End of Contract':
                                $cstatus = '<span class="badge badge-sm badge-warning"> ' . $cstatus . ' </span>';
                                break;
                            case 'blacklisted':
                                $cstatus = '<span class="badge badge-sm badge-danger"> ' . $cstatus . ' </span>';
                                break;
                            default:
                                $cstatus = $cstatus;
                                break;
                        }

                        switch ($list['status']) {
                            case 'active':
                                $status = '<span class="badge badge-sm badge-success">' . $list['status'] . '</span>';
                                break;
                            case 'inactive':
                                $status = '<span class="badge badge-sm badge-danger">' . $list['status'] . '</span>';
                                break;
                        }

                        $action = '
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                            ';
                        if(strtotime($list['date_expiry']) < strtotime(date('Y-m-d'))) {
                            $action .= '
                                                            <button
                                                                type="button"
                                                                class="btn btn-primary btn-sm"
                                                                modal-size=""
                                                                modal-route="placement/masterfile/update_solo_parent"
                                                                modal-form="placement/masterfile/renew_solo_parent/' . $empId . '"
                                                                modal-skeleton="0"
                                                                modal-id=""
                                                                modal-atype="POST"
                                                                modal-title="Renew Solo Parent" 
                                                                onclick="modal(event)">
                                                            Renew?
                                                            </button>';

                            if($list['status'] == 'active') {
                                $action .= '
                                                                    <button
                                                                        type="button"
                                                                        class="btn btn-danger btn-sm"
                                                                        data-swal-route="placement/masterfile/solo_parent_change_status" 
                                                                        data-swal-id="' . $soloid . '" 
                                                                        data-swal-message="Are you sure to inactive this Solo Parent record?" 
                                                                        onclick="certify(event)">
                                                                        Inactive
                                                                    </button>';
                            }

                        } else {
                            $action = '';
                        }

                        $action .= '</div>';




                        $nestedData['EMPID']                   = $list['emp_id'];
                        $nestedData['NAME']                   = ucwords(strtolower($list['name']));
                        $nestedData['EMPTYPE']               = $list['emp_type'];
                        $nestedData['POSITION']                 = $list['position'];
                        $nestedData['C&nbsp;STATUS']              = '<center>' . $cstatus . '</center>';
                        $nestedData['DATE&nbsp;ENTRY']                 = $date_entry;
                        $nestedData['DATE&nbsp;EXPIRY']                   = $date_expiry;
                        $nestedData['ENTRY&nbsp;BY']                   = $entryby;
                        $nestedData['STATUS']                   = '<center>' . $status . '</center>';
                        $nestedData['ACTION']                 =  $action;
                        $data[] = $nestedData;

                    }
                } else {
                    $nestedData['EMPID']             = "No Record Found";
                    $nestedData['NAME']              = "No Record Found";
                    $nestedData['EMPTYPE']           = "No Record Found";
                    $nestedData['POSITION']          = "No Record Found";
                    $nestedData['CURRENT STATUS']    = "No Record Found";
                    $nestedData['DATE ENTRY']        = "No Record Found";
                    $nestedData['DATE EXPIRY']       = "No Record Found";
                    $nestedData['ENTRY BY']          = "No Record Found";
                    $nestedData['STATUS']            = "No Record Found";
                    $nestedData['ACTION']            = "No Record Found";
                    $data[] = $nestedData;
                }

                $json_data = array(
                    "draw"            => intval($this->input->post('draw')),
                    "recordsTotal"    => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data"            => $data
                );

                echo json_encode($json_data);


        }

    public function renew_solo_parent($empId)

    { 
        $data['empid'] = $empId;
     $this->load->view('placement/masterfile/renew_solo_parent',$data);
        
    }

     public function update_solo_parent()

    { 
        $soloparent = $this->input->post('soloempid');
        $dateexpiry = date("Y-m-d", strtotime($this->input->post('dateexpiry')));
        $entryby 	= $this->session->userdata('emp_id');
        $entrydate 	= date('Y-m-d');
        $status 	= "active"; //default
       
        if(!isset($_FILES['dswdid']['tmp_name'])) {
            echo "";
        } else {
            $image = addslashes(file_get_contents($_FILES['dswdid']['tmp_name']));
            $image_name = addslashes($_FILES['dswdid']['name']);
            $array = explode(".", $_FILES["dswdid"]["name"]);
            $fimage = "document/soloparent/" . $soloparent . "=" . date('Y-m-d') . "=" . "SoloParentID" . "=" . date('H-i-s-A') . "." . $array[1];
           move_uploaded_file($_FILES["dswdid"]["tmp_name"], $fimage);

            $select = $this->db->query("SELECT * FROM solo_parent where emp_id = '$soloparent' and status = 'active' limit 1 ");
            $rows 	= $select->row_array();
            $solo_id = $rows['solo_id'];

            $rletter_image = "";
                if($select->num_rows() == 0) {

                    $data = array(
                                    'status' => 'inactive',
                                );

                    $update = $this->dbmodel->update(
                        "solo_parent",
                        $data,
                        "solo_id = '" . $solo_id . "'"
                    );

                    $data = array(
                        'emp_id' => $soloparent,
                        'date_entry' =>   $entrydate,
                        'date_expiry' =>   $dateexpiry,
                        'dswd_id' => $fimage,
                        'request_letter' => $rletter_image,
                        'entry_by' => $entryby,
                        'status' => $status,
                    );

                    $insert = $this->dbmodel->add(
                        "solo_parent",
                        $data
                    );


                    if($insert) {
                        echo json_encode([
                            'status' => 200,
                            'response' => "success",
                            'modal_close'   => "true",
                            'response_message'  => "Solo Parent Renewed Successfully",
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 401,
                            'response'	=> "error",
                            'response_message'	=> "Unable to proceed to renew request due to error."
                        ]);
                    }
                }else{
                     echo json_encode([
                            'status' => 401,
                            'response'	=> "error",
                            'response_message'	=> "Cannot add more. There's an existing active Solo Parent ID"
                        ]);

                }

        }
        
    }

    public function solo_parent_change_status()

    {
        
    // $query = mysql_query("DELETE FROM employee_transfer_details where transfer_no = '$transno' and emp_id = '$empid' ") or die
        $soloid = $this->input->post('id');
        $status = 'inactive';
        $datein 	= date('Y-m-d');
        $updatedby 	= $this->session->userdata('emp_id');

        $data = array(
                'status' => $status,
                'date_inactive' => $datein,
                'updated_by' => $updatedby,
            );

        $update = $this->dbmodel->update(
            "solo_parent",
            $data,
            "solo_id = '" . $soloid . "'"
        );


        if($update) {
            echo json_encode([
                'status' => 200,
                'response' => "success",
                 'dtable'  => true,
                'response_message'  => "Change Status Successful!",
            ]);
        } else {
            echo json_encode([
                'status' => 401,
                'response'	=> "error",
                'response_message'	=> "Change Status Failed!"
            ]);
        }

    }

    public function eligibilities()

    {
    
        $data['title']  = $this->title;
        $data['url']    = $this->url;
        $data['tblApi']     = "placement/masterfile/list_of_eligibilities";
        $data['submenu'] = "";
        $data['tabMenu'] = "";

        $this->load->view('template/header_placement', $data);
        $this->load->view('placement/masterfile/list_eligibilities', $data);
        $this->load->view('template/footer', $data);

    }

    public function list_of_eligibilities()

    {
        
        $columns = array(
            0 => 'el_name',
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir   = $this->input->post('order')[0]['dir'];

        $totalData =  $this->dbmodel->count_data(
            'eligibility'
        );

        $totalFiltered = $totalData;

        if(empty($this->input->post('search')['value'])) {
            // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
            $datas = $this->dbmodel->get_all_data(
                'eligibility',   //table
                '*',    // field selected
                "ASC", // order by ASC or DESC
                "el_name", // order by field
                $limit, // per page
                $start,  // start of the page
                null,
                null
            );
        } else {
            $search = $this->input->post('search')['value'];
            $datas  =  $this->dbmodel->dt_get_where_like(
                'eligibility',   // table
                '*',    // field selected
                array('field1' => 'el_name'), // like field
                array($search), // like field value
                array('field1' => 'el_name'), // like field
                array($search), // or like field value
                array('field1' => 'el_name'),
                array($search),
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start, // start of the page
                null,
                null,
                null,
                null,
                null
            );

            $totalFiltered = count($datas);
        }

        $data = array();
        if(!empty($datas)) {
            $c = 1;
            foreach ($datas as $list) {
                $lno = $list['el_no'];
                $nestedData['No']                        = $list['el_no'];
                $nestedData['ELIGIBILITY NAME']          = '<a
                                                href= "#"
                                                modal-size="modal-lg"
                                                modal-route=""
                                                modal-dtapi="placement/masterfile/certified_employee_list/'.$lno.'"
                                                modal-form="placement/masterfile/modal_eligibilities/'.$lno.'"
                                                modal-skeleton="0"
                                                modal-id=""
                                                modal-atype="POST"
                                                modal-button = "false"
                                                modal-title="Eligible Employees" 
                                                onclick="modal(event)">
                                               '.$list['el_name'].'
                                                </a>';
               
                $nestedData['DISPLAY']                   = $list['el_display'];
                $nestedData['ACTION']                    = '<center><a
                                                            href= "#"
                                                            modal-size=""
                                                            modal-route="placement/masterfile/update_eligibilities"
                                                            modal-dtapi=""
                                                            modal-form="placement/masterfile/modal_edit_eligibilities/'.$lno.'"
                                                            modal-skeleton="0"
                                                            modal-id=""
                                                            modal-atype="POST"
                                                            modal-title="Edit Eligibility" 
                                                            onclick="modal(event)"><i class="icon ion-compose fa-2x"></i>
                                                            </a></center>';



                $data[] = $nestedData;
            }
        } else {
            $nestedData['No']             = "No Record Found";
            $nestedData['ELIGIBILITY NAME']        = "No Record Found";
            $nestedData['DISPLAY']    = "No Record Found";
            $nestedData['ACTION']      = "No Record Found";
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);

    }

    public function modal_eligibilities($lno)
     {
        $data['lno'] = $lno;
        $this->load->view('placement/masterfile/modal_eligibilities',$data);

     }

     public function certified_employee_list($lno)
     {

        $name = $this->dbmodel->get_field("el_name", "eligibility", "el_no = '$lno' ")['el_name'];
     
            $columns = array(
                        0 => 'name',
                    );

            $limit = $this->input->post('length');
            $start = $this->input->post('start');
            $order = @$columns[$this->input->post('order')[0]['column']];
            $dir   = $this->input->post('order')[0]['dir'];

            
            $totalData =  $this->dbmodel->count_data(
                'application_seminarsandeligibility as ap1',
                null,
                array("table1" => "employee3 as emp1"),
                array("emp1.emp_id = ap1.app_id"),
                array( 
                        'field1' => 'ap1.name',
                        'field2' => 'emp1.current_status'
                    ),
                array(  
                        $name,
                        'Active'
                        )
                 );


            $totalFiltered = $totalData;

            if(empty($this->input->post('search')['value'])) {

                $datas = $this->dbmodel->get_data_by_join(
                    "application_seminarsandeligibility as ap1",
                    array("table1" => "employee3 as emp1"),
                    array("emp1.emp_id = ap1.app_id"),
                    " ap1.app_id,
                      emp1.name, 
                      emp1.emp_id,
                      ap1.dates, 
                      emp1.company_code,
                      emp1.bunit_code, 
                      emp1.dept_code, 
                      emp1.emp_type, 
                      emp1.current_status, 
                      emp1.position",
                    null,
                    null,
                    $dir,   // order by field
                    $order, // order by ASC or DESC
                    $limit, // per page
                    $start, // start of the page
                    "emp1.current_status = 'Active' AND ap1.name = '$name'"
                );

            } else {
                $search = $this->input->post('search')['value'];

                $datas  =  $this->dbmodel->dt_get_where_like(
                    "application_seminarsandeligibility as ap1",
                    " ap1.app_id,
                      emp1.name, 
                      emp1.emp_id, 
                      ap1.dates, 
                      emp1.company_code,
                      emp1.bunit_code, 
                      emp1.dept_code, 
                      emp1.current_status,
                      emp1.emp_type, 
                      emp1.position",
                    array('field1' => 'emp1.name'), // like field
                    array($search), // like field value
                    null, // like field
                    null, // like field value
                    array('field2' => 'emp1.name'),   // or like field
                    array($search), // or like field value
                    $dir,   // order by field
                    $order, // order by ASC or DESC
                    $limit, // per page
                    $start,  // start of the page
                    array("table1" => "employee3 as emp1"),
                    array("emp1.emp_id = ap1.app_id"),
                    null,
                    null,
                    "emp1.current_status = 'Active' AND ap1.name = '$name'"
                );

                $totalFiltered = count($datas);
            }


            $data = array();
            if(!empty($datas)) {
                $c = 1;
                foreach ($datas as $list) {
                @$comp = $this->get_comp($list['company_code']);
                @$bu = $this->get_bunit($list['company_code'], $list['bunit_code']);
                @$dep = $this->get_dept($list['company_code'], $list['bunit_code'], $list['dept_code']);
                    // $lno = $list['el_no'];
                    $nestedData['HRMSID']                        = $list['emp_id'];
                    $nestedData['Eligibility Name']          = $list['name'];
                    $nestedData['POSITION']                   = $list['position'];
                    $nestedData['BUSINESS UNIT']                    = $bu;
                    $nestedData['DEPARTMENT']                    = $dep;


                    $data[] = $nestedData;
                }
            } else {
                $nestedData['HRMSID']             = "No Record Found";
                $nestedData['Eligibility Name']        = "No Record Found";
                $nestedData['POSITION']    = "No Record Found";
                $nestedData['BU']      = "No Record Found";
                $nestedData['DEPT']     = "No Record Found";
                $data[] = $nestedData;
            }

            $json_data = array(
                "draw"            => intval($this->input->post('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data
            );

            echo json_encode($json_data);

     }

     public function modal_edit_eligibilities($lno)
     {
        $data['lno'] = $lno;
        $datas = $this->dbmodel->get_all_data(
            'eligibility',   //table
            '*',    // field selected
            null, // order by ASC or DESC
            null, // order by field
            null, // per page
            null,  // start of the page
            null,
            "el_no = '$lno'"
            
        );
        foreach($datas as $list){
            $data['eligb_name'] = $list['el_name'];
            $data['eligb_display'] = $list['el_display'];
        }

        $this->load->view('placement/masterfile/modal_edit_eligibilities',$data);

         }

        public function update_eligibilities()

        {
            
            $name = $this->input->post('updatename');
            $display = $this->input->post('updatedisplay');
            $elno = $this->input->post('elno');
            // $status = 'inactive';
            // $datein 	= date('Y-m-d');
            // $updatedby 	= $this->session->userdata('emp_id');

            $data = array(
                    'el_name' => $name,
                    'el_display' => $display
                );

            $update = $this->dbmodel->update(
                "eligibility",
                $data,
                "el_no = '" . $elno . "'"
            );

            if($update) {
                echo json_encode([
                    'status' => 200,
                    'response' => "success",
                    // 'dtable'  => 'true',
                    'redirect'  => 'placement/masterfile/eligibilities',
                    'response_message'  => "Eligibility is Successfully Updated",
                ]);
            } else {
                echo json_encode([
                    'status' => 401,
                    'response'	=> "error",
                    'response_message'	=> "Eligibility Update Failed!"
                ]);
            }
        }

        public function add_modal_eligibilities()

        {
        
        
         $this->load->view('placement/masterfile/add_eligibility_modal');

        }

        public function insert_eligibilities()

        {
            
            $name = $this->input->post('eligName');
            $display = $this->input->post('eligDisplay');      
            $data = array(
                    'el_name' => $name,
                    'el_display' => $display
                );

            $insert = $this->dbmodel->add(
                "eligibility",
                $data
            );

            if($insert) {

                echo json_encode([
                    'status' => 200,
                    'response' => "success",
                    'redirect' => 'placement/masterfile/eligibilities',
                    'modal_close'   => "true",
                    'response_message'  => "The Eligibility was successfully Inserted.",
                ]);
            } else {
                echo json_encode([
                    'status' => 401,
                    'response'	=> "error",
                    'response_message'	=> "Unable to insert the Eligibility."
                ]);
            }
        }

        public function excel_eligibilities()

        {

            
            $data['datas'] = $this->dbmodel->get_data_by_join(
                "application_seminarsandeligibility as ap1",
                array("table1" => "employee3 as emp1","table2" => "eligibility as e1"),
                array("ap1.app_id = emp1.emp_id","ap1.name = e1.el_name"),
                  " ap1.app_id,
                    emp1.name, 
                    emp1.emp_id,
                    ap1.dates, 
                    emp1.company_code,
                    emp1.bunit_code, 
                    emp1.dept_code, 
                    emp1.emp_type,
                    emp1.position, 
                    emp1.current_status,
                    e1.el_name, 
                    e1.el_display,  
                    emp1.position",
                    null,
                    null,
                    null,   // order by field
                    null, // order by ASC or DESC
                    null, // per page
                    null, // start of the page
                    "emp1.current_status = 'Active'"
            );    
            $this->load->view('placement/excel/xls_eligibilities',$data);
        }

       public function xls_per_licensed($lno)
        {

            $elno = $lno;

            $name= $this->dbmodel->get_field("el_name", "eligibility", "el_no = '".$elno."' ")['el_name'];
            $data['name'] = $name;

            
            $data['datas'] = $this->dbmodel->get_data_by_join(
                "application_seminarsandeligibility as ap1",
                array("table1" => "employee3 as emp1","table2" => "eligibility as e1"),
                array("ap1.app_id = emp1.emp_id","ap1.name = e1.el_name"),
              " ap1.app_id,
                emp1.name, 
                emp1.emp_id,
                ap1.dates, 
                emp1.company_code,
                emp1.bunit_code, 
                emp1.dept_code, 
                emp1.emp_type,
                emp1.position, 
                emp1.current_status,
                e1.el_name, 
                e1.el_display,  
                emp1.position",
                null,
                null,
                null,   // order by field
                null, // order by ASC or DESC
                null, // per page
                null, // start of the page
                "emp1.current_status = 'Active' AND ap1.name = '$name'"
            );
            $this->load->view('placement/excel/xls_per_licensed',$data);

        }

        public function position_leveling()

        {
   
            $data['title']  = $this->title;
            $data['url']    = $this->url;
            $data['tblApi']     = "placement/masterfile/list_position_leveling";
            $data['submenu'] = "";
            $data['tabMenu'] = "";

            $this->load->view('template/header_placement',$data);
            $this->load->view('placement/masterfile/list_position_leveling');
            $this->load->view('template/footer',$data);
        }

        public function list_position_leveling()
        {
                        
            $columns = array(
                    0 => 'position_title',
                );

            $limit = $this->input->post('length');
            $start = $this->input->post('start');
            $order = @$columns[$this->input->post('order')[0]['column']];
            $dir   = $this->input->post('order')[0]['dir'];

            $totalData =  $this->dbmodel->count_data(
                'position_leveling',
                "position_title !=''"
            );

            $totalFiltered = $totalData;

            if(empty($this->input->post('search')['value'])) {
                // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
                $datas = $this->dbmodel->get_all_data(
                    'position_leveling',   //table
                    '*',    // field selected
                    "ASC", // order by ASC or DESC
                    "level", // order by field
                    $limit, // per page
                    $start,  // start of the page
                    null,
                    null
                );
            } else {
                $search = $this->input->post('search')['value'];
                $datas  =  $this->dbmodel->dt_get_where_like(
                    'position_leveling',   // table
                    '*',    // field selected
                    array('field1' => 'level'), // like field
                    array($search), // like field value
                    array('field1' => 'position_title'), // like field
                    array($search), // or like field value
                    array('field1' => 'position_title'),
                    array($search),
                    $dir,   // order by field
                    $order, // order by ASC or DESC
                    $limit, // per page
                    $start, // start of the page
                    null,
                    null,
                    null,
                    null,
                    null
                );

                $totalFiltered = count($datas);
            }

            $data = array();
            if(!empty($datas)) {
                $c = 1;
                foreach ($datas as $list) {
                    $countposquery = $this->db->select('COUNT(position) as position_count')
                        ->from('employee3')
                        ->where('position', $list['position_title'])
                        ->where('current_status', 'Active')
                        ->where('company_code !=', '07')
                        ->get();

                    $rpos = $countposquery->row_array();
                    $positioncount = $rpos['position_count'];

                    $position = urlencode($list['position_title']);

                    // $lno = $list['el_no'];
                    $nestedData['LEVEL']                         = $list['level'];
                    $nestedData['LVLNO']                         = $list['lvlno'];
                    $nestedData['POSITION TITLE']                = '<a
                                                                    href= "#"
                                                                    modal-size="modal-xl wd-1000" 
                                                                    modal-route=""
                                                                    modal-dtapi="placement/masterfile/list_per_position_leveling/'. $position.'"
                                                                    modal-form="placement/masterfile/modal_position_leveling/'. $position.'"
                                                                    modal-skeleton="0"
                                                                    modal-id=""
                                                                    modal-atype="POST"
                                                                    modal-button = "false"
                                                                    modal-title="'.urldecode($position).'" txt-13
                                                                    onclick="modal(event)">
                                                                    '.$list['position_title'].' 
                                                                </a>';
                    $nestedData['COUNT']                         = $positioncount;
                    $nestedData['USED BY']                       = $list['used_by'];
                    $nestedData['TYPE']                          = $list['type'];
                    $nestedData['CATEGORY']                      = $list['category'];
                    $nestedData['ACTION']                        = '';
                    
                    
                    $data[] = $nestedData;
                }
            } else {
                $nestedData['LEVEL']             = "No Record Found";
                $nestedData['LVLNO']        = "No Record Found";
                $nestedData['POSITION TITLE']    = "No Record Found";
                $nestedData['COUNT']      = "No Record Found";
                $nestedData['USED BY']      = "No Record Found";
                $nestedData['TYPE']      = "No Record Found";
                $nestedData['CATEGORY']      = "No Record Found";
                $nestedData['ACTION']      = "No Record Found";
                $data[] = $nestedData;
            }

            $json_data = array(
                "draw"            => intval($this->input->post('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data
            );

            echo json_encode($json_data);


        }

        public function modal_position_leveling($position)
        {
              $data['pos'] = urldecode($position);

            $this->load->view('placement/masterfile/modal_position_leveling',$data);

        }


        public function list_per_position_leveling($position)

          {  
           
                $columns = array(
                            0 => 'name',
                        );

                $limit = $this->input->post('length');
                $start = $this->input->post('start');
                $order = @$columns[$this->input->post('order')[0]['column']];
                $dir   = $this->input->post('order')[0]['dir'];


                $totalData =  $this->dbmodel->count_data(
                    'employee3',
                    'position = "' . urldecode($position) . '" AND current_status = "Active" AND company_code !="07" and coe != "nesco"'
                );


                $totalFiltered = $totalData;

                if(empty($this->input->post('search')['value'])) {

                $datas =  $this->dbmodel->get_all_data(
                        'employee3',
                        'emp_id,name,position, company_code, bunit_code, dept_code, section_code, current_status, emp_type, poslevel',
                        "ASC", // order by ASC or DESC
                        "name", // order by field
                        $limit, // per page
                        $start,  // start of the page
                        null,
                        'position = "' . urldecode($position) . '" AND current_status = "Active" AND company_code !="07" and coe != "nesco" '
                    );

                } else {
                    $search = $this->input->post('search')['value'];
                                        
                    $datas  =  $this->dbmodel->dt_get_where_like(
                        'employee3',   // table
                        '*',    // field selected
                        array('field1' => 'emp_id','field2' => 'name','field3' => 'emp_type'), // like field
                        array($search,$search,$search,$search), // like field value
                        array('field1' => 'emp_id','field2' => 'name','field3' => 'emp_type'), // like field
                        array($search,$search,$search,$search), // or like field value
                        array('field1' => 'emp_id','field2' => 'name','field3' => 'emp_type'),
                        array($search,$search,$search,$search),
                        $dir,   // order by field
                        $order, // order by ASC or DESC
                        $limit, // per page
                        $start, // start of the page
                        null,
                        null,
                        null,
                        null,
                         null,
                        'position = "' . urldecode($position) . '" AND current_status = "Active" AND company_code !="07" and coe != "nesco" '
                    );

                    $totalFiltered = count($datas);
                }

                $data = array();
                if(!empty($datas)) {
                    $c = 1;
                    foreach ($datas as $list) {
                        @$comp = $this->get_comp($list['company_code']);
                        @$bu = $this->get_bunit($list['company_code'], $list['bunit_code']);
                        @$dep = $this->get_dept($list['company_code'], $list['bunit_code'], $list['dept_code']);
                        @$sec = $this->get_sect($list['company_code'], $list['bunit_code'], $list['dept_code'], $list['section_code']);
                        $datehired = $this->dbmodel->get_field("date_hired", "application_details", "app_id = '$list[emp_id]' ")['date_hired'];
                        // $lno = $list['el_no'];
                        $nestedData['EMPID']                       = $list['emp_id'];
                        $nestedData['NAME']                        = $list['name'];
                        $nestedData['POSITION']                    = $list['position'];
                        $nestedData['LVL']                         = $list['poslevel'];
                        $nestedData['EMPTYPE']                     = $list['emp_type'];
                        $nestedData['DATEHIRED']                   = $datehired;
                        $nestedData['BUSINESS UNIT']               = $bu;
                        $nestedData['DEPARTMENT']                  = $dep;
                        $nestedData['SECTION']                     = $sec;


                        $data[] = $nestedData;
                    }
                } else {
                    $nestedData['EMPID']             = "";
                    $nestedData['NAME']        = "";
                    $nestedData['POSITION']    = "";
                    $nestedData['LVL']      = "";
                    $nestedData['EMPTYPE']     = "No Record Found";
                    $nestedData['DATEHIRED']     = "";
                    $nestedData['BUSINESS UNIT']     = "";
                    $nestedData['DEPARTMENT']     = "";
                    $nestedData['SECTION']     = ""; 
                    $data[] = $nestedData;
                }

                $json_data = array(
                    "draw"            => intval($this->input->post('draw')),
                    "recordsTotal"    => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data"            => $data
                );

                echo json_encode($json_data);
        }


        public function position_leveling_add_position()
        {
            $this->load->view('placement/masterfile/modal_addposition_leveling');
        }

        public function insert_new_position_leveling() 

        {
            $position = $this->input->post('position');
            $level = $this->input->post('level');
            $lvlno = $this->input->post('lvlno');
            // Assuming $this->db has been properly loaded in your controller or model.

            $this->db->select('COUNT(position_title) as countpos');
            $this->db->from('position_leveling');
            $this->db->where('position_title', $position);
            $query = $this->db->get();
            $rowq = $query->row_array();
            $countpos = $rowq['countpos'];

            if($countpos == 0){ // insert checking position if exist 
                $data = array(
                    'position_title' => $position,
                    'level' =>   $level,
                    'lvlno' =>   $lvlno
                    
                );

            $insert = $this->dbmodel->add(
                "position_leveling",
                $data
            );

            if($insert) {

                echo json_encode([
                    'status' => 200,
                    'response' => "success",
                    'redirect' => 'placement/masterfile/position_leveling',
                    'modal_close'   => "true",
                    'response_message'  => "The Position was successfully Inserted.",
                ]);
                } else {
                echo json_encode([
                    'status' => 401,
                    'response'	=> "error",
                    'response_message'	=> "Unable to insert the Position."
                ]);
                 }
                }else{
                echo json_encode([
                    'status' => 401,
                    'response'	=> "error",
                    'response_message'	=> "The position $position ALREADY EXIST. Kindly add another."
                ]);
            }
        }

        public function excel_position_level()

        {
            $this->load->view('placement/excel/xls_allpositionlevel');
        }

        public function excel_position_level_per_pos($pos)

        {
            $data['pos'] = urldecode($pos);

            $this->load->view('placement/excel/xls_allposition_per_pos',$data);
        }

        public function position_old()
        {

            $data['title']  = $this->title;
            $data['url']    = $this->url;
            $data['tblApi']     = "";
            $data['submenu'] = "";
            $data['tabMenu'] = "";


            $this->load->view('template/header_placement', $data);
            $this->load->view('placement/masterfile/old_position');
            $this->load->view('template/footer', $data);
        }

        

         public function position_new_updated()
        {

            $data['title']  = $this->title;
            $data['url']    = $this->url;
            $data['tblApi']     = "placement/masterfile/list_new_position_updated";
            $data['submenu'] = "";
            $data['tabMenu'] = "";


            $this->load->view('template/header_placement', $data);
            $this->load->view('placement/masterfile/new_position_updated');
            $this->load->view('template/footer', $data);
        }


        public function list_new_position_updated()

        {
            
            $columns = array(
                0 => 'name',
            );

            $limit = $this->input->post('length');
            $start = $this->input->post('start');
            $order = @$columns[$this->input->post('order')[0]['column']];
            $dir   = $this->input->post('order')[0]['dir'];


            
            $totalData =  $this->dbmodel->count_data(
                'position_change_logs as pcl1',
                null,
                array("table1" => "employee3 as e1"),
                array("pcl1.emp_id = e1.emp_id"),
                array(
                            'field1' => 'e1.current_status'
                        ),
                array(
                            'Active'
                            )
                );


            $totalFiltered = $totalData;

            if(empty($this->input->post('search')['value'])) {

            $datas = $this->dbmodel->get_data_by_join(
                "position_change_logs as pcl1",
                array("table1" => "employee3 as e1"),
                array("pcl1.emp_id = e1.emp_id"),
                "pcl1.pcl_no,
                pcl1.emp_id, 
                e1.name, 
                e1.position, 
                e1.poslevel,
                e1.emp_type,  
                e1.bunit_code,  
                e1.dept_code,  
                pcl1.updateby,  
                pcl1.dateupdated,  
                pcl1.oldpos,  
                pcl1.newpos,  
                e1.company_code",
                array(
                    'field1' => 'e1.current_status'
                ),
                array(
                    'Active'
                ),
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start  // start of the page
            );


            } else {
                $search = $this->input->post('search')['value'];   
                $datas  =  $this->dbmodel->dt_get_where_like(
                    "position_change_logs as pcl1",
                   "pcl1.pcl_no,
                    pcl1.emp_id, 
                    e1.name, 
                    e1.position, 
                    e1.poslevel,
                    e1.emp_type,  
                    e1.bunit_code,  
                    e1.dept_code,  
                    pcl1.updateby,  
                    pcl1.dateupdated,  
                    pcl1.oldpos,  
                    pcl1.newpos,  
                    e1.company_code",
                    array('field1' => 'e1.name'), // like field
                    array($search), // like field value
                    array('field1' => 'e1.current_status'), // like field
                    array('Active'), // like field value
                    array('field2' => 'e1.name'),   // or like field
                    array($search), // or like field value
                    $dir,   // order by field
                    $order, // order by ASC or DESC
                    $limit, // per page
                    $start,  // start of the page
                    array("table1" => "employee3 as e1"),
                    array("pcl1.emp_id = e1.emp_id")
                );


                $totalFiltered = count($datas);
            }

            $data = array();
            if(!empty($datas)) {
                $c = 1;
                foreach ($datas as $list) {
                    @$comp = $this->get_comp($list['company_code']);
                    @$bu = $this->get_bunit($list['company_code'], $list['bunit_code']);
                    @$dep = $this->get_dept($list['company_code'], $list['bunit_code'], $list['dept_code']);
                    @$sec = $this->get_sect($list['company_code'], $list['bunit_code'], $list['dept_code'], $list['section_code']);
                    $datehired = $this->dbmodel->get_field("date_hired", "application_details", "app_id = '$list[emp_id]' ")['date_hired'];
                    // $lno = $list['el_no'];
                    $nestedData['PNO']                           = $c++;
                    $nestedData['EMPID']                         = $list['emp_id'];
                    $nestedData['NAME']                          = $list['name'];
                    $nestedData['EMPTYPE']                       = $list['emp_type'];
                    $nestedData['OLD POS']                       = $list['oldpos'];
                    $nestedData['NEW POS']                       = $list['newpos'];
                    $nestedData['LEVEL']                         = $list['poslevel'];
                    $nestedData['COMPANY']                       = $comp;
                    $nestedData['BUSINESS&nbsp;UNIT']                 = $bu;
                    $nestedData['UPDATED&nbsp;BY']                    = $list['updateby'];
                    $nestedData['DATE&nbsp;UPDATED']                  = $list['dateupdated'];


                    $data[] = $nestedData;
                }
            } else {
                
                $nestedData['PNO']                       = '';
                $nestedData['EMPID']                        = '';
                $nestedData['NAME']                    = '';
                $nestedData['EMPTYPE']                         ='';
                $nestedData['OLD POS']                     ='';
                $nestedData['NEW POS']                   = 'NO RECORDS FOUND';
                $nestedData['LEVEL']               ='';
                $nestedData['COMPANY']                  = '';
                $nestedData['BUSINESS&nbsp;UNIT']                     = '';
                $nestedData['UPDATED&nbsp;BY']                     = '';
                $nestedData['DATE&nbsp;UPDATED']                     = '';

                $data[] = $nestedData;
                
            }
                    
            $json_data = array(
                "draw"            => intval($this->input->post('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data
            );

            echo json_encode($json_data);


        }

        public function filter_by_level($level)
        {

             
            $columns = array(
                0 => 'position_title',
            );

            $limit = $this->input->post('length');
            $start = $this->input->post('start');
            $order = @$columns[$this->input->post('order')[0]['column']];
            $dir   = $this->input->post('order')[0]['dir'];

            $totalData =  $this->dbmodel->count_data(
                'position_leveling',
                "position_title !='' AND lvlno = '$level'"
            );

            $totalFiltered = $totalData;

            if(empty($this->input->post('search')['value'])) {
                // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
                $datas = $this->dbmodel->get_all_data(
                    'position_leveling',   //table
                    '*',    // field selected
                    "ASC", // order by ASC or DESC
                    "level", // order by field
                    $limit, // per page
                    $start,  // start of the page
                    null,
                    "lvlno = '$level'"
                );
            } else {
                $search = $this->input->post('search')['value'];
                $datas  =  $this->dbmodel->dt_get_where_like(
                    'position_leveling',   // table
                    '*',    // field selected
                    array('field1' => 'level'), // like field
                    array($search), // like field value
                    array('field1' => 'position_title'), // like field
                    array($search), // or like field value
                    array('field1' => 'position_title'),
                    array($search),
                    $dir,   // order by field
                    $order, // order by ASC or DESC
                    $limit, // per page
                    $start, // start of the page
                    null,
                    null,
                    null,
                    null,
                    "lvlno = '$level'"
                );

                $totalFiltered = count($datas);
            }

            $data = array();
            if(!empty($datas)) {
                $c = 1;
                foreach ($datas as $list) {
                    $countposquery = $this->db->select('COUNT(position) as position_count')
                        ->from('employee3')
                        ->where('position', $list['position_title'])
                        ->where('current_status', 'Active')
                        ->where('company_code !=', '07')
                        ->get();

                    $rpos = $countposquery->row_array();
                    $positioncount = $rpos['position_count'];

                    $position = urlencode($list['position_title']);

                    // $lno = $list['el_no'];
                    $nestedData['LEVEL']                         = $list['level'];
                    $nestedData['LVLNO']                         = $list['lvlno'];
                    $nestedData['POSITION TITLE']                = '<a
                                                                                href= "#"
                                                                                modal-size="modal-xl wd-1000" 
                                                                                modal-route=""
                                                                                modal-dtapi="placement/masterfile/list_per_position_leveling/' . $position . '"
                                                                                modal-form="placement/masterfile/modal_position_leveling/' . $position . '"
                                                                                modal-skeleton="0"
                                                                                modal-id=""
                                                                                modal-atype="POST"
                                                                                modal-button = "false"
                                                                                modal-title="' . urldecode($position) . '" txt-13
                                                                                onclick="modal(event)">
                                                                                ' . $list['position_title'] . ' 
                                                                            </a>';
                    $nestedData['COUNT']                         = $positioncount;
                    $nestedData['USED BY']                       = $list['used_by'];
                    $nestedData['TYPE']                          = $list['type'];
                    $nestedData['CATEGORY']                      = $list['category'];
                    $nestedData['ACTION']                        = '';


                    $data[] = $nestedData;
                }
            } else {
                $nestedData['LEVEL']             = "No Record Found";
                $nestedData['LVLNO']        = "No Record Found";
                $nestedData['POSITION TITLE']    = "No Record Found";
                $nestedData['COUNT']      = "No Record Found";
                $nestedData['USED BY']      = "No Record Found";
                $nestedData['TYPE']      = "No Record Found";
                $nestedData['CATEGORY']      = "No Record Found";
                $nestedData['ACTION']      = "No Record Found";
                $data[] = $nestedData;
            }

            $json_data = array(
                "draw"            => intval($this->input->post('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data
            );

            echo json_encode($json_data);

        }

        // -------------------------------------------------------- CREATES MENUS --------------------------------------------------------------

        
        

        
        
}
