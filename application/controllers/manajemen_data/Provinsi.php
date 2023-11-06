<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Provinsi extends Telescoope_Controller
{

    var $data;

    public function __construct() {
        
        parent::__construct();

        $this->load->model(array("Administration_m", "Provinsi_m"));

        $this->data['date_format'] = "h:i A | d M Y";

        $this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');

        $this->data['data'] = array();

        $this->data['post'] = $this->input->post();

        $userdata = $this->Administration_m->getLogin();

        $this->data['dir'] = 'provinsi';

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

        $data['get_provinsi'] = $this->Provinsi_m->getProvinsi()->result_array();

        $this->template("manajemen_data/provinsi/list_provinsi_v", "Data Provinsi", $data);
    }

    public function add(){
        $data = array();        
  
        $this->template("manajemen_data/provinsi/add_provinsi_v", "Tambah Provinsi", $data);
    }

    public function update($id){
        $data = array();        
        $data['get_provinsi'] = $this->Provinsi_m->getProvinsi($id)->row_array();

        $this->template("manajemen_data/provinsi/edit_provinsi_v", "Ubah Provinsi", $data);
    }

    public function submit_data(){

        $post = $this->input->post(); 

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('manajemen_data/provinsi/add'));
        }

        $this->db->trans_begin();

        $data = array(
            'parent_id' => 100000,
            'level' => 2,
            'country_id' => 100000,
            'country_name' => 'Indonesia',
            'province_name' => strtoupper($post['province_name']),
            'name_prefix' => 'Provinsi',
            'name' => strtoupper($post['province_name']),
            'full_name' => 'Provinsi ' . strtoupper($post['province_name']),
            'stereotype' => 'PROVINCE',
            'row_status' => 'ACTIVE',
        );

        $simpan = $this->db->insert('ref_locations', $data);
        
        if($simpan){        

            $this->db->set('province_id', $this->db->insert_id())->where('location_id', $this->db->insert_id())->update('ref_locations');

            if ($this->db->trans_status() === FALSE)  {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }            

            redirect(site_url('manajemen_data/provinsi'));
        
        } else {
            $this->renderMessage("error");
        }
    }

    public function submit_update(){

        $post = $this->input->post(); 
    
        $this->db->trans_begin(); 
        
        $dataUpdate = array(
            'parent_id' => 100000,
            'level' => 2,
            'country_id' => 100000,
            'country_name' => 'Indonesia',
            'province_name' => strtoupper($post['province_name']),
            'name_prefix' => 'Provinsi',
            'name' => strtoupper($post['province_name']),
            'full_name' => 'Provinsi ' . strtoupper($post['province_name']),
            'stereotype' => 'PROVINCE',
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
            redirect(site_url('manajemen_data/provinsi/update/' . $post['location_id']));
        } else {
            $this->renderMessage("error");
        }
    }
}
