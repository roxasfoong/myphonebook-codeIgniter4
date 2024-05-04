<?php if (session()->has('user_id')) : ?>
    <?php return redirect()->to('dashboard'); ?>
<?php else : ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/main.css">

  <title>Register Page | MyPhoneBook</title>
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

          <div class="card-header d-flex justify-content-center align-items-center">Register</div>

          <div class="row text-center">
            <div class="col-12 m-auto">
            
            <?php if (session()->getFlashdata('error')) : ?>
                
                <div class="alert alert-danger text-left" role="alert">
                  <b><?php echo session()->getFlashdata('error'); ?> </b>
                </div>
              <?php endif; ?>
              
              <?php if (session()->getFlashdata('db_errors')) : ?>
                <div class="alert alert-danger" role="alert">
                <b><?php echo session()->getFlashdata('db_errors'); ?></b>
                </div>
              <?php endif; ?>
              <?php if (session()->getFlashdata('some_errors')) : ?>
                <div class="alert alert-danger" role="alert">
                <b><?php echo session()->getFlashdata('some_errors'); ?></b>
                </div>
              <?php endif; ?>
            </div>
          </div>
          <div class="card-body">

            <form method="post" action="<?php echo site_url('auth/register'); ?>">

              <div class="mb-3">
                <label for="nickname" class="form-label">Nickname:</label>
                <input name="nickname" type="text" class="form-control" id="nickname" required>
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">Email address:</label>
                <input name="email" type="email" class="form-control" id="email" aria-describedby="emailHelp" required autocomplete="true">
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input name="password" type="password" class="form-control" id="password" required>
              </div>

              <div class="mb-3">
                <label for="confirm-password" class="form-label">Confirm Password:</label>
                <input name="confirm_password" type="password" class="form-control" id="confirm-password" required>
              </div>

              <div class="mb-5 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary border-line-4">Sign Up</button>
              </div>

            </form>

            <div class="col-md-12">
              <p class="text-center mb-0">Have an account? <a href="<?php echo base_url('login'); ?>" class="signup-link">Login</a></p>
            </div>

          </div>

        </div>

      </div>


    </div>

  </div>

</body>
</html>
<?php endif; ?>