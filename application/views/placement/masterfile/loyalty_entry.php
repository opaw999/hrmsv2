 <div class="am-pagebody">
        <div class="col-md-6">              
                <h6 class="card-body-title" style="font-size: 18px">ENTRY ON LOYALTY AWARDEES</h6>
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
                            <label for="name" class="tx-gray-800 tx-15" >Employee Name<span style='color: red;'> * </span></label>
                            <div class="input-group">
                                <input type="text" required="" name="empid" onkeyup="namesearch(this.value)" class="form-control" placeholder="Lastname, Firstname" autocomplete="off">
                                <span class="input-group-btn">
                                    <button class="btn btn-info" name="search">Search &nbsp;<i class="glyphicon glyphicon-search"></i></button>
                                </span>
                            </div>
                            <div class="search-results" style="display:none;"></div>		    
                        </div>		          	

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

                        <div class="form-group">
                            <label for="awarded" class="tx-gray-800 tx-15" > Year Awarded <span style='color: red;'> * </span></label>
                            <input type="text" required="" maxlength="4" width="100%" name="yrawarded" class="form-control" placeholder="2021">
                        </div>           	        

                       <button type="submit" id="form-button" class="btn btn-success" button-message="Submitting...">Submit</button> 	<br><br>			
                        <i> Note: <span style='color: red;'> * </span> Required fields. </i> <br>			
                    </form>
                </div>
         </div>
    </div>
</div>
	

	<script>

        function namesearch(key)
	{
	    $(".search-results").show();

	    var str = key.trim();
	    $(".search-results").hide();
	    if(str == '') {
	        $(".search-results-loading").slideUp(100);
	    }
	    else
	    {
			$.ajax({
				type : "POST",
				url  : "<?= base_url('placement/masterfile/findEmployee'); ?>",
				data : { str : str},
				success : function(data){
			  		data = data.trim();
					if(data != ""){
					  	$(".search-results").show().html(data);
					} else {
						$(".search-results").hide();
					}
			    } 
			});
	    }
	}

	function getEmpId(id)
	{
		var id = id.split("*");
		var empId = id[0].trim();  
		var name = id[1].trim();	

		$("[name='empid']").val(empId+" * "+name);
		$(".search-results").hide();  
	}
	
	
	</script>
	