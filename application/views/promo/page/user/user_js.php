<script>
    $(document).ready(function() {
        $('.form-check-input:not(:checked)').css('border-color', 'rgb(108 117 125)');
        $('.form-check-input').change(function() {
            if (!$(this).is(':checked')) {
                $(this).css('border-color', 'rgb(108 117 125)');
            } else {
                $(this).css('border-color', '');
            }
        });
        $('.datepicker').flatpickr();
        $('.select2').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            tags: true,
        });

        accessRolesTable()
        managePromoTable()
        manageInchargeTable()

        $("form#userAccountForm").submit(function(e) {
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
                Swal.fire({
                    title: 'Confirm?',
                    text: 'Are you sure you want to Add New User Account?',
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
                            url: "<?= site_url('promo/save_userAccount') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {
                                    if (response.checkUser) {
                                        Swal.fire({
                                            title: 'Error!',
                                            text: 'Username not available!',
                                            icon: 'error',
                                            iconColor: '#fd3550',
                                            confirmButtonText: 'Ok',
                                            confirmButtonColor: '#0d6efd',
                                            customClass: 'required_alert'
                                        });
                                        $('input[name="username"]').css('border-color', '#dd4b39');
                                        $('input[name="username"]').prop('readonly', false);
                                    } else {
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'New User Account Added Successfully!',
                                            icon: 'success',
                                            iconColor: '#15ca20',
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'Ok',
                                            customClass: 'required_alert',
                                            allowOutsideClick: false,
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location = "<?= base_url('promo/page/user/addNewUser') ?>";
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

        $("form#inchargeAccountForm").submit(function(e) {
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
                var value = $("input[name='" + field + "'],select[name='" + field + "']").val();

                if (value === '') {
                    if (!errMessage) {
                        alertRequired('Please fillup the required fields!');
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("input[name='" + field + "'],select[name='" + field + "']").css("border-color", "#dd4b39");
                }

                $("input[name='" + field + "'],select[name='" + field + "']").on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
            if (fieldsComplete) {
                Swal.fire({
                    title: 'Confirm?',
                    text: 'Are you sure you want to Add Promo Incharge Account?',
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
                            url: "<?= site_url('promo/addPromoInchargeAccount') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'New Promo Incharge Account Added Successfully!',
                                        icon: 'success',
                                        iconColor: '#15ca20',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok',
                                        customClass: 'required_alert',
                                        allowOutsideClick: false,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location = "<?= base_url('promo/page/user/addPromoIncharge') ?>";
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

    function updateUserAccount(process) {

        var emp_id = $('input[name="emp_id"]').val();
        var new1 = $('input[name="' + process + '1"]').val();
        var new2 = $('input[name="' + process + '2"]').val();
        var password = $('input[name="password"]').val();
        var username = $('input[name="username"]').val();

        if (process == 'username') {
            $('input[name="password"],input[name="password1"],input[name="password2"]').val('');
            $('input[name="password"],input[name="password1"],input[name="password2"]').css("border-color", "");
        } else {
            $('input[name="username1"],input[name="username2"]').val('');
            $('input[name="username1"],input[name="username2"]').css("border-color", "");
        }

        if (emp_id == '' || new1.trim() == '' || new2.trim() == '' || (process == 'password' && password.trim() == '')) {
            alertRequired('Please fillup the required fields!');
            if (new1.trim() == '') {
                $('input[name="' + process + '1"]').css("border-color", "#dd4b39");
            }
            if (new2.trim() == '') {
                $('input[name="' + process + '2"]').css("border-color", "#dd4b39");
            }
            $('input[name="' + process + '1"],input[name="' + process + '2"]').on("focus", function() {
                $(this).css("border-color", "");
            });
            if (process == 'password' && password.trim() == '') {
                $('input[name="password"]').css("border-color", "#dd4b39");
                $('input[name="password"]').on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
        } else {

            Swal.fire({
                title: 'Confirm?',
                text: 'Are you sure you want to update your ' + process + '?',
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
                        type: "POST",
                        url: "<?= site_url('promo/updateUserAccount'); ?>",
                        data: {
                            process,
                            emp_id,
                            password,
                            username,
                            new1,
                            new2,
                        },
                        success: function(data) {
                            let response = JSON.parse(data);
                            if (response.message == 'success') {
                                if (response.report == '') {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Update successful.' +
                                            'The page will automatically logout.' +
                                            'Please login with your new ' + process + '. Thank You!',
                                        icon: 'success',
                                        iconColor: '#15ca20',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok',
                                        customClass: 'required_alert',
                                        allowOutsideClick: false,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location = "<?= base_url('promo/logout') ?>";
                                        }
                                    });
                                } else if (response.report == 'exist' || response.report == 'mismatch') {
                                    alertRequired(response.report == 'exist' ? 'Username already exists!' : 'Your ' + process + ' does not match!');

                                    $('input[name="' + process + '1"], input[name="' + process + '2"]').css("border-color", "#dd4b39");
                                    $('input[name="' + process + '1"], input[name="' + process + '2"]').on("focus", function() {
                                        $(this).css("border-color", "");
                                    });
                                } else if (response.report == 'oldPasswordError') {
                                    alertRequired('Your current password does not match!')
                                    $('input[name="password"]').css("border-color", "#dd4b39");
                                    $('input[name="password"]').on("focus", function() {
                                        $(this).css("border-color", "");
                                    });
                                }
                            } else {
                                alert(data);
                            }
                        }
                    });
                }
            })
        }
    }

    function searhPromo(str, process) {
        $('input[name="username"]').val('').css('border-color', '');
        $('input[name="password"]').val('').attr('type', 'password');
        $('input[name="password"]').prop('readonly', false);
        $('input[name="viewPassword"],input[name="setPassword"]').prop('checked', false);
        $('.form-check-input:not(:checked)').css('border-color', 'rgb(108 117 125)');
        $('input[name="emp_id"],select[name="usertype"] ').val('');
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

    function getPromoDetails(name, emp_id, process, exist) {
        if (name.trim() != '') {
            if (!exist) {
                $('input[name="' + process + '"]').val(name);
                $('input[name="emp_id"]').val(emp_id);
                $('input[name="username"]').val(emp_id);
            } else {
                Swal.fire({
                    title: 'Exist!',
                    text: 'Employee have an existing Account!',
                    icon: 'error',
                    iconColor: '#fd3550',
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#0d6efd',
                    customClass: 'required_alert'
                });
                $('input[name="' + process + '"]').val('');
            }
        }
        $('.' + process).hide()
    }

    function passwordFunc(action) {
        console.log(action)
        if (action == 'viewPassword') {
            if ($('input[name="' + action + '"]').prop('checked')) {
                $('input[name="password"]').attr('type', 'text');
                $('input[name="password1"]').attr('type', 'text');
                $('input[name="password2"]').attr('type', 'text');
            } else {
                $('input[name="password"]').attr('type', 'password');
                $('input[name="password1"]').attr('type', 'password');
                $('input[name="password2"]').attr('type', 'password');
            }
        } else {
            if ($('input[name="' + action + '"]').prop('checked')) {
                $('input[name="password"]').val('Hrms2014');
                $('input[name="password"]').prop('readonly', true)
                $('input[name="password"]').addClass('readonly')
                $('input[name="password"]').css('border-color', '');
            } else {
                $('input[name="password"]').val('');
                $('input[name="password"]').prop('readonly', false)
                $('input[name="password"]').removeClass('readonly')
            }
        }
    }

    function updatePromoUserAccessRoles(name, value) {
        if (name != '' && value != '') {
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/accessRoles'); ?>",
                data: {
                    name,
                    value,
                },
                success: function(data) {
                    let response = JSON.parse(data);
                    if (response.message == 'success') {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Promo User Access Updated!',
                            icon: 'success',
                            iconColor: '#15ca20',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok',
                            customClass: 'required_alert',
                            allowOutsideClick: false,
                        })
                    } else {
                        alert(data);
                    }
                }
            });
        }
    }

    function accessRolesTable() {
        var dataTable = $("#promoUserAccessRoles").DataTable({
            "destroy": true,
            "ajax": {
                url: "<?= site_url('promo/promoUserAccessRoles'); ?>",
                type: "POST"
            },
            "order": [],
            "columnDefs": [{
                    "targets": [1],
                    "orderable": false,
                },
                {
                    "targets": [2, 3, 4],
                    "className": "text-center",
                    "orderable": false,
                }
            ],
        });
    }

    function userAccount(process, id) {
        if (process != '', id != '') {
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/userAccount'); ?>",
                data: {
                    process,
                    id,
                },
                success: function(data) {
                    let response = JSON.parse(data);
                    if (response.message == 'success') {
                        Swal.fire({
                            title: 'Success!',
                            text: response.report,
                            icon: 'success',
                            iconColor: '#15ca20',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok',
                            customClass: 'required_alert',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                managePromoTable()
                            }
                        });
                        // Swal.fire({
                        //     title: 'Success!',
                        //     text: response.report,
                        //     icon: 'success',
                        //     iconColor: '#15ca20',
                        //     showCancelButton: false,
                        //     confirmButtonText: 'Ok',
                        //     showConfirmButton: true,
                        //     customClass: 'required_alert',
                        //     allowOutsideClick: false,
                        //     didOpen: () => {
                        //         Swal.getConfirmButton().innerHTML = '<div class="spinner-border text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>';
                        //         managePromoTable().then(() => {
                        //             Swal.close();
                        //         });
                        //     }
                        // });

                    } else {
                        alert(data);
                    }
                }
            });
        }

    }

    function managePromoTable() {
        var dataTable = $("#managePromoUserAccounts").DataTable({
            "destroy": true,
            "ajax": {
                url: "<?= site_url('promo/managePromoUserAccounts'); ?>",
                type: "POST"
            },
            "order": [],
            "columnDefs": [{
                    "targets": [1, 2],
                    "orderable": false,
                },
                {
                    "targets": [3, 4, 5],
                    "className": "text-center",
                    "orderable": false,
                }
            ],
        });
    }

    function updatePromoInchargeAccounts(name, value) {
        if (name != '' && value != '') {
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/updatePromoInchargeAccounts'); ?>",
                data: {
                    name,
                    value,
                },
                success: function(data) {
                    let response = JSON.parse(data);
                    if (response.message == 'success') {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Promo Incharge Account Updated!',
                            icon: 'success',
                            iconColor: '#15ca20',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok',
                            customClass: 'required_alert',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                manageInchargeTable()
                            }
                        });
                    } else {
                        alert(data);
                    }
                }
            });
        }
    }

    function manageInchargeTable() {
        var dataTable = $("#managePromoInchargeAccounts").DataTable({
            "destroy": true,
            "ajax": {
                url: "<?= site_url('promo/managePromoInchargeAccounts'); ?>",
                type: "POST"
            },
            "order": [],
            "columnDefs": [{
                    "targets": [0, 4, 5],
                    "orderable": false,
                },
                {
                    "targets": [2, 3],
                    "className": "text-center",
                    "orderable": false,
                }
            ],
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