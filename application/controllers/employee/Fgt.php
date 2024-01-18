<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Manila');
class Fgt extends CI_Controller {

        function __construct(){
            parent::__construct();
            // $this->checkEmployeeLogin();
            $this->load->model('DB_model','dbmodel');
            $this->load->model('Fgt_model', 'fgtmodel');

        
        }

            
        function forfgt()
         {      
              
            $data['title']  = "FGT Food Budget Confirmation";
            $data['menu']   = "Family";
            $data['submenu']   = "fgt";
            $this->load->view('template/header',$data);
            $this->load->view('employee/fgt/fgt_new',$data);
            $this->load->view('template/footer',$data);
         }

        
       public function fgt_insert() {

        $id = $this->fgtmodel->check_if_inserted_already();
         $confirmed=$this->input->post('emp_response');
        
        if ($id) {
            $result_emp = $this->fgtmodel->update_fgt(
                $this->input->post('emp_response'),
                $this->input->post('tshirtsize'),
                $id
            );
           
        } else {
            $result_emp = $this->fgtmodel->insert_fgt(
                $this->session->userdata('emp_id'),
                 $confirmed,
                $this->input->post('tshirtsize')
            );
            $id = $this->db->insert_id();
            
        }
        
        // SAVE LOGS
        $emplogs = "{EMP:" . $this->session->userdata('emp_id') . "|ANS:" . $this->input->post('emp_response') . "|TSHIRT:" . $this->input->post('tshirtsize') . "}";
        
        // fgt_confirm_spouse
        $employee = $this->fgtmodel->get_details($this->session->userdata('emp_id'));

        // var_dump($employee);
        
        if ($employee['civilstatus'] != "Annulled" && $employee['civilstatus'] != "Widowed" && $employee['civilstatus'] != "Single") {
            $spousetype = $this->fgtmodel->get_spouse_type($this->session->userdata('emp_id'));

            if (!$spousetype) {
                $sid = $this->fgtmodel->check_if_spouse_inserted_already($id);
                $spouse = explode("*", $this->fgtmodel->get_spouse_for_insert($this->session->userdata('emp_id')));
                if (trim($spouse[0]) == "") {
                    $tshirt = "";
                } else {
                    $tshirt = $this->input->post('spouse_tshirt');
                }
                if ($sid) {
                    $result_spouse = $this->fgtmodel->update_spouse(
                        $this->input->post('spouse_response'),
                        $tshirt,
                        $id
                    );
                    // TO SAVE LOGS
                    $splogs = "{SP:" . $id . "|SP-ANS:" . $this->input->post('spouse_response') . "|SP-TSHIRT:" . $tshirt . "}";
                } else {
                        $spouseResponse = $this->input->post('spouse_response');
                        $confirmedValuespouse = isset($spouseResponse) ? $spouseResponse : '';

                    $result_spouse = $this->fgtmodel->insert_spouse(
                        $id,
                        trim($spouse[1]),
                        trim($spouse[0]),
                        $tshirt,
                        $confirmedValuespouse,
                        $this->session->userdata('emp_id')
                    );
                    // TO SAVE LOGS
                    $splogs = "{SP:" . trim($spouse[0]) . "*" . trim($spouse[1]) . "|SP-ANS:" . $this->input->post('spouse_response') . "|SP-TSHIRT:" . $tshirt . "}";
                }
            }
        }
        
        // fgt_children
        $x = 0;
        $children = $this->input->post('children_id');
        $childname = $this->input->post('childname');
        $cconfirmno = $this->input->post('cconfirmno');
        
        for ($i = 1; $i <= count($children); $i++) {
            $y = $i - 1;
            $c = $cconfirmno[$x];
            
            if ($this->input->post('code') == "insert") {
                $child_ans = $this->input->post('confirm_children_' . $i);
                $confirmedValue = isset($child_ans[0]) ? $child_ans[0] : '';
                // Set a default value for 'confirmed' column
                // $confirmedValue = $this->input->post('confirm_children_' . $i); // Modify this with your desired value
                $result_child = $this->fgtmodel->insert_children(
                    $id,
                    $childname[$y],
                    $confirmedValue, // Pass the default value here
                    $this->session->userdata('emp_id')
                );
                // SAVE LOGS
                $chlogs = "{ CH:" . $childname[$x] . "|CH-ANS:" . $child_ans[0] . "}";
            } else {
                $child_ans = $this->input->post('confirm_children_' . $cconfirmno[$x]);
                $result_child = $this->fgtmodel->update_children(
                    $child_ans[0],
                    $cconfirmno[$x],
                    $id,
                    $this->session->userdata('emp_id')
                );
                // SAVE LOGS
                $chlogs = "{ CH:" . $cconfirmno[$x] . "|CH-ANS:" . $child_ans[0] . "}";
            }
            $x++;
        }
        
        $log = date('Y-m-d h:i:s') . "|SAVE FGT|" . $this->session->userdata('emp_id') . "|" . $this->session->userdata('employee_name') . " " . $emplogs . " " . $splogs . "" . $chlogs . "\n";
        $logDir = "logs/fgt/";
        $filename = "fgt-";
        $this->fgtmodel->writeLogs($log, $logDir, $filename);
        
        if ($result_emp || $result_spouse || $result_child) {
            echo "Success+FGT Food Budget Successfully Save!+success";
        } else {
            echo "Error+FGT Food Budget Failed to save!+error";
        }
    }

        
    

           


}
