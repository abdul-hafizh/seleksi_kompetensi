<!--select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="row">
    <div class="col-lg-8 col-12">
        <div class="card">
            <div class="card-header border-bottom pb-2">
                <h4 class="card-title">Edit Update Kegiatan</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form class="form-bordered" method="post" action="<?php echo site_url('pelaksanaan_harian/update_kegiatan/submit'); ?>" enctype="multipart/form-data">
                        <input type="hidden" class="form-control" name="id" value="<?php echo $selected['id']; ?>">
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Titik Lokasi</label>
                            <div class="col-md-9">
                                <select class="select-single" name="jadwal_kegiatan_id" id="jadwal_kegiatan_id" <?php echo $job_title == 'KOORDINATOR' ? 'required' : 'readonly' ?>>
                                    <option value="" disabled selected>Titik Lokasi</option>
                                    <?php foreach ($get_jadwal_kegiatan as $detail) { ?>
                                        <option value="<?php echo $detail['id']; ?>" selected="<?php echo $selected['id'] == $detail['id']; ?>">
                                            <?php echo $detail['kode_kegiatan'] . " • " . $detail['nama_lokasi']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Tanggal Kegiatan</label>
                            <div class="col-md-3">
                                <input type="date" class="form-control" name="tgl_kegiatan" title="Tanggal Kegiatan" value="<?php echo $selected['tgl_kegiatan']; ?>" <?php echo $job_title == 'KOORDINATOR' ? 'required' : 'readonly' ?>>
                            </div>
                        </div>

                        <?php if($job_title != 'KOORDINATOR'){ ?>
                            <div class="row mb-3">
                                <div class="col-lg-3">
                                    <label class="form-label">Status</label>
                                </div>
                                <div class="col-lg-3">       
                                    <select class="form-control" name="status_kegiatan" required>
                                        <option value="Pending" <?php echo $selected['status_kegiatan'] == 'Pending' ? ' selected' : ''; ?> >Pending</option>
                                        <option value="Approved" <?php echo $selected['status_kegiatan'] == 'Approved' ? ' selected' : ''; ?>>Approved</option>
                                    </select>
                                </div>
                            </div>    
                        <?php } else { ?>
                            <input type="hidden" name="status_kegiatan" value="<?php echo $selected['status_kegiatan']; ?>" readonly>
                        <?php } ?>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Upload Foto Registrasi <span class="text-muted">(Opsional)</span></label>
                            <div class="col-md-1">
                                <div class="avatar-group">
                                    <a href="<?php echo base_url('uploads/update_kegiatan/' . $selected['foto_registrasi']) ?>" target="_blank" class="avatar-group-item" data-img="<?php echo base_url('uploads/update_kegiatan/' . $selected['foto_registrasi']) ?>" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top">
                                        <img src="<?php echo base_url('uploads/update_kegiatan/' . $selected['foto_registrasi']) ?>" class="rounded-circle avatar-xxs">
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <input type="file" class="form-control col-lg-7" name="foto_registrasi" placeholder="Foto Registrasi" <?php echo $job_title == 'KOORDINATOR' ? '' : 'readonly' ?>>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Upload Foto Pengarahan <span class="text-muted">(Opsional)</span></label>
                            <div class="col-md-1">
                                <div class="avatar-group">
                                    <a href="<?php echo base_url('uploads/update_kegiatan/' . $selected['foto_pengarahan']) ?>" target="_blank" class="avatar-group-item" data-img="<?php echo base_url('uploads/update_kegiatan/' . $selected['foto_pengarahan']) ?>" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top">
                                        <img src="<?php echo base_url('uploads/update_kegiatan/' . $selected['foto_pengarahan']) ?>" class="rounded-circle avatar-xxs">
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <input type="file" class="form-control col-lg-7" name="foto_pengarahan" placeholder="Foto Pengarahan" <?php echo $job_title == 'KOORDINATOR' ? '' : 'readonly' ?>>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Upload Foto Kegiatan Lain <span class="text-muted">(Opsional)</span></label>
                            <div class="col-md-1">
                                <div class="avatar-group">
                                    <a href="<?php echo base_url('uploads/update_kegiatan/' . $selected['foto_kegiatan_lain']) ?>" target="_blank" class="avatar-group-item" data-img="<?php echo base_url('uploads/update_kegiatan/' . $selected['foto_kegiatan_lain']) ?>" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top">
                                        <img src="<?php echo base_url('uploads/update_kegiatan/' . $selected['foto_kegiatan_lain']) ?>" class="rounded-circle avatar-xxs">
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <input type="file" class="form-control col-lg-7" name="foto_kegiatan_lain" placeholder="Foto Kegiatan Lain" <?php echo $job_title == 'KOORDINATOR' ? '' : 'readonly' ?>>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Ubah Video Kegiatan <span class="text-muted">(Opsional)</span></label>                            
                            <div class="col-md-9">
                                <input type="file" class="form-control" name="video_kegiatan" placeholder="Video Kegiatan" <?php echo $job_title == 'KOORDINATOR' ? '' : 'readonly' ?>>
                                <?php if (!empty($selected['video_kegiatan'])): ?>
                                    <video width="340" height="160" controls>
                                        <source src="<?php echo base_url('uploads/update_kegiatan/' . $selected['video_kegiatan']) ?>" type="video/mp4">
                                        Maaf, browser Anda tidak mendukung tag video.
                                    </video>
                                <?php else: ?>
                                    <p>Tidak ada video</p>
                                <?php endif; ?>
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
                                <input type="number" class="form-control col-lg-7" name="sesi_1" placeholder="" value="<?php echo $selected['sesi_1']; ?>" <?php echo $job_title == 'KOORDINATOR' ? 'required' : 'readonly' ?>>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control" style="text-align: right; padding-top: 8px;">Sesi-2</label>
                            <div class="col-md-2">
                                <input type="number" class="form-control col-lg-7" name="sesi_2" placeholder="" value="<?php echo $selected['sesi_2']; ?>" <?php echo $job_title == 'KOORDINATOR' ? '' : 'readonly' ?>>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control" style="text-align: right; padding-top: 8px;">Sesi-3</label>
                            <div class="col-md-2">
                                <input type="number" class="form-control col-lg-7" name="sesi_3" placeholder="" value="<?php echo $selected['sesi_3']; ?>" <?php echo $job_title == 'KOORDINATOR' ? '' : 'readonly' ?>>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control" style="text-align: right; padding-top: 8px;">Sesi-4</label>
                            <div class="col-md-2">
                                <input type="number" class="form-control col-lg-7" name="sesi_4" placeholder="" value="<?php echo $selected['sesi_4']; ?>" <?php echo $job_title == 'KOORDINATOR' ? '' : 'readonly' ?>>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control" style="text-align: right; padding-top: 8px;">Sesi-5</label>
                            <div class="col-md-2">
                                <input type="number" class="form-control col-lg-7" name="sesi_5" placeholder="" value="<?php echo $selected['sesi_5']; ?>" <?php echo $job_title == 'KOORDINATOR' ? '' : 'readonly' ?>>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control" style="text-align: right; padding-top: 8px;">Sesi-6</label>
                            <div class="col-md-2">
                                <input type="number" class="form-control col-lg-7" name="sesi_6" placeholder="" value="<?php echo $selected['sesi_6']; ?>" <?php echo $job_title == 'KOORDINATOR' ? '' : 'readonly' ?>>
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