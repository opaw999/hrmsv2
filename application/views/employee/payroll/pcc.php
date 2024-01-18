<div class="am-pagebody">
    <div class="row row-sm">
        <div class="col-lg-4">
            <div class="card pd-20 pd-sm-30">
                <h6 class="card-body-title" style='font-size:20px'> PAYROLL COMPANY GROUPINGS </h6>
                <ul class="list-group">                    
                    <?php                    
                    foreach($results2 as $rows){
                        if($rows['group_no'] == $grp){ $stat = 'active'; }else{ $stat = ''; }
                        echo '<a href="'.base_url().'employee/payroll/pcc/'.$rows['group_no'].'"> <li class="list-group-item '.$stat.'"> '.$rows['group_name'].' </li></a>';
                    } ?>
                </ul>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card pd-20 pd-sm-30">
                <h6 class="card-body-title" style='font-size:24px'> PCC - PAYROLL COST CENTER </h6>
                <div class="table-wrapper">
                    <table id="datatable" class="table table-white table-bordered">
                        <thead>
                            <tr>
                                <th class="wd-5p"> NO </th>
                                <th class="wd-10p"> PCC CODE </th>
                                <th class="wd-30p"> PCC NAME </th>
                                <th class="wd-5p"> PAYROLL DB </th>
                                <th class="wd-5p"> ACTION </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php                    
                            foreach($result as $row){
                                echo "
                                <tr>
                                    <td> $row[pcc_no] </td> 
                                    <td> <a href=".base_url()."employee/payroll/pccemployees/$row[pcc_no] target='_blank'> $row[pcc_code] </a> </td>
                                    <td> $row[pcc_name]</td>
                                    <td> $row[group_no]</td>
                                    <td> <a href='".base_url()."employee/reports/pcc_export/$row[pcc_no]' class='btn btn-success btn-sm'> 
                                        <i class='icon ion-stats-bars'></i> Export </a> </td>
                                </tr>";
                            }?>              
                        </tbody>
                    </table>
                </div><!-- table-wrapper -->
            </div><!-- card -->
          
        </div>
    </div>
</div>