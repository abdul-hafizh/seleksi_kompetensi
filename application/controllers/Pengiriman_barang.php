<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengiriman_barang extends Telescoope_Controller
{

    var $data;

    public function __construct() {
        
        parent::__construct();

        $this->load->model(array("Administration_m", "Pengiriman_barang_m", "Perencanaan_m", "Provinsi_m"));

        $this->data['date_format'] = "h:i A | d M Y";

        $this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');

        $this->data['data'] = array();

        $this->data['post'] = $this->input->post();

        $userdata = $this->Administration_m->getLogin();

        $this->data['dir'] = 'pengiriman_barang';

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

        if(!$position1 && !$position2 && !$position3){
            $this->noAccess("Anda tidak memiliki hak akses untuk halaman ini.");
        }

        if(empty($sess)){
            redirect(site_url('log/in'));
        }
    }

    public function index(){
        $data = array();

        $this->template("pengiriman_barang/list_pengiriman_barang_v", "Data Pengiriman Barang", $data);
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

        $result = $this->Pengiriman_barang_m->getPengiriman_barang()->result_array();

        if (!empty($search)) {
            // $this->db->group_start();
            // $this->db->like('test', $search);
            // $this->db->or_like('test', $search);
            // $this->db->group_end();
        }

        $count = $this->Pengiriman_barang_m->getPengiriman_barang()->num_rows();

        $totalRecords = $count;
        $totalRecordwithFilter = $count;

        $data = array();
        
        foreach($result as $v) {               
            
            $action = '<div class="btn-group" role="group">
                        <a href="' .  site_url('pengiriman_barang/update/' . $v['id']) . '" class="btn btn-sm btn-warning" disabled>Edit</a>
                        <a href="' .  site_url('pengiriman_barang/detail/' . $v['id']) . '" class="btn btn-sm btn-primary" disabled>Detail</a>
                    </div>';
            
            $data[] = array(                                
                "kode_pengiriman" => $v['kode_pengiriman'],
                "province_name" => $v['province_name'],
                "regency_name" => $v['regency_name'],
                "nama_lokasi" => $v['kode_lokasi'] . ' | ' . $v['nama_lokasi'],
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
        $data['get_perencanaan'] = $this->Perencanaan_m->getPerencanaan()->result_array(); 
  
        $this->template("pengiriman_barang/add_pengiriman_barang_v", "Tambah Pengiriman Barang", $data);
    }

    public function detail($id){
        $data = array();        
        $data['get_pengiriman'] = $this->Pengiriman_barang_m->getPengiriman_barang($id)->row_array();
        $data['get_detail'] = $this->Pengiriman_barang_m->getDetail($id)->result_array();
  
        $this->template("pengiriman_barang/detail_pengiriman_barang_v", "Detail Pengiriman Barang", $data);
    }

    public function submit_data(){

        $post = $this->input->post(); 
        $jumlah = $this->input->post('jumlah_kirim');
        $barang_id = $this->input->post('barang_id');

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('pengiriman_barang/add'));
        }

        $this->db->trans_begin();

        $dir = './uploads/' . $this->data['dir'];

        $data = array(
            "perencanaan_id" => $post['perencanaan_id'],
            "kode_pengiriman" => $post['kode_pengiriman'],
            'tgl_kirim' => $post['tgl_kirim'],
            'catatan' => $post['catatan'],
            'created_by' => $this->data['userdata']['employee_id'],
            'created_at' => date('Y-m-d H:i:s'),
        );

        $simpan = $this->db->insert('pengiriman_barang', $data);
        
        if($simpan){        

            $insert_id = $this->db->insert_id();

            $id = strval($insert_id); 
            $res = str_repeat('0', 5 - strlen($id)).$id;   

            $this->db->set('kode_pengiriman', 'PRB.' . $res)->where('id', $insert_id)->update('pengiriman_barang');

            $dir = './uploads/' . $this->data['dir'];

            if (!empty($jumlah)) {
                $data_insert = array();
            
                foreach ($jumlah as $key => $file_name) {                    
                    $data_insert[] = array(
                        'jumlah_kirim' => $jumlah[$key],
                        'barang_id' => $barang_id[$key],
                    );
                }
                                    
                foreach ($data_insert as $insert_data) {
                    $detail = array(
                        "pengiriman_id" => $insert_id,
                        "barang_id" => $insert_data['barang_id'],
                        "jumlah_kirim" => $insert_data['jumlah_kirim'],
                        'created_by' => $this->data['userdata']['employee_id'],
                        'created_at' => date('Y-m-d H:i:s'),
                    );            
                    $simpan_detail = $this->db->insert('pengiriman_detail', $detail);
                }
            }

            if ($this->db->trans_status() === FALSE)  {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }            

            redirect(site_url('pengiriman_barang'));
        
        } else {
            $this->renderMessage("error");
        }
    }

    public function get_barang()
    {        
        $perencanaan_id = $this->input->post('perencanaan_id', true);
        $data = $this->Perencanaan_m->getDetail($perencanaan_id)->result_array();

        echo json_encode($data);
    }
}
