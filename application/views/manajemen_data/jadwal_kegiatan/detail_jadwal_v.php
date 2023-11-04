<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Detail Data Jadwal Kegiatan</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label class="form-label">Status Kegiatan</label>
                    </div>
                    <div class="col-lg-3">       
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status_kegiatan" value="Aktif" <?php echo $get_jadwal['status_kegiatan'] == 'Aktif' ? 'checked' : 'disabled'; ?>>
                            <label class="form-check-label">Aktif</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status_kegiatan" value="Tidak Aktif" <?php echo $get_jadwal['status_kegiatan'] == 'Tidak Aktif' ? 'checked' : 'disabled'; ?>>
                            <label class="form-check-label">Tidak Aktif</label>
                        </div>
                    </div>
                </div>    

                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label class="form-label">Nama Kegiatan</label>
                    </div>
                    <div class="col-lg-3">       
                        <input type="text" class="form-control" name="nama_kegiatan" placeholder="Nama Kegiatan" value="<?php echo $get_jadwal['nama_kegiatan']; ?>" readonly>
                    </div>
                </div>     

                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label class="form-label">Tanggal Mulai/Selesai</label>
                    </div>
                    <div class="col-lg-4">
                        <input type="date" class="form-control" name="tgl_mulai" title="Tanggal Mulai" value="<?php echo $get_jadwal['tgl_mulai']; ?>" readonly>
                    </div>
                    <div class="col-lg-4">
                        <input type="date" class="form-control" name="tgl_selesai" title="Tanggal Selesai" value="<?php echo $get_jadwal['tgl_selesai']; ?>" readonly>
                    </div>
                </div>                

                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label class="form-label">Tahun</label>
                    </div>
                    <div class="col-lg-3">       
                        <input type="number" class="form-control" min="2010" max="9999" name="tahun" placeholder="Tahun Kegiatan" value="<?php echo $get_jadwal['tahun']; ?>" readonly>
                    </div>
                </div>     

                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label class="form-label">Lokasi Kegiatan</label>
                    </div>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?php echo $get_jadwal['nama_lokasi']; ?>" readonly>
                    </div>
                </div>                

                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label class="form-label">Catatan</label>
                    </div>
                    <div class="col-lg-9">
                        <textarea class="form-control" name="catatan" rows="3" placeholder="Catatan" readonly><?php echo $get_jadwal['catatan']; ?></textarea>
                    </div>
                </div>                    
            </div>
        </div>
    </div>
</div>