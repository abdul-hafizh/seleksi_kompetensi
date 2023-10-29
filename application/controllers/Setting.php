<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends Telescoope_Controller {

	var $data;

    public function __construct(){

        // Call the Model constructor
        parent::__construct();

        $this->load->model(array('Administration_m'));

        $this->data['data'] = array();

        $this->data['date_format'] = "h:i A | d M Y";

        $this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');

        $userdata = $this->Administration_m->getLogin();

        $this->data['post'] = $this->input->post();

        $this->data['dir'] = 'setting';

        $this->data['controller_name'] = $this->uri->segment(1);

        $dir = './uploads/'.$this->data['dir'];

        $this->session->set_userdata("module",$this->data['dir']);

        if (!file_exists($dir)){
            mkdir($dir, 0777, true);
        }

        $config['allowed_types'] = '*';
        $config['overwrite'] = false;
        $config['max_size'] = 1024 * 200;
        $config['upload_path'] = $dir;
        $this->load->library('upload', $config);
        $this->load->model("Global_m");
        $this->data['userdata'] = (!empty($userdata)) ? $userdata : array();

        $sess = $this->session->userdata(do_hash(SESSION_PREFIX));

        if(empty($sess)){
            redirect(site_url('log/in'));
        }

    }

    public function role(){
        $data = array();
		$this->template("setting/role/list_role_v", "List Role", $data);
    }

    public function karyawan(){
        $data = array();
		$this->template("setting/karyawan/list_karyawan_v", "List Karyawan", $data);
    }    

    public function suppliers(){
        $data = array();
        $this->template("setting/suppliers/list_suppliers_v", "List Suppliers", $data);
    }

    public function master(){
        $data = array();
		$this->template("setting/master/list_master_v", "List Master", $data);
    }

}
