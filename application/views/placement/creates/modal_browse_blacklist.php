<div class="modal-body" id="browsenames"> 
    <p class="tx-gray-800">1) You are advised to search the last name first to find out if the one being searched is blacklisted.</p>
    <p  class="tx-gray-800">2) If no results found, that indicates that the one being searched is not an applicant nor an employee.</p>
    <form class="form-inline">
        <input type="text" class="form-control-sm mb-2 mr-sm-2" id="lname" placeholder="Last Name">
        <input type="text" class="form-control-sm mb-2 mr-sm-2" id="fname" placeholder="First Name">
        
        <button type="button" class="btn btn-primary btn-sm mb-2" onclick="browsenames()">Search</button>
    </form>
    <!--<button class='btn btn-primary btn-sm' id='choosebtn' style='display:none;'>Choose to blacklist</button>-->
</div>

<div id='nonemp' style='display:none;'>	
    <hr>
    <p style='color:red'>No results found. Kindly fill up the textbox below to blacklist non-applicant or non-employee.</p>
    <form class="form-inline">
        <div class="form-group">
             <input type="text" class="form-control-sm mb-2 mr-sm-2" id="lasname" placeholder="Last Name"> 
        </div>
        <div class="form-group">
             <input type="text" class="form-control-sm mb-2 mr-sm-2" id="firsname" placeholder="First Name">
        </div>
        <div class="form-group">
             <input type="text" class="form-control-sm mb-2 mr-sm-2" id="middlename" placeholder="Middle Name">
        </div>
                <button class='btn btn-primary btn-sm mb-2' id='choosebtn' onclick='choosetobl()'>Choose to Blacklist</button>
    </form>
</div>
<div id='resultbrowse'></div>

<script>

    function browsenames()
    {	
        var ln = document.getElementById('lname').value;
        var fn = document.getElementById('fname').value;
        if(ln == '' && fn == ''){
            alert('Please indicate either the employee lastname or firstname to be searched')
        }
        else{		
            $("#sub-list").html('<img src="../images/system/10.gif" id="loading-gif" style="position:absolute; margin-left:400px;margin-top:30px;">');	
                
            $.ajax({
                type: "POST",
                url: "<?= base_url('placement/creates/browse_blacklist');?>",
                data: { ln:ln, fn:fn },
                success: function(data)
                {	
                    // alert(data);
                    data = data.trim();				
                    if(data == ''){
                        $('#nonemp').show();				
                    }else{
                        $('#nonemp').hide();
                    }
                    $('#resultbrowse').html(data);
                    $("#loading-gif").hide();
                }
            });		
        }	
    }

    function enableds(){

	document.getElementById('reason').disabled = false;
	document.getElementById('datebls').disabled = false;
	document.getElementById('reportedby').disabled = false;
	document.getElementById('bdays').disabled = false;
	document.getElementById('addr').disabled = false;
	document.getElementById('submit').disabled = false;
	document.getElementById('reset').disabled = false;	
    
    }

    function choosetobl()
        {
            var ln = document.getElementById('lasname').value;
            var fn = document.getElementById('firsname').value;
            var mn = document.getElementById('middlename').value;	
            
            if(ln =='' || fn == ''){ 
                alert('Please fill up the required fields'); 
                var m = ln+", "+fn+" "+mn;		
            }
            else{	
                var flag =0;
                if(ln != '' && fn != '')
                {
                    var m = ln+", "+fn+" "+mn;	
                    flag = 1;		
                }
                else if(mn == ''){ 
                    alert('Middlename is not required but input if there is any.')
                    var m = ln+", "+fn;
                    flag = 1;			
                }
                
                if(flag == 1)
                {
                    var r = confirm('Are you sure to Blacklist '+ m + " ?")
                    if(r == true){
                        $.ajax({
                            type: "POST",
                            url: "<?= base_url('placement/creates/notapplicant_blacklist');?>",
                            data: { ln:ln,fn:fn },
                            success: function(data){					
                                if(data == 1){
                                    alert(fn+" "+ln +" is already blacklisted! Please input another one.")
                                    document.getElementById('lasname').value = '';
                                    document.getElementById('firsname').value = '';
                                    document.getElementById('middlename').value = '';
                                }
                                else{
                                    alert('Click close and supply other details.')
                                    document.getElementById('namesearch').value = m;
                                    enableds();
                                }
                            }
                        });	
                    }	
                }	
            }	
        }


</script>
