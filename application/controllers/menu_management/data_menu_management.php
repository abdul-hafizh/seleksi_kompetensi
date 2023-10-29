<?php

$id = $this->input->get("id");

$jobtitle = $this->input->get("jobtitle");

$id = ($id == "#") ? 0 : $id;

$get = $this->db
->where(array("parent_id"=>$id))
->order_by('menu_code','asc')
->get("adm_menu")->result_array();

$n = 0;

$data = array();

foreach ($get as $key => $value) {
	$selected = $this->db
	->where(array("menu_id"=>$value['menuid'],"pos_id"=>$jobtitle))
	->get("adm_jobtitle_menu")->row_array();
	$child = $this->db
	->where(array("parent_id"=>$value['menuid']))
	->order_by('menu_code','asc')
	->get("adm_menu")->num_rows();
	//$name = $value['menuid']." : ".$value['menu_name']." (".$value['menu_code'].") ";
	$name = $value['menu_name'];
	$have_child = (!empty($child));
	$data[$n] = array(
		"id"=>(int)$value['menuid'],
		"text"=>$name,
		"children"=>$have_child,
		"state"=>array(
			"opened"=>true,
			"undetermined"=>true,
			"selected"=>(!empty($selected)) ? true : false
			)
		);
	$n++;

}

$this->output
->set_content_type('application/json')
->set_output(json_encode($data));