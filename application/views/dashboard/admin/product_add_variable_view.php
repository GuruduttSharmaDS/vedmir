<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
          
          <form action="#" method="POST" onsubmit="addProductVariable(this,event);">
          <div class="you_requested">
          <h3><?= $this->lang->line('manageVariableItem');?> Of - <?= (isset($productName))?$productName:'Product';?> </h3>
          
            <div class="expert_contact" style="width: 100%;">
              <div class="col-md-4 variableForm">
                <div class="msg" style="margin: 5px;"></div>
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
                        <label><?=$this->lang->line('variableItemName');?> (EN)<span class="text-danger">*</span></label>
                        <input type="text" class="form-control input-sm" min="0"  placeholder="<?=$this->lang->line('variableItemName');?>" name="variableName" id="variableName" required="required" >
                      </div>  

                    </div>
                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'french') ? 'active' : '';?>" id="french">

                      <div class="form-group">
                        <label><?=$this->lang->line('variableItemName');?> (FR)<span class="text-danger">*</span></label>
                        <input type="text" class="form-control input-sm" min="0"  placeholder="<?=$this->lang->line('variableItemName');?>"   name="variableName_fr" id="variableName_fr" required="required" >
                      </div>  
                               
                    </div>
                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'german') ? 'active' : '';?>" id="german">

                      <div class="form-group">
                        <label><?=$this->lang->line('variableItemName');?> (GR)<span class="text-danger">*</span></label>
                        <input type="text" class="form-control input-sm" min="0"  placeholder="<?=$this->lang->line('variableItemName');?>"   name="variableName_gr" id="variableName_gr" required="required" >
                      </div>  
                           
                    </div> 
                    <div role="tabpanel" class="tab-pane <?=($this->sessLang == 'italian') ? 'active' : '';?>" id="italian">

                      <div class="form-group">
                        <label><?=$this->lang->line('variableItemName');?> (IT)<span class="text-danger">*</span></label>
                        <input type="text" class="form-control input-sm" min="0"  placeholder="<?=$this->lang->line('variableItemName');?>"   name="variableName_it" id="variableName_it" required="required" >
                      </div>  
                             
                    </div>   
                </div> 

          <div class="form-group">
            <label><?=$this->lang->line('variableItemPrice');?><span class="text-danger">*</span></label>
            <input type="text" class="form-control input-sm" min="0"  placeholder="<?=$this->lang->line('variableItemPrice');?>" name="price" id="price" required="required" >
              <input type="hidden" name="productId"  id="productId" value="<?=(isset($productId))?$productId:0?>">
              <input type="hidden" name="hiddenval"  id="hiddenval">
              <input type="hidden" name="indexval"  id="indexval">
              <input type="hidden" name="action" value="add_variable_product">
          </div>
          <br>
          <div class=" col-sm-12 form-group" style=" margin-top: 12px;">
             <button type="submit" name="btnAddProduct" class="btn btn-primary btnAddProduct"><?=$this->lang->line('buttonSave');?></button>
             <button type="submit" name="cancel" class="btn btn-danger" onclick="ResetTextBox($(this).closest('form'))"><?=$this->lang->line('cancel');?></button>
              
          </div>

    </div>
    <div class="col-md-8">
            <div class="man_table" style="overflow-x: hidden;">
              <div class="order_status">
                <h2><?=$this->lang->line('variableList')?></h2>
              </div>
              <table class="table">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('name')?></th>
                    <th><?=$this->lang->line('price')?></th>
                    <th><?=$this->lang->line('status')?></th>
                    <th><?=$this->lang->line('action')?></th>
                  </tr>
                </thead>
                <tbody class="tablebody">
                </tbody>
              </table>
            </div>
      
    </div>
    </div><!--you_requested-->

      </form>
<?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>

</html>