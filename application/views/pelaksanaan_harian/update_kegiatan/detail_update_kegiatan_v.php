<div class="row">
    <div class="col-lg-8 col-12">
        <div class="card">
            <div class="card-header border-bottom pb-2">
                <h4 class="card-title">Detail Update Kegiatan</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form class="form-bordered" method="post" action="<?php echo site_url('pelaksanaan_harian/update_kegiatan/submit'); ?>" enctype="multipart/form-data">
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Titik Lokasi</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" title="Titik Lokasi" value="<?php echo $detail['kode_kegiatan'] . " • " . $detail['nama_lokasi']; ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Tanggal Kegiatan</label>
                            <div class="col-md-3">
                                <input type="date" class="form-control" value="<?php echo $detail['tgl_kegiatan']; ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Upload Foto Registrasi</label>
                            <div class="col-md-9">
                                <div class="avatar-group">
                                    <a href="<?php echo base_url('uploads/update_kegiatan/' . $detail['foto_registrasi']) ?>" target="_blank" class="avatar-group-item" data-img="<?php echo base_url('uploads/update_kegiatan/' . $detail['foto_registrasi']) ?>" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top">
                                        <img src="<?php echo base_url('uploads/update_kegiatan/' . $detail['foto_registrasi']) ?>" class="rounded-circle avatar-xxs">
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Upload Foto Pengarahan</label>
                            <div class="col-md-9">
                                <div class="avatar-group">
                                    <a href="<?php echo base_url('uploads/update_kegiatan/' . $detail['foto_pengarahan']) ?>" target="_blank" class="avatar-group-item" data-img="<?php echo base_url('uploads/update_kegiatan/' . $detail['foto_pengarahan']) ?>" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top">
                                        <img src="<?php echo base_url('uploads/update_kegiatan/' . $detail['foto_pengarahan']) ?>" class="rounded-circle avatar-xxs">
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Upload Foto Kegiatan Lain</label>
                            <div class="col-md-9">
                                <div class="avatar-group">
                                    <a href="<?php echo base_url('uploads/update_kegiatan/' . $detail['foto_kegiatan_lain']) ?>" target="_blank" class="avatar-group-item" data-img="<?php echo base_url('uploads/update_kegiatan/' . $detail['foto_kegiatan_lain']) ?>" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top">
                                        <img src="<?php echo base_url('uploads/update_kegiatan/' . $detail['foto_kegiatan_lain']) ?>" class="rounded-circle avatar-xxs">
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Upload Video Kegiatan</label>
                            <div class="col-md-9">
                                <a href="<?php echo base_url('uploads/update_kegiatan/' . $detail['video_kegiatan']) ?>" target="_blank" class="avatar-group-item" data-img="<?php echo base_url('uploads/update_kegiatan/' . $detail['video_kegiatan']) ?>" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top">
                                    <?php echo $detail['video_kegiatan'] ?>
                                </a>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Jumlah Peserta :</label>
                            <div class="col-md-9">
                                &nbsp;
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control" style="text-align: right; padding-top: 8px;">Sesi-1</label>
                            <div class="col-md-2">
                                <input type="number" class="form-control col-lg-7" name="sesi_1" value="<?php echo $detail['sesi_1']; ?>" required>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control" style="text-align: right; padding-top: 8px;">Sesi-2</label>
                            <div class="col-md-2">
                                <input type="number" class="form-control col-lg-7" name="sesi_2" value="<?php echo $detail['sesi_2']; ?>">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control" style="text-align: right; padding-top: 8px;">Sesi-3</label>
                            <div class="col-md-2">
                                <input type="number" class="form-control col-lg-7" name="sesi_3" value="<?php echo $detail['sesi_3']; ?>">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control" style="text-align: right; padding-top: 8px;">Sesi-4</label>
                            <div class="col-md-2">
                                <input type="number" class="form-control col-lg-7" name="sesi_4" value="<?php echo $detail['sesi_4']; ?>">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control" style="text-align: right; padding-top: 8px;">Sesi-5</label>
                            <div class="col-md-2">
                                <input type="number" class="form-control col-lg-7" name="sesi_5" value="<?php echo $detail['sesi_5']; ?>">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control" style="text-align: right; padding-top: 8px;">Sesi-6</label>
                            <div class="col-md-2">
                                <input type="number" class="form-control col-lg-7" name="sesi_6" value="<?php echo $detail['sesi_6']; ?>">
                            </div>
                        </div>

                        <div class="text-right">
                            <a href="<?php echo site_url('pelaksanaan_harian/update_kegiatan'); ?>" class="btn btn-secondary"><i class="ft-chevrons-left mr-1"></i>Kembali</a>
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
    $(document).ready(function() {})
</script>