

<div class="am-pagebody">
  
 <div class="card pd-20 pd-sm-30">
 
  <div class="row">      
    <div class="col-md-6">              
      <h6 class="card-body-title" style="font-size: 20px">Filtered Employees</h6>
    </div>
    <div class="col-sm-6 col-md-6">             
        <form>
          <button  type="button"
                                class="btn btn-success float-right"
                                modal-size=""
                                modal-route=""
                                modal-form="masterfile/modal_employees"
                                modal-skeleton="0"
                                modal-redirect= "masterfile/filter_employees/"
                                modal-id=""
                                modal-atype="POST"
                                modal-title="Filter Employees" 
                                onclick="modal(event)">Filter</button>
        </form>
         <div class="col-sm-11">
          <a type="button" href="#" class="btn btn-success float-right mr-2"  onclick="excelrep()">Generate Excel</a>
        </div>
    </div>
  </div>
  <br> 
  <br> 


   <!-- <p class="mg-b-20 mg-sm-b-30">Searching, ordering and paging ________ will be immediately added __ the table, as shown __ this example. </p> -->
    <!-- <input type="text" value='<?= $emptype ?>'> -->
   <div class="table-wrapper">
    <input type="hidden" id='xcode' value="<?= $code ?>">    
     <input type="hidden" id='emptype' value="<?= $emptype ?>"> 
     <table id="datatable" class="table table-white table-bordered mg-b-0 tx-12">
       <thead>
        <tr>
        <!-- <th class="wd-5p">No</th> -->
        <th class="wd-10p">Name</th>
        <th class="wd-5p">Business Unit</th>
        <th class="wd-5p">Department</th>
        <th class="wd-5p">Position</th>
        <th class="wd-5p">Emptype</th>
        <th class="wd-5p">Startdate</th>
        <th class="wd-5p">Eocdate</th>
        <th class="wd-5p">Status</th>
        </tr>
       </thead>
     </table>
   </div><!-- table-wrapper -->
 </div><!-- card -->
</div>


 <script>
function excelrep() {
    var code = $("#xcode").val();
    
    var emptype = $("#emptype").val();

  
    

    // Alert for testing purposes
 

    if (code === '') {
        Swal.fire({
            icon: "info",
            title: "Please don't leave the required fields empty!",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: 'OK',
        });
    } else {
        Swal.fire({
            icon: "info",
            title: "Are you sure to proceed?",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: 'OK',
        }).then((result) => {
            if (result.isConfirmed) {
                window.open("<?= base_url('masterfile/excel_per_bu') ?>?xcode=" + code + "&emptype=" + emptype, "_new");
            }
        });
    }
}

</script>
