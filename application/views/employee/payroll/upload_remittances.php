<div class="am-pagebody">
    <div class="row row-sm">

        <div class="col-lg-12">
            <div class="card pd-20 pd-sm-20">                
                <div class="pd-10 bd mg-t-10">
                    <ul class="nav nav-pills flex-column flex-md-row" role="tablist">  
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#upload" role="tab"> Upload Remittances </a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#list" role="tab"> Remittances List </a></li>
                    </ul>
                </div> 
                <div class="tab-content">  
                    <div class="tab-pane fade show active" id="upload">                        
                        <div class="col-md-12"> 
                            <br>
                            <h6 class="card-body-title" style='font-size:24px'> UPLOADED REMITTANCES </h6> 
                            <div class="form-group">
                                
                                <label class="form-control-label"> Deduction Type </label>
                                <input type="text" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="form-control-label"> Description </label>
                                <input type="text" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="form-control-label"> Choose File </label>
                                <input type="file" class="form-control" />
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary"> Submit </button>
                                <button class="btn btn-danger"> Cancel </button>
                            </div>                             
                        </div> 
                    </div>  
                    <div class="tab-pane fade" id="list">                         
                        <div class="col-md-12">
                            <br>
                            <h6 class="card-body-title" style='font-size:24px'> VIEW UPLOADED REMITTANCES </h6> 
                            <form id="filter-prescription" method="POST">
                                <div class="form-row" style="width:94%;">					
                                    <div class="col-md-2">
                                        <input type="date" class="form-control" name="pdatefrom" placeholder="mm/dd/yyyy" value="<?php echo isset($_POST['pdatefrom']) ? htmlspecialchars($_POST['pdatefrom']) : ''; ?>" >
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" class="form-control" name="pdateto" placeholder="mm/dd/yyyy" value="<?php echo isset($_POST['pdateto']) ? htmlspecialchars($_POST['pdateto']) : ''; ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <?php
                                        $rem = array('SSS Loan', 'SSS Premium', 'SSS Calamity Loan', 'Pag-ibig Loan', 'Pag-ibig Payable', 'Pag-ibig Calamity Loan',
                                                    'Philhealth', 'WithholdingTax', 'remittance special_c.a', 'remittance c.a');
                                        ?>
                                        <select class ="form-control" name="remittances">
                                            <option> </option>
                                            <?php 
                                            foreach ($rem as $row):
                                                echo "<option value='$row'> $row </option>";
                                            endforeach;
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 text-left">
                                        <input type="submit" class="btn btn-success" value="Filter">
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </form>
                            &nbsp; 	
                            <div class="table-responsive">
                                <table id="datatable" width="100%" class="table table-white table-bordered mg-b-0 tx-12">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p"> BUSINESS UNIT </th>
                                            <th class="wd-15p"> TYPE </th>
                                            <th class="wd-20p"> FILE NAME </th>
                                            <th class="wd-10p"> CUTOFF </th>
                                            <th class="wd-15p"> UPLOADEDBY </th>
                                            <th class="wd-15p"> DATEUPLOADED </th>
                                            <th class="wd-15p"> ACTION </th>
                                        </tr>
                                    </thead>    
                                    <tbody>
                                        <?php
                                        //var_dump($result);
                                        foreach($result as $rows):
                                            ?>
                                            <tr>
                                                <td> <?php //echo $rows['description'];?></td>
                                                <td> <?php //echo $row['type'];?> </td>
                                                <td> </td>                                    
                                                <td> </td>                                    
                                                <td> <?php //echo $row['uploadedBy'];?> </td>                                    
                                                <td> <?php //echo $row['date_uploaded'];?> </td>                                    
                                                <td>                                  
                                                    <button
                                                        type="button"
                                                        class="btn btn-primary btn-sm active"
                                                        modal-size="modal-lg"
                                                        modal-route=""
                                                        modal-form="employee/payroll/viewcsv"  
                                                        modal-redirect= "employee/payroll/filter_employee/"                             
                                                        modal-skeleton="0"
                                                        modal-id=""
                                                        modal-atype="POST"
                                                        modal-title="CSV REPORT"
                                                        modal-button= "false"
                                                        onclick="modal(event)">
                                                        <i class="icon ion-search"></i> 
                                                    </button>
                                                    <a href="http://172.16.161.100/hrmsv2/employee/reports/pcc_export/10" class="btn btn-danger btn-sm"> 
                                                    <i class="icon ion-trash-a"></i> </a>
                                                </td>                                    
                                            </tr>    <?php    
                                        endforeach;
                                        ?>
                                        <!-- <tr>
                                            <td> FIXRITE PANGLAO </td>
                                            <td> Pag-ibig Payable </td>
                                            <td> PagibgLoanFR_20230715.csv </td>                                    
                                            <td> 07/15/2023 </td>                                    
                                            <td> Thelma Andamon </td>                                    
                                            <td> 07/21/2023 </td>                                    
                                            <td>                                  
                                                <button
                                                    type="button"
                                                    class="btn btn-primary btn-sm active"
                                                    modal-size="modal-lg"
                                                    modal-route=""
                                                    modal-form="employee/payroll/viewcsv"  
                                                    modal-redirect= "employee/payroll/filter_employee/"                             
                                                    modal-skeleton="0"
                                                    modal-id=""
                                                    modal-atype="POST"
                                                    modal-title="CSV REPORT"
                                                    modal-button= "false"
                                                    onclick="modal(event)">
                                                    <i class="icon ion-search"></i> 
                                                </button>
                                                <a href="http://172.16.161.100/hrmsv2/employee/reports/pcc_export/10" class="btn btn-danger btn-sm"> 
                                                <i class="icon ion-trash-a"></i> </a>
                                            </td>                                    
                                        </tr>                           -->
                                    </tbody>
                                </table>
                            </div>                             
                        </div>
                    </div>  
                </div>      
            </div> 
        </div>
    </div>
</div>