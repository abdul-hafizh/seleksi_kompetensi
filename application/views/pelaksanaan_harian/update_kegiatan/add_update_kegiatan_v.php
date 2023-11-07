<!--select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="row">
    <div class="col-lg-9 col-12">
        <div class="card">
            <div class="card-header border-bottom pb-2">
                <h4 class="card-title">Form Update Kegiatan</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form class="form-bordered" method="post" action="<?php echo site_url('pelaksanaan_harian/update_kegiatan/submit'); ?>" enctype="multipart/form-data">
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Titik Lokasi</label>
                            <div class="col-md-6">
                                <select class="select-single" name="jadwal_kegiatan_id" id="jadwal_kegiatan_id" required>
                                    <option value="" disabled selected>Titik Lokasi</option>
                                    <?php foreach ($get_jadwal_kegiatan as $v) { ?>
                                        <option value="<?php echo $v['id']; ?>"><?php echo $v['kode_kegiatan'] . " • " . $v['nama_lokasi']; ?></option>
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
                            <div class="col-md-6">
                                <input type="file" multiple accept="image/*" class="form-control col-lg-7" name="foto_registrasi[]" placeholder="Foto Registrasi">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Upload Foto Pengarahan</label>
                            <div class="col-md-6">
                                <input type="file" multiple accept="image/*" class="form-control col-lg-7" name="foto_pengarahan[]" placeholder="Foto Pengarahan">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Upload Foto Kegiatan Lain</label>
                            <div class="col-md-6">
                                <input type="file" multiple accept="image/*" class="form-control col-lg-7" name="foto_kegiatan_lain[]" placeholder="Foto Kegiatan Lain">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Upload Video Kegiatan</label>
                            <div class="col-md-6">
                                <input type="file" multiple accept="video/*" class="form-control col-lg-7" name="video_kegiatan[]" placeholder="Video Kegiatan">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Jumlah Peserta :</label>
                            <label class="col-md-1 label-control" style="text-align: right; padding-top: 8px;">Sesi-1</label>
                            <div class="col-md-2">
                                <input type="number" class="form-control col-lg-7" name="sesi_1" placeholder="" required>
                            </div>
                            <label class="col-md-1 label-control" style="text-align: right; padding-top: 8px;">Sesi-4</label>
                            <div class="col-md-2">
                                <input type="number" class="form-control col-lg-7" name="sesi_4" placeholder="">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <div class="col-md-3"></div>
                            <label class="col-md-1 label-control" style="text-align: right; padding-top: 8px;">Sesi-2</label>
                            <div class="col-md-2">
                                <input type="number" class="form-control col-lg-7" name="sesi_2" placeholder="">
                            </div>
                            <label class="col-md-1 label-control" style="text-align: right; padding-top: 8px;">Sesi-5</label>
                            <div class="col-md-2">
                                <input type="number" class="form-control col-lg-7" name="sesi_5" placeholder="">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <div class="col-md-3"></div>
                            <label class="col-md-1 label-control" style="text-align: right; padding-top: 8px;">Sesi-3</label>
                            <div class="col-md-2">
                                <input type="number" class="form-control col-lg-7" name="sesi_3" placeholder="">
                            </div>
                            <label class="col-md-1 label-control" style="text-align: right; padding-top: 8px;">Sesi-6</label>
                            <div class="col-md-2">
                                <input type="number" class="form-control col-lg-7" name="sesi_6" placeholder="">
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