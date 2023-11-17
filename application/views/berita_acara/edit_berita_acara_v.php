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

<form action="<?php echo site_url('berita_acara/submit_update'); ?>" method="post" id="basic-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Data Berita Acara</h5>
                </div>
                <div class="card-body">                                        
                    <div class="form-group row mb-3">
                        <label class="col-md-2 label-control">Titik Lokasi</label>
                        <div class="col-md-6">
                            <select class="select-single" name="lokasi_skd_id" id="lokasi_skd_id" <?php echo $job_title == 'KOORDINATOR' ? 'required' : 'disabled'; ?>>
                                <option value="">Pilih Titik Lokasi</option>
                                <?php foreach($get_lokasi as $v) { ?>
                                    <option value="<?php echo $v['id']; ?>" <?php echo $get_row['lokasi_skd_id'] == $v['id'] ? 'selected' : ''; ?>><?php echo $v['province_name'] . ' | ' . $v['regency_name'] . ' | ' . $v['nama_lokasi'] . ' (' . $v['kode_lokasi'] . ')'; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 label-control">Jenis</label>
                        <div class="col-md-4">
                            <select class="select-single" name="jenis_ba_id" id="jenis_ba_id" <?php echo $job_title == 'KOORDINATOR' ? 'required' : 'disabled'; ?>>
                                <option value="">Pilih Jenis</option>
                                <?php foreach($get_jenis as $v) { ?>
                                    <option value="<?php echo $v['id']; ?>" <?php echo $get_row['jenis_ba_id'] == $v['id'] ? 'selected' : ''; ?>><?php echo $v['jenis_berita_acara']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Judul Berita</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="text" class="form-control" name="judul_berita" placeholder="Judul Berita" value="<?php echo $get_row['judul_berita']; ?>" <?php echo $job_title == 'KOORDINATOR' ? 'required' : 'readonly'; ?>>
                            <input type="hidden" name="id_berita" value="<?php echo $get_row['id']; ?>">
                        </div>
                    </div>     

                    <?php if($job_title == 'KOORDINATOR') { ?>
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Ganti File</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="file" class="form-control" name="file_lampiran" id="file_lampiran">
                        </div>
                    </div>   
                    <?php } else { ?>                           
                        <input type="hidden" class="form-control" name="file_lampiran" id="file_lampiran">
                    <?php } ?>   

                    <?php if(!empty($get_row['file_lampiran'])) { ?>
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label"><?php echo $job_title == 'KOORDINATOR' ? '' : 'Dokumen'; ?></label>
                        </div>
                        <div class="col-lg-3">       
                            <a href="<?php echo base_url('uploads/berita_acara/' . $get_row['file_lampiran']);?>" class="btn btn-sm btn-info" target="_blank">Lihat Dokumen</a>
                            <input type="hidden" name="file_lampiran_exist" id="file_lampiran_exist" value="<?php echo $get_row['file_lampiran']; ?>">
                        </div>
                    </div>      
                    <?php } ?>
                    
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Tanggal</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="date" class="form-control" name="tgl_kegiatan" value="<?php echo $get_row['tgl_kegiatan']; ?>" <?php echo $job_title == 'KOORDINATOR' ? 'required' : 'readonly'; ?>>
                        </div>
                    </div>     
                    
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Deskripsi</label>
                        </div>
                        <div class="col-lg-8">
                            <textarea class="form-control" name="deskripsi" rows="3" placeholder="Deskripsi" <?php echo $job_title == 'KOORDINATOR' ? '' : 'readonly'; ?>><?php echo $get_row['deskripsi']; ?></textarea>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>

    <?php if($job_title == 'KOORDINATOR') { ?>
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
    <?php } ?>
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

        if ($("#file_lampiran_exist").length > 0) {
            $("#file_lampiran").prop("required", false); 
        } else {
            $("#file_lampiran").prop("required", true); 
        }
    })
</script>