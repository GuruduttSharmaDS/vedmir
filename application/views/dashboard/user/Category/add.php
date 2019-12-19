<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<style type="text/css">
    #modalLoginAvatar .modal-dialog.cascading-modal.modal-avatar {
        margin-top: 3rem;
    }
</style>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
<section class="heading-section">
    <div class="row">
        <div class="col-lg-12">
            <div class="heading">
                <div class="mr-auto">
                    <h1><?= (isset($detailData->categoryName)) ?'Update': 'Add New';?><span> Category</span></h1>
                </div>
                <div class="ml-auto">
                    <a href="<?=DASHURL.'/'.$this->sessRole?>/category/list" class="btn btn-info">Category list</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="form-section">
    <form action="" method="POST" onsubmit="submitForm(this, event)" enctype="multipart/form-data">
        <div class="row">
            <div class="col-sm-4">
                <div id="modalLoginAvatar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" style="display: block; padding-left: 16px;">
                    <div class="modal-dialog cascading-modal modal-avatar modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <img src="<?php echo isset($detailData->categoryImage) ? $detailData->categoryImage : DASHSTATIC.'/img/user-icon.jpg'; ?>" alt="avatar" class="rounded-circle img-responsive image-upload-img">
                            </div>
                            <div class="modal-body text-center mb-1">
                                <h5 class="mt-1 mb-2"><?php echo isset($detailData->categoryName) ? $detailData->categoryName : ''; ?></h5>
                                  <div class="ml-auto">
                                    <div class="file-btn image-upload">
                                      <img src="<?=DASHSTATIC?>/img/upload-icon.png" alt="upload icon"> <?php echo isset($detailData->categoryName) ? 'Change' : 'Upload'?> Image
                                      <input type="file" name="uploadImg" onchange="userimgpreview(this);" type="file" <?php echo (isset($detailData->categoryImage) && !empty($detailData->categoryImage)) ? '' : 'required'?> />
                                    </div>
                                  </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>






            
            
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="form-group">
                              <label for="idParent">Parent ID</label>
                              <select class="form-control" id="idParent" name="idParent">
                                 <option value="-1">--Select--</option>
                                <?php foreach($parentId as $row):?>
                                    <option value="<?php echo $row->categoryId;?>"
                                            <?php echo (isset($detailData->idParent) && $detailData->idParent == $row->categoryId)? "selected":'';?>><?= $row->categoryName;?></option>
                                <?php endforeach;?>
                                
                              </select>
                            </div>


                            <!-- <?php foreach($parentId as $row):?>
                                    <option value="<?php echo isset($row->categoryId) ? $row->categoryId : ''; ?>"><?= $row->categoryName ?></option>
                                <?php endforeach;?> -->


                            


                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label >Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?php echo isset($detailData->categoryName) ? $detailData->categoryName : ''; ?>" placeholder="Enter category name" id="categoryName" name="categoryName" required="required">
                        </div>
                    </div>
                    

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select class="form-control bindaddress" name="status" id="status" required>

                                <option value="0" <?php echo (isset($detailData->status) && $detailData->status == '0')? "selected":'';?> > Active</option>

                                <option value="1" <?php echo (isset($detailData->status) && $detailData->status == '1')? "selected":'';?>>Deactive</option> 

                              
                            </select>
                        </div>
                    </div>

                    

                    <div class="col-sm-12">
                        <div class="form-group">
                            <button type="button" name="btnAddCategory" class="btn btn-primary validate-form"><?php echo isset($detailData->categoryName) ? 'Update' : 'Submit'?> </button>
                            <a href="<?=DASHURL.'/'.$this->sessRole?>/category/list" class="btn btn-danger">Cancel</a>
                            <input type="hidden" name="action" value="addUpdateCategory">
                            <input type="hidden" name="hiddenval" id="hiddenval" value="<?=isset($detailData->categoryId)?$detailData->categoryId:0;?>">
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