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
 * 
 * DONT FORGET TO ADD COMMENTS!!!
**/

class Dashboard extends CI_Controller {

    function __construct(){
        parent::__construct();
        //$this->checkEmployeeLogin();
        $this->load->model('DB_model','dbmodel');
        $this->load->library('custom');
        
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

        $datas = $this->custom->some_method();
        $data['month'] = $datas;        
        $data['title']  = "Dashboard";

        $this->load->view('template/header_placement', $data);
		$this->load->view('placement/dashboard/dashboard', $data);
        $this->load->view('template/footer', $data);
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

    public function logout(){
        $emptype = $this->session->userdata('usertype');
        $this->session->sess_destroy();
		redirect('/'.$emptype);
    }
}
