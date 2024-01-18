<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Manila');
class Pagenotfound extends CI_Controller {

    function __construct(){
        parent::__construct();       
    }

    public function index()
    {            
        $data['title'] = "Pagenotfound";    
       // $this->load->view('template/header', $data);
        $this->load->view('template/pagenotfound', $data); 
        $this->load->view('template/footer', $data);
    }
   
    public function logout() 
    {          
        $this->session->sess_destroy();
        redirect('login');
    }    
}
