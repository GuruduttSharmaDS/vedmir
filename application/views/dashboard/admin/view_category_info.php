<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
          
          <form action="" method="POST" enctype="multipart/form-data">
          <div class="you_requested">
          <h3><?php echo $category->categoryName; ?> <i style="font-size: 15px;color: #827c7c;">(<?=ucfirst($this->lang->line('category'))?>)</i> </h3>
          

              <div class="row">
                <label class="col-md-3 text-strong"><?=$this->lang->line('categoryName')?></label>
                <div class="col-md-9">
                   <?=$category->categoryName; ?>
                </div>
              </div>
              <div class="row">
                <label class="col-md-3 text-strong"><?=$this->lang->line('categoryMetaTitle')?></label>
                <div class="col-md-9">
                   <?php echo $category->metaTitle; ?>
                </div>
              </div>
              <div class="row">
                <label class="col-md-3 text-strong"><?=$this->lang->line('categoryMetaKeywords')?></label>
                <div class="col-md-9">
                   <?php echo $category->metaKeywords; ?>
                </div>
              </div>
              <div class="row">
                <label class="col-md-3 text-strong"><?=$this->lang->line('categoryMetaDescription')?></label>
                <div class="col-md-9">
                   <?php echo $category->metaDescription; ?>
                </div>
              </div>
              <div class="row">
                <label class="col-md-3 text-strong"><?=$this->lang->line('addedDate')?></label>
                <div class="col-md-9">
                   <?php echo $category->addedOn; ?>
                </div>
              </div>
              <div class="row">
                <label class="col-md-3 text-strong"><?=$this->lang->line('lastUpdateDate')?></label>
                <div class="col-md-9">
                   <?php echo $category->updatedOn; ?>
                </div>
              </div>

      </form>
<?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>
    </body>
    <!--/ END BODY -->

</html>