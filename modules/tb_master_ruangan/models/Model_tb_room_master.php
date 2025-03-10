<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_tb_room_master extends MY_Model
{

    private $primary_key    = 'id';
    private $table_name     = 'tb_master_ruangan';
    public $field_search   = ['id_gedung', 'id', 'id_area', 'ruangan', 'ket_ruangan', 'tb_master_area.area', 'tb_master_gedung.gedung'];
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

    // public function count_all($q = null, $field = null)
    // {
    //     $iterasi = 1;
    //     $num = count($this->field_search);
    //     $where = NULL;
    //     $q = $this->scurity($q);
    //     $field = $this->scurity($field);
    //     $field = in_array($field, $this->field_search) ? $field : "";


    //     if (empty($field)) {
    //         foreach ($this->field_search as $field) {
    //             $f_search = "tb_master_ruangan." . $field;

    //             if (strpos($field, '.')) {
    //                 $f_search = $field;
    //             }
    //             if ($iterasi == 1) {
    //                 $where .=  $f_search . " LIKE '%" . $q . "%' ";
    //             } else {
    //                 $where .= "OR " .  $f_search . " LIKE '%" . $q . "%' ";
    //             }
    //             $iterasi++;
    //         }

    //         $where = '(' . $where . ')';
    //     } else {
    //         $where .= "(" . "tb_master_ruangan." . $field . " LIKE '%" . $q . "%' )";
    //     }

    //     $this->join_avaiable()->filter_avaiable();
    //     $this->db->where($where);
    //     $query = $this->db->get($this->table_name);

    //     return $query->num_rows();
    // }

    // public function get($q = null, $field = null, $limit = 0, $offset = 0, $select_field = [])
    // {
    //     $iterasi = 1;
    //     $num = count($this->field_search);
    //     $where = NULL;
    //     $q = $this->scurity($q);
    //     $field = $this->scurity($field);
    //     $field = in_array($field, $this->field_search) ? $field : "";


    //     if (empty($field)) {
    //         foreach ($this->field_search as $field) {
    //             $f_search = "tb_master_ruangan." . $field;
    //             if (strpos($field, '.')) {
    //                 $f_search = $field;
    //             }

    //             if ($iterasi == 1) {
    //                 $where .= $f_search . " LIKE '%" . $q . "%' ";
    //             } else {
    //                 $where .= "OR " . $f_search . " LIKE '%" . $q . "%' ";
    //             }
    //             $iterasi++;
    //         }

    //         $where = '(' . $where . ')';
    //     } else {
    //         $where .= "(" . "tb_master_ruangan." . $field . " LIKE '%" . $q . "%' )";
    //     }

    //     if (is_array($select_field) and count($select_field)) {
    //         $this->db->select($select_field);
    //     }

    //     $this->join_avaiable()->filter_avaiable();
    //     $this->db->where($where);
    //     $this->db->limit($limit, $offset);

    //     $this->sortable();

    //     $query = $this->db->get($this->table_name);

    //     return $query->result();
    // }

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
                $f_search = "tb_master_ruangan." . $field;

                if (strpos($field, '.')) {
                    $f_search = $field;
                }
                if ($iterasi == 1) {
                    $where .=  $f_search . " ILIKE '%" . $q . "%' ";
                } else {
                    $where .= "OR " .  $f_search . " ILIKE '%" . $q . "%' ";
                }
                $iterasi++;
            }

            $where = '(' . $where . ')';
        } else {
            $where .= "(" . "tb_master_ruangan." . $field . " ILIKE '%" . $q . "%' )";
        }

        $this->join_avaiable()->filter_avaiable();
        $this->db->where($where);
        
        // Add error logging
        $this->db->save_queries = TRUE;
        $query = $this->db->get($this->table_name);
        
        // Check if query failed
        if ($query === FALSE) {
            // Log the error
            log_message('error', 'Database error: ' . $this->db->error()['message'] . ' - SQL: ' . $this->db->last_query());
            return 0; // Return 0 instead of causing an error
        }

        return $query->num_rows();
    }

    public function get($q = null, $field = null, $limit = 0, $offset = 0, $select_field = [])
    {
        $iterasi = 1;
        $num = count($this->field_search);
        $where = NULL;
        $q = $this->scurity($q);
        $field = $this->scurity($field);
        $field = in_array($field, $this->field_search) ? $field : "";

        if (empty($field)) {
            foreach ($this->field_search as $field) {
                $f_search = "tb_master_ruangan." . $field;
                if (strpos($field, '.')) {
                    $f_search = $field;
                }

                if ($iterasi == 1) {
                    $where .= $f_search . " ILIKE '%" . $q . "%' ";
                } else {
                    $where .= "OR " . $f_search . " ILIKE '%" . $q . "%' ";
                }
                $iterasi++;
            }

            $where = '(' . $where . ')';
        } else {
            $where .= "(" . "tb_master_ruangan." . $field . " ILIKE '%" . $q . "%' )";
        }

        if (is_array($select_field) and count($select_field)) {
            $this->db->select($select_field);
        }

        $this->join_avaiable()->filter_avaiable();
        $this->db->where($where);
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
        $this->db->join('tb_master_gedung', 'tb_master_gedung.id = tb_master_ruangan.id_gedung', 'LEFT');
        $this->db->join('tb_master_area', 'tb_master_area.id = tb_master_ruangan.id_area', 'LEFT');
        $this->db->select('tb_master_area.area,tb_master_gedung.gedung,tb_master_ruangan.*,tb_master_gedung.gedung as tb_master_gedung_gedung,tb_master_gedung.gedung as gedung');


        return $this;
    }

    public function filter_avaiable()
    {

        if (!$this->aauth->is_admin()) {
        }

        return $this;
    }

    public function get_detail_ruang($id)
    {
        $query = $this->db->query(
            "SELECT r.*, s.area, s.ket_area, g.gedung, g.ket_gedung FROM tb_master_ruangan r JOIN tb_master_area s ON s.id = r.id_area JOIN tb_master_gedung g ON g.id = r.id_gedung WHERE r.id = $id"
        );

        return $query->result();
    }

    public function update_ruang($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tb_master_ruangan', $data);
    }
}

/* End of file Model_tb_room_master.php */
/* Location: ./application/models/Model_tb_room_master.php */