<div class="am-pagebody">
    <div class="row row-sm"> 
        <?php if(isset($doctor->doctorID)){ ?>       
        <div class="col-lg-8">
            <!-- <div class="card pd-20"> -->
            <div class="card bd-1">
                <div class="card-header card-header-default bg-dark">
                DOCTORS - CLINIC SCHEDULE
                </div> 
                <div class="card-body bd bd-t-0">

                    <div class="row">
                        <div class="col-md-4">
                            <center>  
                                <img src="<?php echo base_url()."".$doctor->photo;?>" width="200" height="200" class="rounded-circle" alt="Image">            
                                <br><br><H4> <?= $doctor->doctorName;?> </H4>
                                <p class="mg-b-0"> <?= $doctor->designation;?>  </b></p>
                                
                                <?php
                                $result = $this->dbmodel
                                    ->get_row(
                                        'hr_clinic.patient_doctor_dailylogs',
                                        'status',
                                        array( 'field1' => 'dateIn','field2' => 'doctorID' ),
                                        array( date('Y-m-d'), $doctor->doctorID ) );
                                if($result){   
                                    echo '<br><a href="#" class="btn btn-success pd-lg-x-20 mg-l-auto"> IN </a>';
                                }else{
                                    echo '<br><a href="#" class="btn btn-danger pd-lg-x-20 mg-l-auto"> OUT </a>';
                                } 
                                ?>  
                                <HR>
                                <h4>CLINIC SCHEDULE</h4>
                            </center> 
                        </div>
                        <div class="col-md-8">
                            <div class="col-md-12">
                                <div class="list-group widget-list-group">
                                    <?php foreach($docSked as $row){                             
                                        switch ($row["location"]) {
                                            case 'Alturas Mall Clinic':     $storelogo = base_url().'assets/images/storelogo/store-alturasmall.png'; break;
                                            case 'Island City Mall Clinic': $storelogo = base_url().'assets/images/storelogo/store-icm.png"'; break;
                                            case 'Plaza Marcela Clinic':    $storelogo = base_url().'assets/images/storelogo/store-plazamarcela.png'; break;
                                            case 'Alta Citta Mall Clinic':  $storelogo = base_url().'assets/images/storelogo/store-altacitta.jpg'; break;                        
                                            default:                        $storelogo = base_url().'assets/images/storelogo/other-store-logo.png'; break;
                                        } ?>
                                    <div class="list-group-item pd-y-20 rounded-top-0">
                                        <div class="media">
                                        <div class="d-flex mg-r-10 wd-50">
                                            <img src="<?= $storelogo;?>" class="wd-45" alt="Image">
                                        </div>
                                        <div class="media-body">
                                            <h6 class="tx-inverse"> <?= $row["location"];?> </h6>
                                            <p class="mg-b-0"> <?= $row["time"];?> <b> <?= $row["day"];?></b></p>
                                        </div>
                                        </div>
                                    </div>     
                                    <?php } ?>                       
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card bd-1">
                <div class="card-header card-header-default bg-dark">
                DOCTORS
                </div>                
                <div class="list-group widget-list-group">
                    <?php foreach($doctors as $row){ ?>
                        <a href="<?= base_url()?>employee/doctor/<?= $row['doctorID'];?>" class="tx-inverse hover-primary">         
                        <div class="list-group-item pd-y-20 rounded-top-0">
                            <div class="media">
                                <div class="d-flex mg-r-10 wd-50">
                                    <img src="<?php echo base_url()."".$row['photo'];?>" class="rounded-circle wd-40" alt="Image">
                                </div><!-- d-flex -->
                                <div class="media-body">
                                    <h6 class="mg-b-5 tx-14"><?= $row['doctorName'];?> </h6>
                                    <p class="mg-b-0 tx-12"> <?= $row['designation'];?> </p>
                                </div><!-- media-body -->
                            </div><!-- media -->
                        </div><!-- list-group-item -->  
                        </a>
                    <?php } ?>                                  
                </div>                
            </div>
        </div>
        <?php } else {?>
            <div class="col-lg-12"> <br><br><br>
                <div class="ht-200v d-flex align-items-center justify-content-center">
                    <div class="wd-lg-70p wd-xl-50p tx-center">
                        <h1 class="tx-100 tx-xs-140 tx-normal tx-gray-800 mg-b-0"> <img src="<?= base_url()?>assets/images/icons/error-info-icon.png"> </h1> 
                        <h5 class="tx-xs-24 tx-normal tx-orange mg-b-30 lh-5"> Page not found  </h5>
                        <p class="tx-16 mg-b-30">
                            It's looking like you may have taken a wrong turn. 
                            Don't worry... it happens to the best of us. You might want to check your network connection.
                            Here's a little tip that might help you get back on track.  </p>
                        <div class="tx-center mg-t-20">... or back to  <a href="<?= base_url(); ?>employee/dashboard" class="tx-orange hover-info"> Dashboard </a></div>                       
                        <br>  
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>   
</div>