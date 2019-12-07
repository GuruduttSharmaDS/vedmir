<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo DASHSTATIC;?>/restaurant/assets/css/multiselect.css">
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
          
    <form  method="POST" role="form" action="#" onsubmit="sendMassNotification(this,event);">
        <div class="you_requested">
            <h3><?= $this->lang->line('massNotification');?></h3>
            <div class="expert_contact" style="width: 100%;">
                <input type="hidden" name="action" value="sendMassNotification" id="action">
                <div class="col-lg-12"><h2 class="msg"></h2></div>
                <!-- <div class="form-group" >
                  <label ><?php echo $this->lang->line('type');?></label>
                  <select class="form-control input-sm" id="push_type" name="push_type">
                    <option value="test"><?= $this->lang->line('test'); ?></option>
                    <option value="live"><?= $this->lang->line('live'); ?></option>
                  </select>
                </div> -->
                <div class="form-group" >

                  <label ><?php echo $this->lang->line('sendTo');?></label>
                  <select class="form-control input-sm" required id="sendTo" name="sendTo">
                    <option value="all"><?= $this->lang->line('all'); ?></option>
                    <option value="subscribed"><?= $this->lang->line('subscribed'); ?></option>
                    <option value="Non-subscribed"><?= $this->lang->line('NonSubscribed'); ?></option>
                  </select>
                </div>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="<?=($this->sessLang == 'english') ? 'active' : '';?>"><a href="#english" aria-controls="english" role="tab" data-toggle="tab">En</a></li>
                    <li role="presentation" class="<?=($this->sessLang == 'french') ? 'active' : '';?>"><a href="#french" aria-controls="french" role="tab" data-toggle="tab">Fr</a></li>
                    <li role="presentation" class="<?=($this->sessLang == 'german') ? 'active' : '';?>"><a href="#german" aria-controls="german" role="tab" data-toggle="tab">Gr</a></li>
                    <li role="presentation" class="<?=($this->sessLang == 'italian') ? 'active' : '';?>"><a href="#italian" aria-controls="italian" role="tab" data-toggle="tab">It</a></li>               
                </ul>
                <div class="tab-content">
                    <div class="clearfix"></div>
                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'english') ? 'active' : '';?>" id="english">
                        <div class="form-group">
                          <label><?= $this->lang->line('title'); ?><span class="asterisk">*</span></label>
                          <input type="text" class="form-control input-sm" placeholder="<?= $this->lang->line('title'); ?>" required  name="title">
                        </div>
                        <div class="form-group">
                          <label><?= $this->lang->line('textForNotification'); ?><span class="asterisk">*</span></label>
                          <textarea class="form-control input-sm" placeholder="<?= $this->lang->line('textForNotification'); ?>" required name="message"></textarea>
                        </div>
                        <div class="form-group">
                          <label><?= $this->lang->line('textForPopup'); ?></label>
                          <textarea class="form-control input-sm" placeholder="<?= $this->lang->line('textForPopup'); ?>"  name="additionalMessage"></textarea>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'french') ? 'active' : '';?>" id="french">
                        <div class="form-group">
                          <label><?= $this->lang->line('title'); ?><span class="asterisk">*</span></label>
                          <input type="text" class="form-control input-sm" placeholder="<?= $this->lang->line('title'); ?>"   name="title_fr">
                        </div>
                        <div class="form-group">
                          <label><?= $this->lang->line('textForNotification'); ?><span class="asterisk">*</span></label>
                          <textarea class="form-control input-sm" placeholder="<?= $this->lang->line('textForNotification'); ?>"  name="message_fr"></textarea>
                        </div>
                        <div class="form-group">
                          <label><?= $this->lang->line('textForPopup'); ?></label>
                          <textarea class="form-control input-sm" placeholder="<?= $this->lang->line('textForPopup'); ?>"  name="additionalMessage_fr"></textarea>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'german') ? 'active' : '';?>" id="german">
                        <div class="form-group">
                          <label><?= $this->lang->line('title'); ?><span class="asterisk">*</span></label>
                          <input type="text" class="form-control input-sm" placeholder="<?= $this->lang->line('title'); ?>"   name="title_gr">
                        </div>
                        <div class="form-group">
                          <label><?= $this->lang->line('textForNotification'); ?><span class="asterisk">*</span></label>
                          <textarea class="form-control input-sm" placeholder="<?= $this->lang->line('textForNotification'); ?>"  name="message_gr"></textarea>
                        </div>
                        <div class="form-group">
                          <label><?= $this->lang->line('textForPopup'); ?></label>
                          <textarea class="form-control input-sm" placeholder="<?= $this->lang->line('textForPopup'); ?>"  name="additionalMessage_gr"></textarea>
                        </div>
                    </div> 
                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'italian') ? 'active' : '';?>" id="italian">
                        <div class="form-group">
                          <label><?= $this->lang->line('title'); ?><span class="asterisk">*</span></label>
                          <input type="text" class="form-control input-sm" placeholder="<?= $this->lang->line('title'); ?>"  name="title_it">
                        </div>
                        <div class="form-group">
                          <label><?= $this->lang->line('textForNotification'); ?><span class="asterisk">*</span></label>
                          <textarea class="form-control input-sm" placeholder="<?= $this->lang->line('textForNotification'); ?>"  name="message_it"></textarea>
                        </div>
                        <div class="form-group">
                          <label><?= $this->lang->line('textForPopup'); ?></label>
                          <textarea class="form-control input-sm" placeholder="<?= $this->lang->line('textForPopup'); ?>"  name="additionalMessage_it"></textarea>
                        </div>
                    </div>   
                </div> 
                
                <div class="form-group">
                  <label><?= $this->lang->line('image'); ?></label>
                  <input type="file" id="uploadImg" name="uploadImg">
                </div>      
                <div class=" col-sm-12 form-group" style=" margin-top: 12px;">
                    <button type="button" name="btnAddProduct" class="btn btn-primary btnAddProduct"><?= $this->lang->line('send'); ?></button>
                      
                </div>

            </div>
        </div><!--you_requested-->
    </form>
<?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>

</body>
</html>