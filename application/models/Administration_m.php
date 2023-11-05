<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Administration_m extends CI_Model
{

	public function __construct()
	{

		parent::__construct();

		$this->load->helper('security');
	}

	public function getEmployeeJoin($employee = "")
	{
		if (!empty($employee)) {
			$this->db->where("employee_id", $employee);
			$this->db->where(array("employee_id" => $employee, "job_title" => "PELAKSANA PENGADAAN"));
		}

		return  $this->db->get("vw_adm_pos A");
	}

	public function getUserData($id = "")
	{

		if (!empty($id)) {

			if (is_numeric($id)) {

				$this->db->where('id', $id);
			} else {

				$this->db->where('username_user', $id);
			}
		}

		return $this->db->get('adm_user');
	}

	public function getUserByJob($job = "")
	{

		$this->db->select("A.employee_id,A.pos_id,A.pos_name,C.fullname");

		if (!empty($job)) {

			$this->db->where('job_title', $job);
		}

		$this->db->join("adm_pos B", "A.pos_id = B.pos_id", "INNER");

		$this->db->join("adm_employee C", "C.id = A.employee_id", "INNER");

		$this->db->order_by("fullname", "asc");

		return $this->db->get('adm_employee_pos A');
	}

	public function checkLogin($username, $password)
	{

		$where = array(
			'user_name' => $username,
			'password' => strtoupper(do_hash($password, 'sha1'))
		);

		$this->db->where($where);

		return $this->db->get('adm_user');
	}

	public function getPos($id = "")
	{

		if (!empty($id)) {
			$this->db->where("pos_id", $id);
		}

		return $this->db->get("adm_pos");
	}

	public function getNewPos($pos = "")
	{

		$pos_name = array("VIEWER", "ENUM");

		if (empty($pos)) {
			$this->db->where("pos_name !=", "ADMINISTRATOR");
		}

		if ($pos == 'VIEWER') {
			$this->db->where("pos_name", "ENUM");
		}

		if ($pos == 'KORWIL') {
			$this->db->where_in("pos_name", $pos_name);
		}

		return $this->db->get("adm_pos");
	}

	public function getEmployeePos($employee = "")
	{
		if (!empty($employee)) {
			$this->db->where("employee_id", $employee);
		}
		return  $this->db->get("vw_adm_pos A");
	}

	public function getDeptUser($employee_id = "")
	{
		$position = $this->getPosition($employee_id);
		$data = array();
		foreach ($position as $key => $value) {
			$data[] = $value['dept_id'];
		}
		return $data;
	}

	public function getPosition($job_title = "", $employee_id = "")
	{

		$employee = $this->getLogin();

		if (empty($employee_id)) {
			$employee_id = $employee['employee_id'];
		}
		if (!empty($job_title)) {
			if (is_array($job_title)) {
				$this->db->where_in("job_title", $job_title);
				$data = $this->getEmployeePos($employee_id)->result_array();
			} else {
				$this->db->where("job_title", $job_title);
				$data = $this->getEmployeePos($employee_id)->row_array();
			}
		} else {
			$data = $this->getEmployeePos($employee_id)->result_array();
		}

		return $data;
	}

	public function getLogin()
	{

		$id = $this->session->userdata(do_hash(SESSION_PREFIX));

		$login = $this->getUser($id)->row_array();

		$role = $this->session->userdata(do_hash("ROLE"));

		if (!empty($role)) {
			$this->db->where("pos_id", $role);
			$getrole = $this->getEmployeePos($login['employee_id'])->row_array();
			if (!empty($getrole)) {
				$login = array_merge($login, $getrole);
			}
		}

		return $login;
	}

	public function getUser($id = "")
	{

		if (!empty($id)) {
			$this->db->where("A.id", $id);
		}

		return $this->db->get('vw_user_access A');
	}

	public function getMenuUser($employee)
	{

		$role = $this->session->userdata(do_hash("ROLE"));

		if (empty($role)) {
			$p = $this->getLogin();
		} else {
			$p = $this->getPos($role)->row_array();
		}

		$role = $p['job_title'];

		$this->db->join("adm_menu", "menu_id=menuid", "inner");
		$this->db->where("jobtitle", $role);

		$parent_menu = $this->db->order_by("menu_code", "asc")->get("adm_jobtitle_menu")->result_array();
		$allparent = array();
		$menu = array();
		foreach ($parent_menu as $key => $value) {
			if (!in_array($value['parent_id'], $allparent)) {
				$allparent[] = $value['parent_id'];
			}
			$menu[$value['parent_id']][$value['menuid']] = $value;
		}

		if (!empty($allparent)) {
			$this->db->where_in("menuid", $allparent);
		}

		$parent_menu = $this->db->join("adm_menu", "menu_id=menuid", "inner")
			->where("parent_id", 0)
			->order_by("menu_code", "asc")
			->get("adm_jobtitle_menu")->result_array();

		foreach ($parent_menu as $key => $value) {
			$menu[$value['parent_id']][$value['menuid']] = $value;
		}

		return $menu;
	}

	public function getMenu($jobtitle = "")
	{

		if (!empty($jobtitle)) {
			$this->db->join("adm_jobtitle_menu", "menu_id=menuid", "inner");
			$this->db->where("jobtitle", $jobtitle);
		}

		$parent_menu = $this->db->order_by("menu_code", "asc")->get("adm_menu")->result_array();
		$menu = array();
		foreach ($parent_menu as $key => $value) {

			$menu[$value['parent_id']][$value['menuid']] = $value;
		}

		return $menu;
	}

	public function get_job_pos($id = "")
	{

		if (!empty($id)) {

			$this->db->where("pos_id", $id);
		}

		return $this->db->get("adm_pos");
	}

	public function get_employee($id = "")
	{

		if (!empty($id)) {

			$this->db->where("id", $id);
		}

		return $this->db->get("adm_employee");
	}

	public function getEmpKoor($id = "")
	{

		if (!empty($id)) {

			$this->db->where("id", $id);
		}

		$this->db->where("employee_type_id", 1);

		$this->db->where("is_koordinator", 1);

		return $this->db->get("adm_employee");
	}

	public function get_employee_type_name($id)
	{

		$data = $this->db->where("employee_type_id = '$id'")->get("adm_employee_type")->row_array();

		return (isset($data['employee_type_name'])) && (!empty($data['employee_type_name'])) ? $data['employee_type_name'] : "";
	}

	public function get_employee_type($id = "")
	{

		if (!empty($id)) {

			$this->db->where("employee_type_id", $id);
		}

		return $this->db->get("adm_employee_type");
	}

	public function get_pos_id($id = "")
	{

		if (!empty($id)) {

			$this->db->where("pos_id", $id);
		}

		return $this->db->get("adm_pos");
	}

	public function get_job_title($id = "")
	{

		if (!empty($id)) {

			$this->db->where("job_title", $id);
		}

		return $this->db->get("adm_jobtitle");
	}

	public function get_user_data($id = "")
	{

		if (!empty($id)) {

			$this->db->where("id", $id);
		}

		return $this->db->get("adm_user");
	}

	public function user_access_view($id = "")
	{

		if (!empty($id)) {

			$this->db->where("id", $id);
		}

		return $this->db->get("vw_user_access");
	}

	public function employee_view($id = "")
	{

		if (!empty($id)) {

			$this->db->where("id", $id);
		}

		return $this->db->get("vw_employee");
	}

	public function getPosbyJob($job)
	{

		if (!empty($job)) {
			$this->db->order_by("pos_id", "desc");
			return $this->db->where("job_title", $job)->get("adm_pos");
		}
	}

	public function getListUser($provinsi = '', $kabupaten = '', $kode_lokasi_skd = '')
	{
		$user = $this->session->userdata();
		$user_pos = $user['adm_pos_id'];

		$this->db->select('ref_locations.province_name,ref_locations.regency_name, lokasi_skd.nama_lokasi,adm_employee.fullname, adm_employee.alamat, adm_employee.phone,adm_pos.pos_name')
			->join('lokasi_skd', 'lokasi_skd.id=adm_employee.lokasi_skd_id', 'left')
			->join('ref_locations', 'ref_locations.location_id=adm_employee.lokasi_user', 'left')
			->join('adm_pos', 'adm_pos.pos_id=adm_employee.adm_pos_id', 'left');

		if ($user_pos > 2) {
			$this->db->where('lokasi_skd.id', $user['lokasi_skd_id']);
		}

		$this->db->where_not_in('adm_employee.adm_pos_id', [1, 2]);

		if (!empty($provinsi)) {

			$this->db->where('ref_locations.province_id', $provinsi);
		}

		if (!empty($kabupaten)) {

			$this->db->where('ref_locations.regency_id', $kabupaten);
		}
		if (!empty($kode_lokasi_skd)) {

			$this->db->where('lokasi_skd.id', $kode_lokasi_skd);
		}

		return $this->db->get('adm_employee');
	}
}
