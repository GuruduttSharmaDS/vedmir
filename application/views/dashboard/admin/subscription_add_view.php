<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
          
        <form action="" method="POST" enctype="multipart/form-data"  novalidate="">
					<div class="you_requested">
                        <h3><?=$this->lang->line('addSubscription').' '.$this->lang->line('plan')?></h3>                      
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
							<!-- <div class="form-group">
                                                            <label ><?=$this->lang->line('planId')?><span class="asterisk">*</span></label>
                                                            <input type="text" class="form-control input-sm" value="<?php echo (!empty($error_message))?$_POST['plan_sub_id']:''?>" placeholder="<?=$this->lang->line('planIdText')?>" id="plan_sub_id" maxlength="15" name="plan_sub_id" required="required">
                                                        </div> -->
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
                                        <input type="text" class="form-control input-sm" value="<?php echo (!empty($error_message))?$_POST['plan_name']:''?>" placeholder="<?=$this->lang->line('planNameText')?>" id="plan_name" name="plan_name" maxlength="75" required="required">
                                    </div><!-- /.form-group -->
                                    <div class="form-group">
                                        <label><?=$this->lang->line('plandesc')?></label>
                                        <input type="text" class="form-control input-sm" value="<?php echo (!empty($error_message))?$_POST['description']:''?>" placeholder="<?=$this->lang->line('plandesc')?>" id="description"  name="description">
                                        <!-- <small class="note"><?=$this->lang->line('maxdesxriptionLimit')?> : 22</small>
                                                                             -->                                    </div>

                                </div>
                                <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'french') ? 'active' : '';?>" id="french">
                                    <div class="form-group">
                                        <label ><?=$this->lang->line('planNameText')?><span class="asterisk">*</span></label>
                                        <input type="text" class="form-control input-sm" value="<?php echo (!empty($error_message))?$_POST['plan_name_fr']:''?>" placeholder="<?=$this->lang->line('planNameText')?>" id="plan_name_fr" name="plan_name_fr" maxlength="75" required="required">
                                    </div><!-- /.form-group -->
                                    <div class="form-group">
                                        <label><?=$this->lang->line('plandesc')?></label>
                                        <input type="text" class="form-control input-sm" value="<?php echo (!empty($error_message))?$_POST['description_fr']:''?>" placeholder="<?=$this->lang->line('plandesc')?>" id="description_fr"  name="description_fr">
                                        <!-- <small class="note"><?=$this->lang->line('maxdesxriptionLimit')?> : 22</small> -->
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'german') ? 'active' : '';?>" id="german">
                                    <div class="form-group">
                                        <label ><?=$this->lang->line('planNameText')?><span class="asterisk">*</span></label>
                                        <input type="text" class="form-control input-sm" value="<?php echo (!empty($error_message))?$_POST['plan_name_gr']:''?>" placeholder="<?=$this->lang->line('planNameText')?>" id="plan_name_gr" name="plan_name_gr" maxlength="75" required="required">
                                    </div><!-- /.form-group -->
                                    <div class="form-group">
                                        <label><?=$this->lang->line('plandesc')?></label>
                                        <input type="text" class="form-control input-sm" value="<?php echo (!empty($error_message))?$_POST['description_gr']:''?>" placeholder="<?=$this->lang->line('plandesc')?>" id="description_gr"  name="description_gr">
                                        <!-- <small class="note"><?=$this->lang->line('maxdesxriptionLimit')?> : 22</small>
                                                                             -->                                    </div>
                                </div> 
                                <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'italian') ? 'active' : '';?>" id="italian">
                                    <div class="form-group">
                                        <label ><?=$this->lang->line('planNameText')?><span class="asterisk">*</span></label>
                                        <input type="text" class="form-control input-sm" value="<?php echo (!empty($error_message))?$_POST['plan_name_it']:''?>" placeholder="<?=$this->lang->line('planNameText')?>" id="plan_name_it" name="plan_name_it" maxlength="75" required="required">
                                    </div><!-- /.form-group -->
                                    <div class="form-group">
                                        <label><?=$this->lang->line('plandesc')?></label>
                                        <input type="text" class="form-control input-sm" value="<?php echo (!empty($error_message))?$_POST['description_it']:''?>" placeholder="<?=$this->lang->line('plandesc')?>" id="description_it"  name="description_it">
                                        <!-- <small class="note"><?=$this->lang->line('maxdesxriptionLimit')?> : 22</small>
                                                                             -->                                    </div>
                                </div>
                            </div>
                            <?php 
                               /* $monthlyDropdwon = '';
                                for($i=1; $i<=12;$i++){
                                    $monthlyDropdwon .= '<option value="'.$i.'">'.$i.' '.$this->lang->line('monthlyText').'</option>';
                                }*/
                            ?>
                            <!--  <script type="text/javascript">
                                var monthhtml = "<?php echo $monthlyDropdwon;?>";
                            </script> -->
                            <div class="subscriptionPlanList" style="border: 1px solid;padding: 10px;">   
                                <div class="subscriptionPlanItem" style="border-bottom: 2px solid;margin-bottom: 10px;">                         
                                    <!-- <div class="form-group">
                                        <label ><?=$this->lang->line('periodText')?><span class="asterisk">*</span></label>
                                        <input type="text" class="form-control input-sm" min="1" value="<?php echo (!empty($error_message))?$_POST['period']:''?>" placeholder="<?=$this->lang->line('periodText')?>" required="required" id="period" name="period[]" onkeydown="OnlyNumericKey(event)">
                                    </div> --><!-- /.form-group -->

                                    <div class="form-group">
                                        <label ><?=$this->lang->line('durationText')?><span class="asterisk">*</span></label>
                                        <select class="form-control" name="duration[]" id="duration" required="required">
                                            <option value="1">1 Month</option><option value="3">1 Quarter</option><option value="6">6 Months</option><option value="12">1 Year</option>
                                            
                                            <!-- <option value="1" <?php echo (!empty($error_message))?(($_POST['description']=='month')?'select':''):''?>><?=$this->lang->line('monthlyText')?></option>
                                            <option value="day" <?php echo (!empty($error_message))?(($_POST['description']=='day')?'select':''):''?>><?=$this->lang->line('dailyText')?></option>
                                            <option value="week" <?php echo (!empty($error_message))?(($_POST['description']=='week')?'select':''):''?>><?=$this->lang->line('weeklyText')?></option>
                                                                                        <option value="year" <?php echo (!empty($error_message))?(($_POST['description']=='year')?'select':''):''?>><?=$this->lang->line('yearlyText')?></option> -->
                                        </select>
                                    </div><!-- /.form-group -->

        							<!-- <div class="form-group">
                                        <label><?=$this->lang->line('trailPeriodText')?></label>
                                        <input type="text" class="form-control input-sm" min="1" value="" placeholder="<?=$this->lang->line('trailPeriodText')?>" value="<?php echo (!empty($error_message))?$_POST['trialperioddays']:''?>" id="trialperioddays" name="trialperioddays[]" onkeydown="OnlyNumericKey(event)">
                                    </div> -->
        							<div class="form-group">
                                        <label ><?=$this->lang->line('currencyText')?><span class="asterisk">*</span></label>
                                        <select class="form-control" name="currency[]" id="currency" required="required">
                                            <option value="CHF"><?=$this->lang->line('currencyNameText')?></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label ><?=$this->lang->line('amountText')?><span class="asterisk">*</span></label>
                                        <input type="text" class="form-control input-sm" min="1" value="<?php echo (!empty($error_message))?$_POST['amount']:''?>" placeholder="<?=$this->lang->line('amountText')?> " required="required" id="amount" name="amount[]" onkeydown="OnlyNumericKey(event)">
                                    </div><!-- /.form-group -->
                                    <div class="form-group">
                                        <label ><?=$this->lang->line('actualAmountText')?></label>
                                        <input type="text" class="form-control input-sm" min="1" value="<?php echo (!empty($error_message))?$_POST['actualAmount']:''?>" placeholder="<?=$this->lang->line('actualAmountText')?> " id="actualAmount" name="actualAmount[]" onkeydown="OnlyNumericKey(event)">
                                    </div><!-- /.form-group -->
                                </div>
                                <a href="javascript:void(0);" class="btn btn-primary add-more-plan"><i class="fa fa-plus"></i>Add More Plan</a>
                            </div>

                            <div class="form-group">
                                <label ><?=$this->lang->line('freePeriodText')?><span class="asterisk">*</span></label>
                                <input type="text" class="form-control input-sm" min="1" value="<?php echo (!empty($error_message))?$_POST['freeperiod']:''?>" placeholder="<?=$this->lang->line('freePeriodText')?>" required="required" id="freeperiod" name="freeperiod" onkeydown="OnlyNumericKey(event)">
                            </div><!-- /.form-group -->

                            <div class="form-group">
                                <label ><?=$this->lang->line('freeDurationText')?><span class="asterisk">*</span></label>
                                <select class="form-control" name="freeduration" id="freeduration" required="required">
                                    <option value="day" <?php echo (!empty($error_message))?(($_POST['freeduration']=='day')?'select':''):''?>><?=$this->lang->line('dailyText')?></option>
                                    <option value="week" <?php echo (!empty($error_message))?(($_POST['freeduration']=='week')?'select':''):''?>><?=$this->lang->line('weeklyText')?></option>
                                    <option value="month" <?php echo (!empty($error_message))?(($_POST['freeduration']=='month')?'select':''):''?>><?=$this->lang->line('monthlyText')?></option>
                                    
                                    
                                    <!-- <option value="year" <?php echo (!empty($error_message))?(($_POST['freeduration']=='year')?'select':''):''?>><?=$this->lang->line('yearlyText')?></option> -->
                                </select>
                            </div><!-- /.form-group -->
                            <div class="image-upload form-group">
                                <label for="file-input">
                                    <img src="<?php echo DASHSTATIC.'/restaurant/assets/img/uplod.png'; ?>" width="200px" height="200px">
                                     
                                 </label>
                                <input class="file-input" name="uploadImg" onchange="filepreviewnew(this);" type="file" />
                            </div>
							
                            <div class=" col-sm-12 form-group" style=" margin-top: 12px;">
                                <a href="javascript:void(0)" class="btn btn-primary btnAddSubscription"><?=$this->lang->line('buttonSave')?> </a>
                                <input type="hidden" name="action" value="add_subscription">
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