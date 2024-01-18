<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Manila');
class Payroll extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->checkEmployeeLogin();
        $this->load->model('DB_model','dbmodel');
        $this->title = "HRMS [ ".ucwords(strtolower($this->session->userdata('sessionType')))." ]";
        $this->url   = "http://172.16.161.100/hrms/employee/";

    }

    private function checkEmployeeLogin()
    {
		if(! $this->session->userdata('hrmsEmployeeSession')){
			redirect('/'.$this->session->userdata('usertype'));
		}
	}

    public function page_load($page,$menu=NULL,$submenu=NULL,$tblapi=null,$datas=NULL,$datas2=NULL)
    {
        $data['title']      = $this->title;
        $data['url']        = $this->url;  
        $data['menu']       = $menu; 
        $data['submenu']    = $submenu; 
        $data['tblApi']     = $tblapi;

        $data['result']     = $datas;     
       
        ($datas2)? $data['results2'] = $datas2 : "";

        $this->load->view('template/header', $data);
        $this->load->view('employee/'.$page, $data);
        $this->load->view('template/footer', $data);
    }    

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
        $this->load->view('employee/payroll/modal_filter_employees',$data);   
    }

    public function view_csv()
    {    
        $data['company'] = "";
        $this->load->view('employee/payroll/modal_remittance',$data);   
    }

    public function filter_employee($code)
    {
        $data['title']      = $this->title;
        $data['url']        = $this->url;  
        $data['menu']       = "payroll"; //used in header
        $data['submenu']    = "filter employees"; //used in header
        $data['tblApi']     = "employee/payroll/filteremployees/".$code; //find in routes

        $this->load->view('template/header', $data);
        $this->load->view('employee/payroll/employees',$data);
        $this->load->view('template/footer', $data);
    }

    public function load_filter_active_employee($code)
    {
        $ncode  = explode(".",$code);
        $cc	   	= @$ncode[0];
        $bc		= @$ncode[1];
        $dc		= @$ncode[2];
        $sc		= @$ncode[3];
        $ssc	= @$ncode[4];
                
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
      
        $totalData =  $this->dbmodel
                        ->count_data(
                        "employee3",
                        "current_status = 'Active' AND company_code != '07' AND 
                        (emp_type IN('Regular','Probationary','Regular Partimer','Contractual','OJT','Seasonal','Back-Up','PTA','Summer Job','Partimer')) $loc");
            
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
                    "current_status = 'Active' AND company_code != '07' AND 
                    (emp_type IN('Regular','Probationary','Regular Partimer','Contractual','OJT','Seasonal','Back-Up','PTA','Summer Job','Partimer')) $loc"
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
                array('field1'=>'emp_id','field2'=>'name','field3'=>'DATE_FORMAT(startdate,"%m/%d/%Y")','field4'=>'emp_type'),
                array($search,$search,$search,$search),
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start, // start of the page
                NULL,
                NULL,
                NULL,
                NULL,
                "current_status = 'Active' AND company_code != '07' AND 
                (emp_type IN('Regular','Probationary','Regular Partimer','Contractual','OJT','Seasonal','Back-Up','PTA','Summer Job','Partimer')) $loc"
            );            
            $totalFiltered = count($datas);
        }

        //load data in the table
        $data = array();
        if(!empty($datas)){
            foreach ($datas as $list){               
                $nestedData['EMPID']        = '<a href="'.base_url().'supervisor/profile/'.$list['emp_id'].'" target="_blank">'. $list['emp_id'] .'</a>';
                $nestedData['PAYROLL']      = $list['payroll_no'];
                $nestedData['NAME']         = ucwords(strtolower($list['name']));
                $nestedData['POSITION']     = $list['position'];
                $nestedData['EMPTYPE']      = $list['emp_type'];
                $nestedData['DEPARTMENT']   = $this->get_bu($list['company_code'],$list['bunit_code'])."
                                              ".$this->get_dept($list['company_code'],$list['bunit_code'],$list['dept_code']);
                $nestedData['STATUS']       = $list['current_status'];
                $data[] = $nestedData;
            }
        }else{
            $nestedData['EMPID']        = "No Record Found";
            $nestedData['PAYROLL']      = "";
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

    public function search()
    {
        $this->page_load('payroll/search',"payroll","search");
    }

    public function noresult()
    {
        $this->page_load('payroll/noresult',"payroll","search");
    }

    public function search_results()
    {
        $val    = $this->input->get('searchval');
        $result = $this->dbmodel
                ->get_all_data("employee3", 
                "*", 
                "ASC", 
                "name", 
                NULL, 
                0, 
                NULL, 
                "current_status = 'Active' and (name LIKE '%$val%' or payroll_no = '$val')"
            );
        $this->page_load('payroll/search',"payroll","search","",$result);
    }

    public function check_duplicate()
    {     
        $result = $this->dbmodel->get_field("emp_id, payroll_no", "employee3", "payroll_no = '".$this->input->post('pid')."' and emp_id !='".$this->input->post('id')."' ");
        ($result['emp_id'] == "") ? $val = 1 : $val = 0 ;
        echo $val;             
    }

    public function save_payrollid()
    {
        $payrollno  = $this->get_empdetails($this->input->post('id'))->payroll_no;      
        $data       = array('payroll_no'  => $this->input->post('pid') );    
        $result     = $this->dbmodel->update("employee3",$data,"emp_id = '".$this->input->post('id')."' ");
              
        if($result){ 
            echo "true"; 
            $data   = array(
                'activity'      => "Updated PayrollNO of ".$this->get_empdetails($this->input->post('id'))->name." from ".$payrollno." to ".$this->input->post('pid'),
                'date'          => date("Y-m-d"),
                'time'          => date('H:i:s'),
                'user'          => $_SESSION['emp_id'],
                'username'      => $_SESSION['username']
            );           
            $result     = $this->dbmodel->add("logs", $data);
        } else { 
            echo "false";
        }
    }

    public function save_charging_company()
    {        
        $empname    = $this->get_empdetails( $this->input->post('empid') )->name;
        $old_pcc    = $this->get_data("employee3","emp_id", $this->input->post('empid') )->pcc;        
        $sal_no     = $this->get_data("employee3","emp_id", $this->input->post('empid') )->payroll_no;        
        $group_no   = @$this->get_data("pcc","pcc_code", $this->input->post('pcc') )->group_no;        
        
        // if this ccid/pcc has no group_no then set this as 20
        if($group_no == 0){
            $data   = array( 'group_no' => '20' ); 
            $this->dbmodel->update("pcc",$data,"pcc_code = '" . $this->input->post('pcc') . "' ");
        }

        // update employee3 pcc
        $data  = array( 'pcc' => $this->input->post('pcc')  ); 
        $query = $this->dbmodel->update("employee3",$data,"emp_id = '" . $this->input->post('empid') . "' ");

        if($query == true)
        {
            // save charging details
            $result= @$this->get_data("employee_charging","emp_id", $this->input->post('empid') );
            if($result)
            {
                // insert to employee_charging_history
                $old_com_id = $result->com_id;
                $data  = array( 
                    'id'            => $result->id,
                    'emp_id'        => $result->emp_id,
                    'com_id'        => $result->com_id,
                    'pcc_code'      => $result->pcc_code,
                    'payroll_no'    => $result->payroll_no,
                    'date_added_hist' => $result->date_added 
                ); 
                $this->dbmodel->add("employee_charging_history", $data);

                // update employee_charging
                $data  = array( 
                    'com_id'        => $this->input->post('company'),
                    'pcc_code'      => $this->input->post('pcc'), 
                    'payroll_no'    => $sal_no, 
                    'date_added'    => NOW() 
                ); 
                $this->dbmodel->update("employee_charging",$data,"emp_id = '" . $this->input->post('empid') . "' ");

                // save this transactions to logs 
                // comment for now
                // $log        =   date('Y-m-d H:i:s') . 
                //                 "|$sal_no|" . $this->input->post('empid'). 
                //                 "|$empname|" . $this->session->userdata('emp_id').
                //                 "|".$this->get_empdetails($this->session->userdata('emp_id'))->name.
                //                 "|$old_com_id-" . $this->input->post('company') . "\r\n";

                // $logDir     = "../logs/companycharging/";
                // $filename   = "companycharging-";
                // $nq->writeLogs($log,$logDir,$filename);

            }
            else{
                // if this employee's record does not exist then insert data to table
                $data  = array( 
                    'emp_id'        => $this->input->post('empid'),
                    'com_id'        => $this->input->post('company'),
                    'pcc_code'      => $this->input->post('pcc'),
                    'payroll_no'    => $sal_no,
                    'date_added'    => NOW()
                ); 
                $this->dbmodel->add("employee_charging", $data);
            }  
            
            //logs textfile
            // comment for now
            // Date/time | salaryno empid - name | updated by | old pcc new pcc
            // $log =  date('Y-m-d H:i:s') . 
            //         "|$sal_no|" . $this->input->post('empid'). 
            //         "|$empname|" . $this->session->userdata('emp_id').
            //         "|".$this->get_empdetails($this->session->userdata('emp_id'))->name.
            //         "|$old_com_id-" . $this->input->post('company') . "\r\n";
           
            // $logDir   = "../logs/costcenter/";
            // $filename = "costcenter-";
            // $nq->writeLogs($log,$logDir,$filename);
            // ends here...
    
            $activity = "Updated PCC of $empname from $old_pcc to " . $this->input->post('pcc');
            $data  = array( 
                'activity'      => $activity,
                'date'          => date('Y-m-d'),
                'time'          => date('H:i:s'),
                'user'          => $this->session->userdata('emp_id'),
                'username'      => $this->session->userdata('username')
            ); 
            $this->dbmodel->add("logs", $data);
            //echo '1';
            echo json_encode([
                'status' => 200,
                'response' => "success",
                'redirect' => 'employee/payroll/search_result?searchval='.$sal_no,
                'response_message'  => "Employee PCC and Charging Successfully Saved!"
            ]);
        }
        else{
           // echo '0';
            echo json_encode([
                'status' => 200,
                'response' => "error",
                'redirect' => 'employee/payroll/searches/',
                'response_message'  => "Saving Error!!!"
            ]);
        }
    }

    public function get_data($tbl,$whrFld,$whrVal)
    {
        $result = $this->dbmodel
            ->get_row(
                $tbl,
                "*",
                array( 'field1' => $whrFld),
                array( $whrVal )
            );       
        return $result;
    }

    public function get_empdetails($id)
    {
        $result = $this->dbmodel
            ->get_row(
                'employee3',
                '*',
                array( 'field1' => 'emp_id'),
                array( $id )
            );       
        return $result;
    }

    public function get_emp($payrollno)
    {
        $result = $this->dbmodel
            ->get_row(
                'employee3',
                '*',
                array( 'field1' => 'payroll_no'),
                array( $payrollno )
            );           
        if(!$result){ return false; } else { return $result; }
    }

    public function profile()
    {
        $data['title']      = $this->title;
        $data['url']        = $this->url;
        $data['menu']       = "payroll";
        $data['submenu']    = "profile"; 

        $payrollno          = $this->input->post('payrollno');
        $empid              = @$this->get_emp($payrollno)->emp_id;

        if($empid)
        {        
            $data['profile'] = $this->dbmodel
                ->get_row(
                    'applicant as a',
                    '*, DATE_FORMAT(birthdate,"%m/%d/%Y") AS bday, DATE_FORMAT(startdate,"%m/%d/%Y") AS startdate, DATE_FORMAT(eocdate,"%m/%d/%Y") AS eocdate',
                    array( 'field1' => 'emp_id'),
                    array( $empid ), 
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
                    array( $empid )
                );     
           
            $data['location']   = "";//$this->get_bu($result->company_code,$result->bunit_code)." ".$this->get_dept($result->company_code,$result->bunit_code,$result->dept_code);
         
            $this->load->view('template/header', $data);
            $this->load->view('employee/payroll/profile', $data);
        }else{
            $this->load->view('template/header', $data);
            $this->load->view('employee/payroll/noresult', $data);
            
        }
        $this->load->view('template/footer', $data);        
    }

    public function transfer()
    {        
        $this->page_load('payroll/transfer',"payroll","transfer","employee/payroll/load_transfer");
    }

    public function load_transfer()
    {
        //searching columns
        $columns = array( 
            0 =>'emp_id',
            1 =>'name',
            2 =>'emp_type',
            3 =>'payroll_no'
        );

        //default
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $totalData =  
            $this->dbmodel->countfromtablejoin(
                "tbl1.emp_id",
                "employee3 as tbl1", 
                "employee_transfer_details as tbl2",
                "tbl1.emp_id = tbl2.emp_id", 
                "current_status = 'Active' AND company_code !='07' AND
                (emp_type IN ('Regular','Probationary','Regular Partimer','Contractual','OJT','Seasonal','Back-Up','PTA','Summer Job','Partimer') )"
            );

        $totalFiltered = $totalData; 

        //if search box is empty // default load
        if(empty($this->input->post('search')['value'])) 
        {                    
            $datas = $this->dbmodel->getallfromtablejoin(
                    "tbl1.emp_id, payroll_no, name, emp_type, current_status, company_code, bunit_code, dept_code, section_code,
                    DATE_FORMAT(effectiveon,'%m/%d/%Y') AS effectiveon, old_position, tbl2.position, old_payroll_no, old_location, new_location", 
                    "employee3 as tbl1", 
                    "employee_transfer_details as tbl2",
                    "tbl1.emp_id = tbl2.emp_id",
                    "current_status = 'Active' AND company_code !='07' AND
                    (emp_type IN ('Regular','Probationary','Regular Partimer','Contractual','OJT','Seasonal','Back-Up','PTA','Summer Job','Partimer') )",
                    $order, $dir, $limit, $start
                );                       
        }else{
            //if search value is not empty
            $search = $this->input->post('search')['value']; 
            $datas  = $this->dbmodel->getallfromtablejoin(
                    "tbl1.emp_id, payroll_no, name, emp_type, current_status, company_code, bunit_code, dept_code, section_code, 
                    DATE_FORMAT(effectiveon,'%m/%d/%Y') AS effectiveon, old_position, tbl2.position, old_payroll_no, old_location, new_location", 
                    "employee3 as tbl1", 
                    "employee_transfer_details as tbl2",
                    "tbl1.emp_id = tbl2.emp_id",
                    "(tbl1.emp_id like '%$search%' or name like '%$search%') AND 
                    current_status = 'Active' AND company_code !='07' AND
                    (emp_type IN ('Regular','Probationary','Regular Partimer','Contractual','OJT','Seasonal','Back-Up','PTA','Summer Job','Partimer') )",
                    $order, $dir, $limit, $start
                );                     
            $totalFiltered = count($datas);
        }

        //load data in the table
        $data = array();
        if(!empty($datas)){
            foreach ($datas as $list){    

                $ol = explode('-',$list['old_location']);
				$nl = explode('-',$list['new_location']);

                $nestedData['EMPID']            = $list['emp_id'];
                $nestedData['NAME']             = ucwords(strtolower($list['name']));
                $nestedData['EFFECTIVE']        = $list['effectiveon'];
                $nestedData['FROM']             = $list['old_payroll_no']."<br>".
                                                  $list['old_position']."<br>".
                                                  @$this->get_bu($ol[0],$ol[1])." ".
                                                  @$this->get_dept($ol[0],$ol[1],$ol[2])." ".
                                                  @$this->get_section($ol[0],$ol[1],$ol[2],$ol[3]);
                $nestedData['TO']               = $list['payroll_no']."<br>".
                                                  $list['position']."<br>".
                                                  @$this->get_bu($nl[0],$nl[1])." ".
                                                  @$this->get_dept($nl[0],$nl[1],$nl[2])." ".
                                                  @$this->get_section($nl[0],$nl[1],$nl[2],$nl[3]);
                $nestedData['STATUS']           = $list['current_status'];
                $nestedData['ACTION']           = "";                
                $data[] = $nestedData;
            }
        }else{
            $nestedData['EMPID']                = "No Record Found";
            $nestedData['NAME']                 = "";
            $nestedData['EFFECTIVE']            = "";
            $nestedData['FROM']                 = "";
            $nestedData['TO']                   = "";
            $nestedData['STATUS']               = "";
            $nestedData['ACTION']               = "";
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

    public function filter_transfer($code)
    {
        $data['title']      = $this->title;
        $data['url']        = $this->url;  
        $data['menu']       = "payroll"; //used in header
        $data['submenu']    = "new"; //used in header
        $data['tblApi']     = "employee/payroll/load_transfer/".$code; //find in routes

        $this->load->view('template/header', $data);
        $this->load->view('employee/payroll/transfer',$data);
        $this->load->view('template/footer', $data);
    }

    public function load_filter_transfer($code)
    {
        $ncode  = explode(".",$code);
        $cc	   	= @$ncode[0];
        $bc		= @$ncode[1];
        $dc		= @$ncode[2];
        $sc		= @$ncode[3];
        $ssc	= @$ncode[4];
                
        if($cc != '')
        {		
            $e = "tbl1";
            if($ssc !=''){	@$loc = "and $e.company_code = '$cc' and $e.bunit_code = '$bc' and $e.dept_code = '$dc' and $e.section_code = '$sc' and $e.sub_section_code = '$ssc' "; }
            else if($sc !=''){	@$loc = "and $e.company_code = '$cc' and $e.bunit_code = '$bc' and $e.dept_code = '$dc' and $e.section_code = '$sc' ";  }
            else if($dc !=''){	@$loc = "and $e.company_code = '$cc' and $e.bunit_code = '$bc' and $e.dept_code = '$dc' ";  }
            else if($bc !=''){  @$loc = "and $e.company_code = '$cc' and $e.bunit_code = '$bc' "; }    
            else if($cc !=''){  @$loc = "and $e.company_code = '$cc'"; }
            else { $loc = ''; }
        }
        
        $dateto   = date("Y-m-d");
        $datefrom = date("Y-m-d", strtotime("-1 month"));
        
        //searching columns
        $columns = array( 
            0 =>'name'
        );

        //default
        $limit  = $this->input->post('length');
        $start  = $this->input->post('start');
        $order  = @$columns[$this->input->post('order')[0]['column']];
        $dir    = $this->input->post('order')[0]['dir'];

        $totalData =  
            $this->dbmodel->countfromtablejoin(
                "tbl1.emp_id",
                "employee3 as tbl1", 
                "employee_transfer_details as tbl2",
                "tbl1.emp_id = tbl2.emp_id", 
                "current_status = 'Active' AND company_code !='07' $loc AND
                (emp_type IN ('Regular','Probationary','Regular Partimer','Contractual','OJT','Seasonal','Back-Up','PTA','Summer Job','Partimer') )"
            );

            
        $totalFiltered = $totalData; 

        //if search box is empty // default load
        if(empty($this->input->post('search')['value'])) {            
            $datas = $this->dbmodel->getallfromtablejoin(
                "tbl1.emp_id, payroll_no, name, emp_type, current_status, company_code, bunit_code, dept_code, section_code,
                DATE_FORMAT(effectiveon,'%m/%d/%Y') AS effectiveon, old_position, tbl2.position, old_payroll_no, old_location, new_location", 
                "employee3 as tbl1", 
                "employee_transfer_details as tbl2",
                "tbl1.emp_id = tbl2.emp_id",
                "current_status = 'Active' AND company_code !='07' $loc AND
                (emp_type IN ('Regular','Probationary','Regular Partimer','Contractual','OJT','Seasonal','Back-Up','PTA','Summer Job','Partimer') )",
                $order, $dir, $limit, $start
            ); 
        }else{
            //if search value is not empty
            $search = $this->input->post('search')['value']; 
            $datas  = $this->dbmodel->getallfromtablejoin(
                    "tbl1.emp_id, payroll_no, name, emp_type, current_status, company_code, bunit_code, dept_code, section_code, 
                    DATE_FORMAT(effectiveon,'%m/%d/%Y') AS effectiveon, old_position, tbl2.position, old_payroll_no, old_location, new_location", 
                    "employee3 as tbl1", 
                    "employee_transfer_details as tbl2",
                    "tbl1.emp_id = tbl2.emp_id",
                    "(tbl1.emp_id like '%$search%' or name like '%$search%') AND 
                    current_status = 'Active' AND company_code !='07' $loc AND
                    (emp_type IN ('Regular','Probationary','Regular Partimer','Contractual','OJT','Seasonal','Back-Up','PTA','Summer Job','Partimer') )",
                    $order, $dir, $limit, $start
                );                     
            $totalFiltered = count($datas);
        }

        //load data in the table
        $data = array();
        if(!empty($datas)){
            foreach ($datas as $list){               
                $ol = explode('-',$list['old_location']);
				$nl = explode('-',$list['new_location']);

                $nestedData['EMPID']            = $list['emp_id'];
                $nestedData['NAME']             = ucwords(strtolower($list['name']));
                $nestedData['EFFECTIVE']        = $list['effectiveon'];
                $nestedData['FROM']             = $list['old_payroll_no']."<br>".
                                                  $list['old_position']."<br>".
                                                  @$this->get_bu($ol[0],$ol[1])." ".
                                                  @$this->get_dept($ol[0],$ol[1],$ol[2])." ".
                                                  @$this->get_section($ol[0],$ol[1],$ol[2],$ol[3]);
                $nestedData['TO']               = $list['payroll_no']."<br>".
                                                  $list['position']."<br>".
                                                  @$this->get_bu($nl[0],$nl[1])." ".
                                                  @$this->get_dept($nl[0],$nl[1],$nl[2])." ".
                                                  @$this->get_section($nl[0],$nl[1],$nl[2],$nl[3]);
                $nestedData['STATUS']           = $list['current_status'];
                $nestedData['ACTION']           = "";   
                $data[] = $nestedData;
            }
        }else{
            $nestedData['EMPID']                = "No Record Found";
            $nestedData['NAME']                 = "";
            $nestedData['EFFECTIVE']            = "";
            $nestedData['FROM']                 = "";
            $nestedData['TO']                   = "";
            $nestedData['STATUS']               = "";
            $nestedData['ACTION']               = "";
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
        
    public function upload_remittances()
    {        
        $data['result'] = $this->dbmodel
        ->get_all_data(
            'payroll_file',
            '*',
            null,
            null,
            null,null,null,
            null
        ); 
        $this->page_load('payroll/upload_remittances',"payroll","upload","",$data);
    }

    public function newemployees()
    {        
        $this->page_load('payroll/new',"payroll","new","employee/payroll/load_newemployees");
    }

    public function load_newemployees()
    {
        //searching columns
        $columns = array( 
            0 =>'emp_id',
            1 =>'name',
            2 =>'emp_type',
            3 =>'payroll_no'
        );

        $dateto   = date("Y-m-d");
        $datefrom = date("Y-m-d", strtotime("-1 month"));

        //default
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $totalData =  $this->dbmodel
                    ->count_data("employee3","current_status = 'Active' and tag_as = 'new' AND 
                    (emp_type IN ('Contractual','OJT','Seasonal','Back-Up','Contractual','PTA','Summer Job','Partimer')) AND
                    startdate BETWEEN '$datefrom' AND '$dateto' ");

        $totalFiltered = $totalData; 

        //if search box is empty // default load
        if(empty($this->input->post('search')['value'])) {            
                // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
            $datas = $this->dbmodel->get_all_data(
                        'employee3',   //table
                        '*, DATE_FORMAT(startdate,"%m/%d/%Y") AS sdate, DATE_FORMAT(eocdate,"%m/%d/%Y") AS edate',    // field selected
                        $dir,   // order by field
                        $order, // order by ASC or DESC
                        $limit, // per page
                        $start,  // start of the page
                        NULL,
                        "current_status = 'Active' AND tag_as = 'new' AND 
                        (emp_type IN ('Contractual','OJT','Seasonal','Back-Up','Contractual','PTA','Summer Job','Partimer')) AND
                        startdate BETWEEN '$datefrom' AND '$dateto' "
                    );
        }else{
            //if search value is not empty
            $search = $this->input->post('search')['value']; 
            $datas =  $this->dbmodel->dt_get_where_like(
                'employee3',   // table
                '*, DATE_FORMAT(startdate,"%m/%d/%Y") AS sdate, DATE_FORMAT(eocdate,"%m/%d/%Y") AS edate',    // field selected
                array('field1'=>'emp_id','field2'=>'name','field3'=>'payroll_no','field4'=>'emp_type'), // like field
                array($search,$search,$search,$search), // like field value
                array('field1'=>'emp_id','field2'=>'name','field3'=>'payroll_no','field4'=>'emp_type'), // like field
                array($search,$search,$search,$search), // or like field value
                array('field1'=>'emp_id','field2'=>'name','field3'=>'payroll_no','field4'=>'emp_type'),
                array($search,$search,$search,$search),
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start, // start of the page
                NULL,
                NULL,
                NULL,
                NULL,
                "current_status = 'Active' AND 
                (emp_type IN ('Contractual','OJT','Seasonal','Back-Up','Contractual','PTA','Summer Job','Partimer')) AND
                startdate BETWEEN '$datefrom' AND '$dateto' "
        
            );   
            $totalFiltered = count($datas);
        }

        //load data in the table
        $data = array();
        if(!empty($datas)){
            foreach ($datas as $list){   
                
                
                $nestedData['EMPID']        = '<a href="'.base_url().'supervisor/profile/'.$list['emp_id'].'" target="_blank">'. $list['emp_id'] .'</a>';
                $nestedData['PAYROLLNO']    = $list['payroll_no'];
                $nestedData['NAME']         = $list['name'];
                $nestedData['POSITION']     = $list['position'];
                $nestedData['EMPTYPE']      = $list['emp_type'];
                $nestedData['DEPARTMENT']   = $this->get_bu($list['company_code'],$list['bunit_code'])."
                                                ".$this->get_dept($list['company_code'],$list['bunit_code'],$list['dept_code']);
                $nestedData['STARTDATE']    = $list['sdate'];
                $nestedData['EOCDATE']      = $list['edate'];
                $data[] = $nestedData;
            }
        }else{
            $nestedData['EMPID']        = "No Record Found";
            $nestedData['PAYROLLNO']    = "";
            $nestedData['NAME']         = "";
            $nestedData['POSITION']     = "";
            $nestedData['EMPTYPE']      = "";
            $nestedData['DEPARTMENT']   = "";
            $nestedData['STARTDATE']    = "";
            $nestedData['EOCDATE']      = "";
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

    public function filter_newemployee($code)
    {
        $data['title']      = $this->title;
        $data['url']        = $this->url;  
        $data['menu']       = "payroll"; //used in header
        $data['submenu']    = "new"; //used in header
        $data['tblApi']     = "employee/payroll/filternewemployees/".$code; //find in routes

        $this->load->view('template/header', $data);
        $this->load->view('employee/payroll/new',$data);
        $this->load->view('template/footer', $data);
    }

    public function load_filter_new_employee($code)
    {
        $ncode  = explode(".",$code);
        $cc	   	= @$ncode[0];
        $bc		= @$ncode[1];
        $dc		= @$ncode[2];
        $sc		= @$ncode[3];
        $ssc	= @$ncode[4];
                
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
        
        $dateto   = date("Y-m-d");
        $datefrom = date("Y-m-d", strtotime("-1 month"));
        
        //searching columns
        $columns = array( 
            0 =>'name'
        );

        //default
        $limit  = $this->input->post('length');
        $start  = $this->input->post('start');
        $order  = @$columns[$this->input->post('order')[0]['column']];
        $dir    = $this->input->post('order')[0]['dir'];

        $totalData =  $this->dbmodel
                        ->count_data(
                        "employee3",
                        "current_status = 'Active' AND company_code != '07' AND 
                        (emp_type IN('Contractual','OJT','Seasonal','Back-Up','Contractual','PTA','Summer Job','Partimer')) $loc AND
                        startdate BETWEEN '$datefrom' AND '$dateto' ");
            
        $totalFiltered = $totalData; 

        //if search box is empty // default load
        if(empty($this->input->post('search')['value'])) {            
                // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
                $datas = $this->dbmodel->get_all_data(
                    'employee3',   //table
                    '*, DATE_FORMAT(startdate,"%m/%d/%Y") AS sdate, DATE_FORMAT(eocdate,"%m/%d/%Y") AS edate',    // field selected
                    $dir,   // order by field
                    $order, // order by ASC or DESC
                    $limit, // per page
                    $start,  // start of the page
                    NULL,
                    "current_status = 'Active' AND company_code != '07' AND 
                    (emp_type IN('Contractual','OJT','Seasonal','Back-Up','Contractual','PTA','Summer Job','Partimer')) $loc  AND
                    startdate BETWEEN '$datefrom' AND '$dateto' "
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
                array('field1'=>'emp_id','field2'=>'name','field3'=>'DATE_FORMAT(startdate,"%m/%d/%Y")','field4'=>'emp_type'),
                array($search,$search,$search,$search),
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start, // start of the page
                NULL,
                NULL,
                NULL,
                NULL,
                "current_status = 'Active' AND company_code != '07' AND 
                (emp_type IN('Contractual','OJT','Seasonal','Back-Up','Contractual','PTA','Summer Job','Partimer')) $loc  AND
                startdate BETWEEN '$datefrom' AND '$dateto' "
            );            
            $totalFiltered = count($datas);
        }

        //load data in the table
        $data = array();
        if(!empty($datas)){
            foreach ($datas as $list){               
                $nestedData['EMPID']        = '<a href="'.base_url().'supervisor/profile/'.$list['emp_id'].'" target="_blank">'. $list['emp_id'] .'</a>';
                $nestedData['PAYROLL']      = $list['payroll_no'];
                $nestedData['NAME']         = ucwords(strtolower($list['name']));
                $nestedData['POSITION']     = $list['position'];
                $nestedData['EMPTYPE']      = $list['emp_type'];
                $nestedData['DEPARTMENT']   = $this->get_bu($list['company_code'],$list['bunit_code'])."
                                              ".$this->get_dept($list['company_code'],$list['bunit_code'],$list['dept_code']);
                $nestedData['STARTDATE']    = $list['sdate'];
                $nestedData['EOCDATE']      = $list['edate'];
                $data[] = $nestedData;
            }
        }else{
            $nestedData['EMPID']        = "No Record Found";
            $nestedData['PAYROLL']      = "";
            $nestedData['NAME']         = "";
            $nestedData['POSITION']     = "";
            $nestedData['EMPTYPE']      = "";
            $nestedData['DEPARTMENT']   = "";
            $nestedData['STARTDATE']    = "";
            $nestedData['EOCDATE']      = "";
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

    public function employees()
    {        
        $this->page_load('payroll/employees',"payroll","employees","employee/payroll/load_employees");
    }

    public function load_employees()
    {
        //searching columns
        $columns = array( 
            0 =>'emp_id',
            1 =>'name',
            2 =>'emp_type',
            3 =>'payroll_no'
        );

        //default
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $totalData =  $this->dbmodel->count_data(
            "employee3",
            "current_status = 'Active' AND company_code != '07' AND 
            (emp_type IN('Regular','Probationary','Regular Partimer','Contractual','OJT','Seasonal','Back-Up','PTA','Summer Job','Partimer'))");

        $totalFiltered = $totalData; 

        //if search box is empty // default load
        if(empty($this->input->post('search')['value'])) {    
            $datas = $this->dbmodel->get_all_data(
                'employee3',   //table
                '*',    // field selected
                "ASC",  // order by ASC or DESC 
                "name", // order by field
                $limit, // per page
                $start,  // start of the page
                NULL,
                "current_status = 'Active' AND company_code != '07' AND 
                (emp_type IN('Regular','Probationary','Regular Partimer','Contractual','OJT','Seasonal','Back-Up','PTA','Summer Job','Partimer'))"
            );       
        }else{
            //if search value is not empty
            $search = $this->input->post('search')['value']; 
            
            $datas =  $this->dbmodel->dt_get_where_like(
                'employee3',   // table
                '*, DATE_FORMAT(startdate,"%m/%d/%Y") AS sdate, DATE_FORMAT(eocdate,"%m/%d/%Y") AS edate',    // field selected
                array('field1'=>'emp_id','field2'=>'name','field3'=>'payroll_no','field4'=>'emp_type'), // like field
                array($search,$search,$search,$search), // like field value
                array('field1'=>'emp_id','field2'=>'name','field3'=>'payroll_no','field4'=>'emp_type'), // like field
                array($search,$search,$search,$search), // or like field value
                array('field1'=>'emp_id','field2'=>'name','field3'=>'payroll_no','field4'=>'emp_type'),
                array($search,$search,$search,$search),
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start, // start of the page
                NULL,
                NULL,
                NULL,
                NULL,
                "current_status = 'Active' AND 
                (emp_type IN('Regular','Probationary','Regular Partimer','Contractual','OJT','Seasonal','Back-Up','PTA','Summer Job','Partimer'))"
            ); 
            $totalFiltered = count($datas);
        }

        //load data in the table
        $data = array();
        if(!empty($datas)){
            foreach ($datas as $list){     
                $nestedData['EMPID']        = '<a href="'.base_url().'supervisor/profile/'.$list['emp_id'].'" target="_blank">'. $list['emp_id'] .'</a>';
                $nestedData['PAYROLLNO']    = $list['payroll_no'];
                $nestedData['NAME']         = ucwords(strtolower($list['name']));
                $nestedData['POSITION']     = $list['position'];
                $nestedData['EMPTYPE']      = $list['emp_type'];
                $nestedData['DEPARTMENT']   = $this->get_bu($list['company_code'],$list['bunit_code'])." ".$this->get_dept($list['company_code'],$list['bunit_code'],$list['dept_code']);
                $nestedData['STATUS']       = $list['current_status'];
                $data[] = $nestedData;
            }
        }else{
            $nestedData['EMPID']            = "No Record Found";
            $nestedData['PAYROLLNO']        = "";
            $nestedData['NAME']             = "";
            $nestedData['POSITION']         = "";
            $nestedData['EMPTYPE']          = "";
            $nestedData['DEPARTMENT']       = "";
            $nestedData['STATUS']           = "";
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

    public function blacklisted()
    {        
        $this->page_load('payroll/blacklisted',"payroll","blacklisted","employee/payroll/load_blacklisted");
    }

    public function load_blacklisted()
    {
        //searching columns
        $columns = array( 
            0 =>'app_id',
            1 =>'name',
            2 =>'date_blacklisted',
            3 =>'reason'
        );

        //default
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $totalData =  $this->dbmodel->count_data("blacklist");

        $totalFiltered = $totalData; 

        //if search box is empty // default load
        if(empty($this->input->post('search')['value'])) {            
                // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
            $datas = $this->dbmodel->get_all_data(
                        'blacklist',   //table
                        '*, DATE_FORMAT(date_blacklisted,"%m/%d/%Y") AS datebl',    // field selected
                        $dir,   // order by field
                        $order, // order by ASC or DESC
                        $limit, // per page
                        $start,  // start of the page
                        NULL,
                        NULL
                    );
        }else{
            //if search value is not empty
            $search = $this->input->post('search')['value']; 
            $datas =  $this->dbmodel->dt_get_where_like(
                        'blacklist',   // table
                        '*, DATE_FORMAT(date_blacklisted,"%m/%d/%Y") AS datebl',            // field seleted
                        array('field1'=>'app_id','field2'=>'name','field3'=>'date_blacklisted','field4'=>'reason'), // like field
                        array($search,$search,$search,$search), // like field value
                        array('field1'=>'app_id','field2'=>'name','field3'=>'date_blacklisted','field4'=>'reason'), // like field
                        array($search,$search,$search,$search), // or like field value
                        array('field1'=>'app_id','field2'=>'name','field3'=>'DATE_FORMAT(date_blacklisted,"%m/%d/%Y")','field4'=>'reason'),
                        array($search,$search,$search,$search),
                        $dir,   // order by field
                        $order, // order by ASC or DESC
                        $limit, // per page
                        $start // start of the page
                    );
            $totalFiltered = count($datas);
        }

        //load data in the table
        $data = array();
        if(!empty($datas)){
            foreach ($datas as $list){               
                $nestedData['EMPID']            = $list['app_id'];
                $nestedData['NAME']             = $list['name'];
                $nestedData['REASON']           = $list['reason'];
                $nestedData['DATE BLACKLISTED'] = $list['datebl'];
                $data[] = $nestedData;
            }
        }else{
            $nestedData['EMPID']            = "No Record Found";
            $nestedData['NAME']             = "No Record Found";
            $nestedData['REASON']           = "No Record Found";
            $nestedData['DATE BLACKLISTED'] = "No Record Found";
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
   
    public function position_leveling()
    {        
        $result = $this->dbmodel->get_all_data("position_leveling", "*");
        $this->page_load('payroll/positions',"payroll","poslevel","",$result);
    }
    
    public function pcc($grp = 0)
    {
        $data['title']      = $this->title;
        $data['url']        = $this->url;  
        $data['menu']       = "payroll"; 
        $data['submenu']    = "pcc";
        $data['grp']        = $grp;

        if($grp > 0){   
            $data['result'] = $this->dbmodel
                ->get_all_data("pcc", "*",null,null,null,0,null,
                "group_no = '$grp' and group_no !=0 ");       
        }else{
            $data['result'] = $this->dbmodel
                ->get_all_data("pcc", "*",null,null,null,0,null,
                "group_no !=0 ");
        }
        
        //$result2 = $this->dbmodel->get_all_data("pcc_group_name","*","stat ='active'");
        $data['results2'] = $this->dbmodel->get_all_data("pcc_group_name","*","stat ='active'");
        //$this->page_load('payroll/pcc',"payroll","pcc",$result,$result2);

        $this->load->view('template/header', $data);
        $this->load->view('employee/payroll/pcc', $data);
        $this->load->view('template/footer', $data);
    }

    public function load_pcc_with_employees($pcc)
    {
        $data['title']      = $this->title;
        $data['url']        = $this->url;
        $data['menu']       = "payroll";
        $data['submenu']    = "pcc"; 
        $data['pcc']        = $pcc;
       
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

        foreach($result as $list){
            $data['result'][] = array(
                'emp_id'        =>  $list['emp_id'],  
                'payroll_no'    =>  $list['payroll_no'], 
                'name'          =>  $list['name'],  
                'emp_type'      =>  $list['emp_type'],  
                'position'      =>  $list['position'], 
                'pcc'           =>  $list['pcc'],
                'dept'          =>  $this->get_bu($list['company_code'],$list['bunit_code'])." ".$this->get_dept($list['company_code'],$list['bunit_code'],$list['dept_code'])       
            );
        }
       
        $this->load->view('template/header', $data);
        $this->load->view('employee/payroll/pcc_with_employees', $data);
        $this->load->view('template/footer', $data);
    }

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
        }else if(@$result->acroname !=''){		
            $dept = " - ".@$result->acroname;
        }else{
            $dept = " - ".@$result->dept_name;	
        }
        return $dept;  
    }

    public function get_section($cc, $bc, $dc, $sc)
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
    }
}