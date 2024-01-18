    <div class="form-group tx-12">
        <label for="company">Company</label>
        <select class="form-control h-100 tx-12"  id='company' required
            onchange="getLocation(this.value,'businessunit','employee/filter/getbunit')"> 
            <option value=''> Select Company </option>
            <?php foreach($company as $comp): ?>                 
                <option value="<?= $comp['company_code']?>"><?=$comp['acroname'];?></option>
            <?php endforeach;?>
        </select>
    </div>
    <div class="form-group tx-12">
        <label for="businessunit">Business Unit</label>
        <select class="form-control h-100 tx-12" id="businessunit"  onchange="getLocation(this.value,'department','employee/filter/getdept')">
            <option value=''> Select Business Unit </option>
        </select>
    </div>
    <div class="form-group tx-12">
        <label for="department">Department</label>
        <select class="form-control h-100 tx-12" id="department" onchange="getLocation(this.value,'section','employee/filter/getsect')"> 
            <option value=''> Select Department </option> 
        </select>
    </div>
    <div class="form-group tx-12">
        <label for="section">Section</label>
        <select class="form-control h-100 tx-12" id="section" onchange="getLocation(this.value,'subsection','employee/filter/getsubsect')"> 
            <option value=''> Select Section </option>
        </select>
    </div>
    <div class="form-group tx-12">
        <label for="subsection">Sub Section</label>
        <select class="form-control h-100 tx-12" id="subsection">
            <option value=''> Select Sub Section </option>
        </select>
    </div> 
    <input type="hidden" name="redirect" id="redirect">