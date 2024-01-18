 <div class="form-group tx-12">
    <label for="exampleInputEmail1">Company</label>
    <select 
     id="company"
     required = "true"
     class="form-control  h-100 tx-12" onchange="getLocation(this.value,'businessunit','employee/filter/bunit')">  
     <option value="">Select Company</option>
             <?php foreach($company as $comp): 
              ?>
                 
               <option value="<?= $comp['company_code']?>"><?=$comp['acroname'];?></option>
        <?php endforeach;?>
    </select>
  </div>
  <div class="form-group tx-12">
    <label for="exampleInputEmail1">Business Unit</label>
    <select class="form-control  h-100 tx-12" id="businessunit"  onchange="getLocation(this.value,'department','employee/filter/dept')" required>
      <option value=''></option>
    </select>
  </div>
  <div class="form-group tx-12">
    <label for="exampleInputEmail1">Department</label>
    <select class="form-control  h-100 tx-12" id="department"  onchange="getLocation(this.value,'section','employee/filter/sect')">
      <option></option> 
    </select>
  </div>
  <div class="form-group tx-12">
    <label for="exampleInputEmail1">Section</label>
    <select class="form-control h-100 tx-12" id="section" onchange="getLocation(this.value,'subsection','employee/filter/subsect')">
      <option></option>
    </select>
  </div>
  <div class="form-group tx-12">
    <label for="exampleInputEmail1">Sub Section</label>
    <select class="form-control h-100 tx-12" id="subsection">
      <option></option>
    </select>
  </div>

  <div class="form-group tx-12">
    <label for="exampleInputEmail1">Emptype</label>
    <select class="form-control h-100 tx-12" id="emptype">
          <option value='all'>All</option>
					<option value='1'>Regular</option>
					<option value='2'>Contractual</option>
          <option value='3'>Probationary</option>
					<option value='4'>Partimer</option>
					<option value='5'>NESCO</option>  

    </select>
  </div>


  <input type="hidden" name="redirect" id="redirect">
 