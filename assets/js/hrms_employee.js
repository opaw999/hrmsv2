//route: http://172.16.161.100/hrmsv2/supervisor/epas/resignation/grade/empid

//forgot password
function forgot_pass(){
    toast('info', 'Call the nearest HRD Office around you. Thank you!');  
}

//USED ONLY IN EPAS
function compute_appraisal() 
{
    let total       = 0;
    let numrates    = 0;
    let desrate     = "";
   
    for (i=1; i<=10; i++)
    {
        var rate = parseFloat( $("#rate"+i).val() );       
        if(!isNaN(rate))
        {      
            total += rate;
            numrates = total.toFixed(2);
            desrate = getRate(numrates);

            $("#numrates").val(numrates);            
            $("#desrate").val(desrate);
        }        
    }
}

//SALARY INCREASE EPAS
function computeAppraisal(empid) 
{
    let total       = 0;
    let numrates    = 0;
    let desrate     = "";
   
    for (i=1; i<=10; i++)
    {
        var rate = parseFloat( $("#rate"+i+empid).val() );       
        if(!isNaN(rate))
        {      
            total += rate;
            numrates = total.toFixed(2);
            desrate = getRate(numrates);

            $("#numrates"+empid).val(numrates);            
            $("#desrate"+empid).val(desrate);
        }        
    }
}

function getRate(numrates)
{
    if (numrates == 100) {
        desrate = "E";
    } else if (numrates >= 90 && numrates <= 99.9) {
        desrate = "VS";
    } else if (numrates >= 85 && numrates <= 89.9) {
        desrate = "S";
    } else if (numrates >= 70 && numrates <= 84.9) {
        desrate = "U";
    } else if (numrates >= 0 && numrates <= 69.9) {
        desrate = "VU";
    }
    return desrate;
}

//USED ONLY IN EPAS
function signoff(){
  
    let rc = $("#ratercomment").val(); 
    if(rc == ""){
        alert("Please leave your comment first in the COMMENTS FROM THE RATER field before clicking Rater's Sign off.")        
    }else{ 
        alert('You have just click sign off. Please click submit button for final saving. Thank you!')
        $("#raterSO").val('1');    
        $("#ratercomment").prop('disabled',true);  
    }
}

//USED IN SUPERVISOR EPAS
$("li[id^=tab-menu]").click(function()
{
	let api = "supervisor/epas/load_eoc/" + $(this).attr("data-api"); // from epas_eoc.php -> data-api  

    $("li[id^=tab-menu]").removeClass("active");
	$(this).addClass("active");

    DTable(api, true);
});


// function confirms()
// {
//     Swal.fire({
//         title: 'Do you want to save the changes?',
//         showDenyButton: true,
//         showCancelButton: true,
//         confirmButtonText: 'Save',
//         denyButtonText: `Don't save`,
//     }).then((result) => {
//         /* Read more about isConfirmed, isDenied below */
//         if (result.isConfirmed) {
//           Swal.fire('Saved!', '', 'success')
//         } else if (result.isDenied) {
//           Swal.fire('Changes are not saved', '', 'info')
//         }
//     })   
// }

//USED IN PAYROLL
//functions that updates the payroll no and pcc code in payroll access
$("[name^='pid']").keypress(function(evt) {
    var id = this.id;			
    var pid = this.value;	
    if(pid == ''){	
        pid = '';
    }
    
    if(evt.which == 13)
    {	
        var plen = pid.length;
        if(plen == '' || pid == '000000' || plen <6 || plen > 6)
        {             
            toast('error', 'Please input a VALID Payroll Number!');           
            $("[name^='pid'][id^='"+id+"']").val("");
        }        
        else if(plen !=''){		
            Swal.fire({
                title: 'Do you want to save the changes?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Save Now!'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        type:"POST",
                        url: url + "employee/payroll/check_duplicate",
                        data:{ id : id, pid : pid },
                        success:function(data)
                        {
                            if(data == 1)
                            {                            
                                $.ajax({
                                    type:"POST",
                                    url: url + "employee/payroll/updatepid",
                                    data:{ id : id, pid : pid },
                                    success:function(data){	

                                        if(data == "true")
                                        {
                                            var opt1 = pid.slice(0, 2);
                                            var opt2 = pid.slice(0, 3);

                                            $("[name^='pid'][id^='"+id+"']").removeClass('loading');
                                            $("[name^='pid'][id^='"+id+"']").addClass('ok');

                                            $("#form-modal").attr({ 'modal-route': 'employee/payroll/save_charging_company', 'modal-atype': 'POST' });

                                            $('#button-cancel').hide(); // hide close button
                                            $('.close').hide();         // hide x close button
                                            $('#modal').modal('show');
                                            $(".modal-title").html("Update Cost Center and Charging");
                                            $(".modal-body").html(
                                                "<input type='hidden' name='empid' value='"+id+"'>" +
                                                "<b>Please select <label class='text-danger'>Cost Center Code and Charging Company</label> for this employee.</b> " +
                                                "<table width='100%'> "+
                                                "<tr> <td> Cost Center </td> <td>"+
                                                "<label> <input type='radio' value='"+ opt1 +"' id='' required name='pcc'> &nbsp; "+ opt1 +" </label> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;"+
                                                "<label> <input type='radio' value='"+ opt2 +"' id='' required name='pcc'> "+ opt2 +" </label> </td> </tr>"+
                                                "<tr> <td> Charging </td> "+   
                                                "<td> <select class='form-control' name='company' required> "+
                                                    "<option value=''> - Please select company - </option>"+
                                                    "<option value='1'> Alturas Supermarket Corporation</option>"+
                                                    "<option value='2'> Marcela Farms Incorporated</option>"+
                                                    "<option value='3'> Leonardo Distributors Incorporated</option>"+
                                                    "<option value='4'> Island City Mall</option>"+
                                                    "<option value='5'> Alturas - Talibon Branch</option>"+
                                                    "<option value='6'> Panglao Bay Premiere Parks & Resorts</option>"+
                                                    "<option value='7'> Rose En Honey Foodline Incorporated</option>"+
                                                    "<option value='8'> ASC Tech</option>"+
                                                    "<option value='9'> Bucarez</option>"+
                                                    "<option value='10'> Crust & Pepper</option>"+
                                                    "<option value='11'> Charcoal & Chop Foodline Inc.</option>"+
                                                    "<option value='12'> Kompas Resorts & Hotels Inc.</option>"+
                                                    "<option value='13'> NETMAN Distributors Inc.</option>"+
                                                    "<option value='14'> ROAST & TOAST FOODLINE INC.</option>"+
                                                "</select> </td> </tr> </table>" +
                                                "<br><p> <b> Note: Please double check before clicking SAVE button. </b></p>"                              
                                            );                    

                                        }else{
                                            $("[name^='pid'][id^='"+id+"']").removeClass('loading');
                                            $("[name^='pid'][id^='"+id+"']").addClass('notok');						
                                        }
                                    }
                                });	
                            }else{
                                toast('error', 'Payroll Number is already taken! Input another Payroll No!'); 
                                $("[name^='pid'][id^='"+id+"']").val("");
                            }
                        }
                    });
                }
            })  
        }				
    }	
});   

//USED IN SALARY INCREASE
$(document).ready(function(){
    //CHECK ALL CHECK BOXES IN STEP1
    $("#chkAll").click(function(){
        $(".chk").not(":disabled").prop("checked",$("#chkAll").prop("checked"));
    }) 						
    
    //CLICK DONE IN STEP1
    $("#doneMisconduct").click(function()
    {      
        const empids = $('input[name="cbname[]"]:checked').map(function() {
            return $(this).val();
        }).get();
        
        if(empids.length != 0)
        {        
            Swal.fire({
                title: 'Do you want to save the changes?',
                icon: 'info',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Save',
                denyButtonText: `Don't save`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        type :"POST",
                        url  : url + "employee/si/tagdonestep",
                        data :{ empids:empids, step_stat: "step1_stat"  },
                        success:function(data)
                        {		
                            if(data == '1')	{
                                toast('success', 'Step 1 Done !');   
                                setTimeout(function(){
                                    window.location = url + 'employee/sistep/1';
                                },1000); 
                            } else {
                                toast('error', 'There is an error in saving!');   
                            }
                        }
                    });	
                } else if (result.isDenied) {
                    toast('info', 'Changes are not saved!');
                }
            })  
        }else{
            toast('error', 'Please select atleast 1 employee!'); 
        }       
    });

    //CLICK DONE IN STEP2
    $("#doneStep2").click(function()
    {      
        const empids = $('input[name="cbname[]"]:checked').map(function() {
            return $(this).val();
        }).get();

        if(empids.length != 0)
        { 
            Swal.fire({
                title: 'Do you want to save the changes?',
                icon: 'info',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Save',
                denyButtonText: `Don't save`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        type :"POST",
                        url  : url + "employee/si/tagdonestep",
                        data :{ empids:empids, step_stat: "step2_stat" },
                        success:function(data)
                        {		
                            if(data == '1')	{
                                toast('success', 'Step 2 Done !');   
                                setTimeout(function(){
                                    window.location = url + 'employee/sistep/2';
                                },1000); 
                            } else {
                                toast('error', 'There is an error in saving!');   
                            }
                        }
                    });	
                } else if (result.isDenied) {
                    toast('info', 'Changes are not saved!');
                }
            })  
        }else{
            toast('error','Please select atleast 1 subordinate!');
        }
    });

    //CLICK CHECKBOX AND RATE
    $("#rate_step2").click(function(){
        const empids = $('input[name="cbname[]"]:checked').map(function() {
            return $(this).val();
        }).get();

        if(empids.length != 0 && empids.length <= 3){         
            window.location = url+'employee/si/rate/method?id='+empids;
        }else if(empids.length == 0){        
            toast('error','Please select atleast 1 subordinate!');
        }else{
            toast('error','You can only select at most 3 subordinates at a time!');
        }
    });
    
    // USED IN SUBORDINATES
    $("#export-sub").click(function()
    {     
        Swal.fire({
            title: 'Do you want to export now?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Yes',
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                window.location = url+"supervisor/report/subordinates";
            } 
        })  
    });
    
    $("#back-btn").click(function()
    {
        Swal.fire({
            title: 'Note that any changes made will not be save when you hit Yes?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Yes',
        }).then((result) => {            
            if (result.isConfirmed) {
                window.location = url+"employee/sistep/2";
            } 
        })  
    });
});

//LOAD IMAGES
var batchSize = 20; // Number of images to load per batch
var startIndex = 0; // Starting index for images

function loadImages(){

    $("#table-container").hide();
    $("#load-more-div").show();
    $("#loading").show();

    $.ajax({
        url: url + 'supervisor/subimages',
        method: 'GET',
        data: { startIndex: startIndex, batchSize: batchSize },
        success: function(response) {
            $('#image-container').append(response);            
            startIndex += batchSize;

            // Hide the "Load More" button if no more images are available
            if (response.trim() === '') {
                $('#load-more-button').hide();
            }
        }
    });
   
}
$('#load-more-button').click(function() {
    loadImages();
});

function save_onEnter(evt,$emp_id,cat,obj)
{
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if(charCode == 13 || charCode == 9){
        save_misconduct($emp_id, cat);
    }
    char_code_check(charCode, obj);
}

function save_onTab(evt,$emp_id,cat,obj)
{
    var charCode = (evt.which) ? evt.which : evt.keyCode

    save_misconduct($emp_id,cat);
    char_code_check(charCode,obj);
}

function save_misconduct($emp_id,cat)
{
    var id = $emp_id;
    var val = $("[name^='"+cat+"'][id^='"+id+"']").val();
    $("[name^='"+cat+"'][id^='"+id+"']").removeClass('ok');
    $("[name^='"+cat+"'][id^='"+id+"']").addClass('loading');
    
    //console.log(id,val,cat);
    $.ajax({
        type : "POST",
        url  : url+ "employee/si/savestep1",
        data : { id:id, val:val, cat:cat },
        success:function(data)
        {	            
            $("[name^='"+cat+"'][id^='"+id+"']").removeClass('loading');
            if(data == 1){
                $("[name^='"+cat+"'][id^='"+id+"']").addClass('ok');
            }else{                
                $("[name^='"+cat+"'][id^='"+id+"']").addClass('notok');						
            }			
        }
    });	
}

function char_code_check(charCode,obj)
{
    var value = obj.value;
    var dotcontains = value.indexOf(".") != -1;
    if (dotcontains)
        if (charCode == 46) return false;
    if (charCode == 46) return true;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
//END USED IN SALARY INCREASE

const modals = async (e) =>  {
    let modalTitle = e.currentTarget.getAttribute('modal-title');
    let modalSize = e.currentTarget.getAttribute('modal-size');
    let modalSkeleton = e.currentTarget.getAttribute('modal-skeleton');
    let modalFormLoad = e.currentTarget.getAttribute('modal-form');
    let modalId = e.currentTarget.getAttribute('modal-id');
    
    $("#appraisalModal .modal-title").html(modalTitle);
    $("#appraisalModal .modal-body").html(skeleton(modalSkeleton));
   // $("#appraisalModal .modal-dialog").removeClass("modal-sm").removeClass("modal-lg").addClass(modalSize);
    $("#appraisalModal .modal-dialog").addClass("modal-sm");
    
    $("#appraisalModal").modal('show');

     
    await $("#appraisalModal .modal-body").load( url + modalFormLoad, { id: modalId }, function( responseTxt, statusTxt, xhr ) {
        
        if(statusTxt == "error"){
            toast('error', "Error: " + xhr.status + ": " + xhr.statusText);
            setTimeout(()=>{
                $("#appraisalModal").modal('hide');
            },500);
        } else {

            // if (modalRedirect.trim()) {
            //     $('#redirect').val(modalRedirect);
            // }
            
            if(responseTxt=="Session Expired!"){
                window.location.reload();
            }
        }
    });
}


// FOR JOBTRANS DELETE

function deleteJobTrans(transno, empid) {
			swal({
				title: 'Confirm Deletion',
				text: 'Confirm Deletion by clicking OK',
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#DD6B55',
				confirmButtonText: 'OK',
				cancelButtonText: 'Cancel',
				closeOnConfirm: false,
				closeOnCancel: true
			},
			function(isConfirmed){
				if (isConfirmed) {
					$.ajax({
						type: 'POST',
						url: url+ "placement/masterfile/delete_jobstransfer",
						data: { transno: transno, empid: empid },
						success: function (data) {
							if (data) {
								swal({
									title: 'Info',
									text: data,
									type: 'info',
									confirmButtonText: 'Ok'
								},
								function(){
									location.reload(); // Change this line as needed
								});
							}
						}
					});
				}
			});
		}
  

