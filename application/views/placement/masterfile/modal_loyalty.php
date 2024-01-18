 <div class="am-pagebody">
        <div class="card pd-20 pd-sm-0 ">
        <div class="row">
            <div class="col-md-12 show-form">	
                        <div class="form-group">
                            <input type="hidden" name='lno' value="<?php echo $lno?>";
                            <label for="name" class="tx-gray-800 tx-15" >Employee Name<span style='color: red;'> * </span></label>
                            <div class="input-group">
                                <input type="text" required="" name="empid" value="<?= $name?>" onkeyup="namesearch(this.value)" class="form-control" placeholder="Lastname, Firstname" autocomplete="off" disabled>
                            </div>
                            <div class="search-results" style="display:none;"></div>		    
                        </div>		          	

                        <div class="form-group">
                            <label for="years" class="tx-gray-800 tx-15" > Years in Service (Loyalty) <span style='color: red;'> * </span></label>    	
                            <select name="yrsinservice" class="form-control" width="100%" value="<?= $years?>">
                                <option> </option>
                                    <option value="10" <?php if($service=='10'){ echo "selected"; }?> > 10 Years </option>
                                    <option value="20" <?php if($service=='20'){ echo "selected"; }?>> 20 Years </option>
                                    <option value="30" <?php if($service=='30'){ echo "selected"; }?>> 30 Years </option>
                                    <option value="40" <?php if($service=='40'){ echo "selected"; }?>> 40 Years </option>
                            </select>		    	
                        </div>	

                        <div class="form-group">
                            <label for="awarded" class="tx-gray-800 tx-15" > Year Awarded <span style='color: red;'> * </span></label>
                            <input type="text" required="" maxlength="4" width="100%" name="yrawarded" class="form-control" placeholder="Year" value="<?= $awarded ?>">
                        </div>           
                        <i> Note: <span style='color: red;'> * </span> Required fields. </i> <br>		
                </div>
         </div>
    </div>
</div>
	