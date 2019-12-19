<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>

<style type="text/css">
    #modalLoginAvatar .modal-dialog.cascading-modal.modal-avatar {
        margin-top: 3rem;
    }
</style>

<section class="heading-section">
    <div class="row">
        <div class="col-lg-12">
            <div class="heading">
                <div class="mr-auto">
                    <h1><?= (isset($subscriptionData->subscriptionPlanId)) ? 'Update': 'Add New';?><span> <?=$this->lang->line('subscription')?></span></h1>
                </div>
                <div class="ml-auto">
                    <a href="<?= DASHURL.'/'.$this->sessRole ?>/subscriptions/list" class="btn btn-info"><?=$this->lang->line('subscriptionList')?></a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="form-section">
    <form action="" method="POST" onsubmit="submitForm(this, event)" enctype="multipart/form-data">
        <div class="row">
            <div class="col-sm-3">
                <div id="modalLoginAvatar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" style="display: block; padding-left: 16px;">
                    <div class="modal-dialog cascading-modal modal-avatar modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <img src="<?php echo (isset($subscriptionData->icon) && !empty($subscriptionData->icon)) ? $subscriptionData->icon : DASHSTATIC.'/img/user-icon.jpg'; ?>" alt="avatar" class="rounded-circle img-responsive image-upload-img">
                            </div>
                            <div class="modal-body text-center mb-1">
                                <h5 class="mt-1 mb-2"><?php echo isset($subscriptionData->planName) ? $subscriptionData->planName : ''; ?></h5>
                                  <div class="ml-auto">
                                    <div class="file-btn image-upload">
                                      <img src="<?=DASHSTATIC?>/img/upload-icon.png" alt="upload icon"> <?php echo isset($subscriptionData->subscriptionPlanId) ? 'Change' : 'Upload'?> Icon
                                      <input type="file" name="uploadImg" onchange="userimgpreview(this);" type="file" <?php echo (isset($subscriptionData->icon) && !empty($subscriptionData->icon)) ? '' : 'required'?> />
                                    </div>
                                  </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-9">
                <div class="row">
    
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>User Type <span class="text-danger">*</span></label>
                            <select class="form-control bindaddress" name="userType" id="userType" required>

                                <option value="1" <?php echo (isset($subscriptionData->userType) && $subscriptionData->userType == '1')? "selected":'';?> > Student</option>

                                <option value="2" <?php echo (isset($subscriptionData->userType) && $subscriptionData->userType == '2')? "selected":'';?>>Teacher</option> 

                                

                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Status<span class="text-danger">*</span></label>
                            <select class="form-control bindaddress" name="status" id="status" required>

                                <option value="0" <?php echo (isset($subscriptionData->status) && $subscriptionData->status == '0')? "selected":'';?> > Active</option>

                                <option value="1" <?php echo (isset($subscriptionData->status) && $subscriptionData->status == '1')? "selected":'';?>>Deactive</option> 

                                

                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label >Plan Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?php echo isset($subscriptionData->planName) ? $subscriptionData->planName : ''; ?>" placeholder="Enter Plan Name" id="planName" name="planName" required="required">
                        </div>
                    </div>             
                   
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label >Plan Name RS <span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?php echo isset($subscriptionData->planName_rs) ? $subscriptionData->planName_rs : ''; ?>" placeholder="Enter Plan Name RS" id="planName_rs" name="planName_rs"   required="required">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label >Description EN<span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?php echo isset($subscriptionData->description) ? $subscriptionData->description : ''; ?>" placeholder="Enter Description" id="description" name="description"  required="required">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label >Description RS<span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?php echo isset($subscriptionData->description_rs) ? $subscriptionData->description_rs : ''; ?>" placeholder="Enter Description" id="description_rs" name="description_rs"  required="required">
                        </div>
                    </div>
                    
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label >Period <span class="text-danger">*</span></label>
                            <input type="number" class="form-control input-sm" value="<?php echo isset($subscriptionData->period) ? $subscriptionData->period : ''; ?>" placeholder="Enter Period" id="period" name="period" maxlength="12" onkeypress="return OnlyInteger(event)" required="required">
                        </div>
                    </div>


                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Duration<span class="text-danger">*</span></label>
                            <select class="form-control bindaddress" name="duration" id="duration" required>

                                <option value="day" <?php echo (isset($subscriptionData->duration) && $subscriptionData->duration == 'day')? "selected":'';?> >Day</option>

                                <option value="month" <?php echo (isset($subscriptionData->duration) && $subscriptionData->duration == 'month')? "selected":'';?>>Month</option> 

                                 <option value="year" <?php echo (isset($subscriptionData->duration) && $subscriptionData->duration == 'year')? "selected":'';?>>Year</option> 
                            </select>
                        </div>
                    </div>


                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" class="form-control" value="<?php echo isset($subscriptionData->amount) ? $subscriptionData->amount : ''; ?>" placeholder="Amount" required="required" onkeypress="return OnlyInteger(event)" id="amount"  name="amount" />
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Currency<span class="text-danger">*</span></label>
                            <select class="form-control bindaddress" name="currencyId" id="currency" required>
                                <option>Select Currency</option>

                                <?php
                                    if (!empty ($currency)) { 
                                        foreach ($currency as $info) { 
                                            $selected = ($info['currencyId'] == $subscriptionData->currencyId) ? "selected": "";
                                            echo "<option value=".$info['currencyId']." $selected >". $info['currencyName'] ."</option>";
                                        }
                                    }
                                ?>                          
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <button type="button" name="btnAdduser" class="btn btn-primary validate-form"><?php echo isset($subscriptionData->subscriptionPlanId) ? 'Update' : 'Submit'?> </button>
                            <a href="<?=DASHURL.'/'.$this->sessRole?>/subscriptions/list" class="btn btn-danger"><?=$this->lang->line('cancel')?></a>
                            <input type="hidden" name="action" value="addUpdateSubscription">
                            <input type="hidden" name="hiddenval" id="hiddenval" value="<?=isset($subscriptionData->subscriptionPlanId)?$subscriptionData->subscriptionPlanId:0;?>">
                        </div>

                    </div>
                    <div class="col-sm-12 msg"></div>

                </div>
            </div>
        </div>
    </form>

</section>


<?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>

</body>
</html>