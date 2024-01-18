<script>
    $(document).ready(function() {

        $('.select2').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
        });
        var dataTable = $("#masterfile").DataTable({
            "destroy": true,
            "ajax": {
                url: "<?= site_url('promo/masterfile'); ?>",
                type: "POST"
            },
            "order": [],
            "columnDefs": [{
                    "targets": [1, 2, 3, 4, 5],
                    "orderable": false,

                },
                {
                    "targets": 6,
                    "className": "text-center",
                    "orderable": false,
                }
            ],
        });

        var fileValue = '<?= $file; ?>';
        if (fileValue === 'profile') {
            profileData('basicInfo');
        } else if (fileValue === 'searchApplicant') {
            $('input[name="lastname"],input[name="firstname"]').keypress(function(e) {
                var code = e.keyCode || e.which;
                if (code == 13) {
                    searchApplicant()
                }
            });
        }

        $("form#save_modal_form").submit(function(e) {
            e.preventDefault();
            var pageRef = $('input[name="page"]').val();
            console.log(pageRef)
            var formData = new FormData(this);
            var fields = [];
            let notRequired = ['suffix', 'mn']
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
                var value = $("input[name='" + field + "'],select[name='" + field + "']").val().trim();

                if (value === '') {
                    if (!errMessage) {
                        alertRequired();
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("input[name='" + field + "'],select[name='" + field + "']").css("border-color", "#dd4b39");
                    $("select[name='" + field + "']").next().find(".select2-selection").css("border-color", "#dd4b39");
                }

                $("input[name='" + field + "'],select[name='" + field + "'],.select2-selection").on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
            if (fieldsComplete) {

                Swal.fire({
                    title: 'Confirm?',
                    text: 'Update/Save data?',
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
                            url: "<?= site_url('promo/save_modal_form') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {

                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Update/Saving successful!',
                                        icon: 'success',
                                        iconColor: '#15ca20',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok',
                                        customClass: 'required_alert',
                                        allowOutsideClick: false,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $("div#modal_form").modal("hide");
                                            profileData(pageRef)
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

        $("form#save_editContract").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var fields = [];
            let notRequired = ['vendor_code', 'mn']
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
                var value = $("input[name='" + field + "'],select[name='" + field + "'],textarea[name='" + field + "']").val();

                if (value === '') {
                    if (!errMessage) {
                        alertRequired();
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("input[name='" + field + "'],select[name='" + field + "'],textarea[name='" + field + "']").css("border-color", "#dd4b39");
                    $("select[name='" + field + "']").next().find(".select2-selection").css("border-color", "#dd4b39");
                }

                $("input[name='" + field + "'],select[name='" + field + "'],textarea[name='" + field + "'],.select2-selection").on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
            if (fieldsComplete) {
                var promo_type = $('select[name="promo_type"]').val();
                var storeCheck = [];
                $('input[name="stores[]"]:checked').each(function() {
                    storeCheck.push($(this).val());
                });
                if (promo_type == 'ROVING' && storeCheck.length < 2) {
                    Swal.fire({
                        title: 'Required!',
                        text: 'Please select atleast 2 store(s)!',
                        icon: 'warning',
                        iconColor: '#fd3550',
                        confirmButtonText: 'Ok',
                        confirmButtonColor: '#0d6efd',
                        customClass: 'required_alert'
                    });
                } else if (promo_type == 'STATION' && storeCheck.length == 0) {
                    Swal.fire({
                        title: 'Required!',
                        text: 'Please select a store!',
                        icon: 'warning',
                        iconColor: '#fd3550',
                        confirmButtonText: 'Ok',
                        confirmButtonColor: '#0d6efd',
                        customClass: 'required_alert'
                    });
                } else {
                    Swal.fire({
                        title: 'Update?',
                        text: 'Update Contract?',
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
                                url: "<?= site_url('promo/save_editContract') ?>",
                                type: 'POST',
                                data: formData,

                                success: function(data) {
                                    let response = JSON.parse(data);
                                    if (response.message == 'success') {

                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'Contract update successful!',
                                            icon: 'success',
                                            iconColor: '#15ca20',
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'Ok',
                                            customClass: 'required_alert',
                                            allowOutsideClick: false,
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $("div#editContract").modal("hide");
                                                profileData('contractHistory')
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

        $("form#save_uploadContract").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var fields = [];
            $('[name]').each(function() {
                var inputElement = $(this);

                if (inputElement.attr('type') === 'file') {
                    fields.push(inputElement.attr('name'));
                }
            });
            console.log(fields)
            var fieldsComplete = false;
            var errMessage = false;
            for (var i = 0; i < fields.length; i++) {

                var field = fields[i];
                var value = $("input[name='" + field + "']").val();

                if (value !== '') {
                    fieldsComplete = true;
                }

                if (value === '') {
                    if (!errMessage) {
                        Swal.fire({
                            title: 'Required!',
                            text: 'Please upload atleast 1(one) of the required fields!',
                            icon: 'warning',
                            iconColor: '#fd3550',
                            confirmButtonText: 'Ok',
                            confirmButtonColor: '#0d6efd',
                            customClass: 'required_alert'
                        });
                        errMessage = true;
                    }
                    if (!fieldsComplete) {
                        $("input[name='" + field + "']").css("border-color", "#dd4b39");
                    }
                }

                $("input").on("focus", function() {
                    $("input").css("border-color", "");
                });
            }
            if (fieldsComplete) {

                Swal.fire({
                    title: 'Upload?',
                    text: 'Upload file documents? ?',
                    icon: 'question',
                    iconColor: '#ffc107',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    customClass: 'required_alert'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("div#uploadContract").modal("hide");
                        $.ajax({
                            url: "<?= site_url('promo/save_uploadContract') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {

                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Upload successfull!',
                                        icon: 'success',
                                        iconColor: '#15ca20',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok',
                                        customClass: 'required_alert',
                                        allowOutsideClick: false,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            uploadContract(response.id)
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

        $("form#save_supervisor_form").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var supervisor = [];
            $('input[name="supervisor[]"]:checked').each(function() {
                supervisor.push($(this).val());
            });
            if (supervisor.length == 0) {
                Swal.fire({
                    title: 'Required!',
                    text: 'Please select a supervisor!',
                    icon: 'warning',
                    iconColor: '#fd3550',
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#0d6efd',
                    customClass: 'required_alert'
                });
            } else {

                Swal.fire({
                    title: 'Confirm?',
                    text: 'Add Supervisor?',
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
                            url: "<?= site_url('promo/save_supervisor_form') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {

                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Update/Saving successful!',
                                        icon: 'success',
                                        iconColor: '#15ca20',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok',
                                        customClass: 'required_alert',
                                        allowOutsideClick: false,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $("div#supervisor_form").modal("hide");
                                            profileData('supervisor')
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
                });
            }
        });

        $("form#tagtoRecruitmentForm").submit(function(e) {
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
                var value = $("input[name='" + field + "'],select[name='" + field + "']").val().trim();

                if (value === '') {
                    if (!errMessage) {
                        alertRequired();
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("input[name='" + field + "'],select[name='" + field + "']").css("border-color", "#dd4b39");
                    $("select[name='" + field + "']").next().find(".select2-selection").css("border-color", "#dd4b39");
                }

                $("input[name='" + field + "'],select[name='" + field + "'],.select2-selection").on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
            if (fieldsComplete) {

                Swal.fire({
                    title: 'Confirm?',
                    text: 'Submit Tag to Recruitment?',
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
                            url: "<?= site_url('promo/tagToRecruitment') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {

                                    if (response.tagging) {
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
                                                window.location = "<?= base_url('promo/page/promo/tagRecruitment') ?>";
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'Error!',
                                            text: response.report,
                                            icon: 'warning',
                                            iconColor: '#fd3550',
                                            confirmButtonText: 'Ok',
                                            confirmButtonColor: '#0d6efd',
                                            customClass: 'required_alert'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location = "<?= base_url('promo/page/promo/tagRecruitment') ?>";
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
    })

    function profilePic(pic) {

        var emp_id = $('input[name="emp_id"]').val();
        Swal.fire({
            html: '<img id="profile-pic" src="' + pic + '" alt="img" class="rounded-circle shadow" width="300" height="300">' +
                '<label class="mt-2">Change profile picture?</label>',
            confirmButtonText: 'Yes',
            confirmButtonColor: '#0d6efd',
            showCancelButton: true,
            cancelButtonText: 'No',
        }).then(async (result) => {
            if (result.isConfirmed) {
                const {
                    value: file
                } = await Swal.fire({
                    title: 'Select image',
                    input: 'file',
                    inputAttributes: {
                        accept: 'image/*',
                        'aria-label': 'Upload your profile picture',
                    },
                    confirmButtonText: 'Sumbit',

                })
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        Swal.fire({
                            title: 'Your uploaded picture',
                            imageUrl: e.target.result,
                            imageAlt: 'The uploaded picture',
                            confirmButtonText: 'Save',
                            showCancelButton: true,
                            cancelButtonText: 'Cancel',
                            customClass: {
                                image: 'rounded-circle shadow img-preview',
                            },
                        }).then(async (result) => {
                            if (result.isConfirmed) {
                                var formData = new FormData();
                                formData.append('file', file);
                                formData.append('emp_id', emp_id);
                                $.ajax({
                                    type: 'POST',
                                    url: '<?= site_url('promo/profilePic'); ?>',
                                    data: formData,
                                    contentType: false,
                                    processData: false,
                                    success: function(data) {
                                        let response = JSON.parse(data);
                                        if (response.message == 'success') {

                                            Swal.fire({
                                                title: 'Success!',
                                                text: 'Profile picture changed!',
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

                                            alert(data)
                                        }
                                    }
                                });
                            }
                        });
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    }

    function profileData(id) {

        var emp_id = $('input[name="emp_id"]').val();
        var text = $('a#' + id).text();
        if (id != '' && emp_id != '') {
            loader_dots('profileData')
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/profileData'); ?>",
                data: {
                    id: id,
                    emp_id: emp_id,
                },
                success: function(data) {


                    if (data == 'notPromo') {
                        window.location = "<?= base_url('supervisor/profile/') ?>" + emp_id;
                    } else {
                        $('div.profileData').html(data);
                        $('span.profileData_title').html(text);
                        new PerfectScrollbar('.sliders');
                    }
                }
            });
        }
    }

    function profileData_edit(action, page) {

        if (action == 'edit') {
            $('#' + page + ' input:disabled, #' + page + ' select:disabled, #' + page + ' textarea:disabled').prop('disabled', false);
            $('button#submit, button#cancel').show();
            $('button#edit').hide();
            $('i.input-group-text').addClass('text-primary');

        } else {
            profileData(page)
        }
    }

    function modal_form(action, no, page) {
        if (action !== 'remove') {
            if (page === 'supervisor') {
                $('div#supervisor_form').modal({
                    backdrop: 'static',
                    keyboard: false,
                });
                $("div#supervisor_form").modal("show");
            } else {
                $('div#modal_form').modal({
                    backdrop: 'static',
                    keyboard: false,
                });
                $("div#modal_form").modal("show");
            }
        }

        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/modal_form'); ?>",
            data: {
                action: action,
                no: no,
                page: page,
            },
            success: function(data) {
                if (action !== 'remove') {
                    if (page === 'supervisor') {
                        $('div.supervisor_form').html(data);
                    } else {
                        $('div.modal_form').html(data);
                    }
                } else {

                    let response = JSON.parse(data);
                    if (response.message == 'success') {

                        Swal.fire({
                            title: 'Success!',
                            text: 'Data deleted successful!',
                            icon: 'success',
                            iconColor: '#15ca20',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok',
                            customClass: 'required_alert',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                profileData(page)
                            }
                        });
                    } else {
                        alert(data);
                    }
                }
            }
        });
    }

    function userAccount(action, no, page) {
        if (action === 'add') {
            $('div#userAccount').modal({
                backdrop: 'static',
                keyboard: false,
            });
            $("div#userAccount").modal("show");
            userAccountUpdate(action, no, page)

        } else {
            Swal.fire({
                title: 'Confirm?',
                text: 'Are you sure you want to ' + action + ' account?',
                icon: 'question',
                iconColor: '#ffc107',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                customClass: 'required_alert'
            }).then((result) => {
                if (result.isConfirmed) {
                    userAccountUpdate(action, no, page)
                }
            })
        }
    }

    function userAccountUpdate(action, no, page) {
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/modal_form'); ?>",
            data: {
                action,
                no,
                page,
            },
            success: function(data) {
                if (action === 'add') {
                    $('.userAccount').html(data);
                    $('.form-check-input:not(:checked)').css('border-color', '#aaa');
                    $('.form-check-input').change(function() {
                        if (!$(this).is(':checked')) {
                            $(this).css('border-color', '#aaa');
                        } else {
                            $(this).css('border-color', '');
                        }
                    });
                } else {
                    let response = JSON.parse(data);
                    if (response.message == 'success') {
                        if (action == 'activate') {
                            var mes = 'activation'
                        } else if (action == 'deactivate') {
                            var mes = 'deactivation'
                        } else {
                            var mes = action
                        }
                        Swal.fire({
                            title: 'Success!',
                            text: 'Account ' + mes + ' successful!',
                            icon: 'success',
                            iconColor: '#15ca20',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok',
                            customClass: 'required_alert',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                profileData(page)
                            }
                        });
                    } else {
                        alert(data);
                    }
                }
            }
        });
    }

    function view_img(id) {
        $('div#view_img').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#view_img").modal("show");
        var link = "<?= base_url('../hrms/promo/') ?>" + id

        $("div.view_img").html(
            '<div class="row">' +
            '<img src="' + link + '" style="display: block;max-width: 100%;height: auto;" alt="...">' +
            '</div>'
        );
    }

    function viewIntro(id) {
        $('div#viewIntro').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#viewIntro").modal("show");
        var link = "<?= base_url('../hrms/promo/') ?>" + id

        $("div.viewIntro").html(
            '<div class="row">' +
            '<img src="' + link + '" style="display: block;max-width: 100%;height: auto;" alt="...">' +
            '</div>'
        );
    }

    function viewClearance(id) {
        $('div#viewClearance').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#viewClearance").modal("show");
        var link = "<?= base_url('../hrms/promo/') ?>" + id

        $("div.viewClearance").html(
            '<div class="row">' +
            '<img src="' + link + '" style="display: block;max-width: 100%;height: auto;" alt="...">' +
            '</div>'
        );
    }

    function viewContracts(id) {
        $('div#viewContracts').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#viewContracts").modal("show");
        var link = "<?= base_url('../hrms/promo/') ?>" + id

        $("div.viewContracts").html(
            '<div class="row">' +
            '<img src="' + link + '" style="display: block;max-width: 100%;height: auto;" alt="...">' +
            '</div>'
        );
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
                    details_id: details_id,
                },
                success: function(data) {

                    $('div.viewAppraisal').html(data);
                }
            });
        }
    }

    function viewExam_history(emp_id) {

        $('div#viewExam_history').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#viewExam_history").modal("show");

        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/viewExam_history'); ?>",
            data: {
                emp_id: emp_id,
            },
            success: function(data) {

                $('div.viewExam_history').html(data);
            }
        });
    }

    function viewApp_details(emp_id) {

        $('div#viewApp_details').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#viewApp_details").modal("show");

        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/viewApp_details'); ?>",
            data: {
                emp_id: emp_id,
            },
            success: function(data) {

                $('div.viewApp_details').html(data);
            }
        });
    }

    function viewInt_details(emp_id) {

        $('div#viewInt_details').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#viewInt_details").modal("show");

        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/viewInt_details'); ?>",
            data: {
                emp_id: emp_id,
            },
            success: function(data) {

                $('div.viewInt_details').html(data);
            }
        });
    }

    function viewContract(id) {

        let [contract, emp_id, record_no] = id.split('|')
        if (contract != '' && emp_id != '' && record_no != '') {
            $('div#viewContract').modal({
                backdrop: 'static',
                keyboard: false,
            });
            $("div#viewContract").modal("show");
            loader_dots('viewContract')
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/viewContract'); ?>",
                data: {
                    contract: contract,
                    emp_id: emp_id,
                    record_no: record_no,
                },
                beforeSend: function() {
                    loader_dots('viewContract');
                },
                success: function(data) {

                    $('div.viewContract').html(data);
                }
            });
        }
    }

    function editContract(id) {

        let [contract, emp_id, record_no] = id.split('|')
        if (contract != '' && emp_id != '' && record_no != '') {
            $('div#editContract').modal({
                backdrop: 'static',
                keyboard: false,
            });
            $("div#editContract").modal("show");
            loader_dots('editContract')
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/editContract'); ?>",
                data: {
                    contract: contract,
                    emp_id: emp_id,
                    record_no: record_no,
                },
                beforeSend: function() {
                    loader_dots('editContract');
                },
                success: function(data) {

                    $('div.editContract').html(data);
                }
            });
        }
    }

    function uploadContract(id) {

        let [contract, emp_id, record_no] = id.split('|')
        if (contract != '' && emp_id != '' && record_no != '') {
            $('div#uploadContract').modal({
                backdrop: 'static',
                keyboard: false,
            });
            $("div#uploadContract").modal("show");
            loader_dots('uploadContract')
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/uploadContract'); ?>",
                data: {
                    contract: contract,
                    emp_id: emp_id,
                    record_no: record_no,
                },
                beforeSend: function() {
                    loader_dots('uploadContract');
                },
                success: function(data) {

                    $('div.uploadContract').html(data);
                }
            });
        }
    }

    function contractFile(file) {
        var emp_id = $('input[name="emp_id"]').val();
        var record_no = $('input[name="record_no"]').val();
        var contract = $('input[name="contract"]').val();
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/contractFile'); ?>",
            data: {
                contract: contract,
                emp_id: emp_id,
                record_no: record_no,
                file: file,
            },
            beforeSend: function() {
                loader_dots('contractFile');
            },
            success: function(data) {

                $('div.contractFile').html(data);
            }
        });
    }

    function getCompany(value) {
        var action = 'getCompany';
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/getSelect'); ?>",
            data: {
                value,
                action,
            },
            success: function(data) {

                $('select[name="promo_company"]').html(data);
            }
        });
    }

    function getProduct(value) {
        var action = 'getProduct';
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/getSelect'); ?>",
            data: {
                value,
                action,
            },
            success: function(data) {
                $('select[name="product[]"]').empty();
                $('select[name="product[]"]').html(data);
            }
        });
    }

    function getStores(value, promo_type) {
        var action = 'getStores';
        var emp_id = $('input[name="emp_id"]').val();
        var record_no = $('input[name="record_no"]').val();
        var contract = $('input[name="contract"]').val();
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/getSelect'); ?>",
            data: {
                value,
                action,
                promo_type,
                emp_id,
                record_no,
                contract
            },
            success: function(data) {
                $('ul.stores').html(data);
            }
        });
    }

    function getDepartment() {
        var action = 'getDepartment';
        var value = [];
        $('input[name="stores[]"]:checked').each(function() {
            value.push($(this).val());
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
                $('select[name="promo_department"]').html(data);
            }
        });
    }

    function getVendor(value) {
        var action = 'getVendor';

        console.log(value)
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/getSelect'); ?>",
            data: {
                value,
                action,
            },
            success: function(data) {
                $('select[name="vendor_code"]').html(data);
            }
        });
    }

    function getDuration(eocdate) {
        var action = 'getDuration';
        var startdate = $('input[name="startdate"]').val();
        console.log(eocdate)
        if (eocdate === 'startdate') {
            $('input[name="eocdate"]').val('');
        } else {
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
                                $('input[name="eocdate"]').val('');
                                $('input[name="duration"]').val('');
                            }
                        });
                    } else {

                        $('input[name="duration"]').val(response.message);
                    }
                }
            });
        }
    }

    function getSupervisor_bc(value) {
        var action = 'getSupervisor_bc';
        var emp_id = $('input[name="emp_id"]').val();
        console.log(value)
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/getSelect'); ?>",
            data: {
                value,
                action,
                emp_id,
            },
            success: function(data) {

                $('select[name="bunit_code"]').html(data);
                supervisorList(value, emp_id)
            }
        });
    }

    function getSupervisor_dc(value) {
        var action = 'getSupervisor_dc';
        var emp_id = $('input[name="emp_id"]').val();
        console.log(value)
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/getSelect'); ?>",
            data: {
                value,
                action,
                emp_id,
            },
            success: function(data) {

                $('select[name="dept_code"]').html(data);
                supervisorList(value, emp_id)
            }
        });
    }

    function getSupervisor_sc(value) {
        var action = 'getSupervisor_sc';
        var emp_id = $('input[name="emp_id"]').val();
        console.log(value)
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/getSelect'); ?>",
            data: {
                value,
                action,
                emp_id,
            },
            success: function(data) {

                $('select[name="section_code"]').html(data);
                supervisorList(value, emp_id)
            }
        });
    }

    function getSupervisor_ssc(value) {
        var action = 'getSupervisor_ssc';
        var emp_id = $('input[name="emp_id"]').val();
        console.log(value)
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/getSelect'); ?>",
            data: {
                value,
                action,
                emp_id,
            },
            success: function(data) {

                $('select[name="sub_section_code"]').html(data);
                supervisorList(value, emp_id)
            }
        });
    }

    function getSupervisor_uc(value) {
        var action = 'getSupervisor_uc';
        var emp_id = $('input[name="emp_id"]').val();
        console.log(value)
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/getSelect'); ?>",
            data: {
                value,
                action,
                emp_id,
            },
            success: function(data) {

                $('select[name="unit_code"]').html(data);
                supervisorList(value, emp_id)
            }
        });
    }

    function supervisorList(value, emp_id) {
        var [cc, bc, dc, sc, ssc, uc] = value.split('|')

        loader_dots('supervisorList')
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/supervisorList'); ?>",
            data: {
                cc,
                bc,
                dc,
                sc,
                ssc,
                uc,
                emp_id
            },
            success: function(data) {
                $('div.supervisorList').html(data);
                $("#supervisorList").DataTable({
                    scrollCollapse: true,
                    scrollY: '300px',
                    ordering: false,
                    lengthChange: false,
                })
                $('.form-check-input:not(:checked)').css('border-color', '#aaa');
                $('.form-check-input').change(function() {
                    if (!$(this).is(':checked')) {
                        $(this).css('border-color', '#aaa');
                    } else {
                        $(this).css('border-color', '');
                    }
                });
            }
        });
    }

    function docFile_view(name, emp_id, no) {
        $('div#docFile_view').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#docFile_view").modal("show");
        $('h5.docFile_view').html(name);
        loader_circle('docFile_view')

        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/docFile_view'); ?>",
            data: {
                emp_id,
                no,
            },
            success: function(data) {

                $('div.docFile_view').html(data);
            }
        });
    }

    function page(page, no) {
        var emp_id = $('input[name="emp_id"]').val();
        console.log(page, no, emp_id)
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/docFile_view'); ?>",
            data: {
                page,
                emp_id,
                no,
            },
            success: function(data) {

                $('div.docFile_view').html(data);
            }
        });
    }

    function passwordFunc(action) {
        if (action == 'viewPassword') {
            if ($('input[name="' + action + '"]').prop('checked')) {
                $('input[name="password"]').attr('type', 'text');
            } else {
                $('input[name="password"]').attr('type', 'password');
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

    function filterDepartment(value) {
        var action = 'filterDepartment';
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/getSelect'); ?>",
            data: {
                value,
                action,
            },
            success: function(data) {
                $('select[name="promo_department"]').html(data);
            }
        });
    }

    function filterMasterfile() {
        var store = $('select[name="store"]').val();
        var promo_department = $('select[name="promo_department"]').val();
        var promo_type = $('select[name="promo_type"]').val();
        var promo_company = $('select[name="promo_company"]').val();
        if (store != '') {
            $("#masterfile").DataTable({
                "destroy": true,
                "ajax": {
                    url: "<?= site_url('promo/masterfile'); ?>",
                    type: "POST",
                    data: {
                        store,
                        promo_department,
                        promo_type,
                        promo_company
                    },
                },
                "order": [],
                "columnDefs": [{
                        "targets": [1, 2, 3, 4, 5],
                        "orderable": false,

                    },
                    {
                        "targets": 6,
                        "className": "text-center",
                        "orderable": false,
                    }
                ],
            });
        } else {
            alertRequired()
            $("select[name='store']").css("border-color", "#dd4b39");
            $("select[name='store'],.select2-selection").on("focus", function() {
                $(this).css("border-color", "");
            });
        }
    }

    function searchApplicant() {
        var firstname = $('input[name="firstname"]').val();
        var lastname = $('input[name="lastname"]').val();
        if (lastname.trim() != '') {
            $('button.searchApplicantButton').prop('disabled', true);
            $('div.result').show();
            loader_dots('result')
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/searchApplicant'); ?>",
                data: {
                    firstname,
                    lastname,
                },
                success: function(data) {
                    $('div.result').html(data);
                    $('button.searchApplicantButton').prop('disabled', false);
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        } else {
            alertRequired()
            $("input[name='lastname']").css("border-color", "#dd4b39");
            $("input[name='lastname']").on("focus", function() {
                $(this).css("border-color", "");
            });
        }
    }

    function searhPromo(str, process) {
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
        $('select[name="recProcess"],select[name="position"]').prop('disabled', true);
        $('select[name="recProcess"],select[name="position"]').val('');
        $('input[name="status"],input[name="current_status"]').val('');
        $('select[name="recProcess"] option[value=""]').prop('selected', true);
        $('select[name="position"] option[value=""]').prop('selected', true);
    }

    function getPromoDetails(name, emp_id, process, status, current_status) {
        if (name.trim() != '') {
            $('select[name="recProcess"],select[name="position"]').prop('disabled', false);
            $('input[name="' + process + '"]').val(name);
            $('input[name="status"]').val(status);
            $('input[name="current_status"]').val(current_status);
            $('input[name="emp_id"]').val(emp_id);
        }
        $('.' + process).hide()
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

    function loader_circle2(page) {
        $('div.' + page).html(
            '<div style="text-align: center;">' +
            '<img src="<?= base_url('assets/promo_assets/images/giphy.gif') ?>" style="width: 40%;">' +
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