<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Log extends Telescoope_Controller
{

	var $data;

	public function __construct()
	{

		parent::__construct();

		$this->load->model(array('Administration_m', 'Provinsi_m', 'Perencanaan_m', 'Pengiriman_barang_m', 'Penerimaan_barang_m'));

		$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');

		$userdata = $this->Administration_m->getLogin();

		$this->data['userdata'] = (!empty($userdata)) ? $userdata : array();

		$sess = $this->session->all_userdata();
	}

	public function index()
	{
		$sess = $this->session->userdata(do_hash(SESSION_PREFIX));

		$data = array();

		if (!empty($sess)) {

			$data['controller_name'] = "log";
			$data['get_provinsi'] = $this->Provinsi_m->getProvinsi()->result_array();
			$data['get_jenis'] = $this->Perencanaan_m->get_jenis_barang();

			$this->template("dashboard_v", "Dashboard", $data);
		} else {

			$this->load->view("login_v", $data);
		}
	}

	public function in()
	{

		$method = "login";
		$post = $this->input->post();

		$this->form_validation->set_rules('username_login', 'Username', 'required');
		$this->form_validation->set_rules('password_login', 'Password', 'required');

		if ($this->form_validation->run() == FALSE) {

			$this->index();
		} else {

			$username = $post['username_login'];
			$password = $post['password_login'];

			$data = $this->Administration_m->checkLogin($username, $password)->row_array();

			$emp = $this->Administration_m->employee_view($data['employeeid'])->row_array();

			$this->db->select('id');
			$this->db->from('adm_employee');
			$this->db->where('id >=', 780);
			$query = $this->db->get();

			$list_ids = array();

			foreach ($query->result_array() as $row) {
				$list_ids[] = $row['id'];
			}

			if (!empty($data)) {
				if ($emp['status'] == 2) {
					$first_pos = $this->db->where("employee_id", $data['employeeid'])->order_by('is_main_job', 'desc')->get("vw_adm_pos")->row()->pos_id;
					$this->session->set_userdata(do_hash("ROLE"), $first_pos);
					$this->session->set_userdata(do_hash(SESSION_PREFIX), $data['id']);
				} else {
					$this->setMessage("Maaf, akun Anda belum aktif.", "Error");
				}
			} else {
				$this->setMessage("Wrong username and password.", "Error");
			}

			redirect(site_url('home'));
		}
	}

	public function remove_file()
	{
		$post = $this->input->post();
		$loc = str_replace("_", "/", $post['folder']);
		$root = str_replace("application", "", APPPATH);
		$dir = $root . "/uploads/" . $loc;
		$dir = str_replace(array("\\", "//"), "/", $dir);
		$file = $post['file'];
		if (unlink($dir . "/" . $file)) {
			echo 1;
		} else {
			echo 0;
		}
	}

	public function doupload()
	{

		$message = "";
		$loc = $this->session->userdata("dir_upload");
		$module = $this->session->userdata("module");
		$status = "error";
		$url = "";

		if (!empty($loc)) {
			$exp = explode("_", $loc);
			$module = $exp[0];
			$loc = str_replace("_", "/", $loc);
			$root = str_replace("application", "", APPPATH);
			$dir = $root . "/uploads/" . $loc;
			$dir = str_replace(array("\\", "//"), "/", $dir);

			if (upload_activity)
				$config['allowed_types'] = 'jpg|gif|png|doc|docx|xls|xlsx|ppt|pptx|pdf|jpeg|zip|rar|tgz|7zip|tar';
			$config['overwrite'] = false;

			if (!file_exists($dir)) {
				mkdir($dir, 0777, true);
			}

			$config['upload_path'] = $dir;
			$config['encrypt_name'] = true;
			$config['max_size'] = 5120; //y max file upload

			$this->load->library('upload', $config);

			if (!empty($_FILES['file']['tmp_name'])) {

				if ($this->upload->do_upload('file')) {
					$upl = $this->upload->data();
					$message = $upl['file_name'];
					$url = site_url('log/download_attachment/' . $loc . '/' . $message);
					$status = "success";
				} else {
					$message = $this->upload->display_errors('', '');
				}
			} else {
				$message = "No file";
			}
		} else {
			$message = "No directory";
		}

		$this->session->unset_userdata("message");

		echo json_encode(array("message" => $message, "status" => $status, "url" => $url));
	}

	private function upload_files($path, $title, $files)
	{

		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}

		$config = array(
			'upload_path'   => $path,
			'allowed_types' => 'jpg|gif|png|doc|docx|xls|xlsx|ppt|pptx|pdf|jpeg|zip|rar|tgz|7zip|tar',
			'overwrite'     => 1,
			'max_size'		=> 5120, //y max file upload
		);

		$this->load->library('upload', $config);

		$files = array();

		$return = array();

		foreach ($files['name'] as $key => $file) {

			$_FILES['files[]']['name'] = $files['name'][$key];
			$_FILES['files[]']['type'] = $files['type'][$key];
			$_FILES['files[]']['tmp_name'] = $files['tmp_name'][$key];
			$_FILES['files[]']['error'] = $files['error'][$key];
			$_FILES['files[]']['size'] = $files['size'][$key];

			$fileName = $file;

			if (!empty($title)) {
				$fileName = $title . '_' . $file;
			}

			$files[] = $fileName;

			$config['file_name'] = $fileName;

			$this->upload->initialize($config);

			if ($this->upload->do_upload('files[]')) {
				$return[] = array("data" => $this->upload->data(), "error" => false);
			} else {
				$return[] = array("data" => false, "error" => $this->upload->display_errors());
			}
		}

		return $return;
	}

	public function logout()
	{

		$this->session->unset_userdata(do_hash(SESSION_PREFIX));
		$this->session->sess_destroy();
		redirect(site_url('log'));
	}

	public function submit_change_password()
	{

		$post = $this->input->post();

		if (!empty($post)) {

			$u = $this->data['userdata'];
			$oldpass = strtoupper(do_hash($post['password_lama_inp'], 'sha1'));
			$check2 = $this->db->where(array("password" => $oldpass, "id" => $u['id']))->get("adm_user")->row_array();
			if ($post['password_baru_inp'] != $post['password_baru_ulang_inp']) {
				$this->setMessage("Password baru dan ulangi password tidak sama", "Error");
			} else if (empty($check2)) {
				$this->setMessage("Password lama salah", "Error");
			} else {
				$pass = strtoupper(do_hash($post['password_baru_inp'], 'sha1'));
				$input = array("password" => $pass);
				$this->db->where("id", $u['id'])->update("adm_user", $input);
				$this->setMessage("Sukses mengubah password", "Success");
				redirect(site_url('home'));
			}
		}

		$data['controller_name'] = "log";
		$this->template("change_password_v", "Ubah Password", $data);
	}

	public function change_password()
	{
		$data['controller_name'] = "log";
		$this->template("change_password_v", "Ubah Password", $data);
	}

	public function forgot()
	{

		$email = $this->input->post('email_login');

		if (!empty($email)) {

			$newpass = generateRandomString();
			$encrypt = do_hash($newpass);
			$employee = $this->db->where("email", $email)->get("adm_employee")->row_array();

			$update = $this->db->where("employeeid", $employee['id'])->update("adm_user", array("password" => strtoupper($encrypt)));

			if ($this->db->affected_rows() > 0) {

				$data = $this->db->where("email", $email)->get("adm_employee")->row_array();

				$user = $data['fullname'];

				$this->load->library('email');

				$config['mailtype'] = 'html';
				$config['wordwrap'] = TRUE;

				$this->email->initialize($config);

				$company = $this->globalparam_m->getData();

				$email_company = $company['site_email'];
				$name_company = $company['site_name'];

				$this->email->from($email_company, $name_company);
				$this->email->to($email);

				$this->email->subject($name_company . ' - Your new password admin panel');
				$this->email->message("<p>Dear $user,</p>
					<br/>
					<p>Your new password is $newpass. Please login <a href='" . site_url('log/in') . "' target='_blank'>here</a> with new password.</p>
					<br/>
					<p>Thanks,</p>
					<p>$name_company</p>");

				$this->email->send();

				$this->session->set_userdata('message', "Success to send email reset password");
			} else {
				$this->session->set_userdata('message', "Invalid email address");
			}
		} else {
			$this->session->set_userdata('message', "Email address cannot be empty");
		}

		redirect(site_url("log/in"));
	}

	public function get_data_dashboard()
	{
		$provinsi = $this->input->post('provinsi', true);
		$kabupaten = $this->input->post('kabupaten', true);
		$kode_lokasi_skd = $this->input->post('kode_lokasi_skd', true);
		$jenis = $this->input->post('jenis', true);
		$kelompok = $this->input->post('kelompok', true);
		$total_perencanaan = $this->Perencanaan_m->getTotalPerencanaan($provinsi, $kabupaten, $kode_lokasi_skd, $jenis, $kelompok);
		$total_pengiriman = $this->Pengiriman_barang_m->getTotalPengiriman($provinsi, $kabupaten, $kode_lokasi_skd, $jenis, $kelompok);
		$total_diterima = $this->Penerimaan_barang_m->getTotalPenerimaan($provinsi, $kabupaten, $kode_lokasi_skd, $jenis, $kelompok);
		$data['total_perencanaan'] = (!empty($total_perencanaan)) ? $total_perencanaan : 0;
		$data['total_pengiriman'] = (!empty($total_pengiriman)) ? $total_pengiriman : 0;
		$data['total_penerimaan'] = (!empty($total_diterima->jumlah_terima)) ? $total_diterima->jumlah_terima : 0;
		$data['total_terinstall'] = (!empty($total_diterima->jumlah_terpasang)) ? $total_diterima->jumlah_terpasang : 0;
		echo json_encode($data);
	}
}
