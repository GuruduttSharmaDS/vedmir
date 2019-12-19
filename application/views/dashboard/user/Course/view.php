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
                 

                  <div class="expert_breif">
                    <div class="image-upload" style="margin-bottom:15px;">
                    <?php
                          $absPath = ABSUPLOADPATH."/course_images/".$coursesData->thumbnailImage;
                          if (($coursesData->thumbnailImage != "") && (file_exists($absPath))) {
                              $logoPath = UPLOADPATH."/course_images/".$coursesData->thumbnailImage;
                          } else {
                            $logoPath = DASHSTATIC."/img/user-icon.jpg";
                          } 
                          ?>
                      <img class="rounded img-bordered-success" src="<?php echo $logoPath."?".StringGenerator(6); ?>" alt="User Logo" height="200" width="200">
                    </div>
                  </div>

                 
                  <div class="col-md-9 border-left pl-4 pb-5">
                    <table class=" profile-information-table">

                        <tr>
                          <th>Course Name</th>
                          <th class="dot1">:</th>
                          <td><?php echo isset ($coursesData->courseName) ? $coursesData->courseName : "" ; ?>
                          </td>
                        </tr>
                        <tr>
                          <th>Category Name</th>
                          <th class="dot1">:</th>
                          <td><?php echo isset ($coursesData->categoryName) ? $coursesData->categoryName : "" ; ?></td>
                        </tr>
                        <!-- <tr>
                          <th>Course Price</th>
                          <th class="dot1">:</th>
                          <td><?php echo isset ($coursesData->coursePrice) ? $coursesData->coursePrice : "" ; ?></td>
                        </tr> -->
                        <tr>
                          <th>Course Title</th>
                          <th class="dot1">:</th>
                          <td><?php echo isset ($coursesData->courseTitle) ? $coursesData->courseTitle : "" ; ?></td>
                        </tr>
                        <tr>
                          <th>Course Description</th>
                          <th class="dot1">:</th>
                          <td><?php echo isset ($coursesData->courseDescription) ? $coursesData->courseDescription : "" ; ?></td>
                        </tr>
                        <tr>
                          <th>Course Price</th>
                          <th class="dot1">:</th>
                          <td><?php echo isset ($coursesData->coursePrice) ? $coursesData->coursePrice : "" ; ?></td>
                        </tr>
                        <tr>
                          <th>Course Price After Discount</th>
                          <th class="dot1">:</th>
                          <td><?php echo isset ($coursesData->coursePriceAfterDiscount) ? $coursesData->coursePriceAfterDiscount : "" ; ?></td>
                        </tr>

                        <tr>
                          <th>Status</th>
                          <th class="dot1">:</th>
                          <td>
                            <?php echo ($coursesData->status == 0) ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">DeActive</span>'; ?>
                          </td>
                        </tr>
                        <tr>
                          <th>Added On</th>
                          <th class="dot1">:</th>
                          <td><?php echo isset ($coursesData->addedOn) ? $coursesData->addedOn : "" ; ?></td>
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

  <!-- /#wrapper -->

  <!-- Menu Toggle Script -->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/popper.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <script type="text/javascript" src="js/custom.js"></script>
</body>

</html>
