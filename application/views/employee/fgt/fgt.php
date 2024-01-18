<?php


$tsize    = array("XS","S","M","L","XL","XXL");

if($validemp == TRUE)
{   
?>
<div class="am-pagebody">
  <div class="row row-sm"> <!-- mg-t-15 mg-sm-t-20-->		
		<div class="col-md-4">
            <div class="card rounded-0">
                <div class="card-header card-header-default">
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
                <div class="card-header card-header-default">
                 FGT HOTLINE
                </div><!-- card-header -->
                <div class="card-body"> ■ Please call the nearest HRD office for all your concerns. <br>
					 <br>
					(1314) HRD CORPORATE - IP PHONE  <br>
					(3212) HRD ALTURAS -IP PHONE  <br> 
				    &nbsp; (205)  HRD MARCELA- LOCAL  <br> 
					(3106) HRD ICM- IP PHONE <br>
					(2302) HRD COMMISSARY-IPHONE <br>
					(1318) HRD SPRP- IP PHONE  <br>
					(4302) HRD CDC - IP PHONE   <br>	
				</div>
            </div>
        </div>
        <div class="col-md-8">
           <div class="card rounded-0">
                    <div class="card-header card-header-default">
                           FGT Food Budget Confirmation
                     </div><!-- card-header -->
                  <div class="card-body">
                        <h5> Join the yearly-FGT on January 01, 2023 and get a chance to win EXCITING PRIZES! <br> 
                        Confirm your attendance and choose your t-shirt size then hit the submit button. <br>
                        <b style="color:red;font-size:18px"> Deadline of confirmation is on December 9, 2022.</b></h5>

                        <form method="POST" id="food_budget_form" >
						<table class="table table-bordered table-striped">';
							//******************** START SELF CONFIRMATION **********************//
                            <?php
							echo '
							<tr> 
								<th width="500px"> NAME OF EMPLOYEE </th>
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
										<input type="radio" id="yes" name="emp_response" required onclick=show_tshirt("tshirt_div",this.value) value="Yes" '.(($conemp['confirmed']=="Yes")?"checked":"").' >
										<label for="yes"> YES </label><br>
										<input type="radio" id="no" name="emp_response" required onclick=show_tshirt("tshirt_div",this.value) value="No" '.(($conemp['confirmed']=="No")?"checked":"").' >
										<label for="no"> NO </label><br><br>'; 
									 						 $dis = '';			
										if($conemp['confirmed']=='No'){ $dis="style='display:none'"; }
										echo '
										<div id="tshirt_div" '.$dis.'>
											<b> CHOOSE YOUR T-SHIRT SIZE </b>(Dri-Fit)
											<select name="tshirtsize" class="form-control" style="width:35%" required>
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
							</tr>
                           </table>
                           </form>';
                          ?>
                 </div>
           </div>
        </div>
    </div>
</div>

<?php  } ?>