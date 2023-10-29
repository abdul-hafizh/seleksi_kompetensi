<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Employee extends Telescoope_Controller
{

  var $data;

  public function __construct()
  {

    // Call the Model constructor
    parent::__construct();

    $this->load->model(array("Administration_m"));

    $this->data['date_format'] = "h:i A | d M Y";

    $this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');

    $this->data['data'] = array();

    $this->data['post'] = $this->input->post();

    $userdata = $this->Administration_m->getLogin();

    $this->data['dir'] = 'employee';

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
    $position2 = $this->Administration_m->getPosition("KORWIL");
    $position3 = $this->Administration_m->getPosition("VIEWER");

    if(!$position1 && !$position2 && !$position3){
        $this->noAccess("Anda tidak memiliki hak akses untuk halaman ini.");
    }

    if(empty($sess)){
        redirect(site_url('log/in'));
    }
  }

  public function index(){
      $data = array();

      $data['get_employee'] = $this->Administration_m->employee_view()->result_array();      

      $this->template("employee/list_employee_v", "Employee", $data);
  }

  public function add(){
      $data = array();
      $data['get_employee_type'] = $this->Administration_m->get_employee_type()->result_array();
      $data['get_pos'] = $this->Administration_m->getNewPos()->result_array();

      $this->template("employee/add_employee_v", "Add Employee", $data);
  }

  public function update($id){
      $data = array();
      $data['get_employee_type'] = $this->Administration_m->get_employee_type()->result_array();
      $data['get_pos'] = $this->Administration_m->getNewPos()->result_array();  
      $data['get_employee'] = $this->Administration_m->employee_view($id)->row_array();

      $this->template("employee/edit_employee_v", "Edit Employee", $data);
  }
  
  public function submit(){
      $post = $this->input->post(); 

      $posisi = $this->Administration_m->get_pos_id($post['employee_pos_id'])->row_array();

      $this->db->trans_begin(); 

      $inputEmp = array(    
        'fullname' => $post['fullname'],
        'nik' => $post['nik'],
        'provinsi' => $post['provinsi'],
        'kabupaten' => $post['kabupaten'],
        'alamat' => $post['alamat'],
        'email' => $post['email'],
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
          redirect(site_url('employee'));
      } else {
          $this->renderMessage("error");
      }
  }

  public function submit_update(){
      $post = $this->input->post(); 

      $posisi = $this->Administration_m->get_pos_id($post['employee_pos_id'])->row_array();

      $this->db->trans_begin(); 

      $updateEmp = array(
        'fullname' => $post['fullname'],
        'nik' => $post['nik'],
        'provinsi' => $post['provinsi'],
        'kabupaten' => isset($post['kabupaten']) ? $post['kabupaten'] : '',
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
        redirect(site_url('employee/update/' . $post['id']));
      } else {
          $this->renderMessage("error");
      }
  }

  public function get_regency()
  {
      $provinces = $this->input->post('provinsi', true);
      $data = $this->db->get_where('adm_ref_locations', ['province_name' => $provinces, 'regency_name !=' => NULL, 'district_name' => NULL])->result_array();
      echo json_encode($data);
  }
}
