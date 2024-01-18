<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- <form> -->
                <?php foreach ($appraisal_types as $type): ?>
                    <div class="form-check ml-5"> <!-- Adjust margin-bottom and margin-left -->
                        <input class="form-check-input" type="radio" name="appraisaltype" id="type_<?php echo $type['code']; ?>" value="<?php echo $type['code']; ?>" required>
                        <label class="form-check-label" for="type_<?php echo $type['code']; ?>">
                            <?php echo $type['appraisal']; ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            <!-- </form> -->
        </div>
    </div>
</div>
 <!-- <input type="hidden" name="redirect" id="redirect"> -->
