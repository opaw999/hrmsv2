<div class="am-pagebody">
    
    <div class="row row-sm" style="font-size:0.95em">
        <div class="col-lg-3">
            <div class="card pd-10 pd-sm-20"> 
                <?php
                echo "
                <img src='$url$profile->photo' style='width:180px;height:180px;border-radius:50%;margin:auto;'>
                <hr> $profile->emp_id
                <h5 class='tx-gray-800 mg-b-25'> $profile->lastname, $profile->firstname $profile->suffix $profile->middlename </h5>
                <i> $profile->position </i> <br>
                $location <br>
                $profile->emp_type <br>
                $profile->startdate - "; echo ($profile->eocdate == "00/00/0000")? "Present" : $profile->eocdate; 
                ?>
                <h4> <span class="badge badge-success">  Active </span> </h4>                                  
            </div>
            <BR>
            <div class="card pd-10 pd-sm-15"> 
                <h6 class="tx-gray-800 mg-b-10"> Basic Information </h6> 
                <?php
                $column_arr = array("Date of Birth:"=> $profile->bday, "Citizenship:" =>$profile->citizenship, "Gender:" => $profile->gender,
                                    "Civil Status:" => $profile->civilstatus, "Religion:" => $profile->religion, "Blood Type:" => $profile->bloodtype,
                                    "Weight: " => $profile->weight, "Height:" => $profile->height); ?>
                
                <table class="table table-white table-bordered">
                    <?php foreach($column_arr as $key => $value) { echo "<tr> <td width='110'> $key </td> <th> $value </th> </tr>";  } ?>                    
                </table>
                <hr> <br>            
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card pd-10 pd-sm-10">  
                <div class="col-lg-12">                 
                    
                    <h6 class="tx-gray-800 mg-b-10"> <br>Family Background </h6>   
                    <table class="table table-white table-bordered" >
                        <tr>
                            <td width="150"> Mother:  </td>
                            <th width="300"> <?= $profile->mother;?> </th>
                            <td width="150"> Spouse: </td>
                            <th> <?= $profile->spouse;?> </th>
                        </tr>
                        <tr>
                            <td> Father:  </td>
                            <th> <?= $profile->father;?> </th>
                            <td> Guardian: </td>
                            <th> <?= $profile->guardian;?> </th>
                        </tr>
                    </table>
                    
                    <h6 class="tx-gray-800 mg-b-10"> Contact Information </h6> 
                    <table class="table table-white table-bordered">
                        <tr>
                            <td width="150"> Home Address:  </td>
                            <th colspan="3"> <?= $profile->home_address;?> </th>
                        </tr>
                        <tr>
                            <td> City Address: </td>
                            <th colspan="3"> <?= $profile->city_address;?> </th>
                        </tr>
                        <tr>
                            <td > Cellphone No.:  </td>
                            <th width="300"> <?= $profile->contactno;?> </th>
                            <td width="150"> Email: </td>
                            <th> <?= $profile->email;?> </th>
                        </tr>
                        <tr>
                            <td>  Contact Person:   </td>
                            <th> <?= $profile->contact_person;?> </th>
                            <td> Contact Person No.:  </td>
                            <th> <?= $profile->contact_person_number;?> </th>
                        </tr>
                        <tr>
                            <td> Contact Person Address:  </td>
                            <th colspan="3"> <?= $profile->contact_person_address;?> </th>
                        </tr>
                    </table> 

                    <h6 class="tx-gray-800 mg-b-10"> Educational Information </h6>   
                    <table class="table table-white table-bordered">
                        <tr>
                            <td width="150"> Attainment:  </td>
                            <th colspan="3"> <?= $profile->attainment;?> </th>
                        </tr>
                        <tr>
                            <td> School: </td>
                            <th colspan="3"> <?= $profile->school;?> </th>
                        </tr>
                        <tr>
                            <td> Details/Course:  </td>
                            <th colspan="3"> <?= $profile->course;?> </th>
                        </tr>
                    </table>

                    <h6 class="tx-gray-800 mg-b-10"> Government Benefits </h6>   
                    <table class="table table-white table-bordered">
                        <tr>
                            <td width="150"> Philhealth No.  </td>
                            <th width="300"> <?= $profile->philhealth;?> </th>
                            <td width="150"> SSS No. </td>
                            <th> <?= $profile->sss_no;?> </th>
                        </tr>
                        <tr>
                            <td> Pagibig MID No. </td>
                            <th> <?= $profile->pagibig;?> </th>
                            <td> Pagibig RTN </td>
                            <th> <?= $profile->pagibig_tracking;?> </th>
                        </tr>
                        <tr>
                            <td> TIN </td>
                            <th colspan="3"> <?= $profile->tin_no;?> </th>
                        </tr>
                    </table>

                    <h6 class="tx-gray-800 mg-b-10"> Application History </h6>   
                    <table class="table table-white table-bordered">
                        <tr>
                            <td width="150"> Date Applied:  </td>
                            <th width="300">  <?= $appdetails->dateapplied;?></th>
                            <td width="150"> Date Hired: </td>
                            <th> <?= $appdetails->datehired;?></th>
                        </tr>
                        <tr>
                            <td> Recommended by: </td>
                            <th colspan="3"> <?= $appdetails->aeregular;?>  </th>
                        </tr>
                    </table>
                </div>
            </div>
            <br>
        </div>
    </div>   
   
    <button id="scrollToTopBtn" class="scroll-to-top-btn" title="Scroll to Top">
        <i class="icon ion-arrow-up-a"></i>
    </button>      
</div>