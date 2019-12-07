
<?php include 'inc/header.php';?>
 <style type="text/css">.form-group {
    margin-bottom: 0px;
}
.login-inside input[type="submit"] {
    margin-top: 0;
}
.login-inside h5 {
    margin-top: 1em;
}
.login-inside label.error {
    float: left;
    margin: 0;
    font-weight: 400;
}
.alert {
    padding: 5px 15px;
    margin-bottom: 15px;
    border: 1px solid transparent;
    border-radius: 4px;
    font-weight: 500;
    cursor: pointer;
}
.alert-success {
    color: #ffffff;
    background-color: #57c163;
    border: 2px solid #1f9632;
}
.alert-danger {
    color: #ffffff;
    background-color: #ea6565;
    border: 2px solid #c73535;
}
.msg {
    margin: 0 40px;
}
</style>

<div class="login-hero">
   <div class="container">
     <div class="login-inside wow" style="margin: 0em auto; padding-top: 0.1em;">
        <h1><?=$this->lang->line('signUp') ?></h1>
        <p><?=$this->lang->line('barAndRestaurantManagement') ?></p> 
        <div class="msg">
         <!--  <span class="alert alert-success" class="close" data-dismiss="alert" aria-label="close">
              Success!ndicates a successful or positive action.
          </span> -->
        </div>
        <form action="#" method="POST" onsubmit="restaurantSignup(this,event);">
            
            <div class="form-group">
              <div class="col-md-12">
                <input type="text" placeholder="<?=$this->lang->line('venueName') ?>" name="restaurantName" required>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <input type="email" placeholder="<?=$this->lang->line('email') ?>" name="email" required>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <input type="text" placeholder="<?=$this->lang->line('mobile') ?>" name="mobile" required>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <input type="text" placeholder="<?=$this->lang->line('city') ?>" name="city" required>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <input type="text" placeholder="<?=$this->lang->line('state') ?>" name="state" required>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <input type="text" placeholder="<?=$this->lang->line('country') ?>" name="country" required>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <input type="text" placeholder="<?=$this->lang->line('zipCode') ?>" name="postalCode" required>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <input type="password" placeholder="<?=$this->lang->line('password') ?>" name="password" id="password" required>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <input type="password" placeholder="<?=$this->lang->line('confirmPassword') ?>" name="confirmPassword" id="confirmPassword" required>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <input type="submit" value="<?=$this->lang->line('signUp') ?>" class="sigin validate-signup">
              </div>
            </div>
        </form>
        <h5><?=$this->lang->line('staff') ?>? <i onclick="window.location.href='<?=BASEURL?>/login'" style="cursor: pointer;"><?=$this->lang->line('signIn') ?></i></h5>
    </div>
  </div>
</div> <!--===========end faq slide===============-->  


<?php include 'inc/footer.php';?>