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
                <h4 class="card-title">Form Edit Data User</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form class="form-bordered" method="post" action="<?php echo site_url('manajemen_user/users/submit_update');?>">                        
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
                                    <?php foreach($provinsi as $v) { ?>
                                        <option value="<?php echo $v['province_name']; ?>" <?php echo $get_employee['provinsi'] == $v['province_name'] ? "selected" : "" ?>><?php echo $v['province_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>                        
                        <div class="form-group row mb-2">
                            <label class="col-md-3 label-control">Kabupaten</label>
                            <div class="col-md-9">
                                <select class="select-single" name="kabupaten" id="kabupaten" disabled>
                                    <option value="">Pilih Kabupaten</option>
                                    <?php foreach($kabupaten as $v) { ?>
                                        <option value="<?php echo $v['regency_name']; ?>" <?php echo $get_employee['kabupaten'] == $v['regency_name'] ? "selected" : "" ?>><?php echo $v['regency_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>                        
                        <div class="form-group row last mb-3">
                            <label class="col-md-3 label-control">Alamat</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="alamat" rows="3" placeholder="Alamat" required><?php echo $get_employee['alamat'];?></textarea>
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
    })
    
</script>