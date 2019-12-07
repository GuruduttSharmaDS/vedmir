<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
          
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="you_requested">
            <h3><?= (isset($subcategoryData->subcategoryName)) ? $this->lang->line('editForm') : $this->lang->line('newForm');?><span> <?php echo $this->lang->line('category');?></span></h3>
          

            <div class="col-md-12">
                <div class="form-group">
                  <label ><?php echo $this->lang->line('selectVenue');?></label>
                  <select id="selRestaurant" name="selRestaurant"  class="form-control" required="required">
                        <option value=""><?php echo $this->lang->line('selectOption');?></option>
                      <?php   

                        if(isset($restaurantData)) {

                          foreach($restaurantData as $row) { ?>

                              <option value="<?php echo $row->restaurantId;?>"<?php echo (isset($subcategoryData->restaurantId) && $subcategoryData->restaurantId == $row->restaurantId) ? 'Selected' : ''; ?> ><?php echo $row->restaurantName;?></option>

                        <?php } }  ?>

                  </select>
                </div>
                <div class="form-group">
                    <label ><?php echo $this->lang->line('selectType');?><span class="asterisk">*</span></label>
                    <select class="form-control" name="categoryId" id="categoryId" required="required">
                        <option value=""><?php echo $this->lang->line('selectOption');?></option>
                        <?php   
                          if(isset($categoryData)) {
                            foreach($categoryData as $row) { 
                                    ?>
                                <option value="<?php echo $row->categoryId;?>" <?php echo (isset($subcategoryData->categoryId) && $subcategoryData->categoryId == $row->categoryId) ? 'Selected' : ''; ?> ><?php echo $row->categoryName;?></option>
                          <?php } }  ?>
                    </select>
                </div><!-- /.form-group -->
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
                            <input type="text" class="form-control input-sm" value="<?= (isset($subcategoryData->subcategoryName)) ? $subcategoryData->subcategoryName : ''; ?>" placeholder="<?php echo $this->lang->line('categoryName');?> Name En" id="subcategoryName" name="subcategoryName" required="required">
                        </div><!-- /.form-group -->
                    </div>
                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'french') ? 'active' : '';?>" id="french">
                        <div class="form-group">
                            <label ><?php echo $this->lang->line('categoryName');?> (Fr)<span class="asterisk">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?= (isset($subcategoryData->subcategoryName_fr)) ? $subcategoryData->subcategoryName_fr : ''; ?>" placeholder="<?php echo $this->lang->line('categoryName');?>" id="subcategoryName_fr" name="subcategoryName_fr" required="required">
                        </div><!-- /.form-group -->
                    </div>
                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'german') ? 'active' : '';?>" id="german">
                        <div class="form-group">
                            <label ><?php echo $this->lang->line('categoryName');?> (Gr)<span class="asterisk">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?= (isset($subcategoryData->subcategoryName_gr)) ? $subcategoryData->subcategoryName_gr : ''; ?>" placeholder="<?php echo $this->lang->line('categoryName');?> " id="subcategoryName_gr" name="subcategoryName_gr" required="required">
                        </div><!-- /.form-group -->
                    </div> 
                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'italian') ? 'active' : '';?>" id="italian">
                        <div class="form-group">
                            <label ><?php echo $this->lang->line('categoryName');?> (It)<span class="asterisk">*</span></label>
                            <input type="text" class="form-control input-sm" value="<?= (isset($subcategoryData->subcategoryName_it)) ? $subcategoryData->subcategoryName_it : ''; ?>" placeholder="<?php echo $this->lang->line('categoryName');?>" id="subcategoryName_it" name="subcategoryName_it" required="required">
                        </div><!-- /.form-group -->
                    </div>   
                </div>               
                <div class=" col-sm-12 form-group" style=" margin-top: 12px;">
                    <button type="submit" name="btnAddCategory" class="btn btn-primary btnAddCategory"><?= (isset($subcategoryData->subcategoryName)) ? $this->lang->line('buttonUpdate') : $this->lang->line('buttonSave'); ?></button>
                      
                </div>

            </div><!--you_requested-->
        </div>
    </form>
<?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>

  
    </body>
    <!--/ END BODY -->

</html>