<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/main.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/sweetalert2.min.css">
  <script src="<?php echo base_url()?>assets/js/sweetalert2.min.js"></script>
  <title>Login Page | MyPhoneBook</title>
</head>

<body>

  <div class="container p-3">

    <div class="row justify-content-center mt-5">

      <div class="col-md-6 ">

        <div class="card add-shadow">

          <div class="d-flex justify-content-center align-items-center mb-4">
            <img src="<?php echo base_url()?>assets/img/logo-medium.png" alt="MyPhoneBook Logo" class="img-fluid">
          </div>

          <div class="d-flex justify-content-center align-items-center mb-4">
            <h1 class="text-center fw-normal">Welcome to MyPhoneBook<h1>
          </div>

          <div class="card-header d-flex justify-content-center align-items-center">Login</div>

          <div class="row text-center">
            <div class="col-12 m-auto">
            
              <?php if (session()->getFlashdata('validation_errors')) : ?>
                <div class="alert alert-danger" role="alert">
                  <b><?php echo session()->getFlashdata('validation_errors'); ?> </b>
                </div>
              <?php endif; ?>
              
              <?php if (session()->getFlashdata('login_errors')) : ?>
                <div class="alert alert-danger" role="alert">
                <b><?php echo session()->getFlashdata('login_errors'); ?></b>
                </div>
              <?php endif; ?>

              <?php if (session()->getFlashdata('db_errors')) : ?>
                <div class="alert alert-danger" role="alert">
                <b><?php echo session()->getFlashdata('db_errors'); ?></b>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <div class="card-body">

            <form method="post" action="<?php echo site_url('auth/login'); ?>">

              <div class="mb-3">
                <div for="email" class="card-label">Email address:</div>
                <input name="email" type="email" class="form-control" id="email" aria-describedby="emailHelp" required autocomplete="true">
              </div>

              <div class="mb-5">
                <div for="password" class="card-label">Password:</div>
                <input name="password" type="password" class="form-control" id="password" required>
                <a href="#" class="forgot-password-link small d-flex justify-content-end">Forgot Password?</a>
              </div>

              <div class="mb-5 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary border-line-4">Login</button>
              </div>

            </form>

            <div class="col-md-12">
              <p class="text-center mb-0">Don't have an account? <a href="<?php echo base_url('register'); ?>" class="signup-link">Sign up</a></p>
            </div>

          </div>

        </div>

      </div>


    </div>

  </div>
  <?php if (session()->getFlashdata('success')) : ?>
    <script>
      Swal.fire({
        title: 'Success!',
        text: '<?php echo session()->getFlashdata('success'); ?>',
        icon: 'success',
        showConfirmButton: false,
        timer: 2000 
      });
    </script>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')) : ?>
    <script>
      Swal.fire({
        title: 'Error!',
        text: '<?php echo session()->getFlashdata('error'); ?>',
        icon: 'error',
        showConfirmButton: true,
      });
    </script>
  <?php endif; ?>
</body>

</html>