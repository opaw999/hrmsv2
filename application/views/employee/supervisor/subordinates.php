<div class="am-pagebody">
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card pd-20 pd-sm-30">               
                <div class="row">
                    <div class="col-md-9">
                        <h6 class="card-body-title" style='font-size:24px'> SUBORDINATES </h6> 
                    </div>
                    <div class="col-md-3 text-right">
                        <a class="btn btn-success btn-sm" title="Export List of Subordinates" href="#" id="export-sub" > <i class="icon ion-stats-bars"></i> Export </a>
                        <a class="btn btn-primary btn-sm" title="View Images of Subordinates" href="#images"  onclick="loadImages()"> <i class="icon ion-images"></i> View </a> 
                        <a class="btn btn-secondary btn-sm" title="View List of Subordinates" href="<?= base_url();?>supervisor/subordinates" > <i class="icon ion-images"></i> List </a> 
                    </div>
                </div>                  

                <div class="table-wrapper" id="table-container">
                    <table id="datatable" class="table table-white table-bordered mg-b-0 tx-12">
                        <thead>
                            <tr>
                                <th class="wd-5p"> EMPID </th>
                                <th class="wd-20p"> NAME </th>
                                <th class="wd-20p"> POSITION</th>
                                <th class="wd-15p"> EMPTYPE </th>
                                <th class="wd-20p"> DEPARTMENT </th>
                                <th class="wd-10p"> DATE HIRED </th>
                                <th class="wd-15p"> ACTION </th>
                            </tr>
                        </thead> 
                    </table>
                </div>
                
                <div id="image-container">   
                </div>
                <div id="load-more-div" style="display:none" class="text-center"> <br>
                    <button id="load-more-button" class="btn btn-primary"> Show More <i class="icon ion-loop"></i> </button>                    
                </div>
            </div>
            <button id="scrollToTopBtn" class="scroll-to-top-btn" title="Scroll to Top">
                <i class="icon ion-arrow-up-a"></i>
            </button>       
        </div>
    </div>
</div>