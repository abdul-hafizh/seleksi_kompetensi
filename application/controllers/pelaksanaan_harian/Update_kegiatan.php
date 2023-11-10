<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Update_kegiatan extends Telescoope_Controller
{

    var $data;

    public function __construct()
    {

        parent::__construct();

        $this->load->model(array("Administration_m", "Penerimaan_barang_m", "Pengiriman_barang_m", "Update_kegiatan_m"));

        $this->data['date_format'] = "h:i A | d M Y";

        $this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');

        $this->data['data'] = array();

        $this->data['post'] = $this->input->post();

        $userdata = $this->Administration_m->getLogin();

        $this->data['dir'] = 'update_kegiatan';

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

        $cek_menu = $this->db->select('ajm.*')
        ->from('adm_jobtitle_menu ajm')
        ->join('adm_menu am', 'ajm.menu_id = am.menuid', 'left')
        ->where(['jobtitle' => $this->data['userdata']['job_title'], 'url_path' => $this->data['dir']])
        ->get()
        ->num_rows();

        if($cek_menu < 1){
            $this->noAccess("Anda tidak memiliki hak akses untuk halaman ini.");
        }

        if (empty($sess)) {
            redirect(site_url('log/in'));
        }
    }

    public function index()
    {
        $data = array();

        $this->template("pelaksanaan_harian/update_kegiatan/list_update_kegiatan_v", "Data Update Kegiatan", $data);
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
        $lokasi = $this->data['userdata']['lokasi_user'];
        $result = $this->Update_kegiatan_m->getUpdateKegiatan('', $lokasi)->result_array();

        if (!empty($search)) {
            // $this->db->group_start();
            // $this->db->like('test', $search);
            // $this->db->or_like('test', $search);
            // $this->db->group_end();
        }

        $count = $this->Update_kegiatan_m->getUpdateKegiatan('', $lokasi)->num_rows();

        $totalRecords = $count;
        $totalRecordwithFilter = $count;

        $data = array();

        foreach ($result as $v) {

            $action = '<div class="btn-group" role="group">
                        <a href="' .  site_url('pelaksanaan_harian/update_kegiatan/update/' . $v['id']) . '" class="btn btn-sm btn-warning" disabled>Edit</a>
                        <a href="' .  site_url('pelaksanaan_harian/update_kegiatan/detail/' . $v['id']) . '" class="btn btn-sm btn-primary" disabled>Detail</a>
                    </div>';

            $data[] = array(
                "kode_kegiatan" => $v['kode_kegiatan'],
                "tgl_kegiatan" => $v['tgl_kegiatan'],
                "foto_registrasi" => "<div class=\"avatar-group\">
                        <a href=\"" . base_url('uploads/update_kegiatan/' . $v['foto_registrasi']) . "\" target=\"_blank\" class=\"avatar-group-item\" data-img=\"" . base_url('uploads/update_kegiatan/' . $v['foto_registrasi']) . "\" data-bs-toggle=\"tooltip\" data-bs-trigger=\"hover\" data-bs-placement=\"top\" title=\"Foto Registrasi\">
                            <img src=\"" . base_url('uploads/update_kegiatan/' . $v['foto_registrasi']) . "\" alt=\"\" class=\"rounded-circle avatar-xxs\">
                        </a>
                    </div>",
                "foto_pengarahan" => "<div class=\"avatar-group\">
                        <a href=\"" . base_url('uploads/update_kegiatan/' . $v['foto_pengarahan']) . "\" target=\"_blank\" class=\"avatar-group-item\" data-img=\"" . base_url('uploads/update_kegiatan/' . $v['foto_pengarahan']) . "\" data-bs-toggle=\"tooltip\" data-bs-trigger=\"hover\" data-bs-placement=\"top\" title=\"Foto Pengarahan\">
                            <img src=\"" . base_url('uploads/update_kegiatan/' . $v['foto_pengarahan']) . "\" alt=\"\" class=\"rounded-circle avatar-xxs\">
                        </a>
                    </div>",
                "foto_kegiatan_lain" => "<div class=\"avatar-group\">
                        <a href=\"" . base_url('uploads/update_kegiatan/' . $v['foto_kegiatan_lain']) . "\" target=\"_blank\" class=\"avatar-group-item\" data-img=\"" . base_url('uploads/update_kegiatan/' . $v['foto_kegiatan_lain']) . "\" data-bs-toggle=\"tooltip\" data-bs-trigger=\"hover\" data-bs-placement=\"top\" title=\"Foto Kegiatan Lain\">
                            <img src=\"" . base_url('uploads/update_kegiatan/' . $v['foto_kegiatan_lain']) . "\" alt=\"\" class=\"rounded-circle avatar-xxs\">
                        </a>
                    </div>",
                "video_kegiatan" => "<div class=\"avatar-group\">
                        <a href=\"" . base_url('uploads/update_kegiatan/' . $v['video_kegiatan']) . "\" target=\"_blank\" class=\"avatar-group-item\" data-img=\"" . base_url('uploads/update_kegiatan/' . $v['video_kegiatan']) . "\" data-bs-toggle=\"tooltip\" data-bs-trigger=\"hover\" data-bs-placement=\"top\" title=\"Video Kegiatan\">
                            " . $v['video_kegiatan'] . "
                        </a>
                    </div>",
                "sesi_1" => $v['sesi_1'],
                "sesi_2" => $v['sesi_2'],
                "sesi_3" => $v['sesi_3'],
                "sesi_4" => $v['sesi_4'],
                "sesi_5" => $v['sesi_5'],
                "sesi_6" => $v['sesi_6'],
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
        $lokasi = $this->data['userdata']['lokasi_user'];
        $data['get_jadwal_kegiatan'] = $this->Update_kegiatan_m->getJadwalKegiatan($lokasi)->result_array();

        $this->template("pelaksanaan_harian/update_kegiatan/add_update_kegiatan_v", "Tambah Update Kegiatan", $data);
    }

    public function detail($id)
    {
        $data = array();
        $data['detail'] = $this->Update_kegiatan_m->getUpdateKegiatan($id)->row_array();

        $this->template("pelaksanaan_harian/update_kegiatan/detail_update_kegiatan_v", "Detail Update Kegiatan", $data);
    }

    public function update($id)
    {
        $data = array();
        $lokasi = $this->data['userdata']['lokasi_user'];
        $data['get_jadwal_kegiatan'] = $this->Update_kegiatan_m->getJadwalKegiatan($lokasi)->result_array();
        $data['selected'] = $this->Update_kegiatan_m->getUpdateKegiatan($id)->row_array();

        $this->template("pelaksanaan_harian/update_kegiatan/edit_update_kegiatan_v", "Detail Update Kegiatan", $data);
    }

    public function submit()
    {

        $post = $this->input->post();
        $id = $this->input->post('id');
        $jadwal_kegiatan_id = $this->input->post('jadwal_kegiatan_id');
        $tgl_kegiatan = $this->input->post('tgl_kegiatan');
        $sesi_1 = $this->input->post('sesi_1');
        $sesi_2 = $this->input->post('sesi_2');
        $sesi_3 = $this->input->post('sesi_3');
        $sesi_4 = $this->input->post('sesi_4');
        $sesi_5 = $this->input->post('sesi_5');
        $sesi_6 = $this->input->post('sesi_6');

        $sesi = [$sesi_1];
        if ($sesi_2 !== '') array_push($sesi, $sesi_2);
        if ($sesi_3 !== '') array_push($sesi, $sesi_3);
        if ($sesi_4 !== '') array_push($sesi, $sesi_4);
        if ($sesi_5 !== '') array_push($sesi, $sesi_5);
        if ($sesi_6 !== '') array_push($sesi, $sesi_6);

        if (!empty($_FILES['foto_registrasi']['name'])) {
            $_FILES['file']['name'] = $jadwal_kegiatan_id . '_fregistrasi_' . date('Ymdhis') . '_' . $_FILES['foto_registrasi']['name'];
            $_FILES['file']['type'] = $_FILES['foto_registrasi']['type'];
            $_FILES['file']['tmp_name'] = $_FILES['foto_registrasi']['tmp_name'];
            $_FILES['file']['error'] = $_FILES['foto_registrasi']['error'];
            $_FILES['file']['size'] = $_FILES['foto_registrasi']['size'];
            if ($this->upload->do_upload('file')) {
                $uploadFotoRegistrasi = $this->upload->data();
            }
        }

        if (!empty($_FILES['foto_pengarahan']['name'])) {
            $_FILES['file']['name'] = $jadwal_kegiatan_id . '_fpengarahan_' . date('Ymdhis') . '_' . $_FILES['foto_pengarahan']['name'];
            $_FILES['file']['type'] = $_FILES['foto_pengarahan']['type'];
            $_FILES['file']['tmp_name'] = $_FILES['foto_pengarahan']['tmp_name'];
            $_FILES['file']['error'] = $_FILES['foto_pengarahan']['error'];
            $_FILES['file']['size'] = $_FILES['foto_pengarahan']['size'];
            if ($this->upload->do_upload('file')) {
                $uploadFotoPengarahan = $this->upload->data();
            }
        }

        if (!empty($_FILES['foto_kegiatan_lain']['name'])) {
            $_FILES['file']['name'] = $jadwal_kegiatan_id . '_fkegiatan_lain_' . date('Ymdhis') . '_' . $_FILES['foto_kegiatan_lain']['name'];
            $_FILES['file']['type'] = $_FILES['foto_kegiatan_lain']['type'];
            $_FILES['file']['tmp_name'] = $_FILES['foto_kegiatan_lain']['tmp_name'];
            $_FILES['file']['error'] = $_FILES['foto_kegiatan_lain']['error'];
            $_FILES['file']['size'] = $_FILES['foto_kegiatan_lain']['size'];
            if ($this->upload->do_upload('file')) {
                $uploadFotoKegiatanLain = $this->upload->data();
            }
        }

        if (!empty($_FILES['video_kegiatan']['name'])) {
            $_FILES['file']['name'] = $jadwal_kegiatan_id . '_vkegiatan_' . date('Ymdhis') . '_' . $_FILES['video_kegiatan']['name'];
            $_FILES['file']['type'] = $_FILES['video_kegiatan']['type'];
            $_FILES['file']['tmp_name'] = $_FILES['video_kegiatan']['tmp_name'];
            $_FILES['file']['error'] = $_FILES['video_kegiatan']['error'];
            $_FILES['file']['size'] = $_FILES['video_kegiatan']['size'];
            if ($this->upload->do_upload('file')) {
                $uploadVideoKegiatan = $this->upload->data();
            }
        }

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('pelaksanaan_harian/update_kegiatan/add'));
        }

        $this->db->trans_begin();

        if (isset($id)) {
            $data = array(
                "jadwal_kegiatan_id" => $jadwal_kegiatan_id,
                "tgl_kegiatan" => $tgl_kegiatan,
                'updated_by' => $this->data['userdata']['employee_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            );
    
            foreach ($sesi as $index => $value) {
                $index = $index + 1;
                $data['sesi_' . $index] = $value;
            }

            if (isset($uploadFotoRegistrasi['file_name'])) $data['foto_registrasi'] = $uploadFotoRegistrasi['file_name'];
            if (isset($uploadFotoPengarahan['file_name'])) $data['foto_pengarahan'] = $uploadFotoPengarahan['file_name'];
            if (isset($uploadFotoKegiatanLain['file_name'])) $data['foto_kegiatan_lain'] = $uploadFotoKegiatanLain['file_name'];
            if (isset($uploadVideoKegiatan['file_name'])) $data['video_kegiatan'] = $uploadVideoKegiatan['file_name'];

            $this->db->where('id', $id);
            $simpan = $this->db->update('update_harian_kegiatan', $data);
        } else {
            $data = array(
                "jadwal_kegiatan_id" => $jadwal_kegiatan_id,
                "tgl_kegiatan" => $tgl_kegiatan,
                'foto_registrasi' => isset($uploadFotoRegistrasi['file_name']) ? $uploadFotoRegistrasi['file_name'] : '',
                'foto_pengarahan' => isset($uploadFotoPengarahan['file_name']) ? $uploadFotoPengarahan['file_name'] : '',
                'foto_kegiatan_lain' => isset($uploadFotoKegiatanLain['file_name']) ? $uploadFotoKegiatanLain['file_name'] : '',
                'video_kegiatan' => isset($uploadVideoKegiatan['file_name']) ? $uploadVideoKegiatan['file_name'] : '',
                'created_by' => $this->data['userdata']['employee_id'],
                'created_at' => date('Y-m-d H:i:s'),
            );
    
            foreach ($sesi as $index => $value) {
                $index = $index + 1;
                $data['sesi_' . $index] = $value;
            }

            $simpan = $this->db->insert('update_harian_kegiatan', $data);
        }

        if ($simpan) {
            if ($this->db->trans_status() === FALSE) {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }

            redirect(site_url('pelaksanaan_harian/update_kegiatan'));
        } else {
            $this->renderMessage("error");
        }
    }
}
