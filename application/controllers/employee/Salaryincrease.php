<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Manila');
class Salaryincrease extends CI_Controller {

    function __construct(){
        parent::__construct();
        // $this->checkEmployeeLogin();
        $this->load->model('DB_model','dbmodel');
        $this->title    = "HRMS [ Supervisor ]";
        $this->url      = "http://172.16.161.100/hrms/employee/";
        $this->periodno = $this->get_siperiod();
        $this->etype    = "and emp_type IN ('Regular', 'Regular Partimer', 'Contractual', 'Probationary',  'Seasonal', 'PTA', 'PTP', 
                          'NESCO Contractual', 'NESCO Probationary', 'NESCO-PTA', 'NESCO-PTP', 'NESCO Regular', 'NESCO Partimer', 'NESCO Regular Partimer')";
    }

    public function page_load($page,$menu,$submenu,$datas = NULL,$datas2 = NULL)
    {
       
        $data['title']      = $this->title;
        $data['url']        = $this->url;  
        $data['menu']       = $menu; //used in header
        $data['submenu']    = $submenu; //used in header

        $data['result']     = $datas;
        $data['siperiodno'] = $this->periodno;

        $this->load->view('template/header', $data);
        $this->load->view('employee/'.$page, $data);
        $this->load->view('template/footer', $data);
    }

    public function simemo(){
        $this->page_load("salaryincrease/si_memo","si","simemo");
    }   

    public function siepas(){
        
        $empid = $this->session->userdata('emp_id');
       
        $result = $this->dbmodel
                ->get_row(
                    "si_details as tbl1",
                    "*, DATE_FORMAT(rater1_daterating,'%m/%d/%Y') AS date1, DATE_FORMAT(rater2_daterating,'%m/%d/%Y') AS date2",
                    array( 
                        'field1' => 'tbl1.emp_id', 
                        'field2' => 'si_period_no'
                    ),
                    array( $empid, $this->periodno ),
                    array( 'employee3 as tbl2' ),
                    array( 'tbl1.emp_id = tbl2.emp_id' ),
                    null, null
                );  
                
        if($result)
        {
            $code = $this->dbmodel
                ->get_row(
                    'position_leveling',
                    'appraisal_code',
                    array( 'field1' => 'position_title'),
                    array( $result->position )
                )->appraisal_code;

            $data['questions'] = $this->dbmodel
                    ->get_data_by_join(
                        "appraisal", 
                        array( "si_answer" ), 
                        array( "si_answer.appraisal_id = appraisal.q_no") , 
                        "q_no, title, description, rate, appraisal.code ", 
                        array( 'field1' => 'code', 'field2' => 'si_details_id'),
                        array( '5', '49843' ),
                        //array( $code, $result->si_details_id ),
                        null, null, null, null
                    );

            $data['photo']      = $this->url."".$this->get_photo($empid);  
            $data['location']   = $this->get_bu($result->company_code,$result->bunit_code)." ".$this->get_dept($result->company_code,$result->bunit_code,$result->dept_code);    
                    
            $data['rater1']      = @$this->get_empdata($result->rater1)->name;           
            $data['rater2']      = @$this->get_empdata($result->rater2)->name;  
            
            $data['date1']      = $result->date1;          
            $data['date2']      = $result->date2;  
    
            $data['empid']      = $empid;  
            $data['appraisaltype']  = $this->get_appraisal_type($code);         
        }
             
        $data['result']     = $result;  
        $data['title']      = $this->title;
        $data['url']        = $this->url;
        $data['menu']       = "si";
        $data['submenu']    = "sirate";         

        $this->load->view('template/header', $data);
        $this->load->view('employee/salaryincrease/si_epas', $data);
        $this->load->view('template/footer', $data);      
    }

    public function siwizard(){
        $data['siperiodno'] = $this->periodno;
        $this->page_load("salaryincrease/si_wizard","si","siwizard", $data);
    }

    public function sirate()
    {
        $id = explode(",",$this->input->get('id'));
        $data['id'] = $id;      
        
        foreach($id as $row):
            $n = $this->dbmodel
                ->get_row(
                    'employee3',
                    'position',
                    array( 'field1' => 'emp_id'),
                    array( $row )
                );

            $code = $this->dbmodel
                ->get_row(
                    'position_leveling',
                    'appraisal_code',
                    array( 'field1' => 'position_title'),
                    array( $n->position )
                )->appraisal_code;

            $appraisaltype = $this->get_appraisal_type($code);
            $questions = $this->get_appraisal($code);    
        endforeach;
     
        $data['appraisaltype']  = $appraisaltype;
        $data['questions']  = $questions;
        $data['siperiod']   = $this->periodno;
        $data['code']       = $code;        
        $data['title']      = $this->title;
        $data['url']        = $this->url;
        $data['menu']       = "si";
        $data['submenu']    = "sirate"; 
        
        $this->load->view('template/header', $data);
        $this->load->view('employee/salaryincrease/si_rate', $data);
        $this->load->view('template/footer', $data);
    }

    public function simodal_guide($step,$val){    
                
        $data['step']       = $step;
        $data['employee']   = $this->get_empdata($val);
        if($step == "violations"){
            $data['sidetails']  = $this->dbmodel
                ->get_row(
                    'si_details',
                    '*',
                    array( 'field1' => 'emp_id', 'field2' => 'si_period_no' ),
                    array( $val, $this->periodno )
                );
        }
        
        $this->load->view('employee/salaryincrease/si_modal_guide',$data);
    }

    public function sistep($step)
    {        
        $employedasof = $this->dbmodel
            ->get_row(
                'si_period',
                'employedasof',
                array( 'field1' => 'si_status', 'field2'=> 'appraisal_of' ),
                array( 'open', 'AE' )
            )->employedasof;

        $result = $this->dbmodel
            ->getallfromtablejoin(
                'e.record_no, e.emp_id, name, position, emp_type, startdate, tag_as',
                'employee3 as e',
                'leveling_subordinates as ls',
                'e.emp_id = ls.subordinates_rater',
                " ratee = '".$this->session->userdata('emp_id')."' and 
                current_status ='Active' ". $this->etype." and 
                ((tag_as = '') OR (startdate <= '$employedasof' and tag_as ='new')) ",
                "name",
                "ASC",
                null, null
            );
        $this->page_load("salaryincrease/si_step".$step,"si","Step ".$step,$result);
    }
   
    public function save_step1()
    {
        $empid	    = $this->input->post('id');
        $val 	    = $this->input->post('val');
        $code 	    = $this->input->post('cat');
        $where      = "emp_id = '$empid' and si_period_no = '".$this->periodno."'";

        $result = $this->dbmodel
            ->get_row(
                'si_details',
                'emp_id',
                array( 'field1' => 'emp_id', 'field2'=> 'si_period_no' ),
                array( $empid, $this->periodno )
            );     

        switch ($code) 
        {
		    case "loa": 
            {        
                if($result){
                    $data  = array('loa' => $val );
                    $query = $this->dbmodel->update("si_details", $data, $where);                   
                }else{
                    $data = array(
                        'si_period_no'  => $this->periodno,
                        'emp_id'        => $empid,
                        'loa'           => $val
                    );
                    $query = $this->dbmodel->add_with_id("si_details", $data);
                }   

                ($query) ? $rval = '1' : $rval = '0' ;
                echo $rval;
			}
			break;
		    case "tardi": 
            {          
                if($result){
                    $data  = array('tardiness' => $val );
                    $query = $this->dbmodel->update("si_details", $data, $where);                   
                }else{
                    $data = array(
                        'si_period_no'  => $this->periodno,
                        'emp_id'        => $empid,
                        'tardiness'     => $val
                    );
                    $query = $this->dbmodel->add_with_id("si_details", $data);
                }   

                ($query) ? $rval = '1' : $rval = '0' ;
                echo $rval;
			}
			break;
		    case "suspension": 
            {            
                if($result){
                    $data  = array('suspension' => $val );
                    $query = $this->dbmodel->update("si_details", $data, $where);
                   
                }else{
                    $data = array(
                        'si_period_no'  => $this->periodno,
                        'emp_id'        => $empid,
                        'suspension'     => $val
                    );
                    $query = $this->dbmodel->add_with_id("si_details", $data);
                }   

                ($query) ? $rval = '1' : $rval = '0' ;
                echo $rval;
			}
			break;
		    case "awol": 
            {            
                if($result){
                    $data  = array('awol' => $val );
                    $query = $this->dbmodel->update("si_details", $data, $where);
                   
                }else{
                    $data = array(
                        'si_period_no'  => $this->periodno,
                        'emp_id'        => $empid,
                        'awol'          => $val
                    );
                    $query = $this->dbmodel->add_with_id("si_details", $data);
                }   

                ($query) ? $rval = '1' : $rval = '0' ;
                echo $rval;
            }
			break;
		    case "undertime": 
            {     
                if($result){
                    $data  = array('undertime' => $val );
                    $query = $this->dbmodel->update("si_details", $data, $where);
                
                }else{
                    $data = array(
                        'si_period_no'  => $this->periodno,
                        'emp_id'        => $empid,
                        'undertime'     => $val
                    );
                    $query = $this->dbmodel->add_with_id("si_details", $data);
                }   

                ($query) ? $rval = '1' : $rval = '0' ;
                echo $rval;
			}
			break;

            // if ($query) {
            // 	$nq->savelogs("SI - Save $loa-LOA of " . $nq->getEmpName($empid) . " ", date('Y-m-d'), date('H:i:s'), $_SESSION['emp_id'], $_SESSION['username']);
            // 	echo '1';
            // }	
	    }
    }
   
    public function save_step2()
    {
        $empids     = $this->input->post('empids');       
        $rater      = $this->input->post('rater');  
        $rate       = $this->input->post('rates');  
        $numrate    = $this->input->post('numrates');  
        $descrate   = $this->input->post('desrate');  
        $rater1com  = $this->input->post('ratercomment');  
        
        $p = 10 * count($empids);         
        for($i=0; $i < count($empids); $i++)
        {
            $data   = array(
                    'numrate'           => $numrate[$i],
                    'descrate'          => $descrate[$i],
                    'rater1'            => $rater,
                    'rater1comment'     => $rater1com[$i],
                    'rater1_daterating' => date('Y-m-d')
                );

            $query      = $this->dbmodel->update("si_details", $data, "emp_id = '$empids[$i]' and si_period_no = '$this->periodno'");
            $sidetails  = $this->dbmodel
                ->get_row(
                    'si_details',
                    'si_details_id',
                    array( 'field1' => 'emp_id', 'field2' => 'si_period_no' ),
                    array( $empids[$i], $this->periodno)
                );

            $sianswer  = $this->dbmodel
                ->get_row(
                    'si_answer',
                    'si_details_id',
                    array( 'field1' => 'si_details_id' ),
                    array( $sidetails->si_details_id )
                );    
            
            $counter = 1;
            if($sianswer)
            {                
                for($y = $i; $y < $p; $y += count($empids) )
                {
                    $data       = array('rate' => $rate[$y]);                    
                    $this->dbmodel->update("si_answer", $data, "appraisal_id = '".$counter."' and si_details_id = '$sidetails->si_details_id' ");
                    $counter++;    
                }
            }else{
                for($y = $i; $y < $p; $y += count($empids) )
                {
                    $data       = array(
                        'si_details_id'     => $sidetails->si_details_id,
                        'appraisal_id'      => $counter,
                        'rate'              => $rate[$y]
                    );
                    $this->dbmodel->add("si_answer", $data);
                    $counter++;    
                }
            }            
        }       
        
        if($query){
            echo json_encode([
                'status' => 200,
                'redirect' => 'employee/sistep/2',
                'response' => "success",
                'response_message' => 'Successfully Saved'
            ]);
        }else{
            echo json_encode([
                'status' => 401,
                'response'	=> "error",
                'response_message' => "Saving Error"
            ]);
        }
    }

    public function save_done()
    {
        $empids = $this->input->post('empids');     
        $steps  = $this->input->post('step_stat');   
        $data   = array( $steps => '1' );
        foreach($empids as $row):           
            $query = $this->dbmodel->update("si_details", $data, "emp_id = '$row' and si_period_no = '$this->periodno'");
        endforeach;
        
        ($query) ? $rval = '1' : $rval = '0' ;
        echo $rval;
    }

    public function save_comment()
    {    
        $data   = array( 'rateecomment' => trim($this->input->post('rateeComment')), 'rateeSO' => '1' );                 
        $query = $this->dbmodel->update("si_details", $data, "emp_id = '".$this->input->post('empid')."' and si_period_no = '$this->periodno'");               
       
        if($query){
            echo json_encode([
                'status' => 200,
                'redirect' => 'employee/si/epas',
                'response' => "success",
                'response_message' => 'Successfully Saved'
            ]);
        }else{
            echo json_encode([
                'status' => 401,
                'response'	=> "error",
                'response_message' => "Saving Error"
            ]);
        }
    }

    public function show_rating($epas,$empid)
    {       
        if($epas == "current"){ 
            $pno = $this->periodno; 
        } else { 
            $pno = $this->dbmodel ->get_field_row( "si_pno", "si_period",  "appraisal_of = 'AE'", "si_pno", "DESC", "1", "1")->si_pno;
        }

        $result = $this->dbmodel
                ->get_row(
                    "si_details as tbl1",
                    "*, DATE_FORMAT(rater1_daterating,'%m/%d/%Y') AS date1, DATE_FORMAT(rater2_daterating,'%m/%d/%Y') AS date2",
                    array( 
                        'field1' => 'tbl1.emp_id', 
                        'field2' => 'si_period_no'
                    ),
                    array( $empid, $pno ),
                    array( 'employee3 as tbl2' ),
                    array( 'tbl1.emp_id = tbl2.emp_id' ),
                    null, null
                );    
        
        $code = $this->dbmodel
                ->get_row(
                    'position_leveling',
                    'appraisal_code',
                    array( 'field1' => 'position_title'),
                    array( $result->position )
                )->appraisal_code;

        $data['questions'] = $this->dbmodel
                ->get_data_by_join(
                    "appraisal", 
                    array( "si_answer" ), 
                    array( "si_answer.appraisal_id = appraisal.q_no") , 
                    "q_no, title, description, rate, appraisal.code ", 
                    array( 'field1' => 'code', 'field2' => 'si_details_id'),
                    array( $code, $result->si_details_id ),
                    null, null, null, null
                );
                       
        $appraisaltype = $this->get_appraisal_type($code);
            
        $data['rater1']     = @$this->get_empdata($result->rater1)->name;
        $data['rater2']     = @$this->get_empdata($result->rater2)->name;
        $data['result']     = $result;            
        $data['appraisaltype']  = $appraisaltype;
        $data['title']      = $this->title;
        $data['url']        = $this->url;
        $data['menu']       = "si";
        $data['submenu']    = "sirate";         
        $this->load->view('employee/salaryincrease/si_prev_rating',$data);
    }

    public function get_appraisal_type($code)
    {
        $result = $this->dbmodel
            ->get_row(
                'appraisal_type',
                '*',
                array( 'field1' => 'code'),
                array( $code )
            );
        return $result; 
    }

    public function get_appraisal($code)
    {
        $result = $this->dbmodel
            ->get_all_data("appraisal","*",null,null,null,0,null,
            "code = '$code' ");
        return $result;
    }

    public function get_empdata($empid)
    {
        $result = $this->dbmodel
            ->get_row(
                'employee3',
                '*, DATE_FORMAT(startdate,"%m/%d/%Y") AS startdate, DATE_FORMAT(eocdate,"%m/%d/%Y") AS eocdate',
                array( 'field1' => 'emp_id'),
                array( $empid )
            );       
        return $result;
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

        if($dc)
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
                    'field3' => 'section_code'
                ),
                array( $cc, $bc, $dc, $sc )
            );
        return $result->section_name;	 
    }

    public function get_siperiod()
    {
        $result = $this->dbmodel
            ->get_row(
                'si_period',
                'si_pno',
                array( 'field1' => 'si_status', 'field2' => 'appraisal_of'),
                array( 'open', 'AE' )
            );  
        if($result){            
            return $result->si_pno;
        }else{
            return 0;
        }     
    }
}