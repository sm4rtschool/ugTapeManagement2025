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
                $f_search = "tb_master_aset." . $field;

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
            $where .= "(" . "tb_master_aset." . $field . " ILIKE '%" . $q . "%' )";
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
                $f_search = "tb_master_aset." . $field;
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
            $where .= "(" . "tb_master_aset." . $field . " ILIKE '%" . $q . "%' )";
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

    public function getDataLaporan($r, $tglawal, $tglakhir)
    {

        $query = $this->db->query(
            $r === '99' ?
                "SELECT a.nama_aset, a.nup, a.kode_aset,a.kode_tid,x.kategori,  a.merk, a.tipe,a.nilai_perolehan, DATE(a.tgl_perolehan) AS tglperolehan,DATE(a.tgl_inventarisasi) AS tglsensus, s.status, r.ruangan, k.kondisi FROM tb_master_aset a 
JOIN tb_master_status s ON s.id = a.status
JOIN tb_master_ruangan r ON r.id = a.lokasi_terakhir
JOIN tb_master_kondisi k ON k.id = a.kondisi 
JOIN tb_master_kategori x ON x.id = a.kategori 
WHERE DATE(a.tgl_inventarisasi) BETWEEN '$tglawal' AND '$tglakhir'" : "SELECT a.nama_aset, a.nup, a.kode_aset,a.kode_tid,x.kategori,  a.merk, a.tipe,a.nilai_perolehan, DATE(a.tgl_perolehan) AS tglperolehan,DATE(a.tgl_inventarisasi) AS tglsensus, s.status, r.ruangan, k.kondisi FROM tb_master_aset a 
JOIN tb_master_status s ON s.id = a.status
JOIN tb_master_ruangan r ON r.id = a.lokasi_terakhir
JOIN tb_master_kondisi k ON k.id = a.kondisi 
JOIN tb_master_kategori x ON x.id = a.kategori 
WHERE a.lokasi_terakhir = $r AND DATE(a.tgl_inventarisasi) BETWEEN '$tglawal' AND '$tglakhir'"

        );

        return $query->result();
    }

    public function getDataTransaksi($tipe, $tglawal, $tglakhir)
    {
        // Menggunakan Active Record untuk query
        $this->db->select("
            d.id, 
            d.kode_aset, 
            d.nup, 
            m.ket_transaksi2, 
            m.ket_transaksi, 
            m.ket_transaksi3, 
            r.ruangan AS ruangawal, 
            rt.ruangan AS ruangtujuan, 
            m.status_transaksi AS statusnya, 
            MIN(m.id) AS id, 
            STRING_AGG(d.kode_tid, ' ') AS rfid, 
            CONCAT(CHR(10), STRING_AGG(CONCAT(CHR(10), d.nama_aset, CHR(10)), '') , CHR(10)) AS asetnya, 
            STRING_AGG(t.tipe_transaksi, ' ') AS tipe, 
            STRING_AGG(TO_CHAR(m.tgl_input, 'DD/MM/YYYY'), ' ') AS tgl_trans, 
            STRING_AGG(TO_CHAR(m.tgl_input, 'HH24:MI:SS'), ' ') AS time_trans, 
            STRING_AGG(TO_CHAR(m.tgl_akhir_transaksi, 'DD/MM/YYYY'), ' ') AS tgl_akhir, 
            STRING_AGG(TO_CHAR(m.tgl_akhir_transaksi, 'HH24:MI:SS'), ' ') AS waktu_akhir
        ");
        $this->db->from("tb_detail_transaksi d");
        $this->db->join("tb_master_transaksi m", "d.id_transaksi = m.id");
        $this->db->join("tb_master_type_transaksi t", "t.id = m.tipe_transaksi");
        $this->db->join("tb_master_ruangan r", "r.id = m.id_ruangan", "left");
        $this->db->join("tb_master_ruangan rt", "rt.id = m.id_ruangan2", "left");

        // Gunakan kondisi untuk filtering
        $this->db->where("DATE(m.tgl_input) >=", $tglawal);
        $this->db->where("DATE(m.tgl_input) <=", $tglakhir);

        if ($tipe !== '99') {
            $this->db->where("m.tipe_transaksi", $tipe);
        }

        $this->db->group_by("d.id_transaksi, d.id, d.kode_aset, d.nup, m.ket_transaksi2, m.ket_transaksi, m.ket_transaksi3, r.ruangan, rt.ruangan, m.status_transaksi");

        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->result();
    }

    public function get_detail_area($id)
    {
        $query = $this->db->query(
            "SELECT r.ruangan,s.area, s.ket_area, g.gedung, g.ket_gedung FROM tb_master_ruangan r JOIN tb_master_area s ON s.id = r.id_area JOIN tb_master_gedung g ON g.id = r.id_gedung WHERE r.id = $id"
        );

        return $query->result();
    }
}

/* End of file Model_tag_reader.php */
/* Location: ./application/models/Model_tag_reader.php */