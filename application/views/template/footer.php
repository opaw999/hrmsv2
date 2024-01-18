           
            
            <div class="am-footer">
                <span>Copyright &copy;. All Rights Reserved. Alturas Group of Companies.</span>
            </div><!-- am-footer -->
        </div><!-- am-mainpanel -->
        
        <script> 
            let DTApi   = "<?= @$tblApi;?>";
            let url     = "<?= base_url(); ?>";
        </script>
        
        <script src="<?= base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
        <!-- <script src="<?= base_url(); ?>assets/js/jquery-3.6.0.min.js"></script> -->
        <!-- <script src="<?= base_url(); ?>assets/js/jquery.min.js"></script> -->
        <script src="<?= base_url(); ?>assets/js/popper.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/bootstrap.js"></script>
        <script src="<?= base_url(); ?>assets/js/bootstrap-dialog.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/perfect-scrollbar.jquery.js"></script>
        <script src="<?= base_url(); ?>assets/js/toggles.min.js"></script>
        <!-- <script src="<?= base_url(); ?>assets/js/bootstrap-datepicker1.min.js"></script> -->
        <!-- <script src="<?= base_url(); ?>assets/js/jquery-ui.min.js"></script> -->
        <!-- <script src="<?= base_url(); ?>assets/js/rickshaw.min.js"></script> -->
        <script src="<?= base_url(); ?>assets/js/jquery.flot.js"></script>
        <script src="<?= base_url(); ?>assets/js/jquery.flot.pie.js"></script>
        <script src="<?= base_url(); ?>assets/js/jquery.flot.resize.js"></script>
        <script src="<?= base_url(); ?>assets/js/jquery.flot.spline.js"></script>

        <script src="<?= base_url(); ?>assets/js/jquery.dataTables.js"></script>
        <script src="<?= base_url(); ?>assets/js/dataTables.responsive.js"></script>
        <script src="<?= base_url(); ?>assets/js/select2.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/toastr.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/sweetalert2.min.js"></script>

        <script src="<?= base_url(); ?>assets/js/amanda.js"></script>
        <script src="<?= base_url(); ?>assets/js/ResizeSensor.js"></script>
        <!-- <script src="<?= base_url(); ?>assets/js/dashboard.js"></script> -->
        <script src="<?= base_url(); ?>assets/js/hrms.js"></script>
        <script src="<?= base_url(); ?>assets/js/hrms_employee.js"></script>
        <script src="<?= base_url(); ?>assets/js/input-mask/jquery.inputmask.js"></script>
        <script src="<?= base_url(); ?>assets/js/input-mask/jquery.inputmask.date.extensions.js"></script>
        <script src="<?= base_url(); ?>assets/js/input-mask/jquery.inputmask.extensions.js"></script>
        <script src="<?= base_url(); ?>assets/medium-editor/medium-editor.js"></script>
        <script src="<?= base_url(); ?>assets/summernote/summernote-bs4.min.js"></script>
        <script src="<?= base_url(); ?>assets/highlightjs/highlight.pack.js"></script>
        <script src="<?= base_url(); ?>assets/confetti/confetti.js"></script>
        <script>
            // Scroll top JS 
            $(document).ready(function() {
            // Show or hide the scroll to top button
            $(window).scroll(function() {
                if ($(this).scrollTop() > 200) {
                $('#scrollToTopBtn').fadeIn();
                } else {
                $('#scrollToTopBtn').fadeOut();
                }
            });

            // Scroll to top when the button is clicked
            $('#scrollToTopBtn').click(function() {
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            });
            });
           
            $(function () {
            'use strict';

            // Inline editor
            var editor = new MediumEditor('.editable');

            // Summernote editor
            $('#summernote').summernote({
                height: 150,
                tooltip: true,
                popatmouse: true,
            });

            // Attach a change event handler to the Summernote editor to capture changes and update the hidden input field
            $('#summernote').on('summernote.change', function (contents, $editable) {
                var jobDescription = $('#summernote').summernote('code');
                // Update the hidden input field with the job description content
                $('textarea[id="desc"]').val(jobDescription);
            });
        });

 
        </script>
    </body>

</html>