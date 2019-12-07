<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>

          
          <section class="heading-section">
            <div class="row">
              <div class="col-lg-12">
                <div class="heading">
                  <h1>Dashboard</h1>
                  <div class="date-box">
                    <input type="text" class="form-control date" placeholder="Date">
                  </div>
                </div>
              </div>
            </div>
          </section>

          <section class="statistic-section">
            <div class="row">
              <div class="col-sm-3">
                <div class="statbox">
                  <h4><?=$this->lang->line('registeredUsers') ?></h4>
                  <h3><?=(isset($statisticsData->totalUsers))?$statisticsData->totalUsers:0 ?></h3>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="statbox">
                  <h4><?=$this->lang->line('submittedOrders') ?></h4>
                  <h3><?=(isset($statisticsData->totalOrder))?$statisticsData->totalOrder:0 ?></h3>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="statbox">
                  <h4><?=$this->lang->line('TotalAmountOfSubscribers') ?></h4>
                  <h3><?=(isset($statisticsData->activeMembership))?$statisticsData->activeMembership:0 ?></h3>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="statbox">
                  <h4>Total Courses</h4>
                  <h3><?=(isset($statisticsData->totalCourse))?$statisticsData->totalCourse:0 ?></h3>
                </div>
              </div>
            </div>
          </section>

        <?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>

    </body>
</html>
