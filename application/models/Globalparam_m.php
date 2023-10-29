<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class globalparam_m extends CI_Model {

    public function __construct(){

        parent::__construct();

    }
    
    public function getData(){
        $result = $this->db->get("global_param")->result_array();
        $filter = array();
        foreach($result as $key => $val){
            $filter[$val['name_gp']] = $val['value_gp'];
        }
        return $filter;
    }

    public function getAllData(){

        return $this->db->order_by("indeks_gp","asc")->get("global_param");
    }

    public function getDataByType($type = "text"){
        return $this->db->where("type_gp",$type)->get("global_param");
    }

    public function getDataById($id){
        $res = array();
        if(!empty($id)){
            $res = $this->db->where('id_gp',$id)->get("global_param");
        }
        return $res;
    }

    public function deleteData($id){
        if(!empty($id)){
            $res = $this->db->where('id_gp',$id)->delete("global_param");
        } else {
            return false;
        }
    }

    public function deleteImg($id = ""){
        if(!empty($id)){
            $img = $this->db->where('id_gp',$id)->get("global_param")->row()->value_gp;
            $this->db->where('id_gp',$id)->update("global_param",array("value_gp"=>""));
            return unlink("../uploads/".$img);
        }
    }

    public function setStatus($id = ""){
        if(!empty($id)){

            $stat = $this->db->where('id_gp',$id)->get("global_param")->row()->status_gp;

            if($stat == 1){
                $upd = 0;
            } else {
                $upd = 1;
            }

            return $this->db->where('id_gp',$id)->update("global_param",array("status_gp"=>$upd));

            
        }
    }

    public function log($log = "",$user = ""){
        if(empty($user)){
            $user = $this->session->userdata('uid');
        }
        return $this->db->insert("log",array("username_log"=>$user,"kegiatan_log"=>$log,"waktu_log"=>date("Y-m-d H:i:s")));
    }

    public function getStatusTrans($detail = ""){
        $data = $this->db->order_by("index_st","asc")->get("status_transaction_ec")->result_array();
        $mydata = array();
        foreach ($data as $key => $value) {
            if($detail == "email"){
                $mydata[$value['label_st']] = $value['email_st'];
            } else if($detail == "end"){

                if($value['endstate_st'] == 1){
                    $mydata[] = $value['label_st'];
                }

            } else if($detail == "start"){

                if($value['startstate_st'] == 1){
                    $mydata[] = $value['label_st'];
                }

            } else {
                $mydata[] = $value['label_st'];
            }
        }
        return $mydata;
    }
    
}
