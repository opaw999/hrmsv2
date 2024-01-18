<div class="am-pagebody">
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card pd-20 pd-sm-20">
                <h4> Search Employee </h4>
                   
                <form method="get" action="<?= base_url();?>placement/search/view_result">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Employee Name" name="searchval" style="text-transform: capitalize;"/>
                        <span class="input-group-btn">
                            <button class="btn bd bg-white tx-gray-600" type="submit">
                            <i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </form>  
                Search Lastname, firstname or middlename of employee whether active or not.
                
                <!-- <div id="results" style="margin:20px;">

                    <?php 
                    $count = 0;
                    foreach($result as $row)
                    {
                        $count++; ?>
                        <div class="row">
                            <div class="col-md-1"> 
                                <img src="<?= $url.$row['photo'];?>" width="90" height="90"> 
                            </div>

                            <div class="col-md-11">                            
                                [<?= $count;?>] 
                                <span style='font-size:16px;font-weight:bold'> <?= $row['emp_id'];?> </span> 
                                <a href="#" style="color:#0000CC; font-weight:bold; font-size:16px "> <?= $row['name'];?> </a> 
                                
                                    <?php
                                    //DISPLAY SA STATUS 
                                    $stat = $row['current_status'];
                                    //substatus
                                    if($row['sub_status'] == ""){ $substatus = ""; } else { $substatus = "(".$row['sub_status'].")"; }
                                    //current status
                                    if($stat == "Active"){ $c_stat = "success"; }	
                                    else if($stat == "blacklisted" || $stat == "Blacklisted"){ $c_stat = "danger"; }
                                    else if($stat == "End of Contract" || $stat == "Resigned" || $stat == "V-Resigned" || $stat == "Ad-Resigned" || $stat == "Retrenched" || $stat == "Retired" || $stat == "Deceased"){  $c_stat = "warning"; }
                                    else { $c_stat = ucwords(strtolower($row['current_status'])); }
                                    ?>
                                <span class="badge badge-<?= $c_stat;?>"> <?= $row['current_status']." ".$substatus;?> </span> <br>

                                <span style='color:green'> 
                                    Company: <i> AGC </i> 
                                    Business Unit: <i> Head Office </i> 
                                    Department: <i> Information Technology </i> 
                                    Section: <i> SYSTEMS DESIGN & DEVELOPMENT </i> 
                                    Sub Section: <i> </i> 
                                </span> <br>

                                 Position: <i> <?= $row['position'];?> (Level <?= $row['poslevel'];?>) </i>   
                                &nbsp; Employee Type: <i> <?= $row['emp_type'];?> (with PRA) </i>   
                                &nbsp; Civil Status: <i> <?= $row['civilstatus'];?> </i>    
                                &nbsp; Birthdate: <i> <?= $row['birthdate'];?> (31yrs old) </i>    
                                &nbsp; Home Address: <i> <?= $row['home_address'];?> </i>  <br>

                                <u> Date Hired: </u> <i> 04/24/2013 </i> 
                                <u> Yrs in Service: </u> <i> (10 yrs & 5 months) </i> 
                                <u> Date Regular: </u> <i> 08/15/2015 </i> 
                            </div>                        
                        </div> <br>

                    <?php } ?>
                </div> -->
                <div id="results" style="margin:20px;">

                    <table class="table table-striped">
                        <thead style="display: none;">
                            <tr>
                                <th></th>
                            </tr>
                        </thead>
                            <?php 
                            $count = 0;
                            if(isset($result)) {
                            foreach($result as $row)
                            {
                                $count++; ?>
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-1"> 
                                                <img src="<?= $url.$row['photo'];?>" width="90" height="90"> 
                                            </div>

                                            <div class="col-md-11">                            
                                                [<?= $count;?>] 
                                                <span style='font-size:16px;font-weight:bold'> <?= $row['emp_id'];?> </span> 
                                                <a href="#" style="color:#0000CC; font-weight:bold; font-size:16px "> <?= $row['name'];?> </a> 
                                                
                                                    <?php
                                                    //DISPLAY SA STATUS 
                                                    $stat = $row['current_status'];
                                                    //substatus
                                                    if($row['sub_status'] == ""){ $substatus = ""; } else { $substatus = "(".$row['sub_status'].")"; }
                                                    //current status
                                                    if($stat == "Active"){ $c_stat = "success"; }	
                                                    else if($stat == "blacklisted" || $stat == "Blacklisted"){ $c_stat = "danger"; }
                                                    else if($stat == "End of Contract" || $stat == "Resigned" || $stat == "V-Resigned" || $stat == "Ad-Resigned" || $stat == "Retrenched" || $stat == "Retired" || $stat == "Deceased"){  $c_stat = "warning"; }
                                                    else { $c_stat = ucwords(strtolower($row['current_status'])); }
                                                    ?>
                                                <span class="badge badge-<?= $c_stat;?>"> <?= $row['current_status']." ".$substatus;?> </span> <br>

                                                <span style='color:green'> 
                                                    Company: <i> AGC </i> 
                                                    Business Unit: <i> Head Office </i> 
                                                    Department: <i> Information Technology </i> 
                                                    Section: <i> SYSTEMS DESIGN & DEVELOPMENT </i> 
                                                    Sub Section: <i> </i> 
                                                </span> <br>

                                                Position: <i> <?= $row['position'];?> (Level <?= $row['poslevel'];?>) </i>   
                                                &nbsp; Employee Type: <i> <?= $row['emp_type'];?> (with PRA) </i>   
                                                &nbsp; Civil Status: <i> <?= $row['civilstatus'];?> </i>    
                                                &nbsp; Birthdate: <i> <?= $row['birthdate'];?> (31yrs old) </i>    
                                                &nbsp; Home Address: <i> <?= $row['home_address'];?> </i>  <br>

                                                <u> Date Hired: </u> <i> 04/24/2013 </i> 
                                                <u> Yrs in Service: </u> <i> (10 yrs & 5 months) </i> 
                                                <u> Date Regular: </u> <i> 08/15/2015 </i> 
                                            </div>                        
                                        </div> <br>
                                    </td>
                                </tr>
                            <?php 
                            } 
                        }?>
                    </table>
                </div>
            </div>       
        </div>
    </div>
</div>