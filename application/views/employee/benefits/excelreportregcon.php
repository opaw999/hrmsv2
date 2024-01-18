<?php


$filename ='reg&cas_report';
header("Cache-Control: public");
header("Content-Type: application/octet-stream");
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-disposition: attachment; filename=".$filename.".xls");

/* $search   = @mysql_escape_string(trim($_GET['search']));
 /********************************************************************************/
/*$etype   = @$_GET['etype'];
if($etype == 'regular'){ $et = "and emp_type = 'Regular' ";}
else if($etype == 'contractual'){ $et = "and emp_type = 'Contractual' ";	}
else { $et = "and (emp_type = 'Contractual' || emp_type = 'Regular')";  }

/****************************** COMPANY CODE ************************************/
/* $code	= @$_GET['code'];
 $ec	 	= explode("/",$code);
 $cc	   	= @$ec[0];
 $bc		= @$ec[1];
 $dc		= @$ec[2];
 $sc		= @$ec[3];
 $ssc	= @$ec[4];
 $uc		= @$ec[5];
 /********************************************************************************/

//$loc		= '';
// mysql_query("SET NAMES utf8");
/*if($cc != '')
{
    if($ssc !=''){	@$loc = "and company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code = '$sc' and sub_section_code = '$ssc' "; }
    else if($sc !=''){	@$loc = "and company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code = '$sc' ";  }
    else if($dc !=''){	@$loc = "and company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' ";  }
    else if($bc !=''){  @$loc = "and company_code = '$cc' and bunit_code = '$bc' "; }
    else if($cc !=''){  @$loc = "and company_code = '$cc'"; }
}

$condition = "where current_status = 'active' $et $loc ";
//$query = mysql_query("SELECT $fields from employee3 $condition order by emp_type, name ") or die(mysql_error());
*/
$query = $this->db->query("SELECT lastname, firstname, middlename, birthdate, home_address, emp_type, company_code, bunit_code, dept_code,section_code
		FROM employee3 
		INNER JOIN applicant
		ON employee3.emp_id = applicant.app_id 
		WHERE 
			current_status = 'Active' and (emp_type = 'Regular' or emp_type = 'Contractual') and
			(company_code = '02' AND bunit_code = '03') OR
			(company_code = '03' AND bunit_code = '01') OR
			(company_code = '02' AND bunit_code = '01') ");

echo "
	<br><BR><BR>
	<CENTER><b>As of ".date('F d, Y')."</b> </CENTER><BR>
	<table border='1'>                	
		<tr>
			<th width='30'>NO</th> 		
			<th width='150'>LASTNAME</th>			
			<th width='150'>FIRSTNAME</th>
			<th width='150'>MIDDLENAME</th>							
			<th width='80'>BIRTHDAY</th>
			<th width='350'>HOME ADDRESS</th>
			<th width='100'>EMPTYPE</th>	
			<th width='150'>BUSINESS UNIT</th>
			<th width='200'>DEPARTMENT</th>							
		</tr>";

$ctr =0;
 foreach($query->result_array() as $row) {
   
    $ctr++;

    if ($row['birthdate'] == '0000-00-00') {
        $bd = '';
    }elseif($row['birthdate'] == ''){
        $bd = '';
    }else{
        $bd = date("m/d/Y", strtotime($row['birthdate']));
    }
     


    //02.03 except 02.03.14
    //03.01 except 03.01.09
    //02.01 except 02.01.09
    if(($row['company_code'] == '02' && $row['bunit_code'] == '03' && $row['dept_code'] == '14') ||
        ($row['company_code'] == '03' && $row['bunit_code'] == '01' && $row['dept_code'] == '09') ||
        ($row['company_code'] == '02' && $row['bunit_code'] == '01' && $row['dept_code'] == '09')) {

    } else {        
    $bunit= $this->dbmodel
                ->get_row(
                    'locate_business_unit',
                    'business_unit',
                    array( 'field1' => 'company_code',
                    'field2' =>'bunit_code'),
                    array($row['company_code'],$row['bunit_code'])
                );

    $dept = $this->dbmodel
        ->get_row(
            'locate_department',
            'dept_name',
            array( 'field1' => 'company_code',
            'field2' => 'bunit_code',
            'filed3' =>'dept_code'),
            array($row['company_code'],$row['bunit_code'],$row['dept_code'])
        );
    //  var_dump($dept);
        
    // $sec = $this->dbmodel
    //         ->get_row(
    //             'locate_section',
    //             'section_name',
    //             array( 'field1' => 'company_code',
    //             'field2' => 'bunit_code',
    //             'filed3' =>'dept_code'),
    //             array($row['company_code'],$row['bunit_code'],$row['dept_code'],$row['section_code'])
    //     );

        // $bun = $nq->getBusinessUnitName($row['bunit_code'], $row['company_code']);
        // $dep	= $nq->getDepartmentName($row['dept_code'], $row['bunit_code'], $row['company_code']);
        // $sec	= $nq->getSectionName($row['section_code'],$row['dept_code'],$row['bunit_code'],$row['company_code']);

        echo "<tr>";
        echo "<td>$ctr</td>           
				<td>".mb_convert_encoding(strtoupper($row['lastname']), 'UCS-2LE', 'UTF-8')."</td>			
				<td>".mb_convert_encoding(strtoupper($row['firstname']), 'UCS-2LE', 'UTF-8')."</td>		
				<td>".mb_convert_encoding(strtoupper($row['middlename']), 'UCS-2LE', 'UTF-8')."</td>					
				<td>".$bd."</td>
				<td>".mb_convert_encoding(strtoupper($row['home_address']), 'UCS-2LE', 'UTF-8')."</td>				
				<td>".$row['emp_type']."</td>	
				<td>".@$bunit->business_unit."</td>
				<td>".@$dept->dept_name."</td>
				</tr>";
    }
}
echo "</table>";
?>					      		