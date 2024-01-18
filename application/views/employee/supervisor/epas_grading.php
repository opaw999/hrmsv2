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
        <div class="col-lg-9">
        
            <div class="card pd-20 pd-sm-30"> 
                <span class="text-right"> <b> Date: <?= date('F d, Y');?></b>  </span>
                <span class="text-center">
                    <?php
              
                    echo '<h5>' . $appraisaltype->appraisal . ' ' . $aptype . '</h5>';
               
                ?>
                     
                </span>   
                <i> Instruction: Please choose the number you believe is the appropriate rating for the subject - rate on the corresponding criterion.</i>   

                <form 
                    api-url = "supervisor/epas/submit"
                    api-type="POST"
                    onsubmit="event.preventDefault(); return handleFormSubmit();"
                    id      = "form" >
                    <table class='table table-bordered table-striped'>
                        <tr> 
                            <th> <center> <b> NO </b> </center> </th> 
                            <th> <center> <b> GUIDE CRITERION </b> </center> </th> 
                            <th> <center> <b> RATING </b> </center> </th> 
                        </tr>
                        <?php
                        $ctr = 0;
                        foreach($questions as $row){
                            $ctr++;
                            $rate =  "rate" . $ctr;

                            echo "
                            <input type='hidden' name='appraisal_id[]' value=".$row['appraisal_id']."/>
                            <input type='hidden' name='no[]' id='no' value=".@$row['answer_id']."/>
                            <tr> 
                                <td> <center> $row[q_no] </center> </td> 
                                <td> <h6> $row[title]</h6> <medium>".$row['description']."</medium> </td> 
                                <td class='pd-sm-5'> <center> 
                                    <input type='hidden' name='rate[]' id='$ctr' />
                                    <select class='form-control' required name='rates[]' id='$rate' onchange='compute_appraisal()'>";
                                        echo '<option> </option>'; 
                                        for($i = 10; $i > 0;$i -= 0.1) {      
                                            if(strval($row['rate']) == strval($i)){
                                                echo "<option value='".sprintf("%.1f", $i)."' selected>".sprintf("%.1f", $i)." </option>";
                                            }else{
                                                echo "<option value='".sprintf("%.1f", $i)."'>".sprintf("%.1f", $i)." </option>";
                                            }                               
                                        }
                                        echo " 
                                    </select>
                                </center> </td> 
                            </tr>"; 
                        } ?>
                    </table>

                    <input type="hidden" name="empid" value="<?= $empid; ?>">
                    <input type="hidden" name="detailsid" value="<?= $detailsid; ?>">
                    <input type="hidden" name="recordno" value="<?= $recordno; ?>">
                    <input type="hidden" name="code" value="<?= $code; ?>">
                    <input type="hidden" name="raterid" value="<?= $raterid; ?>">
                    <input type="hidden" name="apptype" value="<?= $aptype; ?>"> <!-- to check where the form is new or for update -->
                
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label"> Rater: </label>
                                <input type="text" disabled class="form-control" name="rater" value="<?= $rater;?>"/>
                            </div>  
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label"> Numerical Rating: </label>
                                <input type="text" readonly class="form-control" id="numrates" name="numrates" value="<?= $numrate;?>"/>
                            </div> 
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label"> Descriptive Rating: </label>
                                <input type="text" readonly class="form-control" id="desrate" name="desrate" value="<?= $descrate;?>"/>
                            </div>  
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label"> Rater's Comment: </label>
                        <textarea class="form-control" name="ratercomment"> <?= $ratercomm;?> </textarea>
                    </div>    
                    
                    <i> Take Note: 
                        <br> - Click <b style='color:blue'> SIGN OFF BUTTON </b> and then Click <b style='color:green'> SUBMIT BUTTON </b> when rating is FINAL.
                        <br> - Click <b style='color:green'> SUBMIT BUTTON </b> only if you intend to edit and finalize later. 
                    </i>
                    <div class="row">
                        <div class="col-md-12 text-center" >
                        <input type="hidden" id="raterSO" name="raterSO"/>
                        <button type="button" class="btn btn-primary active" onClick="signoff()"> Sign Off  </button> 
                        <button type="submit" class="btn btn-success active" onClick="needToConfirm = false;"> Submit  </button> 
                        </div>
                    </div>        
                
                </form>
                          
            </div>
       
        </div>
        <div class="col-lg-3">
            <div class="card pd-20 text-center">
                <h6 class="card-body-title"> EMPLOYEE DETAILS </h6> 
                <center> <img src="<?= $photo;?>" height="200" width="200"> </center>
                <br> <?= $name;?>
                <br> <?= $location;?>
                <br> <?= $position;?>
                <br> <?= $emptype;?>
                <br> <?= $startdate;?> - <?= $eocdate;?>
            </div>
            <br>
            <div class="card pd-20">
                <h6 class="card-body-title"> RATINGS EQUIVALENT </h6>
                <table>
                    <tr> <td> 10 </td> <td> Excellent(E) </td> </tr>
                    <tr> <td> 9-9.9 </td> <td> Very Satisfactory (VS)</td> </tr>
                    <tr> <td> 8.5-8.9 </td> <td> Satisfactory (S) </td> </tr>
                    <tr> <td> 7-8.4 </td> <td> Unsatisfactory (US) </td> </tr>
                    <tr> <td> 0-6.9 </td> <td> Very Unsatisfactory (VU) </td> </tr>
                </table>
            </div>
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
            </div>
        </div>
    </div>
</div>