
<body>
    <nav class="navbar navbar-expand-lg navbar-light top-nav">
        <a class="navbar-brand" href="<?php echo DASHURL."/admin/welcome"; ?>"><img src="<?=DASHSTATIC?>/img/main-logo.png"/></a>        
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-4 mr-auto">
                <li class="nav-item active">
                    <a class="nav-link toggle-left" href="#" id="menu-toggle"><img src="<?=DASHSTATIC?>/img/setting-icon.png" /></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" 
                    aria-expanded="false">
                        <span class="user-dp mr-1"><img src="<?=DASHSTATIC?>/img/default-dp.png" /></span>
                        Admin
                    </a>

                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo DASHURL."/admin/profile/change-password"; ?>"><i
                                class="fa fa-lock mr-2"></i><?=$this->lang->line('changePassword') ?></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php echo DASHURL."/auth/logout"; ?>"><i
                                class="fa fa-sign-out-alt mr-2"></i><span><?=$this->lang->line('logout') ?></span></a>
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

                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="<?php echo DASHURL."/admin/welcome"; ?>"
                                class="list-group-item list-group-item-action nav-link"> <i
                                    class="fas fa-tachometer-alt"></i><span><?=$this->lang->line('dashboard') ?></span>
                            </a>
                        </li>

                        <!-- Student Management -->
                        <li class="nav-item dropdown <?=($this->menu == 2) ? 'show' : ''; ?>">
                            <a class="list-group-item list-group-item-action nav-link dropdown-toggle <?=($this->menu == 2) ? 'active' : ''; ?>"
                                href="#" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="<?=($this->menu == 2) ? 'true' : 'false'; ?>">
                                <i class="fas fa-user"></i><span><?= $this->lang->line('students') ?></span>
                            </a>

                            <div class="dropdown-menu <?=($this->menu == 2) ? 'show' : ''; ?>"
                                aria-labelledby="navbarDropdown">
                                <a href="<?php echo DASHURL."/admin/student/add"; ?>"
                                    class="dropdown-item <?=($this->subMenu == 21) ? 'active' : ''; ?>">
                                    <?=$this->lang->line('addStudent') ?>
                                </a>
                                <a href="<?php echo DASHURL."/admin/student/list"; ?>"
                                    class="dropdown-item <?php echo ($this->subMenu == 22) ? 'active' : ''; ?>">
                                    <?=$this->lang->line('studentList') ?>
                                </a>
                            </div>
                        </li>

                        <!-- Teacher Management -->
                        <li class="nav-item dropdown <?=($this->menu == 3) ? 'show' : ''; ?>">
                            <a class="list-group-item list-group-item-action nav-link dropdown-toggle <?=($this->menu == 3) ? 'active' : ''; ?>"
                                href="#" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="<?=($this->menu == 3) ? 'true' : 'false'; ?>">
                                <i class="fas fa-user"></i><span>Teacher</span>
                            </a>

                            <div class="dropdown-menu <?=($this->menu == 3) ? 'show' : ''; ?>"
                                aria-labelledby="navbarDropdown">
                                <a href="<?php echo DASHURL."/admin/teacher/list"; ?>"
                                    class="dropdown-item <?php echo ($this->subMenu == 32) ? 'active' : ''; ?>">
                                    Teacher List
                                </a>
                            </div>
                        </li>

                        <!-- Category Management -->
                        <li class="nav-item dropdown <?=($this->menu == 4) ? 'show' : ''; ?>">
                            <a class="list-group-item list-group-item-action nav-link dropdown-toggle <?=($this->menu == 4) ? 'active' : ''; ?>"
                                href="#" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="<?=($this->menu == 4) ? 'true' : 'false'; ?>"><i
                                    class="fa fa-users"></i><span>Category</span></a>
                            <div class="dropdown-menu <?=($this->menu == 4) ? 'show' : ''; ?>"
                                aria-labelledby="navbarDropdown">
                                <a href="<?php echo DASHURL."/admin/category/add"; ?>"
                                    class="dropdown-item <?=($this->subMenu == 41) ? 'active' : ''; ?>"><?=$this->lang->line('addCategory') ?></a>
                                <a href="<?php echo DASHURL."/admin/category/list"; ?>"
                                    class="dropdown-item <?php echo ($this->subMenu == 42) ? 'active' : ''; ?>">
                                    <?=$this->lang->line('categoryList') ?> </a>
                            </div>
                        </li>

                        <!-- Courses List -->
                        <li class="nav-item dropdown <?=($this->menu == 5) ? 'show' : ''; ?>">
                            <a class="list-group-item list-group-item-action nav-link dropdown-toggle <?=($this->menu == 5) ? 'active' : ''; ?>"
                                href="#" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="<?=($this->menu == 5) ? 'true' : 'false'; ?>">
                                <i class="fas fa-user"></i><span>Courses List</span>
                            </a>

                            <div class="dropdown-menu <?=($this->menu == 5) ? 'show' : ''; ?>"
                                aria-labelledby="navbarDropdown">
                                <a href="<?php echo DASHURL."/admin/courses/add"; ?>"
                                    class="dropdown-item <?=($this->subMenu == 51) ? 'active' : ''; ?>">
                                    <?=$this->lang->line('addCourse') ?>
                                </a>
                                <a href="<?php echo DASHURL."/admin/courses/list"; ?>"
                                    class="dropdown-item <?php echo ($this->subMenu == 52) ? 'active' : ''; ?>">
                                    <?=$this->lang->line('courseList') ?>
                                </a>
                            </div>
                        </li>

                        <!-- Subscrition Management -->
                        <li class="nav-item dropdown <?=($this->menu == 6) ? 'show' : ''; ?>">
                            <a class="list-group-item list-group-item-action nav-link dropdown-toggle <?=($this->menu == 6) ? 'active' : ''; ?>"
                                href="#" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="<?=($this->menu == 5) ? 'true' : 'false'; ?>">
                                <i class="fas fa-user"></i><span>Subscriptions</span>
                            </a>

                            <div class="dropdown-menu <?=($this->menu == 6) ? 'show' : ''; ?>"
                                aria-labelledby="navbarDropdown">
                                <a href="<?php echo DASHURL."/admin/subscriptions/add"; ?>"
                                    class="dropdown-item <?=($this->subMenu == 61) ? 'active' : ''; ?>">
                                    <?=$this->lang->line('addSubscription') ?>
                                </a>
                                <a href="<?php echo DASHURL."/admin/subscriptions/list"; ?>"
                                    class="dropdown-item <?php echo ($this->subMenu == 62) ? 'active' : ''; ?>">
                                    <?=$this->lang->line('subscriptionList') ?>
                                </a>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="list-group-item list-group-item-action nav-link"
                                href="<?php echo DASHURL."/admin/staticpage/list";?>"><i class="fa fa-book"
                                    aria-hidden="true"></i><span><?=$this->lang->line('staticpageList') ?></span></a>
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