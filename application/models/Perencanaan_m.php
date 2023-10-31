<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perencanaan_m extends CI_Model {

	public function __construct(){

		parent::__construct();

		$this->load->helper('security');

	}

	public function getPerencanaan($id = ''){

		$this->db->select('perencanaan.*, ref_locations.*, lokasi_skd.kode_lokasi, lokasi_skd.nama_lokasi');

		if(!empty($id)){

			$this->db->where('perencanaan.id', $id);

		}

		$this->db->join('lokasi_skd', 'lokasi_skd.id = perencanaan.kode_lokasi_skd', 'left');
		$this->db->join('ref_locations', 'ref_locations.location_id = lokasi_skd.lokasi_id', 'left');

		return $this->db->get('perencanaan');

	}
}
