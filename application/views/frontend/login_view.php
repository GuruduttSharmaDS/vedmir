
<?php include 'inc/header.php';?>
 <style type="text/css">
   .login-inside ul {
    padding: 0px 45px;
    }
    
   .login-inside label.error {
      width: 100%;
      text-align: center;
    }
 </style>
<div class="login-hero">
   <div class="container">
     <div class="login-inside wow flipInX">
        <h1><?=$this->lang->line('signIn') ?></h1>
        <p><?=$this->lang->line('barAndRestaurantManagement') ?></p> 
        <ul><?php echo $this->common_lib->showSessMsg(); ?></ul>
        <form action="" method="post">
          <span><input type="email" placeholder="<?=$this->lang->line('email') ?>" name="txtEmailId"></span>
          <span><input type="password" placeholder="<?=$this->lang->line('password') ?>" name="txtPassword"></span>
          <span><label><input type="checkbox"> <?=$this->lang->line('rememberMe') ?>? </label> <label><a href="#"><?=$this->lang->line('forgotPassword') ?>?</a></label></span>
          <span><input type="submit" value="<?=$this->lang->line('signIn') ?>" class="sigin validate-form"></span>
          
          <h5><?=$this->lang->line('newToVedmir') ?>? <i onclick="window.location.href='<?=BASEURL?>/signup'" style="cursor: pointer;"><?=$this->lang->line('signUp') ?></i></h5>
       </form> 
    </div>
  </div>
</div> <!--===========end faq slide===============-->  


<?php include 'inc/footer.php';?>