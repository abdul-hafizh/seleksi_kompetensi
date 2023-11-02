<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<!--datatable responsive css-->
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

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

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom pb-2">
                <div class="float-start">
                    <h5 class="card-title mb-0">List SDM</h5>
                </div>
                <div class="float-end">
                    <div class="btn-group">
                        <a href="<?php echo site_url('manajemen_user/users/add_access');?>" class="btn btn-info btn-sm"><i class="ri-add-line align-middle me-1"></i> Tambah SDM Access</a>
                        <a href="<?php echo site_url('manajemen_user/users/add');?>" class="btn btn-primary btn-sm"><i class="ri-add-line align-middle me-1"></i> Tambah SDM</a>
                    </div>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <table id="data-form" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Posisi</th>
                                <th>KTP</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach($get_employee as $v) { ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++;?></td>
                                    <td><?php echo $v['fullname'];?></td>
                                    <td><?php echo $v['email'];?></td>
                                    <td><?php echo $v['phone'];?></td>
                                    <td><?php echo $v['pos_name'];?></td>
                                    <td>
                                        <div class="avatar-group">
                                            <a href="<?php echo base_url('uploads/users/' . $v['file_ktp']); ?>" target="_blank" class="avatar-group-item" data-img="<?php echo base_url('uploads/users/' . $v['file_ktp']); ?>" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Foto KTP">
                                                <img src="<?php echo base_url('uploads/users/' . $v['file_ktp']); ?>" alt="" class="rounded-circle avatar-xxs">
                                            </a>
                                        </div>
                                    </td>
                                    <td><?php echo $v['status'] == 2 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>'; ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo site_url('manajemen_user/users/update/' . $v['id']);?>" class="btn btn-sm btn-warning">Edit</a>
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
        $("#data-form").DataTable();
    })
</script>
