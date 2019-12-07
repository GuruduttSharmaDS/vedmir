<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
          
          <form action="" method="POST" enctype="multipart/form-data">
          <div class="you_requested">
          <h3><?php echo $profile->userName; ?> <i style="font-size: 15px;color: #827c7c;">(<?=ucfirst($this->lang->line('user'))?>)</i></h3>
          
            <div class="expert_breif">
                <div class="image-upload"><?php
          $absPath = ABSUPLOADPATH."/user_images/".$profile->img;
          if(($profile->img != "") && (file_exists($absPath))){
              $logoPath = UPLOADPATH."/user_images/".$profile->img;
              }else{
                $logoPath = DASHSTATIC."/restaurant/assets/img/user.png";
              } 
          ?>
           
                    <img class="img-circle img-bordered-success" src="<?php echo $logoPath."?".StringGenerator(6); ?>" alt="User Logo"  height="200" width="200" style="border:2px solid black;">

              </div>

              

            </div>
            <div class="expert_contact">
              <div class="row">
                <label class="col-md-3 text-strong"><?=$this->lang->line('status')?></label>
                <div class="col-md-9">
                   <?php echo ($profile->status == 0)?'<span class="label label-success">Active</span>':'<span class="label label-danger">DeActive</span>'; ?>
                </div>
              </div>
              <div class="row">
                <label class="col-md-3 text-strong"><?=$this->lang->line('gender')?></label>
                <div class="col-md-9">
                   <?php echo $profile->gender; ?>
                </div>
              </div>
              <div class="row">
                <label class="col-md-3 text-strong"><?=$this->lang->line('dateOfBirth')?></label>
                <div class="col-md-9">
                   <?php echo $profile->dob; ?>
                </div>
              </div>
              <div class="row">
                <label class="col-md-3 text-strong"><?=$this->lang->line('occupation')?></label>
                <div class="col-md-9">
                   <?php echo $profile->occupation; ?>
                </div>
              </div>
              <div class="row">
                <label class="col-md-3 text-strong"><?=$this->lang->line('membership')?></label>
                <div class="col-md-9">
                   <?php echo $profile->membershipName; ?><a href="<?php echo DASHURL;?>/admin/user/view-membership/<?php echo $profile->userId;?>" style="padding-left:10px;"><i class="fa fa-eye"></i></a>
                </div>
                
              </div>
              <div class="row">
                <label class="col-md-3 text-strong"><?=$this->lang->line('about')?></label>
                <div class="col-md-9">
                   <?php echo $profile->about; ?>
                </div>
              </div>
              <div class="row">
                  <div class="image-upload">
                    <ul class="list-group no-margin">
                        <li class="list-group-item"><i class="fa fa-envelope mr-5"></i> <?php echo $profile->email; ?></li>
                        <li class="list-group-item"><i class="fa fa-phone mr-5"></i> <?php echo $profile->mobile; ?></li>
                        <li class="list-group-item"><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo $profile->city.','.$profile->state.','.$profile->country; ?></li>
                    </ul>
                  </div>
              </div>
            </div><!--you_requested-->

      </form>
      <div class="row">
          <div class="col-md-6"><h3><?php echo $this->lang->line('orders');?></h3>
            <table class="table" id="orderTable">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('date')?></th>
                    <th><?=$this->lang->line('venue')?></th>
                    <th><?=$this->lang->line('ProductVariable')?></th>
                    <th><?=$this->lang->line('price')?></th>
                  </tr>
                </thead>
                <tbody class="tablebody">



                </tbody>
            </table></div>
          <div class="col-md-6"><h3><?php echo $this->lang->line('thelistofcoupons');?></h3>
             <table class="table" id="couponList">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('nameofcoupon')?></th>
                    <th><?=$this->lang->line('useDate')?></th>
                    <th><?=$this->lang->line('benefit')?></th>
                    
                  </tr>
                </thead>
                <tbody class="tablebody">



                </tbody>
            </table>
          </div>
      </div>
<?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>
<script type="text/javascript">

  $(document).ready(function(){

    $('#orderTable').DataTable({

        "processing": true,

        "serverSide": true,
        
        "pageLength": 10,
        "searching": false,

        "ajax":{

            "url": DASHURL+'/admin/commonajax',

            "dataType": "json",

            "type": "POST",

            "data":{'action' : 'getUserOrderList', 'userId':  '<?php echo $profile->userId;?>'}

        },

        "columns": [

              {"data": "date"},

              {"data": "restaurantName"},

              {"data": "productName"},
              
              {"data": "price"}

           ],

           "order": [[0, 'desc']]



    });
    
    $('#couponList').DataTable({

        "processing": true,

        "serverSide": true,
        
        "pageLength": 10,
        "searching": false,

        "ajax":{

            "url": DASHURL+'/admin/commonajax',

            "dataType": "json",

            "type": "POST",

            "data":{'action' : 'getUserCouponList', 'userId':  '<?php echo $profile->userId;?>'}

        },

        "columns": [

              {"data": "nameofCoupon"},

              {"data": "dateofUsed"},

              {"data": "benefit"}

           ],

           "order": [[1, 'desc']]



    });

    
  });

</script>
    </body>
    <!--/ END BODY -->

</html>