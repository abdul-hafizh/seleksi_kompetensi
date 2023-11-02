<!-- select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jquery validate-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<form action="<?php echo site_url('manajemen_data/jadwal_kegiatan/submit_data'); ?>" method="post" id="basic-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tambah Data Jadwal Kegiatan</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Status Kegiatan</label>
                        </div>
                        <div class="col-lg-3">       
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status_kegiatan" value="Aktif" required>
                                <label class="form-check-label">Aktif</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status_kegiatan" value="Tidak Aktif" required>
                                <label class="form-check-label">Tidak Aktif</label>
                            </div>
                        </div>
                    </div>    

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Nama Kegiatan</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="text" class="form-control" name="nama_kegiatan" placeholder="Nama Kegiatan" required>
                        </div>
                    </div>     

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Tanggal Mulai/Selesai</label>
                        </div>
                        <div class="col-lg-4">
                            <input type="date" class="form-control" name="tgl_mulai" title="Tanggal Mulai" required>
                        </div>
                        <div class="col-lg-4">
                            <input type="date" class="form-control" name="tgl_selesai" title="Tanggal Selesai" required>                            
                        </div>
                    </div>                

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Tahun</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="number" class="form-control" min="2010" max="9999" name="tahun" placeholder="Tahun Kegiatan" required>
                        </div>
                    </div>     

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Lokasi Kegiatan</label>
                        </div>
                        <div class="col-lg-4">
                            <select class="select-single" name="lokasi_skd_id" id="lokasi_skd_id" required>
                                <option value="">Pilih Lokasi</option>
                                <?php foreach($get_lokasi as $v) { ?>
                                    <option value="<?php echo $v['id']; ?>"><?php echo $v['province_name'] . ' | ' . $v['regency_name'] . ' | ' . $v['nama_lokasi'] . ' (' . $v['kode_lokasi'] . ')'; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>                

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Catatan</label>
                        </div>
                        <div class="col-lg-9">
                            <textarea class="form-control" name="catatan" rows="3" placeholder="Catatan"></textarea>
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