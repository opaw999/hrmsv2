
<div class="am-pagebody" style='padding:20px;'>
    <div class="row row-sm">
        <div class="col-lg-12">
        
            <div class="card pd-20 pd-sm-30"> 
                    
                    <div class='row'>
                        <div class="col-md-1" style='padding-right:0px;' >
                            <div class="form-group"></div>
                        </div>
                        <div class="col-md-6" style='padding-left:0px;'>
                            <div class="form-group"></div>
                        </div>
                        <div class="col-md-1" style='padding-right:0px;'>
                            <div class="form-group"></div>   
                        </div>
                        <div class="col-md-4" style='padding-left:0px; text-align:right;'>
                            <div class="form-group">
                                <span><?= date('F d, Y');?></span>
                            </div>   
                        </div>    
                        
                        <div class="col-md-12">
                            <div class="form-group"></div>
                        </div>
                    </div>
                    
                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <i style='color:red;'>INSTRUCTION</i><i>: <u>Please complete and send to HRD Department for processing and approval by General Manager. 
                                For each type of position nomenclature, a separate requisition is required.</u></i>  
                            </div>
                        </div>
                    </div>

                <!-- <h6> Please secure for my department the required applicant/s to fill the herein position: </h6>   -->
                
                <form id='personnel_request' method='post' enctype="multipart/form-data">
                    <hr/> <!-- horizontal line separates  -->
                    <div class='row'>

                        <div class="col-md-2" style='padding-right:0px;'>
                            <div class="form-group">
                                PR CODE   
                            </div>
                        </div>

                        <div class="col-md-5" style='padding-left:0px;'>
                            <div class="form-group">
                                <input type='text' value ='KM8230704080800' class='form-control' readonly >   
                            </div>
                        </div>

                        <div class="col-md-3" style='padding-right:0px;'>
                            <div class="form-group">
                                NUMBER NEEDED
                            </div>   
                        </div>

                        <div class="col-md-2" style='padding-left:0px;'>
                            <div class="form-group">                               
                                <input type="number" class="form-control" id="numneed" name="numneed">
                            </div>   
                        </div>    
                        
                    </div>

                    <div class='row'>
                        <div class="col-md-2" style='padding-right:0px;'>
                            <div class="form-group">
                                POSITION TITLE   
                            </div>
                        </div>

                        <div class="col-md-5" style='padding-left:0px;'>
                            <div class="form-group">
                                <input type='text' class='form-control'>   
                            </div>
                        </div>

                        <div class="col-md-3" style='padding-right:0px;'>
                            <div class="form-group">
                                DATE BEGIN
                            </div>   
                        </div>

                        <div class="col-md-2" style='padding-left:0px;'>
                            <div class="form-group">                               
                                <input type='date' class="form-control" id="dateBegin" name="dateBegin" class='date2'>
                            </div>   
                        </div>    
                    </div>

                    <hr/> <!-- horizontal line separates  -->

                    <div class='row'>
                        <div class="col-md-2" style='padding-right:0px;'>
                            <div class="form-group">
                                COMPANY <?= $result_applicants = $this->Personnel_model->record_count_applicant();?>  
                            </div>
                        </div>

                        <div class="col-md-5" style='padding-left:0px;'>
                            <div class="form-group">
                                <input type='text' class='form-control'>   
                            </div>
                        </div>

                        <div class="col-md-5" style='padding-right:0px;'>
                            <div class="form-group">
                                
                            </div>   
                        </div>     
                    </div>

                    <div class='row'>
                        <div class="col-md-2" style='padding-right:0px;'>
                            <div class="form-group">
                                BUSINESS UNIT   
                            </div>
                        </div>

                        <div class="col-md-5" style='padding-left:0px;'>
                            <div class="form-group">
                                <input type='text' class='form-control'>   
                            </div>
                        </div>

                        <div class="col-md-5" style='padding-right:0px;'>
                            <div class="form-group">
                                
                            </div>   
                        </div>    
                    </div>

                    <div class='row'>
                        <div class="col-md-2" style='padding-right:0px;'>
                            <div class="form-group">
                                DEPARTMENT  
                            </div>
                        </div>

                        <div class="col-md-5" style='padding-left:0px;'>
                            <div class="form-group">
                                <input type='text' class='form-control'>   
                            </div>
                        </div>

                        <div class="col-md-5" style='padding-right:0px;'>
                            <div class="form-group">
                                
                            </div>   
                        </div>  
                    </div>

                    <hr/> <!-- horizontal line separates  -->

                    <div class='row'>    
                        <div class="col-md-2" style='padding-right:0px;'>
                            <div class="form-group">
                                GENDER PREFERENCE   
                            </div>
                        </div>

                        <div class="col-md-4" style='padding-left:0px;'>
                            <div class="form-group">
                                <select class="form-control">
                                    <option value='none'>Select Preference</option>
                                    <option value='male'>Male</option>
                                    <option value='female'>Female</option>
                                </select>  
                            </div>
                        </div>

                        <div class="col-md-2" style='padding-right:0px;'>
                            <div class="form-group">
                                HIRING PREFERENCE
                            </div>   
                        </div>

                        <div class="col-md-4" style='padding-left:0px;'>
                            <div class="form-group">                               
                                <select class="form-control">
                                    <option value='none'>Select Preference</option>
                                    <option value='contractual'>Contractual</option>
                                    <option value='seassonal'>Seassonal</option>
                                </select>
                            </div>   
                        </div>        
                    </div>

                    <hr/> <!-- horizontal line separates  -->

                    <div class="card pd-10 pd-sm-5 mg-t-10">
                        <h6 class="card-body-title">VACANCY CAUSE BY RESIGNATION/TERMINATION:</h6>
                        <!-- <p class="mg-b-20 mg-sm-b-10">An inline form that is centered align and right aligned.</p> -->

                        <div>
                            <div class="d-md-flex pd-y-10 pd-md-y-0">
                                <input type="text" class="form-control mg-md-l-10 mg-t-10 mg-md-t-0" placeholder="Lastname, Firstname">
                                <button  type="button" class="btn btn-info pd-y-13 pd-x-20 bd-0 mg-md-l-10 mg-t-10 mg-md-t-0">Search</button>
                            </div>
                        </div><!-- d-flex -->
                    </div>

                    <div class="card pd-10 pd-sm-5 mg-t-10">
                        <h6 class="card-body-title">VACANCY CAUSE BY PROMOTION/TRANSFER:</h6>
                        <!-- <p class="mg-b-20 mg-sm-b-10">An inline form that is centered align and right aligned.</p> -->

                        <div>
                            <div class="d-md-flex pd-y-10 pd-md-y-0">
                                <input type="text" class="form-control mg-md-l-10 mg-t-10 mg-md-t-0" placeholder="Lastname, Firstname">
                                <button  type="button" class="btn btn-info pd-y-13 pd-x-20 bd-0 mg-md-l-10 mg-t-10 mg-md-t-0">Search</button>
                            </div>
                        </div><!-- d-flex -->
                    </div>

                    <div class="card pd-10 pd-sm-5 mg-t-10">
                        <h6 class="card-body-title">NEW ADDITIONAL MANPOWER: ( Give Justification )</h6>
                        <!-- <p class="mg-b-20 mg-sm-b-10">An inline form that is centered align and right aligned.</p> -->

                        <div >
                            <div >
                                <textarea class="form-control" style='width:100%; resize:none;' name='manpower' autocomplete='off' placeholder='Add your justification here. .'></textarea>
                            </div>
                        </div><!-- d-flex -->
                    </div>

                    <div class='row'>    
                        <div class="col-md-12" style='padding-right:0px;'>
                            <div class="form-group">
                               
                            </div>
                        </div>       
                    </div>

                    <hr/> <!-- horizontal line separates  -->

                    <div class="panel panel-default">
					   <div class="panel-heading">
							<table style = 'width:100%;'>
								<tr>
								<td style='width:40%; font-size:14px; font-weight:bold;'>
                                    <h6 class="card-body-title">BRIEF DESCRIPTION OF DUTIES</h6>
								</td>
								<td style='width:60%; font-size:14px; font-weight:bold;'><h6 class="card-body-title">QUALIFICATIONS REQUIRED</h6></td>
								</tr>
							</table>
					   </div>
					   <div class="panel-body" style='padding:10px;'>
							<table style='width:100%; font-family:arial; font-size: 14px; font-weight:bold; color:white;'>
								<tr >
									<td colspan='2'rowspan='3' style='padding:0px; width:50%'>
                                    <textarea class="form-control" style='width:100%; padding-top:200px;' name='manpower' autocomplete='off' placeholder='Add your justification here. .'></textarea>
									</td>
									<td style='width:20%; color:gray; padding-left:20px; font-size:14px;'> EDUCATION AND TRANING :</td>
									<td style='padding:0px; height:35px;'>
										<!--input list="educs" name="educ" id='intname' onkeyup="Check(this);" autocomplete="off" style="width:100%; height:100%; color:black;" required >
										<datalist id="educs"-->
											<select name="educ" style="width:100%; height:100%; color:black;" required>
											<option></option>
											<?php
											$corz = mysql_query("Select course_name From course ORDER BY course_name ASC");
											while($r = mysql_fetch_array($corz)){ 
											?>
											<option value='<?php echo $r['course_name']; ?>' ><?php echo $r['course_name']; ?></option>
											<?php } ?>
											</select>
										<!--/datalist-->
									</td>
								</tr>
								<tr >
									<td style='width:20%; color:gray; padding-left:20px; font-size:14px;'>
										WORK EXPERIENCE :
									</td>
									<td style='padding:0px;'>
										<textarea style='width:100%; color:black; height:100%;' name='work_exp' onkeyup="Check(this);"></textarea>
										<!--input type='text' style='width:100%; height:100%;' name='work_exp' onkeyup="Check(this);" autocomplete='off'-->
									</td>
								</tr>
								<tr>
									<td style='width:20%; color:gray; padding-left:20px; font-size:14px;'> 
										SPECIAL QUALIFICATIONS:
									</td>
									<td style='padding:0px;'>
										<textarea style='color:black; width:100%; height:100%;' name='spec_qual' onkeyup="Check(this);"></textarea>
										<!--input type='text' style='width:100%; height:100%;' name='spec_qual' onkeyup="Check(this);" autocomplete='off'-->
									</td>
								</tr>
							</table>
					   </div>
					</div>

                    <hr/> <!-- horizontal line separates  -->
                    
                    <div class="row">
                        <div class="col-md-12" >
                            <button type="submit" class="btn btn-success active"> Submit  </button> 
                        </div>
                    </div>    
                </form>
            </div>
        </div>
        
        <!-- <div class="col-lg-2">
            <div class="card pd-20">
                <h6 class="card-body-title"> Vacancy cause by Resignation/Termination:  </h6>
            </div>
            <br>
            <div class="card pd-20">
                <h6 class="card-body-title">Vacancy cause by Promotion/Transfer: </h6>               
            </div>
        </div> -->
    </div>
</div>
