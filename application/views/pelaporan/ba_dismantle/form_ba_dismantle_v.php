<!-- select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jquery validate-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<form action="<?php echo site_url('pelaporan/ba_dismantle/export'); ?>" method="post" id="basic-form" enctype="multipart/form-data" target="_blank">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pelaporan Berita Acara Dismantle</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Kode Dismantle</label>
                        <div class="col-md-8">
                            <select class="select-single" name="dismantle_id" id="dismantle_id" required>
                                <option value="">Pilih Dismantle</option>
                                <?php if (isset($get_dismantle)) : ?>
                                    <?php foreach ($get_dismantle as $k => $v) : ?>
                                        <option value="<?php echo $v['id']; ?>"><?php echo $v['kode_serah_terima'] . ' | ' . $v['nama_lokasi'] . '('. $v['alamat'] .') ' . $v['regency_name']; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Tanggal Kegiatan</label>
                        </div>
                        <div class="col-lg-3">
                            <input id="tgl_kegiatan" type="date" class="form-control" name="tgl_kegiatan" placeholder="Tanggal Update" required>
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
    })
</script>
<script>
    $("#btnDownload").click(function(){
        let tgl_kegiatan =$("#tgl_kegiatan").val();
        let dismantle_id=$("#dismantle_id").val();
        if(tgl_kegiatan && dismantle_id){
            window.location.href='<?php echo site_url("pelaporan/ba_dismantle/download"); ?>?dismantle_id='+dismantle_id+'&tgl_kegiatan='+tgl_kegiatan
        }else{
            alert('Dismantle dan tanggal kegiatan harap di isi.');
        }
    })
</script>