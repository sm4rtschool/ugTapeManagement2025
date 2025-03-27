<?php
defined('BASEPATH') or exit('No direct script access allowed');


class MY_Model extends CI_Model
{

    private $primary_key = 'id';
    private $table_name = 'table';
    private $field_search = [];
    public $sort_option = [];

    public function __construct($config = array())
    {
        parent::__construct();

        foreach ($config as $key => $val) {
            if (isset($this->$key))
                $this->$key = $val;
        }

        $this->load->database();
    }

    // public function remove($id = NULL)
    // {
    //     $this->db->where($this->primary_key, $id);
    //     return $this->db->delete($this->table_name);
    // }

    public function remove($id)
    {
        try {
            $this->db->where($this->primary_key, $id);
            $delete = $this->db->delete($this->table_name);
            // echo $this->db->last_query();
            // exit;
            
            // return $delete ? true : false;

            if ($delete) {
                // Operasi delete berhasil
                return true;
            } else {
                return false;
                // Operasi delete gagal
                // Bisa cek error dengan: $this->db->error()
            }

        } catch (Exception $e) {
            log_message('error', 'Error saat menghapus data: ' . $e->getMessage());
            return false;
        }
    }

    public function change($id = NULL, $data = array())
    {
        $this->db->where($this->primary_key, $id);
        $this->db->update($this->table_name, $data);

        return $this->db->affected_rows();
    }

    // public function find($id = NULL, $select_field = [])
    // {
    //     if (is_array($select_field) and count($select_field)) {
    //         $this->db->select($select_field);
    //     }

    //     $this->db->where("" . $this->table_name . '.' . $this->primary_key, $id);
    //     $query = $this->db->get($this->table_name);

    //     if ($query->num_rows() > 0) {
    //         return $query->row();
    //     } else {
    //         return FALSE;
    //     }
    // }

    public function find($id = NULL, $select_field = [])
    {
        if (is_array($select_field) && count($select_field)) {
            $this->db->select($select_field);
        }

        $this->db->where($this->table_name . '.' . $this->primary_key, $id);
        $query = $this->db->get($this->table_name);
        
        // Periksa apakah $query adalah object (bukan boolean FALSE)
        if ($query && $query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    public function find_all()
    {
        $this->db->order_by($this->primary_key, 'DESC');
        $query = $this->db->get($this->table_name);

        return $query->result();
    }

    // public function store($data = array())
    // {
    //     $this->db->insert($this->table_name, $data);
    //     return $this->db->insert_id();
    // }

    public function store($data = array())
    {
        
        // echo '<pre>';
        // print_r($this->table_name);
        // echo '</pre>';
        // exit;
        
        // Lakukan insert
        $this->db->insert($this->table_name, $data);
        // echo $this->db->last_query();
        // Ambil ID terakhir yang diinsert
        $query = $this->db->query("SELECT CURRVAL(pg_get_serial_sequence('$this->table_name', 'id')) as last_id");
        // echo $this->db->last_query();

        if ($query) {
            $row = $query->row();
            $id_transaksi = $row->last_id;
            return $id_transaksi;
        } else {
            return 0;
        }
        
        // return null;
    }

    public function get_all_data($table = '')
    {
        $query = $this->db->get($table);

        return $query->result();
    }


    public function get_single($where)
    {
        $query = $this->db->get_where($this->table_name, $where);

        return $query->row();
    }

    public function scurity($input)
    {
        return mysqli_real_escape_string($this->db->conn_id, (string)$input);
    }

    public function generate_url($field, $text = null, $except = null)
    {
        $url = url_title($text);
        if ($except) {
            $this->db->where($this->primary_key . " != " . $except);
        }
        $this->db->order_by($this->primary_key, 'DESC');
        $data = $this->db->get_where($this->table_name, [$field => $url])->row();

        if ($data) {
            return $url . ($data->id += 1);
        }

        return $url;
    }

    // public function export($table, $subject = 'file', $field_search = [])
    // {
    //     // Improve security and use prepared statements
    //     $q = $this->security->xss_clean($this->input->get('q'));
    //     $field = $this->security->xss_clean($this->input->get('f'));

    //     // Prepare search conditions
    //     $whereConditions = [];

    //     if (empty($field)) {
    //         // Dynamic search across multiple fields
    //         foreach ($field_search as $searchField) {
    //             $f_search = strpos($searchField, '.') !== false ? $searchField : $table . '.' . $searchField;
    //             $whereConditions[] = $f_search . " ILIKE '%' || :search_term || '%'";
    //         }
    //     } else {
    //         // Search in a specific field
    //         $whereConditions[] = $table . "." . $field . " ILIKE '%' || :search_term || '%'";
    //     }

    //     // Prepare the base query
    //     $this->db->select('*')
    //             ->from($table);

    //     // Apply search conditions if query exists
    //     if (!empty($q)) {
    //         $this->db->where(implode(' OR ', $whereConditions), ['search_term' => $q]);
    //     }

    //     // Apply additional joins and filters if methods exist
    //     if (method_exists($this, 'join_available') && method_exists($this, 'filter_available')) {
    //         $this->join_available()->filter_available();
    //     }

    //     // Sort if needed
    //     $this->sortable();

    //     // Execute query
    //     $result = $this->db->get();
    //     $resultData = $result->result_array();

    //     // If no results, exit
    //     if (empty($resultData)) {
    //         show_error('No data found for export');
    //     }

    //     // Get fields
    //     $fields = array_keys($resultData[0]);

    //     // Load PhpSpreadsheet instead of PHPExcel (recommended)
    //     $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     // Set up column generation (similar to original method)
    //     $alphabet = 'ABCDEFGHIJKLMOPQRSTUVWXYZ';
    //     $alphabet_arr = str_split($alphabet);
    //     $column = [];

    //     foreach ($alphabet_arr as $alpha) {
    //         $column[] = $alpha;
    //     }

    //     foreach ($alphabet_arr as $alpha) {
    //         foreach ($alphabet_arr as $alpha2) {
    //             $column[] = $alpha . $alpha2;
    //         }
    //     }

    //     foreach ($alphabet_arr as $alpha) {
    //         foreach ($alphabet_arr as $alpha2) {
    //             foreach ($alphabet_arr as $alpha3) {
    //                 $column[] = $alpha . $alpha2 . $alpha3;
    //             }
    //         }
    //     }

    //     // Set column widths
    //     foreach ($column as $col) {
    //         $sheet->getColumnDimension($col)->setWidth(20);
    //     }

    //     $col_total = $column[count($fields) - 1];

    //     // Styling header row
    //     $headerStyle = [
    //         'fill' => [
    //             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //             'startColor' => ['rgb' => 'DA3232']
    //         ],
    //         'alignment' => [
    //             'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    //             'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    //         ],
    //         'font' => [
    //             'color' => ['rgb' => 'FFFFFF']
    //         ]
    //     ];

    //     $sheet->getStyle('A1:' . $col_total . '1')->applyFromArray($headerStyle);
    //     $sheet->getRowDimension(1)->setRowHeight(40);
    //     $sheet->getStyle('A1:' . $col_total . '1')->getAlignment()->setWrapText(true);

    //     // Write headers
    //     $col = 0;
    //     foreach ($fields as $field) {
    //         $sheet->setCellValueByColumnAndRow($col + 1, 1, ucwords(str_replace('_', ' ', $field)));
    //         $col++;
    //     }

    //     // Write data
    //     $row = 2;
    //     foreach ($resultData as $data) {
    //         $col = 0;
    //         foreach ($fields as $field) {
    //             $sheet->setCellValueExplicitByColumnAndRow(
    //                 $col + 1, 
    //                 $row, 
    //                 (string)$data[$field], 
    //                 \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
    //             );
    //             $col++;
    //         }
    //         $row++;
    //     }

    //     // Add borders
    //     $borderStyle = [
    //         'borders' => [
    //             'allBorders' => [
    //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
    //             ]
    //         ]
    //     ];
    //     $sheet->getStyle('A1:' . $col_total . ($row - 1))->applyFromArray($borderStyle);

    //     // Set sheet title
    //     $sheet->setTitle(ucwords($subject));

    //     // Output Excel file
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment;filename="' . ucwords($subject) . '-' . date('Y-m-d') . '.xlsx"');
    //     header('Cache-Control: max-age=0');
    //     header('Cache-Control: max-age=1');
    //     header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    //     header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    //     header('Cache-Control: cache, must-revalidate');
    //     header('Pragma: public');

    //     // Use PhpSpreadsheet writer
    //     $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    //     $writer->save('php://output');
    //     exit;
    // }

    public function export($table, $subject = 'file', $field_search = [])
    {
        $iterasi = 1;
        $where = NULL;
        $q = $this->scurity($this->input->get('q'));
        $field = $this->scurity($this->input->get('f'));
        if (empty($field)) {
            foreach ($field_search as $field) {
                $f_search = $table . '.' . $field;

                if (strpos($field, '.')) {
                    $f_search = $field;
                }

                if ($iterasi == 1) {
                    $where .= $f_search . " LIKE '%" . $q . "%' ";
                } else {
                    $where .= "OR " . $f_search . " LIKE '%" . $q . "%' ";
                }
                $iterasi++;
            }

            $where = '(' . $where . ')';
        } else {
            $where .= "(" . $table . "." . $field . " LIKE '%" . $q . "%' )";
        }

        $this->sortable();
        if (method_exists($this, 'join_avaiable') && method_exists($this, 'filter_avaiable')) {
            $this->join_avaiable()->filter_avaiable();
        }
        if (!empty($q)) {
            $this->db->where($where);
        }

        $this->load->library('excel');

        $result = $this->db->get($table);

        $this->excel->setActiveSheetIndex(0);

        $fields = $result->list_fields();

        $fields = array_unique($fields);

        $alphabet = 'ABCDEFGHIJKLMOPQRSTUVWXYZ';
        $alphabet_arr = str_split($alphabet);
        $column = [];

        foreach ($alphabet_arr as $alpha) {
            $column[] =  $alpha;
        }

        foreach ($alphabet_arr as $alpha) {
            foreach ($alphabet_arr as $alpha2) {
                $column[] =  $alpha . $alpha2;
            }
        }
        foreach ($alphabet_arr as $alpha) {
            foreach ($alphabet_arr as $alpha2) {
                foreach ($alphabet_arr as $alpha3) {
                    $column[] =  $alpha . $alpha2 . $alpha3;
                }
            }
        }

        foreach ($column as $col) {
            $this->excel->getActiveSheet()->getColumnDimension($col)->setWidth(20);
        }

        $col_total = $column[count($fields) - 1];

        //styling
        $this->excel->getActiveSheet()->getStyle('A1:' . $col_total . '1')->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'DA3232')
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                )
            )
        );

        $phpColor = new PHPExcel_Style_Color();
        $phpColor->setRGB('FFFFFF');

        $this->excel->getActiveSheet()->getStyle('A1:' . $col_total . '1')->getFont()->setColor($phpColor);

        $this->excel->getActiveSheet()->getRowDimension(1)->setRowHeight(40);

        $this->excel->getActiveSheet()->getStyle('A1:' . $col_total . '1')
            ->getAlignment()->setWrapText(true);

        $col = 0;
        foreach ($fields as $field) {

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, ucwords(str_replace('_', ' ', $field)));
            $col++;
        }

        $row = 2;
        foreach ($result->result() as $data) {
            $col = 0;
            foreach ($fields as $field) {
                $this->excel->getActiveSheet()->setCellValueExplicit($column[$col] . $row, $data->$field, PHPExcel_Cell_DataType::TYPE_STRING);
                //$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, '' . $data->$field);
                $col++;
            }

            $row++;
        }

        //set border
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $this->excel->getActiveSheet()->getStyle('A1:' . $col_total . '' . $row)->applyFromArray($styleArray);

        $this->excel->getActiveSheet()->setTitle(ucwords($subject));

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . ucwords($subject) . '-' . date('Y-m-d') . '.xls');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function pdf($table, $title)
    {
        $this->load->library('HtmlPdf');

        $iterasi = 1;
        $where = NULL;
        $q = $this->scurity($this->input->get('q'));
        $field = $this->scurity($this->input->get('f'));
        if (empty($field)) {
            foreach ($this->field_search as $field) {
                $f_search = $table . '.' . $field;

                if (strpos($field, '.')) {
                    $f_search = $field;
                }
                if ($iterasi == 1) {
                    $where .= $f_search . " LIKE '%" . $q . "%' ";
                } else {
                    $where .= "OR " . $f_search . " LIKE '%" . $q . "%' ";
                }
                $iterasi++;
            }

            $where = '(' . $where . ')';
        } else {
            $where .= "(" . $table . "." . $field . " LIKE '%" . $q . "%' )";
        }

        $this->sortable();

        if (method_exists($this, 'join_avaiable') && method_exists($this, 'filter_avaiable')) {
            $this->join_avaiable()->filter_avaiable();
        }
        if (!empty($q)) {
            $this->db->where($where);
        }


        $config = array(
            'orientation' => 'l',
            'format' => 'a4',
            'marges' => array(5, 5, 5, 5)
        );

        $this->pdf = new HtmlPdf($config);



        $result = $this->db->get($table);
        $fields = $result->list_fields();
        $fields = array_unique($fields);

        $content = $this->pdf->loadHtmlPdf('core_template/pdf/pdf', [
            'results' => $result->result(),
            'fields' => $fields,
            'title' => $title
        ], TRUE);


        $this->pdf->initialize($config);
        $this->pdf->pdf->SetDisplayMode('fullpage');
        $this->pdf->writeHTML($content);
        $this->pdf->Output($table . '.pdf', 'H');
    }

    // public function pdf($table, $title)
    // {
    //     // Load PDF library (you'll need to ensure a compatible PDF library for PostgreSQL)
    //     $this->load->library('HtmlPdf');

    //     // Prepare search conditions
    //     $q = $this->security->xss_clean($this->input->get('q'));
    //     $field = $this->security->xss_clean($this->input->get('f'));
        
    //     $whereConditions = [];

    //     if (empty($field)) {
    //         // Build dynamic search across multiple fields
    //         foreach ($this->field_search as $searchField) {
    //             $f_search = strpos($searchField, '.') !== false ? $searchField : $table . '.' . $searchField;
    //             $whereConditions[] = $f_search . " ILIKE '%' || :search_term || '%'";
    //         }
    //     } else {
    //         // Search in a specific field
    //         $whereConditions[] = $table . "." . $field . " ILIKE '%' || :search_term || '%'";
    //     }

    //     // Prepare the base query
    //     $this->db->select('*')
    //             ->from($table);

    //     // Apply search conditions if query exists
    //     if (!empty($q)) {
    //         $this->db->where(implode(' OR ', $whereConditions), ['search_term' => $q]);
    //     }

    //     // Apply additional joins and filters if methods exist
    //     if (method_exists($this, 'join_available') && method_exists($this, 'filter_available')) {
    //         $this->join_available()->filter_available();
    //     }

    //     // Sort if needed
    //     $this->sortable();

    //     // Execute query
    //     $result = $this->db->get();
    //     $resultData = $result->result_array();
    //     $fields = array_keys($resultData[0] ?? []);

    //     // PDF Configuration
    //     $config = [
    //         'orientation' => 'landscape',
    //         'format' => 'A4',
    //         'margins' => [5, 5, 5, 5]
    //     ];

    //     // Initialize PDF
    //     $this->pdf = new HtmlPdf($config);

    //     // Load PDF content
    //     $content = $this->pdf->loadHtmlPdf('core_template/pdf/pdf', [
    //         'results' => $resultData,
    //         'fields' => $fields,
    //         'title' => $title
    //     ], true);

    //     // Render PDF
    //     $this->pdf->initialize($config);
    //     $this->pdf->pdf->SetDisplayMode('fullpage');
    //     $this->pdf->writeHTML($content);
    //     $this->pdf->Output($table . '.pdf', 'I');
    // }

    // public function generate_id($suffix = null)
    // {
    //     $format = $suffix . (new DateTime)->format('Ymd');
    //     $exist = $this->db->query('SELECT * FROM ' . $this->table_name . ' WHERE ' . $this->primary_key . '  LIKE "%' . $format . '%" ORDER BY ' . $this->primary_key . ' DESC');

    //     $numbering = '0001';
    //     if ($exist->num_rows()) {
    //         $last = $exist->row();
    //         $last_numbering = substr($last->{$this->primary_key}, -4);
    //         $next_number = $last_numbering += 1;
    //         $numbering = sprintf("%04d", $next_number);

    //         return $format . $numbering;
    //     } else {
    //         return $format . $numbering;
    //     }
    // }

    public function generate_id($suffix = null)
    {
        // Use PostgreSQL-specific date formatting
        $format = $suffix . date('Ymd');

        // Prepare the query with parameterized input to prevent SQL injection
        $query = $this->db->query(
            "SELECT * FROM " . $this->db->escape_identifiers($this->table_name) . 
            " WHERE " . $this->db->escape_identifiers($this->primary_key) . 
            " LIKE :format ORDER BY " . $this->db->escape_identifiers($this->primary_key) . " DESC",
            ['format' => '%' . $format . '%']
        );

        // Default numbering
        $numbering = '0001';

        // Check if any matching records exist
        if ($query->num_rows() > 0) {
            // Get the last record
            $last = $query->row();
            
            // Extract the last 4 digits (numbering)
            $last_numbering = substr($last->{$this->primary_key}, -4);
            
            // Increment the number
            $next_number = intval($last_numbering) + 1;
            
            // Format with leading zeros
            $numbering = sprintf("%04d", $next_number);
        }

        // Return the generated ID
        return $format . $numbering;
    }

    public function sortable()
    {
        if (isset($this->sort_option) && count($this->sort_option)) {
            list($default_sort_field, $default_sort_type) = $this->sort_option;
        } else {
            $default_sort_field = $this->primary_key;
            $default_sort_type = 'DESC';
        }
        $sb = $this->input->get('sb');
        if ($sb) {
            $st = $this->input->get('st') == 'ASC' ? 'ASC' : 'DESC';
        } else {
            $sb = $default_sort_field;
            $st = $default_sort_type;
        }

        if ($sb) {
            if (in_array($sb, $this->field_search)) {
                $this->db->order_by($sb, $st);
            }
        } else {
            $this->db->order_by($this->table_name . '.' . $this->primary_key, "DESC");
        }
    }

    // public function filter_query($param_name = 'filters')
    // {
    //     $this->load->dbforge();
    //     $filter = $this->input->get($param_name);
    //     $query = '';
    //     $table_fields = $this->db->list_fields($this->table_name);
    //     if ($filter) {
    //         $filter = $this->input->get($param_name);

    //         if ($filter) {

    //             if (is_array($filter)) {
    //                 $arr = $filter;
    //             } else {
    //                 $arr = (array)(json_decode($filter));
    //             }

    //             foreach ($arr as $item) {
    //                 $logic_parent = isset($item->lg) ? $item->lg : 'and';
    //                 $item = (object)$item;

    //                 $qry_sub = '';
    //                 foreach ($item->co as $cond) {
    //                     $cond = (object)$cond;

    //                     $value = $cond->vl;
    //                     $field = $cond->fl;
    //                     if (!$field) {
    //                         continue;
    //                     }

    //                     if (!in_array(trim($field), $table_fields)) {
    //                         die(json_encode([
    //                             'status' => false,
    //                             'message' => 'field not exist',
    //                         ]));
    //                     }
    //                     $operator = $cond->op;
    //                     $logic = isset($cond->lg) ? $cond->lg : 'and';
    //                     $value = explode(',', $value);

    //                     $opr = $this->parse_operator($operator);

    //                     $use_logic = ($qry_sub ? $logic : '');
    //                     $use_logic = ' ' . $use_logic;

    //                     if ($operator == 'is_null') {
    //                         $qry_sub .= $use_logic . ' ' . $field . ' = ""';
    //                     } elseif ($operator == 'not_null') {
    //                         $qry_sub .= $use_logic . ' ' . $field . ' != ""';
    //                     } elseif ($operator == 'where_in') {
    //                         $qry_sub .= $use_logic . ' ' . $field . ' IN ("' . implode('","', $value) . '")';
    //                     } elseif ($operator == 'where_not_in') {
    //                         $qry_sub .= $use_logic . ' ' . $field . ' NOT IN ("' . implode('","', $value) . '")';
    //                     } elseif ($operator == 'like') {
    //                         $value = explode(',', $value[0]);
    //                         $qry_sub .= $use_logic . ' ' . $field . ' LIKE "' . $value[0] . '"';
    //                     } elseif (count((array)$value) == 2 && $operator == 'between') {
    //                         $qry_sub .= $use_logic . ' ' . $field . ' BETWEEN "' . $value[0] . '" AND "' . $value[1] . '"';
    //                     } elseif (count((array)$value) > 1) {
    //                         $in_set_query = '(';

    //                         $in_logic = '';
    //                         foreach ($value as $val) {
    //                             if ($val != '') {
    //                                 $in_set_query .= $in_logic . ' find_in_set("' . $val . '", ' . $field . ')';
    //                                 $in_logic = ' OR ';
    //                             }
    //                         }
    //                         $in_set_query .= ')';
    //                         $qry_sub .= $use_logic . ' ' . $in_set_query;
    //                     } else {
    //                         if ($value[0] !== '') {
    //                             $qry_sub .= $use_logic . ' ' . $field . ' ' . $opr . ' "' . $value[0] . '"';
    //                         }
    //                     }
    //                 }

    //                 $query .= $qry_sub ? (($query ? $logic_parent : ' ') . ' (' . $qry_sub . ') ') : '';
    //             }
    //         }
    //         if ($query) {
    //             $this->db->where($query);
    //         }
    //     }
    // }

    public function filter_query($param_name = 'filters')
    {
        // Validate table name to prevent SQL injection
        $table_name = $this->db->escape_identifiers($this->table_name);
        
        // Get table fields securely
        $table_fields = $this->db->list_fields($this->table_name);
        
        // Retrieve filters
        $filter = $this->input->get($param_name);
        $query = '';

        if (!$filter) {
            return;
        }

        // Parse filters (support both array and JSON input)
        $arr = is_array($filter) ? $filter : (array)json_decode($filter, false);

        if (empty($arr)) {
            return;
        }

        $where_conditions = [];

        foreach ($arr as $item) {
            $item = (object)$item;
            $logic_parent = $item->lg ?? 'AND';
            $sub_conditions = [];

            foreach ($item->co as $cond) {
                $cond = (object)$cond;

                // Validate field existence
                $field = trim($cond->fl);
                if (!$field || !in_array($field, $table_fields)) {
                    continue;
                }

                $value = explode(',', $cond->vl);
                $operator = $cond->op;
                $logic = $cond->lg ?? 'AND';

                // Escape field name
                $escaped_field = $this->db->escape_identifiers($field);

                // Process different operators
                switch ($operator) {
                    case 'is_null':
                        $sub_conditions[] = $escaped_field . ' IS NULL';
                        break;

                    case 'not_null':
                        $sub_conditions[] = $escaped_field . ' IS NOT NULL';
                        break;

                    case 'where_in':
                        $escaped_values = array_map([$this->db, 'escape'], $value);
                        $sub_conditions[] = $escaped_field . ' IN (' . implode(',', $escaped_values) . ')';
                        break;

                    case 'where_not_in':
                        $escaped_values = array_map([$this->db, 'escape'], $value);
                        $sub_conditions[] = $escaped_field . ' NOT IN (' . implode(',', $escaped_values) . ')';
                        break;

                    case 'like':
                        $escaped_value = $this->db->escape('%' . $value[0] . '%');
                        $sub_conditions[] = $escaped_field . ' ILIKE ' . $escaped_value;
                        break;

                    case 'between':
                        if (count($value) == 2) {
                            $escaped_start = $this->db->escape($value[0]);
                            $escaped_end = $this->db->escape($value[1]);
                            $sub_conditions[] = $escaped_field . ' BETWEEN ' . $escaped_start . ' AND ' . $escaped_end;
                        }
                        break;

                    default:
                        // Handle other comparison operators
                        $operator_map = [
                            'gt' => '>',
                            'lt' => '<',
                            'gte' => '>=',
                            'lte' => '<=',
                            'eq' => '=',
                            'neq' => '!='
                        ];

                        $mapped_operator = $operator_map[$operator] ?? '=';
                        
                        if (!empty($value[0])) {
                            $escaped_value = $this->db->escape($value[0]);
                            $sub_conditions[] = $escaped_field . ' ' . $mapped_operator . ' ' . $escaped_value;
                        }
                        break;
                }
            }

            // Combine sub-conditions
            if (!empty($sub_conditions)) {
                $where_conditions[] = '(' . implode(' ' . strtoupper($logic) . ' ', $sub_conditions) . ')';
            }
        }

        // Apply final where conditions
        if (!empty($where_conditions)) {
            $final_where = implode(' ' . strtoupper($logic_parent) . ' ', $where_conditions);
            $this->db->where($final_where, null, false);
        }
    }

    public function parse_operator($operator = null)
    {
        switch ($operator) {
            case 'equal':
                $use = '=';
                break;
            case 'not_equal':
                $use = '!=';
                break;
            case 'greather':
                $use = '>';
                break;
            case 'greather_equal':
                $use = '>=';
                break;
            case 'smaller_equal':
                $use = '<=';
                break;
            case 'smaller':
                $use = '<';
                break;
            default:
                $use = '=';
                break;
        }
        return $use;
    }
}

/* End of file My_Model.php */
/* Location: ./application/core/My_Model.php */