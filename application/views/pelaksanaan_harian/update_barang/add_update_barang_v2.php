<!-- select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jquery validate-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<form action="<?php echo site_url('pelaksanaan_harian/update_barang/submit_datav2'); ?>" method="post" id="basic-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tambah Update Barang</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Titik Lokasi</label>
                        <div class="col-md-8">
                            <select class="select-single" name="penerimaan_id" id="penerimaan_id" required>
                                <option value="">Pilih Lokasi</option>
                                <?php if (isset($get_penerimaan)) : ?>
                                    <?php foreach ($get_penerimaan as $k => $v) : ?>
                                        <option value="<?php echo $v['id']; ?>"><?php echo $v['kode_penerimaan'] . ' - ' . $v['nama_lokasi'] . ' (' . $v['alamat'] . ', ' . $v['province_name'] . ', ' . $v['regency_name'] . ')'; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Tanggal Update</label>
                        </div>
                        <div class="col-lg-3">
                            <input type="date" class="form-control" name="tgl_update" placeholder="Tanggal Update" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Catatan</label>
                        </div>
                        <div class="col-lg-8">
                            <textarea class="form-control" name="catatan" rows="3" placeholder="Catatan"></textarea>
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
                                    <th>Satuan</th>
                                    <th>Jumlah Terima</th>
                                    <th>Status Ada</th>
                                    <th>Status Tidak Ada</th>
                                    <th>Kondisi Baik</th>
                                    <th>Kondisi Tidak Baik</th>
                                    <th>Foto Update </th>
                                    <th>Preview </th>
                                    <th style="display:none">Barang ID </th>
                                </tr>
                            </thead>
                            <tbody id="show-barang"></tbody>
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

        $("#penerimaan_id").on("change", function() {
            let penerimaan_id = $("#penerimaan_id").val();
            var url_file = '<?php echo base_url('uploads/perencanaan/'); ?>';
            $.ajax({
                url: "<?php echo site_url('pelaksanaan_harian/update_barang/get_penerimaan'); ?>",
                data: {
                    penerimaan_id: penerimaan_id
                },
                method: "POST",
                dataType: "json",
                success: function(data) {
                    var rows = '';

                    $.each(data, function(i, item) {
                        console.log(item);
                        rows += '<tr>';
                        rows += '<td>' + (i + 1) + '</td>';
                        rows += '<td>' + item.kode_barang_id + '</td>';
                        rows += '<td>' + item.nama_barang + '</td>';
                        rows += '<td>' + item.satuan + '</td>';
                        rows += '<td>' + item.jumlah_terima + '</td>';
                        rows += '<td><input id="status_ada[]" name="status_ada[]" type="number" min="0" value="' + item.jumlah_terima + '" class="form-control" placeholder="Jumlah" required></td>';
                        rows += '<td><input id="status_tidak_ada[]" name="status_tidak_ada[]" type="number" min="0" value="0" class="form-control" placeholder="Jumlah" required></td>';
                        rows += '<td><input id="kondisi_baik[]" name="kondisi_baik[]" type="number" min="0" value="' + item.jumlah_terima + '" class="form-control" placeholder="Jumlah" required></td>';
                        rows += '<td><input id="kondisi_tidak_baik[]" name="kondisi_tidak_baik[]" type="number" min="0" value="0" class="form-control" placeholder="Jumlah" required></td>';
                        rows += '<td><input id="foto_barang[]" name="foto_barang[]" type="file" class="form-control" data-row="' + i + '" required></td>';
                        rows += '<td class="image-preview-container"><img class="image-preview" src="<?php echo base_url('assets/images/noimage.jpeg'); ?>" alt="Image Preview" style="max-width: 50px; max-height: 50px;"></td>';
                        rows += '<td style="display:none"><input id="jumlah_barang[]" name="jumlah_barang[]" type="number" value="' + item.jumlah_terima + '"><input id="barang_id[]" name="barang_id[]" type="number" value="' + item.barang_id + '"></td>';
                        rows += '</tr>';
                    });

                    $('#show-barang').html(rows);
                },
                error: function(xhr, status, error) {
                    console.log(error)
                    alert('Gagal ambil data barang.');
                },
            });
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