<div class="am-pagebody">
    <div class="row row-sm">
        <?php if($siperiodno){ ?>
            <div class="col-lg-12">
                <div class="card pd-20 pd-sm-30">
                    <div class="row">
                        <div class="col-md-8">
                            <h3> Performance Appraisal Wizard </h3>

                            <p style='color:red'> To all Section Heads / MTs/ Supervisors / Managers: </p>

                            Please take time to read before you start:
                            <ul>
                                <li> <b> Jan. 1 - Oct. 30, 2023 </b> - The period where the violations of employees are based in this Salary Appraisal.</li>
                                <li> <b> Dec. 1 - Dec. 30, 2023 </b> - The period for creating the Employee's Performance Appraisal.  </li>
                                <li> Call your respective HRD for your concerns.  </li>
                                <li> Please follow the following steps below.  </li>
                            </ul> 
                               
                        </div>
                        <div class="col-md-4 text-right">
                            <button class='btn btn-primary'> <i class="icon ion-document-text"></i> Show User Guide </button>
                        </div>
                    </div>
                    
                    <br>
                    <div class="row">
                        <div class="col-md-4 mg-t-20 mg-md-t-0">
                            <div class="card bd-0">
                                <div class="card-header card-header-default">
                                    <h5> STEP 1 </h5> 
                                </div>
                                <div class="card-body bd bd-t-0">
                                    <h4>ATTENDANCE AND PUNCTUALITY / VIOLATIONS </h4>
                                    <p>
                                        DTR and violations-related data for attendance and punctuality rating are provided to those employee's 
                                        with Timekeeping and Violation Monitoring system. 	
                                    </p>
                                    <p>
                                        However, those with manual DTR are inputted by the respective HRD offices.	
                                    </p>                                    
                                    <p>
                                        Now, your tasks is to review and confirm the violations incurred by the employee by hitting DONE button.	
                                        Update if needed but there's no need to compute. 
                                    </p>
                                    <p>
                                        Click Open button to start.
                                    </p>
                                    <?php // review this for the access of the supervisor
                                    //if($r['step1'] == 1){ ?>
                                        <hr>
                                        <center> 
                                            <a href="<?= base_url();?>employee/sistep/1" 
                                            class="btn btn-success btn-lg"> OPEN </a>
                                        </center>	
                                    <?php //}?>                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mg-t-20 mg-md-t-0">
                            <div class="card bd-0">
                                <div class="card-header card-header-default">
                                    <h5> STEP 2 </h5> 
                                </div>
                                <div class="card-body bd bd-t-0">
                                    <h4> PERFORMANCE EVALUATION AND APPRAISAL RATING</h4>
                                    <p>
                                        Create a performance evaluation through guide criterion and violations monitoring for the appraisal of all the subordinates.                                        
                                    </p>
                                    <p>
                                        Take note to refrain from inputting a dot (.) as your comments or remarks to the employees.                                        
                                    </p>
                                    <p>
                                        Please take your time and do it diligently. Be reminded as well to the deadline of submission.                                    
                                    </p>
                                    <p>
                                        Click Open button to start.
                                    </p>

                                    <?php //if($r['step2'] == 1){ ?>
                                        <hr><center> 
                                        <a href='<?= base_url();?>employee/sistep/2' class='btn btn-success btn-lg'> OPEN </a> </center>
                                    <?php //}?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mg-t-20 mg-md-t-0">
                            <div class="card bd-0">
                                <div class="card-header card-header-default">
                                    <h5> STEP 3 </h5> 
                                </div><!-- card-header -->
                                <div class="card-body bd bd-t-0">
                                    <h4> STEP3: PERFORMANCE EVALUATION AND APPRAISAL FINALIZATION AND POSTING </h4>
                                    <p class="mg-b-0"> Heads will finalize all ratings and in-charge of posting</p>

                                    <?php // if($r['step3'] == 1){ ?>
                                        <hr><center> 
                                        <a href='<?= base_url();?>employee/sistep/3' class='btn btn-success btn-lg'> CLICK HERE </a> </center>
                                    <?php //}?>    
                                </div>
                            </div>
                        </div>
                    </div>                                
                </div>
            </div>
        <?php }else{ ?>
            <div class="col-lg-12"> <br><br><br>
                <div class="ht-200v d-flex align-items-center justify-content-center">
                    <div class="wd-lg-70p wd-xl-50p tx-center">
                        <h1 class="tx-100 tx-xs-140 tx-normal tx-gray-800 mg-b-0"> <img src="<?= base_url()?>assets/images/icons/error-info-icon.png"> </h1> 
                       
                        <h5 class="tx-xs-24 tx-normal tx-orange mg-b-30 lh-5">Oh snap! No Performance Appraisal Wizard to show. </h5>                    
                                               
                        <br> <br> <br> <br> 
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>