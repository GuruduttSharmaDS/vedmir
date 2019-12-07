<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
          <style type="text/css">
  ul.variable-box {
      margin: 2px 0px;
      padding: 4px 15px;
      border: 1px solid #fbd1b4;
      background-color: #efefef;
  }
</style>
          <form action="" method="POST" enctype="multipart/form-data">
          <div class="you_requested">
          <h3><?php echo $plan->planName; ?> <i style="font-size: 15px;color: #827c7c;">(<?=ucfirst($this->lang->line('subscription'))?>) <?php echo ($plan->status == 0)?'<span class="label label-success">Active</span>':'<span class="label label-danger">DeActive</span>'; ?></i></h3>
            <div class="expert_contact" style="width: 100%;">
              <!-- <div class="row">
                <label class="col-md-3 text-strong"><?=$this->lang->line('status')?></label>
                <div class="col-md-9">
                   <?php //echo ($plan->status == 0)?'<span class="label label-success">Active</span>':'<span class="label label-danger">DeActive</span>'; ?>
                </div>
              </div> -->
              <h4><?=$this->lang->line('plan')?>:</h4>
              <div class="row">
                <?php if (isset($planData) && valResultSet($planData)) { 
                        foreach ($planData as $detail) {   ?>
              
                          <div class="col-md-6">
                            <ul class="variable-box">  
                              <li><h4><small><?=$this->lang->line('name')?>:</small><span> <?=$detail->planId ?></span></h4></li>
                              <li><h4><small><?=$this->lang->line('trailPeriodText')?>:</small><span> <?=$detail->trialperioddays ?></span></h4></li>
                              <?php 
                                  $duration =$detail->duration; ?>
                              <li><h4><small><?=$this->lang->line('period')?>:</small><span> <?=$detail->period ?> <?
                              echo $this->lang->line($duration); ?></span></h4></li>
                              <li><h4><small><?=$this->lang->line('price')?>:</small><span> <?=$detail->currency ?> <?=$detail->amount ?></span></h4></li>
                              <li><h4><small><?=$this->lang->line('status')?>:</small><span> <?php echo ($detail->status == 0)?'<span class="label label-success">Active</span>':'<span class="label label-danger">DeActive</span>'; ?></span></h4></li>
                            </ul>
                          </div>
                              

              <?php }  }else{
                  echo '<div class="col-md-12"><p>'.$this->lang->line('notfound').'</p></div>';
                } ?>
              </div>
            </div><!--you_requested-->

      </form>
<?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>
    </body>
    <!--/ END BODY -->

</html>