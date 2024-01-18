<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <link rel="shortcut icon" href="<?= base_url();?>assets/images/hrmsv2.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="<?= base_url();?>assets/css/fonts.css" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url();?>assets/fonts/icomoon/style.css">

    <link rel="stylesheet" href="<?= base_url();?>assets/css/owl.carousel.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url();?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url();?>assets/css/toastr.min.css">
    
    <!-- Style -->
    <link rel="stylesheet" href="<?= base_url();?>assets/css/style.css">

    <title>HRMS-BOHOL | EMPLOYEE LOGIN </title>
    <style>
      .bg-image {
          background-position: center;
          background-repeat: no-repeat;
          background-size: cover;
          -webkit-background-size: cover;
          -moz-background-size: cover;
          -o-background-size: cover;
          position: fixed;
          background-image: url("<?= base_url();?>assets/images/bg_6.png");
          height: 100%;
          width: 101%;
      }
    </style>
  </head>
  <body>  

  <div class="d-lg-flex half">
    <div class="contents order-2 order-md-1">
      <div class="row bg-image align-items-center">
          <div class="col-lg-4 col-12 col-md-6 offset-lg-1 bg-light p-5">
            <div>
                <!-- <div class="row">
                  <div class="col-md-1">
                    <span> <img src="<?= base_url();?>assets/images/hrmsv2.png" width="30" height="30"> </span>
                  </div> 
                  <div class="col-md-11">
                    <h4> Login to your Account </h4>
                  </div> 
                </div> -->
                <h4> <img src="<?= base_url();?>assets/images/hrmsv2.png" width="30" height="30"> Login to your Account </h4>
                
                <p class="mb-4">Opportunities could knock more than once if you put more doors in your life.</p>
                <form
                    api-url="employee/login"
                    api-type="POST"
                    onsubmit="event.preventDefault(); return handleFormSubmit();"
                    id="form">
                    
                    <div class="form-group first">
                        <label for="username">Username</label>
                        <input
                            type="text"
                            class="form-control"
                            id="username"
                            autocomplete="off"
                            name="username"
                            autofocus="true"
                            required="true">
                    </div>

                    <div class="form-group last mb-3">
                        <label for="password">Password</label>
                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            required="true">
                    </div>
                    
                    <div class="d-flex mb-4 align-items-center">
                        <label class="control control--checkbox mb-0"><span class="caption">Show password</span>
                            <input type="checkbox" onclick="showPassword()">
                            <div class="control__indicator"></div>
                        </label>
                        <span class="ml-auto"><a href="#" onclick="forgot_pass()" class="forgot-pass">Forgot Password</a></span> 
                    </div>

                    <button type="submit" id="form-button" class="btn btn-block btn-success" button-message="Checking...">Log In</button>
                    <br>
                    <div class="text-center">
                        <label class="control control--checkbox mb-0"> 
                        <span class="caption">© 2023 HRMS VERSION 2.0. Alturas Group of Companies</span>
                        </label>
                    </div>
                </form>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>

  <script>let url = "<?= base_url();?>";</script>
  <script src="<?= base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/popper.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/toastr.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/main.js"></script>
  <script src="<?= base_url(); ?>assets/js/hrms.js"></script>
  <script src="<?= base_url(); ?>assets/js/hrms_employee.js"></script>
  </body>
</html>