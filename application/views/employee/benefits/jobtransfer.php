
<div class="am-pagebody">
  <div class="row row-sm">
    <div class="col-lg-12">
      <div class="card pd-20 pd-sm-30">
          <div class="row">          
            <div class="col-md-6">              
              <h6 class="card-body-title" style="font-size: 15px">JOBTRANSFER</h6>
            </div><br>
            <div class="col-md-6" align="right">            
              <div style="width:60%;border:px solid black;" align="right">  
                <form >
                       <button  type="button"
                                class="btn btn-success float-right"
                                modal-size=""
                                modal-route=""
                                modal-form="employee/modal_filter_jobtrans"
                                modal-skeleton="0"
                                modal-redirect= "employee/filter_jobtrans/"
                                modal-id=""
                                modal-atype="POST"
                                modal-title="Filter Employees" 
                                onclick="modal(event)">Filter</button><br><br><br>
              <!-- <select class="form-control" id="inputSelect3">
                <option>Select Year</option>
                <option>Option y</option>
                <option>Option z</option>
              </select> -->
                </form>
              </div><!-- /input-group -->
            </div>
          </div>
          <br>
          <div class="table-wrapper">
            <table id="datatable"  class="table table-white table-bordered mg-b-0 tx-12">
             <thead>
               <tr>
                 <th class="wd-15p"></th>
                 <th class="wd-5p"></th>
                 <th class="wd-10p"></th>
                 <th class="wd-10p"></th>
                 <th class="wd-15p"></th>
                 <th class="wd-15p"></th>
                 <th class="wd-5p"></th>
               </tr>
             </thead>
           </table>
         </div><!-- table-wrapper -->
     </div>
   </div><!-- card -->
 </div><!-- col-6 -->
</div>


