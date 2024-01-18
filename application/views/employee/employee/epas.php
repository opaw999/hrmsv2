<!--
Display EPAS if naay for EPAS
Display NO EPAS if walay EPAS
mo gawas ang epas bisan wala pa ma sign off ni supervisor

check if promo
check if franchise
check if ae or nesco
-->
<div class="am-pagebody">

    <div class="row row-sm">
        <?php if($epas){ ?>
            <div class="col-lg-8">       
                
                <div class="card bd-0">
                    <div class="card-header card-header-default bg-dark">
                    EPAS FORM
                    </div>
                    <div class="card-body bd bd-t-0">

                <!-- <div class="card pd-20 pd-sm-30"> -->
                    <center>
                        <h5> Performance Appraisal for Support Personnel </h5>
                        <h6> FOR NETWORK SERVICES COOPERATIVE (NESCO) MEMBERS </h6>                    
                    </center>
                    <i> Instruction: Carefully look at your grade. Give your response from your supervisors rating and hit the SIGN OFF button. </i>   

                    <table border="1" width='100%'>
                        <tr> 
                            <th> <center> <h5> NO </h5> </center> </tthd> 
                            <th> <center> <h5> GUIDE QUESTIONS </h5> </center> </th> 
                            <th> <center> <h5> RATING </h5> </center> </th> 
                        </tr>
                        <?php
                        foreach($questions as $row){
                            echo "
                            <tr> 
                                <td> <center> $row[q_no] </center> </td> 
                                <td class='pd-sm-5'> <b>$row[title]</b> </td> 
                                <td> <center> $row[rate] </center> </td> 
                            </tr>"; 
                        } ?>
                    </table>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label"> Rater: </label>
                                <input type="text" disabled class="form-control" value="<?= $rater;?>"/>
                            </div><!-- form-group -->  
                        </div><!-- col -->
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label"> Numerical Rating: </label>
                                <input type="text" disabled class="form-control" value="<?= $epas->numrate;?>"/>
                            </div><!-- form-group -->  
                        </div><!-- col -->
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label"> Descriptive Rating: </label>
                                <input type="text" disabled class="form-control" value="<?= $epas->descrate;?>"/>
                            </div><!-- form-group -->    
                        </div><!-- col -->
                    </div><!-- row -->
                    <div class="form-group">
                        <label class="form-control-label"> Rater's Comment: </label>
                        <textarea class="form-control" disabled> <?= $epas->ratercomment;?> </textarea>
                    </div><!-- form-group -->
                    <div class="form-group">
                        <label class="form-control-label"> Your Comment: </label>                    
                        <textarea class="form-control"> </textarea>
                    </div><!-- form-group -->
                
                    <button class="btn btn-primary active"> Sign Off  </button>
                    </div>          
                </div><!-- card -->   
            </div>
            <div class="col-lg-4">
                <div class="card bd-0">
                    <div class="card-header card-header-default bg-dark">
                    EMPLOYEE DETAILS
                    </div>
                    <div class="card-body bd bd-t-0 text-center">
                        <center> <img src="<?= $photo;?>" style="border-radius:70%" height="200" width="200"> </center>
                        <br> <?= $this->session->userdata('employee_name');?>
                        <br> <?= $location;?>
                        <br> <?= $this->session->userdata('position');?>
                        <br> <?= $this->session->userdata('emp_type');?>
                        <br> <?= $this->session->userdata('startdate');?> - <?= $this->session->userdata('eocdate');?>
                    </div>
                </div>
                <br>                    
                <div class="card bd-0">
                    <div class="card-header card-header-default bg-dark">
                    FINAL RATING EQUIVALENT
                    </div>
                    <div class="card-body bd bd-t-0">
                        <table>
                            <tr> <td width='100'> 100 </td> <td> Excellent(E) </td> </tr>
                            <tr> <td> 90-99 </td> <td> Very Satisfactory (VS)</td> </tr>
                            <tr> <td> 85-89 </td> <td> Satisfactory (S) </td> </tr>
                            <tr> <td> 70-84 </td> <td> Unsatisfactory (US) </td> </tr>
                            <tr> <td> 0-69 </td> <td> Very Unsatisfactory (VU) </td> </tr>
                        </table>
                    </div>
                </div>
            </div>

        <?php }else{ ?>
            <div class="col-lg-12"> <br><br><br>
                <div class="ht-200v d-flex align-items-center justify-content-center">
                    <div class="wd-lg-70p wd-xl-50p tx-center">
                        <h1 class="tx-100 tx-xs-140 tx-normal tx-gray-800 mg-b-0"> <img src="<?= base_url()?>assets/images/icons/error-info-icon.png"> </h1> 

                        <?php
                        if($this->session->userdata('emp_type') != "Regular" && $this->session->userdata('emp_type')!="Regular Partimer" ){                      
                            echo '
                            <h5 class="tx-xs-24 tx-normal tx-orange mg-b-30 lh-5">Oh snap! No Performance Appraisal to show. </h5>
                            <p class="tx-16 mg-b-30">Please come back after your End of Contract Date on '.$this->session->userdata('eocdate'). ' Thank you! </p>';
                        }else{
                            echo '
                            <h5 class="tx-xs-24 tx-normal tx-orange mg-b-30 lh-5">Oh snap! No Performance Appraisal to show. </h5>
                            <p class="tx-16 mg-b-30">There is no recent Performance Appraisal for you to view. Either because you are a regular employee now. Thank you! </p>';                               
                        } 
                        ?>                         
                        <br> <br> <br> <br> 
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>