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

        $('.select2clear').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: true
        });

        <?php
        if ($menu == 'contract' && $file == 'renewContract') { ?>
            getIntros()
        <?php
        } ?>


        $('.previousContract :input').prop('disabled', true);

        var dataTable = $("#eocList").DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "<?= site_url('promo/eocList'); ?>",
                type: "POST"
            },
            "order": [],
            "columnDefs": [{
                "targets": [3, 4],
                "orderable": false,

            }, {
                "targets": [4],
                "className": "text-center",
            }],
        });

        $("div#generatePermitForm").on("hidden.bs.modal", function() {
            var emp_id = $('input[name="emp_id"]').val();
            var record_no = $('input[name="record_no"]').val();
            var contract = $('input[name="contract"]').val();
            showAlert(emp_id, record_no, contract)
        });

        $("div#permitModal").on("hidden.bs.modal", function() {
            $('input[name="printPermit"]').val('');
            $('div.contracts').html('');
        });

        $("div#contractModal").on("hidden.bs.modal", function() {
            $('input[name="printContract"]').val('');
            $('div.contracts').html('');
        });

        $("div#extendModal").on("hidden.bs.modal", function() {
            $('input[name="extendSearch"]').val('');
            $('input[name="emp_id"]').val('');
            $('input[name="record_no"]').val('');
        });

        $("div#generateContractForm").on("hidden.bs.modal", function() {
            var emp_id = $('input[name="emp_id"]').val();
            var record_no = $('input[name="record_no"]').val();
            var contract = $('input[name="contract"]').val();
            showAlert(emp_id, record_no, contract)
        });

        $("form#save_uploadClearance").submit(function(e) {
            e.preventDefault();
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
                var value = $("input[name='" + field + "']").val().trim();

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
                    text: 'Are you sure you want to upload clearance?',
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
                            url: "<?= site_url('promo/save_uploadClearance') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {

                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Upload Clearance successful!',
                                        icon: 'success',
                                        iconColor: '#15ca20',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok',
                                        customClass: 'required_alert',
                                        allowOutsideClick: false,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $("div#uploadClearance").modal("hide");
                                            window.location = "<?= base_url('promo/page/contract/renewContract/') ?>" + response.data;

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

        $("form#renewContract").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var fields = [];
            let notRequired = ['vendor_code', 'duration', 'witness1', 'witness2', 'comments', 'remarks']
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
                        alertRequired('Please fillup the required fields!');
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

                    alertRequired('Please select atleast 2 store(s) for ROVING!')
                } else if (promo_type == 'STATION' && storeCheck.length == 0) {

                    alertRequired('Please select a store!')
                } else {
                    Swal.fire({
                        title: 'Confirm?',
                        text: 'Are you sure you want to renew contract?',
                        icon: 'question',
                        iconColor: '#ffc107',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        customClass: 'required_alert'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $("button#submit").prop("disabled", true);
                            $.ajax({
                                url: "<?= site_url('promo/renewContract') ?>",
                                type: 'POST',
                                data: formData,

                                success: function(data) {
                                    let response = JSON.parse(data);
                                    if (response.message == 'success') {
                                        var emp_id = response.emp_id;
                                        var record_no = response.record_no;
                                        var contract = response.contract;
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'Contract renewal successful! You can now generate Contract and Permit.',
                                            icon: 'success',
                                            iconColor: '#15ca20',
                                            showCancelButton: true,
                                            confirmButtonText: 'Go to Contract',
                                            cancelButtonText: 'Go to Permit',
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#3085d6',
                                            showCloseButton: true,
                                            closeButtonAriaLabel: 'Exit',
                                            allowOutsideClick: false,
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                generateContractForm(response.emp_id)
                                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                                generatePermitForm(emp_id, record_no, contract)
                                            } else if (result.dismiss === Swal.DismissReason.close) {
                                                var unset = 'printID';
                                                $.ajax({
                                                    type: "POST",
                                                    url: "<?= site_url('promo/setSession'); ?>",
                                                    data: {
                                                        unset
                                                    },
                                                    success: function(data) {
                                                        window.location = "<?= base_url('promo/page/contract/renewal') ?>";
                                                    }
                                                });
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

        $("form#generatePermitForm").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var fields = [];
            let notRequired = ['specialDays', 'specialSched']
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
                    $("select[name='" + field + "']").next().find(".select2-selection").css("border-color", "#dd4b39");
                }

                $("input[name='" + field + "'],select[name='" + field + "'],.select2-selection").on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
            if (fieldsComplete) {
                var cutOff = $('select[name="cutOff"]').val();
                formData.append('cutOff', cutOff);
                Swal.fire({
                    title: 'Confirm?',
                    text: 'Are you sure you want to submit?',
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
                            url: "<?= site_url('promo/savePermit') ?>",
                            type: 'POST',
                            data: formData,
                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Permit generation successful!',
                                        icon: 'success',
                                        iconColor: '#15ca20',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok',
                                        customClass: 'required_alert',
                                        allowOutsideClick: false,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            var id = response.data;
                                            $.ajax({
                                                type: "POST",
                                                url: "<?= site_url('promo/generatePermit'); ?>",
                                                data: {
                                                    id
                                                },
                                                success: function(data) {
                                                    let response = JSON.parse(data);
                                                    if (response.message == 'success') {
                                                        window.open("<?= base_url('promo/viewPdf') ?>?" + response.id);
                                                    }
                                                }
                                            });
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

        $("form#generateContractForm").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var fields = [];
            let notRequired = ['specialDays', 'specialSched']
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

                var sss_ctc = [];
                $('input[name="sss_ctc[]"]:checked').each(function() {
                    sss_ctc.push($(this).val());
                });

                if (sss_ctc.length == 0) {

                    alertRequired('Please select SSS or Cedula!');
                } else {
                    Swal.fire({
                        title: 'Confirm?',
                        text: 'Are you sure you want to submit?',
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
                                url: "<?= site_url('promo/saveContract') ?>",
                                type: 'POST',
                                data: formData,

                                success: function(data) {
                                    let response = JSON.parse(data);
                                    if (response.message == 'success') {
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'Contract generation successful!',
                                            icon: 'success',
                                            iconColor: '#15ca20',
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'Ok',
                                            customClass: 'required_alert',
                                            allowOutsideClick: false,
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                var id = response.data;
                                                $.ajax({
                                                    type: "POST",
                                                    url: "<?= site_url('promo/generateContract'); ?>",
                                                    data: {
                                                        id,
                                                    },
                                                    success: function(data) {
                                                        let response = JSON.parse(data);
                                                        if (response.message == 'success') {
                                                            window.open("<?= base_url('promo/viewPdf') ?>?" + response.id);
                                                        }
                                                    }
                                                });
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

        $("form#transferRate").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var emp_id = $('input[name="emp_id"]').val()
            if (emp_id != '') {
                var stores = [];
                $('input[name="stores[]"]:checked').each(function() {
                    stores.push($(this).val());
                });

                if (stores.length == 0) {

                    alertRequired('Please select a Rate to Transfer!');
                } else {
                    Swal.fire({
                        title: 'Confirm?',
                        text: 'Are you sure you want to tranfer rate?',
                        icon: 'question',
                        iconColor: '#ffc107',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        customClass: 'required_alert'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('button.transferButton').prop('disabled', true)
                            $.ajax({
                                url: "<?= site_url('promo/transferRateSave') ?>",
                                type: 'POST',
                                data: formData,

                                success: function(data) {
                                    let response = JSON.parse(data);
                                    if (response.message == 'success') {
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'Transfer Rate successful!',
                                            icon: 'success',
                                            iconColor: '#15ca20',
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'Ok',
                                            customClass: 'required_alert',
                                            allowOutsideClick: false,
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location = "<?= base_url('promo/page/contract/transferRate') ?>";
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
            } else {

                alertRequired('The emp_id input is blank!');
            }
        });
    });

    function generatePermitForm(emp_id, record_no, contract) {
        $('div#generatePermitForm').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#generatePermitForm").modal("show");
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/generatePermitForm'); ?>",
            data: {
                emp_id,
                record_no,
                contract

            },
            success: function(data) {
                $('div.generatePermitForm').html(data);
                $('.select2').select2({
                    theme: "bootstrap-5",
                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                    placeholder: $(this).data('placeholder'),
                });
            }
        });

    }

    function generateContractForm(emp_id) {
        $('div#generateContractForm').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#generateContractForm").modal("show");
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/generateContractForm'); ?>",
            data: {
                emp_id,
            },
            success: function(data) {
                $('div.generateContractForm').html(data);
                $('.datepicker').flatpickr();
            }
        });
    }

    function showAlert(emp_id, record_no, contract) {
        Swal.fire({
            title: 'Success!',
            text: 'Contract renewal successful! You can now generate Contract and Permit.',
            icon: 'success',
            iconColor: '#15ca20',
            showCancelButton: true,
            confirmButtonText: 'Go to Contract',
            cancelButtonText: 'Go to Permit',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#3085d6',
            showCloseButton: true,
            closeButtonAriaLabel: 'Exit',
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                generateContractForm(emp_id)
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                generatePermitForm(emp_id, record_no, contract)
            } else if (result.dismiss === Swal.DismissReason.close) {
                var unset = 'printID';
                $.ajax({
                    type: "POST",
                    url: "<?= site_url('promo/setSession'); ?>",
                    data: {
                        unset
                    },
                    success: function(data) {
                        window.location = "<?= base_url('promo/page/contract/renewal') ?>";
                    }
                });

            }
        });
    }

    function showElement(value) {
        if (value == 'cedula') {
            $('input[name="ctc"]').show();
            $('div.issuedat').show();
            $('div.issuedon').show();
            $('input[name="sss"]').hide();
            $('input[name="ctc"]').prop('disabled', false);
            $('input[name="sss"]').prop('disabled', true);
            $('input[name="issuedat"]').prop('disabled', false);
            $('input[name="issuedon"]').prop('disabled', false);
        } else {
            $('input[name="sss"]').show();
            $('div.issuedat').show();
            $('div.issuedon').hide();
            $('input[name="ctc"]').hide();
            $('input[name="ctc"]').prop('disabled', true);
            $('input[name="sss"]').prop('disabled', false);
            $('input[name="issuedat"]').prop('disabled', false);
            $('input[name="issuedon"]').prop('disabled', true);
        }
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

    function proceed(emp_id) {
        $('div#uploadClearance').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#uploadClearance").modal("show");
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/proceed'); ?>",
            data: {
                emp_id,
            },
            success: function(data) {
                $('div.uploadClearance').html(data);
            }
        });
    }

    function imgPreview(input) {
        var uniqueId = input.id;
        var preview = $('#imagePreview_' + uniqueId);
        var clearButton = $('#clearButton_' + uniqueId);

        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(preview).css('display', 'block');
                $(preview).attr('src', e.target.result);
                $(clearButton).css('display', 'block');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function clearImageInput(uniqueId) {
        $('input#' + uniqueId).val('');
        $('#imagePreview_' + uniqueId).css('display', 'none');
        $('#clearButton_' + uniqueId).css('display', 'none');
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
        var contract = 'current';
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
        getIntros()
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

                        $('input[name="eocdate"]').val('');
                        $('input[name="duration"]').val('');
                        Swal.fire({
                            title: 'Required!',
                            text: response.message,
                            icon: 'warning',
                            iconColor: '#fd3550',
                            confirmButtonText: 'Ok',
                            confirmButtonColor: '#0d6efd',
                            customClass: 'required_alert'
                        });
                    } else {

                        $('input[name="duration"]').val(response.message);
                    }
                }
            });
        }
    }

    function getIntros() {
        var action = 'getIntros';
        var value = [];
        $('input[name="stores[]"]:checked').each(function() {
            value.push($(this).val());
        });
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/getSelect'); ?>",
            data: {
                value,
                action,
            },
            success: function(data) {
                $('div.intros').html(data);
            }
        });
    }

    function getDutydays(value) {
        var action = 'getDutydays';

        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/getSelect'); ?>",
            data: {
                value,
                action,
            },
            success: function(data) {
                $('input[name="dutyDays"]').val(data);
            }
        });
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
    }

    function getName(name, element) {
        if (name.trim() != '') {
            $('input[name="' + element + '"]').val(name);
        }
        $('div.' + element).hide()
    }

    function getPromo(name, emp_id, record_no, process) {
        if (name.trim() != '') {
            $('input[name="' + process + '"]').val(name);
            $('input[name="emp_id"]').val(emp_id);
            $('input[name="record_no"]').val(record_no);
        }
        $('.' + process).hide()
    }

    function getPrint(name, emp_id, process) {

        if (name.trim() != '') {
            $('input[name="' + process + '"]').val(name);
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/getSelect'); ?>",
                data: {
                    value: emp_id,
                    action: process,
                },
                success: function(data) {
                    $('div.contracts').html(data);
                    $('.datepicker').flatpickr();
                }
            });
        }
        $('.' + process).hide()
    }

    function getPromoDetails(name, emp_id, process) {
        if (name.trim() != '') {
            $('input[name="' + process + '"]').val(name);
            $('div.transferRateForm').show();
            loader_dots('transferRateForm')
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/getPromoDetails'); ?>",
                data: {
                    emp_id,
                },
                success: function(data) {
                    $('div.promoDetails').html(data);
                    transferRateForm(emp_id)
                }
            });
        }
        $('.' + process).hide()
    }

    function transferRateForm(emp_id) {
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/transferRateForm'); ?>",
            data: {
                emp_id,
            },
            success: function(data) {
                $('div.transferRateForm').html(data);
            }
        });
    }

    function selectPermit(emp_id, record_no, contract) {
        $('div#selectPermit').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#selectPermit").modal("show");
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/generatePermitForm'); ?>",
            data: {
                emp_id,
                record_no,
                contract
            },
            success: function(data) {
                $('div.selectPermit').html(data);
                $('.select2').select2({
                    theme: "bootstrap-5",
                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                    placeholder: $(this).data('placeholder'),
                });
            }
        });
    }

    function proceedExtend() {
        var emp_id = $('input[name="emp_id"]').val();
        var record_no = $('input[name="record_no"]').val();
        var name = $('input[name="extendSearch"]').val();
        if (emp_id != '' && record_no != '' && name.trim() != '') {
            Swal.fire({
                title: 'Confirm?',
                text: 'Are you sure you want to extend contract?',
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
                        url: "<?= site_url('promo/setSession'); ?>",
                        data: {
                            emp_id
                        },
                        success: function(data) {
                            window.location = "<?= base_url('promo/page/contract/renewContract/') ?>" + emp_id;
                        }
                    });
                }
            })
        } else {
            alertRequired('Please fillup the required fields!')
            $("input[name='extendSearch']").css("border-color", "#dd4b39");
            $("input[name='extendSearch']").on("focus", function() {
                $(this).css("border-color", "");
            });
        }
    }

    function chkStore() {

        var storeCheck = [];
        $('input[name="stores[]"]:checked').each(function() {
            storeCheck.push($(this).val());
        });
        var emp_id = $('input[name="emp_id"]').val()
        var process = $('input[name="process"]').val()
        var name = $('input[name="' + process + '"]').val()
        console.log(storeCheck)
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/checkStores'); ?>",
            data: {
                emp_id,
                storeCheck,
                process
            },
            success: function(data) {
                let response = JSON.parse(data);
                if (response.message == 'success') {
                    $.each(storeCheck, function(index, id) {
                        if (!response.data.includes(id)) {
                            Swal.fire({
                                title: 'Check Store!',
                                text: 'The selected Rate Store does not exist in the Current Contract!',
                                icon: 'warning',
                                iconColor: '#fd3550',
                                confirmButtonText: 'Ok',
                                confirmButtonColor: '#0d6efd',
                                customClass: 'required_alert'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('#' + id).prop('checked', false);
                                    getPromoDetails(name, emp_id, process)
                                }
                            });
                        }
                    });
                }
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