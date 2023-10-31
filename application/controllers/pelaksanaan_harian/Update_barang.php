<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Update_barang extends Telescoope_Controller
{

    var $data;

    public function __construct() {
        
        parent::__construct();

        $this->load->model(array("Administration_m", "Update_barang_m", "Penerimaan_barang_m"));

        $this->data['date_format'] = "h:i A | d M Y";

        $this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');

        $this->data['data'] = array();

        $this->data['post'] = $this->input->post();

        $userdata = $this->Administration_m->getLogin();

        $this->data['dir'] = 'update_barang';

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

        $this->template("pelaksanaan_harian/update_barang/list_update_barang_v", "Data Update Pengawasan Barang", $data);
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

        $result = $this->Update_barang_m->getUpdate_barang()->result_array();

        if (!empty($search)) {
            // $this->db->group_start();
            // $this->db->like('test', $search);
            // $this->db->or_like('test', $search);
            // $this->db->group_end();
        }

        $count = $this->Update_barang_m->getUpdate_barang()->num_rows();

        $totalRecords = $count;
        $totalRecordwithFilter = $count;

        $data = array();
        
        foreach($result as $v) {   
            
            $foto_barang ='<a href="' . base_url('uploads/update_barang/' . $v['foto_barang']) . '" target="_blank" class="avatar-group-item" data-img="' . base_url('uploads/update_barang/' . $v['foto_barang']) . '" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Foto Barang">
                            <img src="' . base_url('uploads/update_barang/' . $v['foto_barang']) . '" alt="" class="rounded-circle avatar-xxs">
                        </a>';
            
            $action = '<div class="btn-group" role="group">
                        <a href="' .  site_url('pelaksanaan_harian/update_barang/update/' . $v['id']) . '" class="btn btn-sm btn-warning" disabled>Edit</a>
                        <a href="' .  site_url('pelaksanaan_harian/update_barang/detail/' . $v['id']) . '" class="btn btn-sm btn-primary" disabled>Detail</a>
                    </div>';
            
            $data[] = array(                                
                "nama_lokasi" => $v['kode_lokasi'] . ' | ' . $v['nama_lokasi'],
                "province_name" => $v['province_name'],
                "regency_name" => $v['regency_name'],
                "nama_barang" => $v['nama_barang'],
                "jumlah_barang" => $v['jumlah_barang'],
                "tgl_pelaksanaan" => $v['tgl_pelaksanaan'],
                "status_uji" => $v['status_uji'],
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
        $data['get_penerimaan'] = $this->Penerimaan_barang_m->getPenerimaan_barang()->result_array();
  
        $this->template("pelaksanaan_harian/update_barang/add_update_barang_v", "Tambah Update Barang", $data);
    }

    public function submit_data(){

        $post = $this->input->post(); 

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('pelaksanaan_harian/update_barang/add'));
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
            "penerimaan_id" => $post['penerimaan_id'],            
            "tgl_pelaksanaan" => $post['tgl_pelaksanaan'],
            "nama_barang" => $post['nama_barang'],
            "jumlah_barang" => $post['jumlah_barang'],
            "status_uji" => $post['status_uji'],
            'foto_barang' => isset($uploadKtp['file_name']) ? $uploadKtp['file_name'] : '',
            'catatan' => $post['catatan'],
            'created_by' => $this->data['userdata']['employee_id'],
            'created_at' => date('Y-m-d H:i:s'),
        );

        $simpan = $this->db->insert('update_barang', $data);
        
        if($simpan){        
            if ($this->db->trans_status() === FALSE)  {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }            

            redirect(site_url('pelaksanaan_harian/update_barang'));
        
        } else {
            $this->renderMessage("error");
        }
    }

    public function get_penerimaan()
    {
        $penerimaan_id = $this->input->post('penerimaan_id', true);
        $data = $this->Penerimaan_barang_m->getPenerimaan_barang($penerimaan_id)->row_array();
        echo json_encode($data);
    }
}