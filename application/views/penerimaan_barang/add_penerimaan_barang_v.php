<!-- select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jquery validate-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<form action="<?php echo site_url('penerimaan_barang/submit_data'); ?>" method="post" id="basic-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tambah Data Penerimaan Barang</h5>
                </div>
                <div class="card-body">       
                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Data Pengiriman</label>
                        <div class="col-md-6">
                            <select class="select-single" name="pengiriman_id" id="pengiriman_id" required>
                                <option value="0">Pilih Pengiriman</option>
                                <?php foreach($get_pengiriman as $v) { ?>
                                    <option value="<?php echo $v['id']; ?>"><?php echo $v['perencanaan_id'] . ' | ' . $v['nama_barang'] . ' (' . $v['province_name'] . ', ' . $v['regency_name'] . ', ' . $v['district_name'] . ', ' . $v['village_name'] . ')'; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Jumlah Dikirim / Tanggal Kirim</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="text" class="form-control" id="jumlah_kirim" placeholder="Jumlah Dikirim / Tanggal Kirim" readonly>
                        </div>
                    </div>    

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Tanggal Diterima</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="date" class="form-control" name="tgl_terima" placeholder="Tanggal Diterima" required>
                        </div>
                    </div>    

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Jumlah Diterima</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="number" class="form-control" name="jumlah_terima" placeholder="Jumlah Diterima" required>
                        </div>
                    </div>    

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Jumlah Rusak</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="number" class="form-control" name="rusak" placeholder="Jumlah Rusak" required>
                        </div>
                    </div>    

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Jumlah Terpasang</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="number" class="form-control" name="terpasang" placeholder="Jumlah Terpasang" required>
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
        
        $("#pengiriman_id").on("change", function () {
			let pengiriman_id = $("#pengiriman_id").val();
			$.ajax({
				url: "<?php echo site_url('penerimaan_barang/get_pengiriman');?>",
				data: { pengiriman_id: pengiriman_id },
				method: "POST",
				dataType: "json",
				success: function (data) {					
					$("#jumlah_kirim").val(data.jumlah_kirim + ' | ' + data.tgl_kirim);
				},
			});
		});                
    })
</script>