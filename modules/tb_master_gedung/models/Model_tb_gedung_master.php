<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_tb_gedung_master extends MY_Model
{

    private $primary_key    = 'id';
    private $table_name     = 'tb_master_gedung';
    public $field_search   = ['ket_gedung', 'gedung', 'id_area'];
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
                $f_search = "tb_master_gedung." . $field;

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
            $f_search = "tb_master_gedung." . $field;
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
                $f_search = "tb_master_gedung." . $field;
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
            $f_search = "tb_master_gedung." . $field;
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

    public function join_avaiable()
    {
        $this->db->join('tb_master_area', 'tb_master_area.id = tb_master_gedung.id_area', 'LEFT');

        $this->db->select('tb_master_area.area,tb_master_area.ket_area,tb_master_gedung.*,tb_master_area.area as tb_master_area_area,tb_master_area.area as area');


        return $this;
    }

    public function filter_avaiable()
    {

        if (!$this->aauth->is_admin()) {
        }

        return $this;
    }

    public function get_detail_gedung($id)
    {
        $query = $this->db->query(
            "SELECT a.*, s.area, s.ket_area FROM tb_master_gedung a JOIN tb_master_area s ON s.id = a.id_area WHERE a.id = $id"
        );

        return $query->result();
    }

    public function update_gedung($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tb_master_gedung', $data);
    }
}

/* End of file Model_tb_gedung_master.php */
/* Location: ./application/models/Model_tb_gedung_master.php */