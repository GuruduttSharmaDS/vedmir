
<?php include 'inc/header.php';?>
 
<div class="contact-hero">
   <div class="container">
     <div class="contact-inside wow flipInX">
        <h1><?=$this->lang->line('contactUs') ?></h1>
        <p><?=$this->lang->line('contactUsParagraph') ?></p> 
    </div>
  </div>
</div> <!--===========end contact slide===============-->  
<div class="contact-mail">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ul>
					<li><?=$this->lang->line('frontMediaEvent') ?><a href="mailto:johanna@vedmir.com">johanna@vedmir.com</a></li>
					<li><?=$this->lang->line('frontPartners') ?><a href="mailto:pierre@vedmir.com">pierre@vedmir.com</a></li>
					<li><?=$this->lang->line('frontGenerInfo') ?><a href="mailto:knut@vedmir.com">knut@vedmir.com</a></li>
					<li><?=$this->lang->line('frontAppVedmir') ?><a href="mailto:johanna@vedmir.com"> charles@vedmir.com</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="cotform-main">
  <div class="container">  
    <div class="contform-inside">
       <h1><?=$this->lang->line('getInTouch') ?></h1>
       <p><?=$this->lang->line('getInTouchParagraph') ?></p>
          <form class="Enquiry-form" id="Enquiry-form" method="POST" onsubmit="sendEnquiry(this,event);">
            
            <div class="form-group row">
              <div class="col-sm-6 col-xs-12">
                <input type="text" name="name" placeholder="<?=$this->lang->line('yourName') ?>" required="required">
              </div>
              <div class="col-sm-6 col-xs-12">
                <input type="email" name="email" placeholder="<?=$this->lang->line('email') ?>" required="required">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-6 col-xs-12">
                <select name="reason"  required="required">
                      <option selected="selected"><?=$this->lang->line('reasonForContactingUs') ?> *</option>
                      <option><?=$this->lang->line('reasonForContactingUsOption1') ?></option>
                      <option><?=$this->lang->line('reasonForContactingUsOption2') ?></option>
                  </select>
              </div>
              <div class="col-sm-6 col-xs-12">
                <input type="text" name="subject" placeholder="<?=$this->lang->line('subject') ?>" required="required">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-12">
                <textarea placeholder="<?=$this->lang->line('message') ?>" name="message" required="required"></textarea>
              </div>
            </div>
             
            <div class="form-group row submit">
              <div class="col-sm-12">
                <span><input type="submit" name="btnEnquiry" id="btnEnquiry" value="<?=$this->lang->line('submitMessage') ?>"></span>
              </div>
            </div>
            <div class="form-group row ">
              <div class="col-sm-12 submitResponce">
              </div>
            </div>
      
          </form>    

    </div>

 </div>
</div> <!--===========end contact form main===============-->


<?php include 'inc/footer.php';?>