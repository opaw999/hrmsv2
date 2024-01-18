    <div class="am-pagebody">
        <div class="col-md-6">              
                <h6 class="card-body-title" style="font-size: 18px">SEARCH LOYALTY AWARDEES HISTORY </h6>
        </div><br>
        <div class="card pd-20 pd-sm-0 ">
            <div class="row">
                <div class="col-md-12 show-form">	
                            <div class="form-group">
                                <label for="name" class="tx-gray-800 tx-15" >Employee Name<span style='color: red;'> * </span></label>
                                <div class="input-group">
                                    <input type="text" required="" name="empid" onkeyup="namesearch(this.value)" class="form-control" placeholder="Lastname, Firstname" autocomplete="off">
                                    <span class="input-group-btn">
                                        <button type="button" onclick="search()" class="btn btn-success" name="search">Search &nbsp;<i class="glyphicon glyphicon-search"></i></button>
                                    </span>
                                </div>
                                <div class="search-results" style="display:none;"></div>		    
                            </div>	
                            <div>
                        <div id='search_results'> </div>	
                    </div>	  
                </div>     
            </div>
        </div>
    </div>
<script>

    function search()
    {
			var empid = $("[name='empid']").val();
			$.ajax({
				type : "POST",
				url  : "<?= base_url('placement/masterfile/viewresult_loyalty');?>",
				data : { empid : empid },
				success : function(data){
					$("#search_results").html(data);					
			    } 
			});
	}

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
	