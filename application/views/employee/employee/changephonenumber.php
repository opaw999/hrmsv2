
<div class="card">
          <h6 class="card-body-title"><li class="fa fa-hand-o-right fa-lg" style="color:#d9230f"></i></li>&nbsp;&nbsp;Update Phone Numbers</h6><br>
          <form
            api-url="employee/update_phone_number"
            api-type="POST"
            onsubmit="event.preventDefault(); return handleFormSubmit();"
            id="form"
          >
            <div class="form-layout">
              <div class="row mg-b-25">
                <div class="col-lg-8">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">Current Phone Number: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="text" autocomplete="off" placeholder="Enter your 11-digit mobile number"  value="<?= $contact->contactno?>" required="true" name="phoneNumber" id="phoneNumber" readonly >
                  </div>
                </div><!-- col-8 -->
                <div class="col-lg-8">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">New Phone Number: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="text" autocomplete="off" required="true"  minlength="11" maxlength="13" name="newphoneNumber" id="newphoneNumber">
                  </div>
                </div><!-- col-8 -->
                <div class="col-lg-8">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">Re-type New Phone Number: <span class="tx-danger">*</span></label>
                   <input class="form-control" type="text" autocomplete="off"  required="true"  minlength="11" maxlength="13"  name="confirmphoneNumber" id="confirmphoneNumber">
                  </div>
                </div><!-- col-8 -->
              </div><!-- row--->
              <div class="alert alert-danger" role="alert">
                    <i class="fa fa-exclamation-triangle"></i> The field can only contain numeric characters and valid mobile number in the Philippines.</div>
              <div class="form-layout-footer">
                <button type="submit" class="btn btn-info mg-r-5" id="form-username" button-message="Saving...">Submit</button>
              </div><!-- form-layout-footer -->
            </div><!-- form-layout -->
          </form>
        </div><!-- card -->
