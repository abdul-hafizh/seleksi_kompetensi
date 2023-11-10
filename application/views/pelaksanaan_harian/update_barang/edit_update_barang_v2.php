<!-- select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Tanggal Update</label>
                        </div>
                        <div class="col-lg-3">
                            <input type="date" class="form-control" name="tgl_update" placeholder="Tanggal Update" value="<?= isset($get_update_barang) && isset($get_update_barang['tgl_update_harian']) ? $get_update_barang['tgl_update_harian'] : ''; ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Catatan</label>
                        </div>
                        <div class="col-lg-8">
                            <textarea class="form-control" name="catatan" rows="3" placeholder="Catatan"><?= isset($get_update_barang) && isset($get_update_barang['catatan']) ? $get_update_barang['catatan'] : ''; ?></textarea>
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
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Status Ada</th>
                                    <th>Status Tidak Ada</th>
                                    <th>Kondisi Baik</th>
                                    <th>Kondisi Tidak Baik</th>
                                    <th>Foto Terima </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($get_update_barang_detail) && count($get_update_barang_detail) > 0) : ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($get_update_barang_detail as $k => $v) : ?>
                                        <tr>
                                            <td><?= $no; ?></td>
                                            <td><?= $v["kode_barang_id"]; ?></td>
                                            <td><?= $v["nama_barang"]; ?></td>
                                            <td><?= $v["jumlah_barang"]; ?></td>
                                            <td><?= $v["satuan"]; ?></td>
                                            <td>
                                                <input name="status_ada#<?= $v['barang_id'] ?>" type="number" min="0" value="<?= isset($v['jumlah_barang_status_ada']) ? $v['jumlah_barang_status_ada'] : 0 ?>" class="form-control" placeholder="Jumlah" required>
                                            </td>
                                            <td>
                                                <input name="status_tidak_ada#<?= $v['barang_id'] ?>" type="number" min="0" value="<?= isset($v['jumlah_barang_status_tidak_ada']) ? $v['jumlah_barang_status_tidak_ada'] : 0 ?>" class="form-control" placeholder="Jumlah" required>
                                            </td>
                                            <td>
                                                <input name="kondisi_baik#<?= $v['barang_id'] ?>" type="number" min="0" value="<?= isset($v['jumlah_barang_kondisi_baik']) ? $v['jumlah_barang_kondisi_baik'] : 0 ?>" class="form-control" placeholder="Jumlah" required>
                                            </td>
                                            <td>
                                                <input name="kondisi_tidak_baik#<?= $v['barang_id'] ?>" type="number" min="0" value="<?= isset($v['jumlah_barang_kondisi_rusak']) ? $v['jumlah_barang_kondisi_rusak'] : 0 ?>" class="form-control" placeholder="Jumlah" required>
                                            </td>
                                            <td>
                                                <input name="foto_barang#<?= $v['barang_id'] ?>" type="file" class="form-control">
                                            </td>
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

<!--select2 cdn-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $(".select-single").select2();

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
    })
</script>