<div class="card">
            <h6 class="card-body-title"><li class="fa fa-hand-o-right fa-lg" style="color:#d9230f"></i></li>&nbsp;&nbsp;CHANGE USERNAME</h6><br>
            <form 
              api-url="employee/updateusername"
              api-type="POST"
              onsubmit="event.preventDefault(); return handleFormSubmit();"
              id="form">
              <div class="form-layout">
                <div class="row mg-b-25">
                  <div class="col-lg-8">
                    <div class="form-group mg-b-10-force">
                      <label class="form-control-label">Current Username: <span class="tx-danger">*</span></label>
                      <input class="form-control" type="text" name="currentUsername" autocomplete="off" required="true" value = "<?=$this->session->userdata('username');?>" readonly>
                    </div>
                  </div><!-- col-8 -->
                  <div class="col-lg-8">
                    <div class="form-group mg-b-10-force">
                      <label class="form-control-label">New Username: <span class="tx-danger">*</span></label>
                      <input class="form-control" type="text" name="newUsername" autocomplete="off" required="true">
                    </div>
                  </div><!-- col-8 -->
                  <div class="col-lg-8">
                    <div class="form-group mg-b-10-force">
                      <label class="form-control-label">Re-type New Username: <span class="tx-danger">*</span></label>
                      <input class="form-control" type="text" name="confirmUsername" autocomplete="off" required="true">
                    </div>
                  </div><!-- col-8 -->
                </div><!-- row--->
                <div class="alert alert-danger" role="alert">
                    <i class="fa fa-exclamation-triangle"></i> Username should be unique, therefore you are advised to use a username that is relevant to you or to your name.
                </div>
                <div class="form-layout-footer">
                  <button type="submit" class="btn btn-info mg-r-5" id="form-username" button-message="Saving...">Submit</button>
                </div><!-- form-layout-footer -->
              </div><!-- form-layout -->
            </form>
          </div><!-- card -->