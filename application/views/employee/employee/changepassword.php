<div class="card">
           <h6 class="card-body-title"><li class="fa fa-hand-o-right fa-lg" style="color:#d9230f"></i></li>&nbsp;&nbsp;CHANGE PASSWORD</h6><br>
           <form
              api-url="employee/updatepassword"
              api-type="POST"
              onsubmit="event.preventDefault(); return handleFormSubmit();"
              id="form">
            <div class="form-layout">
              <div class="row mg-b-25">
                <div class="col-lg-8">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">Current Password: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="password" autocomplete="off" required="true" name="currentPassword" id="currentPassword"  >
                  </div>
                </div><!-- col-8 -->
                <div class="col-lg-8">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">New Password: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="password" autocomplete="off" required="true" name="newPassword" id="newPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                  </div>
                </div><!-- col-8 -->
                <div class="col-lg-8">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">Re-type New Password: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="password" autocomplete="off" required="true" name="confirmPassword" id="confirmPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                  </div>
                </div><!-- col-8 -->
              </div><!-- row--->
              <div class="alert alert-danger" role="alert">
                    <i class="fa fa-exclamation-triangle"></i> Password must be alphanumeric and must contain letters (uppercase and lowercase) and numbers.
              </div>
              <div class="form-layout-footer">
                <button type="submit" class="btn btn-info mg-r-5" id="form-username" button-message="Saving...">Submit</button>
              </div><!-- form-layout-footer -->
            </div><!-- form-layout -->
          </form>
        </div><!-- card -->