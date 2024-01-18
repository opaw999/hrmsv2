<div class="am-pagebody">
  <div class="card pd-20 pd-sm-30 col-md-6 ">
        <h6 class="card-body-title" style="font-size: 20px">Birthday Celebrants Report</h6>
      <div class="form-group tx-12 ">
            <label for="exampleInputEmail1">Company</label>
            <select 
            id="company"
            required = "true"
            class="form-control tx-12" onchange="getLocation(this.value,'businessunit','employee/filter/bunit')"  style="width: 455px;">  
            <option value="">Select Company</option>
                    <?php foreach($comp as $row): 
                    ?>
                        
                    <option value="<?= $row['company_code']?>"><?=$row['acroname'];?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="form-group tx-12">
            <label for="exampleInputEmail1">Business Unit</label>
            <select class="form-control  h-100 tx-12" id="businessunit"  onchange="getLocation(this.value,'department','employee/filter/dept')" style="width: 455px;" required>
            <option value=''></option>
            </select>
        </div>
        <div class="form-group tx-12">
            <label for="exampleInputEmail1">Department</label>
            <select class="form-control  h-100 tx-12" id="department"  onchange="getLocation(this.value,'section','employee/filter/sect')" style="width: 455px;">
            <option></option> 
            </select>
        </div>
        <div class="form-group tx-12">
            <label for="exampleInputEmail1">Section</label>
            <select class="form-control h-100 tx-12" id="section" onchange="getLocation(this.value,'subsection','employee/filter/subsect')" style="width: 455px;">
            <option></option>
            </select>
        </div>
        <div class="form-group tx-12">
            <label for="exampleInputEmail1">Sub Section</label>
            <select class="form-control h-100 tx-12" id="subsection" style="width: 455px;">
            <option></option>
            </select>
        </div>

        <div class="form-group tx-12">
            <label for="exampleInputEmail1">Unit</label>
            <select class="form-control h-100 tx-12" id="unit" style="width: 455px;">
            <option></option>
            </select>
        </div>

        <div class="form-group tx-12">
        <label for="exampleInputEmail1">Birth Month</label>
        <select id="birthMonth" class="form-control tx-12" style="width: 455px;">
          <option value="">Select Month</option>
          <?php
            $months = array(
              'January', 'February', 'March', 'April',
              'May', 'June', 'July', 'August',
              'September', 'October', 'November', 'December'
            );

            foreach ($months as $month):
          ?>
            <option value="<?= $month ?>"><?= $month ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group tx-12">
        <label for="exampleInputEmail1"> Display Options</label> (for excel report only)
        <select name="displayopt" id="displayopt" class="form-control tx-12" style="width: 455px;">
                 <option value=""></option>    
				<option value="1">Sort Per month</option> 
				<option value="2">Sort by Name, Birthday</option> 
        </select>
      </div>

        
  </div>