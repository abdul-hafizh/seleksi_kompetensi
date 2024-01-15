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
        $uji_id = $post['uji_id'];  

        $uji_barang = $this->Uji_fungsi_barang_m->get_BarangExist($uji_id)->row_array();
        if (isset($uji_barang)) {
            $uji_barang_detail = $this->Uji_fungsi_barang_m->get_BarangDetail($uji_barang['id'])->result_array();
            $data = array();
            $data['uji_barang'] = $uji_barang;
            $data['uji_barang_detail'] = $uji_barang_detail;
            $data['month_list'] = [
                '' => '',
                '01' => 'Januari',
                '02' => 'Februari',
                '03' => 'Maret',
                '04' => 'April',
                '05' => 'Mei',
                '06' => 'Juni',
                '07' => 'Juli',
                '08' => 'Agustus',
                '09' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember',
            ];
            $data['day_list'] = [
                'Sun' => 'Minggu',
                'Mon' => 'Senin',
                'Tue' => 'Selasa',
                'Wed' => 'Rabu',
                'Thu' => 'Kamis',
                'Fri' => 'Jumat',
                'Sat' => 'Sabtu',
            ];

            $this->template("pelaporan/uji_perangkat/export_pdf", "Data Update Kegiatan", $data);

            $this->load->library('pdf');
            $this->pdf->setPaper('A4', 'potrait');
            $this->pdf->filename = "Laporan Uji Fungsi IT dan Elektronik Jadwal " . $uji_barang['jadwal_kegiatan'] . ".pdf";
            $this->pdf->set_option('isRemoteEnabled', true);
            $this->pdf->load_view('pelaporan/uji_fungsi/export_pdf', $data);
        } else {
            $this->setMessage("Data uji barang tidak ditemukan.");
            redirect(site_url('pelaporan/uji_perangkat'));        
        }
    }

    public function download()
    {
        $post = $this->input->get();
        $uji_id = $post['uji_id'];

        $uji_barang = $this->Uji_fungsi_barang_m->get_BarangExist($uji_id)->row_array();
        if (isset($uji_barang)) {
            $uji_barang_detail = $this->Uji_fungsi_barang_m->get_BarangDetail($uji_barang['id'])->result_array();
            $data = array();
            $data['uji_barang'] = $uji_barang;
            $data['uji_barang_detail'] = $uji_barang_detail;
            $data['month_list'] = [
                '' => '',
                '01' => 'Januari',
                '02' => 'Februari',
                '03' => 'Maret',
                '04' => 'April',
                '05' => 'Mei',
                '06' => 'Juni',
                '07' => 'Juli',
                '08' => 'Agustus',
                '09' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember',
            ];
            $data['day_list'] = [
                'Sun' => 'Minggu',
                'Mon' => 'Senin',
                'Tue' => 'Selasa',
                'Wed' => 'Rabu',
                'Thu' => 'Kamis',
                'Fri' => 'Jumat',
                'Sat' => 'Sabtu',
            ];

            $this->load->library('pdf');
            $this->pdf->setPaper('A4', 'potrait');
            $this->pdf->filename = "Laporan Uji Fungsi IT dan Elektronik Jadwal " . $uji_barang['jadwal_kegiatan'] . ".pdf";
            $this->pdf->set_option('isRemoteEnabled', true);
            $this->pdf->load_view('pelaporan/uji_fungsi/export_pdf', $data, true);
        } else {
            $this->setMessage("Data uji barang tidak ditemukan.");
            redirect(site_url('pelaporan/uji_perangkat'));
        }
    }
}
