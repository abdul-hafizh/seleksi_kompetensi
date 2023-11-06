<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<!-- select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!--datatable responsive css-->
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

<?php
    $pesan = $this->session->userdata('message');
    $pesan = (empty($pesan)) ? "" : $pesan;
    if(!empty($pesan)){ ?>
    <div class="alert bg-light-danger alert-dismissible">
        <?php echo $pesan ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ft-x font-medium-2 text-bold-700"></i></span>
        </button>
    </div>
<?php } $this->session->unset_userdata('message'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Filter Data</h4>
            </div>
            <div class="card-body">
                <div class="row gy-4">
                    <div class="col-xxl-3 col-md-6">
                        <select class="select-single" name="titik_lokasi" id="titik_lokasi">
                            <option value="">Pilih Titik Lokasi</option>
                            <?php foreach($get_tilok as $v) { ?>
                                <option value="<?php echo $v['id']; ?>"><?php echo $v['kode_lokasi'] . ' | ' . $v['nama_lokasi']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-xxl-2 col-md-6">
                        <div class="btn-group">
                            <button type="button" class="btn btn-block btn-primary btn-sm" id="dt_cari" name="button" title="Cari Data">Cari Data</button>
                            <button type="button" class="btn btn-block btn-warning btn-sm" id="dt_reset" name="button" title="Reset">Reset</button>
                        </div>
                    </div>
                </div>                
            </div>                
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom pb-2">
                <div class="float-start">
                    <h5 class="card-title mb-0">List SDM</h5>
                </div>
                <div class="float-end">
                    <div class="btn-group">
                        <a href="<?php echo site_url('manajemen_user/users/add_access');?>" class="btn btn-info btn-sm"><i class="ri-add-line align-middle me-1"></i> Tambah SDM Access</a>
                        <a href="<?php echo site_url('manajemen_user/users/add');?>" class="btn btn-primary btn-sm"><i class="ri-add-line align-middle me-1"></i> Tambah SDM</a>
                    </div>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <table id="data-form" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Posisi</th>
                                <th>Provinsi</th>
                                <th>Kabupaten</th>
                                <th>Titik Lokasi</th>
                                <th>KTP</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!--datatable js-->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<!--select2 cdn-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>    
    $(document).ready(function () {
        $(".select-single").select2();

        $('#dt_cari').click(function() {
            table.ajax.reload();
        });

        $('#dt_reset').click(function() {
            location.reload();
        });

        var table = $("#data-form").DataTable({             
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url':'<?php echo site_url('manajemen_user/users/get_data');?>',
                "type": "POST",
                "data": function(d){                    
                    d.s_titik_lokasi = $('#titik_lokasi').val();
                },
            },
            scrollX: !0,
            'columns': [
                { data: 'fullname' },
                { data: 'email' },
                { data: 'phone' },
                { data: 'pos_name' },
                { data: 'province_name' },
                { data: 'regency_name' },
                { data: 'nama_lokasi' },
                { data: 'file_ktp' },
                { data: 'status' },
                { data: 'action' },
            ]            
        });
    })
</script>
