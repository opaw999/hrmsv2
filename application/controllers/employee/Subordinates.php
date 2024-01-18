<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Manila');
class Subordinates extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->checkEmployeeLogin();
        $this->load->model('DB_model','dbmodel');
        $this->title = "HRMS [ ".ucwords(strtolower($this->session->userdata('sessionType')))." ]";
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
        $data['menu']       = "subordinates"; //used in header
        $data['submenu']    = "lists"; //used in header
        $data['tblApi']     = "supervisor/subordinates/load_subs"; //find in routes

        $this->load->view('template/header', $data);
        $this->load->view('employee/supervisor/subordinates',$data);
        $this->load->view('template/footer', $data);
    }

     public function removesubordinates()
    {        
        $data['title']      = $this->title;
        $data['url']        = $this->url;  
        $data['menu']       = "subordinates"; //used in header
        $data['submenu']    = "removesubordinates"; //used in header
        $data['tblApi']     = "supervisor/subordinates/load_removesubs"; //find in routes

        $this->load->view('template/header', $data);
        $this->load->view('employee/supervisor/removesubordinates',$data);
        $this->load->view('template/footer', $data);
    }

      public function removemodal_sub()
      {
        $this->load->view('employee/supervisor/removesubreason_modal');
      }

  public function removesub()
    {
        $empIds = $this->input->post('cbname');
        $supId = $this->session->userdata('emp_id');
        $reason = $this->input->post('reason');
        $dateRemove = date('Y-m-d H:i:s');

        $data = array(
            'supId'       => $supId,
            'date_remove' => $dateRemove,
            'reason'      => $reason,
        );

        $removeid = $this->dbmodel->add_with_id("remove_subordinates_info", $data);

        if ($removeid) {
            $noS = explode("*", $empIds);
            for ($i = 0; $i < sizeof($noS); $i++) {
                $data = array(
                    'ratee_stat' => 1,
                    'removeNo'   => $removeid,
                );

                $update = $this->dbmodel->update(
                    "leveling_subordinates",
                    $data,
                    "subordinates_rater = '".$noS[$i]."' AND  ratee = '".$supId."'"
                );
            }

            if ($update) {
                echo json_encode([
                    'status'           => 200,
                    'response'         => "success",
                    'modal_close'      => "true",
                    'response_message' => "You have successfully submitted your request for removal",
                ]);
            } else {
                echo json_encode([
                    'status'           => 401,
                    'response'         => "error",
                    'response_message' => "Unable to submit your request for removal!.",
                ]);
            }
        } else {
            echo json_encode([
                'status'           => 401,
                'response'         => "error",
                'response_message' => "Unable to submit your request for removal!.",
            ]);
        }
    }


     public function load_removesubs() //load removesubordinates
    {
        //searching columns
        $columns = array( 
            0 =>'emp_id',
            1 =>'name',
            2 =>'position',
            3 =>'emp_type'
        );

        //default
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];


        $totalData =  $this->dbmodel
            ->dt_get_where_count(
                'employee3 as e',
                'emp_id',
                array( 'field1' => 'l.ratee', 'field2' => 'e.current_status' ),
                array( $this->session->userdata('emp_id'), 'Active' ),
                array(),
                array() ,
                array( 'tbl1' => 'leveling_subordinates as l'),
                array( 'e.emp_id = l.subordinates_rater')                
            );

        $totalFiltered = $totalData; 

        //if search box is empty // default load
        if(empty($this->input->post('search')['value'])) {                  
            $datas = $this->dbmodel
                    ->get_data_by_join(
                        'employee3 as e',
                        array( 'leveling_subordinates as l'),
                        array( 'e.emp_id = l.subordinates_rater') , 
                        '*, name, DATE_FORMAT(e.startdate,"%m/%d/%Y") AS startdate, DATE_FORMAT(e.eocdate,"%m/%d/%Y") AS eocdate',
                        array( 'field1' => 'l.ratee', 'field2' => 'e.current_status' ),
                        array( $this->session->userdata('emp_id'), 'Active' ),
                        "ASC",   // order by field
                        "name", // order by ASC or DESC
                        $limit, // per page
                        $start  // start of the page
                    ); 
        }else{
            //if search value is not empty
            $search = $this->input->post('search')['value']; 
            $datas  = $this->dbmodel                    
                    ->dt_get_where_like(
                        'employee3 as e',
                       '*, name, DATE_FORMAT(e.startdate,"%m/%d/%Y") AS startdate, DATE_FORMAT(e.eocdate,"%m/%d/%Y") AS eocdate',
                        array( 'field1' => 'e.emp_id', 'field2' => 'e.name', 'field3' => 'e.position', 'field4' => 'e.emp_type' ),
                        array( $search, $search, $search, $search ),
                        array( 'field1' => 'e.emp_id', 'field2' => 'e.name', 'field3' => 'e.position', 'field4' => 'e.emp_type' ),
                        array( $search, $search, $search, $search ),
                        array( 'field1' => 'e.emp_id', 'field2' => 'e.name', 'field3' => 'e.position', 'field4' => 'e.emp_type' ),
                        array( $search, $search, $search, $search ),                      
                        "ASC",   // order by field
                        "name", // order by ASC or DESC
                        $limit, // per page
                        $start,  // start of the page
                        array( 'leveling_subordinates as l' ),
                        array( 'e.emp_id = l.subordinates_rater' ),  
                        null,
                        null,
                        "l.ratee = '".$this->session->userdata('emp_id')."'  and e.current_status = 'Active' "
                    );

            $totalFiltered = count($datas);
        }

        //load data in the table
        $data = array();
        if(!empty($datas)){
            foreach ($datas as $list){    
                $nestedData['']                 = '<input type="checkbox" class="select-checkbox chk"  name="cbname[]" value="' . $list['emp_id'] . '">';           
                $nestedData['EMPID']            = $list['emp_id'];
                $nestedData['NAME']             = ucwords(strtolower($list['name']));
                $nestedData['POSITION']         = $list['position'];
                $nestedData['EMPTYPE']          = $list['emp_type'];
                $nestedData['DEPARTMENT']       = $this->get_dept($list['emp_type'],$list['company_code'],$list['bunit_code'],$list['dept_code'])."".$this->get_section($list['emp_type'],$list['company_code'],$list['bunit_code'],$list['dept_code'],$list['section_code']);
                $nestedData['DATE HIRED']       = $this->get_datehired($list['emp_id']);
                $data[] = $nestedData;
            }
        }else{
            $nestedData['EMPID']        = "No Record Found";
            $nestedData['NAME']         = "";
            $nestedData['POSITION']     = "";
            $nestedData['EMPTYPE']      = "";
            $nestedData['DEPARTMENT']   = "";
            $nestedData['DATE HIRED']   = "";
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

    public function load_images()
    {
        $data['title']      = $this->title;
        $data['url']        = $this->url; 
        $data['menu']       = "subordinates"; //used in header
        $data['submenu']    = "images"; //used in header
        $data['tblApi']     = ""; //find in routes

        $result  = $this->dbmodel
            ->get_data_by_join(
                'employee3 as e',
                array( 'tbl2' => 'leveling_subordinates as l', 'tbl3' => 'applicant as a'),
                array( 'e.emp_id = l.subordinates_rater', 'e.emp_id = a.app_id'),
                'photo, emp_id, name, position',
                array( 'field1' => 'l.ratee', 'field2' => 'e.current_status' ),
                array( $this->session->userdata('emp_id'), 'Active' ),
                'DESC', 
                'poslevel, position'
            );  
        
        $startIndex = isset($_GET['startIndex']) ? intval($_GET['startIndex']) : 0;
        $batchSize = isset($_GET['batchSize']) ? intval($_GET['batchSize']) : 20;
        
         
        $output = '';        
        $arr_im = $arr_em = $arr_po = $arr_id = [];
        
        foreach($result as $row){
            $url = "http://172.16.161.100/hrms/employee/".$row['photo'];
            array_push($arr_im,$url);
            array_push($arr_em,$row['name']);
            array_push($arr_po,$row['position']);
            array_push($arr_id,$row['emp_id']);
        }
       
        
        for ($i = $startIndex; $i < min($startIndex + $batchSize, count($arr_im)); $i++) { // 
            $image = $arr_im[$i];
            if ($image !== '.' && $image !== '..') {
                $output .= '
                    <div class="row text-center" style="margin:auto">
                        <div class="col-md-12" style="width:230px;">  
                            <a href="'.base_url().'supervisor/profile/'.$arr_id[$i].'" title="Click to Show Profile" target="_blank">
                            <img src="'.$image.'" width="150" height="150" style="border-radius:70%;">  </a> &nbsp; &nbsp;    
                            <br> <span style="font-size:12px"> <center> '.ucwords(strtolower($arr_em[$i])).' </center>   </span>                           
                            <span style="font-size:12px"> <center> '.ucwords(strtolower($arr_po[$i])).' </center>   </span> <br>                              
                        </div>
                    </div> '; 
            }            
        }
        
        echo $output;    
    }

    public function subordinate_images()
    {
        $data['title']      = $this->title;
        $data['url']        = $this->url; 
        $data['menu']       = "subordinates"; //used in header
        $data['submenu']    = "images"; //used in header
        $data['tblApi']     = ""; //find in routes

        $data['result']  = $this->dbmodel
            ->get_data_by_join(
                'employee3 as e',
                array( 'tbl2' => 'leveling_subordinates as l', 'tbl3' => 'applicant as a'),
                array( 'e.emp_id = l.subordinates_rater', 'e.emp_id = a.app_id'),
                'photo, emp_id, name, position',
                array( 'field1' => 'l.ratee', 'field2' => 'e.current_status' ),
                array( $this->session->userdata('emp_id'), 'Active' ),
                'DESC', 
                'poslevel, position'
            );  

        $this->load->view('template/header', $data);
        $this->load->view('employee/supervisor/subordinates_images',$data);
        $this->load->view('template/footer', $data);
    }

    public function load_subs() //load subordinates
    {
        //searching columns
        $columns = array( 
            0 =>'emp_id',
            1 =>'name',
            2 =>'position',
            3 =>'emp_type'
        );

        //default
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];


        $totalData =  $this->dbmodel
            ->dt_get_where_count(
                'employee3 as e',
                'emp_id',
                array( 'field1' => 'l.ratee', 'field2' => 'e.current_status' ),
                array( $this->session->userdata('emp_id'), 'Active' ),
                array(),
                array() ,
                array( 'tbl1' => 'leveling_subordinates as l'),
                array( 'e.emp_id = l.subordinates_rater')                
            );

        $totalFiltered = $totalData; 

        //if search box is empty // default load
        if(empty($this->input->post('search')['value'])) {                  
            $datas = $this->dbmodel
                    ->get_data_by_join(
                        'employee3 as e',
                        array( 'leveling_subordinates as l'),
                        array( 'e.emp_id = l.subordinates_rater') , 
                        '*, name, DATE_FORMAT(e.startdate,"%m/%d/%Y") AS startdate, DATE_FORMAT(e.eocdate,"%m/%d/%Y") AS eocdate',
                        array( 'field1' => 'l.ratee', 'field2' => 'e.current_status' ),
                        array( $this->session->userdata('emp_id'), 'Active' ),
                        "ASC",   // order by field
                        "name", // order by ASC or DESC
                        $limit, // per page
                        $start  // start of the page
                    ); 
        }else{
            //if search value is not empty
            $search = $this->input->post('search')['value']; 
            $datas  = $this->dbmodel                    
                    ->dt_get_where_like(
                        'employee3 as e',
                       '*, name, DATE_FORMAT(e.startdate,"%m/%d/%Y") AS startdate, DATE_FORMAT(e.eocdate,"%m/%d/%Y") AS eocdate',
                        array( 'field1' => 'e.emp_id', 'field2' => 'e.name', 'field3' => 'e.position', 'field4' => 'e.emp_type' ),
                        array( $search, $search, $search, $search ),
                        array( 'field1' => 'e.emp_id', 'field2' => 'e.name', 'field3' => 'e.position', 'field4' => 'e.emp_type' ),
                        array( $search, $search, $search, $search ),
                        array( 'field1' => 'e.emp_id', 'field2' => 'e.name', 'field3' => 'e.position', 'field4' => 'e.emp_type' ),
                        array( $search, $search, $search, $search ),                      
                        "ASC",   // order by field
                        "name", // order by ASC or DESC
                        $limit, // per page
                        $start,  // start of the page
                        array( 'leveling_subordinates as l' ),
                        array( 'e.emp_id = l.subordinates_rater' ),  
                        null,
                        null,
                        "l.ratee = '".$this->session->userdata('emp_id')."'  and e.current_status = 'Active' "
                    );

            $totalFiltered = count($datas);
        }

        //load data in the table
        $data = array();
        if(!empty($datas)){
            foreach ($datas as $list){               
                $nestedData['EMPID']            = $list['emp_id'];
                $nestedData['NAME']             = ucwords(strtolower($list['name']));
                $nestedData['POSITION']         = $list['position'];
                $nestedData['EMPTYPE']          = $list['emp_type'];
                $nestedData['DEPARTMENT']       = $this->get_dept($list['emp_type'],$list['company_code'],$list['bunit_code'],$list['dept_code'])."".$this->get_section($list['emp_type'],$list['company_code'],$list['bunit_code'],$list['dept_code'],$list['section_code']);
                $nestedData['DATE HIRED']       = $this->get_datehired($list['emp_id']);
                $nestedData['ACTION']           = "<a href='".base_url().'supervisor/profile/'.$list['emp_id']."' target='_blank' title='Show Profile'> 
                                                    <img src='".base_url()."assets/images/icons/profile.png' width='20'> 
                                                  </a>
                                                  <a href='#' title='Show EPAS Details' 
                                                    type='button'                                                    
                                                    modal-size='modal-xl'
                                                    modal-route=''
                                                    modal-form='supervisor/subordinates/getappraisal/".$list['emp_id']."'  
                                                    modal-redirect= ''                            
                                                    modal-skeleton='0'
                                                    modal-id=''
                                                    modal-atype='POST'
                                                    modal-title='APPRAISAL RATING HISTORY'
                                                    modal-button= 'false'
                                                    onclick='modal(event)'>
                                                    <img src='".base_url()."assets/images/icons/rating.png' width='20'> 
                                                </a>";
                $data[] = $nestedData;
            }
        }else{
            $nestedData['EMPID']        = "No Record Found";
            $nestedData['NAME']         = "";
            $nestedData['POSITION']     = "";
            $nestedData['EMPTYPE']      = "";
            $nestedData['DEPARTMENT']   = "";
            $nestedData['DATE HIRED']   = "";
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

    //SHOW APPRAISAL HISTORY IS SUBORDINATES
    public function get_appraisals($empid){    
           
        $data['name'] = @$this->dbmodel
            ->get_row(
                'employee3',
                'name',
                array( 'field1' => 'emp_id'),
                array( $empid )
            )->name;

        $data['emphistory']  = $this->dbmodel  
            ->get_all_data(
                'employmentrecord_',
                '*, DATE_FORMAT(startdate,"%m/%d/%Y") AS startdate, DATE_FORMAT(eocdate,"%m/%d/%Y") AS eocdate',
                'DESC',                
                'startdate',
                null,null,null,
                'emp_id = "'.$empid.'"'
            );   
        $this->load->view('employee/supervisor/subordinates_appraisals',$data);
    }

    //GET THE APPRAISAL ANSWER OF APPRAISAL DETAILS
    public function get_appraisal_answers($did)
    {     
        $result = $this->dbmodel
                ->get_row(
                    'appraisal_details',
                    '*, DATE_FORMAT(ratingdate,"%m/%d/%Y") AS ratingdate',
                    array( 'field1' => 'details_id'),
                    array( $did )
                );

        $data['questions'] = $this->dbmodel
                ->get_data_by_join(
                    "appraisal", 
                    array( "appraisal_answer" ), 
                    array( "appraisal_answer.appraisal_id = appraisal.appraisal_id") , 
                    "q_no, title, description, rate, appraisal.code ", 
                    array( 'field1' => 'details_id' ),
                    array( $did ),
                    null, null, null, null
                );
                                   
        $data['rater']     = $this->dbmodel
                ->get_row(
                    'employee3',
                    'name',
                    array( 'field1' => 'emp_id'),
                    array( $result->rater )
                )->name;
        
        $data['appraisaltype']  = $this->dbmodel
                ->get_row(
                    'appraisal_type',
                    '*',
                    array( 'field1' => 'code'),
                    array( $result->code )
                ); 

        $data['result']     = $result;            
        // $data['title']      = $this->title;
        // $data['url']        = $this->url;
        // $data['menu']       = "si";
        // $data['submenu']    = "sirate";         
        $this->load->view('employee/supervisor/subordinates_appraisal_answers',$data);
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
        if($result){
            return $result->date_hired; 
        }else{
            return "";
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
            return ucwords(strtolower($dept));  
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