
<style>
.animated-button {
  background-color: #3498db; /* Button background color */
  color: #fff; /* Button text color */
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  font-size: 13px;
  transition: background-color 0.3s ease, transform 0.3s ease;
}

.animated-button:hover {
  background-color: #2c3e50; /* Button background color on hover */
  transform: scale(1.05); /* Scale the button on hover for animation */
}

</style>
<div class="am-pagebody">

  <div class="card pd-20 pd-sm-30">

    <div class="row">
      <div class="col-md-6">
        <h6 class="card-body-title" style="font-size: 20px">KEY RESPONSIBILITY AREA</h6>
      </div>
      <div class="col-sm-6 col-md-6">
       <form>
          <button class="btn btn-xs btn-primary animated-button float-right" onclick="addkra()" type="button">
            <i class="ion ion-ios-plus-outline" style="font-size: 18px;"></i>  Add KRA
          </button>
        </form>
      </div>
    </div>
    <div class="table-wrapper"><br>
      <table id="datatable" class="table table-white table-bordered mg-b-0 tx-12">
        <thead>
          <tr>
            <!-- <th class="wd-5p">No</th> -->
            <th class="wd-10p">POSITION</th>
            <th class="wd-15p">BUSINESS UNIT</th>
            <th class="wd-10p">DEPARTMENT</th>
            <th class="wd-10p">SECTION</th>
            <th class="wd-10p">SUB-SECTION</th>
            <th class="wd-10p">UNIT</th>
            <th class="wd-5p">KRA</th>
          </tr>
        </thead>
      </table>
    </div><!-- table-wrapper -->

  </div>
<script>

  function addkra()
     {
         var url = '<?= base_url("placement/masterfile/add_kra") ?>';
         window.location.href = url;
     }
</script>