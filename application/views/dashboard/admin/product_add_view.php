<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
          
        <form action="" method="POST" enctype="multipart/form-data" novalidate="">
          <div class="you_requested">
          <h3><?= (isset($productData->productName)) ? $this->lang->line('editForm') : $this->lang->line('newForm');?><span> <?php echo $this->lang->line('productPageHeading');?></span></h3>
          
            <div class="expert_breif">
              <div class="image-upload" style="">
                <label for="file-input">
                    <img src="<?php echo (isset($productData->img) && $productData->img !='') ?$productData->img:DASHSTATIC.'/restaurant/assets/img/uplod.png' ?>" width="200px" height="200px"  aria-hidden="true" href="#mappopup" role="button" data-toggle="modal"  id="previewImg"  onclick="getFilteredImageList(this, event);">
                     
                 </label>
              </div>
                <input class="file-input hide" name="uploadImg" id="uploadImg" type="text" <?php echo (!isset($productData->img) || empty($productData->img))?'required="required"':'' ?>/>
            </div>
            <div class="expert_contact">
                <div class="form-group">
                  <label ><?php echo $this->lang->line('selectVenue');?> <span class="text-danger">*</span></label>
                  <select id="selRestaurant" name="selRestaurant"  class="form-control" required="required">
                    <option value=""><?php echo $this->lang->line('selectVenue');?></option>
                      <?php   

                        if(isset($restaurantData)) {

                          foreach($restaurantData as $row) { ?>

                              <option value="<?php echo $row->restaurantId;?>"<?php echo (isset($productData->restaurantId) && $productData->restaurantId == $row->restaurantId) ? 'Selected' : ''; ?> ><?php echo $row->restaurantName;?></option>

                        <?php } }  ?>

                  </select>
                </div>
                <div class="form-group">
                    <label ><?php echo $this->lang->line('selectType');?><span class="asterisk">*</span></label>
                    <select class="form-control" name="selCategoryId" id="selCategory" required="required">
                        <option value=""><?php echo $this->lang->line('selectType');?></option>
                        <?php   
                          if(isset($categoryData)) {
                            foreach($categoryData as $row) { ?>
                                <option value="<?php echo $row->categoryId;?>"  data-value="<?=ucfirst($row->categoryName);?>"  <?php echo (isset($productData->categoryId) && $productData->categoryId == $row->categoryId) ? 'Selected' : ''; ?> ><?php echo $row->categoryName;?></option>
                          <?php } }  ?>
                    </select>
                </div><!-- /.form-group -->
                <div class="form-group" id="subcategoryDiv">
                    <label ><?php echo $this->lang->line('selectCategory');?><span class="asterisk">*</span></label>
                    <select class="form-control" name="selSubcategoryId" id="selSubcategoryId" onchange="bindproductsubcategoryitem(this,event)">
                        <option value=""><?php echo $this->lang->line('selectCategory');?></option>
                        <?php   
                          if(isset($subcategoryData)) {
                            foreach($subcategoryData as $row) { ?>
                                <option value="<?php echo $row->subcategoryId;?>" <?php echo (isset($productData->subcategoryId) && $productData->subcategoryId == $row->subcategoryId) ? 'Selected' : ''; ?> ><?php echo $row->subcategoryName;?></option>
                          <?php } }  ?>
                    </select>
                </div><!-- /.form-group -->
                <div class="form-group" id="subcategoryitemDiv" style="display: <?=(isset($subcategoryitemData) && !empty($subcategoryitemData))?'block':'none'?>">
                    <label ><?php echo $this->lang->line('selectSubCategoryLabel');?><span class="asterisk">*</span></label>
                    <select class="form-control" name="selSubcategoryitemId" id="selSubcategoryitemId" <?=(isset($subcategoryitemData) && !empty($subcategoryitemData))?'required="required"':''?>>
                        <option value=""><?php echo $this->lang->line('selectSubCategoryLabel');?></option>
                        <?php   
                          if(isset($subcategoryitemData)) {
                            foreach($subcategoryitemData as $row) { ?>
                                <option value="<?php echo $row->subcategoryitemId;?>" <?php echo (isset($productData->subcategoryitemId) && $productData->subcategoryitemId == $row->subcategoryitemId) ? 'Selected' : ''; ?> ><?php echo $row->subcategoryitemName;?></option>
                          <?php } }  ?>
                    </select>
                </div><!-- /.form-group -->


                  <div class="form-group <?php echo (isset($productData->categoryId) && $productData->categoryId == 5) ? '' : 'hide'; ?>" > 
                    <label class="checkbox-inline">
                      <p>
                        <input type="checkbox" <?php echo (isset($productData->isOnlyForGirl) && $productData->isOnlyForGirl == 1) ?'checked': ''; ?> id="isOnlyForGirl" name="isOnlyForGirl" value="1">
                        <label for="isOnlyForGirl"><?php echo $this->lang->line('isOnlyForGirl');?> </label>
                      </p>
                    </label>
                  </div><!-- /.form-group -->  

                  <div class="form-group <?php echo (isset($productData->categoryId) && $productData->categoryId == 5) ? '' : 'hide'; ?>" > 
                    <label class="checkbox-inline">
                      <p>
                        <input type="checkbox" <?php echo (isset($productData->isAvailableInFree) && $productData->isAvailableInFree == 1) ?'checked': ''; ?> id="isAvailableInFree" name="isAvailableInFree" value="1">
                        <label for="isAvailableInFree"><?php echo $this->lang->line('isAvailableInFree');?> </label>
                      </p>
                    </label>
                  </div><!-- /.form-group -->   

                  
                  <div class="form-group <?php echo (isset($productData->categoryId) && $productData->categoryId == 5 && isset($productData->isAvailableInFree) && $productData->isAvailableInFree == 1) ? '' : 'hide'; ?>" > 
                    <label class="checkbox-inline">
                      <p>
                        <input type="checkbox" <?php echo (isset($productData->doNotIncludeInTheMenu) && $productData->doNotIncludeInTheMenu == 1) ?'checked': ''; ?> id="doNotIncludeInTheMenu" name="doNotIncludeInTheMenu" value="1">
                        <label for="doNotIncludeInTheMenu"><?php echo $this->lang->line('doNotIncludeInTheMenu');?> </label>
                      </p>
                    </label>
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
                          <label ><?php echo $this->lang->line('productNameLabel');?> (En) <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" id="txtProductName" name="txtProductName" value="<?php echo isset($productData->productName) ? $productData->productName : ''; ?>" required="required">
                        </div>
                          
                        <div class="form-group">
                            <label><?php echo $this->lang->line('productDescription');?> (En) <span class="text-danger">*</span></label>
                            
                                <textarea id="txtsortDescription" name="txtsortDescription" class="form-control " maxlength="255" rows="3" placeholder="<?php echo $this->lang->line('productDescription');?> En"><?php echo isset($productData->sortDescription) ? $productData->sortDescription : ''; ?></textarea>

                        </div><!-- /.form-group --> 
                        
                        <!-- <div class="form-group">
                            <label><?php echo $this->lang->line('productDescription');?> (En) <span class="text-danger">*</span></label>
                            
                                <textarea id="txtDescription" name="txtDescription" class="form-control " maxlength="500" rows="3" placeholder="<?php echo $this->lang->line('productDescription');?> En" required="required"><?php echo isset($productData->description) ? $productData->description : ''; ?></textarea>
                        </div> --><!-- /.form-group -->                   
                        <div class="form-group">
                            <label><?php echo $this->lang->line('productTags');?> (En) </label>
                            
                                <textarea id="txtTags" name="txtTags" class="form-control " maxlength="500" rows="3" placeholder="<?php echo $this->lang->line('productTags');?> En"><?php echo isset($productData->tags) ? $productData->tags : ''; ?></textarea>
                        </div><!-- /.form-group -->
                    </div>
                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'french') ? 'active' : '';?>" id="french">
                        <div class="form-group">
                          <label ><?php echo $this->lang->line('productNameLabel');?> (Fr) <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" id="txtProductName_fr" name="txtProductName_fr" value="<?php echo isset($productData->productName_fr) ? $productData->productName_fr : ''; ?>" required="required">
                        </div>
                          
                        <div class="form-group">
                            <label><?php echo $this->lang->line('productDescription');?> (Fr) <span class="text-danger">*</span></label>
                            
                                <textarea id="txtsortDescription_fr" name="txtsortDescription_fr" class="form-control " maxlength="255" rows="3" placeholder="<?php echo $this->lang->line('productDescription');?> Fr"><?php echo isset($productData->sortDescription_fr) ? $productData->sortDescription_fr : ''; ?></textarea>

                        </div><!-- /.form-group --> 
                        
                        <!-- <div class="form-group">
                            <label><?php echo $this->lang->line('productDescription');?> (Fr) <span class="text-danger">*</span></label>
                            
                                <textarea id="txtDescription_Fr" name="txtDescription_fr" class="form-control " maxlength="500" rows="3" placeholder="<?php echo $this->lang->line('productDescription');?> Fr" required="required"><?php echo isset($productData->description_fr) ? $productData->description_fr : ''; ?></textarea>
                        </div> --><!-- /.form-group -->                   
                        <div class="form-group"  style="display: none;">
                            <label><?php echo $this->lang->line('productTags');?> (Fr) </label>
                            
                                <textarea id="txtTags_fr" name="txtTags_fr" class="form-control " maxlength="500" rows="3" placeholder="<?php echo $this->lang->line('productTags');?> Fr" ><?php echo isset($productData->tags_fr) ? $productData->tags_fr : ''; ?></textarea>
                        </div><!-- /.form-group -->
                    </div>
                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'german') ? 'active' : '';?>" id="german">
                        <div class="form-group">
                          <label ><?php echo $this->lang->line('productNameLabel');?> (Gr) <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" id="txtProductName_gr" name="txtProductName_gr" value="<?php echo isset($productData->productName_gr) ? $productData->productName_gr : ''; ?>" required="required">
                        </div>
                          
                        <div class="form-group">
                            <label><?php echo $this->lang->line('productDescription');?> (Gr) <span class="text-danger">*</span></label>
                            
                                <textarea id="txtsortDescription_gr" name="txtsortDescription_gr" class="form-control " maxlength="255" rows="3" placeholder="<?php echo $this->lang->line('productDescription');?> Gr"><?php echo isset($productData->sortDescription_gr) ? $productData->sortDescription_gr : ''; ?></textarea>

                        </div><!-- /.form-group --> 
                        
                        <!-- <div class="form-group">
                            <label><?php echo $this->lang->line('productDescription');?> (Gr) <span class="text-danger">*</span></label>
                            
                                <textarea id="txtDescription_gr" name="txtDescription_gr" class="form-control " maxlength="500" rows="3" placeholder="<?php echo $this->lang->line('productDescription');?> Gr" required="required"><?php echo isset($productData->description_gr) ? $productData->description_gr : ''; ?></textarea>
                        </div> --><!-- /.form-group -->                   
                        <div class="form-group">
                            <label><?php echo $this->lang->line('productTags');?> (Gr) </label>
                            
                                <textarea id="txtTags_gr" name="txtTags_gr" class="form-control " maxlength="500" rows="3" placeholder="<?php echo $this->lang->line('productTags');?> GR" required="required"><?php echo isset($productData->tags_gr) ? $productData->tags_gr : ''; ?></textarea>
                        </div><!-- /.form-group -->
                    </div> 
                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'italian') ? 'active' : '';?>" id="italian">
                        <div class="form-group">
                          <label ><?php echo $this->lang->line('productNameLabel');?> (It) <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" id="txtProductName_it" name="txtProductName_it" value="<?php echo isset($productData->productName_it) ? $productData->productName_it : ''; ?>" required="required">
                        </div>
                          
                        <div class="form-group">
                            <label><?php echo $this->lang->line('productDescription');?> (It) <span class="text-danger">*</span></label>
                            
                                <textarea id="txtsortDescription_it" name="txtsortDescription_it" class="form-control " maxlength="255" rows="3" placeholder="<?php echo $this->lang->line('productDescription');?> It" ><?php echo isset($productData->sortDescription_it) ? $productData->sortDescription_it : ''; ?></textarea>

                        </div><!-- /.form-group --> 
                        
                       <!--  <div class="form-group">
                           <label><?php echo $this->lang->line('productDescription');?> (It) <span class="text-danger">*</span></label>
                           
                               <textarea id="txtDescription_it" name="txtDescription_it" class="form-control " maxlength="500" rows="3" placeholder="<?php echo $this->lang->line('productDescription');?> It" required="required"><?php echo isset($productData->description_it) ? $productData->description_it : ''; ?></textarea>
                       </div> --><!-- /.form-group -->                   
                        <div class="form-group">
                            <label><?php echo $this->lang->line('productTags');?> (It) </label>
                            
                                <textarea id="txtTags_it" name="txtTags_it" class="form-control " maxlength="500" rows="3" placeholder="<?php echo $this->lang->line('productTags');?> It" required="required"><?php echo isset($productData->tags_it) ? $productData->tags_it : ''; ?></textarea>
                        </div><!-- /.form-group -->
                    </div>   
                </div> 

                <div class="form-group">
                  <label ><?php echo $this->lang->line('selectProductType');?><span class="asterisk">*</span></label>
                  <select id="productType" name="productType" class="form-control" onchange="changeProductType()" required="required">
                    <option value="0" <?php echo (isset($productData->productType) && $productData->productType == 0) ? 'Selected' : ''; ?> ><?php echo $this->lang->line('simple');?></option>
                    <option value="1" <?php echo (isset($productData->productType) && $productData->productType == 1) ? 'Selected' : ''; ?> ><?php echo $this->lang->line('variable');?></option>
                  </select>
                </div>
                <style type="text/css">
                  .variableitem{
                    background-color: #f1f1f1;
                    padding: 7px;
                    border-radius: 4px;
                  }
                  a.label.label-danger.rounded.removevariable, a.label.label-danger.rounded.deletevariable{
                    float:right !important;
                  }
                  .variableitemdetails{
                    border: 1px solid #736f6f;
                    margin: 5px 0;
                    padding: 10px;
                  }
                  .variableitemaddmore{
                    margin-bottom: 10px;
                    padding: 10px;
                  }
                  .add-more-variable{
                    margin: 5px;
                  }
                  label.checkbox-inline {
                      margin-top: 15px;
                      padding: 0;
                  }
                </style>
                <div class="col-md-12 variableitem" <?=(isset($variableProductData) && valResultSet($variableProductData))?'style="display: block;"':'style="display: none;"'?> >
                  <ul class="nav nav-tabs" role="tablist">
                      <li role="presentation" class="<?=($this->sessLang == 'english') ? 'active' : '';?>"><a href="#variable-english" aria-controls="english" role="tab" data-toggle="tab">En</a></li>
                      <li role="presentation" class="<?=($this->sessLang == 'french') ? 'active' : '';?>"><a href="#variable-french" aria-controls="french" role="tab" data-toggle="tab">Fr</a></li>
                      <li role="presentation" class="<?=($this->sessLang == 'german') ? 'active' : '';?>"><a href="#variable-german" aria-controls="german" role="tab" data-toggle="tab">Gr</a></li>
                      <li role="presentation" class="<?=($this->sessLang == 'italian') ? 'active' : '';?>"><a href="#variable-italian" aria-controls="italian" role="tab" data-toggle="tab">It</a></li>               
                   </ul>
                  <div class="tab-content">
                    <div class="clearfix"></div>

                                    
                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'english') ? 'active' : '';?>" id="variable-english">

                      <?php 
                      $counter = 1;
                      $encontent = '<div class="col-md-12 variableitemdetails box-'.$counter.'"  data-counter="'.$counter.'">
                        <div class="form-group">
                          <label>'.$this->lang->line('variableItemName').' (EN)<span class="text-danger">*</span></label>
                          <input type="text" class="form-control input-sm" min="0" placeholder="'.$this->lang->line('variableItemName').'"   name="variableName[]">
                          <input type="hidden" name="variableItemId[]" value="0">
                        </div>
                        <div class="form-group">
                          <label>'.$this->lang->line('variableItemPrice').' (EN)<span class="text-danger">*</span></label>
                          <input min="0" placeholder="'.$this->lang->line('variableItemPrice').'" name="variableItemPrice[]" value="0" class="form-control input-sm price-'.$counter.'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'.$counter.'\')">
                        </div>
                        <div class="form-group '.((isset($productData->isAvailableInFree) && $productData->isAvailableInFree == 1)?'': 'hide').'" ><label class="checkbox-inline"><p><input type="checkbox" class="welcomeCheckbox" id="welcomeCheckbox'.$counter.'" name="welcomeCheckbox[]" value="1" onchange="welcomeCheckboxChange(this, event)"><label for="welcomeCheckbox'.$counter.'">'.$this->lang->line('isAvailableInFree').'</label><input type="hidden" name="isAvailableInWelcome[]" class="isAvailableInWelcome" value="0"> </p></label></div>
                      </div>';
                      $frcontent = '<div class="col-md-12 variableitemdetails box-'.$counter.'"  data-counter="'.$counter.'">
                        <div class="form-group">
                          <label>'.$this->lang->line('variableItemName').' (FR)<span class="text-danger">*</span></label>
                          <input type="text" class="form-control input-sm" min="0" placeholder="'.$this->lang->line('variableItemName').'"   name="variableName_fr[]">
                          
                        </div>
                        <div class="form-group">
                          <label>'.$this->lang->line('variableItemPrice').' (FR)<span class="text-danger">*</span></label>
                          <input type="text" placeholder="'.$this->lang->line('variableItemPrice').'" name="variableItemPrice_fr[]" value="0"  class="form-control input-sm price-'.$counter.'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'.$counter.'\')">
                        </div>
                        <div class="form-group '.((isset($productData->isAvailableInFree) && $productData->isAvailableInFree == 1)?'': 'hide').'" ><label class="checkbox-inline"><p><input type="checkbox" class="welcomeCheckbox" id="welcomeCheckbox_fr'.$counter.'" name="welcomeCheckbox_fr[]" value="1" onchange="welcomeCheckboxChange(this, event)"><label for="welcomeCheckbox_fr'.$counter.'">'.$this->lang->line('isAvailableInFree').'</label><input type="hidden" name="isAvailableInWelcome_fr[]" class="isAvailableInWelcome" value="0"> </p></label></div>
                      </div>';
                      $grcontent = '<div class="col-md-12 variableitemdetails box-'.$counter.'" data-counter="'.$counter.'">
                        <div class="form-group">
                          <label>'.$this->lang->line('variableItemName').' (GR)<span class="text-danger">*</span></label>
                          <input type="text" class="form-control input-sm" min="0" placeholder="'.$this->lang->line('variableItemName').'"   name="variableName_gr[]" >
                          
                        </div>
                        <div class="form-group">
                          <label>'.$this->lang->line('variableItemPrice').' (GR)<span class="text-danger">*</span></label>
                          <input type="text" min="0" placeholder="'.$this->lang->line('variableItemPrice').'" name="variableItemPrice_gr[]" value="0" class="form-control input-sm price-'.$counter.'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'.$counter.'\')">
                        </div>
                        <div class="form-group '.((isset($productData->isAvailableInFree) && $productData->isAvailableInFree == 1)?'': 'hide').'" ><label class="checkbox-inline"><p><input type="checkbox" class="welcomeCheckbox" id="welcomeCheckbox_gr'.$counter.'" name="welcomeCheckbox_gr[]" value="1" onchange="welcomeCheckboxChange(this, event)"><label for="welcomeCheckbox_gr'.$counter.'">'.$this->lang->line('isAvailableInFree').'</label><input type="hidden" name="isAvailableInWelcome_gr[]" class="isAvailableInWelcome" value="0"> </p></label></div>
                      </div>';
                      $itcontent = '
                      <div class="col-md-12 variableitemdetails box-'.$counter.'"  data-counter="'.$counter.'">
                        <div class="form-group">
                          <label>'.$this->lang->line('variableItemName').' (IT)<span class="text-danger">*</span></label>
                          <input type="text" class="form-control input-sm" min="0" placeholder="'.$this->lang->line('variableItemName').'"   name="variableName_it[]">
                          
                        </div>
                        <div class="form-group">
                          <label>'.$this->lang->line('variableItemPrice').' (IT)<span class="text-danger">*</span></label>
                          <input type="text" min="0" placeholder="'.$this->lang->line('variableItemPrice').'" name="variableItemPrice_it[]" value="0" class="form-control input-sm price-'.$counter.'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'.$counter.'\')">
                        </div>
                        <div class="form-group '.((isset($productData->isAvailableInFree) && $productData->isAvailableInFree == 1)?'': 'hide').'" ><label class="checkbox-inline"><p><input type="checkbox" class="welcomeCheckbox" id="welcomeCheckbox_it'.$counter.'" name="welcomeCheckbox_it[]" value="1" onchange="welcomeCheckboxChange(this, event)"><label for="welcomeCheckbox_it'.$counter.'">'.$this->lang->line('isAvailableInFree').'</label><input type="hidden" name="isAvailableInWelcome_it[]" class="isAvailableInWelcome" value="0"> </p></label></div>
                      </div>';

                      if (isset($variableProductData) && valResultSet($variableProductData)) {
                        $encontent = $frcontent = $grcontent = $itcontent = '';
                        foreach ($variableProductData as $variablekey => $variableProduct) {
                          $isAvailableInWelcomeCheckbox = (isset($variableProduct->isAvailableInFree) && $variableProduct->isAvailableInFree == 1)?'checked': '';
                          $isAvailableInWelcomeInput = (isset($variableProduct->isAvailableInFree) && $variableProduct->isAvailableInFree == 1)?1: 0;
                          $encontent .= '<div class="col-md-12 variableitemdetails box-'.$counter.'" data-counter="'.$counter.'">
                              <div class="form-group">
                                <a class="label label-danger rounded deletevariable" onclick="deleteVariable('.$variableProduct->variableId.',\'box-'.$counter.'\')" ><i class="fa fa-trash-o"></i> '.$this->lang->line("delete").'</a>
                                <label>'.$this->lang->line('variableItemName').' (EN)<span class="text-danger">*</span></label>
                                <input type="text" class="form-control input-sm" min="0"  placeholder="'.$this->lang->line('variableItemName').'" name="variableName[]" value="'.$variableProduct->variableName.'">
                                <input type="hidden" name="variableItemId[]" value="'.$variableProduct->variableId.'">
                              </div>
                              <div class="form-group">
                                <label>'.$this->lang->line('variableItemPrice').' (EN)<span class="text-danger">*</span></label>
                                <input type="text" min="0"  placeholder="'.$this->lang->line('variableItemPrice').'" name="variableItemPrice[]"  value="'.$variableProduct->price.'" class="form-control input-sm price-'.$counter.'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'.$counter.'\')">
                              </div>
                              <div class="form-group '.((isset($productData->isAvailableInFree) && $productData->isAvailableInFree == 1)?'': 'hide').'" ><label class="checkbox-inline"><p><input type="checkbox" class="welcomeCheckbox" id="welcomeCheckbox'.$counter.'" name="welcomeCheckbox[]" value="1" '.$isAvailableInWelcomeCheckbox.' onchange="welcomeCheckboxChange(this, event)"><label for="welcomeCheckbox'.$counter.'">'.$this->lang->line('isAvailableInFree').'</label><input type="hidden" name="isAvailableInWelcome[]" class="isAvailableInWelcome" value="'.$isAvailableInWelcomeInput.'"> </p></label></div>
                            </div>';

                          $frcontent .= '<div class="col-md-12 variableitemdetails box-'.$counter.'" data-counter="'.$counter.'">
                            <div class="form-group">
                              <a class="label label-danger rounded deletevariable" onclick="deleteVariable('.$variableProduct->variableId.',\'box-'.$counter.'\')" ><i class="fa fa-trash-o"></i> '.$this->lang->line("delete").'</a>
                              <label>'.$this->lang->line('variableItemName').' (FR)<span class="text-danger">*</span></label>
                              <input type="text" class="form-control input-sm" min="0"  placeholder="'.$this->lang->line('variableItemName').'" name="variableName_fr[]" value="'.$variableProduct->variableName_fr.'">
                            </div>
                            <div class="form-group">
                              <label>'.$this->lang->line('variableItemPrice').' (FR)<span class="text-danger">*</span></label>
                              <input type="text" placeholder="'.$this->lang->line('variableItemPrice').'" name="variableItemPrice_fr[]"  value="'.$variableProduct->price.'" class="form-control input-sm price-'.$counter.'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'.$counter.'\')">
                            </div>
                            <div class="form-group '.((isset($productData->isAvailableInFree) && $productData->isAvailableInFree == 1)?'': 'hide').'" ><label class="checkbox-inline"><p><input type="checkbox" class="welcomeCheckbox" id="welcomeCheckbox_fr'.$counter.'" name="welcomeCheckbox_fr[]" value="1" '.$isAvailableInWelcomeCheckbox.' onchange="welcomeCheckboxChange(this, event)"><label for="welcomeCheckbox_fr'.$counter.'">'.$this->lang->line('isAvailableInFree').'</label><input type="hidden" name="isAvailableInWelcome_fr[]" class="isAvailableInWelcome" value="'.$isAvailableInWelcomeInput.'"> </p></label></div>
                          </div>';

                          $grcontent .= '<div class="col-md-12 variableitemdetails box-'.$counter.'" data-counter="'.$counter.'">
                            <div class="form-group">
                              <a class="label label-danger rounded deletevariable" onclick="deleteVariable('.$variableProduct->variableId.',\'box-'.$counter.'\')" ><i class="fa fa-trash-o"></i> '.$this->lang->line("delete").'</a>
                              <label>'.$this->lang->line('variableItemName').' (GR)<span class="text-danger">*</span></label>
                              <input type="text" class="form-control input-sm" min="0"  placeholder="'.$this->lang->line('variableItemName').'" name="variableName_gr[]" value="'.$variableProduct->variableName_gr.'">
                            </div>
                            <div class="form-group">
                              <label>'.$this->lang->line('variableItemPrice').' (GR)<span class="text-danger">*</span></label>
                              <input type="text" placeholder="'.$this->lang->line('variableItemPrice').'" name="variableItemPrice_gr[]"  value="'.$variableProduct->price.'" class="form-control input-sm price-'.$counter.'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'.$counter.'\')">
                            </div>
                            <div class="form-group '.((isset($productData->isAvailableInFree) && $productData->isAvailableInFree == 1)?'': 'hide').'" ><label class="checkbox-inline"><p><input type="checkbox" class="welcomeCheckbox" id="welcomeCheckbox_gr'.$counter.'" name="welcomeCheckbox_gr[]" value="1" '.$isAvailableInWelcomeCheckbox.' onchange="welcomeCheckboxChange(this, event)"><label for="welcomeCheckbox_gr'.$counter.'">'.$this->lang->line('isAvailableInFree').'</label><input type="hidden" name="isAvailableInWelcome_gr[]" class="isAvailableInWelcome" value="'.$isAvailableInWelcomeInput.'"> </p></label></div>
                          </div>';

                          $itcontent .= '<div class="col-md-12 variableitemdetails box-'.$counter.'" data-counter="'.$counter.'">
                            <div class="form-group">
                              <a class="label label-danger rounded deletevariable" onclick="deleteVariable('.$variableProduct->variableId.',\'box-'.$counter.'\')" ><i class="fa fa-trash-o"></i> '.$this->lang->line("delete").'</a>
                              <label>'.$this->lang->line('variableItemName').' (IT)<span class="text-danger">*</span></label>
                              <input type="text" class="form-control input-sm" min="0"  placeholder="'.$this->lang->line('variableItemName').'" name="variableName_it[]" value="'.$variableProduct->variableName_it.'">
                            </div>
                            <div class="form-group">
                              <label>'.$this->lang->line('variableItemPrice').' (IT)<span class="text-danger">*</span></label>
                              <input type="text" placeholder="'.$this->lang->line('variableItemPrice').'" name="variableItemPrice_it[]"  value="'.$variableProduct->price.'" class="form-control input-sm price-'.$counter.'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'.$counter.'\')">
                            </div>
                            <div class="form-group '.((isset($productData->isAvailableInFree) && $productData->isAvailableInFree == 1)?'': 'hide').'" ><label class="checkbox-inline"><p><input type="checkbox" class="welcomeCheckbox" id="welcomeCheckbox_it'.$counter.'" name="welcomeCheckbox_it[]" value="1" '.$isAvailableInWelcomeCheckbox.' onchange="welcomeCheckboxChange(this, event)"><label for="welcomeCheckbox_it'.$counter.'">'.$this->lang->line('isAvailableInFree').'</label><input type="hidden" name="isAvailableInWelcome_it[]" class="isAvailableInWelcome" value="'.$isAvailableInWelcomeInput.'"> </p></label></div>
                          </div>';
                        
                        ++$counter;
                      } }else{++$counter;} ?>

                      <?php $addMore  = '<div class="col-md-12 variableitemaddmore">
                        <a href="javascript:" class="btn btn-info add-more-variable"><i class="fa fa-plus"></i>'.$this->lang->line('addMore').'</a><br/>
                      </div>'; 
                      $encontent .= $addMore;
                      $frcontent .= $addMore;
                      $grcontent .= $addMore;
                      $itcontent .= $addMore;
                      ?>
                      <?php echo $encontent; ?>

                   
                    </div>


                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'french') ? 'active' : '';?>" id="variable-french">        
                      <?php echo $frcontent; ?>
                    </div>


                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'german') ? 'active' : '';?>" id="variable-german">
                      <?php echo $grcontent; ?>
                    </div>


                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'italian') ? 'active' : '';?>" id="variable-italian">      
                     <?php echo $itcontent; ?>
                    </div>
                  </div>
                </div><!-- /.variableItem- -->


                  <div class="form-group pricediv" <?=(isset($productData->productType) && $productData->productType==1)?'style="display: none;"':''?>>               
                      <label><?php echo $this->lang->line('productPrice');?> <span class="text-danger">*</span></label>
                      
                          <input type="text" class="form-control input-sm" min="0" value="<?php echo isset($productData->price) ? $productData->price : '0'; ?>" placeholder="<?php echo $this->lang->line('productPrice');?>" required="required" id="txtPrice" name="txtPrice" onkeydown="OnlyNumericKey(event)">
                  </div><!-- /.form-group -->

                  <div class="form-group"> 
                    <label class="checkbox-inline"  style="display: none;">
                      <p>
                        <input type="checkbox" <?php echo (isset($productData->isFeatured) && $productData->isFeatured == 1) ?'checked': ''; ?> id="isFeatured" name="isFeatured" value="1">
                        <label for="isFeatured"><?php echo $this->lang->line('isFeatureProduct');?> </label>
                      </p>
                    </label>
                  </div><!-- /.form-group -->  

                <div class="form-group"  style="display: none;">
                  <label><?php echo $this->lang->line('productGalleryImages');?> </label>
                  <div class="col-md-12 col-sm-12" style="margin-top: 5px;">
                    <input type="file" name="txtgallaryImgs[]" data-error="Upload Product Gallery Image">
                  </div>

                  <a href="javascript:" class="btn btn-warning add-more-image"><i class="fa fa-plus"></i> <?php echo $this->lang->line('addMore');?></a><br/>

                  <?php 
                  if(isset($productgallaryData) && valResultSet($productgallaryData)){
                    foreach ($productgallaryData as $key => $img) { 
                        echo '<div class="col-md-3 col-sm-3"><div class="gallery imagebox"><input type="hidden" id="gallary_img_id" value="'.$img->id.'"/><img class="fileinput-new thumbnail" style="width: 90px; height: 65px; margin:1px; display: inline;" src="'.UPLOADPATH.'/product_gallary_images/'.$img->image.'"><span class="count btn btn-danger rounded remove"><i class="fa fa-times "></i></span></div></div>';

                        
                        }
                    }
                  ?>   
                      
                </div>
                <br>
                <div class=" col-sm-12 form-group" style=" margin-top: 12px; padding: 0;">
                   <button type="submit" name="btnAddProduct" class="btn btn-primary btnAddProduct"><?php echo isset($productData->productName) ? $this->lang->line('buttonUpdate') : $this->lang->line('buttonSave'); ?></button>
              
          </div>

    </div><!--you_requested-->

      </form>
<?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>
  <style type="text/css">

    .stock-images .image {

      width: 100%;
      height: 100px;
      background-position: center center;
      background-color: gray;
    }

    .stock-images [type="radio"] + label:before,
    .stock-images [type="radio"] + label:after {
      display: none;
    }
    .stock-images [type="radio"] + label {
      width: 100%;
      height: 100px;
      padding: 0;
    }
    .stock-images [type="radio"]:not(:checked) + label .image {
      border: 5px solid white;
    }
    .stock-images [type="radio"]:checked + label .image {
      border: 5px solid aqua;
      opacity: 1;
    }
    .modal-content {
      position: relative;
      height: auto;
      width: 904px;
      background-repeat: no-repeat;
      background-color: #ddd;
    }
    .modal-dialog {
      width: 900px;
      margin: 30px auto;
    }
    .modal-content {
      background-image: unset !important;
    }
    .modal-body {
     overflow-y: scroll;
     height: 450px;
    }
    .modal-body::-webkit-scrollbar {
      width: 8px;
    }

    .modal-body::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 50px;
    }

    .modal-body::-webkit-scrollbar-thumb {
      background: #6d6d6d;
      border-radius: 50px;
    }
    .loading {
      text-align: center;
    }
    .libray form {
      display: flex;
      align-items: center;
    }

    .libray form p {
      padding-right: 20px;
    }
    .libray [type="radio"]:not(:checked)+label, [type="radio"]:checked+label {
      position: relative;
      padding-left: 35px !important;
      cursor: pointer;
      display: inline-block;
      transition: .28s ease;
      user-select: none;
      font-size: 20px !important;
      color: #000;
      text-transform: uppercase;
      font-weight: normal;
      line-height: 13px;
    }

    .stock-images h2 {
      font-size: 30px;
      text-transform: uppercase;
      font-weight: bold;
      margin-bottom: 0px;
      color: #000;
    }

    .stock-images h3 {
      margin-top: 6px;
      font-size: 20px;
      font-style: italic;
      color: #000;
    }
    .libray label {
      font-size: 20px;
      text-transform: uppercase;
      color: #000;
      font-weight: normal;
    }
    .libray [type="radio"]:not(:checked)+label:before, [type="radio"]:not(:checked)+label:after {
      border: 2px solid #5a5a5a;
      width: 20px;
      height: 20px;
      border-radius: 0 !important;
    }
    .libray [type="radio"]:checked+label:before {
      border: 2px solid #000;
      width: 20px;
      height: 20px;
      border-radius: 0px;
    }
    .libray [type="radio"]:checked+label:after, [type="radio"].with-gap:checked+label:after {
      display: inline-block;
      font: normal normal normal 14px/1 FontAwesome;
      font-size: inherit;
      text-rendering: auto;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      content: "\f00d";
      position: absolute;
      left: -1px;
      background-color: transparent;
      border: 0;
      top: -3px;
    }


    .stock-images [type="radio"]:not(:checked)+label, .stock-images [type="radio"]:checked+label {
      position: relative;
      padding-left: 0px !important;
      cursor: pointer;
      display: inline-block;
      transition: .28s ease;
      user-select: none;
      font-size: 20px !important;
      color: #000;
      text-transform: uppercase;
      font-weight: normal;
      line-height: 13px;
    }


    .stock-images [type="radio"]:not(:checked)+label, [type="radio"]:checked+label {
      padding-left: 0px;

    }
  </style>
  <div id="mappopup" class="modal fade image-modal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h2><?php echo $this->lang->line('VEDMIRImageLibray');?></h2>
        </div>
        <div class="modal-body">
          <div class="libray">
              <div class="row">
                <div class="col-md-12">
                  <form action="#">
                    <p>
                      <!-- <input type="radio" id="test1" name="radio-group"> -->
                      <label for="test1"><?php echo $this->lang->line('IamLookingFor');?>I am looking for</label>
                    </p>
                    <p onclick="bindfilteredimages(this,event)">
                      <input type="radio" id="drink-radio" name="imgType" value="2">
                      <label for="drink-radio"><?php echo $this->lang->line('drink');?></label>
                    </p>
                    <p onclick="bindfilteredimages(this,event)">
                      <input type="radio" id="food-radio" name="imgType" value="1">
                      <label for="food-radio"><?php echo $this->lang->line('food');?></label>
                    </p>
                  </form>
                </div>
              </div>
          </div>
          <div class="stock-images row libray-images">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class=" btn btn-info btn-sm " data-dismiss="modal"><?php echo $this->lang->line('close');?></button>
        </div>
      </div>
    </div>
  </div>
        <script type="text/javascript">
          var counter = <?=($counter > 0)?$counter:0?>;

          /* change Item Type--------------------------*/
          function changeProductType() {
              var obj = $('#productType');

              $('.variableitem').find('input[type=text],input[type=password],input[type=email],input[type=file],textarea,select').each(function () {
                $(this).removeAttr('required');
              });

              if (obj.val() ==1) {

                $("#variable-<?=$this->sessLang?>").find('input[type=text],input[type=password],input[type=email],input[type=file],textarea,select').each(function () {                 
                  $(this).attr('required','required');
                });
                $('.pricediv').css('display','none');
                $('.variableitem').css('display','block');
              }else{
                $('.pricediv').css('display','block');
                $('.variableitem').css('display','none');               
              }
          }
        
          $('.add-more-variable').click(function(){
            var obj_en = $(this).closest('div.variableitem').find('div#variable-english').find('div.variableitemaddmore');
            var obj_fr = $(this).closest('div.variableitem').find('div#variable-french').find('div.variableitemaddmore');
            var obj_gr = $(this).closest('div.variableitem').find('div#variable-german').find('div.variableitemaddmore');
            var obj_it = $(this).closest('div.variableitem').find('div#variable-italian').find('div.variableitemaddmore');
            

            $('<div class="col-md-12 variableitemdetails box-'+counter+'" data-counter="'+counter+'"><div class="form-group"><a class="label label-danger rounded removevariable" onclick="removevariable(\'box-'+counter+'\')"><i class="fa fa-times "></i></a><label><?=$this->lang->line('variableItemName');?> (EN)<span class="text-danger">*</span></label><input type="text" class="form-control input-sm" min="0"  placeholder="<?=$this->lang->line('variableItemName');?>" name="variableName[]"><input type="hidden" name="variableItemId[]" value="0"></div><div class="form-group"><label><?=$this->lang->line('variableItemPrice');?> (EN)<span class="text-danger">*</span></label><input type="text" min="0"  placeholder="<?=$this->lang->line('variableItemPrice');?>" name="variableItemPrice[]" value="0" class="form-control input-sm price-'+counter+'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'+counter+'\')"></div><div class="form-group '+(($('#isAvailableInFree').is(":checked"))?'':'hide')+'" ><label class="checkbox-inline"><p><input type="checkbox" class="welcomeCheckbox" id="welcomeCheckbox'+counter+'" name="welcomeCheckbox[]" value="1" onchange="welcomeCheckboxChange(this, event)"><label for="welcomeCheckbox'+counter+'"><?php echo $this->lang->line('isAvailableInFree');?> </label><input type="hidden" name="isAvailableInWelcome[]" class="isAvailableInWelcome" value="0"> </p></label></div></div>').insertBefore(obj_en);
            $('<div class="col-md-12 variableitemdetails box-'+counter+'" data-counter="'+counter+'"><div class="form-group"><a class="label label-danger rounded removevariable" onclick="removevariable(\'box-'+counter+'\')"><i class="fa fa-times "></i></a><label><?=$this->lang->line('variableItemName');?> (FR)<span class="text-danger">*</span></label><input type="text" class="form-control input-sm" min="0"  placeholder="<?=$this->lang->line('variableItemName');?>" name="variableName_fr[]"></div><div class="form-group"><label><?=$this->lang->line('variableItemPrice');?> (FR)<span class="text-danger">*</span></label><input type="text" placeholder="<?=$this->lang->line('variableItemPrice');?>" name="variableItemPrice_fr[]" value="0" class="form-control input-sm price-'+counter+'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'+counter+'\')"></div><div class="form-group '+(($('#isAvailableInFree').is(":checked"))?'':'hide')+'" ><label class="checkbox-inline"><p><input type="checkbox" class="welcomeCheckbox" id="welcomeCheckbox_fr'+counter+'" name="welcomeCheckbox_fr[]" value="1" onchange="welcomeCheckboxChange(this, event)"><label for="welcomeCheckbox_fr'+counter+'"><?php echo $this->lang->line('isAvailableInFree');?> </label><input type="hidden" name="isAvailableInWelcome_fr[]" class="isAvailableInWelcome" value="0"> </p></label></div></div>').insertBefore(obj_fr);
            $('<div class="col-md-12 variableitemdetails box-'+counter+'" data-counter="'+counter+'"><div class="form-group"><a class="label label-danger rounded removevariable" onclick="removevariable(\'box-'+counter+'\')"><i class="fa fa-times "></i></a><label><?=$this->lang->line('variableItemName');?> (GR)<span class="text-danger">*</span></label><input type="text" class="form-control input-sm" min="0"  placeholder="<?=$this->lang->line('variableItemName');?>" name="variableName_gr[]"></div><div class="form-group"><label><?=$this->lang->line('variableItemPrice');?> (GR)<span class="text-danger">*</span></label><input type="text" placeholder="<?=$this->lang->line('variableItemPrice');?>" name="variableItemPrice_gr[]" value="0" class="form-control input-sm price-'+counter+'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'+counter+'\')"></div><div class="form-group '+(($('#isAvailableInFree').is(":checked"))?'':'hide')+'" ><label class="checkbox-inline"><p><input type="checkbox" class="welcomeCheckbox" id="welcomeCheckbox_gr'+counter+'" name="welcomeCheckbox_gr[]" value="1" onchange="welcomeCheckboxChange(this, event)"><label for="welcomeCheckbox_gr'+counter+'"><?php echo $this->lang->line('isAvailableInFree');?> </label><input type="hidden" name="isAvailableInWelcome_gr[]" class="isAvailableInWelcome" value="0"> </p></label></div></div>').insertBefore(obj_gr);
            $('<div class="col-md-12 variableitemdetails box-'+counter+'" data-counter="'+counter+'"><div class="form-group"><a class="label label-danger rounded removevariable" onclick="removevariable(\'box-'+counter+'\')"><i class="fa fa-times "></i></a><label><?=$this->lang->line('variableItemName');?> (IT)<span class="text-danger">*</span></label><input type="text" class="form-control input-sm" min="0"  placeholder="<?=$this->lang->line('variableItemName');?>" name="variableName_it[]"></div><div class="form-group"><label><?=$this->lang->line('variableItemPrice');?> (IT)<span class="text-danger">*</span></label><input type="text" min="0"  placeholder="<?=$this->lang->line('variableItemPrice');?>" name="variableItemPrice_it[]" value="0" class="form-control input-sm price-'+counter+'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'+counter+'\')"></div><div class="form-group '+(($('#isAvailableInFree').is(":checked"))?'':'hide')+'" ><label class="checkbox-inline"><p><input type="checkbox" class="welcomeCheckbox" id="welcomeCheckbox_it'+counter+'" name="welcomeCheckbox_it[]" value="1" onchange="welcomeCheckboxChange(this, event)"><label for="welcomeCheckbox_it'+counter+'"><?php echo $this->lang->line('isAvailableInFree');?> </label><input type="hidden" name="isAvailableInWelcome_it[]" class="isAvailableInWelcome" value="0"> </p></label></div></div>').insertBefore(obj_it);
            ++counter;
            changeProductType();

          }); 

          function removevariable(divClass){
              $('.'+divClass).remove();
          }

          function updatePrice(obj,divClass){
              $('.'+divClass).val($(obj).val());
          }

          function deleteVariable(id,divClass){

            if(confirm(GLOBALERRORS.variableDeleteMsg) === false)
                return false;
            var formdata={action:"Delete_Record",tab:"variable_product",id:id};
            var baseurl=DASHURL+'/admin/commonajax';
            $.ajax({
              type: 'POST',
              data: formdata,
              url: baseurl,
              success:function(response){
                $('.'+divClass).remove();
              },error: function(response){ alert('Failed');  }
            });
          }
       


          $('.add-more-image').click(function(){
              var obj = $(this);
              $('<div class="col-md-12 col-sm-12" style="margin-top: 5px;"><input name="txtgallaryImgs[]" data-error="Upload Property Gallery Image" type="file" class="pull-left"><span class="btn btn-danger btn-xs removeGalleryImage pull-right"><i class="fa fa-times"> <?php echo $this->lang->line('remove');?></i></span></div>').insertBefore(obj);
          });
          $(document).on('click','.removeGalleryImage',function(){
              $(this).closest('div').remove();
          });
          $(document).on('change','.checkstatuschar',function(){
              var classname =$(this).attr('data-id');
              $('input.'+classname).val($(this).val());
          });
          $('.remove').on('click', function(){

            if(confirm("<?php echo $this->lang->line('galleryDeleteMsg');?>") === false)
                return false;
            var obj=$(this);
            // openpoploader();
            var img_id=$(obj).closest('.gallery').find('input[type=hidden]').val();
            var formdata={action:"removeGalleryImage",img_id:img_id};
            var baseurl=DASHURL+'/admin/product/remove_gallery_image';
            $.ajax({
              type: 'POST',
              data: formdata,
              url: baseurl,
              success:function(response){
                // removepoploader();
                  if(response=='deleted'){
                    $(obj).closest('div.col-sm-3').remove();
                  }else{
                    alert('Failed');
                  }
              },error: function(response){ alert('Failed');  }
            });
          });
        /* get Sub category
        -------------------------------------------------------------------*/
        $(document).ready(function(){ 
          checkcategorydata();
        })

        function checkcategorydata(){

          var category = $('option:selected', $('#selCategory')).attr('data-value');
          if (category == GLOBALERRORS.drink) {
            $('#selSubcategoryId').removeAttr('required');
            // $('#subcategoryDiv').hide();
            // $('#subcategoryitemDiv').hide();
            // $('#selSubcategoryitemId option:selected').prop('selected', false);

          }else{
            $('#selSubcategoryId').attr('required', 'required');
            // $('#subcategoryDiv').show();
            // $('#subcategoryitemDiv').show();
          }

        }
        
        $('#selRestaurant').change(function () {

          $('#selCategory option:selected').prop('selected', false);
          $('#selSubcategoryId option:selected').prop('selected', false);
          $('#selSubcategoryitemId option:selected').prop('selected', false);
          $('#subcategoryitemDiv').css('display','none');
        });

        $('#selCategory').change(function () {
            
            checkcategorydata();
            var selCategory = $('#selCategory').val();
            var restaurantId = $('#selRestaurant').val();
            if (restaurantId < 1) {
                ($('#selRestaurant').closest("div").find('label.error').length>0)?"":$('#selRestaurant').closest("div").append('<label class="error" style="color:#DC3C1E;">This field is required.</label>');
                $('option:selected', this).removeAttr('selected');
                return false;
                
            }

            $('#selRestaurant').closest("div").find('label.error').remove();
            if($(this).val() == 5){
                $('#isAvailableInFree,#isOnlyForGirl').closest('div.form-group').removeClass('hide');
            }else{
                $('#isAvailableInFree,#isOnlyForGirl,#doNotIncludeInTheMenu').closest('div.form-group').addClass('hide');
                $('#isAvailableInFree,#isOnlyForGirl,#doNotIncludeInTheMenu').prop('checked', false);
                showHideAllWelcomeCheckbox (0)
            }
            
               
            $.ajax({
                type: "POST",
                url: DASHURL+"/admin/commonajax",
                data:  { selCategoryId: selCategory, restaurantId: restaurantId,action: 'getProductSubcategory' },

                //success
                success: function (data) { //console.log(data);
                     $('#selSubcategoryId').html(data.optionData);
                        // BlankonFormElement.init();
                },
                error: function (data) {
                    alert("try again");
                  
                }

            }) //end ajax call
            return false;
        });

        $('#isAvailableInFree').change(function () {
          if ($(this).prop("checked")){
            $('#doNotIncludeInTheMenu').closest('div.form-group').removeClass('hide');

            showHideAllWelcomeCheckbox (1)
          }
          else{
            $('#doNotIncludeInTheMenu').prop('checked', false);
            $('#doNotIncludeInTheMenu').closest('div.form-group').addClass('hide');

            showHideAllWelcomeCheckbox (0)
          }
        });
        function showHideAllWelcomeCheckbox ($isChecked = 0) {

          if ($isChecked){
            $("input[name='welcomeCheckbox[]'], input[name='welcomeCheckbox_fr[]'],input[name='welcomeCheckbox_gr[]'],input[name='welcomeCheckbox_it[]']").closest('div.form-group').removeClass('hide');
          }
          else{
            $("input[name='welcomeCheckbox[]'], input[name='welcomeCheckbox_fr[]'],input[name='welcomeCheckbox_gr[]'],input[name='welcomeCheckbox_it[]']").prop('checked', false).closest('div.form-group').addClass('hide');
            $("input[name='welcomeCheckbox[]'], input[name='welcomeCheckbox_fr[]'],input[name='welcomeCheckbox_gr[]'],input[name='welcomeCheckbox_it[]']").closest('p').find('input.isAvailableInWelcome').val(0);
          }

        }
        function welcomeCheckboxChange (obj, e) {
          var myCounter = $(obj).closest("div.variableitemdetails").attr('data-counter');
          if ($(obj).prop("checked")){
            $(obj).closest('div.tab-content').find("div.box-"+myCounter).each(function(){
              debugger;
              $(this).find("input.welcomeCheckbox").prop('checked', true).closest('p').find('input.isAvailableInWelcome').val(1);
            });
          }else{
            $(obj).closest('div.tab-content').find("div.box-"+myCounter).each(function(){
              $(this).find("input.welcomeCheckbox").prop('checked', false).closest('p').find('input.isAvailableInWelcome').val(0);
            });
          }
        }
    </script>    
    </body>
    <!--/ END BODY -->

</html>