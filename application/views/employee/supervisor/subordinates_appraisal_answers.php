    <div class="row row-sm">
        <div class="col-lg-12">                  
                        
            <span class="text-center">
                <h5> <?= $appraisaltype->appraisal;?> </h5>    
                <center> Date of Rating: <?= $result->ratingdate;?> </center>
            </span>   
            
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
                    <td class='text-left' colspan='3'> 
                        Rater Comments: <br>   
                        <?= (@$rater) ? "■ ".@$result->ratercomment."<br> ~ <span style='color:green'> ".@$rater."</span> <br>" : "";?>   
                        Employee's Reply: <br>   
                        <?= @$result->rateecomment;?>   
                    </td>                        
                </tr>
            </table>        
        </div>
    </div>
