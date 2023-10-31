<!-- select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jquery validate-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<form action="<?php echo site_url('pengiriman_barang/submit_data'); ?>" method="post" id="basic-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tambah Data Pengiriman Barang</h5>
                </div>
                <div class="card-body">                    
                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Perencanaan</label>
                        <div class="col-md-6">
                            <select class="select-single" name="perencanaan_id" id="perencanaan_id" required>
                                <option value="0">Pilih Perencanaan</option>
                                <?php foreach($get_perencanaan as $v) { ?>
                                    <option value="<?php echo $v['id']; ?>"><?php echo $v['kode_perencanaan'] . ' | ' . $v['nama_barang'] . ' (' . $v['province_name'] . ', ' . $v['regency_name'] . ', ' . $v['district_name'] . ', ' . $v['village_name'] . ')'; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Jumlah Rencana</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="text" class="form-control" id="jumlah_rencana" readonly>
                        </div>
                    </div>    
                    
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Jumlah Kirim</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="number" class="form-control" name="jumlah_kirim" placeholder="Jumlah Kirim" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Tanggal Kirim</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="date" class="form-control" name="tgl_kirim" placeholder="Tanggal Kirim" required>
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
        
        $("#perencanaan_id").on("change", function () {
			let perencanaan_id = $("#perencanaan_id").val();
			$.ajax({
				url: "<?php echo site_url('pengiriman_barang/get_perencanaan');?>",
				data: { perencanaan_id: perencanaan_id },
				method: "POST",
				dataType: "json",
				success: function (data) {					
					$("#jumlah_rencana").val(data.jumlah);
				},
			});
		});        
    })
</script>