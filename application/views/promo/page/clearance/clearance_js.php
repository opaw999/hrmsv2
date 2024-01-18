<script>
    $(document).ready(function() {


        $('div.clearance').hide();
        var dataTable = $("#clearance").DataTable({
            "destroy": true,
            "ajax": {
                url: "<?php echo site_url('promo/clearanceList'); ?>",
                type: "POST",
                beforeSend: function() {
                    loader_dots('clearance_list');
                },
            },
            "order": [],
            "columnDefs": [{
                    "targets": [2, 3, 4, 5, 6],
                    "orderable": false,

                },
                {
                    "targets": 7,
                    "className": "text-center",
                    "orderable": false,
                }
            ],
            "initComplete": function(settings, json) {
                $('div.clearance').show();
                $('.clearance_list').html('');
                $('th.name').css('width', '25%');
            },


        });

        $("form#reprintClearance").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var fields = [];
            for (let entry of formData.entries()) {

                fields.push(entry[0]);
            }
            console.log(fields)
            var fieldsComplete = true;
            var errMessage = false;
            for (var i = 0; i < fields.length; i++) {

                var field = fields[i];
                var value = $("textarea[name='" + field + "'],input[name='" + field + "'],select[name='" + field + "']").val().trim();

                if (value === '') {
                    if (!errMessage) {
                        alertRequired();
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("textarea[name='" + field + "'], input[name='" + field + "'],select[name='" + field + "']").css("border-color", "#dd4b39");
                } else {
                    $("textarea[name='" + field + "'], input[name='" + field + "'],select[name='" + field + "']").css("border-color", "");
                }

                $("textarea[name='" + field + "'], input[name='" + field + "'],select[name='" + field + "']").on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
            if (fieldsComplete) {

                Swal.fire({
                    title: 'Confirm!',
                    text: 'Reprint clearance?',
                    icon: 'question',
                    iconColor: '#ffc107',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    customClass: 'required_alert'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "<?= site_url('promo/reprintClearance') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {

                                    showAlert();
                                    var alertCounter = 0;

                                    function showAlert() {
                                        Swal.fire({
                                            title: 'Reprint Request Successfull!',
                                            text: "You can now print the Clearance.",
                                            icon: 'success',
                                            iconColor: '#15ca20',
                                            showCancelButton: (alertCounter > 0) ? true : false,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: (alertCounter > 0) ? 'Print Again' : 'Print',
                                            cancelButtonText: 'Close',
                                            customClass: 'required_alert',
                                            allowOutsideClick: false
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                console.log(response.data)
                                                print_clearance(response.data)
                                                alertCounter++;
                                                showAlert();
                                            } else {
                                                location.reload();
                                            }
                                        });
                                    }
                                } else {
                                    alert(data);
                                }
                            },
                            async: false,
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }
                })
            }
        });

        $("form#secureClearance").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var fields = [];
            $("select[name='reason']:disabled, input[name='resignDate']:disabled").each(function() {
                formData.append($(this).attr('name'), $(this).val());
            });

            for (let entry of formData.entries()) {

                fields.push(entry[0]);

            }
            console.log(fields)
            var fieldsComplete = true;
            var errMessage = false;
            for (var i = 0; i < fields.length; i++) {

                var field = fields[i];
                var value = $("input[name='" + field + "'],select[name='" + field + "']").val().trim();

                if (value === '') {
                    if (!errMessage) {
                        alertRequired();
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("input[name='" + field + "'], select[name='" + field + "']").css("border-color", "#dd4b39");
                } else {
                    $("input[name='" + field + "'], select[name='" + field + "']").css("border-color", "");
                }

                $("input[name='" + field + "'], select[name='" + field + "']").on("focus", function() {
                    $(this).css("border-color", "");
                });

            }
            if (fieldsComplete) {

                Swal.fire({
                    title: 'Confirm!',
                    icon: 'question',
                    iconColor: '#ffc107',
                    text: 'Secure clearance?',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    customClass: 'required_alert'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "<?= site_url('promo/secureClearance') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {

                                    showAlert();
                                    var alertCounter = 0;

                                    function showAlert() {
                                        Swal.fire({
                                            title: 'Secure Clearance Successfull!',
                                            text: "You can now print the Clearance.",
                                            icon: 'success',
                                            iconColor: '#15ca20',
                                            showCancelButton: (alertCounter > 0) ? true : false,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: (alertCounter > 0) ? 'Print Again' : 'Print',
                                            cancelButtonText: 'Close',
                                            customClass: 'required_alert',
                                            allowOutsideClick: false
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                console.log(response.data)
                                                print_clearance(response.data)
                                                alertCounter++;
                                                showAlert();
                                            } else {
                                                location.reload();
                                            }
                                        });
                                    }
                                } else {
                                    alert(data);
                                }
                            },
                            async: false,
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }
                })
            }
        });

        $("form#uploadClearance").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var fields = [];

            for (let entry of formData.entries()) {

                fields.push(entry[0]);

            }
            console.log(fields)
            var fieldsComplete = true;
            var errMessage = false;
            for (var i = 0; i < fields.length; i++) {

                var field = fields[i];
                var value = $("input[name='" + field + "'],select[name='" + field + "'],textarea[name='" + field + "']").val().trim();

                if (value === '') {
                    if (!errMessage) {
                        alertRequired();
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("input[name='" + field + "'], select[name='" + field + "'],textarea[name='" + field + "']").css("border-color", "#dd4b39");
                } else {
                    $("input[name='" + field + "'], select[name='" + field + "'],textarea[name='" + field + "']").css("border-color", "");
                }

                $("input[name='" + field + "'], select[name='" + field + "'],textarea[name='" + field + "']").on("focus", function() {
                    $(this).css("border-color", "");
                });

            }
            if (fieldsComplete) {

                Swal.fire({
                    title: 'Confirm!',
                    icon: 'question',
                    iconColor: '#ffc107',
                    text: 'Upload clearance?',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    customClass: 'required_alert'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "<?= site_url('promo/uploadClearance') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Clearance upload successful!',
                                        icon: 'success',
                                        iconColor: '#15ca20',
                                        confirmButtonText: 'Ok',
                                        confirmButtonColor: '#0d6efd',
                                        customClass: 'required_alert'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            location.reload();
                                        }
                                    });
                                } else {
                                    alert(data);
                                }
                            },
                            async: false,
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }
                })
            }
        });
    });

    function clearanceDetails(input) {

        var res = input.split('*')
        var scpr_id = res[1]
        var reason = res[0]
        console.log(scpr_id)
        $('div#clearanceDetails').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#clearanceDetails").modal("show");
        $.ajax({
            url: "<?= site_url('promo/clearanceDetails') ?>",
            type: 'POST',
            data: {
                scpr_id: scpr_id,
                reason: reason
            },

            success: function(data) {
                $("div.clearanceDetails").html(data);
            }
        });
    }

    function view_letter(letter) {
        $('div#showLetter').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#showLetter").modal("show");
        var link = "<?= base_url('../hrms/promo/') ?>" + letter
        console.log(link)
        $("div.showLetter").html('<img src="' + link + '" class="d-block w-100" />');
    }

    function print_clearance(input) {
        var res = input.split('*');
        var [reason, emp_id, scdetails_id] = input.split('*');
        var link = "<?= base_url('../hrms/report/promo_clearance.php?empid=') ?>" + emp_id + "&scprdetailsid=" + scdetails_id
        console.log(link)
        if (reason == 'Deceased') {
            window.open(link, 'new');
        } else {
            window.open(link, 'new');
        }
    }

    function nameSearch(str) {
        var process = $("input[name='clearanceProcess']").val();
        if (str.trim() != '') {
            $("select[name='reason']").val('').prop('disabled', false);
            $("select[name='stores'],select[name='scdetails_id']").html('<option value="">Select Store</option>');
            $("input[name='promo_type'],input[name='stat'],input[name='reason'],textarea[name='reasonReprint']").val('');
            $('div.resignation, i.resignation').remove();
            $('div.deceased, i.deceased').remove();
            $('div.termination, i.termination').remove();
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/nameSearch'); ?>",
                data: {
                    str,
                    process
                },
                success: function(data) {
                    $(".dropdown-list").show()
                    $(".dropdown-list").html(data);
                }
            });
        } else(
            $(".dropdown-list").hide()
        )
        $("input[name='name']").on('search', function() {
            $("select[name='reason']").val('').prop('disabled', false);
            $("select[name='stores'],select[name='scdetails_id']").html('<option value="">Select Store</option>');
            $("input[name='promo_type'],input[name='stat'],input[name='reason'],textarea[name='reasonReprint']").val('');
            $('div.resignation, i.resignation').remove();
            $('div.deceased, i.deceased').remove();
            $('div.termination, i.termination').remove();
            $(".dropdown-list").hide()
        });
    }

    function getName(input) {

        var process = $("input[name='clearanceProcess']").val();
        var [name, emp_id, record_no] = input.split('*');
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/getName_clearance'); ?>",
            data: {
                emp_id,
                process
            },
            success: function(data) {
                let response = JSON.parse(data);
                if (response.message == 'success') {

                    if (process == 'reprint') {

                        $("select[name='scdetails_id']").html(response.stores);
                        $("input[name='reason']").val(response.reason);
                        $("input[name='promo_type']").val(response.promo_type);
                        $("input[name='name']").val(name);
                        $("input[name='emp_id']").val(emp_id);
                        $(".dropdown-list").hide()
                    } else if (process == 'secure') {

                        if (response.secured) {
                            if (response.chk) {
                                Swal.fire({
                                    title: 'Secured!',
                                    text: 'Already secured clearance!',
                                    icon: 'warning',
                                    iconColor: '#fd3550',
                                    confirmButtonText: 'Ok',
                                    confirmButtonColor: '#0d6efd',
                                    customClass: 'required_alert',
                                    allowOutsideClick: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location = "<?= base_url('promo/page/clearance/secureClearance') ?>";
                                    }
                                });

                            } else {
                                $("select[name='reason']").val(response.reason).prop('disabled', true);
                                $("select[name='stores']").html(response.stores);
                                reasonRequirement(response.reason)

                                if (response.reason == 'Termination') {
                                    $("input[name='resignDate']").prop('disabled', true);
                                }
                            }
                        } else {
                            $("select[name='reason']").val('').prop('disabled', false);
                            $("select[name='stores']").html(response.stores)
                            $("input[name='resignDate']").prop('disabled', false);
                            $('div.resignation, i.resignation').remove();
                            $('div.deceased, i.deceased').remove();
                            $('div.termination, i.termination').remove();
                        }

                        $("input[name='resignDate']").val(response.reason === 'Termination' ? response.eocdate : '');
                        $("input[name='eocdate']").val(response.eocdate);
                        $("input[name='promo_type']").val(response.promo_type);
                        $("input[name='name']").val(name);
                        $("input[name='emp_id']").val(emp_id);
                        $("input[name='record_no']").val(record_no);
                        $(".dropdown-list").hide()
                    } else {
                        $("input[name='stat']").val(response.stat);
                        $("select[name='stores']").html(response.stores)
                        $("input[name='promo_type']").val(response.promo_type);
                        $("input[name='scpr_id']").val(response.scpr_id);
                        $("input[name='name']").val(name);
                        $("input[name='emp_id']").val(emp_id);
                        $("input[name='record_no']").val(record_no);
                        $(".dropdown-list").hide()
                    }

                } else {
                    alert(data);
                }
            }
        });
    }

    function reasonRequirement(reason) {
        var resignDate = $("input[name='eocdate']").val();

        if (reason == 'Termination') {
            $('div.termination, i.termination').remove();
            var htmlContent =
                '<i class="text-danger termination">Additional requirements for ' + reason + ':</i>' +
                '<div class="col-md-6 termination">' +
                '<label class="form-label">EOC Date</label>' +
                '<input type="text" class="form-control datepicker" name="resignDate" value="' + resignDate + '" placeholder="yyyy-mm-dd">' +
                '</div>' +
                '<div class="col-md-6 requirement"></div>';

            var $resignationDiv = $("div.requirement");
            $resignationDiv.replaceWith(function() {
                return $(htmlContent);
            });
            $('div.resignation, i.resignation').remove();
            $('div.deceased, i.deceased').remove();
        } else if (reason == 'Deceased') {

            var htmlContent =
                '<i class="text-danger deceased">Additional requirements for ' + reason + ':</i>' +
                '<div class="col-md-6 deceased">' +
                '<label class="form-label">Date of Death</label>' +
                '<input type="text" class="form-control datepicker" name="resignDate" placeholder="yyyy-mm-dd">' +
                '</div>' +
                '<div class="col-md-6 deceased">' +
                '<label class="form-label">Cause of Death</label>' +
                '<input type="text" class="form-control" name="deathCause">' +
                '</div>' +
                '<div class="col-md-6 deceased">' +
                '<label class="form-label">Name of Claimant</label>' +
                '<input type="text" class="form-control" name="claimant">' +
                '</div>' +
                '<div class="col-md-6 deceased">' +
                '<label class="form-label">Relationship to the Deceased</label>' +
                '<select class="form-select" name="relationship">' +
                '<option value="">Select Relationship</option>' +
                '<option value="Father">Father</option>' +
                '<option value="Mother">Mother</option>' +
                '<option value="Spouse">Spouse</option>' +
                '<option value="Son">Son</option>' +
                '<option value="Daughter">Daughter</option>' +
                '<option value="Sister/Brother">Sister/Brother</option>' +
                '</select>' +
                '</div>' +
                '<div class="col-md-6 deceased">' +
                '<label class="form-label">Death Certificate</label>' +
                '<input type="file" class="form-control" name="resignLetter">' +
                '</div>' +
                '<div class="col-md-6 deceased">' +
                '<label class="form-label">Authorization Letter</label>' +
                '<input type="file" class="form-control" name="authLetter">' +
                '</div>' +
                '<div class="col-md-6 requirement"></div>';

            var $resignationDiv = $("div.requirement");

            $resignationDiv.replaceWith(function() {
                return $(htmlContent);
            });
            $('div.resignation, i.resignation').remove();
            $('div.termination, i.termination').remove();
        } else {

            $('div.resignation, i.resignation').remove();
            var htmlContent =
                '<i class="text-danger resignation">Additional requirements for ' + reason + ':</i>' +
                '<div class="col-md-6 resignation">' +
                '<label class="form-label">Date of Resignation</label>' +
                '<input type="text" class="form-control datepicker" name="resignDate" placeholder="yyyy-mm-dd">' +
                '</div>' +
                '<div class="col-md-6 resignation">' +
                '<label class="form-label">Resignation Letter</label>' +
                '<input type="file" class="form-control" name="resignLetter">' +
                '</div>' +
                '<div class="col-md-6 requirement"></div>';

            var $resignationDiv = $("div.requirement");

            $resignationDiv.replaceWith(function() {
                return $(htmlContent);
            });
            $('div.deceased, i.deceased').remove();
            $('div.termination, i.termination').remove();
        }

        $(".datepicker").flatpickr();
    }

    function browseEpas(stores) {

        var emp_id = $("input[name='emp_id']").val();
        var record_no = $("input[name='record_no']").val();
        $("input[name='epas']").val('');
        if (stores != '') {

            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/browseEpas'); ?>",
                data: {
                    stores: stores,
                    emp_id: emp_id,
                    record_no: record_no,
                },
                success: function(data) {

                    let response = JSON.parse(data);
                    if (response.message == 'success') {

                        $("input[name='epas']").val(response.numrate);
                    } else {

                        Swal.fire({
                            title: response.alert === 'noepas' ? 'No EPAS!' : 'Sign Off!',
                            text: response.text,
                            icon: 'warning',
                            iconColor: '#ffc107',
                            confirmButtonText: 'Ok',
                            confirmButtonColor: '#0d6efd',
                            customClass: 'required_alert'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("select[name='stores']").val('')
                            }
                        });

                    }
                }
            });
        }
    }

    function loader_dots(page) {
        $('div.' + page).html(
            '<div style="text-align: center;">' +
            '<img src="<?= base_url('assets/promo_assets/images/giphy1.gif') ?>" style="width: 50%;">' +
            '</div>'
        );
    }

    function loader_circle(page) {
        $('div.' + page).html(
            '<div style="text-align: center;">' +
            '<img src="<?= base_url('assets/promo_assets/images/circle.gif') ?>">' +
            '</div>'
        );
    }

    function alertRequired() {
        Swal.fire({
            title: 'Required!',
            text: 'Please fillup the required fields!',
            icon: 'warning',
            iconColor: '#fd3550',
            confirmButtonText: 'Ok',
            confirmButtonColor: '#0d6efd',
            customClass: 'required_alert'
        });
    }
</script>