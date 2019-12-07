<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
  <?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>

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
    <form action="" method="POST" enctype="multipart/form-data" novalidate="">
      <div class="you_requested">
        <h3><?= (isset($productData->productName)) ? $this->lang->line('copy') : $this->lang->line('newForm');?><span> <?php echo $this->lang->line('productPageHeading');?></span> <span><a href="<?=DASHURL.'/admin/product/product-list'?>" class="btn btn-info" style="float: right;" ><?=$this->lang->line('productList');?></a></span></h3>

        <div class="expert_breif">
          <div class="image-upload" style="">
            <label for="file-input">
              <img src="<?php echo (isset($productData->img) && $productData->img !='') ?$productData->img:DASHSTATIC.'/restaurant/assets/img/uplod.png' ?>" width="200px" height="200px"  aria-hidden="true" href="#mappopup" role="button" data-toggle="modal"  id="previewImg"  onclick="getFilteredImageList(this, event);">                
            </label>
          </div>
          <input class="file-input hide" name="uploadImg" id="uploadImg" type="text" <?php echo (!isset($productData->img) || empty($productData->img))?'required="required"':'value = "'.$productData->imageId.'"' ?>/>
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

                <?php }
                }  ?>

            </select>
          </div>
          <div class="form-group">
            <label ><?php echo $this->lang->line('selectType');?><span class="asterisk">*</span></label>
            <select class="form-control" name="selCategoryId" id="selCategory" required>
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
            </select>
          </div><!-- /.form-group -->
          <div class="form-group" id="subcategoryitemDiv" style="display: <?=(isset($subcategoryitemData) && !empty($subcategoryitemData))?'block':'none'?>">
            <label ><?php echo $this->lang->line('selectSubCategoryLabel');?><span class="asterisk">*</span></label>
            <select class="form-control" name="selSubcategoryitemId" id="selSubcategoryitemId" <?=(isset($subcategoryitemData) && !empty($subcategoryitemData))?'required="required"':''?>>
              <option value=""><?php echo $this->lang->line('selectSubCategoryLabel');?></option>
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
                <label ><?php echo $this->lang->line('productNameLabel');?> (En) <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="txtProductName" name="txtProductName" value="<?php echo isset($productData->productName) ? $productData->productName : ''; ?>" required="required">
              </div>

              <div class="form-group">
                <label><?php echo $this->lang->line('productDescription');?> (En) <span class="text-danger">*</span></label>

                <textarea id="txtsortDescription" name="txtsortDescription" class="form-control " maxlength="255" rows="3" placeholder="<?php echo $this->lang->line('productDescription');?> En"><?php echo isset($productData->sortDescription) ? $productData->sortDescription : ''; ?></textarea>

              </div><!-- /.form-group -->                   
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
                $encontent = '<div class="col-md-12 variableitemdetails box-'.$counter.'" >
                <div class="form-group">
                <label>'.$this->lang->line('variableItemName').' (EN)<span class="text-danger">*</span></label>
                <input type="text" class="form-control input-sm" min="0" placeholder="'.$this->lang->line('variableItemName').'"   name="variableName[]">
                <input type="hidden" name="variableItemId[]" value="0">
                </div>
                <div class="form-group">
                <label>'.$this->lang->line('variableItemPrice').' (EN)<span class="text-danger">*</span></label>
                <input min="0" placeholder="'.$this->lang->line('variableItemPrice').'" name="variableItemPrice[]" value="0" class="form-control input-sm price-'.$counter.'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'.$counter.'\')">
                </div>
                </div>';
                $frcontent = '<div class="col-md-12 variableitemdetails box-'.$counter.'" >
                <div class="form-group">
                <label>'.$this->lang->line('variableItemName').' (FR)<span class="text-danger">*</span></label>
                <input type="text" class="form-control input-sm" min="0" placeholder="'.$this->lang->line('variableItemName').'"   name="variableName_fr[]">

                </div>
                <div class="form-group">
                <label>'.$this->lang->line('variableItemPrice').' (FR)<span class="text-danger">*</span></label>
                <input type="text" placeholder="'.$this->lang->line('variableItemPrice').'" name="variableItemPrice_fr[]" value="0"  class="form-control input-sm price-'.$counter.'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'.$counter.'\')">
                </div>
                </div>';
                $grcontent = '<div class="col-md-12 variableitemdetails box-'.$counter.'" >
                <div class="form-group">
                <label>'.$this->lang->line('variableItemName').' (GR)<span class="text-danger">*</span></label>
                <input type="text" class="form-control input-sm" min="0" placeholder="'.$this->lang->line('variableItemName').'"   name="variableName_gr[]" >

                </div>
                <div class="form-group">
                <label>'.$this->lang->line('variableItemPrice').' (GR)<span class="text-danger">*</span></label>
                <input type="text" min="0" placeholder="'.$this->lang->line('variableItemPrice').'" name="variableItemPrice_gr[]" value="0" class="form-control input-sm price-'.$counter.'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'.$counter.'\')">
                </div>
                </div>';
                $itcontent = '
                <div class="col-md-12 variableitemdetails box-'.$counter.'" >
                <div class="form-group">
                <label>'.$this->lang->line('variableItemName').' (IT)<span class="text-danger">*</span></label>
                <input type="text" class="form-control input-sm" min="0" placeholder="'.$this->lang->line('variableItemName').'"   name="variableName_it[]">

                </div>
                <div class="form-group">
                <label>'.$this->lang->line('variableItemPrice').' (IT)<span class="text-danger">*</span></label>
                <input type="text" min="0" placeholder="'.$this->lang->line('variableItemPrice').'" name="variableItemPrice_it[]" value="0" class="form-control input-sm price-'.$counter.'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'.$counter.'\')">
                </div>
                </div>';

                if (isset($variableProductData) && valResultSet($variableProductData)) {
                  $encontent = $frcontent = $grcontent = $itcontent = '';
                  foreach ($variableProductData as $variablekey => $variableProduct) {
                    $encontent .= '<div class="col-md-12 variableitemdetails box-'.$counter.'">
                    <div class="form-group">
                    <a class="label label-danger rounded deletevariable" onclick="deleteVariable('.$variableProduct->variableId.',\'box-'.$counter.'\')" ><i class="fa fa-trash-o"></i> '.$this->lang->line("delete").'</a>
                    <label>'.$this->lang->line('variableItemName').' (EN)<span class="text-danger">*</span></label>
                    <input type="text" class="form-control input-sm" min="0"  placeholder="'.$this->lang->line('variableItemName').'" name="variableName[]" value="'.$variableProduct->variableName.'">
                    <input type="hidden" name="variableItemId[]" value="0">
                    </div>
                    <div class="form-group">
                    <label>'.$this->lang->line('variableItemPrice').' (EN)<span class="text-danger">*</span></label>
                    <input type="text" min="0"  placeholder="'.$this->lang->line('variableItemPrice').'" name="variableItemPrice[]"  value="'.$variableProduct->price.'" class="form-control input-sm price-'.$counter.'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'.$counter.'\')">
                    </div>
                    </div>';

                    $frcontent .= '<div class="col-md-12 variableitemdetails box-'.$counter.'">
                    <div class="form-group">
                    <a class="label label-danger rounded deletevariable" onclick="deleteVariable('.$variableProduct->variableId.',\'box-'.$counter.'\')" ><i class="fa fa-trash-o"></i> '.$this->lang->line("delete").'</a>
                    <label>'.$this->lang->line('variableItemName').' (FR)<span class="text-danger">*</span></label>
                    <input type="text" class="form-control input-sm" min="0"  placeholder="'.$this->lang->line('variableItemName').'" name="variableName_fr[]" value="'.$variableProduct->variableName_fr.'">
                    </div>
                    <div class="form-group">
                    <label>'.$this->lang->line('variableItemPrice').' (FR)<span class="text-danger">*</span></label>
                    <input type="text" placeholder="'.$this->lang->line('variableItemPrice').'" name="variableItemPrice_fr[]"  value="'.$variableProduct->price.'" class="form-control input-sm price-'.$counter.'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'.$counter.'\')">
                    </div>
                    </div>';

                    $grcontent .= '<div class="col-md-12 variableitemdetails box-'.$counter.'">
                    <div class="form-group">
                    <a class="label label-danger rounded deletevariable" onclick="deleteVariable('.$variableProduct->variableId.',\'box-'.$counter.'\')" ><i class="fa fa-trash-o"></i> '.$this->lang->line("delete").'</a>
                    <label>'.$this->lang->line('variableItemName').' (GR)<span class="text-danger">*</span></label>
                    <input type="text" class="form-control input-sm" min="0"  placeholder="'.$this->lang->line('variableItemName').'" name="variableName_gr[]" value="'.$variableProduct->variableName_gr.'">
                    </div>
                    <div class="form-group">
                    <label>'.$this->lang->line('variableItemPrice').' (GR)<span class="text-danger">*</span></label>
                    <input type="text" placeholder="'.$this->lang->line('variableItemPrice').'" name="variableItemPrice_gr[]"  value="'.$variableProduct->price.'" class="form-control input-sm price-'.$counter.'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'.$counter.'\')">
                    </div>
                    </div>';

                    $itcontent .= '<div class="col-md-12 variableitemdetails box-'.$counter.'">
                    <div class="form-group">
                    <a class="label label-danger rounded deletevariable" onclick="deleteVariable('.$variableProduct->variableId.',\'box-'.$counter.'\')" ><i class="fa fa-trash-o"></i> '.$this->lang->line("delete").'</a>
                    <label>'.$this->lang->line('variableItemName').' (IT)<span class="text-danger">*</span></label>
                    <input type="text" class="form-control input-sm" min="0"  placeholder="'.$this->lang->line('variableItemName').'" name="variableName_it[]" value="'.$variableProduct->variableName_it.'">
                    </div>
                    <div class="form-group">
                    <label>'.$this->lang->line('variableItemPrice').' (IT)<span class="text-danger">*</span></label>
                    <input type="text" placeholder="'.$this->lang->line('variableItemPrice').'" name="variableItemPrice_it[]"  value="'.$variableProduct->price.'" class="form-control input-sm price-'.$counter.'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'.$counter.'\')">
                    </div>
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
          <?php 
            if(isset($addonProductData) && !empty($addonProductData)){
          ?>
          <style type="text/css">
            .variableitem{background-color: #f1f1f1;padding: 7px;border-radius: 4px;}
            a.label.label-danger.rounded.removevariable, a.label.label-danger.rounded.deletevariable{float:right !important;}
            .variableitemdetails,.variableaddonitemdetails{border: 1px solid #736f6f;margin: 5px 0;padding: 10px;}.variableaddonitemdetails{background: #f0f0f0;}
            .variableitemaddmore{margin-bottom: 10px;padding: 10px;}
            .add-more-variable{margin: 5px;}
            label.checkbox-inline {margin-top: 15px;padding: 0;}
            .addonsitem {padding: 7px; background-color: aliceblue;}
          </style> 
          <br>
          <label for="addons"><?php echo $this->lang->line('addons');?> </label>
          <div class="col-md-12 addonsitem">
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="<?=($this->sessLang == 'english') ? 'active' : '';?>"><a href="#addons-english" aria-controls="english" role="tab" data-toggle="tab">En</a></li>
              <li role="presentation" class="<?=($this->sessLang == 'french') ? 'active' : '';?>"><a href="#addons-french" aria-controls="french" role="tab" data-toggle="tab">Fr</a></li>
              <li role="presentation" class="<?=($this->sessLang == 'german') ? 'active' : '';?>"><a href="#addons-german" aria-controls="german" role="tab" data-toggle="tab">Gr</a></li>
              <li role="presentation" class="<?=($this->sessLang == 'italian') ? 'active' : '';?>"><a href="#addons-italian" aria-controls="italian" role="tab" data-toggle="tab">It</a></li>               
            </ul>
            <div class="tab-content">
              <div class="clearfix"></div>
              <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'english') ? 'active' : '';?>" id="addons-english">
                <?php
                  $enAddonsContent=$frAddonsContent=$grAddonsContent=$itAddonsContent='';
                  $addonsCounter = $addonsKeyCounter = 0;
                  $addonsKey =0;
                  if(isset($addonProductData) && !empty($addonProductData)){
                    foreach ($addonProductData as $categoryKey => $category) {

                      $isCategoryEnableChecked = ($category->isStockAvailable)?'checked':'';
                      $isCategoryRequiredChecked = ($category->required)?'checked':'';
                      $isChoise2Selected = ($category->choice)?'selected':'';

                      $deleteBtn = '<a onclick="delete_addons(this,\'product_addons_category\','.$category->addonsCatId.',\'box-'.$addonsCounter.'\');" class="label label-danger rounded removevariable" title="Delete"><span class="fa fa-trash-o"></span></a>';
                      $enAddonsContent .= '<div class="col-md-12 variableitemdetails box-'.$addonsCounter.'" >'.$deleteBtn.'
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsCategoryName').' (En)<span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm addoncat" placeholder="'.$this->lang->line('prodAddonsCategoryName').'"   name="prodAddonsCategoryName['.$addonsCounter.']" value="'.$category->categoryName.'">
                            <input type="hidden" name="addonsCatId['.$addonsCounter.']"  value="0" />
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsCategoryRequired').' (En)<span class="text-danger">*</span></label>
                            <input type="checkbox" class="form-control isreq-'.$addonsCounter.'" style="left: 0;" onclick="autoCheckEnableBox(\'isreq-'.$addonsCounter.'\',this.checked)" value="1" '.$isCategoryRequiredChecked.' name="prodAddonsCategoryRequired['.$addonsCounter.']" />
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsCategoryChoice').' (En)<span class="text-danger">*</span></label>
                            <select name="prodAddonsCatChoice['.$addonsCounter.']" class="form-control input-sm catchoice-'.$addonsCounter.'" onchange="setCatChoice(\'catchoice-'.$addonsCounter.'\',this.value);">
                              <option value="0">'.$this->lang->line('prodAddonsCategoryChoice1').'</option>
                              <option value="1" '.$isChoise2Selected.'>'.$this->lang->line('prodAddonsCategoryChoice2').'</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsStatus').' (En)<span class="text-danger">*</span></label>
                            <input type="checkbox" value="1" class="form-control instock-'.$addonsCounter.'"  '.$isCategoryEnableChecked.' style="left: 0;" name="prodAddonsCatStatus['.$addonsCounter.']" onclick="autoCheckEnableBox(\'instock-'.$addonsCounter.'\',this.checked)" />
                          </div>
                        </div>
                        <br/>';

                      $frAddonsContent .= '<div class="col-md-12 variableitemdetails box-'.$addonsCounter.'" >'.$deleteBtn.'
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsCategoryName').' (Fr)<span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm addoncat" placeholder="'.$this->lang->line('prodAddonsCategoryName').'"   name="prodAddonsCategoryName_fr['.$addonsCounter.']" value="'.$category->categoryName_fr.'">
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsCategoryRequired').' (Fr)<span class="text-danger">*</span></label>
                            <input type="checkbox" class="form-control isreq-'.$addonsCounter.'" style="left: 0;" onclick="autoCheckEnableBox(\'isreq-'.$addonsCounter.'\',this.checked)" value="1" '.$isCategoryRequiredChecked.' name="prodAddonsCategoryRequired['.$addonsCounter.']" />
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsCategoryChoice').' (Fr)<span class="text-danger">*</span></label>
                            <select name="prodAddonsCatChoice['.$addonsCounter.']" class="form-control input-sm catchoice-'.$addonsCounter.'" onchange="setCatChoice(\'catchoice-'.$addonsCounter.'\',this.value);">
                              <option value="0">'.$this->lang->line('prodAddonsCategoryChoice1').'</option>
                              <option value="1" '.$isChoise2Selected.'>'.$this->lang->line('prodAddonsCategoryChoice2').'</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsStatus').' (Fr)<span class="text-danger">*</span></label>
                            <input type="checkbox" value="1" class="form-control instock-'.$addonsCounter.'"  '.$isCategoryEnableChecked.' style="left: 0;" name="prodAddonsCatStatus['.$addonsCounter.']" onclick="autoCheckEnableBox(\'instock-'.$addonsCounter.'\',this.checked)" />
                          </div>
                        </div>
                        <br/>';




                      $grAddonsContent .= '<div class="col-md-12 variableitemdetails box-'.$addonsCounter.'" >'.$deleteBtn.'
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsCategoryName').' (Gr)<span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm addoncat" placeholder="'.$this->lang->line('prodAddonsCategoryName').'"   name="prodAddonsCategoryName_gr['.$addonsCounter.']" value="'.$category->categoryName_gr.'">
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsCategoryRequired').' (Gr)<span class="text-danger">*</span></label>
                            <input type="checkbox" class="form-control isreq-'.$addonsCounter.'" style="left: 0;" onclick="autoCheckEnableBox(\'isreq-'.$addonsCounter.'\',this.checked)" value="1" '.$isCategoryRequiredChecked.' name="prodAddonsCategoryRequired['.$addonsCounter.']" />
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsCategoryChoice').' (Gr)<span class="text-danger">*</span></label>
                            <select name="prodAddonsCatChoice['.$addonsCounter.']" class="form-control input-sm catchoice-'.$addonsCounter.'" onchange="setCatChoice(\'catchoice-'.$addonsCounter.'\',this.value);">
                              <option value="0">'.$this->lang->line('prodAddonsCategoryChoice1').'</option>
                              <option value="1" '.$isChoise2Selected.'>'.$this->lang->line('prodAddonsCategoryChoice2').'</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsStatus').' (Gr)<span class="text-danger">*</span></label>
                            <input type="checkbox" value="1" class="form-control instock-'.$addonsCounter.'"  '.$isCategoryEnableChecked.' style="left: 0;" name="prodAddonsCatStatus['.$addonsCounter.']" onclick="autoCheckEnableBox(\'instock-'.$addonsCounter.'\',this.checked)" />
                          </div>
                        </div>
                        <br/>';




                      $itAddonsContent .= '<div class="col-md-12 variableitemdetails box-'.$addonsCounter.'" >'.$deleteBtn.'
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsCategoryName').' (It)<span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm addoncat" placeholder="'.$this->lang->line('prodAddonsCategoryName').'"   name="prodAddonsCategoryName_it['.$addonsCounter.']" value="'.$category->categoryName_it.'">
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsCategoryRequired').' (It)<span class="text-danger">*</span></label>
                            <input type="checkbox" class="form-control isreq-'.$addonsCounter.'" style="left: 0;" onclick="autoCheckEnableBox(\'isreq-'.$addonsCounter.'\',this.checked)" value="1" '.$isCategoryRequiredChecked.' name="prodAddonsCategoryRequired['.$addonsCounter.']" />
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsCategoryChoice').' (It)<span class="text-danger">*</span></label>
                            <select name="prodAddonsCatChoice['.$addonsCounter.']" class="form-control input-sm catchoice-'.$addonsCounter.'" onchange="setCatChoice(\'catchoice-'.$addonsCounter.'\',this.value);">
                              <option value="0">'.$this->lang->line('prodAddonsCategoryChoice1').'</option>
                              <option value="1" '.$isChoise2Selected.'>'.$this->lang->line('prodAddonsCategoryChoice2').'</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsStatus').' (It)<span class="text-danger">*</span></label>
                            <input type="checkbox" value="1" class="form-control instock-'.$addonsCounter.'"  '.$isCategoryEnableChecked.' style="left: 0;" name="prodAddonsCatStatus['.$addonsCounter.']" onclick="autoCheckEnableBox(\'instock-'.$addonsCounter.'\',this.checked)" />
                          </div>
                        </div>
                        <br/>';



                      if(isset($category->addonsItem) && !empty($category->addonsItem)){  
                        foreach ($category->addonsItem as $key => $addons) {
                          $isAddonsEnableChecked = ($addons->isStockAvailable)?'checked':'';
                        
                          $enAddonsContent .= '<div class="col-md-12 variableaddonitemdetails box-'.$addonsCounter.'" >
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label>'.$this->lang->line('prodAddonsName').' (En)<span class="text-danger">*</span></label>
                                  <input type="text" class="form-control input-sm addonname" placeholder="'.$this->lang->line('prodAddonsName').'"   name="prodAddonsName['.$addonsCounter.']['.$addonsKey.']" value="'.$addons->addonsName.'">
                                  <input type="hidden" name="addonsId['.$addonsCounter.']['.$addonsKey.']" value="0" />
                                
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label>'.$this->lang->line('prodAddonsPrice').' (En)<span class="text-danger">*</span></label>
                                <input min="0" placeholder="'.$this->lang->line('prodAddonsPrice').'" name="prodAddonsPrice['.$addonsCounter.']['.$addonsKey.']" value="'.$addons->price.'" onchange="autoSetPrice(this,\'price-'.$addonsCounter.'-'.$addonsKey.'\')" class="form-control input-sm price-'.$addonsCounter.'-'.$addonsKey.'" onkeydown="OnlyNumericKey(event)">
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label>'.$this->lang->line('prodAddonsStatus').' (En)<span class="text-danger">*</span></label>
                                <input type="checkbox" value="1" '.$isAddonsEnableChecked.' class="form-control addonsts-'.$addonsCounter.'-'.$addonsKey.'" onclick="autoCheckEnableBox(\'addonsts-'.$addonsCounter.'-'.$addonsKey.'\',this.checked)"  style="left: 0;"  name="prodAddonsStatus['.$addonsCounter.']['.$addonsKey.']"  />
                              </div>
                            </div>
                            </div>';


                          $frAddonsContent .= '<div class="col-md-12 variableaddonitemdetails box-'.$addonsCounter.'" >
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label>'.$this->lang->line('prodAddonsName').' (Fr)<span class="text-danger">*</span></label>
                                  <input type="text" class="form-control input-sm addonname" placeholder="'.$this->lang->line('prodAddonsName').'"   name="prodAddonsName_fr['.$addonsCounter.']['.$addonsKey.']" value="'.$addons->addonsName_fr.'">
                                
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label>'.$this->lang->line('prodAddonsPrice').' (Fr)<span class="text-danger">*</span></label>
                                <input min="0" placeholder="'.$this->lang->line('prodAddonsPrice').'" name="prodAddonsPrice['.$addonsCounter.']['.$addonsKey.']" value="'.$addons->price.'" onchange="autoSetPrice(this,\'price-'.$addonsCounter.'-'.$addonsKey.'\')" class="form-control input-sm price-'.$addonsCounter.'-'.$addonsKey.'" onkeydown="OnlyNumericKey(event)">
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label>'.$this->lang->line('prodAddonsStatus').' (Fr)<span class="text-danger">*</span></label>
                                <input type="checkbox" value="1" '.$isAddonsEnableChecked.' class="form-control addonsts-'.$addonsCounter.'-'.$addonsKey.'" onclick="autoCheckEnableBox(\'addonsts-'.$addonsCounter.'-'.$addonsKey.'\',this.checked)"  style="left: 0;"  name="prodAddonsStatus['.$addonsCounter.']['.$addonsKey.']"  />
                              </div>
                            </div>
                            </div>';


                          $grAddonsContent .= '<div class="col-md-12 variableaddonitemdetails box-'.$addonsCounter.'" >
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label>'.$this->lang->line('prodAddonsName').' (Gr)<span class="text-danger">*</span></label>
                                  <input type="text" class="form-control input-sm addonname" placeholder="'.$this->lang->line('prodAddonsName').'"   name="prodAddonsName_gr['.$addonsCounter.']['.$addonsKey.']" value="'.$addons->addonsName_gr.'">
                                
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label>'.$this->lang->line('prodAddonsPrice').' (Gr)<span class="text-danger">*</span></label>
                                <input min="0" placeholder="'.$this->lang->line('prodAddonsPrice').'" name="prodAddonsPrice['.$addonsCounter.']['.$addonsKey.']" value="'.$addons->price.'" onchange="autoSetPrice(this,\'price-'.$addonsCounter.'-'.$addonsKey.'\')" class="form-control input-sm price-'.$addonsCounter.'-'.$addonsKey.'" onkeydown="OnlyNumericKey(event)">
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label>'.$this->lang->line('prodAddonsStatus').' (Gr)<span class="text-danger">*</span></label>
                                <input type="checkbox" value="1" '.$isAddonsEnableChecked.' class="form-control addonsts-'.$addonsCounter.'-'.$addonsKey.'" onclick="autoCheckEnableBox(\'addonsts-'.$addonsCounter.'-'.$addonsKey.'\',this.checked)"  style="left: 0;"  name="prodAddonsStatus['.$addonsCounter.']['.$addonsKey.']"  />
                              </div>
                            </div>
                            </div>';


                          $itAddonsContent .= '<div class="col-md-12 variableaddonitemdetails box-'.$addonsCounter.'" >
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label>'.$this->lang->line('prodAddonsName').' (It)<span class="text-danger">*</span></label>
                                  <input type="text" class="form-control input-sm addonname" placeholder="'.$this->lang->line('prodAddonsName').'"   name="prodAddonsName_it['.$addonsCounter.']['.$addonsKey.']" value="'.$addons->addonsName_it.'">
                                
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label>'.$this->lang->line('prodAddonsPrice').' (It)<span class="text-danger">*</span></label>
                                <input min="0" placeholder="'.$this->lang->line('prodAddonsPrice').'" name="prodAddonsPrice['.$addonsCounter.']['.$addonsKey.']" value="'.$addons->price.'" onchange="autoSetPrice(this,\'price-'.$addonsCounter.'-'.$addonsKey.'\')" class="form-control input-sm price-'.$addonsCounter.'-'.$addonsKey.'" onkeydown="OnlyNumericKey(event)">
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label>'.$this->lang->line('prodAddonsStatus').' (It)<span class="text-danger">*</span></label>
                                <input type="checkbox" value="1" '.$isAddonsEnableChecked.' class="form-control addonsts-'.$addonsCounter.'-'.$addonsKey.'" onclick="autoCheckEnableBox(\'addonsts-'.$addonsCounter.'-'.$addonsKey.'\',this.checked)"  style="left: 0;"  name="prodAddonsStatus['.$addonsCounter.']['.$addonsKey.']"  />
                              </div>
                            </div>
                            </div>';
                          $addonsKey++;
                        }

                      }
                      $enAddonsContent .= '<div class="col-md-12 addonsaddcategorymore">
                            <a href="javascript:" class="btn btn-info add-more-addons"><i class="fa fa-plus"></i>'.$this->lang->line('addMore').'</a><br/>
                          </div>
                        </div>';
                      $frAddonsContent .= '<div class="col-md-12 addonsaddcategorymore">
                          <a href="javascript:" class="btn btn-info add-more-addons"><i class="fa fa-plus"></i>'.$this->lang->line('addMore').'</a><br/>
                          </div>
                        </div>';
                      $grAddonsContent .= '<div class="col-md-12 addonsaddcategorymore">
                          <a href="javascript:" class="btn btn-info add-more-addons"><i class="fa fa-plus"></i>'.$this->lang->line('addMore').'</a><br/>
                          </div>
                        </div>';
                      $itAddonsContent .= '<div class="col-md-12 addonsaddcategorymore">
                          <a href="javascript:" class="btn btn-info add-more-addons"><i class="fa fa-plus"></i>'.$this->lang->line('addMore').'</a><br/>
                          </div>
                        </div>';
                      $addonsCounter++;

                    }
                  }else{
                    $enAddonsContent = '<div class="col-md-12 variableitemdetails box-'.$addonsCounter.'" >
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>'.$this->lang->line('prodAddonsCategoryName').' (En)<span class="text-danger">*</span></label>
                          <input type="text" class="form-control input-sm addoncat" placeholder="'.$this->lang->line('prodAddonsCategoryName').'"   name="prodAddonsCategoryName['.$addonsCounter.']">
                          <input type="hidden" name="addonsCatId['.$addonsCounter.']" value="0" />
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>'.$this->lang->line('prodAddonsCategoryRequired').' (En)<span class="text-danger">*</span></label>
                          <input type="checkbox" value="1" class="form-control isreq-'.$addonsCounter.'" style="left: 0;" onclick="autoCheckEnableBox(\'isreq-'.$addonsCounter.'\',this.checked)" value="1"  name="prodAddonsCategoryRequired['.$addonsCounter.']" />
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>'.$this->lang->line('prodAddonsCategoryChoice').' (En)<span class="text-danger">*</span></label>
                          <select name="prodAddonsCatChoice['.$addonsCounter.']" class="form-control input-sm catchoice-'.$addonsCounter.'" onchange="setCatChoice(\'catchoice-'.$addonsCounter.'\',this.value);">
                            <option value="0">'.$this->lang->line('prodAddonsCategoryChoice1').'</option>
                            <option value="1">'.$this->lang->line('prodAddonsCategoryChoice2').'</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>'.$this->lang->line('prodAddonsStatus').' (En)<span class="text-danger">*</span></label>
                          <input type="checkbox" value="1" class="form-control instock-'.$addonsCounter.'" checked style="left: 0;" name="prodAddonsCatStatus['.$addonsCounter.']" onclick="autoCheckEnableBox(\'instock-'.$addonsCounter.'\',this.checked)" />
                        </div>
                      </div>
                      <br/>
                      <div class="col-md-12 variableaddonitemdetails box-'.$addonsCounter.'" >
                        <div class="col-md-6">
                          <div class="form-group">
                              <label>'.$this->lang->line('prodAddonsName').' (En)<span class="text-danger">*</span></label>
                              <input type="text" class="form-control input-sm addonname" placeholder="'.$this->lang->line('prodAddonsName').'"   name="prodAddonsName['.$addonsCounter.']['.$addonsKey.']">
                              <input type="hidden" name="addonsId['.$addonsCounter.']['.$addonsKey.']" value="0" />
                            
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsPrice').' (En)<span class="text-danger">*</span></label>
                            <input min="0" placeholder="'.$this->lang->line('prodAddonsPrice').'" name="prodAddonsPrice['.$addonsCounter.']['.$addonsKey.']" value="0" onchange="autoSetPrice(this,\'price-'.$addonsCounter.'-'.$addonsKey.'\')" class="form-control input-sm price-'.$addonsCounter.'-'.$addonsKey.'" onkeydown="OnlyNumericKey(event)">
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsStatus').' (En)<span class="text-danger">*</span></label>
                            <input type="checkbox" value="1" checked class="form-control addonsts-'.$addonsCounter.'-'.$addonsKey.'" onclick="autoCheckEnableBox(\'addonsts-'.$addonsCounter.'-'.$addonsKey.'\',this.checked)"  style="left: 0;"  name="prodAddonsStatus['.$addonsCounter.']['.$addonsKey.']"  />
                          </div>
                        </div>
                        </div>
                        <div class="col-md-12 addonsaddcategorymore">
                        <a href="javascript:" class="btn btn-info add-more-addons"><i class="fa fa-plus"></i>'.$this->lang->line('addMore').'</a><br/>
                        </div>
                      </div>';
                    $frAddonsContent = '<div class="col-md-12 variableitemdetails box-'.$addonsCounter.'" >
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>'.$this->lang->line('prodAddonsCategoryName').' (Fr)<span class="text-danger">*</span></label>
                          <input type="text" class="form-control input-sm addoncat" placeholder="'.$this->lang->line('prodAddonsCategoryName').'"   name="prodAddonsCategoryName_fr['.$addonsCounter.']">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>'.$this->lang->line('prodAddonsCategoryRequired').' (Fr)<span class="text-danger">*</span></label>
                          <input type="checkbox" value="1" class="form-control isreq-'.$addonsCounter.'" style="left: 0;" onclick="autoCheckEnableBox(\'isreq-'.$addonsCounter.'\',this.checked)" name="prodAddonsCategoryRequired['.$addonsCounter.']" />
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>'.$this->lang->line('prodAddonsCategoryChoice').' (Fr)<span class="text-danger">*</span></label>
                          <select name="prodAddonsCatChoice['.$addonsCounter.']" class="form-control input-sm catchoice-'.$addonsCounter.'" onchange="setCatChoice(\'catchoice-'.$addonsCounter.'\',this.value);">
                            <option value="0">'.$this->lang->line('prodAddonsCategoryChoice1').'</option>
                            <option value="1">'.$this->lang->line('prodAddonsCategoryChoice2').'</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>'.$this->lang->line('prodAddonsStatus').' (Fr)<span class="text-danger">*</span></label>
                          <input type="checkbox" value="1" class="form-control instock-'.$addonsCounter.'" checked style="left: 0;" onclick="autoCheckEnableBox(\'instock-'.$addonsCounter.'\',this.checked)"  name="prodAddonsCatStatus['.$addonsCounter.']" />
                        </div>
                      </div>
                      <br/>
                      <div class="col-md-12 variableaddonitemdetails box-'.$addonsCounter.'" >
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsName').' (Fr)<span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm addonname" placeholder="'.$this->lang->line('prodAddonsName').'"   name="prodAddonsName_fr['.$addonsCounter.']['.$addonsKey.']">
                            
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsPrice').' (Fr)<span class="text-danger">*</span></label>
                            <input min="0" placeholder="'.$this->lang->line('prodAddonsPrice').'" name="prodAddonsPrice['.$addonsCounter.']['.$addonsKey.']" onchange="autoSetPrice(this,\'price-'.$addonsCounter.'-'.$addonsKey.'\')" value="0" class="form-control input-sm price-'.$addonsCounter.'-'.$addonsKey.'" onkeydown="OnlyNumericKey(event)">
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsStatus').' (Fr)<span class="text-danger">*</span></label>
                            <input type="checkbox"  value="1" checked class="form-control addonsts-'.$addonsCounter.'-'.$addonsKey.'" onclick="autoCheckEnableBox(\'addonsts-'.$addonsCounter.'-'.$addonsKey.'\',this.checked)" style="left: 0;"  name="prodAddonsStatus['.$addonsCounter.']['.$addonsKey.']" />
                          </div>
                        </div>
                        </div>
                        <div class="col-md-12 addonsaddcategorymore">
                        <a href="javascript:" class="btn btn-info add-more-addons"><i class="fa fa-plus"></i>'.$this->lang->line('addMore').'</a><br/>
                        </div>
                      </div>';
                    
                    $grAddonsContent = '<div class="col-md-12 variableitemdetails box-'.$addonsCounter.'" >
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>'.$this->lang->line('prodAddonsCategoryName').' (Gr)<span class="text-danger">*</span></label>
                          <input type="text" class="form-control input-sm addoncat" placeholder="'.$this->lang->line('prodAddonsCategoryName').'"   name="prodAddonsCategoryName_gr['.$addonsCounter.']">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>'.$this->lang->line('prodAddonsCategoryRequired').' (Gr)<span class="text-danger">*</span></label>
                          <input type="checkbox" value="1"  class="form-control isreq-'.$addonsCounter.'" onclick="autoCheckEnableBox(\'isreq-'.$addonsCounter.'\',this.checked)"  style="left: 0;"  name="prodAddonsCategoryRequired['.$addonsCounter.']" />
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>'.$this->lang->line('prodAddonsCategoryChoice').' (Gr)<span class="text-danger">*</span></label>
                          <select name="prodAddonsCatChoice['.$addonsCounter.']" class="form-control input-sm catchoice-'.$addonsCounter.'" onchange="setCatChoice(\'catchoice-'.$addonsCounter.'\',this.value);">
                            <option value="0">'.$this->lang->line('prodAddonsCategoryChoice1').'</option>
                            <option value="1">'.$this->lang->line('prodAddonsCategoryChoice2').'</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>'.$this->lang->line('prodAddonsStatus').' (Gr)<span class="text-danger">*</span></label>
                          <input type="checkbox" value="1" class="form-control instock-'.$addonsCounter.'" checked style="left: 0;" onclick="autoCheckEnableBox(\'instock-'.$addonsCounter.'\',this.checked)"  name="prodAddonsCatStatus['.$addonsCounter.']" />
                        </div>
                      </div>
                      <br/>
                      <div class="col-md-12 variableaddonitemdetails box-'.$addonsCounter.'" >
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsName').' (Gr)<span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm addonname" placeholder="'.$this->lang->line('prodAddonsName').'"   name="prodAddonsName_gr['.$addonsCounter.']['.$addonsKey.']">
                            
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsPrice').' (Gr)<span class="text-danger">*</span></label>
                            <input min="0" placeholder="'.$this->lang->line('prodAddonsPrice').'" name="prodAddonsPrice['.$addonsCounter.']['.$addonsKey.']" value="0" class="form-control input-sm price-'.$addonsCounter.'-'.$addonsKey.'" onkeydown="OnlyNumericKey(event)">
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsStatus').' (Gr)<span class="text-danger">*</span></label>
                            <input type="checkbox" value="1" checked class="form-control addonsts-'.$addonsCounter.'-'.$addonsKey.'" onclick="autoCheckEnableBox(\'addonsts-'.$addonsCounter.'-'.$addonsKey.'\',this.checked)" style="left: 0;"  name="prodAddonsStatus['.$addonsCounter.']['.$addonsKey.']" />
                          </div>
                        </div>
                        </div>
                        <div class="col-md-12 addonsaddcategorymore">
                        <a href="javascript:" class="btn btn-info add-more-addons"><i class="fa fa-plus"></i>'.$this->lang->line('addMore').'</a><br/>
                        </div>
                      </div>';
                    
                    $itAddonsContent = '<div class="col-md-12 variableitemdetails box-'.$addonsCounter.'" >
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>'.$this->lang->line('prodAddonsCategoryName').' (It)<span class="text-danger">*</span></label>
                          <input type="text" class="form-control input-sm addoncat" placeholder="'.$this->lang->line('prodAddonsCategoryName').'"   name="prodAddonsCategoryName_it['.$addonsCounter.']">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>'.$this->lang->line('prodAddonsCategoryRequired').' (It)<span class="text-danger">*</span></label>
                          <input type="checkbox" value="1" class="form-control isreq-'.$addonsCounter.'" style="left: 0;" onclick="autoCheckEnableBox(\'isreq-'.$addonsCounter.'\',this.checked)"   name="prodAddonsCategoryRequired['.$addonsCounter.']" />
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>'.$this->lang->line('prodAddonsCategoryChoice').' (It)<span class="text-danger">*</span></label>
                          <select name="prodAddonsCatChoice['.$addonsCounter.']" class="form-control input-sm catchoice-'.$addonsCounter.'" onchange="setCatChoice(\'catchoice-'.$addonsCounter.'\',this.value);">
                            <option value="0">'.$this->lang->line('prodAddonsCategoryChoice1').'</option>
                            <option value="1">'.$this->lang->line('prodAddonsCategoryChoice2').'</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>'.$this->lang->line('prodAddonsStatus').' (It)<span class="text-danger">*</span></label>
                          <input type="checkbox" value="1" class="form-control instock-'.$addonsCounter.'" checked style="left: 0;" onclick="autoCheckEnableBox(\'instock-'.$addonsCounter.'\',this.checked)"  name="prodAddonsCatStatus['.$addonsCounter.']" />
                        </div>
                      </div>
                      <br/>
                      <div class="col-md-12 variableaddonitemdetails box-'.$addonsCounter.'" >
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsName').' (It)<span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-sm addonname" placeholder="'.$this->lang->line('prodAddonsName').'"   name="prodAddonsName_it['.$addonsCounter.']['.$addonsKey.']">
                            
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsPrice').' (It)<span class="text-danger">*</span></label>
                            <input min="0" placeholder="'.$this->lang->line('prodAddonsPrice').'" name="prodAddonsPrice['.$addonsCounter.']['.$addonsKey.']" onchange="autoSetPrice(this,\'price-'.$addonsCounter.'-'.$addonsKey.'\')" value="0" class="form-control input-sm price-'.$addonsCounter.'-'.$addonsKey.'" onkeydown="OnlyNumericKey(event)">
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>'.$this->lang->line('prodAddonsStatus').' (It)<span class="text-danger">*</span></label>
                            <input type="checkbox" value="1" checked class="form-control addonsts-'.$addonsCounter.'-'.$addonsKey.'" onclick="autoCheckEnableBox(\'addonsts-'.$addonsCounter.'-'.$addonsKey.'\',this.checked)" style="left: 0;"  name="prodAddonsStatus['.$addonsCounter.']['.$addonsKey.']" />
                          </div>
                        </div>
                        </div>
                        <div class="col-md-12 addonsaddcategorymore">
                        <a href="javascript:" class="btn btn-info add-more-addons"><i class="fa fa-plus"></i>'.$this->lang->line('addMore').'</a><br/>
                        </div>
                      </div>';
                    $addonsCounter++;
                    $addonsKey++;
                  }?>
                
                <?php 
                  $addAddonsMore  = '<div class="col-md-12 addonsaddcatmore">
                      <a href="javascript:" class="btn btn-info add-more-addons-all"><i class="fa fa-plus"></i>'.$this->lang->line('addMore').'</a><br/>
                    </div>'; 
                  $enAddonsContent .= $addAddonsMore;
                  $frAddonsContent .= $addAddonsMore;
                  $grAddonsContent .= $addAddonsMore;
                  $itAddonsContent .= $addAddonsMore;
                ?>
                <?php echo $enAddonsContent; ?>           
              </div>


              <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'french') ? 'active' : '';?>" id="addons-french">        
                <?php echo $frAddonsContent; ?>
              </div>


              <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'german') ? 'active' : '';?>" id="addons-german">
                <?php echo $grAddonsContent; ?>
              </div>


              <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'italian') ? 'active' : '';?>" id="addons-italian">      
               <?php echo $itAddonsContent; ?>
              </div>
            </div>
          </div><!-- /.addons- -->
          <?php 
            }
          ?>
          <br>
          <br>
          <div class=" col-sm-12 form-group" style=" margin-top: 50px; padding: 0;">
            <button type="submit" name="btnAddProduct" class="btn btn-primary btn-lg btnAddProduct"><?php echo $this->lang->line('submit'); ?></button>

          </div>
        </div>
      </div><!--you_requested-->
    </form>
    <?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>
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
        

        $('<div class="col-md-12 variableitemdetails box-'+counter+'" ><div class="form-group"><a class="label label-danger rounded removevariable" onclick="removevariable(\'box-'+counter+'\')"><i class="fa fa-times "></i></a><label><?=$this->lang->line('variableItemName');?> (EN)<span class="text-danger">*</span></label><input type="text" class="form-control input-sm" min="0"  placeholder="<?=$this->lang->line('variableItemName');?>" name="variableName[]"><input type="hidden" name="variableItemId[]" value="0"></div><div class="form-group"><label><?=$this->lang->line('variableItemPrice');?> (EN)<span class="text-danger">*</span></label><input type="text" min="0"  placeholder="<?=$this->lang->line('variableItemPrice');?>" name="variableItemPrice[]" value="0" class="form-control input-sm price-'+counter+'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'+counter+'\')"></div></div>').insertBefore(obj_en);
        $('<div class="col-md-12 variableitemdetails box-'+counter+'" ><div class="form-group"><a class="label label-danger rounded removevariable" onclick="removevariable(\'box-'+counter+'\')"><i class="fa fa-times "></i></a><label><?=$this->lang->line('variableItemName');?> (FR)<span class="text-danger">*</span></label><input type="text" class="form-control input-sm" min="0"  placeholder="<?=$this->lang->line('variableItemName');?>" name="variableName_fr[]"></div><div class="form-group"><label><?=$this->lang->line('variableItemPrice');?> (FR)<span class="text-danger">*</span></label><input type="text" placeholder="<?=$this->lang->line('variableItemPrice');?>" name="variableItemPrice_fr[]" value="0" class="form-control input-sm price-'+counter+'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'+counter+'\')"></div></div>').insertBefore(obj_fr);
        $('<div class="col-md-12 variableitemdetails box-'+counter+'" ><div class="form-group"><a class="label label-danger rounded removevariable" onclick="removevariable(\'box-'+counter+'\')"><i class="fa fa-times "></i></a><label><?=$this->lang->line('variableItemName');?> (GR)<span class="text-danger">*</span></label><input type="text" class="form-control input-sm" min="0"  placeholder="<?=$this->lang->line('variableItemName');?>" name="variableName_gr[]"></div><div class="form-group"><label><?=$this->lang->line('variableItemPrice');?> (GR)<span class="text-danger">*</span></label><input type="text" placeholder="<?=$this->lang->line('variableItemPrice');?>" name="variableItemPrice_gr[]" value="0" class="form-control input-sm price-'+counter+'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'+counter+'\')"></div></div>').insertBefore(obj_gr);
        $('<div class="col-md-12 variableitemdetails box-'+counter+'" ><div class="form-group"><a class="label label-danger rounded removevariable" onclick="removevariable(\'box-'+counter+'\')"><i class="fa fa-times "></i></a><label><?=$this->lang->line('variableItemName');?> (IT)<span class="text-danger">*</span></label><input type="text" class="form-control input-sm" min="0"  placeholder="<?=$this->lang->line('variableItemName');?>" name="variableName_it[]"></div><div class="form-group"><label><?=$this->lang->line('variableItemPrice');?> (IT)<span class="text-danger">*</span></label><input type="text" min="0"  placeholder="<?=$this->lang->line('variableItemPrice');?>" name="variableItemPrice_it[]" value="0" class="form-control input-sm price-'+counter+'" onkeydown="OnlyNumericKey(event)" onchange="updatePrice(this,\'price-'+counter+'\')"></div></div>').insertBefore(obj_it);
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
      /* get Sub category --------------------------------------*/
      $(document).ready(function(){ 
        checkcategorydata();
      })

      function checkcategorydata(){

        var category = $('option:selected', $('#selCategory')).attr('data-value');
        if (category == GLOBALERRORS.drink) {
          $('#selSubcategoryId').removeAttr('required');
          $('.addonsitem').hide();
          // $('#subcategoryDiv').hide();
          // $('#subcategoryitemDiv').hide();
          // $('#selSubcategoryitemId option:selected').prop('selected', false);

        }else{
          $('#selSubcategoryId').attr('required', 'required');
          $('.addonsitem').show();
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
        if($(this).val() == 5)
          $('#isAvailableInFree,#isOnlyForGirl').closest('div.form-group').removeClass('hide');
        else{
          $('#isAvailableInFree,#isOnlyForGirl,#doNotIncludeInTheMenu').closest('div.form-group').addClass('hide');
          $('#isAvailableInFree,#isOnlyForGirl,#doNotIncludeInTheMenu').prop('checked', false);
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
          // $('#doNotIncludeInTheMenu').prop('checked', true);
          $('#doNotIncludeInTheMenu').closest('div.form-group').removeClass('hide');
        }
        else{
          $('#doNotIncludeInTheMenu').prop('checked', false);
          $('#doNotIncludeInTheMenu').closest('div.form-group').addClass('hide');
        }
      });
    </script>

    <?php 
      if(isset($addonProductData) && !empty($addonProductData)){
    ?>
      <script type="text/javascript">
        var addonsCounter = <?=$addonsCounter;?>;
        var addonsKeyCounter = <?=$addonsKey;?>;
        /* Add Whole Category Div*/
        $('.add-more-addons-all').click(function(){
     
          var obj_en = $(this).closest('div.addonsitem').find('div#addons-english').find('div.addonsaddcatmore');
          var obj_fr = $(this).closest('div.addonsitem').find('div#addons-french').find('div.addonsaddcatmore');
          var obj_gr = $(this).closest('div.addonsitem').find('div#addons-german').find('div.addonsaddcatmore');
          var obj_it = $(this).closest('div.addonsitem').find('div#addons-italian').find('div.addonsaddcatmore');
                /*----------En-----------*/
          $('<div class="col-md-12 variableitemdetails box-'+addonsCounter+'" ><a class="label label-danger rounded removevariable" onclick="removeaddons(\'box-'+addonsCounter+'\')"><i class="fa fa-times "></i></a><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsCategoryName');?> (En)<span class="text-danger">*</span></label><input type="text" class="form-control input-sm addoncat" placeholder="<?=$this->lang->line('prodAddonsCategoryName');?>" name="prodAddonsCategoryName['+addonsCounter+']"> <input type="hidden" name="addonsCatId['+addonsCounter+']" value="0" /></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsCategoryRequired');?> (En)<span class="text-danger">*</span></label><input type="checkbox" value="1" class="form-control isreq-'+addonsCounter+'" style="left: 0;" onclick="autoCheckEnableBox(\'isreq-'+addonsCounter+'\',this.checked)"  name="prodAddonsCategoryRequired['+addonsCounter+']" /></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsCategoryChoice');?> (En)<span class="text-danger">*</span></label><select name="prodAddonsCatChoice['+addonsCounter+']" class="form-control input-sm catchoice-'+addonsCounter+'" onchange="setCatChoice(\'catchoice-'+addonsCounter+'\',this.value)" ><option value="0"><?=$this->lang->line('prodAddonsCategoryChoice1');?></option><option value="1"><?=$this->lang->line('prodAddonsCategoryChoice2');?></option></select></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsStatus');?> (En)<span class="text-danger">*</span></label><input type="checkbox" value="1" class="form-control instock-'+addonsCounter+'" checked style="left: 0;" onclick="autoCheckEnableBox(\'instock-'+addonsCounter+'\',this.checked)"  name="prodAddonsCatStatus['+addonsCounter+']" /></div></div><br/><div class="col-md-12 variableaddonitemdetails box-'+addonsCounter+'" ><div class="col-md-6"><div class="form-group"><label><?=$this->lang->line('prodAddonsName');?> (En)<span class="text-danger">*</span></label> <input type="text" class="form-control input-sm addonname" placeholder="<?=$this->lang->line('prodAddonsName');?>"   name="prodAddonsName['+addonsCounter+']['+addonsKeyCounter+']"></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsPrice');?> (En)<span class="text-danger">*</span></label><input min="0" placeholder="<?=$this->lang->line('prodAddonsPrice');?>" name="prodAddonsPrice['+addonsCounter+']['+addonsKeyCounter+']" value="0" class="form-control input-sm price-'+addonsCounter+'-'+addonsKeyCounter+'" onchange="autoSetPrice(this,\'price-'+addonsCounter+'-'+addonsKeyCounter+'\')" onkeydown="OnlyNumericKey(event)"></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsStatus');?> (En)<span class="text-danger">*</span></label><input type="checkbox" checked value="1" class="form-control addonsts-'+addonsCounter+'-'+addonsKeyCounter+'" onclick="autoCheckEnableBox(\'addonsts-'+addonsCounter+'-'+addonsKeyCounter+'\',this.checked)" style="left: 0;"  name="prodAddonsStatus['+addonsCounter+']['+addonsKeyCounter+']" /></div></div></div><div class="col-md-12 addonsaddcategorymore"><a href="javascript:" class="btn btn-info add-more-addons"><i class="fa fa-plus"></i><?=$this->lang->line('addMore');?></a><br/></div> </div>').insertBefore(obj_en);
          /*----------Fr-----------*/
          $('<div class="col-md-12 variableitemdetails box-'+addonsCounter+'" ><a class="label label-danger rounded removevariable" onclick="removeaddons(\'box-'+addonsCounter+'\')"><i class="fa fa-times "></i></a><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsCategoryName');?> (Fr)<span class="text-danger">*</span></label><input type="text" class="form-control input-sm addoncat" placeholder="<?=$this->lang->line('prodAddonsCategoryName');?>" name="prodAddonsCategoryName_fr['+addonsCounter+']"></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsCategoryRequired');?> (Fr)<span class="text-danger">*</span></label><input type="checkbox" value="1" class="form-control isreq-'+addonsCounter+'" style="left: 0;"  name="prodAddonsCategoryRequired['+addonsCounter+']" onclick="autoCheckEnableBox(\'isreq-'+addonsCounter+'\',this.checked)" /></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsCategoryChoice');?> (Fr)<span class="text-danger">*</span></label><select name="prodAddonsCatChoice['+addonsCounter+']" class="form-control input-sm catchoice-'+addonsCounter+'" onchange="setCatChoice(\'catchoice-'+addonsCounter+'\',this.value)"><option value="0"><?=$this->lang->line('prodAddonsCategoryChoice1');?></option><option value="1"><?=$this->lang->line('prodAddonsCategoryChoice2');?></option></select></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsStatus');?> (Fr)<span class="text-danger">*</span></label><input type="checkbox" value="1" class="form-control instock-'+addonsCounter+'" checked style="left: 0;" onclick="autoCheckEnableBox(\'instock-'+addonsCounter+'\',this.checked)"  name="prodAddonsCatStatus['+addonsCounter+']" /></div></div><br/><div class="col-md-12 variableaddonitemdetails box-'+addonsCounter+'" ><div class="col-md-6"><div class="form-group"><label><?=$this->lang->line('prodAddonsName');?> (Fr)<span class="text-danger">*</span></label> <input type="text" class="form-control input-sm addonname" placeholder="<?=$this->lang->line('prodAddonsName');?>"   name="prodAddonsName_fr['+addonsCounter+']['+addonsKeyCounter+']"></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsPrice');?> (Fr)<span class="text-danger">*</span></label><input min="0" placeholder="<?=$this->lang->line('prodAddonsPrice');?>" name="prodAddonsPrice['+addonsCounter+']['+addonsKeyCounter+']" value="0" class="form-control input-sm price-'+addonsCounter+'-'+addonsKeyCounter+'" onchange="autoSetPrice(this,\'price-'+addonsCounter+'-'+addonsKeyCounter+'\')" onkeydown="OnlyNumericKey(event)"></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsStatus');?> (Fr)<span class="text-danger">*</span></label><input type="checkbox" checked value="1" class="form-control addonsts-'+addonsCounter+'-'+addonsKeyCounter+'" onclick="autoCheckEnableBox(\'addonsts-'+addonsCounter+'-'+addonsKeyCounter+'\',this.checked)" style="left: 0;"  name="prodAddonsStatus['+addonsCounter+']['+addonsKeyCounter+']" /></div></div></div><div class="col-md-12 addonsaddcategorymore"><a href="javascript:" class="btn btn-info add-more-addons"><i class="fa fa-plus"></i><?=$this->lang->line('addMore');?></a><br/></div> </div>').insertBefore(obj_fr);
          
          /*----------Gr-----------*/
          $('<div class="col-md-12 variableitemdetails box-'+addonsCounter+'" ><a class="label label-danger rounded removevariable" onclick="removeaddons(\'box-'+addonsCounter+'\')"><i class="fa fa-times "></i></a><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsCategoryName');?> (Gr)<span class="text-danger">*</span></label><input type="text" class="form-control input-sm addoncat" placeholder="<?=$this->lang->line('prodAddonsCategoryName');?>" name="prodAddonsCategoryName_gr['+addonsCounter+']"></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsCategoryRequired');?> (Gr)<span class="text-danger">*</span></label><input type="checkbox" value="1" class="form-control isreq-'+addonsCounter+'" onclick="autoCheckEnableBox(\'isreq-'+addonsCounter+'\',this.checked)" style="left: 0;"  name="prodAddonsCategoryRequired['+addonsCounter+']" /></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsCategoryChoice');?> (Gr)<span class="text-danger">*</span></label><select name="prodAddonsCatChoice['+addonsCounter+']" class="form-control input-sm catchoice-'+addonsCounter+'" onchange="setCatChoice(\'catchoice-'+addonsCounter+'\',this.value)"><option value="0"><?=$this->lang->line('prodAddonsCategoryChoice1');?></option><option value="1"><?=$this->lang->line('prodAddonsCategoryChoice2');?></option></select></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsStatus');?> (Gr)<span class="text-danger">*</span></label><input type="checkbox" value="1" class="form-control instock-'+addonsCounter+'" checked style="left: 0;" onclick="autoCheckEnableBox(\'instock-'+addonsCounter+'\',this.checked)"  name="prodAddonsCatStatus['+addonsCounter+']" /></div></div><br/><div class="col-md-12 variableaddonitemdetails box-'+addonsCounter+'" ><div class="col-md-6"><div class="form-group"><label><?=$this->lang->line('prodAddonsName');?> (Gr)<span class="text-danger">*</span></label> <input type="text" class="form-control input-sm addonname" placeholder="<?=$this->lang->line('prodAddonsName');?>"   name="prodAddonsName_gr['+addonsCounter+']['+addonsKeyCounter+']"></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsPrice');?> (Gr)<span class="text-danger">*</span></label><input min="0" placeholder="<?=$this->lang->line('prodAddonsPrice');?>" name="prodAddonsPrice['+addonsCounter+']['+addonsKeyCounter+']" value="0" class="form-control input-sm price-'+addonsCounter+'-'+addonsKeyCounter+'" onchange="autoSetPrice(this,\'price-'+addonsCounter+'-'+addonsKeyCounter+'\')" onkeydown="OnlyNumericKey(event)"></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsStatus');?> (Gr)<span class="text-danger">*</span></label><input type="checkbox" checked value="1" class="form-control addonsts-'+addonsCounter+'-'+addonsKeyCounter+'" onclick="autoCheckEnableBox(\'addonsts-'+addonsCounter+'-'+addonsKeyCounter+'\',this.checked)" style="left: 0;"  name="prodAddonsStatus['+addonsCounter+']['+addonsKeyCounter+']" /></div></div></div><div class="col-md-12 addonsaddcategorymore"><a href="javascript:" class="btn btn-info add-more-addons"><i class="fa fa-plus"></i><?=$this->lang->line('addMore');?></a><br/></div> </div>').insertBefore(obj_gr);
          
          /*----------it-----------*/
          $('<div class="col-md-12 variableitemdetails box-'+addonsCounter+'" ><a class="label label-danger rounded removevariable" onclick="removeaddons(\'box-'+addonsCounter+'\')"><i class="fa fa-times "></i></a><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsCategoryName');?> (It)<span class="text-danger">*</span></label><input type="text" class="form-control input-sm addoncat" placeholder="<?=$this->lang->line('prodAddonsCategoryName');?>" name="prodAddonsCategoryName_it['+addonsCounter+']"></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsCategoryRequired');?> (It)<span class="text-danger">*</span></label><input type="checkbox" value="1" class="form-control isreq-'+addonsCounter+'" style="left: 0;"  name="prodAddonsCategoryRequired['+addonsCounter+']" onclick="autoCheckEnableBox(\'isreq-'+addonsCounter+'\',this.checked)" /></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsCategoryChoice');?> (It)<span class="text-danger">*</span></label><select name="prodAddonsCatChoice['+addonsCounter+']"  class="form-control input-sm catchoice-'+addonsCounter+'" onchange="setCatChoice(\'catchoice-'+addonsCounter+'\',this.value)"><option value="0"><?=$this->lang->line('prodAddonsCategoryChoice1');?></option><option value="1"><?=$this->lang->line('prodAddonsCategoryChoice2');?></option></select></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsStatus');?> (It)<span class="text-danger">*</span></label><input type="checkbox" value="1" class="form-control instock-'+addonsCounter+'" checked style="left: 0;" onclick="autoCheckEnableBox(\'instock-'+addonsCounter+'\',this.checked)" name="prodAddonsCatStatus['+addonsCounter+']" /></div></div><br/><div class="col-md-12 variableaddonitemdetails box-'+addonsCounter+'" ><div class="col-md-6"><div class="form-group"><label><?=$this->lang->line('prodAddonsName');?> (It)<span class="text-danger">*</span></label> <input type="text" class="form-control input-sm addonname" placeholder="<?=$this->lang->line('prodAddonsName');?>"   name="prodAddonsName_it['+addonsCounter+']['+addonsKeyCounter+']"></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsPrice');?> (It)<span class="text-danger">*</span></label><input min="0" placeholder="<?=$this->lang->line('prodAddonsPrice');?>" name="prodAddonsPrice['+addonsCounter+']['+addonsKeyCounter+']" value="0" class="form-control input-sm price-'+addonsCounter+'-'+addonsKeyCounter+'" onchange="autoSetPrice(this,\'price-'+addonsCounter+'-'+addonsKeyCounter+'\')" onkeydown="OnlyNumericKey(event)"></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsStatus');?> (It)<span class="text-danger">*</span></label><input type="checkbox" checked value="1" class="form-control addonsts-'+addonsCounter+'-'+addonsKeyCounter+'" onclick="autoCheckEnableBox(\'addonsts-'+addonsCounter+'-'+addonsKeyCounter+'\',this.checked)" style="left: 0;"  name="prodAddonsStatus['+addonsCounter+']['+addonsKeyCounter+']" /></div></div></div><div class="col-md-12 addonsaddcategorymore"><a href="javascript:" class="btn btn-info add-more-addons"><i class="fa fa-plus"></i><?=$this->lang->line('addMore');?></a><br/></div> </div>').insertBefore(obj_it);
                
          addonsCounter++;
          addonsKeyCounter++;
        }); 
        /*------------------- Add Addons Div -----------*/
        $(document).on('click','.add-more-addons',function(){
          var ind=$(this).closest('div.variableitemdetails').index();

          var cnt=parseInt($(this).closest('div.addonsitem').find('div#addons-english').find('div.variableitemdetails:eq('+ind+')').find('div.variableaddonitemdetails').length);
          cnt++;
          var cnt=ind.toString()+cnt.toString();
                var obj_en = $(this).closest('div.addonsitem').find('div#addons-english').find('div.variableitemdetails:eq('+ind+')').find('div.addonsaddcategorymore');
          var obj_fr = $(this).closest('div.addonsitem').find('div#addons-french').find('div.variableitemdetails:eq('+ind+')').find('div.addonsaddcategorymore');
          var obj_gr =  $(this).closest('div.addonsitem').find('div#addons-german').find('div.variableitemdetails:eq('+ind+')').find('div.addonsaddcategorymore');
          var obj_it =  $(this).closest('div.addonsitem').find('div#addons-italian').find('div.variableitemdetails:eq('+ind+')').find('div.addonsaddcategorymore');
                  /*----------En-----------*/
          $('<div class="col-md-12 variableaddonitemdetails itembox-'+cnt+'" ><a class="label label-danger rounded removevariable" onclick="removeaddons(\'itembox-'+cnt+'\')"><i class="fa fa-times "></i></a><div class="col-md-6"><div class="form-group"><label><?=$this->lang->line('prodAddonsName');?> (En)<span class="text-danger">*</span></label><input type="text" class="form-control input-sm" placeholder="<?=$this->lang->line('prodAddonsName');?>" name="prodAddonsName['+ind+']['+addonsKeyCounter+']"></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsPrice');?> (En)<span class="text-danger">*</span></label><input min="0" placeholder="<?=$this->lang->line('prodAddonsPrice');?>" name="prodAddonsPrice['+ind+']['+addonsKeyCounter+']" value="0" class="form-control input-sm price-'+ind+'-'+addonsKeyCounter+'" onchange="autoSetPrice(this,\'price-'+ind+'-'+addonsKeyCounter+'\')" onkeydown="OnlyNumericKey(event)"></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsStatus');?> (En)<span class="text-danger">*</span></label><input type="checkbox" checked value="1" class="form-control addonsts-'+ind+'-'+addonsKeyCounter+'" onclick="autoCheckEnableBox(\'addonsts-'+ind+'-'+addonsKeyCounter+'\',this.checked)" style="left: 0;"  name="prodAddonsStatus['+ind+']['+addonsKeyCounter+']" /></div></div></div>').insertBefore(obj_en);
          /*----------Fr-----------*/
          $('<div class="col-md-12 variableaddonitemdetails itembox-'+ind+'" ><a class="label label-danger rounded removevariable" onclick="removeaddons(\'itembox-'+ind+'\')"><i class="fa fa-times "></i></a><div class="col-md-6"><div class="form-group"><label><?=$this->lang->line('prodAddonsName');?> (Fr)<span class="text-danger">*</span></label><input type="text" class="form-control input-sm" placeholder="<?=$this->lang->line('prodAddonsName');?>" name="prodAddonsName_fr['+ind+']['+addonsKeyCounter+']"></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsPrice');?> (Fr)<span class="text-danger">*</span></label><input min="0" placeholder="<?=$this->lang->line('prodAddonsPrice');?>" name="prodAddonsPrice_fr['+ind+']['+addonsKeyCounter+']" value="0" class="form-control input-sm price-'+ind+'-'+addonsKeyCounter+'" onchange="autoSetPrice(this,\'price-'+ind+'-'+addonsKeyCounter+'\')" onkeydown="OnlyNumericKey(event)"></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsStatus');?> (Fr)<span class="text-danger">*</span></label><input type="checkbox" checked value="1" class="form-control addonsts-'+ind+'-'+addonsKeyCounter+'" onclick="autoCheckEnableBox(\'addonsts-'+ind+'-'+addonsKeyCounter+'\',this.checked)" style="left: 0;"  name="prodAddonsStatus_fr['+ind+']['+addonsKeyCounter+']" /></div></div></div>').insertBefore(obj_fr);
          /*----------Gr-----------*/
          $('<div class="col-md-12 variableaddonitemdetails itembox-'+ind+'" ><a class="label label-danger rounded removevariable" onclick="removeaddons(\'itembox-'+ind+'\')"><i class="fa fa-times "></i></a><div class="col-md-6"><div class="form-group"><label><?=$this->lang->line('prodAddonsName');?> (Gr)<span class="text-danger">*</span></label><input type="text" class="form-control input-sm" placeholder="<?=$this->lang->line('prodAddonsName');?>" name="prodAddonsName_gr['+ind+']['+addonsKeyCounter+']"></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsPrice');?> (Gr)<span class="text-danger">*</span></label><input min="0" placeholder="<?=$this->lang->line('prodAddonsPrice');?>" name="prodAddonsPrice_gr['+ind+']['+addonsKeyCounter+']" value="0" class="form-control input-sm price-'+ind+'-'+addonsKeyCounter+'" onchange="autoSetPrice(this,\'price-'+ind+'-'+addonsKeyCounter+'\')" onkeydown="OnlyNumericKey(event)"></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsStatus');?> (Gr)<span class="text-danger">*</span></label><input type="checkbox" checked value="1" class="form-control addonsts-'+ind+'-'+addonsKeyCounter+'" onclick="autoCheckEnableBox(\'addonsts-'+ind+'-'+addonsKeyCounter+'\',this.checked)" style="left: 0;"  name="prodAddonsStatus_gr['+ind+']['+addonsKeyCounter+']" /></div></div></div>').insertBefore(obj_gr);
          /*----------It-----------*/
          $('<div class="col-md-12 variableaddonitemdetails itembox-'+ind+'" ><a class="label label-danger rounded removevariable" onclick="removeaddons(\'itembox-'+ind+'\')"><i class="fa fa-times "></i></a><div class="col-md-6"><div class="form-group"><label><?=$this->lang->line('prodAddonsName');?> (It)<span class="text-danger">*</span></label><input type="text" class="form-control input-sm" placeholder="<?=$this->lang->line('prodAddonsName');?>" name="prodAddonsName_it['+ind+']['+addonsKeyCounter+']"></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsPrice');?> (It)<span class="text-danger">*</span></label><input min="0" placeholder="<?=$this->lang->line('prodAddonsPrice');?>" name="prodAddonsPrice_it['+ind+']['+addonsKeyCounter+']" value="0" class="form-control input-sm price-'+ind+'-'+addonsKeyCounter+'" onchange="autoSetPrice(this,\'price-'+ind+'-'+addonsKeyCounter+'\')" onkeydown="OnlyNumericKey(event)"></div></div><div class="col-md-3"><div class="form-group"><label><?=$this->lang->line('prodAddonsStatus');?> (It)<span class="text-danger">*</span></label><input type="checkbox" checked value="1" class="form-control addonsts-'+ind+'-'+addonsKeyCounter+'" onclick="autoCheckEnableBox(\'addonsts-'+ind+'-'+addonsKeyCounter+'\',this.checked)" style="left: 0;"  name="prodAddonsStatus_it['+ind+']['+addonsKeyCounter+']" /></div></div></div>').insertBefore(obj_it);
          addonsKeyCounter++;
          
        }); 
        function removeaddons(divClass){
            $('.'+divClass).remove();
        }

        function autoSetPrice(obj,divClass){
            $('.'+divClass).val($(obj).val());
        }
        function autoCheckEnableBox(cls,chk){
          $('.'+cls).prop('checked',chk);
        }
        function setCatChoice(cls,v){
          $('.'+cls).val(v);
        }
      </script>

    <?php  } ?>  
  </body>
</html>