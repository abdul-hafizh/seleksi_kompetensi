<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Desa_m extends CI_Model {

	public function __construct(){

		parent::__construct();

		$this->load->helper('security');

	}

	public function getDesa($id = ''){

		if(!empty($id)){

			$this->db->where('location_id', $id);

		}

		return $this->db->get('vw_desa');

	}
}
