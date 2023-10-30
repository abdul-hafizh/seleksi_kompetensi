<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Jadwal_kegiatan extends Telescoope_Controller
{

    var $data;

    public function __construct() {
        
        parent::__construct();

        $this->load->model(array("Administration_m", "Lokasi_skd_m", "Jadwal_kegiatan_m", "Provinsi_m"));

        $this->data['date_format'] = "h:i A | d M Y";

        $this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');

        $this->data['data'] = array();

        $this->data['post'] = $this->input->post();

        $userdata = $this->Administration_m->getLogin();

        $this->data['dir'] = 'jadwal_kegiatan';

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

        $this->template("manajemen_data/jadwal_kegiatan/list_jadwal_v", "Data Jadwal Kegiatan", $data);
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

        $result = $this->Jadwal_kegiatan_m->getJadwal()->result_array();

        if (!empty($search)) {
            // $this->db->group_start();
            // $this->db->like('test', $search);
            // $this->db->or_like('test', $search);
            // $this->db->group_end();
        }

        $count = $this->Jadwal_kegiatan_m->getJadwal()->num_rows();

        $totalRecords = $count;
        $totalRecordwithFilter = $count;

        $data = array();
        
        foreach($result as $v) {      
            
            $action = '<div class="btn-group" role="group">
                        <a href="' .  site_url('manajemen_data/jadwal_kegiatan/update/' . $v['id']) . '" class="btn btn-sm btn-warning">Edit</a>
                        <a href="' .  site_url('manajemen_data/jadwal_kegiatan/detail/' . $v['id']) . '" class="btn btn-sm btn-primary">Detail</a>
                    </div>';
            
            $data[] = array(                   
                "kode_kegiatan" => $v['kode_kegiatan'],
                "nama_kegiatan" => $v['nama_kegiatan'],
                "nama_lokasi" => $v['nama_lokasi'],
                "province_name" => $v['province_name'],
                "regency_name" => $v['regency_name'],
                "tahun" => $v['tahun'],
                "status_kegiatan" => $v['status_kegiatan'],
                "tgl_mulai" => $v['tgl_mulai'],
                "tgl_selesai" => $v['tgl_selesai'],                
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
  
        $this->template("manajemen_data/jadwal_kegiatan/add_jadwal_v", "Tambah Jadwal Kegiatan", $data);
    }

    public function submit_data(){

        $post = $this->input->post(); 

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('manajemen_data/jadwal_kegiatan/add'));
        }

        $this->db->trans_begin();

        $data = array(
            'lokasi_skd_id' => $post['lokasi_skd_id'],
            'kode_kegiatan' => $post['kode_kegiatan'],
            'nama_kegiatan' => $post['nama_kegiatan'],
            'tahun' => $post['tahun'],
            'status_kegiatan' => $post['status_kegiatan'],
            'tgl_mulai' => $post['tgl_mulai'],
            'tgl_selesai' => $post['tgl_selesai'],
            'catatan' => $post['catatan'],
            'created_by' => $this->data['userdata']['employee_id'],
            'created_at' => date('Y-m-d H:i:s'),
        );

        $simpan = $this->db->insert('jadwal_kegiatan', $data);
        
        if($simpan){        
            if ($this->db->trans_status() === FALSE)  {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }            

            redirect(site_url('manajemen_data/jadwal_kegiatan'));
        
        } else {
            $this->renderMessage("error");
        }
    }
}
