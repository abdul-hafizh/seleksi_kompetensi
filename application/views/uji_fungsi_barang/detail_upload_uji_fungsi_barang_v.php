<?php if(count($get_foto) > 0) { ?>

<form action="<?php echo site_url('uji_fungsi_barang/submit_update_detail_foto'); ?>" method="post" id="basic-form" enctype="multipart/form-data">   
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ubah Foto Barang (Total: <?php echo $get_detail['jumlah_terima']; ?> File Foto)</h5>
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
                            <div class="col-md-4 col-sm-12">
                                <div class="p-3">
                                    <label class="form-label">Keterangan Foto ke - <?php echo $i; ?></label>
                                    <input id="catatan_foto" name="catatan_foto[]" type="text" class="form-control" placeholder="Keterangan Foto ke - <?php echo $i; ?>" value="<?php echo $v['catatan_foto']; ?>">
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
</form>

<?php } else { ?>

<form action="<?php echo site_url('uji_fungsi_barang/submit_detail_foto'); ?>" method="post" id="basic-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Upload Foto Barang (Total: <?php echo $get_detail['jumlah_terima']; ?> File Foto)</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3 p-3">
                        <?php for ($i = 1; $i <= (int)$get_detail['jumlah_terima']; $i++) { ?>
                            <div class="row mb-3">
                                <div class="col-lg-2">
                                    <label class="form-label">Uplaod Foto Barang ke - <?php echo $i; ?></label>
                                </div>
                                <div class="col-lg-4">
                                    <input id="foto_barang" name="foto_barang[]" type="file" class="form-control">
                                </div>
                                <div class="col-lg-4">
                                    <input id="catatan_foto" name="catatan_foto[]" type="text" class="form-control" placeholder="Keterangan Foto ke - <?php echo $i; ?>">
                                </div>
                            </div>
                        <?php } ?>          
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
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin?');">Konfirmasi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php } ?>