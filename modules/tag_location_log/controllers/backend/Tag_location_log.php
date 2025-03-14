<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Tag Location Log Controller
*| --------------------------------------------------------------------------
*| Tag Location Log site
*|
*/
class Tag_location_log extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_tag_location_log');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	/**
	* show all Tag Location Logs
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('tag_location_log_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['tag_location_logs'] = $this->model_tag_location_log->get($filter, $field, $this->limit_page, $offset);
		$this->data['tag_location_log_counts'] = $this->model_tag_location_log->count_all($filter, $field);

		$config = [
			'base_url'     => ADMIN_NAMESPACE_URL  . '/tag_location_log/index/',
			'total_rows'   => $this->data['tag_location_log_counts'],
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);
		
		$this->data['tables'] = $this->load->view('backend/standart/administrator/tag_location_log/tag_location_log_data_table', $this->data, true);
		
		if ($this->input->get('ajax')) {
			$this->response([
				'tables' => $this->data['tables'],
				'pagination' => $this->data['pagination'],
				'total_row' => $this->data['tag_location_log_counts']
			]);
		}

		$this->template->title('Location Log List');
		$this->render('backend/standart/administrator/tag_location_log/tag_location_log_list', $this->data);
	}
	
	/**
	* Add new tag_location_logs
	*
	*/
	public function add()
	{
		$this->is_allowed('tag_location_log_add');

		$this->template->title('Location Log New');
		$this->render('backend/standart/administrator/tag_location_log/tag_location_log_add', $this->data);
	}

	/**
	* Add New Tag Location Logs
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('tag_location_log_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		

		$this->form_validation->set_rules('rfid_id', 'RFID', 'trim|required|max_length[11]');
		

		$this->form_validation->set_rules('librarian_id', 'Librarian', 'trim|required|max_length[11]');
		

		$this->form_validation->set_rules('log_status', 'Status', 'trim|required');
		

		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'rfid_id' => $this->input->post('rfid_id'),
				'librarian_id' => $this->input->post('librarian_id'),
				'log_status' => $this->input->post('log_status'),
				'log_created' => date('Y-m-d H:i:s'),
				'log_createdby' => get_user_data('username'),
			];

			
			



			
			
			$save_tag_location_log = $id = $this->model_tag_location_log->store($save_data);
            

			if ($save_tag_location_log) {
				
				
					
				
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_tag_location_log;
					$this->data['message'] = cclang('success_save_data_stay', [
						admin_anchor('/tag_location_log/edit/' . $save_tag_location_log, 'Edit Tag Location Log'),
						admin_anchor('/tag_location_log', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						admin_anchor('/tag_location_log/edit/' . $save_tag_location_log, 'Edit Tag Location Log')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = admin_base_url('/tag_location_log');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = admin_base_url('/tag_location_log');
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
	* Update view Tag Location Logs
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('tag_location_log_update');

		$this->data['tag_location_log'] = $this->model_tag_location_log->find($id);

		$this->template->title('Location Log Update');
		$this->render('backend/standart/administrator/tag_location_log/tag_location_log_update', $this->data);
	}

	/**
	* Update Tag Location Logs
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('tag_location_log_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
				

		

		

		
		if ($this->form_validation->run()) {
		
			$save_data = [
			];

			

			


			
			
			$save_tag_location_log = $this->model_tag_location_log->change($id, $save_data);

			if ($save_tag_location_log) {

				

				
				
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						admin_anchor('/tag_location_log', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = admin_base_url('/tag_location_log');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = admin_base_url('/tag_location_log');
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
	* delete Tag Location Logs
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('tag_location_log_delete');

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
					"message" => cclang('has_been_deleted', 'tag_location_log')
				]);
			} else {
				$this->response([
					"success" => true,
					"message" => cclang('error_delete', 'tag_location_log')
				]);
			}

		} else {
			if ($remove) {
				set_message(cclang('has_been_deleted', 'tag_location_log'), 'success');
			} else {
				set_message(cclang('error_delete', 'tag_location_log'), 'error');
			}
			redirect_back();
		}

	}

		/**
	* View view Tag Location Logs
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('tag_location_log_view');

		$this->data['tag_location_log'] = $this->model_tag_location_log->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Location Log Detail');
		$this->render('backend/standart/administrator/tag_location_log/tag_location_log_view', $this->data);
	}
	
	/**
	* delete Tag Location Logs
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$tag_location_log = $this->model_tag_location_log->find($id);

		
		
		return $this->model_tag_location_log->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('tag_location_log_export');

		$this->model_tag_location_log->export(
			'tag_location_log', 
			'tag_location_log',
			$this->model_tag_location_log->field_search
		);
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('tag_location_log_export');

		$this->model_tag_location_log->pdf('tag_location_log', 'tag_location_log');
	}


	public function single_pdf($id = null)
	{
		$this->is_allowed('tag_location_log_export');

		$table = $title = 'tag_location_log';
		$this->load->library('HtmlPdf');
      
        $config = array(
            'orientation' => 'p',
            'format' => 'a4',
            'marges' => array(5, 5, 5, 5)
        );

        $this->pdf = new HtmlPdf($config);
        $this->pdf->setDefaultFont('stsongstdlight'); 

        $result = $this->db->get($table);
       
        $data = $this->model_tag_location_log->find($id);
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

	
}


/* End of file tag_location_log.php */
/* Location: ./application/controllers/administrator/Tag Location Log.php */