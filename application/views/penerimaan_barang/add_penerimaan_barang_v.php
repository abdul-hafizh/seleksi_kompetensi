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
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Wilayah</label>
                        </div>
                        <div class="col-lg-4">
                            <select class="select-single" name="provinsi" id="provinsi" required>
                                <option value="">Pilih Provinsi</option>
                                <?php foreach($get_provinsi as $v) { ?>
                                    <option value="<?php echo $v['location_id']; ?>"><?php echo $v['province_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <select class="select-single" name="kabupaten" id="kabupaten" disabled required>
                                <option value="">Pilih Kabupaten</option>
                            </select>
                        </div>
                    </div>                

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">&nbsp;</label>
                        </div>                        
                        <div class="col-lg-4">
                            <select class="select-single" name="kecamatan" id="kecamatan" disabled required>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <select class="select-single" name="desa" id="desa" disabled required>
                                <option value="">Pilih Desa</option>
                            </select>
                        </div>
                    </div>    
                    
                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Lokasi</label>
                        <div class="col-md-9">
                            <select class="select-single" name="lokasi_test_id" id="lokasi_test_id" disabled required>
                                <option value="">Pilih Lokasi</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Perencanaan</label>
                        <div class="col-md-9">
                            <select class="select-single" name="perencanaan_id" id="perencanaan_id" disabled required>
                                <option value="">Pilih Perencanaan</option>
                            </select>
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
        
        $("#provinsi").on("change", function () {
			let provinsi = $("#provinsi").val();
			$.ajax({
				url: "<?php echo site_url('penerimaan_barang/get_regency');?>",
				data: { provinsi: provinsi },
				method: "POST",
				dataType: "json",
				success: function (data) {
					kabupaten = '<option value="">Pilih Kabupaten</option>';
					$.each(data, function (i, item) {   
						kabupaten += '<option value="' + item.location_id +'">' + item.regency_name + "</option>";
					});
					$("#kabupaten").html(kabupaten).removeAttr("disabled");
				},
			});
		});        

        $("#kabupaten").on("change", function () {
			let kabupaten = $("#kabupaten").val();
			$.ajax({
				url: "<?php echo site_url('penerimaan_barang/get_district');?>",
				data: { kabupaten: kabupaten },
				method: "POST",
				dataType: "json",
				success: function (data) {
					kecamatan = '<option value="">Pilih Kecamatan</option>';
					$.each(data, function (i, item) {   
						kecamatan += '<option value="' + item.location_id +'">' + item.district_name + "</option>";
					});
					$("#kecamatan").html(kecamatan).removeAttr("disabled");
				},
			});
		});        

        $("#kecamatan").on("change", function () {
			let kecamatan = $("#kecamatan").val();
			$.ajax({
				url: "<?php echo site_url('penerimaan_barang/get_village');?>",
				data: { kecamatan: kecamatan },
				method: "POST",
				dataType: "json",
				success: function (data) {
					desa = '<option value="">Pilih Desa</option>';
					$.each(data, function (i, item) {   
						desa += '<option value="' + item.location_id +'">' + item.village_name + "</option>";
					});
					$("#desa").html(desa).removeAttr("disabled");
				},
			});
		});        

        $("#desa").on("change", function () {
			let desa = $("#desa").val();
			$.ajax({
				url: "<?php echo site_url('penerimaan_barang/get_lokasi');?>",
				data: { desa: desa },
				method: "POST",
				dataType: "json",
				success: function (data) {
					lokasi_test_id = '<option value="">Pilih Lokasi</option>';
					$.each(data, function (i, item) {   
						lokasi_test_id += '<option value="' + item.id +'">' + item.kode_lokasi + ' | ' + item.nama_lokasi + "</option>";
					});
					$("#lokasi_test_id").html(lokasi_test_id).removeAttr("disabled");
				},
			});
		});

        $("#lokasi_test_id").on("change", function () {
			let lokasi = $("#lokasi_test_id").val();
			$.ajax({
				url: "<?php echo site_url('penerimaan_barang/get_perencanaan');?>",
				data: { lokasi: lokasi },
				method: "POST",
				dataType: "json",
				success: function (data) {
					perencanaan_id = '<option value="">Pilih Perencanaan</option>';
					$.each(data, function (i, item) {   
						perencanaan_id += '<option value="' + item.id +'">' + item.kode_perencanaan + ' | ' + item.nama_barang + "</option>";
					});
					$("#perencanaan_id").html(perencanaan_id).removeAttr("disabled");
				},
			});
		});
    })
</script>