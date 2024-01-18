<div class="am-pagebody">
    <div class="row row-sm">
        
        <!-- <div class="col-lg-4">
            <div class="card pd-20 pd-sm-20">
                <h6 class="card-body-title"> PAYROLL COMPANY GROUPINGS </h6>
                <ul class="list-group">
                    <li class="list-group-item active">Cras justo odio</li>
                    
                </ul>
            </div>
        </div> -->
        <div class="col-lg-12">
            <div class="card pd-5 pd-sm-20">
                <h6 class="card-body-title" style='font-size:20px'> SEARCH EMPLOYEE BY NAME OR PAYROLL NO </h6>
                <div class="row">
                    <div class="col-md-6">
                        <form method="get" action="<?= base_url();?>employee/payroll/search_result">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search Payroll No or Employee Name" name="searchval"/>
                                <span class="input-group-btn">
                                    <button class="btn bd bg-white tx-gray-600" type="submit">
                                    <i class="fa fa-search"></i></button>
                                </span>
                            </div><!-- input-group -->
                        </form>                        
                    </div>
                </div> 
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card pd-20 pd-sm-20">
                <div class="table-responsive">
                    <table id="datatable" class="table table-white table-bordered">
                        <thead>
                            <tr>
                                <th class="wd-5p"> NO </th>
                                <th class="wd-15p"> NAME </th>
                                <th class="wd-15p"> POSITION </th>
                                <th class="wd-5p"> LVL </th>
                                <th class="wd-5p"> EMPTYPE </th>
                                <th class="wd-15p"> DEPARTMENT </th>
                                <th class="wd-5p"> STATUS </th>
                                <th class="wd-10p"> PAYROLLNO </th>
                                <th class="wd-5p"> PCC </th>
                                <th class="wd-5p"> COMPANY </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if($result){
                                $ctr = 0;
                                foreach($result as $row){
                                    $ctr++;
                                    echo "
                                    <tr>
                                        <td> $ctr </td>
                                        <td> <a href='".base_url()."supervisor/profile/".$row['emp_id']."' target='_blank'> ".ucwords(strtolower($row['name']))." </a> </td>
                                        <td> $row[position] </td>
                                        <td> $row[poslevel] </td>
                                        <td> $row[emp_type] </td>
                                        <td></td>
                                        <td> $row[current_status]</td>
                                        <td> <input type='text' id='$row[emp_id]' name='pid' size='8'  value='$row[payroll_no]'> </td>
                                        <td> <input type='text' id='ccid_$row[emp_id]' size='3' value='$row[pcc]' readonly> </td>
                                        <td></td>
                                    </tr>";
                                }
                            }?>
                        </tbody>
                    </table>
                </div><!-- table-wrapper -->
            </div><!-- card -->          
        </div>
    </div>
</div>