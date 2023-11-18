<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Uji_fungsi_barang extends Telescoope_Controller
{

    var $data;

    public function __construct() {
        
        parent::__construct();

        $this->load->model(array("Administration_m", "Uji_fungsi_barang_m", "Penerimaan_barang_m"));

        $this->data['date_format'] = "h:i A | d M Y";

        $this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');

        $this->data['data'] = array();

        $this->data['post'] = $this->input->post();

        $userdata = $this->Administration_m->getLogin();

        $this->data['dir'] = 'uji_fungsi_barang';

        $this->data['controller_name'] = $this->uri->segment(1);

        $dir = './uploads/' . $this->data['dir'];

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['overwrite'] = false;
        $config['max_size'] = 5120;
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

        $this->template("uji_fungsi_barang/list_uji_fungsi_barang_v", "Data Uji Fungsi Barang", $data);
    }

    public function get_data(){
        $post = $this->input->post();    
        
        $position = $this->Administration_m->getPosition("KOORDINATOR");
        $position2 = $this->Administration_m->getPosition("PENGAWAS");
        $position3 = $this->Administration_m->getPosition("ADMINISTRATOR");
        $position4 = $this->Administration_m->getPosition("ADMIN");
        $position5 = $this->Administration_m->getPosition("SUPERVISOR/REGIONAL");
        
        $draw = $post['draw'];
        $row = $post['start'];
        $rowperpage = $post['length']; 
        $search = $post['search']['value']; 
        $columnIndex = $post['order'][0]['column'];
        $columnName = $post['columns'][$columnIndex]['data'];
                        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('province_name', $search);
            $this->db->or_like('regency_name', $search);
            $this->db->or_like('nama_lokasi', $search);
            $this->db->group_end();
        }

        $this->db->limit($rowperpage, $row);

        $result = $this->Uji_fungsi_barang_m->getUji()->result_array();

        if($position || $position2 || $position4) {
            $result = $this->Uji_fungsi_barang_m->getUji("", $this->data['userdata']['lokasi_skd_id'])->result_array();
        }

        if($position5) {
            $result = $this->Uji_fungsi_barang_m->getUji("", "", $this->data['userdata']['lokasi_user'])->result_array();
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('province_name', $search);
            $this->db->or_like('regency_name', $search);
            $this->db->or_like('nama_lokasi', $search);
            $this->db->group_end();
        }

        $count = $this->Uji_fungsi_barang_m->getUji()->num_rows();

        if($position || $position2 || $position4) {
            $count = $this->Uji_fungsi_barang_m->getUji("", $this->data['userdata']['lokasi_skd_id'])->num_rows();
        }

        if($position5) {
            $count = $this->Uji_fungsi_barang_m->getUji("", "", $this->data['userdata']['lokasi_user'])->num_rows();
        }

        $totalRecords = $count;
        $totalRecordwithFilter = $count;

        $data = array();
        
        foreach($result as $v) {   
            
            $action = '<div class="btn-group" role="group">
                        <a href="' .  site_url('uji_fungsi_barang/upload_foto/' . $v['id_uji']) . '" class="btn btn-sm btn-primary">Detail</a>
                    </div>';
                    
            if($position || $position3) {
                $action = '<div class="btn-group" role="group">
                        <a href="' .  site_url('uji_fungsi_barang/upload_foto/' . $v['id_uji']) . '" class="btn btn-sm btn-success">Upload Foto</a>
                        <a href="' .  site_url('uji_fungsi_barang/delete/' . $v['id_uji']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Apakah Anda yakin?\');">Hapus</a>
                    </div>';

                if($v['status_uji'] == 'Approved'){
                    $action = '<div class="btn-group" role="group">
                        <a href="' .  site_url('uji_fungsi_barang/upload_foto/' . $v['id_uji']) . '" class="btn btn-sm btn-primary">Detail</a>
                    </div>';
                }
            }
                
            if($position2) {                    
                $action = '<div class="btn-group" role="group">
                        <a href="' .  site_url('uji_fungsi_barang/upload_foto/' . $v['id_uji']) . '" class="btn btn-sm btn-warning">Edit</a>
                    </div>';
            }

            $status = $v['status_uji'] == 'Pending' ? '<span class="badge bg-secondary">Pending</span>' : '<span class="badge bg-success">Approved</span>';

            if($position) {
                $status = $v['status_uji'] == 'Pending' ? '<span class="badge bg-secondary">Waiting Approval</span>' : '<span class="badge bg-success">Approved</span>';
            }
            
            $data[] = array(                                
                "kode_uji" => $v['kode_uji'],
                "kode_penerimaan" => $v['kode_penerimaan'],
                "province_name" => $v['province_name'],
                "regency_name" => $v['regency_name'],
                "nama_lokasi" => $v['kode_lokasi'] . ' | ' . $v['nama_lokasi'],
                "tgl_terima" => $v['tgl_terima'],
                "tgl_uji" => $v['jadwal_kegiatan'],
                "status" => $status,
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
        $position2 = $this->Administration_m->getPosition("PENGAWAS");

        $data['get_penerimaan'] = $this->Penerimaan_barang_m->getPenerimaan_barang()->result_array();

        if($position || $position2) {
            $data['get_penerimaan'] = $this->Penerimaan_barang_m->getPenerimaan_barang("", $this->data['userdata']['lokasi_skd_id'])->row_array();
        } else {
            $this->noAccess("Hanya koordinator yang dapat melakukan tambah data.");
        }
        
        $this->template("uji_fungsi_barang/add_uji_fungsi_barang_v", "Tambah Uji Fungsi Barang", $data);
    }

    public function detail($id){
        $data = array();
        
        $position = $this->Administration_m->getPosition("KOORDINATOR");

        $data['get_uji'] = $this->Uji_fungsi_barang_m->getUji($id)->row_array();
        $data['get_detail'] = $this->Uji_fungsi_barang_m->getDetail($id)->result_array();
        $data['get_penerimaan'] = $this->Penerimaan_barang_m->getPenerimaan_barang()->result_array();  
        $data['job_title'] = $this->data['userdata']['job_title'];      

        if($position) {
            $data['get_penerimaan'] = $this->Penerimaan_barang_m->getPenerimaan_barang("", $this->data['userdata']['lokasi_skd_id'])->result_array();
        }

        $this->template("uji_fungsi_barang/detail_uji_fungsi_barang_v", "Detail Uji Fungsi Barang", $data);
    }

    public function upload_foto($id){
        $data = array();
        
        $position = $this->Administration_m->getPosition("KOORDINATOR");
        $position2 = $this->Administration_m->getPosition("PENGAWAS");
        $position3 = $this->Administration_m->getPosition("ADMIN");

        $data['get_uji'] = $this->Uji_fungsi_barang_m->getUji($id)->row_array();
        $data['get_detail'] = $this->Uji_fungsi_barang_m->getDetail($id, 'Non-IT')->result_array();
        $data['get_penerimaan'] = $this->Penerimaan_barang_m->getPenerimaan_barang()->result_array();
        $data['job_title'] = $this->data['userdata']['job_title'];

        if($position || $position2 || $position3) {
            $data['get_penerimaan'] = $this->Penerimaan_barang_m->getPenerimaan_barang("", $this->data['userdata']['lokasi_skd_id'])->result_array();
        }

        $this->template("uji_fungsi_barang/upload_uji_fungsi_barang_v", "Detail Uji Fungsi Barang", $data);
    }

    public function detail_foto($id){
        $data = array();
        $data['get_detail'] = $this->Uji_fungsi_barang_m->getDetailFoto($id)->row_array();
        $data['get_foto'] = $this->Uji_fungsi_barang_m->getDetailFotoUploaded($id)->result_array();
        $data['job_title'] = $this->data['userdata']['job_title'];

        $this->template("uji_fungsi_barang/detail_upload_uji_fungsi_barang_v", "Upload Foto Uji Fungsi Barang", $data);
    }

    public function submit_data(){

        $simpan = false;
        $post = $this->input->post(); 
        $catatan = $this->input->post('catatan');
        $status_baik = $this->input->post('status_baik');
        $status_tidak = $this->input->post('status_tidak');
        $barang_id = $this->input->post('barang_id');

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('uji_fungsi_barang/add'));
        }

        $this->db->trans_begin();

        $cek_data = $this->db->select('*')->from('jadwal_kegiatan')->where('lokasi_skd_id', $post['lokasi_skd_id'])->get()->row_array();

        if ($post['jadwal_kegiatan'] >= $cek_data['tgl_mulai'] && $post['jadwal_kegiatan'] <= $cek_data['tgl_selesai']) {

            $cek_num = $this->db->select('id')->from('uji_penerimaan_barang')->where('jadwal_kegiatan', $post['jadwal_kegiatan'])->where('penerimaan_id', $post['penerimaan_id'])->get()->num_rows();

            if($cek_num < 1) {                
                $data = array(
                    "penerimaan_id" => $post['penerimaan_id'],
                    'jadwal_kegiatan' => $post['jadwal_kegiatan'],
                    'status_uji' => 'Pending',
                    'catatan_uji' => $post['catatan_uji'],
                    'created_by' => $this->data['userdata']['employee_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                );
        
                $simpan = $this->db->insert('uji_penerimaan_barang', $data);
            } else {
                $this->setMessage("Tanggal kegiatan sudah ada.");
                $this->db->trans_rollback();
                redirect(site_url('uji_fungsi_barang/add'));    
            }

        } else {
            $this->setMessage("Tanggal kegiatan harus sesuai Jadwal.");
            $this->db->trans_rollback();
            redirect(site_url('uji_fungsi_barang/add'));
        }        
        
        if($simpan){        
            
            $insert_id = $this->db->insert_id();

            $id = strval($insert_id); 
            $res = str_repeat('0', 5 - strlen($id)).$id;   

            $this->db->set('kode_uji', 'UFB.' . $res)->where('id', $insert_id)->update('uji_penerimaan_barang');

            $dir = './uploads/' . $this->data['dir'];

            if (!empty($status_baik)) {
                $data_insert = array();
            
                foreach ($status_baik as $key => $v) {
                    $data_insert[] = array(
                        'catatan' => $catatan[$key],
                        'status_baik' => $status_baik[$key],
                        'status_tidak' => $status_tidak[$key],
                        'barang_id' => $barang_id[$key],
                    );
                }                     
                
                foreach ($data_insert as $insert_data) {
                    $detail = array(
                        "uji_penerimaan_id" => $insert_id,
                        "catatan" => $insert_data['catatan'],
                        "status_baik" => $insert_data['status_baik'],
                        "status_tidak" => $insert_data['status_tidak'],
                        "barang_id" => $insert_data['barang_id'],
                        'created_by' => $this->data['userdata']['employee_id'],
                        'created_at' => date('Y-m-d H:i:s'),
                    );    
                    $simpan_detail = $this->db->insert('uji_penerimaan_detail', $detail);
                }
            }            

            if ($this->db->trans_status() === FALSE)  {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }            

            redirect(site_url('uji_fungsi_barang'));
        
        } else {
            $this->renderMessage("error");
        }
    }

    public function submit_update(){

        $post = $this->input->post(); 

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('uji_fungsi_barang/add'));
        }

        $this->db->trans_begin();
        
        $data = array(
            'status_uji' => $post['status_uji'],
            'jadwal_kegiatan' => $post['jadwal_kegiatan'],
            'catatan_uji' => $post['catatan_uji'],
            'updated_by' => $this->data['userdata']['employee_id'],
            'updated_at' => date('Y-m-d H:i:s')
        );

        $this->db->where('id', $post['id_uji']);
        $update = $this->db->update('uji_penerimaan_barang', $data);
        
        if($update){             
            if ($this->db->trans_status() === FALSE)  {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }            

            redirect(site_url('uji_fungsi_barang'));
        
        } else {
            $this->renderMessage("error");
        }
    }

    public function submit_detail_foto(){

        $post = $this->input->post(); 
        $catatan_foto = $post['catatan_foto'];

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan benar.");
            redirect(site_url('uji_fungsi_barang/detail_foto/' . $post['detail_id']));
        }

        $this->db->trans_begin();
        
        $dir = './uploads/' . $this->data['dir'];

        if (!empty($_FILES['foto_barang']['name'])) {
            $data_insert = array();
        
            foreach ($_FILES['foto_barang']['name'] as $key => $file_name) {
                $_FILES['file']['name'] = $this->data['userdata']['employee_id'] . '_detail_barang_' . $key+1 . '_' . date('His') . '_' . $file_name;
                $_FILES['file']['type'] = $_FILES['foto_barang']['type'][$key];
                $_FILES['file']['tmp_name'] = $_FILES['foto_barang']['tmp_name'][$key];
                $_FILES['file']['error'] = $_FILES['foto_barang']['error'][$key];
                $_FILES['file']['size'] = $_FILES['foto_barang']['size'][$key];
        
                if ($this->upload->do_upload('file')) {
                    $uploadKtp = $this->upload->data();
                    $data_insert[] = array(
                        'file_path' => isset($uploadKtp['file_name']) ? $uploadKtp['file_name'] : '',
                        'catatan_foto' => $catatan_foto[$key],
                    );
                }
            }     
            
            foreach ($data_insert as $insert_data) {
                $data = array(
                    "uji_header_id" => $post['uji_penerimaan_id'],
                    "uji_detail_id" => $post['detail_id'],
                    'foto_barang' => $insert_data['file_path'],
                    'catatan_foto' => $insert_data['catatan_foto'],
                    'created_by' => $this->data['userdata']['employee_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                );
        
                $simpan = $this->db->insert('uji_detail_foto', $data);        
            }
        }                    
        
        if($simpan){
            if ($this->db->trans_status() === FALSE)  {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }            

            redirect(site_url('uji_fungsi_barang/upload_foto/' . $post['uji_penerimaan_id']));
        
        } else {
            $this->renderMessage("error");
        }
    }

    public function submit_update_detail_foto(){

        $post = $this->input->post(); 
        $foto_exist = $post['foto_exist'];
        $catatan_foto = $post['catatan_foto'];
        $detail_foto_id = $post['detail_foto_id'];

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan benar.");
            redirect(site_url('uji_fungsi_barang/detail_foto/' . $post['detail_id']));
        }

        $this->db->trans_begin();
        
        $dir = './uploads/' . $this->data['dir'];

        if (!empty($foto_exist)) {
            $data_insert = array();
        
            foreach ($foto_exist as $key => $v) {
                
                $file_name = isset($_FILES['foto_barang']['name'][$key]) ? $_FILES['foto_barang']['name'][$key] : '';
                
                if (!empty($file_name)) {
                    $_FILES['file']['name'] = $this->data['userdata']['employee_id'] . '_detail_barang_' . $key+1 . '_' . date('His') . '_' . $file_name;
                    $_FILES['file']['type'] = $_FILES['foto_barang']['type'][$key];
                    $_FILES['file']['tmp_name'] = $_FILES['foto_barang']['tmp_name'][$key];
                    $_FILES['file']['error'] = $_FILES['foto_barang']['error'][$key];
                    $_FILES['file']['size'] = $_FILES['foto_barang']['size'][$key];

                    if ($this->upload->do_upload('file')) {
                        $uploadKtp = $this->upload->data();
                        $data_insert[] = array(
                            'file_path' => isset($uploadKtp['file_name']) ? $uploadKtp['file_name'] : '',
                            'catatan_foto' => $catatan_foto[$key],
                            'detail_foto_id' => $detail_foto_id[$key],
                        );
                    }
                } else {
                    $data_insert[] = array(
                        'file_path' => $foto_exist[$key],
                        'catatan_foto' => $catatan_foto[$key],
                        'detail_foto_id' => $detail_foto_id[$key],
                    );
                }
            }     
            
            foreach ($data_insert as $insert_data) {
                $data_update = array(
                    'foto_barang' => $insert_data['file_path'],
                    'catatan_foto' => $insert_data['catatan_foto'],
                    'updated_by' => $this->data['userdata']['employee_id'],
                    'updated_at' => date('Y-m-d H:i:s')
                );
        
                $this->db->where('id', $insert_data['detail_foto_id']);
                $update = $this->db->update('uji_detail_foto', $data_update);
            }
        }
        
        if($update){
            if ($this->db->trans_status() === FALSE)  {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }            

            redirect(site_url('uji_fungsi_barang/upload_foto/' . $post['uji_penerimaan_id']));
        
        } else {
            $this->renderMessage("error");
        }
    }

    public function get_barang()
    {        
        $penerimaan_id = $this->input->post('penerimaan_id', true);
        $data = $this->Penerimaan_barang_m->getDetail($penerimaan_id)->result_array();
        
        echo json_encode($data);
    }

    public function delete($id) {
        $this->db->trans_begin();

        $this->db->where('uji_header_id', $id);
        $foto = $this->db->delete('uji_detail_foto');

        $this->db->where('uji_penerimaan_id', $id);
        $detail = $this->db->delete('uji_penerimaan_detail');

        $this->db->where('id', $id);
        $header = $this->db->delete('uji_penerimaan_barang');

        if ($header) {
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->setMessage("Gagal hapus data.");
                redirect(site_url('uji_fungsi_barang'));
    
            } else {
                $this->db->trans_commit();
                $this->setMessage("Berhasil hapus data.");
                redirect(site_url('uji_fungsi_barang'));
            }
        } else {
            $this->db->trans_rollback();
            $this->setMessage("Gagal hapus data.");
            redirect(site_url('uji_fungsi_barang'));
        }
    }
}
