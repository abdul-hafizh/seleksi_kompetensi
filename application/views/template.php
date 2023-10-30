<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-layout-mode="light" data-layout-width="fluid" data-layout-position="fixed" data-layout-style="detached">
<head>
    <title><?php echo $judul ?></title>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Admin Template" name="description" />
    <meta content="Themesbrand" name="author" />
    
    <!-- App favicon -->
    <!-- <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/logo/favicon.png"> -->

    <!-- Layout config Js -->
    <script src="<?php echo base_url();?>assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="<?php echo base_url();?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?php echo base_url();?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?php echo base_url();?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="<?php echo base_url();?>assets/css/custom.css" rel="stylesheet" type="text/css" />
    <!-- jquery cdn-->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

    <style>
      .show-topnav {
        display: none !important;
      }

      @media screen and (max-width: 768px) {
          .show-topnav {
            display: block !important;
          }
      }
    </style>

</head>

<body>

  <!-- Begin page -->
  <div id="layout-wrapper">    

    <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger show-topnav" id="topnav-hamburger-icon">
        <span class="hamburger-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </button>
    
    <?php include("sidebar_v.php") ?>

    <?php include("content_v.php") ?>

  </div>
  <!-- END layout-wrapper -->

  <!--start back-to-top-->
  <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
      <i class="ri-arrow-up-line"></i>
  </button>
  <!--end back-to-top-->

  <!-- JAVASCRIPT -->
  <script src="<?php echo base_url();?>assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo base_url();?>assets/libs/simplebar/simplebar.min.js"></script>
  <script src="<?php echo base_url();?>assets/libs/node-waves/waves.min.js"></script>
  <script src="<?php echo base_url();?>assets/libs/feather-icons/feather.min.js"></script>
  <script src="<?php echo base_url();?>assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
  <script src="<?php echo base_url();?>assets/js/plugins.js"></script>

  <!-- App js -->
  <script src="<?php echo base_url();?>assets/js/app.js"></script>

</body>
</html>
