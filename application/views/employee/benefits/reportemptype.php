
        <div class="am-pagebody">
          <div class="card">
           <h6 class="card-body-title"><li class="fa fa-hand-o-right fa-lg" style="color:#d9230f"></i></li>&nbsp;EMPLOYEE TYPE REPORT</h6>
           <br>
            <div class="form-layout">
                <div class="row mg-b-25">
                  <div class="col-lg-8">
                    <div class="form-group mg-b-10-force">
                      <label class="form-control-label">Company<span class="tx-danger">*</span></label>
                    <select  class="form-control wd-350 h-100 tx-12" id="company" name="company" onchange="getLocation(this.value,'businessunit','employee/filter/bunit')">  
                            <option value=''>Select Company</option>
                                    <?php foreach($company as $comp): 
                                    ?>
                                        
                                    <option value="<?= $comp['company_code']?>"><?=$comp['acroname'];?></option>
                                <?php endforeach;?>
                    </select>
                    </div>
                  </div><!-- col-8 -->
                  <div class="col-lg-8">
                    <div class="form-group mg-b-10-force">
                      <label class="form-control-label">Business Unit<span class="tx-danger">*</span></label>
                      <select class="form-control wd-350 h-100 tx-12"  id="businessunit" name="businessunit"  onchange="getLocation(this.value,'department','employee/filter/dept')">
                        
                        </select>
                    </div>
                  </div><!-- col-8 -->
                  <div class="col-lg-8">
                    <div class="form-group mg-b-10-force">
                      <label class="form-control-label">Department<span class="tx-danger">*</span></label>
                       <select class="form-control wd-350 h-100 tx-12" id="department" name="department"  onchange="getLocation(this.value,'section','employee/filter/sect')">
                       
                        </select>
                    </div>
                  </div><!-- col-8 -->
                  <div class="col-lg-8">
                    <div class="form-group mg-b-10-force">
                      <label class="form-control-label">Section</label>
                      <select class="form-control wd-350 h-100 tx-12" id="section" name="section" onchange="getLocation(this.value,'subsection','employee/filter/subsect')">
                        
                        </select>
                    </div>
                  </div><!-- col-8 -->
                  <div class="col-lg-8">
                    <div class="form-group mg-b-10-force">
                      <label class="form-control-label">Sub-section</label>
                       <select class="form-control wd-350 h-100 tx-12" id="subsection">
                       
                        </select>
                    </div>
                  </div><!-- col-8 -->
                  <div class="col-lg-8">
                    <div class="form-group mg-b-10-force">
                        <label class="form-control-label">Select Emptype<span class="tx-danger">*</span></label>
                        <select name='emptype' id='emptype' class='form-control wd-350 h-100 tx-12'>
                        <option value=''></option>
                        <option value='8'>All</option>
                        <option value='1'>Contractual</option>
                        <option value='2'>Regular</option>							
                        <option value='3'>Back Up</option>
                        <option value='4'>Probitionary</option>
                        <option value='5'>Summer Job</option>
                        <option value='6'>PTA/PTP/Regular Partimer</option>
                        <option value='7'>Seasonal</option>
                        </select>
                    </div>
                </div><!-- col-8 -->
            </div><!-- row--->


            <div class="form-layout-footer">
              <!-- <button type="submit" class="btn btn-info mg-r-5" id="form-username" button-message="Saving...">PDF REPORT</button> -->
              <a href='#' class='btn btn-success' onclick='xlsrep()'>EXPORT EXCEL</a> 
            </div>
          </div><!-- form-layout -->
        
    </div><!-- card -->

    <script>

      function xlsrep()
        {	
        
          	var cc = $("#company").val();
            var bc = $("#businessunit").val();
            var dc = $("#department").val();
            var sc = $("#section").val();
            var emptype= $("[name='emptype']").val();
          

        var code;
            if (sc !== "") {
                code = sc;
            } else if (dc !== "") {
                code = dc;
            } else if (bc !== "") {
                code = bc;
            } else if (cc !== "") {
                code = cc;
            } else {
                code = '';
            }

            if(emptype == '' || cc == ''){
               Swal.fire({
                    icon: "info",
                    title: "Please don't leave the required fields empty!" ,
                    showDenyButton: false,
                    showCancelButton: true,
                    confirmButtonText: 'OK',    
                });
             }else{

               Swal.fire({
                        icon: "info",
                        title: "Please be patient while waiting for the report to finish, thank you!" ,
                        showDenyButton: false,
                        showCancelButton: true,
                        confirmButtonText: 'OK',    
                    }).then((result) => {
                        if (result.isConfirmed) { 
                          window.open("<?= base_url('employee/qbe/excelreportemptype') ?>?code=" + code + "&emptype=" + emptype);
                        }
                        
                    });  
                 }
           }

    </script>

   