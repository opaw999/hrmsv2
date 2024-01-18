
<div class="am-pagebody">
    <div class="row row-sm">
        <div class="col-lg-9">      
            <div class="card pd-20 pd-sm-30"> 
                <span class="text-right"> <b> Date: <?= date('F d, Y');?></b>  </span>
                <span class="text-center">
                    <h5> INTERVIEW: APPLICANT EVALUATION FORM </h5>    
                </span>  
                <i> SCORING : Applicant evaluation forms are to be completed by the interviewer to rank the candidates' overall qualifications for 
                    the position to which they have applied. Under each heading the interviewer should give the candidate a numerical rating and write a 
                    specific job related comments in the space provided.
                </i>   
 
                <form 
                    api-url = "supervisor/interview/submit"
                    api-type="POST"
                    onsubmit="event.preventDefault(); return handleFormSubmit();"
                    id      = "form" >
                    
                    <input type='hidden' name='interviewcode' value='<?= $interviewcode;?>'>
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
                        <tr> 
                            <td> <center> $row[no] </center> </td> 
                            <td> 
                                <h6> $row[title]</h6> <medium>".$row['description']."</medium> 
                                <br> <b> Comments/Remarks: </b>
                                <textarea class='form-control pd-5' rows='1' name='remarks[]'> </textarea>
                            </td>
                            <td> <br><br><br> <select class='form-control' required name='rates[]' id='$rate' onchange='compute_appraisal()'>";
                            echo '<option> </option>'; 
                            for($i = 10; $i > 0;$i -= 0.1) {     
                                echo "<option value='".sprintf("%.1f", $i)."'>".sprintf("%.1f", $i)." </option>";
                            }
                            echo " 
                        </select> </td> 
                        </tr>"; 
                    } ?>
                    <input type='hidden' name='appid' value='<?= $appid;?>'>
                    <input type='hidden' name='position' value='<?= $position;?>'>
                    <input type='hidden' name='interviewerid' value='<?= $this->session->userdata('emp_id');?>'>
                </table> &nbsp;
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label class="form-control-label"> Rater: </label>
                            <input type="text" disabled class="form-control" value='<?= $rater;?>' />
                        </div> 
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="form-control-label"> Numerical Rating: </label>
                            <input type="text" readonly class="form-control" name='numrates' id='numrates' />
                        </div>  
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="form-control-label"> Descriptive Rating: </label>
                            <input type="text" readonly class="form-control" name='desrate' id='desrate'/>
                        </div>    
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-control-label"> Overall Impression and Recommendation >> Final comments and recommendations for successful applicant evaluation.  </label>
                    <textarea class="form-control" name='comment'>  </textarea>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center" >
                        <button type='submit' class="btn btn-primary active"> Submit  </button>
                        <button type='button' class="btn btn-danger"> Cancel  </button>
                    </div>
                </div>
                </form>                          
            </div>    
            <button id="scrollToTopBtn" class="scroll-to-top-btn" title="Scroll to Top">
                <i class="icon ion-arrow-up-a"></i>
            </button>
        </div>
        <div class="col-lg-3">
            <div class="card pd-20 text-center">
                <h6 class="card-body-title"> APPLICANT DETAILS </h6> 
                <center> <img src="<?= $photo;?>" height="200" width="200"> </center>
                <br> <?= $name;?>
                <br> Date Applied: <?= $dateapplied;?>
                <br> Position Applied: <?= $position;?>
                <button type='button' class='btn btn-success'> SHOW 201 FILES </button>
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