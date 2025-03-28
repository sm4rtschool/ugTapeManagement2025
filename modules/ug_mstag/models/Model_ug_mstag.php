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

}

/* End of file Model_ug_mstag.php */
/* Location: ./application/models/Model_ug_mstag.php */