<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Promo extends CI_Controller
{
    public $adminUser;
    public $systemUser;
    function __construct()
    {
        parent::__construct();

        $empId              = $this->session->userdata('emp_id');
        $this->adminUser    = array('06359-2013', '01186-2023');
        $this->systemUser   = $this->session->userdata('emp_id');
        if (!isset($empId)) {

            redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrmsv2/employee');
        }
    }

    public function masterfile()
    {
        $input = $this->input->post(NULL, TRUE);

        $list = $this->promo_model->masterfile($input);

        $data = [];
        foreach ($list as $row) {

            $name = '<a class="text-truncate" href="' . base_url('promo/page/promo/profile/' . $row['emp_id']) . '" target="_blank">' . ucwords(strtolower($row['name'])) . '</a>';
            $i = 0;
            $stores = '';
            $bUs = $this->promo_model->locate_promo_bu('asc');
            foreach ($bUs as $bu) {
                $hasBu = $this->promo_model->empStores('promo_record', $row['emp_id'], $row['record_no'], $bu['bunit_field']);
                if ($hasBu > 0) {
                    $i++;
                    $str = '<span data-toggle="tooltip" title="' . $bu['bunit_name'] . '">' . $bu['bunit_acronym'] . '</span>';
                    $stores = ($i == 1) ? $str  : $stores . ', ' . $str;
                }
            }
            $status = '<span class="badge bg-success">' . $row['current_status'] . '</span>';
            $column     = [];
            $column[]   = $name;
            $column[]   = $row['promo_company'];
            $column[]   = $stores;
            $column[]   = $row['promo_department'];
            $column[]   = $row['position'];
            $column[]   = $row['emp_type'];
            $column[]   = $status;
            $data[]     = $column;
        }

        echo json_encode(array('data' => $data));
    }

    public function getPromoDetails()
    {
        $input              = $this->input->post(NULL, TRUE);
        $row                = $this->promo_model->promoDetails_wttR(array('e.emp_id' => $input['emp_id']), 'employee3', 'promo_record');
        $data['emp_id']     = $input['emp_id'];
        $data['agency']     = 'No Agency';
        $data['company']    = 'No Company';
        $data['store']      = '';
        $data['current_stores'] = '';
        if ($row['agency_code'] != 0 && $row['agency_code'] != '') {
            $condition      = array('agency_code' => $row['agency_code']);
            $data['agency'] = $this->promo_model->selectAll_tcR_tk('promo_locate_agency', $condition)['agency_name'];
        }
        if ($row['promo_company'] != '') {
            $data['company'] = $row['promo_company'];
        }
        $i      = 0;
        $bUs    = $this->promo_model->locate_promo_bu('asc');
        foreach ($bUs as $bu) {
            $hasBu = $this->promo_model->empStores('promo_record', $input['emp_id'], $row['record_no'], $bu['bunit_field']);
            if ($hasBu > 0) {
                $i++;
                $data['store'] = ($i == 1) ? $bu['bunit_acronym'] : $data['store'] . ', ' . $bu['bunit_acronym'];
            }
        }
        $data['record_no']  = $row['record_no'];
        $data['department'] = $row['promo_department'];
        $data['position']   = $row['position'];
        $data['type']       = $row['emp_type'] . ' (' . $row['type'] . ')';
        $data['promo_type'] = $row['promo_type'];
        $data['contract']   = date('m/d/Y', strtotime($row['startdate']))  . '-' . date('m/d/Y', strtotime($row['eocdate']));
        $data['status']     = $row['current_status'];

        $data['request']    = 'getPromoDetails';
        $this->load->view('promo/page/page_response', $data);
    }

    public function searchApplicant()
    {
        $input = $this->input->post(NULL, TRUE);
        $condition = array('lastname LIKE' => '%' . $input['lastname'] . '%', 'firstname LIKE' => '%' . $input['firstname'] . '%');
        $list = $this->promo_model->selectAll_tcA('applicant', $condition);
        if (count($list) > 0) {
            echo '<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4 slider mx-auto">';
            foreach ($list as $value) {
                $name   = ucwords(strtolower($value['lastname'])) . ', ' .  ucwords(strtolower($value['firstname'] . ' ' . substr($value['middlename'], 0, 1) . '. ' . $value['suffix']));
                if ($value['photo'] != '') {
                    $url        = "http://$_SERVER[SERVER_ADDR]:$_SERVER[SERVER_PORT]/hrms/promoV2/{$value['photo']}";
                    $response   = @file_get_contents($url);
                    $photo      = ($response !== false) ? $value['photo'] : 'assets/images/promologo.png';
                } else {
                    $photo      = 'assets/images/promologo.png';
                }
                $photoLink      = 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promoV2/' . $photo;

                echo   '<div class="col">
                            <div class="card radius-15 bg-gradient-blues">
                                <div class="card-body text-center">
                                    <a href="' . base_url('promo/page/promo/profile/' . $value['app_id']) . '" target="_blank">
                                        <div class="p-1 radius-15">
                                            <img src="' . $photoLink . '" width="110" height="110" class="rounded-circle shadow p-1 bg-white" alt="">
                                            <h5 class="mt-1 mb-0 text-white text-truncate" data-toggle="tooltip" style="max-width: auto;" title="' . $name . '">
                                                ' . $name . '
                                            </h5>
                                            <p class="text-white"><strong>' . $value['app_id'] . '</strong></p>
                                            <p class="text-white mb-0">' . $value['civilstatus'] . '</p>
                                            <p class="text-white mb-0">Born ' . date('F j, Y', strtotime($value['birthdate'])) . '</p>
                                            <p class="text-white mb-0 text-truncate" data-toggle="tooltip" style="max-width: auto;" title="' . $value['home_address'] . '">
                                                ' . $value['home_address'] . '
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>';
            }
            echo '</div';
        } else {
            echo '<div class="col-md-12 mt-2 px-4"><h5>No results found!</h5></div>';
        }
    }

    public function tagToRecruitment()
    {
        $input          = $this->input->post(NULL, TRUE);
        $this->db->trans_start();

        $current_status = $input['current_status'];
        $recProcess     = $input['recProcess'];
        $position       = $input['position'];
        $emp_id         = $input['emp_id'];
        $message        = '';
        $tagging        = false;
        if ($current_status == 'blacklisted' && $recProcess != 'deployment') {
            $message = 'Cannot process request! Applicant/Employee is ' . $current_status . '!';
        } else if ($current_status == 'Active' && $recProcess != 'deployment') {
            $message = 'Cannot process request! Applicant/Employee is ' . $current_status . '!';
        } else {

            $condition      = array('app_id' => $emp_id);
            $applicant      = $this->promo_model->selectAll_tcR('applicant', $condition);
            $chk_appDetails = $this->promo_model->selectAll_tcR('application_details', $condition);
            $app_details    = '';

            if ($recProcess == 'exam') {
                $set            = array('status' => 'initialreq completed', 'position' => $position);
                $app_details    = 'Examination';
                $value          = 'initialreq completed';
                $message        = 'Applicant is now in examination process.';
            } else if ($recProcess == 'interview') {
                $set            = array('status' => 'for interview', 'position' => $position);
                $app_details    = 'Interview';
                $value          = 'for interview';
                $message        = 'Applicant is now in interview process.';
            } else if ($recProcess == 'training') {
                $set            = array('status' => 'for training', 'position' => $position);
                $app_details    = 'Training';
                $value          = 'for training';
                $message        = 'Applicant is now in training process.';
            } else if ($recProcess == 'final_completion') {
                $set            = array('status' => 'for final completion', 'position' => $position);
                $app_details    = 'Final Completion';
                $value          = 'for final completion';
                $message        = 'Applicant is now in final completion process.';
            } else if ($recProcess == 'orientation') {
                $set            = array('status' => 'for orientation', 'position' => $position);
                $app_details    = 'Orientation';
                $value          = 'for orientation';
                $message        = 'Applicant is now in orientation process.';
            } else if ($recProcess == 'hiring') {
                $set            = array('status' => 'for hiring', 'position' => $position);
                $app_details    = 'Hiring';
                $value          = 'for hiring';
                $message        = 'Applicant is now in hiring process.';
            } else {
                $app_details    = 'Initial Completion';
                $value          = 'Initial Completion';
                $message        = 'Applicant may process his/her initial requirements.';
                $this->promo_model->delete_tcr_row('applicants', array('app_code' => $applicant['appcode']));
                $this->promo_model->update_twdA('applicant', $condition, array('appcode' => '0'));
            }

            $applicants = $this->promo_model->selectAll_tcR('applicants', array('app_code' => $applicant['appcode']));
            if (count($applicant) > 0 && count($applicants) > 0) {
                $this->promo_model->update_twdA('applicants', array('app_code' => $applicant['appcode']), $set);
            } else {

                $insert = array(
                    'lastname'      => $applicant['lastname'],
                    'firstname'     => $applicant['firstname'],
                    'middlename'    => $applicant['middlename'],
                    'position'      => $position,
                    'status'        => $value,
                    'date_time'     => date('Y-m-d'),
                    'suffix'        => $applicant['suffix'],
                    'entry_by'      => $this->systemUser,
                    'hr_location'   => 'asc',
                );
                $id = $this->promo_model->insert_tdA('applicants', $insert);
                $this->promo_model->update_twdA('applicant', $condition, array('appcode' => $id));
            }

            if (count($chk_appDetails) > 0) {
                $update = array(
                    'application_status'    => $app_details,
                    'position_applied'      => $position,
                    'updatedby'             => $this->systemUser,
                    'date_updated'          => date('Y-m-d'),
                );
                $tag = $this->promo_model->update_twdA('application_details', $condition, $update);
            } else {
                $update = array(
                    'app_id'                => $emp_id,
                    'date_applied'          => date('Y-m-d'),
                    'application_status'    => $app_details,
                    'position_applied'      => $position,
                    'updatedby'             => $this->systemUser,
                    'date_updated'          => date('Y-m-d'),
                );
                $tag = $this->promo_model->insert_tdA('application_details', $insert);
            }
            if ($tag) {
                $tagging = true;
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {

            echo json_encode(['message' => 'failed']);
        } else {

            echo json_encode(['message' => 'success', 'tagging' => $tagging, 'report' => $message]);
        }
    }
}
