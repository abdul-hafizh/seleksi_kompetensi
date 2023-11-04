<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Detail Data Lokasi Test</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label class="form-label">Status Gedung</label>
                    </div>
                    <div class="col-lg-3">       
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status_gedung" value="Siap" <?php echo $get_lokasi['status_gedung'] == 'Siap' ? 'checked' : 'disabled'; ?>>
                            <label class="form-check-label">Siap</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status_gedung" value="Tidak" <?php echo $get_lokasi['status_gedung'] == 'Tidak' ? 'checked' : 'disabled'; ?>>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                </div>    

                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label class="form-label">Nama Lokasi</label>
                    </div>
                    <div class="col-lg-3">       
                        <input type="text" class="form-control" name="nama_lokasi" placeholder="Nama Lokasi" value="<?php echo $get_lokasi['nama_lokasi']; ?>" readonly>
                    </div>
                </div>     

                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label class="form-label">Nama Daerah</label>
                    </div>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?php echo $get_lokasi['province_name']; ?>" readonly>
                    </div>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?php echo $get_lokasi['regency_name']; ?>" readonly>
                    </div>
                </div>                

                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label class="form-label">&nbsp;</label>
                    </div> 
                    <div class="col-lg-9">
                        <textarea class="form-control" name="alamat" rows="3" placeholder="Alamat" readonly><?php echo $get_lokasi['alamat']; ?></textarea>
                    </div>
                </div>                 

                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label class="form-label">Catatan</label>
                    </div>
                    <div class="col-lg-9">
                        <textarea class="form-control" name="catatan" rows="3" placeholder="Catatan" readonly><?php echo $get_lokasi['catatan']; ?></textarea>
                    </div>
                </div>                    
            </div>
        </div>
    </div>
</div>