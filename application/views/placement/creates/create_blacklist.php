<div class="am-pagebody">
  <div class="card pd-20 pd-sm-30">
    <div class="col-md-6">              
      <h6 class="card-body-title" style="font-size: 15px">Add New Blacklist Entry</h6>
    </div><br>
    <div class="row row-sm">
      <div class="col-lg-12">
        <div class="row">
          <div class="col">
            <div class="form-group">
                <i>Click browse </i>&nbsp;
                <button
                  class="btn btn-primary btn-sm"
                  href="#"
                  modal-size="modal-xl wd-1000"
                  modal-route=""
                  modal-dtapi=""
                  modal-skeleton="0"
                  modal-form='placement/creates/modal_browse_blacklist'
                  modal-id=""
                  modal-atype="POST"
                  modal-button="false"
                  modal-title="Browse" 
                  onclick="modal(event)">
                  <i class="icon ion-globe"></i> Browse
                </button><br>
                <form  
                api-url="placement/creates/update_blacklist"
                api-type="POST"
                onsubmit="event.preventDefault(); return handleFormSubmit();"
                id="form"><br>
                <input type="text" name="namesearch" id='namesearch' value="<?= $names ?>" class="form-control tx-12" readonly>
            </div><!-- form-group -->  
          </div><!-- col -->
        </div>
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label class="form-control-label"> Reason: </label>
              <textarea class="form-control" id='reason' name="reason" disabled></textarea>
            </div><!-- form-group -->  
          </div><!-- col -->
        </div>
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label class="form-control-label"> Date Blacklisted:</label>
              <input type="date" class="form-control" id='datebls' name="datebls" value="<?= date('Y-m-d') ?>" disabled>
            </div><!-- form-group -->  
          </div><!-- col -->
          <div class="col">
            <div class="form-group">
              <label class="form-control-label"> Reported By:  </label>
              <input type="text" class="form-control" id='reportedby' name="reportedby" disabled>
            </div><!-- form-group -->  
          </div><!-- col -->
        </div>
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label class="form-control-label"> Birthday: </label>
              <input type="date" class="form-control" id='bdays' name="bdays" disabled>
            </div><!-- form-group -->    
          </div><!-- col -->
          <div class="col">
            <div class="form-group">
              <label class="form-control-label"> Address: </label>
              <input type="text" class="form-control" id='addr' name="addr" disabled>
            </div><!-- form-group -->    
          </div><!-- col -->
        </div><!-- row -->
        <div>
          <button type="submit" id="form-button" class="btn btn-success" button-message="Submitting..." >SUBMIT</button>
        </div>
      </form><br>
    </div>
     <div class="alert alert-danger">
       Note: Current Status will be updated to blacklisted according to the effectivity of date blacklisted.
     </div>
  </div>
</div>

