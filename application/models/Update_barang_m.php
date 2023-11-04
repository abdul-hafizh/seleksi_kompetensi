<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Update_barang_m extends CI_Model
{

	public function __construct()
	{

		parent::__construct();

		$this->load->helper('security');
	}

	public function getUpdate_barang($id = '')
	{
		$this->db->select('update_harian_barang.*');
		if (!empty($id)) {
			$this->db->where('update_harian_barang.id', $id);
		}
		return $this->db->get('update_harian_barang');

		// $this->db->select('update_barang.*, ref_locations.*, peb.id as penerimaan_id, peb.jumlah_terima, peb.tgl_terima, peb.rusak, peb.terpasang, pr.nama_barang as nama_barang_pr, pr.jenis_barang, pb.jumlah_kirim, pb.tgl_kirim, lokasi_skd.kode_lokasi, lokasi_skd.nama_lokasi');



		// $this->db->join('penerimaan_barang as peb', 'peb.id = update_barang.penerimaan_id', 'left');
		// $this->db->join('pengiriman_barang as pb', 'pb.id = peb.pengiriman_id', 'left');
		// $this->db->join('perencanaan as pr', 'pr.id = pb.perencanaan_id', 'left');
		// $this->db->join('lokasi_skd', 'lokasi_skd.id = pr.kode_lokasi_skd', 'left');
		// $this->db->join('ref_locations', 'ref_locations.location_id = lokasi_skd.lokasi_id', 'left');

		// return $this->db->get('update_barang');
	}

	public function getDetail_Penerimaan($id)
	{
		$this->db->select('pd.*, adm_barang.id as barang_id, adm_barang.kode_barang_id, adm_barang.nama_barang, adm_barang.satuan');
		$this->db->from('penerimaan_detail pd');
		$this->db->join('adm_barang', 'adm_barang.id = pd.barang_id', 'left');
		// if (!empty($id)) {
		$this->db->where('pd.penerimaan_id', $id);
		// }

		return $this->db->get();
	}

	public function getDetail_Perencanaan($id)
	{
		$this->db->select('pd.*, adm_barang.id as barang_id, adm_barang.kode_barang_id, adm_barang.nama_barang, adm_barang.satuan');
		$this->db->from('perencanaan_detail pd');
		$this->db->join('adm_barang', 'adm_barang.id = pd.barang_id', 'left');
		// if (!empty($id)) {
		$this->db->where('pd.perencanaan_id', $id);
		// }

		return $this->db->get();
	}

	public function get_UpdateBarangExist($perencanaan_id, $tgl_update_harian)
	{
		$this->db->select('uhb.*');
		$this->db->where('uhb.perencanaan_id', $perencanaan_id);
		$this->db->where('uhb.tgl_update_harian', $tgl_update_harian);
		$this->db->from('update_harian_barang uhb');
		return $this->db->get('update_harian_barang');
	}
}
