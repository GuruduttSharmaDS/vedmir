<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
          
  <form action="" method="POST" enctype="multipart/form-data">
    <div class="you_requested">
        <!-- Profile Logo -->
        <div class="expert_breif">
          <div class="image-upload" style="margin-bottom:15px;">
          <?php
          $absPath = ABSUPLOADPATH."/user_images/".$profile->img;
          if (($profile->img != "") && (file_exists($absPath))) {
              $logoPath = UPLOADPATH."/user_images/".$profile->img;
          } else {
            $logoPath = DASHSTATIC."/img/user-icon.jpg";
          } 
          ?>
            <img class="rounded img-bordered-success" src="<?php echo $logoPath."?".StringGenerator(6); ?>" alt="User Logo" height="200" width="200">
          </div>
        </div>

        <div class="expert_contact">          
          <div class="row">
            <label class="col-md-3 text-strong"><?=$this->lang->line('userName')?></label>
            <div class="col-md-3">
                <?php echo isset ($profile->userName) ? $profile->userName : "" ; ?>
            </div>

            <label class="col-md-3 text-strong"><?=$this->lang->line('status')?></label>
            <div class="col-md-3">
                <?php echo ($profile->status == 0) ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">DeActive</span>'; ?>
            </div>

            <label class="col-md-3 text-strong"><?=$this->lang->line('email')?></label>
            <div class="col-md-3">
              <?php echo isset ($profile->email) ? $profile->email : "" ; ?>
            </div>

            <label class="col-md-3 text-strong"><?=$this->lang->line('mobile')?></label>
            <div class="col-md-3">
              <?php echo isset ($profile->mobile) ? $profile->mobile : "" ; ?>
            </div>

            <label class="col-md-3 text-strong"><?=$this->lang->line('country')?></label>
            <div class="col-md-3">
              <?php echo isset ($profile->country) ? $profile->country : "" ; ?>
            </div>

            <label class="col-md-3 text-strong"><?=$this->lang->line('state')?></label>
            <div class="col-md-3">
              <?php echo isset ($profile->state) ? $profile->state : "" ; ?>
            </div>

            <label class="col-md-3 text-strong"><?=$this->lang->line('city')?></label>
            <div class="col-md-3">
              <?php echo isset ($profile->city) ? $profile->city : "" ; ?>
            </div>

            <label class="col-md-3 text-strong"><?=$this->lang->line('gender')?></label>
            <div class="col-md-3">
                <?php echo isset ($profile->gender) ? $profile->gender : "" ; ?>
            </div>
            <label class="col-md-3 text-strong"><?=$this->lang->line('dateOfBirth')?></label>
            <div class="col-md-3">
              <?php echo isset ($profile->dob) ? $profile->dob : "" ; ?>
            </div>

            <label class="col-md-3 text-strong"><?=$this->lang->line('occupation')?></label>
            <div class="col-md-3">
              <?php echo isset ($profile->occupation) ? $profile->occupation : "" ; ?> 
            </div>

          </div>               
          </div>                
        </div>
      </form>
    </body>
</html>