
<?php
if(@$dg == "" || @$dg == "0000-00-00"){
	$dates = '';
}
else{
	$dates = date('m/d/Y',strtotime($dg));		
}
?>

<form method="get" action="" target='' class="container mt-4">
    <div class="form-group">
        <b>Name:</b>
        <input type='text' class="form-control" value='<?= $name ?>'>
        <input type="hidden" class="form-control" name='empid' value='<?php echo $empId; ?>'>
        <input type="hidden" name='rec' id='rec' value='<?= $recordno ?>'>
        <input type="hidden" name='etype' id='etype' value="<?= $emptype ?>">
    </div>

    <div class="form-group">
        <b>Witness </b>
        <div class="row">
            <div class="col-md-6">
                <input type='text' class="form-control" style="text-transform:uppercase;" value='<?php echo isset($w1) ? $w1 : ''; ?>' id='w1' name='w1' required placeholder="Witness 1">
            </div>
            <div class="col-md-6">
                <input type='text' class="form-control" style="text-transform:uppercase;" value='<?php echo isset($w2) ? $w2 : ''; ?>' id='w2' name='w2' required placeholder="Witness 2">
            </div>
        </div>
    </div>

    <div class="form-group">
        <b>Please choose Contract Header</b>
        <div class="row">
            <div class="col-md-12">
                <select class='form-control' id='contractheader' name='ccode' required>
                    <option></option>
                    <?php
                    $query = $this->db->query("SELECT * FROM contract_header order by company");
                    foreach ($query->result_array() as $r) {
                        if ($chno == $r['ccode_no']) {
                            echo "<option value='" . $r['ccode_no'] . "' selected>" . $r['company'] . " ----- " . $r['address'] . "</option>";
                        } else {
                            echo "<option value='" . $r['ccode_no'] . "'>" . $r['company'] . " ----- " . $r['address'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group">
        <b>Please choose either to use Cedula (CTC No.) or SSS No.</b>
        <div class="row">
            <div class="col-md-6">
                <div class="form-check">
                    <input type='radio' class="form-check-input" name='clear' id='r1' value='Cedula' onclick="sssctc('ctc')" style="margin-left: 2px;" required>
                    <label class="form-check-label" for="r1">Cedula (CTC No.)</label>
                    <input type='text' class="form-control" name='cleartf' id='cleartf' value="<?php if (@$sss_ctc == "Cedula") { echo @$sno_cno;} ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-check">
                    <input type='radio' class="form-check-input" name='clear' id='r2' value='SSS' onclick="sssctc('sss')" style=" margin-left: 2px;" required>
                    <label class="form-check-label" for="r2">SSS No.</label>
                    <input type='text' class="form-control" name='ssstf' id='ssstf' value="<?= @$sssnum; ?>">
                </div>
            </div>
        </div>
        <p id='is'>Issued on: <input type='text' class="form-control" name='issuedon' id='issuedon' size='50' value="<?= @$issuedon ?>"></p>
        <p>Issued at:&nbsp;<input type='date' class="form-control" name='issuedat' id='issuedat' size='50' value="<?= @$issuedat ?>"> </p>
    </div>

    <div class="form-group">
        <b>Date of Signing the Contract/Employee</b>
        <div class="row">
            <div class="col-md-12">
                <input type='date' class="form-control" name='cdate' id='contractdate' value='<?= $dates ?>'>
            </div>
        </div>
    </div>
</form>
<script>
	function sssctc(val) {
		if (val == 'ctc') {
			$('#ssstf').hide();
			$('#cleartf').show();
			$('#issuedon').show();
			$('#is').show();
		} else if (val == 'sss') {
			$('#ssstf').show();
			$('#cleartf').hide();
			$('#issuedon').hide();
			$('#is').hide();
		}
	}

	function contract(rec, code, etype) {
		document.getElementById('rec').value = rec;
		document.getElementById('etype').value = etype;
	}
	$(document).ready(function() {
		//$('#ssstf').hide();
		//$('#cleartf').hide();
		$('#issuedon').hide();
		$('#is').hide();
	});
</script>
