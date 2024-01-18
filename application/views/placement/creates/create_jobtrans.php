	
<div class="am-pagebody">
    <div class="card pd-20 pd-sm-30">
        <div class="col-md-6">
            <h6 class="card-body-title" style="font-size: 15px">JOB TRANSFER</h6>
        </div><br>
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="tx-gray-800"><span class='rqd tx-gray-800'>*</span> Employee Name</label>
                    <div class="input-group">
                        <input type="text" required name="empid" id='empidClearance' onkeyup="namesearch(this.value)"
                            class="form-control" placeholder="Lastname, Firstname" value="" autocomplete="off"
                            required="">
                        <span class="input-group-btn">
                            <button class="btn btn-info" name="search">Search &nbsp;<i
                                    class="glyphicon glyphicon-search"></i></button>
                        </span>
                    </div>
                </div>
                <div class="search-results" style="display:none;"></div>
            </div>
        </div>
        	<div class='col-md-12'>	
				<span id='_loading'></span>		
				<div style="background-color:white;" class="row">
					<div class="col-md-12 show-form">		

					</div>				
				</div>
			</div> 
    </div>
</div>

<script>
    function namesearch(key) {
        $(".search-results").show();

        var str = key.trim();
        $(".search-results").hide();
        if (str == '') {
            $(".search-results-loading").slideUp(100);
        } else {
            $.ajax({
                type: "POST",
                url: "<?= base_url('placement/creates/searchemployee');?>",
                data: {
                    str: str
                },
                success: function (data) {
                    data = data.trim();
                    if (data !== "") {
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

		//check existing if nag secure ug clearance
	 	$.ajax({
			type : "POST",
			url  : "<?= base_url('placement/creates/jobtransfer');?>",
			data : { empId : empId },
			success : function(data){					
				$('.show-form').html(data);	
                $("[name='cc3']").val(name);
		    } 
		});			 	
	 	
		$("[name='empid']").val(empId+" * "+name);
		$(".search-results").hide();  
	}


    function showEmptype(val)
    { 
    	if(val == 'nescotoae'){
    		$("#emptype").show();
    	}else{
    		$("#emptype").hide();	
    	}
    }

    function getLevel(position)
    {    	
    	$.ajax({
			type : "POST",
			url : "<?= base_url('placement/creates/getLevel');?>",
			data : { position:position },
			success : function(data){
				$("#level").val(data);
			}
		});
    }
</script>





