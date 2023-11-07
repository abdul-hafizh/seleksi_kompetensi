<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class BA_harian extends Telescoope_Controller
{

    var $data;

    public function __construct()
    {

        parent::__construct();

        $this->load->model(array("Administration_m", "Update_barang_m", "Penerimaan_barang_m", "Perencanaan_m"));
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

        if (!$position1 && !$position2 && !$position3) {
            $this->noAccess("Anda tidak memiliki hak akses untuk halaman ini.");
        }

        if (empty($sess)) {
            redirect(site_url('log/in'));
        }
    }

    public function index()
    {
        $data = array();
        $data['get_perencanaan'] = $this->Perencanaan_m->getPerencanaan()->result_array();
        $this->template("pelaporan/ba_harian/form_ba_harian_v", "Data Pelaporan Berita Acara Harian", $data);
    }

    public function export()
    {
        $post = $this->input->post();
        $perencanaan_id = $post['perencanaan_id'];
        $tgl_update = $post['tgl_update'];

        $update_barang = $this->Update_barang_m->get_UpdateBarangExist($perencanaan_id, $tgl_update)->row_array();
        if (isset($update_barang)) {
            $update_barang_detail = $this->Update_barang_m->get_UpdateBarangDetail($update_barang['id'])->result_array();
            $data = array();
            $data['update_barang'] = $update_barang;
            $data['update_barang_detail'] = $update_barang_detail;
            $data['month_list'] = [
                0 => '',
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',
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
            $this->pdf->filename = "laporan_berita_acara_harian.pdf";
            $this->pdf->set_option('isRemoteEnabled', true);
            $this->pdf->load_view('pelaporan/ba_harian/export_pdf', $data);
        } else {
            $this->setMessage("Data tidak ditemukan.");
            redirect(site_url('pelaporan/ba_harian'));
        }
    }

    public function download()
    {
        $post = $this->input->get();
        $perencanaan_id = $post['perencanaan_id'];
        $tgl_update = $post['tgl_update'];

        $update_barang = $this->Update_barang_m->get_UpdateBarangExist($perencanaan_id, $tgl_update)->row_array();
        if (isset($update_barang)) {
            $update_barang_detail = $this->Update_barang_m->get_UpdateBarangDetail($update_barang['id'])->result_array();
            $data = array();
            $data['update_barang'] = $update_barang;
            $data['update_barang_detail'] = $update_barang_detail;
            $data['month_list'] = [
                0 => '',
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',
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
            $this->pdf->filename = "laporan_berita_acara_harian.pdf";
            $this->pdf->set_option('isRemoteEnabled', true);
            $this->pdf->load_view('pelaporan/ba_harian/export_pdf', $data, true);
        } else {
            $this->setMessage("Data tidak ditemukan.");
            redirect(site_url('pelaporan/ba_harian'));
        }
    }
}
