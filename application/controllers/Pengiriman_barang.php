<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengiriman_barang extends Telescoope_Controller
{

    var $data;

    public function __construct() {
        
        parent::__construct();

        $this->load->model(array("Administration_m", "Pengiriman_barang_m", "Perencanaan_m", "Provinsi_m"));

        $this->data['date_format'] = "h:i A | d M Y";

        $this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');

        $this->data['data'] = array();

        $this->data['post'] = $this->input->post();

        $userdata = $this->Administration_m->getLogin();

        $this->data['dir'] = 'pengiriman_barang';

        $this->data['controller_name'] = $this->uri->segment(1);

        $dir = './uploads/' . $this->data['dir'];

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $config['allowed_types'] = '*';
        $config['overwrite'] = false;
        $config['max_size'] = 3064;
        $config['upload_path'] = $dir;
        $this->load->library('upload', $config);

        $this->data['userdata'] = (!empty($userdata)) ? $userdata : array();

        $sess = $this->session->userdata(do_hash(SESSION_PREFIX));

        $cek_menu = $this->db->select('ajm.*')
        ->from('adm_jobtitle_menu ajm')
        ->join('adm_menu am', 'ajm.menu_id = am.menuid', 'left')
        ->where(['jobtitle' => $this->data['userdata']['job_title'], 'url_path' => $this->data['dir']])
        ->get()
        ->num_rows();

        if($cek_menu < 1){
            $this->noAccess("Anda tidak memiliki hak akses untuk halaman ini.");
        }

        if(empty($sess)){
            redirect(site_url('log/in'));
        }
    }

    public function index(){
        $data = array();

        $this->template("pengiriman_barang/list_pengiriman_barang_v", "Data Pengiriman Barang", $data);
    }

    public function get_data(){
        $post = $this->input->post();     
        
        $draw = $post['draw'];
        $row = $post['start'];
        $rowperpage = $post['length']; 
        $search = $post['search']['value']; 
        $columnIndex = $post['order'][0]['column'];
        $columnName = $post['columns'][$columnIndex]['data'];
        // $prov = isset($post['s_provinsi']) ? $post['s_provinsi'] : "";
                        
        if (!empty($search)) {
            // $this->db->group_start();
            // $this->db->like('test', $search);
            // $this->db->or_like('test', $search);
            // $this->db->group_end();
        }

        $this->db->limit($rowperpage, $row);

        $result = $this->Pengiriman_barang_m->getPengiriman_barang()->result_array();

        if (!empty($search)) {
            // $this->db->group_start();
            // $this->db->like('test', $search);
            // $this->db->or_like('test', $search);
            // $this->db->group_end();
        }

        $count = $this->Pengiriman_barang_m->getPengiriman_barang()->num_rows();

        $totalRecords = $count;
        $totalRecordwithFilter = $count;

        $data = array();
        
        foreach($result as $v) {               

            $cek_integrasi = $this->db->select('pengiriman_barang.id')->from('pengiriman_barang')->join('penerimaan_barang', 'pengiriman_barang.id = penerimaan_barang.pengiriman_id', 'right')->where('pengiriman_barang.id', $v['id'])->get()->num_rows();
            
            $action = '<div class="btn-group" role="group">
                        <a href="' .  site_url('pengiriman_barang/detail/' . $v['id']) . '" class="btn btn-sm btn-primary">Detail</a>
                        <a href="' .  site_url('pengiriman_barang/update/' . $v['id']) . '" class="btn btn-sm btn-warning">Edit</a>
                        <a href="' .  site_url('pengiriman_barang/delete/' . $v['id']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Apakah Anda yakin?\');">Hapus</a>
                    </div>';
            
            if($cek_integrasi > 0) {
                $action = '<div class="btn-group" role="group">
                    <a href="' .  site_url('pengiriman_barang/detail/' . $v['id']) . '" class="btn btn-sm btn-primary">Detail</a>
                </div>';
            }
            
            $data[] = array(                                
                "kode_pengiriman" => $v['kode_pengiriman'],
                "province_name" => $v['province_name'],
                "regency_name" => $v['regency_name'],
                "nama_lokasi" => $v['kode_lokasi'] . ' | ' . $v['nama_lokasi'],
                "tgl_kirim" => $v['tgl_kirim'],
                "catatan" => $v['catatan'],
                "action" => $action
            );
        }
        
        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );
        
        echo json_encode($response);
    }
    
    public function add(){
        $data = array();               
        $data['get_provinsi'] = $this->Provinsi_m->getProvinsi()->result_array();
        $data['get_perencanaan'] = $this->Perencanaan_m->getPerencanaan()->result_array(); 
  
        $this->template("pengiriman_barang/add_pengiriman_barang_v", "Tambah Pengiriman Barang", $data);
    }

    public function update($id){
        $data = array();        
        $data['get_perencanaan'] = $this->Perencanaan_m->getPerencanaan()->result_array(); 
        $data['get_pengiriman'] = $this->Pengiriman_barang_m->getPengiriman_barang($id)->row_array();
        $data['get_detail'] = $this->Pengiriman_barang_m->getDetailKirim($id)->result_array();
  
        $this->template("pengiriman_barang/edit_pengiriman_barang_v", "Edit Pengiriman Barang", $data);    
    }

    public function detail($id){
        $data = array();        
        $data['get_pengiriman'] = $this->Pengiriman_barang_m->getPengiriman_barang($id)->row_array();
        $data['get_detail'] = $this->Pengiriman_barang_m->getDetailKirim($id)->result_array();
  
        $this->template("pengiriman_barang/detail_pengiriman_barang_v", "Detail Pengiriman Barang", $data);
    }

    public function submit_data(){

        $post = $this->input->post(); 
        $jumlah = $this->input->post('jumlah_kirim');
        $barang_id = $this->input->post('barang_id');

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('pengiriman_barang/add'));
        }

        $this->db->trans_begin();
        
        $data = array(
            "perencanaan_id" => $post['perencanaan_id'],
            'tgl_kirim' => $post['tgl_kirim'],
            'catatan' => $post['catatan'],
            'created_by' => $this->data['userdata']['employee_id'],
            'created_at' => date('Y-m-d H:i:s'),
        );

        $simpan = $this->db->insert('pengiriman_barang', $data);        
        
        if($simpan){        

            $insert_id = $this->db->insert_id();

            $id = strval($insert_id); 
            $res = str_repeat('0', 5 - strlen($id)).$id;   

            $this->db->set('kode_pengiriman', 'PRB.' . $res)->where('id', $insert_id)->update('pengiriman_barang');

            if (!empty($jumlah)) {
                $data_insert = array();
            
                foreach ($jumlah as $key => $file_name) {                    
                    $data_insert[] = array(
                        'jumlah_kirim' => $jumlah[$key],
                        'barang_id' => $barang_id[$key],
                    );
                }
                                    
                foreach ($data_insert as $insert_data) {
                    $detail = array(
                        "pengiriman_id" => $insert_id,
                        "barang_id" => $insert_data['barang_id'],
                        "jumlah_kirim" => $insert_data['jumlah_kirim'],
                        'created_by' => $this->data['userdata']['employee_id'],
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                    $simpan_detail = $this->db->insert('pengiriman_detail', $detail);

                    $sum = $this->db->select_sum('pd.jumlah_kirim')->from('pengiriman_barang pb')->join('pengiriman_detail pd', 'pb.id = pd.pengiriman_id', 'left')->where('pb.perencanaan_id', $post['perencanaan_id'])->where('pd.barang_id', $insert_data['barang_id'])->get()->row()->jumlah_kirim;
                    
                    if($simpan_detail) {
                        $updateData = array(
                            "jumlah_terkirim" => $sum,
                            'updated_by' => $this->data['userdata']['employee_id'],
                            'updated_at' => date('Y-m-d H:i:s'),
                        );

                        $get_pr = $this->Perencanaan_m->getDetail($post['perencanaan_id'], $insert_data['barang_id'])->row_array();
                        $sisa = (int)$get_pr['jumlah'] - (int)$get_pr['jumlah_terkirim'];

                        if($insert_data['jumlah_kirim'] > $sisa) {
                            $this->setMessage("Gagal simpan, jumlah barang melebihi rencana.");
                            $this->db->trans_rollback();
                            redirect(site_url('pengiriman_barang/add'));
                        }
    
                        $this->db->where('barang_id', $insert_data['barang_id']);
                        $this->db->where('perencanaan_id', $post['perencanaan_id']);
                        $update = $this->db->update('perencanaan_detail', $updateData);
                    }
                }
            }

            if ($this->db->trans_status() === FALSE)  {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }            

            redirect(site_url('pengiriman_barang'));
        
        } else {
            $this->renderMessage("error");
        }
    }

    public function submit_update(){
        
        $post = $this->input->post(); 
        $jumlah = $this->input->post('jumlah_kirim');
        $barang_id = $this->input->post('barang_id');
        $detail_id = $this->input->post('detail_id');

        $this->db->trans_begin();
        
        $update_data = array(
            "perencanaan_id" => $post['perencanaan_id'],
            'tgl_kirim' => $post['tgl_kirim'],
            'catatan' => $post['catatan'],
            'updated_by' => $this->data['userdata']['employee_id'],
            'updated_at' => date('Y-m-d H:i:s'),
        );

        $this->db->where('id', $post['pengiriman_id']);
        $update = $this->db->update('pengiriman_barang', $update_data); 
        
        if($update){        
            if (!empty($jumlah)) {
                $data_insert = array();
            
                foreach ($jumlah as $key => $file_name) {                    
                    $data_insert[] = array(
                        'jumlah_kirim' => $jumlah[$key],
                        'barang_id' => $barang_id[$key],
                        'detail_id' => $detail_id[$key]
                    );
                }
                                    
                foreach ($data_insert as $insert_data) {
                    $detail = array(
                        "pengiriman_id" => $post['pengiriman_id'],
                        "barang_id" => $insert_data['barang_id'],
                        "jumlah_kirim" => $insert_data['jumlah_kirim'],
                        'updated_by' => $this->data['userdata']['employee_id'],
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    $this->db->where('id', $insert_data['detail_id']);
                    $simpan_detail = $this->db->update('pengiriman_detail', $detail);

                    $sum = $this->db->select_sum('pd.jumlah_kirim')->from('pengiriman_barang pb')->join('pengiriman_detail pd', 'pb.id = pd.pengiriman_id', 'left')->where('pb.perencanaan_id', $post['perencanaan_id'])->where('pd.barang_id', $insert_data['barang_id'])->get()->row()->jumlah_kirim;
                    
                    if($simpan_detail) {
                        $updateData = array(
                            "jumlah_terkirim" => $sum,
                            'updated_by' => $this->data['userdata']['employee_id'],
                            'updated_at' => date('Y-m-d H:i:s'),
                        );

                        $get_pr = $this->Perencanaan_m->getDetail($post['perencanaan_id'], $insert_data['barang_id'])->row_array();
                        $sisa = (int)$get_pr['jumlah'] - (int)$get_pr['jumlah_terkirim'];

                        if($insert_data['jumlah_kirim'] > $sisa) {
                            $this->setMessage("Gagal simpan, jumlah barang melebihi rencana.");
                            $this->db->trans_rollback();
                            redirect(site_url('pengiriman_barang/update/' . $post['pengiriman_id']));
                        }
    
                        $this->db->where('id', $insert_data['detail_id']);
                        $update = $this->db->update('perencanaan_detail', $updateData);
                    }
                }
            }

            if ($this->db->trans_status() === FALSE)  {
                $this->setMessage("Failed update data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success update data.");
                $this->db->trans_commit();
            }            

            redirect(site_url('pengiriman_barang'));
        
        } else {
            $this->renderMessage("error");
        }
    }

    public function get_barang()
    {        
        $perencanaan_id = $this->input->post('perencanaan_id', true);
        $data = $this->Perencanaan_m->getDetail($perencanaan_id)->result_array();

        echo json_encode($data);
    }

    public function delete($id)
    {
        $this->db->trans_begin();

        $pr_id = $this->db->where('id', $id)->get('pengiriman_barang')->row()->perencanaan_id;

        $barang_get = $this->db->where('perencanaan_id', $pr_id)->get('perencanaan_detail')->result_array();
        
        foreach($barang_get as $v) {            
            
            $pr_get = $this->Pengiriman_barang_m->getDetailKirim($id, $v['perencanaan_id'], $v['barang_id'])->result_array();

            foreach($pr_get as $res) {
                $updateData = array(
                    "jumlah_terkirim" => $v['jumlah_terkirim'] - $res['jumlah_kirim'],
                    'updated_by' => $this->data['userdata']['employee_id'],
                    'updated_at' => date('Y-m-d H:i:s')
                );

                $this->db->where('perencanaan_id', $v['perencanaan_id']);
                $this->db->where('barang_id', $res['barang_id']);
                $update = $this->db->update('perencanaan_detail', $updateData);
            }
        }
        
        $this->db->where('pengiriman_id', $id);
        $hapus_detail = $this->db->delete('pengiriman_detail');

        if($hapus_detail) {
            $this->db->where('id', $id);
            $this->db->delete('pengiriman_barang');
        }        

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->setMessage("Gagal hapus data.");
            redirect(site_url('pengiriman_barang'));

        } else {
            $this->db->trans_commit();
            $this->setMessage("Berhasil hapus data.");
            redirect(site_url('pengiriman_barang'));
        }
    }
}
