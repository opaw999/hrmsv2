<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Manila');
class Personnelrequisition extends CI_Controller {

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

    public function index()
    {
        $data['title']      = $this->title;
        $data['url']        = $this->url;  
        $data['menu']       = "PR"; 
        $data['submenu']    = "Personnel Request";    
        
        $this->load->view('template/header', $data);
        $this->load->view('employee/supervisor/pr_request', $data);
        $this->load->view('template/footer', $data);
    }  
}