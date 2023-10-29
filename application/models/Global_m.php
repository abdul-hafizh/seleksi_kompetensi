<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Global_m extends CI_Model {

	public function __construct(){
		parent::__construct();
		$this->load->model("Administration_m");
	}

	public function insert_table($table_name,$data)
	{
		$this->db->insert($table_name,$data);
	}

	public function get_data($table,$where=null,$out=1)
	{
		$this->db->where($where);
		if($out==1){
			$hsl=$this->db->get($table)->row_array();
		}else{
			$hsl=$this->db->get($table)->result_array();
		}

		return $hsl;
	}
}
