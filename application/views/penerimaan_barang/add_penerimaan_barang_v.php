<!-- select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jquery validate-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<form action="<?php echo site_url('penerimaan_barang/submit_data'); ?>" method="post" id="basic-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tambah Data Penerimaan</h5>
                </div>
                <div class="card-body">                    
                    
                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Pengiriman</label>
                        <div class="col-md-8">
                            <select class="select-single" name="pengiriman_id" id="pengiriman_id" required>
                                <option value="">Pilih Pengiriman</option>
                                <?php foreach($get_pengiriman as $v) { ?>
                                    <option value="<?php echo $v['perencanaan_id']; ?>"><?php echo $v['kode_pengiriman'] . ' | ' . $v['province_name'] . ' | ' . $v['regency_name'] . ' | ' . $v['nama_lokasi'] . ' (' . $v['kode_lokasi'] . ')'; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Tanggal Terima</label>
                        </div>
                        <div class="col-lg-3">       
                            <input type="date" class="form-control" name="tgl_terima" placeholder="Tanggal Terima" required>
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
                                    <th>Jumlah Kirim</th>
                                    <th>Jumlah Terima</th>
                                    <th>Jumlah Rusak</th>
                                    <th>Jumlah Terpasang</th>
                                    <th>Foto Terima </th>
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
    $(document).ready(function () {        
        $(".select-single").select2();

        $("#basic-form").validate({
            invalidHandler: function(event, validator) {            
                var errors = validator.numberOfInvalids();
                if (errors) { window.scrollTo({top: 0}); }
            }
        });        

        $("#pengiriman_id").on("change", function () {			
            let pengiriman_id = $("#pengiriman_id").val();
            var url_file = '<?php echo base_url('uploads/perencanaan/');?>';
            $.ajax({
                url: "<?php echo site_url('penerimaan_barang/get_barang');?>",
                data: { pengiriman_id: pengiriman_id },
                method: "POST",
                dataType: "json",
                success: function (data) {
                    var rows = '';

                    $.each(data, function (i, item) {   
						rows+= '<tr>';
                            rows+= '<td>' + (i + 1) + '</td>';
                            rows+= '<td>' + item.kode_barang_id + '</td>';
                            rows+= '<td>' + item.nama_barang + '</td>';
                            rows+= '<td>' + item.satuan + '</td>';
                            rows+= '<td>' + item.jumlah_kirim + '</td>';
                            rows+= '<td><input id="jumlah_terima" name="jumlah_terima[]" type="number" min="0" class="form-control" placeholder="Jumlah Terima"></td>';
                            rows+= '<td><input id="jumlah_rusak" name="jumlah_rusak[]" type="number" min="0" class="form-control" placeholder="Jumlah Rusak"></td>';
                            rows+= '<td><input id="jumlah_terpasang" name="jumlah_terpasang[]" type="number" min="0" class="form-control" placeholder="Jumlah Terpasang"></td>';                            
                            rows+= '<td><input id="foto_barang" name="foto_barang[]" type="file" class="form-control"></td>';
                            rows+= '<input id="barang_id" name="barang_id[]" type="text" value="' + item.barang_id + '">';
                        rows+= '</tr>';
					});

                    $('#show-barang').html(rows);
                },
                error: function (xhr, status, error) {
                    alert('Gagal ambil data barang.');
                },
            });
        });

    })
</script>