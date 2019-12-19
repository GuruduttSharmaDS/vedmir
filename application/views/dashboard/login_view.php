<!doctype html>
<html>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SIGN IN | VEDMIR</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,300i,400,400i,500,500i,700,700i,900&display=swap"
        rel="stylesheet">
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
                        <h3>SIGN IN</h3>
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
                                <input type="email" class="form-control" placeholder="Email address"
                                    value="<?php echo (isset($_COOKIE['cookieVedmir'.$role])) ? $_COOKIE['cookieVedmir'.$role] : ""; ?>"
                                    name="txtEmailId" required>

                            </div>
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" class="form-control" placeholder="Password" name="txtPassword"
                                    required>
                            </div>
                            <div class="row align-items-center remember mb-3">
                                <input type="checkbox">Remember Me
                            </div>
                            <div class="form-group" style="text-align: center;">
                                <input type="submit" value="Login" class="btn float-right login_btn">
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-center links">
                            Forgot your <a href="<?php echo DASHURL."/".$role."/forgot"; ?>">password ?</a>
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