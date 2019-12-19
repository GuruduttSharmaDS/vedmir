<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
          
  <form action="" method="POST" enctype="multipart/form-data">
    <div class="you_requested">
        <!-- subProfile Logo -->
        <div class="expert_breif">
          <div class="image-upload" style="margin-bottom:15px;">
          <?php
          $absPath = ABSUPLOADPATH."/user_images/".$subProfile->icon;
          if (($subProfile->icon != "") && (file_exists($absPath))) {
              $logoPath = UPLOADPATH."/user_images/".$subProfile->icon;
          } else {
            $logoPath = DASHSTATIC."/img/user-icon.jpg";
          } 
          ?>
            <img class="rounded img-bordered-success" src="<?php echo $logoPath."?".StringGenerator(6); ?>" alt="User Logo" height="200" width="200">
          </div>
        </div>

        <div class="expert_contact">          
          <div class="row">
            <label class="col-md-3 text-strong"><?=$this->lang->line('planName')?></label>
            <div class="col-md-3">
                <?php echo isset ($subProfile->planName) ? $subProfile->planName : "" ; ?>
            </div>

            <label class="col-md-3 text-strong">Plan Name RS</label>
            <div class="col-md-3">
              <?php echo isset ($subProfile->planName_rs) ? $subProfile->planName_rs : "" ; ?>
            </div>         

            <label class="col-md-3 text-strong"><?=$this->lang->line('description')?></label>
            <div class="col-md-3">
              <?php echo isset ($subProfile->description) ? $subProfile->description : "" ; ?>
            </div>

            <label class="col-md-3 text-strong">Description RS</label>
            <div class="col-md-3">
              <?php echo isset ($subProfile->description_rs) ? $subProfile->description_rs : "" ; ?>
            </div>            

            <label class="col-md-3 text-strong"><?=$this->lang->line('period')?></label>
            <div class="col-md-3">
              <?php echo isset ($subProfile->period) ? $subProfile->period ." ". $subProfile->duration: "" ; ?>
            </div>

            <label class="col-md-3 text-strong"><?=$this->lang->line('amount')?></label>
            <div class="col-md-3">
              <?php echo isset ($subProfile->amount) ? $subProfile->amount ." ". $subProfile->currency : "" ; ?>
            </div>

            <label class="col-md-3 text-strong"><?=$this->lang->line('status')?></label>
            <div class="col-md-3">
                <?php echo ($subProfile->status == 0) ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">DeActive</span>'; ?>
            </div>

          </div>               
          </div>                
        </div>
      </form>
    </body>
</html>