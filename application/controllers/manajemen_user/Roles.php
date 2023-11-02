<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Roles extends Telescoope_Controller
{

  var $data;

  public function __construct()
  {
    parent::__construct();

    $this->load->model(array("Administration_m"));

    $this->data['date_format'] = "h:i A | d M Y";

    $this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');

    $this->data['data'] = array();

    $this->data['post'] = $this->input->post();

    $userdata = $this->Administration_m->getLogin();

    $this->data['dir'] = 'roles';

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
  
  public function index()
  {
    $view = 'manajemen_user/roles/menu_management_v';

    $jobtitle = $this->security->xss_clean($this->input->get("jobtitle"));

    $data = array(
        'jumlah' =>1,
        'jobtitle' => $this->Administration_m->get_job_title()->result_array(),
        'current_jobtitle' => $jobtitle
        );

    $menu = $this->db->order_by('menu_code','asc')->get("adm_menu")->result_array();
    $selected_menu = $this->db->where("jobtitle",$jobtitle)->get("adm_jobtitle_menu")->result_array();
    $selected = array();

    foreach ($selected_menu as $key => $value) {
        $selected[] = $value['menu_id'];
    }

    $mymenu = array();

    foreach ($menu as $key => $value) {
        $value['selected'] = (in_array($value['menuid'], $selected));
        $mymenu[$value['parent_id']][] = $value;
    }

    $html = "<ul>";

    foreach ($mymenu[0] as $k => $v) {
        $selected = ($v['selected']) ? "checked" : "";
        $html .= "<li id='".$v['menuid']."' data-parent='0'><input type='checkbox' name='menu[]' value='".$v['menuid']."' ".$selected."> ".$v['menu_name'];
        if(isset($mymenu[$v['menuid']])){
            $html .= "<ul>";
            foreach ($mymenu[$v['menuid']] as $k2 => $v2) {
                $selected = ($v2['selected']) ? "checked" : "";
                $html .= "<li id='".$v2['menuid']."' data-parent='".$v['menuid']."'><input type='checkbox' name='menu[]' value='".$v2['menuid']."' ".$selected."> ".$v2['menu_name'];
                if(isset($mymenu[$v2['menuid']])){
                    $html .= "<ul>";
                    foreach ($mymenu[$v2['menuid']] as $k3 => $v3) {
                        $selected = ($v3['selected']) ? "checked" : "";
                        $html .= "<li id='".$v3['menuid']."' data-parent='".$v2['menuid']."'><input type='checkbox' name='menu[]' value='".$v3['menuid']."' ".$selected."> ".$v3['menu_name']."</li>";
                    }
                    $html .= "</ul>";
                }
                $html .= "</li>";
            }
            $html .= "</ul>";
        }
        $html .= "</li>";
    }

    $html .= "</ul>";

    $data['html'] = $html;
    $data['selected'] = $selected;

    $this->template($view, "Role Manajemen", $data);
  }

  public function data_menu_management()
  {
    $id = $this->input->get("id");

    $jobtitle = $this->input->get("jobtitle");

    $id = ($id == "#") ? 0 : $id;

    $get = $this->db->where(array("parent_id"=>$id))->order_by('menu_code','asc')->get("adm_menu")->result_array();

    $n = 0;

    $data = array();

    foreach ($get as $key => $value) {
        $selected = $this->db->where(array("menu_id"=>$value['menuid'],"pos_id"=>$jobtitle))->get("adm_jobtitle_menu")->row_array();

        $child = $this->db->where(array("parent_id"=>$value['menuid']))->order_by('menu_code','asc')->get("adm_menu")->num_rows();
        $name = $value['menu_name'];
        $have_child = (!empty($child));
        $data[$n] = array(
            "id"=>(int)$value['menuid'],
            "text"=>$name,
            "children"=>$have_child,
            "state"=>array(
                "opened"=>true,
                "undetermined"=>true,
                "selected"=>(!empty($selected)) ? true : false
                )
            );
        $n++;
    }

    $this->output->set_content_type('application/json')->set_output(json_encode($data));
  }

  public function submit_menu_management()
  {
    $post = $this->input->post();

    $jobtitle = $post['jobtitle'];

    $selected_menu = array_unique($post['menu']);

    $this->db->where("jobtitle",$jobtitle)->delete("adm_jobtitle_menu");

    if(!empty($selected_menu)){
        $filter = array();
        foreach ($selected_menu as $key => $value) {
            $menu = $this->db->where("menuid",$value)->get("adm_menu")->row_array();
            $parent_id = $menu['parent_id'];
            $menu2 = $this->db->where("menuid",$parent_id)->get("adm_menu")->row_array();
            $parent_id2 = $menu2['parent_id'];
            if(!in_array($value, $filter)){
                $filter[] = $value;
            }
            
            if(!in_array($parent_id, $filter)){
                $filter[] = (!empty($parent_id)) ? $parent_id : 0;
            }
        }
        foreach ($filter as $key => $value) {
            $where = array("jobtitle"=>$jobtitle,"menu_id"=>$value);
            $check = $this->db->where($where)->get("adm_jobtitle_menu")->num_rows();
            if(empty($check)){
                $this->db->insert("adm_jobtitle_menu",$where);
            }
        }
    }
  }

  public function generate_menu()
  {
    $posisi = $this->db->get("adm_pos")->result_array();
    $menu = $this->db->get("adm_menu")->result_array();

    foreach ($posisi as $key => $value) {
        foreach ($menu as $k => $v) {
            $pos_id = $value['pos_id'];
            $menu_id = $v['menuid'];
            $data = array("menu_id"=>$menu_id,"pos_id"=>$pos_id);
            $check = $this->db->where($data)->get("adm_pos_menu")->num_rows();
            if(empty($check)){
                $this->db->insert("adm_pos_menu",$data);
            }
        }
    }
  }

}