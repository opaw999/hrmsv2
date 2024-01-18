
    <div class="am-pagebody">
        <div class="card">
           <h6 class="card-body-title"></li></h6>
               <div class="form-layout-footer ">
                 <div class="alert alert-danger" role="alert">
                    You can Generate Regular/Contractual Employees From Plaza Marcela, ICM , Alturas Here!!
                </div>
              <!-- <button type="submit" class="btn btn-info mg-r-5" id="form-username" button-message="Saving...">PDF REPORT</button> -->
              <center><a href='#' class='btn btn-info' onclick='xlsrep()'>EXPORT EXCEL</a></center> 
            </div>
        </div><!-- card -->
    </div><!-- am page-body --> 
<script>
     function xlsrep()
        {	 
            Swal.fire({
                        icon: "info",
                        title: "Please be patient while waiting for the report to finish, thank you!" ,
                        showDenyButton: false,
                        showCancelButton: true,
                        confirmButtonText: 'OK',    
                    }).then((result) => {
                        if (result.isConfirmed) { 
                            window.open("<?= base_url('employee/qbe/excelregularcontractual') ?>");
                        }    
                    }); 	
        }
</script>