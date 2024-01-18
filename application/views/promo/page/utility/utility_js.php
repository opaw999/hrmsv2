<script>
    $(document).ready(function() {
        $('.datepicker').flatpickr();
        loader_dots('logs_list');
        $('div.logs').hide();
        var dataTable = $("#logs").DataTable({
            "destroy": true,
            "ajax": {
                url: "<?php echo site_url('promo/logs'); ?>",
                type: "POST"
            },
            "order": [
                [2, 'desc']
            ],
            "columnDefs": [{
                    "targets": [0, 1, 3],
                    "orderable": false,
                },
                {
                    "targets": [4, 5],
                    "className": "text-center",
                    "orderable": false,
                },
            ],
            "initComplete": function(settings, json) {
                $('div.logs').show();
                $('.logs_list').html('');
            }
        });
        logsAdmin()
    });

    function logsAdmin() {
        loader_dots('logsAdmin_list');
        $('div.logsAdmin').hide();
        var day = $('input[name="day"]').val();
        var dataTable = $("#logsAdmin").DataTable({
            "destroy": true,
            "ajax": {
                url: "<?php echo site_url('promo/logsAdmin'); ?>",
                type: "POST",
                data: {
                    day
                }
            },
            "order": [
                [0, 'desc']
            ],
            "columnDefs": [{
                    "targets": [1, 2, 3],
                    "orderable": false,
                },
                {
                    "targets": [4, 5],
                    "className": "text-center",
                    "orderable": false,
                },
            ],
            "initComplete": function(settings, json) {
                $('div.logsAdmin').show();
                $('.logsAdmin_list').html('');
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