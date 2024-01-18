<div class="am-pagebody">
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card pd-20 pd-sm-20">               
                   
                <form method="get" action="<?= base_url();?>placement/search/view_result_applicant">
                    <table>
                        <tr>
                            <td><h4> Search </h4> </td>
                            <td width="40%"> <input type="text" class="form-control" placeholder="Lastname" name="lastname" style="text-transform: capitalize;" value="<?= @$ln;?>"/> </td>
                            <td width="40%"> <input type="text" class="form-control" placeholder="Firstname" name="firstname" style="text-transform: capitalize;" value="<?= @$fn;?>"/></td>
                            <td> <button class="btn btn-primary" type="submit"> Search </button></td>
                        </tr>
                    </table>
                </form>  
                <div> <span id='result_count'>  </span> <hr> </div>

                <div id="results" style="margin:30px;">

                    <?php 
                    $count = 0;
                    if(isset($result)){
                        foreach($result as $row)
                        {

                            if($row['suffix']){
                                $name = $row['lastname'].", ".$row['firstname']." ".$row['suffix'].", ".$row['middlename'];	
                            }else{
                                $name = $row['lastname'].", ".$row['firstname']." ".$row['middlename'];	
                            }

                            $count++; ?>
                        <div class="row">
                            <div class="col-md-1"> 
                                <img src="<?= $url.$row['photo'];?>" width="70" height="70"> 
                            </div>

                            <div class="col-md-11">                            
                                [<?= $count;?>] 
                                <span style='font-size:16px;font-weight:bold'> <?= $row['app_id'];?> </span> 
                                <a href="#" style="color:#0000CC; font-weight:bold; font-size:16px "> <?= ucwords(strtolower($name));?>  </a> <br>                                
                              
                                Civil Status: <i> <?= $row['civilstatus'];?> </i>    
                                &nbsp; Birthdate: <i> <?= $row['birthdate'];?> (31yrs old) </i>    
                                &nbsp; Home Address: <i> <?= $row['home_address'];?> </i>  <br>
                            </div>                        
                        </div> <br>

                        <?php }
                    } ?>
                </div>
            </div>       
        </div>
    </div>
</div>