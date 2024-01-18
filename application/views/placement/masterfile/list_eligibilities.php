<div class="am-pagebody">
  <div class="card pd-20 pd-sm-30">
      <div class="col-md-6">
        <h6 class="card-body-title" style="font-size: 20px">Eligibilities</h6>
      </div>
      <div class="text-right" >
					<button
                        class="btn btn-success btn-sm"
                        href= "#"
                        modal-size=""
                        modal-route="placement/masterfile/insert_eligibilities"
                        modal-dtapi=""
                        modal-form="placement/masterfile/add_modal_eligibilities"
                        modal-skeleton="0"
                        modal-id=""
                        modal-atype="POST"
                        modal-title="Add Eligibility" 
                        onclick="modal(event)"><i class="icon ion-plus"></i> Add Eligibility
                        </button>
						<a type="button" class="btn btn-success btn-sm" href="<?= base_url('placement/masterfile/excel_eligibilities'); ?>">
						    <span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Generate All Licensed Holder
            </a>
      </div>
    <br>
    <br>
    <div class="table-wrapper">
      <table id="datatable" class="table table-white table-bordered mg-b-0 tx-12">
        <thead>
          <tr>
            <!-- <th class="wd-5p">No</th> -->
            <th>No</th>
            <th>Eligibility Name</th>
            <th>Display</th>
            <th class="wd-5p">Action</th>
          </tr>
        </thead>
      </table>
    </div><!-- table-wrapper -->

  </div>