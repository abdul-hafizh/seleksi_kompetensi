<!-- select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jquery validate-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<form action="<?php echo site_url('pelaporan/uji_perangkat/export'); ?>" method="post" id="basic-form" enctype="multipart/form-data" target="_blank">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Laporan Uji Fungsi Perangkat TIK</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Kode Uji Fungsi</label>
                        <div class="col-md-8">
                            <select class="select-single" name="uji_id" id="uji_id" required>
                                <option value="">Pilih Uji Fungsi</option>
                                <?php if (isset($get_uji_fungsi)) : ?>
                                    <?php foreach ($get_uji_fungsi as $k => $v) : ?>
                                        <option value="<?php echo $v['id_uji']; ?>"><?php echo $v['kode_uji'] . ' | ' . $v['nama_lokasi'] . '('. $v['alamat'] .') ' . $v['regency_name']; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
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
                        <button type="submit" class="btn btn-primary">Export PDF</button>
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