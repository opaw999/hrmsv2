
        <div class="am-pagebody">
          <div class="card">
           <h6 class="card-body-title"><li class="fa fa-hand-o-right fa-lg" style="color:#d9230f"></i></li> NO SSS, PHILHEALTH, PAGBIG REPORT</h6>
           <br>
           <!-- <form
              api-url=""
              api-type="POST"
              onsubmit=""
              id="form1"> -->
            <div class="form-layout">
              <div class="row mg-b-25">
                <div class="col-lg-8">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">Company<span class="tx-danger">*</span></label>
                    <select 
                            class="form-control wd-350 h-100 tx-12" id="company" onchange="getLocation(this.value,'businessunit','employee/filter/bunit')">  
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
                      <select class="form-control wd-350 h-100 tx-12"  id="businessunit"  onchange="getLocation(this.value,'department','employee/filter/dept')">
                      
                        </select>
                  </div>
                </div><!-- col-8 -->
                <div class="col-lg-8">
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label">Department<span class="tx-danger">*</span></label>
                    <select class="form-control wd-350 h-100 tx-12" id="department"  onchange="getLocation(this.value,'section','employee/filter/sect')">
                        
                        </select>
                  </div>
                </div><!-- col-8 -->
                  <div class="col-lg-8">
                    <div class="form-group mg-b-10-force">
                      <label class="form-control-label">Section</label>
                      <select class="form-control wd-350 h-100 tx-12" id="section" onchange="getLocation(this.value,'subsection','employee/filter/subsect')">
                        
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
                      <label class="form-control-label">Benefits<span class="tx-danger">*</span></label>
                       <select class="form-control wd-350 h-100 tx-12" name='benefits' id='benefits'>
                        <option></option>
                        <option value='nosss'> No SSS </option>
                        <option value='noph'> No Philhealth </option>
                        <option value='nopgrtn'> No Pagibig RTN </option>
                        <option value='nopgno'> No Pagibig MID</option>
                        </select>
                    </div>
                  </div><!-- col-8 -->
              </div><!-- row--->
              <div class="form-layout-footer">
                <!-- <button type="submit" class="btn btn-info mg-r-5" id="form-button" button-message="Saving...">Generate in PDF</button> -->
                <button type='button' name='submit-pdf' class='btn btn-danger mg-r-5' onclick='pdf_rep()'> Generate in PDF</button>
              </div><!-- form-layout-footer -->
            </div><!-- form-layout -->
          <!-- </form> -->
        </div><!-- card -->
      </div><!-- am page-body -->
  <script>

    function pdf_rep()
      {	
        var cc = $("#company").val();
        var bc = $("#businessunit").val();
        var dc = $("#department").val();
        var sc = $("#section").val();
        // var permonth = $("[name='permonth']").val();
      

      
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
  
        var ben = document.getElementById('benefits').value;

        if(ben =='' || cc == ''){
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
                        title: "Are you sure to proceed?" ,
                        showDenyButton: false,
                        showCancelButton: true,
                        confirmButtonText: 'OK',    
                    }).then((result) => {
                        if (result.isConfirmed) { 
                          window.open("<?= base_url('employee/qbe/pdfreportbenefits') ?>?code=" + code + "&ben=" + ben, "_new");
                        }    
                    }); 	
              }
            }
     

    </script>

 
      
