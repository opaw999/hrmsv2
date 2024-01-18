<?php 


$query = $this->db->query("SELECT 
				name, emp_type, position, poslevel, company_code, bunit_code, dept_code, section_code, sub_section_code, unit_code
	 			FROM employee3 WHERE emp_id = '$empid' and current_status = 'Active' ");
$row   = $query->result_array();
foreach($query->result_array() as $row){

                            $name 		= $row['name'];
                            $position 	= $row['position'];
                            $level 		= $row['poslevel'];
                            $emptype 	= $row['emp_type'];

                            $comp = $this->dbmodel
                                ->get_row(
                                'locate_company',
                                'acroname',
                                array( 'field1' => 'company_code'),
                                array(@$row['company_code'])
                            );

                            $bunit = $this->dbmodel
                                    ->get_row(
                                    'locate_business_unit',
                                    'business_unit',
                                    array( 'field1' => 'company_code',
                                    'field2' => 'bunit_code'),
                                    array(@$row['company_code'],@$row['bunit_code'])
                            );

                            
                            $dept = $this->dbmodel
                                    ->get_row(
                                        'locate_department',
                                        'dept_name',
                                        array(  'field1' => 'company_code',
                                                'field2' => 'bunit_code',
                                                'filed3' => 'dept_code'),
                                        array(@$row['company_code'],@$row['bunit_code'],@$row['dept_code'])
                            );

                            
                            $sect = $this->dbmodel
                                   ->get_row(
                                       'locate_section',
                                       'section_name',
                                       array(   'field1' => 'company_code',
                                                'field2' => 'bunit_code',
                                                 'field3' => 'dept_code',
                                                'field4' => 'section_code'),
                                       array(@$row['company_code'],@$row['bunit_code'],@$row['dept_code'],@$row['section_code'])
                            );

                            
                            $ssect = $this->dbmodel
                                   ->get_row(
                                       'locate_sub_section',
                                       'sub_section_name',
                                       array( 'field1' => 'company_code',
                                                'field2' => 'bunit_code',
                                                 'field3' => 'dept_code',
                                                 'field4' => 'section_code',
                                                 'field5' => 'sub_section_code'),
                                       array(@$row['company_code'],@$row['bunit_code'],@$row['dept_code'],@$row['section_code'],@$row['sub_section_code'])
                            );

                            
                            $unit = $this->dbmodel
                                  ->get_row(
                                      'locate_unit',
                                      'unit_name',
                                      array( 'field1' => 'company_code',
                                                'field2' => 'bunit_code',
                                                 'field3' => 'dept_code',
                                                 'field4' => 'section_code',
                                                 'field5' => 'sub_section_code',
                                                'field6' => 'unit_code'),
                                      array(@$row['company_code'],@$row['bunit_code'],@$row['dept_code'],@$row['section_code'],@$row['sub_section_code'],@$row['unit_code'])
                            );
                        }

                      

?>
	<div class="row">
    	<div class="col-md-5">
    		<b class="tx-gray-900"> CURRENT CONTRACT DETAILS </b>
    		<table>
    			<tr> 
    				<td class="tx-gray-800" > Position </td> 
    				<td class="text-primary"> : <?= $position;?> </td>
    			</tr>
    			<tr>
    				<td class="tx-gray-800"> Level </td>
    				<td class="text-primary"> : <?= $level;?> </td>
    			</tr>
    			<tr>
    				<td class="tx-gray-800"> Employee Type </td>
    				<td class="text-primary"> : <?= $emptype;?> </td>
    			</tr>
    			<tr>
    				<td class="tx-gray-800"> Company </td>
    				<td class="text-primary"> : <?= @$comp->acroname;?> </td>
    			</tr>
    			<tr>
    				<td class="tx-gray-800"> Business Unit </td>
    				<td class="text-primary"> : <?= @$bunit->business_unit;?> </td>
    			</tr>
    			<tr>
    				<td class="tx-gray-800"> Department </td>
    				<td class="text-primary"> : <?= @$dept->dept_name;?> </td>
    			</tr>
    			<tr>
    				<td class="tx-gray-800"> Section </td>
    				<td class="text-primary"> : <?= @$sect->section_name;?> </td>
    			</tr>
    			<tr>
    				<td class="tx-gray-800"> Sub Section </td>
    				<td class="text-primary"> : <?= @$ssect->sub_section_name;?> </td>
    			</tr>
    			<tr>
    				<td class="tx-gray-800"> Unit </td>
    				<td class="text-primary"> : <?= @$unit->unit_name;?> </td>
    			</tr>
    		</table>
    	</div>
    	<div class="col-md-7">

            <form  
            api-url="placement/creates/insert_jobtransfer"
            api-type="POST"
            onsubmit="event.preventDefault(); return handleFormSubmit();"
            id="form">
    		<b> TRANSFER DETAILS </b>
    		<input type='hidden' value='<?= $empid;?>' name='empid'>
    		<table>
    			<tr> 
    				<td> Direct Supervisor </td>
    				<td> <input type="text" name="supervisor" class="form-control form-control-sm mb-3 mt-3" style='width:100%' id= 'supervisor' onkeyup="namesearch_2(this.value)" placeholder="Lastname, Firstname" value="" autocomplete="off" required="">
                          <div class="search-results1" style="display:none;"></div>
    				</td>
    			</tr>
    			<tr> 
    				<td> New Position </td>
    				<td> 
    					<select  id="newposition" name="newposition" class="form-control form-control-sm mb-3"  required onchange="getLevel(this.value)">
			              	<option value="">Select</option>			              
			              	<?php $query = $this->db->query("SELECT position_title from position_leveling order by position_title ");
                                foreach($query->result_array() as $rs) {
                                    echo "<option value='" . $rs['position_title'] . "'>" . $rs['position_title'] . "</option>";
                                }?>
			            </select>
    				</td>
    			</tr>
    			<tr>
    				<td> New Level </td>
    				<td> <input type='text' id='level' class="form-control form-control-sm mb-3" name="level" readonly="" style="width: 100%" required> </td>
    			</tr>  
    			<tr>
    				<td> Date of Effectivity </td>
    				<td> <input type="date" name="effectivity" class="form-control form-control-sm mb-3"  required id="effectivity" style="width: 100%"/> </td>
    			</tr>    	
    			<tr>
    				<td colspan="2" style="background-color:darkgreen; color:white"> (New Location Details) </td>	
    			</tr>		
    			<tr>
    				<td> Company </td>
    				<td>    						
    					<select id= "company" name="comp_code"  class="form-control form-control-sm mb-3 mt-3" style="width: 100%" onchange="getLocation(this.value,'businessunit','masterfile/filter/getbunit')" required >
							<option value="">Select</option>
							<?php
                             $sub_query = $this->db->query("SELECT * FROM locate_company where status='active' ORDER BY company ASC ");
                            foreach($sub_query->result_array() as $res) { ?>
									<option value="<?php echo $res['company_code'];?>" <?php if(@$row['company_code'] == $res['company_code']) {
									    echo "selected";
									}?>><?php echo $res['acroname'];?></option>
								<?php } ?>
						<select>
    				</td>
    			</tr>
    			<tr>
    				<td> Business Unit </td>
    				<td>
    					<select name="bunit_code"  id="businessunit" class="form-control form-control-sm mb-3 " style="width: 100%" onchange="getLocation(this.value,'department','masterfile/filter/getdept')" required>
							<?php
                            if(@$row['bunit_code'] || @$row['company_code']) {
                                echo '<option value="">Select</option>';
                                $sql = $this->db->query("SELECT * FROM locate_business_unit WHERE company_code = '" . $row['company_code'] . "' ORDER BY business_unit ASC");
                                foreach($sql->result_array() as $res) {?>
								<option value='<?php echo $row['company_code'] . "." . $res['bunit_code'];?>' <?php if($row['bunit_code'] == $res['bunit_code']) {
								    echo "selected";
								}?>><?php echo $res['business_unit'];?></option> <?php
                                }
                            } else {?>
								<option value="">Select</option>
							<?php } ?>
						<select>
    				</td>
    			</tr>
    			<tr>
    				<td> Department </td>
    				<td> 
    					<select name="dept_code" id="department" class="form-control form-control-sm mb-3 " style="width: 100%" onchange="getLocation(this.value,'section','masterfile/filter/getsect')">						
							<?php
                            if(@$row['dept_code'] || @$row['bunit_code']) {
                                echo '<option value="">Select</option>';
                                $sql = $this->db->query("SELECT * FROM locate_department WHERE company_code = '" . $row['company_code'] . "' and bunit_code = '" . $row['bunit_code'] . "' ORDER BY dept_name ASC");
                                foreach($sql->result_array() as $res) {?>
									<option value='<?php echo $row['company_code'] . "." . $row['bunit_code'] . "." . $res['dept_code'];?>' <?php if($row['dept_code'] == $res['dept_code']) {
									    echo "selected";
									}?>>
									<?php echo $res['dept_name'];?></option><?php
                                }
                            } else {?>
							<option value="">Select</option>
							<?php } ?>
						<select>
    				</td>
    			</tr>
    			<tr>
    				<td> Section </td>
    				<td> 
    					<select name="sec_code"  id="section" class="form-control form-control-sm mb-3 " style="width: 100%" onchange="getLocation(this.value,'subsection','masterfile/filter/getsubsect')">
							<?php
                            if(@$row['section_code'] || @$row['dept_code']) {
                                echo '<option value="">Select</option>';
                                $sql = $this->db->query("SELECT * FROM locate_section WHERE company_code = '" . $row['company_code'] . "' and bunit_code = '" . $row['bunit_code'] . "' and dept_code = '" . $row['dept_code'] . "' 
										ORDER BY section_name ASC");
                                foreach($sql->result_array() as $res) {?>
									<option value='<?php echo $row['company_code'] . "." . $row['bunit_code'] . "." . $row['dept_code'] . "." . $res['section_code'];?>' <?php if($row['section_code'] == $res['section_code']) {
									    echo "selected";
									}?>>
									<?php echo $res['section_name'];?></option><?php
                                }
                            } else { ?>
								<option value="">Select</option>
							<?php } ?>
						<select>
    				</td>
    			</tr>
    			<tr>
    				<td> Sub Section </td>
    				<td>
    					<select name="ssec_code" id="subsection" class="form-control form-control-sm mb-3 " style="width: 100%">
							<?php
                            if(@$row['sub_section_code'] || @$row['section_code']) {
                                echo '<option value="">Select</option>';
                                $sql = $this->db->query("SELECT * FROM locate_sub_section WHERE company_code = '" . $row['company_code'] . "' and bunit_code = '" . $row['bunit_code'] . "' and dept_code = '" . $row['dept_code'] . "' and section_code = '" . $row['section_code'] . "' ORDER BY sub_section_name ASC ");
                                foreach($sql->result_array() as $res) {?>
									<option value='<?php echo $row['company_code'] . "." . $row['bunit_code'] . "." . $row['dept_code'] . "." . $row['section_code'] . "." . $res['sub_section_code'];?>' <?php if($row['sub_section_code'] == $res['sub_section_code']) {
									    echo "selected";
									}?>>
									<?php echo $res['sub_section_name'];?></option><?php
                                }
                            } else {?>
								<option value="">Select</option>
								<?php } ?>
						<select>
    				</td>
    			</tr>
    			<tr>
    				<td> Unit </td>
    				<td> 
						<select name="unit_code" class="form-control form-control-sm mb-3 " style="width: 100%">
							<?php
                            if(@$row['unit_code'] || @$row['sub_section_code']) {
                                echo '<option value="">Select</option>';
                                $sql = $this->db->query("SELECT * FROM locate_unit WHERE company_code = '" . $row['company_code'] . "' and bunit_code = '" . $row['bunit_code'] . "' and dept_code = '" . $row['dept_code'] . "' and section_code = '" . $row['section_code'] . "' and  sub_section_code = '" . $row['sub_section_code'] . "' ORDER BY unit_name ASC ");
                                foreach($sql->result_array() as $res) {?>
									<option value='<?php echo $row['company_code'] . "." . $row['bunit_code'] . "." . $row['dept_code'] . "." . $row['section_code'] . "." . $row['sub_section_code'] . "." . $res['unit_code'];?>' <?php if($row['unit_code'] == $res['unit_code']) {
									    echo "selected";
									}?>>
									<?php echo $res['unit_name'];?></option><?php
                                }
                            } else {?>
								<option value="">Select</option>
								<?php } ?>
						<select>
					 </td>
    			</tr>
    			<tr>
    				<td colspan="2" style="background-color:darkgreen; color:white"> (Please fill up for the cc) </td>	
    			</tr>
    			<tr>
    				<td> </td>
    				<td> <input type="text" class="form-control form-control-sm mb-3 mt-3" name="cc1" id="cc1" size="40" value="Payroll" /> </td>
    			</tr>
    			<tr>
    				<td> </td>
    				<td> <input type="text" class="form-control form-control-sm mb-3 " name="cc2" id="cc2" size="40" value="201 File" /> </td>
    			</tr>
    			<tr>
    				<td> </td>
    				<td> <input type="text" class="form-control form-control-sm mb-3 " name="cc3" id="cc3" size="40" /> </td>
    			</tr>
    			<tr>
    				<td> </td>
    				<td> <input type="text" class="form-control form-control-sm mb-3 " name="cc4" id="cc4" size="40" /> </td>
    			</tr>
    			<tr>
    				<td> </td>
    				<td> <input type="text" class="form-control form-control-sm mb-3 " name="cc5" id="cc5" size="40"/></td>
    			</tr>
    			<tr>
    				<td> </td>
    				<td> <input type="text" class="form-control form-control-sm mb-3 " name="cc6" id="cc6" size="40"/> </td>
    			</tr>
    			<tr>
    				<td colspan="2" style="background-color:darkgreen; color:white"> (Indicate Type of Transfer) </td>	
    			</tr>
    			<tr>
    				<td> Type of Transfer </td>
    				<td> 
    					<select name='transfer_type' class="form-control form-control-sm mb-3 mt-3" required onchange="showEmptype(this.value)" style="width: 100%">
    						<option> </option>
    						<option value='jobtransfer'> Job Transfer </option>
    						<option value='nescotoae'> NESCO to AE - Transfer </option>
    					</select>
    				</td>
    			</tr>
    			<tr>
    				<td>  New Employee Type </td>
    				<td>
    					<select name='emptype' id='emptype' class="form-control form-control-sm mb-3" style="width: 100%; display: none">
    						<option> </option>
    						<option value='Regular'> Regular </option>
    						<option value='Regular Partimer'> Regular Partimer </option>
    						<option value='Contractual'> Contractual </option>
    						<option value='Probationary'> Probationary </option>
    					</select>
    				</td>
    			</tr>
    			<tr>
    				<td> </td>	
    				<td>  <button type="submit" id="form-button" class="btn btn-success" button-message="Generating...">Generate Job Transfer Report</button> </td>	
    			</tr>
    		</table>
    		</form>
    		<br>
    	</div>
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
		
		$("[name='supervisor']").val(empId);
		$(".search-results1").hide();  

		$("[name='cc4']").val(name);
	}
    
    </script>
