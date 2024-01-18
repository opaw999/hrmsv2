<?php 
  
    $filename ='positionwithemployee'.date('Ymd');	
	header("Cache-Control: public"); 
	header("Content-Type: application/octet-stream");
	header( "Content-Type: application/vnd.ms-excel; charset=utf-8" );
	header( "Content-disposition: attachment; filename=".$filename.".xls");

	$blq = $this->db->query("SELECT emp_id, name, position, company_code, bunit_code, dept_code, section_code, current_status, emp_type from employee3 WHERE position = '$pos' and current_status = 'Active' and company_code !='07' and coe !='nesco' ORDER BY name");	
	?>

	<CENTER><h4> EMPLOYEE LIST HAVING THE POSITION OF <?= strtoupper($pos) ?></h4></CENTER>
	<table border="1">	
		<tr>
			<td>EMPID</td>
			<td>NAME</td>		
			<td width="15%">POSITION</td>
			<td>EMPTYPE</td>					
			<td>BUSINESS UNIT</td>		
			<td>DEPARTMENT</td>
			<td>SECTION</td>
		</tr> 
		<?php
        foreach ($blq->result_array() as $row)
		{			

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
                'field3' => 'dept_code'),
                array(@$row['company_code'],@$row['bunit_code'],@$row['dept_code'])
                 );

            $sect = $this->dbmodel
            ->get_row(
                'locate_section',
                'section_name',
                array( 'field1' => 'company_code',
                'field2' => 'bunit_code',
                'field3' => 'dept_code',
                'field4' => 'section_code'),
                array(@$row['company_code'],@$row['bunit_code'],@$row['dept_code'],@$row['section_code'])
                );

			echo 
			"<tr>
				<td>".$row['emp_id']."</td>
				<td>".$row['name']."</td>
				<td>".$row['position']."</td>
				<td>".$row['emp_type']."</td>														
				<td>".@$bunit->business_unit."</td>	
				<td>".@$dept->dept_name."</td>	
				<td>".@$sect->section_name."</td>											
			</tr>"; 		          
		}   ?>
	</table>  
	<?php
?>