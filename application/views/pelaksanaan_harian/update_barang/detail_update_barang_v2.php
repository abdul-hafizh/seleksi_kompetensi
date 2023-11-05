<!-- select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jquery validate-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<form action="<?php echo site_url('pelaksanaan_harian/update_barang/update/' . $id); ?>" method="post" id="basic-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Update Barang</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Kode Perencanaan</label>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" name="kode_perencanaan" placeholder="Kode Perencanaan" value="<?= isset($get_update_barang) && isset($get_update_barang['kode_perencanaan']) ? $get_update_barang['kode_perencanaan'] : ''; ?>" disabled>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Tanggal Update</label>
                        </div>
                        <div class="col-lg-3">
                            <input type="date" class="form-control" name="tgl_update" placeholder="Tanggal Update" value="<?= isset($get_update_barang) && isset($get_update_barang['tgl_update_harian']) ? $get_update_barang['tgl_update_harian'] : ''; ?>" disabled>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Catatan</label>
                        </div>
                        <div class="col-lg-8">
                            <textarea class="form-control" name="catatan" rows="3" placeholder="Catatan" disabled><?= isset($get_update_barang) && isset($get_update_barang['catatan']) ? $get_update_barang['catatan'] : ''; ?></textarea>
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
                                            <td><?= $v["jumlah_barang_status_ada"]; ?></td>
                                            <td><?= $v["jumlah_barang_status_tidak_ada"]; ?></td>
                                            <td><?= $v["jumlah_barang_kondisi_baik"]; ?></td>
                                            <td><?= $v["jumlah_barang_kondisi_rusak"]; ?></td>
                                            <td>
                                                <a target="_blank" href="<?= base_url('uploads/update_barang/' . $v["foto_barang"]) ?>" class="btn btn-sm btn-warning">Show</a>
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