<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
            <div class="">
              <div class="order_status">
                <h2>Coupon List<span><i class="fa fa-gear" aria-hidden="true"></i></span></h2>
                
              </div>
              <h3 ><span><button type="button" class="btn btn-success pull-right addNewCoupon" style="margin-bottom: 5px;"><?=$this->lang->line('new') ?></button></span></h3>
              <table class="table" id="sampleTable">
                <thead>
                  <tr>
                   <th><?=$this->lang->line('couponCode') ?></th>
                   <th><?=$this->lang->line('type') ?></th>
                   <th><?=$this->lang->line('limituse') ?></th>
                   <th><?=$this->lang->line('startDate') ?></th>
                    <th><?=$this->lang->line('expiryDate') ?></th>
                    <th><?=$this->lang->line('activeMember') ?></th>
                    <th><?=$this->lang->line('count') ?></th>          
                    <th><?=$this->lang->line('status') ?></th>
                    <th><?=$this->lang->line('action') ?></th>
                  </tr>
                </thead>
                <tbody class="tablebody">



                </tbody>
              </table>
            </div>
            <div id="couponFormModel" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?=$this->lang->line('couponHeading') ?></h4>
                  </div>
                  <div class="modal-body">
                    <form action="" method="post" class="couponForm" onsubmit="return ValidateCoupon(this, event);">
                      <div class="msg"></div>
                      <div class="form-group col-md-6 firstInput">
                        <label><?=$this->lang->line('couponCode') ?></label>
                        <input type="text" name="couponCode" id="couponCode" class="form-control" required>
                      </div>
                      <div class="form-group col-md-6">
                        <label><?=$this->lang->line('type') ?></label>
                        <select name="type" id="type" class="form-control" required>
                          <option value="0"> <?=$this->lang->line('single') ?></option>
                          <option value="1"> <?=$this->lang->line('multiple') ?></option>
                        </select>
                      </div>
                      <div class="form-group col-md-6 multiple" style="display:none;">
                        <label><?=$this->lang->line('limituse') ?></label>
                        <input type="text" name="limituse" id="limituse" class="form-control" onkeydown="OnlyNumericKey(event)">
                      </div>
                      <div class="form-group col-md-6 ">
                        <label><?=$this->lang->line('couponOffered') ?></label>
                        <select name="couponOffered" id="couponOffered" class="form-control" required>
                         
                          <option value="0"> <?=$this->lang->line('discountper') ?></option>
                          <option value="1"> <?=$this->lang->line('discountchf') ?></option>
                          <option value="2"> <?=$this->lang->line('offeredMembership') ?></option>
                          <option value="3"> <?=$this->lang->line('customdiscount') ?></option>
                        </select>
                      </div>
                      <div class="form-group col-md-6 discountper">
                        <label><?=$this->lang->line('discountper') ?></label>
                        <input type="text" name="discountper" id="discountper" class="form-control" onkeydown="OnlyNumericKey(event)" required>
                      </div>
                      <div class="form-group col-md-6 discountchf" style="display:none;">
                        <label><?=$this->lang->line('discountchf') ?></label>
                        <input type="text" name="discountchf" id="discountchf" class="form-control" onkeydown="OnlyNumericKey(event)">
                      </div>
                      <div class="membershipgroup " style="display:none;">
                        <div class="form-group col-md-6">
                          <label><?=$this->lang->line('typeMembership') ?></label>
                          <select name="membership" id="membership" class="form-control" >
                            <option value=""> <?=$this->lang->line('selectMembership') ?></option>
                            <?php 
                              if(!empty($subscriptionList)) {
                                foreach ($subscriptionList as $subscription) {
                                  echo '<option value="'.$subscription->planId.'">'.$subscription->planName.'</option>';
                                }
                              }
                            ?>                          
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label><?=$this->lang->line('membershipDuration') ?></label>
                          <select name="membershipplan" id="membershipplan" class="form-control" >
                            <option value=""> <?=$this->lang->line('selectDuration') ?></option>
                                                      
                          </select>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label><?=$this->lang->line('discountedPrice')?></label>
                            <input type="number" class="form-control input-sm" name="discountedPrice" value="0">
                        </div>
                      </div> 
                       <div class="clearfix"></div> 
                      <div class="customdiscount" style="display:none;">

                        	<div class="form-group col-md-6">
		                        <label><?=$this->lang->line('subscription') ?> <?=$this->lang->line('duration') ?></label>
		                        <input type="number" name="period" id="period" class="form-control" min="1" value="1" >
	                      	</div>
	                      	<div class="form-group col-md-6">
		                        <label><!-- <?=$this->lang->line('subscription') ?> <?=$this->lang->line('duration') ?> -->&nbsp;</label>
		                        <select name="duration" id="duration" class="form-control" >
		                          <option value="day"> <?=$this->lang->line('day') ?></option>
		                          <option value="month"> <?=$this->lang->line('month') ?></option>
		                          <option value="year"> <?=$this->lang->line('year') ?></option>
		                        </select>
	                      	</div>
	                      	<div class="form-group col-md-6">
                                <label><?=$this->lang->line('freePeriodTextOffered')?></label>
                                <input type="text" class="form-control input-sm" min="1" value="" placeholder="<?=$this->lang->line('freePeriodTextOffered')?>" id="freeperiod" name="freeperiod" onkeydown="OnlyNumericKey(event)">
                            </div>
                            <div class="form-group col-md-6">
                                <label><?=$this->lang->line('freeDurationText')?></label>
                                <select class="form-control" name="freeduration" id="freeduration">
                                    <option value="day"> <?=$this->lang->line('dailyText')?></option>
                                    <option value="month"><?=$this->lang->line('monthlyText')?></option>
                                </select>
                            </div>
                      </div>   
                      <div class="clearfix"></div>                
                      <div class="form-group col-md-6">
                        <label><?=$this->lang->line('expiryDate') ?></label>
                        <input type="text" class="form-control input-sm dateRangeSlide" value="" placeholder="YYYY-MM-DD - YYYY-MM-DD" id="daterange" name="dateranges" required="required" readonly>
                      </div>
                      
                      <div class="form-group col-md-6"> 
                        <input type="hidden" name="action" value="addCoupon">
                        <input type="hidden" name="hiddenVal" id="hiddenVal" value="0">
                        <input type="hidden" name="currentIndex" id="currentIndex" value="">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary validate-coupon" style="margin-top: 24px;"><?=$this->lang->line('submit') ?></button>
                      </div>
                    </form>
                  </div>
                  
                </div>

              </div>
            </div>
<?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>
        
    </body>
    <!--/ END BODY -->

</html>


