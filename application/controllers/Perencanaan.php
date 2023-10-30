<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Perencanaan extends Telescoope_Controller
{

    var $data;

    public function __construct() {
        
        parent::__construct();

        $this->load->model(array("Administration_m", "Perencanaan_m"));

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
            
            $action = '<div class="btn-group" role="group">
                        <a href="' .  site_url('perencanaan/update/' . $v['id']) . '" class="btn btn-sm btn-warning">Edit</a>
                        <a href="' .  site_url('perencanaan/detail/' . $v['id']) . '" class="btn btn-sm btn-primary">Detail</a>
                    </div>';
            
            $data[] = array(                                
                "kode_perencanaan" => $v['kode_perencanaan'],
                "province_name" => $v['province_name'],
                "nama_lokasi" => $v['nama_lokasi'],
                "nama_barang" => $v['nama_barang'],
                "jenis_barang" => $v['jenis_barang'],
                "jumlah" => $v['jumlah'],
                "satuan" => $v['satuan'],
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

    public function submit_data(){

        $post = $this->input->post(); 

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('perencanaan/add'));
        }

        $this->db->trans_begin();

        $data = array(
            "kode_lokasi_skd" => $post['kode_lokasi_skd'],
            "kode_perencanaan" => $post['kode_perencanaan'],            
            "nama_barang" => $post['nama_barang'],
            "jenis_barang" => $post['jenis_barang'],
            "jumlah" => $post['jumlah'],
            "satuan" => $post['satuan'],
            'catatan' => $post['catatan'],
            'created_by' => $this->data['userdata']['employee_id'],
            'created_at' => date('Y-m-d H:i:s'),
        );

        $simpan = $this->db->insert('perencanaan', $data);
        
        if($simpan){        
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

    public function get_regency()
    {
        $provinces = $this->input->post('provinsi', true);
        $data = $this->db->get_where('ref_locations', ['parent_id' => $provinces])->result_array();
        echo json_encode($data);
    }

    public function get_district()
    {
        $kabupaten = $this->input->post('kabupaten', true);
        $data = $this->db->get_where('ref_locations', ['parent_id' => $kabupaten])->result_array();
        echo json_encode($data);
    }

    public function get_village()
    {
        $kecamatan = $this->input->post('kecamatan', true);
        $data = $this->db->get_where('ref_locations', ['parent_id' => $kecamatan])->result_array();
        echo json_encode($data);
    }

    public function get_lokasi()
    {
        $desa = $this->input->post('desa', true);
        $data = $this->db->get_where('lokasi_skd', ['lokasi_id' => $desa])->result_array();
        echo json_encode($data);
    }
}
