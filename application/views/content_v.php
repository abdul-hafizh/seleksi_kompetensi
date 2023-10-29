    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h3 class="mb-sm-0"><?php echo $mytitle; ?></h3>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">     
                                            <div class="hstack gap-2">
                                                <a class="btn btn-sm btn-info" href="#">Profile</a>
                                                <a class="btn btn-sm btn-outline-info" href="<?php echo site_url('log/logout') ?>" onclick="return confirm('Apakah Anda yakin ingin keluar dari aplikasi?');">Log Out</a>
                                            </div>                            
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <?php
                    $message = $this->session->userdata("message");
                    $validate = validation_errors();

                    if(!empty($message)){ 
                ?>

                    <div class="alert alert-primary alert-dismissible alert-label-icon rounded-label fade show" role="alert">
                        <i class="ri-error-warning-line label-icon"></i><strong> <?php echo $message ?> </strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                <?php } $this->session->unset_userdata("message");

                    if(!empty($validate)){ 
                        
                ?>

                    <div class="alert alert-danger alert-dismissible alert-label-icon rounded-label fade show" role="alert">
                        <i class="ri-error-warning-line label-icon"></i><strong> <?php echo $validate ?> </strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                <?php } ?>

                <?php include($body.".php"); ?>

            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>document.write(new Date().getFullYear())</script> ©
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-end d-none d-sm-block">
                            Sistem Seleksi Kompetensi Dasar
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <!-- end main content-->