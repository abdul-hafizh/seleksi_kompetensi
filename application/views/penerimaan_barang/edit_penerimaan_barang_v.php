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

<form action="<?php echo site_url('penerimaan_barang/submit_update'); ?>" method="post" id="basic-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Data Penerimaan</h5>
                </div>
                <div class="card-body">                    
                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Kode Penerimaan</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?php echo $get_penerimaan['kode_penerimaan']; ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Pengiriman</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?php echo $get_penerimaan['province_name'] . ' | ' . $get_penerimaan['regency_name'] . ' | ' . $get_penerimaan['nama_lokasi'] . ' (' . $get_penerimaan['kode_lokasi'] . ')'; ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Tanggal Terima</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="date" class="form-control" name="tgl_terima" placeholder="Tanggal Terima" value="<?php echo $get_penerimaan['tgl_terima']; ?>" required>
                        </div>
                    </div>    

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Catatan</label>
                        </div>
                        <div class="col-lg-8">
                            <input type="hidden" name="id" value="<?php echo $get_penerimaan['id']; ?>" readonly>
                            <input type="hidden" name="pengiriman_id" value="<?php echo $get_penerimaan['pengiriman_id']; ?>" readonly>
                            <textarea class="form-control" name="catatan" rows="3" placeholder="Catatan"><?php echo $get_penerimaan['catatan']; ?></textarea>
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
                                    <th>Jumlah Kirim</th>
                                    <th>Jumlah Terima</th>
                                    <th>Jumlah Rusak</th>
                                    <th>Jumlah Terpasang</th>
                                    <th>Foto Terima </th>
                                    <th>Uplaod Foto </th>
                                    <th style="display:none">Barang ID </th>
                                    <th style="display:none">Detail ID </th>
                                    <th style="display:none">Foto ID </th>
                                </tr>
                            </thead>
                            <tbody id="show-barang">
                                <?php $no=1; foreach($get_detail as $v) { ?>
                                    <tr>
                                        <td class="text-center"><?php echo $no++;?></td>
                                        <td><?php echo $v['kode_barang_id'];?></td>
                                        <td><?php echo $v['nama_barang'];?></td>
                                        <td><?php echo $v['satuan'];?></td>
                                        <td><?php echo $v['jumlah_kirim'];?></td>
                                        <td><input id="jumlah_terima" name="jumlah_terima[]" type="number" min="0" class="form-control" placeholder="Jumlah Terima" value="<?php echo $v['jumlah_terima'];?>" required></td>
                                        <td><input id="jumlah_rusak" name="jumlah_rusak[]" type="number" min="0" class="form-control" placeholder="Jumlah Rusak" value="<?php echo $v['jumlah_rusak'];?>" required></td>
                                        <td><input id="jumlah_terpasang" name="jumlah_terpasang[]" type="number" min="0" class="form-control" placeholder="Jumlah Terpasang" value="<?php echo $v['jumlah_terpasang'];?>" required></td>
                                        <td>
                                            <div class="avatar-group">
                                                <a href="<?php echo base_url('uploads/penerimaan_barang/' . $v['foto_barang']); ?>" target="_blank" class="avatar-group-item" data-img="<?php echo base_url('uploads/penerimaan_barang/' . $v['foto_barang']); ?>" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Foto Barang">
                                                    <img src="<?php echo base_url('uploads/penerimaan_barang/' . $v['foto_barang']); ?>" alt="" class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                        </td>
                                        <td><input id="foto_barang" name="foto_barang[]" type="file" class="form-control"></td>
                                        <td style="display:none"><input id="barang_id" name="barang_id[]" type="hidden" value="<?php echo $v['barang_id'];?>"></td>
                                        <td style="display:none"><input id="detail_id" name="detail_id[]" type="hidden" value="<?php echo $v['id'];?>"></td>
                                        <td style="display:none"><input id="foto_exist" name="foto_exist[]" type="hidden" value="<?php echo $v['foto_barang'];?>"></td>
                                    </tr>
                                <?php } ?>
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
    $(document).ready(function () {   
        $("#basic-form").validate({
            invalidHandler: function(event, validator) {            
                var errors = validator.numberOfInvalids();
                if (errors) { window.scrollTo({top: 0}); }
            }
        });
    })
</script>