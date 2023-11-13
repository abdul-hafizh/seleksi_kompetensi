<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Update_kegiatan_m extends CI_Model
{

	public function __construct()
	{

		parent::__construct();

		$this->load->helper('security');
	}

	public function getUpdateKegiatan($id = '', $lokasi = '')
	{

		$this->db->select('uhk.*, jk.kode_kegiatan, lok.nama_lokasi, lok.alamat, ref_locations.*');

		$this->db->from('update_harian_kegiatan as uhk');
		$this->db->join('jadwal_kegiatan as jk', 'uhk.jadwal_kegiatan_id = jk.id', 'inner');
		$this->db->join('lokasi_skd as lok', 'lok.id = jk.lokasi_skd_id', 'inner');
		$this->db->join('ref_locations', 'ref_locations.location_id = lok.lokasi_id', 'left');

		if (!empty($id)) {
			$this->db->where('uhk.id', $id);
		}

		if (!empty($lokasi)) {
			$this->db->where('lok.id', $lokasi);
		}

		return $this->db->get();
	}

	public function getJadwalKegiatan($lokasi = '')
	{

		$this->db->select('jk.*, lok.nama_lokasi');
		$this->db->from('jadwal_kegiatan as jk');
		$this->db->join('lokasi_skd as lok', 'jk.lokasi_skd_id = lok.id', 'inner');

		if (!empty($lokasi)) {
			$this->db->where('lok.id', $lokasi);
		}

		return $this->db->get();
	}

	public function getDetail($id = '')
	{
		$this->db->select('pd.*, adm_barang.kode_barang_id, adm_barang.nama_barang, adm_barang.merek, adm_barang.satuan, adm_barang.jenis_alat, adm_barang.kelompok, adm_barang.sn, pnd.jumlah_kirim');
		$this->db->from('penerimaan_detail pd');
		$this->db->join('penerimaan_barang pb', 'pb.id = pd.penerimaan_id', 'left');
		$this->db->join('pengiriman_barang pnb', 'pnb.id = pb.pengiriman_id', 'left');
		$this->db->join('pengiriman_detail pnd', 'pnd.pengiriman_id = pnb.id', 'left');
		$this->db->join('adm_barang', 'adm_barang.id = pd.barang_id', 'left');

		if (!empty($id)) {
			$this->db->where('pd.penerimaan_id', $id);
		}

		$this->db->group_by('barang_id');

		return $this->db->get();
	}
}
