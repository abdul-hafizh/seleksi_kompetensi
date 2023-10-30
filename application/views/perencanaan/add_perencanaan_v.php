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

<form action="<?php echo site_url('perencanaan/submit_data'); ?>" method="post" id="basic-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tambah Data Perencanaan</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Kode Perencanaan</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="text" class="form-control" name="kode_perencanaan" placeholder="Kode Perencanaan" required>
                        </div>
                    </div>    

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Nama Barang</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="text" class="form-control" name="nama_barang" placeholder="Nama Barang" required>
                        </div>
                    </div>    

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Jenis Barang</label>
                        </div>
                        <div class="col-lg-3">       
                            <select class="select-single" name="jenis_barang" id="jenis_barang" required>
                                <option value="">Pilih Jenis</option>                                
                                <option value="IT">IT</option>                                
                                <option value="Non-IT">Non-IT</option>                                
                            </select>
                        </div>
                    </div>     

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
                            <select class="select-single" name="kode_lokasi_skd" id="kode_lokasi_skd" disabled required>
                                <option value="">Pilih Lokasi</option>
                            </select>
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
				url: "<?php echo site_url('perencanaan/get_regency');?>",
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
				url: "<?php echo site_url('perencanaan/get_district');?>",
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
				url: "<?php echo site_url('perencanaan/get_village');?>",
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
				url: "<?php echo site_url('perencanaan/get_lokasi');?>",
				data: { desa: desa },
				method: "POST",
				dataType: "json",
				success: function (data) {
					kode_lokasi_skd = '<option value="">Pilih Lokasi</option>';
					$.each(data, function (i, item) {   
						kode_lokasi_skd += '<option value="' + item.id +'">' + item.kode_lokasi + ' | ' + item.nama_lokasi + "</option>";
					});
					$("#kode_lokasi_skd").html(kode_lokasi_skd).removeAttr("disabled");
				},
			});
		});
    })
</script>