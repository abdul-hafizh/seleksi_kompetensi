    <!-- ========== App Menu ========== -->
    <div class="app-menu navbar-menu">
        <!-- LOGO -->
        <div class="navbar-brand-box">
            <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                <i class="ri-record-circle-line"></i>
            </button>
        </div>

        <div id="scrollbar">
            <div class="container-fluid">

                <div id="two-column-menu"></div>
                    
                <div class="text-center img-side">
                    <img class="mt-2" src="<?php echo base_url()?>assets/images/logo-bkn.png" alt="Header Avatar" height="50"> <br/>
                    <img class="rounded-circle" src="<?php echo base_url()?>assets/images/users/user-dummy-img.jpg" alt="Header Avatar" height="120">
                </div>
                
                <div class="row">
                    <span class="text-center border mb-3">
                        <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text"><?php echo $userdata['complete_name'] ?></span>
                        <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text"><?php echo $userdata['pos_name'] ?></span>
                    </span>
                </div>


                <ul class="navbar-nav" id="navbar-nav">	                
                    <?php include("menu_v.php"); ?>    
                </ul>

            </div>
            <!-- Sidebar -->
        </div>

        <div class="sidebar-background"></div>
    </div>
    <!-- Left Sidebar End -->
    <!-- Vertical Overlay-->
    <div class="vertical-overlay"></div>