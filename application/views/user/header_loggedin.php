<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?php echo $page_title; ?></title>
    <!-- Favicon-->
    <link rel="shortcut icon" href="data:image/png;base64,<?php echo $page_favicon; ?>"/>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url();?>public/new/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?php echo base_url();?>public/new/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?php echo base_url();?>public/new/plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?php echo base_url();?>public/new/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url();?>public/new/css/custom.css" rel="stylesheet">
    <link href="<?php echo base_url();?>public/new/css/circle.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.4.4/jquery.contextMenu.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?php echo base_url();?>public/new/css/themes/theme-red.css" rel="stylesheet" />
</head>

<body class="theme-red">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <a href="javascript:void(0);" class="bars"></a><br/>

  </br/>
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar" style="top:0; height: 100%;">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $this->authentication->firstname.' '.$this->authentication->lastname; ?></div>
                    <div class="email"><?php echo $this->authentication->email; ?></div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="<?php echo site_url('user/account'); ?>"><i class="material-icons">person</i>Profile</a></li>
                            <li role="seperator" class="divider"></li>
                            <li><a href="<?php echo site_url('user/logout'); ?>"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                  <li class="active">
                      <a href="<?php echo site_url('/');?>">
                          <i class="material-icons">storage</i>
                          <span>Dashboard</span>
                      </a>
                  </li>
                  <li>
                      <a href="<?php echo site_url('/trashcan');?>">
                          <i class="material-icons">delete</i>
                          <span>Trashcan</span>
                      </a>
                  </li>
                  <li>
                      <a href="<?php echo site_url('/shared');?>">
                          <i class="material-icons">folder_shared</i>
                          <span>Shared Files</span>
                      </a>
                  </li>
                  <li>
                      <a href="<?php echo site_url('marked');?>">
                          <i class="material-icons">star</i>
                          <span>Marked</span>
                      </a>
                  </li>
                    <?php
                    if($this->authentication->premium == 0 && $premium_enabled == true){
                      ?>
                      <li class="active">
                          <a href="<?php echo site_url('premium/buy');?>">
                              <i class="material-icons">info</i>
                              <span>Become a Premium User</span>
                          </a>
                      </li>
                      <?php
                    }
                    ?>

                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <style>
            .progress .progress-bar{
              background-color: #F44336;
            }
            .context-menu-item:hover{
              background-color: gray;
            }
            </style>
            <div class="legal">
                <div class="copyright">
                  <p style="margin-bottom: 1px;">
                    Storage Capacity
                    <span class="small_statistics">(<?php echo round($usage_statistics['usage']['total_filesize']/1024/1024,2); ?>Mb/
                    <?php if(is_numeric($usage_statistics['max']['total_filesize'])){
                    echo round($usage_statistics['max']['total_filesize']/1024/1024).'Mb';
                  }else{ echo $usage_statistics['max']['total_filesize']; } ?>
                    )</span>

                  </p>
                  <div class="progress" style="height: 5px; margin-bottom: 10px;">
                      <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo round($usage_statistics['usage']['percent']['total_filesize'], 0); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo round($usage_statistics['usage']['percent']['total_filesize'], 0); ?>%;">
                          <?php echo round($usage_statistics['usage']['percent']['total_filesize'], 0); ?>%
                      </div>
                  </div>
                  <p style="margin-bottom: 1px;">
                    Max Files <span class="small_statistics">(<?php echo $usage_statistics['usage']['filecount'] ?>/<?php echo $usage_statistics['max']['filecount'] ?>)</span>
                  </p>
                  <div class="progress" style="height: 5px; margin-bottom: 10px;">
                      <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo round($usage_statistics['usage']['percent']['filecount'], 0); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo round($usage_statistics['usage']['percent']['filecount'], 0); ?>%;">
                          <?php echo round($usage_statistics['usage']['percent']['filecount'], 0); ?>%
                      </div>
                  </div>
                  <p style="margin-bottom: 1px;">
                    Max Folders <span class="small_statistics">(<?php echo $usage_statistics['usage']['foldercount'] ?>/<?php echo $usage_statistics['max']['foldercount'] ?>)</span>
                  </p>
                  <div class="progress" style="height: 5px; margin-bottom: 10px;">
                      <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo round($usage_statistics['usage']['percent']['foldercount'], 0); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo round($usage_statistics['usage']['percent']['foldercount'], 0); ?>%;">
                          <?php echo round($usage_statistics['usage']['percent']['foldercount'], 0); ?>%
                      </div>
                  </div>
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
    </section>
<div class="info_messages">
  <?php
  if(!empty($error_message)){
    foreach($error_message as $message){
      ?>
      <p class="alert alert-danger"><?php echo $message; ?></p>
      <?php
    }
  }
  if(!empty($success_message)){
    foreach($success_message as $message){
      ?>
      <p class="alert alert-success"><?php echo $message; ?></p>
      <?php
    }
  }
   ?>
</div>
<div style="margin: 0px 0px 0 315px; padding-right: 30px;">
  <?php if($this->authentication->is_staff() == true || $this->authentication->is_admin() == true){
    ?>
    <a href="<?php echo site_url('admin/dashboard'); ?>" class="btn btn-warning pull-right">Back to Admin CP</a>
    <br />
    <?php
  }
  ?>
</div>
