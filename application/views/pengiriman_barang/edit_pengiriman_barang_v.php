<!-- select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jquery validate-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<form action="<?php echo site_url('pengiriman_barang/submit_update'); ?>" method="post" id="basic-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Data Pengiriman</h5>
                </div>
                <div class="card-body">                    
                    
                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Perencanaan</label>
                        <div class="col-md-8">
                            <select class="select-single" disabled required>
                                <option value="">Pilih Perencanaan</option>
                                <?php foreach($get_perencanaan as $v) { ?>
                                    <option value="<?php echo $v['id']; ?>" <?php echo $get_pengiriman['perencanaan_id'] == $v['id'] ? 'selected' : ''; ?>><?php echo $v['kode_perencanaan'] . ' | ' . $v['province_name'] . ' | ' . $v['regency_name'] . ' | ' . $v['nama_lokasi'] . ' (' . $v['kode_lokasi'] . ')'; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Tanggal Kirim</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="date" class="form-control" name="tgl_kirim" placeholder="Tanggal Kirim" value="<?php echo $get_pengiriman['tgl_kirim']; ?>" required>
                        </div>
                    </div>    

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Catatan</label>
                        </div>
                        <div class="col-lg-8">
                            <textarea class="form-control" name="catatan" rows="3" placeholder="Catatan"><?php echo $get_pengiriman['catatan']; ?></textarea>
                            <input type="hidden" name="pengiriman_id" value="<?php echo $get_pengiriman['id']; ?>" readonly>
                            <input type="hidden" name="perencanaan_id" value="<?php echo $get_pengiriman['perencanaan_id']; ?>" readonly>
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
                                    <th>Jenis Alat</th>
                                    <th>Satuan</th>
                                    <th>Jumlah Rencana</th>
                                    <th>Jumlah Terkirim</th>
                                    <th>Jumlah Kirim</th>
                                    <th style="display:none">Barang ID </th>
                                    <th style="display:none">Detail ID </th>
                                </tr>
                            </thead>
                            <tbody id="show-barang">
                                <?php $no = 1; foreach($get_detail as $v) { ?>
                                <?php $jumlah_rencana = $this->db->where('perencanaan_id', $v['perencanaan_id'])->get('perencanaan_detail')->row()->jumlah; ?>
                                <?php $jumlah_terkirim = $this->db->where('perencanaan_id', $v['perencanaan_id'])->get('perencanaan_detail')->row()->jumlah_terkirim; ?>
                                <tr>
                                    <td><?php echo $no++;?></td>
                                    <td><?php echo $v['kode_barang_id'];?></td>
                                    <td><?php echo $v['nama_barang'];?></td>
                                    <td><?php echo $v['jenis_alat'];?></td>
                                    <td><?php echo $v['satuan'];?></td>
                                    <td><?php echo $jumlah_rencana; ?></td>
                                    <td><?php echo $jumlah_terkirim; ?></td>
                                    <td><input id="jumlah_kirim" name="jumlah_kirim[]" type="number" min="0" class="form-control" placeholder="Jumlah Kirim" value="<?php echo $v['jumlah_kirim'];?>" required></td>
                                    <td><input id="barang_id" name="barang_id[]" type="hidden" value="<?php echo $v['barang_id'];?>"></td>
                                    <td><input id="detail_id" name="detail_id[]" type="hidden" value="<?php echo $v['id'];?>"></td>
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