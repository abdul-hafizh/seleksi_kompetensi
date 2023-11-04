<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Perencanaan extends Telescoope_Controller
{

    var $data;

    public function __construct() {
        
        parent::__construct();

        $this->load->model(array("Administration_m", "Perencanaan_m", "Provinsi_m", "Kabupaten_m"));

        $this->data['date_format'] = "h:i A | d M Y";

        $this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');

        $this->data['data'] = array();

        $this->data['post'] = $this->input->post();

        $userdata = $this->Administration_m->getLogin();

        $this->data['dir'] = 'perencanaan';

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

        if(!$position1 && !$position2){
            $this->noAccess("Anda tidak memiliki hak akses untuk halaman ini.");
        }

        if(empty($sess)){
            redirect(site_url('log/in'));
        }
    }

    public function index(){
        $data = array();

        $this->template("perencanaan/list_perencanaan_v", "Data Perencanaan", $data);
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

        $result = $this->Perencanaan_m->getPerencanaan()->result_array();

        if (!empty($search)) {
            // $this->db->group_start();
            // $this->db->like('test', $search);
            // $this->db->or_like('test', $search);
            // $this->db->group_end();
        }

        $count = $this->Perencanaan_m->getPerencanaan()->num_rows();

        $totalRecords = $count;
        $totalRecordwithFilter = $count;

        $data = array();
        
        foreach($result as $v) {     
            
            $cek_integrasi = $this->db->select('perencanaan.id')->from('perencanaan')->join('pengiriman_barang', 'perencanaan.id = pengiriman_barang.perencanaan_id', 'right')->where('perencanaan.id', $v['id'])->get()->num_rows();
            
            $action = '<div class="btn-group" role="group">
                        <a href="' .  site_url('perencanaan/detail/' . $v['id']) . '" class="btn btn-sm btn-primary">Detail</a>
                        <a href="' .  site_url('perencanaan/update/' . $v['id']) . '" class="btn btn-sm btn-warning">Edit</a>
                        <a href="' .  site_url('perencanaan/delete/' . $v['id']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Apakah Anda yakin?\');">Hapus</a>
                    </div>';

            if($cek_integrasi > 0) {
                $action = '<div class="btn-group" role="group">
                    <a href="' .  site_url('perencanaan/detail/' . $v['id']) . '" class="btn btn-sm btn-primary">Detail</a>
                </div>';
            }
            
            $data[] = array(                                
                "kode_perencanaan" => $v['kode_perencanaan'],
                "province_name" => $v['province_name'],
                "regency_name" => $v['regency_name'],
                "nama_lokasi" => $v['nama_lokasi'],
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
        $data['get_provinsi'] = $this->Provinsi_m->getProvinsi()->result_array();
  
        $this->template("perencanaan/add_perencanaan_v", "Tambah Perencanaan", $data);
    }

    public function update($id){
        $data = array();        
        $data['get_provinsi'] = $this->Provinsi_m->getProvinsi()->result_array();
        $data['get_kabupaten'] = $this->Kabupaten_m->getKabupaten()->result_array();
        $data['get_perencanaan'] = $this->Perencanaan_m->getPerencanaan($id)->row_array();
        $data['get_detail'] = $this->Perencanaan_m->getDetail($id)->result_array();
        $data['get_lokasi'] = $this->db->get_where('lokasi_skd', ['lokasi_id' => $data['get_perencanaan']['location_id']])->result_array();
  
        $this->template("perencanaan/edit_perencanaan_v", "Edit Perencanaan", $data);
    }

    public function detail($id){
        $data = array();        
        $data['get_perencanaan'] = $this->Perencanaan_m->getPerencanaan($id)->row_array();
        $data['get_detail'] = $this->Perencanaan_m->getDetail($id)->result_array();
  
        $this->template("perencanaan/detail_perencanaan_v", "Detail Perencanaan", $data);
    }

    public function submit_data(){

        $post = $this->input->post(); 
        $jumlah = $this->input->post('jumlah');
        $barang_id = $this->input->post('barang_id');

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('perencanaan/add'));
        }     

        $this->db->trans_begin();

        $data = array(
            "kode_lokasi_skd" => $post['kode_lokasi_skd'],
            'catatan' => $post['catatan'],
            'created_by' => $this->data['userdata']['employee_id'],
            'created_at' => date('Y-m-d H:i:s'),
        );

        $simpan = $this->db->insert('perencanaan', $data);
        
        if($simpan){        

            $insert_id = $this->db->insert_id();

            $id = strval($insert_id); 
            $res = str_repeat('0', 5 - strlen($id)).$id;   

            $this->db->set('kode_perencanaan', 'PR.' . $res)->where('id', $insert_id)->update('perencanaan');

            $dir = './uploads/' . $this->data['dir'];

            if (!empty($jumlah)) {
                $data_insert = array();
            
                foreach ($jumlah as $key => $v) {
                    
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
                                'jumlah' => $jumlah[$key],
                                'barang_id' => $barang_id[$key],
                                'file_path' => isset($uploadKtp['file_name']) ? $uploadKtp['file_name'] : '',
                            );
                        }
                    } else {
                        $data_insert[] = array(
                            'jumlah' => $jumlah[$key],
                            'barang_id' => $barang_id[$key],
                            'file_path' => '',
                        );
                    }
                }
            
                foreach ($data_insert as $insert_data) {
                    $detail = array(
                        "perencanaan_id" => $insert_id,
                        "barang_id" => $insert_data['barang_id'],
                        "jumlah" => $insert_data['jumlah'],
                        'foto_barang' => $insert_data['file_path'],
                        'created_by' => $this->data['userdata']['employee_id'],
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                    $simpan_detail = $this->db->insert('perencanaan_detail', $detail);
                }
            }            

            if ($this->db->trans_status() === FALSE)  {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }            

            redirect(site_url('perencanaan'));
        
        } else {
            $this->renderMessage("error");
        }
    }

    public function submit_update(){
        $post = $this->input->post(); 
        $jumlah = $this->input->post('jumlah');
        $barang_id = $this->input->post('barang_id');
        $detail_id = $this->input->post('detail_id');        
        $foto_exist = $this->input->post('foto_exist');        

        $this->db->trans_begin(); 

        $update_data = array(
            "kode_lokasi_skd" => $post['kode_lokasi_skd'],
            'catatan' => $post['catatan'],
            'updated_by' => $this->data['userdata']['employee_id'],
            'updated_at' => date('Y-m-d H:i:s'),
        );
        
        $this->db->where('id', $post['perencanaan_id']);
        $update = $this->db->update('perencanaan', $update_data);

        if($update){

            $dir = './uploads/' . $this->data['dir'];
            
            if (!empty($jumlah)) {
                $data_insert = array();
            
                foreach ($jumlah as $key => $v) {
                    
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
                                'jumlah' => $jumlah[$key],
                                'barang_id' => $barang_id[$key],                                
                                'detail_id' => $detail_id[$key],
                                'file_path' => isset($uploadKtp['file_name']) ? $uploadKtp['file_name'] : '',
                            );
                        }
                    } else {
                        $data_insert[] = array(
                            'jumlah' => $jumlah[$key],
                            'barang_id' => $barang_id[$key],
                            'detail_id' => $detail_id[$key],
                            'file_path' => $foto_exist[$key],
                        );
                    }
                }
            
                foreach ($data_insert as $insert_data) {
                    $detail = array(
                        "perencanaan_id" => $post['perencanaan_id'],
                        "barang_id" => $insert_data['barang_id'],
                        "jumlah" => $insert_data['jumlah'],
                        'foto_barang' => $insert_data['file_path'],
                        'updated_by' => $this->data['userdata']['employee_id'],
                        'updated_at' => date('Y-m-d H:i:s'),
                    );

                    $this->db->where('id', $insert_data['detail_id']);
                    $simpan_detail = $this->db->update('perencanaan_detail', $detail);
                }
            } 

            if ($this->db->trans_status() === FALSE)  {
                $this->setMessage("Gagal mengubah data");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Sukses mengubah data");
                $this->db->trans_commit();
            }
            redirect(site_url('perencanaan'));
        } else {
            $this->renderMessage("error");
        }
    }

    public function get_regency()
    {
        $provinces = $this->input->post('provinsi', true);
        $data = $this->db->get_where('ref_locations', ['parent_id' => $provinces])->result_array();
        echo json_encode($data);
    }

    public function get_lokasi()
    {
        $kabupaten = $this->input->post('kabupaten', true);
        $data = $this->db->get_where('lokasi_skd', ['lokasi_id' => $kabupaten])->result_array();
        echo json_encode($data);
    }

    public function get_barang()
    {        
        $data = $this->db->order_by('id', 'asc')->get('adm_barang')->result_array();
        echo json_encode($data);
    }

    public function get_detail($id)
    {        
        $data = $this->Perencanaan_m->getDetail($id)->result_array();
        echo json_encode($data);
    }

    public function delete($id) {
        $this->db->trans_begin();

        $this->db->where('id', $id);
        $this->db->delete('perencanaan');

        $this->db->where('perencanaan_id', $id);
        $this->db->delete('perencanaan_detail');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->setMessage("Gagal hapus data.");
            redirect(site_url('perencanaan'));

        } else {
            $this->db->trans_commit();
            $this->setMessage("Berhasil hapus data.");
            redirect(site_url('perencanaan'));
        }
    }
}
