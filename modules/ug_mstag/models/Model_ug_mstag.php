<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_ug_mstag extends MY_Model {

    private $primary_key    = 'id';
    private $table_name     = 'tb_master_tag_rfid';
    public $field_search   = ['kode_tid', 'kode_epc', 'status_tag', 'kategori_tag'];
    public $sort_option = ['id', 'DESC'];
    
    public function __construct()
    {
        $config = array(
            'primary_key'   => $this->primary_key,
            'table_name'    => $this->table_name,
            'field_search'  => $this->field_search,
            'sort_option'   => $this->sort_option,
         );

        parent::__construct($config);
    }

    public function count_all($q = null, $field = null)
    {
        $iterasi = 1;
        $num = count($this->field_search);
        $where = NULL;
        $q = $this->scurity($q);
        $field = $this->scurity($field);
        $field = in_array($field, $this->field_search) ? $field : "";

        if (empty($field)) {
            foreach ($this->field_search as $field) {
                $f_search = "tb_master_tag_rfid." . $field;

                if (strpos($field, '.')) {
                    $f_search = $field;
                }
                if ($iterasi == 1) {
                    $where .= "COALESCE(" . $f_search . "::text, '') ILIKE '%" . $this->db->escape_like_str($q) . "%'";
                } else {
                    $where .= " OR COALESCE(" . $f_search . "::text, '') ILIKE '%" . $this->db->escape_like_str($q) . "%'";
                }
                $iterasi++;
            }

            $where = '(' . $where . ')';
        } else {
            $f_search = "tb_master_tag_rfid." . $field;
            if (strpos($field, '.')) {
                $f_search = $field;
            }
            
            $where .= "(COALESCE(" . $f_search . "::text, '') ILIKE '%" . $this->db->escape_like_str($q) . "%')";
        }

        $this->join_avaiable()->filter_avaiable();
        
        if (!empty($where)) {
            $this->db->where($where);
        }
        
        // Add error logging
        $this->db->save_queries = TRUE;
        $query = $this->db->get($this->table_name);
        
        // Check if query failed
        if ($query === FALSE) {
            // Log the error
            log_message('error', 'Database error: ' . print_r($this->db->error(), TRUE) . ' - SQL: ' . $this->db->last_query());
            return 0; // Return 0 instead of causing an error
        }

        return $query->num_rows();
    }

    public function get($q = null, $field = null, $limit = 0, $offset = 0, $select_field = [])
    {
        $iterasi = 1;
        $num = count($this->field_search);
        $where = NULL;
        // $q = $this->scurity($q);
        // $field = $this->scurity($field);
        $field = in_array($field, $this->field_search) ? $field : "";
        
        if (empty($field)) {
            foreach ($this->field_search as $field) {
                $f_search = "tb_master_tag_rfid." . $field;
                if (strpos($field, '.')) {
                    $f_search = $field;
                }
                
                if ($iterasi == 1) {
                    $where .= "COALESCE(" . $f_search . "::text, '') ILIKE '%" . $this->db->escape_like_str($q) . "%'";
                } else {
                    $where .= " OR COALESCE(" . $f_search . "::text, '') ILIKE '%" . $this->db->escape_like_str($q) . "%'";
                }
                $iterasi++;
            }
            
            $where = '(' . $where . ')';
        } else {
            $f_search = "tb_master_tag_rfid." . $field;
            if (strpos($field, '.')) {
                $f_search = $field;
            }
            
            $where .= "(COALESCE(" . $f_search . "::text, '') ILIKE '%" . $this->db->escape_like_str($q) . "%')";
        }
        
        if (is_array($select_field) and count($select_field)) {
            $this->db->select($select_field);
        }
        
        $this->join_avaiable()->filter_avaiable();
        
        if (!empty($where)) {
            $this->db->where($where);
        }
        
        $this->db->limit($limit, $offset);
        $this->sortable();
        
        $this->db->order_by($this->sort_option[0], $this->sort_option[1]);
        
        // Add error handling
        $this->db->save_queries = TRUE;
        $query = $this->db->get($this->table_name);
        
        // Check if query failed
        if ($query === FALSE) {
            // Log the error
            log_message('error', 'Database error: ' . print_r($this->db->error(), TRUE) . ' - SQL: ' . $this->db->last_query());
            return []; // Return empty array instead of causing an error
        }
        
        return $query->result();
    }

    public function join_avaiable() {
        
        $this->db->select('tb_master_tag_rfid.*');


        return $this;
    }

    public function filter_avaiable() {

        if (!$this->aauth->is_admin()) {
            }

        return $this;
    }

    function saveData($data) {
        $this->db->trans_begin();

        $existing_data = []; // Array untuk menyimpan data yang sudah ada di database

        foreach ($data as $item) {
            $kode_tid = $item['tid'];
            $status_tag = 'Y';
            $kode_epc = $item['epc'] ? $item['epc'] : NULL;

            // Cek apakah kode_tid sudah ada di database
            $this->db->select('kode_tid, kode_epc');
            $this->db->where('kode_tid', $kode_tid);
            $existing = $this->db->get('tb_master_tag_rfid')->row_array();

            if ($existing) {
                // Jika sudah ada, tambahkan ke array existing_data
                $existing_data[] = [
                    'kode_tid' => $existing['kode_tid'],
                    'kode_epc' => $existing['kode_epc']
                ];
            } else {
                // Jika belum ada, insert data baru
                $save_data = [
                    'kode_tid' => $kode_tid,
                    'status_tag' => $status_tag,
                    'kode_epc' => $kode_epc
                ];
                $this->db->insert('tb_master_tag_rfid', $save_data);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return [
                'is_success' => false,
                'existing_data' => $existing_data
            ];
        } else {
            $this->db->trans_commit();
            return [
                'is_success' => true,
                'existing_data' => $existing_data
            ];
        }
    }

    function getPengaturanSistem(){
        $this->db->select('*');
        $this->db->from('pengaturan_sistem');
        return $this->db->get()->row();
    }

    function saveDataIntegrasiExisting($data, $save_data_master_transaksi) {

        // $this->db->trans_begin();
        $existing_data = []; // Array untuk menyimpan data yang sudah ada di database

        // echo '<pre>';
        // print_r($save_data_master_transaksi);
        // echo '</pre>';
        // exit;

        try {
            // Insert ke tabel master transaksi
            $this->db->insert('tb_master_transaksi', $save_data_master_transaksi);
            // $id_transaksi = $this->db->insert_id();
            // $save_tag_temp_table = $this->db->insert_id('tag_temp_table_process_id_temp_table_seq');
            // $id_transaksi = $this->db->insert_id('tb_master_transaksi_id_seq');

            $query = $this->db->query("SELECT currval(pg_get_serial_sequence('tb_master_transaksi','id')) AS last_id");

            if ($query) {
                $row = $query->row();
                $id_transaksi = $row->last_id;
            } else {
                // Handle the error appropriately
                log_message('error', 'Failed to retrieve last insert ID from tb_master_transaksi');
                return false;
            }

            foreach ($data as $item) {

                $kode_tid = $item['tid'];
                $status_tag = 'N';
                $kode_epc = $item['epc'] ? $item['epc'] : NULL;
                
                // Cek apakah kode_epc sudah ada di database
                $this->db->select('*');
                $this->db->from('tb_master_aset');
                $this->db->where('kode_aset', $kode_epc); // pastikan kolomnya sesuai
                $query = $this->db->get();

                if ($query && $query->num_rows() > 0) {
                    $existing_kode_aset = $query->row_array();
                } else {
                    $existing_kode_aset = NULL;
                }

                // Debug print
                // echo '<pre>';
                // print_r($existing_kode_aset);
                // echo '</pre>';
                // exit;

                if ($existing_kode_aset) {

                    $data_detail = array(
                        'id_transaksi' => $id_transaksi,
                        'kode_transaksi' => '',
                        'kode_tid' => $kode_tid,
                        'id_aset' => $existing_kode_aset['id_aset'],
                        'kode_aset' => $existing_kode_aset['kode_aset'],
                        'nup' => $existing_kode_aset['nup'],
                        'nama_aset' => $existing_kode_aset['nama_aset'],
                        'status' => 1,
                        'id_kondisi' => 1
                    );

                    // echo '<pre>';
                    // print_r($data_detail);
                    // echo '</pre>';

                    // Insert ke tabel detail transaksi
                    $this->db->insert('tb_detail_transaksi', $data_detail);

                    $data_aset = array(
                        'kode_tid' => $kode_tid,
                        'id_area' => $save_data_master_transaksi['id_area'],
                        'id_gedung' => $save_data_master_transaksi['id_gedung'],
                        'id_lokasi' => $save_data_master_transaksi['id_ruangan'],
                        'lokasi_moving' => $save_data_master_transaksi['id_ruangan'],
                        'kondisi' => 1,
                        'status' => 1,
                        'borrow' => 0,
                        'tipe_moving' => 0,
                        'flag_inventarisasi' => 1,
                        'tgl_inventarisasi' => date('Y-m-d H:i:s'),
                    );
                    
                    $this->db->where('kode_aset', $kode_epc);
                    $this->db->update('tb_master_aset', $data_aset);

                    $this->db->select('id_aset');
                    $this->db->where('kode_aset', $kode_epc);
                    $aset = $this->db->get('tb_master_aset')->row_array();
                    $id_aset = $aset['id_aset'];

                    // Jika belum ada, insert data baru
                    $save_data = [
                        'kategori_tag' => 1,
                        'kode_tid' => $kode_tid,
                        'status_tag' => $status_tag,
                        'kode_epc' => $kode_epc,
                        'id_aset' => $id_aset,
                    ];

                    $this->db->insert('tb_master_tag_rfid', $save_data);

                } else {

                    // Jika belum ada, insert data baru
                    // $save_data = [
                    //     'kode_tid' => $kode_tid,
                    //     'status_tag' => $status_tag,
                    //     'kode_epc' => $kode_epc
                    // ];
                    // $this->db->insert('tb_master_tag_rfid', $save_data);

                    // kata bro miftah ga usah di insert, skip aja

                }
        
            }

            // if ($this->db->trans_status() === FALSE) {
            //     $this->db->trans_rollback();
            //     return [
            //         'is_success' => false,
            //         'existing_data' => $existing_data
            //     ];
            // } else {
            //     $this->db->trans_commit();
            //     return [
            //         'is_success' => true,
            //         'existing_data' => $existing_data
            //     ];
            // }

            return [
                'is_success' => true,
                'existing_data' => $existing_data
            ];

        } catch (Exception $e) {
            // Rollback jika terjadi exception
            // $this->db->trans_rollback();
            return FALSE;
        }

    }

    function saveDataIntegrasiNew($data, $save_data_master_transaksi, $detail_data) {

        // $this->db->trans_begin();
        $existing_data = []; // Array untuk menyimpan data yang sudah ada di database

        // echo '<pre>';
        // print_r($save_data_master_transaksi);
        // echo '</pre>';
        // exit;

        try {
            // Insert ke tabel master transaksi
            $this->db->insert('tb_master_transaksi', $save_data_master_transaksi);
            // $id_transaksi = $this->db->insert_id();
            // $save_tag_temp_table = $this->db->insert_id('tag_temp_table_process_id_temp_table_seq');
            // $id_transaksi = $this->db->insert_id('tb_master_transaksi_id_seq');

            $query = $this->db->query("SELECT currval(pg_get_serial_sequence('tb_master_transaksi','id')) AS last_id");

            if ($query) {
                $row = $query->row();
                $id_transaksi = $row->last_id;
            } else {
                // Handle the error appropriately
                log_message('error', 'Failed to retrieve last insert ID from tb_master_transaksi');
                return false;
            }

            foreach ($data as $item) {

                $kode_tid = $item['tid'];
                $status_tag = 'N';
                $kode_epc = $item['epc'] ? $item['epc'] : NULL;
                
                // Cek apakah kode_epc sudah ada di database
                $this->db->select('*');
                $this->db->from('tb_master_aset');
                $this->db->where('kode_aset', $kode_epc); // pastikan kolomnya sesuai
                $query = $this->db->get();

                if ($query && $query->num_rows() > 0) {
                    $existing_kode_aset = $query->row_array();
                } else {
                    $existing_kode_aset = NULL;
                }

                // Debug print
                // echo '<pre>';
                // print_r($existing_kode_aset);
                // echo '</pre>';
                // exit;

                if (!$existing_kode_aset) {

                    $data_aset = array(
                        'kode_tid' => $kode_tid,
                        'kode_aset' => $kode_epc,
                        // 'nup' => '-',
                        'kategori' => 2,
                        'merk' => $detail_data['merk'],
                        'tipe' => $detail_data['tipe'],
                        'kondisi' => 1,
                        'status' => 1,
                        'borrow' => 0,
                        'tipe_moving' => 0,
                        'nama_aset' => $kode_epc,
                        'id_area' => $save_data_master_transaksi['id_area'],
                        'id_gedung' => $save_data_master_transaksi['id_gedung'],
                        'id_lokasi' => $save_data_master_transaksi['id_ruangan'],

                        // 'tgl_perolehan' => '-',
                        // 'nilai_perolehan' => '-',

                        'tgl_inventarisasi' => date('Y-m-d H:i:s'),

                        // 'tgl_peminjaman' => '-',
                        // 'tgl_pengembalian' => '-',

                        'flag_inventarisasi' => 1,

                        // 'id_peminjam' => '-',
                        'lokasi_moving' => $save_data_master_transaksi['id_ruangan'],

                        // 'lokasi_terakhir' => '-',
                        // 'nama_lokasi_terakhir' => '-',
                        
                        'id_pegawai' => $detail_data['pic'],
                        
                        // 'image_uri' => '-',
                        'id_transaksi' => $id_transaksi,
                        // 'no_batch_sensus' => '-',
                        // 'keterangan' => '-',
                    );

                    $this->db->insert('tb_master_aset', $data_aset);

                    // echo $this->db->last_query();
                    // exit;

                    $query = $this->db->query("SELECT currval(pg_get_serial_sequence('tb_master_aset','id_aset')) AS last_id");

                    if ($query) {
                        $row = $query->row();
                        $id_aset = $row->last_id;
                    } else {
                        // Handle the error appropriately
                        log_message('error', 'Failed to retrieve last insert ID from tb_master_transaksi');
                        return false;
                    }

                    // echo 'aman bro';
                    // exit;

                    // Jika belum ada, insert data baru
                    $save_data = [
                        'kategori_tag' => 1,
                        'kode_tid' => $kode_tid,
                        'status_tag' => $status_tag,
                        'kode_epc' => $kode_epc,
                        'id_aset' => $id_aset,
                    ];

                    $this->db->insert('tb_master_tag_rfid', $save_data);

                    $data_detail = array(
                        'id_transaksi' => $id_transaksi,
                        'kode_transaksi' => '',
                        'kode_tid' => $kode_tid,
                        'id_aset' => $id_aset,
                        'kode_aset' => $kode_epc,
                        'nup' => '-',
                        'nama_aset' => $kode_epc,
                        'status' => 1,
                        'id_kondisi' => 1
                    );

                    // echo '<pre>';
                    // print_r($data_detail);
                    // echo '</pre>';

                    // Insert ke tabel detail transaksi
                    $this->db->insert('tb_detail_transaksi', $data_detail);

                }
        
            }

            // if ($this->db->trans_status() === FALSE) {
            //     $this->db->trans_rollback();
            //     return [
            //         'is_success' => false,
            //         'existing_data' => $existing_data
            //     ];
            // } else {
            //     $this->db->trans_commit();
            //     return [
            //         'is_success' => true,
            //         'existing_data' => $existing_data
            //     ];
            // }

            return [
                'is_success' => true,
                'existing_data' => $existing_data
            ];

        } catch (Exception $e) {
            // Rollback jika terjadi exception
            // $this->db->trans_rollback();
            return FALSE;
        }

    }

}

/* End of file Model_ug_mstag.php */
/* Location: ./application/models/Model_ug_mstag.php */