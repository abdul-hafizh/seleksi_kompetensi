<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="sm-hover" data-sidebar-image="none">

<head>

    <meta charset="utf-8" />
    <title>Sign In</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
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
    <link href="<?php echo base_url();?>assets/css/custom.min.css" rel="stylesheet" type="text/css" />

</head>

<body>

    <div class="auth-page-wrapper pt-5">
        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <div>
                                <a href="#" class="d-inline-block auth-logo"></a>
                            </div>
                            <p class="mt-3 fs-15 fw-medium"></p>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4 shadow-lg">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <!-- <img class="mb-3" src="<?php echo base_url();?>assets/images/logo/logo.png" alt="Logo" height="45"> -->
                                    <h5 class="text-muted">LOGIN</h5>
                                    <p class="text-muted">Sistem Seleksi Kompetensi Dasar<hr/>
                                </div>
                                <div class="p-2 mt-4">
                                    <form action="<?php echo site_url("log/in") ?>" method="post">
                                        <?php
                                            $pesan = $this->session->userdata('message');
                                            $pesan = (empty($pesan)) ? "" : $pesan;
                                            if(!empty($pesan)){ ?>
                                                <div class="alert alert-info alert-border-left alert-dismissible fade show mb-3" role="alert">
                                                    <i class="ri-error-warning-line me-3 align-middle fs-16"></i><strong>Info</strong>
                                                    - <?php echo $pesan ?>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>
                                        <?php } $this->session->unset_userdata('message'); ?>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" name="username_login" placeholder="Enter username" required>
                                        </div>

                                        <div class="mb-5">
                                            <div class="float-end">
                                            </div>
                                            <label class="form-label" for="password-input">Password</label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input type="password" class="form-control pe-5" placeholder="Enter password" name="password_login" required>
                                                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <input class="btn btn-primary w-100" type="submit" value="Sign In">
                                        </div>

                                        <div class="mt-4 text-center">
                                            <div class="signin-other-title">
                                                <h5 class="fs-13 mb-4 title">ver 1.0</h5>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                        
                        <div class="mt-4 text-center">
                            <p class="mb-0">Tidak memiliki akun ? <a href="#" class="fw-semibold text-primary text-decoration-underline" data-bs-toggle="modal" data-bs-target="#daftarModal"> Daftar </a> </p>
                        </div>

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer start-0">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy;
                                <script>document.write(new Date().getFullYear())</script> Crafted with <i class="mdi mdi-heart text-danger"></i>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->
    
    <!-- jquery cdn-->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>    
        
    <!-- JAVASCRIPT -->
    <script src="<?php echo base_url();?>assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>