<div class="am-pagebody">  
    <div class="card pd-20 pd-sm-30 ">
      <h6 class="card-body-title">Reports</h6>
      <br>
      <div class="row" data-widget="tab-slider">
        <div class="col-3">
          <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <a class="nav-link active" id="newemployee" onclick="navreport('newemployee','employee/qbe/newemployee')" href="#">New Employee Report</a>
            <a class="nav-link" id="nobenefits"  onclick="navreport('nobenefits','employee/qbe/nobenefits')" href="#">No SSS, Philhealth, Pagibig Report</a>
            <!-- <a class="nav-link" id="regularcontractual" onclick="navreport('regularcontractual','employee/qbe/regularcontractual')" href="#">Regular/Contractual Report</a> -->
            <a class="nav-link" id="employeetype"  onclick="navreport('employeetype','employee/qbe/employeetype')" href="#">Active Employee Report</a>
          </div>
        </div> <!-- col-3 -->
        <div class="col-9">
          <div class="tab-content" id="nav-report"></div>
        </div>
     </div>
   </div> 
</div>
