<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_page extends MY_Model
{

	private $primary_key 	= 'id';
	private $table_name 	= 'page';
	private $field_search 	= ['title', 'type', 'link', 'created_at'];

	public function __construct()
	{
		$config = array(
			'primary_key' 	=> $this->primary_key,
			'table_name' 	=> $this->table_name,
			'field_search' 	=> $this->field_search,
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

		if (empty($field)) {
			foreach ($this->field_search as $field) {
				if ($iterasi == 1) {
					$where .= $field . " LIKE '%" . $q . "%' ";
				} else {
					$where .= "OR " . $field . " LIKE '%" . $q . "%' ";
				}
				$iterasi++;
			}

			$where = '(' . $where . ')';
		} else {
			$where .= "(" . $field . " LIKE '%" . $q . "%' )";
		}

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

		if (empty($field)) {
			foreach ($this->field_search as $field) {
				if ($iterasi == 1) {
					$where .= $field . " LIKE '%" . $q . "%' ";
				} else {
					$where .= "OR " . $field . " LIKE '%" . $q . "%' ";
				}
				$iterasi++;
			}

			$where = '(' . $where . ')';
		} else {
			$where .= "(" . $field . " LIKE '%" . $q . "%' )";
		}

		if (is_array($select_field) and count($select_field)) {
			$this->db->select($select_field);
		}

		$this->db->where($where);
		$this->db->limit($limit, $offset);
		$this->sortable();
		$query = $this->db->get($this->table_name);

		return $query->result();
	}

	public function get_page_by_slug($slug = null, $page_type = null)
	{
		$this->db->where('link', $slug);
		$this->db->where('type', $page_type);
		$query = $this->db->get($this->table_name);

		return $query->row();
	}
}

/* End of file Model_page.php */
/* Location: ./application/models/Model_page.php */