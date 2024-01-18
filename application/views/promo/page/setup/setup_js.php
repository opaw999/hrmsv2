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
            // tags: true,
        });
        agencyListTable()
        companyListTable()
        productListTable()
        companyAgencyTable()
        productCompanyTable()
        departmentListTable()
        buListTable()

        $("form#saveBuForm").submit(function(e) {
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
                    text: 'Are you sure you want to add a Business Unit?',
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
                            url: "<?= site_url('promo/updateBu') ?>",
                            type: 'POST',
                            data: formData,
                            beforeSend: function() {
                                $('button.addFormButton').prop('disabled', true)
                                $('button.addFormButton').html(
                                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>' +
                                    'Loading...')
                            },
                            success: function(data) {
                                let response = JSON.parse(data);
                                if (response.message == 'success') {
                                    if (response.report == 'added') {
                                        $("div#addFormBu").modal("hide");
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'Business Unit has been ' + response.report + '!',
                                            icon: 'success',
                                            iconColor: '#15ca20',
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'Ok',
                                            customClass: 'required_alert',
                                            allowOutsideClick: false,
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $('button.addFormButton').prop('disabled', false)
                                                $('button.addFormButton').html('Submit')
                                                buListTable()
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'Failed!',
                                            text: 'Business Unit exist!',
                                            icon: 'warning',
                                            iconColor: '#fd3550',
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'Ok',
                                            customClass: 'required_alert',
                                            allowOutsideClick: false,
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $('input[name="' + response.report + '"]').css("border-color", "#dd4b39");
                                                $('input[name="' + response.report + '"]').on("focus", function() {
                                                    $(this).css("border-color", "");
                                                });

                                                $('button.addFormButton').prop('disabled', false)
                                                $('button.addFormButton').html('Submit')
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

    function searhPromo(str, process) {
        $('input[name="emp_id"]').val('');
        $('div.supDetails').html('');
        $('div.setupSubordinateForm').html('');
        $('div.buttonsSubordinate').html('');
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

    function getSupDetails(name, emp_id, process) {
        if (name.trim() != '') {
            $('input[name="' + process + '"]').val(name);
            $('input[name="emp_id"]').val(emp_id);
            $('div.' + process + 'Form').show();
            loader_dots(process + 'Form')
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/supervisorDetails'); ?>",
                data: {
                    emp_id,
                },
                success: function(data) {
                    $('div.supDetails').html(data);
                    $('div.buttonsSubordinate').html('<button type="button" class="btn btn-sm btn-primary px-3" ' +
                        'onclick="addSub(\'' + name + '\', \'' + emp_id + '\', \'' + process + '\')">' +
                        'Add subordinates</button> <button type="button" class="btn btn-sm btn-primary px-3" ' +
                        'onclick="getSupDetails(\'' + name + '\', \'' + emp_id + '\', \'' + process + '\')">' +
                        'View subordinates</button>');

                    subordinates(emp_id);
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
        $('.' + process).hide()
    }

    function subordinates(emp_id) {
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/subordinates'); ?>",
            data: {
                emp_id,
            },
            success: function(data) {
                $('div.setupSubordinateForm').html(data);
                var dataTable = $("#setupSubordinateTable").DataTable({
                    "destroy": true,
                    "order": [],
                    "columnDefs": [{
                            "targets": [1, 3],
                            "orderable": false,

                        },
                        {
                            "targets": [0, 4],
                            "className": "text-center",
                            "orderable": false,
                        }
                    ],
                    // scrollCollapse: true,
                    // scrollY: '300px',
                });
                // $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }

    function updateSub(process, id, emp_id) {

        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/updateSubordinates'); ?>",
            data: {
                process,
                id,
                emp_id
            },
            success: function(data) {
                let response = JSON.parse(data);
                if (response.message == 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Subordinates has been updated!',
                        icon: 'success',
                        iconColor: '#15ca20',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok',
                        customClass: 'required_alert',
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (process == 'remove') {
                                subordinates(response.emp_id)
                            } else {
                                generateSub()
                            }

                        }
                    });
                } else {
                    alert(data);
                }
            }
        });

    }

    function addSub(name, emp_id, process) {

        $('div.supDetails').html('');
        $('div.setupSubordinateForm').html('');
        $('div.buttonsSubordinate').html('');

        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/addSubordinatesForm'); ?>",
            success: function(data) {
                $('div.supDetails').html(data);
                $('div.buttonsSubordinate').html('<button type="button" class="btn btn-sm btn-primary px-2"' +
                    'onclick="generateSub()">' +
                    'Generate subordinates</button> <button type="button" class="btn btn-sm btn-primary px-2" ' +
                    'onclick="getSupDetails(\'' + name + '\', \'' + emp_id + '\', \'' + process + '\')">' +
                    'View subordinates</button>');
            }
        });
    }

    function generateSub() {
        var emp_id = $('input[name="emp_id"]').val();
        var emp_type = $('select[name="emp_type"]').val();
        var store = $('select[name="store"]').val();
        var promo_department = $('select[name="promo_department"]').val();
        var fields = ['emp_id', 'emp_type', 'store', 'promo_department'];
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
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/generateSub'); ?>",
                data: {
                    emp_id,
                    emp_type,
                    store,
                    promo_department
                },
                success: function(data) {
                    $('div.setupSubordinateForm').html(data);
                    var dataTable = $("#generateSubordinateTable").DataTable({
                        "destroy": true,
                        "order": [],
                        "columnDefs": [{
                                "targets": [1, 3],
                                "orderable": false,

                            },
                            {
                                "targets": [0, 4],
                                "className": "text-center",
                                "orderable": false,
                            }
                        ],
                        // scrollCollapse: true,
                        // scrollY: '300px',
                    });
                }
            });
        }
    }

    function getDepartment(val) {
        var action = 'getDepartment';
        var value = [];

        value.push(val);

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

    function companyAgencyTable() {
        $('select[name="ac"]').val(null).trigger('change');
        var dataTable = $("#compAgencyTable").DataTable({
            "destroy": true,
            "ajax": {
                url: "<?= site_url('promo/companyAgency'); ?>",
                type: "POST"
            },
            "order": [
                [0, 'asc']
            ],
            "columnDefs": [{
                "targets": 2,
                "className": "text-center",
                "orderable": false,
            }],
        });
    }

    function agencyListTable() {
        var dataTable = $("#agencyListTable").DataTable({
            "destroy": true,
            "ajax": {
                url: "<?= site_url('promo/agencyList'); ?>",
                type: "POST"
            },
            "order": [
                [0, 'asc']
            ],
            "columnDefs": [{
                "targets": 1,
                "className": "text-center",
                "orderable": false,
            }],
        });
    }

    function departmentListTable() {
        var dataTable = $("#departmentListTable").DataTable({
            "destroy": true,
            "ajax": {
                url: "<?= site_url('promo/departmentList'); ?>",
                type: "POST"
            },
            "order": [
                [0, 'asc']
            ],
            "columnDefs": [{
                "targets": 3,
                "className": "text-center",
                "orderable": false,
            }],
        });
    }

    function buListTable() {
        var dataTable = $("#buListTable").DataTable({
            "destroy": true,
            "ajax": {
                url: "<?= site_url('promo/buList'); ?>",
                type: "POST"
            },
            "order": [
                [0, 'asc']
            ],
            "columnDefs": [{
                "targets": 4,
                "className": "text-center",
                "orderable": false,
            }],
        });
    }

    function companyListTable() {
        var dataTable = $("#companyListTable").DataTable({
            "destroy": true,
            "ajax": {
                url: "<?= site_url('promo/companyList'); ?>",
                type: "POST"
            },
            "order": [
                [0, 'asc']
            ],
            "columnDefs": [{
                "targets": 1,
                "className": "text-center",
                "orderable": false,
            }],
        });
    }

    function productListTable() {
        var dataTable = $("#productListTable").DataTable({
            "destroy": true,
            "ajax": {
                url: "<?= site_url('promo/productList'); ?>",
                type: "POST"
            },
            "order": [
                [0, 'asc']
            ],
            "columnDefs": [{
                "targets": 1,
                "className": "text-center",
                "orderable": false,
            }],
        });
    }

    function productCompanyTable() {
        $('select[name="company"]').val(null).trigger('change');
        var dataTable = $("#productCompanyTable").DataTable({
            "destroy": true,
            "ajax": {
                url: "<?= site_url('promo/productCompany'); ?>",
                type: "POST"
            },
            "order": [
                [0, 'asc']
            ],
            "columnDefs": [{
                "targets": 2,
                "className": "text-center",
                "orderable": false,
            }],
        });
    }

    function updateCompanyAgency(process, cc) {
        var ac = $('select[name="ac"]').val();
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/updateCompanyAgency'); ?>",
            data: {
                process,
                cc,
                ac
            },
            success: function(data) {
                let response = JSON.parse(data);
                if (response.message == 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Company for Agency List has been updated!',
                        icon: 'success',
                        iconColor: '#15ca20',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok',
                        customClass: 'required_alert',
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (process == 'remove') {
                                companyAgencyTable()
                            } else {
                                getCompany(ac)
                            }
                        }
                    });
                } else {
                    alert(data);
                }
            }
        });

    }

    function updateProductCompany(process, id) {
        var company = $('select[name="company"]').val();
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/updateProductCompany'); ?>",
            data: {
                process,
                id,
                company
            },
            success: function(data) {
                let response = JSON.parse(data);
                if (response.message == 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Product for Company List has been updated!',
                        icon: 'success',
                        iconColor: '#15ca20',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok',
                        customClass: 'required_alert',
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (process == 'remove') {
                                productCompanyTable()
                            } else {
                                getProduct(company)
                            }
                        }
                    });
                } else {
                    alert(data);
                }
            }
        });

    }

    function getCompany(agency_code) {
        var dataTable = $("#compAgencyTable").DataTable({
            "destroy": true,
            "ajax": {
                url: "<?= site_url('promo/companyAgencyList'); ?>",
                type: "POST",
                data: {
                    agency_code
                },
            },
            "order": [],
            "columnDefs": [{
                "targets": 0,
                "visible": false,
            }, {
                "targets": 2,
                "className": "text-center",
                "orderable": false,
            }],
        });
    }

    function getProduct(company) {
        var dataTable = $("#productCompanyTable").DataTable({
            "destroy": true,
            "ajax": {
                url: "<?= site_url('promo/productCompanyList'); ?>",
                type: "POST",
                data: {
                    company
                },
            },
            "order": [],
            "columnDefs": [{
                "targets": 0,
                "visible": false,
            }, {
                "targets": 2,
                "className": "text-center",
                "orderable": false,
            }],
        });
    }

    function updateForm(process, id, table) {
        $('div#updateForm').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#updateForm").modal("show");

        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/updateForm'); ?>",
            data: {
                process,
                id,
                table
            },
            success: function(data) {
                $('div.updateForm').html(data)
            }
        });

    }

    function addFormBu(table) {
        $('div#addFormBu').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#addFormBu").modal("show");

        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/updateForm'); ?>",
            data: {
                table
            },
            success: function(data) {
                $('div.addFormBu').html(data)
            }
        });

    }

    function addForm() {
        $('div#addForm').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#addForm").modal("show");
    }

    function saveAgency() {
        var id = $('input[name="agency_code"]').val();
        var agency_name = $('input[name="agency_name"]').val();
        var process = $('input[name="process"]').val();
        if (agency_name.trim() != '') {
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/updateAgency'); ?>",
                data: {
                    process,
                    id,
                    agency_name
                },
                success: function(data) {
                    let response = JSON.parse(data);
                    if (response.message == 'success') {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Agency has been ' + response.report + '!',
                            icon: 'success',
                            iconColor: '#15ca20',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok',
                            customClass: 'required_alert',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("div#updateForm").modal("hide");
                                $("div#addForm").modal("hide");
                                window.location = "<?= base_url('promo/page/setup/agencyList') ?>";
                            }
                        });
                    } else {
                        alert(data);
                    }
                }
            });
        } else {
            alertRequired('Please fillup the required fields!')
            $("input[name='agency_name']").css("border-color", "#dd4b39");
            $("input[name='agency_name']").on("focus", function() {
                $(this).css("border-color", "");
            });
        }
    }

    function updateAgency(process, id) {

        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/updateAgency'); ?>",
            data: {
                process,
                id,
            },
            success: function(data) {
                let response = JSON.parse(data);
                if (response.message == 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Agency has been ' + response.report + '!',
                        icon: 'success',
                        iconColor: '#15ca20',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok',
                        customClass: 'required_alert',
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            agencyListTable()
                        }
                    });
                } else {
                    alert(data);
                }
            }
        });
    }

    function saveCompany() {
        var id = $('input[name="pc_code"]').val();
        var pc_name = $('input[name="pc_name"]').val();
        var process = $('input[name="process"]').val();
        if (pc_name.trim() != '') {
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/updateCompany'); ?>",
                data: {
                    process,
                    id,
                    pc_name
                },
                success: function(data) {
                    let response = JSON.parse(data);
                    if (response.message == 'success') {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Company has been ' + response.report + '!',
                            icon: 'success',
                            iconColor: '#15ca20',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok',
                            customClass: 'required_alert',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("div#updateForm").modal("hide");
                                $("div#addForm").modal("hide");
                                window.location = "<?= base_url('promo/page/setup/companyList') ?>";
                            }
                        });
                    } else {
                        alert(data);
                    }
                }
            });
        } else {
            alertRequired('Please fillup the required fields!')
            $("input[name='pc_name']").css("border-color", "#dd4b39");
            $("input[name='pc_name']").on("focus", function() {
                $(this).css("border-color", "");
            });
        }
    }

    function updateCompany(process, id) {

        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/updateCompany'); ?>",
            data: {
                process,
                id,
            },
            success: function(data) {
                let response = JSON.parse(data);
                if (response.message == 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Company has been ' + response.report + '!',
                        icon: 'success',
                        iconColor: '#15ca20',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok',
                        customClass: 'required_alert',
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            companyListTable()
                        }
                    });
                } else {
                    alert(data);
                }
            }
        });
    }

    function saveProduct() {
        var id = $('input[name="id"]').val();
        var product = $('input[name="product"]').val();
        var process = $('input[name="process"]').val();
        if (product.trim() != '') {
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/updateProduct'); ?>",
                data: {
                    process,
                    id,
                    product
                },
                success: function(data) {
                    let response = JSON.parse(data);
                    if (response.message == 'success') {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Product has been ' + response.report + '!',
                            icon: 'success',
                            iconColor: '#15ca20',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok',
                            customClass: 'required_alert',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("div#updateForm").modal("hide");
                                $("div#addForm").modal("hide");
                                window.location = "<?= base_url('promo/page/setup/productList') ?>";
                            }
                        });
                    } else {
                        alert(data);
                    }
                }
            });
        } else {
            alertRequired('Please fillup the required fields!')
            $("input[name='pc_name']").css("border-color", "#dd4b39");
            $("input[name='pc_name']").on("focus", function() {
                $(this).css("border-color", "");
            });
        }
    }

    function updateProduct(process, id) {

        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/updateProduct'); ?>",
            data: {
                process,
                id,
            },
            success: function(data) {
                let response = JSON.parse(data);
                if (response.message == 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Product has been ' + response.report + '!',
                        icon: 'success',
                        iconColor: '#15ca20',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok',
                        customClass: 'required_alert',
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            productListTable()
                        }
                    });
                } else {
                    alert(data);
                }
            }
        });
    }

    function updateDepartment(process, id) {
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/updateDepartment'); ?>",
            data: {
                process,
                id,
            },
            success: function(data) {
                let response = JSON.parse(data);
                if (response.message == 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Department has been ' + response.report + '!',
                        icon: 'success',
                        iconColor: '#15ca20',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok',
                        customClass: 'required_alert',
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            departmentListTable()
                        }
                    });
                } else {
                    alert(data);
                }
            }
        });
    }

    function saveDepartment() {
        var process = $('input[name="process"]').val();
        var bunit_id = $('select[name="bunit_id"]').val();
        var dept_name = $('select[name="dept_name"]').val();
        if (bunit_id == '' || dept_name == '') {
            alertRequired('Please fill the required fields!')

            if (bunit_id == '') {
                $('select[name="bunit_id"]').css("border-color", "#dd4b39");
                $('select[name="bunit_id"]').on("focus", function() {
                    $(this).css("border-color", "");
                });
            }

            if (dept_name == '') {
                $('select[name="dept_name"]').css("border-color", "#dd4b39");
                $('select[name="dept_name"]').on("focus", function() {
                    $(this).css("border-color", "");
                });
            }

        } else {
            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/updateDepartment'); ?>",
                data: {
                    process,
                    bunit_id,
                    dept_name
                },
                success: function(data) {
                    let response = JSON.parse(data);
                    if (response.message == 'success') {

                        if (response.report == 'exist') {
                            Swal.fire({
                                title: 'Failed!',
                                text: 'Department already ' + response.report + '!',
                                icon: 'warning',
                                iconColor: '#fd3550',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok',
                                customClass: 'required_alert',
                                allowOutsideClick: false,
                            })
                        } else {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Department has been ' + response.report + '!',
                                icon: 'success',
                                iconColor: '#15ca20',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok',
                                customClass: 'required_alert',
                                allowOutsideClick: false,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $("div#addForm").modal("hide");
                                    departmentListTable()
                                }
                            });
                        }
                    } else {
                        alert(data);
                    }
                }
            });
        }
    }

    function updateBu(process, column, value) {
        $.ajax({
            type: "POST",
            url: "<?= site_url('promo/updateBu'); ?>",
            data: {
                process,
                column,
                value
            },
            success: function(data) {
                let response = JSON.parse(data);
                if (response.message == 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Business Unit has been ' + response.report + '!',
                        icon: 'success',
                        iconColor: '#15ca20',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok',
                        customClass: 'required_alert',
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // buListTable()
                        }
                    });
                } else {
                    alert(data);
                }
            }
        });
    }

    function saveBu() {

        var updateForm = $('div.updateForm');

        var process = updateForm.find('input[name="process"]').val();
        var bunit_id = updateForm.find('input[name="bunit_id"]').val();
        var business_unit = updateForm.find('input[name="business_unit"]').val();
        var bunit_name = updateForm.find('input[name="bunit_name"]').val();
        var bunit_acronym = updateForm.find('input[name="bunit_acronym"]').val();

        if (bunit_id == '' || business_unit == '' || bunit_name == '' || bunit_acronym == '') {
            alertRequired('Please fill the required fields!')

            if (business_unit == '') {
                $('input[name="business_unit"]').css("border-color", "#dd4b39");
            }
            if (bunit_name == '') {
                $('input[name="bunit_name"]').css("border-color", "#dd4b39");
            }
            if (bunit_acronym == '') {
                $('input[name="bunit_acronym"]').css("border-color", "#dd4b39");
            }

            $('input[name="business_unit"],input[name="bunit_name"],input[name="bunit_acronym"]').on("focus", function() {
                $(this).css("border-color", "");
            });
        } else {

            $.ajax({
                type: "POST",
                url: "<?= site_url('promo/updateBu'); ?>",
                data: {
                    process,
                    bunit_id,
                    business_unit: business_unit.toUpperCase(),
                    bunit_name: bunit_name.toUpperCase(),
                    bunit_acronym: bunit_acronym.toUpperCase()
                },
                success: function(data) {
                    let response = JSON.parse(data);
                    if (response.message == 'success') {
                        if (response.report == 'updated') {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Business Unit has been ' + response.report + '!',
                                icon: 'success',
                                iconColor: '#15ca20',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok',
                                customClass: 'required_alert',
                                allowOutsideClick: false,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $("div#updateForm").modal("hide");
                                    buListTable()
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Failed!',
                                text: 'Business Unit exist!',
                                icon: 'warning',
                                iconColor: '#fd3550',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok',
                                customClass: 'required_alert',
                                allowOutsideClick: false,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('input[name="' + response.report + '"]').css("border-color", "#dd4b39");
                                    $('input[name="' + response.report + '"]').on("focus", function() {
                                        $(this).css("border-color", "");
                                    });

                                    $('button.addFormButton').prop('disabled', false)
                                    $('button.addFormButton').html('Submit')
                                }
                            });
                        }

                    } else {
                        alert(data);
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