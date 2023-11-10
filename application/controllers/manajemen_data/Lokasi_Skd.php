<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lokasi_skd extends Telescoope_Controller
{

    var $data;

    public function __construct() {
        
        parent::__construct();

        $this->load->model(array("Administration_m", "Lokasi_skd_m", "Provinsi_m"));

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

        $this->template("manajemen_data/lokasi_skd/list_lokasi_skd_v", "Data Lokasi Test", $data);
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

        $result = $this->Lokasi_skd_m->getLokasi()->result_array();

        if (!empty($search)) {
            // $this->db->group_start();
            // $this->db->like('test', $search);
            // $this->db->or_like('test', $search);
            // $this->db->group_end();
        }

        $count = $this->Lokasi_skd_m->getLokasi()->num_rows();

        $totalRecords = $count;
        $totalRecordwithFilter = $count;

        $data = array();
        
        foreach($result as $v) {      

            $cek_integrasi = $this->db->select('lokasi_skd.id')->from('lokasi_skd')->join('perencanaan', 'lokasi_skd.id = perencanaan.kode_lokasi_skd', 'right')->where('lokasi_skd.id', $v['id'])->get()->num_rows();
            
            $action = '<div class="btn-group" role="group">
                        <a href="' .  site_url('manajemen_data/lokasi_skd/detail/' . $v['id']) . '" class="btn btn-sm btn-primary">Detail</a>
                        <a href="' .  site_url('manajemen_data/lokasi_skd/update/' . $v['id']) . '" class="btn btn-sm btn-warning">Edit</a>
                        <a href="' .  site_url('manajemen_data/lokasi_skd/delete/' . $v['id']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Apakah Anda yakin?\');">Hapus</a>
                    </div>';

            if($cek_integrasi > 0) {
                $action = '<div class="btn-group" role="group">
                            <a href="' .  site_url('manajemen_data/lokasi_skd/detail/' . $v['id']) . '" class="btn btn-sm btn-primary">Detail</a>
                        </div>';
            }
            
            $data[] = array(                                
                "kode_lokasi" => $v['kode_lokasi'],
                "nama_lokasi" => $v['nama_lokasi'],
                "province_name" => $v['province_name'],
                "regency_name" => $v['regency_name'],
                "status_gedung" => $v['status_gedung'],
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
  
        $this->template("manajemen_data/lokasi_skd/add_lokasi_skd_v", "Tambah Lokasi Test", $data);
    }

    public function update($id){
        $data = array();
        $data['get_provinsi'] = $this->Provinsi_m->getProvinsi()->result_array();        
        $data['get_lokasi'] = $this->Lokasi_skd_m->getLokasi($id)->row_array();
  
        $this->template("manajemen_data/lokasi_skd/edit_lokasi_skd_v", "Edit Lokasi Test", $data);
    
    }
    public function detail($id){
        $data = array();
        
        $data['get_lokasi'] = $this->Lokasi_skd_m->getLokasi($id)->row_array();
  
        $this->template("manajemen_data/lokasi_skd/detail_lokasi_skd_v", "Detail Lokasi Test", $data);
    }

    public function submit_data(){

        $post = $this->input->post(); 

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('manajemen_data/lokasi_skd/add'));
        }

        $this->db->trans_begin();

        $data = array(
            'status_gedung' => $post['status_gedung'],            
            'nama_lokasi' => $post['nama_lokasi'],
            'lokasi_id' => $post['kabupaten'],
            'alamat' => $post['alamat'],
            'catatan' => $post['catatan'],
            'created_by' => $this->data['userdata']['employee_id'],
            'created_at' => date('Y-m-d H:i:s'),
        );

        $simpan = $this->db->insert('lokasi_skd', $data);
        
        if($simpan){        
            
            $id = strval($this->db->insert_id()); 
            $res = str_repeat('0', 5 - strlen($id)).$id;   

            $this->db->set('kode_lokasi', 'LK.' . $res)->where('id', $this->db->insert_id())->update('lokasi_skd');

            if ($this->db->trans_status() === FALSE)  {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }            

            redirect(site_url('manajemen_data/lokasi_skd'));
        
        } else {
            $this->renderMessage("error");
        }
    }

    public function submit_update(){
        $post = $this->input->post(); 

        $this->db->trans_begin(); 

        $update_data = array(
            'status_gedung' => $post['status_gedung'],            
            'nama_lokasi' => $post['nama_lokasi'],
            'lokasi_id' => $post['kabupaten'],
            'alamat' => $post['alamat'],
            'catatan' => $post['catatan'],
            'updated_by' => $this->data['userdata']['employee_id'],
            'updated_at' => date('Y-m-d H:i:s'),
        );
        
        $this->db->where('id', $post['id']);
        $update = $this->db->update('lokasi_skd', $update_data);

        if($update){
            if ($this->db->trans_status() === FALSE)  {
                $this->setMessage("Gagal mengubah data");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Sukses mengubah data");
                $this->db->trans_commit();
            }
            redirect(site_url('manajemen_data/lokasi_skd'));
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

    public function delete($id) {        
        $this->db->trans_begin();

        $this->db->where('id', $id);
        $this->db->delete('lokasi_skd');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->setMessage("Gagal hapus data.");
            redirect(site_url('manajemen_data/lokasi_skd'));

        } else {
            $this->db->trans_commit();
            $this->setMessage("Berhasil hapus data.");
            redirect(site_url('manajemen_data/lokasi_skd'));
        }
    }
}
