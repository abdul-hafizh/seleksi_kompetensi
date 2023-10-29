<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kabupaten_m extends CI_Model {

	public function __construct(){

		parent::__construct();

		$this->load->helper('security');

	}

	public function getKabupaten($id = ''){

		if(!empty($id)){

			$this->db->where('location_id', $id);

		}

		return $this->db->get('vw_kabupaten');

	}
}
