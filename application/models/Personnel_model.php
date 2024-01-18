<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Promo_model extends CI_Model
{
    public $db;
    public $tk;
    // public $tk_tal;
    // public $tk_tub;
    public $usertype;
    public $systemUser;
    public $today;
    public $emp3Fields;


    function __construct()
    {
        parent::__construct();
        $this->db           = $this->load->database('default', TRUE);
        $this->tk           = $this->load->database('timekeeping', TRUE);
        // $this->tk_tal       = $this->load->database('talibon', TRUE);
        // $this->tk_tub       = $this->load->database('tubigon', TRUE);
        $this->systemUser   = $this->session->userdata('emp_id');
        $this->usertype     = $this->get_usertype();
        $this->today        = date('Y-m-d');
        $this->emp3Fields   = array(
            'record_no', 'tag_as', 'date_added', 'added_by', 'Details', 'DepCode',
            'Branch', 'Branchcode', 'tag_request', 'sub_status'
        );
    }

    public function record_count_applicant()
		{
			$query = $this->db->from('applicants')
				->where("(status = 'tagged') AND (tagged_to = 'nesco')")
				->order_by('app_code', 'ASC')
				->get();
			return $query->num_rows();
		}

    
}