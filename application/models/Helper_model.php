<?php
	class Helper_model extends CI_Model {
	/*
	*	info:	This Method is meant to fetch global configuration data from database
	*	to use this method
	*	Create table 'config'
	*	id increment and primary key
	*	name string
	*	value text
	*	Where name will be settings name like 'sitename'
	*	Value will store sitename value like 'Example Site'
	*	Parameter name : string
	*/
	public function get_settings($name){
	/*
	Fetching data from config table of database
	*/

		$query			=	$this->db->get_where('config', array('name' => $name),1);
		$result_row		=	$query->row();
		if($result_row){
			return $details->value;
		}

	}

	/*
	*	Uploading file was not this simple enough.
	*	All you need is name of input type file which is $_FILES's name
	*	And Upload path for the file where you want to upload file.
	*	Make Sure Path where file is to be uploaded, exist and is writable
	*	All Failure message will be logged to log directory
	*/
	public function upload_file($path,$name){

		if(!isset($path)){
			/*
			*	Need to set path like /directory/image/
			*/
			log_message('error','Image upload path not defined');
			return FALSE;
		}

		if(!isset($name)){
			/*
			*	name of input type file is missing
			*/

			log_message('error','File name not defined');
			return false;
		}

		if(!is_dir($path)){
			return false;
			log_message('error','Directory does not exit, please create directory and make it writable');
		}

		$target_dir 	=	$path;
		$time			=	base64_encode(time().random_string('alnum',5));

		/*
		*	By Default, This function generate random filename based on current timestamp
		*	and a random string and MD5 Hashing them along with original filename
		*	Orignal Filename is trimmed just to prevent last 6 character including extention of file.
		*	Random string is trimmed to keep 5-20 character and filename smaller
		*/
		$target_file	=	$target_dir.substr(md5($time.rand()),5,20)."_".str_replace(" ","-",substr(basename($_FILES[$name]["name"]),-6));

		if($_FILES[$name]) {

			if (move_uploaded_file($_FILES[$name]["tmp_name"], $target_file)) {
				return $target_file;
			}else {
				log_message('error','Directory does not exist or not writable, please create directory or defined valid path in first argument');
				return FALSE;
			}

		} else {
			log_message('error','File not found in request body, please check if form has attribute enctype=multipart/form-data');
			return FALSE;
		}

	}

	/*
	*	This function can be used anywhere to display alert message in well formatted manner
	*	Any warning or success or info message will be displayed
	*	Require bootstrap for well formatting
	*	Parameters : msg : Message to be displayed
	*				type : Type of messages 'info','danger','success','warning'
	*				'icon': This will be fontawesome icon class which icon should be displayed
	*				example : for warning, instead of 'fa fa-warning', 'fa-warning' should be passed here
	*
	*/
	public function display_alert($msg,$type='info',$icon='info-circle'){
		if(!$msg){
			return FALSE;
			exit();
		}
		?>
		<div class="alert alert-<?php echo $type;?>" align="center">
			<i class="fa fa-<?php echo $icon;?>"></i> <?php echo $msg;?>
		</div>
		<?php
	}

	/*
	*	This method can be used to redirect current page to next page and displayed message
	*	Example, if login fails, Next page can be shown incorrect username password.
	*	Example and parameter explaination
	*	msg :	message to be displayed, e.g. Record deleted successfully
	*	type:	Type/context of message example, for success or green color, 'success' should be passed
	*		:	Available options are: success|warning|danger|info
	*	url :	url should be the redirecting page url, without base_url, example 'home/login'
	*
	*/
	public function redirect_msg($msg,$type='info',$url){
		if(!$msg OR !$url){
			return false;
		}

		$this->session->set_flashdata(array('msg'=>$msg,'type'=>$type));
		redirect(base_url($url));
		exit();
	}

	/*
	*	Works with Bootstap classes
	*	This function needs to call on views where certain alert messages needs to be displayed.
	*	This function works once the 'redirect_msg' is been called and data will be passed to session
	*	And will be catch by this function
	*	This function will be evoked when there is variable named 'msg'
	*
	*/
	public function alert_message(){
		if($this->session->userdata('msg')){
			?>
			<div id="fading_div" class="alert alert-<?php echo $this->session->userdata('type')?>">
				<i class="fa fa-info-circle fa-lg"></i> <?php echo $this->session->userdata('msg');?>
			</div>
			<?php
		}
	}

	/*
	*	Simple function for mailing using codeigniter
	*	Required parameter is an array which should have 4 values in it
	*	string name: by which name email should be sent
	*	string : email, by which email should be sent, (from)
	*	string : subject of the email
	*	message: it can be simple text, html or complete view of html
	*	it will return true when email function runs successfully, else false
	*	more details will be visible in error log
	*
	*/
	public function send_formatted_mail($data){
		$this->load->library('email');
		$this->email->clear();

		//	Checking any blank entry
		if(empty($data['name'])
			OR empty($data['subject'])
			OR empty($data['to'])
			OR empty($data['message']) ){
			log_message('error','Empty paraters for email');
			return FALSE;
		}


		/*
			Initializing codeigniter email configuration
		*/
		$config['useragent']		=	'Codeigniter';
		$config['mailpath']			=	'/usr/sbin/sendmail'; // or "/usr/sbin/sendmail"
		$config['protocol']			=	'smtp';
		$config['smtp_host']		=	'localhost';
		$config['smtp_port']		=	'25';


		$config['mailtype']			=	'html';
		$config['charset']			=	'utf-8';

		$config['wordwrap']			=	TRUE;
		$config['newline']			=	"\r\n";
		$config['crlf'] 			= 	"\r\n";


		ini_set('sendmail_from', $data['from']);
		$this->email->reply_to($data['from'], $data['name']);
		$this->email->initialize($config);
		$this->email->to($data['to']);
		$this->email->from($data['from'], $data['name']);
		$this->email->subject($data['subject']);
		$this->email->message($data['message']);
		$mail = $this->email->send();

		if($mail){
			log_message('error','Message sent to :'.$data['to']);
			return TRUE;
		}else{
			log_message('error','message not sent to :'.$data['to']);
			return FALSE;
			exit();
		}
	}

	/*
	*	Image resizing using this function
	*	@Param strong upload path : image path
	*	with and height optional any one parameter to be passed
	*
	*/
	public function image_resize($upload_path,$width=NULL,$height=NULL){
		// Just need path to grab image and resize followed by overwriting
		$this->load->library('image_lib');
		ini_set('memory_limit', '-1');	// this will prevent memory overload by PHP,

		$config['source_image']		=	$upload_path;
		$config['new_image']			=	$upload_path;

		if($width==NULL && $height==NULL){
			return false;
		}

		$this->image_lib->initialize($config);
		$confirm=$this->image_lib->resize();
		if($confirm){
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function update_sitedata($var,$val){
		log_message('error', 'Variable loaded'.$var);
		log_message('error', 'value loaded'.$val);
		$data = array(
				'value' => $val
		);

		$query=$this->db->where('var', $var);
		$query=$this->db->update('config', $data);

		if($query){
			log_message('error', 'query success');
			return TRUE;
		} else {
			log_message('error', 'query failed');
			return FALSE;
		}
	}

	public function del($table,$col,$val){
		if(($table==NULL) OR ($col==NULL) OR ($val==NULL)){
			log_message('error','Missing table, col, or value');
			return FALSE;
		}

		$query		=	$this->db->where($col, $val);
		$query		=	$this->db->delete($table);
		if($query){
			return TRUE;
		}else{
			log_message('error','Unable to delete from '.$table." given attribute ".$col." with value ".$val);
			return FALSE;

		}
	}

	public function update($table,$data,$col,$val){
		if(($data==NULL) OR ($col==NULL) OR ($val==NULL) OR ($table==NULL)){
			log_message('error','Missing table, col, or value');
			return FALSE;
			exit();
		}

		$query	=	$this->db->where($col, $val);
		$query	=	$this->db->update($table, $data);
		if($query){
				return TRUE;
			}else{
				return FALSE;
			}
	}

	public function get_by_id($table,$col,$value,$limit=null,$order=NULL){

		if(!empty($order)){

			foreach ($order as $key => $value){
			$query=$this->db->order_by($key, $value);
			}
		}
		$query =	$this->db->get_where($table, array($col => $value),$limit);

		if($query->num_rows()>0){
				return $query->result();
			}else{
				return FALSE;
			}
	}

	public function get_multi_where($table,$where,$limit=null,$count=FALSE,$order=NULL,$group=NULL){

		if(!empty($group)){
			$this->db->group_by($group);
		}

		if(!empty($order)){

			foreach ($order as $key => $value){
				$query=$this->db->order_by($key, $value);
			}

		} else {

		}

		$query =	$this->db->get_where($table, $where,$limit);

		if($query->num_rows()>0){
			if($count){
				return $query->num_rows();
			} else {
				return $query->result();
			}

		} else {

			return FALSE;
		}
	}

	public function get_row_array($table,$where,$order=NULL,$limit=1){

		$query =	$this->db->get_where($table, $where,$limit);
		if($query->num_rows()>0){
			$row = $query->row();
			if(isset($row)){
			return $row;
			}
		} else {
			return FALSE;
		}

	}

	public function get_row($table,$col,$value,$col_get=NULL){
		if(!$table OR !$col OR !$value){
			return FALSE;
		}

		$query =	$this->db->get_where($table, array($col => $value),1);

		if($query->num_rows()>0){
			$data=$query->row_array();
			if($col_get){
				return $data[$col_get];
			} else {
				return $data;
			}

		} else {
			return FALSE;
		}
	}

	public function get_table($table,$order=NULL,$group=NULL,$limit=NULL){
		if(!empty($order)){

			foreach ($order as $key => $value){
				$query=$this->db->order_by($key, $value);
			}

		}

		if(!empty($group)){
			$this->db->group_by($group);
		}

		if(!empty($limit)){
			$this->db->limit($limit);
		}

		$query = $this->db->get($table);
		if($query->num_rows()>0){
			return $query->result();
		} else {
			return FALSE;
		}
	}

	public function count_data($table,$condition=NULL,$value=NULL){
		if($condition!=NULL && $value!=NULL){
			$query = $this->db->get_where($table, array($condition => $value));
			if($query){
					echo $query->num_rows();
			}else{
					return FALSE;
			}
		} else {
			$query = $this->db->get($table);
			if($query){
				echo $query->num_rows();
			}
		}
	}

	public function count_multiple($table,$condition=NULL){
		if($condition!=NULL){
			$query = $this->db->get_where($table,$condition);
			if($query){
					return $query->num_rows();
			} else {
					return FALSE;
			}
		} else {
			$query = $this->db->get($table);
			if($query){
				return $query->num_rows();
			}
		}

	}

	public function search($table,$query,$attribute,$condition=NULL){
		$this->db->from($table);
		foreach($attribute as $key=>$values){
			$this->db->or_like($values,$query);
		}
		if($condition!=NULL){

		}
		$query	=	$this->db->get();
		if($query){
			return $query->result();
		}
	}
}
