<!-- select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jquery validate-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<!--datatable responsive css-->
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">


<div class="row project-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <select class="select-single dashboardOption" name="provinsi" id="provinsi">
                                <option value="">Pilih Provinsi</option>
                                <?php foreach ($get_provinsi as $v) { ?>
                                    <option value="<?php echo $v['location_id']; ?>"><?php echo $v['province_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <select class="select-single dashboardOption" name="kabupaten" id="kabupaten" disabled>
                                <option value="">Pilih Kabupaten</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <select class="select-single dashboardOption" name="kode_lokasi_skd" id="kode_lokasi_skd" disabled>
                                <option value="">Pilih Lokasi</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <select class="select-single dashboardOption" name="jenis" id="jenis">
                                <option value="">Pilih Jenis</option>
                                <?php foreach ($get_jenis as $v) { ?>
                                    <option value="<?php echo $v['kelompok']; ?>"><?php echo $v['kelompok']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <select class="select-single dashboardOption" name="kelompok" id="kelompok" disabled>
                                <option value="">Pilih Kelompok</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <a href="javascript:;" class="btn btn-sm btn-danger" id="reset">Reset</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-primary text-primary rounded-2 fs-2">
                                <i data-feather="target" class="text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-3">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Target Perencanaan</p>
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value target_perencanaan">0</span></h4>
                            </div>
                            <p class="text-muted text-truncate mb-0">Item</p>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </div><!-- end col -->

        <div class="col-xl-3">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
                                <i data-feather="truck" class="text-warning"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-3">Terkirim</p>
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value terkirim" id="terkirim">0</span></h4>
                            </div>
                            <p class="text-muted mb-0">Item</p>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </div><!-- end col -->

        <div class="col-xl-3">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-success text-success rounded-2 fs-2">
                                <i data-feather="package" class="text-success"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-3">Diterima</p>
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value diterima">0</span></h4>
                            </div>
                            <p class="text-muted mb-0">Item</p>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </div><!-- end col -->

        <div class="col-xl-3">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                                <i data-feather="thumbs-up" class="text-info"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-3">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Ter-Install</p>
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value terinstall">0</span></h4>
                            </div>
                            <p class="text-muted text-truncate mb-0">Item</p>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </div><!-- end col -->
    </div><!-- end row -->

    <div class="row">
        <div class="col-xl-3">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-primary text-primary rounded-2 fs-2">
                                <i data-feather="globe" class="text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-3">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Titik Lokasi</p>
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value titik_lokasi">0</span></h4>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </div><!-- end col -->

        <div class="col-xl-3">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
                                <i data-feather="users" class="text-warning"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-3">Kordinator</p>
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value kordinator">0</span></h4>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </div><!-- end col -->

        <div class="col-xl-3">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-success text-success rounded-2 fs-2">
                                <i data-feather="user-check" class="text-success"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-3">Pengawas</p>
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value pengawas">0</span></h4>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </div><!-- end col -->

        <div class="col-xl-3">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                                <i data-feather="tool" class="text-info"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-3">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Teknisi</p>
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value teknisi">0</span></h4>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </div><!-- end col -->
    </div><!-- end row -->


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Charts Barang</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div id="column_chart" class="apex-charts" dir="ltr"></div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Data Barang</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3 p-3">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Provinsi</th>
                                    <th>Kabupaten</th>
                                    <th>Titik Lokasi</th>
                                    <th>Nama Barang</th>
                                    <th>Rencana</th>
                                    <th>Kirim</th>
                                    <th>Terima</th>
                                    <th>Install</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Data SDM</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3 p-3">
                        <table id="datatable_user" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Provinsi</th>
                                    <th>Kabupaten</th>
                                    <th>Titik Lokasi</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>No Telpon</th>
                                    <th>Posisi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div><!-- end row -->

<!-- apexcharts -->
<script src="<?php echo base_url(); ?>assets/libs/apexcharts/apexcharts.min.js"></script>

<!--select2 cdn-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- projects js -->
<script src="<?php echo base_url(); ?>assets/js/pages/dashboard-projects.init.js"></script>

<!--datatable js-->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>

<script>
    $(document).ready(function() {
        $(".select-single").select2();
        loadDatatableAjax();
        searchTable();
        resetSearch();

        var options_chart = {
            chart: {
                height: 350,
                type: "bar",
                toolbar: {
                    show: !1
                }
            },
            plotOptions: {
                bar: {
                    horizontal: !1,
                    columnWidth: "45%",
                    endingShape: "rounded"
                }
            },
            dataLabels: {
                enabled: !1
            },
            stroke: {
                show: !0,
                width: 2,
                colors: ["transparent"]
            },
            series: [{
                name: "Target Perencanaan",
                data: [0]
            }, {
                name: "Terkirim",
                data: [0]
            }, {
                name: "Diterima",
                data: [0]
            }, {
                name: "Ter-Install",
                data: [0]
            }],
            xaxis: {
                categories: ["Data"]
            },
            yaxis: {
                title: {
                    text: "Item"
                }
            },
            grid: {
                borderColor: "#f1f1f1"
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(t) {
                        return t + " item"
                    }
                }
            }
        }
        var chart = new ApexCharts(document.querySelector("#column_chart"), options_chart);
        chart.render();

        initDashboard();
        $("#provinsi").on("change", function() {
            let provinsi = $("#provinsi").val();
            $.ajax({
                url: "<?php echo site_url('dashboard/get_regency'); ?>",
                data: {
                    provinsi: provinsi
                },
                method: "POST",
                dataType: "json",
                success: function(data) {
                    kabupaten = '<option value="">Pilih Kabupaten</option>';
                    $.each(data, function(i, item) {
                        kabupaten += '<option value="' + item.location_id + '">' + item.regency_name + "</option>";
                    });
                    $("#kabupaten").html(kabupaten).removeAttr("disabled");
                },
            });
        });

        $("#kabupaten").on("change", function() {
            let kabupaten = $("#kabupaten").val();
            $.ajax({
                url: "<?php echo site_url('dashboard/get_lokasi'); ?>",
                data: {
                    kabupaten: kabupaten
                },
                method: "POST",
                dataType: "json",
                success: function(data) {
                    kode_lokasi_skd = '<option value="">Pilih Lokasi</option>';
                    $.each(data, function(i, item) {
                        kode_lokasi_skd += '<option value="' + item.id + '">' + item.kode_lokasi + ' | ' + item.nama_lokasi + "</option>";
                    });
                    $("#kode_lokasi_skd").html(kode_lokasi_skd).removeAttr("disabled");
                },
            });
        });

        $("#jenis").on("change", function() {
            let jenis = $("#jenis").val();
            $.ajax({
                url: "<?php echo site_url('dashboard/get_kelompok'); ?>",
                data: {
                    jenis: jenis
                },
                method: "POST",
                dataType: "json",
                success: function(data) {
                    kelompok = '<option value="">Pilih Kelompok</option>';
                    $.each(data, function(i, item) {
                        kelompok += '<option value="' + item.jenis_alat + '">' + item.jenis_alat + "</option>";
                    });
                    $("#kelompok").html(kelompok).removeAttr("disabled");
                },
            });
        });

        $('.dashboardOption').on('keyup change', function() {
            let provinsi = $('#provinsi').val();
            let kabupaten = $('#kabupaten').val();
            let kode_lokasi_skd = $('#kode_lokasi_skd').val();
            let jenis = $('#jenis').val();
            let kelompok = $('#kelompok').val();

            initDashboardChange(provinsi, kabupaten, kode_lokasi_skd, jenis, kelompok);

        });

        function initDashboard() {

            $.ajax({
                url: "<?php echo site_url('dashboard/get_data_dashboard'); ?>",
                type: 'POST',
                dataType: 'json',
                success: function(result) {
                    $('.target_perencanaan').html(result.total_perencanaan);
                    $('.terkirim').html(result.total_pengiriman);
                    $('.diterima').html(result.total_penerimaan);
                    $('.terinstall').html(result.total_terinstall);
                    $('.titik_lokasi').html(result.total_tilok);
                    $('.kordinator').html(result.total_kordinator);
                    $('.pengawas').html(result.total_pengawas);
                    $('.teknisi').html(result.total_teknisi);
                    const barData = [{
                        name: "Target Perencanaan",
                        data: [Number(result.total_perencanaan)]
                    }, {
                        name: "Terkirim",
                        data: [Number(result.total_pengiriman)]
                    }, {
                        name: "Diterima",
                        data: [Number(result.total_penerimaan)]
                    }, {
                        name: "Ter-Install",
                        data: [Number(result.total_terinstall)]
                    }];
                    chart.updateSeries(barData);

                    if (result.adm_pos_id > 2) {
                        kode_lokasi_skd = '<option value="">- Pilih -</option>';
                        $.each(result.lokasi_skd, function(key, val) {
                            var select = '';
                            if (val.id == result.lokasi_skd_id)
                                select = 'selected';
                            kode_lokasi_skd += '<option value="' + val.id + '" ' + select + '>' + val.nama_lokasi + '</option>';
                        });
                        $('#kode_lokasi_skd').html(kode_lokasi_skd);

                        $.each(result.lokasi_user, function(key, val) {
                            select = 'selected';
                            kabupaten = '<option value="' + val.regency_id + '" ' + select + '>' + val.regency_name + '</option>';
                            provinsi = '<option value="' + val.province_id + '" ' + select + '>' + val.province_name + '</option>';
                        });
                        $('#kabupaten').html(kabupaten);
                        $('#provinsi').html(provinsi).prop('disabled', true);
                    }
                }
            })
        }

        function initDashboardChange(provinsi, kabupaten, kode_lokasi_skd, jenis, kelompok) {

            $.ajax({
                url: "<?php echo site_url('dashboard/get_data_dashboard'); ?>",
                type: 'POST',
                data: {
                    provinsi: provinsi,
                    kabupaten: kabupaten,
                    kode_lokasi_skd: kode_lokasi_skd,
                    jenis: jenis,
                    kelompok: kelompok
                },
                dataType: 'json',
                success: function(result) {
                    $('.target_perencanaan').html(result.total_perencanaan);
                    $('.terkirim').html(result.total_pengiriman);
                    $('.diterima').html(result.total_penerimaan);
                    $('.terinstall').html(result.total_terinstall);
                    $('.titik_lokasi').html(result.total_tilok);
                    $('.kordinator').html(result.total_kordinator);
                    $('.pengawas').html(result.total_pengawas);
                    $('.teknisi').html(result.total_teknisi);
                    const barData = [{
                        name: "Target Perencanaan",
                        data: [Number(result.total_perencanaan)]
                    }, {
                        name: "Terkirim",
                        data: [Number(result.total_pengiriman)]
                    }, {
                        name: "Diterima",
                        data: [Number(result.total_penerimaan)]
                    }, {
                        name: "Ter-Install",
                        data: [Number(result.total_terinstall)]
                    }];
                    chart.updateSeries(barData);
                }
            })
        }

    })

    function loadDatatableAjax() {
        table = $('#datatable').DataTable({

            language: {
                search: "Cari",
                lengthMenu: "Tampilkan _MENU_ baris per halaman",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _START_  dari _END_ ",
                infoEmpty: "Tidak ada data yang ditampilkan ",
                infoFiltered: "(pencarian dari _MAX_ total records)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelum",
                },
            },
            processing: true,
            serverSide: true,
            bDestroy: true,
            responsive: false,
            orderCellsTop: true,
            searchDelay: 3500,
            ajax: {
                'url': '<?php echo site_url('dashboard/get_barang_dashboard'); ?>',
                type: "POST",
                data: {},
            },
            fixedHeader: {
                header: true,
                footer: true
            },
            scrollCollapse: true,
            scrollX: true,
            scrollY: 500,
            order: [
                [1, 'asc']
            ],

        });

        table2 = $('#datatable_user').DataTable({

            language: {
                search: "Cari",
                lengthMenu: "Tampilkan _MENU_ baris per halaman",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _START_  dari _END_ ",
                infoEmpty: "Tidak ada data yang ditampilkan ",
                infoFiltered: "(pencarian dari _MAX_ total records)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelum",
                },
            },
            processing: true,
            serverSide: true,
            bDestroy: true,
            responsive: false,
            orderCellsTop: true,
            searchDelay: 3500,
            ajax: {
                'url': '<?php echo site_url('dashboard/get_sdm_dashboard'); ?>',
                type: "POST",
                data: {},
            },
            fixedHeader: {
                header: true,
                footer: true
            },
            scrollCollapse: true,
            scrollX: true,
            scrollY: 500,
            order: [
                [1, 'asc']
            ],

        });
    }

    function searchTable() {
        $('.dashboardOption').on('keyup change', function() {
            var filter = [{
                provinsi: $("#provinsi").val(),
                kabupaten: $("#kabupaten").val(),
                kode_lokasi_skd: $("#kode_lokasi_skd").val(),
                jenis: $("#jenis").val(),
                kelompok: $("#kelompok").val(),
            }, ];

            table.column(0).search(JSON.stringify(filter), true, true);
            table.draw();


            table2.column(0).search(JSON.stringify(filter), true, true);
            table2.draw();
        });
    }

    function resetSearch() {
        $("#reset").on("click", function() {
            $("#provinsi").val("").trigger("change");
            $("#kabupaten").val("").trigger("change");
            $("#kode_lokasi_skd").val("").trigger("change");
            $("#jenis").val("").trigger("change");
            $("#kelompok").val("").trigger("change");

            table.search("").columns().search("").draw();
            table2.search("").columns().search("").draw();
        });
    }
</script>