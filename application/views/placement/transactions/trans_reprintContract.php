<div class="am-pagebody">
    <div class="row row-sm ">
        <div class="col-xl-12">
            <div class="card pd-20">
                <h6 class="card-body-title tx-20 mb-3">Reprint Contract</h6>
                <form api-url="placement/transactions/contractTable"
                    api-type="POST"
                    onsubmit="event.preventDefault(); return handleFormSubmit();"
                    id="form">
                    <!-- <input type='hidden' name='soloempid'> -->
                    <div class="container-fluid">
                        <div class="row">   
                            <div class="col-md-12">
                                <label class="tx-gray-800"><span class='rqd tx-gray-800'>*</span> Employee Name</label>
                                <div class="input-group mb-3">
                                    <input type="text" required name="empid" id='empidClearance' onkeyup="namesearch(this.value)"
                                        class="form-control" placeholder="Lastname, Firstname" value="" autocomplete="off"
                                        required="">
                                </div>
                                <div class="search-results" style="display:none;"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div><!-- card -->
            <div class='col-md-12'>	
				<span id='_loading'></span>		
				<div style="background-color:white;" class="row">
					<div class="col-md-12 show-form">		

					</div>				
				</div>
			</div> 
        </div>
    </div><!-- /.card -->
</div><!-- /.container -->


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
            url  : "<?= base_url('placement/transactions/contractTable');?>",
            data : { empId : empId },
            success : function(data){					
                $('.show-form').html(data);	
                $("[name='cc3']").val(name);
            } 
        });			 	
        
        $("[name='empid']").val(empId+" * "+name);
        $(".search-results").hide();  
    }
</script>
