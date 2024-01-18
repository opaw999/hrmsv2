<?php

$date = date('Y-m-d');
$datetoday = date('F d Y', strtotime($date));


$filename = 'Active Employee Report'.date('Ymd');
header("Cache-Control: public");
header("Content-Type: application/octet-stream");
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-disposition: attachment; filename=".$filename.".xls");

/****************************** COMPANY CODE ************************************/
$code	= $this->input->get('code');
$ec	 	= explode(".", $code);
$cc	   	= @$ec[0];
$bc		= @$ec[1];
$dc		= @$ec[2];
$sc		= @$ec[3];
/********************************************************************************/
$emptypes= $this->input->get('emptype');

switch($emptypes) {
    case '1': $emptype= " and (emp_type = 'Contractual')";
        break;
    case '2': $emptype= " and (emp_type = 'Regular')";
        break;
    case '3': $emptype= " and (emp_type = 'Back-Up')";
        break;
    case '4': $emptype= " and (emp_type = 'Probitionary')";
        break;
    case '5': $emptype= " and (emp_type = 'Summer Job')";
        break;
    case '6': $emptype= " and (emp_type = 'PTA' or emp_type ='PTP' or emp_type ='Regular Partimer')";
        break;
    case '7': $emptype= " and (emp_type = 'Seasonal')";
        break;
    case '8': $emptype= " and (emp_type = 'Contractual' or emp_type = 'Regular' or emp_type = 'Back-Up' or emp_type = 'Probitionary' or emp_type = 'Summer Job' or emp_type = 'PTA' or emp_type ='PTP' or emp_type ='Regular Partimer' or emp_type = 'Seasonal')";
}



$location="";

if(@$cc) {
    if(@$sc) {
        $location .= " and `company_code` = '$cc' and `bunit_code` = '$bc' and `dept_code` = '$dc' and `section_code` = '$sc'";
    } elseif(@$dc) {
        $location .= " and `company_code` = '$cc' and `bunit_code` = '$bc' and `dept_code` = '$dc'";
    } elseif(@$bc) {
        $location .= " and `company_code` = '$cc' and `bunit_code` = '$bc'";
    } else {
        $location .= " and `company_code` = '$cc' ";
    }

}

// $select = $this->db->query("SELECT emp_id, emp_type, firstname,lastname,middlename,suffix,company_code,bunit_code,dept_code FROM employee3 INNER JOIN applicant ON applicant.app_id= employee3.emp_id WHERE  current_status = 'active' $emptype $location order by company_code, bunit_code, dept_code, emp_type");

$select = $this->db->query("SELECT emp_id, emp_type, company_code,bunit_code,dept_code,sss_no,pagibig_tracking, pagibig,philhealth,tin_no FROM employee3  INNER JOIN applicant_otherdetails on employee3.emp_id = applicant_otherdetails.app_id WHERE  current_status = 'active' $emptype $location order by company_code, bunit_code, dept_code, emp_type");


// echo "SELECT emp_id, emp_type, company_code,bunit_code,dept_code,sss_no,pagibig_tracking, pagibig,philhealth,tin_no FROM employee3  INNER JOIN applicant_otherdetails on employee3.emp_id = applicant_otherdetails.app_id WHERE  current_status = 'active' $emptype $location order by company_code, bunit_code, dept_code, emp_type";


// echo "SELECT emp_id,emp_type,company_code,bunit_code,dept_code FROM employee3 WHERE  current_status = 'active' $emptype $location order by company_code, bunit_code, dept_code, emp_type";


// var_dump($select);


    
    

?>
<center><h2>Active Employees Report as of <?php echo $datetoday; ?></h2> </center>

<table border='1'>                	
	<tr>
		<th>NO</th> 				
		<th>LASTNAME</th>
		<th>FIRSTNAME</th>
		<th>MIDDLENAME</th>	
		<th>SUFFIX</th>	
		<th>BIRTHDATE</th>	
		<th>GENDER</th>		
		<th>HOME ADDRESS</th>		
		<th>EMPTYPE</th>									
		<th>BU/DEPT</th>	
        <th>SSS</th>
        <th>PAGIBIGRTN</th>
        <th>PAGIBIGNO</th>
        <th>PHILHEALTH</th>
        <th>TIN#</th>
	</tr>
  <?php
    $ctr =0;

foreach($select->result_array() as $row) 
{
    $sql = $this->db->query(
        "SELECT `lastname`,`firstname`,`middlename`,`suffix`
				 FROM `applicant`
				 WHERE `app_id` = '".$row['emp_id']."' limit 1"
    );
    $res = $sql->row_array();
    // var_dump($res['lastname']);
    $ctr++;

    $bdays= $this->dbmodel->get_field("birthdate", "applicant", "app_id = '$row[emp_id]' limit 1 ");
    $gender= $this->dbmodel->get_field("gender", "applicant", "app_id = '$row[emp_id]' limit 1 ");
    $hmadd= $this->dbmodel->get_field("home_address", "applicant", "app_id = '$row[emp_id]' limit 1 ");
    
        if ($bdays) {
            $bdate = date("m/d/Y", strtotime($bdays['birthdate']));
        } else {
            $bdate = '';
        }

    echo "<tr>";
    echo "<td>$ctr </td>           
			<td>".mb_convert_encoding(ucwords(strtolower(@$res['lastname'])), 'UCS-2LE', 'UTF-8')."</td>
			<td>".mb_convert_encoding(ucwords(strtolower(@$res['firstname'])), 'UCS-2LE', 'UTF-8')."</td>
			<td>".mb_convert_encoding(ucwords(strtolower(@$res['middlename'])), 'UCS-2LE', 'UTF-8')."</td>	
			<td>".mb_convert_encoding(ucwords(strtolower(@$res['suffix'])), 'UCS-2LE', 'UTF-8')."</td>	
			<td>".$bdate."</td>		
			<td>".$gender['gender']."</td>	
			<td>".mb_convert_encoding(ucwords(strtolower($hmadd['home_address'])), 'UCS-2LE', 'UTF-8')."</td>	
			<td>".$row['emp_type']."</td>	
			<td>".$bunit->business_unit."-".$dept->dept_name ."</td>	
            <td>".@$row['sss_no']."</td>	
            <td>".@$row['pagibig_tracking']."</td>	
            <td>".@$row['pagibig']."</td>	
            <td>".@$row['philhealth']."</td>	
            <td>".@$row['tin_no']."</td>	
			</tr>";
}
echo "</table>";
?>   