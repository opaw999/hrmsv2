
 <style>
 
 [class^="search-resultemployee-"] {
    box-shadow: 5px 5px 5px #ccc; 
    margin-left: -5px; 
    background-color: white;
    width: 88%;
    border-radius: 3px;
    font-size: 15px;
    padding: 8px 10px;
    display: block;
    position: absolute;
    z-index: 9999;
    max-height: 300px;
    overflow-y: scroll;
    overflow: auto;
  }

  #name1
  {
     margin-top: 40%;
  }
  #sssno1
  {
     margin-top: 167px;
  }
  #philhealth1
  {
     margin-top: 233px;
  }
  #pagibigrtn1
  {
     margin-top: 299px;
  }
  #pagibig1
  {
     margin-top: 366px;
  }
  #tinno1
  {
     margin-top: 431px;
  }

  
</style>
<div class="am-pagebody">
        <div class="row row-sm mg-t-5">
          <div class="col-lg-3">
            <div class="card pd-20 pd-sm-20 form-layout form-layout-4">
              <h6 class="card-body-title">🔍&nbsp;SEARCH EMPLOYEE BY </h6><br>
              <div class="row mg-t-5">
                <label class="col-sm-3 form-control-label"></label>
                <div class="w-100 pd-sm-10 mg-sm-t-0">
                <input type="text" id='search-name' name='search-name' class="form-control" placeholder="Lastname, Firstname" onkeyup="employee_search(this.value, 'name')">
                </div>
              </div>
                 <div class="search-resultemployee-name" id='name1' style="display:none; border:1px solid #ccc "></div>   
              <div class="row mg-t-5">
                <label class="col-sm-2 form-control-label"></label>
                <div class="w-100 pd-sm-10 mg-sm-t-0">
                 <input type="text" name='search-sssno' class="form-control" placeholder="SSS NUMBER" onkeyup="employee_search(this.value, 'sssno')" data-inputmask='"mask": "99-9999999-9"' data-mask>
                </div>
              </div>
              <div class="search-resultemployee-sssno" id='sssno1' style="display:none; border:1px solid #ccc "></div>   
              <div class="row mg-t-5">
                <label class="col-sm-2 form-control-label"></label>
                <div class="w-100 pd-sm-10 mg-sm-t-0">
                  <input type="text" name='search-philhealth' class="form-control" placeholder="Philhealth" onkeyup="employee_search(this.value, 'philhealth')" data-inputmask='"mask": "99-999999999-9"' data-mask>
                </div>
              </div>
               <div class="search-resultemployee-philhealth"  id='philhealth1' style="display:none; border:1px solid #ccc "></div> 
              <div class="row mg-t-5">
                <label class="col-sm-2 form-control-label"></label>
                <div class="w-100 pd-sm-10 mg-sm-t-0">
                   <input type="text" name='search-pagibigrtn' class="form-control" placeholder="Pag-ibig RTN" onkeyup="employee_search(this.value, 'pagibigrtn')" data-inputmask='"mask": "9999-9999-9999"' data-mask>
                </div>
              </div>
               <div class="search-resultemployee-pagibigrtn" id='pagibigrtn1' style="display:none; border:1px solid #ccc "></div>   
                <div class="row mg-t-5">
                <label class="col-sm-2 form-control-label"></label>
                <div class="w-100 pd-sm-10 mg-sm-t-0">
                   <input type="text" name='search-pagibig' class="form-control" placeholder="Pag-ibig NO" onkeyup="employee_search(this.value, 'pagibig')" data-inputmask='"mask": "9999-9999-9999"' data-mask>
                </div>
              </div>
               <div class="search-resultemployee-pagibig"  id='pagibig1'style="display:none; border:1px solid #ccc "></div>   
                <div class="row mg-t-5">
                <label class="col-sm-2 form-control-label"></label>
                <div class="w-100 pd-sm-10 mg-sm-t-0">
                   <input type="text" name='search-tinno' class="form-control" placeholder="TIN NO." onkeyup="employee_search(this.value, 'tinno')" data-inputmask='"mask": "999-999-999-999"' data-mask>
                </div>
              </div>  
               <div class="search-resultemployee-tinno"  id='tinno1' style="display:none; border:1px solid #ccc "></div>   
              <div class="row mg-t-5">
                  <div class="col-sm-12 d-flex justify-content-center">
                    <button type="button" class="btn btn-success" onclick="resetFields()">Reset Fields</button>
                  </div>
              </div> 
            </div><!-- card -->
          </div><!-- col-6 -->
          <div class="col-lg-9 mg-t-25 mg-lg-t-0">
            <div class="card pd-5 pd-sm-20">
              <!-- <h6 class="card-body-title"></h6> -->
                 <div class="card">
                      <div class="table-wrapper">
                        <table id="datatable" class="table table-white table-bordered mg-b-0 tx-12 w-100" >
                          <thead>
                            <tr>
                              <th class="wd-5p">No</th>
                              <th class="wd-5p">Name</th>
                              <th class="wd-5p">Position</th>
                              <th class="wd-5p">Status</th>
                              <th class="wd-10p">SSSno</th>
                              <th class="wd-10p">Philhealth</th>
                              <th class="wd-10p">Pagibig RTN</th>
                              <th class="wd-10p">Pagibig NO</th>
                              <th class="wd-10p">Tin NO</th>
                              <th class="wd-5p"></th>
                            </tr>
                          </thead>
                        </table>
                      </div><!-- table-wrapper --> 
                    </div>
                  </div><!-- card --> 
                </div><!-- card -->
              </div><!-- col-6 -->
            </div>

             <script>

            
                    
          var timer;
          var isSSSNO = false;

          function employee_search(key, searchType) {
              clearTimeout(timer);
              $(".search-resultemployee-"+searchType).show();
              var str = key.trim();

              if (str === '') {
                  $(".search-resultemployee-"+searchType).hide(); 
                  return;
              }

              // Delay the search by 500ms after the user stops typing
              timer = setTimeout(function () {
                  $.ajax({
                      type: "POST",
                      url: "<?= base_url('employee/search/searchemployee');?>",
                      data: { str: str, searchType: searchType },
                      success: function(data) {
                          data = data.trim();
                          if (data !== "") {
                              $(".search-resultemployee-"+searchType).show().html(data);
                          } else {
                              $(".search-resultemployee-"+searchType).hide();
                          }
                      }
                  });
              }, 500);
          }

        
        

          function getProfile(id,ttype) {
              var idSplit = id.split("*");
              var empId = idSplit[0].trim();
              var name = idSplit[1].trim();
            
              
               if (ttype === 'sssno') {
                    $("[name='sssno']").val(name);
                } else if (ttype === 'philhealth') {
                    $("[name='philhealth']").val(name);
                } else if (ttype === 'pagibigrtn') {
                    $("[name='pagibigrtn']").val(name);
                } else if (ttype === 'pagibig') {
                    $("[name='pagibig']").val(name);
                } else if (ttype === 'tinno') {
                    $("[name='tinno']").val(name);
                } else {
                    // Default behavior for other search types
                    $("[name='search-"+ttype+"']").val(name);
                }
              dataTableInstance = DTable('employee/search/searchallemployee/' + empId, true);

              // Hide all search result elements
              $(".search-resultemployee-name").hide();
              $(".search-resultemployee-sssno").hide();
              $(".search-resultemployee-philhealth").hide();
              $(".search-resultemployee-pagibigrtn").hide();
              $(".search-resultemployee-pagibig").hide();
              $(".search-resultemployee-tinno").hide();
            }


            function resetFields() {

            window.location.reload();

            }

            
           
        </script>