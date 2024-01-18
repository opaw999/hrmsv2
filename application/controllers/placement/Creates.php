<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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

class Creates extends CI_Controller {

    function __construct(){
        parent::__construct();
        //$this->checkEmployeeLogin();
        $this->load->model('DB_model','dbmodel');
        
        $this->title = "HRMS [ Placement ]";
        $this->url   = "http://172.16.161.100/hrms/employee/";
       
    }

    // private function checkEmployeeLogin()
    // {
	// 	if(! $this->session->userdata('hrmsEmployeeSession')){
	// 		redirect('/'.$this->session->userdata('usertype'));
	// 	}
	// }

    public function index(){          
            
        $this->page_load("dashboard/dashboard","dashboard","");

        // $data['title']  = "Dashboard";
        // $this->load->view('template/header_placement', $data);
		// $this->load->view('placement/dashboard/dashboard', $data);
        // $this->load->view('template/footer', $data);
    }

    public function page_load($page,$menu=NULL,$datas = NULL){
        
        $data['title']  = $this->title;
        $data['url']    = $this->url;
        $data['menu']   = $menu;
        $data['submenu']= "";
        $data['tabMenu']= "";           
 
      
        $this->load->view('template/header_placement', $data);
        $this->load->view('placement/'.$page, $data); 
        $this->load->view('template/footer', $data);
    }

    public function form_add_blacklist()

        {   
            $data['title']  = $this->title;
            $data['url']    = $this->url;
            $data['tblApi']     = "";
            $data['submenu'] = "";
            $data['tabMenu'] = "";
            $data['names'] = $this->input->post('namesearch');

            $this->load->view('template/header_placement', $data);
            $this->load->view('placement/creates/create_blacklist',$data);
            $this->load->view('template/footer', $data);
        }

        public function modal_browse_blacklist()

        {   
           
            $this->load->view('placement/creates/modal_browse_blacklist');
            // $this->load->view('template/footer');
        }

         public function browse_blacklist()

        {   

            $this->load->view('placement/creates/browse_blacklist');
           
        }

        public function notapplicant_blacklist()

        {   
             $n1 = $this->input->post('ln') . ", " . $this->input->post('fn');
            $n2 = $this->input->post('ln') . "," . $this->input->post('fn');

            $check = $this->db->query("SELECT name from blacklist where name like '%$n1%' || name like '%$n2%' ");
            if($check->num_rows() > 0) {
                echo 1;
            } else {
                echo 0;
            }
        }

        
     public function savelogs($activity, $date, $time, $user, $username)

        {
            $data = array(
                'activity' => $activity,
                'date' => $date,
                'time' => $time,
                'user' => $user,
                'username' => $username
            );

            $this->db->insert('logs', $data);

            return $this->db->affected_rows() > 0;
        }



        
    public function splitString($v, $key)
        {
            $value 	= explode("$v", $key);
            if(count($value) > 1) {
                $id		= $value[0];
            }
            return @$id;
        }
    
     public function update_blacklist()
     
        {
                    
        $dateadded 	= date("Y-m-d");
        $datebls 	= date('Y-m-d', strtotime($this->input->post('datebls')));
        $emp 		= $this->input->post('empid');
        $reportedby = $this->input->post('reportedby');
        $reason 	= addslashes($this->input->post('reason'));	
        $status 	= 'blacklisted';
        $bdays		= $this->input->post('bdays');
        $addr		= $this->input->post('addr');
        $creator	= $this->input->post('reportedby');
        $namesearch = $this->input->post('namesearch');
        $ids 		= $this->splitString("*", $namesearch);
        // $ids = $idss[0];


        if($ids != "") {
            $key 	= explode("*", $namesearch);
            $id 	= $ids; //empid
            $name 	= utf8_decode(@$key[1]); //name
            $check 	= $this->db->query("SELECT app_id FROM blacklist where app_id = '$id ' ");	//SELECT *
        } else {
            $id 	= "";
            $name 	= utf8_decode($namesearch);
            $check 	= $this->db->query("SELECT name FROM blacklist where name = 'trim($name)' "); //SELECT *
        }

        if($check->num_rows() == 0) {
            //insert into blacklisted table
            //blacklist_no, app_id, name, date_blacklisted, date_added, reportedby, reason, status, staff
            $qu = $this->db->query("INSERT INTO blacklist
                    (blacklist_no, app_id,name, date_blacklisted,date_added,reportedby,reason,status,staff,bday,address)
                VALUES ('','$id','$name','$datebls','$dateadded','$reportedby','$reason','$status','$creator','$bdays','$addr')");

            //inserting to logs
            $date = date("Y-m-d");
            $time = date('H:i:s');
            $this->savelogs("Added " . $name . " to Blacklisted Employee", $date, $time, $this->session->userdata('emp_id'), $this->session->userdata('username'));

            //get the current contract status of the employee
            /*$select = mysql_query("SELECT record_no FROM `EMPLOYEE3` WHERE EMP_ID = '$id' order by record_no desc limit 1 ");
            $rr 	= mysql_fetch_array($select);
            $record = $rr['record_no'];*/

            if($datebls == date('Y-m-d')) { // if date today is equal to date blacklisted, updates users and employee3
                //update user status to inactive
                $updateusers 	= $this->db->query("UPDATE users set user_status = 'inactive' where emp_id = '$id' "); // edited 070919

                //update employee3 current_status to blacklisted
                $updatestatus 	= $this->db->query("UPDATE employee3 set current_status = 'blacklisted' where emp_id = '$id'  ");
            }
        }

            if($qu) {
                echo json_encode([
                    'status' => 200,
                    'response' => "success",
                    'modal_close'   => "true",
                    'redirect'   => "placement/creates/form_add_blacklist",
                    'response_message'  => "The Blacklist Information was successfully inserted.",
                ]);
            } else {
                echo json_encode([
                    'status' => 401,
                    'response'	=> "error",
                    'response_message'	=> "Unable to add the Blacklist Information."
                ]);
            }

        }

        public function form_jobtransfer()

        {
            
            $data['title']  = $this->title;
            $data['url']    = $this->url;
            $data['tblApi']     = "";
            $data['submenu'] = "";
            $data['tabMenu'] = "";
            // $data['names'] = $this->input->post('namesearch');

            $this->load->view('template/header_placement', $data);
            $this->load->view('placement/creates/create_jobtrans', $data);
            $this->load->view('template/footer', $data);


        }

        public function searchemployee ()

         {    
            $key = addslashes($_POST['str']);
            $val = "";
            $empname = $this->db->query("SELECT employee3.`emp_id`, `name` FROM `employee3`
                                            WHERE (current_status = 'Active' ) 
                                            AND (name like '%$key%' or employee3.emp_id = '$key')  order by name limit 10");
            //or current_status = 'End of Contract'
            foreach($empname->result_array() as $n) {
                $empId = $n['emp_id'];
                $name  = $n['name'];

                if($val != $empId) {
                    echo "<a class = \"nameFind\" href = \"javascript:void\" onclick=\"getEmpId('$empId*$name')\">[ " . $empId . " ] = " . $name . "</a></br>";
                } else {
                    echo "<a class = \"afont\" href = \"javascript:void\">No Result Found</a></br>";
                }
            }
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
                     array($cc)
                 );
         
             if (is_object($result)) {
                 return $result->company;
             } else {
                 // Handle the case where $result is not an object
                 return null; // Or any default value
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
                        array($cc, $bc)
                    );
                    if (is_object($result)) {
                        return $result->company;
                    } else {
                        // Handle the case where $result is not an object
                        return null; // Or any default value
                    }  
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
        

          public function jobtransfer()

            {
                
                $data['empid']  = $this->input->post('empId');
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
                    
                   
                // $data['names'] = $this->input->post('namesearch');
                $this->load->view('placement/creates/form_jobtransfer',$data);

            }

            public function getLevel()

            {
                $position = $this->input->post('position');
                $level= $this->dbmodel->get_field("lvlno", "position_leveling", "position_title = '".$position."' ");
                echo $level['lvlno'];
            }

            function insert_jobtransfer()

                {
                    $empid = $this->input->post('empid');
                    $effectivity= date('Y-m-d',strtotime($this->input->post('effectivity')));	 
                    $newcc  = $this->input->post('comp_code');
                    $newbc 	= $this->input->post('bunit_code');
                    $newdc 	= $this->input->post('dept_code'); 
                    $newsc 	= $this->input->post('sec_code');
                    $newssc = $this->input->post('ssec_code');
                    $newuc 	= $this->input->post('unit_code');
                    $transfertype = $this->input->post('transfer_type');
                    $emptype 	= $this->input->post('emptype');
                    $newposition= $this->input->post('newposition');
                    $level 		= $this->input->post('level');
                    $assignedfrom = "HRD";
                    $sup 		= explode("*",$this->input->post('supervisor'));
                    $supervisor = trim($sup[0]);
                    $status 	= "transferred";
                    $reason 	= "";
                    $process 	= "no";
                    $file 		= "";

                    $cc1     	= addslashes(@$this->input->post('cc1'));
                    $cc2     	= addslashes(@$this->input->post('cc2'));
                    $cc3     	= addslashes(@$this->input->post('cc3'));
                    $cc4     	= addslashes(@$this->input->post('cc4'));
                    $cc5     	= addslashes(@$this->input->post('cc5'));
                    $cc6     	= addslashes(@$this->input->post('cc6'));
                    $ccopy   	= $cc1."$".$cc2."$".$cc3."$".$cc4."$".$cc5."$".$cc6; 

                    $select 	= $this->db->query("SELECT record_no, position, payroll_no, poslevel, company_code, bunit_code, dept_code, section_code, sub_section_code, unit_code FROM employee3 WHERE emp_id = '$empid' ");
                     foreach($select->result_array() as $row){

                        $oldposition= $row['position'];
                        $oldlevel 	= $row['poslevel'];
                        $oldpayroll = $row['payroll_no'];
                        $record_no 	= $row['record_no'];
                        $cc 		= $row['company_code'];
                        $bc 		= $row['bunit_code'];
                        $dc 		= $row['dept_code'];
                        $sc 		= $row['section_code'];
                        $ssc 		= $row['sub_section_code'];
                        $uc 		= $row['unit_code'];
                    }

                    //old location
                    if($uc){          $oldlocation = $cc."-".$bc."-".$dc."-".$sc."-".$ssc."-".$uc; }
                    else if($ssc){    $oldlocation = $cc."-".$bc."-".$dc."-".$sc."-".$ssc;  }
                    else if($sc){     $oldlocation = $cc."-".$bc."-".$dc."-".$sc;  } 
                    else if($dc){     $oldlocation = $cc."-".$bc."-".$dc;  }
                    else if($bc){     $oldlocation = $cc."-".$bc;  }
                    else if($cc){     $oldlocation = $cc; }

                    // var_dump($oldlocation);


                    //new location
                    if($newuc){         $codes = $newuc;  }
                    else if($newssc){   $codes = $newssc; }
                    else if($newsc){    $codes = $newsc;  } 
                    else if($newdc){   	$codes = $newdc;  }
                    else if($newbc){ 	$codes = $newbc;  }
                    else if($newcc){    $codes = $newcc;  }

                    $newlocation = str_replace(".","-", $codes);

               


                    $entryby 	= $this->session->userdata('emp_id');;
	                $entrydate 	= date('Y-m-d');

                    $data = array(
                        'emp_id' => $empid,
                        'record_no' => $record_no,
                        'effectiveon' => $effectivity,
                        'old_position' => $oldposition,
                        'old_level' => $oldlevel,
                        'position' => $newposition,
                        'level' => $level,
                        'old_location' => $oldlocation,
                        'new_location' => $newlocation,
                        'carbon_copy' => $ccopy,
                        'assignedfrom' => $assignedfrom,
                        'supervision' => $supervisor,
                        'reason' => $reason,
                        'status' => $status,
                        'file' => $file,
                        'old_payroll_no' => $oldpayroll,
                        'entry_date' => $entrydate,
                        'entry_by' => $entryby,
                        'process' => $process,
                        'type_of_transfer' => $transfertype,
                        'transfer_to_emptype' => $emptype,
                    );

                    $insert = $this->dbmodel->add(
                        "employee_transfer_details",
                        $data
                    );

                    $transquery = $this->db->query("SELECT max(transfer_no) as transno from employee_transfer_details where emp_id = '$empid' ");
                    $tr         = $transquery->row_array();
                    $transno    = $tr['transno'];


                    if($insert){

                     $select = $this->db->query("SELECT * FROM leveling_subordinates WHERE ratee = '$supervisor' and subordinates_rater = '$empid' ");
                        if($select->num_rows() == 0){
                            //insert subordinates
                            $insert_sub = $this->db->query("INSERT INTO `leveling_subordinates`
                                (`record_no`, `ratee`, `subordinates_rater`, `ratee_stat`, `removeNo`) 
                                VALUES 
                                ('','$supervisor','$empid','','') ");
                        }
		
                        if($transfertype == "nescotoae") {
                            $loc = "placement/masterfile/pdf_nescotoae?rec=" . $record_no . "&empid=" . $empid . "&transno=" . $transno . "&dates=" . $entrydate;
                        } else {
                            $loc = "placement/masterfile/pdf_newtransfer?rec=" . $record_no . "&empid=" . $empid . "&transno=" . $transno . "&dates=" . $entrydate;
                        }

                     echo json_encode([
                            'status' => 200,
                            'response' => "success",
                            'modal_close'   => "true",
                            'redirect'   => $loc,
                            'response_message'  => "The Job Transfer Information was successfully inserted.",
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 401,
                            'response'	=> "error",
                            'response_message'	=> "Unable to insert the Job Transfer  Information."
                        ]);
                    }  

                }

                public function createKra ()

                {
                    $data['title']  = $this->title;
                    $data['url']    = $this->url;
                    $data['tblApi']     = "";
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
                    // $data['names'] = $this->input->post('namesearch');

                    $this->load->view('template/header_placement', $data);
                    $this->load->view('placement/creates/create_kra', $data);
                    $this->load->view('template/footer', $data);
                }

                public function insertKRA()
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
                                    'redirect' => 'placement/creates/createKra',    
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
                    
                public function createSoloparent ()

                {
                    $data['title']  = $this->title;
                    $data['url']    = $this->url;
                    $data['tblApi']     = "";
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
                    // $data['names'] = $this->input->post('namesearch');

                    $this->load->view('template/header_placement', $data);
                    $this->load->view('placement/creates/create_solo_parent', $data);
                    $this->load->view('template/footer', $data);
                }

                public function insertSoloParent()
                    {
                        
                        $soloParent		= $this->input->post('empid');
                        $empId      = explode("*",$soloParent);
                        $empid = $empId[0];
                        $dateexpiry = date("Y-m-d",strtotime($this->input->post('dateexpiry')));	
                        $entryby 	= $this->session->userdata('emp_id');
                        $entrydate 	= date('Y-m-d');
                        $status 	= "active"; //default

                        if(!isset($_FILES['dswdid']['tmp_name']) && !isset($_FILES['requestletter']['tmp_name']) ) {
                            echo "";
                        }else{		
                            //dswdid	
                            $image= addslashes(file_get_contents($_FILES['dswdid']['tmp_name']));
                            $image_name= addslashes($_FILES['dswdid']['name']);	
                            $array = explode(".",$_FILES["dswdid"]["name"]);
                            $fimage = "document/soloparent/".$empid."=".date('Y-m-d')."="."SoloParentID"."=".date('H-i-s-A').".".$array[1]; 
                            move_uploaded_file($_FILES["dswdid"]["tmp_name"],$fimage);			
                            
                            //requestletter
                            $rletter= addslashes(file_get_contents($_FILES['requestletter']['tmp_name']));
                            $rletter_name= addslashes($_FILES['requestletter']['name']);	
                            $array = explode(".",$_FILES["requestletter"]["name"]);
                            $rletter_image = "document/soloparent/".$empid."=".date('Y-m-d')."="."SoloRequestLetter"."=".date('H-i-s-A').".".$array[1]; 
                            move_uploaded_file($_FILES["requestletter"]["tmp_name"],$rletter_image);			
                            
                            $select = $this->db->query("SELECT * FROM solo_parent where emp_id = '$empid' and status = 'active' ");
                            if($select->num_rows() == 0){

                                $save=$this->db->query("INSERT INTO solo_parent (`solo_id`,`emp_id`,`date_entry`,`date_expiry`,`dswd_id`,`request_letter`,`entry_by`,`status`)
                                VALUES ('','$empid','$entrydate','$dateexpiry','$fimage','$rletter_image','$entryby','$status') ");
                                
                                if($save) {
                                    
                                    echo json_encode([
                                        'status' => 200,
                                        'response' => "success",
                                        'redirect' => 'placement/creates/createSoloparent',    
                                        // 'modal_close'   => "true",
                                        'response_message'  => "Solo Parent info was successfully Inserted.",
                                    ]);
                                } else {
                                    echo json_encode([
                                        'status' => 401,
                                        'response'	=> "error",
                                        'response_message'	=> "Unable to insert the Solo Parent info."
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

                    public function CreateLoyaltyAwardees ()

                    {
                        $data['title']  = $this->title;
                        $data['url']    = $this->url;
                        $data['tblApi']     = "";
                        $data['submenu'] = "";
                        $data['tabMenu'] = "";
    
    
                        $this->load->view('template/header_placement', $data);
                        $this->load->view('placement/creates/create_loyalty_awardees', $data);
                        $this->load->view('template/footer', $data);
                    }

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
     
}