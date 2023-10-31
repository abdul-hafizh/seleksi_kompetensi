<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penerimaan_barang_m extends CI_Model {

	public function __construct(){

		parent::__construct();

		$this->load->helper('security');

	}

	public function getPenerimaan_barang($id = ''){

        $this->db->select('penerimaan_barang.*, ref_locations.*, pr.nama_barang, pr.jenis_barang, pb.jumlah_kirim, pb.tgl_kirim, lokasi_skd.kode_lokasi, lokasi_skd.nama_lokasi');

		if(!empty($id)){

			$this->db->where('id', $id);

		}

		$this->db->join('pengiriman_barang as pb', 'pb.id = penerimaan_barang.pengiriman_id', 'left');
		$this->db->join('perencanaan as pr', 'pr.id = pb.perencanaan_id', 'left');
		$this->db->join('lokasi_skd', 'lokasi_skd.id = pr.kode_lokasi_skd', 'left');
		$this->db->join('ref_locations', 'ref_locations.location_id = lokasi_skd.lokasi_id', 'left');

		return $this->db->get('penerimaan_barang');

	}
}
