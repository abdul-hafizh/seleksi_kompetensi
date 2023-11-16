<!--select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="row">
    <div class="col-lg-8 col-12">
        <div class="card">
            <div class="card-header border-bottom pb-2">
                <h4 class="card-title">Form Pengisian SDM</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form class="form-bordered" method="post" action="<?php echo site_url('manajemen_user/users/submit');?>" enctype="multipart/form-data">
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
                        <div class="form-group row mb-3">
                            <label class="col-md-3 label-control">Alamat</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="alamat" rows="3" placeholder="Alamat" placeholder="Alamat" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Status</label>
                            <div class="col-md-9">
                                <select class="select-single" name="status" required>
                                    <option value="">Pilih Status</option>
                                    <option value="1">Tidak Aktif</option>
                                    <option value="2" selected>Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-2" id="div-provinsi">
                            <label class="col-md-3 label-control">Provinsi</label>
                            <div class="col-md-9">
                                <select class="select-single" name="provinsi" id="provinsi">
                                    <option value="">Pilih Provinsi</option>
                                    <?php foreach($get_provinsi as $v) { ?>
                                        <option value="<?php echo $v['location_id']; ?>"><?php echo $v['province_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-2" id="div-kabupaten">
                            <label class="col-md-3 label-control">Kabupaten</label>
                            <div class="col-md-9">
                                <select class="select-single" name="kabupaten" id="kabupaten" disabled>
                                    <option value="">Pilih Kabupaten</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group last row mb-2" id="div-lokasi">
                            <label class="col-md-3 label-control">Titik Lokasi</label>
                            <div class="col-md-9">
                                <select class="select-single" name="lokasi_skd_id" id="lokasi_skd_id" disabled>
                                    <option value="">Pilih Lokasi</option>
                                </select>
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

        $("#posisi").on("change", function () {
            var selectedPosisi = $(this).val();
            
            // Jika posisi adalah ADMIN
            if (selectedPosisi === "3" || selectedPosisi === "4" || selectedPosisi === "5") {
                $("#div-provinsi").show();
                $("#provinsi").prop("required", true); 
                $("#provinsi").val('');

                $("#div-kabupaten").show();
                $("#kabupaten").prop("required", true); 
                $("#kabupaten").val('');

                $("#div-lokasi").show();
                $("#lokasi_skd_id").prop("required", true); 
                $("#lokasi_skd_id").val('');
            } 

            // Jika posisi adalah PUSAT
            else if (selectedPosisi === "2") {
                $("#div-provinsi").hide();
                $("#provinsi").prop("required", false); 
                $("#provinsi").val('');

                $("#div-kabupaten").hide();
                $("#kabupaten").prop("required", false); 
                $("#kabupaten").val('');

                $("#div-lokasi").hide();
                $("#lokasi_skd_id").prop("required", false); 
                $("#lokasi_skd_id").val('');
            } 

            // Jika posisi adalah SUPERVISOR/REGIONAL
            else if (selectedPosisi === "6") {
                $("#div-provinsi").show();
                $("#provinsi").prop("required", true); 
                $("#provinsi").val('');

                $("#div-kabupaten").show();
                $("#kabupaten").prop("required", true); 
                $("#kabupaten").val('');

                $("#div-lokasi").hide();
                $("#lokasi_skd_id").prop("required", false); 
                $("#lokasi_skd_id").val('');
            }
        });

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
				url: "<?php echo site_url('manajemen_user/users/get_lokasi');?>",
				data: { kabupaten: kabupaten },
				method: "POST",
				dataType: "json",
				success: function (data) {
					lokasi_skd_id = '<option value="">Pilih Lokasi</option>';
					$.each(data, function (i, item) {   
						lokasi_skd_id += '<option value="' + item.id +'">' + item.province_name + ' | ' + item.regency_name + ' | ' + item.kode_lokasi + ' | ' + item.nama_lokasi + "</option>";
					});
					$("#lokasi_skd_id").html(lokasi_skd_id).removeAttr("disabled");
				},
			});
		});        
    })
    
</script>