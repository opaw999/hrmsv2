<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class DB_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    public function add($table, $data){
        $this->db->insert($table, $data);
        // print_r($this->db->last_query());
        return true;
    }
    
    public function add_with_id($table, $data){
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }
    
    public function delete($table, $where){
        $this->db->delete($table, $where);
        return true;
    }

    public function update($table,$data,$where){
        $this->db->update($table, $data, $where);
        return true;
    }
    
    public function count_data($table, $where = null, $join_table = null, $join_fields = null, $where_field=null,$where_values=null){
        if($where){
            $query = $this->db->get_where($table, $where);
        }else{
            if($join_table){
                $i = 0;
                foreach($join_table as $key => $jtable){
                    $this->db->join($jtable, $join_fields[$i]);
                    $i++;
                }   
            }              
            
            if($where_field){
                $a = 0;
                foreach($where_field as $key => $fields){
                    $this->db->where($fields,$where_values[$a]);
                    $a++;
                } 
            }
            $query = $this->db->get_where($table);
        }
        // print_r($this->db->last_query()); 
        return $query->num_rows();
    }
    
    public function sum_fields($table, $fields, $where = null){
        $this->db->select($fields);
        if($where){
            $query = $this->db->get_where($table, $where);
        }else{
            $query = $this->db->get_where($table);
        }
        return $query->row();
    }
    
    public function get_all_data($table, $fields, $order_by=null, $orderby_field = null, $limit = null, $offset = 0, $group_by = null, $where = null){
        if($order_by){
            $this->db->order_by($orderby_field , $order_by);
        }
        if($limit){
            $this->db->limit($limit , $offset);
        }
        if($where){
            $this->db->where($where);
        }
        if($group_by){
            $this->db->group_by($group_by);
        }
        $this->db->select($fields);
        $query = $this->db->get($table);
        // print_r($this->db->last_query());
        return $query->result_array();
    }
    

    public function get_all_data1($table, $fields, $order_by = null, $orderby_field = null, $limit = null, $offset = 0, $group_by = null, $where = null)
    {
        if ($order_by) {
            $this->db->order_by($orderby_field, $order_by);
        }
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        if ($where) {
            $this->db->where($where);
        }
        if ($group_by) {
            $this->db->group_by($group_by);
        }
        $this->db->select($fields);
        $query = $this->db->get($table);
        // print_r($this->db->last_query());
        return $query->row_array(); // Use row_array() instead of result_array()
    }
    
    public function get_data_by_join($table, $join_table, $join_fields, $select_fields, $where_field=null,$where_values=null, $order_by=null, $orderby_field = null, $limit = null, $offset = null,$where = null){
        $i = 0;
        foreach($join_table as $key => $jtable){
            $this->db->join($jtable, $join_fields[$i]);
            $i++;
        }   
        
        if($where_field){
            $i = 0;
            foreach($where_field as $key => $fields){
                $this->db->where($fields,$where_values[$i]);
                $i++;
            } 
        }
        
        if($order_by){
            $this->db->order_by($orderby_field , $order_by);
        }
        
        
        if($limit){
            $this->db->limit($limit , $offset);
        }
                
        if ($where) {
            $this->db->where($where);
        }

        $this->db->select($select_fields);
        $query = $this->db->get($table);
        // print_r($this->db->last_query());
        return $query->result_array();
    }

    public function get_data_by_join1($table, $join_table, $join_fields, $select_fields, $where_field = null, $where_values = null, $order_by = null, $orderby_field = null, $limit = null, $offset = null)
    {
        
        $num_joins = count($join_table);
        if (count($join_fields) != $num_joins) {
            throw new Exception("Mismatch in the number of join fields and join tables.");
        }

        for ($i = 0; $i < $num_joins; $i++) {
            $this->db->join($join_table[$i], $join_fields[$i]);
        }

        if ($where_field) {
            $i = 0;
            foreach ($where_field as $key => $fields) {
                $this->db->where($fields, $where_values[$i]);
                $i++;
            }
        }

        if ($order_by) {
            $this->db->order_by($orderby_field, $order_by);
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $this->db->select($select_fields);
        $query = $this->db->get($table);
        return $query->result_array();
    }

   
    public function get_row($table,$select_fields,$where_field,$where_values,$join_table=null, $join_fields=null, $order_by=null, $orderby_field = null){
        $i = 0;
        foreach($where_field as $key => $fields){
            $this->db->where($fields,$where_values[$i]);
            $i++;
        }
        if($join_table){
            $i = 0;
            foreach($join_table as $key => $jtable){
                $this->db->join($jtable, $join_fields[$i]);
                $i++;
            }   
        }
        if($order_by){
            $this->db->order_by($orderby_field , $order_by);
        }
        
        $this->db->limit(1);
        
        $this->db->select($select_fields);
        $q = $this->db->get($table);
        // print_r($this->db->last_query());
        if($q->num_rows() > 0)
        {
            return $q->row();
        }
        return false;
    }
    
    public function get_where($table,$select_fields,$where_field,$where_values){
        $i = 0;
        foreach($where_field as $key => $fields){
            $this->db->where($fields,$where_values[$i]);
            $i++;
        }           
        $this->db->select($select_fields);
        $query = $this->db->get($table);
        return $query->result_array();
    }
    
    public function get_where_minmax($table,$select_fields,$where_field,$where_values,$minwhere_field,$minwhere_values,$maxwhere_field,$maxwhere_values){
        $i = 0;
        foreach($where_field as $key => $fields){
            $this->db->where($fields,$where_values[$i]);
            $i++;
        }  
        $i = 0;
        foreach($minwhere_field as $key => $fields1){
            $this->db->where($fields1,$minwhere_values[$i]);
            $i++;
        } 
        $i = 0;
        foreach($maxwhere_field as $key => $fields2){
            $this->db->where($fields2,$maxwhere_values[$i]);
            $i++;
        }           
        $this->db->select($select_fields);
        $query = $this->db->get($table);
        // print_r($this->db->last_query());
        return $query->result_array();
    }
    
    public function get_where_like($table,$select_fields,$where_field,$where_values){
        $i = 0;
        foreach($where_field as $key => $fields){
            $this->db->like($fields,$where_values[$i]);
            $i++;
        }     
        $this->db->select($select_fields);
        $query = $this->db->get($table);
        return $query->result_array();
    }
    
    public function dt_get_where_like(
        $table,
        $select_fields,
        $where_field=null, 
        $where_values=null,
        $where_field1=null, 
        $where_values1=null, 
        $or_where_field=null, 
        $or_where_values=null,
        $order_by=null, 
        $orderby_field = null, 
        $limit = null,
         $offset = null, 
        $join_table=null, 
        $join_fields=null,
         $where_efield=null, 
         $where_evalues=null,
         $where=null){
        $i = 0;
        $this->db->group_start();
        if($where_efield){
            
            foreach($where_efield as $key => $fields){
                $this->db->where($fields,$where_evalues[$i]);
                $i++;
            }
            // $this->db->group_end();
        }
        $i = 0;
        if($where_field){
            // $this->db->or_group_start();
            foreach($where_field as $key => $fields){
                $this->db->like($fields,$where_values[$i]);
                $i++;
            }
            // $this->db->group_end();
        }            
        
        $a = 0;
        if($or_where_field){
            // $this->db->or_group_start();
            foreach($or_where_field as $key => $fields){
                $this->db->or_like($fields,$or_where_values[$a]);
                $a++;
            }
            
        }
        $this->db->group_end();
        if($order_by){
            $this->db->order_by($orderby_field , $order_by);
        } 
        
        if($limit){
            $this->db->limit($limit , $offset);
        }  
        
        if($join_table){
            $i = 0;
            foreach($join_table as $key => $jtable){
                $this->db->join($jtable, $join_fields[$i]);
                $i++;
            }   
        }  
        
        if($where){
            $this->db->where($where);
        }
        
        $this->db->select($select_fields);
        $query = $this->db->get($table);
        // print_r($this->db->last_query());
        return $query->result_array();
    }
    
    public function dt_get_where_count($table,$select_fields,$where_field,$where_values,$or_where_field,$or_where_values,$join_table=null, $join_fields=null){
        $i = 0;
        if($where_field){
            foreach($where_field as $key => $fields){
                $this->db->like($fields,$where_values[$i]);
                $i++;
            }
        }
        
        $a = 0;
        foreach($or_where_field as $key => $fields){
            $this->db->or_like($fields,$or_where_values[$a]);
            $a++;
        }
        
        if($join_table){
            $i = 0;
            foreach($join_table as $key => $jtable){
                $this->db->join($jtable, $join_fields[$i]);
                $i++;
            }   
        }
        $this->db->select($select_fields);
        $query = $this->db->get($table);
        return $query->num_rows();
    }

    public function getallfromtablejoin($fields,$tbl1,$tbl2,$join,$where,$value=null,$order=null,$limit=null,$offset=null)
    {
        $this->db->select($fields);
        $this->db->from($tbl1);
        $this->db->join($tbl2, $join, 'inner');	
        $this->db->where($where);		
        $this->db->order_by($value,$order);
        $this->db->limit($limit , $offset);         
        $query = $this->db->get();
        return $query->result_array();	
    }  

     public function getallfromtablejoinresult($fields,$tbl1,$tbl2,$join,$where)
    {
        $this->db->select($fields);
        $this->db->from($tbl1);
        $this->db->join($tbl2, $join, 'inner');	
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();	
    }     

    public function countfromtablejoin($fields,$tbl1,$tbl2,$join,$where)
    {
        $this->db->select($fields);
        $this->db->from($tbl1);
        $this->db->join($tbl2, $join, 'inner');	
        $this->db->where($where);		        
        $query = $this->db->get();
        return $query->num_rows();	
    }

    public function get_field($field, $table, $where)
    {
        $this->db->select($field);
        $this->db->where($where);
        $query = $this->db->get($table);
        $result = $query->row_array();
        // print_r($this->db->last_query());
        return $result;
    }

     public function get_field_result($field, $table, $where)
    {
        $this->db->select($field);
        $this->db->where($where);
        $query = $this->db->get($table);
        $result = $query->result_array();
        return $result;
    }

    public function get_field_result_order($field, $table, $where, $order, $order_by)
    {
        $this->db->select($field);
        $this->db->where($where);
        $this->db->order_by($order,$order_by);
        $query = $this->db->get($table);
        $result = $query->result_array();
        return $result;
    }

    public function get_count_data($table, $fields = '*', $where = null) {
        if ($where) {
            $this->db->where($where);
        }
        
        $this->db->select($fields);
        $query = $this->db->get($table);

        return $query->row()->counts; // Assuming 'counts' is the alias for the count field in the query
    }

    public function get_field_row($field, $table, $where, $value, $order, $limit, $offset)
    {
        $this->db->select($field);
        $this->db->where($where);
        $this->db->order_by($value,$order);
        $this->db->limit($limit , $offset);  
        $query = $this->db->get($table);
        return $query->row();
    }

    
    function changeDateFormat($changeformatto, $date)
    {
        if ($date != '0000-00-00') {
            $convert_date = new DateTime($date);
            return $convert_date->format($changeformatto);
        } else {
            return '';
        }
    }    
}