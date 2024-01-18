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

class Search extends CI_Controller {

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

    public function load_search_employee(){          
        
        $data['title']  = "Search";
        
        $this->load->view('template/header_placement', $data);
		$this->load->view('placement/search/search_employee', $data);
        $this->load->view('template/footer', $data);
    }

    public function load_search_applicant(){          
        
        $data['title']  = "Search";

        $this->load->view('template/header_placement', $data);
		$this->load->view('placement/search/search_applicant', $data);
        $this->load->view('template/footer', $data);
    }

    public function view_result(){
        
        $val    = $this->input->get('searchval');
        $data['url']    = $this->url;
        $data['title']  = "Search";
        $data['result'] = $this->dbmodel
                ->getallfromtablejoinresult(
                    '*, DATE_FORMAT(tbl1.startdate,"%m/%d/%Y") AS startdate, DATE_FORMAT(tbl1.eocdate,"%m/%d/%Y") AS eocdate, DATE_FORMAT(tbl2.birthdate,"%m/%d/%Y") AS birthdate',
                    'employee3 as tbl1',
                    'applicant as tbl2',
                    'tbl1.emp_id = tbl2.app_id',
                    "name LIKE '%$val%'"
                );                

        $this->load->view('template/header_placement', $data);
        $this->load->view('placement/search/search_employee', $data);
        $this->load->view('template/footer', $data);
    }

    public function view_result_applicant(){
               
        $ln    = $this->input->get('lastname');
        $fn    = $this->input->get('firstname');

        $data['title']  = "Search";
        $data['url']    = $this->url;
        $data['ln'] = $ln;
        $data['fn'] = $fn;
        
        $data['result'] = $this->dbmodel
                ->get_field_result_order(
                    'app_id, lastname, firstname, middlename, suffix, home_address, civilstatus, photo, DATE_FORMAT(birthdate,"%M %d, %Y") AS birthdate', 
                    "applicant", 
                    "lastname = '$ln' and firstname like '%$fn%' ",
                    "lastname, firstname",
                    "ASC"
                );
                
        // $count = $this->dbmodel->get_count_data("applicant", '*', "lastname = '$ln' and firstname like '%$fn%' ") 
        // if($count == 0){
        //     $data['msg'] =  "<i style='color:red;font-size:24px'>No Result found!</i>";
        // }
               
        $this->load->view('template/header_placement', $data);
        $this->load->view('placement/search/search_applicant', $data);
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
  
}