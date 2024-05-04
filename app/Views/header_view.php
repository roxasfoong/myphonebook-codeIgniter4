<!-- 

  HTML ID: recently-add-row 
  Usage  : Used to change the item using ajax

-->

<header class="bg-dark text-light py-3">

  <div class="container-sm">
    <div class="row">
      <div class="col-6 d-flex justify-content-center align-items-center">
        <a href="/" class="text-decoration-none text-white fw-bold">
          <img src="<?php echo site_url('/assets/img/logo-small.png'); ?>" class="img-fluid" alt="Description of your image">MyPhoneBook
        </a>
      </div>
      <div class="col-6 d-flex justify-content-center align-items-center">
        <button id="add-new-btn" class="btn btn-primary btn-lg btn-block border-line-4">+ Add New</button>
      </div>
    </div>
  </div>
</header>

<div class="row p-2 mb-4 bg-secondary bg-gradient text-center text-white">
  <div class="col-12 m-auto">

    <?php if (session()->has('user_id')) : ?>
      Welcome, <b> <?php echo esc(session()->get('user_nickname'));?> </b>
  </div>
  <div class="col-12 m-auto">
      <a href="<?php echo site_url('auth/logout'); ?>" class="btn btn-secondary border-line-3">Logout</a>
  </div>
    <?php else : ?>
      <?php

      if (!session()->has('user_id')) {
        redirect('login');
      }

      ?>
    <?php endif; ?>
  </div>
</div>