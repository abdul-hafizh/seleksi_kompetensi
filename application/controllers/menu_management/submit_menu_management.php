<?php 

$post = $this->input->post();

$jobtitle = $post['jobtitle'];

$selected_menu = array_unique($post['menu']);

$this->db->where("jobtitle",$jobtitle)->delete("adm_jobtitle_menu");

if(!empty($selected_menu)){
	$filter = array();
	foreach ($selected_menu as $key => $value) {
		$menu = $this->db->where("menuid",$value)->get("adm_menu")->row_array();
		$parent_id = $menu['parent_id'];
		$menu2 = $this->db->where("menuid",$parent_id)->get("adm_menu")->row_array();
		$parent_id2 = $menu2['parent_id'];
		if(!in_array($value, $filter)){
			$filter[] = $value;
		}
		
		if(!in_array($parent_id, $filter)){
			$filter[] = (!empty($parent_id)) ? $parent_id : 0;
		}
	}
	foreach ($filter as $key => $value) {
		$where = array("jobtitle"=>$jobtitle,"menu_id"=>$value);
		$check = $this->db->where($where)->get("adm_jobtitle_menu")->num_rows();
		if(empty($check)){
			$this->db->insert("adm_jobtitle_menu",$where);
		}
	}
}