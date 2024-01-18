<form class="form-group" method="POST">
        <div class=""> 
                <input type="hidden" name="elno" id="elno" value="<?= $lno ?>">
        <p><label>Name:</label>
            <input type="text" autocomplete="off" class="form-control" name="updatename" id="updatename" minlength="3" value="<?= $eligb_name?>" required="">
        </p>
        <p><label>Display:</label>
            <input type="text" autocomplete="off" class="form-control" name="updatedisplay" id="updatedisplay" value="<?= $eligb_display?>" required="">
        </p>
        <hr>
        </div>
</form>