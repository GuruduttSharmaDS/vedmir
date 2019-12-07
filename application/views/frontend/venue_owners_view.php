

<?php include 'inc/header.php';?>

 

<div class="vanue-hero">

   <div class="container">

     <div class="vanue-inside wow flipInX">

        <h1><?=$this->lang->line('venueOwners') ?></h1> 

        <p><?=$this->lang->line('venueOwnersParagraph') ?> </p> 



    </div><!--===========vanue container===============--> 

  </div>

</div> <!--===========end vanue slide===============-->  



<div class="feature-main">

  <div class="container">  
	<div class="col-md-12">
		<h2><?=$this->lang->line('Wantto') ?></h2>
	</div>
   <div class="feature-inside wow shake">

      <div class="col-md-4">

        <h4><img src="<?php echo FRONTSTATIC; ?>/images/cust2.png"></h4>

        <!--<h3><?=$this->lang->line('newCustomerRevenue') ?></h3>-->

        <p><?=$this->lang->line('newCustomerRevenueParagraph') ?></p>

     </div>  

     <div class="col-md-4">

        <h4><img src="<?php echo FRONTSTATIC; ?>/images/ana2.png"></h4>

        <!--<h3><?=$this->lang->line('marketingAnalytics') ?></h3>-->

        <p><?=$this->lang->line('marketingAnalyticsParagraph') ?></p>

     </div>  

     <div class="col-md-4">

        <h4><img src="<?php echo FRONTSTATIC; ?>/images/mem2.png"></h4>

        <!--<h3><?=$this->lang->line('paidMembershipReferrals') ?></h3>-->

        <p><?=$this->lang->line('paidMembershipReferralsParagraph') ?></p>

     </div>   

 </div> <!--===========end feature inside===============-->

 </div>

</div> <!--===========end how work===============-->

<div class="business-main">
 <div class="container">
	<div class="row">
		<div class="col-md-12">
			<h2><?=$this->lang->line('visibleon1') ?></h2>
		</div>
		<div class="col-md-4">
			<img src="<?php echo FRONTSTATIC; ?>/images/teer.png">
			<h4><?=$this->lang->line('visible') ?><span><?=$this->lang->line('visible1') ?></span><?=$this->lang->line('visible2') ?></h4>
			
			<p><?=$this->lang->line('presenting') ?></p>
			<h3><?=$this->lang->line('Almost100%') ?></h3>
		</div>  
		
		<div class="col-md-4">
			<img src="<?php echo FRONTSTATIC; ?>/images/inkeras.png">
			<h4><?=$this->lang->line('Increaseyour') ?><span><?=$this->lang->line('attractiveness') ?></span><?=$this->lang->line('and5') ?><span><?=$this->lang->line('revenues') ?></span></h4>
			
			<p><?=$this->lang->line('Vedmiroffers') ?></p>
			<p><?=$this->lang->line('Vedmiroffers2') ?></p>
			<h3><?=$this->lang->line('75%ofpeopl') ?></h3>
		</div> 
		
		<div class="col-md-4">
			<img src="<?php echo FRONTSTATIC; ?>/images/loatry.png">
			<h4><?=$this->lang->line('Improveyour5') ?><span><?=$this->lang->line('knowledge') ?></span></h4>
			
			<p><?=$this->lang->line('Vedmircanhelpyou') ?></p>
			<h3><?=$this->lang->line('100%ofyourbusinessis') ?></h3>
		</div> 	
		
	</div>
 </div>
</div>
<div class="app-main">

 <div class="container">

   <div class="col-md-6 col-sm-8 col-xs-12">

      <div class="app-left wow bounceInLeft">

      <h1><?=$this->lang->line('download') ?> <b>VEDMIR</b> <?=$this->lang->line('app') ?></h1>

      <p><?=$this->lang->line('downloadAppParagraph1') ?> <br><?=$this->lang->line('downloadAppParagraph2') ?></p> 

      <h4><a href="https://play.google.com/store/apps/details?id=com.dreamsteps.vedmir" class="<?=$this->lang->line('language') ?>"><?=$this->lang->line('download').' '.$this->lang->line('app') ?></a></h4>

    </div>

   </div>



   <div class="col-md-6 col-sm-4 col-xs-12">

     <div class="app-right wow bounceInRight">

     <p><img src="<?php echo FRONTSTATIC; ?>/images/app.png"></p>

    </div> 

  </div>



 </div> 



</div> <!--===========end testi main===============-->



<div class="future-main">

  <div class="container wow flash">

    <div class="future-inside">

      <h1><?=$this->lang->line('theFutureOfDrink') ?></h1>

      <p><?=$this->lang->line('theFutureOfDrinkParagraph') ?></p>

      <h4><a href="<?=BASEURL; ?>/signup" class="<?=$this->lang->line('language') ?>"><?=$this->lang->line('applyForAVenuePartner') ?></a></h4>

   </div>

 </div>



</div> <!--===========end future main===============-->

 

<div class="touch-main">
   <div class="container">
      <div class="touch-inside">
        <div class="touch-form wow">
          <h3>
                <span><?=$this->lang->line('frontinterested') ?></span>
                <span><?=$this->lang->line('frontknowmore') ?></span>
                <span><?=$this->lang->line('have') ?> <?=$this->lang->line('aEnquiry') ?>?</span>
          </h3>
          <form class="Enquiry-form" id="Enquiry-form" method="POST" onsubmit="sendEnquiry(this,event);">
            <div class="form-group row">
              <div class="col-sm-12">
                <input type="text" name="name" placeholder="<?=$this->lang->line('yourName') ?>" required="required">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-6 col-xs-12">
                <input type="email" name="email" placeholder="<?=$this->lang->line('email') ?>" required="required">
              </div>
              <div class="col-sm-6 col-xs-12">
                <input type="text" name="subject" placeholder="<?=$this->lang->line('subject') ?>" required="required">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-12">
                <textarea placeholder="Message" name="<?=$this->lang->line('message') ?>" required="required"></textarea>
              </div>
            </div>
             
            <div class="form-group row submit">
              <div class="col-sm-12">
                <input type="submit" name="btnEnquiry" id="btnEnquiry" value="<?=$this->lang->line('submitMessage') ?>">
              </div>
            </div>
            <div class="form-group row ">
              <div class="col-sm-12 submitResponce">
              </div>
            </div>
      
          </form>  
       </div>
    </div>
  </div>
</div> <!--===========end touch main===============-->

<?php include 'inc/footer.php';?>