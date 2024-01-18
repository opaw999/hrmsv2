<div class="am-pagebody">
    <div class="row row-sm">

    <?php if($result){ ?>
        <div class="col-lg-8">  
            
        <div class="card pd-20 pd-sm-30">  
            Date &nbsp; &nbsp;:  <?= ($date1 == "00/00/0000") ? @$date2 : @$date1 ; ?>  <br> 
            Name : <?= $result->name;?>
            <hr>
            <span class="text-center">
                <h5> <?= $appraisaltype->appraisal;?> </h5>    
            </span>   
            
            <form 
                api-url = "employee/si/savecomment"
                api-type="POST"
                onsubmit="event.preventDefault(); return submitConfirm(event);"
                id      = "form" >

                <table class="table table-striped table-bordered">                                      
                    <tr> 
                        <th> <center> <b> NO </b> </center> </th> 
                        <th> <center> <b> GUIDE CRITERION </b> </center> </th> 
                        <th width='15%'> <center> <b> RATE </b> </center> </th> 
                    </tr>
                    <tr>
                    <?php
                    $ctr = 0;
                    foreach($questions as $row){
                        $ctr++;
                    
                        echo "
                        <tr> 
                            <td> <center> $row[q_no] </center> </td> 
                            <td> <b> $row[title] </b> <br><i> ". $row['description']." </i></td>
                            <td> $row[rate] </td>                                                                 
                        </tr>";
                    } ?>
                    <tr> 
                        <td> </td> 
                        <td class='text-right'> Total Rating </td> 
                        <td> <?= $result->numrate;?> (<?= $result->descrate;?>) </td> 
                    </tr>
                    <tr> 
                        <td class='text-left' colspan='3'> Rater Comments: <br>   
                            <?= (@$rater1) ? "■ ".@$result->rater1comment."<br> ~ <span style='color:green'> ".@$rater1."</span> <br>" : "";?>                      
                            <?= (@$rater2) ? "■ ".@$result->rater2comment."<br> ~ <span style='color:green'> ".@$rater2."</span>" : "";?>
                        </td>                        
                    </tr>
                </table> 

                <input type="hidden" name="empid" value="<?= $empid;?>">
                <label> Your Comment: </label>
                <textarea class='form-control' name="rateeComment" id='rateeComment' <?= ($result->rateeSO == 1) ? "disabled": ""; ?> > <?= $result->rateecomment;?>  </textarea>             
                <?= ($result->rateeSO == 1) ? "": "<p><br><button type='submit' id='form-button' class='btn btn-primary'> Submit </button> </p>"; ?>            
            
            </form>
        </div>                      
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
        <button id="scrollToTopBtn" class="scroll-to-top-btn" title="Scroll to Top">
            <i class="icon ion-arrow-up-a"></i>
        </button>   

        <?php }else{ ?>
            <div class="col-lg-12"> <br><br><br>
                <div class="ht-200v d-flex align-items-center justify-content-center">
                    <div class="wd-lg-70p wd-xl-50p tx-center">
                        <h1 class="tx-100 tx-xs-140 tx-normal tx-gray-800 mg-b-0"> <img src="<?= base_url()?>assets/images/icons/error-info-icon.png"> </h1> 
                       
                        <h5 class="tx-xs-24 tx-normal tx-orange mg-b-30 lh-5">Oh snap! No Performance Appraisal to show. </h5>
                        <p class="tx-16 mg-b-30">There is no Performance Appraisal for you to view. Thank you! </p>                             
                                               
                        <br> <br> <br> <br> 
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>