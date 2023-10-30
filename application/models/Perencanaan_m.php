<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perencanaan_m extends CI_Model {

	public function __construct(){

		parent::__construct();

		$this->load->helper('security');

	}

	public function getPerencanaan($id = ''){

		if(!empty($id)){

			$this->db->where('id', $id);

		}

		return $this->db->get('perencanaan');

	}
}
