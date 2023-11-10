<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Perencanaan_m extends CI_Model
{

	public function __construct()
	{

		parent::__construct();

		$this->load->helper('security');
	}

	public function getPerencanaan($id = '', $lokasi = '')
	{
		$this->db->select('perencanaan.*, ref_locations.*, lokasi_skd.kode_lokasi, lokasi_skd.nama_lokasi, lokasi_skd.alamat');

		if (!empty($id)) {

			$this->db->where('perencanaan.id', $id);
		}

		if(!empty($lokasi)){

			$this->db->where('lokasi_skd.id', $lokasi);

		}

		$this->db->join('lokasi_skd', 'lokasi_skd.id = perencanaan.kode_lokasi_skd', 'left');
		$this->db->join('ref_locations', 'ref_locations.location_id = lokasi_skd.lokasi_id', 'left');

		return $this->db->get('perencanaan');
	}

	public function getDetail($id = '', $barang_id = '')
	{

		$this->db->select('pd.*, adm_barang.kode_barang_id, adm_barang.nama_barang, adm_barang.merek, adm_barang.satuan, adm_barang.jenis_alat, adm_barang.kelompok, adm_barang.sn');

		if (!empty($id)) {

			$this->db->where('pd.perencanaan_id', $id);
		}

		if (!empty($barang_id)) {

			$this->db->where('pd.barang_id', $barang_id);
		}

		$this->db->join('adm_barang', 'adm_barang.id = pd.barang_id', 'left');

		return $this->db->get('perencanaan_detail pd');
	}

	public function getTotalPerencanaan($provinsi = '', $kabupaten = '', $kode_lokasi_skd = '', $jenis = '', $kelompok = '')
	{

		$user = $this->session->userdata();
		$user_pos = $user['adm_pos_id'];

		$this->db->select('sum(perencanaan_detail.jumlah) as jumlah_perencanaan')
			->join('lokasi_skd', 'perencanaan.kode_lokasi_skd=lokasi_skd.id', 'left')
			->join('ref_locations', 'lokasi_skd.lokasi_id=ref_locations.location_id', 'left')
			->join('perencanaan_detail', 'perencanaan.id=perencanaan_detail.perencanaan_id', 'left')
			->join('adm_barang', 'adm_barang.id=perencanaan_detail.barang_id', 'left');

		if ($user_pos > 2) {
			$this->db->where('lokasi_skd.id', $user['lokasi_skd_id']);
		}

		if (!empty($provinsi)) {

			$this->db->where('ref_locations.province_id', $provinsi);
		}

		if (!empty($kabupaten)) {

			$this->db->where('ref_locations.regency_id', $kabupaten);
		}
		if (!empty($kode_lokasi_skd)) {

			$this->db->where('lokasi_skd.id', $kode_lokasi_skd);
		}
		if (!empty($jenis)) {

			$this->db->where('adm_barang.kelompok', $jenis);
		}
		if (!empty($kelompok)) {

			$this->db->where('adm_barang.jenis_alat', $kelompok);
		}
		return $this->db->get('perencanaan')->row()->jumlah_perencanaan;
	}

	public function getListBarang($provinsi = '', $kabupaten = '', $kode_lokasi_skd = '', $jenis = '', $kelompok = '')
	{
		$user = $this->session->userdata();
		$user_pos = $user['adm_pos_id'];

		$query = $this->db->select('ref_locations.province_name,ref_locations.regency_name, lokasi_skd.nama_lokasi, adm_barang.nama_barang, perencanaan_detail.jumlah, pengiriman_detail.jumlah_kirim, penerimaan_detail.jumlah_terima, penerimaan_detail.jumlah_terpasang')
			->join('lokasi_skd', 'perencanaan.kode_lokasi_skd=lokasi_skd.id', 'left')
			->join('ref_locations', 'lokasi_skd.lokasi_id=ref_locations.location_id', 'left')
			->join('perencanaan_detail', 'perencanaan.id=perencanaan_detail.perencanaan_id', 'left')
			->join('adm_barang', 'adm_barang.id=perencanaan_detail.barang_id', 'left')
			->join('pengiriman_barang', 'pengiriman_barang.perencanaan_id=perencanaan.id', 'left')
			->join('pengiriman_detail', 'pengiriman_detail.pengiriman_id=pengiriman_barang.id and pengiriman_detail.barang_id=adm_barang.id', 'left')
			->join('penerimaan_barang', 'penerimaan_barang.pengiriman_id=pengiriman_barang.id', 'left')
			->join('penerimaan_detail', 'penerimaan_detail.penerimaan_id=penerimaan_barang.id and penerimaan_detail.barang_id=adm_barang.id', 'left');

		if ($user_pos > 2) {
			$query->where('lokasi_skd.id', $user['lokasi_skd_id']);
		}

		if (!empty($provinsi)) {

			$query->where('ref_locations.province_id', $provinsi);
		}

		if (!empty($kabupaten)) {

			$query->where('ref_locations.regency_id', $kabupaten);
		}
		if (!empty($kode_lokasi_skd)) {

			$query->where('lokasi_skd.id', $kode_lokasi_skd);
		}
		if (!empty($jenis)) {

			$query->where('adm_barang.kelompok', $jenis);
		}
		if (!empty($kelompok)) {

			$query->where('adm_barang.jenis_alat', $kelompok);
		}
		return $query->get('perencanaan');
	}

	public function get_jenis_barang()
	{
		$data = $this->db->group_by('kelompok')->order_by('id', 'asc')->get('adm_barang')->result_array();
		return $data;
	}
}
