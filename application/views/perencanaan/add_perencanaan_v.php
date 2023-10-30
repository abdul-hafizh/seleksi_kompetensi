<!-- select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jquery validate-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<style>
    label.error {
        color: red;
        font-size: 14px;
        display: block;
        margin-top: 5px;
    }
</style>

<form action="<?php echo site_url('manajemen_data/lokasi_skd/submit_data'); ?>" method="post" id="basic-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tambah Data Lokasi Test</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Status Gedung</label>
                        </div>
                        <div class="col-lg-3">       
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status_gedung" value="Siap">
                                <label class="form-check-label">Siap</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status_gedung" value="Tidak">
                                <label class="form-check-label">Tidak</label>
                            </div>
                        </div>
                    </div>    

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Kode Lokasi</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="text" class="form-control" name="kode_lokasi" placeholder="Kode Lokasi" required>
                        </div>
                    </div>     

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Nama Lokasi</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="text" class="form-control" name="nama_lokasi" placeholder="Nama Lokasi" required>
                        </div>
                    </div>     

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Lokasi</label>
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

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">&nbsp;</label>
                        </div> 
                        <div class="col-lg-9">
                            <textarea class="form-control" name="alamat" rows="3" placeholder="Alamat" required></textarea>
                        </div>
                    </div>    

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Luas Ruangan Test (m2)</label> 
                        </div>
                        <div class="col-lg-4">
                            <input type="number" class="form-control" name="panjang_ruangan_test" placeholder="Panjang" required>
                        </div>
                        <div class="col-lg-4">
                            <input type="number" class="form-control" name="lebar_ruangan_test" placeholder="Lebar" required>
                        </div>
                    </div>                

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Luas Ruangan Tunggu (m2)</label> 
                        </div>
                        <div class="col-lg-4">
                            <input type="number" class="form-control" name="panjang_ruangan_tunggu" placeholder="Panjang" required>
                        </div>
                        <div class="col-lg-4">
                            <input type="number" class="form-control" name="lebar_ruangan_tunggu" placeholder="Lebar" required>
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
				url: "<?php echo site_url('manajemen_data/lokasi_skd/get_regency');?>",
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
				url: "<?php echo site_url('manajemen_data/lokasi_skd/get_district');?>",
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
				url: "<?php echo site_url('manajemen_data/lokasi_skd/get_village');?>",
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
    })
</script>