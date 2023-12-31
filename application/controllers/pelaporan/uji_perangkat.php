<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Uji_perangkat extends Telescoope_Controller
{

    var $data;

    public function __construct()
    {

        parent::__construct();

        $this->load->model(array("Administration_m", "Update_barang_m", "Penerimaan_barang_m", "Uji_fungsi_barang_m"));
        $this->data['date_format'] = "h:i A | d M Y";
        $this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');
        $this->data['data'] = array();
        $this->data['post'] = $this->input->post();
        $userdata = $this->Administration_m->getLogin();
        $this->data['dir'] = 'uji_perangkat';
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

        if (empty($sess)) {
            redirect(site_url('log/in'));
        }
    }

    public function index()
    {
        $data = array();
        $data['get_uji_fungsi'] = $this->Uji_fungsi_barang_m->getUji()->result_array();
        $this->template("pelaporan/uji_fungsi/form_uji_fungsi_v", "Berita Acara Uji Fungsi Perangkat TIK", $data);
    }

    public function export()
    {
        $post = $this->input->post();
        $penerimaan_id = $post['uji_id'];
        $tgl_terima = $post['tgl_terima'];
        $file = './assets/uji_aceh_Potola_arabia.pdf';
        force_download($file, NULL);

        // $instalasi_barang = $this->Uji_fungsi_barang_m->get_InstalasiBarangExist($penerimaan_id, $tgl_terima)->row_array();
        // if (isset($instalasi_barang)) {
        //     $instalasi_barang_detail = $this->Uji_fungsi_barang_m->get_InstalasiBarangDetail($instalasi_barang['id'])->result_array();
        //     $data = array();
        //     $data['instalasi_barang'] = $instalasi_barang;
        //     $data['instalasi_barang_detail'] = $instalasi_barang_detail;

        //     $this->load->library('pdf');
        //     $this->pdf->setPaper('A4', 'potrait');
        //     $this->pdf->filename = "laporan_instalasi_barang.pdf";
        //     $this->pdf->set_option('isRemoteEnabled', true);
        //     $this->pdf->load_view('pelaporan/instalasi_barang/export_pdf', $data);
        // } else {
        //     $this->setMessage("Data instalasi barang tidak ditemukan.");
        //     redirect(site_url('pelaporan/instalasi_barang'));
        // }
    }
}
