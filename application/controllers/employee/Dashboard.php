<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Manila');
class Dashboard extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->checkEmployeeLogin();
        $this->load->model('DB_model','dbmodel');
        
        $this->title = "HRMS [ ".ucwords(strtolower($this->session->userdata('usertype')))." ]";
        $this->url   = "http://172.16.161.100/hrms/employee/";
       
    }

    private function checkEmployeeLogin()
    {
		if(! $this->session->userdata('hrmsEmployeeSession')){
			redirect('/'.$this->session->userdata('usertype'));
		}
	}

    public function index(){  
        
        $result = $this->dbmodel->get_all_data("ip_phone_directories", "*" );         
        $this->page_load("employee/dashboard","dashboard",$result);
    }

    public function page_load($page,$menu=NULL,$datas = NULL){
        
        $data['title']  = $this->title;
        $data['url']    = $this->url;
        $data['team']   = $datas;
        $data['iphone'] = $datas;
        $data['menu']   = $menu;
        $data['submenu']= "";
        $data['tabMenu']= "";     

        //**************************************************************************************************************//
        //DOCTORS
        $data['doctors']= $this->dbmodel
                    ->get_all_data("hr_clinic.patient_doctor","*",null,null,null,0,null,
                    "status = 'active' ");

        //DOCTORS SKED
        if($menu == "doctor"){
            $data['doctor'] = $this->dbmodel
                ->get_row(
                    '`hr_clinic`.`patient_doctor` as a',
                    '*',
                    array( 'field1' => 'doctorID'),
                    array( $datas )
                ); 

            $data['docSked']= $this->dbmodel
                ->get_all_data("hr_clinic.patient_doctor_sked","*",null,null,null,0,null,
                "doctorID = '$datas' and status = 'Active' ");
        }    


        //**************************************************************************************************************//
        //EPAS
        $emptype = $this->session->userdata('emp_type');
        $company = $emptype = $this->session->userdata('company');
        if($emptype == "Promo" || $emptype == "Promo-NESCO")
        {
            //insert promo code here
            $result = "";
        }
        else if($company == "11" || $company == "12"){
            //insert franchise
            $result = "";
        }else{
            $result = $this->dbmodel
                ->get_row(
                    'appraisal_details as a',
                    'details_id, code, name, rater, ratercomment, numrate,descrate, company_code, bunit_code, dept_code, DATE_FORMAT(ratingdate,"%m/%d/%Y") AS ratingdate',
                    array( 'field1' => 'e.emp_id', 'field2' => 'e.record_no','field3' => 'a.rateeSO'),
                    array( $this->session->userdata('emp_id'), $this->session->userdata('record_no'),'0' ), 
                    array( 'employee3 as e' ),
                    array( 'a.emp_id = e.emp_id' )
                );     
                
            if($result)
            {
                $data['rate']       = 1;            
            }else{
                $data['rate']       = 0; 
            }
        }
        //**************************************************************************************************************//
        //ANNOUNCEMENTS
        $data['announcements']  = $this->dbmodel
            ->get_all_data("announcements","*",null,null,null,0,null,
            "posting_stat = '1' and type = 'announcement' ");

        $data['memos']  = $this->dbmodel
            ->get_all_data("announcements","*",null,null,null,0,null,
            "posting_stat = '1' and type = 'memo' ");    

        if($menu == "announcements"){
            $data['result_announce']= $this->dbmodel
            ->get_row(
                'announcements',
                '*, DATE_FORMAT(date_posted,"%m/%d/%Y %H:%i %a") AS date_posted',
                array( 'field1' => 'am_id'),
                array( $datas )
            );
        }  
        
        if($menu == "memos"){
            $data['result_memos']= $this->dbmodel
            ->get_row(
                'announcements',
                '*, DATE_FORMAT(date_posted,"%m/%d/%Y %H:%i %a") AS date_posted',
                array( 'field1' => 'am_id'),
                array( $datas )
            );
        }  

        $this->load->view('template/header', $data);
        $this->load->view('employee/'.$page, $data); 
        $this->load->view('template/footer', $data);
    }
  
    public function about(){
                //MNUF,MCE,JC,JS,MAC,ZO,
                //AU,RY,RT,JB,LG,DMB
                //JL,JA,QVS,NCC,CB,GS
        $team = array("43864-2013","21114-2013","01022-2014","03553-2013","03399-2013","06359-2013",
                        "00599-2014","01521-2020","00916-2022","01476-2015","19708-2018","02456-2022",
                        "33802-2013","25931-2013","02609-2015","00497-2014","08270-2013","13217-2013");

        foreach($team as $key => $value){
            $result = $this->dbmodel
                ->get_row(
                    'applicant',
                    'photo',
                    array( 'field1' => 'app_id'),
                    array( $value )
                );
            $data[] = $result->photo;
        }   
       
        $this->page_load("dashboard/about","about",$data);
    }

    public function contactus(){
        $this->page_load("dashboard/contactus","contactus");
    }

    public function announcements($amId){
        $this->page_load("dashboard/announcements","announcements",$amId);
    }

    public function memos($amId){
        $this->page_load("dashboard/memos","memos",$amId);
    }

    public function doctor($doctorID){
        
        $this->page_load("dashboard/doctor","doctor",$doctorID);
    }

    public function logout(){
        $emptype = $this->session->userdata('usertype');
        $this->session->sess_destroy();
		redirect('/'.$emptype);
    }
}
