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

class Transactions extends CI_Controller {

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

    public function formContract()

    {
        $data['title']   = $this->title;
        $data['url']     = $this->url;
        $data['tblApi']  = "";
        $data['submenu'] = "";
        $data['tabMenu'] = "";


        $this->load->view('template/header_placement', $data);
        $this->load->view('placement/transactions/trans_reprintContract', $data);
        $this->load->view('template/footer', $data);
    }

    public function contractTable()

    {
        $empId = $this->input->post('empId');
        $data['empid'] = $empId;

        $data['datas'] = $this->dbmodel->get_all_data(
            'employee3',
            'record_no,
             emp_id, 
             emp_type, 
             name, 
             permit, 
             contract, 
             company_code, 
             bunit_code, 
             dept_code,
             position, 
             startdate, 
             eocdate',
            'DESC',
            'record_no',
            null,
            null,
            null,
            'emp_id = "'.$empId.'"'
        );
        $this->load->view('placement/transactions/table_contractList',$data);
    }

    public function editContract($empid)

    {
        // echo $empid;
         $data['empId'] = $empid;

         $data['name'] = $this->dbmodel->get_field("name","employee3","emp_id = '$data[empId]'")['name'];
         $recordno = $this->dbmodel->get_field("record_no","employee3","emp_id = '$data[empId]'")['record_no'];
         $emptype = $this->dbmodel->get_field("emp_type","employee3","emp_id = '$data[empId]'")['emp_type'];
         $data['sssnum'] = $this->dbmodel->get_field("sss_no","applicant_otherdetails","app_id = '$data[empId]'")['sss_no'];
         $data['recordno'] = $this->dbmodel->get_field("record_no","employee3","emp_id = '$data[empId]'")['record_no'];
         $data['emptype'] = $this->dbmodel->get_field("emp_type","employee3","emp_id = '$data[empId]'")['emp_type'];
         $datas = $this->db->query(" SELECT * from employment_witness where rec_no = '$recordno' ");


         foreach ($datas->result_array() as $r) {
            $data['chno'] 	= $r['contract_header_no'];
            $data['dg'] 	= $r['date_generated'];
            $data['sss_ctc'] = $r['sss_ctc'];
            $data['sno_cno'] = $r['sssno_ctcno'];
            $data['w1'] 	= $r['witness1'];
            $data['w2'] 	= $r['witness2'];
            $data['issuedon'] = $r['issuedon'];
            $data['issuedat'] = $r['issuedat'];
        }
        $this->load->view('placement/transactions/trans_editcontractModal',$data);

    }

    public function generateContract($empid)

    {
        $recordno = $this->dbmodel->get_field("record_no","employee3","emp_id = '$empid'")['record_no'];
        $emptype = $this->dbmodel->get_field("emp_type","employee3","record_no = '$recordno'")['emp_type'];
        $chno = $this->input->post('ccode');

        if($chno == "24"){ 
           echo "wak2";
        } 
        else if($emptype == 'PTA' || $emptype == 'PTP')
        {
            echo "iro2";
        }else{
            echo "zebra2x";
        }
    }
  
}