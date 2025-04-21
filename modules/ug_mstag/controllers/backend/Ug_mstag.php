<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Ug Mstag Controller
*| --------------------------------------------------------------------------
*| Ug Mstag site
*|
*/
class Ug_mstag extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_ug_mstag');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	/**
	* show all Ug Mstags
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{

		// echo $offset;
		// die;

		$this->is_allowed('ug_mstag_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['ug_mstags'] = $this->model_ug_mstag->get($filter, $field, $this->limit_page, $offset);
		$this->data['ug_mstag_counts'] = $this->model_ug_mstag->count_all($filter, $field);

		$config = [
			'base_url'     => ADMIN_NAMESPACE_URL  . '/ug_mstag/index/',
			'total_rows'   => $this->data['ug_mstag_counts'],
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);
		
		$this->data['tables'] = $this->load->view('backend/standart/administrator/ug_mstag/ug_mstag_data_table', $this->data, true);
		
		if ($this->input->get('ajax')) {
			$this->response([
				'tables' => $this->data['tables'],
				'pagination' => $this->data['pagination'],
				'total_row' => $this->data['ug_mstag_counts']
			]);
		}

		$this->template->title('Master Tag RFID List');
		$this->render('backend/standart/administrator/ug_mstag/ug_mstag_list', $this->data);
	}
	
	/**
	* Add new ug_mstags
	*
	*/
	public function add()
	{
		$this->is_allowed('ug_mstag_add');
		$this->data['pengaturan_sistem'] = $this->model_ug_mstag->getPengaturanSistem();
		$this->template->title('Master Tag RFID New');
		$this->render('backend/standart/administrator/ug_mstag/ug_mstag_add', $this->data);
	}

	public function integrasi()
	{
		$this->is_allowed('ug_mstag_add');
		$this->data['pengaturan_sistem'] = $this->model_ug_mstag->getPengaturanSistem();
		$this->template->title('Master Tag RFID New');
		$this->render('backend/standart/administrator/ug_mstag/ug_mstag_integrasi', $this->data);
	}

	/**
	* Add New Ug Mstags
	*
	* @return JSON
	*/
	public function add_save()
	{

		// if (!$this->is_allowed('ug_mstag_add', false)) {
		// 	echo json_encode([
		// 		'success' => false,
		// 		'message' => cclang('sorry_you_do_not_have_permission_to_access')
		// 		]);
		// 	exit;
		// }

		// Ambil data JSON dari POST dan decode
		$rawData = $this->input->raw_input_stream; // Ambil data mentah
		$data = json_decode($rawData, true); // Decode JSON ke array

		$save_type = '';
		foreach ($data['data_post'] as $item) {
			if ($item['name'] === 'save_type') {
				$save_type = $item['value'];
				break;
			}
		}

		// echo $save_type; // Output: stay
		// die;
		
		if (!empty($data['data'])) {

			$result = $this->model_ug_mstag->saveData($data['data']);

				// Kirimkan response sukses
				// if ($is_success) {
				if ($result['is_success']) {

					if ($save_type == 'stay') {
						$this->data['success'] = true;
						// $this->data['id'] 	   = $save_ug_mstag;
						$this->data['existing_data'] = $result['existing_data'];
						$this->data['message'] = cclang('success_save_data_stay', [
							// admin_anchor('/ug_mstag/edit/' . $save_ug_mstag, 'Edit Ug Mstag'),
							admin_anchor('/ug_mstag', ' Go back to list')
						]);
					} else {
						set_message(
							cclang('success_save_data_redirect', [
							// admin_anchor('/ug_mstag/edit/' . $save_ug_mstag, 'Edit Ug Mstag')
						]), 'success');

						$this->data['success'] = true;
						$this->data['existing_data'] = $result['existing_data'];
						$this->data['redirect'] = admin_base_url('/ug_mstag');
					}

				} else {

					if ($save_type == 'stay') {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
					} else {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
						$this->data['redirect'] = admin_base_url('/ug_mstag');
					}
					
				}

		} else {
				
			// Jika data kosong, kirimkan response error
				
			if ($save_type == 'stay') {
				$this->data['success'] = false;
				$this->data['message'] = cclang('data_not_change');
			} else {
				$this->data['success'] = false;
				$this->data['message'] = cclang('data_not_change');
				$this->data['redirect'] = admin_base_url('/ug_mstag');
			}

		}

		$this->response($this->data);
	}
	
		/**
	* Update view Ug Mstags
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('ug_mstag_update');

		$this->data['ug_mstag'] = $this->model_ug_mstag->find($id);

		$this->template->title('Master Tag RFID Update');
		$this->render('backend/standart/administrator/ug_mstag/ug_mstag_update', $this->data);
	}

	/**
	* Update Ug Mstags
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('ug_mstag_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
				$this->form_validation->set_rules('kode_tid', 'Kode Tid', 'trim|required|max_length[50]');
		

		$this->form_validation->set_rules('kode_epc', 'Kode Epc', 'trim|max_length[50]');
		

		$this->form_validation->set_rules('status_tag', 'Status Tag', 'trim|required');
		

		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'kode_tid' => $this->input->post('kode_tid'),
				'kode_epc' => $this->input->post('kode_epc'),
				'status_tag' => $this->input->post('status_tag'),
			];

			

			


			
			
			$save_ug_mstag = $this->model_ug_mstag->change($id, $save_data);

			if ($save_ug_mstag) {

				

				
				
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						admin_anchor('/ug_mstag', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = admin_base_url('/ug_mstag');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = admin_base_url('/ug_mstag');
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
	* delete Ug Mstags
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('ug_mstag_delete');

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
					"message" => cclang('has_been_deleted', 'ug_mstag')
				]);
			} else {
				$this->response([
					"success" => true,
					"message" => cclang('error_delete', 'ug_mstag')
				]);
			}

		} else {
			if ($remove) {
				set_message(cclang('has_been_deleted', 'ug_mstag'), 'success');
			} else {
				set_message(cclang('error_delete', 'ug_mstag'), 'error');
			}
			redirect_back();
		}

	}

		/**
	* View view Ug Mstags
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('ug_mstag_view');

		$this->data['ug_mstag'] = $this->model_ug_mstag->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Master Tag RFID Detail');
		$this->render('backend/standart/administrator/ug_mstag/ug_mstag_view', $this->data);
	}
	
	/**
	* delete Ug Mstags
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$ug_mstag = $this->model_ug_mstag->find($id);

		
		
		return $this->model_ug_mstag->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('ug_mstag_export');
		$field_search = ['kode_tid', 'kode_epc', 'status_tag', 'kategori_tag']; // Assign field_search with desired fields
		$this->model_ug_mstag->export(
			'tb_master_tag_rfid', 
			'tb_master_tag_rfid',
			// $this->model_ug_mstag->field_search
			$field_search
		);
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('ug_mstag_export');
		$this->model_ug_mstag->pdf('tb_master_tag_rfid', 'List Master Tag RFID');
	}

	public function single_pdf($id = null)
	{
		$this->is_allowed('ug_mstag_export');

		$table = $title = 'ug_mstag';
		$this->load->library('HtmlPdf');
      
        $config = array(
            'orientation' => 'p',
            'format' => 'a4',
            'marges' => array(5, 5, 5, 5)
        );

        $this->pdf = new HtmlPdf($config);
        $this->pdf->setDefaultFont('stsongstdlight'); 

        $result = $this->db->get($table);
       
        $data = $this->model_ug_mstag->find($id);
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

	public function postToDatabase()
	{
		// Periksa apakah request adalah POST
		if ($this->input->method() === 'post') {
			// Ambil data JSON dari POST dan decode
			$rawData = $this->input->raw_input_stream; // Ambil data mentah
			$data = json_decode($rawData, true); // Decode JSON ke array

			if (!empty($data['data'])) {

				foreach ($data['data'] as $item) {

					$kode_tid = $item['tid'];
					$status_tag = $item['status'];
					$kode_epc = $item['epc'];

					$is_success = $this->model_ug_mstag->saveData($kode_tid, $status_tag, $kode_epc);
				}

				// Kirimkan response sukses
				if ($is_success) {
					echo json_encode([
						'is_success' => true,
						'message' => 'Data has been saved.',
						'data' => $data['data']
					]);
				} else {
					echo json_encode([
						'is_success' => false,
						'message' => 'Failed to save data.',
						'data' => $data['data']
					]);
				}

			} else {
				// Jika data kosong, kirimkan response error
				echo json_encode([
					'is_success' => false,
					'message' => 'No data received.'
				]);
			}

		} else {
			// Jika request bukan POST
			echo json_encode([
				'is_success' => false,
				'message' => 'Invalid request method.'
			]);
		}
	}

	public function add_integrasi_existing()
	{

		// if (!$this->is_allowed('ug_mstag_add', false)) {
		// 	echo json_encode([
		// 		'success' => false,
		// 		'message' => cclang('sorry_you_do_not_have_permission_to_access')
		// 		]);
		// 	exit;
		// }

		// $this->form_validation->set_rules('id_area', 'Area', 'trim|required');
		// $this->form_validation->set_rules('id_gedung', 'Gedung', 'trim|required');
		// $this->form_validation->set_rules('id_ruangan', 'Ruangan', 'trim|required');

		// $this->form_validation->set_rules('data_post[id_area]', 'Area', 'trim|required|callback[check_id_area]');
		// $this->form_validation->set_rules('data_post[id_gedung]', 'Gedung', 'trim|required|callback[check_id_gedung]');
		// $this->form_validation->set_rules('data_post[id_ruangan]', 'Ruangan', 'trim|required|callback[check_id_ruangan]');
		
		// Validation passed, proceed with the rest of the code

		// Ambil data JSON dari POST dan decode
		$rawData = $this->input->raw_input_stream; // Ambil data mentah
		$data = json_decode($rawData, true); // Decode JSON ke array

		$save_type = '';

		$id_area = '';
		$id_gedung = '';
		$id_ruangan = '';

		foreach ($data['data_post'] as $item) {
			if ($item['name'] === 'save_type') {
				$save_type = $item['value'];
			} elseif ($item['name'] === 'id_area') {
				$id_area = $item['value'];
			} elseif ($item['name'] === 'id_gedung') {
				$id_gedung = $item['value'];
			} elseif ($item['name'] === 'id_ruangan') {
				$id_ruangan = $item['value'];
			}
		}

		// Validasi bahwa id_area tidak boleh bernilai '0'
		// if ($id_area === '0') {
		// 	$this->data['success'] = false;
		// 	$this->data['message'] = 'Area tidak boleh bernilai 0.';
		// 	$this->response($this->data);
		// 	return;
		// }

		// // Validasi bahwa id_gedung tidak boleh bernilai '0'
		// if ($id_gedung === '0') {
		// 	$this->data['success'] = false;
		// 	$this->data['message'] = 'Gedung tidak boleh bernilai 0.';
		// 	$this->response($this->data);
		// 	return;
		// }

		// // Validasi bahwa id_ruangan tidak boleh bernilai '0'
		// if ($id_ruangan === '0') {
		// 	$this->data['success'] = false;
		// 	$this->data['message'] = 'Ruangan tidak boleh bernilai 0.';
		// 	$this->response($this->data);
		// 	return;
		// }

		// echo $save_type; // Output: stay
		// die;

		// if ($this->form_validation->run()) {

			$save_data_master_transaksi = [
				// 'kode_transaksi' => $this->input->post('kode_transaksi'),
				'tipe_transaksi' => '2',
				'status_transaksi' => '1',
				'tgl_input' => date('Y-m-d H:i:s'),
				'tgl_awal_transaksi' => date('Y-m-d'),
				// 'tgl_akhir_transaksi' => $this->input->post('tgl_akhir_transaksi'),
				'id_pegawai_input' => '0',
				'nama_pegawai_input' => '0',
				'id_pegawai' => '0',
				'nama_pegawai' => '0',
				'ket_transaksi' => 'Integrasi Aset Existing',
				'id_area' => $id_area,
				'id_gedung' => $id_gedung,
				'id_ruangan' => $id_ruangan,
			];

			if (!empty($data['data'])) {

				$result = $this->model_ug_mstag->saveDataIntegrasiExisting($data['data'], $save_data_master_transaksi);

					// Kirimkan response sukses
					// if ($is_success) {
					if ($result['is_success']) {

						if ($save_type == 'stay') {
							$this->data['success'] = true;
							// $this->data['id'] 	   = $save_ug_mstag;
							$this->data['existing_data'] = $result['existing_data'];
							$this->data['message'] = cclang('success_save_data_stay', [
								// admin_anchor('/ug_mstag/edit/' . $save_ug_mstag, 'Edit Ug Mstag'),
								admin_anchor('/ug_mstag', ' Go back to list')
							]);
						} else {
							set_message(
								cclang('success_save_data_redirect', [
								// admin_anchor('/ug_mstag/edit/' . $save_ug_mstag, 'Edit Ug Mstag')
							]), 'success');

							$this->data['success'] = true;
							$this->data['existing_data'] = $result['existing_data'];
							$this->data['redirect'] = admin_base_url('/ug_mstag');
						}

					} else {

						if ($save_type == 'stay') {
							$this->data['success'] = false;
							$this->data['message'] = cclang('data_not_change');
						} else {
							$this->data['success'] = false;
							$this->data['message'] = cclang('data_not_change');
							$this->data['redirect'] = admin_base_url('/ug_mstag');
						}
						
					}

				$this->response($this->data);

			} else {
					
				// Jika data kosong, kirimkan response error
					
				if ($save_type == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = admin_base_url('/ug_mstag');
				}

			}

		// } else {
		// 	$this->data['success'] = false;
		// 	$this->data['message'] = 'Opss validation failed';
		// 	$this->data['errors'] = $this->form_validation->error_array();
		// 	$this->response($this->data);
		// 	return;
		// }

	}

	public function add_new()
	{

		// if (!$this->is_allowed('ug_mstag_add', false)) {
		// 	echo json_encode([
		// 		'success' => false,
		// 		'message' => cclang('sorry_you_do_not_have_permission_to_access')
		// 		]);
		// 	exit;
		// }

		// $this->form_validation->set_rules('id_area', 'Area', 'trim|required');
		// $this->form_validation->set_rules('id_gedung', 'Gedung', 'trim|required');
		// $this->form_validation->set_rules('id_ruangan', 'Ruangan', 'trim|required');

		// $this->form_validation->set_rules('data_post[id_area]', 'Area', 'trim|required|callback[check_id_area]');
		// $this->form_validation->set_rules('data_post[id_gedung]', 'Gedung', 'trim|required|callback[check_id_gedung]');
		// $this->form_validation->set_rules('data_post[id_ruangan]', 'Ruangan', 'trim|required|callback[check_id_ruangan]');
		
		// Validation passed, proceed with the rest of the code

		// Ambil data JSON dari POST dan decode
		$rawData = $this->input->raw_input_stream; // Ambil data mentah
		$data = json_decode($rawData, true); // Decode JSON ke array

		$save_type = '';

		$id_area = '';
		$id_gedung = '';
		$id_ruangan = '';
		$merk = '';
		$tipe = '';
		$pic = '';

		foreach ($data['data_post'] as $item) {

			if ($item['name'] === 'save_type') {
				$save_type = $item['value'];
			} elseif ($item['name'] === 'id_area') {
				$id_area = $item['value'];
			} elseif ($item['name'] === 'id_gedung') {
				$id_gedung = $item['value'];
			} elseif ($item['name'] === 'id_ruangan') {
				$id_ruangan = $item['value'];
			} elseif ($item['name'] === 'merk') {
				$merk = $item['value'];
			} elseif ($item['name'] === 'tipe') {
				$tipe = $item['value'];
			} elseif ($item['name'] === 'pic') {
				$pic = $item['value'];
			}
		}

		$detail_data = [
			'merk' => $merk,
			'tipe' => $tipe,
			'pic' => $pic
		];

		// Validasi bahwa id_area tidak boleh bernilai '0'
		// if ($id_area === '0') {
		// 	$this->data['success'] = false;
		// 	$this->data['message'] = 'Area tidak boleh bernilai 0.';
		// 	$this->response($this->data);
		// 	return;
		// }

		// // Validasi bahwa id_gedung tidak boleh bernilai '0'
		// if ($id_gedung === '0') {
		// 	$this->data['success'] = false;
		// 	$this->data['message'] = 'Gedung tidak boleh bernilai 0.';
		// 	$this->response($this->data);
		// 	return;
		// }

		// // Validasi bahwa id_ruangan tidak boleh bernilai '0'
		// if ($id_ruangan === '0') {
		// 	$this->data['success'] = false;
		// 	$this->data['message'] = 'Ruangan tidak boleh bernilai 0.';
		// 	$this->response($this->data);
		// 	return;
		// }

		// echo $save_type; // Output: stay
		// die;

		// if ($this->form_validation->run()) {

			$save_data_master_transaksi = [
				// 'kode_transaksi' => $this->input->post('kode_transaksi'),
				'tipe_transaksi' => '2',
				'status_transaksi' => '1',
				'tgl_input' => date('Y-m-d H:i:s'),
				'tgl_awal_transaksi' => date('Y-m-d'),
				// 'tgl_akhir_transaksi' => $this->input->post('tgl_akhir_transaksi'),
				'id_pegawai_input' => '0',
				'nama_pegawai_input' => '0',
				'id_pegawai' => '0',
				'nama_pegawai' => '0',
				'ket_transaksi' => 'Integrasi Aset New (Print Sticker RFID With Barcode)',
				'id_area' => $id_area,
				'id_gedung' => $id_gedung,
				'id_ruangan' => $id_ruangan,
			];

			if (!empty($data['data'])) {

				$result = $this->model_ug_mstag->saveDataIntegrasiNew($data['data'], $save_data_master_transaksi, $detail_data);

					// Kirimkan response sukses
					// if ($is_success) {
					if ($result['is_success']) {

						if ($save_type == 'stay') {
							$this->data['success'] = true;
							// $this->data['id'] 	   = $save_ug_mstag;
							$this->data['existing_data'] = $result['existing_data'];
							$this->data['message'] = cclang('success_save_data_stay', [
								// admin_anchor('/ug_mstag/edit/' . $save_ug_mstag, 'Edit Ug Mstag'),
								admin_anchor('/ug_mstag', ' Go back to list')
							]);
						} else {
							set_message(
								cclang('success_save_data_redirect', [
								// admin_anchor('/ug_mstag/edit/' . $save_ug_mstag, 'Edit Ug Mstag')
							]), 'success');

							$this->data['success'] = true;
							$this->data['existing_data'] = $result['existing_data'];
							$this->data['redirect'] = admin_base_url('/ug_mstag');
						}

					} else {

						if ($save_type == 'stay') {
							$this->data['success'] = false;
							$this->data['message'] = cclang('data_not_change');
						} else {
							$this->data['success'] = false;
							$this->data['message'] = cclang('data_not_change');
							$this->data['redirect'] = admin_base_url('/ug_mstag');
						}
						
					}

				$this->response($this->data);

			} else {
					
				// Jika data kosong, kirimkan response error
					
				if ($save_type == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = admin_base_url('/ug_mstag');
				}

			}

		// } else {
		// 	$this->data['success'] = false;
		// 	$this->data['message'] = 'Opss validation failed';
		// 	$this->data['errors'] = $this->form_validation->error_array();
		// 	$this->response($this->data);
		// 	return;
		// }

	}

	public function check_id_area($id_area)
	{
		if ($id_area == '') {
			$this->form_validation->set_message('check_id_area', 'Area harus diisi !!');
			return false;
		}
		return true;
	}

	public function check_id_gedung($id_gedung)
	{
		if ($id_gedung == '') {
			$this->form_validation->set_message('check_id_gedung', 'Gedung harus diisi !!');
			return false;
		}
		return true;
	}

	public function check_id_ruangan($id_ruangan)
	{
		if ($id_ruangan == '') {
			$this->form_validation->set_message('check_id_ruangan', 'Ruangan harus diisi !!');
			return false;
		}
		return true;
	}
	
}


/* End of file ug_mstag.php */
/* Location: ./application/controllers/administrator/Ug Mstag.php */