<!doctype html>
<html>
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>RESET PASSWORD | VEDMIR</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,300i,400,400i,500,500i,700,700i,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= DASHSTATIC ?>/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= DASHSTATIC ?>/css/mdb.min.css">
  <link href="<?= DASHSTATIC ?>/css/simple-sidebar.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= DASHSTATIC ?>/css/style.css">
  <link rel="stylesheet" href="<?= DASHSTATIC ?>/css/responsive.css">
  <link rel="icon" href="<?php echo BASEURL; ?>/favicon.ico">

</head>

<body>
  <div class="loginbody">
 <div class="container">
  <div class="d-flex justify-content-center h-100">
    <div class="card">
      <div class="card-header">
        <h3>Reset your password</h3>
      </div>
      <div class="card-body">
        <form action="" method="post">
          <div class="mb-3">
            <?php echo $this->common_lib->showSessMsg(); ?>
          </div>
          <div class="input-group form-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            </div>
            <input type="email" class="form-control" name="txtEmailId" required placeholder="Enter registered email address">
            
          </div>
          <div class="form-group">
            <input type="submit" value="Send reset email" class="btn float-right login_btn">
          </div>
        </form>
      </div>
      <div class="card-footer">
        <div class="d-flex justify-content-center links">
          Back to <a href="<?php echo DASHURL."/".$role."/login"; ?>">Sign in</a>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
  <!-- Menu Toggle Script -->
  <script type="text/javascript" src="<?=DASHSTATIC?>/js/jquery.min.js"></script>
  <script type="text/javascript" src="<?=DASHSTATIC?>/js/popper.min.js"></script>
  <script type="text/javascript" src="<?=DASHSTATIC?>/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?=DASHSTATIC?>/js/mdb.min.js"></script>
  <script type="text/javascript" src="<?=DASHSTATIC?>/js/custom.js"></script>
</body>

</html>
