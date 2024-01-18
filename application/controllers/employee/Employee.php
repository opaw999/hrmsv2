<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Manila');
class Employee extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->checkEmployeeLogin();
        $this->load->model('DB_model','dbmodel');
        $this->title = "Human Resource Management System [ ".ucwords(strtolower($this->session->userdata('usertype')))." ]";
        $this->url   = "http://172.16.161.100/hrms/employee/";
    }

    private function checkEmployeeLogin()
    {
		if(! $this->session->userdata('hrmsEmployeeSession')){
			redirect('/'.$this->session->userdata('usertype'));
		}
	}

    public function profile(){
        $result = $this->dbmodel
            ->get_row(
                'applicant as a',
                '*, DATE_FORMAT(birthdate,"%m/%d/%Y") AS bday, DATE_FORMAT(startdate,"%m/%d/%Y") AS startdate, DATE_FORMAT(eocdate,"%m/%d/%Y") AS eocdate,
                e.company_code as ec, e.bunit_code as ebc, e.dept_code as edc, e.section_code as esc',
                array( 'field1' => 'emp_id'),
                array( $this->session->userdata('emp_id') ), 
                array( 'employee3 as e', 'applicant_otherdetails as ao' ),
                array( 'e.emp_id = a.app_id', 'e.emp_id = ao.app_id' )
            );   
        
        $res_app = $this->dbmodel
            ->get_data_by_join(
                'appraisal_details as a',
                array( 'employmentrecord_ as er',' employee3 as e' ),
                array( 'a.record_no = er.record_no', 'a.rater = e.emp_id' ), 
                '*, name, DATE_FORMAT(er.startdate,"%m/%d/%Y") AS startdate, DATE_FORMAT(er.eocdate,"%m/%d/%Y") AS eocdate, numrate, rater, DATE_FORMAT(ratingdate,"%m/%d/%Y") AS ratingdate',
                array( 'field1' => 'er.emp_id' ),
                array( $this->session->userdata('emp_id') )
            );   
        
        $data['appdetails'] = $this->dbmodel
            ->get_row(
                'application_details as a',
                '*, DATE_FORMAT(date_hired,"%m/%d/%Y") AS datehired, DATE_FORMAT(date_applied,"%m/%d/%Y") AS dateapplied',
                array( 'field1' => 'app_id'),
                array( $this->session->userdata('emp_id') )               
            );
        
        
        $data['title']      = $this->title;
        $data['url']        = $this->url;
        $data['menu']       = "profile";
        $data['submenu']    = ""; 
        $data['profile']    = $result;
        $data['eocapp']     = $res_app;
        $data['location']   = $this->get_com($result->ec)."<br>".
                              $this->get_bu($result->ec,$result->ebc)."".
                              $this->get_dept($result->ec,$result->ebc,$result->edc) ."<br>".
                              $this->get_section($result->ec,$result->ebc,$result->edc,$result->esc);

        $this->load->view('template/header', $data);
        $this->load->view('employee/employee/profile', $data);
        $this->load->view('template/footer', $data);
    }

    public function epas()
    {
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
                $data['rater']      = $this->get_rater($result->rater);
                $data['questions']  = $this->get_appraisal($result->details_id);
                $data['location']   = $this->get_bu($result->company_code,$result->bunit_code)." ".$this->get_dept($result->company_code,$result->bunit_code,$result->dept_code);    
            }else{
                $data['rate']       = 0; 
            }
        }
        
            
        $data['photo']      = $this->url."".$this->get_photo($this->session->userdata('emp_id'));
      
        $data['title']      = $this->title;
        $data['url']        = $this->url;
        $data['epas']       = $result;
        $data['menu']       = "myepas";
        $data['submenu']    = ""; 
        $this->load->view('template/header', $data);
        $this->load->view('employee/employee/epas', $data);
        $this->load->view('template/footer', $data);
    }

    public function get_photo($appid)
    {
        $result = $this->dbmodel
            ->get_row(
                'applicant',
                'photo',
                array( 'field1' => 'app_id'),
                array( $appid )
            );       
        return $result->photo;
    }

    public function get_appraisal($did)
    {
        $result = $this->dbmodel
            ->get_data_by_join(
                'appraisal_answer as aa',
                array( 'appraisal as a' ),
                array( 'a.appraisal_id = aa.appraisal_id' ), 
                'q_no,title, rate, description',
                array( 'field1' => 'aa.details_id' ),
                array( $did )
            );          
        return $result;
    }

    public function get_rater($rater)
    {
        $result = $this->dbmodel
            ->get_row(
                'employee3',
                'name',
                array( 'field1' => 'emp_id'),
                array( $rater )
            );
        return $result->name;  
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
            'company_code = "'.$company.'" and status = "active" '
        );
        
        if(count($result) > 0){
            foreach($result as $res){
                $data['data'][]	=	array(
                    "id"     => $company.".".$res['bunit_code'],
                    "location_name"   => $res['business_unit']
                );
            }
        }else{
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
        $id     = explode('.',$this->input->post('id'));
        $company    = $id[0];
        $bunit      = $id[1];        
        
        $result = $this->dbmodel->get_all_data(
            'locate_department',
            'dept_code, dept_name',
            'ASC',
            'dept_name',
            null,
            null,
            null,
            'company_code = "'.$company.'" and bunit_code = "'.$bunit.'" and status = "active" '
        );
        
        if(count($result) > 0){
            foreach($result as $res){
                $data['data'][]	=	array(
                    "id"     => $company.".". $bunit.".".$res['dept_code'],
                    "location_name"   => $res['dept_name']
                );
            }
        }else{
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
        $id = explode('.',$this->input->post('id'));
        $company = $id[0];
        $bunit  = $id[1];
        $dept   = $id[2];
                
        $result = $this->dbmodel->get_all_data(
            'locate_section',
            'section_code, section_name',
            'ASC',
            'section_name',
            null,
            null,
            null,
            'company_code = "'.$company.'" and bunit_code = "'.$bunit.'" and dept_code = "'.$dept.'"'
        );
        
        if(count($result) > 0){
            foreach($result as $res){
                $data['data'][]	=	array(
                    "id"     => $company.".". $bunit.".". $dept.".".$res['section_code'],
                    "location_name"   => $res['section_name']
                );
            }
        }else{
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
        $id = explode('.',$this->input->post('id'));
        $company    = $id[0];
        $bunit      = $id[1];
        $dept       = $id[2];
        $sect       = $id[3];        
        
        $result = $this->dbmodel->get_all_data(
            'locate_sub_section',
            'sub_section_code, sub_section_name',
            'ASC',
            'sub_section_name',
            null,
            null,
            null,
            'company_code = "'.$company.'" and bunit_code = "'.$bunit.'" and dept_code = "'.$dept.'" and section_code = "'.$sect.'" '
        );
        
        if(count($result) > 0){
            foreach($result as $res){
                $data['data'][]	=	array(
                    "id"     => $company.".". $bunit.".". $dept .".". $sect.".".$res['sub_section_code'],
                    "location_name"   => $res['sub_section_name']
                );
            }
        }else{
            $data['data'][]	=	array(
                "id"     => "",
                "location_name"   => ""
            );
        }
        echo json_encode($data);
    }  

    public function get_com($cc)
    {
        $result = $this->dbmodel
            ->get_row(
                'locate_company',
                'company, acroname',
                array( 'field1' => 'company_code'),
                array( $cc )
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
                array( $cc, $bc)
            );
        return $result->business_unit;   
    }

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
        return $result->section_name;	 
    }
}