 <div class="am-pagebody">
    <div class="card pd-0 pd-sm-30">
          <div class="text-right mb-2" >
            <select class="form-control  wd-150 tx-16" name="perlevel" id="perlevel" onchange="filterlevelingperlevel(this.value)">
            <option value=''>Filter By Level</option>
            <?php 
            $lvlno = $this->db->query("SELECT poslevel FROM employee3 GROUP BY poslevel")->result_array();

            foreach ($lvlno as $comp):
                echo "<option value='{$comp['poslevel']}'>LEVEL {$comp['poslevel']}</option>";
            endforeach;
            ?>
            </select>
			<button
                class="btn btn-success btn-sm"
                href= "#"
                modal-size="modal-sm" 
                modal-route="placement/masterfile/insert_new_position_leveling"
                modal-dtapi=""
                modal-form="placement/masterfile/position_leveling_add_position"
                modal-skeleton="0"
                modal-id=""
                modal-atype="POST"
                modal-title="POSITION LEVEL" txt-13
                onclick="modal(event)"> Add Position
            </button>
           	<a type="button" class="btn btn-success btn-sm" href="<?= base_url('placement/masterfile/excel_position_level'); ?>">
						    <span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Export Positions
            </a>
      </div>
      <!-- <div class="col-md-6">
        <h6 class="card-body-title" style="font-size: 20px"></h6>
      </div> -->
    <br>
    <br>
    <div class="table-wrapper">
      <table id="datatable" class="table table-white table-bordered mg-b-0 tx-12">
        <thead>
          <tr>
            <!-- <th class="wd-5p">No</th> -->
            <th class="wd-10p">LEVEL</th>
            <th class="wd-5p">LVLNO</th>
            <th class="wd-5p">POSITION TITLE</th>
            <th class="wd-5p">COUNT</th>
            <th class="wd-5p">USED BY</th>
            <th class="wd-5p">TYPE</th>
            <th class="wd-5p">CATEGORY</th>
            <th class="wd-5p">ACTION</th>
          </tr>
        </thead>
      </table>
    </div><!-- table-wrapper -->
</div>
</div>

 <input type="hidden" name="redirect" id="redirect">

