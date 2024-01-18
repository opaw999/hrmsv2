
<?php
    $filename = "PositionLevel_" . date('mdY');
    header("Cache-Control: public");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-disposition: attachment; filename=" . $filename . ".xls");
    ?>
        <center> <h3> LIST OF NEW POSITIONS  </h3> </center> 
        <table border='1' style="font-size: 13px">
            <thead>
                <tr>
                    <th>LEVEL</th>
                    <th>POSITION TITLE</th>
                    <th>USED BY</th>
                    <th>TYPE</th>
                    <th>CATEGORY</th>
                                
                </tr>
            </thead>
            <tbody>
            <?php
              $query_position = $this->db->query("SELECT * FROM position_leveling where position_title !='' order by lvlno ");
                     foreach ($query_position->result_array() as $row)
                    {
                echo
                "<tr>				
                    <td>$row[lvlno]</td>
                    <td>$row[position_title]</td>
                    <td>$row[used_by]</td>
                    <td>$row[type]</td>
                    <td>$row[category]</td>
                </tr>";
            } ?>
		</tbody>
	</table>		