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
        var dataTable = $("#resignationList").DataTable({
            "destroy": true,
            "ajax": {
                url: "<?= site_url('promo/resignationList'); ?>",
                type: "POST"
            },
            "order": [],
            "columnDefs": [{
                    "targets": [1, 2, 3, 4],
                    "orderable": false,

                },
                {
                    "targets": 5,
                    "className": "text-center",
                    "orderable": false,
                }
            ],
        });

        $("form#save_uploadLetter").submit(function(e) {
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
                    text: 'Are you sure you want to Upload Resignation Letter?',
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
                            url: "<?= site_url('promo/save_uploadLetter') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Uploading Resignation Letter successful!',
                                        icon: 'success',
                                        iconColor: '#15ca20',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok',
                                        customClass: 'required_alert',
                                        allowOutsideClick: false,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location = "<?= base_url('promo/page/resignation/resignTermList') ?>";
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

        $("form#resignationForm").submit(function(e) {
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
                var value = $("input[name='" + field + "'],select[name='" + field + "'],textarea[name='" + field + "']").val();

                if (value === '') {
                    if (!errMessage) {
                        alertRequired('Please fillup the required fields!');
                        errMessage = true;
                    }
                    fieldsComplete = false;
                    $("input[name='" + field + "'],select[name='" + field + "'],textarea[name='" + field + "']").css("border-color", "#dd4b39");
                }

                $("input[name='" + field + "'],select[name='" + field + "'],textarea[name='" + field + "']").on("focus", function() {
                    $(this).css("border-color", "");
                });
            }
            if (fieldsComplete) {
                Swal.fire({
                    title: 'Confirm?',
                    text: 'Are you sure you want to Add Resignation/Termination?',
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
                            url: "<?= site_url('promo/save_addResignation') ?>",
                            type: 'POST',
                            data: formData,

                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Adding Resignation/Termination successful!',
                                        icon: 'success',
                                        iconColor: '#15ca20',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok',
                                        customClass: 'required_alert',
                                        allowOutsideClick: false,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location = "<?= base_url('promo/page/resignation/addResignTerm') ?>";
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

    function view_letter(letter) {
        $('div#showLetter').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#showLetter").modal("show");
        var link = "<?= base_url('../hrms/promo/') ?>" + letter
        $("div.showLetter").html('<img src="' + link + '" class="d-block w-100" />');
    }

    function uploadLetter(termination_no, emp_id) {
        $('div#uploadLetter').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#uploadLetter").modal("show");
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/uploadLetter'); ?>",
            data: {
                termination_no,
                emp_id
            },
            success: function(data) {
                $('div.uploadLetter').html(data);
                $('input[name="termination_no"]').val(termination_no);
                $('input[name="emp_id"]').val(emp_id);
            }
        });
    }

    function searhPromo(str, process) {
        if (str.trim() != '') {
            $('input[name="emp_id"]').val('');
            if (process == 'resignation') {
                $('div.addResignationForm').html('');
                $('select[name="status"] option[value=""]').prop('selected', true);
                $('select[name="status"]').val('');
                $('textarea[name="remarks"]').val('');
                $('input[name="date"]').prop('disabled', true);
                $('select[name="status"]').prop('disabled', true);
                $('textarea[name="remarks"]').prop('disabled', true);
            } else {
                $('div.tagResignationTable').html('');
            }
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

    function getSupDetails(name, emp_id, process) {
        if (name.trim() != '') {
            $('input[name="' + process + '"]').val(name);
            $('input[name="emp_id"]').val(emp_id);
            loader_dots('tagResignationTable')
            tagResignationTable(emp_id)
        }
        $('.' + process).hide()
    }

    function tagResignationTable(emp_id) {
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/tagResignationTable'); ?>",
            data: {
                emp_id,
            },
            success: function(data) {
                $('div.tagResignationTable').html(data);
                var dataTable = $("#tagResignationTable").DataTable({
                    "destroy": true,
                    "order": [],
                    "columnDefs": [{
                            "targets": [0, 2, 3],
                            "orderable": false,

                        },
                        {
                            "targets": 4,
                            "className": "text-center",
                            "orderable": false,
                        }
                    ],
                    // scrollCollapse: true,
                    // scrollY: '300px',
                });
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }

    function getPromoDetails(name, emp_id, process, status) {
        if (name.trim() != '') {
            if (status == 'Active' || status == 'End of Contract') {
                Swal.fire({
                    title: status + '!',
                    text: 'Employee status is ' + status + '.',
                    icon: 'success',
                    iconColor: '#15ca20',
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#0d6efd',
                    customClass: 'required_alert',
                    allowOutsideClick: false,
                });
                $('input[name="date"]').prop('disabled', false);
                $('select[name="status"]').prop('disabled', false);
                $('textarea[name="remarks"]').prop('disabled', false);
                $('input[name="' + process + '"]').val(name);
                $('input[name="emp_id"]').val(emp_id);
            } else {
                Swal.fire({
                    title: status + '!',
                    text: 'Employee status is ' + status + '.',
                    icon: 'error',
                    iconColor: '#fd3550',
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#0d6efd',
                    customClass: 'required_alert',
                    allowOutsideClick: false,
                });
                $('input[name="date"]').prop('disabled', true);
                $('select[name="status"]').prop('disabled', true);
                $('textarea[name="remarks"]').prop('disabled', true);
                $('input[name="' + process + '"]').val('');
            }


        }
        $('.' + process).hide()
    }

    function addResignation(status) {
        if (status.trim() != '') {
            var emp_id = $('input[name="emp_id"]').val();
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/addResignationForm'); ?>",
                data: {
                    status,
                    emp_id,
                },
                success: function(data) {
                    $('div.addResignationForm').html(data);
                }
            });
        } else {
            $('div.addResignationForm').html('');
        }
    }

    function tagResignation(tag_stat, rater_id, ratee_id) {
        if (tag_stat != '' && rater_id != '' && ratee_id != '') {
            Swal.fire({
                title: 'Confirm?',
                text: 'Are you sure you want to ' + tag_stat + ' this employee?',
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
                        url: "<?= site_url('promo/tagResignation'); ?>",
                        data: {
                            tag_stat,
                            rater_id,
                            ratee_id
                        },
                        success: function(data) {
                            if (tag_stat == 'tag') {
                                $mess = 'Tagging successful!';
                            } else {
                                $mess = 'Untagging successful!';
                            }
                            Swal.fire({
                                title: 'Success!',
                                text: $mess,
                                icon: 'success',
                                iconColor: '#15ca20',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok',
                                customClass: 'required_alert',
                                allowOutsideClick: false,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    tagResignationTable(rater_id)
                                };
                            });
                        }
                    });
                };
            });
        } else {
            alertRequired('Data is empty!')
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