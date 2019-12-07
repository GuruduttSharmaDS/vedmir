<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
          
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="you_requested">
            <h3><?= (isset($categoryData->categoryName)) ? $this->lang->line('editForm') : $this->lang->line('newForm');?><span> <?php echo $this->lang->line('blog').' '.$this->lang->line('category');?></span></h3>
          

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
                        <div class="form-group">
                            <label ><?php echo $this->lang->line('categoryMetaTitle');?> (En)</label>
                            <textarea id="metaTitle" name="metaTitle" class="form-control" rows="3" placeholder="<?php echo $this->lang->line('categoryMetaTitle');?>"><?= isset($categoryData->metaTitle) ? $categoryData->metaTitle : ''; ?></textarea>
                        </div><!-- /.form-group -->
                        <div class="form-group">
                            <label ><?php echo $this->lang->line('categoryMetaKeywords');?> (En)</label>
                            <textarea id="metaKeywords" name="metaKeywords" class="form-control" rows="3" placeholder="<?php echo $this->lang->line('categoryMetaKeywords');?>"><?= isset($categoryData->metaKeywords) ? $categoryData->metaKeywords : ''; ?></textarea>
                        </div><!-- /.form-group -->
                        <div class="form-group">
                            <label ><?php echo $this->lang->line('categoryMetaDescription');?> (En)</label>
                            <textarea id="metaDescription" name="metaDescription" class="form-control" rows="3" placeholder="<?php echo $this->lang->line('categoryMetaDescription');?>"><?= isset($categoryData->metaDescription) ? $categoryData->metaDescription : ''; ?></textarea>
                        </div><!-- /.form-group -->
                    </div>
                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'french') ? 'active' : '';?>" id="french">
                        <div class="form-group">
                            <label ><?php echo $this->lang->line('categoryName');?> (Fr)<span class="asterisk">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?= (isset($categoryData->categoryName_fr)) ? $categoryData->categoryName_fr : ''; ?>" placeholder="<?php echo $this->lang->line('categoryNamePlaceHolder');?> Fr" id="categoryName_fr" name="categoryName_fr" required="required">
                        </div><!-- /.form-group -->
                        <div class="form-group">
                            <label ><?php echo $this->lang->line('categoryMetaTitle');?> (Fr)</label>
                            <textarea id="metaTitle_fr" name="metaTitle_fr" class="form-control" rows="3" placeholder="<?php echo $this->lang->line('categoryMetaTitle');?>"><?= isset($categoryData->metaTitle_fr) ? $categoryData->metaTitle_fr : ''; ?></textarea>
                        </div><!-- /.form-group -->
                        <div class="form-group">
                            <label ><?php echo $this->lang->line('categoryMetaKeywords');?> (Fr)</label>
                            <textarea id="metaKeywords_fr" name="metaKeywords_fr" class="form-control" rows="3" placeholder="<?php echo $this->lang->line('categoryMetaKeywords');?>"><?= isset($categoryData->metaKeywords_fr) ? $categoryData->metaKeywords_fr : ''; ?></textarea>
                        </div><!-- /.form-group -->
                        <div class="form-group">
                            <label ><?php echo $this->lang->line('categoryMetaDescription');?> (Fr)</label>
                            <textarea id="metaDescription_fr" name="metaDescription_fr" class="form-control" rows="3" placeholder="<?php echo $this->lang->line('categoryMetaDescription');?>"><?= isset($categoryData->metaDescription_fr) ? $categoryData->metaDescription_fr : ''; ?></textarea>
                        </div><!-- /.form-group -->
                    </div>
                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'german') ? 'active' : '';?>" id="german">
                        <div class="form-group">
                            <label ><?php echo $this->lang->line('categoryName');?> (Gr)<span class="asterisk">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?= (isset($categoryData->categoryName_gr)) ? $categoryData->categoryName_gr : ''; ?>" placeholder="<?php echo $this->lang->line('categoryNamePlaceHolder');?> Gr" id="categoryName_gr" name="categoryName_gr" required="required">
                        </div><!-- /.form-group -->
                        <div class="form-group">
                            <label ><?php echo $this->lang->line('categoryMetaTitle');?> (Gr)</label>
                            <textarea id="metaTitle_gr" name="metaTitle_gr" class="form-control" rows="3" placeholder="<?php echo $this->lang->line('categoryMetaTitle');?>"><?= isset($categoryData->metaTitle_gr) ? $categoryData->metaTitle_gr : ''; ?></textarea>
                        </div><!-- /.form-group -->
                        <div class="form-group">
                            <label ><?php echo $this->lang->line('categoryMetaKeywords');?> (Gr)</label>
                            <textarea id="metaKeywords_gr" name="metaKeywords_gr" class="form-control" rows="3" placeholder="<?php echo $this->lang->line('categoryMetaKeywords');?>"><?= isset($categoryData->metaKeywords_gr) ? $categoryData->metaKeywords_gr : ''; ?></textarea>
                        </div><!-- /.form-group -->
                        <div class="form-group">
                            <label ><?php echo $this->lang->line('categoryMetaDescription');?> (Gr)</label>
                            <textarea id="metaDescription_gr" name="metaDescription_gr" class="form-control" rows="3" placeholder="<?php echo $this->lang->line('categoryMetaDescription');?>"><?= isset($categoryData->metaDescription_gr) ? $categoryData->metaDescription_gr : ''; ?></textarea>
                        </div><!-- /.form-group -->
                    </div> 
                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'italian') ? 'active' : '';?>" id="italian">
                        <div class="form-group">
                           <label ><?php echo $this->lang->line('categoryName');?> (It)<span class="asterisk">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?= (isset($categoryData->categoryName_it)) ? $categoryData->categoryName_it : ''; ?>" placeholder="<?php echo $this->lang->line('categoryNamePlaceHolder');?> It" id="categoryName_it" name="categoryName_it" required="required">
                        </div><!-- /.form-group -->
                        <div class="form-group">
                            <label ><?php echo $this->lang->line('categoryMetaTitle');?> (It)</label>
                            <textarea id="metaTitle_it" name="metaTitle_it" class="form-control" rows="3" placeholder="<?php echo $this->lang->line('categoryMetaTitle');?>"><?= isset($categoryData->metaTitle_it) ? $categoryData->metaTitle_it : ''; ?></textarea>
                        </div><!-- /.form-group -->
                        <div class="form-group">
                            <label ><?php echo $this->lang->line('categoryMetaKeywords');?> (It)</label>
                            <textarea id="metaKeywords_it" name="metaKeywords_it" class="form-control" rows="3" placeholder="<?php echo $this->lang->line('categoryMetaKeywords');?>"><?= isset($categoryData->metaKeywords_it) ? $categoryData->metaKeywords_it : ''; ?></textarea>
                        </div><!-- /.form-group -->
                        <div class="form-group">
                            <label ><?php echo $this->lang->line('categoryMetaDescription');?> (It)</label>
                            <textarea id="metaDescription_it" name="metaDescription_it" class="form-control" rows="3" placeholder="<?php echo $this->lang->line('categoryMetaDescription');?>"><?= isset($categoryData->metaDescription_it) ? $categoryData->metaDescription_it : ''; ?></textarea>
                        </div><!-- /.form-group -->
                    </div>   
                </div>                
               
                <div class=" col-sm-12 form-group" style=" margin-top: 12px;">
                    <button type="submit" name="btnAddCategory" class="btn btn-primary btnAddCategory"><?= (isset($categoryData->name)) ? $this->lang->line('buttonUpdate') : $this->lang->line('buttonSave'); echo ' '.$this->lang->line('category'); ?></button>
                      
                </div>
            </div>
            </div><!--you_requested-->
    </form>
<?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>

  
    </body>
    <!--/ END BODY -->

</html>