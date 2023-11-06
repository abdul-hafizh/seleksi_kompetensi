<!--select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<?php
    $pesan = $this->session->userdata('message');
    $pesan = (empty($pesan)) ? "" : $pesan;
    if(!empty($pesan)){ ?>
    <div class="alert bg-light-danger alert-dismissible">
        <?php echo $pesan ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ft-x font-medium-2 text-bold-700"></i></span>
        </button>
    </div>
<?php } $this->session->unset_userdata('message'); ?>

<div class="row">
    <div class="col-lg-8 col-12">
        <div class="card">
            <div class="card-header border-bottom pb-2">
                <h4 class="card-title">Form Edit Data SDM</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form class="form-bordered" method="post" action="<?php echo site_url('manajemen_user/users/submit_update');?>" enctype="multipart/form-data">
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Posisi</label>
                            <div class="col-md-9">
                                <select class="select-single" name="employee_pos_id" id="posisi" required>
                                    <option value="" disabled selected>Pilih Posisi</option>
                                    <?php foreach($get_pos as $v) { ?>
                                        <option value="<?php echo $v['pos_id'];?>" <?php echo $get_employee['adm_pos_id'] == $v['pos_id'] ? "selected" : "" ?>><?php echo $v['pos_name'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Nama Lengkap</label>
                            <div class="col-md-9">
                                <input type="hidden" name="id" value="<?php echo $get_employee['id'];?>">  
                                <input type="text" class="form-control col-lg-7" name="fullname" value="<?php echo $get_employee['fullname'];?>" required>                                
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">NIK</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control col-lg-7" name="nik" value="<?php echo $get_employee['nik'];?>" required>                                
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Upload Foto KTP</label>
                            <div class="col-md-9">
                                <input type="file" class="form-control col-lg-7" name="file_ktp" placeholder="Foto KTP">
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <div class="col-lg-3">
                                <label class="form-label label-control">&nbsp;</label>
                            </div>
                            <div class="col-lg-2">
                                <div class="d-inline-flex gap-2 border border-dashed p-2 mb-2 w-75">
                                    <a href="<?php echo base_url('uploads/users/' . $get_employee['file_ktp']); ?>" target="_blank" class="bg-light rounded p-1">
                                        <img src="<?php echo base_url('uploads/users/' . $get_employee['file_ktp']); ?>" alt="" class="img-fluid d-block" />
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Email</label>
                            <div class="col-md-9">
                                <input type="email" class="form-control col-lg-7" name="email" value="<?php echo $get_employee['email'];?>">                                
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Phone</label>
                            <div class="col-md-9">
                                <input type="text" maxlength="25" class="form-control col-lg-7" name="phone" onkeypress="return onlyNumber(event)" value="<?php echo $get_employee['phone'];?>" required>                                
                            </div>
                        </div>   
                        <div class="form-group row mb-3">
                            <label class="col-md-3 label-control">Alamat</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="alamat" rows="3" placeholder="Alamat" required><?php echo $get_employee['alamat'];?></textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Status</label>
                            <div class="col-md-9">
                                <select class="select-single" name="status" required>
                                    <option value="">Pilih Status</option>
                                    <option value="1" <?php echo $get_employee['status'] == 1 ? "selected" : "" ?>>Tidak Aktif</option>
                                    <option value="2" <?php echo $get_employee['status'] == 2 ? "selected" : "" ?>>Aktif</option>
                                </select>
                            </div>
                        </div> 
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Provinsi</label>
                            <div class="col-md-9">
                                <select class="select-single" name="provinsi" id="provinsi" required>
                                    <option value="">Pilih Provinsi</option>
                                    <?php foreach($get_provinsi as $v) { ?>
                                        <option value="<?php echo $v['location_id']; ?>" <?php echo $get_employee['province_id'] == $v['location_id'] ? "selected" : "" ?>><?php echo $v['province_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Kabupaten</label>
                            <div class="col-md-9">
                                <select class="select-single" name="kabupaten" id="kabupaten">
                                    <option value="">Pilih Kabupaten</option>
                                    <?php foreach($get_kabupaten as $v) { ?>
                                        <option value="<?php echo $v['location_id']; ?>" <?php echo $get_employee['regency_id'] == $v['location_id'] ? "selected" : "" ?>><?php echo $v['regency_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row last mb-2">
                            <label class="col-md-3 label-control">Titik Lokasi</label>
                            <div class="col-md-9">
                                <select class="select-single" name="lokasi_skd_id" id="lokasi_skd_id" required>
                                    <option value="">Pilih Lokasi</option>
                                    <?php foreach($get_lokasi as $v) { ?>
                                        <option value="<?php echo $v['id']; ?>"><?php echo $v['kode_lokasi'] . ' | ' . $v['nama_lokasi']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>                        
                        <div class="text-right">
                            <a href="<?php echo site_url('employee');?>" class="btn btn-secondary"><i class="ft-chevrons-left mr-1"></i>Kembali</a>
                            <button type="submit" class="btn btn-info" onclick="return confirm('Apakah Anda yakin simpan data ini?');"><i class="ft-check-square mr-1"></i>Update</button>
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
				url: "<?php echo site_url('manajemen_user/users/get_lokasi');?>",
				data: { kabupaten: kabupaten },
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