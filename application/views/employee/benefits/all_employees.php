
<div class="am-pagebody">
  
 <div class="card pd-20 pd-sm-30">
 
  <div class="row">          
    <div class="col-md-6">              
      <h6 class="card-body-title" style="font-size: 20px">ALL EMPLOYEES</h6>
    </div>
    <div class="col-sm-6 col-md-6">             
        <form>
          <button  type="button"
                                class="btn btn-success float-right"
                                modal-size=""
                                modal-route=""
                                modal-form="employee/modal_all_employees"
                                modal-skeleton="0"
                                modal-redirect= "employee/filter_employees/"
                                modal-id=""
                                modal-atype="POST"
                                modal-title="Filter Employees" 
                                onclick="modal(event)">Filter</button>
        </form>
    </div>
  </div>
  <br> 
  <br> 


   <!-- <p class="mg-b-20 mg-sm-b-30">Searching, ordering and paging ________ will be immediately added __ the table, as shown __ this example. </p> -->

   <div class="table-wrapper">
     <table id="datatable" class="table table-white table-bordered mg-b-0 tx-12">
       <thead>
         <tr>
           <th class="wd-5p"></th>
           <th class="wd-15p"></th>
           <!-- <th class="wd-5p"></th> -->
           <th class="wd-5p"></th>
           <th class="wd-5p"></th>
           <th class="wd-10p"></th>
           <th class="wd-10p"></th>
           <th class="wd-10p"></th>
           <th class="wd-10p"></th>
         </tr>
       </thead>
     </table>
   </div><!-- table-wrapper -->
 </div><!-- card -->
</div>
