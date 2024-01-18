<?php
	header("Cache-Control: public"); 
	header("Content-Type: application/octet-stream");
	header( "Content-Type: application/vnd.ms-excel; charset=utf-8" );
	header( "Content-disposition: attachment; filename=".$filename.".xls");
?>
	<style type="text/css">
		table, tr, th, td { border: 1px dotted #ccc; font-size:12px; }
	</style>

	<div style="font-size:24px;font-weight:bold"> <?= $title;?> </div>
	Date Generated: <?= date('M d, Y');?> <br><br>

    <table border='1' style="font-size:12px">
        <tr style='height:40px;text-align:center;vertical-align:middle'>
            <th> NO </th>						
            <th> NAME </th>						
            <th> POSITION </th>
            <th> EMPTYPE </th>
            <th> DEPARTMENT </th>
            <th> DATE HIRED </th>
            <th> YRS IN SERVICE </th>
        </tr>
        <?php
        $ctr = 0;
        foreach($results as $row):
            $ctr++;
           
            echo "
            <tr>
                <td> $ctr </td>						
                <td> ".ucwords(strtolower(utf8_decode($row['name'])))." </td>						
                <td> $row[position] </td>
                <td> $row[emptype] </td>
                <td> $row[department] $row[section] </td>
                <td align='right'> $row[datehired] </td>
                <td align='right'> $row[yrsinservice] </td>
            </tr>";           						
        endforeach; ?>
    </table>			