<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<link rel="stylesheet" type="text/css" href="<?=DASHSTATIC?>/css/addons/datatables.min.css">
<?php $this->load->viewD($this->sessRole.'/inc/sidebar');?>

    <!-- Page Content -->
    <div id="page-content-wrapper">
      <div class="dashboard-page">
        <div class="container-fluid">
          <div class="row">
          <div class="col-md-12">
              <h1 class="mt-4">Course Details View</h1>
              <div class="w-100 p-4 mt-4 shadow-lg bg-white rounded">
                <div class="row">
                  <div class="col-md-3 pr-4">
                    <div class="d-flex flex-column justify-content-center align-items-center text-center text-secondary">
                      <?php $imgPath =UPLOADPATH."/use/images/".$coursesData->thumbnailImage; ?>
                      <img src="<?php echo $imgPath?>" class="rounded-circle shadow-sm mt-2" alt="...">
                    </div>
                    
                  </div>
                 
                  <div class="col-md-9 border-left pl-4 pb-5">
                    <table class=" profile-information-table">

                        <tr>
                          <th>Course Name</th>
                          <th class="dot1">:</th>
                          <td><?php echo $coursesData->courseName; ?></td>
                        </tr>
                        <tr>
                          <th>Category Name</th>
                          <th class="dot1">:</th>
                          <td><?php echo $coursesData->categoryName; ?></td>
                        </tr>
                        <tr>
                          <th>Course Price</th>
                          <th class="dot1">:</th>
                          <td><?php echo $coursesData->coursePrice; ?></td>
                        </tr>
                        <tr>
                          <th>Course Title</th>
                          <th class="dot1">:</th>
                          <td><?php echo $coursesData->courseTitle; ?></td>
                        </tr>
                        <tr>
                          <th>Course Description</th>
                          <th class="dot1">:</th>
                          <td><?php echo $coursesData->courseDescription; ?></td>
                        </tr>
                        <tr>
                          <th>Course Price</th>
                          <th class="dot1">:</th>
                          <td><?php echo $coursesData->coursePrice; ?></td>
                        </tr>
                        <tr>
                          <th>Course Price After Discount</th>
                          <th class="dot1">:</th>
                          <td><?php echo $coursesData->coursePriceAfterDiscount; ?></td>
                        </tr>

                        <tr>
                          <th>Status</th>
                          <th class="dot1">:</th>
                          <td><?php echo $coursesData->status; ?></td>
                        </tr>
                        <tr>
                          <th>Added On</th>
                          <th class="dot1">:</th>
                          <td><?php echo $coursesData->addedOn; ?></td>
                        </tr>
                        
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /#page-content-wrapper -->
  </div>
  <!-- /#wrapper -->

  <!-- Menu Toggle Script -->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/popper.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <script type="text/javascript" src="js/custom.js"></script>
</body>

</html>
