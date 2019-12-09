<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<link rel="stylesheet" type="text/css" href="<?=DASHSTATIC?>/css/addons/datatables.min.css">
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
  

  <section class="heading-section">
    <div class="row">
      <div class="col-lg-12">
        <div class="heading">
          <div class="mr-auto">
            <h1>User List</h1>
          </div>
          <div class="ml-auto">
            <a href="<?=DASHURL.'/'.$this->sessRole?>/user/add-user" class="btn btn-info">Add New Student</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="course-list">
    <div class="row">
      <div class="col-md-12">
        <h2>Course List</h2>
        <table class="table courseList-table" id="tableDataList">
          <thead>
            <tr>
              <th>Image</th>
              <th>Name</th>
              <th>Email</th>
              <th>Mobile</th>
              <th>Create Date</th>
              <th>Action</th>
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
          "data":{"action" : "getUserList"}
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