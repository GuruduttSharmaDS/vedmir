
<?php include 'inc/header.php';?>
 
<section class="formPage-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div id="loginForm">
          <form class="text-center border border-light p-5" action="" method="post">
            <p class="h4 mb-4"><?=$this->lang->line('signIn') ?></p>
            <ul><?php echo $this->common_lib->showSessMsg(); ?></ul>
             <hr class="mb-4">
            <!-- Email -->
            <input type="email" id="defaultLoginFormEmail" name="txtEmailId" class="form-control mb-4" placeholder="<?=$this->lang->line('email') ?>">
            <!-- Password -->
            <input type="password" id="defaultLoginFormPassword" name="txtPassword" class="form-control mb-4" placeholder="<?=$this->lang->line('password') ?>">
            <div class="d-flex justify-content-around">
                <div>
                  <!-- Remember me -->
                  <div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" id="defaultLoginFormRemember">
                      <label class="custom-control-label" for="defaultLoginFormRemember"><?=$this->lang->line('rememberMe') ?></label>
                  </div>
                </div>
                <div>
                  <!-- Forgot password -->
                  <a class="forgot-btn" href="#"><?=$this->lang->line('forgotPassword') ?>?</a>
                </div>
            </div>
            <!-- Sign in button -->
            <button class="btn btn-info btn-block my-4" type="submit"><?=$this->lang->line('signIn') ?></button>
            <!-- Register -->
            <p>Not a member?
                <a href="signup.html"><?=$this->lang->line('signUp') ?></a>
            </p>
          </form>
        </div>

        <div id="forgotPassword" class="hide">
          <form class="text-center border border-light p-5" action="#!">
            <p class="h4 mb-4">Forgot Password</p>
             <hr class="mb-4">
            <!-- Email -->
            <input type="email" id="defaultLoginFormEmail" class="form-control mb-4" placeholder="E-mail">
            <button class="btn btn-info btn-block my-4" type="submit">Submit</button>
            <p><a href="#" class="login-btn">Login Here</a></p>
          </form>
        </div>

      </div>
    </div> 
  </div>
</section>

<?php include 'inc/footer.php';?>