<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        $('.datepicker').flatpickr();
        $('.select2').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            tags: true,
        });
        $("#changeOutletHistory").DataTable({
            "destroy": true,
            "ajax": {
                url: "<?= site_url('promo/changeOutletHistory'); ?>",
                type: "POST"
            },
            "order": [],
            "columnDefs": [{
                "targets": [2, 3, 4],
                "orderable": false,

            }, {
                "targets": [0],
                "className": "text-center",
            }],
        });

        $("form#addOutletForm").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            var fields = [];
            let notRequired = []
            for (let entry of formData.entries()) {
                if (!notRequired.includes(entry[0])) {
                    fields.push(entry[0]);
                }
            }
            console.log(fields)
            var fieldsComplete = true;
            var errMessage = false;
            for (var i = 0; i < fields.length; i++) {

                var field = fields[i];
                var value = $("input[name='" + field + "']").val();

                if (value === '') {
                    if (!errMessage) {
                        alertRequired('Please fillup the required fields!');
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("input[name='" + field + "']").css("border-color", "#dd4b39");
                }

                $("input[name='" + field + "']").on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
            if (fieldsComplete) {

                var stores = [];
                var current_stores = [];
                $('input[name="stores[]"]:checked').each(function() {
                    if (!$(this).is(':disabled')) {
                        stores.push($(this).val());
                    } else {
                        current_stores.push($(this).val());
                    }
                });
                if (stores.length == 0) {

                    alertRequired('Please select a Store!');
                } else {
                    Swal.fire({
                        title: 'Confirm?',
                        text: 'Are you sure you want to Add Store(s)?',
                        icon: 'question',
                        iconColor: '#ffc107',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        customClass: 'required_alert'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            current_stores.forEach(function(store) {
                                formData.append('current_stores[]', store);
                            });
                            $('button.outletButton').prop('disabled', true)
                            $.ajax({
                                url: "<?= site_url('promo/changeOutlet') ?>",
                                type: 'POST',
                                data: formData,

                                success: function(data) {
                                    let response = JSON.parse(data);
                                    if (response.message == 'success') {
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'Add Outlet successful!',
                                            icon: 'success',
                                            iconColor: '#15ca20',
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'Ok',
                                            customClass: 'required_alert',
                                            allowOutsideClick: false,
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location = "<?= base_url('promo/page/outlet/addOutlet') ?>";
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
            }
        });

        $("form#save_uploadClearance").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var fields = [];
            let notRequired = []
            for (let entry of formData.entries()) {
                if (!notRequired.includes(entry[0])) {
                    fields.push(entry[0]);
                }
            }
            console.log(fields)
            var fieldsComplete = true;
            var errMessage = false;
            for (var i = 0; i < fields.length; i++) {

                var field = fields[i];
                var value = $("input[name='" + field + "']").val();

                if (value === '') {
                    if (!errMessage) {
                        alertRequired('Please fillup the required fields!');
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("input[name='" + field + "']").css("border-color", "#dd4b39");
                }

                $("input[name='" + field + "']").on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
            if (fieldsComplete) {
                $('input[name="stores[]"]:checked').each(function() {
                    formData.append('stores[]', $(this).val());
                });
                $('input[name="transfer[]"]:checked').each(function() {
                    formData.append('transfer[]', $(this).val());
                });
                var process = $('input[name="process"]').val();
                var text = '';
                if (process == 'removeOutlet') {
                    text = 'Remove';
                } else {
                    text = 'Transfer';
                }
                Swal.fire({
                    title: 'Confirm?',
                    text: 'Are you sure you want to ' + text + ' Store(s)?',
                    icon: 'question',
                    iconColor: '#ffc107',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    customClass: 'required_alert'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("div#uploadClearance").modal("hide");
                        $('button.outletButton').prop('disabled', true)
                        $.ajax({
                            url: "<?= site_url('promo/changeOutlet') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {
                                    if (process == 'removeOutlet') {
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'Remove Outlet successful!',
                                            icon: 'success',
                                            iconColor: '#15ca20',
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'Ok',
                                            customClass: 'required_alert',
                                            allowOutsideClick: false,
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location = "<?= base_url('promo/page/outlet/removeOutlet') ?>";
                                            }
                                        });
                                    } else if (process == 'transferOutlet') {
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'Transfer Outlet successful!',
                                            icon: 'success',
                                            iconColor: '#15ca20',
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'Ok',
                                            customClass: 'required_alert',
                                            allowOutsideClick: false,
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location = "<?= base_url('promo/page/outlet/transferOutlet') ?>";
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
    });

    function getDuration(startdate) {
        var action = 'getDuration';
        var eocdate = $('input[name="eocdate"]').val();
        console.log(startdate, eocdate)
        if (startdate != '') {
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/getSelect'); ?>",
                data: {
                    eocdate,
                    action,
                    startdate,
                },
                success: function(data) {
                    let response = JSON.parse(data);
                    if (response.result == 'failed') {
                        Swal.fire({
                            title: 'Required!',
                            text: response.message,
                            icon: 'warning',
                            iconColor: '#fd3550',
                            confirmButtonText: 'Ok',
                            confirmButtonColor: '#0d6efd',
                            customClass: 'required_alert'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('input[name="startdate"]').val('');
                            }
                        });
                    } else {

                        $('input[name="duration"]').val(response.message);
                    }
                }
            });
        }
    }

    function searhPromo(str, process) {
        $('div.promoDetails').html('');
        $($('div.' + process + 'Form')).html('');
        if (str.trim() != '') {
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/nameSearch'); ?>",
                data: {
                    str,
                    process,
                },
                success: function(data) {
                    $('div.' + process).show();
                    $('div.' + process).html(data);
                }
            });
        } else {
            $('div.' + process).hide();
        }
    }

    function getPromoDetails(name, emp_id, process) {
        if (name.trim() != '') {
            $('input[name="' + process + '"]').val(name);
            $('div.' + process + 'Form').show();
            loader_dots(process + 'Form')
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/getPromoDetails'); ?>",
                data: {
                    emp_id,
                },
                success: function(data) {
                    $('div.promoDetails').html(data);
                    changeOutletForm(emp_id, process)
                }
            });
        }
        $('.' + process).hide()
    }

    function changeOutletForm(emp_id, formProcess) {
        var process = formProcess + 'Form';
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/changeOutletForm'); ?>",
            data: {
                emp_id,
                process
            },
            success: function(data) {
                $('div.' + process).html(data);
            }
        });
    }

    function viewAppraisal(details_id) {
        if (details_id != '') {
            $('div#viewAppraisal').modal({
                backdrop: 'static',
                keyboard: false,
            });
            $("div#viewAppraisal").modal("show");

            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/viewAppraisal'); ?>",
                data: {
                    details_id,
                },
                success: function(data) {

                    $('div.viewAppraisal').html(data);
                    $('button.print').prop('id', details_id)
                }
            });
        }
    }

    function printEpas(details_id) {
        console.log(details_id)
    }

    function outletClearance() {

        var stores = [];
        var transfer = [];
        var process = $('input[name="process"]').val();
        var emp_id = $('input[name="emp_id"]').val();
        var record_no = $('input[name="record_no"]').val();
        var previous_stores = $('input[name="previous_stores"]').val();
        var startdate = $('input[name="startdate"]').val();
        var count = $('input[name="storeCount"]').val();
        $('input[name="stores[]"]:checked').each(function() {
            stores.push($(this).val());
        });
        $('input[name="transfer[]"]:checked').each(function() {
            transfer.push($(this).val());
        });
        if (stores.length == 0) {
            if (process == 'removeOutlet') {
                alertRequired('Please select a Store!');
            } else {
                alertRequired('Please select a Store to Transfer From!');
            }
        } else {
            var complete = true;
            if (stores.length == count) {
                if (process == 'removeOutlet') {
                    alertRequired('You cannot remove all stores!');
                    $('input[name="stores[]"]:checked').each(function() {
                        $(this).prop('checked', false);
                        $(this).css('border-color', 'rgb(108 117 125)');
                    });
                    complete = false;
                }
            }
            if (process == 'transferOutlet') {
                if (transfer.length == 0) {
                    alertRequired('Please select a Store to Transfer To!');
                    complete = false;
                }
            }
            if (startdate == '') {
                alertRequired('Please fillup the required fields!');
                complete = false;
                $("input[name='startdate']").css("border-color", "#dd4b39");
                $("input[name='startdate']").on("focus", function() {
                    $(this).css("border-color", "");
                });
            }

            if (complete) {
                $('div#uploadClearance').modal({
                    backdrop: 'static',
                    keyboard: false,
                });
                $("div#uploadClearance").modal("show");
                $.ajax({
                    type: "POST",
                    url: "<?= site_url('promo/outletClearance'); ?>",
                    data: {
                        process,
                        stores,
                        transfer,
                        emp_id,
                        record_no,
                        previous_stores,
                        startdate,
                    },
                    success: function(data) {
                        $('div.uploadClearance').html(data);
                        getDuration(startdate)
                    }
                });
            }
        }
    }

    function getIntros() {
        var action = 'getIntros';
        var value = [];
        $('input[name="stores[]"]:checked').each(function() {
            if (!$(this).is(':disabled')) {
                value.push($(this).val());
            }
        });
        console.log(value)
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/getSelect'); ?>",
            data: {
                value,
                action,
            },
            success: function(data) {

                $('div.intros').html(data);
                $('div.intros .col-sm-3').removeClass('col-sm-3').addClass('col-sm-4');
            }
        });
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

    function alertRequired(message) {
        Swal.fire({
            title: 'Required!',
            text: message,
            icon: 'warning',
            iconColor: '#fd3550',
            confirmButtonText: 'Ok',
            confirmButtonColor: '#0d6efd',
            customClass: 'required_alert'
        });
    }
</script>