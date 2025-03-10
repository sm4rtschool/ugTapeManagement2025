<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Model_permission extends MY_Model {

	private $primary_key 	= 'id';
	private $table_name 	= 'aauth_perms';
	private $field_search 	= array('name', 'definition');

	public function __construct()
	{
		$config = array(
			'primary_key' 	=> $this->primary_key,
		 	'table_name' 	=> $this->table_name,
		 	'field_search' 	=> $this->field_search,
		 );

		parent::__construct($config);
	}

	// public function count_all($q = '', $field = '')
	// {
	// 	$iterasi = 1;
    //     $num = count($this->field_search);
    //     $where = NULL;
    //     $q = $this->scurity($q);
	// 	$field = $this->scurity($field);

    //     if (empty($field)) {
	//         foreach ($this->field_search as $field) {
	//             if ($iterasi == 1) {
	//                 $where .= "(" . $field . " LIKE '%" . $q . "%' ";
	//             } else if ($iterasi == $num) {
	//                 $where .= "OR " . $field . " LIKE '%" . $q . "%') ";
	//             } else {
	//                 $where .= "OR " . $field . " LIKE '%" . $q . "%' ";
	//             }
	//             $iterasi++;
	//         }
    //     } else {
    //     	$where .= "(" . $field . " LIKE '%" . $q . "%' )";
    //     }

    //     $this->db->where($where);
	// 	$query = $this->db->get($this->table_name);

	// 	return $query->num_rows();
	// }

	public function count_all($q = '', $field = '')
	{
		$iterasi = 1;
		$num = count($this->field_search);
		$where = NULL;
		$q = $this->scurity($q);
		$field = $this->scurity($field);

		if (empty($field)) {
			foreach ($this->field_search as $field) {
				// Cek jika field kemungkinan bertipe numerik
				if ($field == 'id' || $field == 'menu_type_id' || preg_match('/_id$/', $field)) {
					$searchField = "CAST(" . $field . " AS TEXT)";
				} else {
					$searchField = $field;
				}
				
				if ($iterasi == 1) {
					$where .= "(" . $searchField . " ILIKE '%" . $q . "%' ";
				} else if ($iterasi == $num) {
					$where .= "OR " . $searchField . " ILIKE '%" . $q . "%') ";
				} else {
					$where .= "OR " . $searchField . " ILIKE '%" . $q . "%' ";
				}
				$iterasi++;
			}
		} else {
			// Jika field tunggal dipilih
			if ($field == 'id' || $field == 'menu_type_id' || preg_match('/_id$/', $field)) {
				$field = "CAST(" . $field . " AS TEXT)";
			}
			$where .= "(" . $field . " ILIKE '%" . $q . "%' )";
		}

		// Untuk menghindari error ketika query gagal
		try {
			if ($where) {
				$this->db->where($where);
			}
			$query = $this->db->get($this->table_name);
			
			if (is_object($query)) {
				return $query->num_rows();
			} else {
				return 0;
			}
		} catch (Exception $e) {
			log_message('error', 'PostgreSQL error: ' . $e->getMessage());
			return 0;
		}
	}

	// public function get($q = '', $field = '', $limit = 0, $offset = 0)
	// {
	// 	$iterasi = 1;
    //     $num = count($this->field_search);
    //     $where = NULL;
    //     $q = $this->scurity($q);
	// 	$field = $this->scurity($field);

    //     if (empty($field)) {
	//         foreach ($this->field_search as $field) {
	//             if ($iterasi == 1) {
	//                 $where .= "(" . $field . " LIKE '%" . $q . "%' ";
	//             } else if ($iterasi == $num) {
	//                 $where .= "OR " . $field . " LIKE '%" . $q . "%') ";
	//             } else {
	//                 $where .= "OR " . $field . " LIKE '%" . $q . "%' ";
	//             }
	//             $iterasi++;
	//         }
    //     } else {
    //     	$where .= "(" . $field . " LIKE '%" . $q . "%' )";
    //     }

    //     $this->db->where($where);
    //     $this->db->limit($limit, $offset);
    //     $this->sortable();
	// 	$query = $this->db->get($this->table_name);

	// 	return $query->result();
	// }

	public function get($q = '', $field = '', $limit = 0, $offset = 0)
	{
		$iterasi = 1;
		$num = count($this->field_search);
		$where = NULL;
		$q = $this->scurity($q);
		$field = $this->scurity($field);

		if (empty($field)) {
			foreach ($this->field_search as $field) {
				// Cek jika field kemungkinan bertipe numerik
				if ($field == 'id' || $field == 'menu_type_id' || preg_match('/_id$/', $field)) {
					$searchField = "CAST(" . $field . " AS TEXT)";
				} else {
					$searchField = $field;
				}
				
				if ($iterasi == 1) {
					$where .= "(" . $searchField . " ILIKE '%" . $q . "%' ";
				} else if ($iterasi == $num) {
					$where .= "OR " . $searchField . " ILIKE '%" . $q . "%') ";
				} else {
					$where .= "OR " . $searchField . " ILIKE '%" . $q . "%' ";
				}
				$iterasi++;
			}
		} else {
			// Jika field tunggal dipilih
			if ($field == 'id' || $field == 'menu_type_id' || preg_match('/_id$/', $field)) {
				$field = "CAST(" . $field . " AS TEXT)";
			}
			$where .= "(" . $field . " ILIKE '%" . $q . "%' )";
		}

		// Untuk menghindari error ketika query gagal
		try {
			if ($where) {
				$this->db->where($where);
			}
			$this->db->limit($limit, $offset);
			$this->sortable();
			$query = $this->db->get($this->table_name);
			
			if (is_object($query)) {
				return $query->result();
			} else {
				// Log error dan kembalikan array kosong
				log_message('error', 'PostgreSQL query error on table: ' . $this->table_name);
				return array();
			}
		} catch (Exception $e) {
			log_message('error', 'PostgreSQL error: ' . $e->getMessage());
			return array();
		}
	}

}

/* End of file Model_permission.php */
/* Location: ./application/models/Model_permission.php */