<div class="am-pagebody">
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card pd-20 pd-sm-30">
                <h6 class="card-body-title" style='font-size:20px'> EMPLOYEES UNDER PCC <?= $pcc;?></h6>                   
                <div class="table-wrapper">
                    <table id="datatable" class="table table-white table-bordered mg-b-0 tx-12">
                        <thead>
                            <tr>
                                <th class="wd-5p"> EMPID </th>
                                <th class="wd-5p"> PAYROLLNO </th>
                                <th class="wd-20p"> NAME </th>
                                <th class="wd-15p"> EMPTYPE </th>
                                <th class="wd-20p"> POSITION </th>
                                <th class="wd-30p"> DEPARTMENT </th>
                                <th class="wd-5"> PCC </th>
                            </tr>
                        </thead> 
                        <tbody>
                            <?php   
                            if(@$result){                 
                                foreach($result as $row){
                                    echo "
                                    <tr>
                                        <td> <a href=".base_url().'supervisor/profile/'.$row['emp_id']." target='_blank'>". $row['emp_id'] ."</a> </td>
                                        <td> $row[payroll_no] </td>
                                        <td> ".ucwords(strtolower($row['name']))."</td>
                                        <td> $row[emp_type] </td>
                                        <td> $row[position] </td>
                                        <td> $row[dept] </td>
                                        <td> $row[pcc] </td>
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