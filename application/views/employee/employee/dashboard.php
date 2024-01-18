<div class="am-pagebody">
    
    <div class="row row-sm">
        <div class="col-lg-8">
            <div class="card">                
                <div id="demo" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ul class="carousel-indicators">
                        <li data-target="#demo" data-slide-to="0" class="active"></li>
                        <li data-target="#demo" data-slide-to="1"></li>
                        <li data-target="#demo" data-slide-to="2"></li>
                    </ul>

                    <!-- The slideshow -->
                    <div class="carousel-inner">
                        <div class="carousel-item active"> 
                            <img src="<?= base_url()?>assets/images/carousel/1download.png" alt="Los Angeles" width="100%" height="560">
                        </div>
                        <?php
                            $carousel = array('2alturush_banner_reg.png','3alturush_banner_store.png','4alturush_banner_category.png',
                                '5alturush_banner_BUSINESS.png','6contact.png');
                            foreach($carousel as $row): ?>
                                <div class="carousel-item">
                                    <img src="<?= base_url()?>assets/images/carousel/<?= $row;?>" width="100%" height="560">
                                </div> <?php 
                            endforeach;
                        ?>                        
                    </div>

                    <!-- Left and right controls -->
                    <a class="carousel-control-prev" href="#demo" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next" href="#demo" data-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </a>
                </div>
            </div><!-- card -->
            <br>
            <div class="card">   
                <div class="row">
                    <div class="col-md-12 mg-t-15 mg-sm-t-20 mg-md-t-0">
                        <div class="list-group widget-list-group bg-app-primary">
                        <?php foreach($doctors as $row){?>
                       
                        <div class="list-group-item d-flex">
                            <a href="<?= base_url()?>employee/doctor/<?= $row['doctorID'];?>" class="tx-white" title="Click Doctor's Schedule"> 
                            <div class="media d-block d-sm-flex">
                                <div class="d-block d-sm-flex mg-sm-r-20">
                                    <img src="<?php echo base_url()."".$row['photo'];?>" class="rounded-circle wd-40" alt="Image">
                                </div><!-- d-flex -->
                                <div class="media-body mg-t-10 mg-sm-t-0">
                                    <h6 class="mg-b-5 tx-14"><?= $row['doctorName'];?> </h6>
                                    <p class="mg-b-0 tx-12"> <?= $row['designation'];?> </p>
                                </div><!-- media-body -->
                            </div><!-- media -->
                            <?php
                                $result = $this->dbmodel
                                    ->get_row(
                                        'hr_clinic.patient_doctor_dailylogs',
                                        'status',
                                        array( 'field1' => 'dateIn','field2' => 'doctorID' ),
                                        array( date('Y-m-d'), $row['doctorID'] ) );
                                if($result){   
                                    echo '<a href="#" class="btn btn-success pd-lg-x-20 mg-l-auto"> IN </a>';
                                }else{
                                    echo '<a href="#" class="btn btn-danger pd-lg-x-20 mg-l-auto"> OUT </a>';
                                } 
                                ?>  
                                </a>
                        </div><!-- list-group-item -->
                        <?php } ?>
                        
                        </div>   
                    
                    </div><!-- list-group -->
                </div><!-- col-lg-6 -->
            </div>
            <br>            
            <div class="card pd-20">                
                <h6 class="card-body-title"> IP PHONE DIRECTORIES </h6>                
                <div class="table-responsive">
                    <table id="datatable" class="table table-white table-bordered mg-b-0 tx-12">
                        <thead>
                            <tr>
                                <th class="wd-15p"> IP PHONE </th>
                                <th class="wd-15p"> GROUP </th>
                                <th class="wd-20p"> DEPT / NAME </th>
                            </tr>
                        </thead>    
                        <tbody>
                            <?php
                            foreach($iphone as $row){ 
                                echo "
                                <tr>
                                    <td> $row[ip_phone] </td>
                                    <td> $row[dir_group] </td>
                                    <td> $row[dept_and_names] </td>                                    
                                </tr>";
                            } ?>                           
                        </tbody>
                    </table>
                </div><!-- table-responsive -->               
            </div><!-- card -->
        </div><!-- col-8 -->
        <div class="col-lg-4 mg-t-15 mg-sm-t-20 mg-lg-t-0">
            <?php if($this->session->userdata('usertype') == 'supervisor'){ ?>
            <ul class="list-group widget-list-group bg-info">
                <li class="list-group-item rounded-top-0">
                    <a href="<?= base_url()?>supervisor/interview/lists" style='color:white;'>
                        <p class="mg-b-0"><i class="fa fa-cube tx-white-7 mg-r-8"></i><strong class="tx-medium">
                            Applicants For Interview </strong>  <span class="text-muted"> (1) </span>
                        </p>
                    </a>
                </li>

                <li class="list-group-item rounded-top-0">
                    <a href="<?= base_url()?>supervisor/epas/resignations" style='color:white;'>
                        <p class="mg-b-0"><i class="fa fa-cube tx-white-7 mg-r-8"></i><strong class="tx-medium">
                            Tagged for Resignation  </strong>  <span class="text-muted"> (for EPAS) </span>
                        </p>
                    </a>
                </li>
            </ul>
            <br>
            <?php } ?>
                           
            <div class="card bd-1">
                <div class="card-header card-header-default bg-dark">
                ANNOUNCEMENTS
                </div>                
                <div class="list-group widget-list-group">
                    <?php foreach($announcements as $row){ ?>
                        <div class="list-group-item pd-y-20 rounded-top-0">
                            <div class="media">
                                <div class="d-flex mg-r-10 wd-50">
                                    <i class="fa fa-file-text-o tx-info tx-40 tx"></i>
                                </div>
                                <div class="media-body">
                                    <a href="<?= base_url()?>employee/announcements/<?= $row['am_id'];?>" 
                                        data-toggle="tooltip" data-placement="top" title="Click to view the details"> 
                                        <h6 class="tx-inverse"> <?= $row['title'];?> </h6> 
                                        <p class="tx-13 mg-b-0"> <?= $row['short_desc'];?> </p>
                                        
                                    </a>
                                    <span class="tx-13"> Date Posted: 09/14/2023</span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>                                  
                </div>                
            </div>

            <br>        
            <div class="card bd-1">
                <div class="card-header card-header-default bg-dark">
                    MEMO
                </div>
                <ul class="list-group widget-list-group">                        
                    <?php
                    foreach($memos as $row){
                        ?>
                        <li class="list-group-item">
                            <p class="tx-13 mg-b-0"> <a href='<?= base_url()?>employee/memos/<?= $row['am_id'];?>'> <?= $row['title'];?>  </a> 
                            <br> Date Posted: 09/14/2023 
                            </p>
                        </li>  
                        <?php
                    }?>  
                    <li class="list-group-item">
                        <a href="<?= base_url()?>employee/memos/1"><i class="fa fa-angle-down mg-r-5"></i>View All Memos </a>
                    </li>         
                </ul>                
            </div>
            <br>  

            <div class="card pd-20">
                <h6 class="card-body-title"> BIRTHDAY GREETINGS FROM AGC</h6>
                <!-- <img src="<?= base_url()?>assets/images/bday-greetings.gif" width="360" height="350"> -->
                <!-- <div id="f1" class="ht-200 ht-sm-300"></div> -->
            </div><!-- card -->
             <script>

                // start

                const start = () => {
                    setTimeout(function() {
                        confetti.start()
                    }, 1000); // 1000 is time that after 1 second start the confetti ( 1000 = 1 sec)
                };

                //  Stop

                const stop = () => {
                    setTimeout(function() {
                        confetti.stop()
                    }, 10000); // 5000 is time that after 5 second stop the confetti ( 5000 = 5 sec)
                };

                start();
                stop();
            </script>
        </div><!-- col-4 -->
    </div><!-- row -->   
</div><!-- am-pagebody -->