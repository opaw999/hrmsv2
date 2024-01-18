<?php

$filename = 'allemployee_excelreport_"' . date("F m, Y") . '"';
header("Cache-Control: public");
header("Content-Type: application/octet-stream");
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-disposition: attachment; filename=" . $filename . ".xls");



/****************************** COMPANY CODE ************************************/

$emptype = $this->input->get('emptype');


if($emptype != '') {
    switch($emptype) {
        case 'All': $ttype = "and (emp_type='Regular Partimer' or emp_type='Partimer' or emp_type='PTA' or emp_type='PTP'  or emp_type='Contractual' or emp_type='Regular' or emp_type='NESCO' or emp_type ='NESCO-PTA' or emp_type='NESCO-PTP' or emp_type='NESCO Regular' or emp_type='NESCO Contractual' or emp_type='NESCO Partimer' or emp_type='NESCO Regular Partimer' or emp_type='NESCO Probationary' or emp_type='Probationary')";
            break;
        case 'Partimer':	$ttype = "and (emp_type='Regular Partimer' or emp_type='Partimer' or emp_type='PTA' or emp_type='PTP')";
            break;
        case 'NESCO':	$ttype = "and (emp_type='NESCO' or emp_type ='NESCO-PTA' or emp_type='NESCO-PTP' or emp_type='NESCO Regular' or emp_type='NESCO Contractual' or emp_type='NESCO Partimer' or emp_type='NESCO Regular Partimer' or emp_type='NESCO Probationary')";
            break;
        default: 	$ttype = "and emp_type='" . $emptype . "'";
            break;
    }
} else {
    $ttype = '';
}


$code	= $this->input->get('xcode');

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
    if($sc != '') {
        $loc = "and company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' and section_code = '$sc' ";
    } elseif($dc != '') {
        $loc = "and company_code = '$cc' and bunit_code = '$bc' and dept_code = '$dc' ";
    } elseif($bc != '') {
        $loc = "and company_code = '$cc' and bunit_code = '$bc' ";
    } elseif($cc != '') {
        $loc = "and company_code = '$cc'";
    }

}



$datas = $this->dbmodel->get_all_data(
    'employee3',   //table
    '*',    // field selected
    "ASC", // order by ASC or DESC
    "name", // order by field
    null, // per page
    null,  // start of the page
    null,
    "current_status = 'Active' AND company_code != '07'  
                                    $ttype  $loc"
);



                    echo "
                        <br><BR><BR>
                        <CENTER><b>Report for all employees per Business Unit  as of  " . date("F m, Y") . "</b> </CENTER><BR>
                        <table border='1'>                	
                            <tr>
                                <th>NO</th> 	
                                <th>EMPID</th> 			
                                <th>NAME</th>
                                <th>BUSINESS UNIT</th>	
                                <th>DEPARTMENT</th>				
                                <th>POSITION</th>
                                <th>EMPTYPE</th>															
                                <th>STARTDATE</th> 								 
                                <th>EOCDATE</th> 
                                <th>STATUS</th> 
                            </tr>";

                    $ctr = 0;
                    foreach($datas as $row) {

                        
                    $bunit = $this->dbmodel
                            ->get_row(
                                'locate_business_unit',
                                'business_unit',
                                array( 'field1' => 'company_code',
                                'field2' => 'bunit_code'),
                                array($row['company_code'],$row['bunit_code'])
                            );

                    $dept = $this->dbmodel
                        ->get_row(
                            'locate_department',
                            'dept_name',
                            array( 'field1' => 'company_code',
                            'field2' => 'bunit_code',
                            'filed3' => 'dept_code'),
                            array(@$row['company_code'],@$row['bunit_code'],@$row['dept_code'])
                        );

                        $ctr++;
    

                     echo "<tr>
                             <td>$ctr</td>           
                            <td>" . $row['emp_id'] . "</td>	
                            <td>" . ucwords(strtolower($row['name'])) . "</td>	
                            <td>" . @$bunit->business_unit . "</td>
                            <td>" . @$dept->dept_name . "</td>
                            <td>" . ucwords(strtolower($row['position'])) . "</td>
                            <td>" . ucwords(strtolower($row['emp_type'])) . "</td>
                            <td>" . $row['startdate'] . "</td>	
                            <td>" . $row['eocdate'] . "</td>									
                            <td>" . $row['current_status'] . "</td>						
                            </tr>";


                    }
                    echo "</table>";

?>						      				