<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengiriman_barang_m extends CI_Model
{

	public function __construct()
	{

		parent::__construct();

		$this->load->helper('security');
	}

	public function getPengiriman_barang($id = '', $lokasi = '')
	{

		$this->db->select('pengiriman_barang.*, lokasi_skd.kode_lokasi, lokasi_skd.nama_lokasi, ref_locations.*');

		if (!empty($id)) {

			$this->db->where('pengiriman_barang.id', $id);
		}

		if (!empty($lokasi)) {

			$this->db->where('lokasi_skd.lokasi_id', $lokasi);
		}

		$this->db->join('perencanaan as pr', 'pr.id = pengiriman_barang.perencanaan_id', 'left');
		$this->db->join('lokasi_skd', 'lokasi_skd.id = pr.kode_lokasi_skd', 'left');
		$this->db->join('ref_locations', 'ref_locations.location_id = lokasi_skd.lokasi_id', 'left');

		return $this->db->get('pengiriman_barang');
	}

	public function getDetailKirim($id = '', $pr = '', $br = '')
	{
		$this->db->select('pd.*, adm_barang.kode_barang_id, adm_barang.nama_barang, adm_barang.merek, adm_barang.satuan, adm_barang.jenis_alat, adm_barang.kelompok, adm_barang.sn, pb.perencanaan_id');
		$this->db->from('pengiriman_detail pd');
		$this->db->join('pengiriman_barang as pb', 'pb.id = pd.pengiriman_id', 'left');
		$this->db->join('perencanaan as pr', 'pr.id = pb.perencanaan_id', 'left');
		$this->db->join('adm_barang', 'adm_barang.id = pd.barang_id', 'left');

		if (!empty($id)) {
			$this->db->where('pb.id', $id);
		}

		if (!empty($pr)) {
			$this->db->where('pb.perencanaan_id', $pr);
		}

		if (!empty($br)) {
			$this->db->where('pd.barang_id', $br);
		}

		$this->db->group_by('pd.id');

		return $this->db->get();
	}

	public function getDetail($id = '')
	{
		$this->db->select('pd.*, adm_barang.kode_barang_id, adm_barang.nama_barang, adm_barang.merek, adm_barang.satuan, adm_barang.jenis_alat, adm_barang.kelompok, adm_barang.sn');
		$this->db->from('pengiriman_detail pd');
		$this->db->join('pengiriman_barang as pb', 'pb.id = pd.pengiriman_id', 'left');
		$this->db->join('perencanaan as pr', 'pr.id = pb.perencanaan_id', 'left');
		$this->db->join('adm_barang', 'adm_barang.id = pd.barang_id', 'left');

		if (!empty($id)) {
			$this->db->where('pb.perencanaan_id', $id);
		}

		$this->db->group_by('pd.id');

		return $this->db->get();
	}

	public function getTotalPengiriman($provinsi = '', $kabupaten = '', $kode_lokasi_skd = '', $jenis = '', $kelompok = '')
	{

		$this->db->select('sum(pengiriman_detail.jumlah_kirim) as jumlah_kirim')
			->join('pengiriman_barang', 'pengiriman_detail.pengiriman_id=pengiriman_barang.id', 'left')
			->join('perencanaan', 'pengiriman_barang.perencanaan_id=perencanaan.id', 'left')
			->join('lokasi_skd', 'perencanaan.kode_lokasi_skd=lokasi_skd.id', 'left')
			->join('ref_locations', 'lokasi_skd.lokasi_id=ref_locations.location_id', 'left')
			->join('adm_barang', 'adm_barang.id=pengiriman_detail.barang_id', 'left');;

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
		return $this->db->get('pengiriman_detail')->row()->jumlah_kirim;
	}
}
