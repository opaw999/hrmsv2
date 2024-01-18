<div class="am-pagebody">
    <div class="row row-sm">
        <div class="col-lg-3">
            <div class="card pd-20 pd-sm-30 text-center">
                
                <img src="<?= $url."".$profile->photo;?>" style="width:200px;height:200px;border-radius:50%;background-color:tomato;margin:auto;margin-bottom:20px">
                <h5 class="tx-gray-800 mg-b-25"> <?= $profile->lastname;?>, <?= $profile->firstname;?> <?= $profile->suffix;?> </h5> 
                <p class="mg-b-20 mg-sm-b-30"> 
                    <?=
                    $profile->emp_id."<br>".
                    "<b>".$profile->payroll_no."</b> <br> ".
                    $profile->position." <br> ".
                    $location." <br> ".
                    $profile->emp_type." <br> ".
                    $profile->startdate." - ". $eoc = ($profile->eocdate == "00/00/0000")? "Present" : $profile->eocdate; 
                    ?>   
                </p>
               
            </div><!-- card -->
        </div>
        <div class="col-lg-9">
            <div class="card pd-20 pd-sm-30">
                <h6 class="card-body-title"> PROFILE </h6>
                <div class="pd-10 bd mg-t-10">
                    <ul class="nav nav-pills flex-column flex-md-row" role="tablist"> <!--justify-content-center-->
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#basic" role="tab"> Basic Info </a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#family" role="tab"> Family </a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#contact" role="tab"> Contact </a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#educ" role="tab"> Education </a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#benefits" role="tab"> Benefits </a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#appraisal" role="tab"> Appraisal </a></li>
                    </ul>
                </div><!-- pd-10 --> 
                <div class="tab-content">  
                    <div class="tab-pane fade show active" id="basic">
                        <div class="col-lg-12">
                            <h5 class="tx-gray-800 mg-b-25"> <br> Basic Information </h5>                            
                            
                            <div class="form-group">
                                <label class="form-control-label"> Employee No. </label>
                                <input type="text" disabled class="form-control" value="<?= $profile->emp_id;?>" />
                            </div><!-- form-group -->
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                    <label class="form-control-label"> Firstname: </label>
                                    <input type="text" disabled class="form-control" value="<?= $profile->firstname;?>"/>
                                    </div><!-- form-group -->
                                </div><!-- col -->
                                <div class="col">
                                    <div class="form-group">
                                    <label class="form-control-label"> Lastname: </label>
                                    <input type="text" disabled class="form-control" value="<?= $profile->lastname;?>" />
                                    </div><!-- form-group -->
                                </div><!-- col -->
                            </div><!-- row -->

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                    <label class="form-control-label"> Middlename: </label>
                                    <input type="text" disabled class="form-control" value="<?= $profile->middlename;?>"/>
                                    </div><!-- form-group -->
                                </div><!-- col -->
                                <div class="col">
                                    <div class="form-group">
                                    <label class="form-control-label"> Suffix: </label>
                                    <input type="text" disabled class="form-control" value="<?= $profile->suffix;?>"/>
                                    </div><!-- form-group -->
                                </div><!-- col -->
                            </div><!-- row -->

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                    <label class="form-control-label"> Date of Birth: </label>
                                    <input type="text" disabled class="form-control" value="<?= $profile->bday;?>"/>
                                    </div><!-- form-group -->
                                </div><!-- col -->
                                <div class="col">
                                    <div class="form-group">
                                    <label class="form-control-label"> Citizenship: </label>
                                    <input type="text" disabled class="form-control" value="<?= $profile->citizenship;?>" />
                                    </div><!-- form-group -->
                                </div><!-- col -->
                            </div><!-- row -->

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                    <label class="form-control-label"> Gender: </label>
                                    <input type="text" disabled class="form-control" value="<?= $profile->gender;?>"/>
                                    </div><!-- form-group -->
                                </div><!-- col -->
                                <div class="col">
                                    <div class="form-group">
                                    <label class="form-control-label"> Civil Status: </label>
                                    <input type="text" disabled class="form-control" value="<?= $profile->civilstatus;?>" />
                                    </div><!-- form-group -->
                                </div><!-- col -->
                            </div><!-- row -->

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                    <label class="form-control-label"> Religion: </label>
                                    <input type="text" disabled class="form-control" value="<?= $profile->religion;?>"/>
                                    </div><!-- form-group -->
                                </div><!-- col -->
                                <div class="col">
                                    <div class="form-group">
                                    <label class="form-control-label"> Blood Type: </label>
                                    <input type="text" disabled class="form-control" value="<?= $profile->bloodtype;?>"/>
                                    </div><!-- form-group -->
                                </div><!-- col -->
                            </div><!-- row -->

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                    <label class="form-control-label"> Weight: </label>
                                    <input type="text" disabled class="form-control" value="<?= $profile->weight;?>"/>
                                    </div><!-- form-group -->
                                </div><!-- col -->
                                <div class="col">
                                    <div class="form-group">
                                    <label class="form-control-label"> Height: </label>
                                    <input type="text" disabled class="form-control" value="<?= $profile->height;?>" />
                                    </div><!-- form-group -->
                                </div><!-- col -->
                            </div><!-- row -->  
                            
                        </div><!-- col-7 -->
                    </div>  
                    <div class="tab-pane fade" id="family">                         
                        <div class="col-lg-12">
                            <h5 class="tx-gray-800 mg-b-25"> <br>Family Information </h5>                            
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                    <label class="form-control-label"> Mother: </label>
                                    <input type="text" disabled class="form-control" value="<?= $profile->mother;?>"/>
                                    </div><!-- form-group -->
                                </div><!-- col -->
                                <div class="col">
                                    <div class="form-group">
                                    <label class="form-control-label"> Father: </label>
                                    <input type="text" disabled class="form-control" value="<?= $profile->father;?>" />
                                    </div><!-- form-group -->
                                </div><!-- col -->
                            </div><!-- row -->
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                    <label class="form-control-label"> Spouse: </label>
                                    <input type="text" disabled class="form-control" value="<?= $profile->spouse;?>"/>
                                    </div><!-- form-group -->
                                </div><!-- col -->
                                <div class="col">
                                    <div class="form-group">
                                    <label class="form-control-label"> Guardian: </label>
                                    <input type="text" disabled class="form-control" value="<?= $profile->guardian;?>" />
                                    </div><!-- form-group -->
                                </div><!-- col -->
                            </div><!-- row -->
                            <div class="form-group">
                                <label class="form-control-label"> Children/s: </label>
                                <input type="text" disabled class="form-control" />
                            </div><!-- form-group -->
                        </div><!-- col-7 -->
                    </div>  
                    <div class="tab-pane fade" id="contact">
                        <div class="col-lg-12">
                            <h5 class="tx-gray-800 mg-b-25"> <br>Contact Information </h5>                            
                            
                            <div class="form-group">
                                <label class="form-control-label"> Home Address: </label>
                                <input type="text" disabled class="form-control" value="<?= $profile->home_address;?>" />
                            </div><!-- form-group -->
                            <div class="form-group">
                                <label class="form-control-label"> City Address: </label>
                                <input type="text" disabled class="form-control" value="<?= $profile->city_address;?>"/>
                            </div><!-- form-group -->
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                    <label class="form-control-label"> Cellphone No.: </label>
                                    <input type="text" disabled class="form-control" value="<?= $profile->contactno;?>"/>
                                    </div><!-- form-group -->
                                </div><!-- col -->
                                <div class="col">
                                    <div class="form-group">
                                    <label class="form-control-label"> Email: </label>
                                    <input type="text" disabled class="form-control" value="<?= $profile->email;?>" />
                                    </div><!-- form-group -->
                                </div><!-- col -->
                            </div><!-- row -->

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                    <label class="form-control-label"> Contact Person: </label>
                                    <input type="text" disabled class="form-control" value="<?= $profile->contact_person;?>" />
                                    </div><!-- form-group -->
                                </div><!-- col -->
                                <div class="col">
                                    <div class="form-group">
                                    <label class="form-control-label"> Contact Person No.: </label>
                                    <input type="text" disabled class="form-control" value="<?= $profile->contact_person_number;?>" />
                                    </div><!-- form-group -->
                                </div><!-- col -->
                            </div><!-- row -->
                            <div class="form-group">
                                <label class="form-control-label"> Contact Person Address: </label>
                                <input type="text" disabled class="form-control" value="<?= $profile->contact_person_address;?>"/>
                            </div><!-- form-group -->
                            
                        </div><!-- col-7 -->
                    </div>  
                    <div class="tab-pane fade" id="educ">
                        <div class="col-lg-12">
                            <h5 class="tx-gray-800 mg-b-25"> <br>Educational Information </h5>  
                            <div class="form-group">
                                <label class="form-control-label"> Educational Attainment: </label>
                                <input type="text" disabled class="form-control" value="<?= $profile->attainment;?>"/>
                            </div><!-- form-group -->
                            <div class="form-group">
                                <label class="form-control-label"> School: </label>
                                <input type="text" disabled class="form-control" value="<?= $profile->school;?>"/>
                            </div><!-- form-group -->                            
                            <div class="form-group">
                                <label class="form-control-label"> Details/Course: </label>
                                <input type="text" disabled class="form-control" value="<?= $profile->course;?>" />
                            </div><!-- form-group -->                            
                        </div><!-- col-7 -->
                    </div>                    
                    <div class="tab-pane fade" id="benefits">
                        <div class="col-lg-12">
                            <h5 class="tx-gray-800 mg-b-25"> <br> Government Benefits </h5> 
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label"> Philhealth No.</label>
                                        <input type="text" disabled class="form-control" value="<?= $profile->philhealth;?>"/>
                                    </div><!-- form-group -->  
                                </div><!-- col -->
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label"> SSS No.</label>
                                        <input type="text" disabled class="form-control" value="<?= $profile->sss_no;?>"/>
                                    </div><!-- form-group -->    
                                </div><!-- col -->
                            </div><!-- row -->
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label"> Pagibig MID No. </label>
                                        <input type="text" disabled class="form-control" value="<?= $profile->pagibig;?>"/>
                                    </div><!-- form-group -->  
                                </div><!-- col -->
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-control-label"> Pagibig RTN </label>
                                        <input type="text" disabled class="form-control" value="<?= $profile->pagibig_tracking;?>"/>
                                    </div><!-- form-group -->    
                                </div><!-- col -->
                            </div><!-- row -->                            
                            <div class="form-group">
                                <label class="form-control-label"> TIN </label>
                                <input type="text" disabled class="form-control" value="<?= $profile->tin_no;?>"/>
                            </div><!-- form-group -->                        
                        </div><!-- col-7 -->
                    </div>
                    <div class="tab-pane fade" id="appraisal">
                        <div class="col-lg-12">
                            <h5 class="tx-gray-800 mg-b-25"> <br>EOC Appraisal </h5> 
                            <table id="datatable" class="table table-bordered display responsive nowrap">
                                <thead>
                                    <tr>
                                        <th class="wd-15p">STARTDATE </th>
                                        <th class="wd-15p">EOCDATE </th>
                                        <th class="wd-20p">RATER </th>
                                        <th class="wd-15p">RATING </th>
                                        <th class="wd-10p">DATE </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div><!-- card -->
                    </div>
                </div>      
            </div><!-- card -->
        </div>
    </div>   
</div>