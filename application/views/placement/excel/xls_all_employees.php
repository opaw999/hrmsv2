<?php

$filename = 'allemployee_excelreport"' . date("F m, Y") . '"';
header("Cache-Control: public");
header("Content-Type: application/octet-stream");
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-disposition: attachment; filename=" . $filename . ".xls");


$datas = $this->dbmodel->get_all_data(
    'employee3',   //table
    '*',    // field selected
    "ASC", // order by ASC or DESC
    "name", // order by field
    null, // per page
    null,  // start of the page
    null,
    "current_status = 'Active' AND company_code != '07'"
);


echo "
    <br><BR><BR>
    <CENTER><h2>Report for all employees  as of  " . date("F m, Y") . "</h2> </CENTER><BR>
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
                    echo "  <tr>
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
