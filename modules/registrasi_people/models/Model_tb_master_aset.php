<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_tb_master_aset extends MY_Model {

    private $primary_key    = 'id';
    private $table_name     = 'tb_master_pegawai';
    public $field_search   = ['kode_tid', 'nip', 'nama'];
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
    //             $f_search = "tb_master_aset.".$field;

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

    //         $where = '('.$where.')';
    //     } else {
    //         $where .= "(" . "tb_master_aset.".$field . " LIKE '%" . $q . "%' )";
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
    //             $f_search = "tb_master_pegawai.".$field;
    //             if (strpos($field, '.')) {
    //                 $f_search = $field;
    //             }

    //             if ($iterasi == 1) {
    //                 $where .= $f_search . " LIKE '%" . $q . "%' ";
    //             } else {
    //                 $where .= "OR " .$f_search . " LIKE '%" . $q . "%' ";
    //             }
    //             $iterasi++;
    //         }

    //         $where = '('.$where.')';
    //     } else {
    //         $where .= "(" . "tb_master_pegawai.".$field . " LIKE '%" . $q . "%' )";
    //     }

    //     if (is_array($select_field) AND count($select_field)) {
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
                $f_search = "tb_master_aset.".$field;

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

            $where = '('.$where.')';
        } else {
            $where .= "(" . "tb_master_aset.".$field . " ILIKE '%" . $q . "%' )";
        }

        $this->join_avaiable()->filter_avaiable();
        $this->db->where($where);
        $query = $this->db->get($this->table_name);

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
                $f_search = "tb_master_pegawai.".$field;
                if (strpos($field, '.')) {
                    $f_search = $field;
                }

                if ($iterasi == 1) {
                    $where .= $f_search . " ILIKE '%" . $q . "%' ";
                } else {
                    $where .= "OR " .$f_search . " ILIKE '%" . $q . "%' ";
                }
                $iterasi++;
            }

            $where = '('.$where.')';
        } else {
            $where .= "(" . "tb_master_pegawai.".$field . " ILIKE '%" . $q . "%' )";
        }

        if (is_array($select_field) AND count($select_field)) {
            $this->db->select($select_field);
        }
        
        $this->join_avaiable()->filter_avaiable();
        $this->db->where($where);
        
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        
        $this->sortable();
        
        $query = $this->db->get($this->table_name);

        return $query->result();
    }

    public function join_avaiable() {
        $this->db->join('tb_master_kategori', 'tb_master_kategori.id = tb_master_aset.kategori', 'LEFT');
        $this->db->select('tb_master_aset.*');
        return $this;
    }

    public function filter_avaiable() {

        if (!$this->aauth->is_admin()) {

        }

        return $this;
    }

    public function get_pegawai()
    {
        $query = $this->db->query(
            "SELECT * FROM tb_master_pegawai WHERE kode_tid_pegawai IS NULL ORDER BY kode_tid_pegawai ASC LIMIT 500 OFFSET 0"
        );

        return $query->result();
    }

}

/* End of file Model_tb_master_transaksi.php */
/* Location: ./application/models/Model_tb_master_transaksi.php */