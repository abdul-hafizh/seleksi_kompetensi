<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_access extends Telescoope_Controller
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

    $this->data['dir'] = 'user_access';

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

      $pos1 = $this->Administration_m->getPosition("KORWIL");
      $pos2 = $this->Administration_m->getPosition("VIEWER");   

      $data['get_user'] = $this->Administration_m->user_access_view()->result_array();

      if($pos1){        
        $data['get_user'] = $this->Administration_m->user_access_view("", $this->data['userdata']['provinsi'], "KORWIL")->result_array();      
      }

      if($pos2){        
        $data['get_user'] = $this->Administration_m->user_access_view("", "", "VIEWER", $this->data['userdata']['employee_id'])->result_array();      
      }

      $this->template("user_access/list_user_v", "User Access", $data);
  }

  public function add(){
      $data = array();
      $pos1 = $this->Administration_m->getPosition("KORWIL");
      $pos2 = $this->Administration_m->getPosition("VIEWER");

      $data['get_employee'] = $this->Administration_m->get_new_employee()->result_array();

      if($pos1){

        $data['get_employee'] = $this->Administration_m->get_new_employee($this->data['userdata']['provinsi'], "KORWIL")->result_array();

      }

      if($pos2){

        $data['get_employee'] = $this->Administration_m->get_new_employee("", "VIEWER", $this->data['userdata']['employee_id'])->result_array();

      }

      $this->template("user_access/add_user_v", "Add User Access", $data);
  }
  
  public function submit(){
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
              redirect(site_url('user_access'));
          
            } else {
              $this->renderMessage("error");
          }
          
      } else {
        $this->setMessage("Username telah digunakan.");
        $this->db->trans_rollback();
        redirect(site_url('user_access/add'));
      }
  }
}
