<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<!--datatable responsive css-->
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Detail Data Penerimaan</h5>
            </div>
            <div class="card-body">                    
                <div class="form-group row mb-2">
                    <label class="col-md-2 label-control">Kode Penerimaan</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" value="<?php echo $get_penerimaan['kode_penerimaan']; ?>" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label class="form-label">Wilayah</label>
                    </div>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?php echo $get_penerimaan['province_name']; ?>" readonly>
                    </div>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?php echo $get_penerimaan['regency_name']; ?>" readonly>
                    </div>
                </div>                
                
                <div class="form-group row mb-2">
                    <label class="col-md-2 label-control">Lokasi</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" value="<?php echo $get_penerimaan['nama_lokasi']; ?>" readonly>
                    </div>
                </div>

                <div class="form-group row mb-2">
                    <label class="col-md-2 label-control">Tanggal Terima</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" value="<?php echo $get_penerimaan['tgl_terima']; ?>" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label class="form-label">Catatan</label>
                    </div>
                    <div class="col-lg-8">
                        <textarea class="form-control" rows="3" readonly><?php echo $get_penerimaan['catatan']; ?></textarea>
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
                    <table id="data-form" class="table table-bordered dt-responsive table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th style="width:400px">Nama Barang</th>
                                <th>Satuan</th>
                                <th>Jumlah Kirim</th>
                                <th>Jumlah Terima</th>
                                <th>Jumlah Rusak</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach($get_detail as $v) { ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++;?></td>
                                    <td><?php echo $v['kode_barang_id'];?></td>
                                    <td><?php echo $v['nama_barang'];?></td>
                                    <td><?php echo $v['satuan'];?></td>
                                    <td><?php echo $v['jumlah_kirim'];?></td>
                                    <td><?php echo $v['jumlah_terima'];?></td>
                                    <td><?php echo $v['jumlah_rusak'];?></td>
                                    <td>
                                        <div class="avatar-group">
                                            <?php
                                                $foto_barang = $v['foto_barang'];
                                                $image_url = base_url('uploads/penerimaan_barang/' . $foto_barang);
                                                if (empty($foto_barang)) { $image_url = base_url('assets/images/noimage.jpeg'); }
                                            ?>
                                            <a href="<?php echo $image_url; ?>" target="_blank" class="avatar-group-item" data-img="<?php echo $image_url; ?>" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Foto Barang">
                                                <img src="<?php echo $image_url; ?>" alt="" class="rounded-circle avatar-xxs">
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>                            
                    </table>                    
                </div>                      
            </div>
        </div>
    </div>
</div>

<!--datatable js-->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script>    
    $(document).ready(function () {
        var table = $("#data-form").DataTable();
    })
</script>
