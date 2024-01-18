<div class="am-pagebody">
    <div class="row row-sm">
        
        <div class="col-lg-12">
            <div class="card pd-20 pd-sm-30">
                <h4 class="text-center"> STEP2: PERFORMANCE EVALUATION AND APPRAISAL RATING </h4>
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon bg-transparent">
                            <label class="ckbox wd-16">
                                <input type="checkbox" id='chkAll'/><span></span>
                            </label> &nbsp; Check all per page
                            </span>
                        &nbsp;
                        <button type="button" class="btn btn-success" id="rate_step2"> 
                            <i class="fa fa-star"></i> Rate 
                        </button>
                        &nbsp;
                        <button class="btn btn-primary" id="doneStep2">
                            <i class="icon ion-flag"></i> Done
                        </button>
                        
                        </div>
                    </div>
                    <div class="col-md-5">
                    </div>
                    <div class="col-md-3 text-right">
                        <a href='<?= base_url();?>employee/siwizard' class="btn btn-info">
                            <i class="icon ion-backspace"></i> Back
                        </a>
                        <button
                            type="button"
                            class="btn btn-success active"
                            modal-size="modal-lg"
                            modal-route=""
                            modal-form="employee/si/showguide/step2/1"  
                            modal-redirect= ""                             
                            modal-skeleton="0"
                            modal-id=""
                            modal-atype="POST"
                            modal-title="STEP2 GUIDELINES"
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
                                <th> </th>				 			
                                <th> <b> NAME </b> </th>		 
                                <th> <b> POSITION </b> </th>
                                <th> <b> EMPTYPE </b> </th>   
                                <th> <b> PREVIOUS<BR> RATE </b> </th>   						
                                <th> <b> RATING </b> </th>					
                                <th> <b> RATER </b> </th>
                                <th> <b> STEP1 </b> </th>	
                                <th> <b> STEP2 </b> </th>					
                                <th> <b> STEP3 </b> </th>					
                            </tr>
                        </thead>
                        <tbody style='font-size:13px'>
                            <?php
                            foreach($result as $row): 
                                $vio = $this->dbmodel->get_field(
                                        "*", 
                                        "si_details", 
                                        "si_period_no = '".$siperiodno."' and emp_id = '$row[emp_id]'"
                                    );

                                $getname1 = $this->dbmodel
                                    ->get_row(
                                        'employee3',
                                        'name',
                                        array( 'field1' => 'emp_id'),
                                        array( $vio['rater1'] )
                                    );

                                $getname2 = $this->dbmodel
                                    ->get_row(
                                        'employee3',
                                        'name',
                                        array( 'field1' => 'emp_id'),
                                        array( $vio['rater2'] )
                                    );

                                $siprev = $this->dbmodel                                       
                                    ->get_field_row(
                                        'si_pno',
                                        'si_period',
                                        "appraisal_of = 'AE' ",
                                        'si_pno', 
                                        'DESC', 
                                        '1', 
                                        '1'
                                    )->si_pno;
                                                                
                                if(@$siprev){
                                    $prev_rate = @$this->dbmodel
                                    ->get_row(
                                        'si_details',
                                        'numrate',
                                        array( 'field1' => 'emp_id', 'field2' => 'si_period_no'),
                                        array( $row['emp_id'], $siprev )
                                    )->numrate;    
                                }else{
                                    $prev_rate = ""; 
                                } ?>

                                <tr id='test<?php echo $row['emp_id'];?>' >
                                    <td> 
                                        <input type='checkbox' class='form-control chk' name='cbname[]' value='<?= $row['emp_id'];?>'
                                        <?php echo ($vio['step1_stat'] == '1' && $vio['step2_stat'] == '1' ) ? "disabled" : "" ; ?>    
                                        <?php echo ($vio['step1_stat'] == '0' || $vio['step1_stat'] == '') ? "disabled" : "" ; ?> >                                      
                                    </td>
                                    <td> <?php echo '<a href="'.base_url().'supervisor/profile/'.$row['emp_id'].'" target="_blank">'. ucwords(strtolower($row['name']));?> </a> </td>
                                    <td> <?= $row['position'] ;?> </td>
                                    <td> <?= $row['emp_type'] ;?> </td>
                                    <td> 
                                        <?php
                                        if($prev_rate == 0){
                                            
                                        }else{ ?>
                                        <button
                                            type="button"
                                            class="btn btn-default btn-sm active"
                                            modal-size="modal-lg"
                                            modal-route=""
                                            modal-form="employee/si/showrate/previous/<?php echo $row['emp_id'];?>"  
                                            modal-redirect= ""                             
                                            modal-skeleton="0"
                                            modal-id=""
                                            modal-atype="POST"
                                            modal-title="APPRAISAL DETAILS"
                                            modal-button= "false"
                                            onclick="modal(event)">
                                            <?= $prev_rate ;?>
                                        </button> <?php } ?>
                                    </td>
                                    <td> 
                                        <?php
                                        if($vio['numrate'] == 0){
                                            
                                        }else{ ?>
                                            <button
                                                type="button"
                                                class="btn btn-default btn-sm active"
                                                modal-size="modal-lg"
                                                modal-route=""
                                                modal-form="employee/si/showrate/current/<?php echo $row['emp_id'];?>"  
                                                modal-redirect= ""                             
                                                modal-skeleton="0"
                                                modal-id=""
                                                modal-atype="POST"
                                                modal-title="APPRAISAL DETAILS"
                                                modal-button= "false"
                                                onclick="modal(event)">
                                                <?= $vio['numrate'] ;?>
                                            </button> <?php
                                        }?>
                                    </td>
                                    <td> 
                                        <?= (@$getname1->name) ? substr(ucwords(strtolower(@$getname1->name)),0,20).".." : ""; ?>
                                        <?= (@$getname2->name) ? substr(ucwords(strtolower(@$getname2->name)),0,20).".." : ""; ?>
                                    </td>
                                    <td> 
                                        <?php echo ($vio['step1_stat'] == '1') ? "<span class='badge badge-success'> Done </span>" : "" ; ?>
                                    </td>
                                    <td> 
                                        <?php echo ($vio['step2_stat'] == '1') ? "<span class='badge badge-success'> Done </span>" : "" ; ?>
                                    </td>
                                    <td> 
                                        <?php echo ($vio['step3_stat'] == '1') ? "<span class='badge badge-success'> Done </span>" : "" ; ?>
                                    </td>
                                </tr>
                            <?php endforeach;?>      
                        </tbody>
                    </table>
                </div>
            </div>          
        </div>
    </div>
</div>