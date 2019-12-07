<?php $this->load->viewD($this->sessRole.'/inc/header_page_table'); ?> 
 <link href="<?php echo DASHSTATIC; ?>/admin/css/pages/invoice.css" rel="stylesheet">   
	<?php $this->load->viewD($this->sessRole.'/inc/sidebar01'); ?>

        	<form  id="frmPostCandidate" name="frmPostCandidate" method="POST">
	            <!-- START @PAGE CONTENT -->
	            <section id="page-content">

	                <!-- Start page header -->
	                <div class="header-content">
	                    <h2><i class="fa fa-list-alt"></i> Order Details <span>Listing & View</span></h2>
	                    <div class="breadcrumb-wrapper hidden-xs">
	                        <span class="label">You are here:</span>
	                        <ol class="breadcrumb">
	                            <li>
								<i class="fa fa-home"></i>
								<a href="<?php echo DASHURL."/".$this->sessRole."/welcome"; ?>">Dashboard</a>
								<i class="fa fa-angle-right"></i>
							</li>
							<i class="fa fa-list-alt"></i>
							<li class="active">Order Details</li>
	                        </ol>
	                    </div><!-- /.breadcrumb-wrapper -->
	                </div><!-- /.header-content -->
	                <!--/ End page header -->

	                <!-- Start body content -->
	                <div class="body-content animated fadeIn">

	                    <div class="row">
	                        <div class="col-md-12">

	                            <!-- Start table advanced -->
	                            <div class="panel panel-default shadow no-overflow">
	                                <div class="panel-heading">
	                                    <div class="pull-left">
	                                        <h3 class="panel-title">Order Details</h3>
	                                    </div>
	                                    <div class="clearfix"></div>
	                                </div><!-- /.panel-heading -->
	                                <div class="panel-body no-padding">
                                        <div class="panel-body">
                                            <div class="panel panel-default panel-table no-margin">
                                                <div class="panel-body no-padding"> 
                                                	<table class="table table-striped table-theme">
                                            <thead>
                                            <tr>
                                                <th>Product Description</th>
                                                <th>Qty</th>
                                                <th>Unit Price (Rs)</th>
                                                <th>Discount %</th>
                                                <th>Tax</th>
                                                <th>Total Price (Rs)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            	<?php if (valResultSet($orderData)) { 
                                            		$srno = 1;
                                            		$subtotal = 0;
                                            		foreach ($orderData as $key => $value) {
                                            			$subtotal = $subtotal+$value->subtotal;
                                            		?>
	                                            <tr>
	                                                <td><div class="product-name">
	                                                    	<div class="product-num" style="    padding: 0px;
    width: 34px;">
	                                                    		<?=$srno++ ?>
	                                                    	</div>
	                                                    	<h4><?=$value->productName ?></h4><small><?=$value->restaurantName ?></small>
	                                                	</div>
	                                                </td>
	                                                <td><?=$value->quantity ?></td>
	                                                <td>Rs <?=$value->price ?></td>
	                                                <td><?=$value->discount ?></td>
	                                                <td>0.00</td>
	                                                <td>Rs <?=$value->subtotal ?></td>
	                                            </tr>
                                            <?php }} ?>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="3" rowspan="3">&nbsp;</td>
                                                <td colspan="2">Total (Net)</td>
                                                <td colspan="2">Rs <?= isset($subtotal)?$subtotal:'0:00' ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">VAT</td>
                                                <td colspan="2">0.00</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><b>Total</b></td>
                                                <td colspan="2"><b>Rs <?= isset($subtotal)?$subtotal:'0:00' ?></b></td>
                                            </tr>
                                            </tfoot>

                                        </table>
                                                </div>
                                            </div>
                                        </div><!-- /.panel-body -->
	                                       
	                                </div>
	                            </div><!-- /.panel -->
	                            <!--/ End table advanced -->

	                        </div><!-- /.col-md-12 -->
	                    </div><!-- /.row -->

	                </div><!-- /.body-content -->
	                <!--/ End body content -->

	                <?php $this->load->viewD($this->sessRole.'/inc/footer01'); ?>

	            </section><!-- /#page-content -->
	            <!--/ END PAGE CONTENT -->

	        </section><!-- /#wrapper -->
	        <!--/ END WRAPPER -->

	        <!-- START @BACK TOP -->
	        <div id="back-top" class="animated pulse circle">
	            <i class="fa fa-angle-up"></i>
	        </div><!-- /#back-top -->
        <!--/ END BACK TOP -->

	 </form>
<?php $this->load->viewD($this->sessRole.'/inc/footer_page_table'); ?>


    </body>
    <!--/ END BODY -->

</html>
