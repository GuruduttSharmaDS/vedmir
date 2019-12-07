<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="description" content="">
            <meta name="author" content="">
            <link rel="icon" href="<?php echo BASEURL; ?>/favicon.ico">
            <link rel="stylesheet" type="text/css" href="<?php echo FRONTSTATIC; ?>/css/bootstrap.css">
            <link rel="stylesheet" type="text/css" href="<?php echo FRONTSTATIC; ?>/css/font-awesome.css">
            <link rel="stylesheet" type="text/css" href="<?php echo FRONTSTATIC; ?>/css/style.css">
            <link rel="stylesheet" href="<?php echo FRONTSTATIC; ?>/css/animate.css">
            <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" rel="stylesheet"> 

            <title>VEDMIR <?php if(isset($title)) echo ' | '.$title; else echo ''; ?> </title>

          <script>
            var DASHURL   = '<?php echo DASHURL; ?>';
            var FRONTSTATIC = '<?php echo FRONTSTATIC; ?>';
            var BASEURL   = '<?php echo BASEURL; ?>';
            var UPLOADPATH  = '<?php echo UPLOADPATH; ?>';
            var GLOBALERRORS = <?php echo json_encode($this->lang->language);?>;
          </script>

        </head>

        <body>

            <div class="nav-main">
                <nav class="nav navbar-nav navbar-default mynavbar">
                   <div class="container">
                      <div class="row">
                             
                             <div class="navbar-header">
                               <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                                 <span class="sr-only">Toggle navigation</span>
                                 <span class="icon-bar"></span>
                                 <span class="icon-bar"></span>
                                 <span class="icon-bar"></span>
                              </button>
                              <a href="<?php echo BASEURL; ?>"  class="navbar-brand"><img src="<?php echo FRONTSTATIC; ?>/images/logo.png" class="img-responsive"></a>
                            </div>
                            
                            <div id="navbar" class="navbar-collapse collapse <?=$this->lang->line('language') ?>">
                             
                              <ul class="nav navbar-nav mynav-list navbar-right">
                               
                                  <li><a href="<?php echo BASEURL; ?>/faq"><?=$this->lang->line('faq') ?></a></li>
                                                   
                                  <li><a href="<?php echo BASEURL; ?>/contact-us"><?=$this->lang->line('contactUs') ?></a></li> 
                                  
                                <!-- language dropdown start-->
                                  <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=$this->sessLang ?> <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                      <li onclick="changeLanguage('english')" class="<?=($this->sessLang == 'english')?'active':''  ?>">
                                        <a >English</a>
                                      </li>
                                      <li onclick="changeLanguage('french')" class="<?=($this->sessLang == 'french')?'active':''  ?>">
                                        <a >français</a>
                                      </li>
                                    </ul>
                                  </li>
       
                                  <li><a href="<?=DASHURL?>/admin/login"><?php echo $this->lang->line('login') ?></a></li>
                                </ul>
                            
                           </div>
                     
                      </div> <!--===========end row===============-->        
                  </div> <!--===============end container==============-->
               
               </nav>

            </div>