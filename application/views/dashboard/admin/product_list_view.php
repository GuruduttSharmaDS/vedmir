<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>

            <div class="">
              <div class="order_status">
                <h2><?=$this->lang->line('productList')?><span><i class="fa fa-gear" aria-hidden="true"></i></span></h2>
              </div>
              <table class="table" id="sampleTable">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('image')?></th>
                    <th><?=$this->lang->line('name')?></th>
                    <th><?=$this->lang->line('price')?></th>
                    <th><?=$this->lang->line('description')?></th>
                    <th><?=$this->lang->line('restaurant')?></th>
                    <th><?=$this->lang->line('type')?></th>
                    <th><?=$this->lang->line('categoryName')?></th>
                    <th><?=$this->lang->line('subCategoryName')?></th>
                    <th><?=$this->lang->line('status')?></th>
                    <th><?=$this->lang->line('action')?></th>
                  </tr>
                </thead>
                <tbody class="tablebody">



                </tbody>
              </table>
            </div>
<?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>
    </body>
    <!--/ END BODY -->

</html>
<?php 

  $filterBy = (isset($_GET['search']) && !empty($_GET['search']))?'"search": {"search": "'.trim($_GET['search']).'"},':'';
  $filterBy .= '"pageLength": '.((isset($_GET['limit']) && !empty($_GET['limit']))?trim($_GET['limit']):100).',';
  $filterBy .= (isset($_GET['start']) && $_GET['start'] !='')?'"displayStart": '.trim($_GET['start']).',':'';
  $sortBy = (isset($_GET['order']) && $_GET['order'] !='' && isset($_GET['dir']) && $_GET['dir'] !='')?$_GET['order'].',"'.trim($_GET['dir']).'"':'0, \'desc\'';
 ?>

<script type="text/javascript">/*-------- get product list -----------*/

  $(document).ready(function(){

    $('#sampleTable').DataTable({

        "processing": true,

        "serverSide": true,<?=$filterBy?>

        "ajax":{

            "url": DASHURL+'/admin/commonajax',

            "dataType": "json",

            "type": "POST",

            "data":{'action' : 'Get_Product_List' }

        },

        "columns": [

              {"data": "image"},

              {"data": "productName"},

              {"data": "price"},

              {"data": "description"},

              {"data": "restaurantName"},

              {"data": "categoryName"},

              {"data": "subcategoryName"},

              {"data": "subcategoryitemName"},

              {"data": "status"},

              {"data": "action"},

           ],

           "order": [[<?=$sortBy?>]]



    });

    
  });

</script>