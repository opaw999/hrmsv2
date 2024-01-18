  <!-- <link href="<?= base_url(); ?>assets/css/jquery-ui.css" rel="stylesheet" />
<script src="<?= base_url(); ?>assets/js/jquery-ui.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/jquery-3.3.1.min.js"></script> -->

<div class="am-pagebody">
  
 <div class="card pd-20 pd-sm-30">
 
 <div class="row">
  <div class="col-md-11">
    <h6 class="card-body-title" style="font-size: 20px">NEW SET OF EMPLOYEES FROM <?= date("M d, Y", strtotime("-1 month"))." ".date("M d, Y");?></h6>
  </div>
  <div class="col-sm-6 col-md-12">
    <form class="d-flex justify-content-end align-items-center">
      <button type="button"
        class="btn btn-success mr-1"
        modal-size=""
        modal-route=""
        modal-form="employee/modal_new_employees"
        modal-redirect="employee/filter_employee/"
        modal-skeleton="0"
        modal-id=""
        modal-atype="POST"
        modal-title="Filter Employees"
        onclick="modal(event)">Filter</button>
      <!-- <div class="input-group wd-300">
        <div class="input-group-prepend">
          <span class="btn btn-success"><i class="fa fa-calendar"></i></span>
        </div>
        <select class="form-control" name="month" onchange = 'window.location = "<?= base_url();?>" + "employee/new_employees?filter_date=" + this.value'>
                 <option value='<?php echo $m_plus;?>' <?php if($permonth == $m_plus){ echo 'selected';}?>>Month of <?php echo $month_plus;?></option>	
								<option value='<?php echo $m;?>' <?php if($permonth == $m || $permonth ==''){ echo 'selected';}?>>Month of <?php echo $month_m;?></option>	
								<option value='<?php echo $m_minus;?>' <?php if($permonth == $m_minus){ echo 'selected';}?>>Month of <?php echo $month_minus;?></option>	
        </select>
      </div> -->
    </form>
  </div>
</div>
  <br> 
  <br> 
   <div class="table-wrapper">
     <table id="datatable" class="table table-white table-bordered mg-b-0 tx-12">
       <thead>
         <tr>
           <th class="wd-5p"></th>
           <th class="wd-15p"></th>
           <th class="wd-15p"></th>
           <th class="wd-15p"></th>
           <th class="wd-10p"></th>
           <th class="wd-15p"></th>
           <th class="wd-15p"></th>
           <th class="wd-15p"></th>
         </tr>
       </thead>
     </table>
   </div><!-- table-wrapper -->
 </div><!-- card -->
</div>
  

  
