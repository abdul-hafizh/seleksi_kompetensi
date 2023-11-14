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

<form action="<?php echo site_url('instalasi_barang/submit_update'); ?>" method="POST" id="basic-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Data Penerimaan</h5>
                </div>
                <div class="card-body">                    
                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Kode Penerimaan</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?php echo $get_penerimaan['kode_penerimaan']; ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Pengiriman</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?php echo $get_penerimaan['province_name'] . ' | ' . $get_penerimaan['regency_name'] . ' | ' . $get_penerimaan['nama_lokasi'] . ' (' . $get_penerimaan['kode_lokasi'] . ')'; ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Tanggal Terima</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="date" class="form-control" name="tgl_terima" placeholder="Tanggal Terima" value="<?php echo $get_penerimaan['tgl_terima']; ?>" required>
                        </div>
                    </div>

                    <?php if(!$get_role) { ?>
                        <div class="row mb-3">
                            <div class="col-lg-2">
                                <label class="form-label">Status</label>
                            </div>
                            <div class="col-lg-3">       
                                <select class="form-control" name="status" required>
                                    <option value="Pending" <?php echo $get_penerimaan['status'] == 'Pending' ? ' selected' : ''; ?> >Pending</option>
                                    <option value="Approved" <?php echo $get_penerimaan['status'] == 'Approved' ? ' selected' : ''; ?>>Approved</option>
                                </select>
                            </div>
                        </div>    
                    <?php } else { ?>
                        <input type="hidden" name="status" value="<?php echo $get_penerimaan['status']; ?>" readonly>
                    <?php } ?>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Catatan</label>
                        </div>
                        <div class="col-lg-8">
                            <input type="hidden" name="id" value="<?php echo $get_penerimaan['id']; ?>" readonly>
                            <input type="hidden" name="pengiriman_id" value="<?php echo $get_penerimaan['pengiriman_id']; ?>" readonly>
                            <textarea class="form-control" name="catatan" rows="3" placeholder="Catatan"><?php echo $get_penerimaan['catatan']; ?></textarea>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>

    <?php if($job_title == 'KOORDINATOR') { ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Data Barang</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3 p-3">
                        <table id="data-form" class="table table-bordered dt-responsive table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th style="width:400px">Nama Barang</th>
                                    <th>Satuan</th>
                                    <th>Jumlah Kirim</th>
                                    <th>Jumlah Terima</th>
                                    <th style="display:none">Jumlah Rusak</th>
                                    <th>Jumlah Terpasang</th>
                                    <th>Foto Terima </th>
                                    <th>Foto Terpasang </th>
                                    <th>Preview</th>
                                    <th style="display:none">Barang ID </th>
                                    <th style="display:none">Detail ID </th>
                                    <th style="display:none">Foto ID </th>
                                </tr>
                            </thead>
                            <tbody id="show-barang">
                                <?php $no=1; foreach($get_detail as $v) { ?>
                                    <tr>
                                        <td class="text-center"><?php echo $no;?></td>
                                        <td><?php echo $v['kode_barang_id'];?></td>
                                        <td><?php echo $v['nama_barang'];?></td>
                                        <td><?php echo $v['satuan'];?></td>
                                        <td><?php echo $v['jumlah_kirim'];?></td>
                                        <td><input id="jumlah_terima" name="jumlah_terima[]" type="number" min="0" class="form-control" placeholder="Jumlah Terima" value="<?php echo $v['jumlah_terima'];?>" readonly></td>
                                        <td style="display:none"><input id="jumlah_rusak" name="jumlah_rusak[]" type="number" min="0" class="form-control" placeholder="Jumlah Rusak" value="<?php echo $v['jumlah_rusak'];?>" readonly></td>
                                        <td><input id="jumlah_terpasang" name="jumlah_terpasang[]" type="number" min="0" class="form-control" placeholder="Jumlah Terpasang" value="<?php echo $v['jumlah_terpasang'];?>"></td>
                                        <td>
                                            <div class="avatar-group">
                                                <?php
                                                    $foto_barang = $v['foto_barang'];
                                                    $image_url = base_url('uploads/penerimaan_barang/' . $foto_barang);
                                                    if (empty($foto_barang)) { $image_url = base_url('assets/images/noimage.jpeg'); }
                                                ?>
                                                <a href="<?php echo $image_url; ?>" target="_blank" class="avatar-group-item" data-img="<?php echo $image_url; ?>" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Foto Barang">
                                                    <img src="<?php echo $image_url; ?>" alt="" class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <input id="foto_barang_terpasang" name="foto_barang_terpasang[]" type="file" class="form-control" data-row="<?php echo $no;?>">
                                                </div>
                                                <?php if(isset($v['foto_barang_terpasang'])) { ?>
                                                    <div class="col-md-2">
                                                        <?php
                                                            $foto_barang_install = $v['foto_barang_terpasang'];
                                                            $image_url_install = base_url('uploads/instalasi_barang/' . $foto_barang_install);
                                                            if (empty($foto_barang_install)) { $image_url_install = base_url('assets/images/noimage.jpeg'); }
                                                        ?>
                                                        <div class="avatar-group">
                                                            <a href="<?php echo $image_url_install; ?>" target="_blank" class="avatar-group-item" data-img="<?php echo $image_url_install; ?>" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Foto Barang">
                                                                <img src="<?php echo $image_url_install; ?>" alt="" class="rounded-circle avatar-xxs">
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </td>
                                        <td class="image-preview-container"><img class="image-preview" src="<?php echo base_url('assets/images/noimage.jpeg'); ?>" alt="Image Preview" style="max-width: 50px; max-height: 50px;"></td>
                                        <td style="display:none"><input id="barang_id" name="barang_id[]" type="hidden" value="<?php echo $v['barang_id'];?>"></td>
                                        <td style="display:none"><input id="detail_id" name="detail_id[]" type="hidden" value="<?php echo $v['id'];?>"></td>
                                        <td style="display:none"><input id="foto_terpasang_exist" name="foto_terpasang_exist[]" type="hidden" value="<?php echo $v['foto_barang_terpasang'];?>"></td>
                                    </tr>
                                <?php $no++; } ?>
                            </tbody>                            
                        </table>                    
                    </div>                      
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

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

<script>    
    $(document).ready(function () {   
        $("#basic-form").validate({
            invalidHandler: function(event, validator) {            
                var errors = validator.numberOfInvalids();
                if (errors) { window.scrollTo({top: 0}); }
            }
        });

        $("#show-barang").on("change", 'input[name="foto_barang_terpasang[]"]', function () {
            var fileInput = $(this);
            var rowIndex = fileInput.data("row");
            var imagePreview = fileInput.closest('tr').find('.image-preview');

            if (fileInput[0].files && fileInput[0].files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    imagePreview.attr('src', e.target.result);
                };

                reader.readAsDataURL(fileInput[0].files[0]);
            } else {
                imagePreview.attr('src', '');
            }
        });
    })
</script>