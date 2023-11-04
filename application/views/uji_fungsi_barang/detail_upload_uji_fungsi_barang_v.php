<!--Swiper slider css-->
<link href="<?php echo base_url();?>assets/libs/swiper/swiper-bundle.min.css" rel="stylesheet" type="text/css" />

<?php if(count($get_foto) > 0) { ?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">Foto Barang</h4>
        </div>
        <div class="card-body">
            <div class="swiper responsive-swiper rounded gallery-light pb-4">
                <div class="swiper-wrapper">
                    <?php foreach($get_foto as $v) { ?>
                    <div class="swiper-slide">
                        <div class="gallery-box card">
                            <div class="gallery-container">
                                <a class="image-popup" href="<?php echo base_url('uploads/uji_fungsi_barang/' . $v['foto_barang']);?>" title="">
                                    <img class="gallery-img img-fluid mx-auto" src="<?php echo base_url('uploads/uji_fungsi_barang/' . $v['foto_barang']);?>" alt="" />
                                    <div class="gallery-overlay">
                                        <h5 class="overlay-caption">&nbsp;</h5>
                                    </div>
                                </a>
                            </div>                            
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="swiper-pagination swiper-pagination-dark"></div>
            </div>
        </div>
    </div>
</div>

<?php } else { ?>

<form action="<?php echo site_url('uji_fungsi_barang/submit_detail_foto'); ?>" method="post" id="basic-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Upload Foto Barang (Total: <?php echo $get_detail['jumlah_terima']; ?> File Foto)</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3 p-3">
                        <?php for ($i = 1; $i <= (int)$get_detail['jumlah_terima']; $i++) { ?>
                            <div class="row mb-3">
                                <div class="col-lg-2">
                                    <label class="form-label">Uplaod Foto Barang ke - <?php echo $i; ?></label>
                                </div>
                                <div class="col-lg-5">
                                    <input id="foto_barang" name="foto_barang[]" type="file" class="form-control" required>
                                </div>
                            </div>
                        <?php } ?>          
                        <input type="hidden" name="uji_penerimaan_id" value="<?php echo $get_detail['uji_penerimaan_id']; ?>" readonly>
                        <input type="hidden" name="detail_id" value="<?php echo $get_detail['id']; ?>" readonly>
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

<?php } ?>

<!--Swiper slider js-->
<script src="<?php echo base_url();?>assets/libs/swiper/swiper-bundle.min.js"></script>

<!-- swiper.init js -->
<script src="<?php echo base_url();?>assets/js/pages/swiper.init.js"></script>
