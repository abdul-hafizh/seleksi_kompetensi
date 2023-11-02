<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penerimaan_barang_m extends CI_Model {

	public function __construct(){

		parent::__construct();

		$this->load->helper('security');

	}

	public function getPenerimaan_barang($id = ''){

        $this->db->select('penerimaan_barang.*, ref_locations.*, pb.tgl_kirim, lokasi_skd.kode_lokasi, lokasi_skd.nama_lokasi');

		if(!empty($id)){

			$this->db->where('penerimaan_barang.id', $id);

		}

		$this->db->join('pengiriman_barang as pb', 'pb.id = penerimaan_barang.pengiriman_id', 'left');
		$this->db->join('perencanaan as pr', 'pr.id = pb.perencanaan_id', 'left');
		$this->db->join('lokasi_skd', 'lokasi_skd.id = pr.kode_lokasi_skd', 'left');
		$this->db->join('ref_locations', 'ref_locations.location_id = lokasi_skd.lokasi_id', 'left');

		return $this->db->get('penerimaan_barang');

	}

	public function getDetail($id = '') {
		$this->db->select('pd.*, adm_barang.kode_barang_id, adm_barang.nama_barang, adm_barang.merek, adm_barang.satuan, adm_barang.jenis_alat, adm_barang.kelompok, adm_barang.sn, pnd.jumlah_kirim');
		$this->db->from('penerimaan_detail pd');
		$this->db->join('penerimaan_barang pb', 'pb.id = pd.penerimaan_id', 'left');
		$this->db->join('pengiriman_barang pnb', 'pnb.id = pb.pengiriman_id', 'left');
		$this->db->join('pengiriman_detail pnd', 'pnd.pengiriman_id = pnb.id', 'left');		
		$this->db->join('adm_barang', 'adm_barang.id = pd.barang_id', 'left');
	
		if (!empty($id)) {
			$this->db->where('pd.penerimaan_id', $id);
		}
	
		$this->db->group_by('barang_id');
	
		return $this->db->get();
	}
	
}
