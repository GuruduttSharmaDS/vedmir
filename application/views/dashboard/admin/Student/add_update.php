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
                    <h1><?= (isset($restaurantData->restaurantName)) ? 'Update': 'Add New';?><span> Student</span></h1>
                </div>
                <div class="ml-auto">
                    <a href="<?= DASHURL.'/'.$this->sessRole ?>/student/list" class="btn btn-info">Student list</a>
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
                                <img src="<?php echo isset($userData->img) ? $userData->img : DASHSTATIC.'/img/user-icon.jpg'; ?>" alt="avatar" class="rounded-circle img-responsive image-upload-img">
                            </div>
                            <div class="modal-body text-center mb-1">
                                <h5 class="mt-1 mb-2"><?php echo isset($userData->userName) ? $userData->userName : ''; ?></h5>
                                  <div class="ml-auto">
                                    <div class="file-btn image-upload">
                                      <img src="<?=DASHSTATIC?>/img/upload-icon.png" alt="upload icon"> <?php echo isset($userData->userName) ? 'Change' : 'Upload'?> Image
                                      <input type="file" name="uploadImg" onchange="userimgpreview(this);" type="file" <?php echo (isset($userData->img) && !empty($userData->img)) ? '' : 'required'?> />
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
                            <label >Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?php echo isset($userData->userName) ? $userData->userName : ''; ?>" placeholder="Enter name" id="userName" name="userName" required="required">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Email address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control input-sm" value="<?php echo isset($userData->email) ? $userData->email : ''; ?>" placeholder="Enter email address" id="email" name="email" required="required">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label >Country code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?php echo isset($userData->countryCode) ? $userData->countryCode : ''; ?>" placeholder="Enter country code" id="countryCode" name="countryCode" maxlength="5" onkeypress="return OnlyInteger(event)" required="required">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label >Mobile number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?php echo isset($userData->mobile) ? $userData->mobile : ''; ?>" placeholder="Enter mobile number" id="mobile" name="mobile" maxlength="12" onkeypress="return OnlyInteger(event)" required="required">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Gender <span class="text-danger">*</span></label>
                            <select class="form-control bindaddress" name="gender" id="gender" required>

                                <option value="Male" <?php echo (isset($userData->gender) && $userData->gender == 'Male')? "selected":'';?> > Male</option>

                                <option value="Female" <?php echo (isset($userData->gender) && $userData->gender == 'Female')? "selected":'';?>>Female</option> 

                                <option value="Other" <?php echo (isset($userData->gender) && $userData->gender == 'Other')? "selected":'';?>>Other</option>

                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Date Of Birth</label>
                            <input type="date" class="form-control"  data-date-format="dd MM yyyy"  value="<?php echo isset($userData->dob) ? $userData->dob : ''; ?>" placeholder="Choose date of birth" required="required"  id="dob"  name="dob" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label >Country</label>
                            <input type="text" class="form-control"   value="<?php echo isset($userData->country) ? $userData->country : ''; ?>" placeholder="Enter country" name="country" id="country"  />
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label >State</label>
                            <input type="text" class="form-control"   value="<?php echo isset($userData->state) ? $userData->state : ''; ?>" placeholder="Enter state" name="state" id="state"  />
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label >City</label>
                            <input type="text" class="form-control"   value="<?php echo isset($userData->city) ? $userData->city : ''; ?>" placeholder="Enter city" name="city" id="city"  />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label >Address </label>
                            <textarea id="address" name="address" class="form-control " maxlength="500" rows="1" placeholder="Enter address" ><?= isset($userData->address) ? $userData->address : ''; ?></textarea>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label >Zipcode</label>
                                <input type="text" class="form-control input-sm" value="<?php echo isset($userData->userName) ? $userData->postalCode : ''; ?>" placeholder="Enter zipcode" id="postalCode" maxlength="8" name="postalCode" >
                        </div>
                    </div>
                    <?php if(!isset($userData->userName)){?>
                        <div class="col-sm-6">
                            <div class="form-group" >
                                <label >Password <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control input-sm" value="" placeholder="Enter password" id="password" name="password" required>
                            </div>
                        </div>
                    <?php }?>


                    <div class="col-sm-12">
                        <div class="form-group">
                            <button type="button" name="btnAdduser" class="btn btn-primary validate-form"><?php echo isset($userData->userName) ? 'Update' : 'Submit'?> </button>
                            <a href="<?=DASHURL.'/'.$this->sessRole?>/student/list" class="btn btn-danger">Cancel</a>
                            <input type="hidden" name="action" value="addUpdateStudent">
                            <input type="hidden" name="hiddenval" id="hiddenval" value="<?=isset($userData->userId)?$userData->userId:0;?>">
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