<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Manila');

/** NOTE:
 * NAME EACH FUNCTION AS TO ITS FUNCTIONALITY
 * IF IT SHOW A FORM, NAME IT LIKE => form_functionName()
 * IF IT LOADS A PAGE OR LIST OF TABLE, NAME IT LIKE => list_functionName()
 * IF IT LOADS WITH FILTERS, NAME IT LIKE => list_filter_functionName()
 * IF IT IS A MODAL, NAME IT LIKE => modal_functionName()
 * IF IT IS A REPORT, NAME IT LIKE => xls_functionName(), pdf_functionName()
 * IF IT SAVE, NAME IT LIKE => insert_functionName()
 * IF IT UPDATES, NAME IT LIKE => update_functionName()
 * IF IT DELETES, NAME IT LIKE => delete_functionName()
 * IF IT RETURNS SOMETHING OR LIKE REUSABLE FUNCTION WITHIN THE CONTROLLER, get_functionName()
 * 
 * DONT FORGET TO ADD COMMENTS!!!
**/

class Reports extends CI_Controller {

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

    public function birthdayCelebrants()

    {
        $data['title']  = $this->title;
        $data['url']    = $this->url;
        // $data['menu']   = $menu;
        $data['submenu']= "";
        $data['tabMenu']= "";    

        

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

    
        $this->load->view('template/header_placement', $data);
        $this->load->view('placement/pdf/pdf_birthdayCelebrants',$data); 
        $this->load->view('template/footer', $data);

    }
  
}