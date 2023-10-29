<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Menu_management extends Telescoope_Controller
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

    $this->data['dir'] = 'menu_management';

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

    $position = $this->Administration_m->getPosition("ADMINISTRATOR");

    if(!$position){
        $this->noAccess("Hanya ADMINISTRATOR yang dapat mengubah data.");
    }

    if(empty($sess)){
        redirect(site_url('log/in'));
    }
  }
  
  public function index()
  {
    include("menu_management/menu_management.php");
  }

  public function data_menu_management()
  {
    include("menu_management/data_menu_management.php");
  }

  public function submit_menu_management()
  {
    include("menu_management/submit_menu_management.php");
  }

  public function generate_menu()
  {
    include("menu_management/generate_menu.php");
  }

}