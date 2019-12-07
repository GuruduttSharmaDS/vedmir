<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
          
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="you_requested">
            <h3><?= (isset($categoryData->categoryName)) ? $this->lang->line('editForm') : $this->lang->line('newForm');?><span> <?php echo $this->lang->line('catPageHeading');?></span></h3>
          

            <div class="col-md-12">
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
                            <label ><?php echo $this->lang->line('categoryName');?> (En)<span class="asterisk">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?= (isset($categoryData->categoryName)) ? $categoryData->categoryName : ''; ?>" placeholder="<?php echo $this->lang->line('categoryNamePlaceHolder');?> En" id="categoryName" name="categoryName" required="required">
                        </div><!-- /.form-group -->
                    </div>
                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'french') ? 'active' : '';?>" id="french">
                        <div class="form-group">
                            <label ><?php echo $this->lang->line('categoryName');?> (Fr)<span class="asterisk">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?= (isset($categoryData->categoryName_fr)) ? $categoryData->categoryName_fr : ''; ?>" placeholder="<?php echo $this->lang->line('categoryNamePlaceHolder');?> Fr" id="categoryName_fr" name="categoryName_fr" required="required">
                        </div><!-- /.form-group -->
                    </div>
                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'german') ? 'active' : '';?>" id="german">
                        <div class="form-group">
                            <label ><?php echo $this->lang->line('categoryName');?> (Gr)<span class="asterisk">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?= (isset($categoryData->categoryName_gr)) ? $categoryData->categoryName_gr : ''; ?>" placeholder="<?php echo $this->lang->line('categoryNamePlaceHolder');?> Gr" id="categoryName_gr" name="categoryName_gr" required="required">
                        </div><!-- /.form-group -->
                    </div> 
                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'italian') ? 'active' : '';?>" id="italian">
                        <div class="form-group">
                           <label ><?php echo $this->lang->line('categoryName');?> (It)<span class="asterisk">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?= (isset($categoryData->categoryName_it)) ? $categoryData->categoryName_it : ''; ?>" placeholder="<?php echo $this->lang->line('categoryNamePlaceHolder');?> It" id="categoryName_it" name="categoryName_it" required="required">
                        </div><!-- /.form-group -->
                    </div>   
                </div>                
               
                <div class=" col-sm-12 form-group" style=" margin-top: 12px;">
                    <button type="submit" name="btnAddCategory" class="btn btn-primary btnAddCategory"><?= (isset($categoryData->name)) ? $this->lang->line('buttonUpdate') : $this->lang->line('buttonSave'); ?></button>
                      
                </div>

            </div><!--you_requested-->
        </div>
    </form>
<?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>

  
    </body>
    <!--/ END BODY -->

</html>