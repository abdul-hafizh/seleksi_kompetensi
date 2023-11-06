<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Serah_terima_barang_m extends CI_Model {

	public function __construct(){

		parent::__construct();

		$this->load->helper('security');

	}

	public function getDismantle($id = '', $lokasi = ''){

        $this->db->select('st.*, rl.*, lokasi_skd.kode_lokasi, lokasi_skd.nama_lokasi');

		if(!empty($id)){

			$this->db->where('st.id', $id);

		}

		if(!empty($lokasi)){

			$this->db->where('lokasi_skd.id', $lokasi);

		}
		
		$this->db->join('lokasi_skd', 'lokasi_skd.id = st.lokasi_skd_id', 'left');
		$this->db->join('ref_locations rl', 'rl.location_id = lokasi_skd.lokasi_id', 'left');

		return $this->db->get('serah_terima st');
    }

	public function getDetail($id = ''){

        $this->db->select('st.*, rl.*, lokasi_skd.kode_lokasi, lokasi_skd.nama_lokasi, stf.foto_kegiatan, stf.keterangan');

		if(!empty($id)){

			$this->db->where('st.id', $id);

		}
		
		$this->db->join('serah_terima_foto stf', 'stf.serah_terima_id = st.id', 'left');
		$this->db->join('lokasi_skd', 'lokasi_skd.id = st.lokasi_skd_id', 'left');
		$this->db->join('ref_locations rl', 'rl.location_id = lokasi_skd.lokasi_id', 'left');
		$this->db->order_by('stf.id', 'asc');

		return $this->db->get('serah_terima st');
    }
}
