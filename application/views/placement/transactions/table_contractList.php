
<div class="am-pagebody">
  <div class="card">
         <input type="hidden" class="form-control" name='empid' value='<?php echo $empid; ?>'>
        <div class="table-wrapper">
            <table id="datatable" class="table table-white table-bordered  tx-12">
                <thead>
                <tr>
                    <th>EMP NO.</th>
                    <th>NAME</th>
                    <th>COMPANY</th>
                    <th>BUSINESS&nbsp;UNIT</th>
                    <th>DEPARTMENT</th>
                    <th>POSITION</th>
                    <th>STARTDATE</th>
                    <th>EOCDATE</th>
                    <th class="wd-5p">ACTION</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    foreach($datas as $row) {
                        $rec 		= $row['record_no'];
                        $permit 	= $row['permit'];
                        $contract 	= $row['contract'];
                        $type 		= $row['emp_type'];
                        $company= $this->dbmodel->get_field("acroname","locate_company","company_code = '$row[company_code]'")['acroname'];
                        $bunit = $this->dbmodel->get_field("business_unit","locate_business_unit","company_code = '$row[company_code]' AND bunit_code = '$row[bunit_code]'")['business_unit'];
                        $dept = $this->dbmodel->get_field("dept_name","locate_department","company_code = '$row[company_code]' AND bunit_code = '$row[bunit_code]' AND dept_code = '$row[dept_code]'")['dept_name'];
                    ?>
                    <tr>
                            <td><?php echo $row['emp_id']; ?></td>
                            <td><?php echo ucwords(strtolower($row['name'])); ?></td>
                            <td><?php echo $company; ?></td>
                            <td><?php echo $bunit; ?></td>
                            <td><?php echo $dept; ?></td>
                            <td><?php echo $row['position']; ?></td>
                            <td><?php echo date('m/d/Y', strtotime($row['startdate'])); ?></td>
					        <td><?php echo date('m/d/Y', strtotime($row['eocdate'])); ?></td>
                            <td><center>
                                     <a type="button"
                                        modal-size=""
                                        modal-route="placement/transactions/generate/<?php echo $empid; ?>"
                                        modal-form="placement/transactions/editContract/<?php echo $empid; ?>"
                                        modal-skeleton="0"
                                        modal-id=""
                                        title = 'Edit/Generate'
                                        modal-atype="POST"
                                        modal-title="Edit the fields below that needs to be changed."
                                        onclick="modal(event)"><i class="ion-edit tx-20 text-danger">
                                    </a></center></td>
                            <?php
                            }
                                    ?>
                        </tr>
                </tbody>
            </table>
        </div><!-- table-wrapper -->
    </div>
</div>