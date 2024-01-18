<?php

$filename ='Newemployee_excelreport';
header("Cache-Control: public");
header("Content-Type: application/octet-stream");
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-disposition: attachment; filename=".$filename.".xls");




// $search   = @mysql_escape_string(trim($_GET['search']));
/********************************************************************************/
//filters only this employee type
$emp_typefilter = "and (emp_type = 'Regular' or emp_type = 'Contractual' or emp_type = 'PTA' or emp_type = 'PTP' or emp_type = 'Seasonal' or emp_type = 'OJT') ";
// /********************************************************************************/
$y2d		= date('Y');
$m 			= date('m');


if(!empty($permonth)) {
    if(strlen($permonth) <2) {
        $querymonth = $y2d."-0".$permonth; // 2015-08
    } else {
        $querymonth = $y2d."-".$permonth; // 2015-08
    }
    $forthemonth= "for the Month of ".strtoupper($this->custom->getmonthname($permonth));
} else {
    $querymonth = $y2d."-".$m;
    $forthemonth= "for the Month of ".strtoupper($this->custom->getmonthname($m));
}
$monthquery = "and startdate like '%$querymonth%' ";


$m_plus		= $m+1;
$m_minus	= $m-1;
$month_plus =$this->custom->getmonthname($m_plus);
$month_m 	= $this->custom->getmonthname($m);
$month_minus= $this->custom->getmonthname($m_minus);

if($m == '1') {
    $y_minus	= $y2d-1;
    $month_minus= $this->custom->getmonthname('12')." ".$y_minus;
    $m_minus	= '12|'.$y_minus;
} elseif($m == '12') {
    $y_plus		= $y2d+1;
    $m_plus		= '1|'.$y_plus;
    $month_plus = $this->custom->getmonthname('1')." ".$y_plus;
}
/****************************** COMPANY CODE ************************************/


    $code	= $this->input->get('code');
    $ec	 	= explode(".", $code);
    $cc	   	= @$ec[0];
    $bc		= @$ec[1];
    $dc		= @$ec[2];
    $sc		= @$ec[3];
    $ssc	= @$ec[4];
    $uc		= @$ec[5];




// /********************************************************************************/
// //	echo "permonth=".$permonth." code=".$code;

    $loc		= '';
    // mysql_query("SET NAMES utf8");

    if($cc != '') {
        if($sc !='') {
            $loc = "and company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code = '$sc' ";
        } elseif($dc !='') {
            $loc = "and company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' ";
        } elseif($bc !='') {
            $loc = "and company_code = '$cc' and bunit_code = '$bc' ";
        } elseif($cc !='') {
            $loc = "and company_code = '$cc'";
        }
        
    }


    $tag_as 	= "new";

    $fields 	= "record_no, payroll_no, name, employee3.emp_id, position, company_code, bunit_code, dept_code,section_code, sub_section_code,emp_type, current_status, startdate, eocdate";
    $tag_as 	= "new";

    
        $condition = "where tag_as = '$tag_as' and current_status = 'active' $monthquery $loc $emp_typefilter";
        $query = $this->db->query("SELECT $fields from employee3 $condition order by name  ");
        $nquery =$this->db->query("SELECT record_no from employee3 $condition ");
        $total_pages = $nquery->num_rows();
   
  
        echo "
	<br><BR><BR>
	<CENTER><b>REPORT FOR THE MONTH OF AUGUST, ".date('Y')."</b> </CENTER><BR>
	<table border='1'>                	
		<tr>
			<th width='30'>NO</th> 			
			<th width='150'>Name</th>
			<th width='260'>BUSINESS UNIT</th>	
			<th width='260'>DEPT/SECTION</th>				
			<th width='160'>POSITION</th>
			<th width='80'>EMP TYPE</th>															
			<th width='100'>SSS NUMBER</th> 								 
			<th width='110'>PHILHEALTH</th> 
			<th width='130'>PAG-IBIG RTN</th> 
			<th width='130'>PAG-IBIG MID NO</th> 
		</tr>";

            $ctr =0;
                    foreach($query->result_array() as $row) {
                        $ctr++;
                        $ben_q = $this->db->query("SELECT * FROM benefits where emp_id = '$row[emp_id]' ");
                        $rb =$ben_q->row_array();

                            echo "<tr>";
                            echo "<td>$ctr</td>           
                            <td>".$row['name']."</td>	
                            <td>".@$bunit->business_unit."</td>
                            <td>".@$dept->dept_name."</td>
                            <td>".ucwords(strtolower($row['position']))."</td>
                            <td>".$row['emp_type']."</td>	
                            <td>".@$rb['sssno']."</td>									
                            <td>".@$rb['philhealth']."</td>									
                            <td>".@$rb['pagibig_tracking']."</td>
                            <td>".@$rb['pagibig']."</td>									
                            </tr>";

                    
                }
                echo "</table>";

?>						      				