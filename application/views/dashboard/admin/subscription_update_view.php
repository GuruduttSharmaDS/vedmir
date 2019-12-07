<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
          
        <form action="" method="POST" enctype="multipart/form-data"  novalidate="">
				<div class="you_requested">
                        <h3><?=$this->lang->line('updatePlan')?></span></h3>                      
                        <div class="expert_contact" style="width:100%;">
							<p class="payment-errors"></p>
							<?php 
							  if(isset($_POST) && !empty($_POST)){
								if(!empty($error_message))
								  echo '<p class="error">'.implode('<br>',$error_message).'</p>';
								if(isset($successMessage) && !empty($successMessage))
								  echo '<p class="success">'.$successMessage.'</p>';
							  }
							?>
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
                                        <label ><?=$this->lang->line('planNameText')?><span class="asterisk">*</span></label>
                                        <input type="text" class="form-control input-sm" value="<?php echo isset($SubData->planName) ? $SubData->planName : $_POST['plan_name']; ?>" placeholder="<?=$this->lang->line('planNameText')?>" id="plan_name" name="plan_name" maxlength="75" required="required">
                                    </div><!-- /.form-group -->
                                    <div class="form-group">
                                        <label><?=$this->lang->line('plandesc')?></label>
                                        <input type="text" class="form-control input-sm" value="<?php echo isset($SubData->description) ? $SubData->description : $_POST['description']; ?>" placeholder="<?=$this->lang->line('plandesc')?>" id="description"  name="description">
                                       <!-- <small class="note"><?=$this->lang->line('maxdesxriptionLimit')?> : 22</small> -->
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'french') ? 'active' : '';?>" id="french">
                                    <div class="form-group">
                                        <label ><?=$this->lang->line('planNameText')?><span class="asterisk">*</span></label>
                                        <input type="text" class="form-control input-sm" value="<?php echo isset($SubData->planName_fr) ? $SubData->planName_fr : $_POST['plan_name_fr']; ?>" placeholder="<?=$this->lang->line('planNameText')?>" id="plan_name_fr" name="plan_name_fr" maxlength="75" required="required">
                                    </div><!-- /.form-group -->
                                    <div class="form-group">
                                        <label><?=$this->lang->line('plandesc')?></label>
                                        <input type="text" class="form-control input-sm" value="<?php echo isset($SubData->description_fr) ? $SubData->description_fr : $_POST['description_fr']; ?>" placeholder="<?=$this->lang->line('plandesc')?>" id="description_fr"  name="description_fr">
                                        <!-- <small class="note"><?=$this->lang->line('maxdesxriptionLimit')?> : 22</small> -->
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'german') ? 'active' : '';?>" id="german">
                                    <div class="form-group">
                                        <label ><?=$this->lang->line('planNameText')?><span class="asterisk">*</span></label>
                                        <input type="text" class="form-control input-sm" value="<?php echo isset($SubData->planName_gr) ? $SubData->planName_gr : $_POST['plan_name_gr']; ?>" placeholder="<?=$this->lang->line('planNameText')?>" id="plan_name_gr" name="plan_name_gr" maxlength="75" required="required">
                                    </div><!-- /.form-group -->
                                    <div class="form-group">
                                        <label><?=$this->lang->line('plandesc')?></label>
                                        <input type="text" class="form-control input-sm" value="<?php echo isset($SubData->description_gr) ? $SubData->description_gr : $_POST['description_gr']; ?>" placeholder="<?=$this->lang->line('plandesc')?>" id="description_gr"  name="description_gr">
                                        <!-- <small class="note"><?=$this->lang->line('maxdesxriptionLimit')?> : 22</small> -->
                                    </div>
                                </div> 
                                <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'italian') ? 'active' : '';?>" id="italian">
                                    <div class="form-group">
                                        <label ><?=$this->lang->line('planNameText')?><span class="asterisk">*</span></label>
                                        <input type="text" class="form-control input-sm" value="<?php echo isset($SubData->planName_it) ? $SubData->planName_it : $_POST['plan_name_it']; ?>" placeholder="<?=$this->lang->line('planNameText')?>" id="plan_name_it" name="plan_name_it" maxlength="75" required="required">
                                    </div><!-- /.form-group -->
                                    <div class="form-group">
                                        <label><?=$this->lang->line('plandesc')?></label>
                                        <input type="text" class="form-control input-sm" value="<?php echo isset($SubData->description_it) ? $SubData->description_it : $_POST['description_it']; ?>" placeholder="<?=$this->lang->line('plandesc')?>" id="description_it"  name="description_it">
                                        <!-- <small class="note"><?=$this->lang->line('maxdesxriptionLimit')?> : 22</small> -->
                                    </div>
                                </div>
                            </div>
                            

							<div class="form-group">
								<label><?=$this->lang->line('trailPeriodText')?></label>
								<input type="text" class="form-control input-sm" min="1" value="<?php echo isset($SubData->trialperioddays) ? (($SubData->trialperioddays=='0')?'':$SubData->trialperioddays) : $_POST['trialperioddays']; ?>" placeholder="<?=$this->lang->line('trailPeriodText')?>" value="0" id="trialperioddays" name="trialperioddays" onkeydown="OnlyNumericKey(event)">
								 <input type="hidden" name="hiddenval"  id="hiddenval" value="<?php echo isset($SubData->Id) ? $SubData->Id : $_POST['hiddenval']; ?>">
							</div>
							<div class="form-group">
                                <label ><?=$this->lang->line('freePeriodText')?><span class="asterisk">*</span></label>
                                <input type="text" class="form-control input-sm" min="1" value="<?php echo isset($SubData->numberFreeDrink) ? (($SubData->numberFreeDrink=='0')?'':$SubData->numberFreeDrink) : $_POST['freeperiod'];?>" placeholder="<?=$this->lang->line('freePeriodText')?>" required="required" id="freeperiod" name="freeperiod" onkeydown="OnlyNumericKey(event)">
                            </div><!-- /.form-group -->

                            <div class="form-group">
                                <label ><?=$this->lang->line('freeDurationText')?><span class="asterisk">*</span></label>
                                <select class="form-control" name="freeduration" id="freeduration" required="required">
                                    <option value="day" <?php echo isset($SubData->freeDrinkPeriod) ? (($SubData->freeDrinkPeriod=='day')?'selected':'') : '';?>><?=$this->lang->line('dailyText')?></option>
                                    <option value="month" <?php echo isset($SubData->freeDrinkPeriod) ? (($SubData->freeDrinkPeriod=='month')?'selected':'') : '';?>><?=$this->lang->line('monthlyText')?></option>
                                    
                                    <!-- <option value="week" <?php echo (!empty($error_message))?(($_POST['freeduration']=='week')?'select':''):''?>><?=$this->lang->line('weeklyText')?></option>
                                    <option value="year" <?php echo (!empty($error_message))?(($_POST['freeduration']=='year')?'select':''):''?>><?=$this->lang->line('yearlyText')?></option> -->
                                </select>
                            </div><!-- /.form-group -->
                            <div class="image-upload form-group">
                                <label for="file-input">
                                    <img src="<?php echo isset($SubData->icon) ? UPLOADPATH.'/'.$SubData->icon : DASHSTATIC.'/restaurant/assets/img/uplod.png'; ?>" width="200px" height="200px">
                                     
                                 </label>
                                <input class="file-input" name="uploadImg" onchange="filepreviewnew(this);" type="file" />
                            </div>
                            <div class=" col-sm-12 form-group" style=" margin-top: 12px;">
                                <a href="javascript:void(0)" class="btn btn-primary btnAddSubscription"><?=$this->lang->line('buttonUpdate')?> </a>
                                <input type="hidden" name="action" value="update_subscription">
                            </div>
                        </div><!--you_requested-->
                </div>
		</form>
		<?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>
        <style type="text/css">.subscriptioninfo li {
			width: 33%;
			float: left;}
		</style>
    </body>
    <!--/ END BODY -->
</html>