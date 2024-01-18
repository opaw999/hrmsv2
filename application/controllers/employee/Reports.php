<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

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

    ## DYNAMIC PAGE LOAD
    public function page_load_view()
    {
        $data['title']      = $this->title;
        $data['url']        = $this->url;  
        $data['menu']       = "excel"; 
        $data['submenu']    = "";   

        $this->load->view('template/header', $data);
        $this->load->view('employee/supervisor/excel', $data);
        $this->load->view('template/footer', $data);
    }

    ## GENERATE EMPLOYEE LISTS PER PCC
    public function generate_pcc_employees($pcc)
    {
        $result = $this->dbmodel->get_all_data(
            'employee3',   //table
            '*',    // field selected
            "ASC",  // order by ASC or DESC 
            "name", // order by field
            NULL,   // per page
            NULL,   // start of the page
            NULL,
            "current_status = 'Active' AND pcc = '$pcc' AND
            (emp_type IN('Regular','Probationary','Regular Partimer','Contractual','OJT','Seasonal','Back-Up','PTA','Summer Job','Partimer'))"
        ); 
            
        foreach($result as $row):
            $data['results'][] = array(
                'name'              => $row['name'],
                'emp_id'            => $row['emp_id'],
                'payroll_no'        => $row['payroll_no'],
                'emptype'           => $row['emp_type'],
                'position'          => $row['position'],
                'businessunit'      => $this->get_bu($row['emp_type'],$row['company_code'],$row['bunit_code']),
                'department'        => $this->get_dept($row['emp_type'],$row['company_code'],$row['bunit_code'],$row['dept_code'])
                                       ." ".$this->get_section($row['emp_type'],$row['company_code'],$row['bunit_code'],$row['dept_code'],$row['section_code']),
                'pcc'               => $row['pcc']
            );
        endforeach;
            
        $data['filename']   = "PCC".$pcc."_Employees_".date('Ymd');	
        $data['title']      = "Employee List Under PCC $pcc Report";
        $this->load->view('employee/reports/pcc_employees',$data);    
    }

    ## GENERATE SUBORDINATE LISTS REPORT
    public function generate_subordinates()
    {
        $result = $this->dbmodel
            ->get_data_by_join(
                'employee3 as tbl1',
                array( 'leveling_subordinates as tbl2' ),
                array( 'tbl1.emp_id = tbl2.subordinates_rater' ), 
                '*, name, DATE_FORMAT(tbl1.startdate,"%m/%d/%Y") AS startdate, DATE_FORMAT(tbl1.eocdate,"%m/%d/%Y") AS eocdate',
                array( 'field1' => 'tbl2.ratee', 'field2' => 'tbl1.current_status' ),
                array( $this->session->userdata('emp_id'), 'Active' ),
                'ASC', 
                'position, name'
            );
            
        foreach($result as $row):
            $data['results'][] = array(
                'name'              => $row['name'],
                'emptype'           => $row['emp_type'],
                'position'          => $row['position'],
                'datehired'         => $this->get_datehired($row['emp_id']),
                'yrsinservice'      => $this->years_service( $this->get_datehired($row['emp_id']) ),
                'department'        => $this->get_dept($row['emp_type'],$row['company_code'],$row['bunit_code'],$row['dept_code']),
                'section'           => $this->get_section($row['emp_type'],$row['company_code'],$row['bunit_code'],$row['dept_code'],$row['section_code'])
            );
        endforeach;
            
        $data['filename']   = "MySubordinates_".date('Ymd');	
        $data['title']      = "Subordinates List Report";
        $this->load->view('employee/reports/subordinate_lists',$data);    
    }

    public function get_datehired($empid)
    {
        $result = $this->dbmodel
            ->get_row(
                'application_details',
                'DATE_FORMAT(date_hired,"%m/%d/%Y") AS date_hired',
                array( 'field1' => 'app_id'),
                array( $empid )
            );
        return $result->date_hired; 
    }

    public function years_service($datehired)
    {
        // Current date
        $currentDate = date('Y-m-d');

        // Calculate the difference
        $startDate = new DateTime($datehired);
        $currentDate = new DateTime($currentDate);
        $interval = $startDate->diff($currentDate);

        // Get the years of service
        $yearsOfService = $interval->y;
        $monthsOfService = $interval->m;

        ($yearsOfService == 1)? $years = "year" : $years = 'years' ; 
        ($monthsOfService == 1)? $months = "month" : $months = 'months' ; 

        // Display the result
        return $yearsOfService." ".$years." ".$monthsOfService." ".$months;
    }

    public function get_bu($emptype,$cc,$bc)
    {
        if($emptype !="Promo")
        {
            $result = $this->dbmodel
                ->get_row(
                    'locate_business_unit',
                    'acroname, business_unit',
                    array( 
                        'field1' => 'company_code',
                        'field2' => 'bunit_code'
                    ),
                    array( $cc, $bc)
                );
            if(!$bc){
                $bu = "";
            }else if($result->acroname !=''){		
                $bu = $result->acroname;
            }else{
                $bu = $result->business_unit;	
            }
            return $bu;  
        }else{
            return;
        }   
    }

    public function get_dept($emptype,$cc,$bc,$dc)
    {
        if($emptype !="Promo")
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
            //return ucwords(strtolower($dept));  
        }else{
            return;
        }   
    }

    public function get_section($emptype,$cc, $bc, $dc, $sc)
    {        
        if($emptype !="Promo")
        {  
            if(!isset($sc) && !isset($dc)){
                return;
            }
            else if($sc){
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
                if($result){
                    return "-".ucwords(strtolower($result->section_name));	
                }else{
                    return;
                }
            }else{
                return;
            }   
        }else{
            return;
        }     
    }
}