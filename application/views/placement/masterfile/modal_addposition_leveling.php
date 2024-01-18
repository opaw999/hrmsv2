
						<div class='form-group'>
							<label> Position </label>	
							<input type='text' required name='position' id='position' class='form-control' required="" >		
					    </div>
					    <div class='form-group'>
							<label> Level </label>	
							<?php $level = array("0","I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII","XIII","XIV","XV"); ?>	
							<select class="form-control" name='level' required>
								<option> </option>
								<?php for($i=0;$i<count($level);$i++){
									echo "<option value='$level[$i]'> $level[$i] </option>";
								} ?>
							</select>								
					    </div>
					    <div class='form-group'>
							<label> Level No </label>	
							<?php $level = array("0","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15"); ?>	
							<select class="form-control" name='lvlno' required>
								<option> </option>
								<?php for($i=0;$i<count($level);$i++){
									echo "<option value='$level[$i]'> $level[$i] </option>";
								} ?>
							</select>			
					    </div>
	        