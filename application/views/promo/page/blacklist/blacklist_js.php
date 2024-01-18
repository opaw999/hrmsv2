<script>
    $(document).ready(function() {

        var dataTable = $("#blacklist").DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "<?php echo site_url('promo/getBlacklist'); ?>",
                type: "POST",
            },
            "order": [],
            "columnDefs": [{
                    "targets": [0, 2, 3, 4],
                    "orderable": false,

                },
                {
                    "targets": 5,
                    "className": "text-center",
                    "orderable": false,
                }
            ],
        });

        $("form#save_bl_update").submit(function(e) {
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
                var value = $("textarea[name='" + field + "'],input[name='" + field + "']").val();

                if (value.trim() === '') {

                    if (!errMessage) {

                        alertRequired()
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("textarea[name='" + field + "'], input[name='" + field + "']").each(function() {
                        $(this).css("border-color", "#dd4b39");
                    });
                    $("textarea[name='" + field + "'], input[name='" + field + "']").on("focus", function() {
                        $(this).css("border-color", "");
                    });
                }
            }
            if (fieldsComplete) {

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Update',
                    customClass: 'required_alert'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "<?= site_url('promo/save_bl_update') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {

                                    $("div#update_bl_form").modal("hide");
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Blacklist information updated.',
                                        icon: 'success',
                                        iconColor: '#15ca20',
                                        confirmButtonText: 'Ok',
                                        confirmButtonColor: '#0d6efd',
                                        customClass: 'required_alert'
                                    }).then(() => {

                                        location.reload();
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

        $("form#save_bl").submit(function(e) {
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
                var value = $("textarea[name='" + field + "'],input[name='" + field + "']").val().trim();

                if (value === '') {

                    if (!errMessage) {

                        alertRequired()
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("textarea[name='" + field + "'], input[name='" + field + "']").each(function() {
                        $(this).css("border-color", "#dd4b39");
                    });
                    $("textarea[name='" + field + "'], input[name='" + field + "']").on("focus", function() {
                        $(this).css("border-color", "");
                    });
                }
            }
            if (fieldsComplete) {

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'question',
                    iconColor: '#ffc107',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    customClass: 'required_alert'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "<?= site_url('promo/save_bl') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {

                                    $("div#add_bl_form").modal("hide");
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Added to Blacklist.',
                                        icon: 'success',
                                        iconColor: '#15ca20',
                                        confirmButtonText: 'Ok',
                                        confirmButtonColor: '#0d6efd',
                                        customClass: 'required_alert'
                                    }).then(() => {
                                        location.reload();
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

        $("input[name='lastname'],input[name='firstname'],input[name='middlename']").on('search', function() {
            $("button.addManual").hide();
            $("div.checkBl").hide();
            $("div.checkBlt").hide();
        });
        $("input[name='lastname'],input[name='firstname'],input[name='middlename']").on('keyup', function() {
            $("button.addManual").hide();
            $("div.checkBl").hide();
            $("div.checkBlt").hide();
        });
    });

    function checkBl() {

        var fn = $('input[name="firstname"]').val().trim();
        var ln = $('input[name="lastname"]').val().trim();
        var mn = $('input[name="middlename"]').val().trim();
        if (ln == '' || fn == '') {
            if (ln == '') {
                $('input[name="lastname"]').css("border-color", "#dd4b39");
            }
            if (fn == '') {
                $('input[name="firstname"]').css("border-color", "#dd4b39");
            }
            $('input[name="lastname"],input[name="firstname"]').on("focus", function() {
                $(this).css("border-color", "");
            });
            $("button.addManual").hide();
            alertRequired()
        } else {
            loader_dots('checkBl')
            loader_dots('checkBlt')
            $("button.addManual").show();
            $("div.checkBl").show();
            $("div.checkBlt").show();
            $.ajax({
                url: "<?= site_url('promo/checkBl') ?>",
                type: 'POST',
                success: function(data) {
                    $("div.checkBl").html(data);
                    $("div.checkBl").addClass("border-start");

                    $("#checkBl_table").DataTable({
                        "destroy": true,
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            url: "<?php echo site_url('promo/addCheckBl'); ?>",
                            type: "POST",
                            data: {
                                fn,
                                ln,
                                mn,
                            },
                        },
                        "order": [],
                        "columnDefs": [{
                            "targets": [1, 2],
                            "className": "text-center",
                            "orderable": false,
                        }],
                        lengthChange: false,
                        searching: false
                    });
                }
            });

            $.ajax({
                url: "<?= site_url('promo/checkBlt') ?>",
                type: 'POST',
                success: function(data) {
                    $("div.checkBlt").html(data);
                    $("div.checkBlt").addClass("border-start");
                    $("#checkBlt_table").DataTable({
                        "destroy": true,
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            url: "<?php echo site_url('promo/addCheckBlt'); ?>",
                            type: "POST",
                            data: {
                                fn,
                                ln,
                                mn,
                            },
                        },
                        "order": [],
                        "columnDefs": [{
                            "targets": [1],
                            "className": "text-center",
                            "orderable": false,
                        }],
                        lengthChange: false,
                        searching: false
                    });

                }
            });
        }
    }

    function addBl(emp_id) {

        $('div#add_bl_form').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#add_bl_form").modal("show");
        $.ajax({
            url: "<?= site_url('promo/addBl') ?>",
            type: 'POST',
            data: {
                emp_id: emp_id,
            },

            success: function(data) {
                $("div.bl_form").html(data);
                $(".datepicker").flatpickr();
                //para sa year nga ma-edit
                document.addEventListener('focusin', (e) => {
                    if (e.target.closest(".flatpickr-calendar") !== null) {
                        e.stopImmediatePropagation();
                    }
                });
            }
        });
    }

    function addManual() {
        var fn = $('input[name="firstname"]').val().trim();
        var ln = $('input[name="lastname"]').val().trim();
        var mn = $('input[name="middlename"]').val().trim();
        if (ln == '' || fn == '') {
            if (ln == '') {
                $('input[name="lastname"]').css("border-color", "#dd4b39");
            }
            if (fn == '') {
                $('input[name="firstname"]').css("border-color", "#dd4b39");
            }
            $('input[name="lastname"],input[name="firstname"]').on("focus", function() {
                $(this).css("border-color", "");
            });
            $("button.addManual").hide();
            alertRequired()
        } else {
            $('div#add_bl_form').modal({
                backdrop: 'static',
                keyboard: false,
            });
            $("div#add_bl_form").modal("show");

            $.ajax({
                url: "<?= site_url('promo/addManualBl') ?>",
                type: 'POST',
                data: {
                    fn,
                    ln,
                    mn
                },

                success: function(data) {
                    $("div.bl_form").html(data);
                    $(".datepicker").flatpickr();
                    //para sa year nga ma-edit
                    document.addEventListener('focusin', (e) => {
                        if (e.target.closest(".flatpickr-calendar") !== null) {
                            e.stopImmediatePropagation();
                        }
                    });
                }
            });
        }
    }

    function nameSearch(str) {
        if (str.trim() != '') {
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/reportedbyBl'); ?>",
                data: {
                    str: str
                },
                success: function(data) {
                    $(".dropdown-list").show()
                    $(".dropdown-list").html(data);
                }
            });
        } else(
            $(".dropdown-list").hide()
        )
    }

    function getName(name) {

        $("input[name='reportedby']").val(name);
        $(".dropdown-list").hide()
    }

    function updateBl(id) {

        $('div#update_bl_form').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#update_bl_form").modal("show");

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('promo/update_blacklist'); ?>",
            data: {
                id,
            },
            success: function(data) {

                $("div.bl_form").html(data);
                $(".datepicker").flatpickr();

                //para sa year nga ma-edit
                document.addEventListener('focusin', (e) => {
                    if (e.target.closest(".flatpickr-calendar") !== null) {
                        e.stopImmediatePropagation();
                    }
                });
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

    function alertRequired() {
        Swal.fire({
            title: 'Required!',
            text: 'Please fillup the required fields!',
            icon: 'warning',
            iconColor: '#ffc107',
            confirmButtonText: 'Ok',
            confirmButtonColor: '#0d6efd',
            customClass: 'required_alert'
        });
    }
</script>