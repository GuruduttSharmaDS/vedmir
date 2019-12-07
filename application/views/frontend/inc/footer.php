

        <footer >
          <div class="container">
             <h4 class="wow pulse"><img src="<?php echo FRONTSTATIC; ?>/images/logo.png"></h4>
             <ul class="<?=$this->lang->line('language'); ?>">
              <!-- <li><a href="<?php //echo BASEURL; ?>/job"><?php //echo $this->lang->line('jobs'); ?></a></li> -->
              <!-- <li><a href="<?php echo BASEURL; ?>/venue-owners"><?=$this->lang->line('venue'); ?></a></li>-->
               <!-- <li><a href="<?php //echo BASEURL; ?>/login"><?php //echo $this->lang->line('login') ?></a></li> -->
               <!--<li><a href="#"><?=$this->lang->line('insidersLogin') ?>Insiders Login</a></li>-->
               <li><a href="<?php echo BASEURL; ?>/faq"><?=$this->lang->line('faq') ?></a></li>
               <!-- <li><a href="<?php echo BASEURL; ?>/privacy-policy"><?php//echo $this->lang->line('privacyPolicy') ?></a></li> -->
               <li><a href="<?php echo BASEURL; ?>/terms"><?=$this->lang->line('terms&Conditions') ?></a></li>
            </ul>
            <p><!-- <span><b><?php//echo $this->lang->line('phone') ?>:</b> +41 21 691 26 41</span>  --><span><b><?=$this->lang->line('email') ?>:</b> info@vedmir.com</span> </p>
            <h4 class="social-icons">
                <a href="#" class="fa fa-facebook"></a> 
                <!--<a href="#" class="fa fa-twitter"></a>-->
                <a href="#" class="fa fa-instagram"></a>
                <a href="#" class="fa fa-tumblr"></a>
           </h4>
           <small><?=$this->lang->line('copyRight') ?> © 2018 <?=$this->lang->line('vedmir') ?>.</small>
          </div>
        </footer>
        <script src="<?php echo FRONTSTATIC; ?>/js/jquery-1.9.1.js" type="text/javascript"></script>
          <script src="<?php echo FRONTSTATIC; ?>/js/bootstrap.js" type="text/javascript"></script>
          <script src="<?php echo FRONTSTATIC; ?>/js/wow.min.js" type="text/javascript"></script>
          <script src="<?php echo FRONTSTATIC; ?>/js/validate.js" type="text/javascript"></script>
          <script src="<?php echo FRONTSTATIC; ?>/js/paging.js" type="text/javascript"></script>
          <script src="<?php echo FRONTSTATIC; ?>/js/frontend.js" type="text/javascript"></script>
           <script>
          new WOW().init();
          </script>

    </body>




</html>