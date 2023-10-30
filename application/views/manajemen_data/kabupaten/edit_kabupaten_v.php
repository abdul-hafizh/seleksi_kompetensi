<!-- select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jquery validate-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<form action="<?php echo site_url('manajemen_data/kabupaten/submit_update'); ?>" method="post" id="basic-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Data Provinsi</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Nama Provinsi</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="hidden" name="location_id" value="<?php echo $get_kabupaten['location_id']; ?>" readonly>
                            <input type="text" class="form-control" name="province_name" placeholder="Nama Provinsi" value="<?php echo $get_kabupaten['province_name']; ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Kabupaten/Kota</label>
                        </div>
                        <div class="col-lg-3">       
                            <select class="select-single" name="name_prefix" id="name_prefix" required>
                                <option value="">Pilih</option>
                                <option value="Kota" <?php echo $get_kabupaten['name_prefix'] == 'Kota' ? 'selected' : ''; ?> >Kota</option>
                                <option value="Kabupaten" <?php echo $get_kabupaten['name_prefix'] == 'Kabupaten' ? 'selected' : ''; ?> >Kabupaten</option>                                
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Nama Kabupaten</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="text" class="form-control" name="regency_name" placeholder="Nama Kabupaten" value="<?php echo $get_kabupaten['name']; ?>" required>
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