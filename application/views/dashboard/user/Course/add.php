<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>

<style type="text/css">
    #modalLoginAvatar .modal-dialog.cascading-modal.modal-avatar {
        margin-top: 3rem;
    }
</style>
<?php //print_r($coursesData);die; ?>
<section class="heading-section">
    <div class="row">
        <div class="col-lg-12">
            <div class="heading">
                <div class="mr-auto">
                    <h1><?= (isset($coursesData->courseId)) ? 'Update': 'Add New';?><span> Courses</span></h1>
                </div>
                <div class="ml-auto">
                    <a href="<?= DASHURL.'/'.$this->sessRole ?>/courses/list" class="btn btn-info">Courses List</a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php //print_r($coursesData);die; ?>

<section class="form-section">
    <form action="" method="POST" onsubmit="submitForm(this, event)" enctype="multipart/form-data">
        <div class="row">
            <div class="col-sm-3">
                <div id="modalLoginAvatar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" style="display: block; padding-left: 16px;">
                    <div class="modal-dialog cascading-modal modal-avatar modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <img src="<?php echo (isset($coursesData->thumbnailImage) && !empty($coursesData->thumbnailImage)) ? $coursesData->thumbnailImage : DASHSTATIC.'/img/user-icon.jpg'; ?>" alt="avatar" class="rounded-circle img-responsive image-upload-img">
                            </div>
                            <div class="modal-body text-center mb-1">
                                <h5 class="mt-1 mb-2"><?php echo isset($coursesData->planName) ? $coursesData->planName : ''; ?></h5>
                                  <div class="ml-auto">
                                    <div class="file-btn image-upload">
                                      <img src="<?=DASHSTATIC?>/img/upload-icon.png" alt="upload icon"> <?php echo isset($coursesData->subscriptionPlanId) ? 'Change' : 'Upload'?> Icon
                                      <input type="file" name="uploadImg" onchange="userimgpreview(this);" type="file" <?php echo (isset($coursesData->thumbnailImage) && !empty($coursesData->thumbnailImage)) ? '' : ''?> />
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
                            <label >Category Name <span class="text-danger">*</span></label>
                            <select class="form-control" id="categoryId" name="categoryId">
                                 <option value="-1">--Select--</option>
                                <?php foreach($categoryData as $row){?>
                                    <option value="<?php echo $row->categoryId;?>"
                                            <?php echo (isset($coursesData->categoryId) && $coursesData->categoryId == $row->categoryId)? "selected":'';?>><?= $row->categoryName;?></option>
                                <?php }?>
                              </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Video Thumbnail <span class="text-danger">*</span></label>
                           
                            <!-- <input type="file" class="form-control bindaddress" name="uploadVid"   <?php echo (isset($coursesData->thumbnailVedio) && !empty($coursesData->thumbnailVedio)) ? '' : ''?> /> -->

                            <input type="file" name="uploadVid" <?php echo (isset($coursesData->promovideo) && !empty($coursesData->promovideo)) ? '' : 'required'?> />
                        </div>
                    </div>

                   
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label >Course Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?php echo isset($coursesData->courseName) ? $coursesData->courseName : ''; ?>" placeholder="Enter Course Name" id="courseName" name="courseName"   required="required">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label >Course Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?php echo isset($coursesData->courseTitle) ? $coursesData->courseTitle : ''; ?>" placeholder="Enter Course Title" id="courseTitle" name="courseTitle"   required="required">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label >Course Description <span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?php echo isset($coursesData->courseDescription) ? $coursesData->courseDescription : ''; ?>" placeholder="Enter Course Description" id="courseDescription" name="courseDescription"  required="required">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label >Course Prices<span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?php echo isset($coursesData->coursePrice) ? $coursesData->coursePrice : ''; ?>" placeholder="Enter Course Price" id="coursePrice" name="coursePrice"  required="required">
                        </div>
                    </div>

                    
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label >Course Price After Discount <span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?php echo isset($coursesData->coursePriceAfterDiscount) ? $coursesData->coursePriceAfterDiscount : ''; ?>" placeholder="Enter Course Price After Discount" id="coursePriceAfterDiscount" name="coursePriceAfterDiscount"  required="required">
                        </div>
                    </div>

                  
                    
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Status<span class="text-danger">*</span></label>
                            <select class="form-control bindaddress" name="status" id="status" required>

                                <option value="0" <?php echo (isset($coursesData->status) && $coursesData->status == '0')? "selected":'';?> > Active</option>

                                <option value="1" <?php echo (isset($coursesData->status) && $coursesData->status == '1')? "selected":'';?>>Deactive</option> 

                                

                            </select>
                        </div>
                    </div>


                   
                   

                    <div class="col-sm-12">
                        <div class="form-group">
                            <button type="button" name="btnAdduser" class="btn btn-primary validate-form"><?php echo isset($coursesData->courseId) ? 'Update' : 'Submit'?> </button>
                            <a href="<?=DASHURL.'/'.$this->sessRole?>/Courses/list" class="btn btn-danger"><?=$this->lang->line('cancel')?></a>
                            <input type="hidden" name="action" value="addUpdateCourse">
                            <input type="hidden" name="hiddenval" id="hiddenval" value="<?=isset($coursesData->courseId)?$coursesData->courseId:0;?>">
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