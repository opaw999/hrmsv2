<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Manila');
class Accounting extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->checkEmployeeLogin();
        $this->load->model('DB_model','dbmodel');
        $this->title = "HRMS [ ".ucwords(strtolower($this->session->userdata('sessionType')))." ]";
        $this->url   = "http://172.16.161.100/hrms/employee/";
    }

    //check session for the employee login
    private function checkEmployeeLogin(){
        if(!$this->session->userdata('hrmsEmployeeSession')){
            redirect($this->session->userdata('sessionType') . '/dashboard');
        }
    }
  
    //used in form filter of employees as default for company // located in views/pages/accounting/filter_employees.php
    public function form_filter()
    {    
        $data['company'] = $this->dbmodel
            ->get_all_data(
                'locate_company',
                '*',
                'ASC',
                'company',
                null,null,null,
                'status = "Active"'
            );    
        $this->load->view('employee/accounting/modal_filter_employees',$data);   
    }
 
    //calls the load employees default list
    public function employees()
    {        
        $data['title']      = $this->title;
        $data['url']        = $this->url;  
        $data['menu']       = "accounting"; //used in header
        $data['submenu']    = "masterfile"; //used in header
        $data['tblApi']     = "employee/accounting/allemployees"; //find in routes

        $this->load->view('template/header', $data);
        $this->load->view('employee/accounting/employees',$data);
        $this->load->view('template/footer', $data);
    }

    //load all the employees by default page
    public function load_employees()
    {
        //searching columns
        $columns = array( 
            0 =>'emp_id',
            1 =>'name',
            2 =>'emp_type',
            3 =>'position'
        );

        //default
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $totalData =  $this->dbmodel->count_data(
                        'employee3',
                        "current_status = 'Active' and company_code !='07' and 
                        ( emp_type IN 
                        ('Regular','Contractual','PTA','OJT','Back-Up','Probationary','Partimer','Regular Partimer','Summer Job'
                        'NESCO-PTA','NESCO-PTP','NESCO Regular','NESCO Contractual',
                        'NESCO Regular Partimer','NESCO Probationary','NESCO-BACKUP') )
                        "); // (emp_type NOT IN ('CYDEM','Promo','Promo-NESCO','NICO','EasyL','NEMPEX','ENTECH') )

        $totalFiltered = $totalData; 

        //if search box is empty // default load
        if(empty($this->input->post('search')['value'])) {            
                // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
                $datas = $this->dbmodel->get_all_data(
                    'employee3',   //table
                    '*',    // field selected
                    "ASC",  // order by ASC or DESC 
                    "name", // order by field
                    $limit, // per page
                    $start,  // start of the page
                    NULL,
                    "current_status = 'Active' and company_code !='07' and 
                    ( emp_type IN 
                        ('Regular','Contractual','PTA','OJT','Back-Up','Probationary','Partimer','Regular Partimer','Summer Job'
                        'NESCO-PTA','NESCO-PTP','NESCO Regular','NESCO Contractual',
                        'NESCO Regular Partimer','NESCO Probationary','NESCO-BACKUP') )
                        "
                );
        }else{
            //if search value is not empty
            $search = $this->input->post('search')['value']; 
            $datas =  $this->dbmodel->dt_get_where_like(
                'employee3',   // table
                '*, DATE_FORMAT(startdate,"%m/%d/%Y") AS sdate, DATE_FORMAT(eocdate,"%m/%d/%Y") AS edate',    // field selected
                array('field1'=>'emp_id','field2'=>'name','field3'=>'position','field4'=>'emp_type'), // like field
                array($search,$search,$search,$search), // like field value
                array('field1'=>'emp_id','field2'=>'name','field3'=>'position','field4'=>'emp_type'), // like field
                array($search,$search,$search,$search), // or like field value
                array('field1'=>'emp_id','field2'=>'name','field3'=>'position','field4'=>'emp_type'),
                array($search,$search,$search,$search),
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start, // start of the page
                NULL,
                NULL,
                NULL,
                NULL,
                "current_status = 'Active' and company_code !='07' and 
                ( emp_type IN 
                        ('Regular','Contractual','PTA','OJT','Back-Up','Probationary','Partimer','Regular Partimer','Summer Job'
                        'NESCO-PTA','NESCO-PTP','NESCO Regular','NESCO Contractual',
                        'NESCO Regular Partimer','NESCO Probationary','NESCO-BACKUP') )
                "
            );            
            $totalFiltered = count($datas);
        }

        //load data in the table
        $data = array();
        if(!empty($datas)){
            foreach ($datas as $list){               
                $nestedData['EMPID']        = '<a href="'.base_url().'supervisor/profile/'.$list['emp_id'].'" target="_blank">'. $list['emp_id'] .'</a>';
                $nestedData['NAME']         = ucwords(strtolower($list['name']));
                $nestedData['POSITION']     = $list['position'];
                $nestedData['EMPTYPE']      = $list['emp_type'];
                $nestedData['DEPARTMENT']   = @$this->get_bu($list['company_code'],$list['bunit_code'])."
                                                ".@$this->get_dept($list['company_code'],$list['bunit_code'],$list['dept_code'])."
                                                ".@$this->get_section($list['company_code'],$list['bunit_code'],$list['dept_code'],$list['section_code']);
                $nestedData['STATUS']       = $list['current_status'];
                $data[] = $nestedData;
            }
        }else{
            $nestedData['EMPID']        = "No Record Found";
            $nestedData['NAME']         = "";
            $nestedData['POSITION']     = "";
            $nestedData['EMPTYPE']      = "";
            $nestedData['DEPARTMENT']   = "";
            $nestedData['STATUS']       = "";
            $data[] = $nestedData;
        }

        //JSON DEFAULT
        $json_data = array(
            "draw"            => intval($this->input->post('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );
        echo json_encode($json_data);
    }

    //calls the filter employee funtion
    public function filter_employee($code)
    {
        $data['title']      = $this->title;
        $data['url']        = $this->url;  
        $data['menu']       = "accounting"; //used in header
        $data['submenu']    = "filter employees"; //used in header
        $data['tblApi']     = "employee/accounting/filteremployees/".$code; //find in routes

        $this->load->view('template/header', $data);
        $this->load->view('employee/accounting/employees',$data);
        $this->load->view('template/footer', $data);
    }

    //loads the results for filtering employees per location
    public function load_filter_employee($code)
    {
        $ncode  = explode(".",$code);
        $cc	   	= @$ncode[0];
        $bc		= @$ncode[1];
        $dc		= @$ncode[2];
        $sc		= @$ncode[3];
        $ssc	= @$ncode[4];
                
        // get the filtered location
        if($cc != '')
        {		
            $e = "employee3";
            if($ssc !=''){	@$loc = "and $e.company_code = '$cc' and $e.bunit_code = '$bc' and $e.dept_code = '$dc' and $e.section_code = '$sc' and $e.sub_section_code = '$ssc' "; }
            else if($sc !=''){	@$loc = "and $e.company_code = '$cc' and $e.bunit_code = '$bc' and $e.dept_code = '$dc' and $e.section_code = '$sc' ";  }
            else if($dc !=''){	@$loc = "and $e.company_code = '$cc' and $e.bunit_code = '$bc' and $e.dept_code = '$dc' ";  }
            else if($bc !=''){  @$loc = "and $e.company_code = '$cc' and $e.bunit_code = '$bc' "; }    
            else if($cc !=''){  @$loc = "and $e.company_code = '$cc'"; }
            else { $loc = ''; }
        }
        
        //searching columns
        $columns = array( 
            0 =>'name'
        );

        //default
        $limit  = $this->input->post('length');
        $start  = $this->input->post('start');
        $order  = @$columns[$this->input->post('order')[0]['column']];
        $dir    = $this->input->post('order')[0]['dir'];

        $totalData = $this->dbmodel
                        ->count_data('employee3',
                        "current_status = 'Active' and emp_type NOT IN ('CYDEM','Promo','Promo-NESCO','NICO','EasyL','NEMPEX','ENTECH') $loc "
                    );
        $totalFiltered = $totalData; 

        //if search box is empty // default load
        if(empty($this->input->post('search')['value'])) {            
                // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
                $datas = $this->dbmodel->get_all_data(
                    'employee3',   //table
                    '*',    // field selected
                    $dir,   // order by field
                    $order, // order by ASC or DESC
                    $limit, // per page
                    $start,  // start of the page
                    NULL,
                    "current_status = 'Active' and emp_type NOT IN ('CYDEM','Promo','Promo-NESCO','NICO','EasyL','NEMPEX','ENTECH') $loc"
                );
        }else{
            //if search value is not empty
            $search = $this->input->post('search')['value']; 
            $datas =  $this->dbmodel->dt_get_where_like(
                'employee3',   // table
                '*, DATE_FORMAT(startdate,"%m/%d/%Y") AS sdate, DATE_FORMAT(eocdate,"%m/%d/%Y") AS edate',    // field selected
                array('field1'=>'emp_id','field2'=>'name','field3'=>'position','field4'=>'emp_type'), // like field
                array($search,$search,$search,$search), // like field value
                array('field1'=>'emp_id','field2'=>'name','field3'=>'position','field4'=>'emp_type'), // like field
                array($search,$search,$search,$search), // or like field value
                array('field1'=>'emp_id','field2'=>'name','field3'=>'position','field4'=>'emp_type'),
                array($search,$search,$search,$search),
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start, // start of the page
                NULL,
                NULL,
                NULL,
                NULL,
                "current_status = 'Active' and emp_type NOT IN ('CYDEM','Promo','Promo-NESCO','NICO','EasyL','NEMPEX','ENTECH') $loc"
            );            
            $totalFiltered = count($datas);
        }

        //load data in the table
        $data = array();
        if(!empty($datas)){
            foreach ($datas as $list){               
                $nestedData['EMPID']        = '<a href="'.base_url().'supervisor/profile/'.$list['emp_id'].'" target="_blank">'. $list['emp_id'] .'</a>';
                $nestedData['NAME']         = ucwords(strtolower($list['name']));
                $nestedData['POSITION']     = $list['position'];
                $nestedData['EMPTYPE']      = $list['emp_type'];
                $nestedData['DEPARTMENT']   = @$this->get_bu($list['company_code'],$list['bunit_code'])."
                                              ".@$this->get_dept($list['company_code'],$list['bunit_code'],$list['dept_code'])."
                                              ".@$this->get_section($list['company_code'],$list['bunit_code'],$list['dept_code'],$list['section_code']);
                $nestedData['STATUS']       = $list['current_status'];
                $data[] = $nestedData;
            }
        }else{
            $nestedData['EMPID']        = "No Record Found";
            $nestedData['NAME']         = "";
            $nestedData['POSITION']     = "";
            $nestedData['EMPTYPE']      = "";
            $nestedData['DEPARTMENT']   = "";
            $nestedData['STATUS']       = "";
            $data[] = $nestedData;
        }

        //JSON DEFAULT
        $json_data = array(
            "draw"            => intval($this->input->post('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );
        echo json_encode($json_data);
    }

    //get the business unit of the employee
    public function get_bu($cc,$bc)
    {
        $result = $this->dbmodel
            ->get_row(
                'locate_business_unit',
                'business_unit, acroname',
                array( 
                    'field1' => 'company_code',
                    'field2' => 'bunit_code'
                ),
                array( $cc, $bc)
            );
        if($result){
            ($result->acroname) ? $bu = $result->acroname : $bu = $result->business_unit ;
        }else{
            $bu = "";
        }
        return $bu;
    }

    //get the department name of the employee
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

        if(!$dc)
        {
            $dept = "";
        }else if($result->acroname !=''){		
            $dept = " - ".$result->acroname;
        }else{
            $dept = " - ".$result->dept_name;	
        }
        return $dept;  
    }
    
    //get the section name of the employee
    public function get_section($cc, $bc, $dc, $sc)
    {
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
        return " - ".$result->section_name;	 
    }    
}