<?php 

$view = 'menu_management/menu_management_v';

$jobtitle = $this->security->xss_clean($this->input->get("jobtitle"));

$data = array(
	'jumlah' =>1,
	'jobtitle' => $this->Administration_m->get_job_title()->result_array(),
	'current_jobtitle' => $jobtitle
	);

$menu = $this->db->order_by('menu_code','asc')->get("adm_menu")->result_array();
$selected_menu = $this->db->where("jobtitle",$jobtitle)
->get("adm_jobtitle_menu")->result_array();
$selected = array();

foreach ($selected_menu as $key => $value) {
	$selected[] = $value['menu_id'];
}

$mymenu = array();

foreach ($menu as $key => $value) {
	$value['selected'] = (in_array($value['menuid'], $selected));
	$mymenu[$value['parent_id']][] = $value;
}

$html = "<ul>";

foreach ($mymenu[0] as $k => $v) {
	$selected = ($v['selected']) ? "checked" : "";
	$html .= "<li id='".$v['menuid']."' data-parent='0'><input type='checkbox' name='menu[]' value='".$v['menuid']."' ".$selected."> ".$v['menu_name'];
	if(isset($mymenu[$v['menuid']])){
		$html .= "<ul>";
		foreach ($mymenu[$v['menuid']] as $k2 => $v2) {
			$selected = ($v2['selected']) ? "checked" : "";
			$html .= "<li id='".$v2['menuid']."' data-parent='".$v['menuid']."'><input type='checkbox' name='menu[]' value='".$v2['menuid']."' ".$selected."> ".$v2['menu_name'];
			if(isset($mymenu[$v2['menuid']])){
				$html .= "<ul>";
				foreach ($mymenu[$v2['menuid']] as $k3 => $v3) {
					$selected = ($v3['selected']) ? "checked" : "";
					$html .= "<li id='".$v3['menuid']."' data-parent='".$v2['menuid']."'><input type='checkbox' name='menu[]' value='".$v3['menuid']."' ".$selected."> ".$v3['menu_name']."</li>";
				}
				$html .= "</ul>";
			}
			$html .= "</li>";
		}
		$html .= "</ul>";
	}
	$html .= "</li>";
}

$html .= "</ul>";

$data['html'] = $html;
$data['selected'] = $selected;

$this->template($view,"Menu Management",$data);