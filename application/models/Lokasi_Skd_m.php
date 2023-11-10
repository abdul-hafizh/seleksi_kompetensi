<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lokasi_skd_m extends CI_Model
{

	public function __construct()
	{

		parent::__construct();

		$this->load->helper('security');
	}

	public function getLokasi($id = '', $code = '')
	{

		if (!empty($id)) {

			$this->db->where('lokasi_skd.id', $id);
		}

		if (!empty($code)) {

			$this->db->where('lokasi_skd.lokasi_id', $code);
		}

		$this->db->join('ref_locations', 'ref_locations.location_id = lokasi_skd.lokasi_id', 'left');

		return $this->db->get('lokasi_skd');
	}

	public function getTotalTilok($provinsi = '', $kabupaten = '', $kode_lokasi_skd = '')
	{

		$user = $this->session->userdata();
		$user_pos = $user['adm_pos_id'];

		$this->db->select('count(lokasi_skd.id) as jumlah_tilok')
			->join('ref_locations', 'lokasi_skd.lokasi_id=ref_locations.location_id', 'left');

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
		return $this->db->get('lokasi_skd')->row()->jumlah_tilok;
	}
}
