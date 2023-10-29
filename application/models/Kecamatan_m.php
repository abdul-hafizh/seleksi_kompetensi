<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kecamatan_m extends CI_Model {

	public function __construct(){

		parent::__construct();

		$this->load->helper('security');

	}

	public function getKecamatan($id = ''){

		if(!empty($id)){

			$this->db->where('location_id', $id);

		}

		return $this->db->get('vw_kecamatan');

	}
}
