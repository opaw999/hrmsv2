<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Manila');
class Epas extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->checkEmployeeLogin();
        $this->load->model('DB_model','dbmodel');
        $this->title = "Human Resource Management System [ Supervisor ]";
        $this->url   = "http://172.16.161.100/hrms/employee/";
    }

    private function checkEmployeeLogin(){
        if(!$this->session->userdata('hrmsEmployeeSession')){
            redirect($this->session->userdata('sessionType') . '/dashboard');
        }
    }

    public function index()
    {        
        $data['title']      = $this->title;
        $data['url']        = $this->url;  
        $data['menu']       = "epas"; //used in header
        $data['submenu']    = "resigns"; //used in header
        $data['tblApi']     = "supervisor/epas/load_resigns"; //find in routes

        $this->load->view('template/header', $data);
        $this->load->view('employee/supervisor/epas_resignations',$data);
        $this->load->view('template/footer', $data);
    }

    public function eoc()
    {
        $data['title']      = $this->title;
        $data['url']        = $this->url;  
        $data['menu']       = "epas"; //used in header
        $data['submenu']    = "eoc"; //used in header
        $data['tblApi']     = "supervisor/epas/load_eoc/thismonth"; //find in routes

        $this->load->view('template/header', $data);
        $this->load->view('employee/supervisor/epas_eoc',$data);
        $this->load->view('template/footer', $data);
    }

    public function load_eoc($eoctype) //load subordinates
    {
        switch($eoctype){
            case "nextmonth" : $condi = "eocdate like '".date('Y-m',strtotime("+1 month",strtotime(date("Y-m-01"))))."%' "; break;
            case "overdue"   : $condi = "eocdate < '".date('Y')."-".date('m')."-01' ";  break; 
            default          : $condi = "eocdate like '".date('Y')."-".date('m')."%' ";  
        }
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

        $totalData = $this->dbmodel 
            ->countfromtablejoin(
                'tbl1.emp_id',
                'employee3 as tbl1',
                'leveling_subordinates as tbl2',
                'tbl1.emp_id = tbl2.subordinates_rater',
                "$condi AND 
                    current_status IN ('Active','End of Contract') AND
                    ratee = '".$this->session->userdata('emp_id')."' AND
                    emp_id != '".$this->session->userdata('emp_id')."' AND
                    emp_type IN ('Contractual','Probationary','Partimer','Seasonal','OJT','Summer Job','Back-Up','NESCO Contractual','NESCO-PTA','NESCO-PTP','NESCO-BACKUP','NESCO Probationary')"
            );

        $totalFiltered = $totalData; 

        //if search box is empty // default load
        if(empty($this->input->post('search')['value'])) {      
            
            $datas =  $this->dbmodel
                ->getallfromtablejoin(
                    'name, emp_id, tbl1.record_no, ratee, position, DATE_FORMAT(eocdate,"%m/%d/%Y") AS eocdate, current_status, emp_type, epas_code, company_code, bunit_code, dept_code,  section_code',
                    'employee3 as tbl1',
                    'leveling_subordinates as tbl2',
                    'tbl1.emp_id = tbl2.subordinates_rater',
                    "$condi AND 
                        current_status IN ('Active','End of Contract') AND
                        ratee = '".$this->session->userdata('emp_id')."' AND
                        emp_id != '".$this->session->userdata('emp_id')."' AND
                        emp_type IN ('Contractual','Probationary','Partimer','Seasonal','OJT','Summer Job','Back-Up','NESCO Contractual','NESCO-PTA','NESCO-PTP','NESCO-BACKUP','NESCO Probationary')",
                    "eocdate, name",
                    $dir,
                    $limit,
                    $start
            );      
        }else{
                 
            //if search value is not empty
            $search = $this->input->post('search')['value'];   
            $datas =  $this->dbmodel
                ->getallfromtablejoin(
                    'name, emp_id, tbl1.record_no, ratee, position, DATE_FORMAT(eocdate,"%m/%d/%Y") AS eocdate, current_status, emp_type, epas_code, company_code, bunit_code, dept_code,  section_code',
                    'employee3 as tbl1',
                    'leveling_subordinates as tbl2',
                    'tbl1.emp_id = tbl2.subordinates_rater',
                    "$condi AND 
                        name like '%$search%' AND
                        current_status IN ('Active','End of Contract') AND
                        ratee = '".$this->session->userdata('emp_id')."' AND
                        emp_id != '".$this->session->userdata('emp_id')."' AND
                        emp_type IN ('Contractual','Probationary','Partimer','Seasonal','OJT','Summer Job','Back-Up','NESCO Contractual','NESCO-PTA','NESCO-PTP','NESCO-BACKUP','NESCO Probationary')",
                    "eocdate, name",
                    $dir,
                    $limit,
                    $start
            );    
            $totalFiltered = count($datas);
        }

        //load data in the table
        $data = array();
        if(!empty($datas)){
            foreach ($datas as $list){    

                $app_result = $this->get_grade($list['emp_id'],$list['record_no']);
                // $code   = $this->get_appraisal_code_type($this->get_empdata_type($list['emp_id'])->position);
                
                $code = $this->get_appraisal_code_type($this->get_empdata_type($list['emp_id'])->position);
                    @$appraisalCode = $code->appraisal_code;
                    @$positionTitle = $code->position_title;

                if($appraisalCode == '0' || $positionTitle == ''){

                                           
                                    $action = '<center><button
                                        class="btn btn-primary btn-sm"
                                        type="button"
                                        modal-size=""
                                        modal-redirect=""
                                        modal-route="resignation/grade/'.$list['emp_id'].'"
                                        modal-form="supervisor/epas/appraissal_type/'.$list['emp_id'].'"
                                        modal-skeleton="0"
                                        modal-id=""
                                        modal-atype="POST"
                                        modal-title="Appraisal Type" 
                                        onclick="Redirectmodal(event)">Rate Now?</button></center>';


                        // $action = "<a href='".base_url()."supervisor/epas/appraissal_type/$list[emp_id]'  class='btn btn-primary btn-sm'> Rate Now? </a>";
                 }
                 else if($list['epas_code'] == "" || $list['epas_code'] == 0 )    
                {
                    $action = "<a href='".base_url()."supervisor/epas/resignation/grade/$list[emp_id]/0'  class='btn btn-primary btn-sm'> Rate Now? </a>";
                }else{
                    $did    = @$app_result->details_id;
                    if(@$app_result->raterSO == 0)
                    {                        
                        $action = "<a href='".base_url()."supervisor/epas/resignation/grade/$did'  class='btn btn-success btn-sm'> Update & Sign Off? </a>";
                    }else{
                        $action = "<a href='".base_url()."supervisor/epas/preview/$did'  class='btn btn-warning btn-sm'> Preview </a>";                    
                    }
                }

                $nestedData['EMPID']            = $list['emp_id'];
                $nestedData['NAME']             = ucwords(strtolower($list['name']));
                $nestedData['POSITION']         = $list['position'];
                $nestedData['EMPTYPE']          = $list['emp_type'];
                $nestedData['STATUS']           = $list['current_status'];
                $nestedData['DEPARTMENT']       = $this->get_dept($list['company_code'],$list['bunit_code'],$list['dept_code'])."".$this->get_section($list['company_code'],$list['bunit_code'],$list['dept_code'],$list['section_code']);
                $nestedData['EOCDATE']          = $list['eocdate'];
                $nestedData['RATING']           = @$app_result->numrate;
                $nestedData['ACTION']           = $action;
                $data[] = $nestedData;
            }
        }else{
            $nestedData['EMPID']        = "No Record Found";
            $nestedData['NAME']         = "";
            $nestedData['POSITION']     = "";
            $nestedData['EMPTYPE']      = "";
            $nestedData['STATUS']       = "";
            $nestedData['DEPARTMENT']   = "";
            $nestedData['EOCDATE']       = "";
            $nestedData['RATING']       = "";
            $nestedData['ACTION']       = "";
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

    public function appraisal_type()
    {
           $data['cc']  = $this->session->userdata('company');
            $data['appraisal_types'] = $this->dbmodel
                ->get_all_data(
                    'appraisal_type',
                    '*',
                    null,
                    null,
                    null,
                    null,
                    null,
                    'add != "COLONNADE" AND appraisal NOT LIKE "%Promo%" AND add NOT LIKE "%Crew%"'
                );

        $this->load->view('employee/supervisor/appraisal_type', $data);
    }




    public function load_resigns() //load subordinates
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

        $totalData = $this->dbmodel 
            ->countfromtablejoin(
                'tbl1.emp_id',
                'employee3 as tbl1',
                'tag_for_resignation as tbl2',
                'tbl1.emp_id = tbl2.ratee_id',
                "tag_stat = 'Pending' AND 
                    rater_id = '".$this->session->userdata('emp_id')."' AND                    
                    (current_status != 'blacklisted' and current_status != 'Deceased') and emp_id != '04331-2015' "
            );
        $totalFiltered = $totalData; 

        //if search box is empty // default load
        if(empty($this->input->post('search')['value'])) {                  
            $datas =  $this->dbmodel
                ->getallfromtablejoin(
                    'name, emp_id, ratee_id, record_no, rater_id, tag_stat, position, startdate, eocdate, current_status, emp_type, epas_code, company_code, bunit_code, dept_code,  section_code',
                    'employee3 as tbl1',
                    'tag_for_resignation as tbl2',
                    'tbl1.emp_id = tbl2.ratee_id',
                    "tag_stat = 'Pending' and rater_id = '".$this->session->userdata('emp_id')."' and 
                     (current_status != 'blacklisted' and current_status != 'Deceased') and emp_id != '04331-2015' "
            );             
                
        }else{
                                    
            //if search value is not empty
            $search = $this->input->post('search')['value'];                   
            $datas =  $this->dbmodel
                ->getallfromtablejoin(
                    'name, emp_id, ratee_id, record_no, rater_id, tag_stat, position, startdate, eocdate, current_status, emp_type, epas_code, company_code, bunit_code, dept_code,  section_code',
                    'employee3 as tbl1',
                    'tag_for_resignation as tbl2',
                    'tbl1.emp_id = tbl2.ratee_id',
                    "tag_stat = 'Pending' and rater_id = '".$this->session->userdata('emp_id')."' and
                     (current_status != 'blacklisted' and current_status != 'Deceased') and emp_id != '04331-2015' "
            );    
            $totalFiltered = count($datas);
        }

        //load data in the table
        $data = array();
        if(!empty($datas)){
            foreach ($datas as $list){    

                $app_result = $this->get_grade($list['emp_id'],$list['record_no']);
            
                if($list['epas_code'] == "" || $list['epas_code'] == 0 )    
                {
                    $action = "<a href='".base_url()."supervisor/epas/resignation/grade/$list[emp_id]'  class='btn btn-primary btn-sm'> Rate Now? </a>";
                }else{
                    if($app_result->raterSO == 0)
                    {
                        $action = "<a href='".base_url()."supervisor/epas/resignation/grade/$app_result->details_id'  class='btn btn-success btn-sm'> Update & Sign Off? </a>";
                    }else{
                        $action = "<a href='".base_url()."supervisor/epas/preview/$app_result->details_id'  class='btn btn-warning btn-sm'> Preview </a>";
                    }                    
                }

                $nestedData['EMPID']            = $list['emp_id'];
                $nestedData['NAME']             = ucwords(strtolower($list['name']));
                $nestedData['POSITION']         = $list['position'];
                $nestedData['EMPTYPE']          = $list['emp_type'];
                $nestedData['STATUS']           = $list['current_status'];
                $nestedData['DEPARTMENT']       = $this->get_dept($list['company_code'],$list['bunit_code'],$list['dept_code'])."".$this->get_section($list['company_code'],$list['bunit_code'],$list['dept_code'],$list['section_code']);
                $nestedData['DATE EFFECTIVITY'] = $this->get_dateeffectivity($list['emp_id']);
                $nestedData['RATING']           = @$app_result->numrate;
                $nestedData['ACTION']           = $action;
                $data[] = $nestedData;
            }
        }else{
            $nestedData['EMPID']        = "No Record Found";
            $nestedData['NAME']         = "No Record Found";
            $nestedData['POSITION']     = "No Record Found";
            $nestedData['EMPTYPE']      = "No Record Found";
            $nestedData['DEPARTMENT']       = "No Record Found";
            $nestedData['DATE EFFECTIVITY'] = "No Record Found";
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

    public function get_grade($empid,$recordno){
        $result = $this->dbmodel
            ->get_row(
                'appraisal_details',
                '*',
                array( 'field1' => 'emp_id', 'field2' => 'record_no' ),
                array( $empid, $recordno )
            );
        return $result;  
    }


    

    public function epas_grading($empid, $epass = null)
    {
        

        if (strpbrk($empid, '-')) //checking if empid (to new epas) or details id (edit epas) //if wala pay grado
        {            
            $data['codeselect'] = $epass;
            
            @$code   = $this->get_appraisal_code($this->get_empdata($empid)->position);

            $cc     = $this->get_empdata($empid)->company_code;
            $bc     = $this->get_empdata($empid)->bunit_code;
            $dc     = $this->get_empdata($empid)->dept_code;
            
            if($code == '0' || $code == '') {  
                $data['questions']  = $this->get_appraisal($data['codeselect']);
            } else {
                $data['questions']  = $this->get_appraisal($code);     
            }
            
            $data['location']   = $this->get_bu($cc,$bc)." ".$this->get_dept($cc,$bc,$dc);    
            
            //EMPLOYEE INFO
            $data['photo']      = $this->url."".$this->get_photo($empid);       
            $data['empid']      = $empid;             
            $data['rater']      = $this->get_rater($this->session->userdata('emp_id'));
            $data['name']       = $this->get_empdata($empid)->name;
            $data['recordno']   = $this->get_empdata($empid)->record_no;
            $data['position']   = $this->get_empdata($empid)->position;
            $data['emptype']    = $this->get_empdata($empid)->emp_type;
            $data['startdate']  = $this->get_empdata($empid)->startdate;
            $data['eocdate']    = $this->get_empdata($empid)->eocdate;   
            $data['detailsid']  = "";
            $data['aptype']     = "(NEW RATING)";
            
        }else{
            $result = $this->dbmodel
            ->get_row(
                'appraisal_details as tbl1',
                'tbl2.emp_id, position, tbl2.record_no, emp_type, name, DATE_FORMAT(startdate,"%m/%d/%Y") AS startdate, DATE_FORMAT(eocdate,"%m/%d/%Y") AS eocdate, 
                details_id, code, rater, ratercomment, numrate,descrate, company_code, bunit_code, dept_code, 
                DATE_FORMAT(ratingdate,"%m/%d/%Y") AS ratingdate',
                array( 'field1' => 'tbl1.details_id', 'field2' => 'tbl1.rateeSO'),
                array( $empid, '0' ), 
                array( 'employee3 as tbl2' ),
                array( 'tbl1.emp_id = tbl2.emp_id' )
            );  
            $data['aptype']      = "(FOR SIGN OFF)"; 
            $code                   = $this->get_appraisal_code($result->position);

            $data['questions']  = $this->get_appraisal_answer($result->details_id);                 
            $data['location']   = $this->get_bu($result->company_code,$result->bunit_code)." ".$this->get_dept($result->company_code,$result->bunit_code,$result->dept_code);    
            
            //EMPLOYEE INFO
            $data['photo']      = $this->url."".$this->get_photo($result->emp_id);       
            $data['empid']      = $result->emp_id;
            $data['name']       = $result->name;
            $data['recordno']   = $result->record_no;
            $data['position']   = $result->position;
            $data['emptype']    = $result->emp_type;
            $data['startdate']  = $result->startdate;
            $data['eocdate']    = $result->eocdate;  
            $data['detailsid']  = $empid;//detailsid  
        }    
      
        if($code == '0' || $code == ''){
          $data['appraisaltype']  = $this->get_appraisal_type($data['codeselect']);
        }else{
        $data['appraisaltype']  = $this->get_appraisal_type($code);
        }
      
        $data['rater']      = $this->get_rater($this->session->userdata('emp_id'));
        $data['raterid']    = $this->session->userdata('emp_id');
         
        $data['numrate']    = (@$result->numrate) ? $result->numrate : ""; 
        $data['descrate']   = (@$result->descrate) ? $result->descrate : ""; 
        $data['ratercomm']  = (@$result->ratercomment) ? $result->ratercomment : ""; 

        $data['code']       = $code;        
        $data['title']      = $this->title;
        $data['url']        = $this->url;
        $data['menu']       = "epas";
        $data['submenu']    = "eoc"; 
        
        $this->load->view('template/header', $data);
        $this->load->view('employee/supervisor/epas_grading', $data);
        $this->load->view('template/footer', $data);
    }

    public function epas_submit()
    {                
        $numrate = $this->input->post('numrates');
        if ($numrate == 100) { $descrate = "E"; } 
        else if ($numrate >= 90 && $numrate <= 99.9) { $descrate = "VS"; } 
        else if ($numrate >= 85 && $numrate <= 89.9) { $descrate = "S";  } 
        else if ($numrate >= 70 && $numrate <= 84.9) { $descrate = "US"; } 
        else if ($numrate >= 0 && $numrate <= 69.9)  { $descrate = "VU"; }

        $raterSO = $this->input->post('raterSO');
        $dateRSO = ($raterSO)? $dateRSO = date('Y-m-d H:i:s') : $dateRSO='' ;

        $data = array(
            'emp_id'            =>  $this->security->xss_clean($this->input->post('empid')),  
            'record_no'         =>  $this->security->xss_clean($this->input->post('recordno')),  
            'rater'             =>  $this->security->xss_clean($this->input->post('raterid')),  
            'numrate'           =>  $this->security->xss_clean($this->input->post('numrates')),  
            'descrate'          =>  $this->security->xss_clean($descrate), 
            'ratercomment'      =>  $this->security->xss_clean($this->input->post('ratercomment')), 
            'ratingdate'        =>  date('Y-m-d H:i:s'),
            'raterSO'           =>  $this->security->xss_clean($raterSO),
            'dateraterSO'       =>  $this->security->xss_clean($dateRSO),
            'addedby'           =>  $this->security->xss_clean($this->session->userdata('emp_id')), 
            'dateadded'         =>  date('Y-m-d H:i:s'),
            'code'              =>  $this->security->xss_clean($this->input->post('code')), 
            'remarks'           =>  ""
        );

        $apptype = $this->input->post('apptype');
        if($apptype=="(FOR SIGN OFF)")
        {
            $results = $this->dbmodel
                ->update("appraisal_details",
                $data,
                "details_id = '".$this->input->post('detailsid')."'"
            );  
            if($results){
                $resultid = $this->input->post('detailsid');
            }     
        }
        else
        {
            $resultid = $this->dbmodel->add_with_id("appraisal_details", $data);
        }

               
        if($resultid)
        {
            //INSERT APPRAISAL ANSWER
            $rate           = $this->input->post('rates');
            $appraisal_id   = $this->input->post('appraisal_id');
            
            for($i=0;$i<10;$i++)
            {
                if($apptype=="(FOR SIGN OFF)")
                {
                    $data   = array( 'rate' => $rate[$i] );
                    $no     = $this->input->post('no');
                    $result = $this->dbmodel
                        ->update("appraisal_answer",
                        $data,
                        "answer_id = '".$no[$i]."'"
                    ); 
                }else{
                    $data = array( 
                        'appraisal_id'   =>  $appraisal_id[$i],  
                        'rate'           =>  $rate[$i],  
                        'details_id'     =>  $resultid
                    );
                    $result = $this->dbmodel->add("appraisal_answer", $data);
                    
                    //UPDATE RECORD NO
                    $data = array('epas_code'=> '1');
                    $this->dbmodel->update("employee3",$data," emp_id = '".$this->input->post('empid')."' ");
                }
            }            

            echo json_encode([
                'status' => 200,
                'redirect' => 'supervisor/epas/preview/'.$resultid,
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

    public function epas_preview($did)
    {   
        $result = $this->dbmodel
            ->get_row(
                'appraisal_details as tbl1',
                'position, tbl2.record_no, emp_type, name, DATE_FORMAT(startdate,"%m/%d/%Y") AS startdate, DATE_FORMAT(eocdate,"%m/%d/%Y") AS eocdate,
                tbl2.emp_id, tbl2.company_code, tbl2.bunit_code, tbl2.dept_code, tbl2.section_code, details_id, code, name,
                rater, ratercomment, numrate,descrate, company_code, bunit_code, dept_code, DATE_FORMAT(ratingdate,"%m/%d/%Y") AS ratingdate',
                array( 'field1' => 'tbl1.details_id'),
                array( $did ), 
                array( 'employee3 as tbl2' ),
                array( 'tbl1.emp_id = tbl2.emp_id' )
            );  

        $data['rater']          = $this->get_rater($result->rater);    
        $data['ratercomment']   = $result->ratercomment;    
        $data['numrate']        = $result->numrate;    
        $data['descrate']       = $result->descrate;  

        $data['questions']  = $this->get_appraisal_answer($did);    
        $data['epas']       = $result;
        $data['title']      = $this->title;
        $data['url']        = $this->url;
        $data['menu']       = "epas";
        $data['submenu']    = ""; 

        $data['photo']      = $this->url."".$this->get_photo($result->emp_id);   
        $data['recordno']   = $result->record_no;   
        $data['location']   = $this->get_bu($result->company_code,$result->bunit_code)." ".$this->get_dept($result->company_code,$result->bunit_code,$result->dept_code);    
               
        $this->load->view('template/header', $data);
        $this->load->view('employee/supervisor/epas_preview', $data);
        $this->load->view('template/footer', $data);
    }

    public function get_appraisal_answer($did)
    {  
        $result = $this->dbmodel
            ->get_data_by_join(
                'appraisal_answer as tbl1',
                array( 'appraisal as tbl2' ),
                array( 'tbl1.appraisal_id = tbl2.appraisal_id' ), 
                '*',
                array( 'field1' => 'details_id' ),
                array( $did )
            );       
        return $result;
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

    public function get_appraisal_code($position)
    {
        $result = $this->dbmodel
            ->get_row(
                'position_leveling',
                'appraisal_code',
                array( 'field1' => 'position_title'),
                array( $position )
            );
        return $result->appraisal_code;  
    }


   public function get_appraisal_code_type($position)
    {
        $result = $this->dbmodel->get_row(
            'position_leveling',
            'appraisal_code, position_title', // Fetch both columns
            array('field1' => 'position_title'),
            array($position)
        );

        if ($result) {
            return $result; // Return the entire result object
        } else {
            // Handle the case where no matching row is found.
            return 'No Appraisal Code Found';
        }
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

     public function get_empdata_type($empid)
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
    
    public function get_dateeffectivity($rateeid)
    {
        $result = $this->dbmodel
            ->get_row(
                'secure_clearance',
                'DATE_FORMAT(date_resignation,"%m/%d/%Y") AS date_resignation',
                array( 'field1' => 'emp_id', 'field2' => 'status'),
                array( $rateeid, 'Pending' )  
            );
        if($result){
            return $result->date_resignation; 
        }else{
            return "";
        }
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
}