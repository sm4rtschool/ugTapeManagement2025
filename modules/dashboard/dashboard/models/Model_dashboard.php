<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_dashboard extends MY_Model
{

	private $primary_key 	= 'id';
	private $table_name 	= 'dashboard';
	private $table_kategori 	= 'tb_category_aset';
	private $field_search 	= ['title', 'slug', 'created_at'];

	public function __construct()
	{
		$config = array(
			'primary_key' 	=> $this->primary_key,
			'table_name' 	=> $this->table_name,
			'field_search' 	=> $this->field_search,
		);
		parent::__construct($config);
	}

	public function count_all($q = null, $field = null, $category = null, $tag = null)
	{
		$iterasi = 1;
		$num = count($this->field_search);
		$where = NULL;
		$q = $this->scurity($q);
		$field = $this->scurity($field);

		if (empty($field)) {
			foreach ($this->field_search as $field) {
				if ($iterasi == 1) {
					$where .= "dashboard." . $field . " LIKE '%" . $q . "%' ";
				} else {
					$where .= "OR " . "dashboard." . $field . " LIKE '%" . $q . "%' ";
				}
				$iterasi++;
			}

			$where = '(' . $where . ')';
		} else {
			$where .= "(" . "dashboard." . $field . " LIKE '%" . $q . "%' )";
		}

		if ($tag) {
			$this->db->where('tags LIKE "%' . $tag . '%"');
		}

		if ($category) {
			$this->db->where('category', $category);
		}
		$this->join_avaiable()->filter_avaiable();
		$this->db->where($where);
		$query = $this->db->get($this->table_name);

		return $query->num_rows();
	}

	public function get($q = null, $field = null, $limit = 0, $offset = 0, $category = null, $tag = null)
	{
		$iterasi = 1;
		$num = count($this->field_search);
		$where = NULL;
		$q = $this->scurity($q);
		$field = $this->scurity($field);

		if (empty($field)) {
			foreach ($this->field_search as $field) {
				if ($iterasi == 1) {
					$where .= "dashboard." . $field . " LIKE '%" . $q . "%' ";
				} else {
					$where .= "OR " . "dashboard." . $field . " LIKE '%" . $q . "%' ";
				}
				$iterasi++;
			}

			$where = '(' . $where . ')';
		} else {
			$where .= "(" . "dashboard." . $field . " LIKE '%" . $q . "%' )";
		}
		if ($tag) {
			$this->db->where('tags LIKE "%' . $tag . '%"');
		}

		if ($category) {
			$this->db->where('category', $category);
		}
		$this->join_avaiable()->filter_avaiable();
		$this->db->where($where);
		$this->db->limit($limit, $offset);
		$this->sortable();
		$query = $this->db->get($this->table_name);

		if ($query) {
			return $query->result();
		} else {
			return []; // Mengembalikan array kosong jika query gagal
		}
	}

	public function join_avaiable()
	{
		return $this;
	}
	public function filter_avaiable()
	{
		return $this;
	}
	public function find_by_slug($slug)
	{
		return $this->db->get_where($this->table_name, ['slug' => $slug])->row();
	}

	public function getKategori($q = null, $field = null, $limit = 0, $offset = 0, $select_field = [])
	{
		$query = $this->db->get($this->table_kategori);

		return $query->result();
	}
}

/* End of file Model_dashboard.php */
/* Location: ./application/models/Model_dashboard.php */