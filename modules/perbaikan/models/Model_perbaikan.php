<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_perbaikan extends MY_Model {

    private $primary_key    = 'id';
    private $table_name     = 'tb_master_transaksi';
    public $field_search   = ['kode_transaksi', 'tipe_transaksi', 'status_transaksi', 'tgl_awal_transaksi', 'ket_transaksi', 'id_pegawai_input', 'nama_pegawai_input', 'id_area', 'id_gedung', 'id_ruangan', 'tb_master_type_transaksi.tipe_transaksi', 'tb_master_area.area', 'tb_master_gedung.gedung', 'tb_master_ruangan.ruangan'];
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
                $f_search = "tb_master_transaksi.".$field;

                if (strpos($field, '.')) {
                    $f_search = $field;
                }
                if ($iterasi == 1) {
                    $where .=  $f_search . " LIKE '%" . $q . "%' ";
                } else {
                    $where .= "OR " .  $f_search . " LIKE '%" . $q . "%' ";
                }
                $iterasi++;
            }

            $where = '('.$where.')';
        } else {
            $where .= "(" . "tb_master_transaksi.".$field . " LIKE '%" . $q . "%' )";
        }

        $this->join_avaiable()->filter_avaiable();
        $this->db->where('tb_master_transaksi.tipe_transaksi = 6');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get($this->table_name);

        return $query->num_rows();
    }

    public function get($q = null, $field = null, $limit = 0, $offset = 0, $select_field = [])
    {
        // echo $field;
        // exit();
        
        // $q = $this->scurity($q);
        // $field = $this->scurity($field);

        $field = in_array($field, $this->field_search) ? $field : "";
        $where = "tb_master_transaksi.tipe_transaksi::text = '6'";

        if ($field == 'status_transaksi') {

            switch (strtolower($q)) {
                case "open":
                    $where .= " AND tb_master_transaksi.status_transaksi = 1";
                    break;
                case "progress":
                    $where .= " AND tb_master_transaksi.status_transaksi = 2";
                    break;
                case "complete":
                    $where .= " AND tb_master_transaksi.status_transaksi = 3";
                    break;
                case "batal":
                    $where .= " AND tb_master_transaksi.status_transaksi = 4";
                    break;
            }

        } elseif (empty($field)) {
            // Global search across multiple fields
            $where_conditions = [];
            foreach ($this->field_search as $search_field) {
                $f_search = strpos($search_field, '.') !== false 
                    ? $search_field 
                    : "tb_master_transaksi." . $search_field;
                
                // Use ILIKE for case-insensitive search and handle different column types
                $where_conditions[] = "COALESCE(" . $f_search . "::text, '') ILIKE '%" . $this->db->escape_like_str($q) . "%'";
            }
            
            if (!empty($where_conditions)) {
                $where .= " AND (" . implode(" OR ", $where_conditions) . ")";
            }
        } else {
            // Single field search
            $f_search = strpos($field, '.') !== false 
                ? $field 
                : "tb_master_transaksi." . $field;
            
            $where .= " AND (COALESCE(" . $f_search . "::text, '') ILIKE '%" . $this->db->escape_like_str($q) . "%')";
        }

        // Handle select fields
        if (is_array($select_field) && count($select_field)) {
            $this->db->select($select_field);
        }
        
        // Join and filter methods
        // $this->join_avaiable()->filter_avaiable();
        $this->join_avaiable();
        
        // Add where clause with proper escaping
        $this->db->where($where, NULL, FALSE);
        
        // Ordering
        $this->db->order_by('id', 'DESC');
        
        // Limit and offset
        $this->db->limit($limit, $offset);
        
        // Additional sorting
        $this->sortable();
        
        // Execute query
        $query = $this->db->get($this->table_name);
        
        // Optional: Log the last query (remove in production)
        // log_message('debug', $this->db->last_query());
        // echo $this->db->last_query();

        // Return results or empty array
        return $query ? $query->result() : [];
    }


    public function join_avaiable() {
        $this->db->select('tb_master_type_transaksi.tipe_transaksi,tb_master_area.area,tb_master_gedung.gedung,tb_master_ruangan.ruangan,tb_master_transaksi.*,tb_master_type_transaksi.tipe_transaksi as tb_master_type_transaksi_tipe_transaksi,tb_master_type_transaksi.tipe_transaksi as tipe_transaksi,tb_master_area.area as tb_master_area_area,tb_master_area.area as area,tb_master_gedung.gedung as tb_master_gedung_gedung,tb_master_gedung.gedung as gedung,tb_master_ruangan.ruangan as tb_master_ruangan_ruangan,tb_master_ruangan.ruangan as ruangan');
        // $this->db->select('tb_detail_transaksi.id as detail_id, tb_detail_transaksi.kode_tid, tb_detail_transaksi.id_aset, tb_detail_transaksi.kode_aset, tb_detail_transaksi.nup, tb_detail_transaksi.nama_aset, tb_detail_transaksi.status, tb_detail_transaksi.id_kondisi, tb_detail_transaksi.flag_transaksi');
        // $this->db->join('tb_detail_transaksi', 'tb_detail_transaksi.id_transaksi = tb_master_transaksi.id', 'INNER');
        $this->db->join('tb_master_type_transaksi', 'tb_master_type_transaksi.id = tb_master_transaksi.tipe_transaksi', 'LEFT');
        $this->db->join('tb_master_area', 'tb_master_area.id = tb_master_transaksi.id_area', 'LEFT');
        $this->db->join('tb_master_gedung', 'tb_master_gedung.id = tb_master_transaksi.id_gedung', 'LEFT');
        $this->db->join('tb_master_ruangan', 'tb_master_ruangan.id = tb_master_transaksi.id_ruangan', 'LEFT');
        return $this;
    }

    public function filter_avaiable() {
        if (!$this->aauth->is_admin()) {}
        return $this;
    }

    public function count_all_content(){

        $this->db->from('tb_master_aset');
        $this->db->where('kode_tid IS NOT NULL');
        $this->db->where('status = 1');
        return $this->db->count_all_results();
        
    }

    public function get_all_aset($filter_data) {

        // if ($filter_data['id_area'] != '') {
        //     $this->db->where('a.id_area', $filter_data['id_area']);
        // }
        // if ($filter_data['id_gedung'] != '') {
        //     $this->db->where('a.id_gedung', $filter_data['id_gedung']);
        // }
        if ($filter_data['id_ruangan'] != '') {
            $this->db->where('a.lokasi_moving', $filter_data['id_ruangan']);
        }

        $this->db->select('a.*');
        $this->db->from('tb_master_aset a');
        $this->db->where('a.kode_tid IS NOT NULL');
        $this->db->where('a.status in (1,4)');
        return $this->db->get()->result();
    }

    public function get_all_search_aset($filter_data)
    {
        // Lakukan query database berdasarkan filter data
        $this->db->select('*');
        $this->db->where($filter_data);
        $query = $this->db->get('tb_detail_transaksi');
        $result = $query->result();
        return $result;
    }   

    public function get_content($limit, $start, $order, $dir, $select_all, $filter_data){

        if ($select_all == '1') {
            // $this->db->where('a.id_area', $filter_data['id_area']);
            // $this->db->where('a.id_gedung', $filter_data['id_gedung']);
            $this->db->where('a.lokasi_moving', $filter_data['id_ruangan']);
        } else {
            
            // if ($filter_data['id_area'] != '') {
            //     $this->db->where('a.id_area', $filter_data['id_area']);
            // }
            // if ($filter_data['id_gedung'] != '') {
            //     $this->db->where('a.id_gedung', $filter_data['id_gedung']);
            // }
            if ($filter_data['id_ruangan'] != '') {
                $this->db->where('a.lokasi_moving', $filter_data['id_ruangan']);
            }

        }

        $this->db->select('a.*, b.kode_epc');
        $this->db->from('tb_master_aset a');
        $this->db->join('tb_master_tag_rfid b', 'b.id_aset = a.id_aset', 'JOIN');
        $this->db->where('a.kode_tid IS NOT NULL');
        $this->db->where('a.status in (1,4)');
        $this->db->order_by($order, $dir);
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->result();
        
    }

    public function content_search($limit, $start, $search, $order, $dir, $select_all, $filter_data){
        $this->db->select('a.*, b.kode_epc');
        $this->db->from('tb_master_aset a');
        $this->db->join('tb_master_tag_rfid b', 'b.id_aset = a.id_aset', 'JOIN');
        $this->db->where('a.kode_tid IS NOT NULL');
        $this->db->where('a.status in (1,4)');
        $this->db->like('a.nama_aset', $search);
        $this->db->or_like('a.kode_aset', $search);
        $this->db->order_by($order, $dir);
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result();
    }

    public function content_search_count($search, $select_all, $filter_data){
        $this->db->from('tb_master_aset a');
        $this->db->join('tb_master_tag_rfid b', 'b.id_aset = a.id_aset', 'JOIN');
        $this->db->where('a.kode_tid IS NOT NULL');
        $this->db->where('a.status in (1,4)');
        $this->db->like('a.nama_aset', $search);
        $this->db->or_like('a.kode_aset', $search);
        return $this->db->count_all_results();
    }

    public function saveRegisterAset($save_data_master_transaksi, $save_data_detail_transaksi, $array_data_aset){

        // Mulai transaksi database
        // $this->db->trans_start();

        try {
            // Insert ke tabel master transaksi
            $this->db->insert('tb_master_transaksi', $save_data_master_transaksi);
            $query = $this->db->query("SELECT currval(pg_get_serial_sequence('tb_master_transaksi','id')) AS last_id");

            if ($query) {
                $row = $query->row();
                $id_transaksi = $row->last_id;
            } else {
                // Handle the error appropriately
                log_message('error', 'Failed to retrieve last insert ID from tb_master_transaksi');
                return false;
            }

            // Jika ada data linked aset dan tag
            if(!empty($array_data_aset)) {
                
                // Loop untuk setiap data linked
                foreach($array_data_aset as $data) {
                    
                    $data_detail = array(
                        'id_transaksi' => $id_transaksi,
                        // 'kode_transaksi' => '',
                        'nama_aset' => $data['nama_aset'],
                        'kode_tid' => $data['kode_tid'], 
                        'id_aset' => $data['id'],
                        'kode_aset' => $data['kode_aset'],
                        'nup' => $data['nup'],
                        'id_area' => $save_data_master_transaksi['id_area'],
                        'id_gedung' => $save_data_master_transaksi['id_gedung'],
                        'id_ruangan' => $save_data_master_transaksi['id_ruangan']
                    );

                    // echo '<pre>';
                    // print_r($data_detail);
                    // echo '</pre>';
                    // exit();
                    
                    // Insert ke tabel detail transaksi
                    $this->db->insert('tb_detail_transaksi', $data_detail);

                    // echo '<pre>';
                    // echo $this->db->last_query();
                    // echo '</pre>';
                    // exit();

                    // update field status, borrow, tipe_moving ke master aset
                    $this->db->where('id_aset', $data['id']);
                    $this->db->update('tb_master_aset', array(
                        'status' => 3,
                        'borrow' => 1,
                        'tipe_moving' => 1  // Aset ada izin moving
                    )); 
                }
            }

            // Commit transaksi jika semua berhasil
            // $this->db->trans_complete();
            
            // if ($this->db->trans_status() === FALSE) {
            //     // Rollback jika ada error
            //     $this->db->trans_rollback();
            //     return FALSE;
            // } else {
                return $id_transaksi;
            // }

        } catch(Exception $e) {
            // Log error message
            log_message('error', 'Error in saveRegisterAset: ' . $e->getMessage());
            
            // Rollback jika terjadi exception
            // $this->db->trans_rollback();
            return FALSE;
        }
    }

    function getTransaksiById($id){
        $this->db->select('tb_master_transaksi.*, tb_master_area.area as tb_master_area_area, tb_master_gedung.gedung as tb_master_gedung_gedung, tb_master_ruangan.ruangan as tb_master_ruangan_ruangan,
        tb_master_area2.area as tb_master_area_area2, tb_master_gedung2.gedung as tb_master_gedung_gedung2, tb_master_ruangan2.ruangan as tb_master_ruangan_ruangan2');
        $this->db->from('tb_master_transaksi');
        $this->db->join('tb_master_area', 'tb_master_area.id = tb_master_transaksi.id_area', 'left');   
        $this->db->join('tb_master_gedung', 'tb_master_gedung.id = tb_master_transaksi.id_gedung', 'left');
        $this->db->join('tb_master_ruangan', 'tb_master_ruangan.id = tb_master_transaksi.id_ruangan', 'left');
        $this->db->join('tb_master_area as tb_master_area2', 'tb_master_area2.id = tb_master_transaksi.id_area2', 'left');   
        $this->db->join('tb_master_gedung as tb_master_gedung2', 'tb_master_gedung2.id = tb_master_transaksi.id_gedung2', 'left');
        $this->db->join('tb_master_ruangan as tb_master_ruangan2', 'tb_master_ruangan2.id = tb_master_transaksi.id_ruangan2', 'left');
        $this->db->where('tb_master_transaksi.id', $id);
        return $this->db->get()->row();
    }

    function getDetailTransaksiById($id){
        $this->db->select('a.*, b.kode_epc');
        $this->db->from('tb_detail_transaksi a');
        $this->db->join('tb_master_tag_rfid b', 'b.id_aset = a.id_aset', 'JOIN');
        $this->db->where('id_transaksi', $id);
        return $this->db->get()->result();
    }

    function getPengaturanSistem(){
        $this->db->select('*');
        $this->db->from('pengaturan_sistem');
        return $this->db->get()->row();
    }

}

/* End of file Model_perbaikan.php */
/* Location: ./application/models/Model_perbaikan.php */