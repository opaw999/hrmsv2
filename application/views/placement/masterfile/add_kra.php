<div class="am-pagebody">
        <div class="row row-sm mg-t-0">
          <div class="col-xl-12">
            <div class="card pd-20 pd-sm-40 form-layout form-layout-4">
              <h6 class="card-body-title tx-20"> ADD KEY RESPONSIBILITY AREA </h6>
               <form  
                    api-url="placement/masterfile/insert_kra"
                    api-type="POST"
                    onsubmit="event.preventDefault(); return handleFormSubmit();"
                    id="form">
                <div class="card pd-20 pd-sm-40 mg-t-0">
                        <div class="form-group">
                            <label for="position" class="tx-gray-800 tx-15">Position <span style='color: red;'>(required)</span></label>
                            <input list="se" id="search" type="text" class="form-control" name="position" autocomplete="off" style='height:32px' />
                            <datalist id="se">
                                <?php 
                                $res = $this->db->query(" SELECT position FROM positions");
                                foreach ($res->result_array() as $rs) { ?>
                                    <option value="<?php echo $rs['position'];?>"><?php echo $rs['position'];?></option>
                                <?php } ?>
                            </datalist>
                        </div>
                        
                        <div id="add_contact_form">
                            <div class="row">
                                <div class="col-md-4" style='width:40%;'>
                                    <div class="form-group">
                                        <label for="summary" class="tx-gray-800 tx-15" >Job Summary <span style='color: red;'>(required)</span></label>
                                        <textarea name='summary' class="form-control" style='height:173px;resize:none'></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4" style='width:30%;'>
                                    <div class="form-group">
                                        <label for="comp_code" class="tx-gray-800 tx-15">Company :</label>
                                         <select 
                                            id="company"
                                            name ='comp_code'
                                            required = "true"
                                            class="form-control  h-100 tx-12" onchange="getLocation(this.value,'businessunit','masterfile/filter/getbunit')">  
                                            <option value="">Select Company</option>
                                                    <?php foreach($comp as $comps): 
                                                    
                                                    ?>                                              
                                                    <option value="<?php echo $comps['company_code'];?>" <?php if(@$cc == $comps['company_code']) {
                                                echo "selected";
                                            }?>><?php echo $comps['acroname'];?></option>
                                                <?php endforeach;?>
                                                
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="bunit_code" class="tx-gray-800 tx-15">Business Unit : </label>
                                         <select class="form-control  h-100 tx-12" id="businessunit"  name ='bunit_code' onchange="getLocation(this.value,'department','masterfile/filter/getdept')" required>
                                         
                                         </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="dept_code" class="tx-gray-800 tx-15">Department : </label>
                                         <select class="form-control  h-100 tx-12" id="department"   name ='dept_code' onchange="getLocation(this.value,'section','masterfile/filter/getsect')">
                                    	    
                                            </select>
                                    </div>
                                </div>
                                <div class="col-md-4" style='width:30%'>
                                    <div class="form-group">
                                        <label for="sec_code" class="tx-gray-800 tx-15">Section : </label>
                                       <select class="form-control h-100 tx-12" id="section"  name ='sec_code' onchange="getLocation(this.value,'subsection','masterfile/filter/getsubsect')">
                                      
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="ssec_code" class="tx-gray-800 tx-15">Sub-section : </label>
                                         <select class="form-control h-100 tx-12" id="subsection"  name ='ssec_code'>
                                       
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="unit_code" class="tx-gray-800 tx-15">Unit : </label>
                                        <select class="form-control" name="unit_code">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="summernote" class="tx-gray-800 tx-15" > Job Description <span style='color: red;'>(required)</span></label>
                            <div id="summernote" class="form-control"></div>
                            <textarea name="desc" id="desc" cols="30" rows="10" style="display:none"></textarea>
                        </div>
                        <div>
                            <button type="submit" id="form-button" class="btn btn-success" button-message="Submitting...">SUBMIT</button>
                        </div>
                     </form>
                </div><!-- card -->
            </div>
        </div><!-- /.card -->
    </div><!-- /.container -->
</div>