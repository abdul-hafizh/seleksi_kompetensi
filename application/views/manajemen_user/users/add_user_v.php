<!--select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="row">
    <div class="col-lg-8 col-12">
        <div class="card">
            <div class="card-header border-bottom pb-2">
                <h4 class="card-title">Form Pengisian User</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form class="form-bordered" method="post" action="<?php echo site_url('manajemen_user/users/submit');?>">                        
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Posisi</label>
                            <div class="col-md-9">
                                <select class="select-single" name="employee_pos_id" id="posisi" required>
                                    <option value="" disabled selected>Pilih Posisi</option>
                                    <?php foreach($get_pos as $v) { ?>
                                        <option value="<?php echo $v['pos_id'];?>"><?php echo $v['pos_name'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Nama Lengkap</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control col-lg-7" name="fullname" placeholder="Nama Lengkap" required>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">NIK</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control col-lg-7" name="nik" placeholder="NIK" required>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Upload Foto KTP</label>
                            <div class="col-md-9">
                                <input type="file" class="form-control col-lg-7" name="file_ktp" placeholder="Foto KTP">
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Email</label>
                            <div class="col-md-9">
                                <input type="email" class="form-control col-lg-7" name="email" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Phone</label>
                            <div class="col-md-9">
                                <input type="text" maxlength="25" class="form-control col-lg-7" name="phone" onkeypress="return onlyNumber(event)" placeholder="Nomor HP" required>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Status</label>
                            <div class="col-md-9">
                                <select class="select-single" name="status" required>
                                    <option value="">Pilih Status</option>
                                    <option value="1">Tidak Aktif</option>
                                    <option value="2">Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Provinsi</label>
                            <div class="col-md-9">
                                <select class="select-single" name="provinsi" id="provinsi" required>
                                    <option value="">Pilih Provinsi</option>
                                    <?php foreach($get_provinsi as $v) { ?>
                                        <option value="<?php echo $v['location_id']; ?>"><?php echo $v['province_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Kabupaten</label>
                            <div class="col-md-9">
                                <select class="select-single" name="kabupaten" id="kabupaten" disabled required>
                                    <option value="">Pilih Kabupaten</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Kecamatan</label>
                            <div class="col-md-9">
                                <select class="select-single" name="kecamatan" id="kecamatan" disabled required>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Desa</label>
                            <div class="col-md-9">
                                <select class="select-single" name="desa" id="desa" disabled required>
                                    <option value="">Pilih Desa</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Lokasi</label>
                            <div class="col-md-9">
                                <select class="select-single" name="lokasi_skd_id" id="lokasi_skd_id" disabled required>
                                    <option value="">Pilih Lokasi</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row last mb-3">
                            <label class="col-md-3 label-control">Alamat</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="alamat" rows="3" placeholder="Alamat" placeholder="Alamat" required></textarea>
                            </div>
                        </div>
                        <div class="text-right">
                            <a href="<?php echo site_url('manajemen_user/users');?>" class="btn btn-secondary"><i class="ft-chevrons-left mr-1"></i>Kembali</a>
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
    $(document).ready(function () {
        $(".select-single").select2();

        $("#provinsi").on("change", function () {
			let provinsi = $("#provinsi").val();
			$.ajax({
				url: "<?php echo site_url('manajemen_user/users/get_regency');?>",
				data: { provinsi: provinsi },
				method: "post",
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
				url: "<?php echo site_url('manajemen_user/users/get_district');?>",
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
				url: "<?php echo site_url('manajemen_user/users/get_village');?>",
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
				url: "<?php echo site_url('manajemen_user/users/get_lokasi');?>",
				data: { desa: desa },
				method: "POST",
				dataType: "json",
				success: function (data) {
					lokasi_skd_id = '<option value="">Pilih Lokasi</option>';
					$.each(data, function (i, item) {   
						lokasi_skd_id += '<option value="' + item.id +'">' + item.kode_lokasi + ' | ' + item.nama_lokasi + "</option>";
					});
					$("#lokasi_skd_id").html(lokasi_skd_id).removeAttr("disabled");
				},
			});
		});
    })
    
</script>