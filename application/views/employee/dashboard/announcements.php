<div class="am-pagebody">
    <?php if(isset($result_announce->title)){ ?>  
    <div class="row row-sm">        
        <div class="col-lg-8">
            <div class="card pd-20">
                <h6 class="card-body-title"> ANNOUNCEMENTS </h6>
                
                <H4> <?= $result_announce->title ;?> </H4>
                <p class="mg-b-0"> <?= $result_announce->short_desc ;?> </p>
                
                <p class="mg-b-20 mg-sm-b-30"> <?= $result_announce->details ;?> </p>
                <p class="mg-b-0"> <small> Date Posted: <?= $result_announce->date_posted ;?> </small> </p>
            </div><!-- card -->
        </div>
        <div class="col-lg-4">
            <div class="card pd-10"> 
                <h6 class="card-body-title"> <br> &nbsp; ANNOUNCEMENTS </h6>  
                <div class="col-md-12">
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
                                        <p class="tx-14 mg-b-0"> <?= $row['short_desc'];?> </p>
                                        
                                    </a>
                                    Date Posted: 09/14/2023
                                </div>
                            </div>
                        </div>
                    <?php } ?>                                  
                    </div>
                </div>                
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