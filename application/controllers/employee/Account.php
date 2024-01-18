<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Manila');
class Account extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->checkEmployeeLogin();
        $this->load->model('DB_model', 'dbmodel');
    }

    //check session for the employee login
    private function checkEmployeeLogin(){
        if(!$this->session->userdata('hrmsEmployeeSession')){
            redirect($this->session->userdata('sessionType') . '/dashboard');
        }
    }

    public function accountsettings()
    {
        $data['title']  = "Account Settings";
        $data['menu']= "account";
        $data['submenu']= "accountsettings";
        $this->load->view('template/header', $data);
        $this->load->view('employee/employee/accountsettings', $data);
        $this->load->view('template/footer', $data);
    }
    
    public function changeusername()
    {
        $this->load->view('employee/employee/changeusername');
    }
    public function changepassword()
    {
        $this->load->view('employee/employee/changepassword');
    }
    
    public function changephone()
    {
        
        $data['contact'] = $this->dbmodel
            ->get_row(
                'applicant',
                'contactno',
                array('field1' => 'app_id'),
                array($this->session->userdata('emp_id'))
            );

        $this->load->view('employee/employee/changephonenumber',$data);
    }

    public function update_username()
    {
        $this->form_validation->set_rules(
            'currentUsername',
            'Current Username',
            'required|trim',
            array('required' => 'You must provide a %s.')
        );

        $this->form_validation->set_rules(
            'newUsername',
            'New Username',
            'required|trim',
            array('required' => 'You must provide a %s.')
        );

        $this->form_validation->set_rules(
            'confirmUsername',
            'Re-type New Username',
            'required|trim',
            array('required' => 'You must provide a %s.')
        );

        if ($this->form_validation->run() == false) {
            $errors = validation_errors('<li>', '</li>');
            echo json_encode([
                'status' => 401,
                'response' => 'error',
                'response_message' =>$errors
            ]);
        } else {
            $currentUsername = $this->input->post('currentUsername');
            $newUsername = $this->input->post('newUsername');
            $confirmUsername = $this->input->post('confirmUsername');

            if($newUsername === $confirmUsername) {


                // 'username = "'.strtolower($currentUsername).'" AND emp_id = "'.$this->session->userdata('emp_id').'" AND usertype = "'.$this->session->userdata('sessionType').'"'
                $check  = $this->dbmodel->count_data(
                    'users',
                    'username = "'.strtolower($currentUsername).'" AND emp_id = "'.$this->session->userdata('emp_id').'" AND usertype = "'.$this->session->userdata('sessionType').'"'
                );

                $check1  = $this->dbmodel->count_data(
                    'users',
                    'username = "'.strtolower($newUsername).'"'
                );

                if($check === 0) {
                    echo json_encode([
                        'status' => 401,
                        'response' => 'error',
                        'response_message' => "The Current Username is Incorrect"
                    ]);
                    die();
                }

                if($check1 > 0) {
                    echo json_encode([
                        'status' => 401,
                        'response' => 'error',
                        'response_message' => "New username is already taken."
                    ]);
                    die();
                }

                $fields = array(
                    'username' => $newUsername,
                );

                $update = $this->dbmodel->update('users', $fields, "emp_id ='".$this->session->userdata('emp_id')."' AND usertype = '".$this->session->userdata('sessionType')."'");

                if($update) {
                    echo json_encode([
                        'status' => 200,
                        'response' => "success",
                        'redirect' => 'employee/logout',
                        'response_message'  => "The Username  was successfully updated. <br> The system will logout and kindly log in again. Thank You!"
                    ]);
                } else {
                    echo json_encode([
                        'status' => 401,
                        'response'   => "errror",
                        'response_message'   => "Unable to update the Username."
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 401,
                    'response' => 'error',
                    'response_message' => "The new username and the retyped username didn’t match. Try again."
                ]);
                die();
            }
        }
    }

    public function update_password()
    {
        $this->form_validation->set_rules(
            'currentPassword',
            'Current Password',
            'required|trim',
            array('required' => 'You must provide a %s.')
        );

        $this->form_validation->set_rules(
            'newPassword',
            'New Password',
            'required|trim',
            array('required' => 'You must provide a %s.')
        );

        $this->form_validation->set_rules(
            'confirmPassword',
            'Re-type New Password',
            'required|trim',
            array('required' => 'You must provide a %s.')
        );

        if ($this->form_validation->run() == false) {
            $errors = validation_errors('<li>', '</li>');
            echo json_encode([
                'status' => 401,
                'response' => 'error',
                'response_message' =>$errors
            ]);
        } else {
            $currentPassword = md5($this->input->post('currentPassword'));
            $newPassword = md5($this->input->post('newPassword'));
            $confirmPassword = md5($this->input->post('confirmPassword'));

            if($newPassword === $confirmPassword) {


                // 'username = "'.strtolower($currentUsername).'" AND emp_id = "'.$this->session->userdata('emp_id').'" AND usertype = "'.$this->session->userdata('sessionType').'"'
                $check  = $this->dbmodel->count_data(
                    'users',
                    'password = "'.$currentPassword.'" AND emp_id = "'.$this->session->userdata('emp_id').'" AND usertype = "'.$this->session->userdata('sessionType').'"'
                );

                if($check === 0) {
                    echo json_encode([
                        'status' => 401,
                        'response' => 'error',
                        'response_message' => "The Current Password is Incorrect"
                    ]);
                    die();
                }

                $fields = array(
                    'password' => $newPassword,
                );

                $update = $this->dbmodel->update('users', $fields, "emp_id ='".$this->session->userdata('emp_id')."' AND usertype = '".$this->session->userdata('sessionType')."'");

                if($update) {
                    echo json_encode([
                        'status' => 200,
                        'response' => "success",
                        'redirect' => 'employee/logout',
                        'response_message'  => "The Password  was successfully updated. <br> The system will logout and kindly log in again. Thank You!"
                    ]);
                } else {
                    echo json_encode([
                        'status' => 401,
                        'response'   => "errror",
                        'response_message'   => "Unable to update the Passoword."
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 401,
                    'response' => 'error',
                    'response_message' => "The new password and the retyped password didn’t match. Try again."
                ]);
                die();
            }
        }
    }

    public function update_phone_number()
    {
        $this->form_validation->set_rules(
            'phoneNumber',
            'Current Phone Number',
            'required|trim',
            array(
                'required' => 'You must provide a %s.',
                'numeric' => 'The %s field can only contain numeric characters.',
            
            )
        );


        $this->form_validation->set_rules(
            'newphoneNumber',
            'New Phone Number',
            'required|trim|regex_match[/^(09|\+639)\d{9}$/]',
            array(
                'required' => 'You must provide a %s.',
                'regex_match' => 'The %s field must be a valid mobile number in the Philippines.'
            )
        );

        $this->form_validation->set_rules(
            'confirmphoneNumber',
            'Re-type New Phone Number',
            'required|trim|regex_match[/^(09|\+639)\d{9}$/]',
            array(
                'required' => 'You must provide a %s.',
                'regex_match' => 'The %s field must be a valid mobile number in the Philippines.'
            )
        );

        if ($this->form_validation->run() == false) {
            $errors = validation_errors('<li>', '</li>');
            echo json_encode([
                'status' => 401,
                'response' => 'error',
                'response_message' =>$errors
            ]);
        } else {
            $phoneNumber = $this->input->post('phoneNumber');
            $newphoneNumber = $this->input->post('newphoneNumber');
            $confirmphoneNumber = $this->input->post('confirmphoneNumber');

            if ($newphoneNumber === $confirmphoneNumber) {
                if ($phoneNumber === $newphoneNumber) {
                    echo json_encode([
                        'status' => 401,
                        'response' => 'error',
                        'response_message' => "The new phone number cannot be the same as the current phone number."
                    ]);
                } else {
                    $check  = $this->dbmodel->count_data(
                        'applicant',
                        'contactno = "'.$phoneNumber.'" AND app_id = "'.$this->session->userdata('emp_id').'"'
                    );

                    if ($check === 0) {
                        echo json_encode([
                            'status' => 401,
                            'response' => 'error',
                            'response_message' => "The current phone number is incorrect."
                        ]);
                        die();
                    }

                    $fields = array(
                        'contactno' => $newphoneNumber,
                    );

                    $update = $this->dbmodel->update('applicant', $fields, "app_id ='".$this->session->userdata('emp_id')."'");

                    // Perform your logic to update the phone number here

                    if ($update) {
                        echo json_encode([
                            'status' => 200,
                            'response' => "success",
                            'redirect' => 'employee/accountsettings',
                            'response_message'  => "The phone number was successfully updated."
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 401,
                            'response'   => "error",
                            'response_message'   => "Unable to update the phone number."
                        ]);
                    }
                }
            } else {
                echo json_encode([
                    'status' => 401,
                    'response' => 'error',
                    'response_message' => "The new phone numbers do not match."
                ]);
            }
        }
    }
}