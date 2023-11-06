<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Update_barang extends Telescoope_Controller
{

    var $data;

    public function __construct()
    {

        parent::__construct();

        $this->load->model(array("Administration_m", "Update_barang_m", "Penerimaan_barang_m", "Perencanaan_m"));
        $this->data['date_format'] = "h:i A | d M Y";
        $this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');
        $this->data['data'] = array();
        $this->data['post'] = $this->input->post();
        $userdata = $this->Administration_m->getLogin();
        $this->data['dir'] = 'update_barang';
        $this->data['controller_name'] = $this->uri->segment(1);
        $dir = './uploads/' . $this->data['dir'];
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $config['allowed_types'] = '*';
        $config['overwrite'] = false;
        $config['max_size'] = 3064;
        $config['upload_path'] = $dir;
        $this->load->library('upload', $config);

        $this->data['userdata'] = (!empty($userdata)) ? $userdata : array();
        $sess = $this->session->userdata(do_hash(SESSION_PREFIX));
        $position1 = $this->Administration_m->getPosition("ADMINISTRATOR");
        $position2 = $this->Administration_m->getPosition("PUSAT");
        $position3 = $this->Administration_m->getPosition("KOORDINATOR");

        if (!$position1 && !$position2 && !$position3) {
            $this->noAccess("Anda tidak memiliki hak akses untuk halaman ini.");
        }

        if (empty($sess)) {
            redirect(site_url('log/in'));
        }
    }

    public function index()
    {
        $data = array();
        $this->template("pelaksanaan_harian/update_barang/list_update_barang_v", "Data Update Pengawasan Barang", $data);
    }

    public function get_data()
    {
        $post = $this->input->post();

        $draw = $post['draw'];
        $row = $post['start'];
        $rowperpage = $post['length'];
        $search = $post['search']['value'];
        $columnIndex = $post['order'][0]['column'];
        $columnName = $post['columns'][$columnIndex]['data'];
        // $prov = isset($post['s_provinsi']) ? $post['s_provinsi'] : "";

        if (!empty($search)) {
            // $this->db->group_start();
            // $this->db->like('test', $search);
            // $this->db->or_like('test', $search);
            // $this->db->group_end();
        }

        $this->db->limit($rowperpage, $row);

        $result = $this->Update_barang_m->getUpdate_barang()->result_array();
        if (!empty($search)) {
            // $this->db->group_start();
            // $this->db->like('test', $search);
            // $this->db->or_like('test', $search);
            // $this->db->group_end();
        }

        $count = $this->Update_barang_m->getUpdate_barang()->num_rows();

        $totalRecords = $count;
        $totalRecordwithFilter = $count;

        $data = array();

        foreach ($result as $v) {

            // $foto_barang = '<a href="' . base_url('uploads/update_barang/' . $v['foto_barang']) . '" target="_blank" class="avatar-group-item" data-img="' . base_url('uploads/update_barang/' . $v['foto_barang']) . '" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Foto Barang">
            //                 <img src="' . base_url('uploads/update_barang/' . $v['foto_barang']) . '" alt="" class="rounded-circle avatar-xxs">
            //             </a>';

            $action = '<div class="btn-group" role="group">
                        <a href="' .  site_url('pelaksanaan_harian/update_barang/edit/' . $v['id']) . '" class="btn btn-sm btn-warning" disabled>Edit</a>
                        <a href="' .  site_url('pelaksanaan_harian/update_barang/detail/' . $v['id']) . '" class="btn btn-sm btn-primary" disabled>Detail</a>
                        <a href="' .  site_url('pelaksanaan_harian/update_barang/delete/' . $v['id']) . '" onclick=\'return confirm("Apakah anda yakin?")\' class="btn btn-sm btn-danger">Delete</a></div>';

            $data[] = array(
                "kode_perencanaan" => $v['kode_perencanaan'],
                "tgl_update_harian" => date('d-m-Y', strtotime($v['tgl_update_harian'])),
                "catatan" => $v['catatan'],
                "action" => $action
            );
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        echo json_encode($response);
    }

    public function add()
    {
        $data = array();
        $data['get_perencanaan'] = $this->Perencanaan_m->getPerencanaan()->result_array();
        $this->template("pelaksanaan_harian/update_barang/add_update_barang_v2", "Tambah Update Barang", $data);
    }

    public function detail($id)
    {
        $data = array();
        $data['id']  = $id;
        $data['get_update_barang'] = $this->Update_barang_m->getUpdate_barang($id)->row_array();
        $data['get_update_barang_detail'] = $this->Update_barang_m->get_UpdateBarangDetail($id)->result_array();
        $this->template("pelaksanaan_harian/update_barang/detail_update_barang_v2", "Detail Update Barang", $data);
    }

    public function edit($id)
    {
        $data = array();
        $data['id']  = $id;
        $data['get_update_barang'] = $this->Update_barang_m->getUpdate_barang($id)->row_array();
        $data['get_update_barang_detail'] = $this->Update_barang_m->get_UpdateBarangDetail($id)->result_array();
        $this->template("pelaksanaan_harian/update_barang/edit_update_barang_v2", "Edit Update Barang", $data);
    }

    public function update($id)
    {
        $post = $this->input->post();
        $dir = './uploads/' . $this->data['dir'];

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('pelaksanaan_harian/update_barang/edit/' . $id));
        }
        $update_barang = $this->Update_barang_m->getUpdate_barang($id)->row_array();
        $exist = $this->Update_barang_m->get_UpdateBarangExist($update_barang['perencanaan_id'], $post['tgl_update'], $id)->row_array();
        if (isset($exist)) {
            $this->setMessage("Data perencanaan : " . $update_barang['kode_perencanaan'] . ' di tanggal :' . date('d-m-Y', strtotime($post['tgl_update'])) . ' sudah pernah di input.');
            redirect(site_url('pelaksanaan_harian/update_barang/edit/' . $id));
        }
        $this->db->trans_begin();
        $data = array(
            "tgl_update_harian" => $post['tgl_update'],
            'catatan' => $post['catatan'],
            'updated_by' => $this->data['userdata']['employee_id'],
            'updated_at' => date('Y-m-d H:i:s'),
        );

        $this->db->where('id', $id);
        $simpan = $this->db->update('update_harian_barang', $data);
        if ($simpan) {

            $update_barang_details = $this->Update_barang_m->get_UpdateBarangDetail($id)->result_array();
            foreach ($update_barang_details as $k => $v) {
                $detail_update = array(
                    'jumlah_barang_status_ada' => $this->input->post('status_ada#' . $v['barang_id']),
                    'jumlah_barang_status_tidak_ada' => $this->input->post('status_tidak_ada#' . $v['barang_id']),
                    'jumlah_barang_kondisi_baik' => $this->input->post('kondisi_baik#' . $v['barang_id']),
                    'jumlah_barang_kondisi_rusak' => $this->input->post('kondisi_tidak_baik#' . $v['barang_id']),
                );

                // URUSAN FILE BROH
                $dir = './uploads/' . $this->data['dir'];

                if (!empty($_FILES['foto_barang#' . $v['barang_id']]['name'])) {
                    $_FILES['file']['name'] = $this->data['userdata']['employee_id'] . '_barang_update_' . date('YmdHis') . '_' . $v['barang_id'];
                    $_FILES['file']['type'] = $_FILES['foto_barang#' . $v['barang_id']]['type'];
                    $_FILES['file']['tmp_name'] = $_FILES['foto_barang#' . $v['barang_id']]['tmp_name'];
                    $_FILES['file']['error'] = $_FILES['foto_barang#' . $v['barang_id']]['error'];
                    $_FILES['file']['size'] = $_FILES['foto_barang#' . $v['barang_id']]['size'];
                    if ($this->upload->do_upload('file')) {
                        $uploadKtp = $this->upload->data();
                        $detail_update["foto_barang"] = $uploadKtp['file_name'];
                    }
                }

                $this->db->where('id', $v['id']);
                $simpan_details = $this->db->update('update_harian_barang_detail', $detail_update);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }
            redirect(site_url('pelaksanaan_harian/update_barang'));
        } else {
            $this->renderMessage("error");
        }
    }

    public function delete($id)
    {
        $this->db->where('update_barang_id', $id);
        $this->db->delete('update_harian_barang_detail');
        $this->db->where('id', $id);
        $this->db->delete('update_harian_barang');
        $this->setMessage("Success delete data.");
        redirect(site_url('pelaksanaan_harian/update_barang'));
    }

    public function submit_datav2()
    {
        $post = $this->input->post();

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('pelaksanaan_harian/update_barang/add'));
        }
        $perencanaan = $this->Perencanaan_m->getPerencanaan($post['perencanaan_id'])->row_array();
        $exist = $this->Update_barang_m->get_UpdateBarangExist($post['perencanaan_id'], $post['tgl_update'])->row_array();
        if (isset($exist)) {
            $this->setMessage("Data perencanaan : " . $perencanaan['kode_perencanaan'] . ' di tanggal :' . date('d-m-Y', strtotime($post['tgl_update'])) . ' sudah pernah di input.');
            redirect(site_url('pelaksanaan_harian/update_barang/add'));
        }

        $this->db->trans_begin();

        $data = array(
            "perencanaan_id" => $post['perencanaan_id'],
            "kode_perencanaan" => $perencanaan['kode_perencanaan'],
            "tgl_update_harian" => $post['tgl_update'],
            'catatan' => $post['catatan'],
            'created_by' => $this->data['userdata']['employee_id'],
            'created_at' => date('Y-m-d H:i:s'),
        );

        $simpan = $this->db->insert('update_harian_barang', $data);

        if ($simpan) {

            $insert_id = $this->db->insert_id();
            $dir = './uploads/' . $this->data['dir'];
            $data_insert = array();

            // LOAD EXISTING PRODUCT ON PERENCANAAN
            $perencanaan_id = $post['perencanaan_id'];
            $data_perencanaan = $this->Update_barang_m->getDetail_Perencanaan($perencanaan_id)->result_array();

            foreach ($data_perencanaan as $k => $v) {

                $_FILES['file']['name'] = $this->data['userdata']['employee_id'] . '_barang_update_' . date('YmdHis') . '_' . $v['barang_id'];
                $_FILES['file']['type'] = $_FILES['foto_barang#' . $v['barang_id']]['type'];
                $_FILES['file']['tmp_name'] = $_FILES['foto_barang#' . $v['barang_id']]['tmp_name'];
                $_FILES['file']['error'] = $_FILES['foto_barang#' . $v['barang_id']]['error'];
                $_FILES['file']['size'] = $_FILES['foto_barang#' . $v['barang_id']]['size'];

                if ($this->upload->do_upload('file')) {
                    $uploadKtp = $this->upload->data();
                    $data_insert[] = array(
                        'update_barang_id' => $insert_id,
                        'jumlah_barang' => $this->input->post('jumlah_barang#' . $v['barang_id']),
                        'jumlah_barang_status_ada' => $this->input->post('status_ada#' . $v['barang_id']),
                        'jumlah_barang_status_tidak_ada' => $this->input->post('status_tidak_ada#' . $v['barang_id']),
                        'jumlah_barang_kondisi_baik' => $this->input->post('kondisi_baik#' . $v['barang_id']),
                        'jumlah_barang_kondisi_rusak' => $this->input->post('kondisi_tidak_baik#' . $v['barang_id']),
                        'barang_id' => $v['barang_id'],
                        'file_path' => $uploadKtp['file_name'],
                    );
                }
            }

            foreach ($data_insert as $insert_data) {
                $detail = array(
                    "update_barang_id" => $insert_data['update_barang_id'],
                    "barang_id" => $insert_data['barang_id'],
                    "jumlah_barang" => $insert_data['jumlah_barang'],
                    "jumlah_barang_status_ada" => $insert_data['jumlah_barang_status_ada'],
                    "jumlah_barang_status_tidak_ada" => $insert_data['jumlah_barang_status_tidak_ada'],
                    "jumlah_barang_kondisi_baik" => $insert_data['jumlah_barang_kondisi_baik'],
                    "jumlah_barang_kondisi_rusak" => $insert_data['jumlah_barang_kondisi_rusak'],
                    'foto_barang' => $insert_data['file_path'],
                    'created_by' => $this->data['userdata']['employee_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                );
                $simpan_detail = $this->db->insert('update_harian_barang_detail', $detail);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }

            redirect(site_url('pelaksanaan_harian/update_barang'));
        } else {
            $this->renderMessage("error");
        }
    }
    public function submit_data()
    {

        $post = $this->input->post();

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('pelaksanaan_harian/update_barang/add'));
        }

        $this->db->trans_begin();

        $dir = './uploads/' . $this->data['dir'];

        if (!empty($_FILES['foto_barang']['name'])) {
            $_FILES['file']['name'] = $this->data['userdata']['employee_id'] . '_barang_' . date('his') . '_' . $_FILES['foto_barang']['name'];
            $_FILES['file']['type'] = $_FILES['foto_barang']['type'];
            $_FILES['file']['tmp_name'] = $_FILES['foto_barang']['tmp_name'];
            $_FILES['file']['error'] = $_FILES['foto_barang']['error'];
            $_FILES['file']['size'] = $_FILES['foto_barang']['size'];
            if ($this->upload->do_upload('file')) {
                $uploadKtp = $this->upload->data();
            }
        }

        $data = array(
            "penerimaan_id" => $post['penerimaan_id'],
            "tgl_pelaksanaan" => $post['tgl_pelaksanaan'],
            "nama_barang" => $post['nama_barang'],
            "jumlah_barang" => $post['jumlah_barang'],
            "status_uji" => $post['status_uji'],
            'foto_barang' => isset($uploadKtp['file_name']) ? $uploadKtp['file_name'] : '',
            'catatan' => $post['catatan'],
            'created_by' => $this->data['userdata']['employee_id'],
            'created_at' => date('Y-m-d H:i:s'),
        );

        $simpan = $this->db->insert('update_barang', $data);

        if ($simpan) {
            if ($this->db->trans_status() === FALSE) {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }

            redirect(site_url('pelaksanaan_harian/update_barang/add'));
        } else {
            $this->renderMessage("error");
        }
    }

    public function get_perencanaan()
    {
        $perencanaan_id = $this->input->post('perencanaan_id', true);
        $data = $this->Update_barang_m->getDetail_Perencanaan($perencanaan_id)->result_array();
        echo json_encode($data);
    }
}
