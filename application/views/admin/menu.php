<div class="container body">
  <div class="main_container">
    <div class="col-md-3 left_col">
      <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
          <a href="<?php echo site_url('admin/dashboard'); ?>" class="site_title"><i class="fa fa-paw"></i> <span>Administration</span></a>
        </div>

        <div class="clearfix"></div>
        <!-- /menu profile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
          <div class="menu_section">
            <h3>General</h3>
            <ul class="nav side-menu">
              <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="fa fa-home"></i> Dashboard</a>
              </li>
              <li><a href="<?php echo site_url('admin/user'); ?>"><i class="fa fa-user"></i> User Management</a>
              </li>
              <li><a href="<?php echo site_url('admin/files'); ?>"><i class="fa fa-file"></i> File Browser</a>
              </li>
            </ul>
            <br/>
            <h3>Administrator</h3>
            <ul class="nav side-menu">
              <li><a href="<?php echo site_url('admin/settings/general'); ?>"><i class="fa fa-gear"></i> General Settings</a>
              </li>
              <li><a href="<?php echo site_url('admin/storage'); ?>"><i class="fa fa-hdd-o"></i> Storage Engines</a>
              </li>
              <li><a href="<?php echo site_url('admin/fileupload'); ?>"><i class="fa fa-file"></i> Upload Limitations</a>
              </li>
              <li><a href="<?php echo site_url('admin/user/settings'); ?>"><i class="fa fa-gear"></i>Upload Limits</a></li>
              <li><a href="<?php echo site_url('admin/payment'); ?>"><i class="fa fa-money"></i>Payment Settings</a></li>
              <li><a href="<?php echo site_url('admin/premium'); ?>"><i class="fa fa-star"></i>Premium Features</a></li>

              <li><a><i class="fa fa-envelope"></i> Communication <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('admin/settings/emailSettings'); ?>"><i class="fa fa-gear"></i>Server Settings</a></li>
                      <li><a href="<?php echo site_url('admin/settings/emailTemplates'); ?>"><i class="fa fa-envelope"></i>Message Templates</a></li>
                    </ul>
              </li>
            </ul>
          </div>

        </div>
        <!-- /sidebar menu -->

      </div>
    </div>

    <!-- top navigation -->
    <div class="top_nav">
      <div class="nav_menu">
        <nav class="" role="navigation">
          <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
          </div>

          <ul class="nav navbar-nav navbar-right">
            <li class="">
              <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                My Account
                <span class=" fa fa-angle-down"></span>
              </a>
              <ul class="dropdown-menu dropdown-usermenu pull-right">
                <li><a href="<?php echo site_url('user/logout'); ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
              </ul>
            </li>
            <li class="">
              <a href="<?php echo site_url('dashboard'); ?>">
                Switch to User Panel
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
    <!-- /top navigation -->
