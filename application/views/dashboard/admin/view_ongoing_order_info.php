<style type="text/css">label>img{width: 200px;height: 150px; border:1px solid grey;padding: 2px;}</style>
<div class= "panel panel-primary" >
	<div class="panel-heading">
        <div class="pull-left">
            <h3 class="panel-title">Order Details</h3>
        </div>
        <div class="clearfix"></div>
	</div><!-- /.panel-heading -->
		<div class="panel-body">
			<table class="table">
                <thead>
                    <tr>
                        <th>Product Image</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th class="text-center" style="min-width: 15%">Actions</th>
                    </tr>
                </thead>
                <tbody>
					<?php if (valResultSet($productInfo)) {
						foreach ($productInfo as $product) {
					?>

                    <tr>
                        <td>
                        	<img src="<?=UPLOADPATH.'/product_images/'.$product->img ?>" class="img-bordered-default " height="50px" width="50px" alt="Product Icon">
                        </td>
                        <td>
                            <b class="text-block"><?=$product->productName ?></b>
                        </td>
                        <td>
                            <b class="text-block"><?=$product->quantity ?></b>
                            
                        </td>
                        <td  class="text-center">
                        	<?php if ($product->isServed == 0) 
                        		echo '<a href="#" class="btn btn-sm btn-primary btn-xs btn-push makeserved" data-id="'.$product->detailId.'" data-toggle="tooltip" data-placement="top" data-original-title="Update As Served">Make Served</a>';
                        	else
                        		echo '<a href="#" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Served">Served</a>';
                        	 ?>
                            
                        </td>
                    </tr>
                    <?php } } ?>
                    <tr class="alrt"></tr>
                </tbody>
            </table>
		</div><!-- /.panel-body -->
</div>
<script type="text/javascript">
		$(document).find('.makeserved').click(function(){
			var obj = $(this);
			obj.text('updating..');
			obj.closest('tbody').find('tr.alrt').html('');
			$.ajax({
				url:DASHURL+'/restaurant/order/make-served',
				method:'POST',
				data:{detailId:$(this).attr('data-id')},
				success: function(data){
					if (data == 'updated')
						obj.closest('td').html('<a href="#" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Served">Served</a>');
					else{
						obj.text('Make Served');
						obj.closest('tbody').find('tr.alrt').html('<td colspan="4"><div class="alert alert-danger"><strong>Oh snap!</strong> Try submitting again.</div></td>');
					}

				},
				failed:function(data){

					obj.text('Make Served');
					obj.closest('tbody').find('tr.alrt').html('<td colspan="4"><div class="alert alert-danger"><strong>Oh snap!</strong> Try submitting again.</div></td>');
				}
			});
		});
</script>



