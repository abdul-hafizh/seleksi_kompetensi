<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengiriman_barang_m extends CI_Model {

	public function __construct(){

		parent::__construct();

		$this->load->helper('security');

	}

	public function getPengiriman_barang($id = ''){

        $this->db->select('pengiriman_barang.*, pr.kode_perencanaan, pr.nama_barang, pr.jenis_barang, pr.jumlah, pr.satuan, lokasi_skd.kode_lokasi, lokasi_skd.nama_lokasi, ref_locations.*');

		if(!empty($id)){

			$this->db->where('pengiriman_barang.id', $id);

		}

		$this->db->join('perencanaan as pr', 'pr.id = pengiriman_barang.perencanaan_id', 'left');
		$this->db->join('lokasi_skd', 'lokasi_skd.id = pr.kode_lokasi_skd', 'left');
		$this->db->join('ref_locations', 'ref_locations.location_id = lokasi_skd.lokasi_id', 'left');

		return $this->db->get('pengiriman_barang');

	}
}
