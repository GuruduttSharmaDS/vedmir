<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<link rel="stylesheet" type="text/css" href="<?=DASHSTATIC?>/css/addons/datatables.min.css">
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
  

<section class="heading-section">
  <div class="row">
    <div class="col-lg-12">
      <div class="heading">
        <div class="mr-auto">
          <h1><?= $this->lang->line('studentList') ?></h1>
        </div>
        <div class="ml-auto">
          <a href="<?=DASHURL.'/'.$this->sessRole?>/user/add-user" class="btn btn-info"><?= $this->lang->line('addNewStudent') ?></a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="course-list">
  <div class="row">
    <div class="col-md-12">
      <h2><?= $this->lang->line('studentList') ?></h2>
      <table class="table courseList-table" id="tableDataList">
        <thead>
          <tr>
            <th><?= $this->lang->line('image') ?></th>
            <th><?= $this->lang->line('name') ?></th>
            <th><?= $this->lang->line('email') ?></th>
            <th><?= $this->lang->line('mobile') ?></th>
            <th><?= $this->lang->line('createdAt') ?></th>
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
          "data":{"action" : "getStudentList"}
      },
      "columns": [
            {"data": "img"},
            {"data": "userName"},
            {"data": "email"},
            {"data": "mobile"},
            {"data": "addedOn"},
            {"data": "action"},
          ],
      "order": [[0, 'desc']]
    });
  </script>

  </body>
</html>