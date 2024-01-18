
      <div class="text-right mb-2" >
						<a type="button" href="<?= base_url('placement/masterfile/xls_per_licensed/'.$lno); ?>" class="btn btn-success btn-sm">
							<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Generate
						</a>	
      </div>
    <div class="table-wrapper">
      <input type="hidden" name="lno" value ='<?=$lno?>'>
      
      <table id="modal-datatable" class="table table-bordered mg-b-0 tx-12">
        <thead>
          <tr>
            <th>HRMSID</th>
            <th>Eligibility Name</th>
            <th>POSITION</th>
            <th>BU</th>
            <th>DEPT</th>
          </tr>
        </thead>
      </table>
    </div><!-- table-wrapper -->