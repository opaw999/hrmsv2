<div class="am-pagebody">
    <div class="row row-sm">        
        <div class="col-lg-12">
            <div class="card pd-20 pd-sm-30">
                <h4 class="text-center"> STEP1: ATTENDANCE AND PUNCTUALITY / VIOLATIONS </h4>
                <medium class="text-center"> Note: Review and save the violations incurred by the employee. Update if needed (NO COMPUTATION), otherwise, click the checkbox and hit DONE button. </medium>
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon bg-transparent">
                            <label class="ckbox wd-16">
                                <input type="checkbox" id='chkAll'/><span></span>
                            </label> &nbsp; Check all per page
                            </span>
                            &nbsp;
                            <button class="btn btn-primary btn-sm " id="doneMisconduct">
                                <i class="icon ion-flag"></i> Done
                            </button>                        
                        </div>
                    </div>
                    <div class="col-md-5"> </div>
                    <div class="col-md-3 text-right">
                        <a href='<?= base_url();?>employee/siwizard' class="btn btn-info">
                            <i class="icon ion-backspace"></i> Back
                        </a>                        
                        <button
                            type="button"
                            class="btn btn-success active"
                            modal-size="modal-lg"
                            modal-route=""
                            modal-form="employee/si/showguide/step1/1"  
                            modal-redirect= ""                             
                            modal-skeleton="0"
                            modal-id=""
                            modal-atype="POST"
                            modal-title="STEP1 GUIDELINES"
                            modal-button= "false"
                            onclick="modal(event)">
                            <i class="icon ion-search"></i> Show Guide
                        </button>
                        <br><br>
                    </div>
                </div>
                <div class="table-wrapper">
                    <table id="datatable" class="table table-white table-bordered">
                        <thead>
                            <tr>
                                <th class="wd-5p">  </th>
                                <th class="wd-20p"> NAME </th>
                                <th class="wd-20p"> POSITION </th>
                                <th class="wd-5p"> EMPTYPE </th>
                                <th class="wd-5p"> LOA </th>
                                <th class="wd-5p"> TARDY </th>
                                <th class="wd-5p"> SSPNSN </th>
                                <th class="wd-5p"> AWOL </th>
                                <th class="wd-5p"> UT </th>
                                <th class="wd-5p"> STATUS </th>
                            </tr>
                        </thead>
                        <tbody style='font-size:13px'>
                            <?php
                            foreach($result as $row):
                                $vio = $this->dbmodel->get_field(
                                    "loa, tardiness, suspension, awol, undertime, si_details_id, step1_stat", 
                                    "si_details", 
                                    "si_period_no = '".$siperiodno."' and emp_id = '$row[emp_id]'"
                                ); ?>
                                <tr>
                                    <td> 
                                        <input type='checkbox' class='form-control chk' name='cbname[]' value='<?= $row['emp_id'];?>'
                                        <?php echo ($vio['step1_stat'] == '1') ? "disabled" : "" ; ?> >  
                                    </td>
                                    <td> <?php echo '<a href="'.base_url().'supervisor/profile/'.$row['emp_id'].'" target="_blank">
                                        '.ucwords(strtolower($row['name']));?> </a> </td>
                                    <td> <?= $row['position'] ;?> </td>
                                    <td> <?= $row['emp_type'] ;?> </td>
                                    <td> <input type='text' size='4' name='loa' class='loa_<?= $row['emp_id'];?>' value='<?= @$vio['loa'];?>' 
                                            onchange="return save_onTab(event,'<?= trim($row['emp_id']);?>','loa',this);" 
                                            onkeypress="return save_onEnter(event,'<?= trim($row['emp_id']);?>','loa',this);" 
                                            id='<?= $row['emp_id'];?>' <?php echo ($vio['step1_stat'] == '1') ? "disabled" : "" ; ?>>
                                    </td>
                                    <td> <input type='text' size='4' name='tardi' class='tardi_<?= $row['emp_id'];?>' value='<?= @$vio['tardiness'];?>'  
                                            onchange="return save_onTab(event,'<?= trim($row['emp_id']);?>','tardi',this);"  
                                            onkeypress="return save_onEnter(event,'<?= trim($row['emp_id']);?>','tardi',this);"
                                            id='<?= $row['emp_id'];?>' <?php echo ($vio['step1_stat'] == '1') ? "disabled" : "" ; ?>> 
                                    </td>
                                    <td> <input type='text' size='4' name='suspension'  class='suspension_<?= $row['emp_id'];?>' value='<?= $vio['suspension'];?>'  
                                            onchange="return save_onTab(event,'<?= trim($row['emp_id']);?>','suspension',this);" 
                                            onkeypress="return save_onEnter(event,'<?= trim($row['emp_id']);?>','suspension',this);"
                                            id='<?= $row['emp_id'];?>' <?php echo ($vio['step1_stat'] == '1') ? "disabled" : "" ; ?>> 
                                    </td>
                                    <td> <input type='text' size='4' name='awol' class='awol_<?= $row['emp_id'];?>' value='<?= $vio['awol'];?>' 
                                            onchange="return save_onTab(event,'<?= trim($row['emp_id']);?>','awol',this);" 
                                            onkeypress="return save_onEnter(event,'<?= trim($row['emp_id']);?>','awol',this);"
                                            id='<?= $row['emp_id'];?>' <?php echo ($vio['step1_stat'] == '1') ? "disabled" : "" ; ?>> 
                                    </td>
                                    <td> <input type='text' size='3' name='undertime' class='undertime_<?= $row['emp_id'];?>'  value='<?= $vio['undertime'];?>'
                                            onchange="return save_onTab(event,'<?= trim($row['emp_id']);?>','undertime',this);"  
                                            onkeypress="return save_onEnter(event,'<?= trim($row['emp_id']);?>','undertime',this);"
                                            id='<?= $row['emp_id'];?>' <?php echo ($vio['step1_stat'] == '1') ? "disabled" : "" ; ?>> 
                                    </td>
                                    <td>
                                        <?php echo ($vio['step1_stat'] == '1') ? "<span class='badge badge-success'> Done </span>" : "" ; ?>
                                    </td>
                                </tr> <?php
                            endforeach;?>     
                        </tbody>
                    </table>
                </div><!-- table-wrapper -->
            </div><!-- card -->
          
        </div>
    </div>
</div>