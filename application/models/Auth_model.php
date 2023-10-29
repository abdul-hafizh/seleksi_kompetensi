<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}	

	public function getAllData($table)
    {
        return $this->db->get($table);
    }

    public function getAllDataLimited($table, $limit, $offset)
    {
        return $this->db->get($table, $limit, $offset);
    }

    public function getSelectedDataLimited($table, $data, $limit, $offset)
    {
        return $this->db->get_where($table, $data, $limit, $offset);
    }

    //select table
    public function getSelectedData($table, $data)
    {
        return $this->db->get_where($table, $data);
    }
  
	//update table
    function updateData($table, $data, $field_key)
    {
        return $this->db->update($table, $data, $field_key);
    }

    function deleteData($table, $data)
    {
        return $this->db->delete($table, $data);
    }

    function insertData($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id($table, $data);
    }

    //Query manual
    function manualQuery($q)
    {
        return $this->db->query($q);
    }
}

?>