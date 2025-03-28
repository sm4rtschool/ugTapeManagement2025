<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 *| --------------------------------------------------------------------------
 *| Tb Pegawai Master Controller
 *| --------------------------------------------------------------------------
 *| Tb Pegawai Master site
 *|
 */
class tb_master_pegawai extends Admin
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_tb_master_pegawai');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	/**
	 * show all Tb Pegawai Masters
	 *
	 * @var $offset String
	 */
	public function index($offset = 0)
	{
		$this->is_allowed('tb_pegawai_master_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['tb_pegawai_masters'] = $this->model_tb_master_pegawai->get($filter, $field, $this->limit_page, $offset);
		$this->data['tb_pegawai_master_counts'] = $this->model_tb_master_pegawai->count_all($filter, $field);

		$config = [
			'base_url'     => ADMIN_NAMESPACE_URL  . '/tb_master_pegawai/index/',
			'total_rows'   => $this->data['tb_pegawai_master_counts'],
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->data['tables'] = $this->load->view('backend/standart/administrator/tb_pegawai_master/tb_pegawai_master_data_table', $this->data, true);

		if ($this->input->get('ajax')) {
			$this->response([
				'tables' => $this->data['tables'],
				'pagination' => $this->data['pagination'],
				'total_row' => $this->data['tb_pegawai_master_counts']
			]);
		}

		$this->template->title('Master Pegawai List');
		$this->render('backend/standart/administrator/tb_pegawai_master/tb_pegawai_master_list', $this->data);
	}

	/**
	 * Add new tb_pegawai_masters
	 *
	 */
	public function add()
	{
		$this->is_allowed('tb_pegawai_master_add');

		$this->template->title('Tb Pegawai Master New');
		$this->render('backend/standart/administrator/tb_pegawai_master/tb_pegawai_master_add', $this->data);
	}

	/**
	 * Add New Tb Pegawai Masters
	 *
	 * @return JSON
	 */
	public function add_save()
	{
		if (!$this->is_allowed('tb_pegawai_master_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);
			exit;
		}

		// $this->form_validation->set_rules('nip', 'NIP', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('pegawai', 'Pegawai', 'trim|required|max_length[130]');
		$this->form_validation->set_rules('jabatan', 'Jabatan', 'trim|required|max_length[130]');
		// $this->form_validation->set_rules('telp', 'Telp', 'trim|required|max_length[130]');
		// $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required|max_length[130]');
		// $this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[130]');

		$rand = rand();
		$ekstensi =  array('png', 'jpg', 'jpeg');
		$filename = $_FILES['fotopegawai']['name'];
		$ukuran = $_FILES['fotopegawai']['size'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$folderfoto = 'Pegawai';

		if ($this->form_validation->run()) {

			$kode_tid_pegawai = $this->input->post('kode_tid_pegawai');

			$save_data = [
				'kode_tid_pegawai' => $kode_tid_pegawai !== '' ? $kode_tid_pegawai : null,
				'nip' => $this->input->post('nip'),
				'nama' => $this->input->post('pegawai'),
				'jabatan' => $this->input->post('jabatan'),
				'email' => $this->input->post('email'),
				'telp' => $this->input->post('telp'),
				'alamat' => $this->input->post('alamat'),
				'image_uri' => $rand . '_' . $_FILES['fotopegawai']['name']
			];

			// $save_tb_pegawai_master = $this->model_tb_master_pegawai->store($save_data);
			$this->db->insert('tb_master_pegawai', $save_data);
			// echo $this->db->last_query();
			// exit;

			$query = $this->db->query("SELECT CURRVAL(pg_get_serial_sequence('tb_master_pegawai', 'id')) as last_id");

			if ($query) {
				$row = $query->row();
				$id_transaksi = $row->last_id;
			} else {
				$id_transaksi = 0;
			}

			if ($id_transaksi) {

				// echo $id_transaksi;
				// exit;

				if ($kode_tid_pegawai != ''){
					$this->db->where('kode_tid', $kode_tid_pegawai);
					$is_success = $this->db->update('tb_master_tag_rfid', array('status_tag' => 'N', 'id_pegawai' => $id_transaksi));
				}

				if (!in_array($ext, $ekstensi)) {
					header("location:index.php?alert=gagal_ekstensi");
				} else {
					if (!file_exists('uploads/' . $folderfoto)) {
						mkdir('uploads/' . $folderfoto, 0777, true);
					}
					if ($ukuran < 500000) {
						if (file_exists('uploads/' . $folderfoto . basename($_FILES["fotopegawai"]["name"]))) {
							echo "Sorry, file already exists.";
						} else {
							move_uploaded_file($_FILES["fotopegawai"]["tmp_name"], "uploads/" . $folderfoto . "/" . $rand . '_' . $_FILES['fotopegawai']['name']);
						}
					} else {
						header("location:index.php?alert=Ukuran File Maks .500 Kb");
					}
				}

				$this->session->set_flashdata('success', 'succes_save');


				// if ($this->input->post('save_type') == 'stay') {
				// 	$this->data['success'] = true;
				// 	$this->data['id'] 	   = $save_tb_master_aset;
				// 	$this->data['message'] = cclang('success_save_data_stay', [
				// 		admin_anchor('/tb_master_aset/edit/' . $save_tb_master_aset, 'Edit Tb Master Aset'),
				// 		admin_anchor('/tb_master_aset', ' Go back to list')
				// 	]);
				// } else {
				// 	set_message(
				// 		cclang('success_save_data_redirect', [
				// 			admin_anchor('/tb_master_aset/edit/' . $save_tb_master_aset, 'Edit Tb Master Aset')
				// 		]),
				// 		'success'
				// 	);

				// 	$this->data['success'] = true;
				// 	$this->data['redirect'] = admin_base_url('/tb_master_aset');
				// }
				redirect_back();

			} else {
				// if ($this->input->post('save_type') == 'stay') {
				// 	$this->data['success'] = false;
				// 	$this->data['message'] = cclang('data_not_change');
				// } else {
				// 	$this->data['success'] = false;
				// 	$this->data['message'] = cclang('data_not_change');
				// 	$this->data['redirect'] = admin_base_url('/tb_master_aset');
				// }
				$this->session->set_flashdata('failsave', 'cannot save data');
			}
		} else {
			$this->session->set_flashdata('err_val', 'error_validasi');
		}
	}

	/**
	 * Update view Tb Pegawai Masters
	 *
	 * @var $id String
	 */
	public function edit($id)
	{
		$this->is_allowed('tb_pegawai_master_update');

		$this->data['tb_pegawai_master'] = $this->model_tb_master_pegawai->get_detail_pegawai($id);

		$this->template->title('Tb Pegawai Master Update');
		$this->render('backend/standart/administrator/tb_pegawai_master/tb_pegawai_master_update', $this->data);
	}

	/**
	 * Update Tb Pegawai Masters
	 *
	 * @var $id String
	 */

	public function edit_pegawai($id)
	{

		if (!$this->is_allowed('tb_pegawai_master_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);
			exit;
		}

		$this->form_validation->set_rules('nip', 'NIP', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('pegawai', 'Pegawai', 'trim|required|max_length[130]');
		$this->form_validation->set_rules('jabatan', 'Jabatan', 'trim|required|max_length[130]');
		// $this->form_validation->set_rules('telp', 'Telp', 'trim|required|max_length[130]');
		$this->form_validation->set_rules('alamat', 'Alamat', 'trim|required|max_length[130]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[130]');

		$rand = rand();
		$ekstensi =  array('png', 'jpg', 'jpeg');
		$filename = $_FILES['fotopegawai']['name'];
		$ukuran = $_FILES['fotopegawai']['size'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$folderfoto = 'Pegawai';

		if ($this->form_validation->run()) {

			$save_data = [
				'kode_tid_pegawai' => $this->input->post('kode_tid_pegawai') !== '' ? $this->input->post('kode_tid_pegawai') : null,
				'nip' => $this->input->post('nip'),
				'nama' => $this->input->post('pegawai'),
				'jabatan' => $this->input->post('jabatan'),
				'email' => $this->input->post('email'),
				'telp' => $this->input->post('telp') !== '' ? $this->input->post('telp') : null,
				'alamat' => $this->input->post('alamat'),
				'image_uri' => $rand . '_' . $_FILES['fotopegawai']['name'],
			];

			$save_tb_pegawai_master = $this->model_tb_master_pegawai->update_pegawai($id, $save_data);

			if ($id != ''){
				$this->db->where('kode_tid', $this->input->post('kode_tid_pegawai'));
				$is_success = $this->db->update('tb_master_tag_rfid', array('status_tag' => 'N', 'id_pegawai' => $id));
			}

			if ($save_tb_pegawai_master) {
				if (!in_array($ext, $ekstensi)) {
					header("location:index.php?alert=gagal_ekstensi");
				} else {
					if (!file_exists('uploads/' . $folderfoto)) {
						mkdir('uploads/' . $folderfoto, 0777, true);
					}
					if ($ukuran < 500000) {
						if (file_exists('uploads/' . $folderfoto . basename($_FILES["fotopegawai"]["name"]))) {
							echo "Sorry, file already exists.";
						} else {
							move_uploaded_file($_FILES["fotopegawai"]["tmp_name"], "uploads/" . $folderfoto . "/" . $rand . '_' . $_FILES['fotopegawai']['name']);
						}
					} else {
						header("location:index.php?alert=Ukuran File Maks .500 Kb");
					}
				}
				$this->session->set_flashdata('success', 'succes_save');


				// if ($this->input->post('save_type') == 'stay') {
				// 	$this->data['success'] = true;
				// 	$this->data['id'] 	   = $save_tb_master_aset;
				// 	$this->data['message'] = cclang('success_save_data_stay', [
				// 		admin_anchor('/tb_master_aset/edit/' . $save_tb_master_aset, 'Edit Tb Master Aset'),
				// 		admin_anchor('/tb_master_aset', ' Go back to list')
				// 	]);
				// } else {
				// 	set_message(
				// 		cclang('success_save_data_redirect', [
				// 			admin_anchor('/tb_master_aset/edit/' . $save_tb_master_aset, 'Edit Tb Master Aset')
				// 		]),
				// 		'success'
				// 	);

				// 	$this->data['success'] = true;
				// 	$this->data['redirect'] = admin_base_url('/tb_master_aset');
				// }
				redirect_back();
			} else {
				// if ($this->input->post('save_type') == 'stay') {
				// 	$this->data['success'] = false;
				// 	$this->data['message'] = cclang('data_not_change');
				// } else {
				// 	$this->data['success'] = false;
				// 	$this->data['message'] = cclang('data_not_change');
				// 	$this->data['redirect'] = admin_base_url('/tb_master_aset');
				// }
				$this->session->set_flashdata('failsave', 'cannot save data');
			}
		} else {
			$this->session->set_flashdata('err_val', 'error_validasi');
		}
	}



	/**
	 * delete Tb Pegawai Masters
	 *
	 * @var $id String
	 */
	public function delete($id = null)
	{

		$this->is_allowed('tb_pegawai_master_delete');

		$this->load->helper('file');

		$arr_id = $this->input->get('id');
		$remove = false;

		if (!empty($id)) {
			
			// echo 'your id:' . $id;

			$remove = $this->_remove($id);

		} elseif (count($arr_id) > 0) {

			foreach ($arr_id as $id) {
				// echo 'your arr_id:' . print_r($id);
				// exit;
				$remove = $this->_remove($id);
			}

		}

		// echo '<pre>';
		// print_r($remove);
		// echo '</pre>';
		// exit;

		if ($this->input->get('ajax')) {
			if ($remove) {
				$this->response([
					"success" => true,
					"message" => cclang('has_been_deleted', 'tb_master_pegawai')
				]);
			} else {
				$this->response([
					"success" => true,
					"message" => cclang('error_delete', 'tb_master_pegawai')
				]);
			}
		} else {
			if ($remove) {
				set_message(cclang('has_been_deleted', 'tb_master_pegawai'), 'success');
			} else {
				set_message(cclang('error_delete', 'tb_master_pegawai'), 'error');
			}
			redirect_back();
		}
	}

	/**
	 * delete Tb Pegawai Masters
	 *
	 * @var $id String
	 */
	// private function _remove($id)
	// {
	// 	$tb_pegawai_master = $this->model_tb_master_pegawai->find($id);
	// 	return $this->model_tb_master_pegawai->remove($id);
	// }

	private function _remove($id)
	{

		// Periksa apakah data ditemukan
		$tb_pegawai_master = $this->model_tb_master_pegawai->find($id);

		// echo '<pre>';
		// echo print_r($tb_pegawai_master);
		// echo '<pre>';
		// exit;
		
		if ($tb_pegawai_master == 1) {

			// echo 'aman bro';
			// exit;

			// Lakukan operasi dengan data (jika perlu)
			// Misalnya: if (isset($tb_pegawai_master->file_path) && file_exists($tb_pegawai_master->file_path)) {
			//     unlink($tb_pegawai_master->file_path);
			// }

			// echo 'id yg mau di remove:' . $id;
			// exit;

			// $hasil = $this->model_tb_master_pegawai->remove($id);
			
			// echo '<pre>';
			// print_r($hasil);
			// echo '</pre>';
			// exit;

			// Lanjutkan dengan penghapusan data
			return $this->model_tb_master_pegawai->remove($id);
		}

		// echo 'ini bro biang keroknya';
		// exit;
		
		return false;
	}

	/**
	 * View view Tb Pegawai Masters
	 *
	 * @var $id String
	 */
	public function view($id)
	{
		$this->is_allowed('tb_pegawai_master_view');

		$this->data['tb_master_pegawai'] = $this->model_tb_master_pegawai->get_detail_pegawai($id);
		$length = sizeof($this->data['tb_master_pegawai']);
		if ($length == 0) {
			$this->session->set_flashdata('nulldata', 'data kosong');
		}
		$this->template->title('Tb Pegawai Master Detail');
		$this->render('backend/standart/administrator/tb_pegawai_master/tb_pegawai_master_view', $this->data);
	}

	/**
	 * Export to excel
	 *
	 * @return Files Excel .xls
	 */
	public function export()
	{
		$this->is_allowed('tb_pegawai_master_export');

		$this->model_tb_master_pegawai->export(
			'tb_pegawai_master',
			'tb_pegawai_master',
			$this->model_tb_master_pegawai->field_search
		);
	}

	/**
	 * Export to PDF
	 *
	 * @return Files PDF .pdf
	 */
	public function export_pdf()
	{
		$this->is_allowed('tb_pegawai_master_export');

		$this->model_tb_master_pegawai->pdf('tb_pegawai_master', 'tb_pegawai_master');
	}


	public function single_pdf($id = null)
	{
		$this->is_allowed('tb_pegawai_master_export');

		$table = $title = 'tb_pegawai_master';
		$this->load->library('HtmlPdf');

		$config = array(
			'orientation' => 'p',
			'format' => 'a4',
			'marges' => array(5, 5, 5, 5)
		);

		$this->pdf = new HtmlPdf($config);
		$this->pdf->setDefaultFont('stsongstdlight');

		$result = $this->db->get($table);

		$data = $this->model_tb_master_pegawai->find($id);
		$fields = $result->list_fields();

		$content = $this->pdf->loadHtmlPdf('core_template/pdf/pdf_single', [
			'data' => $data,
			'fields' => $fields,
			'title' => $title
		], TRUE);

		$this->pdf->initialize($config);
		$this->pdf->pdf->SetDisplayMode('fullpage');
		$this->pdf->writeHTML($content);
		$this->pdf->Output($table . '.pdf', 'H');
	}
}


/* End of file tb_pegawai_master.php */
/* Location: ./application/controllers/administrator/Tb Pegawai Master.php */