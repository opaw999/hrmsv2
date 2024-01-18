<div class="am-pagebody">
    <div class="row row-sm">
        <div class="col-lg-8">
        <?php if($epas){ ?>
            <div class="card pd-20 pd-sm-30">
                <span class="text-right"> <b> Date: <?= date('F d, Y');?></b>  </span>
                <span class="text-center">
                    <h5> Performance Appraisal for Support Personnel (Preview) <?php //$appraisaltype->appraisal;?> </h5>                                       
                </span>   
                
                <table class='table table-bordered table-striped'>
                    <tr> 
                        <th> <span class="text-center"> NO </span> </th> 
                        <th> GUIDE CRITERION </th> 
                        <th> RATING </th> 
                    </tr>
                    <?php
                    $ctr = 0;
                    foreach($questions as $row){
                        $ctr++;
                        $rate =  "rate" . $ctr;
                        echo "
                        <tr> 
                            <td> <center> $row[q_no] </center> </td> 
                            <td class='pd-sm-5'> <b>$row[title]</b> </td> 
                            <td class='pd-sm-5 text-center'>  $row[rate] </td> 
                        </tr>"; 
                    } ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-center"> <?= $numrate." - ".$descrate;?> </td>
                    </tr>
                </table>
            
                
                <div class="row">
                    <div class="col-md-12">
                        <?= $ratercomment;?> <br>
                        - <?= $rater;?>
                    </div>
                </div>  
                                         
            </div><!-- card -->
        <?php } ?>
            
        </div>
        <div class="col-lg-4">
            <div class="card pd-20 text-center">
                <h6 class="card-body-title"> EMPLOYEE DETAILS </h6> 
                <center> <img src="<?= $photo;?>" height="200" width="200"> </center>
                <br> <?= $epas->name;?>
                <br> <?= $location;?>
                <br> <?= $epas->position;?>
                <br> <?= $epas->emp_type;?>
                <br> <?= $epas->startdate;?> - <?= $epas->eocdate;?>
            </div><!-- card -->
            <br>
            <div class="card pd-20">
                <h6 class="card-body-title"> FINAL RATING EQUIVALENT </h6>
                <table>
                    <tr> <td> 100 </td> <td> Excellent(E) </td> </tr>
                    <tr> <td> 90-99 </td> <td> Very Satisfactory (VS)</td> </tr>
                    <tr> <td> 85-89 </td> <td> Satisfactory (S) </td> </tr>
                    <tr> <td> 70-84 </td> <td> Unsatisfactory (US) </td> </tr>
                    <tr> <td> 0-69 </td> <td> Very Unsatisfactory (VU) </td> </tr>
                </table>
            </div><!-- card -->
        </div>
    </div>
</div>