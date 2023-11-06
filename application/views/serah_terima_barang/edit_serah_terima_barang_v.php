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

<form action="<?php echo site_url('serah_terima_barang/submit_update'); ?>" method="post" id="basic-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Serah Terima Barang</h5>
                </div>
                <div class="card-body">                                        
                    <div class="form-group row mb-3">
                        <label class="col-md-2 label-control">Titik Lokasi</label>
                        <div class="col-md-8">
                            <select class="select-single" name="lokasi_skd_id" id="lokasi_skd_id" required>
                                <option value="">Pilih Titik Lokasi</option>
                                <?php foreach($get_lokasi as $v) { ?>
                                    <option value="<?php echo $v['id']; ?>" <?php echo $get_row['lokasi_skd_id'] == $v['id'] ? 'selected' : ''; ?>><?php echo $v['province_name'] . ' | ' . $v['regency_name'] . ' | ' . $v['nama_lokasi'] . ' (' . $v['kode_lokasi'] . ')'; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Nama Penerima</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="text" class="form-control" name="nama_penerima" placeholder="Nama Penerima" value="<?php echo $get_row['nama_penerima']; ?>" required>
                            <input type="hidden" name="id_dismantle" value="<?php echo $get_row['id']; ?>" readonly>
                        </div>
                    </div>     

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Nama Penyedia</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="text" class="form-control" name="nama_penyedia" placeholder="Nama Penyedia" value="<?php echo $get_row['nama_penyedia']; ?>" required>
                        </div>
                    </div>     
                    
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Tanggal Kegiatan</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="date" class="form-control" name="tgl_kegiatan" value="<?php echo $get_row['tgl_kegiatan']; ?>" required>
                        </div>
                    </div>     

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Jabatan</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="text" class="form-control" name="jabatan" placeholder="Jabatan" value="<?php echo $get_row['jabatan']; ?>" required>
                        </div>
                    </div>     

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">NIP</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="number" class="form-control" min="0" name="nip" placeholder="NIP" value="<?php echo $get_row['nip']; ?>" required>
                        </div>
                    </div>     

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Alamat Kegiatan</label>
                        </div> 
                        <div class="col-lg-9">
                            <textarea class="form-control" name="alamat_kegiatan" rows="3" placeholder="Alamat Kegiatan"><?php echo $get_row['alamat_kegiatan']; ?></textarea>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Catatan</label>
                        </div>
                        <div class="col-lg-8">
                            <textarea class="form-control" name="catatan" rows="3" placeholder="Catatan"><?php echo $get_row['catatan']; ?></textarea>
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
                    <h5 class="card-title mb-0">Foto/Dokumentasi</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-2 col-sm-12">
                            <button type="button" class="btn btn-sm btn-info" id="tambah-foto"><i class="las la-plus-square"></i> Tambah Foto</button>
                        </div>
                    </div>
                    <?php $no=1; foreach($get_foto as $v) { ?>                        
                        <div class="row mb-3">
                            <div class="col-md-2 col-sm-12">
                                <h6>Foto ke - <?php echo $no; ?></h6>
                                <div class="d-flex align-items-center">
                                    <div class="d-inline-flex gap-2 border border-dashed p-2 mb-2 w-75">
                                        <a href="<?php echo base_url('uploads/serah_terima_barang/' . $v['foto_kegiatan']);?>" class="bg-light rounded p-1" target="_blank">
                                            <img src="<?php echo base_url('uploads/serah_terima_barang/' . $v['foto_kegiatan']);?>" alt="" class="img-fluid d-block" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="p-3">
                                    <label class="form-label">Ubah Foto ke - <?php echo $no; ?></label>
                                    <input id="foto_kegiatan" name="foto_kegiatan[]" type="file" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="p-3">
                                    <label class="form-label">Keterangan Foto ke - <?php echo $no; ?></label>
                                    <input id="keterangan" name="keterangan[]" type="text" class="form-control" placeholder="Keterangan Foto ke - <?php echo $no; ?>" value="<?php echo $v['keterangan']; ?>">
                                </div>
                            </div>
                        </div>
                    <?php $no++; } ?>

                    <div class="row" id="form-foto"></div>
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

        $("#tambah-foto").click(function() {
            var newRow = '<div class="row mt-3" id="form-foto">' +
                    '<div class="col-md-2 col-sm-12">' +
                        '<label class="form-label">Upload Foto ke - ' + ($('div[id^="form-foto"]').length + 2) + '</label>' +
                    '</div>' +
                    '<div class="col-md-3 col-sm-12">' +
                        '<input name="foto_kegiatan[]" type="file" class="form-control" required>' +
                    '</div>' +
                    '<div class="col-md-4 col-sm-12">' +
                        '<input name="keterangan[]" type="text" class="form-control" placeholder="Keterangan Foto ke - ' + ($('div[id^="form-foto"]').length + 2) + '">' +
                    '</div>' +
                    '<div class="col-md-2 col-sm-12">' +
                        '<button class="btn btn-sm btn-danger hapus-foto"><i class="las la-trash-alt"></i> Hapus Foto</button>' +
                    '</div>' +
                '</div>';

            $("#form-foto:last").after(newRow);
        });

        $(document).on('click', '.hapus-foto', function() {
            $(this).closest('.row').remove();
        });
    })
</script>