<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Penerimaan_barang_m extends CI_Model
{

	public function __construct()
	{

		parent::__construct();

		$this->load->helper('security');
	}

	public function getPenerimaan_barang($id = '', $lokasi = '')
	{

		$this->db->select('penerimaan_barang.*, ref_locations.*, pb.tgl_kirim, lokasi_skd.id as lokasi_skd_detail, lokasi_skd.kode_lokasi, lokasi_skd.nama_lokasi, lokasi_skd.alamat, pb.kode_pengiriman, pr.id as pr_id');

		if (!empty($id)) {

			$this->db->where('penerimaan_barang.id', $id);
		}

		if (!empty($lokasi)) {

			$this->db->where('lokasi_skd.id', $lokasi);
		}

		$this->db->join('pengiriman_barang as pb', 'pb.id = penerimaan_barang.pengiriman_id', 'left');
		$this->db->join('perencanaan as pr', 'pr.id = pb.perencanaan_id', 'left');
		$this->db->join('lokasi_skd', 'lokasi_skd.id = pr.kode_lokasi_skd', 'left');
		$this->db->join('ref_locations', 'ref_locations.location_id = lokasi_skd.lokasi_id', 'left');

		return $this->db->get('penerimaan_barang');
	}

	public function getDetail($id = '')
	{
		$this->db->select('pd.*, adm_barang.kode_barang_id, adm_barang.nama_barang, adm_barang.merek, adm_barang.satuan, adm_barang.jenis_alat, adm_barang.kelompok, adm_barang.sn, SUM(pnd.jumlah_kirim) AS jumlah_kirim');
		$this->db->from('penerimaan_detail pd');
		$this->db->join('penerimaan_barang pb', 'pb.id = pd.penerimaan_id', 'left');
		$this->db->join('pengiriman_barang pnb', 'pnb.id = pb.pengiriman_id', 'left');
		$this->db->join('pengiriman_detail pnd', 'pnd.pengiriman_id = pnb.id AND pnd.barang_id = pd.barang_id', 'left');
		$this->db->join('adm_barang', 'adm_barang.id = pd.barang_id');

		if (!empty($id)) {
			$this->db->where('pd.penerimaan_id', $id);
		}

		$this->db->group_by('pd.barang_id');

		return $this->db->get();
	}

	public function getTotalPenerimaan($provinsi = '', $kabupaten = '', $kode_lokasi_skd = '', $jenis = '', $kelompok = '')
	{
		$user = $this->session->userdata();
		$user_pos = $user['adm_pos_id'];

		$this->db->select('sum(penerimaan_detail.jumlah_terima) as jumlah_terima, sum(penerimaan_detail.jumlah_terpasang) as jumlah_terpasang')
			->join('penerimaan_barang', 'penerimaan_detail.penerimaan_id=penerimaan_barang.id', 'left')
			->join('pengiriman_barang', 'penerimaan_barang.pengiriman_id=pengiriman_barang.id', 'left')
			->join('perencanaan', 'pengiriman_barang.perencanaan_id=perencanaan.id', 'left')
			->join('lokasi_skd', 'perencanaan.kode_lokasi_skd=lokasi_skd.id', 'left')
			->join('ref_locations', 'lokasi_skd.lokasi_id=ref_locations.location_id', 'left')
			->join('adm_barang', 'adm_barang.id=penerimaan_detail.barang_id', 'left');

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
		return $this->db->get('penerimaan_detail')->row();
	}

	public function get_InstalasiBarangExist($penerimaan_id, $tgl_terima)
	{
		$this->db->select('pb.*, skd.nama_lokasi, skd.alamat, ref.province_name');
		$this->db->where('pb.id', $penerimaan_id);
		$this->db->where('pb.tgl_terima', $tgl_terima);
		$this->db->from('penerimaan_barang pb');
		$this->db->join('pengiriman_barang pbr', 'pbr.id = pb.pengiriman_id', 'left');
		$this->db->join('perencanaan p', 'p.id = pbr.perencanaan_id', 'left');
		$this->db->join('lokasi_skd skd', 'skd.id = p.kode_lokasi_skd', 'left');
		$this->db->join('ref_locations ref', 'ref.location_id = skd.lokasi_id', 'left');
		return $this->db->get();
	}

	public function get_InstalasiBarangDetail($id = '')
	{
		$this->db->select('pd.*, adm_barang.kode_barang_id, adm_barang.nama_barang, adm_barang.merek, adm_barang.satuan, adm_barang.jenis_alat, adm_barang.kelompok, adm_barang.sn');
		$this->db->where('pd.penerimaan_id', $id);
		$this->db->join('adm_barang', 'adm_barang.id = pd.barang_id', 'left');

		return $this->db->get('penerimaan_detail pd');
	}
}
