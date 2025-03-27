<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Pencarian Aset Controller
*| --------------------------------------------------------------------------
*| Pencarian Aset site
*|
*/
class Pencarian_single_tag extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_pencarian_single_tag');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	public function index()
	{
		$this->is_allowed('pencarian_single_tag_add');

		$this->data['pengaturan_sistem'] = $this->model_pencarian_single_tag->getPengaturanSistem();

		$this->template->title('Pencarian Aset');
		$this->render('backend/standart/administrator/pencarian_single_tag/pencarian_single_tag', $this->data);
	}

	public function serverSideData()
    {
        
        $columns = array(
            0 => 'checkbox_id_master_aset',
            1 => 'id_aset',
            2 => 'nama_aset',
            3 => 'kode_aset',
            4 => 'nup',
			5 => 'kode_tid'
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $id_area = $this->input->post('id_area');
        $id_gedung = $this->input->post('id_gedung');
        $id_ruangan = $this->input->post('id_ruangan');
        $select_all = $this->input->post('select_all');

		$filter_data = array();

		$filter_data['id_area'] = $id_area;
		$filter_data['id_gedung'] = $id_gedung;
		$filter_data['id_ruangan'] = $id_ruangan;

        $totalData = $this->model_pencarian_single_tag->count_all_content($filter_data);
        $totalFiltered = $totalData;

        if(empty($this->input->post('search')['value'])) {
            $contents = $this->model_pencarian_single_tag->get_content($limit, $start, $order, $dir, $select_all, $filter_data);
        } else {
            $search = $this->input->post('search')['value'];
            $contents =  $this->model_pencarian_single_tag->content_search($limit, $start, $search, $order, $dir, $select_all, $filter_data);
            $totalFiltered = $this->model_pencarian_single_tag->content_search_count($search, $select_all, $filter_data);
        }

        $data = array();
        if(!empty($contents)) {
            $autoNumber = $start + 1;
            foreach($contents as $row) {
                $nestedData['checkbox_id_master_aset'] = '<input type="checkbox" value="'.$row->id_aset.'" class="cekbok" data-id="'.$row->id_aset.'" data-kode-aset="'.$row->kode_aset.'" data-nup="'.$row->nup.'" data-nama-aset="'.$row->nama_aset.'" data-kode-tid="'.$row->kode_tid.'">';
				$nestedData['auto_number'] = $autoNumber;
				$nestedData['id'] = $row->id_aset;
                $autoNumber++;
                $nestedData['nama_aset'] = $row->nama_aset;
                $nestedData['kode_aset'] = $row->kode_aset;
                $nestedData['nup'] = $row->nup;
                $nestedData['kode_tid'] = $row->kode_tid;
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }

	/**
	* Add New Tb Master Transaksis
	*
	* @return JSON
	*/
	public function add_save()
	{

		if (!$this->is_allowed('tb_master_transaksi_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('tipe_transaksi', 'Tipe Transaksi', 'trim|required');
		$this->form_validation->set_rules('status_transaksi', 'Status Transaksi', 'trim|required');
		$this->form_validation->set_rules('tgl_awal_transaksi', 'Tgl Awal Transaksi', 'trim|required');
		$this->form_validation->set_rules('ket_transaksi', 'Ket Transaksi', 'trim|required|max_length[500]');
		// $this->form_validation->set_rules('nama_pegawai_input', 'Nama Pegawai Input', 'trim|max_length[100]');
		$this->form_validation->set_rules('id_area', 'Id Area', 'trim|required');
		$this->form_validation->set_rules('id_gedung', 'Id Gedung', 'trim|required');
		$this->form_validation->set_rules('id_ruangan', 'Id Ruangan', 'trim|required');
		
		if ($this->form_validation->run()) {
		
			$save_data_master_transaksi = [
				'kode_transaksi' => $this->input->post('kode_transaksi'),
				'tipe_transaksi' => $this->input->post('tipe_transaksi'),
				'status_transaksi' => $this->input->post('status_transaksi'),
				'tgl_input' => date('Y-m-d H:i:s'),
				'tgl_awal_transaksi' => $this->input->post('tgl_awal_transaksi'),
				// 'tgl_akhir_transaksi' => $this->input->post('tgl_akhir_transaksi'),
				'id_pegawai_input' => $this->input->post('id_pegawai_input'),
				'nama_pegawai_input' => $this->input->post('nama_pegawai_input'),
				'id_pegawai' => $this->input->post('id_pegawai'),
				'nama_pegawai' => $this->input->post('nama_pegawai'),
				'ket_transaksi' => $this->input->post('ket_transaksi'),
				'id_area' => $this->input->post('id_area'),
				'id_gedung' => $this->input->post('id_gedung'),
				'id_ruangan' => $this->input->post('id_ruangan'),
			];

			$save_data_detail_transaksi = [
				// 'id_transaksi' => $id_transaksi,
				// 'kode_transaksi' => $save_data_master_transaksi['kode_transaksi'],
				// 'id_aset' => $id_aset,
				// 'id_area' => $save_data_master_transaksi['id_area'],
				// 'id_gedung' => $save_data_master_transaksi['id_gedung'], 
				// 'id_ruangan' => $save_data_master_transaksi['id_ruangan'],
				'status' => 1,
				'id_kondisi' => 1	
			];

			$string_id = $this->input->post('string_id');

			// Ambil data array aset dan tag dari ajax post
			$array_data_aset = json_decode($this->input->post('data_array_aset'), true);
			$uniqueDataArray = json_decode($this->input->post('uniqueDataArray'), true);

			// Link array aset dengan array tag berdasarkan index
			$linked_data = array();
			for($i = 0; $i < count($array_data_aset); $i++) {
				$linked_data[] = array(
					'aset' => $array_data_aset[$i],
					'tag' => $uniqueDataArray[$i]
				);
			}

			// echo '<pre>';
			// print_r($linked_data);
			// echo '</pre>';
			// exit();

			$save_register_aset = $id = $this->model_tb_master_transaksi->saveRegisterAset($save_data_master_transaksi, $save_data_detail_transaksi, $linked_data);
			// $save_register_aset = $this->model_tb_master_transaksi->saveRegisterAset($save_data_master_transaksi, $save_data_detail_transaksi, $linked_data);

			// echo '<pre>';	
			// print_r($save_register_aset);
			// echo '</pre>';
			// exit();

			if ($save_register_aset) {
				
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_register_aset;
					$this->data['message'] = cclang('success_save_data_stay', [admin_anchor('/tb_master_transaksi', 'Go back to list')]);
				} else {
					set_message(cclang('success_save_data_redirect', [admin_anchor('/tb_master_transaksi/view/'.$save_register_aset, 'See detail')]), 'success');
            		$this->data['success'] = true;
					$this->data['redirect'] = admin_base_url('/tb_master_transaksi');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = admin_base_url('/tb_master_transaksi');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = 'Opss validation failed';
			$this->data['errors'] = $this->form_validation->error_array();
		}

		$this->response($this->data);
	}
	
		/**
	* Update view Tb Master Transaksis
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('tb_master_transaksi_update');

		$this->data['tb_master_transaksi'] = $this->model_tb_master_transaksi->find($id);

		$this->template->title('Register Aset Update');
		$this->render('backend/standart/administrator/tb_master_transaksi/tb_master_transaksi_update', $this->data);
	}

	/**
	* Update Tb Master Transaksis
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('tb_master_transaksi_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
				

		$this->form_validation->set_rules('tipe_transaksi', 'Tipe Transaksi', 'trim|required');
		

		$this->form_validation->set_rules('status_transaksi', 'Status Transaksi', 'trim|required');
		

		

		$this->form_validation->set_rules('tgl_awal_transaksi', 'Tgl Awal Transaksi', 'trim|required');
		

		$this->form_validation->set_rules('ket_transaksi', 'Ket Transaksi', 'trim|required|max_length[500]');
		

		

		$this->form_validation->set_rules('nama_pegawai_input', 'Nama Pegawai Input', 'trim|max_length[100]');
		

		

		$this->form_validation->set_rules('id_area', 'Id Area', 'trim|required');
		

		$this->form_validation->set_rules('id_gedung', 'Id Gedung', 'trim|required');
		

		$this->form_validation->set_rules('id_ruangan', 'Id Ruangan', 'trim|required');
		

		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'tipe_transaksi' => $this->input->post('tipe_transaksi'),
				'status_transaksi' => $this->input->post('status_transaksi'),
				'tgl_awal_transaksi' => $this->input->post('tgl_awal_transaksi'),
				'ket_transaksi' => $this->input->post('ket_transaksi'),
				'id_pegawai_input' => $this->input->post('id_pegawai_input'),
				'nama_pegawai_input' => $this->input->post('nama_pegawai_input'),
				'id_area' => $this->input->post('id_area'),
				'id_gedung' => $this->input->post('id_gedung'),
				'id_ruangan' => $this->input->post('id_ruangan'),
			];

			

			


			
			
			$save_tb_master_transaksi = $this->model_tb_master_transaksi->change($id, $save_data);

			if ($save_tb_master_transaksi) {

				

				
				
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						admin_anchor('/tb_master_transaksi', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = admin_base_url('/tb_master_transaksi');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = admin_base_url('/tb_master_transaksi');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = 'Opss validation failed';
			$this->data['errors'] = $this->form_validation->error_array();
		}

		$this->response($this->data);
	}
	
	/**
	* delete Tb Master Transaksis
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('tb_master_transaksi_delete');

		$this->load->helper('file');

		$arr_id = $this->input->get('id');
		$remove = false;

		if (!empty($id)) {
			$remove = $this->_remove($id);
		} elseif (count($arr_id) >0) {
			foreach ($arr_id as $id) {
				$remove = $this->_remove($id);
			}
		}

		if ($this->input->get('ajax')) {
			if ($remove) {
				$this->response([
					"success" => true,
					"message" => cclang('has_been_deleted', 'tb_master_transaksi')
				]);
			} else {
				$this->response([
					"success" => true,
					"message" => cclang('error_delete', 'tb_master_transaksi')
				]);
			}

		} else {
			if ($remove) {
				set_message(cclang('has_been_deleted', 'tb_master_transaksi'), 'success');
			} else {
				set_message(cclang('error_delete', 'tb_master_transaksi'), 'error');
			}
			redirect_back();
		}

	}

	/**
	* View view Tb Master Transaksis
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('tb_master_transaksi_view');

		$this->data['tb_master_transaksi'] = $this->model_tb_master_transaksi->getTransaksiById($id);
		$this->data['tb_detail_transaksi'] = $this->model_tb_master_transaksi->getDetailTransaksiById($id);
		$this->template->title('Detail Register Aset');
		$this->render('backend/standart/administrator/tb_master_transaksi/tb_master_transaksi_view', $this->data);
	}
	
	/**
	* delete Tb Master Transaksis
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$tb_master_transaksi = $this->model_tb_master_transaksi->find($id);
		return $this->model_tb_master_transaksi->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('tb_master_transaksi_export');

		$this->model_tb_master_transaksi->export(
			'tb_master_transaksi', 
			'tb_master_transaksi',
			$this->model_tb_master_transaksi->field_search
		);
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('tb_master_transaksi_export');

		$this->model_tb_master_transaksi->pdf('tb_master_transaksi', 'tb_master_transaksi');
	}


	public function single_pdf($id = null)
	{
		$this->is_allowed('tb_master_transaksi_export');

		$table = $title = 'tb_master_transaksi';
		$this->load->library('HtmlPdf');
      
        $config = array(
            'orientation' => 'p',
            'format' => 'a4',
            'marges' => array(5, 5, 5, 5)
        );

        $this->pdf = new HtmlPdf($config);
        $this->pdf->setDefaultFont('stsongstdlight'); 

        $result = $this->db->get($table);
       
        $data = $this->model_tb_master_transaksi->find($id);
        $fields = $result->list_fields();

        $content = $this->pdf->loadHtmlPdf('core_template/pdf/pdf_single', [
            'data' => $data,
            'fields' => $fields,
            'title' => $title
        ], TRUE);

        $this->pdf->initialize($config);
        $this->pdf->pdf->SetDisplayMode('fullpage');
        $this->pdf->writeHTML($content);
        $this->pdf->Output($table.'.pdf', 'H');
	}

	public function ajax_id_gedung($id = null)
	{
		if (!$this->is_allowed('tb_master_transaksi_list', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		$results = db_get_all_data('tb_master_gedung', ['id_area' => $id]);
		$this->response($results);	
	}

	public function ajax_id_ruangan($id = null)
	{
		if (!$this->is_allowed('tb_master_transaksi_list', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		$results = db_get_all_data('tb_master_ruangan', ['id_gedung' => $id]);
		$this->response($results);	
	}

	public function check_unique_data()
	{
		$uniqueData = $this->input->get('uniqueData');
		$uniqueDataArray = json_decode($uniqueData, true);

		// Cek apakah data sudah ada di database
		$exists = false;
		foreach ($uniqueDataArray as $data) {
			$tid = $data['tid'];
			$epc = $data['epc'];
			
			$check = $this->db->get_where('tb_master_tag_rfid', [
				'kode_tid' => $tid,
				'status_tag' => 'N'
				// 'kode_epc' => $epc
			])->num_rows();
			
			if ($check > 0) {
				$exists = true;
				break;
			}
		}

		$response = [
			'exists' => $exists
		];

		$this->response($response);
	}

	public function check_unique_single_tag()
	{

		$tid = $this->input->get('tid');

		$query = $this->db->get_where('tb_master_tag_rfid', 
		[
			'kode_tid' => $tid
			// 'status_tag' => 'Y'
		]);

		$response_data_aset = [];

		$check = $query->num_rows();

		if ($check > 0) {

			$data_result = $query->row_array();
			$status_tag = $data_result['status_tag'];

			// tag belum ada yang punya
			if ($status_tag == 'Y') {

				$response_data_aset = [
					'id_aset' => '',
					'kode_aset' => '',
					'nama_aset' => '',
					'nup' => '',
					'id_area' => '',
					'id_gedung' => '',
					'id_ruangan' => '',
					'kode_tid' => '',
					'area' => '',
					'gedung' => '',
					'ruangan' => ''
				];
				
			} else {

				$data_aset = $this->model_pencarian_single_tag->getAsetByID($tid);

				$response_data_aset = [
					'id_aset' => $data_aset->id_aset,
					'kode_aset' => $data_aset->kode_aset,
					'nama_aset' => $data_aset->nama_aset,
					'nup' => $data_aset->nup,
					'id_area' => $data_aset->id_area,
					'id_gedung' => $data_aset->id_gedung,
					'id_ruangan' => $data_aset->id_lokasi,
					'kode_tid' => $data_aset->kode_tid,
					'area' => $data_aset->area,
					'gedung' => $data_aset->gedung,
					'ruangan' => $data_aset->ruangan
				];

			}

			$response = [	
				'check' => $check,
				'status_tag' => $status_tag,
				'data_aset' => $response_data_aset
			];

		} else {

			$response = [
				'check' => $check,
				'data_aset' => $response_data_aset
			];
			
		}

		$this->response($response);

	}

	public function get_all_tag()
	{
		// Ambil semua data tag dari database
		$tags = $this->db->get('tb_master_tag_rfid')->result();

		// Format response
		$response = [
			'success' => true,
			'data' => $tags
		];

		// Kirim response dalam format JSON
		$this->response($response);
	}

	public function delete_all_tag()
	{
		// Hapus semua data dari tabel tb_master_tag_rfid
		$this->db->empty_table('tb_master_tag_rfid');

		// Format response
		$response = [
			'success' => true,
			'message' => 'Semua data RFID tag berhasil dihapus'
		];

		// Kirim response dalam format JSON 
		$this->response($response);
	}

	public function get_all_aset()
	{
		$id_area = $this->input->get('id_area');	
		$id_gedung = $this->input->get('id_gedung');
		$id_ruangan = $this->input->get('id_ruangan');
		$metode_pencarian = $this->input->get('metode_pencarian');

		$filter_data = array();

		$filter_data = array(
			'id_area' => $id_area,
			'id_gedung' => $id_gedung,
			'id_ruangan' => $id_ruangan,
			'metode_pencarian' => $metode_pencarian
		);

		$results = $this->model_pencarian_single_tag->get_all_aset($filter_data);
		
		$response = [
			'success' => true,
			'data' => $results
		];

		$this->response($response);
	}

	public function ajax_pie_chart()
	{
		$results = $this->model_pencarian_single_tag->get_data_pie_chart();

		$response = [
			'success' => true,
			'data' => $results
		];

		$this->response($response);

	}

	public function get_single_tag()
	{

		$tid = $this->input->get('tid');

		$query = $this->db->get_where('tb_master_tag_rfid', 
		[
			'kode_tid' => $tid
		]);

		$response_data_aset = [];

		$check = $query->num_rows();

		if ($check > 0) {

			$data_result = $query->row_array();
			$status_tag = $data_result['status_tag'];
			$kategori_tag = $data_result['kategori_tag'];

			// tag belum ada yang punya
			if ($status_tag == 'Y') {

				$response_data_aset = [
					'id_aset' => '',
					'kode_aset' => '',
					'nama_aset' => '',
					'nup' => '',
					'id_area' => '',
					'id_gedung' => '',
					'id_ruangan' => '',
					'kode_tid' => '',
					'area' => '',
					'gedung' => '',
					'ruangan' => ''
				];
				
			} else {

				if ($kategori_tag == 1) {

					$data_aset = $this->model_pencarian_single_tag->getAsetByID($tid);

					$response_data_aset = [
						'id_aset' => $data_aset->id_aset,
						'kode_aset' => $data_aset->kode_aset,
						'nama_aset' => $data_aset->nama_aset,
						'nup' => $data_aset->nup,
						'id_area' => $data_aset->id_area,
						'id_gedung' => $data_aset->id_gedung,
						'id_ruangan' => $data_aset->id_lokasi,
						'kode_tid' => $data_aset->kode_tid,
						'area' => $data_aset->area,
						'gedung' => $data_aset->gedung,
						'ruangan' => $data_aset->ruangan
					];

				}
				else if ($kategori_tag == 2) {

					$data_pegawai = $this->model_pencarian_single_tag->getPeopleByID($tid);

					$response_data_people = [
						'id_pegawai' => $data_pegawai->id,
						'nip' => $data_pegawai->nip,
						'nama_pegawai' => $data_pegawai->nama,
						'kode_tid_pegawai' => $data_pegawai->kode_tid_pegawai
					];

				}

			}

			$response = [	
				'check' => $check,
				'status_tag' => $status_tag,
				'kategori_tag' => $kategori_tag,
				'data_aset' => $response_data_aset,
				'data_pegawai' => $response_data_people
			];

		} else {

			$response = [
				'check' => $check,
				'data_aset' => $response_data_aset,
				'data_pegawai' => '-'
			];
			
		}

		$this->response($response);

	}

}

/* End of file tb_master_transaksi.php */
/* Location: ./application/controllers/administrator/Tb Master Transaksi.php */