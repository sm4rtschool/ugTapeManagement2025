<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_tag_reader extends MY_Model
{

    private $primary_key    = 'reader_id';
    private $table_name     = 'tag_reader';
    public $field_search   = ['room_id', 'room_id', 'reader_name', 'reader_serialnumber', 'reader_type', 'reader_ip', 'reader_port', 'reader_com', 'reader_mode', 'reader_family', 'connecting', 'reader_identity', 'alias_antenna', 'tb_master_ruangan.ruangan'];
    public $sort_option = ['reader_id', 'DESC'];

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
                $f_search = "tag_reader." . $field;

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
            $where .= "(" . "tag_reader." . $field . " ILIKE '%" . $q . "%' )";
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
    //             $f_search = "tag_reader." . $field;
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
    //         $where .= "(" . "tag_reader." . $field . " LIKE '%" . $q . "%' )";
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
                $f_search = "tag_reader." . $field;
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
            $where .= "(" . "tag_reader." . $field . " ILIKE '%" . $q . "%' )";
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
        echo $this->db->last_query();
        
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
        $this->db->join('tb_master_ruangan', 'tb_master_ruangan.id = tag_reader.room_id', 'LEFT');
        $this->db->select('tb_master_ruangan.ruangan,tag_reader.*,tb_master_ruangan.ruangan as tb_room_master_name_room,tb_master_ruangan.ruangan as name_room');
        return $this;
    }

    public function filter_avaiable()
    {

        if (!$this->aauth->is_admin()) {
        }

        return $this;
    }
}

/* End of file Model_tag_reader.php */
/* Location: ./application/models/Model_tag_reader.php */