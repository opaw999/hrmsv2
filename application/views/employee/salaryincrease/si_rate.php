<div class="am-pagebody">
    <div class="row row-sm">  
        <div class="col-lg-12">        
            <div class="card pd-20 pd-sm-30"> 
                <span class="text-right"> <b> Date: <?= date('F d, Y');?></b>  </span>
                <span class="text-center">
                    <h5> <?= $appraisaltype->appraisal;?> </h5>    
                </span>   
                <i class='text-center'> Instruction: Please choose the number you believe is the appropriate rating for the subject - rate on the corresponding criterion.</i>   
                <hr>
                <form 
                    api-url = "employee/si/savestep2"
                    api-type="POST"
                    onsubmit="event.preventDefault(); return handleFormSubmit();"
                    id      = "form" >

                    <input type="hidden" name='rater' value='<?= $this->session->userdata('emp_id');?>'>
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td colspan='2'>  
                                <b> RATINGS EQUIVALENT </b>                         
                                <table class="table table-bordered">
                                    <tr> <td> 100 </td> <td> Excellent(E) </td> <td> 85-89 </td> <td> Satisfactory (S) </td> </tr>
                                    <tr> <td> 90-99 </td> <td> Very Satisfactory (VS)</td> <td> 70-84 </td> <td> Unsatisfactory (US) </td> </tr>                           
                                    <tr> <td> 0-69 </td> <td> Very Unsatisfactory (VU) </td> <td> </td><td> </td> </tr>
                                </table>
                            </td>                                 
                            <?php
                            foreach($id as $row):
                                $n = $this->dbmodel
                                    ->get_row(
                                        'employee3',
                                        'name',
                                        array( 'field1' => 'emp_id'),
                                        array( $row )
                                    );

                                $p = $this->dbmodel
                                    ->get_row(
                                        'applicant',
                                        'photo',
                                        array( 'field1' => 'app_id'),
                                        array( $row )
                                    );

                                echo "<td width='18%'> <input type='hidden' name='empids[]' value='$row'>
                                <center> <img src='http://172.16.161.100/hrms/employee/".$p->photo."' style='border-radius:70%' height='120' width='120'> <br>".
                                ucwords(strtolower($n->name)) ."<br>
                                    <button
                                        type='button'
                                        class='btn btn-danger btn-sm'
                                        modal-size=''
                                        modal-route=''
                                        modal-form='employee/si/showguide/violations/$row'  
                                        modal-redirect= ''                             
                                        modal-skeleton='0'
                                        modal-id=''
                                        modal-atype=''
                                        modal-title='Violations Incurred'
                                        modal-button= 'false'
                                        onclick='modal(event)'>
                                        <i class='fa fa-ban'></i> Violations
                                    </button>

                                    <button
                                        type='button'
                                        class='btn btn-success btn-sm'
                                        modal-size=''
                                        modal-route=''
                                        modal-form='employee/si/showguide/prevrate/$row'  
                                        modal-redirect= ''                             
                                        modal-skeleton='0'
                                        modal-id=''
                                        modal-atype=''
                                        modal-title='Previous Rate'
                                        modal-button= 'false'
                                        onclick='modal(event)'>
                                        <i class='fa fa-star'></i> Latest Rating
                                    </button>
                                </center></td>";
                            endforeach;
                            ?>       
                        </tr>
                                            
                        <tr> 
                            <th> <center> <b> NO </b> </center> </th> 
                            <th> <center> <b> GUIDE CRITERION </b> </center> </th> 
                            <?php
                            foreach($id as $row):
                                echo " <th width='20%'> <center> <b> RATE </b> </center> </th> ";
                            endforeach;
                            ?> 
                        </tr>
                        <tr>
                        <?php
                        $ctr = 0;
                        foreach($questions as $row){
                            $ctr++;
                            
                            echo "
                            <tr> 
                                <td> <center> $row[q_no] </center> </td> 
                                <td> <b> $row[title] </b> <br><i> ". $row['description']." </i></td>";
                               
                                foreach($id as $rows):

                                    $sid = $this->dbmodel
                                        ->get_row(
                                            'si_details',
                                            '*',
                                            array( 'field1' => 'emp_id', 'field2' => 'si_period_no'),
                                            array( $rows, $siperiod )
                                        );  
                                        
                                    //FOR ATTENDANCE COMPUTATION    
                                    $loa    = @$sid->loa * 0.05;
                                    $awol   = @$sid->awol * 0.75;
                                    $tardy  = @$sid->tardiness * 0.25;
                                    $suspe  = @$sid->suspension * 0.5;
                                    $undert = @$sid->undertime * 0.05;

                                    $total  = $loa + $awol + $tardy + $suspe + $undert;

                                    $computed_attendance = 10-$total;                                    
                                    if($computed_attendance <= 0){ //back to zero to na
                                        $computed_attendance = 0.0;
                                    }
                                
                                    $rate =  "rate". $ctr.$rows;  //<input type='hidden' name='rate[]' id='$ctr' /> 
                                    echo "
                                    <td class='pd-sm-5'> <center>                                   
                                        <select class='form-control' required name='rates[]' id='$rate' onchange=computeAppraisal('$rows') style='width:70px'>";
                                            echo '<option> </option>'; 
                                            for($i = 10; $i > 0;$i -= 0.1) { 
                                                
                                                $si = $this->dbmodel
                                                    ->get_row(
                                                        'si_answer',
                                                        '*',
                                                        array( 'field1' => 'si_details_id ', 'field2' => 'appraisal_id'),
                                                        array( $sid->si_details_id, $ctr )
                                                    );

                                                if($code == '1' && $ctr == '9') // Managers Heads Supervisors
                                                {                                                    
                                                    if(strval(@$si->rate) == strval($i)) {
                                                        echo "<option value='".sprintf("%.1f", $i)."' selected>".sprintf("%.1f", $i)." </option>";
                                                    }else if(strval($computed_attendance) == strval($i)) {
                                                        echo "<option value='".sprintf("%.1f", $i)."' selected>".sprintf("%.1f", $i)." </option>";
                                                    }else{
                                                        echo "<option value='".sprintf("%.1f", $i)."'>".sprintf("%.1f", $i)." </option>";
                                                    }                                                  
                                                }else if($code == "3" && $ctr == '5'){ // Frontliners / Support Personnel
                                                    if(strval(@$si->rate) == strval($i)){
                                                        echo "<option value='".sprintf("%.1f", $i)."' selected>".sprintf("%.1f", $i)."</option>";
                                                    }else if(strval($computed_attendance) == strval($i)){
                                                        echo "<option value='".sprintf("%.1f", $i)."' selected>".sprintf("%.1f", $i)." </option>";
                                                    }else{
                                                        echo "<option value='".sprintf("%.1f", $i)."'>".sprintf("%.1f", $i)." </option>";
                                                    }                                                      
                                                }else{
                                                    if(strval(@$si->rate) == strval($i)){
                                                        echo "<option value='".sprintf("%.1f", $i)."' selected>".sprintf("%.1f", $i)." </option>";
                                                    }else{
                                                        echo "<option value='".sprintf("%.1f", $i)."'>".sprintf("%.1f", $i)." </option>";
                                                    }  
                                                }                                                                             
                                            }
                                            echo " 
                                        </select> </center> 
                                    </td>";
                                endforeach; 
                                echo "                                
                            </tr>"; 
                        } ?>
                        <tr> 
                            <td> </td> 
                            <td class='text-right'> Total Rating: </td> 
                            <?php
                            foreach($id as $row):
                                $sid = $this->dbmodel
                                    ->get_row(
                                        'si_details',
                                        '*',
                                        array( 'field1' => 'emp_id', 'field2' => 'si_period_no'),
                                        array( $row, $siperiod )
                                    );
                                
                                echo '<td>
                                    <input type="text" readonly id="numrates'.$row.'" name="numrates[]" style="width:100px" value="'.$sid->numrate.'" />  
                                    <input type="text" readonly id="desrate'.$row.'" name="desrate[]" style="width:100px" value="'.$sid->descrate.'" />
                                </td>';
                            endforeach; ?> 
                        </tr>
                        <tr> 
                            <td> </td> 
                            <td class='text-right'> Please input proper comments to your subordinates: </td> 
                            <?php
                            foreach($id as $row):
                                $sid = $this->dbmodel
                                    ->get_row(
                                        'si_details',
                                        '*',
                                        array( 'field1' => 'emp_id', 'field2' => 'si_period_no'),
                                        array( $row, $siperiod )
                                    );

                                $rater = @$this->dbmodel
                                    ->get_row(
                                        'employee3',
                                        'name',
                                        array( 'field1' => 'emp_id'),
                                        array( $sid->rater2 )
                                    )->name;    

                                echo '<td>
                                    <textarea class="form-control" name="ratercomment[]"> '.$sid->rater1comment.' </textarea>
                                    <br> Rater: '. $rater .'
                                </td>';
                            endforeach; ?> 
                        </tr>
                        <tr> 
                            <td> </td> 
                            <td> </td>                             
                            <?php
                            foreach($id as $row):
                                echo "<td>". ucwords(strtolower($this->dbmodel
                                    ->get_row(
                                        'employee3',
                                        'name',
                                        array( 'field1' => 'emp_id'),
                                        array( $row )
                                    )->name))."</td>";
                            endforeach;
                            ?> </td>
                        </tr>
                    </table>
                                        
                    <div class="row">
                        <div class="col-md-12 text-center" >                        
                            <button type="submit" class="btn btn-primary active" onClick="needToConfirm = false;"> <i class="icon ion-navigate"></i> Submit  </button> 
                            <a href='#' id="back-btn" class="btn btn-danger">
                                <i class="icon ion-backspace"></i> Back
                            </a>
                        </div>
                    </div>                    
                </form>                          
            </div>       
        </div>
        <button id="scrollToTopBtn" class="scroll-to-top-btn" title="Scroll to Top">
            <i class="icon ion-arrow-up-a"></i>
        </button>          
    </div>
</div>