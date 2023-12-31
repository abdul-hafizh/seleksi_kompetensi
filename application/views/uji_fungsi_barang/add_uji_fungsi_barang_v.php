<!-- select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jquery validate-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

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

<form action="<?php echo site_url('uji_fungsi_barang/submit_data'); ?>" method="post" id="basic-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tambah Data Penerimaan</h5>
                </div>
                <div class="card-body">                    
                    
                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Titik Lokasi</label>
                        <div class="col-md-8">                            
                            <input type="text" class="form-control" name="nama_lokasi" value="<?php echo $get_penerimaan['nama_lokasi'] . ' (' . $get_penerimaan['alamat'] . ', ' . $get_penerimaan['province_name'] . ', ' . $get_penerimaan['regency_name'] . ')'; ?>" readonly>
                            <input type="hidden" name="penerimaan_id" id="penerimaan_id" value="<?php echo $get_penerimaan['id']; ?>">
                            <input type="hidden" name="lokasi_skd_id" id="lokasi_skd_id" value="<?php echo $get_penerimaan['lokasi_skd_detail']; ?>">
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Tanggal Kegiatan</label>
                        <div class="col-md-8">
                            <input type="date" class="form-control" id="jadwal_kegiatan" name="jadwal_kegiatan" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Catatan</label>
                        </div>
                        <div class="col-lg-8">
                            <textarea class="form-control" name="catatan_uji" rows="3" placeholder="Catatan"></textarea>
                        </div>
                    </div>                    
                </div>
            </div>
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
                        <table id="data-form" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Satuan</th>
                                    <th>Jumlah Terima</th>
                                    <th>Jumlah Rusak</th>
                                    <th>Jumlah Terpasang</th>
                                    <th>Status Baik</th>
                                    <th>Status Tidak Baik</th>
                                    <th>Catatan</th>
                                    <th>Foto Terima</th>
                                    <th style="display:none">Barang ID </th>
                                </tr>
                            </thead>
                            <tbody id="show-barang"></tbody>                            
                        </table>                    
                    </div>                      
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin?');">Konfirmasi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!--select2 cdn-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>    
    $(document).ready(function () {        
        $(".select-single").select2();

        $("#basic-form").validate({
            invalidHandler: function(event, validator) {            
                var errors = validator.numberOfInvalids();
                if (errors) { window.scrollTo({top: 0}); }
            }
        });        

        $("#jadwal_kegiatan").on("change", function () {
            let penerimaan_id = $("#penerimaan_id").val();
            var url_file = '<?php echo base_url("uploads/perencanaan/"); ?>';
            $.ajax({
                url: '<?php echo site_url("uji_fungsi_barang/get_barang"); ?>',
                data: { penerimaan_id: penerimaan_id },
                method: "POST",
                dataType: "json",
                success: function (data) {
                    var rows = '';

                    $.each(data, function (i, item) {
                        console.log(item);
                        rows += '<tr>';
                        rows += '<td>' + (i + 1) + '</td>';
                        rows += '<td>' + item.kode_barang_id + '</td>';
                        rows += '<td>' + item.nama_barang + '</td>';
                        rows += '<td>' + item.satuan + '</td>';
                        rows += '<td>' + item.jumlah_terima + '</td>';
                        rows += '<td>' + item.jumlah_rusak + '</td>';
                        rows += '<td>' + item.jumlah_terpasang + '</td>';
                        rows += '<td><input id="status_baik" name="status_baik[]" type="number" min="0" class="form-control" placeholder="Jumlah Status Baik" value="' + item.jumlah_terima + '" required></td>';
                        rows += '<td><input id="status_tidak" name="status_tidak[]" type="number" min="0" class="form-control" placeholder="Jumlah Status Tidak Baik" value="0" required></td>';
                        rows += '<td><input id="catatan" name="catatan[]" type="text" class="form-control" placeholder="Catatan"></td>';
                        rows += '<td>';
                        rows += '<div class="avatar-group">';
                        if (item.foto_barang) {
                            rows += '<a href="<?php echo base_url("uploads/penerimaan_barang/"); ?>' + item.foto_barang + '" target="_blank" class="avatar-group-item" data-img="<?php echo base_url("uploads/penerimaan_barang/"); ?>' + item.foto_barang + '" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Foto Barang">';
                            rows += '<img src="<?php echo base_url("uploads/penerimaan_barang/"); ?>' + item.foto_barang + '" alt="" class="rounded-circle avatar-xxs">';
                        } else {
                            rows += '<a href="<?php echo base_url('assets/images/noimage.jpeg')?>" target="_blank" class="avatar-group-item" data-img="<?php echo base_url('assets/images/noimage.jpeg')?>" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Foto Barang">';
                            rows += '<img src="<?php echo base_url('assets/images/noimage.jpeg')?>" alt="" class="rounded-circle avatar-xxs">';
                        }
                        rows += '</a>';
                        rows += '</div>';
                        rows += '</td>';
                        rows += '<td style="display:none"><input id="barang_id" name="barang_id[]" type="number" value="' + item.barang_id + '"></td>';
                        rows += '</tr>';
                    });

                    $('#show-barang').html(rows);
                },
                error: function (xhr, status, error) {
                    alert('Gagal ambil data barang.');
                },
            });
        });
    })
</script>