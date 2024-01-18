
<!-- <div class="am-pagebody"  -->
    <div class="row row-sm">
        <div class="col-lg-12">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label class="form-control-label"> NAME: </label>
                            <input type="text" disabled="" class="form-control tx-12" value="<?= $name ?>" >
                            <input type="hidden" name= "app_id" value="<?= $appid?>">
                        </div><!-- form-group -->  
                    </div><!-- col -->
               </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label class="form-control-label"> SSS NO </label>
                            <input type="text"  class="form-control tx-12"  name ="ssno" value="<?= $sssno ?>" data-inputmask='"mask": "99-9999999-9"' data-mask>
                        </div><!-- form-group -->  
                    </div><!-- col -->
                    <div class="col">
                        <div class="form-group">
                            <label class="form-control-label"> PHILHEALTH </label>
                            <input type="text"  class="form-control tx-12" name ="philhealth"  value="<?= $philhealth ?>" data-inputmask='"mask": "99-999999999-9"' data-mask>
                        </div><!-- form-group -->  
                    </div><!-- col -->
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label class="form-control-label"> PAGIBIG RTN </label>
                            <input type="text" class="form-control tx-12" name ="pagibigrtn" value="<?= $pagibigrtn ?>" data-inputmask='"mask": "9999-9999-9999"' data-mask>
                        </div><!-- form-group -->    
                    </div><!-- col -->
                    <div class="col">
                        <div class="form-group">
                            <label class="form-control-label"> PAGIBIG NO </label>
                            <input type="text"  class="form-control tx-12" name ="pagibigno" value="<?= $pagibigno ?>" data-inputmask='"mask": "9999-9999-9999"' data-mask>
                        </div><!-- form-group -->    
                    </div><!-- col -->
                </div><!-- row -->
                 <div>
                        <div class="form-group">
                            <label class="form-control-label"> TIN NO </label>
                            <input type="text"  class="form-control tx-12" name ="tinno" value="<?= $tinno ?>" data-inputmask='"mask": "999-999-999-999"' data-mask>
                        </div><!-- form-group -->    
                </div><!-- col -->
    </div>
<!-- </div> -->
<script>
    
$("[data-mask]").inputmask();

</script>