
<div class="am-pagebody">
    <div class="row row-sm">
        <div class="col-lg-4">
            <div class="card pd-20 pd-sm-10">
                <div class="card">
                    <div class="card-body"> 
                        <table class="table table-bordered" style="background-color: transparent;">
                            <tbody>
                                   <tr>
                                <td colspan="2" style="background-color:darkgreen; color:white"><i>(Current Details)</i></td>
                            </tr>
                                <tr>
                                    <td><b>Company</b></td>
                                    <td><i><?=$company?></i></td>
                                </tr>
                                <tr>
                                    <td><b>Business Unit</b></td>
                                    <td><i><?=$bussinessunit?></i></td>
                                </tr>
                                <tr>
                                    <td><b>Department</b></td>
                                    <td><i><?=$department?></i></td>
                                </tr>
                                <tr>
                                    <td><b>Section</b></td>
                                    <td><i><?=$section?></i></td>
                                </tr>
                                <tr>
                                    <td><b>Sub Section</b></td>
                                    <td><i><?=$subsection?></i></td>
                                </tr>
                                <tr>
                                    <td><b>Unit</b></td>
                                    <td><i><?=$units?></i></td>
                                </tr>
                                <tr>
                                    <td><b>Position</b></td>
                                    <td><i><?= $oldposition ?></i></td>
                                </tr>
                                <tr>
                                    <td><b>Position Level</b></td>
                                    <td><i><?= $poslevel ?></i></td>
                                </tr>
                                <tr>
                                    <td><b>Lodging</b></td>
                                    <td><i><?= $lodging ?></i></td>
                                </tr>
                                <tr>
                                    <td><b>Employee Type</b></td>
                                    <td><i><?= $emptype ?></i></td>
                                </tr>
                                <tr>
                                    <td><b>Startdate</b></td>
                                    <td><i><?= $startdate ?></i></td>
                                </tr>
                                <tr>
                                    <td><b>Eocdate</b></td>
                                    <td><i><?= $eocdate ?></i></td>
                                </tr>
                                <tr>
                                    <td><b>Current Status</b></td>
                                    <td><i><?= $current_status ?></i></td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- </div>s -->
                    </div>
                </div>
                <!-- </div> -->
            </div><!-- card -->
        </div><!-- col-6 -->
        <div class="col-lg-8 mg-t-25 mg-lg-t-0">
            <div class="card pd-10 pd-sm-10">
                <!-- <div class="card wd-xs-300"> -->
                <div class="card-body">
                    <form  
                    api-url="placement/masterfile/update_jobtransfer"
                    api-type="POST"
                    onsubmit="event.preventDefault(); return handleFormSubmit();"
                    id="form">
                        <input type='hidden' value='<?php echo $oldcc;?>' name='company'>
                        <input type='hidden' value='<?php echo $oldbc;?>' name='businessunit'>
                        <input type='hidden' value='<?php echo $olddc;?>' name='department'>
                        <input type='hidden' value='<?php echo $oldsc;?>' name='section'>
                        <input type='hidden' value='<?php echo $oldssc;?>' name='subsection'>
                        <input type='hidden' value='<?php echo $olduc;?>' name='unit'>
                        <input type= 'hidden' name= 'empid' value ='<?= $empId ?>'>
                        <input type= 'hidden' name= 'rec' value ='<?= $recordno ?>'>
                        <input type= 'hidden' name= 'transno' value ='<?= $transno ?>'>
                        <input type='hidden' value='<?php echo $oldposition;?>' name='oldpos'>
					    <input type='hidden' value='<?php echo $oldlevel;?>' name='oldlevel'>
					    <input type='hidden' value='<?php echo $payrollno;?>' name='payrollno'>
                        

                    <table class="table" width="100%" style="background-color: transparent;">
                        <tbody>
                            <tr>
                                <td colspan="2" style="background-color:darkgreen; color:white"><i>(Please fill up for
                                        the letter heading)</i></td>
                            </tr>
                            <tr>
                                <td><b>Direct Supervisor</b> <span class="text-danger">*</span></td>
                                <td><input type="text"  class="form-control  h-100 tx-12" name="supervision" id="supervision" style="width:100%"
                                        onkeyup="namesearch_2(this.value)" value="<?= $supervision ?>" autocomplete="off"
                                        required="">
                                    <div class="search-results1" style="display:none;"></div>
                                </td>

                            </tr>
                            <tr>
                                <td><b>New Position <span class="text-danger">*</span></b> </td>
                                <td>
                                    <input type="hidden" name="prev_pos" id="prev_pos" value="<?= @$oldposition;?>">
                                    <select  class="form-control  h-100 tx-12" name="contract_position" id="contract_position" value= "<?= $position?>" style="width:100%"
                                        onchange="getLevel(this.value)" required="required">
                                        <option value="">Select</option>
                                        	<?php
                                            $query = $this->db->query("SELECT position_title, lvlno FROM position_leveling order by position_title");
                                                foreach ($query->result_array() as $rq) {
                                                    if($position == $rq['position_title']) {
                                                        echo "<option value='" . $rq['position_title'] . "' selected>" . $rq['position_title'] . "</option>";
                                                    } else {
                                                        echo "<option value='" . $rq['position_title'] . "'>" . $rq['position_title'] . "</option>";
                                                    }
                                                } ?>
                                    </select>

                                </td>
                            </tr>
                            <tr>
                                <td><b> New Level </b> <span class="text-danger">*</span></td>
                                <td><input type="text"  class="form-control  h-100 tx-12" name="newlevel" id="level" style="width:100%" value="<?= $level ?>" required=""></td>
                            </tr>
                            <tr>
                                <td><b>To</b> </td>
                                <td><input id="to"  class="form-control  h-100 tx-12" style="width:100%" type="text" name="to" autocomplete="off"
                                        placeholder="  Search Employee" value="<?= $name?>">
                                </td>
                            </tr>
                            <tr>
                                <td><b>From <span class="text-danger">*</span></b> </td>
                                <td><input type="text"  class="form-control  h-100 tx-12" class="theight" name="from" id="from" value="<?= $assignedfrom?>" required=""
                                        onkeyup="changeborder(this.id)"></td>
                            </tr>
                            <!--<tr>
                                <td><b>Re</b> </td>
                                <td><input type="text" class='theight' name="re" id="re" readonly value="Job Transfer"/></td>
                            </tr> -->
                            <tr>
                                <td><b>Date Created </b> </td>
                                <td><input type="date"  class="form-control  h-100 tx-12"class="theight hasDatepicker" name="dates" id="dates"
                                        value="<?= $entrydate?>"></td>
                            </tr>
                            <tr>
                                <td><b>Effectivity Date <span class="text-danger">*</span></b></td>
                                <td><input type="date"  class="form-control  h-100 tx-12" name="effectiveon" id="effectiveon" class="theight hasDatepicker"
                                        value="<?=$effectiveon?>" placeholder="mm/dd/yyyy" required=""></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="background-color:darkgreen; color:white"><i>(New Location
                                        Details)</i></td>
                            </tr>
                            <tr>
                                <td><b>Company</b> <span class="text-danger">*</span></td>
                                <td> <input type="hidden" name="cc" id="cc" value="<?= @$n_cc?>">
                                    <select 
                                        id="company"
                                        name ='comp_code'
                                        required = "true"
                                        class="form-control  h-100 tx-12" onchange="getLocation(this.value,'businessunit','masterfile/filter/getbunit')">  
                                        <option value="">Select Company</option>
                                                <?php foreach($comp as $comps): 
                                                
                                                ?>                                              
                                                <option value="<?php echo $comps['company_code'];?>" <?php if(@$n_cc == $comps['company_code']) {
										    echo "selected";
										}?>><?php echo $comps['acroname'];?></option>
                                            <?php endforeach;?>
                                            
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Business Unit</b> <span class="text-danger">*</span></td>
                                <td> <input type="hidden" name="bc" id="bc" value="<?= @$n_bc?>">
                                      <select class="form-control  h-100 tx-12" id="businessunit"  name ='bunit_code' onchange="getLocation(this.value,'department','masterfile/filter/getdept')" required>
                                         <?php
                                            if(@$n_bc || @$n_cc) {
                                                echo '<option value="">Select</option>';
                                                foreach($bunit as $bu) {?>
                                                    <option value='<?php echo $n_cc . "." . $bu['bunit_code'];?>' <?php if($n_bc == $bu['bunit_code']) {
                                                        echo "selected";
                                                    }?>><?php echo $bu['business_unit'];?></option> <?php
                                                }
                                            } else {?>
                                                    <option value="">Select</option>
                                             <?php } ?>
                                     </select>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Department</b> <span class="text-danger">*</span></td>
                                <td> <input type="hidden" name="dc" id="dc" value="<?= @$n_dc?>">
                                    <select class="form-control  h-100 tx-12" id="department" name ='dept_code' onchange="getLocation(this.value,'section','masterfile/filter/getsect')">
                                    	<?php
                                    if(@$n_dc || @$n_bc) {
                                        echo '<option value="">Select</option>';
                                        foreach ($dept as $dc) {?>
											<option value='<?php echo $n_cc . "." . $n_bc . "." . $dc['dept_code'];?>' <?php if($n_dc == $dc['dept_code']) {
											    echo "selected";
											}?>>
											<?php echo $dc['dept_name'];?></option><?php
                                        }

                                    } else {?>
									<option value="">Select</option>
									<?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Section</b></td>
                                <td> <input type="hidden" name="sc" id="sc" value="<?= @$n_sc?>">
                                      <select class="form-control h-100 tx-12" id="section"  name ='sec_code' onchange="getLocation(this.value,'subsection','masterfile/filter/getsubsect')">
                                        <?php
                                            if(@$n_sc || @$n_dc) {
                                                echo '<option value="">Select</option>';
                                                foreach($sect as $sc) {?>
                                                    <option value='<?php echo $n_cc . "." . $n_bc . "." . $n_dc . "." . $sc['section_code'];?>' <?php if($n_sc == $sc['section_code']) {
											    echo "selected";
											}?>>
											<?php echo $sc['section_name'];?></option><?php
                                                }
                                            } else { ?>
										<option value="">Select</option>
									<?php } ?>
                                     </select>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Sub-section</b></td>
                                <td> <input type="hidden" name="ssc" id="ssc" value="<?= @$n_ssc?>">
                                    <select class="form-control h-100 tx-12" id="subsection"  name ='ssec_code'>
                                    <?php
                                        if(@$n_ssc || @$n_sc) {
                                            echo '<option value="">Select</option>';
                                             foreach($subsect as $subs) {?>
                                            <option value='<?php echo $n_cc . "." . $n_bc . "." . $n_dc . "." . $n_sc . "." . $subs['sub_section_code'];?>' <?php if($n_ssc == $subs['sub_section_code']) {
                                                echo "selected";
                                            }?>>
                                            <?php echo $subs['sub_section_name'];?></option><?php
                                            }
                                        } else {?>
										<option value="">Select</option>
										<?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Unit</b></td>
                                <td><input type="hidden" name="uc" id="uc" value="">
                                    <select class="form-control h-100 tx-12" id="unit"  name ='unit_code'>
                                    <option></option>
                                    </select>
                                </td>
                            </tr>



                            <tr>
                                <td colspan="2" style="background-color:darkgreen; color:white"><i>(Please fill up for
                                        the cc)</i></td>
                            </tr>
                            <tr>
                                <td>1.)<span class="text-danger"> *</span></td>
                                <td><input type="text" name="cc1" class="form-control h-100 tx-12" id="cc1" size="40" value="<?=$cc1?>" required=""></td>
                                    
                            </tr>
                            <tr>
                                <td>2.)<span class="text-danger"> *</span></td>
                                <td><input type="text" name="cc2" id="cc2" class="form-control h-100 tx-12" value="<?=$cc2?>" required="">
                                   </td>
                            </tr>
                            <tr>
                                <td>3.)<span class="text-danger"> *</span></td>
                                <td><input type="text" name="cc3" id="cc3" class="form-control h-100 tx-12" value="<?=$cc3?>">
                                </td>
                            </tr>
                            <tr>
                                <td>4.)</td>
                                <td><input type="text" name="cc4" id="cc4" class="form-control h-100 tx-12" value="<?=$cc4?>">
                                </td>
                            </tr>
                            <tr>
                                <td>5.)</td>
                                <td><input type="text" name="cc5" id="cc5" class="form-control h-100 tx-12" value="<?=$cc5?>"></td>
                            </tr>
                            <tr>
                                <td>6.)</td>
                                <td><input type="text" name="cc6" id="cc6" class="form-control h-100 tx-12" value="<?=$cc6?>"></td>
                            </tr>

                            <tr>
                                <td colspan="2" style="background-color:darkgreen; color:white"> (Indicate Type of
                                    Transfer) </td>
                            </tr>
                            <tr>
                                <td> Type of Transfer </td>
                                <td>
                                    <select name="transfer_type" class="form-control h-100 tx-12" required="" onchange="showEmptype(this.value)"
                                        style="width: 100%">
                                        <option> </option>
                                        <option value='jobtransfer' <?php echo ($transtype == "jobtransfer") ? "selected" : ""; ?> > Job Transfer </option>
                                        <option value='nescotoae' <?php echo ($transtype == "nescotoae") ? "selected" : ""; ?> > 
                                        NESCO to AE - Transfer </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td> New Employee Type </td>
                                <td>
                                    <select name="emptype" id="emptype" class="form-control h-100 tx-12" style="width: 100%;display: none;">
                                        <option> </option>
                                        <option value="Regular"> Regular </option>
                                        <option value="Regular Partimer"> Regular Partimer </option>
                                        <option value="Contractual"> Contractual </option>
                                        <option value="Probationary"> Probationary </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><i>Note : Mark with <span class="text-danger">*</span> means required
                                        fields.</i></td>
                            </tr>

                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <button onclick="back()" class="btn btn-warning"> Back </button>
                                    <!-- <button type='button' onclick ="gen_jobtrans()" class="btn btn-success">Generate Job Transfer Report</button> -->
                                     <button type="submit" id="form-button" class="btn btn-success" button-message="Generating...">Generate Job Transfer Report</button>
                                </td>
                                <td colspan="2">&nbsp;</td>
                            </tr>

                        </tbody>
                    </table>
                    </form>	  
                </div><!-- card-body -->
                <!-- </div>card -->
            </div><!-- card -->
        </div><!-- col-6 -->
    </div><!-- row -->
</div>
<script>

    function namesearch_2(key)
	{

	    $(".search-results1").show();

	    var str = key.trim();
	    $(".search-results1").hide();
	    if(str == '') {
	        $(".search-results-loading").slideUp(100);
	    }
	    else {
			$.ajax({
				type : "POST",
				url  : "<?= base_url('masterfile/findsupervisor'); ?>",
				data : { str : str},
				success : function(data){
                    // alert(data);
			  		data = data.trim();
					if(data != ""){
					  $(".search-results1").show().html(data);
					} else {

						$(".search-results1").hide();
					}
			    } 
			});
	    }
	}

    function getEmpId_2(id)
	{
		var id 		= id.split("*");
		var empId 	= id[0].trim();  
		var name 	= id[1].trim();
		var status 	= id[2].trim();
		
		$("[name='supervision']").val(empId);
		$(".search-results1").hide();  

		$("[name='cc4']").val(name);
	}

    function getLevel(position)
    {    	
    	$.ajax({
			type : "POST",
			url : "<?= base_url('masterfile/getlevel'); ?>",
			data : { position:position },
			success : function(data){
                // alert(data);
				$("#level").val(data);
			}
		});
    }
    function showEmptype(val)
    { 
    	if(val == 'nescotoae'){
    		$("#emptype").show();
    	}else{
    		$("#emptype").hide();	
    	}
    }

    function back()
	{
		 window.location ='<?php echo base_url('placement/masterfile/employee_jobtransfer'); ?>';
		
	}

    
</script>