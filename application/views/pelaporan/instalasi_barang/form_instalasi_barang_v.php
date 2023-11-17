<!-- select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jquery validate-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<form action="<?php echo site_url('pelaporan/instalasi_barang/export'); ?>" method="post" id="basic-form" enctype="multipart/form-data" target="_blank">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Laporan Penerimaan dan Instalasi/Pemasangan Barang</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Kode Penerimaan</label>
                        <div class="col-md-8">
                            <select class="select-single" name="penerimaan_id" id="penerimaan_id" required>
                                <option value="">Pilih Penerimaan</option>
                                <?php if (isset($get_penerimaan)) : ?>
                                    <?php foreach ($get_penerimaan as $k => $v) : ?>
                                        <option value="<?php echo $v['id']; ?>"><?php echo $v['kode_penerimaan'] . ' | ' . $v['nama_lokasi'] . '('. $v['alamat'] .') ' . $v['regency_name']; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Tanggal Terima</label>
                        </div>
                        <div class="col-lg-3">
                            <input type="date" class="form-control" name="tgl_terima" id="tgl_terima" placeholder="Tanggal Terima" required>
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
                        <button id="btnDownload" type="button" class="btn btn-danger">Download</button>
                        <button type="submit" class="btn btn-primary">Preview</button>
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

        $("#btnDownload").click(function() {
            let tgl_terima = $("#tgl_terima").val();
            let penerimaan_id = $("#penerimaan_id").val();
            if (tgl_terima && penerimaan_id) {
                window.location.href = '<?php echo site_url("pelaporan/instalasi_barang/download"); ?>?penerimaan_id=' + penerimaan_id + '&tgl_terima=' + tgl_terima
            } else {
                alert('Kode penerimmaan dan tanggal terima harap di isi!')
            }
        })
    })
</script>