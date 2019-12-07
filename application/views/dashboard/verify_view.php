<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

    <!-- START @HEAD -->
    <head>
        <!-- START @META SECTION -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="author" content="Vedmir">
        <title>VERIFY | VEDMIR</title>
        <!--/ END META SECTION -->

		<link href="<?php echo DASHSTATIC; ?>/admin/ico/html/vedmir_logo.png" width="72" rel="apple-touch-icon-precomposed" sizes="72x72">
        <!--/ END FAVICONS -->
        <!-- START @GLOBAL MANDATORY STYLES -->
        <link href="<?php echo DASHSTATIC; ?>/global/plugins/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <!--/ END GLOBAL MANDATORY STYLES -->

        <!-- START @PAGE LEVEL STYLES -->
        <link href="<?php echo DASHSTATIC; ?>/global/plugins/bower_components/fontawesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?php echo DASHSTATIC; ?>/global/plugins/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <!--/ END PAGE LEVEL STYLES -->

        <!-- START @THEME STYLES -->
        <link href="<?php echo DASHSTATIC; ?>/admin/css/reset.css" rel="stylesheet">
        <link href="<?php echo DASHSTATIC; ?>/admin/css/layout.css" rel="stylesheet">
        <link href="<?php echo DASHSTATIC; ?>/admin/css/components.css" rel="stylesheet">
        <link href="<?php echo DASHSTATIC; ?>/admin/css/plugins.css" rel="stylesheet">
        <link href="<?php echo DASHSTATIC; ?>/admin/css/themes/default.theme.css" rel="stylesheet" id="theme">
        <link href="<?php echo DASHSTATIC; ?>/admin/css/pages/sign.css" rel="stylesheet">
        <link href="<?php echo DASHSTATIC; ?>/admin/css/custom.css" rel="stylesheet">
        <!--/ END THEME STYLES -->
        <link rel="icon" href="<?php echo BASEURL; ?>/favicon.ico"> 

    </head>
    <!--/ END HEAD -->

    
	<style>
		body, html {
		 
		 background-image: url("<?php echo DASHSTATIC."/admin/ico/loginBg.jpg"; ?>");
		  background-size: cover;
		  font-family: "Open Sans", sans-serif;
		  color: #1D2939;
		  -webkit-font-smoothing: antialiased;
		  direction: ltr;
		  line-height: 21px;
		  font-size: 13px;
		  width: 100%;
		  margin: 0;
		  padding: 0 !important;
		}
	</style>
    <body>
        <!--[if lt IE 9]>
        <p class="upgrade-browser">Upps!! You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/" target="_blank">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- START @SIGN WRAPPER -->
        <div id="sign-wrapper">

            <!-- Brand -->
            <div class="brand">
                <img src="<?= DASHSTATIC ?>/restaurant/assets/img/logo-w.png" width="" alt="Vedmir"/>
            </div>
            <!--/ Brand -->

            <!-- Login form -->
            <form class="sign-in form-horizontal shadow rounded no-overflow" action="" method="post">
                <div class="sign-header">
                    <div class="form-group">
                        <div class="sign-text">
                            <span>Account Verification</span>
                        </div>
                    </div><!-- /.form-group -->
                </div><!-- /.sign-header -->
                        
                <div class="sign-body">
					<div class="form-group" id="sendalert">
						<?php echo $this->common_lib->showSessMsg(); ?>
					</div>
                    <div class="form-group">
                        <div class="input-group input-group-lg rounded no-overflow">
                            <input type="text" class="form-control input-sm" required placeholder="Otp" value="" name="txtOtp"  autocomplete="off">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                        </div>
                    </div><!-- /.form-group -->
                    
                </div><!-- /.sign-body -->
                <div class="sign-footer">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-6">
                                
                            </div>
                            <div class="col-xs-6 text-right">
                                <a href="<?php echo DASHURL."/admin/resend"; ?>" title="resend otp">Resend Otp</a>
                            </div>
                        </div>
                    </div><!-- /.form-group -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm btn-primary btn-lg btn-block no-margin rounded" >Verify Now</button>
                    </div><!-- /.form-group -->
                    <div class="form-group">
                        <!-- <a href="javascript:void(0);" onclick="fbLogin()" id="fbLink"><img src="<?php echo DASHSTATIC; ?>/admin/ico/html/flogin.png"  style="height: 50px" /></a> -->
                    </div><!-- /.form-group -->
                </div><!-- /.sign-footer -->
            </form><!-- /.form-horizontal -->
            <!--/ Login form -->

            <!-- Content text -->
            <p class="text-muted text-center sign-link">Vedmir &copy; Copyright <?php echo date("Y"); ?>. All Rights Reserved.</p>
            <!--/ Content text -->

        </div><!-- /#sign-wrapper -->
        <!--/ END SIGN WRAPPER -->

        <!-- START JAVASCRIPT SECTION (Load javascripts at bottom to reduce load time) -->
        <!-- START @CORE PLUGINS -->
        <script src="<?php echo DASHSTATIC; ?>/global/plugins/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo DASHSTATIC; ?>/global/plugins/bower_components/jquery-cookie/jquery.cookie.js"></script>
        <script src="<?php echo DASHSTATIC; ?>/global/plugins/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo DASHSTATIC; ?>/global/plugins/bower_components/jquery-easing-original/jquery.easing.1.3.min.js"></script>
        <script src="<?php echo DASHSTATIC; ?>/global/plugins/bower_components/ionsound/js/ion.sound.min.js"></script>
        <!--/ END CORE PLUGINS -->

        <!-- START @PAGE LEVEL PLUGINS -->
        <script src="<?php echo DASHSTATIC; ?>/global/plugins/bower_components/jquery-validation/dist/jquery.validate.min.js"></script>
        <!--/ END PAGE LEVEL PLUGINS -->

<script>
    window.fbAsyncInit = function() {
        // FB JavaScript SDK configuration and setup
        FB.init({
          appId      : '2096409630589918', // FB App ID
          cookie     : true,  // enable cookies to allow the server to access the session
          xfbml      : true,  // parse social plugins on this page
          version    : 'v2.8' // use graph api version 2.8
        });
        
        // Check whether the user already logged in
        FB.getLoginStatus(function(response) {
            if (response.status === 'connected') {
                //display user data
                //getFbUserData();
            }
        });
    };

    // Load the JavaScript SDK asynchronously
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    // Facebook login with JavaScript SDK
    function fbLogin() {
        FB.login(function (response) {
            if (response.authResponse) {
                // Get and display the user profile data
                getFbUserData();
            } else {
                document.getElementById('sendalert').innerHTML = '<div class="alert alert-danger"  onclick="javascript:$(this).fadeOut(500)"><strong>Oh snap!</strong>User cancelled login or did not fully authorize.</div>';
            }
        }, {scope: 'email'});
    }

    // Fetch the user profile data from facebook
    function getFbUserData(){
        FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,link,gender,locale,picture'},
        function (response) {
            debugger;
            checkUserData(response);
        });
    }

    function checkUserData(userData){
        $.post("<?php echo BASEURL.'/home/fbsignup'; ?>", {role:"<?php echo $role; ?>",oauth_provider:'facebook',userData: JSON.stringify(userData)}, function(data){
            var responceData = jQuery.parseJSON(data);
            if (responceData.status == '1') {
                 window.location.href = "<?php echo DASHURL.'/'.$role.'/welcome'; ?>";
            }else{
                 document.getElementById('sendalert').innerHTML = '<div class="alert alert-danger"  onclick="javascript:$(this).fadeOut(500)"><strong>Oh snap!</strong>Something is wrong.</div>';
            }
        });
    }
    // Logout from facebook
    function fbLogout() {
        FB.logout(function() {
            document.getElementById('fbLink').setAttribute("onclick","fbLogin()");
            document.getElementById('fbLink').innerHTML = '<img src="<?php echo DASHSTATIC; ?>/admin/ico/html/flogin.png"  style="height: 50px"/>';
            document.getElementById('userData').innerHTML = '';
            document.getElementById('status').innerHTML = 'You have successfully logout from Facebook.';
        });
    }
    </script>
    </body>
    <!-- END BODY -->

</html>