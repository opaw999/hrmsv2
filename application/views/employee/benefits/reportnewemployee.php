 <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
         <div class="am-pagebody">

          <div class="card">
            <h4 class="card-body-title"><li class="fa fa-hand-o-right fa-lg" style="color:#d9230f"></i></li>&nbsp;&nbsp;NEW EMPLOYEE REPORTS</h4><br>
            <!-- <form 
              api-url="employee/qbe/excelreport"
              api-type="POST"
              onsubmit="event.preventDefault(); return handleFormSubmit();"
              id="form"> -->
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
                    <label class="form-control-label">Select Month<span class="tx-danger">*</span></label>
                    <select class="form-control wd-350 h-100 tx-12" name="permonth">
                        <option value = ''></option>
                        <?php foreach ($monthsList as $monthName) { ?>
                            <option value="<?= $monthName; ?>">Month of <?= $monthName; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div><!-- col-8 -->
         </div><!-- row--->


                <div class="form-layout-footer">
                  <!-- <button type="submit" class="btn btn-info mg-r-5" id="form-username" button-message="Saving...">PDF REPORT</button> -->
                  <a href='#' class='btn btn-danger' onclick='pdfrep()'>PDF REPORT</a>
                 <!-- <button type="submit" class="btn btn-info mg-r-5" id="form-username"    button-message="Saving...">EXCEL REPORT</button>                -->
                <a href="#" class="btn btn-success mg-r-5" onclick="excelrep()">EXCEL REPORT</a>

               </div>
              </div><!-- form-layout -->
        
          </div><!-- card -->
        </div>
      </div>
    
<script>

// In your JavaScript
function excelrep() {
    var cc = $("#company").val();
    var bc = $("#businessunit").val();
    var dc = $("#department").val();
    var sc = $("#section").val();
    var permonth = $("[name='permonth']").val();
  

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

     if(permonth == ''){
        Swal.fire({
                    icon: "info",
                    title: "Please don't leave the required fields empty!" ,
                    showDenyButton: false,
                    showCancelButton: true,
                    confirmButtonText: 'OK',    
                });
          // alert("Please don't leave the required fields empty!")
        }else {
            Swal.fire({
                    icon: "info",
                    title: "Are you sure to proceed?" ,
                    showDenyButton: false,
                    showCancelButton: true,
                    confirmButtonText: 'OK',    
                }).then((result) => {
                    if (result.isConfirmed) { 
                        window.open("<?= base_url('employee/qbe/excelreport') ?>?code=" + code + "&permonth=" + permonth, "_new");
                    }
                    
                }); 
        }
    }

function pdfrep()
{	
	 var cc = $("#company").val();
    var bc = $("#businessunit").val();
    var dc = $("#department").val();
    var sc = $("#section").val();
    var permonth = $("[name='permonth']").val();
  

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

    if(permonth == ''||cc == ''){
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
                          window.open("<?= base_url('employee/qbe/pdfreport') ?>?code=" + code + "&permonth=" + permonth, "_new");
                        }
                        
                    }); 
               } 
            }



</script>