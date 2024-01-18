<div class="am-pagebody">
        <div class="row row-sm mg-t-0">
          <div class="col-xl-12">
            <div class="card pd-20 pd-sm-40 form-layout form-layout-4">
              <h6 class="card-body-title tx-20"> UPDATE KEY RESPONSIBILITY AREA </h6>
               <form  
                    api-url="placement/masterfile/update_kra"
                    api-type="POST"
                    onsubmit="event.preventDefault(); return handleFormSubmit();"
                    id="form">
                <div class="card pd-20 pd-sm-40 mg-t-0">
                    <input type="hidden" name ="kc" value = "<?= $kc ?>">
                        <div class="form-group">
                            <label for="position" class="tx-gray-800 tx-15">Position <span style='color: red;'>(required)</span></label>
                            <input list="se" id="search" type="text" class="form-control" name="position" autocomplete="off" style='height:32px' value="<?=$position;?>"/>
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
                                        <textarea name='summary' class="form-control" style='height:173px;resize:none'><?= $summary?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4" style='width:30%;'>
                                    <div class="form-group">
                                        <label for="comp_code" class="tx-gray-800 tx-15">Company :</label>
                                         <input type="hidden" name="comp_code" value="<?= $cc ?>">
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
                                        <input type="hidden" name="bc" value="<?= $bc ?>">
                                         <select class="form-control  h-100 tx-12" id="businessunit"  name ='bunit_code' onchange="getLocation(this.value,'department','masterfile/filter/getdept')" required>
                                         <?php
                                            if(@$bc || @$cc) {
                                                echo '<option value="">Select</option>';
                                                foreach($bunit as $bu) {?>
                                                    <option value='<?php echo $cc . "." . $bu['bunit_code'];?>' <?php if($bc == $bu['bunit_code']) {
                                                        echo "selected";
                                                    }?>><?php echo $bu['business_unit'];?></option> <?php
                                                }
                                            } else {?>
                                                    <option value="">Select</option>
                                             <?php } ?>
                                         </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="dept_code" class="tx-gray-800 tx-15">Department : </label>
                                          <input type="hidden" name= "dec" value="<?= $dec ?>">
                                         <select class="form-control  h-100 tx-12" id="department"   name ='dept_code' onchange="getLocation(this.value,'section','masterfile/filter/getsect')">
                                    	    <?php
                                            if(@$dec || @$bc) {
                                                echo '<option value="">Select</option>';
                                                foreach ($dept as $dc) {?>
                                                    <option value='<?php echo $cc . "." . $bc . "." . $dc['dept_code'];?>' <?php if($dec == $dc['dept_code']) {
                                                        echo "selected";
                                                    }?>>
                                                    <?php echo $dc['dept_name'];?></option><?php
                                                }

                                            } else {?>
                                            <option value="">Select</option>
                                            <?php } ?>
                                            </select>
                                    </div>
                                </div>
                                <div class="col-md-4" style='width:30%'>
                                    <div class="form-group">
                                        <label for="sec_code" class="tx-gray-800 tx-15">Section : </label>
                                        <input type="hidden" name= "sec" value="<?= $sec ?>">
                                       <select class="form-control h-100 tx-12" id="section"  name ='sec_code' onchange="getLocation(this.value,'subsection','masterfile/filter/getsubsect')">
                                        <?php
                                            if(@$sec || @$dec) {
                                                echo '<option value="">Select</option>';
                                                foreach($sect as $sc) {?>
                                                    <option value='<?php echo $cc . "." . $bc . "." . $dec . "." . $sc['section_code'];?>' <?php if($sec == $sc['section_code']) {
											    echo "selected";
											}?>>
											<?php echo $sc['section_name'];?></option><?php
                                                }
                                            } else { ?>
										<option value="">Select</option>
                                        <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="ssec_code" class="tx-gray-800 tx-15">Sub-section : </label>
                                          <input type="hidden" name= "ssec" value="<?= $ssc ?>">
                                         <select class="form-control h-100 tx-12" id="subsection"  name ='ssec_code'>
                                        <?php
                                            if(@$ssc || @$sc) {
                                                echo '<option value="">Select</option>';
                                                foreach($subsect as $subs) {?>
                                                <option value='<?php echo $cc . "." . $bc . "." . $dc . "." . $sc . "." . $subs['sub_section_code'];?>' <?php if($ssc == $subs['sub_section_code']) {
                                                    echo "selected";
                                                }?>>
                                                <?php echo $subs['sub_section_name'];?></option><?php
                                                }
                                            } else {?>
                                            <option value="">Select</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="unit_code" class="tx-gray-800 tx-15">Unit : </label>
                                        <select class="form-control" name="unit_code">
                                            <option value="">Select</option>
                                            <!-- Options go here -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="summernote" class="tx-gray-800 tx-15" > Job Description <span style='color: red;'>(required)</span></label>
                            <div id="summernote" class="form-control"><?= $jobdesc?></div>
                            <textarea name="desc" id="desc" cols="30" rows="10" style="display:none"><?= $jobdesc?></textarea>
                        </div>
                        <div>
                            <button type="submit" id="form-button" class="btn btn-success" button-message="Updating...">Update</button>
                        </div>
                     </form>
                </div><!-- card -->
            </div>
        </div><!-- /.card -->
    </div><!-- /.container -->
</div>