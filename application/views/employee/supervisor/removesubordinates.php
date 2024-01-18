<div class="am-pagebody">
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card pd-20 pd-sm-30">               
                  <div class="row">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon bg-transparent">
                            <label class="ckbox wd-16">
                                <input type="checkbox" id='chkAll' name= 'chkall'/><span></span>
                            </label> &nbsp; Check all per page
                            </span>
                            &nbsp;
                             <button
                                    type="button"
                                    class="btn btn-success active"
                                    modal-size="modal-lg"
                                    modal-route="supervisor/subordinates/removesub"
                                    modal-form="supervisor/subordinates/modalreason"  
                                    modal-redirect= ""                             
                                    modal-skeleton="0"
                                    modal-id=""
                                    modal-atype="POST"
                                    modal-title="Reason for Removing Subordinates"
                                    onclick="modal(event)">
                                    <i class="icon ion-flag">&nbsp;REQUEST TO REMOVE SUBORDINATES</i>
                             </button>                     
                        </div>
                    </div>
                </div> 
                <br>              
                <div class="table-wrapper" id="table-container">
                    <input type="hidden" name="supId" value="<?= $this->session->userdata('emp_id')?>">
                    <table id="datatable" class="table table-white table-bordered mg-b-0 tx-12">
                        <thead>
                            <tr>
                                <th class="wd-5p"></th>
                                <th class="wd-5p"> EMPID </th>
                                <th class="wd-20p"> NAME </th>
                                <th class="wd-20p"> POSITION</th>
                                <th class="wd-15p"> EMPTYPE </th>
                                <th class="wd-20p"> DEPARTMENT </th>
                                <th class="wd-10p"> DATE HIRED </th>
                                <!-- <th class="wd-15p"> ACTION </th> -->
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