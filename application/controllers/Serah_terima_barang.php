<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Serah_terima_barang extends Telescoope_Controller
{

    var $data;

    public function __construct() {
        
        parent::__construct();

        $this->load->model(array("Administration_m", "Serah_terima_barang_m", "Lokasi_skd_m"));

        $this->data['date_format'] = "h:i A | d M Y";

        $this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');

        $this->data['data'] = array();

        $this->data['post'] = $this->input->post();

        $userdata = $this->Administration_m->getLogin();

        $this->data['dir'] = 'serah_terima_barang';

        $this->data['controller_name'] = $this->uri->segment(1);

        $dir = './uploads/' . $this->data['dir'];

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['overwrite'] = false;
        $config['max_size'] = 4088;
        $config['upload_path'] = $dir;
        $this->load->library('upload', $config);

        $this->data['userdata'] = (!empty($userdata)) ? $userdata : array();

        $sess = $this->session->userdata(do_hash(SESSION_PREFIX));

        $position1 = $this->Administration_m->getPosition("ADMINISTRATOR");
        $position2 = $this->Administration_m->getPosition("PUSAT");

        if(!$position1 && !$position2 && !$position3){
            $this->noAccess("Anda tidak memiliki hak akses untuk halaman ini.");
        }

        if(empty($sess)){
            redirect(site_url('log/in'));
        }
    }

    public function index(){
        $data = array();

        $this->template("serah_terima_barang/list_serah_terima_barang_v", "Data Serah Terima Barang", $data);
    }

    public function get_data(){
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

        $result = $this->Serah_terima_barang_m->getDismantle()->result_array();

        if (!empty($search)) {
            // $this->db->group_start();
            // $this->db->like('test', $search);
            // $this->db->or_like('test', $search);
            // $this->db->group_end();
        }

        $count = $this->Serah_terima_barang_m->getDismantle()->num_rows();

        $totalRecords = $count;
        $totalRecordwithFilter = $count;

        $data = array();
        
        foreach($result as $v) {   
            
            $action = '<div class="btn-group" role="group">
                        <a href="' .  site_url('serah_terima_barang/upload_foto/' . $v['id']) . '" class="btn btn-sm btn-warning">Edit</a>
                        <a href="' .  site_url('serah_terima_barang/delete/' . $v['id']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Apakah Anda yakin?\');">Hapus</a>
                    </div>';
            
            $data[] = array(                                
                "kode_serah_terima" => $v['kode_serah_terima'],
                "province_name" => $v['province_name'],
                "regency_name" => $v['regency_name'],
                "nama_lokasi" => $v['kode_lokasi'] . ' | ' . $v['nama_lokasi'],
                "nama_penerima" => $v['nama_penerima'],
                "tgl_kegiatan" => $v['tgl_kegiatan'],
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
        $data['get_lokasi'] = $this->Lokasi_skd_m->getLokasi()->result_array();
        
        $this->template("serah_terima_barang/add_serah_terima_barang_v", "Tambah Serah Terima Barang", $data);
    }

    public function update($id){
        $data = array();        
        
        $position = $this->Administration_m->getPosition("KOORDINATOR");

        $data['get_uji'] = $this->Serah_terima_barang_m->getUji($id)->row_array();
        $data['get_detail'] = $this->Serah_terima_barang_m->getDetail($id)->result_array();
        $data['get_penerimaan'] = $this->Penerimaan_barang_m->getPenerimaan_barang()->result_array();        

        if($position) {
            $data['get_penerimaan'] = $this->Penerimaan_barang_m->getPenerimaan_barang("", $this->data['userdata']['lokasi_skd_id'])->result_array();
        }

        $this->template("serah_terima_barang/detail_serah_terima_barang_v", "Detail Serah Terima Barang", $data);
    }

    public function submit_data(){

        $post = $this->input->post(); 
        $keterangan = $post['keterangan'];

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan benar.");
            redirect(site_url('serah_terima_barang'));
        }

        $this->db->trans_begin();

        $data = array(
            "lokasi_skd_id" => $post['lokasi_skd_id'],
            'nama_penerima' => $post['nama_penerima'],
            'nama_penyedia' => $post['nama_penyedia'],
            'tgl_kegiatan' => $post['tgl_kegiatan'],
            'jabatan' => $post['jabatan'],
            'nip' => $post['nip'],
            'alamat_kegiatan' => $post['alamat_kegiatan'],
            'catatan' => $post['catatan'],
            'created_by' => $this->data['userdata']['employee_id'],
            'created_at' => date('Y-m-d H:i:s'),
        );

        $simpan = $this->db->insert('serah_terima', $data);
        
        $dir = './uploads/' . $this->data['dir'];        

        if($simpan) {
            $insert_id = $this->db->insert_id();

            $id = strval($insert_id); 
            $res = str_repeat('0', 5 - strlen($id)).$id;   

            $this->db->set('kode_serah_terima', 'STB.' . $res)->where('id', $insert_id)->update('serah_terima');

            if(!empty($_FILES['foto_kegiatan']['name'])) {
                $data_insert = array();
            
                foreach ($_FILES['foto_kegiatan']['name'] as $key => $file_name) {
                    $_FILES['file']['name'] = $this->data['userdata']['employee_id'] . '_detail_barang_' . date('His') . '_' . $file_name;
                    $_FILES['file']['type'] = $_FILES['foto_kegiatan']['type'][$key];
                    $_FILES['file']['tmp_name'] = $_FILES['foto_kegiatan']['tmp_name'][$key];
                    $_FILES['file']['error'] = $_FILES['foto_kegiatan']['error'][$key];
                    $_FILES['file']['size'] = $_FILES['foto_kegiatan']['size'][$key];
            
                    if ($this->upload->do_upload('file')) {
                        $uploadKtp = $this->upload->data();
                        $data_insert[] = array(
                            'file_path' => isset($uploadKtp['file_name']) ? $uploadKtp['file_name'] : '',
                            'keterangan' => $keterangan[$key],
                        );
                    }
                }     
                
                foreach ($data_insert as $insert_data) {
                    $data = array(
                        "serah_terima_id" => $insert_id,
                        'foto_kegiatan' => $insert_data['file_path'],
                        'keterangan' => $insert_data['keterangan'],
                        'created_by' => $this->data['userdata']['employee_id'],
                        'created_at' => date('Y-m-d H:i:s'),
                    );
            
                    $simpan = $this->db->insert('serah_terima_foto', $data);                
                }
            }                    
        }
        
        if ($this->db->trans_status() === FALSE)  {
            $this->setMessage("Failed save data.");
            $this->db->trans_rollback();
        } else {
            $this->setMessage("Success save data.");
            $this->db->trans_commit();
        }            

        redirect(site_url('serah_terima_barang'));        
    }

    public function submit_update_detail_foto(){

        $post = $this->input->post(); 
        $foto_exist = $post['foto_exist'];
        $catatan_foto = $post['catatan_foto'];
        $detail_foto_id = $post['detail_foto_id'];

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan benar.");
            redirect(site_url('serah_terima_barang/detail_foto/' . $post['detail_id']));
        }

        $this->db->trans_begin();
        
        $dir = './uploads/' . $this->data['dir'];        

        if (!empty($foto_exist)) {
            $data_insert = array();
        
            foreach ($foto_exist as $key => $v) {
                
                $file_name = isset($_FILES['foto_barang']['name'][$key]) ? $_FILES['foto_barang']['name'][$key] : '';
                
                if (!empty($file_name)) {
                    $_FILES['file']['name'] = $this->data['userdata']['employee_id'] . '_detail_barang_' . date('His') . '_' . $file_name;
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

            redirect(site_url('serah_terima_barang/upload_foto/' . $post['uji_penerimaan_id']));
        
        } else {
            $this->renderMessage("error");
        }
    }

    public function delete($id) {
        $this->db->trans_begin();

        $this->db->where('serah_terima_id', $id);
        $detail = $this->db->delete('serah_terima_foto');

        $this->db->where('id', $id);
        $header = $this->db->delete('serah_terima');

        if ($header) {
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->setMessage("Gagal hapus data.");
                redirect(site_url('serah_terima_barang'));
    
            } else {
                $this->db->trans_commit();
                $this->setMessage("Berhasil hapus data.");
                redirect(site_url('serah_terima_barang'));
            }
        } else {
            $this->db->trans_rollback();
            $this->setMessage("Gagal hapus data.");
            redirect(site_url('serah_terima_barang'));
        }
    }
}
