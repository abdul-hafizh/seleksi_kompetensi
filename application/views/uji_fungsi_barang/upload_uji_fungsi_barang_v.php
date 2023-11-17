<!-- select2 css-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<!--datatable responsive css-->
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

<form action="<?php echo site_url('uji_fungsi_barang/submit_update'); ?>" method="post" id="basic-form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detail Data Penerimaan</h5>
                </div>
                <div class="card-body">                    
                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Kode Uji Fungsi Barang</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?php echo $get_uji['kode_penerimaan']; ?>" readonly>
                            <input type="hidden" name="id_uji" value="<?php echo $get_uji['id_uji']; ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Tanggal Kegiatan</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?php echo $get_uji['jadwal_kegiatan']; ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-md-2 label-control">Penerimaan</label>
                        <div class="col-md-8">
                            <select class="select-single" name="penerimaan_id" id="penerimaan_id" disabled>
                                <option value="">Pilih Pengiriman</option>
                                <?php foreach($get_penerimaan as $v) { ?>
                                    <option value="<?php echo $v['id']; ?>" <?php echo $get_uji['id'] == $v['id'] ? 'selected' : ''; ?>><?php echo $v['kode_penerimaan'] . ' | ' . $v['province_name'] . ' | ' . $v['regency_name'] . ' | ' . $v['nama_lokasi'] . ' (' . $v['kode_lokasi'] . ')'; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Status</label>
                        </div>
                        <?php if($job_title != 'KOORDINATOR'){ ?>
                            <div class="col-lg-3">
                                <select class="form-control" name="status_uji" required>
                                    <option value="Pending" <?php echo $get_uji['status_uji'] == 'Pending' ? ' selected' : ''; ?> >Pending</option>
                                    <option value="Approved" <?php echo $get_uji['status_uji'] == 'Approved' ? ' selected' : ''; ?>>Approved</option>
                                </select>
                            </div>
                            <?php } else { ?>
                            <div class="col-lg-3">
                                <input type="text" class="form-control" name="status_uji" value="<?php echo $get_uji['status_uji']; ?>" readonly>
                            </div>
                        <?php } ?>
                    </div>    

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label">Catatan</label>
                        </div>
                        <div class="col-lg-8">
                            <textarea class="form-control" rows="3" <?php echo $job_title == 'KOORDINATOR' ? '' : 'readonly'; ?> ><?php echo $get_uji['catatan_uji']; ?></textarea>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>

    <?php if($job_title == 'PENGAWAS'){ ?>
    <div class="row mt-2">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-warning" onclick="return confirm('Apakah Anda yakin?');">Ubah Data</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

</form>

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
                                <th>Jumlah Terima</th>
                                <th>Jumlah Rusak</th>
                                <th>Jumlah Terpasang</th>
                                <th>Status Baik</th>
                                <th>Status Tidak Baik</th>
                                <th>Catatan</th>
                                <th>Foto Terima</th>
                                <th>Uplaod Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach($get_detail as $v) { ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++;?></td>
                                    <td><?php echo $v['kode_barang_id'];?></td>
                                    <td><?php echo $v['nama_barang'];?></td>
                                    <td><?php echo $v['satuan'];?></td>
                                    <td><?php echo $v['jumlah_terima'];?></td>
                                    <td><?php echo $v['jumlah_rusak'];?></td>
                                    <td><?php echo $v['jumlah_terpasang'];?></td>
                                    <td><?php echo $v['status_baik'];?></td>
                                    <td><?php echo $v['status_tidak'];?></td>
                                    <td><?php echo $v['catatan'];?></td>
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
                                    <td class="text-center">
                                        <?php $cek_exist = $this->db->select('id')->from('uji_detail_foto')->where('uji_detail_id', $v['id_detail'])->get()->num_rows(); ?>                                        
                                            <div class="btn-group">
                                            <?php if($cek_exist > 0) { ?>
                                                <a href="<?php echo site_url('uji_fungsi_barang/detail_foto/' . $v['id_detail']); ?>" class="btn btn-sm btn-info">Lihat Foto</a>
                                            <?php } else { ?>
                                                <?php if($job_title == 'KOORDINATOR') { ?>
                                                    <a href="<?php echo site_url('uji_fungsi_barang/detail_foto/' . $v['id_detail']); ?>" class="btn btn-sm btn-success">Upload</a>
                                                <?php } else { ?>
                                                    <span class="badge bg-warning">Belum Upload</span>
                                                <?php } ?>
                                            <?php } ?>
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

<!--select2 cdn-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>    
    $(document).ready(function () {
        $(".select-single").select2();

        var table = $("#data-form").DataTable();
    })
</script>
