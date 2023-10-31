<!-- select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jquery validate-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<form action="<?php echo site_url('pelaksanaan_harian/update_barang/submit_data'); ?>" method="post" id="basic-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tambah Data Update Pengawasan Barang</h5>
                </div>
                <div class="card-body">       
                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Data Penerimaan</label>
                        <div class="col-md-6">
                            <select class="select-single" name="penerimaan_id" id="penerimaan_id" required>
                                <option value="0">Pilih penerimaan</option>
                                <?php foreach($get_penerimaan as $v) { ?>
                                    <option value="<?php echo $v['id']; ?>"><?php echo $v['id'] . ' | ' . $v['nama_barang'] . ' (' . $v['province_name'] . ', ' . $v['regency_name'] . ', ' . $v['district_name'] . ', ' . $v['village_name'] . ')'; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Jumlah Diterima / Tanggal Terima</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="text" class="form-control" id="jumlah_terima" placeholder="Jumlah Diterima / Tanggal Terima" readonly>
                        </div>
                    </div>    

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Nama Barang</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Nama Barang" readonly>
                        </div>
                    </div>    

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Status Barang</label>
                        </div>
                        <div class="col-lg-3">       
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status_uji" value="Baik">
                                <label class="form-check-label">Baik</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status_uji" value="Tidak">
                                <label class="form-check-label">Tidak</label>
                            </div>
                        </div>
                    </div>    

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Tanggal Kegiatan</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="date" class="form-control" name="tgl_pelaksanaan" placeholder="Tanggal Kegiatan" required>
                        </div>
                    </div>    

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Jumlah Barang</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="number" class="form-control" name="jumlah_barang" placeholder="Jumlah Barang" required>
                        </div>
                    </div>    

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Foto Barang</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="file" class="form-control" name="foto_barang" placeholder="Foto Barang" required>
                        </div>
                    </div>          

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Catatan</label>
                        </div>
                        <div class="col-lg-9">
                            <textarea class="form-control" name="catatan" rows="3" placeholder="Catatan" required></textarea>
                        </div>
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
        
        $("#penerimaan_id").on("change", function () {
			let penerimaan_id = $("#penerimaan_id").val();
			$.ajax({
				url: "<?php echo site_url('pelaksanaan_harian/update_barang/get_penerimaan');?>",
				data: { penerimaan_id: penerimaan_id },
				method: "POST",
				dataType: "json",
				success: function (data) {					
					$("#nama_barang").val(data.nama_barang);
					$("#jumlah_terima").val(data.jumlah_terima + ' | ' + data.tgl_terima);
				},
			});
		});                
    })
</script>