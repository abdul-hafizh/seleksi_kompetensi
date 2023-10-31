<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Desa extends Telescoope_Controller
{

    var $data;

    public function __construct() {
        
        parent::__construct();

        $this->load->model(array("Administration_m", "Desa_m", "Provinsi_m", "Kecamatan_m"));

        $this->data['date_format'] = "h:i A | d M Y";

        $this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');

        $this->data['data'] = array();

        $this->data['post'] = $this->input->post();

        $userdata = $this->Administration_m->getLogin();

        $this->data['dir'] = 'desa';

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
        $this->template("manajemen_data/desa/list_desa_v", "Data Desa", $data);
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

        $result = $this->Desa_m->getDesa()->result_array();

        if (!empty($search)) {
            // $this->db->group_start();
            // $this->db->like('test', $search);
            // $this->db->or_like('test', $search);
            // $this->db->group_end();
        }

        $count = $this->Desa_m->getDesa()->num_rows();

        $totalRecords = $count;
        $totalRecordwithFilter = $count;

        $data = array();
        
        foreach($result as $v) {      
            
            $action = '<div class="btn-group" role="group">
                        <a href="' .  site_url('manajemen_data/desa/update/' . $v['location_id']) . '" class="btn btn-sm btn-warning">Edit</a>                        
                    </div>';
            
            $data[] = array(
                "kode_desa" => $v['province_id'] . '' . $v['regency_id'] . '' . $v['district_id'] . '' . $v['village_id'],
                "village_name" => $v['village_name'],
                "district_name" => $v['district_name'],
                "regency_name" => $v['regency_name'],
                "province_name" => $v['province_name'],
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

        $this->template("manajemen_data/desa/add_desa_v", "Tambah Desa", $data);
    }

    public function update($id){
        $data = array();        
        $data['get_desa'] = $this->Desa_m->getDesa($id)->row_array();

        $this->template("manajemen_data/desa/edit_desa_v", "Ubah Desa", $data);
    }

    public function submit_data(){

        $post = $this->input->post(); 

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('manajemen_data/desa/add'));
        }

        $this->db->trans_begin();

        $kecamatan = $this->Kecamatan_m->getKecamatan($post['kecamatan'])->row_array();

        $data = array(
            'parent_id' => $kecamatan['district_id'],
            'province_id' => $kecamatan['province_id'],
            'regency_id' => $kecamatan['regency_id'],
            'district_id' => $kecamatan['district_id'],
            'level' => 5,
            'country_id' => 100000,
            'country_name' => 'Indonesia',
            'province_name' => strtoupper($kecamatan['province_name']),
            'regency_name' => strtoupper($kecamatan['regency_name']),
            'district_name' => strtoupper($kecamatan['district_name']),
            'village_name' => strtoupper($post['village_name']),
            'name_prefix' => 'Kelurahan',
            'name' => strtoupper($post['village_name']),
            'full_name' => 'Kelurahan ' . strtoupper($post['village_name']),
            'stereotype' => 'VILLAGE',
            'row_status' => 'ACTIVE'
        );

        $simpan = $this->db->insert('ref_locations', $data);
        
        if($simpan){        

            $this->db->set('village_id', $this->db->insert_id())->where('location_id', $this->db->insert_id())->update('ref_locations');

            if ($this->db->trans_status() === FALSE)  {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }            

            redirect(site_url('manajemen_data/desa'));
        
        } else {
            $this->renderMessage("error");
        }
    }

    public function submit_update(){

        $post = $this->input->post(); 
    
        $this->db->trans_begin(); 

        $desa = $this->Desa_m->getDesa($post['location_id'])->row_array();
        
        $dataUpdate = array(
            'parent_id' => $desa['district_id'],
            'province_id' => $desa['province_id'],
            'regency_id' => $desa['regency_id'],
            'district_id' => $desa['district_id'],
            'level' => 5,
            'country_id' => 100000,
            'country_name' => 'Indonesia',
            'province_name' => strtoupper($desa['province_name']),
            'regency_name' => strtoupper($desa['regency_name']),
            'district_name' => strtoupper($desa['district_name']),
            'village_name' => strtoupper($post['village_name']),
            'name_prefix' => 'Kelurahan',
            'name' => strtoupper($post['village_name']),
            'full_name' => 'Kelurahan ' . strtoupper($post['village_name']),
            'stereotype' => 'VILLAGE',
            'row_status' => 'ACTIVE'
        );
        
        $this->db->where('location_id', $post['location_id']);
        $update = $this->db->update('ref_locations', $dataUpdate);

        if($update){
            if ($this->db->trans_status() === FALSE)  {
                $this->setMessage("Gagal mengubah data");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Sukses mengubah data");
                $this->db->trans_commit();
            }
            redirect(site_url('manajemen_data/desa/update/' . $post['location_id']));
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
        $regency = $this->input->post('kabupaten', true);
        $data = $this->db->get_where('ref_locations', ['parent_id' => $regency])->result_array();
        echo json_encode($data);
    }
}
