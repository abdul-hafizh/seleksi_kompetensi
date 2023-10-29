<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Administration extends Telescoope_Controller
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

    $this->data['dir'] = 'administration';

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

    if(empty($sess)){
        redirect(site_url('log/in'));
    }
  }

  public function document($param1 = "", $param2 = "", $param3 = "")
  {
    switch ($param1) {
      case 'news':
        switch ($param2) {

          case 'tambah':
            $this->submit_news();
            break;

          case 'hapus':
            $this->delete_news($param3);
            break;

          case 'hapus_lkpp':
            $this->delete_news_lkpp($param3);
            break;

          case 'add':
            $this->submit_news_lkpp();
            break;
        }
      default:
        $this->news();
        break;
    }
  }

  public function user_management($param1 = "", $param2 = "", $param3 = "")
  {

    switch ($param1) {

      case 'user_access':

        switch ($param2) {

          case 'add_user_access':
            $this->add_user_access();
            break;

          case 'ubah':
            $this->edit_user_access($param3);
            break;

          case 'hapus':
            $this->delete_user_access($param3);
            break;

          default:
            $this->user_access();
            break;
        }
        break;

      case 'hcis':

        switch ($param2) {

          case 'detail':
            $this->detail_hcis($param3);
            break;

          default:
            include("administration/user_management/hcis/hcis.php");
            break;
        }
        break;

      case 'employee':

        switch ($param2) {

          case 'add_employee':
            $this->add_employee();
            break;

          case 'ubah':
            $this->edit_employee($param3);
            break;

          case 'hapus':
            $this->delete_employee($param3);
            break;

          case 'add_job_post':
            $this->add_job_post($param3);
            break;

          case 'hapus_job_post':
            $this->delete_job_post($param3);
            break;

          default:
            $this->employee();
            break;
        }

        break;

      default:
        $this->user_management();
        break;
    }
  }

  public function master_data($param1 = "", $param2 = "", $param3 = "")
  {


    switch ($param1) {

      case 'deskripsi_matgis':

        switch ($param2) {

          case 'tambah':
            $this->add_deskripsi_matgis();
            break;

          case 'ubah':
            $this->edit_deskripsi_matgis($param3);
            break;

          case 'hapus':
            $this->delete_deskripsi_matgis($param3);
            break;

          default:

            $this->deskripsi_matgis();
            break;
        }

        break;

      case 'proyek':

        switch ($param2) {

          case 'tambah':
            $this->add_proyek();
            break;

          case 'ubah':
            $this->edit_proyek($param3);
            break;

          case 'hapus':
            $this->delete_proyek($param3);
            break;

          default:
            $this->proyek();
            break;
        }

        break;

        case 'hse':

          switch ($param2) {
  
            case 'verivikasi':
              $this->hse_verivikasi($param3);
              break;
  
            // case 'ubah':
            //   $this->edit_proyek($param3);
            //   break;
  
            // case 'hapus':
            //   $this->delete_proyek($param3);
            //   break;
  
            default:
              $this->hse($param2);
              break;
          }
  
          break;

      case 'pph':

        switch ($param2) {

          case 'tambah':
            $this->add_pph();
            break;

          case 'ubah':
            $this->edit_pph($param3);
            break;

          case 'hapus':
            $this->delete_pph($param3);
            break;

          case 'check':
            $this->check_pph($param3);
            break;

          default:
            $this->pph();
            break;
        }

        break;
        //haqim
      case 'divisi_utama':

        switch ($param2) {

          case 'add_divisi_utama':
            $this->add_divisi_utama();
            break;

          case 'ubah':
            $this->edit_divisi_utama($param3);
            break;

          case 'hapus':
            $this->delete_divisi_utama($param3);
            break;


          default:
            $this->divisi_utama();
            break;
        }

        break;

      case 'departemen':

        switch ($param2) {

          case 'add_departemen':
            $this->add_departemen();
            break;

          case 'ubah':
            $this->edit_departemen($param3);
            break;

          case 'nonaktif':
            $this->nonaktif_departemen($param3);
            break;

          default:
            $this->departemen();
            break;
        }

        break;

      case 'divisi':

        switch ($param2) {

          case 'add_divisi':
            $this->add_divisi();
            break;

          case 'ubah':
            $this->edit_divisi($param3);
            break;

          case 'nonaktif':
            $this->nonaktif_divisi($param3);
            break;

          default:
            $this->divisi();
            break;
        }

        break;

      case 'biro':

        switch ($param2) {

          case 'add_biro':
            $this->add_biro();
            break;

          case 'ubah':
            $this->edit_biro($param3);
            break;

          case 'nonaktif':
            $this->nonaktif_biro($param3);
            break;

          default:
            $this->biro();
            break;
        }

        break;
        //end


      case 'divisi_departemen':

        switch ($param2) {

          case 'add_divisi_departemen':
            $this->add_divisi_departemen();
            break;

          case 'ubah':
            $this->edit_divisi_departemen($param3);
            break;

          case 'nonaktif':
            $this->nonaktif_divisi_departemen($param3);
            break;

          default:
            $this->divisi_departemen();
            break;
        }

        break;

      case 'gudang':

        switch ($param2) {

          case 'tambah':
            $this->add_gudang();
            break;

          case 'ubah':
            $this->edit_gudang($param3);
            break;

          case 'hapus':
            $this->delete_gudang($param3);
            break;

          default:
            $this->gudang();
            break;
        }

        break;


      case 'kapal':

        switch ($param2) {

          case 'tambah':
            $this->add_kapal();
            break;

          case 'ubah':
            $this->edit_kapal($param3);
            break;

          case 'hapus':
            $this->delete_kapal($param3);
            break;

          default:
            $this->kapal();
            break;
        }

        break;


      case 'ruangan':

        switch ($param2) {

          case 'tambah':
            $this->add_ruangan();
            break;

          case 'ubah':
            $this->edit_ruangan($param3);
            break;

          case 'hapus':
            $this->delete_ruangan($param3);
            break;

          default:
            $this->ruangan();
            break;
        }

        break;

      case 'property_aset':

        switch ($param2) {

          case 'tambah':
            $this->add_property_aset();
            break;

          case 'ubah':
            $this->edit_property_aset($param3);
            break;

          case 'hapus':
            $this->delete_property_aset($param3);
            break;

          default:
            $this->property_aset();
            break;
        }

        break;

      case 'anggaran':

        switch ($param2) {

          case 'tambah':
            $this->add_anggaran();
            break;

          case 'ubah':
            $this->edit_anggaran($param3);
            break;

          case 'hapus':
            $this->delete_anggaran($param3);
            break;

          default:
            $this->anggaran();
            break;
        }

        break;

      case 'rks':

        switch ($param2) {

          case 'tambah':
            $this->add_rks();
            break;

          case 'tambah':
            $this->submit_add_rks_header();
            break;

          case 'tambah':
            $this->submit_add_rks_header_sub();
            break;

          case 'tambah':
            $this->submit_add_rks_description();
            break;

          case 'ubah':
            $this->edit_rks($param3);
            break;

          case 'hapus':
            $this->delete_rks($param3);
            break;

          default:
            $this->rks();
            break;
        }

        break;

      case 'kategori_pajak':

        switch ($param2) {

          case 'tambah':
            $this->add_kategori_pajak();
            break;

          case 'ubah':
            $this->edit_kategori_pajak($param3);
            break;

          case 'hapus':
            $this->delete_kategori_pajak($param3);
            break;

          default:
            $this->kategori_pajak();
            break;
        }

        break;

      case 'delivery_point':

        switch ($param2) {

          case 'add_delivery_point':
            $this->add_delivery_point();
            break;

          case 'ubah':
            $this->edit_delivery_point($param3);
            break;

          case 'nonaktif':
            $this->nonaktif_delivery_point($param3);
            break;

          default:
            $this->delivery_point();
            break;
        }

        break;

      case 'daftar_kantor':

        switch ($param2) {

          case 'add_daftar_kantor':
            $this->add_daftar_kantor();
            break;

          case 'ubah':
            $this->edit_daftar_kantor($param3);
            break;

          case 'hapus':
            $this->delete_daftar_kantor($param3);
            break;


          default:
            $this->daftar_kantor();
            break;
        }

        break;

      case 'currency':

        switch ($param2) {

          case 'add_currency':
            $this->add_currency();
            break;

          case 'ubah':
            $this->edit_currency($param3);
            break;

          case 'hapus':
            $this->delete_currency($param3);
            break;


          default:
            $this->currency();
            break;
        }

        break;

      case 'employee_type':

        switch ($param2) {

          case 'add_employee_type':
            $this->add_employee_type();
            break;

          case 'ubah':
            $this->edit_employee_type($param3);
            break;

          case 'hapus':
            $this->delete_employee_type($param3);
            break;


          default:
            $this->employee_type();
            break;
        }

        break;

      case 'salutation':

        switch ($param2) {

          case 'add_salutation':
            $this->add_salutation();
            break;

          case 'ubah':
            $this->edit_salutation($param3);
            break;

          case 'hapus':
            $this->delete_salutation($param3);
            break;


          default:
            $this->salutation();
            break;
        }

        break;

      case 'menu_management':

        $this->menu_management();

        break;


      case 'lintasan':

        switch ($param2) {

          case 'add_lintasan':
            $this->add_lintasan();
            break;

          case 'ubah':
            $this->edit_lintasan($param3);
            break;

          case 'nonaktif':
            $this->nonaktif_lintasan($param3);
            break;

          default:
            $this->lintasan();
            break;
        }

        break;

        //y delegasi
      case 'delegasi_tugas':

        switch ($param2) {

          case 'add_delegasi_tugas':
            $this->add_delegasi_tugas();
            break;

          case 'ubah':
            $this->edit_delegasi_tugas($param3);
            break;

          default:
            $this->delegasi_tugas();
            break;
        }
        //end
        break;

      case 'master_mdiv':

        switch ($param2) {

          case 'add':
            include("administration/master_data/master_mdiv/add_master_mdiv.php");
            break;

          case 'edit':
            include("administration/master_data/master_mdiv/edit_master_mdiv.php");
            break;

          case 'submit_add':
            include("administration/master_data/master_mdiv/submit_add_master_mdiv.php");
            break;

          case 'submit_edit':
            include("administration/master_data/master_mdiv/submit_edit_master_mdiv.php");
            break;

          case 'data':
            include("administration/master_data/master_mdiv/data_master_mdiv.php");
            break;

          case 'nonaktif':
            include("administration/master_data/master_mdiv/nonaktif_master_mdiv.php");
            break;

          default:
            include("administration/master_data/master_mdiv/master_mdiv.php");
            break;
        }
        //end
        break;

      case 'lokasi_proyek':

        switch ($param2) {

          case 'add':
            include("administration/master_data/lokasi_proyek/add_lokasi_proyek.php");
            break;

          case 'edit':
            include("administration/master_data/lokasi_proyek/edit_lokasi_proyek.php");
            break;

          case 'submit_add':
            include("administration/master_data/lokasi_proyek/submit_add_lokasi_proyek.php");
            break;

          case 'submit_edit':
            include("administration/master_data/lokasi_proyek/submit_edit_lokasi_proyek.php");
            break;

          case 'data':
            include("administration/master_data/lokasi_proyek/data_lokasi_proyek.php");
            break;

          case 'nonaktif':
            include("administration/master_data/lokasi_proyek/nonaktif_lokasi_proyek.php");
            break;

          default:
            include("administration/master_data/lokasi_proyek/lokasi_proyek.php");
            break;
        }
        break;

      case 'penilaian_resiko_paket':

        switch ($param2) {

          case 'add':
            include("administration/master_data/penilaian_resiko_paket/add_resiko_paket.php");
            break;

          case 'edit':
            include("administration/master_data/lokasi_proyek/edit_lokasi_proyek.php");
            break;

          case 'submit_add':
            include("administration/master_data/lokasi_proyek/submit_add_lokasi_proyek.php");
            break;

          case 'submit_edit':
            include("administration/master_data/lokasi_proyek/submit_edit_lokasi_proyek.php");
            break;

          default:
            include("administration/master_data/penilaian_resiko_paket/penilaian_resiko_paket.php");
            break;
        }
        break;
        //end

    }
  }

  public function admin_tools($param1 = "", $param2 = "", $param3 = "", $param4 = "")
  {

    switch ($param1) {

      case 'hierarchy_position':

        switch ($param2) {

          case 'add':
            $this->act_hierarchy_position($param2, $param3, $param4);
            break;

          case 'edit':
            $this->act_hierarchy_position($param2, $param3, $param4);
            break;

          case 'delete':
            $this->delete_hierarchy_position($param3, $param4);
            break;


          default:
            $this->hierarchy_position();
            break;
        }

        break;

      case 'position':

        switch ($param2) {

          case 'add_position':
            $this->add_position();
            break;

          case 'ubah':
            $this->edit_position($param3);
            break;

          default:
            $this->position();
            break;
        }

        break;

      case 'exchange_rate':

        switch ($param2) {

          case 'add_exchange_rate':
            $this->add_exchange_rate();
            break;

          case 'ubah':
            $this->edit_exchange_rate($param3);
            break;

          case 'hapus':
            $this->delete_exchange_rate($param3);
            break;

          default:
            $this->exchange_rate();
            break;
        }
        break;


        //START
      case 'lintasan':

        switch ($param2) {

          case 'tambah':
            $this->add_lintasan();
            break;

          case 'ubah':
            $this->edit_lintasan($param3);
            break;

          case 'hapus':
            $this->delete_lintasan($param3);
            break;

          default:
            $this->lintasan();
            break;
        }

        break;
    }
  }

  public function helpdesk($param1 = "", $param2 = "", $param3 = "", $param4 = "")
  {
    switch ($param1) {
      case 'ticket':
        switch ($param2) {
          case 'edit_ticket':
            $this->edit_ticket();
            break;

          case 'detail_ticket':
            $this->detail_ticket();
            break;

          case 'delete_ticket':
            $this->delete_ticket($param3);
            break;

          case 'get_ticket':
            $this->get_ticket();
            break;

          case 'submit_ticket':
            $this->submit_ticket();
            break;

          case 'add_chat':
            $this->add_chat();
            break;

          case 'delete_chat':
            $this->delete_chat();
            break;

          default:
            $this->ticket();
            break;
        }
        break;
      case 'faq':
        switch ($param2) {
          case 'add_faq':
            $this->add_faq();
            break;

          case 'delete_faq':
            $this->delete_faq();
            break;

          default:
            $this->faq();
            break;
        }
        break;

      case 'flow':
        switch ($param2) {
          case 'add_flow':
            $this->add_flow();
            break;

          case 'delete_flow':
            $this->delete_flow();
            break;

          default:
            $this->flow();
            break;
        }
        break;

      case 'video':
        switch ($param2) {
          case 'add_video':
            $this->add_video();
            break;

          case 'delete_video':
            $this->delete_video();
            break;

          default:
            $this->video();
            break;
        }
        break;

      case 'pelaporan':
        switch ($param2) {
          case 'delete_pelaporan':
            $this->delete_pelaporan();
            break;

          default:
            $this->pelaporan();
            break;
        }
        break;

      default:
        # code...
        break;
    }
  }

  public function user_access()
  {
    include("administration/user_management/user_access/user_access.php");
  }

  public function add_user_access()
  {
    include("administration/user_management/user_access/add_user_access.php");
  }

  public function edit_user_access($id)
  {
    include("administration/user_management/user_access/edit_user_access.php");
  }

  public function submit_add_user_access()
  {
    include("administration/user_management/user_access/submit_add_user_access.php");
  }

  public function submit_edit_user_access()
  {
    include("administration/user_management/user_access/submit_edit_user_access.php");
  }

  public function delete_user_access($id)
  {
    include("administration/user_management/user_access/delete_user_access.php");
  }

  public function data_user_access()
  {
    include("administration/user_management/user_access/data_user_access.php");
  }

  public function alias_user_access()
  {
    include("administration/user_management/user_access/alias_user_access.php");
  }

  public function employee()
  {
    include("administration/user_management/employee/employee.php");
  }

  public function add_employee()
  {
    include("administration/user_management/employee/add_employee.php");
  }
  
  public function edit_employee($id)
  {
    include("administration/user_management/employee/edit_employee.php");
  }

  public function submit_employee()
  {
    include("administration/user_management/employee/submit_employee.php");
  }

  public function submit_edit_employee()
  {
    include("administration/user_management/employee/submit_edit_employee.php");
  }

  public function delete_employee($id)
  {
    include("administration/user_management/employee/delete_employee.php");
  }

  public function data_employee()
  {
    include("administration/user_management/employee/data_employee.php");
  }

  public function alias_employee()
  {
    include("administration/user_management/employee/alias_employee.php");
  }

  public function add_job_post($id)
  {
    include("administration/user_management/employee/add_job_post.php");
  }

  public function submit_job_post()
  {
    include("administration/user_management/employee/submit_job_post.php");
  }

  public function data_job_post()
  {
    include("administration/user_management/employee/data_job_post.php");
  }

  public function delete_job_post($id)
  {
    include("administration/user_management/employee/delete_job_post.php");
  }
}