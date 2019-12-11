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
                    <a href="<?=DASHURL.'/'.$this->sessRole?>/category/category-list" class="btn btn-info">Category list</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="form-section">
    <form action="" method="POST" onsubmit="submitForm(this, event)" enctype="multipart/form-data">
        <div class="row">
            
            <div class="col-sm-9">
                <div class="row">
    
                    <div class="col-sm-6">
                        <div class="form-group">
                            <!-- <label >Parent ID <span class="text-danger">*</span></label> -->
                            <!-- <input type="text" class="form-control input-sm" value="<?php echo isset($detailData->idParent) ? $detailData->idParent : ''; ?>" placeholder="Enter category name" id="idParent" name="idParent" required="required">  -->


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
                    <div class="col-sm-6">
                        <div class="form-group">
                            
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label >Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?php echo isset($detailData->categoryName) ? $detailData->categoryName : ''; ?>" placeholder="Enter category name" id="categoryName" name="categoryName" required="required">
                        </div>
                    </div>
                    

                    <div class="col-sm-6">
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
                            <a href="<?=DASHURL.'/'.$this->sessRole?>/category/category-list" class="btn btn-danger">Cancel</a>
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