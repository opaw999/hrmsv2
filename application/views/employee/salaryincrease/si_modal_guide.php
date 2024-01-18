<?php
if($step == "step1"){
    ?>
    <div class='row'>   
        <div class="col-md-3">  
            <b> Point deduction scheme for Attendance and Punctuality computation </b> 
        </div>
        <div class="col-md-1">  
            LOA (.05) 
        </div>
        <div class="col-md-2">   
            TARDINESS <br>(.25)
        </div>
        <div class="col-md-2">
            SUSPENSION  <br>(.5)   
        </div>
        <div class="col-md-2">
            AWOL  <br>(.75)   
        </div>
        <div class="col-md-2">
            UNDERTIME  <br>(.05)   
        </div>
    </div>
    <div class='row'>   
        <div class="col-md-12">    
            <div class="bs-callout bs-callout-warning" id="callout-tables-responsive-overflow">        
                <p> 1) Violation occurences are already provided based on the timekeeping data. However, if there's a need to change, input it in the checkbox. 
                    <b><code> DO NOT COMPUTE.</code></b> The system will do it for you. </p>
            </div>

            <div class="bs-callout bs-callout-warning" id="callout-tables-responsive-overflow">
                <p> 2) After inputting, press enter to save. A green check in the textbox will be an indicator that it is successfully saved. 
            If error occurs, call 1821 or 1844.  </p>
            </div>   			

            <div class="bs-callout bs-callout-warning" id="callout-tables-responsive-overflow">
                <p> 3) Click the checkbox beside each employee's name, click as many as you can or you can click the checkbox CHECK ALL PER PAGE then click
                    <button class='btn btn-primary btn-sm'> <i class="icon ion-flag"></i>  DONE </button> button.
                </p>
            </div>            
            
            <div class="bs-callout bs-callout-warning" id="callout-tables-responsive-overflow">        
                <p> 4) If status is <span class="badge badge-success"> Done</span>, you can proceed to giving of grades in STEP2. Good luck! </p>
            </div> 
        </div>       
    </div> <?php
}else if($step == "step2"){ ?>
    <div class='row'>   
        <div class="col-md-12">   		      	
            <div class="bs-callout bs-callout-warning" id="callout-tables-responsive-overflow">        
                <p> 1) Click the checkbox beside the employee's name then click <button type="button" class="btn btn-success btn-sm" > <i class="fa fa-star"></i> RATE</button>  </p>
            </div>

            <div class="bs-callout bs-callout-warning" id="callout-tables-responsive-overflow">
                <p> 2) Performance Appraisal grading sheet will be displayed, please rate carefully. By default attendance and functuality rating is already computed. 
                    Then after that click <button type="button" class="btn btn-primary btn-sm" > <i class="icon ion-navigate"></i> Submit</button> </p>
            </div>   			

            <div class="bs-callout bs-callout-warning" id="callout-tables-responsive-overflow">
                <p> 3) Click the checkbox beside each employee's name, click as many as you can or you can click the checkbox 
                    CHECK ALL PER PAGE then click 
		      		<button class='btn btn-primary btn-sm'> <i class="icon ion-flag"></i> DONE </button> button 
                </p>
            </div>            
            
            <div class="bs-callout bs-callout-warning" id="callout-tables-responsive-overflow">        
                <p> 4) If status is <span class='label label-success'> Done </span>, you can no longer edit the grade. </p>
            </div> 
        </div>       
    </div> 
<?php }
else if($step == "violations"){ 
    
    $arr = array("LOA" => $sidetails->loa,"TARDINESS" => $sidetails->tardiness,
            "SUSPENSION" => $sidetails->suspension,"AWOL" => $sidetails->awol,
            "UNDERTIME" => $sidetails->undertime);

        $arr = array
            (
                array("LOA", $sidetails->loa, 0.05),
                array("TARDINESS", $sidetails->tardiness, 0.25),
                array("SUSPENSION", $sidetails->suspension, 0.5),
                array("AWOL", $sidetails->awol, 0.75),
                array("UNDERTIME", $sidetails->undertime, 0.05)
            );     
    ?>        
              
    NAME: <?= $employee->name;?>
    <table class="table table-bordered table-striped"> 
        <tr> 
            <th> VIOLATIONS INCURRED </th> 
            <th> OCCURENCES </th>  
            <th> POINT DEDUCTION </th>  
            <th> RESULT </th>  
        </tr>
        <?php
        $total = 0;
        for($i=0;$i<5;$i++)
        {
            $res = $arr[$i][1] * $arr[$i][2];
            $total += $res;
            echo "<tr>";            
                echo " <td> ". $arr[$i][0]." </td> ";
                echo " <td> ". $arr[$i][1]." </td> ";
                echo " <td> ". $arr[$i][2]." </td> ";
                echo " <td> ". $res ." </td> ";           
            echo "</tr>";
        }
        ?>
        <tr>            
            <td colspan='3' class='text-right'> Total </td> 
            <td> <?= $total;?></td>         
        </tr>
    </table>
<?php } 
else if($step == "prevrate"){ ?>
    <table class="table table-bordered table-striped"> 
        <thead>
            <tr>
                <th> NO </th>
                <th> DESCRIPTION </th>
                <th> RATING </th>
            </tr>
        </thead>
        <tbody>
            <?php
            for($i=0;$i<10;$i++){
                echo "
                <tr>
                    <td> $i </td>
                    <td> </td>
                    <td> </td>
                </tr>";  } ?>
        </tbody>
        <tr>
            <th>  </th>
            <th class='text-right'> TOTAL RATING </th>
            <th>  </th>
        </tr>
    </table>
    <p> Comments: </p>
<?php } ?>