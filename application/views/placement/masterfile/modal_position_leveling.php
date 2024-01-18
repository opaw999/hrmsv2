
      <div class="text-right mb-2" >
							<a type="button" class="btn btn-success btn-sm" href="<?= base_url('placement/masterfile/excel_position_level_per_pos/'.urldecode($pos).'');?>">
						    <span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Generate
            </a>	
      </div>
    <div class="table-wrapper">
      <!-- <input type="hidden" name='pos' value ="<?= $pos ?>"> -->
      <table id="modal-datatable" class="table table-bordered mg-b-0 tx-12">
        <thead>
          <tr>
            <th>EMPID</th>
            <th>NAME</th>
            <th>POSITION</th>
            <th>LVL</th>
            <th>EMPTYPE</th>
            <th>DATEHIRED</th>
            <th>BUSINESS UNIT</th>
            <th>DEPARTMENT</th>
            <th>SECTION</th>
          </tr>
        </thead>
      </table>
    </div><!-- table-wrapper -->