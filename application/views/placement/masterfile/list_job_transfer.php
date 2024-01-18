
<div class="am-pagebody">
  <div class="row row-sm">
    <div class="col-lg-12">
      <div class="card pd-20 pd-sm-30">
          <div class="row">          
            <div class="col-md-6">              
              <h6 class="card-body-title" style="font-size: 15px">JOBTRANSFER</h6>
            </div><br>
            <div class="col-md-6" align="right">            
              <div style="width:60%;border:px solid black;" align="right">  
                <form >
                    <select class="form-control" id="peryear" name='peryear' onchange=filterJobTransByDate(this.value)>
                        <option>Filter By Year</option>
                        <?php        
                        $commonYrTransfer = $this->db->query("SELECT EXTRACT(year from effectiveon) as year 
                        FROM employee_transfer_details  
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
                </form>
                <br>
              </div><!-- /input-group -->
            </div>
          </div>
          <br>
          <div class="table-wrapper">
            <table id="datatable"  class="table table-white table-bordered mg-b-0 tx-12">
             <thead>
               <tr>
                 <th class="wd-15p"></th>
                 <th class="wd-5p"></th>
                 <th class="wd-10p"></th>
                 <th class="wd-10p"></th>
                 <th class="wd-15p"></th>
                 <th class="wd-15p"></th>
                 <th class="wd-15p"></th>
               </tr>
             </thead>
           </table>
         </div><!-- table-wrapper -->
     </div>
   </div><!-- card -->
 </div><!-- col-6 -->
</div>
  <input type="hidden" name="redirect" id="redirect">

