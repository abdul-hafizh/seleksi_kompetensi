<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kabupaten extends Telescoope_Controller
{

    var $data;

    public function __construct() {
        
        parent::__construct();

        $this->load->model(array("Administration_m", "Kabupaten_m","Provinsi_m"));

        $this->data['date_format'] = "h:i A | d M Y";

        $this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');

        $this->data['data'] = array();

        $this->data['post'] = $this->input->post();

        $userdata = $this->Administration_m->getLogin();

        $this->data['dir'] = 'kabupaten';

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
        $data['get_kabupaten'] = $this->Kabupaten_m->getKabupaten()->result_array();

        $this->template("manajemen_data/kabupaten/list_kabupaten_v", "Data Kabupaten", $data);
    }

    public function add(){
        $data = array();        
        $data['get_provinsi'] = $this->Provinsi_m->getProvinsi()->result_array();

        $this->template("manajemen_data/kabupaten/add_kabupaten_v", "Tambah Kabupaten", $data);
    }

    public function update($id){
        $data = array();        
        $data['get_kabupaten'] = $this->Kabupaten_m->getKabupaten($id)->row_array();

        $this->template("manajemen_data/kabupaten/edit_kabupaten_v", "Ubah Kabupaten", $data);
    }

    public function submit_data(){

        $post = $this->input->post(); 

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('manajemen_data/kabupaten/add'));
        }

        $this->db->trans_begin();

        $provinsi = $this->Provinsi_m->getProvinsi($post['provinsi_id'])->row_array();

        $data = array(
            'parent_id' => $provinsi['province_id'],
            'province_id' => $provinsi['province_id'],
            'level' => 3,
            'country_id' => 100000,
            'country_name' => 'Indonesia',
            'province_name' => strtoupper($provinsi['province_name']),
            'regency_name' => $post['name_prefix'] == 'Kota' ? 'KOTA ' . strtoupper($post['regency_name']) : 'KAB. ' . strtoupper($post['regency_name']),
            'name_prefix' => $post['name_prefix'],
            'name' => strtoupper($post['regency_name']),
            'full_name' => $post['name_prefix'] . ' ' . strtoupper($post['regency_name']),
            'stereotype' => 'REGENCY',
            'row_status' => 'ACTIVE',
        );

        $simpan = $this->db->insert('ref_locations', $data);
        
        if($simpan){        

            $this->db->set('regency_id', $this->db->insert_id())->where('location_id', $this->db->insert_id())->update('ref_locations');

            if ($this->db->trans_status() === FALSE)  {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }            

            redirect(site_url('manajemen_data/kabupaten'));
        
        } else {
            $this->renderMessage("error");
        }
    }

    public function submit_update(){

        $post = $this->input->post(); 
    
        $this->db->trans_begin(); 

        $kabupaten = $this->Provinsi_m->getKabupaten($post['location_id'])->row_array();
        
        $dataUpdate = array(
            'parent_id' => $kabupaten['province_id'],
            'province_id' => $kabupaten['province_id'],
            'level' => 3,
            'country_id' => 100000,
            'country_name' => 'Indonesia',
            'province_name' => strtoupper($kabupaten['province_name']),
            'regency_name' => $post['name_prefix'] == 'Kota' ? 'KOTA ' . strtoupper($post['regency_name']) : 'KAB. ' . strtoupper($post['regency_name']),
            'name_prefix' => $post['name_prefix'],
            'name' => strtoupper($post['regency_name']),
            'full_name' => $post['name_prefix'] . ' ' . strtoupper($post['regency_name']),
            'stereotype' => 'REGENCY',
            'row_status' => 'ACTIVE',
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
            redirect(site_url('manajemen_data/kabupaten/update/' . $post['location_id']));
        } else {
            $this->renderMessage("error");
        }
    }
}
