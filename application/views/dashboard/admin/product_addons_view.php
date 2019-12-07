<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
          
    <form action="" method="POST" enctype="multipart/form-data" novalidate="">
        <div class="you_requested">
        <h3><?php echo $this->lang->line('prodAddonsHead'); ?></h3>
            <div class="expert_contact">
                <style type="text/css">
					  .variableitem{background-color: #f1f1f1;padding: 7px;border-radius: 4px;}
					  a.label.label-danger.rounded.removevariable, a.label.label-danger.rounded.deletevariable{float:right !important;}
					  .variableitemdetails,.variableaddonitemdetails{border: 1px solid #736f6f;margin: 5px 0;padding: 10px;}
					  .variableitemaddmore{margin-bottom: 10px;padding: 10px;}
					  .add-more-variable{margin: 5px;}
					  label.checkbox-inline {margin-top: 15px;padding: 0;}
                </style> 
	          	<br>
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
						  		// v3print($addonProductData);exit;
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
											  <input type="hidden" name="addonsCatId['.$addonsCounter.']"  value="'.$category->addonsCatId.'" />
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
												  		<input type="hidden" name="addonsId['.$addonsCounter.']['.$addonsKey.']" value="'.$addons->addonsId.'" />
													  
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
						  }
						else{
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
						  
						  <?php $addAddonsMore  = '<div class="col-md-12 addonsaddcatmore">
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
			  	<br>
	          	<div class=" col-sm-12 form-group" style=" margin-top: 12px; padding: 0;">
	             	<button type="submit" name="btnAddProductAddons" class="btn btn-primary btnAddProductAddons"><?php echo isset($productData->productName) ? $this->lang->line('buttonUpdate') : $this->lang->line('buttonSave'); ?></button>
	          	</div>
	        </div>
		</div><!--you_requested-->
  	</form>
<?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>


        <script type="text/javascript">
			var addonsCounter = <?=$addonsCounter;?>;
			var addonsKeyCounter = <?=$addonsKey;;?>;
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
    </body>
    <!--/ END BODY -->

</html>