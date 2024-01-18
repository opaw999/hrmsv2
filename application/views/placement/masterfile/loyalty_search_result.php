
<div class="am-pagebody">
  <div class="row row-sm">
    <div class="col-lg-12">
      <div class="card pd-0">
          <div class="row">    
          </div>
          <br>
            <div class="table-responsive">
                    <table id="datatable" class="table table-white table-bordered tx-12" style = "border: 2px solid #87CEEB">
                        <thead class="bg-info">
                        <tr>
                            <th >NO</th>
                            <th >NAME</th>
                            <th >POSITION</th>
                            <th >LVL</th>
                            <th >EMPTYPE</th>
                            <th >STATUS</th>
                            <th >DEPT</th>
                            <th >DATE&nbsp;HIRED</th>
                            <th >YRS&nbsp;IN&nbsp;SERVICE</th>
                            <th >YEAR&nbsp;AWARDED</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $c =1; 
                            foreach($loyal as $row) {

                            $datehired = $this->dbmodel->get_field("date_hired", "application_details", "app_id = '$row[emp_id]'")['date_hired'];
                            $comp = $this->dbmodel->get_field("*", "locate_company", "company_code = '$row[company_code]'")['acroname'];
                            $bunit = $this->dbmodel->get_field("*", "locate_business_unit", "company_code = '$row[company_code]' AND bunit_code = '$row[bunit_code]'")['acroname'];
                            $dept =  $this->dbmodel->get_field("*", "locate_department", "company_code = '$row[company_code]' AND bunit_code = '$row[bunit_code]' AND dept_code = '$row[dept_code]'")['acroname'];

                           echo' <tr>
                            <td> '.$c++ .'</td>
                            <td> '. ucwords(strtolower($row['name'])).'</td>
                            <td> '.$row['position'] .'</td>
                            <td> '. $row['poslevel'] .' </td>
                            <td> '. $row['emp_type'] .'</td>
                            <td> '.$row['current_status'] .'</td>
                            <td>  '.$comp."/".$bunit."/".$dept.'</td>
                            <td> '.$datehired.' </td>
                            <td> <center>'.$row['yrsinservice'] .'</center></td>
                            <td> <center>'.$row['year'] .'</center></td> 
                             </tr>
                        </tbody>';
                            } ?>
                    </table>
                </div>
            </div><!-- card -->
        </div><!-- col-6 -->
    </div>
  

