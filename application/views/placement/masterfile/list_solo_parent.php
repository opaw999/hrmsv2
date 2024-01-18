<div class="am-pagebody">

  <div class="card pd-20 pd-sm-30">

    <div class="row">
      <div class="col-md-6">
        <h6 class="card-body-title" style="font-size: 20px">SOLO PARENT</h6>
      </div>
      <div class="col-md-6" align="right">            
              <div style="width:60%;border:px solid black;" align="right">  
                    <select class="form-control" id="peryear" name='peryear' onchange=filtersoloparent(this.value)>
                        <option>Filter By Year</option>
                        <?php        
                        $commonYrTransfer = $this->db->query("SELECT EXTRACT(year from date_entry) as year 
                        FROM solo_parent  
                        GROUP BY year");
                        $yr = date('Y');
                        foreach ($commonYrTransfer->result_array() as $y)
                          {
                          $selected = '';
                          if($yr == $y['year']) {
                              $selected = 'selected';
                          }
                          echo "<option value=\"" . $y['year'] . "\" $selected > " . $y['year'] . " </option>";
                         }
                      ?>    
                    </select>
                <br>
              </div><
    </div>
    <div class="table-responsive"><br>
      <table id="datatable" class="table table-white table-bordered mg-b-0 tx-12" width="100%">
        <thead>
          <tr>
            <!-- <th class="wd-5p">No</th> -->
            <th class="wd-20p">EMPID</th>
            <th class="wd-20p">NAME</th>
            <th class="wd-10p">EMPTYPE</th>
            <th class="wd-10p">POSITION</th>
            <th class="wd-10p">CURRENT STATUS</th>
            <th class="wd-10p">DATE ENTRY</th>
            <th class="wd-5p">DATE EXPIRY</th>
            <th class="wd-20p"> ENTRY BY</th>
            <th class="wd-5p">STATUS</th>
            <th class="wd-5p">ACTION</th>
          </tr>
        </thead>
      </table>
    </div><!-- table-wrapper -->

  </div>
</div>