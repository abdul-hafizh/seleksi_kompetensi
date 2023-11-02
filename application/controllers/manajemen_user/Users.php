<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Telescoope_Controller
{

    var $data;

    public function __construct()
    {
        parent::__construct();

        $this->load->model(array("Administration_m","Provinsi_m","Lokasi_skd_m"));

        $this->data['date_format'] = "h:i A | d M Y";

        $this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');

        $this->data['data'] = array();

        $this->data['post'] = $this->input->post();

        $userdata = $this->Administration_m->getLogin();

        $this->data['dir'] = 'users';

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
        $data['get_employee'] = $this->Administration_m->employee_view()->result_array();      

        $this->template("manajemen_user/users/list_user_v", "Data SDM", $data);
    }

    public function add(){
        $data = array();        
        $data['get_pos'] = $this->Administration_m->getNewPos()->result_array();
        $data['get_provinsi'] = $this->Provinsi_m->getProvinsi()->result_array();        

        $this->template("manajemen_user/users/add_user_v", "Add SDM", $data);
    }

    public function add_access(){
        $data = array();        
        $data['get_employee'] = $this->Administration_m->get_employee()->result_array();        
  
        $this->template("manajemen_user/users/add_user_access_v", "Add SDM Access", $data);
    }

    public function update($id){
        $data = array();
        $data['get_pos'] = $this->Administration_m->getNewPos()->result_array();  
        $data['get_employee'] = $this->Administration_m->employee_view($id)->row_array();

        $this->template("manajemen_user/users/edit_user_v", "Edit SDM", $data);
    }

    public function submit(){
        $post = $this->input->post(); 

        $posisi = $this->Administration_m->get_pos_id($post['employee_pos_id'])->row_array();

        $this->db->trans_begin(); 

        $dir = './uploads/' . $this->data['dir'];

        if(!empty($_FILES['file_ktp']['name'])){
            $_FILES['file']['name'] = $this->data['userdata']['employee_id'] . '_ktp_' . date('his') . '_' . $_FILES['file_ktp']['name'];
            $_FILES['file']['type'] = $_FILES['file_ktp']['type'];
            $_FILES['file']['tmp_name'] = $_FILES['file_ktp']['tmp_name'];
            $_FILES['file']['error'] = $_FILES['file_ktp']['error'];
            $_FILES['file']['size'] = $_FILES['file_ktp']['size'];
            if($this->upload->do_upload('file')){ $uploadKtp = $this->upload->data(); }
        }

        $inputEmp = array(    
            'fullname' => $post['fullname'],
            'nik' => $post['nik'],
            'lokasi_user' => $post['kabupaten'],
            'lokasi_skd_id' => $post['lokasi_skd_id'],
            'alamat' => $post['alamat'],
            'email' => $post['email'],
            'file_ktp' => isset($uploadKtp['file_name']) ? $uploadKtp['file_name'] : '',
            'status' => $post['status'],
            'phone' => $post['phone'],
            'adm_pos_id' => $post['employee_pos_id']
        );
        
        $this->db->insert("adm_employee", $inputEmp); 

        $insert_id = $this->db->insert_id();

        $data_pos = array(
            'employee_id' => $insert_id,
            'pos_id' => $post['employee_pos_id'],
            'pos_name'=> $posisi['pos_name']
        );

        $save = $this->db->insert('adm_employee_pos', $data_pos);

        if($save){
            if ($this->db->trans_status() === FALSE)  {
                $this->setMessage("Gagal menambah data");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Sukses menambah data");
                $this->db->trans_commit();
            }
                redirect(site_url('manajemen_user/users'));
        } else {
            $this->renderMessage("error");
        }
    }

    public function submit_update(){
        $post = $this->input->post(); 

        $posisi = $this->Administration_m->get_pos_id($post['employee_pos_id'])->row_array();

        $row_data = $this->Administration_m->employee_view($post['employee_pos_id'])->row_array();

        $this->db->trans_begin(); 

        if(!empty($_FILES['file_ktp']['name'])){
            $_FILES['file']['name'] = $this->data['userdata']['employee_id'] . '_ktp_' . date('his') . '_' . $_FILES['file_ktp']['name'];
            $_FILES['file']['type'] = $_FILES['file_ktp']['type'];
            $_FILES['file']['tmp_name'] = $_FILES['file_ktp']['tmp_name'];
            $_FILES['file']['error'] = $_FILES['file_ktp']['error'];
            $_FILES['file']['size'] = $_FILES['file_ktp']['size'];
            if($this->upload->do_upload('file')){ $uploadKtp = $this->upload->data(); }
        }

        $updateEmp = array(
            'fullname' => $post['fullname'],
            'nik' => $post['nik'],
            'lokasi_user' => $post['kabupaten'],
            'lokasi_skd_id' => $post['lokasi_skd_id'],
            'file_ktp' => isset($uploadKtp['file_name']) ? $uploadKtp['file_name'] : $row_data['file_ktp'],
            'alamat' => $post['alamat'],
            'email' => $post['email'],
            'phone' => $post['phone'],
            'status' => $post['status'],
            'adm_pos_id' => $post['employee_pos_id']
        );
        
        $this->db->where('id', $post['id']);
        $update = $this->db->update('adm_employee', $updateEmp);

        $data_pos = array(
            'pos_id' => $post['employee_pos_id'],
            'pos_name'=> $posisi['pos_name']
        );

        $this->db->where('employee_pos_id', $post['id']);
        $update_pos = $this->db->update('adm_employee_pos', $data_pos);

        if($update){
            if ($this->db->trans_status() === FALSE)  {
                $this->setMessage("Gagal mengubah data");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Sukses mengubah data");
                $this->db->trans_commit();
            }
            redirect(site_url('manajemen_user/users/update/' . $post['id']));
        } else {
            $this->renderMessage("error");
        }
    }

    public function submit_access(){
        $post = $this->input->post(); 
  
        $emp = $this->Administration_m->get_employee($post['employeeid_inp'])->row_array();
  
        $password = $post['password_inp'];
  
        $check = $this->db->where("user_name",$post['user_name_inp'])->get("adm_user")->num_rows();
  
        if(empty($check)){
  
            $data = array(
              'employeeid' => $post['employeeid_inp'],
              'user_name' => $post['user_name_inp'],
              'complete_name' => $emp['fullname'],
              'created_date' => date('Y-m-d H:i:s')
            );
  
            if(!empty($password)){
                $data['password'] = strtoupper(do_hash($password,'sha1'));
            }
  
            $insert = $this->db->insert('adm_user', $data);
            
            if($insert){
                if ($this->db->trans_status() === FALSE)  {
                  $this->setMessage("Gagal menambah data");
                  $this->db->trans_rollback();
                } else {
                  $this->setMessage("Sukses menambah data");
                  $this->db->trans_commit();
                }
                redirect(site_url('manajemen_user/users'));
            
              } else {
                $this->renderMessage("error");
            }
            
        } else {
          $this->setMessage("Username telah digunakan.");
          $this->db->trans_rollback();
          redirect(site_url('manajemen_user/users/add_access'));
        }
    }

    public function get_regency()
    {
        $provinces = $this->input->post('provinsi', true);
        $data = $this->db->get_where('ref_locations', ['parent_id' => $provinces])->result_array();
        echo json_encode($data);
    }

    public function get_lokasi()
    {
        $kabupaten = $this->input->post('kabupaten', true);        
        $data = $this->Lokasi_skd_m->getLokasi("", $kabupaten)->result_array();
        echo json_encode($data);
    }
}
