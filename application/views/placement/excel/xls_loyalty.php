<?php

$filename = "LOYALTY AWARDEES " . date('Ymd');
header("Cache-Control: public");
header("Content-Type: application/octet-stream");
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-disposition: attachment; filename=" . $filename . ".xls");

$columns 	= array('NO','NAME','POSITION','LVL','EMPTYPE','STATUS','DEPT','DATE HIRED','YRS IN<br>SERVICE','YEAR<br>AWARDED');

echo "<b> LOYALTY AWARDEES WITH $yrsinservice YEARS OF SERVICE FROM $yrfrom to $yrto  </b> <br>
	Date Generated: " . date('m/d/Y') . " <br><br>

	<table border='1'> <tr>";
foreach($columns as $key => $value) {
    echo "<td> <center> <b> $value </b> </center> </td>";
}
echo "</tr>";
$ctr = 0;
foreach($loyalreport as $row) {
    $cc     = $row['company_code'];
    $bc     = $row['bunit_code'];
    $dc     = $row['dept_code'];
    $sc     = $row['section_code'];
   
    $datehired = $this->dbmodel->get_field("date_hired", "application_details", "app_id = '$row[emp_id]'")['date_hired'];
    $comp = $this->dbmodel->get_field("*", "locate_company", "company_code = '$cc'")['acroname'];
    $bunit = $this->dbmodel->get_field("*", "locate_business_unit", "company_code = '$cc' AND bunit_code = '$bc'")['acroname'];
    $dept =  $this->dbmodel->get_field("*", "locate_department", "company_code = '$cc' AND bunit_code = '$bc' AND dept_code = '$dc'")['acroname'];

    $ctr++;
    echo "<tr>
				<td> $ctr </td>
				<td> " . ucwords(strtolower($row['name'])) . " </td>
				<td> $row[position] </td>
				<td> <center> $row[poslevel] </center> </td>
				<td> $row[emp_type] </td>
				<td> $row[current_status] </td>
				<td> ".$comp." ".$bunit." ".$dept." </td>
				<td> $datehired </td>
				<td> <center> $row[yrsinservice] </center></td>
				<td> <center> $row[year] </center></td>
			</tr>";
}
echo "
	</table>";
