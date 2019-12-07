<?php $this->load->viewD($this->sessRole.'/inc/header_page_form'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar01'); ?>

                
<!-- START @PAGE CONTENT -->
	<section id="page-content">

		<!-- Start page header -->
		<div class="header-content">
			<h2><i class="fa fa-home"></i>Admin <span><?php echo isset($subcategoryData->subcategoryName) ? 'Update' : 'Add'; ?> Subcategory</span></h2>
			<div class="breadcrumb-wrapper hidden-xs">
				<span class="label">You are here:</span>
				<ol class="breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="<?php echo DASHURL."/".$this->sessRole."/welcome"; ?>">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li class="active"><?php echo isset($subcategoryData->subcategoryName) ? 'Update' : 'Add'; ?> Subcategory</li>
				</ol>
			</div>
		</div><!-- /.header-content -->
		<!--/ End page header -->
<!-- Start body content -->
	<div class="body-content animated fadeIn">
		<div class="row">
            <div class="col-md-12">
                <div class="panel panel-default shadow no-overflow">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h3 class="panel-title">Create New Subcategory</h3>
                        </div>                                  
                        <div class="clearfix"></div>
                    </div><!-- /.panel-heading -->
                    <div class="panel-body">
                        <form class="form-horizontal form-bordered" id="basic-validate" method="post" role="form"  enctype="multipart/form-data">
                            <div class="form-body"> 
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Select Category</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="selCategory" id="selCategory">
                                            <?php   
                                              if(isset($categoryData)) {
                                                foreach($categoryData as $row) { ?>
                                                    <option value="<?php echo $row->categoryId;?>" <?php echo (isset($subcategoryData->subcategoryName) && $subcategoryData->categoryId == $row->categoryId) ? 'Selected' : ''; ?> ><?php echo $row->categoryName;?></option>
                                              <?php } }  ?>
                                        </select>
                                    </div>
                                </div><!-- /.form-group --> 
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Subcategory Name <span class="asterisk">*</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control input-sm" value="<?php echo isset($subcategoryData->subcategoryName) ? $subcategoryData->subcategoryName : ''; ?>" placeholder="Subcategory Name" required id="txtsubcategoryName" name="txtsubcategoryName" >
                                    </div>
                                </div><!-- /.form-group -->
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Subcategory Description <span class="asterisk">*</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control input-sm" value="<?php echo isset($subcategoryData->description) ? $subcategoryData->description : ''; ?>" placeholder="Subcategory Description" required id="txtDescription" name="txtDescription">
                                    </div>
                                </div><!-- /.form-group --> 
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Subcategory Icon <span class="asterisk">*</span></label>
                                    <div class="col-sm-7">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                <img <?php echo (isset($subcategoryData->icon) && $subcategoryData->icon !='') ? 'src="'.UPLOADPATH.'/subcategory_icons/'.$subcategoryData->icon.'"' : 'data-src="holder.js/200x150/blankon/text:Icon JPEG or PNG"'; ?> alt="Category Icon">
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
                                            <div>
                                                <span class="btn btn-teal btn-sm btn-file"><span class="fileinput-new">Select Icon</span><span class="fileinput-exists">Change</span><input type="file" name="uploadIcon" <?php echo isset($subcategoryData->subcategoryName) ? '' : 'required'; ?>></span>
                                                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.form-group -->
                            </div>                                            
    							<!-- Start pager -->
    						<div class="panel-footer no-bg">
    							<label class="col-sm-8"></label>
    							<button type="submit" id="btnAddSubcategory" name="btnAddSubcategory" class="btn btn-sm btn-primary"> <?php echo isset($subcategoryData->subcategoryName) ? 'Update' : 'Add'; ?> Subcategory</button>
    						</div>
    						<!--/ End pager -->
    					</form>
    				</div>                                        
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
        </div>
	</div><!-- /.body-content -->
	<!--/ End body content -->
		<?php $this->load->viewD('admin/inc/footer01'); ?>

		</section><!-- /#page-content -->
		<!--/ END PAGE CONTENT -->

		<!-- START @SIDEBAR RIGHT -->
		
		<!--/ END SIDEBAR RIGHT -->

        </section><!-- /#wrapper -->
        <!--/ END WRAPPER -->

        <!-- START @BACK TOP -->
        <div id="back-top" class="animated pulse circle">
            <i class="fa fa-angle-up"></i>
        </div><!-- /#back-top -->
        <!--/ END BACK TOP -->


        <?php $this->load->viewD('admin/inc/footer_page_form'); ?>

    </body>
    <!--/ END BODY -->

</html>