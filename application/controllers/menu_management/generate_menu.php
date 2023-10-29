<?php 
$posisi = $this->db->get("adm_pos")->result_array();
$menu = $this->db->get("adm_menu")->result_array();

foreach ($posisi as $key => $value) {
	foreach ($menu as $k => $v) {
		$pos_id = $value['pos_id'];
		$menu_id = $v['menuid'];
		$data = array("menu_id"=>$menu_id,"pos_id"=>$pos_id);
		$check = $this->db->where($data)->get("adm_pos_menu")->num_rows();
		if(empty($check)){
			$this->db->insert("adm_pos_menu",$data);
		}
	}
}