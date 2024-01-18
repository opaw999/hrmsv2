
<div class="am-pagebody">
  <div class="row row-sm">
    <div class="col-lg-12">
      <div class="card pd-0">
          <div class="row">          
            <div class="col-md-6">              
              <h6 class="card-body-title" style="font-size: 18px">LOYALTY AWARDEES MONITORING</h6>
            </div><br>
            <div class="col-md-6" align="right">            
              <div style="width:60%;border:px solid black;" align="right">  
                <form >
                    <select class="form-control" id="peryear" name='peryear' onchange=filterLoyaltyawardee(this.value)>
                        <option>Filter By Year</option>
                        <?php        
                        $commonYrTransfer = $this->db->query("SELECT DISTINCT(`year`) FROM `loyalty_awardees` order by year desc");
                        $yr = date('Y')-1;
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
                </form>
                <br>
              </div><!-- /input-group -->
            </div>
          </div>
          <br>
                    <!-- <div class="table-wrapper"> -->
                        <div class="table-responsive">
                                <table id="datatable" class="table table-white table-bordered tx-12">
                                    <thead>
                                    <tr>
                                        <th >NAME</th>
                                        <th >DEPARTMENT</th>
                                        <th >POSITION</th>
                                        <th >STATUS</th>
                                        <th >DATEHIRED</th>
                                        <th >YRSINSERVICE</th>
                                        <th >YEAR</th>
                                        <th >ACTION</th>
                                    </tr>
                                    </thead>
                                </table>
                        </div><!-- table-responsive -->
                    <!-- </div>table-wrapper -->
            </div>
        </div><!-- card -->
    </div><!-- col-6 -->
</div>
  

