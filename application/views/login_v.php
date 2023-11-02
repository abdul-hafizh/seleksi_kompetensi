<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="sm-hover" data-sidebar-image="none">

<head>

    <meta charset="utf-8" />
    <title>Sign In</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <!-- App favicon -->
    <!-- <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/logo/favicon.png"> -->
    <!-- Layout config Js -->
    <script src="<?php echo base_url(); ?>assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?php echo base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="<?php echo base_url(); ?>assets/css/custom.min.css" rel="stylesheet" type="text/css" />
    <!-- Login Css-->
    <link href="<?php echo base_url(); ?>assets/css/login.css" rel="stylesheet" type="text/css" />

</head>

<body>

    <div class="auth-page-wrapper pt-5">
        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-12">
                        <div class="card mt-4 shadow-lg">

                            <div class="container card-body p-4">

                                <div class="row">
                                    <div class="col-4 h-100 justify-content-center align-items-center">
                                        <div class="text-center mt-4">
                                            <div class="container">
                                                <div class="row row-cols-auto justify-content-center">
                                                    <div class="col"><img class="mb-3" src="<?php echo base_url(); ?>assets/images/logo-bkn.png" alt="Logo" height="45"></div>
                                                    <div class="col"><img class="mb-3" src="<?php echo base_url(); ?>assets/images/logo-sucofindo.png" alt="Logo" height="45"></div>
                                                </div>
                                            </div>
                                            <div class="row mt-3 p-4">
                                                <div class="col-auto"><img class="mb-3" src="<?php echo base_url(); ?>assets/images/login-vertical-divider.png" alt="Logo" height="76"></div>
                                                <div class="col" style="text-align: left;">
                                                    <span style="font-size: 36px; font-weight: 700; line-height: 41px;">Sistem Seleksi <span style="color: #26CCD4">CASN</span></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-2 mt-3">
                                            <form action="<?php echo site_url("log/in") ?>" method="post">
                                                <?php
                                                $pesan = $this->session->userdata('message');
                                                $pesan = (empty($pesan)) ? "" : $pesan;
                                                if (!empty($pesan)) { ?>
                                                    <div class="alert alert-info alert-border-left alert-dismissible fade show mb-3" role="alert">
                                                        <i class="ri-error-warning-line me-3 align-middle fs-16"></i><strong>Info</strong>
                                                        - <?php echo $pesan ?>
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    </div>
                                                <?php }
                                                $this->session->unset_userdata('message'); ?>
                                                <div class="mb-3">
                                                    <label for="username" class="form-label">Nama Pengguna</label>
                                                    <input type="text" class="form-control" name="username_login" placeholder="Masukkan nama pengguna" required>
                                                </div>

                                                <div class="mb-5">
                                                    <div class="float-end"></div>
                                                    <label class="form-label" for="password-input">Kata Sandi</label>
                                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                                        <input type="password" class="form-control pe-5" placeholder="Masukkan kata sandi" name="password_login" id="password-input" required>
                                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted" type="button" id="password-addon">
                                                            <i class="ri-eye-fill align-middle"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="mt-4">
                                                    <input class="btn btn-primary w-100" style="background-color: #26CCD4; border-radius: 0.5rem; border: none;" type="submit" value="Masuk">
                                                </div>

                                                <div class="mt-5 text-center">
                                                    <span class="text-muted fs-10 mb-4 title">Copyright by SSIMA</span><br />
                                                    <span class="text-muted fs-10 mb-4 title">Version 1.0</span>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <img src="<?php echo base_url(); ?>assets/images/login-side-photo.png" alt="Login image" class="w-100" style="object-fit: cover; object-position: left;">
                                    </div>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

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
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> Crafted with <i class="mdi mdi-heart text-danger"></i>
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
    <script src="<?php echo base_url(); ?>assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#password-addon").click(function() {
                var passwordInput = $("#password-input");
                var passwordAddon = $("#password-addon");

                if (passwordInput.attr("type") === "password") {
                    passwordInput.attr("type", "text");
                    passwordAddon.html('<i class="ri-eye-off-fill align-middle"></i>');
                } else {
                    passwordInput.attr("type", "password");
                    passwordAddon.html('<i class="ri-eye-fill align-middle"></i>');
                }
            });
        });
    </script>

</body>

</html>