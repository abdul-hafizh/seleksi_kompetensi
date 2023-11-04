<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengiriman_barang_m extends CI_Model {

	public function __construct(){

		parent::__construct();

		$this->load->helper('security');

	}

	public function getPengiriman_barang($id = '', $lokasi = ''){

        $this->db->select('pengiriman_barang.*, lokasi_skd.kode_lokasi, lokasi_skd.nama_lokasi, ref_locations.*');

		if(!empty($id)){

			$this->db->where('pengiriman_barang.id', $id);

		}

		if(!empty($lokasi)){

			$this->db->where('lokasi_skd.lokasi_id', $lokasi);

		}

		$this->db->join('perencanaan as pr', 'pr.id = pengiriman_barang.perencanaan_id', 'left');
		$this->db->join('lokasi_skd', 'lokasi_skd.id = pr.kode_lokasi_skd', 'left');
		$this->db->join('ref_locations', 'ref_locations.location_id = lokasi_skd.lokasi_id', 'left');

		return $this->db->get('pengiriman_barang');

	}

	public function getDetailKirim($id = ''){
		$this->db->select('pd.*, adm_barang.kode_barang_id, adm_barang.nama_barang, adm_barang.merek, adm_barang.satuan, adm_barang.jenis_alat, adm_barang.kelompok, adm_barang.sn, pb.perencanaan_id');
		$this->db->from('pengiriman_detail pd');
		$this->db->join('pengiriman_barang as pb', 'pb.id = pd.pengiriman_id', 'left');
		$this->db->join('perencanaan as pr', 'pr.id = pb.perencanaan_id', 'left');
		$this->db->join('adm_barang', 'adm_barang.id = pd.barang_id', 'left');
	
		if (!empty($id)) {
			$this->db->where('pb.id', $id);
		}
	
		$this->db->group_by('pd.id');
	
		return $this->db->get();
	}

	public function getDetail($id = ''){
		$this->db->select('pd.*, adm_barang.kode_barang_id, adm_barang.nama_barang, adm_barang.merek, adm_barang.satuan, adm_barang.jenis_alat, adm_barang.kelompok, adm_barang.sn');
		$this->db->from('pengiriman_detail pd');
		$this->db->join('pengiriman_barang as pb', 'pb.id = pd.pengiriman_id', 'left');
		$this->db->join('perencanaan as pr', 'pr.id = pb.perencanaan_id', 'left');
		$this->db->join('adm_barang', 'adm_barang.id = pd.barang_id', 'left');
	
		if (!empty($id)) {
			$this->db->where('pb.perencanaan_id', $id);
		}
	
		$this->db->group_by('pd.id');
	
		return $this->db->get();
	}
	
}
