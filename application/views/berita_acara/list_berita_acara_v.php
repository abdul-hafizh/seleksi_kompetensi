<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
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
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom pb-2">
                <div class="float-start">
                    <h5 class="card-title mb-0">Data Berita Acara</h5>
                </div>
                <?php if($job_title == 'KOORDINATOR') { ?>
                    <div class="float-end">
                        <a href="<?php echo site_url('berita_acara/add');?>" class="btn btn-primary btn-sm"><i class="ri-add-line align-middle me-1"></i> Tambah Berita Acara</a>
                    </div>
                <?php } ?>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <table id="data-form" class="table table-bordered dt-responsive table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nama Provinsi</th>
                                <th>Nama Kabupaten</th>
                                <th>Nama Lokasi</th>
                                <th>Judul Berita</th>
                                <th>Jenis</th>
                                <th>Tanggal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>                            
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

<script>    
    $(document).ready(function () {
        var table = $("#data-form").DataTable({             
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url':'<?php echo site_url('berita_acara/get_data');?>',
                "type": "POST",
                "data": function(d){                    
                    
                },
            },
            scrollX: !0,
            'columns': [
                { data: 'province_name' }, 
                { data: 'regency_name' }, 
                { data: 'nama_lokasi' }, 
                { data: 'judul_berita' }, 
                { data: 'jenis_berita_acara' },
                { data: 'tgl_kegiatan' },
                { data: 'action' },
            ]            
        });
    })
</script>
