<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<style type="text/css">
    #modalLoginAvatar .modal-dialog.cascading-modal.modal-avatar {
        margin-top: 3rem;
    }
</style>
<link rel="stylesheet" href="<?= DASHSTATIC ?>/css/summernote.css">
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
<section class="heading-section">
    <div class="row">
        <div class="col-lg-12">
            <div class="heading">
                <div class="mr-auto">
                    <h1><?= (isset($detailData->staticpageId)) ?'Update': '';?><span> Static Page</span></h1>
                </div>
                <div class="ml-auto">
                    <a href="<?=DASHURL.'/'.$this->sessRole?>/Staticpage/list" class="btn btn-info">Static Page list</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="form-section">
    <form action="" method="POST" onsubmit="submitForm(this, event)" enctype="multipart/form-data">
        <div class="row">
                       
            
            <div class="col-sm-8">
                <div class="row">

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label >Key <span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?php echo isset($detailData->key) ? $detailData->key : ''; ?>" placeholder="Enter key name" id="key" name="key" required="required">
                        </div>
                    </div>
                    
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label >Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?php echo isset($detailData->name) ? $detailData->name : ''; ?>" placeholder="Enter name" id="name" name="name" required="required">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Name rs<span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?php echo isset($detailData->name_rs) ? $detailData->name_rs : ''; ?>" placeholder="Enter name rs" id="name_rs" name="name_rs" required="required">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label >Description <span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?php echo isset($detailData->description) ? $detailData->description : ''; ?>" placeholder="Enter description name" id="description" name="description" required="required">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Description rs<span class="text-danger">*</span></label>
                            
                       
                        <textarea  id="description_rs" name="description_rs" ><?php echo isset($detailData->description_rs) ? $detailData->description_rs : ''; ?>
                          
                        </textarea>
                         </div>
                                                
                    </div>

                    

                    

                    

                    <div class="col-sm-12">
                        <div class="form-group">
                            <button type="button" name="btnAddCategory" class="btn btn-primary validate-form"><?php echo isset($detailData->staticpageId) ? 'Update' : ''?> </button>
                            <a href="<?=DASHURL.'/'.$this->sessRole?>/Staticpage/list" class="btn btn-danger">Cancel</a>
                            <input type="hidden" name="action" value="UpdateStaticPage">
                            <input type="hidden" name="hiddenval" id="hiddenval" value="<?=isset($detailData->staticpageId)?$detailData->staticpageId:0;?>">
                        </div>

                    </div>
                    <div class="col-sm-12 msg"></div>

                </div>
            </div>
        </div>
    </form>

</section>


<?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>

<script type="text/javascript" src="<?=DASHSTATIC?>/js/summernote.js"></script>
<script>
  $("#description_rs").mdbWYSIWYG({
  colorPalette: {
    red: '#d50000',
    green: '#64dd17',
    yellow: '#fff176',
    blue: '#03a9f4',
    purple: '#6a1b9a',
    white: '#fff',
    black: '#000'
  }
});
</script>
</body>
</html>

