 <div class="row row-sm">
        <div class="col-lg-12">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label class="form-control-label">Name: </label>
                            <input type="text" disabled='' class="form-control tx-12" value="<?= ucwords(strtolower($name)) ?>" >
                            <input type="hidden" name= "appid" value="<?= $appid?>">
                        </div><!-- form-group -->  
                    </div><!-- col -->
               </div>
               <div class="row">
               <div class="col">
                        <div class="form-group">
                            <label class="form-control-label"> Reason: </label>
                            <textarea class="form-control" tx-12" name = "reason" ><?= $reason ?></textarea>
                        </div><!-- form-group -->  
                </div><!-- col -->
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label class="form-control-label"> Date Blacklisted:</label>
                            <input type="date"  class="form-control tx-12"  name ="dateblacklisted" value="<?= $dateblacklisted ?>" data-inputmask='"mask": "99-9999999-9"' data-mask>
                        </div><!-- form-group -->  
                    </div><!-- col -->
                    <div class="col">
                        <div class="form-group">
                            <label class="form-control-label"> Reported By:  </label>
                            <input type="text"  class="form-control tx-12" name ="reportedby" value="<?= ucwords(strtolower($reportedby)) ?>" data-inputmask='"mask": "99-999999999-9"' data-mask>
                        </div><!-- form-group -->  
                    </div><!-- col -->
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label class="form-control-label"> Birthday: </label>
                            <input type="date" class="form-control tx-12 " name ="birthday" value="<?= $birthday ?>" data-inputmask='"mask": "9999-9999-9999"' data-mask>
                        </div><!-- form-group -->    
                    </div><!-- col -->
                    <div class="col">
                        <div class="form-group">
                            <label class="form-control-label"> Address: </label>
                            <input type="text"  class="form-control tx-12" name ="address" value="<?= $address ?>" data-inputmask='"mask": "9999-9999-9999"' data-mask>
                        </div><!-- form-group -->    
                    </div><!-- col -->
                </div><!-- row -->
    </div>