<?php
   $currentDateTime = date('F j, Y g:i:s A');


		$filename = 'alllicensedHolder_report';
		header("Cache-Control: public");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-disposition: attachment; filename=".$filename.".xls");

	echo"
     <center>
        <h1>ALL LICENSED HOLDER </h1>
		<h4>Date Generated: $currentDateTime</h4>
    </center>
    <table class='table table-striped table-bordered' border = '1'>
        <thead>
        <tr>
            <td> EMPID </td>
            <td> NAME</td>
            <td> POSITION </td>                                        
            <td> BUSINESS&nbsp;UNIT </td>                                        
            <td> DEPARTMENT </td>
			<td> LICENSED </td>                                        
        </tr>
        </thead>
        <tbody>";

        
        foreach ($datas as $list) {

            $bunit = $this->dbmodel->get_field(
                "acroname, business_unit",
                "locate_business_unit",
                "company_code = '" . $list['company_code'] . "' AND bunit_code = '" . $list['bunit_code'] . "'"
            )['business_unit'];

            $dep = $this->dbmodel->get_field(
                "acroname",
                "locate_department",
                "company_code = '" . $list['company_code'] . "' AND bunit_code = '" . $list['bunit_code'] . "' AND dept_code = '" . $list['dept_code'] . "'"
            )['acroname'];

            $appid = $list['app_id'];
            $position = $list['position'];
            $display = $list['el_display'];
            $name = $list['name'];


          echo "
                <tr>
                    <td>$appid]</td>
                    <td> " . ucwords(strtolower($name)) . " </td>
                    <td> $position </td>
                    <td>$bunit</td>
                    <td>$dep</td>
					<td> $display </td>
                </tr>";

            }
            echo "</tbody>
                </table>"; 
