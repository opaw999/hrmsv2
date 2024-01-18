<?php

$empid  = $_SESSION['emp_id'];//$_GET['emp'];
$validemp = $this->fgtmodel->check_valid_emp($empid);

if($validemp == TRUE)
{    
    
?>	<div class="am-pagebody">
  <div class="row row-sm"> <!-- mg-t-15 mg-sm-t-20-->		
		<div class="col-md-4">
            <div class="card rounded-0">
                <div class="card-header bg-primary text-white">
                  FAMILY GET TOGETHER
                </div><!-- card-header -->
                <div class="card-body">               
                    <b style='color:green; font-size:18px'> <img src="<?= base_url('assets/images/fgt/family.png');?>" width='40' height='40' > AGC FAMILY GET-TOGETHER </b>		              
                   <div id="myCarousel" class="carousel slide" data-ride="carousel"><br>
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                            <li data-target="#myCarousel" data-slide-to="1"></li>
                            <li data-target="#myCarousel" data-slide-to="2"></li>
                        </ol>

                        <!-- Wrapper for slides -->
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="<?= base_url('assets/images/fgt/img1.jpg');?>" alt="Los Angeles" style="width:100%;">
                            </div>
                            <div class="carousel-item">
                                <img src="<?= base_url('assets/images/fgt/img2.jpg');?>" alt="Chicago" style="width:100%;">
                            </div>
                            <div class="carousel-item">
                                <img src="<?= base_url('assets/images/fgt/img3.jpg');?>" alt="New York" style="width:100%;">
                            </div>
                        </div>

                        <!-- Left and right controls -->
                        <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div><br>

           <div class="card rounded-0">
				<div class="card-header bg-primary text-white">
					FGT HOTLINE
				</div><!-- card-header -->
				<div class="card-body">
					<ul class="list-unstyled">
						<li></i> Please call the nearest HRD office for all your concerns.</li><br>
						<li><i class="ion-ios-telephone text-danger"></i> &nbsp;(1314) HRD CORPORATE - IP PHONE</li>
						<li><i class="ion-ios-telephone text-danger "></i>&nbsp;(3212) HRD ALTURAS - IP PHONE</li>
						<li><i class="ion-ios-telephone text-danger"></i> &nbsp;(205) HRD MARCELA - LOCAL</li>
						<li><i class="ion-ios-telephone text-danger"></i> &nbsp;(3106) HRD ICM - IP PHONE</li>
						<li><i class="ion-ios-telephone text-danger"></i> &nbsp;(2302) HRD COMMISSARY - IPHONE</li>
						<li><i class="ion-ios-telephone text-danger"></i> &nbsp;(1318) HRD SPRP - IP PHONE</li>
						<li><i class="ion-ios-telephone text-danger"></i> &nbsp;(4302) HRD CDC - IP PHONE</li>
					</ul>
				</div>
			</div>
        </div>

		<div class="col-md-8">
        <div class="card bg-default">
                <div class="card-header bg-primary text-white"> <h4> FGT Food Budget Confirmation </h4> </div>
				<div class="card-body">
					<?php
					//TSHIRT SIZE ARRAY
					$tsize    = array("XS","S","M","L","XL","XXL"); 
					//APPLICANT AND EMPLOYEE DETAILS
				   $employee = $this->fgtmodel->get_details($empid);
                   $spouse   =  $this->fgtmodel->get_spouse($empid);
					//RETURN SPOUSE ID
					$spouseid = $this->fgtmodel->get_spouse_id($empid);
					
					//RETURN LIST OF CHILDREN
					$children = $this->fgtmodel->get_children($spouseid);
					$conemp   = $this->fgtmodel->get_confirm_employee($empid); 
					if($conemp){						
						$conspo   = $this->fgtmodel->get_confirm_spouse($conemp['confirmno1']);
					}else{
						$conspo   = "";
					}

					$spousetype = $this->fgtmodel->get_spouse_type($empid);
					     
					$conby    = $this->fgtmodel->check_if_confirm_by_husband($empid,$conemp['confirmno1']); 
					$conby_no = $this->fgtmodel->count_if_confirmed_by($empid,$conemp['confirmno1']);                        
					$confirmby= $this->fgtmodel->get_details($conby['confirmedby'])['name'];											
					$confirm  = $this->fgtmodel->get_confirmation_details($conby['confirmno1'])['confirmed'];  					
             				
					echo '
					<h5 class="card-title">Join the yearly-FGT on January 01, 2023 and get a chance to win EXCITING PRIZES!<br>Confirm your attendance and choose your t-shirt size then hit the submit button.<br><b style="color:red;font-size:18px">Deadline of confirmation is on December 9, 2022.</b></h5>

						
					<form method="POST" id="food_budget_form"  onsubmit="return submitForm();" action="">
						<table class="table table-bordered table-striped">';
							//******************** START SELF CONFIRMATION **********************//
							echo '
							<tr> 
								<th width="350px"> NAME OF EMPLOYEE </th>
								<th> PLEASE CONFIRM </th>
							</tr>
							<tr> 
								<td> '.$this->session->userdata('employee_name').' <hr>';
									//IF NAKA SELECT NA PREVIOUSLY ANG USER MUGAWAS NI
									if($conemp['confirmed'] != ""){									
										echo '<b style="color:red"><i> 
										<ul> 
											<li> You previously confirmed <u>'.$conemp['confirmed'].'</u>.</li>';
											if($conemp['confirmed']=='Yes'){
												echo '<li> Your t-shirt size >> '.$conemp['tshirt'].' </li>';
											}
											echo '
										</ul>
										</i> </b>';
									}
									echo '
								</td>
								<td>';
									//IF CONFIRMED BY PARTNER DLI NA MAKA SELECT
									if($conby_no > 0){
										echo '<b style="color:red"><i> 
											<ul> 
												<li> <b style="color:red">'.$conby['confirmed'].'</b> <i style="color:red">(Confirmed by '.$confirmby.')</i> </li>';
												if($conby['confirmed'] == "Yes"){
													echo '<li> Your t-shirt size >> '.$conby['tshirt'].' </li>';
												}
												echo '
											</ul>';
									}else{
										echo '
										<input type="radio" id="yes" name="emp_response"  onclick=show_tshirt("tshirt_div",this.value) value="Yes" '.(($conemp['confirmed']=="Yes")?"checked":"").' required >
										<label for="yes"> YES </label><br>
										<input type="radio" id="no" name="emp_response"  onclick=show_tshirt("tshirt_div",this.value) value="No" '.(($conemp['confirmed']=="No")?"checked":"").' required>
										<label for="no"> NO </label><br><br>'; 
                                        $dis= '';
									 									
										if($conemp['confirmed']=='No'){ $dis="style='display:none'"; }
										echo '
										<div id="tshirt_div" '.$dis.'>
											<b> CHOOSE YOUR T-SHIRT SIZE </b>(Dri-Fit)
											<select name="tshirtsize" class="form-control" style="width:45%" required>
												<option></option>';											
												foreach($tsize as $row){													
													if($row == $conemp['tshirt'])
														echo "<option value='$row' selected> $row </option>";
													else
														echo "<option value='$row'> $row </option>";
												} echo'
											</select>
										</div>';
									}
									echo '
								</td>
							</tr>';
							//******************** END SELF CONFIRMATION **********************//
							//-----------------------------------------------------------------//				
							//******************** START SPOUSE CONFIRMATION ******************//
							if($employee['emp_type'] == "Regular") 
							{                               
								//check if naay setup sa spouse ug children MARRIED AND NAAY SPOUSE
								if($employee['civilstatus'] != "Annulled" && $employee['civilstatus'] != "Widowed" && $spouse !='')
								{ 
									echo '                                       
									<tr> 
										<th> NAME OF SPOUSE </th>
										<th> PLEASE CONFIRM </th>
									</tr>
									<tr> 
										<td> '.utf8_encode($spouse);
											//IF NAKA SELECT NA PREVIOUSLY ANG USER MUGAWAS NI
											if(isset($conspo['confirmed']) && $conspo['confirmed'] != ""){									
												echo '<hr><b style="color:red"><i> 
												<ul> 
													<li> You previously confirmed <u>'.$conspo['confirmed'].'</u>. </li>';
													$spousetype = $this->fgtmodel->get_spouse_type($empid);
													if($spousetype == ''){
														//echo '<li> Your t-shirt size >> '.$conspo['tshirt'].' </li>';	
													}else if($conspo['confirmed']=='Yes'){
														echo '<li> Your t-shirt size >> '.$conspo['tshirt'].' </li>';
													}
													echo '
												</ul>
												</i> </b>';
											}
											echo '
										</td>
										<td>';
											//IF CONFIRMED BY PARTNER DLI NA MAKA SELECT
											if($conby_no > 0){ //greater than 0 if na confirm nas partner
												echo '<b style="color:red"><i> 
												<ul> 
													<li> <b style="color:red">'.$confirm.'</b> <i style="color:red"> (Confirmed by '.$confirmby.')</i> </li>';
													if($confirm == "Yes"){
														echo '<li> Your t-shirt size >> '.$this->fgtmodel->get_confirmation_details($conby['confirmno1'])['tshirt'].' </li>';	
													}
													echo '
												</ul>';
											}else{
												if($spousetype == true) // if employee ang partner
												{
													echo '<b style="color:red">Will be confirmed by the employee himself/herself.</b>';
												}
												else // if dli employee ang partner
												{ 
												   echo '
                                                        <input type="radio" id="yes" name="spouse_response" onclick=show_tshirt("sp_tshirt_div",this.value) value="Yes" '.(isset($conspo['confirmed']) && $conspo['confirmed'] == 'Yes' ? 'checked' : '').'>
                                                        <label for="yes"> YES </label><br>
                                                        <input type="radio" id="no" name="spouse_response" onclick=show_tshirt("sp_tshirt_div",this.value) value="No" '.(isset($conspo['confirmed']) && $conspo['confirmed'] == 'No' ? 'checked' : '').'>
                                                        <label for="no"> NO </label><br>';

												}
											} 																						
											echo '
										</td>
									</tr>';
								}							
								
								//******************** END SPOUSE CONFIRMATION **********************//
								//-------------------------------------------------------------------//				
								//******************** START CHILDREN CONFIRMATION ******************//								
								if($spouseid)
								{
									echo '
									<tr> 
										<th> NAME OF CHILDREN </th>
										<th> PLEASE CONFIRM </th>
									</tr>';
									$ctr = 0;
                                        $children_info = $this->fgtmodel->get_children($spouseid);

                                        foreach ($children_info as $row) 
									{
										$age    = $this->fgtmodel->calculate_age($row['bday']);
										$cname  = $row['lastname'].", ".$row['firstname'];										

										$spouseid = $this->fgtmodel->get_field('spouse_empId', 'spouse_info', "`empId` = '$empid' ")['spouse_empId'];
										$confirmno= $this->fgtmodel->get_field('confirmno1', 'fgt_confirm_employee', "empid = '$spouseid' and fgtdate = '".$this->fgtmodel->fgtdate()."' ")['confirmno1'];
										
										//get record sa employee nga naka login ig naka confirm na under niya
										$conchi_emp=$this->fgtmodel->get_confirm_children($conemp['confirmno1'], $cname);	
										//get record sa partnert sa employee nga naka login ug naka confirm na sa iya
										$conchi_partner = $this->fgtmodel->get_confirm_children($confirmno, $cname)['confirmed'];
										
										if($conchi_partner == true)
										{											
											$conchi =$this->fgtmodel->get_confirm_children($confirmno, $cname);
										}else{
											$conchi = $this->fgtmodel->get_confirm_children($conemp['confirmno1'], $cname);
										}
										
										$confirmby= $this->fgtmodel->get_field('name', 'employee3', "emp_id = '".$conchi['confirmedby']."' ")['name'];	
										if($conchi){
											$res = "update";
											$cno = $conchi['cconfirmno'];
										}else{
											$res = "insert";
											$ctr++;
											$cno = $ctr;
										}

										echo '
										<tr> 
											<td> 
												<input type="hidden" name="children_id[]" value='.$cno.'>
												<input type="hidden" name="cconfirmno[]" value='.$conchi['cconfirmno'].'>
												<input type="hidden" name="childname[]" value="'.$cname.'">
												'.utf8_encode($row['lastname']).', '.$row['firstname'].' ('.$age.' yr/s old) ';
												
												if($conchi['confirmed'] != ""){
													echo '<hr>
													<b style="color:red"><i> 
													<ul> 
														<li> <b style="color:red"> You previously confirmed: '.$conchi['confirmed'].'</b>
														<br> Confirmed by: '. utf8_encode($confirmby).'</b> </li> 
													</ul>';
												}
												echo '</td>
											<td>
												<input type="hidden" name="code" value='.$res.'>												
												<input type="radio" id="yes" value="Yes" 
												name="confirm_children_'.$cno.'[]" '.(($conchi['confirmed'] == "Yes")? "checked":"").'>
												<label> YES </label><br>

												<input type="radio" id="no" value="No" 
												name="confirm_children_'.$cno.'[]" '.(($conchi['confirmed'] == "No")? "checked":"").'>
												<label> NO </label><br>
											</td>
										</tr>';
									}
								}								
							} 
							echo '
								<tr>
									<td></td>
									<td>
										<input type="submit" value="Submit" class="btn btn-primary">
									</td>
								</tr>
						</table>
					</form>'; //?>                   
				</div>
            </div>
		</div>		
	</div>                 
<?php }else{
    echo "<center><h1> PAGE NOT FOUND  </h1></center>";
}?>
<script>
// $(document).ready(function($) {
	function submitForm() {
    var formData = new FormData(document.querySelector("#food_budget_form"));

    Swal.fire({
        title: "Confirm Food Budget",
        text: "Please click Yes to submit",
        icon: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes!",
        cancelButtonText: "No!",
        allowOutsideClick: false,
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?= base_url('employee/insertingfgt')?>",
                type: 'POST',
                data: formData,
                enctype: 'multipart/form-data',
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    var dataArray = data.split("+");
                    Swal.fire(dataArray[0], dataArray[1], dataArray[2]);
                    if (dataArray[0] == "Success") {
                         setTimeout(function() {
                           window.location.href = window.location.href;
                       }, 2000);
                    }
                },
            });
        }
    });

    return false; // Prevent form from actually submitting
}
	

function show_tshirt(div,ans)
{
    if(ans == "Yes"){
        $("#"+div).show();
		if(div == "tshirt_div"){
			$("[name='tshirtsize']").prop('required', true);
		}else{
			$("[name='spouse_tshirt']").prop('required', true);
		}		
    }else{
        $("#"+div).hide();
		if(div == "tshirt_div"){
			$("[name='tshirtsize']").removeAttr('required');
		}else{
			$("[name='spouse_tshirt']").removeAttr('required');
		}		
    }
}

</script>
