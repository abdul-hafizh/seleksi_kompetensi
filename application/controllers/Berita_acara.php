<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Berita_acara extends Telescoope_Controller
{

    var $data;

    public function __construct() {
        
        parent::__construct();

        $this->load->model(array("Administration_m", "Berita_acara_m", "Lokasi_skd_m"));

        $this->data['date_format'] = "h:i A | d M Y";

        $this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');

        $this->data['data'] = array();

        $this->data['post'] = $this->input->post();

        $userdata = $this->Administration_m->getLogin();

        $this->data['dir'] = 'berita_acara';

        $this->data['controller_name'] = $this->uri->segment(1);

        $dir = './uploads/' . $this->data['dir'];

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $config['allowed_types'] = '*';
        $config['overwrite'] = false;
        $config['max_size'] = 50920;
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

        if(empty($sess)){
            redirect(site_url('log/in'));
        }
    }

    public function index(){
        $data = array();
        $data['job_title'] = $this->data['userdata']['job_title'];

        $this->template("berita_acara/list_berita_acara_v", "Data Berita Acara", $data);
    }

    public function get_data(){
        $post = $this->input->post();            
        $draw = $post['draw'];
        $row = $post['start'];
        $rowperpage = $post['length']; 
        $search = $post['search']['value']; 
        $columnIndex = $post['order'][0]['column'];
        $columnName = $post['columns'][$columnIndex]['data'];

        $position = $this->Administration_m->getPosition("KOORDINATOR");
        $position2 = $this->Administration_m->getPosition("PENGAWAS");
        $position3 = $this->Administration_m->getPosition("ADMINISTRATOR");
        $position4 = $this->Administration_m->getPosition("ADMIN");
        $position5 = $this->Administration_m->getPosition("SUPERVISOR/REGIONAL");
                        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('province_name', $search);
            $this->db->or_like('regency_name', $search);
            $this->db->or_like('nama_lokasi', $search);
            $this->db->or_like('judul_berita', $search);
            $this->db->or_like('jenis_berita_acara', $search);
            $this->db->group_end();
        }

        $this->db->limit($rowperpage, $row);

        $result = $this->Berita_acara_m->getBerita()->result_array();
        
        if($position || $position2 || $position4) {
            $result = $this->Berita_acara_m->getBerita("", $this->data['userdata']['lokasi_skd_id'])->result_array();
        }

        if($position5) {
            $result = $this->Berita_acara_m->getBerita("", "", $this->data['userdata']['lokasi_user'])->result_array();
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('province_name', $search);
            $this->db->or_like('regency_name', $search);
            $this->db->or_like('nama_lokasi', $search);
            $this->db->or_like('judul_berita', $search);
            $this->db->or_like('jenis_berita_acara', $search);
            $this->db->group_end();
        }

        $count = $this->Berita_acara_m->getBerita()->num_rows();
        
        if($position || $position2 || $position4) { 
            $count = $this->Berita_acara_m->getBerita("", $this->data['userdata']['lokasi_skd_id'])->num_rows();
        }

        if($position5) { 
            $count = $this->Berita_acara_m->getBerita("", "", $this->data['userdata']['lokasi_user'])->num_rows();
        }

        $totalRecords = $count;
        $totalRecordwithFilter = $count;

        $data = array();
        $lampiran = '<span class="badge bg-secondary">No File</span>';       
        
        foreach($result as $v) {   

            $file_path = FCPATH . 'uploads/berita_acara/' . $v['file_lampiran'];
            
            $action = '<div class="btn-group" role="group">
                        <a href="' .  site_url('berita_acara/update/' . $v['id']) . '" class="btn btn-sm btn-primary">Detail</a>
                    </div>';
                    
            if($position) {                         
                $action = '<div class="btn-group" role="group">
                    <a href="' .  site_url('berita_acara/update/' . $v['id']) . '" class="btn btn-sm btn-warning">Edit</a>
                    <a href="' .  site_url('berita_acara/delete/' . $v['id']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Apakah Anda yakin?\');">Hapus</a>
                </div>';
            }

            if(!empty($v['file_lampiran']) && file_exists($file_path)) {
                $lampiran = '<a href="' . base_url('uploads/berita_acara/' . $v['file_lampiran']) . '" class="btn btn-sm btn-info" target="_blank">View</a>';
            }

            $data[] = array(                                
                "province_name" => $v['province_name'],
                "regency_name" => $v['regency_name'],
                "nama_lokasi" => $v['kode_lokasi'] . ' | ' . $v['nama_lokasi'],
                "judul_berita" => $v['judul_berita'],
                "jenis_berita_acara" => $v['jenis_berita_acara'],
                "tgl_kegiatan" => $v['tgl_kegiatan'],
                "file_lampiran" => $lampiran,
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
    
    public function add(){
        $data = array();        
        $position = $this->Administration_m->getPosition("KOORDINATOR");

        $data['get_jenis'] = $this->db->get('adm_jenis_ba')->result_array();
        $data['get_lokasi'] = $this->Lokasi_skd_m->getLokasi()->result_array();
        if($position) {
            $data['get_lokasi'] = $this->Lokasi_skd_m->getLokasi($this->data['userdata']['lokasi_skd_id'])->result_array();
        }
        
        $this->template("berita_acara/add_berita_acara_v", "Tambah Berita Acara", $data);
    }

    public function update($id){
        $data = array();                
        $position = $this->Administration_m->getPosition("KOORDINATOR");

        $data['job_title'] = $this->data['userdata']['job_title'];
        $data['get_jenis'] = $this->db->get('adm_jenis_ba')->result_array();
        $data['get_lokasi'] = $this->Lokasi_skd_m->getLokasi()->result_array();
        if($position) {
            $data['get_lokasi'] = $this->Lokasi_skd_m->getLokasi($this->data['userdata']['lokasi_skd_id'])->result_array();
        }

        $data['get_row'] = $this->Berita_acara_m->getBerita($id)->row_array();

        $this->template("berita_acara/edit_berita_acara_v", "Edit Berita Acara", $data);
    }

    public function submit_data(){

        $post = $this->input->post(); 

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan benar.");
            redirect(site_url('berita_acara'));
        }

        $this->db->trans_begin();

        $dir = './uploads/' . $this->data['dir'];

        if(!empty($_FILES['file_lampiran']['name'])){
            $_FILES['file']['name'] = $this->data['userdata']['employee_id'] . '_BA_' . date('his') . '_' . $_FILES['file_lampiran']['name'];
            $_FILES['file']['type'] = $_FILES['file_lampiran']['type'];
            $_FILES['file']['tmp_name'] = $_FILES['file_lampiran']['tmp_name'];
            $_FILES['file']['error'] = $_FILES['file_lampiran']['error'];
            $_FILES['file']['size'] = $_FILES['file_lampiran']['size'];
            if($this->upload->do_upload('file')){ 
                $uploadLampiran = $this->upload->data(); 
            } else {
                $error = $this->upload->display_errors();
                if (strpos($error, 'The file you are attempting to upload exceeds the maximum allowed size') !== false) {
                    $this->setMessage("Ukuran yang diizinkan 5 Mb.");
                    $this->db->trans_rollback();
                }
                $this->setMessage("Gagal upload file.");
                $this->db->trans_rollback();
            }
        }

        $data = array(
            "lokasi_skd_id" => $post['lokasi_skd_id'],
            'jenis_ba_id' => $post['jenis_ba_id'],
            'judul_berita' => $post['judul_berita'],
            'deskripsi' => $post['deskripsi'],
            'file_lampiran' => isset($uploadLampiran['file_name']) ? $uploadLampiran['file_name'] : '',
            'tgl_kegiatan' => $post['tgl_kegiatan'],
            'created_by' => $this->data['userdata']['employee_id'],
            'created_at' => date('Y-m-d H:i:s'),
        );

        $simpan = $this->db->insert('berita_acara', $data);        
        
        if ($this->db->trans_status() === FALSE)  {
            $this->setMessage("Failed save data.");
            $this->db->trans_rollback();
        } else {
            $this->setMessage("Success save data.");
            $this->db->trans_commit();
        }            

        redirect(site_url('berita_acara'));        
    }

    public function submit_update(){

        $post = $this->input->post(); 

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan benar.");
            redirect(site_url('berita_acara'));
        }

        $this->db->trans_begin();

        $file_path = FCPATH . 'uploads/berita_acara/' . $post['file_lampiran_exist'];

        $row_data = $this->Berita_acara_m->getBerita($post['id'])->row_array();

        if(!empty($_FILES['file_lampiran']['name'])){                        

            $_FILES['file']['name'] = $this->data['userdata']['employee_id'] . '_BA_' . date('his') . '_' . $_FILES['file_lampiran']['name'];
            $_FILES['file']['type'] = $_FILES['file_lampiran']['type'];
            $_FILES['file']['tmp_name'] = $_FILES['file_lampiran']['tmp_name'];
            $_FILES['file']['error'] = $_FILES['file_lampiran']['error'];
            $_FILES['file']['size'] = $_FILES['file_lampiran']['size'];
            if($this->upload->do_upload('file')){ 
                if (file_exists($file_path)) {
                    unlink($file_path);
                }                
                $uploadLampiran = $this->upload->data(); 
            } else {
                $error = $this->upload->display_errors();
                if (strpos($error, 'The file you are attempting to upload exceeds the maximum allowed size') !== false) {
                    $this->setMessage("Ukuran yang diizinkan 5 Mb.");
                    $this->db->trans_rollback();
                }
                $this->setMessage("Gagal upload file.");
                $this->db->trans_rollback();
            }
        }

        $data = array(
            "lokasi_skd_id" => $post['lokasi_skd_id'],
            'jenis_ba_id' => $post['jenis_ba_id'],
            'judul_berita' => $post['judul_berita'],
            'file_lampiran' => isset($uploadLampiran['file_name']) ? $uploadLampiran['file_name'] : $row_data['file_lampiran'],
            'deskripsi' => $post['deskripsi'],
            'tgl_kegiatan' => $post['tgl_kegiatan'],
            'updated_by' => $this->data['userdata']['employee_id'],
            'updated_at' => date('Y-m-d H:i:s')
        );

        $this->db->where('id', $post['id_berita']);
        $update = $this->db->update('berita_acara', $data);

        if ($this->db->trans_status() === FALSE)  {
            $this->setMessage("Failed update data.");
            $this->db->trans_rollback();
        } else {
            $this->setMessage("Success update data.");
            $this->db->trans_commit();
        }            

        redirect(site_url('berita_acara'));        
    }

    public function delete($id) {
        $this->db->trans_begin();
    
        $this->db->where('id', $id);
        $header = $this->db->get('berita_acara')->row();
    
        if ($header) {
            $file_path = FCPATH . 'uploads/berita_acara/' . $header->file_lampiran;

            if (file_exists($file_path)) {
                unlink($file_path);
            }
    
            $this->db->where('id', $id);
            $this->db->delete('berita_acara');
    
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->setMessage("Gagal hapus data.");
                redirect(site_url('berita_acara'));
        
            } else {
                $this->db->trans_commit();
                $this->setMessage("Berhasil hapus data.");
                redirect(site_url('berita_acara'));
            }
        } else {
            $this->db->trans_rollback();
            $this->setMessage("Data tidak ditemukan.");
            redirect(site_url('berita_acara'));
        }
    }    
}
