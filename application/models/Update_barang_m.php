<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Update_barang_m extends CI_Model
{

	public function __construct()
	{

		parent::__construct();

		$this->load->helper('security');
	}

	public function getUpdate_barang($id = '', $lokasi = '')
	{
		$this->db->select('uhb.id, uhb.tgl_update_harian, uhb.catatan, uhb.created_at, ref_locations.*, pb.kode_penerimaan, pb.tgl_terima, lokasi_skd.nama_lokasi, lokasi_skd.alamat');

		$this->db->join('penerimaan_barang as pb', 'pb.id = uhb.penerimaan_id', 'left');
		$this->db->join('pengiriman_barang as pb2', 'pb2.id = pb.pengiriman_id', 'left');
		$this->db->join('perencanaan as pr', 'pr.id = pb2.perencanaan_id', 'left');
		$this->db->join('lokasi_skd', 'lokasi_skd.id = pr.kode_lokasi_skd', 'left');
		$this->db->join('ref_locations', 'ref_locations.location_id = lokasi_skd.lokasi_id', 'left');

		if (!empty($id)) {
			$this->db->where('uhb.id', $id);
		}

		if (!empty($lokasi)) {
			$this->db->where('lokasi_skd.id', $lokasi);
		}

		return $this->db->get('update_harian_barang uhb');
	}

	public function getDetail_Penerimaan($id)
	{
		$this->db->select('pd.*, adm_barang.id as barang_id, adm_barang.kode_barang_id, adm_barang.nama_barang, adm_barang.satuan');
		$this->db->from('penerimaan_detail pd');
		$this->db->join('adm_barang', 'adm_barang.id = pd.barang_id', 'left');
		$this->db->where('pd.penerimaan_id', $id);

		return $this->db->get();
	}

	public function getDetail_Perencanaan($id)
	{
		$this->db->select('pd.*, adm_barang.id as barang_id, adm_barang.kode_barang_id, adm_barang.nama_barang, adm_barang.satuan');
		$this->db->from('perencanaan_detail pd');
		$this->db->join('adm_barang', 'adm_barang.id = pd.barang_id', 'left');
		$this->db->where('pd.perencanaan_id', $id);

		return $this->db->get();
	}

	public function get_UpdateBarangExist($perencanaan_id, $tgl_update_harian, $id = null)
	{
		$this->db->select('uhb.*, skd.nama_lokasi, skd.alamat, ref.province_name');
		$this->db->where('uhb.perencanaan_id', $perencanaan_id);
		$this->db->where('uhb.tgl_update_harian', $tgl_update_harian);
		$this->db->join('perencanaan p', 'p.id = uhb.perencanaan_id', 'left');
		$this->db->join('lokasi_skd skd', 'skd.id = p.kode_lokasi_skd', 'left');
		$this->db->join('ref_locations ref', 'ref.location_id = skd.lokasi_id', 'left');
		if (isset($id)) {
			$this->db->where('uhb.id !=', $id);
		}

		$this->db->from('update_harian_barang uhb');
		return $this->db->get('update_harian_barang');
	}

	public function get_UpdateBarangDetail($id = '')
	{
		$this->db->select('pd.*, adm_barang.kode_barang_id, adm_barang.nama_barang, adm_barang.merek, adm_barang.satuan, adm_barang.jenis_alat, adm_barang.kelompok, adm_barang.sn');
		$this->db->where('pd.update_barang_id', $id);
		$this->db->join('adm_barang', 'adm_barang.id = pd.barang_id', 'left');

		return $this->db->get('update_harian_barang_detail pd');
	}
}
