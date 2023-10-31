<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Penerimaan_barang extends Telescoope_Controller
{

    var $data;

    public function __construct() {
        
        parent::__construct();

        $this->load->model(array("Administration_m", "Penerimaan_barang_m", "Provinsi_m"));

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

        $this->template("penerimaan_barang/list_penerimaan_barang_v", "Data Penerimaan Barang", $data);
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

        $result = $this->Penerimaan_barang_m->getPenerimaan_barang()->result_array();

        if (!empty($search)) {
            // $this->db->group_start();
            // $this->db->like('test', $search);
            // $this->db->or_like('test', $search);
            // $this->db->group_end();
        }

        $count = $this->Penerimaan_barang_m->getPenerimaan_barang()->num_rows();

        $totalRecords = $count;
        $totalRecordwithFilter = $count;

        $data = array();
        
        foreach($result as $v) {   
            
            $foto_barang ='<a href="' . base_url('uploads/penerimaan_barang/' . $v['foto_barang']) . '" target="_blank" class="avatar-group-item" data-img="' . base_url('uploads/penerimaan_barang/' . $v['foto_barang']) . '" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Foto Barang">
                            <img src="' . base_url('uploads/penerimaan_barang/' . $v['foto_barang']) . '" alt="" class="rounded-circle avatar-xxs">
                        </a>';
            
            $action = '<div class="btn-group" role="group">
                        <a href="' .  site_url('penerimaan_barang/update/' . $v['id']) . '" class="btn btn-sm btn-warning" disabled>Edit</a>
                        <a href="' .  site_url('penerimaan_barang/detail/' . $v['id']) . '" class="btn btn-sm btn-primary" disabled>Detail</a>
                    </div>';
            
            $data[] = array(                                
                "kode_perencanaan" => $v['kode_perencanaan'],
                "nama_lokasi" => $v['kode_lokasi'] . ' | ' . $v['nama_lokasi'],
                "province_name" => $v['province_name'],
                "regency_name" => $v['regency_name'],
                "nama_barang" => $v['nama_barang'],
                "jenis_barang" => $v['jenis_barang'],
                "jumlah" => $v['jumlah'],
                "jumlah_terima" => $v['jumlah_terima'],
                "tgl_terima" => $v['tgl_terima'],
                "rusak" => $v['rusak'],
                "terpasang" => $v['terpasang'],
                "foto_barang" => '<div class="avatar-group">' . $foto_barang . '</div>',
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
  
        $this->template("penerimaan_barang/add_penerimaan_barang_v", "Tambah Penerimaan Barang", $data);
    }

    public function submit_data(){

        $post = $this->input->post(); 

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('penerimaan_barang/add'));
        }

        $this->db->trans_begin();

        $dir = './uploads/' . $this->data['dir'];

        if(!empty($_FILES['foto_barang']['name'])){
            $_FILES['file']['name'] = $this->data['userdata']['employee_id'] . '_barang_' . date('his') . '_' . $_FILES['foto_barang']['name'];
            $_FILES['file']['type'] = $_FILES['foto_barang']['type'];
            $_FILES['file']['tmp_name'] = $_FILES['foto_barang']['tmp_name'];
            $_FILES['file']['error'] = $_FILES['foto_barang']['error'];
            $_FILES['file']['size'] = $_FILES['foto_barang']['size'];
            if($this->upload->do_upload('file')){ $uploadKtp = $this->upload->data(); }
        }

        $data = array(
            "lokasi_test_id" => $post['lokasi_test_id'],
            "perencanaan_id" => $post['perencanaan_id'],            
            "tgl_terima" => $post['tgl_terima'],
            "jumlah_terima" => $post['jumlah_terima'],
            "rusak" => $post['rusak'],
            "terpasang" => $post['terpasang'],
            'foto_barang' => isset($uploadKtp['file_name']) ? $uploadKtp['file_name'] : '',
            'catatan' => $post['catatan'],
            'created_by' => $this->data['userdata']['employee_id'],
            'created_at' => date('Y-m-d H:i:s'),
        );

        $simpan = $this->db->insert('penerimaan_barang', $data);
        
        if($simpan){        
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

    public function get_perencanaan()
    {
        $lokasi = $this->input->post('lokasi', true);
        $data = $this->db->get_where('perencanaan', ['kode_lokasi_skd' => $lokasi])->result_array();
        echo json_encode($data);
    }
}
