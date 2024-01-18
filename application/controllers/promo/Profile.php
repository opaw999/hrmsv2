<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{
    public $adminUser;
    public $systemUser;
    function __construct()
    {
        parent::__construct();
        $this->adminUser    = array('06359-2013', '01186-2023');
        $this->systemUser   = $this->session->userdata('emp_id');
        $empId              = $this->session->userdata('emp_id');
        if (!isset($empId)) {

            redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrmsv2/employee');
        }
    }

    public function profilePic()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $profilePic_path   = '';
        if (isset($_FILES['file']['name'])) {

            $image_name         = addslashes($_FILES['file']['name']);
            $array              = explode(".", $image_name);
            $filename           = $input['emp_id'] . "=" . date('Y-m-d') . "=" . 'Profile' . "=" . date('H-i-s-A') . "." . end($array);
            $profilePic_path    = "../hrms/images/users/" . $filename; //temp
            move_uploaded_file($_FILES['file']['tmp_name'], $profilePic_path);
        }
        $path   = '../images/users/' . $filename; //temp
        $where  = array('app_id' => $input['emp_id']);
        $data   = array('photo' => $path);
        $this->promo_model->update_twdA('applicant', $where, $data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success']);
        }
    }

    public function profileData()
    {
        $input  = $this->input->post(NULL, TRUE);
        $row    = $this->promo_model->promoDetails_wttR(array('e.emp_id' => $input['emp_id']), 'employee3', 'promo_record');

        if (count($row) > 1) {
            $epasField      = '';
            $current_store  = [];
            $i              = 0;
            $bUs            = $this->promo_model->locate_promo_bu();
            foreach ($bUs as $bu) {
                $hasBu = $this->promo_model->empStores('promo_record', $input['emp_id'], $row['record_no'], $bu['bunit_field']);
                if ($hasBu > 0) {
                    $i++;
                    $current_store[] = $bu['bunit_acronym'];
                    if ($i == 1) {
                        $epasField = $bu['bunit_epascode'] . ' = "1"';
                    } else {
                        $epasField .= ' OR ' . $bu['bunit_epascode'] . ' = "1"';
                    }
                }
            }
            $storeEpas              = ' (' . $epasField . ')';
            $data['store']          = $current_store;
            $data['emp_id']         = $input['emp_id'];
            $data['religion']       = $this->promo_model->selectAll('religion');
            $data['weight']         = $this->promo_model->selectAll('weight');
            $data['height']         = $this->promo_model->selectAll('height');
            $data['attainment']     = $this->promo_model->selectAll('attainment');
            $data['school']         = $this->promo_model->selectAll('school');
            $data['course']         = $this->promo_model->selectAll('course');
            $data['training']       = $this->promo_model->selectAll_tcA('application_seminarsandeligibility', array('app_id' => $input['emp_id']));
            $data['character']      = $this->promo_model->selectAll_tcA('application_character_ref', array('app_id' => $input['emp_id']));
            $data['appraisal']      = $this->promo_model->selectAll_tcA('appraisal_details', array('emp_id' => $input['emp_id']));
            $data['application']    = $this->promo_model->selectAll_tcR('application_details', array('app_id' => $input['emp_id']));
            $data['appHist']        = $this->promo_model->selectAll_tcA('application_history', array('app_id' => $input['emp_id'], 'phase' => 'Examination', 'status' => 'completed'));
            $data['positions']      = $this->promo_model->positions();
            $data['app']            = $this->promo_model->selectAll_tcR('applicant', array('app_id' => $input['emp_id']));
            $data['address']        = $this->promo_model->addressBoholCebu();
            $data['row']            = $this->promo_model->promoDetails_wttR(array('e.emp_id' => $input['emp_id']), 'employee3', 'promo_record');
            $data['current']        = $this->promo_model->promoDetails_wttA(array('e.emp_id' => $input['emp_id']), 'employee3', 'promo_record');
            $data['previous']       = $this->promo_model->promoDetails_wttA(array('e.emp_id' => $input['emp_id']), 'employmentrecord_', 'promo_history_record');
            $data['eocApp_c']       = ($epasField != '') ? $this->promo_model->eocApp_c($input, $storeEpas) : $this->promo_model->eocApp_c($input);
            $data['eocApp_p']       = $this->promo_model->promoDetails_wttA(array('e.emp_id' => $input['emp_id']), 'employmentrecord_', 'promo_history_record');
            $data['empHistory']     = $this->promo_model->selectAll_tcA('application_employment_history', array('app_id' => $input['emp_id']));
            $data['benefits']       = $this->promo_model->selectAll_tcR('applicant_otherdetails', array('app_id' => $input['emp_id']));
            $data['supervisor']     = $this->promo_model->selectAll_tcA('leveling_subordinates', array('subordinates_rater' => $input['emp_id']));
            $data['docFile']        = $this->promo_model->selectAll_tcA('201document', array('promo' => 'yes'));
            $data['remarks']        = $this->promo_model->selectAll_tcR('remarks', array('emp_id' => $input['emp_id']));
            $data['clearance']      = $this->promo_model->clearanceHistory($input['emp_id']);
            $data['transfer']       = $this->promo_model->selectAll_tcA('employee_transfer_details', array('emp_id' => $input['emp_id']));
            $data['blacklist']      = $this->promo_model->selectAll_tcA('blacklist', array('app_id' => $input['emp_id']));
            $data['userAccount']    = $this->promo_model->selectAll_tcA('users', array('emp_id' => $input['emp_id'], 'usertype' => 'employee'));
            $data['request']        = $input['id'];

            $this->load->view('promo/page/profile_page_response', $data);
        } else {
            echo 'notPromo';
        }
    }

    public function basicInfo()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $name   = ucwords(strtolower($input['ln'] . ', ' . $input['fn'] . ' ' . $input['mn'] . ' ' . $input['suffix']));
        $where1 = array('app_code'   => $input['appcode']);
        $where2 = array('app_id'    => $input['emp_id']);
        $where3 = array('emp_id'    => $input['emp_id']);
        $where4 = array('emp_id'    => $input['emp_id']);
        $data1  = array(
            'lastname'      => ucwords(strtolower($input['ln'])),
            'firstname'     => ucwords(strtolower($input['fn'])),
            'middlename'    => ucwords(strtolower($input['mn'])),
            'suffix'        => ucwords(strtolower($input['suffix'])),
        );
        $data2  = array(
            'lastname'      => ucwords(strtolower($input['ln'])),
            'firstname'     => ucwords(strtolower($input['fn'])),
            'middlename'    => ucwords(strtolower($input['mn'])),
            'suffix'        => ucwords(strtolower($input['suffix'])),
            'birthdate'     => $input['birthdate'],
            'religion'      => $input['religion'],
            'civilstatus'   => $input['civilstatus'],
            'gender'        => $input['gender'],
            'citizenship'   => $input['citizenship'],
            'bloodtype'     => $input['bloodtype'],
            'weight'        => $input['weight'],
            'height'        => $input['height'],
        );
        $data3 = array('name' => $name);
        $data4 = array('names' => $name);
        $this->promo_model->update_twdA('applicants', $where1, $data1);
        $this->promo_model->update_twdA('applicant', $where2, $data2);
        $this->promo_model->update_twdA('employee3', $where3, $data3);
        $this->promo_model->update_twdA('employmentrecord_', $where4, $data4);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success']);
        }
    }

    public function benefits()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $where  = array('app_id'   => $input['emp_id']);
        $data   = array(
            'philhealth'        => $input['philhealth'],
            'sss_no'            => $input['sss_no'],
            'card_no'           => $input['card_no'],
            'pagibig'           => $input['pagibig'],
            'pagibig_tracking'  => $input['pagibig_tracking'],
            'tin_no'            => $input['tin_no'],
            'cedula_no'         => $input['cedula_no'],
            'cedula_date'       => $input['cedula_date'],
            'cedula_place'      => $input['cedula_place'],
        );
        $this->promo_model->update_twdA('applicant_otherdetails', $where, $data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success']);
        }
    }

    public function contactInfo()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $where  = array('app_id'   => $input['emp_id']);
        $data   = array(
            'home_address'              => $input['home_address'],
            'city_address'              => $input['city_address'],
            'contactno'                 => $input['contactno'],
            'telno'                     => $input['telno'],
            'email'                     => $input['email'],
            'facebookAcct'              => $input['facebookAcct'],
            'twitterAcct'               => $input['twitterAcct'],
            'contact_person'            => ucwords(strtolower($input['contact_person'])),
            'contact_person_address'    => $input['contact_person_address'],
            'contact_person_number'     => $input['contact_person_number'],
        );
        $this->promo_model->update_twdA('applicant', $where, $data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success']);
        }
    }

    public function famEducBackground()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $where  = array('app_id' => $input['emp_id']);
        $data   = array(
            'mother'        => ucwords(strtolower($input['mother'])),
            'father'        => ucwords(strtolower($input['father'])),
            'guardian'      => ucwords(strtolower($input['guardian'])),
            'spouse'        => ucwords(strtolower($input['spouse'])),
            'attainment'    => $input['attainment'],
            'school'        => $input['school'],
            'course'        => $input['course'],
            'hobbies'       => $input['hobbies'],
            'specialSkills' => $input['specialSkills'],
        );
        $this->promo_model->update_twdA('applicant', $where, $data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success']);
        }
    }

    public function modal_form()
    {
        $input  = $this->input->post(NULL, TRUE);

        if ($input['action'] == 'edit') {
            if ($input['page'] == 'training') {
                $value   = $this->promo_model->selectAll_tcR('application_seminarsandeligibility', array('no' => $input['no']));

                echo    '<div class="row">
                            <input type="hidden" class="form-control" name="app_id" value="' . $value['app_id'] . '">
                            <input type="hidden" class="form-control" name="no" value="' . $value['no'] . '">
                            <input type="hidden" class="form-control" name="page" value="' . $input['page'] . '">
                            <div class="col-lg-12">
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <label>Certificate Name</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bx-brain font-22"></i>
                                            <input type="text" class="form-control" name="name" value="' . $value['name'] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Certificate Date</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bx-calendar font-22"></i>
                                            <input type="text" class="form-control datepicker" name="dates" value="' . $value['dates'] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Certificate Location</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bx-map-pin font-22"></i>
                                            <input type="text" class="form-control" name="location" value="' . $value['location'] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Certificate</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bx-upload font-22"></i>
                                            <input type="file" class="form-control" name="sem_certificate">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
            } else if ($input['page'] == 'characterRef') {
                $value   = $this->promo_model->selectAll_tcR('application_character_ref', array('no' => $input['no']));

                echo    '<div class="row">
                            <input type="hidden" class="form-control" name="app_id" value="' . $value['app_id'] . '">
                            <input type="hidden" class="form-control" name="no" value="' . $value['no'] . '">
                            <input type="hidden" class="form-control" name="page" value="' . $input['page'] . '">
                            <div class="col-lg-12">
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <label>Name</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bxs-user font-22"></i>
                                            <input type="text" class="form-control" name="name" value="' . $value['name'] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Position</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bxs-briefcase font-22"></i>
                                            <input type="text" class="form-control" name="position" value="' . $value['position'] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Company/Location</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bxs-map-pin font-22"></i>
                                            <input type="text" class="form-control" name="company" value="' . $value['company'] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Contact No.</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bxs-phone font-22"></i>
                                            <input type="text" class="form-control" name="contactno" value="' . $value['contactno'] . '">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
            } else if ($input['page'] == 'empHistory') {
                $value   = $this->promo_model->selectAll_tcR('application_employment_history', array('no' => $input['no']));
                echo    '<div class="row">
                            <input type="hidden" class="form-control" name="app_id" value="' . $value['app_id'] . '">
                            <input type="hidden" class="form-control" name="no" value="' . $value['no'] . '">
                            <input type="hidden" class="form-control" name="page" value="' . $input['page'] . '">
                            <div class="col-lg-12">
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <label>Company</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bxs-buildings font-22"></i>
                                            <input type="text" class="form-control" name="company" value="' . $value['company'] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Position</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bxs-briefcase font-22"></i>
                                            <input type="text" class="form-control" name="position" value="' . $value['position'] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Date Start</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bx-calendar-star font-22"></i>
                                            <input type="text" class="form-control datepicker" name="yr_start" value="' . $value['yr_start'] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Date End</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bx-calendar-exclamation font-22"></i>
                                            <input type="text" class="form-control datepicker" name="yr_ends" value="' . $value['yr_ends'] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Address/Location</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bxs-map-pin font-22"></i>
                                            <input type="text" class="form-control" name="address" value="' . $value['address'] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Certificate</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bx-upload font-22"></i>
                                            <input type="file" class="form-control" name="emp_certificate">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
            }
        } else if ($input['action'] == 'add') {
            if ($input['page'] == 'training') {
                echo    '<div class="row">
                        <input type="hidden" class="form-control" name="app_id" value="' . $input['no'] . '">
                        <input type="hidden" class="form-control" name="page" value="' . $input['page'] . '">
                        <div class="col-lg-12">
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <label>Certificate Name</label>
                                    <div class="input-group mb-3">
                                        <i class="input-group-text fadeIn animated bx bx-brain font-22"></i>
                                        <input type="text" class="form-control" name="name">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Certificate Date</label>
                                    <div class="input-group mb-3">
                                        <i class="input-group-text fadeIn animated bx bx-calendar font-22"></i>
                                        <input type="text" class="form-control datepicker" name="dates">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Certificate Location</label>
                                    <div class="input-group mb-3">
                                        <i class="input-group-text fadeIn animated bx bx-map-pin font-22"></i>
                                        <input type="text" class="form-control" name="location">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Certificate</label>
                                    <div class="input-group mb-3">
                                        <i class="input-group-text fadeIn animated bx bx-upload font-22"></i>
                                        <input type="file" class="form-control" name="sem_certificate">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
            } else if ($input['page'] == 'characterRef') {
                echo    '<div class="row">
                            <input type="hidden" class="form-control" name="app_id" value="' . $input['no'] . '">
                            <input type="hidden" class="form-control" name="page" value="' . $input['page'] . '">
                            <div class="col-lg-12">
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <label>Name</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bxs-user font-22"></i>
                                            <input type="text" class="form-control" name="name">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Position</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bxs-briefcase font-22"></i>
                                            <input type="text" class="form-control" name="position">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Company/Location</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bxs-map-pin font-22"></i>
                                            <input type="text" class="form-control" name="company">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Contact No.</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bxs-phone font-22"></i>
                                            <input type="text" class="form-control" name="contactno"">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
            } else if ($input['page'] == 'empHistory') {
                echo    '<div class="row">
                            <input type="hidden" class="form-control" name="app_id" value="' . $input['no'] . '">
                            <input type="hidden" class="form-control" name="page" value="' . $input['page'] . '">
                            <div class="col-lg-12">
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <label>Company</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bxs-buildings font-22"></i>
                                            <input type="text" class="form-control" name="company">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Position</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bxs-briefcase font-22"></i>
                                            <input type="text" class="form-control" name="position">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Date Start</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bx-calendar-star font-22"></i>
                                            <input type="text" class="form-control datepicker" name="yr_start">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Date End</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bx-calendar-exclamation font-22"></i>
                                            <input type="text" class="form-control datepicker" name="yr_ends">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Address/Location</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bxs-map-pin font-22"></i>
                                            <input type="text" class="form-control" name="address">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Certificate</label>
                                        <div class="input-group mb-3">
                                            <i class="input-group-text fadeIn animated bx bx-upload font-22"></i>
                                            <input type="file" class="form-control" name="emp_certificate">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
            } else if ($input['page'] == 'supervisor') {
                $company = $this->promo_model->selectAll('locate_company');
                echo    '<div class="row">
                            <input type="hidden" class="form-control" name="app_id" value="' . $input['no'] . '">
                            <input type="hidden" class="form-control" name="page" value="' . $input['page'] . '">
                            <div class="col-lg-4 border-end">
                                <div class="text-center">
                                    <label class="text-primary">Filter to tag a Supervisor</label>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>Company</label>
                                        <div class="input-group input-group-sm mb-1 font-22">
                                            <select class="form-select select2" name="company_code" data-placeholder="Select company" onchange="getSupervisor_bc(this.value)">
                                                <option value="">Select company</option>';
                foreach ($company as $value) {
                    echo                        '<option value="' . $value['company_code'] . '">' . $value['company'] . '</option>';
                }
                echo                        '</select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Business Unit</label>
                                        <div class="input-group input-group-sm mb-1">
                                            <select class="form-select select2" name="bunit_code" data-placeholder="Select Business Unit" onchange="getSupervisor_dc(this.value)">
                                                <option value="">Select Business Unit</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Department</label>
                                        <div class="input-group input-group-sm mb-1">
                                            <select class="form-select select2" name="dept_code" data-placeholder="Select Department" onchange="getSupervisor_sc(this.value)">
                                                <option value="">Select Department</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Section</label>
                                        <div class="input-group input-group-sm mb-1">
                                            <select class="form-select select2" name="section_code" data-placeholder="Select Section" onchange="getSupervisor_ssc(this.value)">
                                                <option value="">Select Section</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Sub-section</label>
                                        <div class="input-group input-group-sm mb-1">
                                            <select class="form-select select2" name="sub_section_code" data-placeholder="Select Sub-section" onchange="getSupervisor_uc(this.value)">
                                                <option value="">Select Sub-section</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Unit</label>
                                        <div class="input-group input-group-sm mb-1">
                                            <select class="form-select select2" name="unit_code" data-placeholder="Select Unit" onchange="supervisorList(this.value,' . $input['no'] . ')">
                                                <option value="">Select Unit</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 supervisorList"></div>
                        </div>';
            } else if ($input['page'] == 'userAccount') {
                $name = $this->promo_model->getName_employee3($input['no']);
                echo    '<div class="row">
                            <input type="hidden" class="form-control" name="emp_id" value="' . $input['no'] . '">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>Account</label>
                                        <div class="input-group mb-2">
                                            <i class="input-group-text fadeIn animated bx bx-id-card font-22"></i>
                                            <input type="text" class="form-control readonly" name="account" value="[' . $input['no'] . '] ' . strtoupper($name) . '" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>User Type</label>
                                        <div class="input-group mb-2">
                                            <i class="input-group-text fadeIn animated bx bx-user-pin font-22"></i>
                                            <input type="text" class="form-control readonly" name="usertype" value="employee" readonly style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Username</label>
                                        <div class="input-group mb-2">
                                            <i class="input-group-text fadeIn animated bx bx-user font-22"></i>
                                            <input type="text" class="form-control readonly" name="username" value="' . $input['no'] . '" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Password</label>
                                        <div class="input-group mb-1">
                                            <i class="input-group-text fadeIn animated bx bx-key font-22"></i>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 d-md-flex d-grid align-items-center gap-3 justify-content-left">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="viewPassword" id="flexCheckDefault" onclick="passwordFunc(this.name)">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Show Password
                                            </label>
                                        </div>
                                        <div class="form-check form-switch">
								            <input class="form-check-input" type="checkbox" role="switch" name="setPassword" id="flexSwitchCheckDefault1" onclick="passwordFunc(this.name)">
								            <label class="form-check-label" for="flexSwitchCheckDefault1">
                                                Set default Password : Hrms2014
                                            </label>
							            </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
            }
        } else if ($input['action'] == 'deactivate') {
            $this->db->trans_start();
            $this->promo_model->update_twdA('users', array('user_no' => $input['no']), array('user_status' => 'inactive', 'date_updated' => date('Y-m-d H:i:s')));
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                echo json_encode(['message' => 'failed']);
            } else {
                echo json_encode(['message' => 'success']);
            }
        } else if ($input['action'] == 'activate') {
            $this->db->trans_start();
            $this->promo_model->update_twdA('users', array('user_no' => $input['no']), array('user_status' => 'active', 'date_updated' => date('Y-m-d H:i:s')));
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                echo json_encode(['message' => 'failed']);
            } else {
                echo json_encode(['message' => 'success']);
            }
        } else if ($input['action'] == 'reset') {
            $this->db->trans_start();
            $this->promo_model->update_twdA('users', array('user_no' => $input['no']), array('password' => md5('Hrms2014'), 'date_updated' => date('Y-m-d H:i:s')));
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                echo json_encode(['message' => 'failed']);
            } else {
                echo json_encode(['message' => 'success']);
            }
        } else {
            $this->db->trans_start();
            if ($input['page'] == 'supervisor') {

                $this->promo_model->delete_tcr_row('leveling_subordinates', array('record_no' => $input['no']));
            } else if ($input['page'] == 'userAccount') {

                $this->promo_model->delete_tcr_row('users', array('user_no' => $input['no']));
            }
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                echo json_encode(['message' => 'failed']);
            } else {
                echo json_encode(['message' => 'success']);
            }
        }
    }

    public function save_modal_form()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        if ($input['page'] == 'training') {

            $certificate_path   = '';
            if (isset($_FILES['sem_certificate']['name'])) {

                $image_name         = addslashes($_FILES['sem_certificate']['name']);
                $array              = explode(".", $image_name);
                $filename           = $input['app_id'] . "=" . date('Y-m-d') . "=" . 'Seminar-Certificate' . "=" . date('H-i-s-A') . "." . end($array);
                $certificate_path   = "../hrms/document/seminar_certificate/" . $filename; //temp
                move_uploaded_file($_FILES['sem_certificate']['tmp_name'], $certificate_path);
            }
            $path   = "../document/seminar_certificate/" . $filename; //temp
            $data1  = array(
                'name'              => $input['name'],
                'dates'             => $input['dates'],
                'location'          => $input['location'],
                'sem_certificate'   => $path,
            );
            $data2  = array(
                'app_id'            => $input['app_id'],
                'name'              => $input['name'],
                'dates'             => $input['dates'],
                'location'          => $input['location'],
                'sem_certificate'   => $path,
            );
            if (isset($input['no'])) {
                $where  = array('no' => $input['no']);
                $this->promo_model->update_twdA('application_seminarsandeligibility', $where, $data1);
            } else {
                $this->promo_model->insert_tdA('application_seminarsandeligibility', $data2);
            }
        } else if ($input['page'] == 'characterRef') {

            $data1  = array(
                'name'      => $input['name'],
                'position'  => $input['position'],
                'company'   => $input['company'],
                'contactno' => $input['contactno'],
            );
            $data2  = array(
                'app_id'    => $input['app_id'],
                'name'      => $input['name'],
                'position'  => $input['position'],
                'company'   => $input['company'],
                'contactno' => $input['contactno'],
            );
            if (isset($input['no'])) {
                $where  = array('no' => $input['no']);
                $this->promo_model->update_twdA('application_character_ref', $where, $data1);
            } else {
                $this->promo_model->insert_tdA('application_character_ref', $data2);
            }
        } else if ($input['page'] == 'empHistory') {

            $certificate_path   = '';
            if (isset($_FILES['emp_certificate']['name'])) {

                $image_name         = addslashes($_FILES['emp_certificate']['name']);
                $array              = explode(".", $image_name);
                $filename           = $input['app_id'] . "=" . date('Y-m-d') . "=" . 'Employment_Certificate' . "=" . date('H-i-s-A') . "." . end($array);
                $certificate_path   = "../hrms/document/employment_certificate/" . $filename; //temp
                move_uploaded_file($_FILES['emp_certificate']['tmp_name'], $certificate_path);
            }
            $path   = "../document/employment_certificate/" . $filename; //temp
            $data1  = array(
                'company'           => $input['company'],
                'position'          => $input['position'],
                'yr_start'          => $input['yr_start'],
                'yr_ends'           => $input['yr_ends'],
                'address'           => $input['address'],
                'emp_certificate'   => $path,
            );
            $data2  = array(
                'app_id'            => $input['app_id'],
                'company'           => $input['company'],
                'position'          => $input['position'],
                'yr_start'          => $input['yr_start'],
                'yr_ends'           => $input['yr_ends'],
                'address'           => $input['address'],
                'emp_certificate'   => $path,
            );
            if (isset($input['no'])) {
                $where  = array('no' => $input['no']);
                $this->promo_model->update_twdA('application_employment_history', $where, $data1);
            } else {
                $this->promo_model->insert_tdA('application_employment_history', $data2);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success']);
        }
    }

    public function viewAppraisal()
    {
        $input = $this->input->post(NULL, TRUE);
        $answer = $this->promo_model->selectAll_tcA('appraisal_answer', array('details_id' => $input['details_id']));
        $details = $this->promo_model->selectAll_tcR('appraisal_details', array('details_id' => $input['details_id']));
        $i = 0;
        switch ($details['descrate']) {
            case "E":
                $descrate = "Excellent";
                break;
            case "VS":
                $descrate = "Very Satisfactory";
                break;
            case "S":
                $descrate = "Satisfactory";
                break;
            case "US":
                $descrate = "Unsatisfactory";
                break;
            case "VU":
                $descrate = "Very Unsatisfactory";
                break;
        }
        echo    '<table class="table table-bordered">
                  
                    <tr>
                        <th colspan="3">Guide Questions</th>
                        <th class="text-center">Rate</th>
                    </tr>';

        foreach ($answer as $value) {
            $i++;
            $questions = $this->promo_model->selectAll_tcR('appraisal', array('appraisal_id' => $value['appraisal_id']));
            echo    '<tr>
                        <td colspan="3" style="font-size:80%;">
                            ' . $i . '. <b>' . $questions['title'] . '</b></br>' . $questions['description'] . '
                        </td>
                        <td class="text-center"><b>' . $value['rate'] . '</b></td>';
        }
        echo        '<tr>
                        <td style="font-size:80%;">Descriptive Rating:</td>
                        <td><b>' . $descrate . '</b></td>
                        <td style="font-size:80%;">Total Rating:</td>
                        <td><b>' . $details['numrate'] . '</b></td>
                    </tr>
                    <tr style="font-size:80%;">
                        <td >Rater\'s Comment</td>
                        <td colspan="3">
                            <textarea class="form-control readonly" readonly>' . $details['ratercomment'] . '</textarea>
                        </td>
                    </tr>
                    <tr style="font-size:80%;">
                        <td>Ratee\'s Comment</td>
                        <td colspan="3">
                        <textarea class="form-control readonly" readonly>' . $details['rateecomment'] . '</textarea>
                        </td>
                    </tr>';

        echo    '</table>';
    }

    public function viewExam_history()
    {
        $input = $this->input->post(NULL, TRUE);
        $appHist    = $this->promo_model->application_history($input['emp_id']);

        echo    '<table class="table ">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Examination Date</th>
                            <th>Applying For</th>
                            <th>Exam Code</th>
                            <th>Exam Type</th>
                            <th>Score</th>
                            <th>Norm</th>
                            <th>Result</th>
                        </tr>
                    </thead>';
        if ($appHist > 0) {
            $n = 0;
            $mergedData = [];
            $examDetails = $this->promo_model->selectAll_tcA('application_exams2take', array('app_id' => $input['emp_id']));

            for ($i = 0; $i < count($appHist); $i++) {
                $mergedRow = array_merge($appHist[$i], $examDetails[$i]);
                $mergedData[] = $mergedRow;
            }
            foreach ($mergedData as $value) {
                $n++;
                $exstr = explode(",", $value['description']);
                $excode = explode(" ", $exstr[1]);
                $exam_val = $input['emp_id'] . "|" . $excode[0];
                $input['code'] = $excode[0];
                $exam_ref = $value['no'] . '/' . $input['emp_id'];
                $examDet  = $this->promo_model->selectAll_tcR('application_examdetails', array('exam_ref' => $exam_ref));
                $exam_codename = $this->promo_model->selectAll_tcR('application_examtypes', array('exam_code' => $excode[0]));
                $extype = $examDet['exam_type'];
                if ($extype == "EXB") {
                    $overall = 28;
                } elseif ($extype == "ACCP-A" || $extype == "ACCP-B") {
                    $overall = 10;
                } elseif ($extype == "AIT-A") {
                    $overall = 60;
                } elseif ($extype == "AIT-B") {
                    $overall = 50;
                } elseif ($extype == "FIT") {
                    $overall = 12;
                } elseif ($extype == "NTA" || $extype == "VAT") {
                    $overall = 25;
                } elseif ($extype == "STAR" || $extype == "SACHS") {
                    $overall = 0;
                } else {
                    $overall = 0;
                }
                $norm = 'N/A';
                $examNorms  = $this->promo_model->selectAll_tcA('application_exam_norms', array('n_type' => $extype));
                foreach ($examNorms as $valNorm) {
                    if (intval($examDet['exam_score']) >= $valNorm['n_low'] && intval($examDet['exam_score']) <= $valNorm['n_high']) {
                        $norm = $valNorm['n_desc'];
                    }
                }

                if ($value['result'] == "passed") {
                    $result = '<span class="badge bg-success">Passed</span>';
                } else if ($value['result'] == "assessment") {
                    $result = '<span class="badge bg-primary">For Assessment</span>';
                } else if ($value['result'] == "failed") {
                    $result = '<span class="badge bg-danger">Failed</span>';
                }

                echo    '<tr>
                            <td>' . $n . '.</td>
                            <td>' . date('F j, Y', strtotime($value['date_time'])) . '</td>
                            <td>' . $value['position'] . '</td>
                            <td>' . $exam_codename['exam_codename'] . '</td>
                            <td>' . $extype . '</td>
                            <td>' . $examDet['exam_score'] . '/' . $overall . '</td>
                            <td>' . $norm . '</td>
                            <td>' . $result . '</td>
                        </tr>';
            }
        }
        echo    '</table>';
    }

    public function viewApp_details()
    {
        $input = $this->input->post(NULL, TRUE);
        $application = $this->promo_model->selectAll_tcA('application_history', array('app_id' => $input['emp_id']));
        echo    '<table class="table ">
                    <thead>
                        <tr>
                            <th>Date Accomplished</th>
                            <th>Description</th>
                            <th>Applying For</th>
                            <th>Status</th>
                            <th>Phases/Process</th>
                        </tr>
                    </thead>';
        foreach ($application as $row) {
            echo        '<tr>
                            <td>' . date('F j, Y', strtotime($row['date_time'])) . '</td>
                            <td>' . $row['description'] . '</td>
                            <td>' . $row['position'] . '</td>
                            <td>' . $row['status'] . '</td>
                            <td>' . $row['phase'] . '</td>
                        </tr>';
        }
    }

    public function viewInt_details()
    {
        $input = $this->input->post(NULL, TRUE);
        $interview = $this->promo_model->selectAll_tcA('application_interview_details_history', array('interviewee_id' => $input['emp_id']));
        echo    '<table class="table ">
                    <thead>
                        <tr>
                            <th>Date Interviewed</th>
                            <th>Interview Code</th>
                            <th>Interviewer</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>';
        foreach ($interview as $row) {
            echo        '<tr>
                            <td>' . date('F j, Y', strtotime($row['date_interviewed'])) . '</td>
                            <td>' . $row['interview_code'] . '</td>
                            <td>' . ucwords(strtolower($this->promo_model->getName_employee3($row['interviewer_id']))) . '</td>
                            <td>' . $row['interview_status'] . '</td>
                            <td>' . $row['interviewer_remarks'] . '</td>
                        </tr>';
        }
    }

    public function appHistory()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $data = array(
            'position_applied'  => $input['position_applied'],
            'date_applied'      => $input['date_applied'],
            'date_examined'     => $input['date_examined'],
            'exam_results'      => $input['exam_results'],
            'date_brief'        => $input['date_brief'],
            'date_hired'        => $input['date_hired'],
            'aeregular'         => ucwords(strtolower($input['aeregular'])),
        );
        $where  = array('app_id' => $input['emp_id']);
        $this->promo_model->update_twdA('application_details', $where, $data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success']);
        }
    }

    public function viewContract()
    {
        $input          = $this->input->post(NULL, TRUE);
        $empTable       = ($input['contract'] == 'current') ? 'employee3' : 'employmentrecord_';
        $recordTable    = ($input['contract'] == 'current') ? 'promo_record' : 'promo_history_record';
        $where          = array('e.emp_id' => $input['emp_id'], 'e.record_no' => $input['record_no']);
        $row            = $this->promo_model->promoDetails_wttR($where, $empTable, $recordTable);
        $bUs            = $this->promo_model->locate_promo_bu();
        $i              = 0;
        $epas           = [];
        $intro          = [];
        $clearance      = [];
        $contract       = [];
        foreach ($bUs as $bu) {

            $hasBu = $this->promo_model->empStores($recordTable, $input['emp_id'], $input['record_no'], $bu['bunit_field']);
            if ($hasBu > 0) {

                $i++;
                $bunitAcro              = $bu['bunit_acronym'];
                $epas[$bunitAcro]       = $bu['bunit_name'];
                $intro[$bunitAcro]      = $bu['bunit_intro'];
                $clearance[$bunitAcro]  = $bu['bunit_clearance'];
                $contract[$bunitAcro]   = $bu['bunit_contract'];
            }
        }

        if ($row > 0) {
            echo    '<table class="table">
                        <tr>
                            <td>Employee ID</td>
                            <th>:</th>
                            <th>' . $row['emp_id'] . '</th>
                            <td>Record No.</td>
                            <th>:</th>
                            <th>' . $row['record_no'] . '</th>
                        </tr>
                        <tr>
                            <td>Company</td>
                            <th>:</th>
                            <th>' . $row['promo_company'] . '</th>
                            <td>Department</td>
                            <th>:</th>
                            <th>' . $row['promo_department'] . '</th>
                        </tr>
                        <tr>    
                            <td>Promo Type</td>
                            <th>:</th>
                            <th>' . $row['promo_type'] . '</th>
                            <td>Contract Type</td>
                            <th>:</th>
                            <th>' . $row['type'] . '</th>
                        </tr>
                        <tr>    
                            <td>Start Date</td>
                            <th>:</th>
                            <th>' . date('F j, Y', strtotime($row['startdate'])) . '</th>
                            <td>EOC Date</td>
                            <th>:</th>
                            <th>' . date('F j, Y', strtotime($row['eocdate'])) . '</th>
                        </tr>
                        <tr>
                            <td>Position</td>
                            <th>:</th>
                            <th>' . $row['position'] . '</th>
                            <td>Current Status</td>
                            <th>:</th>
                            <th>' . $row['current_status'] . '</th>
                        </tr>';
            echo        '<tr>
                            <td>EPAS</td>
                            <th>:</th>
                            <th>
                                <div class="d-md-flex d-grid align-items-center gap-1 justify-content-right">';
            foreach ($epas as $key => $value) {

                $epasData = $this->promo_model->selectAll_tcR('appraisal_details', array('emp_id' => $row['emp_id'], 'record_no' => $row['record_no'], 'store' => $value));

                if ($epasData > 0) {

                    if ($epasData['numrate'] >= 85) {

                        $badge = ($epasData['raterSO'] == 1 && $epasData['rateeSO'] == 1) ? 'success' : 'warning';
                    } else {
                        $badge = 'danger';
                    }

                    echo            '<a href="javascript:;" id="' . $epasData['details_id'] . '" onclick="viewAppraisal(this.id)">
                                        <span class="badge bg-' . $badge . '">' . $key . '|' . $epasData['numrate'] . '</span>
                                    </a>';
                }
            }
            echo                '</div>
                            </th>
                            <td>INTRO</td>
                            <th>:</th>
                            <th>
                                <div class="d-md-flex d-grid align-items-center gap-1 justify-content-right">';
            foreach ($intro as $key => $value) {
                if ($row[$value] != '') {
                    echo            '<a href="javascript:;" id="' . $row[$value] . '" onclick="viewIntro(this.id)">
                                        <span class="badge bg-primary">' . $key . '</span>
                                    </a>';
                }
            }
            echo            '</th>
                        </tr>';
            echo        '<tr>
                            <td>CLEARANCE</td>
                            <th>:</th>
                            <th>
                                <div class="d-md-flex d-grid align-items-center gap-1 justify-content-right">';
            foreach ($clearance as $key => $value) {
                if ($row[$value] != '') {
                    echo            '<a href="javascript:;" id="' . $row[$value] . '" onclick="viewClearance(this.id)">
                                        <span class="badge bg-primary">' . $key . '</span>
                                    </a>';
                }
            }
            echo                '</div>
                            </th>
                            <td>CONTRACT</td>
                            <th>:</th>
                            <th>
                                <div class="d-md-flex d-grid align-items-center gap-1 justify-content-right">';
            foreach ($contract as $key => $value) {
                if ($row[$value] != '') {
                    echo            '<a href="javascript:;" id="' . $row[$value] . '" onclick="viewContracts(this.id)">
                                        <span class="badge bg-primary">' . $key . '</span>
                                    </a>';
                }
            }
            echo            '</th>
                        </tr>';
            echo        '<tr>
                            <td>Remarks</td>
                            <th>:</th>
                            <th colspan="4">' . $row['remarks'] . '</th>
                        </tr>
                    </table>';
        }
    }

    public function editContract()
    {
        $input          = $this->input->post(NULL, TRUE);
        $empTable       = ($input['contract'] == 'current') ? 'employee3' : 'employmentrecord_';
        $recordTable    = ($input['contract'] == 'current') ? 'promo_record' : 'promo_history_record';
        $bUs            = $this->promo_model->locate_promo_bu('asc');
        $promo_type     = ['ROVING', 'STATION'];
        $contract_type  = ['Contractual', 'Seasonal'];
        $bunit_id       = [];
        foreach ($bUs as $bu) {

            $hasBu = $this->promo_model->empStores($recordTable, $input['emp_id'], $input['record_no'], $bu['bunit_field']);
            if ($hasBu > 0) {
                $bunit_id[] = $bu['bunit_id'];
            }
        }
        $where      = array('e.emp_id' => $input['emp_id'], 'e.record_no' => $input['record_no']);
        $row        = $this->promo_model->promoDetails_wttR($where, $empTable, $recordTable);
        $agency     = $this->promo_model->selectAll_tk('promo_locate_agency');
        $company    = $this->promo_model->selectAll_tcA_tk('promo_locate_company', array('agency_code' => $row['agency_code']));
        $product    = $this->promo_model->selectAll_tcA('promo_products', array('record_no' => $input['record_no']));
        if (count($bunit_id) > 0) {
            $department = $this->promo_model->whereIN_stcd('DISTINCT', 'dept_name', 'locate_promo_department', 'bunit_id', $bunit_id);
        }
        $vendor     = $this->promo_model->selectAll_tcA('promo_vendor_lists', array('department' => $row['promo_department']));
        $position   = $this->promo_model->positions();
        $emp_type   = $this->promo_model->selectAll('employee_type');
        $cutoff     = $this->promo_model->selectAll_tk('promo_schedule');
        $statCut    = $this->promo_model->selectAll_tcR_tk('promo_sched_emp', array('empId' => $input['emp_id'], 'recordNo' => $input['record_no'])); ?>

        <div class="row">
            <input type="hidden" name="emp_id" value="<?= $input['emp_id'] ?>">
            <input type="hidden" name="record_no" value="<?= $input['record_no'] ?>">
            <input type="hidden" name="contract" value="<?= $input['contract'] ?>">
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-sm-12 mb-2">
                        <label>Agency</label>
                        <select class="form-select form-select-sm select2clear" name="agency_code" data-placeholder="Select Agency" onchange="getCompany(this.value)">
                            <option value="">Select Agency</option>
                            <?php
                            foreach ($agency as $value) {
                                echo '<option value="' . $value['agency_code'] . '"';
                                echo ($value['agency_code'] == $row['agency_code']) ? 'selected' : '';
                                echo '>' . $value['agency_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-12 mb-2">
                        <label>Company</label>
                        <select class="form-select form-select-sm select2clear" name="promo_company" data-placeholder="Select Company" onchange="getProduct(this.value)">
                            <option value="">Select Company</option>
                            <?php
                            foreach ($company as $value) {
                                echo '<option value="' . $value['company_name'] . '"';
                                echo ($value['company_name'] == $row['promo_company']) ? 'selected' : '';
                                echo '>' . $value['company_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-12 mb-2">
                        <label>Promo Type</label>
                        <select class="form-select form-select-sm select2" name="promo_type" data-placeholder="Select Promo Type" onchange="getStores(this.value,'<?= $row['promo_type'] ?>')">
                            <option value="">Select Promo Type</option>
                            <?php
                            foreach ($promo_type as $value) {
                                echo '<option value="' . $value . '"';
                                echo ($value == $row['promo_type']) ? 'selected' : '';
                                echo '>' . $value . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-12">
                        <label>Store(s)</label>
                        <ul class="list-group list-group-flush stores">
                            <?php
                            $type = ($row['promo_type'] == 'ROVING') ? 'checkbox' : 'radio';
                            foreach ($bUs as $bu) {

                                $hasBu      = $this->promo_model->empStores($recordTable, $input['emp_id'], $input['record_no'], $bu['bunit_field']);
                                $checked    = ($hasBu > 0) ? 'checked' : '';
                                $field      = $bu['bunit_id'] . '|' . $bu['bunit_field'];

                                echo    '<li class="list-group-item">
                                            <div class="form-check form-check-success">
                                                <input type="' . $type . '" class="form-check-input" name="stores[]" id="' . $field . '"  value="' . $field . '" ' . $checked . ' onclick="getDepartment()">
                                                <label class="form-check-label" for="' . $field . '">' . $bu['bunit_name'] . '</label>
                                            </div>
                                        </li>';
                            } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-sm-12 mb-2">
                        <label>Department</label>
                        <select class="form-select form-select-sm select2" name="promo_department" data-placeholder="Select Department" onchange="getVendor(this.value)">
                            <option value="">Select Department</option>
                            <?php
                            foreach ($department as $value) {
                                echo '<option value="' . $value['dept_name'] . '"';
                                echo ($value['dept_name'] == $row['promo_department']) ? 'selected' : '';
                                echo '>' . $value['dept_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-12 mb-2">
                        <label>Vendor</label>
                        <select class="form-select form-select-sm select2clear" name="vendor_code" data-placeholder="Select Vendor">
                            <option value="">Select Vendor</option>
                            <?php
                            foreach ($vendor as $value) {
                                echo '<option value="' . $value['vendor_code'] . '"';
                                echo ($value['vendor_code'] == $row['vendor_code']) ? 'selected' : '';
                                echo '>' . $value['vendor_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-12 mb-2">
                        <label>Product</label>
                        <select class="form-select form-select-sm select2" name="product[]" multiple data-placeholder="Select Product">
                            <option value="">Select Product</option>
                            <?php
                            foreach ($product as $value) {
                                echo '<option value="' . $value['product'] . '" selected>' . $value['product'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-12 mb-2">
                        <label>Position</label>
                        <select class="form-select form-select-sm select2" name="position" data-placeholder="Select Position">
                            <option value="">Select Position</option>
                            <?php
                            foreach ($position as $value) {
                                echo '<option value="' . $value['position_title'] . '"';
                                echo ($value['position_title'] == $row['position']) ? 'selected' : '';
                                echo '>' . $value['position_title'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-12 mb-2">
                        <label>Employee Type</label>
                        <select class="form-select form-select-sm select2" name="emp_type" data-placeholder="Select Employee Type">
                            <option value="">Select Employee Type</option>
                            <?php
                            foreach ($emp_type as $value) {
                                echo '<option value="' . $value['emp_type'] . '"';
                                echo ($value['emp_type'] == $row['emp_type']) ? 'selected' : '';
                                echo '>' . $value['emp_type'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-12 mb-2">
                        <label>Contract Type</label>
                        <select class="form-select form-select-sm select2" name="type" data-placeholder="Select Contract Type">
                            <option value="">Select Contract Type</option>
                            <?php
                            foreach ($contract_type as $value) {
                                echo '<option value="' . $value . '"';
                                echo ($value == $row['type']) ? 'selected' : '';
                                echo '>' . $value . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-12 mb-2">
                        <label>Startdate</label>
                        <input type="text" class="form-control form-control-sm datepicker" name="startdate" value="<?= $row['startdate'] ?>" placeholder="yyyy/mm/dd" onchange="getDuration(this.name)">
                    </div>
                    <div class="col-sm-12 mb-2">
                        <label>EOCdate</label>
                        <input type="text" class="form-control form-control-sm datepicker" name="eocdate" value="<?= $row['eocdate'] ?>" placeholder="yyyy/mm/dd" onchange="getDuration(this.value)">
                    </div>
                    <input type="hidden" name="duration" value="<?= $row['duration'] ?>">
                    <div class="col-sm-12 mb-2">
                        <label>Cutoff</label>
                        <select class="form-select form-select-sm select2" name="statCut" data-placeholder="Select Cutoff">
                            <option value="">Select Cutoff</option>
                            <?php
                            foreach ($cutoff as $value) {
                                $endFC = ($value['endFC'] != '') ? $value['endFC'] : 'last';
                                echo '<option value="' . $value['statCut'] . '"';
                                echo ($value['statCut'] == $statCut['statCut']) ? 'selected' : '';
                                echo '>' . $value['startFC'] . '-' . $endFC . ' / ' . $value['startSC'] . '-' . $value['endSC'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-12 mb-2">
                        <label>Remarks</label>
                        <textarea class="form-control form-control-sm" name="remarks" rows="4"><?= $row['remarks'] ?></textarea>
                    </div>
                </div>
            </div>
        </div>
<?php
    }

    public function getSelect()
    {
        $input = $this->input->post(NULL, TRUE);
        if ($input['action'] == 'getCompany') {

            $row = $this->promo_model->selectAll_tcA_tk('promo_locate_company', array('agency_code' => $input['value']));
            echo '<option value="">Select Company</option>';

            foreach ($row as $value) {

                echo '<option value="' . $value['company_name'] . '">' . $value['company_name'] . '</option>';
            }
        } else if ($input['action'] == 'getProduct') {

            $row = $this->promo_model->selectAll_tcA('promo_company_products', array('company' => $input['value']));
            echo '<option value="">Select Product</option>';
            foreach ($row as $value) {
                echo '<option value="' . $value['product'] . '">' . $value['product'] . '</option>';
            }
        } else if ($input['action'] == 'getStores') {

            $bUs            = $this->promo_model->locate_promo_bu('asc');
            $type           = ($input['value'] == 'ROVING') ? 'checkbox' : 'radio';
            $recordTable    = ($input['contract'] == 'current') ? 'promo_record' : 'promo_history_record';
            foreach ($bUs as $bu) {

                $hasBu      = $this->promo_model->empStores($recordTable, $input['emp_id'], $input['record_no'], $bu['bunit_field']);
                $checked    = ($input['promo_type'] == $input['value'] && $hasBu > 0) ? 'checked' : '';
                $field      = $bu['bunit_id'] . '|' . $bu['bunit_field'];

                echo    '<li class="list-group-item">
                            <div class="form-check form-check-success">
                                <input type="' . $type . '" class="form-check-input" name="stores[]" id="' . $field . '"  value="' . $field . '" ' . $checked . ' onclick="getDepartment()">
                                <label class="form-check-label" for="' . $field . '">' . $bu['bunit_name'] . '</label>
                            </div>
                        </li>';
            }
        } else if ($input['action'] == 'getDepartment') {

            if (isset($input['value'])) {
                $id         = [];
                foreach ($input['value'] as $value) {
                    $val    = explode('|', $value);
                    $id[]   = $val['0'];
                }
                $department = $this->promo_model->whereIN_stcd('DISTINCT', 'dept_name', 'locate_promo_department', 'bunit_id', $id);
                echo '<option value="">Select Department</option>';
                foreach ($department as $value) {
                    echo '<option value="' . $value['dept_name'] . '">' . $value['dept_name'] . '</option>';
                }
            } else {
                echo '<option value="">Select Department</option>';
            }
        } else if ($input['action'] == 'getIntros') {
            if (isset($input['value'])) {
                $id         = [];
                echo '<div class="row mt-2">
                        <strong class="mb-1">Upload Intros</strong>';
                foreach ($input['value'] as $value) {
                    $val    = explode('|', $value);
                    $id[]   = $val[0];
                    $intro = $this->promo_model->selectAll_tcR('locate_promo_business_unit', array('bunit_id' => $val[0]));
                    echo    '<div class="col-sm-3 mb-2">
                                <label>' . $intro['bunit_name'] . '</label>
                                <input type="file" class="form-control form-control-sm" name="' . $intro['bunit_intro'] . '">
                            </div>';
                }
                echo '</div>';
            }
        } else if ($input['action'] == 'getVendor') {

            $vendor = $this->promo_model->selectAll_tcA('promo_vendor_lists', array('department' => $input['value']));
            echo '<option value="">Select Vendor</option>';
            foreach ($vendor as $value) {
                echo '<option value="' . $value['vendor_code'] . '">' . $value['vendor_name'] . '</option>';
            }
        } else if ($input['action'] == 'getDuration') {

            $date1      = $input['startdate'];
            $date2      = $input['eocdate'];
            $message    = '';
            if ($date1 != '') {
                $timestamp1 = strtotime($date1);
                $timestamp2 = strtotime($date2);
                if ($timestamp1 < $timestamp2) {
                    $startdate  = new DateTime($date1);
                    $eocdate    = new DateTime($date2);
                    $interval   = $startdate->diff($eocdate);
                    $years      = $interval->y;
                    $months     = $interval->m;
                    $days       = $interval->d;
                    $result     = 'success';
                    if ($years > 0) {
                        $message    .=  "$years year" . ($years > 1 ? "s" : "") . " ";
                    }
                    if ($months > 0) {
                        $message    .= "$months month" . ($months > 1 ? "s" : "") . " ";
                    }
                    if ($days > 0) {
                        $message    .= "$days day" . ($days > 1 ? "s" : "") . " ";
                    }
                } else if ($timestamp1 > $timestamp2) {
                    $result     = 'failed';
                    $message    = 'EOCdate should be later than Startdate!';
                } else {
                    $result     = 'success';
                    $message    = "1 day";
                }
            } else {
                $result         = 'failed';
                $message        = 'Startdate is blank!';
            }
            echo json_encode(['result' => $result, 'message' => $message]);
        } else if ($input['action'] == 'getSupervisor_bc') {
            $bc = $this->promo_model->selectAll_tcA('locate_business_unit', array('company_code' => $input['value']));
            echo '<option value="">Select Business Unit</option>';
            foreach ($bc as $value) {
                echo '<option value="' . $input['value'] . '|' . $value['bunit_code'] . '">' . $value['business_unit'] . '</option>';
            }
        } else if ($input['action'] == 'getSupervisor_dc') {
            $val = explode('|', $input['value']);
            $cc = $val[0];
            $bc = $val[1];
            $query = $this->promo_model->selectAll_tcA('locate_department', array('company_code' => $cc));
            echo '<option value="">Select Department</option>';
            foreach ($query as $value) {
                if ($value['bunit_code'] == $bc) {
                    echo '<option value="' . $input['value'] . '|' . $value['dept_code'] . '">' . $value['dept_name'] . '</option>';
                }
            }
        } else if ($input['action'] == 'getSupervisor_sc') {
            $val = explode('|', $input['value']);
            $cc = $val[0];
            $bc = $val[1];
            $dc = $val[2];
            $query = $this->promo_model->selectAll_tcA('locate_section', array('company_code' => $cc));
            echo '<option value="">Select Section</option>';
            foreach ($query as $value) {
                if ($value['bunit_code'] == $bc && $value['dept_code'] == $dc) {
                    echo '<option value="' . $input['value'] . '|' . $value['section_code'] . '">' . $value['section_name'] . '</option>';
                }
            }
        } else if ($input['action'] == 'getSupervisor_ssc') {
            $val = explode('|', $input['value']);
            $cc = $val[0];
            $bc = $val[1];
            $dc = $val[2];
            $sc = $val[3];
            $query = $this->promo_model->selectAll_tcA('locate_sub_section', array('company_code' => $cc));
            echo '<option value="">Select Sub-Section</option>';
            foreach ($query as $value) {
                if ($value['bunit_code'] == $bc && $value['dept_code'] == $dc && $value['section_code'] == $sc) {
                    echo '<option value="' . $input['value'] . '|' . $value['sub_section_code'] . '">' . $value['sub_section_name'] . '</option>';
                }
            }
        } else if ($input['action'] == 'getSupervisor_uc') {
            $val    = explode('|', $input['value']);
            $cc     = $val[0];
            $bc     = $val[1];
            $dc     = $val[2];
            $sc     = $val[3];
            $ssc    = $val[4];
            $query  = $this->promo_model->selectAll_tcA('locate_unit', array('company_code' => $cc));
            echo '<option value="">Select Sub-Section</option>';
            foreach ($query as $value) {
                if ($value['bunit_code'] == $bc && $value['dept_code'] == $dc && $value['section_code'] == $sc && $value['sub_section_code'] == $ssc) {
                    echo '<option value="' . $input['value'] . '|' . $value['unit_code'] . '">' . $value['unit_name'] . '</option>';
                }
            }
        } else if ($input['action'] == 'getDutydays') {
            $days = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
            $dutyDays = '';
            $i = 0;
            foreach ($days as $value) {
                if ($value != substr($input['value'], 0, 3)) {
                    $i++;
                    $dutyDays = ($i == 1) ? $value : $dutyDays . ', ' . $value;
                }
            }
            echo $dutyDays;
        } else if ($input['action'] == 'printPermit') {
            $emp_id = $input['value'];
            $where = array('e.emp_id' => $emp_id);
            $current = $this->promo_model->promoDetails_wttA($where, 'employee3', 'promo_record');
            $previous = $this->promo_model->promoDetails_wttA($where, 'employmentrecord_', 'promo_history_record');

            echo    '<div class="table-container">
                        <table class="table">
                            <tr>
                                <th class="text-center">Contract No.</th>
                                <th>Startdate</th>
                                <th>EOCdate</th>
                                <th class="text-center">Action</th>
                            </tr>
                            <tbody>';
            if (count($current) > 0) {
                foreach ($current as $value) {
                    $record_no = $value['record_no'];
                    echo    '<tr>
                                <td class="text-center"><span class="badge bg-success">Current</span></td>
                                <td>' . date('M. d, Y', strtotime($value['startdate'])) . '</td>
                                <td>' . date('M. d, Y', strtotime($value['eocdate'])) . '</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary" onclick="selectPermit(\'' . $emp_id . '\',\'' . $record_no . '\', \'current\')">Select</button>
                                </td>
                            </tr>';
                }
            }
            if (count($previous) > 0) {
                $i = 0;
                foreach ($previous as $value) {
                    $i++;
                    $record_no = $value['record_no'];
                    echo    '<tr>
                                <td class="text-center"><span class="badge bg-danger">' . $i . '. Previous</span></td>
                                <td>' . date('M. d, Y', strtotime($value['startdate'])) . '</td>
                                <td>' . date('M. d, Y', strtotime($value['eocdate'])) . '</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary" onclick="selectPermit(\'' . $emp_id . '\',\'' . $record_no . '\', \'previous\')">Select</button>
                                </td>
                            </tr>';
                }
            }
            echo    '</tbody>
                    </table>
                    </div>';
        } else if ($input['action'] == 'printContract') {
            $emp_id     = $input['value'];
            $where      = array('e.emp_id' => $emp_id);
            $row        = $this->promo_model->promoDetails_wttR($where, 'employee3', 'promo_record');
            $sss_ctc    = $this->promo_model->selectAll_tcR('applicant_otherdetails', array('app_id' => $row['emp_id']));
            $witness        = $this->promo_model->selectAll_tcR('employment_witness', array('emp_id' => $row['emp_id'], 'rec_no' => $row['record_no']));
            $issuedAt       = !empty($witness['issuedat']) ? $witness['issuedat'] : (!empty($sss_ctc['cedula_place']) ? $sss_ctc['cedula_place'] : '');
            $issuedOn       = !empty($witness['issuedon']) ? $witness['issuedon'] : (!empty($sss_ctc['cedula_date']) ? $sss_ctc['cedula_date'] : '');
            if ($witness['sss_ctc'] == 'Cedula') {
                $cedulaNo   = !empty($witness['sssno_ctcno']) ? $witness['sssno_ctcno'] : (!empty($sss_ctc['cedula_no']) ? $sss_ctc['cedula_no'] : '');
                $sssNo      = !empty($sss_ctc['sss_no']) ? $sss_ctc['sss_no'] : '';
            } else {
                $sssNo      = !empty($witness['sssno_ctcno']) ? $witness['sssno_ctcno'] : (!empty($sss_ctc['sss_no']) ? $sss_ctc['sss_no'] : '');
                $cedulaNo   = !empty($sss_ctc['cedula_no']) ? $sss_ctc['cedula_no'] : '';
            }

            $bUs        = $this->promo_model->locate_promo_bu('asc');
            $field      = '';
            echo '
            <input type="hidden" class="form-control" name="emp_id" value="' . $row['emp_id'] . '">
            <input type="hidden" class="form-control" name="record_no" value="' . $row['record_no'] . '">
            <input type="hidden" class="form-control" name="contract" value="current">
            <div class="col-md-10 mx-auto">
                <div class="row">
                    <div class="col-sm-12 mb-2">
                        <label>Contract Header</label>
                        <select class="form-select form-select-sm select2" name="contract_header_no" data-placeholder="Select Contract Header">
                            <option value="">Select Contract Header</option>';

            foreach ($bUs as $bU) {
                $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bU['bunit_field']);
                if ($hasBu > 0) {
                    $array = array(
                        10 => 'tal_contract',
                        11 => 'pm_contract',
                        12 => 'icm_contract',
                        14 => 'asc_contract',
                        31 => 'tub_contract',
                        32 => 'colc_contract',
                        33 => 'alta_contract',
                        36 => 'fr_panglao_contract',
                        37 => 'fr_tubigon_contract',
                        23 => $bU['bunit_contract'],
                    );
                    $arraykey = array_search($bU['bunit_contract'], $array);
                    if ($arraykey !== false) {
                        $value = $this->promo_model->selectAll_tcR('contract_header', array('ccode_no' => $arraykey));
                        echo '<option value="' . $value['ccode_no'] . '">' . $value['company'] . '</option>';
                    }
                }
            }
            echo        '</select>
                    </div>
                    <div class="col-sm-12 mb-2">
                        <label>Please choose either to use SSS No. or Cedula (CTC No.)</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-check form-check-success">
                                    <input class="form-check-input" type="radio" name="sss_ctc[]" id="flexRadioSuccess2" value="sss" onclick="showElement(this.value)">
                                    <label class="form-check-label" for="flexRadioSuccess2">SSS No.</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" name="sss" value="' . $sssNo . '" style="display: none;" disabled placeholder="SSS No.">
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check form-check-success">
                                    <input class="form-check-input" type="radio" name="sss_ctc[]" id="flexRadioSuccess1" value="cedula" onclick="showElement(this.value)">
                                    <label class="form-check-label" for="flexRadioSuccess1">Cedula (CTC No.)</label>
                                </div>
                            </div>
                            <div class="col-sm-6 issuedOn">
                                <input type="text" class="form-control form-control-sm" name="ctc" value="' . $cedulaNo . '" style="display: none;" disabled placeholder="Cedula (CTC No.)">
                            </div>
                            <div class="col-sm-6 mt-2 issuedat" style="display: none;" style="display: none;">
                                <label>Issued At</label>
                                <input type="text" class="form-control form-control-sm" name="issuedat" value="' . $issuedAt . '" disabled>
                            </div>
                            <div class="col-sm-6 mt-2 issuedon" style="display: none;" style="display: none;">
                                <label>Issued On</label>
                                <input type="text" class="form-control form-control-sm datepicker" name="issuedon" value="' . $issuedOn . '" placeholder="yyyy/mm/dd" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-2 searchContainer">
                        <label>Witness 1</label>
                        <input type="text" class="form-control form-control-sm" name="w1" value="' . $witness['witness1'] . '" onkeyup="searhPromo(this.value, this.name)">
                        <div class="dropdown-list w1"></div>
                    </div>
                    <div class="col-sm-6 mb-2 searchContainer">
                        <label>Witness 2</label>
                        <input type="text" class="form-control form-control-sm" name="w2" value="' . $witness['witness2'] . '" onkeyup="searhPromo(this.value, this.name)">
                        <div class="dropdown-list w2"></div>
                    </div>
                    <div class="col-sm-6">
                        <label>Date of Signing the Contract</label>
                        <input type="text" class="form-control form-control-sm datepicker" name="date" value="' . $witness['date_generated'] . '" placeholder="yyyy/mm/dd">
                    </div>
                </div>
            </div>';
        } else if ($input['action'] == 'filterDepartment') {
            if (isset($input['value'])) {

                $val    = explode('|', $input['value']);
                $id     = $val['0'];
                $department = $this->promo_model->selectAll_tcA('locate_promo_department', array('bunit_id' => $id));
                echo '<option value="">Department...</option>';
                foreach ($department as $value) {
                    echo '<option value="' . $value['dept_name'] . '">' . $value['dept_name'] . '</option>';
                }
            } else {
                echo '<option value="">Department...</option>';
            }
        } else if ($input['action'] == 'getQbeDept') {
            if (isset($input['value'])) {
                $val    = explode('|', $input['value']);
                $id     = $val['0'];
                $department = $this->promo_model->selectAll_tcA('locate_promo_department', array('bunit_id' => $id));
                echo '<option value="">Select Department</option>';
                foreach ($department as $value) {
                    echo '<option value="' . $value['dept_name'] . '">' . $value['dept_name'] . '</option>';
                }
            } else {
                echo '<option value="">Select Department</option>';
            }
        }
    }

    public function supervisorList()
    {
        $input = $this->input->post(NULL, TRUE);
        $query = $this->promo_model->supervisorList($input);
        echo    '<table class="table table-hover" id="supervisorList">
                    <thead>
                        <tr>
                            <th class="text-center"></th>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th width="30%">Position</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach ($query as $value) {
            echo        '<tr>
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input" name="supervisor[]" value="' . $value['emp_id'] . '">
                            </td>
                            <td>' . $value['emp_id'] . '</td>
                            <td>' . $value['name'] . '</td>
                            <td>' . $value['position'] . '</td>
                            <td class="text-center">' . $value['current_status'] . '</td>
                        </tr>';
        }
        echo        '</tbody>
                </table>';
    }

    public function save_editContract()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $this->promo_model->save_editContract($input);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success']);
        }
    }

    public function uploadContract()
    {
        $input = $this->input->post(NULL, TRUE);
        echo    '<div class="row">
                    <input type="hidden" name="emp_id" value="' . $input['emp_id'] . '">
                    <input type="hidden" name="record_no" value="' . $input['record_no'] . '">
                    <input type="hidden" name="contract" value="' . $input['contract'] . '">
                    <div class="row">
                        <div class="col-sm-12 mb-2">
                            <select class="form-select select2" data-placeholder="Select Contract File" onchange="contractFile(this.value)">
                                <option value="clearance">Clearance Files</option>
                                <option value="contract">Contract Files</option>
                                <option value="intro">Intro Files</option>
                                <option value="epas" disabled>EPAS Files</option>
                            </select>
                        </div>
                    </div>
                    <div class="row contractFile"></div>
                </div>';
    }

    public function contractFile()
    {
        $input          = $this->input->post(NULL, TRUE);
        $empTable       = ($input['contract'] == 'current') ? 'employee3' : 'employmentrecord_';
        $recordTable    = ($input['contract'] == 'current') ? 'promo_record' : 'promo_history_record';
        $where          = array('e.emp_id' => $input['emp_id'], 'e.record_no' => $input['record_no']);
        $row            = $this->promo_model->promoDetails_wttR($where, $empTable, $recordTable);
        $bUs            = $this->promo_model->locate_promo_bu();
        $file           = 'bunit_' . $input['file'];
        $function       = ($input['file'] == 'contract') ? 'viewContracts' : 'view' . ucfirst($input['file']);

        echo '<input type="hidden" class="form-control" name="file" value="' . $file . '">';
        foreach ($bUs as $bu) {

            $hasBu = $this->promo_model->empStores($recordTable, $input['emp_id'], $input['record_no'], $bu['bunit_field']);
            if ($hasBu > 0) {
                if ($row[$bu[$file]] != '') {
                    $url        = base_url('../hrms/promo/' . $row[$bu[$file]]);
                    $response   = @file_get_contents($url);
                    $photo      = ($response !== false) ? $row[$bu[$file]] : '../../hrmsv2/assets/promo_assets/images/noimage.jpg';
                    if ($file == 'bunit_intro') {
                        $photo      = $row[$bu[$file]];
                    }
                } else {
                    $photo      = '../../hrmsv2/assets/promo_assets/images/noimage.jpg';
                }
                $link      = base_url('../hrms/promo/' . $photo);
                echo    '<div class="col-sm-4 mb-3 text-center">
                            <div class="card border-primary border-bottom border-3 border-0">
                                <a href="javascript:;" id="' . $photo  . '" onclick="' . $function . '(this.id)">   
                                    <div class="d-md-flex d-grid align-items-center gap-1 justify-content-center mt-2 mb-1">
                                        <i class="fadeIn animated bx bx-info-circle font-18 text-primary">
                                        </i><span class="badge text-secondary">Click to enlarge image.</span>
                                    </div>       
                                    <img src="' . $link  . '" class="card-img-top" style="max-height:50px;" alt="...">
                                </a>
                                <div class="card-body">
                                    <p class="card-title text-primary">' . $bu['bunit_name'] . '</br>' . ucfirst($input['file']) . '</p>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="file" class="form-control form-control-sm" name="' . $bu[$file] . '">
                                    </div>
                                </div>
                            </div>
                        </div>';
            }
        }
    }

    public function save_uploadContract()
    {
        $input              = $this->input->post(NULL, TRUE);
        $bUs                = $this->promo_model->locate_promo_bu('asc');
        $docPath            = explode('_', $input['file']);
        $path               = ($docPath[1] == 'intro') ? 'final_requirements/others' : $docPath[1];
        $input['insert']    =  $docPath[1];
        $recordTable        = ($input['contract'] == 'current') ? 'promo_record' : 'promo_history_record';
        $this->db->trans_start();

        foreach ($bUs as $bu) {

            $hasBu = $this->promo_model->empStores($recordTable, $input['emp_id'], $input['record_no'], $bu['bunit_field']);
            if ($hasBu > 0) {

                $file_path   = '';
                if (isset($_FILES[$bu[$input['file']]]['name'])) {

                    $image_name = addslashes($_FILES[$bu[$input['file']]]['name']);
                    $array      = explode(".", $image_name);
                    $filename   = $input['emp_id'] . "=" . date('Y-m-d') . "=" . $bu[$input['file']] . "=" . date('H-i-s-A') . "." . end($array);
                    $file_path  = '../hrms/document/' . $path . '/' . $filename; //temp

                    if (move_uploaded_file($_FILES[$bu[$input['file']]]['tmp_name'], $file_path)) {

                        $path   = '../document/' . $path . '/' . $filename; //temp
                        $column = $bu[$input['file']];
                        $where  = array('emp_id' => $input['emp_id'], 'record_no' => $input['record_no']);
                        $this->promo_model->update_twdA($recordTable, $where, array($column => $path));

                        if ($input['insert'] == 'intro') {
                            $data = array(
                                'app_id'                => $input['emp_id'],
                                'requirement_name'      => $input['insert'],
                                'filename'              => $path,
                                'date_time'             => date('Y-m-d'),
                                'requirement_status'    => 'passed',
                                'receiving_staff'       => $this->systemUser,
                            );
                            $this->promo_model->insert_tdA('application_otherreq', $data);
                        }
                    }
                }
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            $id = $input['contract'] . '|' . $input['emp_id'] . '|' . $input['record_no'];
            echo json_encode(['message' => 'success', 'id' => $id]);
        }
    }

    public function save_supervisor_form()
    {
        $input = $this->input->post(NULL, TRUE);

        $this->db->trans_start();
        foreach ($input['supervisor'] as $value) {
            $input['ratee'] = $value;
            $data = array(
                'ratee'                 => $input['ratee'],
                'subordinates_rater'    => $input['app_id'],
                'date_added'            => date('Y-m-d'),
                'added_by'              => $this->systemUser,
            );
            $this->promo_model->insert_tdA('leveling_subordinates', $data);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success']);
        }
    }

    public function docFile_view()
    {
        $input      = $this->input->post(NULL, TRUE);
        $page       = (isset($input['page'])) ? $input['page'] : 0;
        if ($input['no'] != 27) {
            $row    = $this->promo_model->selectAll_tcR('201document', array('no' => $input['no']));
            if ($input['no'] == 15) {
                $query  = $this->promo_model->get201_file($row, $input['emp_id'], $page, $input['no']);
                $total  = $this->promo_model->get201_file($row, $input['emp_id'], null, $input['no']);
            } else {
                $query  = $this->promo_model->get201_file($row, $input['emp_id'], $page);
                $total  = $this->promo_model->get201_file($row, $input['emp_id']);
            }
            $name   = $this->promo_model->getName_employee3($query['receiving_staff']);
            $link   =  base_url('../hrms/promo/' . $query['filename']);
            $date   = date('F j, Y', strtotime($query['date_time']));
        } else {
            $query  = $this->promo_model->get201_resignation($input['emp_id'], $page);
            $total  = $this->promo_model->get201_resignation($input['emp_id']);
            $name   = $this->promo_model->getName_employee3($query['added_by']);
            $link   =  base_url('../hrms/promo/' . $query['resignation_letter']);
            $date   = date('F j, Y', strtotime($query['date_secure']));
        }
        $num    = count($total);
        echo    '<div class="row">
                    <div class="col-sm-10">
                        <small>
                        <span>Date Uploaded:</span>
                        <span><b>' . $date . '</b></span>
                        <span>| Uploaded By:</span>
                        <span><b>' . $name . '</b></span>
                        </small>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group input-group-sm mb-3 justify-content-end">
                            <span class="input-group-text">page</span>
                            <select name="page" onchange="page(this.value, ' . $input['no'] . ')" style="border-color: #dee2e6;">';
        for ($i = 0; $i < $num; $i++) {
            $pageNum = $i + 1;
            $selected = ($i == $page) ? 'selected' : '';
            echo                '<option value="' . $i . '" ' . $selected . '>' . $pageNum . '</option>';
        }
        echo                '</select>
                        </div>
                    </div>
                </div>
                <img src="' . $link . '" class="d-block w-100" alt="..."">';
    }

    public function docFile_upload()
    {
        $input  = $this->input->post(NULL, TRUE);
        $this->db->trans_start();
        if ($input['no'] != 27) {

            $row = $this->promo_model->selectAll_tcR('201document', array('no' => $input['no']));
            $name = str_replace(' ', '', $row['201_name']);
            $file_path   = '';
            if (isset($_FILES[$name]['name'])) {

                $image_name = addslashes($_FILES[$name]['name']);
                $array      = explode(".", $image_name);
                $filename   = $input['emp_id'] . "=" . date('Y-m-d') . "=" . $row['201_name'] . "=" . date('H-i-s-A') . "." . end($array);
                $file_path  = '../hrms/document/' . $row['path'] . '/' . $filename; //temp

                if (move_uploaded_file($_FILES[$name]['tmp_name'], $file_path)) {
                    $path   = $row['path'] . '/' . $filename; //temp
                    $data   = array(
                        $row['empField']        => $input['emp_id'],
                        'requirement_name'      => $row['requirementName'],
                        'filename'              => $path,
                        'date_time'             => date('Y-m-d'),
                        'requirement_status'    => 'passed',
                        'receiving_staff'       => $this->systemUser,
                    );
                    $this->promo_model->insert_tdA($row['tableName'], $data);
                }
            }
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success']);
        }
    }

    public function remarks()
    {
        $input  = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $chk    = $this->promo_model->selectAll_tcA('remarks', array('emp_id' => $input['emp_id']));
        $data   = array(
            'emp_id'    => $input['emp_id'],
            'remarks'   => $input['remarks']
        );
        if (count($chk) == 0) {
            $this->promo_model->insert_tdA('remarks', $data);
        } else {
            $this->promo_model->update_twdA('remarks', array('emp_id' => $input['emp_id']), array('remarks' => $input['remarks']));
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'failed']);
        } else {
            echo json_encode(['message' => 'success']);
        }
    }
}
