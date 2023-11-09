<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uji_fungsi_barang_m extends CI_Model {

	public function __construct(){

		parent::__construct();

		$this->load->helper('security');

	}

	public function getUji($id = '', $lokasi = ''){

        $this->db->select('pbg.*, ref_locations.*, pb.tgl_kirim, lokasi_skd.kode_lokasi, lokasi_skd.nama_lokasi, pb.kode_pengiriman, upb.kode_uji, upb.id as id_uji, upb.jadwal_kegiatan, upb.catatan_uji');

		if(!empty($id)){

			$this->db->where('upb.id', $id);

		}

		if(!empty($lokasi)){

			$this->db->where('lokasi_skd.id', $lokasi);

		}

		$this->db->join('penerimaan_barang as pbg', 'pbg.id = upb.penerimaan_id', 'left');
		$this->db->join('pengiriman_barang as pb', 'pb.id = pbg.pengiriman_id', 'left');
		$this->db->join('perencanaan as pr', 'pr.id = pb.perencanaan_id', 'left');
		$this->db->join('lokasi_skd', 'lokasi_skd.id = pr.kode_lokasi_skd', 'left');
		$this->db->join('ref_locations', 'ref_locations.location_id = lokasi_skd.lokasi_id', 'left');

		return $this->db->get('uji_penerimaan_barang upb');
	}	

	public function getDetail($id = '', $kelompok = '') {
		$this->db->select('ab.kode_barang_id, ab.nama_barang, ab.merek, ab.satuan, ab.jenis_alat, ab.kelompok, ab.sn, pd.jumlah_terima, pd.jumlah_rusak, pd.jumlah_terpasang, pd.foto_barang, upd.status_baik, upd.status_tidak, upd.catatan, upd.id as id_detail');
		$this->db->from('uji_penerimaan_detail as upd');
		$this->db->join('uji_penerimaan_barang as upb', 'upb.id = upd.uji_penerimaan_id', 'left');
		$this->db->join('penerimaan_barang as pb', 'pb.id = upb.penerimaan_id', 'left');
		$this->db->join('penerimaan_detail as pd', 'pd.penerimaan_id = pb.id AND pd.barang_id = upd.barang_id', 'left');
		$this->db->join('adm_barang as ab', 'ab.id = pd.barang_id');
		
		if (!empty($id)) {
			$this->db->where('upb.id', $id);
		}		

		if (!empty($kelompok)) {
			$this->db->where('ab.kelompok !=', $kelompok);
		}		
		
		return $this->db->get();
	}	

	public function getDetailFoto($id = '') {
		$this->db->select('upd.*, pd.jumlah_terima');
		$this->db->from('uji_penerimaan_detail as upd');
		$this->db->join('uji_penerimaan_barang as upb', 'upb.id = upd.uji_penerimaan_id', 'left');
		$this->db->join('penerimaan_barang as pb', 'pb.id = upb.penerimaan_id', 'left');
		$this->db->join('penerimaan_detail as pd', 'pd.penerimaan_id = pb.id AND pd.barang_id = upd.barang_id', 'left');		
		
		if (!empty($id)) {
			$this->db->where('upd.id', $id);
		}		
		
		return $this->db->get();
	}

	public function getDetailFotoUploaded($id = '') {
		$this->db->select('id, foto_barang, catatan_foto, created_at');
		$this->db->from('uji_detail_foto');		
		
		if (!empty($id)) {
			$this->db->where('uji_detail_id', $id);
		}		
		
		return $this->db->get();
	}	
}
