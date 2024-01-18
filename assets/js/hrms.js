var dtTable = "";

const ajax = async ( api, args, reqType, buttons ) => {
    let result;
    
    try {
        result = await $.ajax({
            url: url + api,
            type: reqType,
            data: args,
            enctype: 'multipart/form-data',
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend : function(){
                btnText = buttons.text();
                buttons.html(
                    `<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> 
                    ${ buttons.attr("button-message") }`
                    ).addClass('disabled');
                $('#button-cancel').addClass('disabled');
            },
            success: function(result){
                result = result;
                buttons.removeClass('disabled').html(btnText);
                $('#button-cancel').removeClass('disabled');
            },
            error : function(xhr, status, errorThrown){
                toast('error', errorThrown);
                buttons.removeClass("disabled").html(btnText);
            }
        });

        return result;
    } catch (error) {
        toast('error', error);
    }
}

const DTable = (api, serverSide = null) => {
    if(serverSide){
        var columns = [];
        $.ajax({
            url: url + api,
            success: function (data) {
                if( isJson(data) ){
                    data = JSON.parse(data);
                    columnNames = Object.keys(data.data[0]);
                    for (var i in columnNames) {
                        columns.push({data: columnNames[i],
                            title: cfl(columnNames[i])
                        });
                    }
                    
                    dtTable = $("#datatable").DataTable({
                        "processing": true,
                        "serverSide": true,
                        "responsive": true,
                        "bDestroy": true,
                        "language": {
                            searchPlaceholder: 'Search...',
                            sSearch: '',
                            lengthMenu: '_MENU_ items/page',
                        },
                        "ajax":{
                            "url": url + api,
                            "dataType": "json",
                            "type": "POST"
                        },
                        "columns": columns,
                        "columnDefs": [{
                            "targets": "_all",
                            "orderable": false
                        }],
                        "order": [[ 0, "asc" ]],
                        "pageLength": 10,
                        createdRow: function (row) {
                            $(row).addClass("dt-row pointer");
                        }
                    });
                    
                    // "initComplete": function () {
                    //     var api = this.api();
                    //     $('.dataTables_filter:first input').off('.DT').on('keyup.DT', function (e) {
                    //         if (e.keyCode == 13) {
                    //             api.search(this.value).draw();
                    //         }
                    //     });
                    // },
                    
                    // $('.documents tbody').on('dblclick', 'tr.dt-row', function (e) {
                    //     var pos = $(e.target).index()
                    //     var name = $(e.target).find("td:first-child span").attr('id');
                    //     // var name = $(e.target).closest("table").find("tr >td").eq(pos).html();
                    //     // alert($(e.target).text())
                    //     var values = $(e.target).parent().html().split('\n');
                    //     alert( name);
                    // });
                } else {
                    
                    toast('error', data);
                }
            }
        });
    } else {
        $('#datatable').DataTable({
            "responsive": true,
            "bDestroy": true,
            "language": {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ items/page',
            }
        });
        
    }
    setTimeout(function(){

        $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });
    },500)
    
}




const ModalDTable = (api, serverSide = null) => {
    console.log(api);
    if(serverSide){
        var columns = [];
        $.ajax({
            url: url + api,
            success: function (data) {
                if( isJson(data) ){
                    data = JSON.parse(data);
                    columnNames = Object.keys(data.data[0]);
                    for (var i in columnNames) {
                        columns.push({data: columnNames[i], 
                            title: cfl(columnNames[i])
                        });
                    }
                    
                    dtTable = $("#modal-datatable").DataTable({
                        "processing": true,
                        "serverSide": true,
                        "responsive": true,
                        "bDestroy": true,
                        "language": {
                            searchPlaceholder: 'Search...',
                            sSearch: '',
                            lengthMenu: '_MENU_ items/page',
                        },
                        "ajax":{
                            "url": url + api,
                            "dataType": "json",
                            "type": "POST"
                        },
                        "columns": columns,
                        "columnDefs": [{
                            "targets": "_all",
                            "orderable": false
                        }],
                        "order": [[ 0, "asc" ]],
                        "pageLength": 10,
                        createdRow: function (row) {
                            $(row).addClass("dt-row pointer");
                        }
                    });
                    
                    // "initComplete": function () {
                    //     var api = this.api();
                    //     $('.dataTables_filter:first input').off('.DT').on('keyup.DT', function (e) {
                    //         if (e.keyCode == 13) {
                    //             api.search(this.value).draw();
                    //         }
                    //     });
                    // },
                    
                    // $('.documents tbody').on('dblclick', 'tr.dt-row', function (e) {
                    //     var pos = $(e.target).index()
                    //     var name = $(e.target).find("td:first-child span").attr('id');
                    //     // var name = $(e.target).closest("table").find("tr >td").eq(pos).html();
                    //     // alert($(e.target).text())
                    //     var values = $(e.target).parent().html().split('\n');
                    //     alert( name);
                    // });
                } else {
                    
                    toast('error', data);
                }
            }
        });
    } else {
        $('#modal-datatable').DataTable({
            "responsive": true,
            "bDestroy": true,
            "language": {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ items/page',
            }
        });
        
    }
    setTimeout(function(){

        $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });
    },500)
    
}

const cfl = ( string ) => {

    const str = string;

    //split the above string into an array of strings 
    //whenever a blank space is encountered

    const arr = str.split(" ");

    //loop through each element of the array and capitalize the first letter.


    for (var i = 0; i < arr.length; i++) {
        arr[i] = arr[i].charAt(0).toUpperCase() + arr[i].slice(1);

    }

    //Join all the elements of the array back into a string 
    //using a blankspace as a separator 
    const str2 = arr.join(" ");
    
    return str2;
}

const toast = ( tType, message, duplicates = null ) => {
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": false,
        "positionClass": "toast-bottom-left",
        "preventDuplicates": (duplicates) ? true : false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    toastr[tType](message, toTitleCase(tType));
}

const swal = ( api, requestID, alertMsg ) => {
    Swal.fire({
        title:"Warning",
        html: alertMsg,
        icon: 'warning',
        showCancelButton:!0,
        confirmButtonColor:"#3085d6",
        cancelButtonColor:"#d33",
        confirmButtonText:"Yes",
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading()
    }).then(function(result){
        if (result.isConfirmed) {
            $.post(url + api,{"id":requestID },function(data){
                if( isJson(data) ){
                    var json = $.parseJSON(data);
                    switch (json.status) {
                    case 401:
                        tType = json.response; 
                        message = json.response_message;
                        break;
                    case 200:
                        tType = json.response;
                        message = json.response_message;
                        ( json.dtable ) ? dtTable.ajax.reload(null, false) : ''
                    default:
                        break;
                    }
                    toast(tType, message);
                } else {
                    console.log(data);
                }
            })
            .fail(function() {
                toast('error', 'Something went wrong!');
            });
        }        
    });
}

const certify = ( e ) => {
    let api         = e.currentTarget.getAttribute("data-swal-route");
    let id   = e.currentTarget.getAttribute("data-swal-id");
    let msg  = e.currentTarget.getAttribute("data-swal-message");

    swal( api, id, msg );
}

const toTitleCase = ( str ) => {
    return str.replace(
      /\w\S*/g,
      function(txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    }
    );
}

const showPassword = () => {
    let x = document.getElementById('password');
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}

const clearFields = ( form ) => {
 return $(form)[0].reset();
}

const formFocus = ( form ) => {
    return $(`#${form} input:text:visible:first`).focus();
}

const createUsername = () =>  {
    var fname = $('input[name=first_name]').val().substring(0,1).toLowerCase();
    
    var lname = $('input[name=last_name]').val().toLowerCase();
    var username = fname + lname;
    $('input[name=username]').val(username);
}

const handleFormSubmit = () => {
                  
    let formData = document.getElementById('form');
    let api = formData.getAttribute('api-url');
    let reqType = formData.getAttribute('api-type');
    let formButton = $('#form-username');
    let tType, message = "";
    if ( $('form').valid() === true ) {
        ajax(api, new FormData(formData), reqType, formButton)
        .then( (result) => {
            if( isJson(result) ){
                let json = $.parseJSON(result);
                switch (json.status) {
                case 401:
                    tType = json.response
                    message = json.response_message
                    clearFields(formData);
                    formFocus('form');
                    break;
                case 200:
                    tType = json.response
                    message = json.response_message
                    setTimeout(() => {
                        ( json.redirect ) ? window.location = url + json.redirect : ''
                    }, 1500);
                default:
                    break;
                }

                toast(tType, message);
            } else {
                toast('error', result);
            }
        });
    }
       
}






const validateForm = () => {
    if ( $('#form').length ) {
        $('#form').validate({
            errorClass: 'text-danger'
        });
    }
}



const skeleton = (i) => {
    let skeleton, skeletoContent = '';
    skeleton = '<div>';
    for(var a = 0; a<=i; a++){
        skeletoContent += `<div class="skeleton skeleton-text"></div><div class="skeleton skeleton-text skeleton-text__body"></div>`;
    }
    skeleton += skeletoContent;
    skeleton += '</div>';
    return skeleton;
}

const modal = async (e) =>  {
    let modalTitle = e.currentTarget.getAttribute('modal-title');
    let modalSize = e.currentTarget.getAttribute('modal-size');
    let modalRoute = e.currentTarget.getAttribute('modal-route');
    let modalSkeleton = e.currentTarget.getAttribute('modal-skeleton');
    let modalFormLoad = e.currentTarget.getAttribute('modal-form');
    let modalId = e.currentTarget.getAttribute('modal-id');
    let modalAType = e.currentTarget.getAttribute('modal-atype');
    let modalRedirect = e.currentTarget.getAttribute('modal-redirect');
    let modalBtn = e.currentTarget.getAttribute('modal-button');
    let modalformbtn = $("#form-button");
    let modalDtapi = e.currentTarget.getAttribute('modal-dtapi');
    (modalRedirect) ? modalformbtn.text("Submit"): modalformbtn.text("Save") ;
    (modalBtn == "false") ?  $("#form-button").hide() : $("#form-button").show() ;

    // console.log('here');
    
    $(".modal-title").html(modalTitle);
    $(".modal-body").html(skeleton(modalSkeleton));
    $(".modal-dialog").removeClass("modal-sm").removeClass("modal-lg").removeClass("modal-xl").addClass(modalSize);
    $("#form-modal").attr({ 'modal-route': modalRoute, 'modal-atype': modalAType });
    (modalTitle === "Filter Employees") ? $("#form-modal").attr('filter-employee',true) : '';
    $("#modal").modal('show');
    
    await $(".modal-body").load( url + modalFormLoad, { id: modalId }, function( responseTxt, statusTxt, xhr ) {
        
        if(statusTxt == "error"){
            toast('error', "Error: " + xhr.status + ": " + xhr.statusText);
            setTimeout(()=>{
                $("#modal").modal('hide');
            },500);
        } else {

            $("#form-modal").validate({
                errorClass: "text-danger"
            });

            if (modalFormLoad.includes('supervisor/subordinates/modalreason') == true) {
                var a = document.getElementsByName('cbname[]');
                var cbname = '';
                var x = 0;
                for(var i = 0;i<a.length;i++) {
                    if(a[i].checked == true) {
                        cbname += a[i].value+'*';
                        x++
                    }
                }

                if (x == 0) {
                    setTimeout(() => {
                        toast('error', "Please select atleat one subordinates in the list.", true);
                        $("#modal").modal('hide');
                    }, 500);
                    return false;
                } else {
                    $('#cbname').val(cbname);
                }
            }
            else if (modalFormLoad.includes('placement/masterfile/modal_eligibilities/') == true ||
                        modalFormLoad.includes('placement/masterfile/modal_position_leveling/') == true){ 
                setTimeout(() => {
                    ModalDTable(modalDtapi, 'true');
                }, 1000);
            }       

            
            if(responseTxt=="Session Expired!"){
                window.location.reload();
            }
        }
    });
}


const Redirectmodal = async (e) => {
  let modalTitle = e.currentTarget.getAttribute('modal-title');
  let modalSize = e.currentTarget.getAttribute('modal-size');
  let modalRoute = e.currentTarget.getAttribute('modal-route');
  let modalSkeleton = e.currentTarget.getAttribute('modal-skeleton');
  let modalFormLoad = e.currentTarget.getAttribute('modal-form');
  let modalId = e.currentTarget.getAttribute('modal-id');
  let modalAType = e.currentTarget.getAttribute('modal-atype');
  let modalBtn = e.currentTarget.getAttribute('modal-button');
  let modalformbtn = $("#form-button");
  
  
  // Set the text of the form button
  modalformbtn.text(modalBtn === "false" ? "Save" : "Submit");
  
  // Hide or show the form button based on modalBtn
  modalBtn === "false" ? $("#form-button").hide() : $("#form-button").show();
  
  $(".modal-title").html(modalTitle);
  $(".modal-body").html(skeleton(modalSkeleton));
  $(".modal-dialog").removeClass("modal-sm").removeClass("modal-lg").addClass(modalSize);
  $("#form-modal").attr({ 'modal-route': modalRoute, 'modal-atype': modalAType });
  modalTitle === "Filter Employees" ? $("#form-modal").attr('filter-employee', true) : '';
  $("#modal").modal('show');

  await $(".modal-body").load(url + modalFormLoad, { id: modalId }, function(responseTxt, statusTxt, xhr) {
    if (statusTxt == "error") {
      toast('error', "Error: " + xhr.status + ": " + xhr.statusText);
      setTimeout(() => {
        $("#modal").modal('hide');
      }, 500);
    } else {
      $("#form-modal").validate({
        errorClass: "text-danger"
      });

      setTimeout(() => {
        $("#form-modal input:text:visible:first").select().get(0).focus();
      }, 500);

      if (responseTxt == "Session Expired!") {
        window.location.reload();
      }
    }
  });

  // Add a click event handler to the form-button
  $("#form-button").click(function() {
      // Redirect to the specified modal-route URL
      let inputVal = $("#form-modal input[type='radio']:checked").val();
      
    window.location.href = modalRoute + '/' + inputVal;
  });
}



const validateModalForm = () => {
    let formData = document.getElementById('form-modal');
    let route = formData.getAttribute('modal-route');
    let reqType = formData.getAttribute('modal-atype');
    let formButton = $('#form-button');
   

    if ($('#form-modal').valid() === true) {
        if (formData.getAttribute('filter-employee') == "true") {
            let apiRedirect = $('#redirect').val();
            let code = "";
            let companyCode     = $('#company').val();
            let businessUnit    = ($('#businessunit').val()) ?  $('#businessunit').val() : '';
            let department      = ($('#department').val()) ?  $('#department').val() : '';
            let section         = ($('#section').val()) ?  $('#section').val() : '';
            let subsection = ($('#subsection').val()) ? $('#subsection').val() : '';
            let eType = $('#emptype option:selected').text();
            (companyCode) ? code = companyCode : "";
            (businessUnit)? code = businessUnit : "";
            (department) ?  code = department : "";
            (section) ?     code = section : "";
            (subsection) ?  code = subsection : "";
            let data = code;
            window.location = url + apiRedirect + data + '/' + eType;
        } else {
            ajax(route, new FormData(formData), reqType, formButton)
            .then( (result) => {
                if( isJson(result) ){
                    let json = $.parseJSON(result);
                    switch (json.status) {
                    case 401:
                        tType = json.response
                        message = json.response_message
                        formFocus('form-modal');
                        ( json.clear_text ) ? clearFields(formData) : ''
                        break;
                    case 200:
                        tType = json.response
                        message = json.response_message
                        clearFields(formData);
                        formFocus('form-modal');
                        ( json.modal_close ) ? dtTable.ajax.reload(null, false) : ''
                        setTimeout(() => {
                            ( json.redirect ) ? window.location = url + json.redirect : ''
                        }, 1500);
                        setTimeout(() => {
                            ( json.modal_close ) ? $('#modal').modal('hide') : ''
                        }, 500);
                    default:
                        break;
                    }
                    toast(tType, message);
                } else {
                    console.log(result);
                }
            });
        }
    }
}



const isJson = (str) => {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

const getLocation = async (id, container, api, d = null) => {
    let result;
    if (id) {
        try {
            result = await $.ajax({
                url: url + api,
                type: 'POST',
                data : { 'id': id },
                dataType: 'json',
                success : function(data){
                    let mhtml = "";
                    mhtml += `<option value=''>Select ${container}</option>`;
                    $.each(data.data, function(index, element){
                        if(element.id==""){
                            mhtml += `<option value="">No Record Found.</option>`;
                        }else{
                            mhtml += `
                            <option value="${element.id}">${element.location_name}</option>
                            `;
                        }
                    });
                    $("#" +container).html(mhtml);
                }
            });
            return result;
        } catch (error) {
            toast('error', error);
        }   
    } else {
        toast('error', 'Please select a '+ container);
    }
   
}

let activeNav = 'username'; // keep track of the currently active navigation link

  const nav = async (e, route) => {
    await $.ajax({
      url: url + route,
      type: 'POST',
      success: function (data) {
        $('#' + activeNav).removeClass('active'); // remove 'active' from previously active link
        $('#' + e).addClass('active'); // add 'active' to newly clicked link
        $("#nav-content").html(data);
        activeNav = e; // update currently active link
      }
    });
  };

  // Function to load the default view ('username') when the page loads
  const loadDefaultView = async () => {
    const route = 'employee/change_user'; // Replace this with the appropriate default route for 'username'
    await $.ajax({
      url: url + route,
      type: 'POST',
      success: function (data) {
        $('#' + activeNav).addClass('active'); // add 'active' to the default link ('username')
        $("#nav-content").html(data);
      }
    });
  };

  // Call the loadDefaultView function with a small delay to set the default view to 'username' when the page loads
  $(document).ready(function () {
    loadDefaultView(); // Adjust the delay (in milliseconds) if needed
  });


  let activeqbe = 'newemployee'; // keep track of the currently active navigation link

  const navreport = async (e, route) => {
    await $.ajax({
      url: url + route,
      type: 'POST',
      success: function (data) {
        $('#' + activeqbe).removeClass('active'); // remove 'active' from previously active link
        $('#' + e).addClass('active'); // add 'active' to newly clicked link
        $("#nav-report").html(data);
        activeqbe = e; // update currently active link
      }
    });
  };

  // Function to load the default view ('username') when the page loads
  const loadDefaultViewReport = async () => {
    const route = 'employee/qbe/newemployee'; // Replace this with the appropriate default route for 'username'
    await $.ajax({
      url: url + route,
      type: 'POST',
      success: function (data) {
        $('#' + activeqbe).addClass('active'); // add 'active' to the default link ('username')
        $("#nav-report").html(data);
      }
    });
};
  
// Call the loadDefaultView function with a small delay to set the default view to 'username' when the page loads
  $(document).ready(function () {
    loadDefaultViewReport(); // Adjust the delay (in milliseconds) if needed
  });


  let activeloyalty = 'loyalty_search'; // keep track of the currently active navigation link

  const navawardees = async (e, route, dtApi = null) => {
    await $.ajax({
      url: url + route,
      type: 'POST',
      success: function (data) {
        $('#' + activeloyalty).removeClass('active'); // remove 'active' from previously active link
        $('#' + e).addClass('active'); // add 'active' to newly clicked link
          $("#nav-loyalty").html(data);
          setTimeout(() => {
              DTable(dtApi, true);
          }, 1000);
        activeloyalty = e; // update currently active link
      }
    });
  };

  // Function to load the default view ('username') when the page loads
  const loadDefaultViewAwardees = async () => {
    const route = 'placement/masterfile/loyalty_search'; // Replace this with the appropriate default route for 'username'
    await $.ajax({
      url: url + route,
      type: 'POST',
      success: function (data) {
        $('#' + activeloyalty).addClass('active'); // add 'active' to the default link ('username')
        $("#nav-loyalty").html(data);
      }
    });
  };

  // Call the loadDefaultView function with a small delay to set the default view to 'username' when the page loads
  $(document).ready(function () {
    loadDefaultViewAwardees(); // Adjust the delay (in milliseconds) if needed
  });

  
  

(function($) {
    "use strict";
    ( DTApi ) ? DTable(DTApi, true) : DTable(DTApi);
})(jQuery);

(function($) {
    "use strict";
    ( DTApi ) ? ModalDTable(DTApi, true) : ModalDTable(DTApi);
})(jQuery);




function toggleTshirtDiv(show) {
    var tshirtDiv = document.getElementById('tshirt_div');
    if (show) {
        tshirtDiv.style.display = 'block';
    } else {
        tshirtDiv.style.display = 'none';
    }
}

function filterJobTransByDate(d) {
    if (d) DTable('masterfile/filter_per_year/' + d, true)
}

function filtersoloparent(d) {
    if (d) DTable('masterfile/filter_year_solo_parent/' + d, true)
}

function filterlevelingperlevel(d) {
    if (d) DTable('masterfile/filter_per_level/' + d, true)
}

function filterLoyaltyawardee(d) {
    if (d) DTable('placement/masterfile/loyalty_awardee/' + d, true)
}



 $(document).ready(function () {
            $("[data-mask]").inputmask();
        });
