<div class="am-pagebody">
    <div class="row row-sm">        
        <div class="col-lg-8">
            <div class="card pd-20">
                <h6 class="card-body-title"> MEMOS </h6>                
                <H4> <?= $result_memos->title ;?> </H4>
                Date Posted: 09/14/2023
                <!-- <p class="mg-b-20 mg-sm-b-30"> </p> -->
                <?php
                    $res = $this->dbmodel
                    ->get_row(
                        'announcements_details',
                        '*',
                        array( 'field1' => 'am_id'),
                        array( $result_memos->am_id )
                    ); 
                    if($res)
                    {
                        ?> <img src="<?= base_url().$res->images;?>"> <?php
                    }
                ?>                
                <p class="mg-b-0"> <small> Date Posted: <?= $result_memos->date_posted ;?> </small> </p>
            </div><!-- card -->
        </div>
        <div class="col-lg-4">
            <div class="card pd-20">
                <h6 class="card-body-title"> MEMOS </h6>  
                <?php
                    foreach($memos as $row){
                        ?>
                        <div class="list-group widget-list-group">
                            <div class="list-group-item pd-y-20 rounded-top-0">
                                <div class="media">
                                    <div class="d-flex mg-r-10 wd-50">
                                        <i class="fa fa-file-text-o tx-info tx-40 tx"></i>
                                    </div>
                                    <div class="media-body">                                
                                        <a href="<?= base_url()?>employee/memos/<?= $row['am_id'];?>"> <h6 class="tx-inverse"> <?= $row['title'];?> </h6> </a>
                                        Date Posted: 09/14/2023
                                        <!-- <p class="mg-b-0"> <?= $row['short_desc'];?> </p> -->
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>   
</div>