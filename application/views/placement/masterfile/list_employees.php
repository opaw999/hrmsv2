<div class="am-pagebody">

  <div class="card pd-20 pd-sm-30">

    <div class="row">
      <div class="col-md-6">
        <h6 class="card-body-title" style="font-size: 20px">ALL EMPLOYEES</h6>
      </div>
      <div class="col-sm-5 col-md-6">
        <form>
          <button type="button" class="btn btn-success float-right" modal-size="" modal-route=""
            modal-form="masterfile/modal_employees" modal-skeleton="0" modal-redirect="masterfile/filter_employees/"
            modal-id="" modal-atype="POST" modal-title="Filter Employees" onclick="modal(event)">Filter</button>
        </form>
        <div class="col-sm-11">
          <a type="button" href="<?= base_url('placement/masterfile/excel_all_employees'); ?>" class="btn btn-success float-right mr-2">Generate Excel</a>
        </div>
      </div>
    </div>
    <br>
    <br>

    <div class="table-wrapper">
      <table id="datatable" class="table table-white table-bordered mg-b-0 tx-12">
        <thead>
          <tr>
            <!-- <th class="wd-5p">No</th> -->
            <th class="wd-10p">Name</th>
            <th class="wd-5p">Business Unit</th>
            <th class="wd-5p">Department</th>
            <th class="wd-5p">Position</th>
            <th class="wd-5p">Emptype</th>
            <th class="wd-5p">Startdate</th>
            <th class="wd-5p">Eocdate</th>
            <th class="wd-5p">Status</th>
          </tr>
        </thead>
      </table>
    </div><!-- table-wrapper -->

  </div>