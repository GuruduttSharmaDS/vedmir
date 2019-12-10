
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light top-nav">
    <a class="navbar-brand" href="<?php echo DASHURL."/admin/welcome"; ?>"><img src="<?=DASHSTATIC?>/img/main-logo.png" /></a>     
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-4 mr-auto">
        <li class="nav-item active">
          <a class="nav-link toggle-left" href="#" id="menu-toggle"><img src="<?=DASHSTATIC?>/img/setting-icon.png" /></a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="user-dp mr-1"><img src="<?=DASHSTATIC?>/img/default-dp.png" /></span>
            Admin
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="<?php echo DASHURL."/admin/profile/change-password"; ?>"><i class="fa fa-lock mr-2"></i><?=$this->lang->line('changePassword') ?></a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?php echo DASHURL."/auth/logout"; ?>"><i class="fa fa-sign-out-alt mr-2"></i><span><?=$this->lang->line('logout') ?></span></a>
          </div>
        </li>
      </ul>
    </div>
  </nav>

  <div class="d-flex main-body" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-light" id="sidebar-wrapper">
      <aside>

        <div class="list-group list-group-flush">
          <ul class="navbar-nav w-100">
            <li class="nav-item">
                <a href="<?php echo DASHURL."/admin/welcome"; ?>" class="list-group-item list-group-item-action nav-link"> <i class="fas fa-tachometer-alt"></i><span><?=$this->lang->line('dashboard') ?></span> </a>
            </li>
            <!-- Student Management -->
            <li class="nav-item dropdown <?=($this->menu == 3) ? 'show' : ''; ?>">
                <a class="list-group-item list-group-item-action nav-link dropdown-toggle <?=($this->menu == 3) ? 'active' : ''; ?>" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="<?=($this->menu == 3) ? 'true' : 'false'; ?>">
                  <i class="fas fa-user"></i><span><?= $this->lang->line('students') ?></span>
                </a>

                <div class="dropdown-menu <?=($this->menu == 3) ? 'show' : ''; ?>" aria-labelledby="navbarDropdown">
                    <a href="<?php echo DASHURL."/admin/student/add"; ?>" class="dropdown-item <?=($this->subMenu == 31) ? 'active' : ''; ?>">
                      <?=$this->lang->line('addStudent') ?>
                    </a>
                    <a href="<?php echo DASHURL."/admin/student/list"; ?>" class="dropdown-item <?php echo ($this->subMenu == 32) ? 'active' : ''; ?>"> 
                      <?=$this->lang->line('studentList') ?> 
                    </a>
                </div>
            </li>            
          </ul>     
        </div>
        <!-- <div class="fixed-footer-button">
          <ul class="navbar-nav w-100">
            <li>
              <a class="list-group-item list-group-item-action nav-link" href="<?php echo DASHURL."/auth/logout"; ?>"><i class="fas fa-star"></i><span><?=$this->lang->line('logout') ?></span></a>
            </li>
          </ul>
        </div> -->
      </aside>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">
      <div class="dashboard-page">
        <div class="container-fluid">
            <ul><?php echo $this->common_lib->showSessMsg(); ?></ul>
            