<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Manila');
class Supervisor extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->checkEmployeeLogin();
        $this->load->model('DB_model','dbmodel');
        $this->title = "Human Resource Management System [ SUPERVISOR ]";
        $this->url   = "http://172.16.161.100/hrms/employee/";
    }
    
    private function checkEmployeeLogin()
    {
		if(! $this->session->userdata('hrmsEmployeeSession')){
			redirect('/'.$this->session->userdata('usertype'));
		}
	}

    public function page_load($page,$menu=NULL,$datas = NULL){
        $data['title']  = $this->title;
        $data['url']    = $this->url;  
        $data['menu']   = $menu;
        $data['submenu']= "";        

        $this->load->view('template/header', $data);
        $this->load->view('employee/'.$page, $data);
        $this->load->view('template/footer', $data);
    }
  
    public function profile($empid){
        $result = $this->dbmodel
            ->get_row(
                'applicant as a',
                '*, DATE_FORMAT(birthdate,"%m/%d/%Y") AS bday, DATE_FORMAT(startdate,"%m/%d/%Y") AS startdate, DATE_FORMAT(eocdate,"%m/%d/%Y") AS eocdate,
                DATE_FORMAT(date_hired,"%m/%d/%Y") AS datehired, DATE_FORMAT(date_applied,"%m/%d/%Y") AS dateapplied,
                e.company_code as ec, e.bunit_code as ebc, e.dept_code as edc, e.section_code as esc',                 
                array( 'field1' => 'emp_id'),
                array( $empid ), 
                array( 'employee3 as e', 'applicant_otherdetails as ao', 'application_details as ad' ),
                array( 'e.emp_id = a.app_id', 'e.emp_id = ao.app_id', 'e.emp_id = ad.app_id' )
            );   
        
        $res_app = $this->dbmodel
            ->get_data_by_join(
                'appraisal_details as a',
                array( 'employmentrecord_ as er',' employee3 as e' ),
                array( 'a.record_no = er.record_no', 'a.rater = e.emp_id' ), 
                '*, name, DATE_FORMAT(er.startdate,"%m/%d/%Y") AS startdate, DATE_FORMAT(er.eocdate,"%m/%d/%Y") AS eocdate, numrate, rater, DATE_FORMAT(ratingdate,"%m/%d/%Y") AS ratingdate',
                array( 'field1' => 'er.emp_id' ),
                array( $empid )
            );     
            
        $data['employee3'] = $this->dbmodel->get_all_data(
                'employee3',
                '*, DATE_FORMAT(startdate,"%m/%d/%Y") AS startdate, DATE_FORMAT(eocdate,"%m/%d/%Y") AS eocdate,',
                null, null, null, null, null,
                "emp_id = '$empid' "
            );
        
        $data['emphistory'] = $this->dbmodel->get_all_data(
                'employmentrecord_',
                '*, DATE_FORMAT(startdate,"%m/%d/%Y") AS sdate, DATE_FORMAT(eocdate,"%m/%d/%Y") AS eocdate,',
                'DESC',
                'startdate',
                null, null, null,
                "emp_id = '$empid' "
            );    
        
        $data['title']      = $this->title;
        $data['url']        = $this->url;
        $data['menu']       = "profile";
        $data['submenu']    = ""; 
        $data['profile']    = $result;
        $data['eocapp']     = $res_app;
        $data['comp']       = $result->ec;
        $data['location']   = $this->get_com($result->ec)."<br>".
                              $this->get_bu($result->ec,$result->ebc)."".
                              $this->get_dept($result->ec,$result->ebc,$result->edc) ."<br>".
                              $this->get_section($result->ec,$result->ebc,$result->edc,$result->esc);

        $this->load->view('template/header', $data);
        $this->load->view('employee/supervisor/profile', $data);
        $this->load->view('template/footer', $data);
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
        return @$result->acroname;        
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
        return @$result->business_unit;   
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
        if(!$sc)        
            return "";
        else    
            return $result->section_name;	 
    }
}