<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Update_barang extends Telescoope_Controller
{

    var $data;

    public function __construct()
    {

        parent::__construct();

        $this->load->model(array("Administration_m", "Update_barang_m", "Penerimaan_barang_m", "Perencanaan_m"));
        $this->data['date_format'] = "h:i A | d M Y";
        $this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');
        $this->data['data'] = array();
        $this->data['post'] = $this->input->post();
        $userdata = $this->Administration_m->getLogin();
        $this->data['dir'] = 'update_barang';
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

        if (empty($sess)) {
            redirect(site_url('log/in'));
        }
    }

    public function index()
    {
        $data = array();
        $this->template("pelaksanaan_harian/update_barang/list_update_barang_v", "Data Update Pengawasan Barang", $data);
    }

    public function get_data()
    {
        $post = $this->input->post();

        $lokasi = '';
        $draw = $post['draw'];
        $row = $post['start'];
        $rowperpage = $post['length'];
        $search = $post['search']['value'];
        $columnIndex = $post['order'][0]['column'];
        $columnName = $post['columns'][$columnIndex]['data'];

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('province_name', $search);
            $this->db->or_like('regency_name', $search);
            $this->db->or_like('nama_lokasi', $search);
            $this->db->or_like('alamat', $search);
            $this->db->group_end();
        }

        $this->db->limit($rowperpage, $row);

        $position = $this->Administration_m->getPosition("KOORDINATOR");

        if($position) {
            $lokasi = $this->data['userdata']['lokasi_skd_id'];
        }

        $result = $this->Update_barang_m->getUpdate_barang("", $lokasi)->result_array();
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('province_name', $search);
            $this->db->or_like('regency_name', $search);
            $this->db->or_like('nama_lokasi', $search);
            $this->db->or_like('alamat', $search);
            $this->db->group_end();
        }

        $count = $this->Update_barang_m->getUpdate_barang("", $lokasi)->num_rows();

        $totalRecords = $count;
        $totalRecordwithFilter = $count;

        $data = array();

        foreach ($result as $v) {

            $action = '<div class="btn-group" role="group">
                        <a href="' .  site_url('pelaksanaan_harian/update_barang/edit/' . $v['id']) . '" class="btn btn-sm btn-warning" disabled>Edit</a>
                        <a href="' .  site_url('pelaksanaan_harian/update_barang/detail/' . $v['id']) . '" class="btn btn-sm btn-primary" disabled>Detail</a>
                        <a href="' .  site_url('pelaksanaan_harian/update_barang/delete/' . $v['id']) . '" onclick=\'return confirm("Apakah anda yakin?")\' class="btn btn-sm btn-danger">Delete</a></div>';

            $data[] = array(
                "kode_penerimaan" => $v['kode_penerimaan'],
                "province_name" => $v['province_name'],
                "regency_name" => $v['regency_name'],
                "nama_lokasi" => $v['nama_lokasi'] . ' (' . $v['alamat'] . ')',
                "tgl_update" => date('d-m-Y', strtotime($v['tgl_update_harian'])),
                "tgl_terima" => date('d-m-Y', strtotime($v['tgl_terima'])),
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

    public function add()
    {
        $data = array();
        $position = $this->Administration_m->getPosition("KOORDINATOR");
        
        $data['get_penerimaan'] = $this->Penerimaan_barang_m->getPenerimaan_barang()->result_array();        

        if($position) {
            $data['get_penerimaan'] = $this->Penerimaan_barang_m->getPenerimaan_barang("", $this->data['userdata']['lokasi_skd_id'])->result_array();
        } else {
            $this->noAccess("Hanya koordinator yang dapat melakukan tambah data.");
        }

        $this->template("pelaksanaan_harian/update_barang/add_update_barang_v2", "Tambah Update Barang", $data);
    }

    public function detail($id)
    {
        $data = array();
        $data['id']  = $id;
        $data['get_update_barang'] = $this->Update_barang_m->getUpdate_barang($id)->row_array();
        $data['get_update_barang_detail'] = $this->Update_barang_m->get_UpdateBarangDetail($id)->result_array();
        $this->template("pelaksanaan_harian/update_barang/detail_update_barang_v2", "Detail Update Barang", $data);
    }

    public function edit($id)
    {
        $data = array();
        $position = $this->Administration_m->getPosition("KOORDINATOR");
        
        $data['get_penerimaan'] = $this->Penerimaan_barang_m->getPenerimaan_barang()->result_array();

        if($position) {
            $data['get_penerimaan'] = $this->Penerimaan_barang_m->getPenerimaan_barang("", $this->data['userdata']['lokasi_skd_id'])->row_array();
        } else {
            $this->noAccess("Hanya koordinator yang dapat melakukan tambah data.");
        }

        $data['id']  = $id;        
        $data['get_update_barang'] = $this->Update_barang_m->getUpdate_barang($id)->row_array();
        $data['get_update_barang_detail'] = $this->Update_barang_m->get_UpdateBarangDetail($id)->result_array();
        $this->template("pelaksanaan_harian/update_barang/edit_update_barang_v2", "Edit Update Barang", $data);
    }

    public function update($id)
    {
        $post = $this->input->post();
        $status_ada = $this->input->post('status_ada');
        $status_tidak_ada = $this->input->post('status_tidak_ada');
        $kondisi_baik = $this->input->post('kondisi_baik');
        $kondisi_tidak_baik = $this->input->post('kondisi_tidak_baik');
        $barang_id = $this->input->post('barang_id');
        $detail_id = $this->input->post('detail_id');
        $foto_exist = $this->input->post('foto_exist');

        $dir = './uploads/' . $this->data['dir'];

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('pelaksanaan_harian/update_barang/edit/' . $id));
        }
        
        $this->db->trans_begin();
        
        $data = array(
            "penerimaan_id" => $post['penerimaan_id'],
            "tgl_update_harian" => $post['tgl_update'],
            'catatan' => $post['catatan'],
            'updated_by' => $this->data['userdata']['employee_id'],
            'updated_at' => date('Y-m-d H:i:s'),
        );

        $this->db->where('id', $id);
        $update = $this->db->update('update_harian_barang', $data);

        if ($update) {

            if (!empty($barang_id)) {

                $data_insert = array();
            
                foreach ($barang_id as $key => $v) {

                    $file_name = isset($_FILES['foto_barang']['name'][$key]) ? $_FILES['foto_barang']['name'][$key] : '';
                    
                    $_FILES['file']['name'] = $this->data['userdata']['employee_id'] . '_barang_update_' . date('YmdHis') . '_' . $file_name;
                    $_FILES['file']['type'] = $_FILES['foto_barang']['type'][$key];
                    $_FILES['file']['tmp_name'] = $_FILES['foto_barang']['tmp_name'][$key];
                    $_FILES['file']['error'] = $_FILES['foto_barang']['error'][$key];
                    $_FILES['file']['size'] = $_FILES['foto_barang']['size'][$key];

                    if ($this->upload->do_upload('file')) {
                        $uploadBarang = $this->upload->data();
                        $data_insert[] = array(                            
                            'jumlah_barang_status_ada' => $status_ada[$key],
                            'jumlah_barang_status_tidak_ada' => $status_tidak_ada[$key],
                            'jumlah_barang_kondisi_baik' => $kondisi_baik[$key],
                            'jumlah_barang_kondisi_rusak' => $kondisi_tidak_baik[$key],
                            'barang_id' => $barang_id[$key],
                            'detail_id' => $detail_id[$key],
                            'file_path' => $uploadBarang['file_name'],
                        );
                    } else {
                        $data_insert[] = array(
                            'jumlah_barang_status_ada' => $status_ada[$key],
                            'jumlah_barang_status_tidak_ada' => $status_tidak_ada[$key],
                            'jumlah_barang_kondisi_baik' => $kondisi_baik[$key],
                            'jumlah_barang_kondisi_rusak' => $kondisi_tidak_baik[$key],
                            'barang_id' => $barang_id[$key],
                            'detail_id' => $detail_id[$key],
                            'file_path' => $foto_exist[$key]
                        );
                    }          
                }

                foreach ($data_insert as $insert_data) {
                    $detail_update = array(                        
                        "jumlah_barang_status_ada" => $insert_data['jumlah_barang_status_ada'],
                        "jumlah_barang_status_tidak_ada" => $insert_data['jumlah_barang_status_tidak_ada'],
                        "jumlah_barang_kondisi_baik" => $insert_data['jumlah_barang_kondisi_baik'],
                        "jumlah_barang_kondisi_rusak" => $insert_data['jumlah_barang_kondisi_rusak'],
                        'foto_barang' => $insert_data['file_path'],
                        'updated_by' => $this->data['userdata']['employee_id'],
                        'updated_at' => date('Y-m-d H:i:s')
                    );            

                    $this->db->where('id', $insert_data['detail_id']);
                    $simpan_details = $this->db->update('update_harian_barang_detail', $detail_update);
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }
            redirect(site_url('pelaksanaan_harian/update_barang'));
        } else {
            $this->renderMessage("error");
        }
    }

    public function delete($id)
    {
        $this->db->where('update_barang_id', $id);
        $this->db->delete('update_harian_barang_detail');
        $this->db->where('id', $id);
        $this->db->delete('update_harian_barang');
        $this->setMessage("Success delete data.");
        redirect(site_url('pelaksanaan_harian/update_barang'));
    }

    public function submit_datav2()
    {
        $post = $this->input->post();
        $status_ada = $this->input->post('status_ada');
        $status_tidak_ada = $this->input->post('status_tidak_ada');
        $kondisi_baik = $this->input->post('kondisi_baik');
        $kondisi_tidak_baik = $this->input->post('kondisi_tidak_baik');
        $jumlah_barang = $this->input->post('jumlah_barang');
        $barang_id = $this->input->post('barang_id');

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('pelaksanaan_harian/update_barang/add'));
        }

        $cek_integrasi = $this->db->select('id')->from('update_harian_barang')->where('penerimaan_id', $post['penerimaan_id'])->get()->num_rows();
        
        if ($cek_integrasi > 0) {
            $this->setMessage("Data update barang sudah pernah diinput.");
            redirect(site_url('pelaksanaan_harian/update_barang/add'));
        }

        $this->db->trans_begin();

        $data = array(
            "penerimaan_id" => $post['penerimaan_id'],
            "tgl_update_harian" => $post['tgl_update'],
            'catatan' => $post['catatan'],
            'created_by' => $this->data['userdata']['employee_id'],
            'created_at' => date('Y-m-d H:i:s'),
        );

        $simpan = $this->db->insert('update_harian_barang', $data);

        if ($simpan) {

            $insert_id = $this->db->insert_id();
            
            $id = strval($insert_id); 
            $res = str_repeat('0', 5 - strlen($id)).$id;   

            $this->db->set('kode_update_barang', 'UPB.' . $res)->where('id', $insert_id)->update('update_harian_barang');

            $dir = './uploads/' . $this->data['dir'];
            $data_insert = array();

            if (!empty($barang_id)) {
            
                foreach ($barang_id as $key => $v) {

                    $file_name = isset($_FILES['foto_barang']['name'][$key]) ? $_FILES['foto_barang']['name'][$key] : '';

                    $_FILES['file']['name'] = $this->data['userdata']['employee_id'] . '_barang_update_' . date('YmdHis') . '_' . $file_name;
                    $_FILES['file']['type'] = $_FILES['foto_barang']['type'][$key];
                    $_FILES['file']['tmp_name'] = $_FILES['foto_barang']['tmp_name'][$key];
                    $_FILES['file']['error'] = $_FILES['foto_barang']['error'][$key];
                    $_FILES['file']['size'] = $_FILES['foto_barang']['size'][$key];

                    if ($this->upload->do_upload('file')) {
                        $uploadBarang = $this->upload->data();
                        $data_insert[] = array(
                            'jumlah_barang' => $jumlah_barang[$key],
                            'jumlah_barang_status_ada' => $status_ada[$key],
                            'jumlah_barang_status_tidak_ada' => $status_tidak_ada[$key],
                            'jumlah_barang_kondisi_baik' => $kondisi_baik[$key],
                            'jumlah_barang_kondisi_rusak' => $kondisi_tidak_baik[$key],
                            'barang_id' => $barang_id[$key],
                            'file_path' => $uploadBarang['file_name'],
                        );
                    } else {
                        $data_insert[] = array(
                            'jumlah_barang' => $jumlah_barang[$key],
                            'jumlah_barang_status_ada' => $status_ada[$key],
                            'jumlah_barang_status_tidak_ada' => $status_tidak_ada[$key],
                            'jumlah_barang_kondisi_baik' => $kondisi_baik[$key],
                            'jumlah_barang_kondisi_rusak' => $kondisi_tidak_baik[$key],
                            'barang_id' => $barang_id[$key],
                            'file_path' => ''
                        );
                    }
                }

                foreach ($data_insert as $insert_data) {
                    $detail = array(
                        "update_barang_id" => $insert_id,
                        "barang_id" => $insert_data['barang_id'],
                        "jumlah_barang" => $insert_data['jumlah_barang'],
                        "jumlah_barang_status_ada" => $insert_data['jumlah_barang_status_ada'],
                        "jumlah_barang_status_tidak_ada" => $insert_data['jumlah_barang_status_tidak_ada'],
                        "jumlah_barang_kondisi_baik" => $insert_data['jumlah_barang_kondisi_baik'],
                        "jumlah_barang_kondisi_rusak" => $insert_data['jumlah_barang_kondisi_rusak'],
                        'foto_barang' => $insert_data['file_path'],
                        'created_by' => $this->data['userdata']['employee_id'],
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                    $simpan_detail = $this->db->insert('update_harian_barang_detail', $detail);
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }

            redirect(site_url('pelaksanaan_harian/update_barang'));
        } else {
            $this->renderMessage("error");
        }
    }

    public function submit_data()
    {
        $post = $this->input->post();

        if (count($post) == 0) {
            $this->setMessage("Isi data dengan Benar.");
            redirect(site_url('pelaksanaan_harian/update_barang/add'));
        }

        $this->db->trans_begin();

        $dir = './uploads/' . $this->data['dir'];

        if (!empty($_FILES['foto_barang']['name'])) {
            $_FILES['file']['name'] = $this->data['userdata']['employee_id'] . '_barang_' . date('his') . '_' . $_FILES['foto_barang']['name'];
            $_FILES['file']['type'] = $_FILES['foto_barang']['type'];
            $_FILES['file']['tmp_name'] = $_FILES['foto_barang']['tmp_name'];
            $_FILES['file']['error'] = $_FILES['foto_barang']['error'];
            $_FILES['file']['size'] = $_FILES['foto_barang']['size'];
            if ($this->upload->do_upload('file')) {
                $uploadKtp = $this->upload->data();
            }
        }

        $data = array(
            "penerimaan_id" => $post['penerimaan_id'],
            "tgl_pelaksanaan" => $post['tgl_pelaksanaan'],
            "nama_barang" => $post['nama_barang'],
            "jumlah_barang" => $post['jumlah_barang'],
            "status_uji" => $post['status_uji'],
            'foto_barang' => isset($uploadKtp['file_name']) ? $uploadKtp['file_name'] : '',
            'catatan' => $post['catatan'],
            'created_by' => $this->data['userdata']['employee_id'],
            'created_at' => date('Y-m-d H:i:s'),
        );

        $simpan = $this->db->insert('update_barang', $data);

        if ($simpan) {
            if ($this->db->trans_status() === FALSE) {
                $this->setMessage("Failed save data.");
                $this->db->trans_rollback();
            } else {
                $this->setMessage("Success save data.");
                $this->db->trans_commit();
            }

            redirect(site_url('pelaksanaan_harian/update_barang/add'));
        } else {
            $this->renderMessage("error");
        }
    }

    public function get_penerimaan()
    {
        $penerimaan_id = $this->input->post('penerimaan_id', true);
        $data = $this->Penerimaan_barang_m->getDetail($penerimaan_id)->result_array();
        echo json_encode($data);
    }
}
