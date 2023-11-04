<!-- select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jquery validate-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

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
                            <select class="select-single" name="provinsi" id="provinsi" disabled required>
                                <option value="">Pilih Provinsi</option>
                                <?php foreach($get_provinsi as $v) { ?>
                                    <option value="<?php echo $v['location_id']; ?>" <?php echo $get_perencanaan['province_id'] == $v['location_id'] ? "selected" : "" ?>><?php echo $v['province_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <select class="select-single" name="kabupaten" id="kabupaten" disabled required>
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
                            <select class="select-single" disabled required>
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
                            <input type="hidden" name="kode_lokasi_skd" value="<?php echo $get_perencanaan['kode_lokasi_skd']; ?>" readonly>
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
                                    <td style="display:none"><input id="barang_id" name="barang_id[]" type="hidden" value="<?php echo $v['barang_id'];?>"></td>
                                    <td style="display:none"><input id="detail_id" name="detail_id[]" type="hidden" value="<?php echo $v['id'];?>"></td>
                                    <td style="display:none"><input id="foto_exist" name="foto_exist[]" type="hidden" value="<?php echo $v['foto_barang'];?>"></td>
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
    })
</script>