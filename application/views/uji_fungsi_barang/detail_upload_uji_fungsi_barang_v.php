<?php if(count($get_foto) > 0) { ?>
    <form action="<?php echo site_url('uji_fungsi_barang/submit_update_detail_foto'); ?>" method="POST" id="basic-form" enctype="multipart/form-data">   
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Ubah Foto Barang</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3 p-3">

                            <?php $i=1; foreach($get_foto as $v) { ?>
                            <div class="row mb-3">
                                <div class="col-md-2 col-sm-12">
                                    <h6>Foto ke - <?php echo $i; ?></h6>
                                    <div class="d-flex align-items-center">
                                        <div class="d-inline-flex gap-2 border border-dashed p-2 mb-2 w-75">
                                            <a href="<?php echo base_url('uploads/uji_fungsi_barang/' . $v['foto_barang']);?>" class="bg-light rounded p-1" target="_blank">
                                                <img src="<?php echo base_url('uploads/uji_fungsi_barang/' . $v['foto_barang']);?>" alt="" class="img-fluid d-block" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="p-3">
                                        <label class="form-label">Ubah Foto Barang ke - <?php echo $i; ?></label>
                                        <input id="foto_barang" name="foto_barang[]" type="file" class="form-control">                                    
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="p-3">
                                        <label class="form-label">Keterangan Foto ke - <?php echo $i; ?></label>
                                        <div class="d-flex align-items-center">
                                            <input id="catatan_foto" name="catatan_foto[]" type="text" class="form-control" placeholder="Keterangan Foto ke - <?php echo $i; ?>" value="<?php echo $v['catatan_foto']; ?>" style="margin-right: 10px">
                                            <a href="<?php echo site_url('uji_fungsi_barang/delete_foto/' . $v['id']); ?>" onclick="return confirm('Apakah Anda yakin hapus foto ini?');" class="btn btn-sm btn-danger" title="Hapus Foto"><i class="ri-delete-bin-5-line"></i></a>
                                        </div>
                                        <input type="hidden" name="detail_foto_id[]" value="<?php echo $v['id']; ?>" readonly>
                                        <input type="hidden" name="foto_exist[]" value="<?php echo $v['foto_barang']; ?>" readonly>
                                    </div>
                                </div>                                
                            </div>
                            <?php $i++; } ?>
                            <input type="hidden" name="uji_penerimaan_id" value="<?php echo $get_detail['uji_penerimaan_id']; ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if($job_title == 'KOORDINATOR' && $get_detail['status_uji'] == 'Pending') { ?>
        <div class="row mt-2">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">                    
                        <div class="text-center">
                            <button type="submit" class="btn btn-warning" onclick="return confirm('Apakah Anda yakin?');">Ubah Data Foto</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </form>
<?php } ?>

<?php if($job_title == 'KOORDINATOR' && $get_detail['status_uji'] == 'Pending') { ?>
    <form action="<?php echo site_url('uji_fungsi_barang/submit_detail_foto'); ?>" method="post" id="basic-form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Tambah Foto Barang</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3 p-3">
                            <div class="row mb-3" id="form-foto">
                                <div class="col-lg-2">
                                    <label class="form-label">Upload Foto ke - 1</label>
                                </div>
                                <div class="col-lg-3">
                                    <input id="foto_barang" name="foto_barang[]" type="file" class="form-control" required>
                                </div>
                                <div class="col-lg-3">
                                    <input id="catatan_foto" name="catatan_foto[]" type="text" class="form-control" placeholder="Keterangan Foto ke - 1">
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <button type="button" class="btn btn-sm btn-info" id="tambah-foto"><i class="las la-plus-square"></i> Tambah Foto</button>
                                </div>
                            </div>
                            <input type="hidden" name="uji_penerimaan_id" value="<?php echo $get_detail['uji_penerimaan_id']; ?>" readonly>
                            <input type="hidden" name="detail_id" value="<?php echo $get_detail['id']; ?>" readonly>
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
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin?');">Simpan Data Baru</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php } else {?>
    <div class="row mt-2">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">                    
                    <div class="text-center">
                        <h3>Belum ada foto.</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<script>    
    $(document).ready(function () {                
        $("#tambah-foto").click(function() {
            var newRow = '<div class="row mt-3" id="form-foto">' +
                    '<div class="col-md-2 col-sm-12">' +
                        '<label class="form-label">Upload Foto ke - ' + ($('div[id^="form-foto"]').length + 1) + '</label>' +
                    '</div>' +
                    '<div class="col-md-3 col-sm-12">' +
                        '<input name="foto_barang[]" type="file" class="form-control" required>' +
                    '</div>' +
                    '<div class="col-md-3 col-sm-12">' +
                        '<input name="catatan_foto[]" type="text" class="form-control" placeholder="Keterangan Foto ke - ' + ($('div[id^="form-foto"]').length + 1) + '">' +
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