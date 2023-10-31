<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penerimaan_barang_m extends CI_Model {

	public function __construct(){

		parent::__construct();

		$this->load->helper('security');

	}

	public function getPenerimaan_barang($id = ''){

        $this->db->select('penerimaan_barang.*, ref_locations.*, pr.kode_perencanaan, pr.nama_barang, pr.jenis_barang, pr.jumlah, pr.satuan, loc.lokasi_id, loc.kode_lokasi, loc.nama_lokasi');

		if(!empty($id)){

			$this->db->where('id', $id);

		}

		$this->db->join('perencanaan as pr', 'pr.id = penerimaan_barang.perencanaan_id', 'left');
		$this->db->join('lokasi_skd as loc', 'loc.id = penerimaan_barang.lokasi_test_id', 'left');
		$this->db->join('ref_locations', 'ref_locations.location_id = loc.lokasi_id', 'left');

		return $this->db->get('penerimaan_barang');

	}
}
