<?php
$ln = trim($this->input->post('ln'));
$fn = trim($this->input->post('fn'));
$n  = $ln . ", " . $fn;
$n2 = $ln . "," . $fn;
$q1 = $this->db->query("SELECT app_id, lastname, firstname, middlename FROM applicant where lastname like '%$ln%' and firstname like '%$fn%'  ");
$q2 = $this->db->query("SELECT name, status FROM blacklist where name like '%$n2%'");
$nn1 = $q1->num_rows();
$nn2 = $q1->num_rows();
if($nn1 == 0 && $nn2 == 0) {
    echo '';
} else {
    echo "<br>
		<div class='row'>
			<div class='col-md-6'>
				APPLICANT/EMPLOYEE
				<table class='table'>";
    foreach($q1->result_array() as $r) {
        $n = $r['lastname'] . ", " . $r['firstname'];
        $n1 = $r['lastname'] . ", " . $r['firstname'];

        $q3 = $this->db->query("SELECT status FROM blacklist where app_id = '$r[app_id]' ");
        //$q4 = mysql_query("SELECT status FROM blacklist where name like '%$n%' or name like '%$n1%' ");


        if($q3->num_rows() > 0) {

            foreach($q3->result_array() as $rr) {
                echo "<tr><td><a href='employee_details.php?com=$r[app_id]'>" . ucwords(strtolower($r['lastname'])) . ", " . ucwords(strtolower($r['firstname'])) . " " . ucwords(strtolower($r['middlename'])) . "</a></td>
							<td> <span class='label label-danger'>" . $rr['status'] . "</span></td><td></td></tr>";
            }

        } else {
                $currentstatus = $this->dbmodel->get_field('current_status', 'employee3', "emp_id = '" . $r['app_id'] . "' ")['current_status'];

                echo "<tr>     
                        <td><a href='" . base_url() . "supervisor/profile/" . $r['app_id'] . "' target='_blank'>" . ucwords(strtolower($r['lastname'])) . ", " . ucwords(strtolower($r['firstname'])) . " " . ucwords(strtolower($r['middlename'])) . "</a></td>
                        <td>";

                $label = ''; // Initialize $label variable

                switch ($currentstatus) {
                    case "blacklisted":
                        $label = 'badge badge-danger';
                        break;
                    case "Active":
                        $label = 'badge badge-success';
                        break;
                    case "Resigned":
                    case "End of Contract":
                     case "V-Resigned":
                        case "Retrenched":
                        $label = 'badge badge-warning';
                        break;
                }

                echo "<span class='$label'>$currentstatus</span></td>                 
                        <td><button type='button' id='chbtn' class='btn btn-primary btn-sm' data-dismiss='modal' onclick='choose(\"{$r['app_id']}*{$r['lastname']}, {$r['firstname']} {$r['middlename']}\")'>Choose</button></td>
                           <script>

                           function enableds(){
                                document.getElementById('reason').disabled = false;
                                document.getElementById('datebls').disabled = false;
                                document.getElementById('reportedby').disabled = false;
                                document.getElementById('bdays').disabled = false;
                                document.getElementById('addr').disabled = false;
                                document.getElementById('submit').disabled = false;
                                document.getElementById('reset').disabled = false;	
                            }
                        function choose(n){	
                            document.getElementById('namesearch').value = n;
                            enableds();
                        }
                                </script>
                        </tr>";
                
                }
                
            }
            
            echo "</table>		
                    </div>
                    
			
			<div class='col-md-6'>
				BLACKLISTED 
				<table class='table'>";
            foreach($q2->result_array() as $r2) {
                if($n != @$r['name']) {
                    echo "<tr><td>" . $r2['name'] . "</td><td><span class='badge badge-danger'>" . $r2['status'] . "</span></td></tr>";
                } else {
                    echo "<tr><td>" . $r2['name'] . "</td><td><span class='badge badge-danger'>" . $r2['status'] . "</span></td></tr>";
                }
            }

            echo "</table>
                    </div>
                </div>
                
                ";
            }
        
         ?>
 