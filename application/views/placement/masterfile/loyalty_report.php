 <div class="am-pagebody">
        <div class="col-md-6">              
                <h6 class="card-body-title" style="font-size: 18px">GENERATE LOYALTY AWARDEES REPORT </h6>
        </div><br>
        <div class="card pd-20 pd-sm-0 ">
        <div class="row">
            <div class="col-md-12 show-form">	
                    <form 
                    api-url="placement/masterfile/insert_loyalty_entries"
                    api-type="POST"
                    onsubmit="event.preventDefault(); return handleFormSubmit();"
                    id="form">		
                        <div class="form-group">
                            <label for="years" class="tx-gray-800 tx-15" > Years in Service (Loyalty) <span style='color: red;'> * </span></label>    	
                            <select name="yrsinservice" class="form-control" width="100%">
                                <option> </option>
                                <option value="10"> 10 Years </option>
                                <option value="20"> 20 Years </option>
                                <option value="30"> 30 Years </option>
                                <option value="40"> 40 Years </option>
                            </select>		    	
                        </div>	
                          <label for="years" class="tx-gray-800 tx-18 tx-bold" > Year Awarded</label> 

                        <div class="form-group">
                            <label for="awarded" class="tx-gray-800 tx-15" > From Year  <span style='color: red;'> * </span></label>
                            <input type="text" required="" maxlength="4" width="100%" name="yrfrom" class="form-control" placeholder="2021">
                        </div>      
                        <div class="form-group">
                            <label for="awarded" class="tx-gray-800 tx-15" > To Year <span style='color: red;'> * </span></label>
                            <input type="text" required="" maxlength="4" width="100%" name="yrto" class="form-control" placeholder="2021">
                        </div>        	        

                        <a href='#' class='btn btn-success' onclick='xlsrep()'>GENERATE</a>  	<br><br>			
                        <i> Note: <span style='color: red;'> * </span> Required fields. </i> <br>			
                    </form>
                </div>
         </div>
    </div>
</div>


<script>
     function xlsrep()
        {	
        
          	var yrsinservice 	= $("[name='yrsinservice']").val();
		    var yrfrom 			= $("[name='yrfrom']").val();
		    var yrto 	        = $("[name='yrto']").val();
        
            if(yrsinservice == '' || yrfrom == '' || yrto == ''){
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
                            var $params = "yrsinservice=" + encodeURIComponent(yrsinservice) + "&yrfrom=" + encodeURIComponent(yrfrom) + "&yrto=" + encodeURIComponent(yrto);
                            window.open("<?= base_url('placement/masterfile/loyalty_report_xls') ?>?" + $params);
                        }
                        
                    });  
                 }
           }

</script>