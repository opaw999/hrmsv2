<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Fgt_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        
        $this->datetime     = date('Y-m-d H:i:s');
        $this->fgtdate      = (date('Y') + 1)."-01-01";
        $this->fgtloginid      = $_SESSION['emp_id'];



    }


      public function makeQuery($query){						
        return @mysql_query($query);
    }
    
    public function fetchArray($result){						
        return @mysql_fetch_array($result);
    }
    
    public function fgtdate(){
        return $this->fgtdate;
    }
    
    public function get_prizes()
    {
        $this->db->where('fgt_date', date('Y') . '-01-01');
        $query = $this->db->get('fgt_prizes');
        return $query->result();
    }



      
    public function other_emptype()
    {
        $emptype = array(
            "NESCO Contractual",
            "NESCO Regular",
            "NESCO-PTA",
            "NESCO-PTP",
            "NESCO Partimer",
            "NESCO Regular Partimer",
            "NESCO Probationary",
            "NICO",
            "CYDEM",
            "NEMPEX",
            "ENTECH",
            "Promo",
            "Promo-NESCO"
        );
        return $emptype;
    }

    

    
    public function get_details($empid)

     {
        $this->db->select('civilstatus, spouse, emp_type, name, position, emp_id');
        $this->db->from('employee3');
        $this->db->join('applicant', 'employee3.emp_id = applicant.app_id');
        $this->db->where('emp_id', $empid);
        $query = $this->db->get();
        return $query->row_array(); // Use row_array() to get a single row result
    }
    
    
     public function get_spouse($empid) {

        $this->db->select('*');
        $this->db->from('spouse_info');
        $this->db->where('empId', $empid);
        $query = $this->db->get();
        $fetch = $query->row_array();

        if (!empty($fetch)) {
            $spouseId = $fetch['spouse_empId'];
            $spousename = $fetch['spouse'];

            if ($spouseId != '' || $spousename != '') {
                $this->db->select('name, current_status');
                $this->db->from('employee3');
                $this->db->where('emp_id', $spouseId);
                $query1 = $this->db->get();
                $fetch1 = $query1->row_array();

                if (!empty($fetch1)) {
                    if ($fetch1['current_status'] == 'Active') {
                        return strtoupper($fetch1['name']) . " <i>(Employee)</i>";
                    } else {
                        return strtoupper($fetch1['name']) . " <i>(Non-Employee)</i>";
                    }
                } else {
                    return strtoupper($fetch['spouse']) . " <i>(Non-Employee)</i>";
                }
            } else {
                return "";
            }
        } else {
            return "";
        }
    }

    
     public function get_spouse_type($empid) {

        $this->db->select('*');
        $this->db->from('spouse_info');
        $this->db->where('empId', $empid);
        $query = $this->db->get();
        $fetch = $query->row_array();

        if (!empty($fetch)) {
            $spouseId = $fetch['spouse_empId'];
            $spousename = $fetch['spouse'];

            if ($spouseId != '' || $spousename != '') {
                $this->db->select('name');
                $this->db->from('employee3');
                $this->db->where('emp_id', $spouseId);
                $this->db->where('current_status', 'Active');
                $query1 = $this->db->get();
                $fetch1 = $query1->row_array();

                if (!empty($fetch1)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    public function get_spouse_id($empid) {

        $this->db->select('spouseId');
        $this->db->from('spouse_info');
        $this->db->where('empId', $empid);
        $query = $this->db->get();
        $fetch = $query->row_array();
        return isset($fetch['spouseId']) ? $fetch['spouseId'] : null;
    }
    
    public function get_spouse_for_insert($empid) {
        $this->db->select('*');
        $this->db->from('spouse_info');
        $this->db->where('empId', $empid);
        $query = $this->db->get();
        $fetch = $query->row_array();

        if (!empty($fetch)) {
            $spouseId = $fetch['spouse_empId'];
            $spousename = $fetch['spouse'];

            if ($spouseId != '' || $spousename != '') {
                $this->db->select('emp_id, name');
                $this->db->from('employee3');
                $this->db->where('emp_id', $spouseId);
                $query1 = $this->db->get();
                $fetch1 = $query1->row_array();

                if (!empty($fetch1)) {
                    if ($fetch1['current_status'] == 'Active') {
                        return $fetch1['emp_id'] . "*" . $fetch1['name'];
                    } else {
                        return "*" . $fetch1['name'];
                    }
                } else {
                    return "*" . $fetch['spouse'];
                }
            } else {
                return "";
            }
        } else {
            return "";
        }
    }
    
    public function get_children($id) {
        $this->db->select('*');
        $this->db->from('children_info');
        $this->db->where('spouseId', $id);
        $query = $this->db->get();
        return $query->result_array(); // Use result_array() to get multiple rows
    }

    
     public function calculate_age($dob) {
        $age = 0;
        $dob_timestamp = strtotime($dob);
        $current_timestamp = strtotime(date('Y-m-d'));

        while ($current_timestamp >= $dob_timestamp = strtotime('+1 year', $dob_timestamp)) {
            ++$age;
        }

        return $age;
    }
    
    
    
      public function check_valid_emp($empid) 
      
      {
        $this->db->select('count(emp_id) as x');
        $this->db->from('employee3');
        $this->db->where('emp_id', $empid);
        $this->db->where('current_status', 'Active');
        $this->db->where_not_in('emp_type', array('OJT', 'Back-Up', 'Seasonal', 'NESCO-BACKUP', 'NESCO Partimer', 'Promo', 'Promo-NESCO', 'NICO', 'ENTECH', 'NEMPEX', 'CYDEM'));
        $query = $this->db->get();
        $fetch = $query->row_array();

        return $fetch['x'] == 0 ? FALSE : TRUE;
    }
    
    public function explode_emp_id($empid) {
        $empid_array = explode("*", $empid);
        $empid_trimmed = trim($empid_array[0]);
        return $empid_trimmed;
    }
    
    public function get_confirm_employee($empid) {
        $this->db->select('*');
        $this->db->from('fgt_confirm_employee');
        $this->db->where('empid', $empid);
        $this->db->where('fgtdate', $this->fgtdate()); // Assuming fgtdate() is a function to get the current date
        $query = $this->db->get();
        return $query->row_array(); // Use row_array() to get a single row result
    }
    
   public function get_confirm_spouse($confirmno) 
   {
        $this->db->select('*');
        $this->db->from('fgt_confirm_spouse');
        $this->db->where('confirmno1', $confirmno);
        $query = $this->db->get();
        return $query->row_array(); // Use row_array() to get a single row result
    }
    
    public function get_confirm_children($confirmno, $cname) {
        $this->db->select('*');
        $this->db->from('fgt_confirm_children');
        $this->db->where('confirmno1', $confirmno);
        $this->db->where('children_name', $cname);
        $query = $this->db->get();
        return $query->row_array(); // Use row_array() to get a single row result
    }
    
   public function check_if_confirm_by_husband($empid, $confirmno) {
        $this->db->select('*');
        $this->db->from('fgt_confirm_spouse');
        $this->db->where("(spouse LIKE '$empid%' OR spouse_id LIKE '$empid%')");
        $this->db->where("dateconfirmed LIKE '".date('Y')."%'"); // Assuming dateconfirmed is the field name for the confirmation date
        // $this->db->where('confirmno1', $confirmno); // You might want to include this condition if needed
        $query = $this->db->get();
        return $query->row_array(); // Use row_array() to get a single row result
    }
    
      public function count_if_confirmed_by($empid)
       {
        $this->db->select('count(spouse) as counts');
        $this->db->from('fgt_confirm_spouse');
        $this->db->where("(spouse LIKE '$empid%' OR spouse_id LIKE '$empid%')");
        $this->db->where("dateconfirmed LIKE '".date('Y')."%'"); // Assuming dateconfirmed is the field name for the confirmation date
        $query = $this->db->get();
        $fetch = $query->row_array();
        return isset($fetch['counts']) ? $fetch['counts'] : 0;
    }
    
    public function get_confirmation_details($cno) 
    {
            $this->db->select('*');
            $this->db->from('fgt_confirm_employee');
            $this->db->where('confirmno1', $cno);
            $query = $this->db->get();
            return $query->row_array(); // Use row_array() to get a single row result
    }
        
    
    public function check_if_inserted_already() 
    {
        $this->db->select('*');
        $this->db->from('fgt_confirm_employee');
        $this->db->where('empid', $this->fgtloginid); // Assuming $this->loginid is a property of the model
        $this->db->where('fgtdate', $this->fgtdate); // Assuming $this->fgtdate is a property of the model
        $query = $this->db->get();
        $fetch = $query->row_array();
        return isset($fetch['confirmno1']) ? $fetch['confirmno1'] : NULL;
    }
    
    
    public function insert_fgt($empid, $confirm, $tshirt) {
        $data = array(
            'empid' => $empid,
            'confirmed' => $confirm,
            'dateconfirmed' => $this->datetime, // Assuming $this->dateadded is a property of the model
            'tshirt' => $tshirt,
            'fgtdate' => $this->fgtdate, // Assuming $this->fgtdate is a property of the model
            'confirmedby' => $this->fgtloginid // Assuming $this->loginid is a property of the model
        );

        $this->db->insert('fgt_confirm_employee', $data);
        return $this->db->insert_id();
    }
    
    
    public function update_fgt($confirm, $tshirt, $cno) {
        $data = array(
            'confirmed' => $confirm,
            'tshirt' => $tshirt,
            'dateconfirmed' => $this->datetime // Assuming $this->dateadded is a property of the model
        );

        $this->db->where('confirmno1', $cno);
        $this->db->update('fgt_confirm_employee', $data);
        return $this->db->affected_rows();
    }
    
    
    //CHECKING IF SPOUSE ALREADY EXIST
   public function check_if_spouse_inserted_already($confirmno)
    {
        $this->db->select('*');
        $this->db->from('fgt_confirm_spouse');
        $this->db->where('confirmno1', $confirmno);
        $query = $this->db->get();
        $fetch = $query->row_array();
        return isset($fetch['confirmno1']) ? $fetch['confirmno1'] : NULL;
    }
    
    //INSERTING SPOUSE
    public function insert_spouse($confirmno, $spouse, $spouseid, $tshirt, $confirm, $confirmby) {
        $data = array(
            'confirmno1' => $confirmno,
            'spouse' => $spouse,
            'spouse_id' => $spouseid,
            'tshirt' => $tshirt,
            'confirmed' => $confirm,
            'dateconfirmed' => $this->datetime, // Assuming $this->dateadded is a property of the model
            'confirmedby' => $confirmby
        );

        $this->db->insert('fgt_confirm_spouse', $data);
        return $this->db->insert_id();
    }
    
    //UPDATE SPOUSE DETAILS
    public function update_spouse($confirm, $tshirt, $cno) {
        $data = array(
            'confirmed' => $confirm,
            'tshirt' => $tshirt,
            'dateconfirmed' => $this->datetime // Assuming $this->dateadded is a property of the model
        );

        $this->db->where('confirmno1', $cno);
        $this->db->update('fgt_confirm_spouse', $data);
        return $this->db->affected_rows();
    }
    
    
    //CHECKING IF CHILDREN ALREADY EXIST
  public function check_if_children_inserted_already($confirmno) {
        $this->db->select('*');
        $this->db->from('fgt_confirm_children');
        $this->db->where('confirmno1', $confirmno);
        $query = $this->db->get();
        $fetch = $query->row_array();
        return isset($fetch['confirmno1']) ? $fetch['confirmno1'] : NULL;
    }
    
    
    //INSERTING CHILDREN
   public function insert_children($confirmno, $child, $confirm, $confirmby) {
        $data = array(
            'confirmno1' => $confirmno,
            'children_name' => $child,
            'confirmed' => $confirm,
            'dateconfirmed' => $this->datetime, // Assuming $this->dateadded is a property of the model
            'confirmedby' => $confirmby
        );

        $this->db->insert('fgt_confirm_children', $data);
        return $this->db->insert_id();
    }
    
    //UPDATING CHILDREN
     public function update_children($confirm, $cno, $confirmno, $empid) {
        $data = array(
            'confirmed' => $confirm,
            'confirmno1' => $confirmno,
            'dateconfirmed' => $this->datetime, // Assuming $this->datetime is a property of the model
            'confirmedby' => $empid
        );

        $this->db->where('cconfirmno', $cno);
        $this->db->update('fgt_confirm_children', $data);
        return $this->db->affected_rows();
    }
    


     public function get_field($field, $table, $where) {
        $this->db->select($field);
        $this->db->from($table);
        $this->db->where($where);
        $query = $this->db->get();
        return $query->row_array();
    }

        public function writeLogs($log, $logDir, $filename) {
        $filename = $filename . date("Ymd") . ".txt";
        $file = $logDir . "/" . $filename;

        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true); // Create directory recursively
        }

        if (file_exists($file)) {
            if (is_writable($file)) {
                if (!$handle = fopen($file, 'a')) {
                    return false; // Return false on error
                }
                if (fwrite($handle, $log) === FALSE) {
                    fclose($handle);
                    return false; // Return false on error
                }
                fclose($handle);
                return true; // Return true on success
            } else {
                return false; // Return false if file is not writable
            }
        } else {
            if ($handle = fopen($file, "w")) {
                if (is_writable($file)) {
                    if (!$handle = fopen($file, 'a')) {
                        return false; // Return false on error
                    }
                    if (fwrite($handle, $log) === FALSE) {
                        fclose($handle);
                        return false; // Return false on error
                    }
                    fclose($handle);
                    return true; // Return true on success
                } else {
                    return false; // Return false if file is not writable
                }
            } else {
                return false; // Return false if unable to create file
            }
        }
    }



}

