<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jadwal_kegiatan_m extends CI_Model {

	public function __construct(){

		parent::__construct();

		$this->load->helper('security');

	}

	public function getJadwal($id = ''){

		$this->db->select('jadwal_kegiatan.*, ref_locations.*, lokasi_skd.lokasi_id, lokasi_skd.nama_lokasi');

		if(!empty($id)){

			$this->db->where('jadwal_kegiatan.id', $id);

		}

		$this->db->join('lokasi_skd', 'lokasi_skd.id = jadwal_kegiatan.lokasi_skd_id', 'left');
		$this->db->join('ref_locations', 'ref_locations.location_id = lokasi_skd.lokasi_id', 'left');

		return $this->db->get('jadwal_kegiatan');

	}
}
