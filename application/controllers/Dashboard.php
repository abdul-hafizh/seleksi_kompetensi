<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Telescoope_Controller
{

	var $data;

	public function __construct()
	{

		parent::__construct();

		$this->load->model(array('Administration_m', 'Provinsi_m', 'Perencanaan_m', 'Pengiriman_barang_m', 'Penerimaan_barang_m'));

		$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');

		$userdata = $this->Administration_m->getLogin();

		$this->data['userdata'] = (!empty($userdata)) ? $userdata : array();

		$sess = $this->session->all_userdata();
	}

	public function test()
	{
		print_r($this->session->all_userdata());
	}

	public function get_data_dashboard()
	{
		$provinsi = $this->input->post('provinsi', true);
		$kabupaten = $this->input->post('kabupaten', true);
		$kode_lokasi_skd = $this->input->post('kode_lokasi_skd', true);
		$jenis = $this->input->post('jenis', true);
		$kelompok = $this->input->post('kelompok', true);
		$total_perencanaan = $this->Perencanaan_m->getTotalPerencanaan($provinsi, $kabupaten, $kode_lokasi_skd, $jenis, $kelompok);
		$total_pengiriman = $this->Pengiriman_barang_m->getTotalPengiriman($provinsi, $kabupaten, $kode_lokasi_skd, $jenis, $kelompok);
		$total_diterima = $this->Penerimaan_barang_m->getTotalPenerimaan($provinsi, $kabupaten, $kode_lokasi_skd, $jenis, $kelompok);
		$data['total_perencanaan'] = (!empty($total_perencanaan)) ? $total_perencanaan : 0;
		$data['total_pengiriman'] = (!empty($total_pengiriman)) ? $total_pengiriman : 0;
		$data['total_penerimaan'] = (!empty($total_diterima->jumlah_terima)) ? $total_diterima->jumlah_terima : 0;
		$data['total_terinstall'] = (!empty($total_diterima->jumlah_terpasang)) ? $total_diterima->jumlah_terpasang : 0;
		echo json_encode($data);
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
		$data = $this->db->get_where('lokasi_skd', ['lokasi_id' => $kabupaten])->result_array();
		echo json_encode($data);
	}

	public function get_kelompok()
	{
		$jenis = $this->input->post('jenis', true);
		$data = $this->db->group_by('jenis_alat')->get_where('adm_barang', ['kelompok' => $jenis])->result_array();
		echo json_encode($data);
	}

	public function get_barang_dashboard()
	{
		$post = $this->input->post();

		$draw = $post['draw'];
		$row = $post['start'];
		$rowperpage = $post['length'];
		$columnIndex = $post['order'][0]['column'];
		$columnName = $post['columns'][$columnIndex]['data'];
		$column_search  = array('province_name', 'regency_name', 'nama_lokasi', 'nama_barang');


		$searchColumn = $post['columns'];
		$provinsi = '';
		$regency = '';
		$kode_lokasi = '';
		$jenis = '';
		$kelompok = '';
		if (!empty($searchColumn[0]['search']['value'])) {
			$value = $searchColumn[0]['search']['value'];
			// $search['project_new.name'] = $value;
			$filterjs = json_decode($value);
			$provinsi = $filterjs[0]->provinsi;
			$regency = $filterjs[0]->kabupaten;
			$kode_lokasi = $filterjs[0]->kode_lokasi_skd;
			$jenis = $filterjs[0]->jenis;
			$kelompok = $filterjs[0]->kelompok;
		}

		$search = $post['search']['value'];
		if (!empty($search)) {
			$this->db->group_start();
			foreach ($column_search as $item) {
				$this->db->or_like($item, $search);
			}
			$this->db->group_end();
		}

		$this->db->limit($rowperpage, $row);

		$result = $this->Perencanaan_m->getListBarang($provinsi, $regency, $kode_lokasi, $jenis, $kelompok)->result_array();

		$count = $this->Perencanaan_m->getListBarang($provinsi, $regency, $kode_lokasi, $jenis, $kelompok)->num_rows();

		$totalRecords = $count;
		$totalRecordwithFilter = $count;

		$data = array();
		$no = 1;
		foreach ($result as $v) {

			$row    = array();
			$row[]  = $no;
			$row[]  = $v['province_name'];
			$row[]  =  $v['regency_name'];
			$row[]  =  $v['nama_lokasi'];
			$row[]  =  $v['nama_barang'];
			$row[]  =  $v['jumlah'];
			$row[]  =  $v['jumlah_kirim'];
			$row[]  =  $v['jumlah_terima'];
			$row[]  =  $v['jumlah_terpasang'];


			$data[] = $row;

			$no++;
		}

		## Response
		$response = array(
			"draw" => intval($draw),
			"recordsTotal" => $totalRecords,
			"recordsFiltered" => $totalRecordwithFilter,
			"data" => $data
		);

		echo json_encode($response);
	}

	public function get_sdm_dashboard()
	{
		$post = $this->input->post();

		$draw = $post['draw'];
		$row = $post['start'];
		$rowperpage = $post['length'];
		$search = $post['search']['value'];
		$columnIndex = $post['order'][0]['column'];
		$columnName = $post['columns'][$columnIndex]['data'];
		$column_search  = array('province_name', 'regency_name', 'nama_lokasi', 'fullname', 'adm_employee.alamat', 'phone', 'pos_name');

		$searchColumn = $post['columns'];
		$search = [];
		$provinsi = '';
		$regency = '';
		$kode_lokasi = '';
		if (!empty($searchColumn[0]['search']['value'])) {
			$value = $searchColumn[0]['search']['value'];
			// $search['project_new.name'] = $value;
			$filterjs = json_decode($value);
			$provinsi = $filterjs[0]->provinsi;
			$regency = $filterjs[0]->kabupaten;
			$kode_lokasi = $filterjs[0]->kode_lokasi_skd;
		}


		$search = $post['search']['value'];
		if (!empty($search)) {
			$this->db->group_start();
			foreach ($column_search as $item) {
				$this->db->or_like($item, $search);
			}
			$this->db->group_end();
		}

		$this->db->limit($rowperpage, $row);

		$result = $this->Administration_m->getListUser($provinsi, $regency, $kode_lokasi)->result_array();


		$count = $this->Administration_m->getListUser($provinsi, $regency, $kode_lokasi)->num_rows();

		$totalRecords = $count;
		$totalRecordwithFilter = $count;

		$data = array();
		$no = 1;
		foreach ($result as $v) {

			$row    = array();
			$row[]  = $no;
			$row[]  = $v['province_name'];
			$row[]  =  $v['regency_name'];
			$row[]  =  $v['nama_lokasi'];
			$row[]  =  $v['fullname'];
			$row[]  =  $v['alamat'];
			$row[]  =  $v['phone'];
			$row[]  =  $v['pos_name'];


			$data[] = $row;

			$no++;
		}

		## Response
		$response = array(
			"draw" => intval($draw),
			"recordsTotal" => $totalRecords,
			"recordsFiltered" => $totalRecordwithFilter,
			"data" => $data
		);

		echo json_encode($response);
	}
}
