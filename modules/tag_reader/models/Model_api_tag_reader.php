<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_api_tag_reader extends MY_Model {

    private $primary_key = 'reader_id';
    private $table_name = 'tag_reader';
    private $field_search = ['reader_id', 'room_id', 'reader_name', 'setfor', 'reader_serialnumber', 'reader_type', 'reader_ip', 'reader_port', 'reader_com', 'reader_baudrate', 'reader_power', 'reader_interval', 'reader_mode', 'reader_updatedby', 'reader_updated', 'reader_createdby', 'reader_created', 'reader_family', 'connecting', 'reader_model', 'reader_identity', 'reader_antena', 'reader_angle', 'reader_gate'];

    public function __construct()
    {

        $config = array(
        'primary_key' => $this->primary_key,
        'table_name' => $this->table_name,
        'field_search' => $this->field_search,
        );

        parent::__construct($config);

    }

    public function count_all($q = null, $field = null)
    {
        $where = NULL;
        $q = $this->scurity($q);
        $field = $this->scurity($field);

        if (empty($field)) {
            $where_conditions = [];
            foreach ($this->field_search as $field) {
                // Handle both text and non-text fields in PostgreSQL
                $where_conditions[] = "CAST(" . $field . " AS TEXT) ILIKE '%" . $this->db->escape_like_str($q) . "%'";
            }

            if (!empty($where_conditions)) {
                $where = '(' . implode(' OR ', $where_conditions) . ')';
            }
        } else {
            // For a specific field, also use CAST for PostgreSQL compatibility
            $where = "(CAST(" . $field . " AS TEXT) ILIKE '%" . $this->db->escape_like_str($q) . "%')";
        }

        if ($where) {
            $this->db->where($where, NULL, FALSE);
        }
        
        $this->filter_query();

        try {
            $query = $this->db->get($this->table_name);
            
            if ($query === FALSE) {
                log_message('error', 'Database count query failed: ' . $this->db->error()['message']);
                return 0;
            }
            
            return $query->num_rows();
        } catch (Exception $e) {
            log_message('error', 'Exception in count_all method: ' . $e->getMessage());
            return 0;
        }
    }

    public function get($q = null, $field = null, $limit = 0, $offset = 0, $select_field = [])
    {
        $where = NULL;
        $q = $this->scurity($q);
        $field = $this->scurity($field);

        if (empty($field)) {
            $where_conditions = [];
            foreach ($this->field_search as $field) {
                // Handle both text and non-text fields in PostgreSQL
                if (strpos($field, '.') !== false) {
                    // Field with table reference
                    $where_conditions[] = "CAST(" . $field . " AS TEXT) ILIKE '%" . $this->db->escape_like_str($q) . "%'";
                } else {
                    // Field without table reference
                    $where_conditions[] = "CAST(" . $field . " AS TEXT) ILIKE '%" . $this->db->escape_like_str($q) . "%'";
                }
            }

            if (!empty($where_conditions)) {
                $where = '(' . implode(' OR ', $where_conditions) . ')';
            }
        } else {
            if (in_array($field, $select_field)) {
                // For a specific field, also use CAST for PostgreSQL compatibility
                $where = "(CAST(" . $field . " AS TEXT) ILIKE '%" . $this->db->escape_like_str($q) . "%')";
            }
        }

        if (is_array($select_field) && count($select_field)) {
            $this->db->select($select_field);
        }

        if ($where) {
            $this->db->where($where, NULL, FALSE);
        }
        
        $this->db->where('is_active', 1);
        $this->filter_query();

        // Apply limit only if positive
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        
        // Get sort parameters
        $sort_field = $this->input->get('sort_field') ? $this->input->get('sort_field') : $this->primary_key;
        $sort_order = $this->input->get('sort_order') ? $this->input->get('sort_order') : 'DESC';
        
        // Apply sorting
        $this->db->order_by($sort_field, $sort_order);
        
        // Execute query with error handling
        try {
            $query = $this->db->get($this->table_name);
            
            if ($query === FALSE) {
                log_message('error', 'Database query failed: ' . $this->db->error()['message']);
                return [];
            }
            
            return $query->result();
        } catch (Exception $e) {
            log_message('error', 'Exception in get method: ' . $e->getMessage());
            return [];
        }
    }

}

/* End of file Model_tag_reader.php */
/* Location: ./application/models/Model_tag_reader.php */