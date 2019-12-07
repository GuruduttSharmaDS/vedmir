 <?php include 'inc/header.php';?>

<div class="hero-slide">
   <div class="container">
     <div class="hero-container">
         <div class="col-md-7 col-sm-7 slide-left wow bounceInUp">
      			<p><?=$this->lang->line('Dearfriendsfrom') ?></p>
            <p><?=$this->lang->line('inMostCasesGoingOut..') ?></p>
            <h4> <a href="#" class="<?=$this->lang->line('language') ?>"><img src="<?php echo FRONTSTATIC; ?>/images/apple.png"> <?=$this->lang->line('appStore') ?></a> <a href="#" ><img src="<?php echo FRONTSTATIC; ?>/images/anroid.png"> <?=$this->lang->line('Soonavailableon') ?></a>  </h4>
			

			
        </div>

         <div class="col-md-5 col-sm-5 slide-right wow bounceInDown">
            <video preload="auto" src="<?=BASEURL.'/home/streming/'.$fileName?>" width="100%" controls controlsList="nodownload"></video>'
        </div>


    </div><!--===========hero container===============--> 
  </div>
</div> <!--===========end hero slide===============-->  

<?php include 'inc/footer.php';?>