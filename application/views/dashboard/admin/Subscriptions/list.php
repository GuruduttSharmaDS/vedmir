<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<link rel="stylesheet" type="text/css" href="<?=DASHSTATIC?>/css/addons/datatables.min.css">
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
  

<section class="heading-section">
  <div class="row">
    <div class="col-lg-12">
      <div class="heading">
        <div class="mr-auto">
          <h1><?= $this->lang->line('subscriptionList') ?></h1>
        </div>
        <div class="ml-auto">
          <a href="<?=DASHURL.'/'.$this->sessRole?>/subscriptions/add" class="btn btn-info">Add New Subscription</a>
          <!-- <?= $this->lang->line('addNewSubscription') ?> -->
        </div>
      </div>
    </div>
  </div>
</section>

<section class="course-list">
  <div class="row">
    <div class="col-md-12">
      <h2><?= $this->lang->line('subscriptionList') ?></h2>
     
      <div class="form-group col-md-3">
        <label>User Type</label>
        <select class="form-control bindaddress" name="userType" id="userType" required="">
          <option value="1"> Student</option>
          <option value="2"> Teacher</option> 
        </select>
      </div>

      <table class="table courseList-table" id="tableDataList">
        <thead>
          <tr>
            <th><?= $this->lang->line('icon') ?></th>
            <th><?= $this->lang->line('planName') ?></th>
            <th><?= $this->lang->line('duration') ?></th>
            <th><?= $this->lang->line('description') ?></th>
            <th><?= $this->lang->line('userType') ?></th>
            <th><?= $this->lang->line('action') ?></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>

    </div>
  </div>
</section>
      
<?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>
<script type="text/javascript" src="<?=DASHSTATIC?>/js/addons/datatables.min.js"></script>
<script type="text/javascript">

  $('#userType').on('change', function() {
     
    $("#tableDataList").dataTable().fnDestroy();


    getSubscriptionList (this.value);
  });

  function getSubscriptionList (userType) {
    $('#tableDataList').DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 10,
        "preDrawCallback": function (settings) {                
          currentAjax = 1 ;
        },
        "fnDrawCallback": function( oSettings ) {
          currentAjax = 0 ;
        },
        "ajax":{
            "url": DASHURL+'/admin/commonajax',
            "dataType": "json",
            "type": "POST",
            "data":{"action" : "getSubscriptionList", "userType": userType}
        },
        "columns": [
            
              {"data": "icon"},
              {"data": "planName"},
              {"data": "duration"},
              {"data": "description"},
              {"data": "userType"},
              {"data": "action"},
            ],
        "order": [[0, 'desc']]
      });
  }

  getSubscriptionList (1);
  
  </script>

  </body>
</html>