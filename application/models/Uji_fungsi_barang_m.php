<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uji_fungsi_barang_m extends CI_Model {

	public function __construct(){

		parent::__construct();

		$this->load->helper('security');

	}

	public function getUji($id = '', $lokasi = ''){

        $this->db->select('pbg.*, ref_locations.*, pb.tgl_kirim, lokasi_skd.kode_lokasi, lokasi_skd.nama_lokasi, pb.kode_pengiriman, upb.kode_uji');

		if(!empty($id)){

			$this->db->where('upb.id', $id);

		}

		if(!empty($lokasi)){

			$this->db->where('lokasi_skd.lokasi_id', $lokasi);

		}

		$this->db->join('penerimaan_barang as pbg', 'pbg.id = upb.penerimaan_id', 'left');
		$this->db->join('pengiriman_barang as pb', 'pb.id = pbg.pengiriman_id', 'left');
		$this->db->join('perencanaan as pr', 'pr.id = pb.perencanaan_id', 'left');
		$this->db->join('lokasi_skd', 'lokasi_skd.id = pr.kode_lokasi_skd', 'left');
		$this->db->join('ref_locations', 'ref_locations.location_id = lokasi_skd.lokasi_id', 'left');

		return $this->db->get('uji_penerimaan_barang upb');

	}	
}
