
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
            <li class="nav-item dropdown <?=($this->menu == 3) ? 'show' : ''; ?>">
                <a class="list-group-item list-group-item-action nav-link dropdown-toggle <?=($this->menu == 3) ? 'active' : ''; ?>" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="<?=($this->menu == 3) ? 'true' : 'false'; ?>"><i class="fas fa-user"></i><span><?=$this->lang->line('users') ?></span></a>
                <div class="dropdown-menu <?=($this->menu == 3) ? 'show' : ''; ?>" aria-labelledby="navbarDropdown">
                    <a href="<?php echo DASHURL."/admin/user/add-user"; ?>" class="dropdown-item <?=($this->subMenu == 31) ? 'active' : ''; ?>"><?=$this->lang->line('addUsers') ?></a>
                    <a href="<?php echo DASHURL."/admin/user/user-list"; ?>" class="dropdown-item <?php echo ($this->subMenu == 32) ? 'active' : ''; ?>"> <?=$this->lang->line('userList') ?> </a>
                    <a href="<?php echo DASHURL."/admin/user/blocked-user-list"; ?>" class="dropdown-item <?php echo ($this->subMenu == 34) ? 'class="active"' : ''; ?>"> <?=$this->lang->line('blocked').' '.$this->lang->line('userList') ?> </a>
                    <a href="<?php echo DASHURL."/admin/user/enquiry-list"; ?>" class="dropdown-item <?php echo ($this->subMenu == 33) ? 'class="active"' : ''; ?>"> <?=$this->lang->line('enquiryList') ?> </a>
                </div>
            </li>
            <li class="nav-item dropdown <?=($this->menu == 7) ? 'show' : ''; ?>">
                <a class="list-group-item list-group-item-action nav-link dropdown-toggle <?=($this->menu == 7) ? 'active' : ''; ?>" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="<?=($this->menu == 7) ? 'true' : 'false'; ?>"><i class="fas fa-star"></i><span><?=$this->lang->line('subscription') ?></span></a>
                <div class="dropdown-menu <?=($this->menu == 7) ? 'show' : ''; ?>" aria-labelledby="navbarDropdown">
                    <a href="<?php echo DASHURL."/admin/subscription/add-subscription"; ?>" class="dropdown-item <?php echo ($this->subMenu == 71) ? 'active' : ''; ?>"><?=$this->lang->line('addSubscription') ?></a>
                    <a href="<?php echo DASHURL."/admin/subscription/subscription-list"; ?>" class="dropdown-item <?php echo ($this->subMenu == 72) ? 'active' : ''; ?>"> <?=$this->lang->line('subscriptionList') ?> </a>
                </div>
            </li>
            <li class="nav-item">
              <a class="list-group-item list-group-item-action nav-link" href="#"><i class="fas fa-hands-helping"></i> <span>Support</span></a>
            </li>
            <li class="nav-item">
              <a class="list-group-item list-group-item-action nav-link" href="#"><i class="fas fa-chart-pie"></i> <span>Sales Statics</span></a>
            </li>
            <li class="nav-item">
              <a class="list-group-item list-group-item-action nav-link" href="#"><i class="fas fa-users"></i> <span>Employ Management</span></a>
            </li>
            <li class="nav-item">
              <a class="list-group-item list-group-item-action nav-link" href="#"><i class="fab fa-youtube"></i> <span>Course Management</span></a>
            </li>
            <li class="nav-item">
              <a class="list-group-item list-group-item-action nav-link" href="#"><i class="fas fa-bell"></i> <span>Send Notification</span></a>
            </li>
          </ul>     
        </div>
        <div class="fixed-footer-button">
          <ul class="navbar-nav w-100">
            <li>
              <a class="list-group-item list-group-item-action nav-link" href="<?php echo DASHURL."/auth/logout"; ?>"><i class="fas fa-star"></i><span><?=$this->lang->line('logout') ?></span></a>
            </li>
          </ul>

        </div>
      </aside>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">
      <div class="dashboard-page">
        <div class="container-fluid">
            <ul><?php echo $this->common_lib->showSessMsg(); ?></ul>
            