<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
<style type="text/css">
  ul.variable-box {
      margin: 2px 0px;
      padding: 4px 15px;
      border: 1px solid #fbd1b4;
      background-color: #efefef;
  }
  ul.addon-category-box {
    margin: 2px 0px;
    padding: 4px 15px;
    border: 1px solid #b18b72;
    background-color: #b7efe4;
}
  ul.addon-box {
    margin: 2px 0px;
    padding: 4px 15px;
    border: 1px solid #d26418;
    background-color: #ffd7d7;
}
</style>

          
          <div class="you_requested">
          
      

        <?php if (valResultSet($productInfo)) {
            ?>
      <div class="expert_breif">
        <div class="image-upload">
               
                    <img src="<?=(!empty($productInfo->img ))?$productInfo->img:DASHSTATIC.'/restaurant/assets/img/product.png'?>" style="height: 200px;width: 200px;">
           

               
           </div>
      </div>
            <div class="expert_contact expert_ware">
        <h3><?=$productInfo->productName?>
        <a href="<?=DASHURL.'/admin/product/add-product/'.$productInfo->productId ?>"><i class="fa fa-pencil"></i></a></h3>
        <ul>  
          <li><h2><small><?=$this->lang->line('name')?>:</small><span><?=$productInfo->productName?></span></h2></li>
          <li><h2><small><?=$this->lang->line('price')?>:</small><span><?=$productInfo->price?></span></h2></li>
          <li><h2><small><?=$this->lang->line('type')?>:</small><span><?=$productInfo->categoryName?></span></h2></li>
          <li><h2><small><?=$this->lang->line('category')?>:</small><span><?=$productInfo->subcategoryName?></span></h2></li>
          <li><h2><small><?=$this->lang->line('subCategoryName')?>:</small><span><?=$productInfo->subcategoryitemName?></span></h2></li>
          <li><h2><small><?=$this->lang->line('tags')?>:</small><span><?=$productInfo->tags?></span></h2></li>
          <li><h2><small><?=$this->lang->line('isFeatureProduct')?>:</small><span><?=($productInfo->isFeatured == 1)?'<b class="label label-success" >'.$this->lang->line('yes').'</b>':'<b class="label label-danger" >'.$this->lang->line('no').'</b>'?></span></h2></li>
          <li><h2><small><?=$this->lang->line('isAvailableInFree')?>:</small><span><?=($productInfo->isAvailableInFree == 1)?'<b class="label label-success" >'.$this->lang->line('yes').'</b>':'<b class="label label-danger" >'.$this->lang->line('no').'</b>'?></span></h2></li>
          <li><h2><small><?=$this->lang->line('addedOn')?>:</small><span><?=$productInfo->addedOn?></span></h2></li>
          <li><h2><small><?=$this->lang->line('status')?>:</small><span><?=($productInfo->status == 0)?'<b class="label label-success" >'.$this->lang->line('active').'</b>':'<b class="label label-danger" >'.$this->lang->line('inactive').'</b>'?></span></h2></li>
        </ul>
        <h4><?=$this->lang->line('description')?>:</h4>
        <p><?=$productInfo->description?></p>

        <h4><?=$this->lang->line('variableList')?>:</h4>
        <div class="row">
          <?php if (isset($productVariableData) && valResultSet($productVariableData)) { 
                  foreach ($productVariableData as $variable) {   ?>
        
                    <div class="col-md-6">
                      <ul class="variable-box">  
                        <li><h2><small><?=$this->lang->line('name')?>:</small><span><?=$variable->variableName ?></span></h2></li>
                        <li><h2><small><?=$this->lang->line('price')?>:</small><span><?=$variable->price ?></span></h2></li>
                        <li><h2><small><?=ucfirst($this->lang->line('welcomeDrink'))?>:</small><span><?=($variable->isAvailableInFree)?'<b class="label label-success" >'.$this->lang->line('yes').'</b>':'<b class="label label-danger" >'.$this->lang->line('no').'</b>'; ?></span></h2></li>
                      </ul>
                    </div>
                        

        <?php }  }else{
            echo '<div class="col-md-12"><p>'.$this->lang->line('variableNotFound').'</p></div>';
          } ?>
        </div>


        <h4><?=$this->lang->line('addonsList')?>:</h4>
        <div class="row">
          <?php if (isset($addonData) && valResultSet($addonData)) { 
                  foreach ($addonData as $addonCategory) {   ?>
        
                    <div class="col-md-12">
                      <ul class="addon-category-box">  
                        <li><h2><small><?=$this->lang->line('categoryName')?>:</small><span><?=$addonCategory->categoryName ?></span></h2></li>
                        <li><h2><small><?=$this->lang->line('prodAddonsCategoryChoice')?>:</small><span><?=($addonCategory->choice)?$this->lang->line('prodAddonsCategoryChoice2'):$this->lang->line('prodAddonsCategoryChoice1') ?></span></h2></li>
                        <li><h2><small><?=$this->lang->line('required')?>:</small><span><?=($addonCategory->required)?$this->lang->line('yes'):$this->lang->line('no'); ?></span></h2></li>
                        <li><h2><small><?=$this->lang->line('inStock')?>:</small><span><?=($addonCategory->isStockAvailable)?$this->lang->line('yes'):$this->lang->line('no'); ?></span></h2></li>
                        <li><h2><small><?=$this->lang->line('addedOn')?>:</small><span><?=$addonCategory->addedOn?></span></h2></li>
                        <?php if (isset($addonCategory->addonsItem) && !empty($addonCategory->addonsItem)) { 
                          foreach ($addonCategory->addonsItem as $key => $addon) {
                          ?>
                        <li>
                          <ul class="addon-box">  
                            <li><h2><small><?=$this->lang->line('addonName')?>:</small><span><?=$addon->addonsName ?></span></h2></li>
                            <li><h2><small><?=$this->lang->line('price')?>:</small><span>CHF <?=$addon->price ?></span></h2></li>
                            <li><h2><small><?=$this->lang->line('inStock')?>:</small><span><?=($addon->isStockAvailable)?$this->lang->line('yes'):$this->lang->line('no'); ?></span></h2></li>
                            <li><h2><small><?=$this->lang->line('addedOn')?>:</small><span><?=$addon->addedOn?></span></h2></li>
                            <li><h2><small><?=$this->lang->line('updatedOn')?>:</small><span><?=$addon->updatedOn?></span></h2></li>
                          </ul>
                        </li>
                      <?php  
                    } }?>
                      </ul>
                    </div>
                        

        <?php }  }else{
            echo '<div class="col-md-12"><p>'.$this->lang->line('addonNotFound').'</p></div>';
          } ?>
        </div>
        <?php if (valResultSet($productGallaryData)) { ?>
          <h4><?=$this->lang->line('galleryImages')?>:</h4>
          <p><?php foreach ($productGallaryData as $glryimg) { ?>
              <img src="<?= UPLOADPATH."/product_gallary_images/".$glryimg->image; ?>" alt="Gallery image" style="border:2px solid black; width: 200px;height: 200px;" >
            <?php } ?>
          </p>
        <?php } ?>
            </div>

        <?php 
      }
        ?>

          </div><!--you_requested-->
<?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>

    
    </body>
    <!--/ END BODY -->

</html>

