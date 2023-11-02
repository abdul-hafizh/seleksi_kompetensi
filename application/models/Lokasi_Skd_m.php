<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lokasi_skd_m extends CI_Model {

	public function __construct(){

		parent::__construct();

		$this->load->helper('security');

	}

	public function getLokasi($id = '', $code = ''){

		if(!empty($id)){

			$this->db->where('lokasi_skd.id', $id);

		}

		if(!empty($code)){

			$this->db->where('lokasi_skd.lokasi_id', $code);

		}

		$this->db->join('ref_locations', 'ref_locations.location_id = lokasi_skd.lokasi_id', 'left');

		return $this->db->get('lokasi_skd');

	}
}
