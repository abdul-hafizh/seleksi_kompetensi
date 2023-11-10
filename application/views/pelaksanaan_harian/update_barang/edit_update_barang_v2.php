<!-- jquery validate-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<form action="<?php echo site_url('pelaksanaan_harian/update_barang/update/' . $id); ?>" method="post" id="basic-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detail Update Barang</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Titik Lokasi</label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" value="<?php echo $get_penerimaan['nama_lokasi'] . ' - ' . $get_penerimaan['alamat']; ?>" readonly>
                            <input type="hidden" name="penerimaan_id" value="<?php echo $get_penerimaan['id']; ?>" readonly>
                            <input type="hidden" name="update_barang_id" value="<?php echo $get_update_barang['id']; ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Tanggal Update</label>
                        </div>
                        <div class="col-lg-3">
                            <input type="date" class="form-control" name="tgl_update" placeholder="Tanggal Update" value="<?php echo isset($get_update_barang) && isset($get_update_barang['tgl_update_harian']) ? $get_update_barang['tgl_update_harian'] : ''; ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Catatan</label>
                        </div>
                        <div class="col-lg-8">
                            <textarea class="form-control" name="catatan" rows="3" placeholder="Catatan"><?php echo isset($get_update_barang) && isset($get_update_barang['catatan']) ? $get_update_barang['catatan'] : ''; ?></textarea>
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
                        <table id="data-form" class="table table-bordered dt-responsive table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th style="200px">Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Status Ada</th>
                                    <th>Status Tidak Ada</th>
                                    <th>Kondisi Baik</th>
                                    <th>Kondisi Tidak Baik</th>
                                    <th>Foto Uji </th>
                                    <th>Ubah Foto </th>
                                    <th>Preview</th>
                                    <th style="display:none">Barang ID </th>
                                    <th style="display:none">Detail ID </th>
                                    <th style="display:none">Foto ID </th>
                                </tr>
                            </thead>
                            <tbody id="show-barang">
                                <?php if (isset($get_update_barang_detail) && count($get_update_barang_detail) > 0) : ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($get_update_barang_detail as $k => $v) : ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo $v["kode_barang_id"]; ?></td>
                                            <td><?php echo $v["nama_barang"]; ?></td>
                                            <td><?php echo $v["jumlah_barang"]; ?></td>
                                            <td><?php echo $v["satuan"]; ?></td>
                                            <td>
                                                <input name="status_ada[]" type="number" min="0" value="<?php echo isset($v['jumlah_barang_status_ada']) ? $v['jumlah_barang_status_ada'] : 0 ?>" class="form-control" placeholder="Jumlah" required>
                                            </td>
                                            <td>
                                                <input name="status_tidak_ada[]" type="number" min="0" value="<?php echo isset($v['jumlah_barang_status_tidak_ada']) ? $v['jumlah_barang_status_tidak_ada'] : 0 ?>" class="form-control" placeholder="Jumlah" required>
                                            </td>
                                            <td>
                                                <input name="kondisi_baik[]" type="number" min="0" value="<?php echo isset($v['jumlah_barang_kondisi_baik']) ? $v['jumlah_barang_kondisi_baik'] : 0 ?>" class="form-control" placeholder="Jumlah" required>
                                            </td>
                                            <td>
                                                <input name="kondisi_tidak_baik[]" type="number" min="0" value="<?php echo isset($v['jumlah_barang_kondisi_rusak']) ? $v['jumlah_barang_kondisi_rusak'] : 0 ?>" class="form-control" placeholder="Jumlah" required>
                                            </td>
                                            <td>
                                                <div class="avatar-group">
                                                    <?php
                                                        $foto_barang = $v['foto_barang'];
                                                        $image_url = base_url('uploads/update_barang/' . $foto_barang);
                                                        if (empty($foto_barang)) { $image_url = base_url('assets/images/noimage.jpeg'); }
                                                    ?>
                                                    <a href="<?php echo $image_url; ?>" target="_blank" class="avatar-group-item" data-img="<?php echo $image_url; ?>" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Foto Barang">
                                                        <img src="<?php echo $image_url; ?>" alt="" class="rounded-circle avatar-xxs">
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <input id="foto_barang" name="foto_barang[]" type="file" class="form-control" data-row="<?php echo $no;?>">
                                            </td>
                                            <td class="image-preview-container"><img class="image-preview" src="<?php echo base_url('assets/images/noimage.jpeg'); ?>" alt="Image Preview" style="max-width: 50px; max-height: 50px;"></td>
                                            <td style="display:none"><input id="barang_id" name="barang_id[]" type="hidden" value="<?php echo $v['barang_id'];?>"></td>
                                            <td style="display:none"><input id="detail_id" name="detail_id[]" type="hidden" value="<?php echo $v['id'];?>"></td>
                                            <td style="display:none"><input id="foto_exist" name="foto_exist[]" type="hidden" value="<?php echo $v['foto_barang'];?>"></td>
                                        </tr>
                                        <?php $no++ ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
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

<script>
    $(document).ready(function() {
        $("#basic-form").validate({
            invalidHandler: function(event, validator) {
                var errors = validator.numberOfInvalids();
                if (errors) {
                    window.scrollTo({
                        top: 0
                    });
                }
            }
        });

        $("#show-barang").on("change", 'input[name="foto_barang[]"]', function () {
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