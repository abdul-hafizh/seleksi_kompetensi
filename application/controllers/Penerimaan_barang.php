<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Penerimaan_barang extends Telescoope_Controller
{

    var $data;

    public function __construct() {
        
        parent::__construct();

        $this->load->model(array("Administration_m", "Penerimaan_barang_m", "Pengiriman_barang_m"));

        $this->data['date_format'] = "h:i A | d M Y";

        $this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');

        $this->data['data'] = array();

        $this->data['post'] = $this->input->post();

        $userdata = $this->Administration_m->getLogin();

        $this->data['dir'] = 'penerimaan_barang';

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

        if(empty($sess)){
            redirect(site_url('log/in'));
        }
    }

    public function index(){
        $data = array();

        $this->template("penerimaan_barang/list_penerimaan_barang_v", "Data Penerimaan Barang", $data);
    }

    public function get_data(){
        $post = $this->input->post();    
        
        $position = $this->Administration_m->getPosition("KOORDINATOR");
        
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

        $result = $this->Penerimaan_barang_m->getPenerimaan_barang()->result_array();

        if($position) {
            $result = $this->Penerimaan_barang_m->getPenerimaan_barang("", $this->data['userdata']['lokasi_skd_id'])->result_array();
        }

        if (!empty($search)) {
            // $this->db->group_start();
            // $this->db->like('test', $search);
            // $this->db->or_like('test', $search);
            // $this->db->group_end();
        }

        $count = $this->Penerimaan_barang_m->getPenerimaan_barang()->num_rows();

        if($position) {
            $count = $this->Penerimaan_barang_m->getPenerimaan_barang("", $this->data['userdata']['lokasi_skd_id'])->num_rows();
        }

        $totalRecords = $count;
        $totalRecordwithFilter = $count;

        $data = array();
        
        foreach($result as $v) {   

            $cek_integrasi = $this->db->select('id')->from('uji_penerimaan_barang')->where('penerimaan_id', $v['id'])->get()->num_rows();
            
            $action = '<div class="btn-group" role="group">
                        <a href="' .  site_url('penerimaan_barang/detail/' . $v['id']) . '" class="btn btn-sm btn-primary">Detail</a>
                        <a href="' .  site_url('penerimaan_barang/update/' . $v['id']) . '" class="btn btn-sm btn-warning">Edit</a>
                        <a href="' .  site_url('penerimaan_barang/delete/' . $v['id']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Apakah Anda yakin?\');">Hapus</a>
                    </div>';

            if($cek_integrasi > 0) {
                $action = '<div class="btn-group" role="group">
                    <a href="' .  site_url('penerimaan_barang/detail/' . $v['id']) . '" class="btn btn-sm btn-primary">Detail</a>
                </div>';
            }
            
            $data[] = array(                                
                "kode_penerimaan" => $v['kode_penerimaan'],
                "kode_pengiriman" => $v['kode_pengiriman'],
                "province_name" => $v['province_name'],
                "regency_name" => $v['regency_name'],
                "nama_lokasi" => $v['kode_lokasi'] . ' | ' . $v['nama_lokasi'],
                "tgl_terima" => $v['tgl_terima'],
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
    
    public function add(){
        $data = array();        

        $position = $this->Administration_m->getPosition("KOORDINATOR");

        $data['get_pengiriman'] = $this->Pengiriman_barang_m->getPengiriman_barang()->result_array();        

        if($position) {
            $data['get_pengiriman'] = $this->Pengiriman_barang_m->getPengiriman_barang("", $this->data['userdata']['lokasi_skd_id'])->result_array();
        }
        
        $this->template("penerimaan_barang/add_penerimaan_barang_v", "Tambah Penerimaan Barang", $data);
    }

    public function update($id){
        $data = array();        

        $position = $this->Administration_m->getPosition("KOORDINATOR");

        $data['get_penerimaan'] = $this->Penerimaan_barang_m->getPenerimaan_barang($id)->row_array();
        $data['get_detail'] = $this->Penerimaan_barang_m->getDetail($id)->result_array();

        if($position) {
            $data['get_penerimaan'] = $this->Penerimaan_barang_m->getPenerimaan_barang($id)->row_array();
            $data['get_detail'] = $this->Penerimaan_barang_m->getDetail($id)->result_array();
        }        
        
        $this->template("penerimaan_barang/edit_penerimaan_barang_v", "Edit Penerimaan Barang", $data);
    }

    public function detail($id){
        $data = array();        
        $data['get_penerimaan'] = $this->Penerimaan_barang_m->getPenerimaan_barang($id)->row_array();
        $data['get_detail'] = $this->Penerimaan_barang_m->getDetail($id)->result_array();

        $this->template("penerimaan_barang/detail_penerimaan_barang_v", "Detail Penerimaan Barang", $data);
    }

    public function submit_data(){

        $post = $this->input->post(); 
        $jumlah_terima = $this->input->post('jumlah_terima');
        $jumlah_rusak = $this->input->post('jumlah_rusak');
        $jumlah_terpasang = $this->input->post('jumlah_terpasang');
        $barang_id = $this->input->post('barang_id');

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('penerimaan_barang/add'));
        }

        $this->db->trans_begin();

        $cek_integrasi = $this->db->select('id')->from('penerimaan_barang')->where('pengiriman_id', $post['pengiriman_id'])->get()->num_rows();

        if ($cek_integrasi > 0) {
            $this->setMessage("Data pengiriman sudah pernah diinput.");
            redirect(site_url('penerimaan_barang'));
        }

        $data = array(
            "pengiriman_id" => $post['pengiriman_id'],
            "tgl_terima" => $post['tgl_terima'],
            'catatan' => $post['catatan'],
            'created_by' => $this->data['userdata']['employee_id'],
            'created_at' => date('Y-m-d H:i:s'),
        );

        $simpan = $this->db->insert('penerimaan_barang', $data);
        
        if($simpan){        
            
            $insert_id = $this->db->insert_id();

            $id = strval($insert_id); 
            $res = str_repeat('0', 5 - strlen($id)).$id;   

            $this->db->set('kode_penerimaan', 'PRM.' . $res)->where('id', $insert_id)->update('penerimaan_barang');

            $dir = './uploads/' . $this->data['dir'];

            if (!empty($_FILES['foto_barang']['name'])) {
                $data_insert = array();
            
                foreach ($_FILES['foto_barang']['name'] as $key => $file_name) {
                    $_FILES['file']['name'] = $this->data['userdata']['employee_id'] . '_barang_terima_' . date('His') . '_' . $file_name;
                    $_FILES['file']['type'] = $_FILES['foto_barang']['type'][$key];
                    $_FILES['file']['tmp_name'] = $_FILES['foto_barang']['tmp_name'][$key];
                    $_FILES['file']['error'] = $_FILES['foto_barang']['error'][$key];
                    $_FILES['file']['size'] = $_FILES['foto_barang']['size'][$key];
            
                    if ($this->upload->do_upload('file')) {
                        $uploadKtp = $this->upload->data();
                        $data_insert[] = array(
                            'jumlah_terima' => $jumlah_terima[$key],
                            'jumlah_rusak' => $jumlah_rusak[$key],
                            'jumlah_terpasang' => $jumlah_terpasang[$key],
                            'barang_id' => $barang_id[$key],
                            'file_path' => $uploadKtp['file_name'],
                        );
                    }
                }     
                
                foreach ($data_insert as $insert_data) {
                    $detail = array(
                        "penerimaan_id" => $insert_id,
                        "barang_id" => $insert_data['barang_id'],
                        "jumlah_terima" => $insert_data['jumlah_terima'],
                        "jumlah_rusak" => $insert_data['jumlah_rusak'],
                        "jumlah_terpasang" => $insert_data['jumlah_terpasang'],
                        'foto_barang' => $insert_data['file_path'],
                        'created_by' => $this->data['userdata']['employee_id'],
                        'created_at' => date('Y-m-d H:i:s'),
                    );            
                    $simpan_detail = $this->db->insert('penerimaan_detail', $detail);
                }
            }            

            if ($this->db->trans_status() === FALSE)  {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }            

            redirect(site_url('penerimaan_barang'));
        
        } else {
            $this->renderMessage("error");
        }
    }

    public function submit_update(){

        $post = $this->input->post(); 
        $jumlah_terima = $this->input->post('jumlah_terima');
        $jumlah_rusak = $this->input->post('jumlah_rusak');
        $jumlah_terpasang = $this->input->post('jumlah_terpasang');
        $barang_id = $this->input->post('barang_id');
        $detail_id = $this->input->post('detail_id');
        $foto_exist = $this->input->post('foto_exist');

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('penerimaan_barang/add'));
        }

        $this->db->trans_begin();

        $update_data = array(
            "pengiriman_id" => $post['pengiriman_id'],
            "tgl_terima" => $post['tgl_terima'],
            'catatan' => $post['catatan'],
            'updated_by' => $this->data['userdata']['employee_id'],
            'updated_at' => date('Y-m-d H:i:s'),
        );

        $this->db->where('id', $post['id']);
        $update = $this->db->update('penerimaan_barang', $update_data);
        
        if($update){                            

            $dir = './uploads/' . $this->data['dir'];

            if (!empty($jumlah_terima)) {
                $data_insert = array();
            
                foreach ($jumlah_terima as $key => $v) {
                    
                    $file_name = isset($_FILES['foto_barang']['name'][$key]) ? $_FILES['foto_barang']['name'][$key] : '';

                    if (!empty($file_name)) {
                        $_FILES['file']['name'] = $this->data['userdata']['employee_id'] . '_barang_' . date('His') . '_' . $file_name;
                        $_FILES['file']['type'] = $_FILES['foto_barang']['type'][$key];
                        $_FILES['file']['tmp_name'] = $_FILES['foto_barang']['tmp_name'][$key];
                        $_FILES['file']['error'] = $_FILES['foto_barang']['error'][$key];
                        $_FILES['file']['size'] = $_FILES['foto_barang']['size'][$key];
            
                        if ($this->upload->do_upload('file')) {
                            $uploadKtp = $this->upload->data();
                            $data_insert[] = array(
                                'jumlah_terima' => $jumlah_terima[$key],
                                'jumlah_rusak' => $jumlah_rusak[$key],
                                'jumlah_terpasang' => $jumlah_terpasang[$key],
                                'barang_id' => $barang_id[$key],
                                'detail_id' => $detail_id[$key],
                                'file_path' => isset($uploadKtp['file_name']) ? $uploadKtp['file_name'] : '',
                            );
                        }
                    } else {
                        $data_insert[] = array(
                            'jumlah_terima' => $jumlah_terima[$key],
                            'jumlah_rusak' => $jumlah_rusak[$key],
                            'jumlah_terpasang' => $jumlah_terpasang[$key],
                            'barang_id' => $barang_id[$key],
                            'detail_id' => $detail_id[$key],
                            'file_path' => $foto_exist[$key],
                        );
                    }            
                }     
                
                foreach ($data_insert as $insert_data) {
                    $detail = array(
                        "penerimaan_id" => $post['id'],
                        "barang_id" => $insert_data['barang_id'],
                        "jumlah_terima" => $insert_data['jumlah_terima'],
                        "jumlah_rusak" => $insert_data['jumlah_rusak'],
                        "jumlah_terpasang" => $insert_data['jumlah_terpasang'],
                        'foto_barang' => $insert_data['file_path'],
                        'updated_by' => $this->data['userdata']['employee_id'],
                        'updated_at' => date('Y-m-d H:i:s'),
                    );            

                    $this->db->where('id', $insert_data['detail_id']);
                    $simpan_detail = $this->db->update('penerimaan_detail', $detail);
                }
            }            

            if ($this->db->trans_status() === FALSE)  {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }            

            redirect(site_url('penerimaan_barang'));
        
        } else {
            $this->renderMessage("error");
        }
    }

    public function get_barang()
    {        
        $pengiriman_id = $this->input->post('pengiriman_id', true);
        $data = $this->Pengiriman_barang_m->getDetailKirim($pengiriman_id)->result_array();
        
        echo json_encode($data);
    }

    public function delete($id) {
        $this->db->trans_begin();

        $this->db->where('penerimaan_id', $id);
        $this->db->delete('penerimaan_detail');

        $this->db->where('id', $id);
        $this->db->delete('penerimaan_barang');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->setMessage("Gagal hapus data.");
            redirect(site_url('penerimaan_barang'));

        } else {
            $this->db->trans_commit();
            $this->setMessage("Berhasil hapus data.");
            redirect(site_url('penerimaan_barang'));
        }
    }
}
