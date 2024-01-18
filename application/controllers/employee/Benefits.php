<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Manila');
class Benefits extends CI_Controller 
{        
    function __construct(){
        parent::__construct();
        $this->checkEmployeeLogin();
        $this->load->model('DB_model','dbmodel');
        $this->load->library('custom');
        $this->load->library('ppdf');
        $this->title = "HRMS [ ".ucwords(strtolower($this->session->userdata('sessionType')))." ]";
    }

    private function checkEmployeeLogin(){
        if(!$this->session->userdata('hrmsEmployeeSession')){
            redirect($this->session->userdata('sessionType') . '/dashboard');
        }
    }    
       
    public function final_completion()
    {        
        $data['title']      = $this->title;
        $data['menu']       = "benefits";
        $data['submenu']    = "finalcompletion";
        $data['tblApi']     = "employee/finalcompletion";
        $this->load->view('template/header', $data);
        $this->load->view('employee/benefits/finalcompletion',$data);
        $this->load->view('template/footer', $data);        
    }
        
    public function get_all_finalcompletion()
    {
        $columns = array( 
            0 =>'lastname',
            1 =>'firstname',
            2 =>'position'            
        );
        
        $limit  = $this->input->post('length');
        $start  = $this->input->post('start');
        $order  = @$columns[$this->input->post('order')[0]['column']];
        $dir    = $this->input->post('order')[0]['dir'];
                        
        $totalData =  $this->dbmodel->count_data('applicant');            
        $totalData =  $this->dbmodel
                        ->count_data(
                            "applicants",
                            "status = 'for final completion' AND (position !='Lady Guard' && position !='Security Guard' && position !='Promodiser' && position !='Merchandiser' && position !='Service Crew') ");
  
        $totalFiltered = $totalData; 
                
        if(empty($this->input->post('search')['value'])) {            
            // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
            $datas = $this->dbmodel->get_all_data(
                'applicants',   //table
                '*',    // field selected
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start,  // start of the page
                null,
               "status = 'for final completion' AND (position !='Lady Guard' && position !='Security Guard' && position !='Promodiser' && position !='Merchandiser' && position !='Service Crew')"
            );
        }
        else
        {
            $search = $this->input->post('search')['value'];             
           
            $datas =  $this->dbmodel->dt_get_where_like(
                'applicants',   // table
                '*',   // field selected
                array('field1'=>'lastname','field2'=>'firstname','field3'=>'position'), // like field
                array($search,$search,$search,$search), // like field value
                array('field1'=>'lastname','field2'=>'firstname','field3'=>'position'), // like field
                array($search,$search,$search,$search), // or like field value
                array('field1'=>'lastname','field2'=>'firstname','field3'=>'position'),
                array($search,$search,$search,$search),
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start, // start of the page
                null,
                null,
                null,
                null,
                "status = 'for final completion' AND (position !='Lady Guard' && position !='Security Guard' && position !='Promodiser' && position !='Merchandiser' && position !='Service Crew')"
            );   
            $totalFiltered = count($datas);
        }
                
        $data = array();
        if(!empty($datas)){
            $c=1;
            foreach ($datas as $list){
                        
                if (trim($list['suffix']) != '') {                    
                    $name = $list['lastname'] . ', ' .$list['firstname'] . ' ' . $list['suffix'] . ', ' . $list['middlename'];
                } else {                    
                    $name = $list['lastname'] . ', ' . $list['firstname'] . ' ' . $list['middlename'];
                }     

                                
                $applic= $this->dbmodel
                ->get_row(
                    'applicant',
                    '*',
                    array( 'field1' => 'appcode'),
                    array($list['app_code'])
                );
                 @$appid = $applic->app_id;

                 
                $benef= $this->dbmodel
                ->get_row(
                    'applicant_otherdetails',
                    '*',
                    array( 'field1' => 'app_id'),
                    array($appid)
                );

                        
                $nestedData['No']             = $c++;
                $nestedData['Name']           ='<a href="'.base_url().'supervisor/profile/'.$appid.'" target="_blank">'. ucwords(strtolower($name)).'</a>'; 
                $nestedData['position']       = @$list['position']; 
                // <a href="'.base_url().'supervisor/profile/'.$list['app_id'].'" target="_blank">'. $name .'</a>';
                $nestedData['SSSno']          = @$benef->sss_no;
                $nestedData['Philheath']      = @$benef->philhealth;
                $nestedData['Pagibig RTN']    = @$benef->pagibig_tracking;
                $nestedData['Pagibig NO']     = @$benef->pagibig;
                $nestedData['Action']         = '<center><button
                                                    class="btn btn-sm btn-success" 
                                                    type="button"
                                                    modal-size=""
                                                    modal-route="employee/finalcompletionupdate"
                                                    modal-form="employee/modal_finalcompletion/'.$appid.'"
                                                    modal-skeleton="0"
                                                    modal-id=""
                                                    modal-atype="POST"
                                                    modal-title="Update Final Completion" 
                                                    onclick="modal(event)">Update</button></center>';
                
                $data[] = $nestedData;
                
            }
        }else{
            $nestedData['No']           = "No Record Found";
            $nestedData['Name']         = "";  
            $nestedData['position']     = "";
            $nestedData['SSSno']        = "";  
            $nestedData['Philheath']    = "";  
            $nestedData['Pagibig RTN']  = "";
            $nestedData['Pagibig NO']   = "";        
            $nestedData['Action']       = "";
            $data[] = $nestedData;
        }
                
        $json_data = array(
            "draw"            => intval($this->input->post('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );        
        echo json_encode($json_data);
    }
            
    public function update_finalcompletion()
    {
        $data = array(
            'sss_no' => $this->input->post('ssno'),
            'philhealth' => $this->input->post('philhealth'),
            'pagibig_tracking' => $this->input->post('pagibigrtn'),
            'pagibig' => $this->input->post('pagibigno'),
        );        
        
        $update=$this->dbmodel->update(
            "applicant_otherdetails",
            $data,
            "app_id = '".$this->input->post('app_id')."'"      
        );        
        
        if($update)
        {
            echo json_encode([
                'status' => 200,
                'response' => "success",
                'modal_close'   => "true",
                'response_message'  => "The Final completion benefits entry was successfully updated.",
            ]);
        }
        else
        {
            echo json_encode([
                'status' => 401,
                'response'	=> "error",
                'response_message'	=> "Unable to update the Final completion benefits entry."
            ]);
        }        
    }
    
    public function modal_final_completion($appid)
    {
        $datas = $this->dbmodel->get_data_by_join(
            "applicant as app1",
            array("table1"=>"applicants as app2","table2"=>"applicant_otherdetails as appot1"),
            array("app1.appcode = app2.app_code","app1.app_id = appot1.app_id"),
            "app1.no, 
            app1.app_id, 
            app1.lastname, 
            app1.firstname, 
            app1.middlename,
            app1.suffix,   
            app2.position, 
            appot1.sss_no,
            appot1.philhealth,
            appot1.pagibig_tracking,
            appot1.pagibig", 
            array(
                'field1'=>'app2.status',
                'field2'=>'app1.app_id'
            ),
            array(
                'for final completion',
                $appid
                )
            );
                    
        foreach ($datas as $list){
            
            if (trim($list['suffix']) != '') {
                
                $data['name'] = $list['lastname'] . ', ' .$list['firstname'] . ' ' . $list['suffix'] . ', ' . $list['middlename'];
            } else {
                
                $data['name'] = $list['lastname'] . ', ' . $list['firstname'] . ' ' . $list['middlename'];
            }
            $data['ssno'] = $list['sss_no'];
            $data['philhealth'] = $list['philhealth'];
            $data['pagibigrtn'] = $list['pagibig_tracking'];
            $data['pagibigno'] = $list['pagibig'];
            
        }
        $data['appid'] = $appid;
        
        $this->load->view('employee/benefits/modal_finalcompletion',$data);
    }
                
    public function all_employees()
    {
        $data['title']      = $this->title;
        $data['menu']       = "benefits";
        $data['tblApi']     = "employee/allemployees";
        $data['submenu']    = "all_employees";
        
        $this->load->view('template/header', $data);
        $this->load->view('employee/benefits/all_employees',$data);
        $this->load->view('template/footer', $data);        
    }
                
    public function modal_all_employees()                
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
        $this->load->view('employee/benefits/modal_all_employees',$data);
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
                
    public function get_all_employees()
    {
        $columns = array( 
            0 =>'emp_id',
            1 =>'name',
            1 =>'emp_type',
            
        );
        
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir   = $this->input->post('order')[0]['dir'];
                                     
        $totalData =  $this->dbmodel->count_data(
            "employee3",
            "current_status = 'Active' AND company_code != '07' AND 
                    (emp_type IN('Regular','Probationary','Regular Partimer','Contractual','OJT','Seasonal','Back-Up','PTA','Summer Job','Partimer'))"
        );
            
        $totalFiltered = $totalData; 
                        
        if(empty($this->input->post('search')['value'])) {            
            // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
            $datas = $this->dbmodel->get_all_data(
                'employee3',   //table
                '*',    // field selected
                "ASC", // order by ASC or DESC
                "name", // order by field
                $limit, // per page
                $start,  // start of the page
                null,
                "current_status = 'Active' AND company_code != '07' AND 
                            (emp_type IN('Regular','Probationary','Regular Partimer','Contractual','OJT','Seasonal','Back-Up','PTA','Summer Job','Partimer'))"
            );
        }
        else
        {
            $search = $this->input->post('search')['value']; 
            $datas  =  $this->dbmodel->dt_get_where_like(
                'employee3',   // table
                '*',    // field selected
                array('field1'=>'emp_id','field2'=>'name','field3'=>'emp_type'), // like field
                array($search,$search,$search,$search), // like field value
                array('field1'=>'emp_id','field2'=>'name','field3'=>'emp_type'), // like field
                array($search,$search,$search,$search), // or like field value
                array('field1'=>'emp_id','field2'=>'name','field3'=>'emp_type'),
                array($search,$search,$search,$search),
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start, // start of the page
                null,
                null,
                null,
                null,
                "current_status = 'Active' AND company_code != '07' AND 
                            (emp_type IN('Regular','Probationary','Regular Partimer','Contractual','OJT','Seasonal','Back-Up','PTA','Summer Job','Partimer'))"
            );

            $totalFiltered = count($datas);
        }
        $c      = 1;
        $data   = array();
        if(!empty($datas))
        {
            foreach ($datas as $list){

                $bunit= $this->dbmodel
                ->get_row(
                    'locate_business_unit',
                    'business_unit,acroname',
                    array( 'field1' => 'company_code',
                    'field2' =>'bunit_code'),
                    array(@$list['company_code'],@$list['bunit_code'])
                );
                
                $dept = $this->dbmodel
                ->get_row(
                    'locate_department',
                    'dept_name,acroname',
                    array( 'field1' => 'company_code',
                    'field2' => 'bunit_code',
                    'field3' =>'dept_code'),
                    array(@$list['company_code'],@$list['bunit_code'],@$list['dept_code'])
                );
                
                            
                $businessunit=@$bunit->acroname;
                $department=@$dept->acroname;
                // $bday=$app->birthdate;
                
                $n=$c++;
                $nestedData['No']                = "<center>$n</center>";
                $nestedData['Name']              = '<a href="'.base_url().'supervisor/profile/'.$list['emp_id'].'" target="_blank">'. ucwords(strtolower($list['name'])) .'</a>';
                $nestedData['Birthdate']         = @$this->applicantinfo($list['emp_id']);
                // $nestedData['Emptype']           = $list['emp_type'];
                $nestedData['BU/Dept']           =  @$businessunit.'-'.@$department;
                $nestedData['SSSno']             = @$this->benefits($list['emp_id'])->sss_no;
                $nestedData['Philheath']         = @$this->benefits($list['emp_id'])->philhealth;
                $nestedData['Pagibig RTN']       = @$this->benefits($list['emp_id'])->pagibig_tracking;
                $nestedData['Pagibig NO']        = @$this->benefits($list['emp_id'])->pagibig;                
                $data[] = $nestedData;                
            }
        }else{
            $nestedData['No']           = "No Record Found";
            $nestedData['Name']         = "No Record Found";
            $nestedData['Birthdate']    = "No Record Found";
            $nestedData['BU/Dept']      = "No Record Found";
            $nestedData['SSSno']        = "No Record Found";
            $nestedData['Philheath']    = "No Record Found";
            $nestedData['Pagibig RTN']  = "No Record Found";
            $nestedData['Pagibig NO']   = "No Record Found";            
            $data[] = $nestedData;
        }
                    
        $json_data = array(
            "draw"            => intval($this->input->post('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );        
        echo json_encode($json_data);
    }

    public function filter_employes($code)
    {        
        $data['title']      = $this->title;
        $data['menu']       = "benefits";
        $data['tblApi']     = "employee/filter_all_employees/".$code;
        $data['submenu']    = "all_employees";
        
        $this->load->view('template/header', $data);
        $this->load->view('employee/benefits/filter_all_employees',$data);
        $this->load->view('template/footer', $data);
    }

    public function load_filter_employes($code)
    {       
        $ncode  = explode(".", $code);
        $cc	   	= @$ncode[0];
        $bc		= @$ncode[1];
        $dc		= @$ncode[2];
        $sc		= @$ncode[3];
        $ssc	= @$ncode[4];

        if($cc != '') {
            $e = "employee3";
            if($ssc !='') {
                @$loc = "and $e.company_code = '$cc' and $e.bunit_code = '$bc' and $e.dept_code = '$dc' and $e.section_code = '$sc' and $e.sub_section_code = '$ssc' ";
            } elseif($sc !='') {
                @$loc = "and $e.company_code = '$cc' and $e.bunit_code = '$bc' and $e.dept_code = '$dc' and $e.section_code = '$sc' ";
            } elseif($dc !='') {
                @$loc = "and $e.company_code = '$cc' and $e.bunit_code = '$bc' and $e.dept_code = '$dc' ";
            } elseif($bc !='') {
                @$loc = "and $e.company_code = '$cc' and $e.bunit_code = '$bc' ";
            } elseif($cc !='') {
                @$loc = "and $e.company_code = '$cc'";
            } else {
                $loc = '';
            }
        }

        $columns = array( 
            0 =>'emp_id',
            1 =>'name',
            1 =>'emp_type',            
        );
        
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir   = $this->input->post('order')[0]['dir'];
                               
        $totalData =  $this->dbmodel->count_data(
            "employee3",
            "current_status = 'Active' AND company_code != '07' AND 
                            (emp_type IN('Regular','Probationary','Regular Partimer','Contractual','OJT','Seasonal','Back-Up','PTA','Summer Job','Partimer')) $loc"
        );

        $totalFiltered = $totalData;

        if(empty($this->input->post('search')['value'])) {
            // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
            $datas = $this->dbmodel->get_all_data(
                'employee3',   //table
                '*',    // field selected
                "ASC", // order by ASC or DESC
                "name", // order by field
                $limit, // per page
                $start,  // start of the page
                null,
                "current_status = 'Active' AND company_code != '07' AND 
                                    (emp_type IN('Regular','Probationary','Regular Partimer','Contractual','OJT','Seasonal','Back-Up','PTA','Summer Job','Partimer')) $loc"
            );

        } else {
            $search = $this->input->post('search')['value'];
            $datas  =  $this->dbmodel->dt_get_where_like(
                'employee3',   // table
                '*',    // field selected
                array('field1'=>'emp_id','field2'=>'name','field3'=>'emp_type'), // like field
                array($search,$search,$search,$search), // like field value
                array('field1'=>'emp_id','field2'=>'name','field3'=>'emp_type'), // like field
                array($search,$search,$search,$search), // or like field value
                array('field1'=>'emp_id','field2'=>'name','field3'=>'emp_type'),
                array($search,$search,$search,$search),
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start, // start of the page
                null,
                null,
                null,
                null,
                "current_status = 'Active'  AND company_code != '07' AND 
                                    (emp_type IN('Regular','Probationary','Regular Partimer','Contractual','OJT','Seasonal','Back-Up','PTA','Summer Job','Partimer')) $loc"
            );

            $totalFiltered = count($datas);
        }
        $c=1;
        $data = array();
        if(!empty($datas)) {
            foreach ($datas as $list) {

                $bunit= $this->dbmodel
                ->get_row(
                    'locate_business_unit',
                    'business_unit,acroname',
                    array( 'field1' => 'company_code',
                    'field2' =>'bunit_code'),
                    array(@$list['company_code'],@$list['bunit_code'])
                );

                $dept = $this->dbmodel
                ->get_row(
                    'locate_department',
                    'dept_name,acroname',
                    array( 'field1' => 'company_code',
                    'field2' => 'bunit_code',
                    'field3' =>'dept_code'),
                    array(@$list['company_code'],@$list['bunit_code'],@$list['dept_code'])
                );

                $businessunit=@$bunit->acroname;
                $department=@$dept->acroname;

                // $bday=$app->birthdate;
                $n=$c++;
                $nestedData['No']                = "<center>$n</center>";
                $nestedData['Name']              = '<a href="'.base_url().'supervisor/profile/'.$list['emp_id'].'" target="_blank">'. ucwords(strtolower($list['name'])) .'</a>';
                $nestedData['Birthdate']         = @$this->applicantinfo($list['emp_id']);
                // $nestedData['Emptype']           = $list['emp_type'];
                $nestedData['BU/Dept']           =  $businessunit."-".$department;
                $nestedData['SSSno']             = @$this->benefits($list['emp_id'])->sss_no;
                $nestedData['Philheath']         = @$this->benefits($list['emp_id'])->philhealth;
                $nestedData['Pagibig RTN']       = @$this->benefits($list['emp_id'])->pagibig_tracking;
                $nestedData['Pagibig NO']        = @$this->benefits($list['emp_id'])->pagibig;
                $data[] = $nestedData;
            }
        } else {
            $nestedData['No']           = "No Record Found";
            $nestedData['Name']         = "No Record Found";
            $nestedData['Birthdate']    = "No Record Found";
            $nestedData['BU/Dept']      = "No Record Found";
            $nestedData['SSSno']        = "No Record Found";
            $nestedData['Philheath']    = "No Record Found";
            $nestedData['Pagibig RTN']  = "No Record Found";
            $nestedData['Pagibig NO']   = "No Record Found";
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        echo json_encode($json_data);
    }
        
    public function new_employees()
    {
        $data['title']      = $this->title;
        $data['menu']       = "benefits";
        $data['submenu']    = "new_employees";
        $data['tblApi']     = "employee/newemployees";
        
        $this->load->view('template/header', $data);
        $this->load->view('employee/benefits/new_employees',$data);
        $this->load->view('template/footer', $data);        
    }        
                    
    public function get_new_employees()
    {       
        $dateto   = date("Y-m-d");
        $datefrom = date("Y-m-d", strtotime("-1 month"));
        $columns  = array( 
            0 =>'name',
            1 =>'no'
        );
        
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir   = $this->input->post('order')[0]['dir'];
        
        $totalData =  $this->dbmodel
                   ->count_data("employee3", "current_status = 'Active'   AND company_code != '07' AND tag_as = 'new' AND 
                    (emp_type IN ('Contractual','OJT','Seasonal','Back-Up','Contractual','PTA','Summer Job','Partimer')) AND
                    startdate BETWEEN '$datefrom' AND '$dateto' ");
                            
        $totalFiltered = $totalData; 
        
        if(empty($this->input->post('search')['value'])) {            
            // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);           
            $datas = $this->dbmodel->get_all_data(
                'employee3',   //table
                '*, DATE_FORMAT(startdate,"%m/%d/%Y") AS sdate, DATE_FORMAT(eocdate,"%m/%d/%Y") AS edate',    // field selected
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start,  // start of the page
                null,
                "current_status = 'Active'  AND company_code != '07' AND tag_as = 'new' AND 
                                    (emp_type IN ('Contractual','OJT','Seasonal','Back-Up','Contractual','PTA','Summer Job','Partimer')) AND
                                    startdate BETWEEN '$datefrom' AND '$dateto' "
            );

        }else{
            $search = $this->input->post('search')['value'];             
            $datas  =  $this->dbmodel->dt_get_where_like(
                'employee3',   // table
                '*, DATE_FORMAT(startdate,"%m/%d/%Y") AS sdate, DATE_FORMAT(eocdate,"%m/%d/%Y") AS edate',    // field selected
                array('field1'=>'emp_id','field2'=>'name','field3'=>'emp_type'), // like field
                array($search,$search,$search,$search), // like field value
                array('field1'=>'emp_id','field2'=>'name','field3'=>'emp_type'), // like field
                array($search,$search,$search,$search), // or like field value
                array('field1'=>'emp_id','field2'=>'name','field3'=>'emp_type'),
                array($search,$search,$search,$search),
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start, // start of the page
                null,
                null,
                null,
                null,
                "current_status = 'Active'  AND company_code != '07' AND 
                            (emp_type IN ('Contractual','OJT','Seasonal','Back-Up','Contractual','PTA','Summer Job','Partimer')) AND
                            startdate BETWEEN '$datefrom' AND '$dateto' "
            );
            $totalFiltered = count($datas);
        }
        $c=1;
        $data = array();
        if(!empty($datas)){
            foreach ($datas as $list){
                
               $ben = $this->dbmodel
                ->get_row(
                    'applicant_otherdetails',
                    '*',
                    array( 'field1' => 'app_id'),
                    array($list['emp_id'])
                );

                $n=$c++;
                $nestedData['No']             = "<center>$n</center>";
                $nestedData['Name']          = '<a href="'.base_url().'supervisor/profile/'.$list['emp_id'].'" target="_blank">'. ucwords(strtolower($list['name'])) .'</a>';
                $nestedData['Position']       = $list['position'];
                $nestedData['Emptype']       = $list['emp_type'];
                $nestedData['SSSno']          = @$ben->sss_no;
                $nestedData['Philheath']       = @$ben->philhealth;
                $nestedData['Pagibig RTN']       = @$ben->pagibig_tracking;
                $nestedData['Pagibig NO']       = @$ben->pagibig;
                
                $data[] = $nestedData;
                
            }
        }else{
            $nestedData['No']    = "No Record Found";
            $nestedData['Name']   = "No Record Found";
            $nestedData['Position']   = "No Record Found";
            $nestedData['Emptype']   = "No Record Found";
            $nestedData['SSSno']   = "No Record Found";
            $nestedData['Philheath']   = "No Record Found";
            $nestedData['Pagibig RTN']   = "No Record Found";
            $nestedData['Pagibig NO']   = "No Record Found";
            
            $data[] = $nestedData;
        }
        
        $json_data = array(
            "draw"            => intval($this->input->post('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );        
        echo json_encode($json_data);
    }

    public function filter_new_employees($code)
    {        
        $data['title']      = $this->title;
        $data['menu']       = "benefits";
        $data['tblApi']     = "employee/filter_newemployees/".$code;
        $data['submenu']    = "new_employees";
        
        $this->load->view('template/header', $data);
        $this->load->view('employee/benefits/filter_new_employees',$data);
        $this->load->view('template/footer', $data);
    }     

    public function load_filter_new_employees($code)
    {              
        $ncode  = explode(".", $code);
        $cc	   	= @$ncode[0];
        $bc		= @$ncode[1];
        $dc		= @$ncode[2];
        $sc		= @$ncode[3];
        $ssc	= @$ncode[4];

        if($cc != '') {
            $e = "employee3";
            if($ssc !='') {
                @$loc = "and $e.company_code = '$cc' and $e.bunit_code = '$bc' and $e.dept_code = '$dc' and $e.section_code = '$sc' and $e.sub_section_code = '$ssc' ";
            } elseif($sc !='') {
                @$loc = "and $e.company_code = '$cc' and $e.bunit_code = '$bc' and $e.dept_code = '$dc' and $e.section_code = '$sc' ";
            } elseif($dc !='') {
                @$loc = "and $e.company_code = '$cc' and $e.bunit_code = '$bc' and $e.dept_code = '$dc' ";
            } elseif($bc !='') {
                @$loc = "and $e.company_code = '$cc' and $e.bunit_code = '$bc' ";
            } elseif($cc !='') {
                @$loc = "and $e.company_code = '$cc'";
            } else {
                $loc = '';
            }
        }

        $dateto   = date("Y-m-d");
        $datefrom = date("Y-m-d", strtotime("-1 month"));

        $columns = array( 
            0 =>'name',
            1 =>'no'
        );
        
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir   = $this->input->post('order')[0]['dir'];
        
        $totalData =  $this->dbmodel
                        ->count_data(
                           "employee3",
                           "current_status = 'Active' AND company_code != '07' AND 
                        (emp_type IN('Contractual','OJT','Seasonal','Back-Up','Contractual','PTA','Summer Job','Partimer')) $loc AND
                        startdate BETWEEN '$datefrom' AND '$dateto' "
                    );
        
        $totalFiltered = $totalData; 
                            
        if(empty($this->input->post('search')['value'])) {            
            // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);            
            $datas = $this->dbmodel->get_all_data(
                'employee3',   //table
                '*, DATE_FORMAT(startdate,"%m/%d/%Y") AS sdate, DATE_FORMAT(eocdate,"%m/%d/%Y") AS edate',    // field selected
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start,  // start of the page
                null,
                "current_status = 'Active' AND company_code != '07' AND 
                                (emp_type IN('Contractual','OJT','Seasonal','Back-Up','Contractual','PTA','Summer Job','Partimer')) $loc  AND
                                startdate BETWEEN '$datefrom' AND '$dateto' "
            );

        }else{
            $search = $this->input->post('search')['value']; 
            $datas  =  $this->dbmodel->dt_get_where_like(
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
                null,
                null,
                null,
                null,
                "current_status = 'Active' AND company_code != '07' AND 
                            (emp_type IN('Contractual','OJT','Seasonal','Back-Up','Contractual','PTA','Summer Job','Partimer')) $loc  AND
                            startdate BETWEEN '$datefrom' AND '$dateto' "
            );
            
            $totalFiltered = count($datas);
        }
        $c=1;
        $data = array();
        if(!empty($datas)){
            foreach ($datas as $list){

            $ben = $this->dbmodel
                ->get_row(
                    'applicant_otherdetails',
                    '*',
                    array( 'field1' => 'app_id'),
                    array($list['emp_id'])
                );

                $n=$c++;
                $nestedData['No']           = "<center>$n</center>";
                $nestedData['Name']         ='<a href="'.base_url().'supervisor/profile/'.$list['emp_id'].'" target="_blank">'. ucwords(strtolower($list['name'])) .'</a>';
                $nestedData['Position']     = $list['position'];
                $nestedData['Emptype']      = $list['emp_type'];
                $nestedData['SSSno']        = @$ben->sss_no;
                $nestedData['Philheath']    = @$ben->philhealth;
                $nestedData['Pagibig RTN']  = @$ben->pagibig_tracking;
                $nestedData['Pagibig NO']   = @$ben->pagibig;
                $data[]                     = $nestedData;                
            }
        }else{
            $nestedData['No']               = "No Record Found";
            $nestedData['Name']             = "No Record Found";
            $nestedData['Position']         = "No Record Found";
            $nestedData['Emptype']          = "No Record Found";
            $nestedData['SSSno']            = "No Record Found";
            $nestedData['Philheath']        = "No Record Found";
            $nestedData['Pagibig RTN']      = "No Record Found";
            $nestedData['Pagibig NO']       = "No Record Found";            
            $data[] = $nestedData;
        }
        
        $json_data = array(
            "draw"            => intval($this->input->post('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );        
        echo json_encode($json_data);
    }
                        
    public function modal_new_employees()                        
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
        $this->load->view('employee/benefits/modal_new_employees',$data);
    }
                        
    public function blacklisted_employees()
    {
        $data['title']      = $this->title;
        $data['menu']       = "benefits";
        $data['tblApi']     = "employee/blacklist";
        $data['submenu']    = "blacklisted_employees";
        $this->load->view('template/header', $data);
        $this->load->view('employee/benefits/blacklisted_employees',$data);
        $this->load->view('template/footer', $data);
    }
                        
    public function getblacklist()
    {
        $columns = array( 
            0 =>'name'
        );
        
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir   = $this->input->post('order')[0]['dir'];
        
        $totalData =  $this->dbmodel->count_data('blacklist');        
        $totalFiltered = $totalData; 
        
        if(empty($this->input->post('search')['value'])) {            
            // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
            $datas = $this->dbmodel->get_all_data(
                'blacklist',   //table
                '*',    // field selected
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start  // start of the page
            );
        }else{
            $search = $this->input->post('search')['value']; 
            
            $datas =  $this->dbmodel->dt_get_where_like(
                'blacklist',   // table
                '*',            // field seleted
                array('field1'=>'name'), // like field
                array($search), // like field value
                array('field1'=>'name'),   // or like field
                array($search), // or like field value
                null,
                null,
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start  // start of the page
                
            );
            
            $totalFiltered = $this->dbmodel->dt_get_where_count(
                'blacklist',
                '*',
                array('field1'=>'name'),
                array($search),
                array('field1'=>'name'),
                array($search)
                
            );
        }
        
        $data = array();
        if(!empty($datas)){
            foreach ($datas as $list){
                $width= 60;
                $break= "</br>";
                $nestedData['Emp Id']   = $list['app_id'];
                $nestedData['Name']     = '<a href="'.base_url().'supervisor/profile/'.$list['app_id'].'" target="_blank">'. ucwords(strtolower($list['name'])) .'</a>';
                $nestedData['Date']     = $list['date_blacklisted'];
                $nestedData['Reason']   = wordwrap($list['reason'],$width,$break);
                $nestedData['Status']   = $list['status'];                
                $data[] = $nestedData;
                
            }
        }else{
            $nestedData['subsidiary']   = "No Record Found";
            $nestedData['Name']         = "No Record Found";
            $nestedData['Date']         = "No Record Found";
            $nestedData['Reason']       = "No Record Found";
            $nestedData['Status']       = "No Record Found";
            
            $data[] = $nestedData;
        }
        
        $json_data = array(
            "draw"            => intval($this->input->post('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );
                            
        echo json_encode($json_data);
    }
                        
    public function jobtransfer()
    {                            
        $data['title']      = "Jobtransfer";
        $data['menu']       = "benefits";
        $data['tblApi']     = "employee/jobtrans";
        $data['submenu']    = "jobtransfer";
        
        $this->load->view('template/header', $data);
        $this->load->view('employee/benefits/jobtransfer',$data);
        $this->load->view('template/footer', $data);                            
    }

    public function filter_transfer($id)
    {    
        $data['title']      = "FILTERED JOBTRANSFER";
        $data['menu']       = "benefits";
        $data['tblApi']     = "employee/filter_jobtransfer/".$id;
        $data['submenu']    = "jobtransfer";
        
        $this->load->view('template/header', $data);
        $this->load->view('employee/benefits/filter_jobtrans',$data);
        $this->load->view('template/footer', $data);
    }
                        
    public function get_jobtrans()
    {
        $columns = array( 
            0 =>'name',            
        );
        
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir   = $this->input->post('order')[0]['dir'];
                
        $totalData =  $this->dbmodel->count_data(
            'employee3 as emp1',
            null,
            array("table1"=>"employee_transfer_details as etd1"),
            array("emp1.emp_id = etd1.emp_id"),
            array(
                'field1'=>'emp1.current_status' 
            ),
            array(
                'Active' 
                )
            );                  
                                
        $totalFiltered = $totalData; 
        
        if(empty($this->input->post('search')['value'])) {            
            // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
            $datas = $this->dbmodel->get_data_by_join(
                "employee3 as emp1",
                array("table1"=>"employee_transfer_details as etd1"),
                array("emp1.emp_id = etd1.emp_id"),
                "emp1.name,
                emp1.emp_id, 
                etd1.effectiveon, 
                etd1.old_location, 
                etd1.new_location,
                etd1.position,  
                etd1.supervision", 
                array(
                    'field1'=>'emp1.current_status'
                ),
                array(
                    'Active' 
                ),
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start  // start of the page
            );
        }else{
            $search = $this->input->post('search')['value'];             
            $datas  =  $this->dbmodel->dt_get_where_like(
                "employee3 as emp1",
                "emp1.name, 
                emp1.emp_id, 
                etd1.effectiveon, 
                etd1.old_location, 
                etd1.new_location,
                etd1.position,  
                etd1.supervision",
                array('field1'=>'emp1.name'), // like field
                array($search), // like field value
                array('field1'=>'emp1.current_status'), // like field
                array('Active'), // like field value    
                array('field2'=>'emp1.name'),   // or like field
                array($search), // or like field value
                $dir,   // order by field
                $order, // order by ASC or DESC 
                $limit, // per page
                $start,  // start of the page
                array("table1"=>"employee_transfer_details as etd1"),
                array("emp1.emp_id = etd1.emp_id")
            );
            
            $totalFiltered = count($datas);
        }
                                
        $data = array();
        if(!empty($datas)){
            $c=1;
            foreach ($datas as $list){  
                
                $nl = explode('-',$list['new_location']);
                $ol = explode('-',$list['old_location']);
                
                // print_r($ol);
                // print_r($nl);
                
                $bunit= $this->dbmodel
                ->get_row(
                    'locate_business_unit',
                    'business_unit',
                    array( 'field1' => 'company_code',
                    'field2' =>'bunit_code'),
                    array($nl[0],$nl[1])
                );
                
                $bunitold= $this->dbmodel
                ->get_row(
                    'locate_business_unit',
                    'business_unit',
                    array( 'field1' => 'company_code',
                    'field2' =>'bunit_code'),
                    array($ol[0],@$ol[1])
                ); 
                
                $dept = $this->dbmodel
                ->get_row(
                    'locate_department',
                    'dept_name',
                    array( 'field1' => 'company_code',
                    'field2' => 'bunit_code',
                    'filed3' =>'dept_code'),
                    array($nl[0],$nl[1],@$nl[2])
                );
                
                $deptold = $this->dbmodel
                ->get_row(
                    'locate_department',
                    'dept_name',
                    array( 'field1' => 'company_code',
                    'field2' => 'bunit_code',
                    'filed3' =>'dept_code'),
                    array($ol[0],@$ol[1],@$ol[2])
                );
                                
                $bunit_new = @$bunit->business_unit;     
                $bunit_old = @$bunitold->business_unit;
                $dept_new  = @$dept->dept_name;
                $dept_old  = @$deptold->dept_name;
                
                $nestedData['Name']               = '<a href="'.base_url().'supervisor/profile/'.$list['emp_id'].'" target="_blank">'. ucwords(strtolower($list['name'])) .'</a>';
                $nestedData['Effective']          = $list['effectiveon'];
                $nestedData['Transfer From']      = $bunit_old."-".$dept_old;
                $nestedData['Transfer To']        =  $bunit_new."-".$dept_new;
                $nestedData['New Position']       = $list['position'];
                $nestedData['Direct Sup']         = $list['supervision'];
                $nestedData['Transfer']           = '<center><button
                class="btn btn-sm btn-success" 
                type="button"
                modal-size="modal-lg"
                modal-route=""
                modal-form="employee/modal_jobtrans/'.$list['emp_id'].'"
                modal-skeleton="0"
                modal-id=""
                modal-atype="GET"
                modal-button="false"
                modal-title="Job Transfer Report" 
                onclick="modal(event)">View</button></center>';
                
                $data[] = $nestedData;                
            }
        }else{
            $nestedData['Name']             = "No Record Found";
            $nestedData['Effective']        = "No Record Found";
            $nestedData['Transfer From']    = "No Record Found";
            $nestedData['Transder To']      = "No Record Found";
            $nestedData['New Position']     = "No Record Found";
            $nestedData['Direct Sup']       = "No Record Found";
            $nestedData['Transfer']         = "No Record Found";
            $data[] = $nestedData;
        }
        
        $json_data = array(
            "draw"            => intval($this->input->post('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );
        
        echo json_encode($json_data);
    }

    public function load_filter_transfer($id)
    {  
        $code = $id;
        if (strpos($code, '.') !== false) {
            $code = explode(".", $code);
            $condition1 = ($code[0]) ? array('field5' => 'company_code') : array();
            $condition2 = (@$code[1]) ? array('field6' => 'bunit_code') : array();
            $condition3 = (@$code[2]) ? array('field7' => 'dept_code') : array();
            // $condition4 = (@$code[3]) ? array('field8' => 'section_code') : array();
            // $condition5 = (@$code[4]) ? array('field9' => 'sub_section_code') : array();

            $condition1Value = ($code[0]) ? array($code[0]) : array();
            $condition2Value = (@$code[1]) ? array($code[1]) : array();
            $condition3Value = (@$code[2]) ? array($code[2]) : array();
        // $condition4Value = (@$code[3]) ? array($code[3]) : array();
        // $condition5Value = (@$code[4]) ? array($code[4]) : array();
        } else {
            $condition1 = ($code) ? array('field5' => 'company_code') : array();
            $condition2 = array();
            $condition3 = array();


            $condition1Value = array();
            $condition2Value = array();
            $condition3Value = array();

        }

        $defaultFields = array(
            'field1'=>'emp1.current_status',
        );
        $defaultValues = array(
            'Active'
        );

        $arrayFields = array_merge($defaultFields, $condition1, $condition2, $condition3);
        $arrayValues = array_merge($defaultValues, $condition1Value, $condition2Value, $condition3Value);

        $columns = array( 
            0 =>'name',                
        );
        
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        
        
        $totalData =  $this->dbmodel->count_data(
            'employee3 as emp1',
            null,
            array("table1"=>"employee_transfer_details as etd1"),
            array("emp1.emp_id = etd1.emp_id"),
            $arrayFields,
            $arrayValues
            );
                                    
        $totalFiltered = $totalData; 
        
        if(empty($this->input->post('search')['value'])) {            
            // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
            $datas = $this->dbmodel->get_data_by_join(
                "employee3 as emp1",
                array("table1"=>"employee_transfer_details as etd1"),
                array("emp1.emp_id = etd1.emp_id"),
                "emp1.name,
                emp1.emp_id, 
                etd1.effectiveon, 
                etd1.old_location, 
                etd1.new_location,
                etd1.position,  
                etd1.supervision", 
                $arrayFields,
                $arrayValues,
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start  // start of the page
            );
        }else{
            $search = $this->input->post('search')['value']; 
            
            $datas =  $this->dbmodel->dt_get_where_like(
                "employee3 as emp1",
                "emp1.name, 
                emp1.emp_id, 
                etd1.effectiveon, 
                etd1.old_location, 
                etd1.new_location,
                etd1.position,  
                etd1.supervision",
                array('field1'=>'emp1.name'), // like field
                array($search), // like field value
                null,
                null,
                null,
                null,
                $dir,   // order by field
                $order, // order by ASC or DESC 
                $limit, // per page
                $start,  // start of the page
                array("table1"=>"employee_transfer_details as etd1"),
                array("emp1.emp_id = etd1.emp_id"),
                $arrayFields,
                $arrayValues
            );
            
            $totalFiltered = count($datas);
        }
        
        $data = array();
        if(!empty($datas)){
            $c=1;
            foreach ($datas as $list){  
                
                
                
                $nl = explode('-',$list['new_location']);
                $ol = explode('-',$list['old_location']);
                
                // print_r($ol);
                // print_r($nl);
                
                $bunit= $this->dbmodel
                ->get_row(
                    'locate_business_unit',
                    'business_unit',
                    array( 'field1' => 'company_code',
                    'field2' =>'bunit_code'),
                    array($nl[0],$nl[1])
                );
                
                $bunitold= $this->dbmodel
                ->get_row(
                    'locate_business_unit',
                    'business_unit',
                    array( 'field1' => 'company_code',
                    'field2' =>'bunit_code'),
                    array($ol[0],@$ol[1])
                ); 
                
                $dept = $this->dbmodel
                ->get_row(
                    'locate_department',
                    'dept_name',
                    array( 'field1' => 'company_code',
                    'field2' => 'bunit_code',
                    'filed3' =>'dept_code'),
                    array($nl[0],$nl[1],@$nl[2])
                );
                
                $deptold = $this->dbmodel
                ->get_row(
                    'locate_department',
                    'dept_name',
                    array( 'field1' => 'company_code',
                    'field2' => 'bunit_code',
                    'filed3' =>'dept_code'),
                    array($ol[0],@$ol[1],@$ol[2])
                );
                
                
                $bunit_new = @$bunit->business_unit;     
                $bunit_old = @$bunitold->business_unit;
                $dept_new  = @$dept->dept_name;
                $dept_old   = @$deptold->dept_name;
                
                
                
                $nestedData['Name']               = '<a href="'.base_url().'supervisor/profile/'.$list['emp_id'].'" target="_blank">'. ucwords(strtolower($list['name'])) .'</a>';
                $nestedData['Effective']          = $list['effectiveon'];
                $nestedData['Transfer From']      = $bunit_old."-".$dept_old;
                $nestedData['Transfer To']        =  $bunit_new."-".$dept_new;
                $nestedData['New Position']       = $list['position'];
                $nestedData['Direct Sup']         = $list['supervision'];
                $nestedData['Transfer']           = '<center><button
                class="btn btn-sm btn-success" 
                type="button"
                modal-size="modal-lg"
                modal-route=""
                modal-form="employee/modal_jobtrans/'.$list['emp_id'].'"
                modal-skeleton="0"
                modal-id=""
                modal-atype="GET"
                modal-title="Job Transfer Report" 
                onclick="modal(event)">View</button></center>';
                
                $data[] = $nestedData;
                
            }
        }else{
            $nestedData['Name']             = "No Record Found";
            $nestedData['Effective']        = "No Record Found";
            $nestedData['Transfer From']    = "No Record Found";
            $nestedData['Transder To']      = "No Record Found";
            $nestedData['New Position']     = "No Record Found";
            $nestedData['Direct Sup']       = "No Record Found";
            $nestedData['Transfer']         = "No Record Found";
            $data[] = $nestedData;
        }
        
        $json_data = array(
            "draw"            => intval($this->input->post('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );
        
        echo json_encode($json_data);
    }
                            
    public function modal_jobtransfer($empId)                            
    {
        $data['getfile'] = $this->dbmodel
        ->get_row(
            'employee_transfer_details',
            'file',
            array( 'field1' => 'emp_id'),
            array($empId)
        );
        // return $result->file;  
        $this->load->view('employee/benefits/modal_jobtransfer',$data);
    }
                            
    public function filter_jobtrans()                            
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
        $this->load->view('employee/benefits/modal_filter_jobtransfer',$data);
    }
                            
    public function inactive_employees()
    {
        $data['title'] = $this->title;

        $data['menu']   = "benefits";
        $data['submenu']   = "inactive_employees";
        $data['tblApi']   = "employee/inactiveemployees";
        
        $this->load->view('template/header', $data);
        $this->load->view('employee/benefits/inactive_employees',$data);
        $this->load->view('template/footer', $data);        
    }
                            
    public function get_inactive_employees()
    {
        $columns = array( 
            0 =>'name',
            
        );
        
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
                
        $totalData =  $this->dbmodel->count_data(
            'termination as t1',
            null,
            array("table1"=>"employee3 as emp1"),
            array("t1.emp_id = emp1.emp_id"),
            null
        );
        
        $totalFiltered = $totalData; 
        
        if(empty($this->input->post('search')['value'])) {            
            // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
            $datas = $this->dbmodel->get_data_by_join(
                "termination as t1",
                array("table1"=>"employee3 as emp1"),
                array("t1.emp_id = emp1.emp_id"),
                "emp1.emp_id, 
                emp1.name, 
                emp1.emp_type, 
                t1.date, 
                t1.added_by, 
                t1.date_updated,
                t1.remarks", 
                null,
                null,
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start  // start of the page
            );
        }else{
            $search = $this->input->post('search')['value']; 
            
            $datas =  $this->dbmodel->dt_get_where_like(
                "termination as t1",
                "emp1.emp_id, 
                emp1.name, 
                emp1.emp_type, 
                t1.date, 
                t1.added_by, 
                t1.date_updated,
                t1.remarks", 
                array('field1'=>'emp1.name'), // like field
                array($search), // like field value
                null, // like field value
                null,   
                array('field2'=>'emp1.name'),   // or like field
                array($search), // or like field value
                $dir,   // order by field
                $order, // order by ASC or DESC 
                $limit, // per page
                $start,  // start of the page
                array("table1"=>"employee3 as emp1"), 
                array("t1.emp_id = emp1.emp_id")
            );
            
            $totalFiltered = count($datas);
        }
        
        $data = array();
        if(!empty($datas)){
            $c=1;
            foreach ($datas as $list){                
                
                $nestedData['No']            = $c++;
                $nestedData['Empid']         = $list['emp_id'];
                $nestedData['Name']          = '<a href="'.base_url().'supervisor/profile/'.$list['emp_id'].'" target="_blank">'. ucwords(strtolower($list['name'])) .'</a>';
                $nestedData['Emptype']       = $list['emp_type'];
                $nestedData['Resigned']      = $list['date'];
                $nestedData['Added_by']      = $list['added_by'];
                $nestedData['Updated']       = $list['date_updated'];
                $nestedData['Remarks']       = $list['remarks'];                
                $data[] = $nestedData;                
            }
        }else{
            $nestedData['No']               = "No Record Found";
            $nestedData['Empid']            = "No Record Found";
            $nestedData['Name']             = "No Record Found";
            $nestedData['Emptype']          = "No Record Found";
            $nestedData['Resigned']         = "No Record Found";
            $nestedData['Added_by']         = "No Record Found";
            $nestedData['Updated']          = "No Record Found";
            $nestedData['Remarks']          = "No Record Found";            
            $data[] = $nestedData;
        }
        
        $json_data = array(
            "draw"            => intval($this->input->post('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );
        
        echo json_encode($json_data);
    }
            
    public function qbe_reports()
    {
        $data['title']      = $this->title;
        $data['menu']       = "benefits";
        $data['submenu']    = "reports";
        $this->load->view('template/header', $data);
        $this->load->view('employee/benefits/qbe_reports',$data);
        $this->load->view('template/footer', $data);        
    }   

    public function getMonthsFromCurrentMonth() {
        // Get the current month number (1 to 12)
        $currentMonth = date('n');

        // Array to store the month names
        $monthsList = array();

        // Loop to get the next three months starting from the current month
        for ($i = 0; $i < 3; $i++) {
            $month = ($currentMonth + $i) % 12;
            if ($month === 0) $month = 12; // Handle December (12 % 12 = 0)
            
            // Get the full month name
            $monthName = date('F', mktime(0, 0, 0, $month, 1));
            $monthsList[] = $monthName;
        }

        // Return the array of month names
        return $monthsList;
    }

    public function newemployee()    
    {        
        $data['company'] = $this->dbmodel
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
 
        $data['monthsList'] = $this->getMonthsFromCurrentMonth();
        $this->load->view('employee/benefits/reportnewemployee',$data);
    }

    public function excelnewemployeereport()
    {        
        $code	= $this->input->get('code');
        $ec	 	= explode(".", $code);
        $cc	   	= @$ec[0];
        $bc		= @$ec[1];
        $dc		= @$ec[2];
        $sc		= @$ec[3];
        
        $data['bunit']= $this->dbmodel
            ->get_row(
                'locate_business_unit',
                'business_unit',
                array( 'field1' => 'company_code',
                'field2' =>'bunit_code'),
                array(@$ec[0],@$ec[1])
            );

        $data['dept'] = $this->dbmodel
            ->get_row(
                'locate_department',
                'dept_name',
                array( 'field1' => 'company_code',
                'field2' => 'bunit_code',
                'filed3' =>'dept_code'),
                array(@$ec[0],@$ec[1],@$ec[2])  
            );
        $this->load->view('employee/benefits/reportnewemployeeexcel', $data);
    }

    public function pdfnewemployeereport()
    {
        

        $code	= $this->input->get('code');
        $ec	 	= explode(".", $code);
        $cc	   	= @$ec[0];
        $bc		= @$ec[1];
        $dc		= @$ec[2];
        $sc		= @$ec[3];
    
        $data['bunit']= $this->dbmodel
            ->get_row(
                'locate_business_unit',
                'business_unit',
                array( 'field1' => 'company_code',
                'field2' =>'bunit_code'),
                array(@$ec[0],@$ec[1])
        );

        $data['dept'] = $this->dbmodel
            ->get_row(
                'locate_department',
                'dept_name',
                array( 'field1' => 'company_code',
                'field2' => 'bunit_code',
                'filed3' =>'dept_code'),
                array(@$ec[0],@$ec[1],@$ec[2])  
        );
          
        $this->load->view('employee/benefits/reportnewemployeepdf', $data);
    }

    public function nobenefits()
    {        
        $data['company'] = $this->dbmodel
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
        $this->load->view('employee/benefits/reportnobenefits',$data);
    }

    public function regularcontractual()
    {        
        $data['company'] = $this->dbmodel
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
        $this->load->view('employee/benefits/reportregularcontractual',$data);
    }
    
    public function excelregularcontractualreport()
    {
        $this->load->view('employee/benefits/excelreportregcon');
    }
    
    public function pdfnobenefits()
    {
        $code	= $this->input->get('code');
        $ec	 	= explode(".", $code);
        $cc	   	= @$ec[0];
        $bc		= @$ec[1];
        $dc		= @$ec[2];
        $sc		= @$ec[3];

        $data['bunit']= $this->dbmodel
            ->get_row(
                'locate_business_unit',
                'business_unit',
                array( 'field1' => 'company_code',
                'field2' =>'bunit_code'),
                array(@$ec[0],@$ec[1])
            );

        $data['dept'] = $this->dbmodel
            ->get_row(
                'locate_department',
                'dept_name',
                array( 'field1' => 'company_code',
                'field2' => 'bunit_code',
                'filed3' =>'dept_code'),
                array(@$ec[0],@$ec[1],@$ec[2])
        );

        $this->load->view('employee/benefits/pdfreportnobenefits', $data);
    }

    public function employeetype()
    {            
        $data['company'] = $this->dbmodel
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

        $this->load->view('employee/benefits/reportemptype',$data);
    }

    public function excelreportemptype()         
    {
    
        $code	= $this->input->get('code');
        $ec	 	= explode(".", $code);
        $cc	   	= @$ec[0];
        $bc		= @$ec[1];
        $dc		= @$ec[2];
        $sc		= @$ec[3];

        
        $data['bunit']= $this->dbmodel
        ->get_row(
            'locate_business_unit',
            'business_unit',
            array( 'field1' => 'company_code',
            'field2' =>'bunit_code'),
            array(@$ec[0],@$ec[1])
        );

        $data['dept'] = $this->dbmodel
            ->get_row(
                'locate_department',
                'dept_name',
                array( 'field1' => 'company_code',
                'field2' => 'bunit_code',
                'filed3' =>'dept_code'),
                array(@$ec[0],@$ec[1],@$ec[2])  
        );      

        $this->load->view('employee/benefits/excelreportemptype', $data);
    }

    public function search_employee()
    {
        $data['title'] = $this->title;

        $data['menu']   = "benefits";
        $data['submenu']   = "search_employee";
        $this->load->view('template/header', $data);
        $this->load->view('employee/benefits/search_employee',$data);
        $this->load->view('template/footer', $data);        
    }

    public function searchbyname()
    {        
        $data['title'] = "";
        $data['menu']   = "benefits";
        $data['submenu']   = "searchallemployee";
        $data['tblApi']   = "employee/search/searchallemployee";
        $this->load->view('template/header', $data);
        $this->load->view('employee/benefits/searchbyname',$data);
        $this->load->view('template/footer', $data);        
    }

    public function searchallbyname($id)
    {     
        $columns = array(
                    0 =>'lastname',
                    1 =>'firstname'

                );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = @$columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $totalData =  $this->dbmodel->count_data(
            'applicant_otherdetails as appo1',
            null,
            array("table1"=>"employee3 as emp1","table2"=>"applicant as app1"),
            array("appo1.app_id = emp1.emp_id","app1.app_id = emp1.emp_id"),
            array(
                'field1'=>'emp1.current_status',
                'field2'=>'emp1.emp_id'
            ),
            array(
                'Active',
                $id
                )
        );

        $totalFiltered = $totalData;

        if(empty($this->input->post('search')['value'])) {
            // $posts = $this->Homemodel->allposts($limit,$start,$order,$dir);
            $datas = $this->dbmodel->get_data_by_join(
                "applicant_otherdetails as appo1",
                array("table1"=>"employee3 as emp1","table2"=>"applicant as app1"),
                array("appo1.app_id = emp1.emp_id","app1.app_id = emp1.emp_id"),
                        "appo1.app_id, 
                        emp1.name, 
                        emp1.position, 
                        emp1.emp_type, 
                        emp1.current_status,
                        app1.firstname,   
                        app1.lastname, 
                        app1.middlename, 
                        app1.suffix, 
                        appo1.sss_no,
                        appo1.philhealth,
                        appo1.pagibig_tracking,
                        appo1.pagibig,
                        appo1.tin_no",
                        array(
                        'field1'=>'emp1.current_status',
                        'field2'=>'emp1.emp_id'
                        ),
                            array(
                        'Active',
                        $id
                            ),
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start  // start of the page
            );
        } else {
            $search = $this->input->post('search')['value'];
            $datas  =  $this->dbmodel->dt_get_where_like(
                "applicant_otherdetails as appo1",
                "appo1.app_id, 
                        emp1.name, 
                        emp1.position, 
                        emp1.emp_type, 
                        emp1.current_status,
                        app1.firstname,   
                        app1.lastname, 
                        app1.middlename, 
                        app1.suffix, 
                        appo1.sss_no,
                        appo1.philhealth,
                        appo1.pagibig_tracking,
                        appo1.pagibig,
                        appo1.tin_no",
                array('field1'=>'app1.lastname'), // like field
                array($search), // like field value
                array('field1'=>'emp1.current_status','field2' => 'emp1.emp_id'), // like field
                array('Active',$id), // like field value
                array('field2'=>'app1.firstname'),   // or like field
                array($search), // or like field value
                $dir,   // order by field
                $order, // order by ASC or DESC
                $limit, // per page
                $start,  // start of the page
                array("table1"=>"employee3 as emp1","table2"=>"applicant as app1"),
                array("appo1.app_id = emp1.emp_id","app1.app_id = emp1.emp_id")
            );

            $totalFiltered = count($datas);
        }

        $data = array();
        if(!empty($datas)) {
            $c=1;
            foreach ($datas as $list) {

                if (trim($list['suffix']) != '') {
                    $name = $list['lastname'] . ', ' .$list['firstname'] . ' ' . $list['suffix'] . ', ' . $list['middlename'];
                } else {
                    $name = $list['lastname'] . ', ' . $list['firstname'] . ' ' . $list['middlename'];
                }

                $nestedData['No']             = $c++;
                $nestedData['Name']           =  '<a href="'.base_url().'supervisor/profile/'.$list['app_id'].'" target="_blank">'. ucwords(strtolower($name)) .'</a>';
                $nestedData['Position']       = $list['position'];
                $nestedData['Status']         = $list['current_status'];
                $nestedData['SSSno']          = $list['sss_no'];
                $nestedData['Philhealth']      = $list['philhealth'];
                $nestedData['Pagibig RTN']    = $list['pagibig_tracking'];
                $nestedData['Pagibig NO']     = $list['pagibig'];
                $nestedData['Tin NO']         = $list['tin_no'];
                $nestedData['']               = '<button
                                                class="btn btn-sm btn-success" 
                                                type="button"
                                                modal-size=""
                                                modal-route="employee/updatesearchemployee"
                                                modal-form="employee/modal_searchemployee/'.$list['app_id'].'"
                                                modal-skeleton="0"
                                                modal-id=""
                                                modal-atype="POST"
                                                modal-title="Update Benefits" 
                                                onclick="modal(event)"><i class="fa fa-pencil" aria-hidden="true"></i></button>';

                
                $data[] = $nestedData;

            }
        } else {
            $nestedData['No']             = "No Record Found";
            $nestedData['Name']       = "No Record Found";
            $nestedData['Position']       = "No Record Found";
            $nestedData['Status']       = "No Record Found";
            $nestedData['SSSno']          = "No Record Found";
            $nestedData['Philhealth']      = "No Record Found";
            $nestedData['Pagibig RTN']    ="No Record Found";
            $nestedData['Pagibig NO']     ="No Record Found";
            $nestedData['Tin NO']         = "No Record Found";
            $nestedData['Action']         = "No Record Found";
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }

    public function modal_searchemployee($appid)
    {
        $datas = $this->dbmodel->get_data_by_join(
            "applicant_otherdetails as appo1",
            array(
                "table1" => "employee3 as emp1",
                "table2" => "applicant as app1"
            ),
            array(
                "appo1.app_id = emp1.emp_id",
                "app1.app_id = emp1.emp_id"
            ),
            "appo1.app_id, 
            emp1.name, 
            emp1.position, 
            emp1.emp_type, 
            emp1.current_status,
            app1.firstname,   
            app1.lastname, 
            app1.middlename, 
            app1.suffix, 
            appo1.sss_no,
            appo1.philhealth,
            appo1.pagibig_tracking,
            appo1.pagibig,
            appo1.tin_no",
            array(
                'field1' => 'emp1.current_status',
                'field2' => 'emp1.emp_id'
            ),
            array(
                'Active',
                $appid
            )
        );
                    
        foreach ($datas as $list){
            
            if (trim($list['suffix']) != '') {
                
                $data['name'] = $list['lastname'] . ', ' .$list['firstname'] . ' ' . $list['suffix'] . ', ' . $list['middlename'];
            } else {
                
                $data['name'] = $list['lastname'] . ', ' . $list['firstname'] . ' ' . $list['middlename'];
            }
            $data['sssno'] = $list['sss_no'];
            $data['philhealth'] = $list['philhealth'];
            $data['pagibigrtn'] = $list['pagibig_tracking'];
            $data['pagibigno'] = $list['pagibig'];
            $data['tinno'] = $list['tin_no'];

            
        }
        $data['appid'] = $appid;
        
        $this->load->view('employee/benefits/modaleditsearchemployee',$data);
    }

    public function update_searchemployee()
    {
        $data = array(
            'sss_no' => $this->input->post('ssno'),
            'philhealth' => $this->input->post('philhealth'),
            'pagibig_tracking' => $this->input->post('pagibigrtn'),
            'pagibig' => $this->input->post('pagibigno'),
            'tin_no' => $this->input->post('tinno'),
            
        );        
        
        $update=$this->dbmodel->update(
            "applicant_otherdetails",
            $data,
            "app_id = '".$this->input->post('app_id')."'"      
        );        
        
        if($update)
        {
            echo json_encode([
                'status' => 200,
                'response' => "success",
                'modal_close'   => "true",
                'response_message'  => "The Employee benefits entry was successfully updated.",
            ]);
        }
        else
        {
            echo json_encode([
                'status' => 401,
                'response'	=> "error",
                'response_message'	=> "Unable to update the Final completion benefits entry."
            ]);
        }        
    }
             
    public function searchemp()
    {
        $key = $this->input->post('str');
        $searchType = $this->input->post('searchType');
        $val = "";

        $where = "1"; // Default condition to fetch all records

        if ($searchType === 'name') {
            $where = "name LIKE '%$key%'";
        } elseif ($searchType === 'sssno') {
            $where = "applicant_otherdetails.sss_no = '$key'";
        } elseif ($searchType === 'philhealth') {
            $where = "applicant_otherdetails.philhealth = '$key'";
        } elseif ($searchType === 'pagibigrtn') {
            $where = "applicant_otherdetails.pagibig_tracking = '$key'";
        } elseif ($searchType === 'pagibig') {
            $where = "applicant_otherdetails.pagibig = '$key'";
        } elseif ($searchType === 'tinno') {
            $where = "applicant_otherdetails.tin_no = '$key'";
        }
        

        $this->db->select("employee3.emp_id, employee3.name, employee3.position, employee3.emp_type, employee3.current_status, 
                        applicant.firstname, applicant.lastname, applicant_otherdetails.sss_no, 
                        applicant_otherdetails.philhealth, applicant_otherdetails.pagibig_tracking, 
                        applicant_otherdetails.pagibig, applicant_otherdetails.tin_no")
                ->from('pis.employee3')
                ->join('applicant', 'applicant.app_id = employee3.emp_id', 'inner')
                ->join('applicant_otherdetails', 'applicant_otherdetails.app_id = employee3.emp_id', 'inner')
                ->where($where)
                ->order_by('employee3.name', 'ASC')
                ->limit(10);

        $query = $this->db->get();

        $findresult = $query->result_array();

        foreach ($findresult as $fr) {
            $empId = $fr['emp_id'];
            $name = ucwords(strtolower($fr['name']));
            $ssno = $fr['sss_no'];
            $philhealth = $fr['philhealth'];
            $pagibigrtn = $fr['pagibig_tracking'];
            $pagibig = $fr['pagibig'];
            $tinno = $fr['tin_no'];

            if ($val != $empId) {
                echo '<a class="nameFind" href="#" onclick=\'getProfile("'.$empId.'*'.$name.'","'.$searchType.'")\'>' . $name . '</a><br>';
            }else{
                echo "<a class=\"afont\" href=\"#\">No Result Found</a><br>";
            }
        }
    }
                    
    function applicantinfo($appid){

        $result = $this->dbmodel
            ->get_row(
                'applicant',
                '*,DATE_FORMAT(birthdate,"%m/%d/%Y") AS bday,',
                array('field1' => 'app_id'),
                array($appid)
            );
        $bday=$result->birthdate;
        return $bday;
    }        
     
    function benefits($appid)
    {
        $result = $this->dbmodel
            ->get_row(
                'applicant_otherdetails',
                '*',
                array('field1' => 'app_id'),
                array($appid)
            );
        return $result;
    }    
}                        