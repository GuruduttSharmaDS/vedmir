<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<link rel="stylesheet" type="text/css" href="<?=DASHSTATIC?>/css/addons/datatables.min.css">
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
  

          <section class="heading-section">
            <div class="row">
              <div class="col-lg-12">
                <div class="heading">
                  <div class="mr-auto">
                    <h1>Courses List</h1>
                  </div>
                  <div class="ml-auto">
                    <a href="<?=DASHURL.'/'.$this->sessRole?>/Courses/add" class="btn btn-info">Add New Courses</a>
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
                      <th>Sr No.</th>
                      <th>Course Name</th>
                      <th>Category Name</th>
                      <th>Course Price</th>
                      <th>Status</th>
                      <th>Added On</th>
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
              "data":{"action" : "getCourseList"}
          },
          "columns": [
                
                {"data": "srNo"},
                {"data": "courseName"},
                {"data": "categoryName"},
                {"data": "coursePrice"},
                {"data": "status"},
                {"data": "addedOn"},
                {"data": "action"},
             ],
          "order": [[0, 'desc']]
        });
      </script>
    </body>
</html>