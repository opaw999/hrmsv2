
<!-- 
<style>
#animated-button {
  background-color: green; /* Button background color */
  color: #fff; /* Button text color */
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  font-size: 13px;
  transition: background-color 0.3s ease, transform 0.3s ease;
}

#animated-button:hover {
  background-color: #2c3e50; /* Button background color on hover */
  transform: scale(1.05); /* Scale the button on hover for animation */
}

</style>  -->
<div class="am-pagebody">
        <div class="row row-sm mg-t-0">
          <div class="col-xl-3">
            <div class="card pd-20 pd-sm-40 form-layout form-layout-4">
              <!-- <h6 class="card-body-title tx-20 text-center">ASSIGNED AT</h6> -->
              <p class="mg-b-20 mg-sm-b-30">
                   <input type="hidden" id="kra" name= "kra" value = "<?= $kc ?>">
                    <h4 class="tx-gray-900 tx-13 text-bold">COMPANY:</h4>
                    <i class="tx-gray-900 tx-12 text-success"><?= $company ?></i>
                     <h4 class="tx-gray-900 tx-13 text-bold">BUSINESS UNIT:</h4>
                    <i class="tx-gray-900 tx-12 text-success"><?= $bunit ?></i>
                     <h4 class="tx-gray-900 tx-13 text-bold">DEPARTMENT:</h4>
                    <i class="tx-gray-900 tx-12 text-success"><?= $dept ?></i>
                     <h4 class="tx-gray-900 tx-13 text-bold">SECTION:</h4>
                    <i class="tx-gray-900 tx-12 text-success"><?= $section ?></i>
                     <h4 class="tx-gray-900 tx-13 text-bold">SUB-SECTION:</h4>
                    <i class="tx-gray-900 tx-12 text-success"><?= $subsec ?></i>
                     <h4 class="tx-gray-900 tx-13 text-bold">UNIT:</h4>
                    <i class="tx-gray-900 tx-12 text-success "><?= $unit ?></i>
                    <hr class = "boarder boarder-primary">
                    <h4 class="tx-gray-900 tx-13 text-bold">ADDED BY:</h4>
                    <i class="tx-gray-900 tx-12 text-success "><?= $addedby->name ?></i>
                    <h4 class="tx-gray-900 tx-13 text-bold">DATE ADDED:</h4>
                    <i class="tx-gray-900 tx-12 text-success "><?= date('F ,d Y',strtotime($dateadded)) ?></i>
                    <h4 class="tx-gray-900 tx-13 text-bold">UPDATED BY:</h4>
                    <i class="tx-gray-900 tx-12 text-success "><?= $updatedby->name ?></i>
                    <h4 class="tx-gray-900 tx-13 text-bold">DATE UPDATED:</h4>
                    <i class="tx-gray-900 tx-12 text-success "><?=  date('F ,d Y',strtotime($dateupdated)) ?></i>
              </p>
            </div><!-- card -->
          </div><!-- col-6 -->
          <div class="col-xl-9 mg-t-25 mg-xl-t-0">
            <div class="card pd-20 pd-sm-40 form-layout form-layout-5">
              <h6 class="tx-gray-800 tx-uppercase tx-bold tx-24 mg-b-10 text-center">KEY RESPONSIBILITY AREA</h6>
              <p class="mg-b-30 tx-gray-600 tx-18 text-center text-success"><?= $position ?></p>
               <div class="row justify-content-end">
                    <div class="col-md-2"  style="margin-right: -56px;">
                        <button class="btn btn-xs btn-danger"  onclick ="back()" type="button">BACK</button>
                    </div>
                    <div class="col-md-2"  style="margin-right: -62px;">
                        <button class="btn btn-xs btn-warning"  onclick= "editkra()" type="button">EDIT</button>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-xs btn-primary"   onclick= "printkra()" type="button">PRINT</button>
                    </div>
                </div>
              <h4 class="tx-gray-800 tx-20 tx-bold">Job Summary</h4>
              <i class="tx-gray-800 tx-15 "><?= $summary ?></i>
              <br>
              <h4 class="tx-gray-800 tx-20 tx-bold">Job Description </h4>
              <i class="tx-gray-800 tx-13 "><?= $jobdesc ?></i>
            </div><!-- card -->
          </div><!-- col-6 -->
        </div><!-- row -->
    </div>
    <script>
      function back() 
      {
          window.location.href = '<?= base_url("placement/masterfile/kra"); ?>';
      }

      function editkra()
      {
        var nameInput = document.getElementById("kra");
        var kc = nameInput.value;
          var url = '<?= base_url("placement/masterfile/editkra/") ?>' + '?kc=' + kc;
          window.location.href = url;
      }

        function printkra() {
          var nameInput = document.getElementById("kra");
          var kc = nameInput.value;
          var url = '<?= base_url("placement/masterfile/msword_kra/") ?>' + '?kc=' + kc;
          window.open(url);
      }


    </script>