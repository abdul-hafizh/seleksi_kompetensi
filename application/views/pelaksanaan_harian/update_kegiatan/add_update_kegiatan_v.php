<!--select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="row">
    <div class="col-lg-8 col-12">
        <div class="card">
            <div class="card-header border-bottom pb-2">
                <h4 class="card-title">Form Update Kegiatan</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form class="form-bordered" method="post" action="<?php echo site_url('pelaksanaan_harian/update_kegiatan/submit'); ?>" enctype="multipart/form-data">
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Jadwal Kegiatan</label>
                            <div class="col-md-9">
                                <select class="select-single" name="jadwal_kegiatan_id" id="jadwal_kegiatan_id" required>
                                    <option value="" disabled selected>Jadwal Kegiatan</option>
                                    <?php foreach ($get_jadwal_kegiatan as $v) { ?>
                                        <option value="<?php echo $v['id']; ?>"><?php echo $v['kode_kegiatan'] . " | " . $v['nama_kegiatan'] . " • " . $v['nama_lokasi']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Tanggal Kegiatan</label>
                            <div class="col-md-3">
                                <input type="date" class="form-control" name="tgl_kegiatan" title="Tanggal Kegiatan" required>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Upload Foto Registrasi</label>
                            <div class="col-md-9">
                                <input type="file" class="form-control col-lg-7" name="foto_registrasi" placeholder="Foto Registrasi">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Upload Foto Pengarahan</label>
                            <div class="col-md-9">
                                <input type="file" class="form-control col-lg-7" name="foto_pengarahan" placeholder="Foto Pengarahan">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Upload Foto Kegiatan Lain</label>
                            <div class="col-md-9">
                                <input type="file" class="form-control col-lg-7" name="foto_kegiatan_lain" placeholder="Foto Kegiatan Lain">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Upload Video Kegiatan</label>
                            <div class="col-md-9">
                                <input type="file" class="form-control col-lg-7" name="video_kegiatan" placeholder="Video Kegiatan">
                            </div>
                        </div>

                        <div class="text-right">
                            <a href="<?php echo site_url('pelaksanaan_harian/update_kegiatan'); ?>" class="btn btn-secondary"><i class="ft-chevrons-left mr-1"></i>Kembali</a>
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin simpan data ini?');"><i class="ft-check-square mr-1"></i>Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-2"></div>
</div>

<!--select2 cdn-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $(".select-single").select2();
    })
</script>