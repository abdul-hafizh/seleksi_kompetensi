<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lokasi_Skd extends Telescoope_Controller
{

    var $data;

    public function __construct() {
        
        parent::__construct();

        $this->load->model(array("Administration_m", "Lokasi_Skd_m"));

        $this->data['date_format'] = "h:i A | d M Y";

        $this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');

        $this->data['data'] = array();

        $this->data['post'] = $this->input->post();

        $userdata = $this->Administration_m->getLogin();

        $this->data['dir'] = 'lokasi_skd';

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
        $this->template("manajemen_data/lokasi_skd/list_lokasi_skd_v", "Data Lokasi SKD", $data);
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

        $result = $this->Lokasi_Skd_m->getLokasi()->result_array();

        if (!empty($search)) {
            // $this->db->group_start();
            // $this->db->like('test', $search);
            // $this->db->or_like('test', $search);
            // $this->db->group_end();
        }

        $count = $this->Lokasi_Skd_m->getLokasi()->num_rows();

        $totalRecords = $count;
        $totalRecordwithFilter = $count;

        $data = array();
        
        foreach($result as $v) {      
            
            $action = '<div class="btn-group" role="group">
                        <a href="' .  site_url('manajemen_data/lokasi_skd/update/' . $v['id']) . '" class="btn btn-sm btn-warning">Edit</a>
                        <a href="' .  site_url('manajemen_data/lokasi_skd/detail/' . $v['id']) . '" class="btn btn-sm btn-primary">Detail</a>
                    </div>';
            
            $data[] = array(                                
                "lokasi_id" => $v['lokasi_id'],
                "province_name" => $v['province_name'],
                "district_name" => $v['district_name'],
                "regency_name" => $v['regency_name'],
                "village_name" => $v['village_name'],
                "status_gedung" => $v['status_gedung'],
                "luas_ruangan_test" => $v['panjang_ruangan_test'] . 'x' . $v['lebar_ruangan_test'],
                "luas_ruangan_tunggu" => $v['panjang_ruangan_tunggu'] . 'x' . $v['lebar_ruangan_tunggu'],
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
}
