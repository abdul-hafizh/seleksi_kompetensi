<!-- select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jquery validate-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<form action="<?php echo site_url('perencanaan/submit_update'); ?>" method="post" id="basic-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tambah Data Perencanaan</h5>
                </div>
                <div class="card-body">       
                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Kode Perencanaan</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?php echo $get_perencanaan['kode_perencanaan']; ?>" readonly>
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
                                    <option value="<?php echo $v['location_id']; ?>" <?php echo $get_perencanaan['province_id'] == $v['location_id'] ? "selected" : "" ?>><?php echo $v['province_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <select class="select-single" name="kabupaten" id="kabupaten" required>
                                <option value="">Pilih Kabupaten</option>
                                <?php foreach($get_kabupaten as $v) { ?>
                                    <option value="<?php echo $v['location_id']; ?>" <?php echo $get_perencanaan['regency_id'] == $v['location_id'] ? "selected" : "" ?>><?php echo $v['regency_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>                
                    
                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Lokasi</label>
                        <div class="col-md-8">
                            <select class="select-single" name="kode_lokasi_skd" id="kode_lokasi_skd" required>
                                <option value="">Pilih Lokasi</option>
                                <?php foreach($get_lokasi as $v) { ?>
                                    <option value="<?php echo $v['id']; ?>" <?php echo $get_perencanaan['kode_lokasi_skd'] == $v['id'] ? "selected" : "" ?> ><?php echo $v['kode_lokasi'] . ' | ' . $v['nama_lokasi']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Catatan</label>
                        </div>
                        <div class="col-lg-8">
                            <textarea class="form-control" name="catatan" rows="3" placeholder="Catatan"><?php echo $get_perencanaan['catatan']; ?></textarea>
                            <input type="hidden" name="perencanaan_id" value="<?php echo $get_perencanaan['id']; ?>" readonly>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Data Barang</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3 p-3">
                        <table id="data-form" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Jenis Alat</th>
                                    <th>Satuan</th>
                                    <th>Jumlah</th>
                                    <th>Foto</th>
                                    <th>Upload Foto</th>
                                    <th style="display:none">Barang ID </th>
                                    <th style="display:none">Detail ID </th>
                                    <th style="display:none">Foto ID </th>
                                </tr>
                            </thead>
                            <tbody id="show-barang">
                                <?php $no = 1; foreach($get_detail as $v) { ?>
                                <tr>
                                    <td><?php echo $no++;?></td>
                                    <td><?php echo $v['kode_barang_id'];?></td>
                                    <td><?php echo $v['nama_barang'];?></td>
                                    <td><?php echo $v['jenis_alat'];?></td>
                                    <td><?php echo $v['satuan'];?></td>
                                    <td><input id="jumlah" name="jumlah[]" type="number" min="0" class="form-control" placeholder="Jumlah" value="<?php echo $v['jumlah'];?>" required></td>
                                    <td>
                                        <div class="avatar-group">
                                            <a href="<?php echo base_url('uploads/perencanaan/' . $v['foto_barang']); ?>" target="_blank" class="avatar-group-item" data-img="<?php echo base_url('uploads/perencanaan/' . $v['foto_barang']); ?>" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Foto Barang">
                                                <img src="<?php echo base_url('uploads/perencanaan/' . $v['foto_barang']); ?>" alt="" class="rounded-circle avatar-xxs">
                                            </a>
                                        </div>
                                    </td>
                                    <td><input id="foto_barang" name="foto_barang[]" type="file" class="form-control"></td>
                                    <td><input id="barang_id" name="barang_id[]" type="hidden" value="<?php echo $v['barang_id'];?>"></td>
                                    <td><input id="detail_id" name="detail_id[]" type="hidden" value="<?php echo $v['id'];?>"></td>
                                    <td><input id="foto_exist" name="foto_exist[]" type="hidden" value="<?php echo $v['foto_barang'];?>"></td>
                                </tr>
                                <?php } ?>
                            </tbody>                            
                        </table>                    
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
				url: "<?php echo site_url('perencanaan/get_lokasi');?>",
				data: { kabupaten: kabupaten },
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

        $("#kode_lokasi_skd").on("change", function () {
            var url = window.location.href;
            var segments = url.split('/');
            var id_url = segments.pop();

            var base_url = "<?php echo base_url('uploads/perencanaan/'); ?>";

            $.ajax({
                url: "<?php echo site_url('perencanaan/get_detail/');?>" + id_url,
                method: "GET",
                dataType: "json",
                success: function (data) {
                    var rows = '';

                    $.each(data, function (i, item) {
                        rows += '<tr>';
                        rows += '<td>' + (i + 1) + '</td>';
                        rows += '<td>' + item.kode_barang_id + '</td>';
                        rows += '<td>' + item.nama_barang + '</td>';
                        rows += '<td>' + item.jenis_alat + '</td>';
                        rows += '<td>' + item.satuan + '</td>';
                        rows += '<td><input id="jumlah" name="jumlah[]" type="number" min="0" class="form-control" placeholder="Jumlah" required></td>';
                        rows += '<td>';
                        rows += '<div class="avatar-group">';
                        rows += '<a href="' + base_url + item.foto_barang + '" target="_blank" class="avatar-group-item" data-img="' + base_url + item.foto_barang + '" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Foto Barang">';
                        rows += '<img src="' + base_url + item.foto_barang + '" alt="" class="rounded-circle avatar-xxs">';
                        rows += '</a>';
                        rows += '</div>';
                        rows += '</td>';
                        rows += '<td><input id="foto_barang" name="foto_barang[]" type="file" class="form-control"></td>';
                        rows += '<td style="display:none"><input id="barang_id" name="barang_id[]" type="number" value="' + item.id + '"></td>';
                        rows += '<td style="display:none"><input id="foto_exist" name="foto_exist[]" type="text" value="' + item.foto_barang + '"></td>';
                        rows += '</tr>';
                    });

                    $('#show-barang').html(rows);
                },
                error: function (xhr, status, error) {
                    alert('Gagal ambil data barang.');
                },
            });
        });

    })
</script>