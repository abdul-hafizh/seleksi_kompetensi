<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_m extends CI_Model {

	public function __construct(){

		parent::__construct();}

		public function get_settings_char($identifier)
		{
			$qry="SELECT val_char from adm_settings WHERE identifier='$identifier'";
			$sql = $this->db->query($qry)->result()[0]->val_char;
			return  $sql;
		}
		public function get_settings_num($identifier)
		{
			$qry="SELECT val_num from adm_settings WHERE identifier='$identifier'";
			$sql = $this->db->query($qry)->result()[0]->val_num;
			return  $sql;
		}
}
