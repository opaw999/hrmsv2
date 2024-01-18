<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Manila');
class Interview extends CI_Controller
{
    function __construct()
    {
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

    public function index()
    {
        $data['title']      = $this->title;
        $data['url']        = $this->url;  
        $data['menu']       = "interview"; 
        $data['submenu']    = ""; 
        
        $result = $this->dbmodel
            ->get_all_data("application_interview_details","*",null,null,null,0,null,
            "interviewer_id = '".$this->session->userdata('emp_id')."' AND chosen = '1' AND interview_status = '' 
        "); 

        if($result){
            foreach($result as $row):                
                $interviews[] = array(
                    "interview_code"        => $row['interview_code'],
                    "interviewee_id"        => $row['interviewee_id'],
                    "interviewer_id"        => $row['interviewer_id'],
                    "interview_status"      => $row['interview_status'],
                    "name"                  => $this->get_applicant($row['interviewee_id'])->name,
                    "position"              => $this->get_app($row['interviewee_id'])->position_applied,
                    "date_applied"          => $this->get_app($row['interviewee_id'])->date_applied 
                );            
            endforeach;
    
            $data['interviews'] = $interviews;  
        }             
        
        $this->load->view('template/header', $data);
        $this->load->view('employee/supervisor/interview', $data);
        $this->load->view('template/footer', $data);
    } 

    public function interview_grading($appid)
    {                
        // APPLICANT INFO        
        $data['appid']      = $appid;   
        $data['photo']      = $this->get_applicant($appid)->photo;   
        $data['name']       = $this->get_applicant($appid)->name;   
        $data['position']   = $this->get_app($appid)->position_applied;
        $data['dateapplied']= $this->get_app($appid)->date_applied;
        $data['interviewcode'] = $this->get_code($appid)->interview_code;
        $data['rater']      = $this->get_applicant($this->session->userdata('emp_id'))->name;

        $data['title']      = $this->title;
        $data['url']        = $this->url;
        $data['menu']       = "interview";
        $data['submenu']    = "interview form"; 

        $data['questions']       = $this->dbmodel
            ->get_all_data("application_interview_questions","*",null,null,null,0,null,null
        ); 
        
        $this->load->view('template/header', $data);
        $this->load->view('employee/supervisor/interview_form', $data);
        $this->load->view('template/footer', $data);
    }

    public function interview_saving()
    {                
        $numrate = $this->input->post('numrates');
        if ($numrate == 100) { $descrate = "E"; } 
        else if ($numrate >= 90 && $numrate <= 99.9) { $descrate = "VS"; } 
        else if ($numrate >= 85 && $numrate <= 89.9) { $descrate = "S";  } 
        else if ($numrate >= 70 && $numrate <= 84.9) { $descrate = "US"; } 
        else if ($numrate >= 0 && $numrate <= 69.9)  { $descrate = "VU"; }

        ($numrate < 85)? $stat = 'failed': $stat = 'passed';

        $data = array(
            'interview_code'    =>  $this->security->xss_clean($this->input->post('interviewcode')),              
            'num_rate'          =>  $this->security->xss_clean($this->input->post('numrates')),  
            'desc_rate'         =>  $this->security->xss_clean($this->input->post('desrate'))
        );
        $results = $this->dbmodel->add("application_interview_totalrates", $data);
       
        if($results)
        {
            $rates   = $this->input->post('rates');
            $remarks = $this->input->post('remarks');

            //INSERT INTERVIEW ANSWER           
            for($i=0;$i<10;$i++)
            {       
                $data = array( 
                    'interview_code'    =>  $this->input->post('interviewcode'),  
                    'no'                =>  $i+1,  
                    'rate'              =>  $rates[$i],  
                    'remarks'           =>  $remarks[$i]
                );
                $result = $this->dbmodel->add("application_interview_rates", $data);                    
            }    
            
            //UPDATE REMARKS
            $data = array( 
                'interviewer_id'        =>  $this->input->post('interviewerid'),  
                'interview_status'      =>  $stat,  
                'interviewer_remarks'   =>  $this->input->post('comment'),  
                'date_interviewed'      =>  date('Y-m-d')
            );
            $update = $this->dbmodel
                ->update("application_interview_details",
                $data,
                "interview_code = '".$this->input->post('interviewcode')."' "
            );

            //UPDATE APPLICATION HISTORY
            if( $update)
            {
                $data = array( 
                    'app_id'            => $this->input->post('appid'),  
                    'date_time'         => date('Y-m-d'),  
                    'description'       => 'done interview with '. $this->get_applicant($this->input->post('interviewerid'))->name,  
                    'position'          => $this->input->post('position'),
                    'phase'             => 'Interview',
                    'status'            => $stat,
                    'soc'               => '',
                    'eoc'               => ''
                );
                $result = $this->dbmodel->add("application_history", $data);  
            }      

            echo json_encode([
                'status' => 200,
                'redirect' => 'supervisor/dashboard',
                'response' => "success",
                'response_message' => 'Interview: Applicant evaluation form completed successfully!'
            ]);
        }else{
            echo json_encode([
                'status' => 401,
                'response'	=> "error",
                'response_message' => "Saving Error"
            ]);
        }
    }
   
    public function get_applicant($appid)
    {
        $result = $this->dbmodel
            ->get_row(
                'applicant',
                'CONCAT(`lastname` , ", " , `firstname`, " " , `middlename`) AS name, photo',
                array( 'field1' => 'app_id'),
                array( $appid )
            );       
        //$name = $result->lastname.", ".$result->firstname." ".$result->middlename;
        return $result;
    }

    public function get_app($appid)
    {
        $result = $this->dbmodel
            ->get_row(
                'application_details',
                'position_applied, DATE_FORMAT(date_applied,"%m/%d/%Y") AS date_applied',
                array( 'field1' => 'app_id'),
                array( $appid )
            );              
        return $result;
    }

    public function get_code($appid)
    {
        $result = $this->dbmodel
            ->get_row(
                'application_interview_details',
                '*',
                array( 'field1' => 'interviewee_id', 'field2' => 'interviewer_id' , 'field3' => 'chosen' ),
                array( $appid, $this->session->userdata('emp_id'), 1)
            );              
        return $result;
    }
}