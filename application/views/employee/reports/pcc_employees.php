<?php
	header("Cache-Control: public"); 
	header("Content-Type: application/octet-stream");
	header( "Content-Type: application/vnd.ms-excel; charset=utf-8" );
	header( "Content-disposition: attachment; filename=".$filename.".xls");
?>
	<style type="text/css">
		table, tr, th, td { border: 1px dotted #ccc; font-size:14px; }
	</style>

	<div style="font-size:24px;font-weight:bold"> <?= $title;?> </div>
	Date Generated: <?= date('M d, Y');?> <br><br>

    <table border='1' style="font-size:14px">
        <tr style='height:40px;text-align:center;vertical-align:middle'>
            <th> EMPID </th>
            <th> PAYROLLNO </th>
            <th> NAME </th>
            <th> EMPTYPE </th>
            <th> POSITION </th>
            <th> BUSINESS UNIT </th>
            <th> DEPARTMENT </th>
            <th> PCC </th>            
        </tr>
        <?php                    
            foreach($results as $row){
                echo "
                <tr>
                    <td> ". $row['emp_id'] ." </td>
                    <td> <center> $row[payroll_no] </center> </td>
                    <td> ".ucwords(strtolower(utf8_decode($row['name'])))."</td>
                    <td> $row[emptype] </td>
                    <td> $row[position] </td>
                    <td> $row[businessunit] </td>
                    <td> $row[department] </td>
                    <td> $row[pcc] </td>
                </tr>";
            }
        ?>  
    </table>			