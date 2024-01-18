<div class="am-pagebody">
    <div class="row row-sm mg-t-0">
        <div class="col-xl-12">
            <div class="card pd-20 pd-sm-40 form-layout form-layout-4">
                <h6 class="card-body-title tx-20 mb-3">Add Solo Parent</h6>
                <form api-url="placement/creates/insertSoloParent"
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dswdid">Scanned DSWD ID</label>
                                    <input type="file" class="form-control form-control-sm" required name='dswdid' id='dswdid'>
                                </div>
                                <div class="form-group">
                                    <label for="requestletter">Request Letter</label>
                                    <input type="file" class="form-control form-control-sm" required name='requestletter' id='requestletter'>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dateexpiry">Date Expiry</label>
                                    <input type='date' class="form-control form-control-sm" required name='dateexpiry' id='dateexpiry' placeholder='MM/DD/YYYY'>
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="form-button" class="btn btn-success btn-sm"
                                        button-message="Submitting...">SUBMIT</button>
                                    <button type='clear' class="btn btn-danger btn-sm">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div><!-- card -->
        </div>
    </div><!-- /.card -->
</div><!-- /.container -->
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
</script>
