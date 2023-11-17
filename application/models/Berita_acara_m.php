<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Berita_acara_m extends CI_Model {

	public function __construct(){

		parent::__construct();

		$this->load->helper('security');

	}

	public function getBerita($id = '', $lokasi = '', $regional = ''){

        $this->db->select('ba.*, rl.*, lokasi_skd.kode_lokasi, lokasi_skd.nama_lokasi, lokasi_skd.alamat, jba.jenis_berita_acara');

		if(!empty($id)){

			$this->db->where('ba.id', $id);

		}

		if(!empty($lokasi)){

			$this->db->where('lokasi_skd.id', $lokasi);

		}

		if(!empty($regional)){

			$this->db->where('lokasi_skd.lokasi_id', $regional);

		}
		
		$this->db->join('adm_jenis_ba jba', 'jba.id = ba.jenis_ba_id', 'left');
		$this->db->join('lokasi_skd', 'lokasi_skd.id = ba.lokasi_skd_id', 'left');
		$this->db->join('ref_locations rl', 'rl.location_id = lokasi_skd.lokasi_id', 'left');

		return $this->db->get('berita_acara ba');
    }
}
