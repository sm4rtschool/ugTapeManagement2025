<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 *| --------------------------------------------------------------------------
 *| Form Controller
 *| --------------------------------------------------------------------------
 *| form site
 *|
 */
class Form extends Admin
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_form');
	}

	/**
	 * show all forms
	 *
	 * @var $offset String
	 */
	public function index($offset = 0)
	{
		$this->is_allowed('form_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['forms'] = $this->model_form->get($filter, $field, $this->limit_page, $offset);
		$this->data['form_counts'] = $this->model_form->count_all($filter, $field);

		$config = [
			'base_url'     => ADMIN_NAMESPACE_URL  . '/form/index/',
			'total_rows'   => $this->data['form_counts'],
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Form List');
		$this->render('backend/standart/administrator/form/form_list', $this->data);
	}

	/**
	 * show all forms
	 *
	 */
	public function add()
	{
		$this->is_allowed('form_add');
		$this->template->title('Form New');
		$this->load->helper('directory');
		$this->load->library('crud_builder');

		$directories = directory_map(APPPATH . '/controllers/administrator/');

		$tables = array_diff($this->db->list_tables(), [''/*except*/]);

		$this->data['tables'] = $tables;
		$this->render('backend/standart/administrator/form/form_add', $this->data);
	}

	/**
	 * Add New forms
	 *
	 * @return JSON
	 */
	public function add_save()
	{
		if (!$this->is_allowed('form_add', false)) {
			return $this->response([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);
			exit;
		}

		$this->form_validation->set_rules('subject', 'Subject', 'trim|required|alpha_numeric_spaces|is_unique[form.subject]');
		$this->form_validation->set_rules('title', 'Subject', 'trim|alpha_numeric_spaces');

		echo $this->save_form();
	}

	/**
	 * Update view forms
	 *
	 * @var $id String
	 */
	public function edit($id)
	{
		$this->is_allowed('form_update');

		$this->load->library('crud_builder');

		$this->data = [
			'form' => $this->model_form->find($id),
			'form_field' => $this->model_form->get_form_field($id),
			'form_field_validation' => $this->model_form->get_form_field_validation($id),
			'form_field_option' => $this->model_form->get_form_field_option($id),
			'form_custom_option' => $this->model_form->get_form_custom_option($id),
			'form_custom_attribute' => $this->model_form->get_form_custom_attribute($id),
		];

		$this->template->title('Form Update');
		$this->render('backend/standart/administrator/form/form_update', $this->data);
	}

	/**
	 * Update forms
	 *
	 * @var $id String
	 */
	public function edit_save()
	{
		if (!$this->is_allowed('form_update', false)) {
			return $this->response([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);
			exit;
		}

		$this->form_validation->set_rules('subject', 'Subject', 'trim|required|alpha_numeric_spaces');
		$this->form_validation->set_rules('title', 'Title', 'trim|alpha_numeric_spaces');

		echo $this->save_form();
	}

	public function save_form()
	{
		$this->form_validation->set_rules('form[]', 'Form', 'trim|required');

		if ($this->form_validation->run()) {
			$this->load->library('parser');
			$this->load->helper('file');
			$this->load->library('crud_builder', [
				'crud' => $_POST['form']
			]);

			$subject = strtolower('form_' . str_replace('-', '_', url_title($this->input->post('subject'))));
			$field_all = $this->crud_builder->getFieldAll(true, false);
			$field_all_include_non_input = $this->crud_builder->getFieldAllForm(true);
			$this->data = [
				'php_open_tag' 				=> '<?php',
				'php_close_tag' 			=> '?>',
				'php_open_tag_echo' 		=> '<?=',
				'table_name'				=> $this->input->post('table_name'),
				'primary_key'				=> 'id',
				'subject'					=> $this->input->post('subject'),
				'non_input_able_validation' => $this->crud_builder->getNonInputableValidation(),
				'input_able_validation'		=> $this->crud_builder->getInputableValidation(),
				'show_in_add_form'			=> $this->crud_builder->getFieldShowInAddForm(),
				'show_in_update_form'		=> $this->crud_builder->getFieldShowInUpdateForm(),
				'table_name' 				=> $subject,
				'field_all'					=> $field_all,
				'field_all_include_non_input' => $field_all_include_non_input,
			];

			$this->load->dbforge();

			$this->dbforge->drop_table($subject, true);

			$fields = [
				'id' => [
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				]
			];

			foreach ($field_all as $field => $option) {
				$field_name = $option['input_name'];
				$field_type = $option['input_type'];

				$fields[$field_name] = [
					'type' => 'INT'
				];

				$list_type_data = $this->crud_builder->getListTypeData();

				$type_column = 'TEXT';

				foreach ($list_type_data as $type => $group) {
					if (in_array($field_type, $group)) {
						$type_column = $type;
					}
				}

				$fields[$field_name]['type'] = $type_column;

				if ($type_column == 'VARCHAR') {
					if (!$this->crud_builder->getFieldValidation($field, 'max_length')) {
						$fields[$field_name]['constraint'] = '225';
					}
				}

				if (!$this->crud_builder->getFieldValidation($field, 'required')) {
					$fields[$field_name]['null'] = true;
				}
				if ($max_length = $this->crud_builder->getFieldValidation($field, 'max_length')) {
					$fields[$field_name]['constraint'] = $max_length;
				}
			}

			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table($subject);

			if ($this->input->post('title')) {
				$this->data['title'] = $this->input->post('title');
			} else {
				$this->data['title'] = $this->input->post('subject');
			}

			$view_path = FCPATH . '/modules/form/views/public/' . $subject . '/';
			$public_path = FCPATH . '/modules/form/views/public/';
			$controller_path = FCPATH . '/modules/form/controllers/';
			$model_path = FCPATH . '/modules/form/models/';

			$view_admin_path = FCPATH . '/modules/form/views/backend/standart/administrator/form_builder/' . $subject . '/';
			$controller_admin_path = FCPATH . '/modules/form/controllers/backend/';

			if (!is_dir($public_path)) {
				mkdir($public_path);
			}

			if (!is_dir($view_path)) {
				mkdir($view_path);
			}

			if (!is_dir($controller_path)) {
				mkdir($controller_path);
			}

			if (!is_dir($view_admin_path)) {
				mkdir($view_admin_path);
			}

			$validate = $this->crud_builder->validateAll();

			if ($validate->isError()) {
				return $this->response([
					'success' => false,
					'message' => $validate->getErrorMessage()
				]);
				exit;
			}

			$template_form_path = 'core_template/form/';

			$builder_list = $this->parser->parse($template_form_path . 'form_submission', $this->data, true);
			$path = $view_path . $subject . '.php';
			@unlink($path);
			write_file($path, $builder_list);

			$builder_list = $this->parser->parse($template_form_path . 'form_controller', $this->data, true);
			$path = $controller_admin_path . ucwords($subject) . '.php';
			@unlink($path);
			write_file($path, $builder_list);

			$builder_list = $this->parser->parse($template_form_path . 'form_submission_controller', $this->data, true);
			$path = $controller_path . ucwords($subject) . '.php';
			@unlink($path);
			write_file($path, $builder_list);

			$builder_list = $this->parser->parse($template_form_path . 'form_model', $this->data, true);
			$path = $model_path . 'Model_' . $subject . '.php';
			@unlink($path);
			write_file($path, $builder_list);

			$builder_list = $this->parser->parse($template_form_path . 'form_list', $this->data, true);
			$path = $view_admin_path . $subject . '_list.php';
			@unlink($path);
			write_file($path, $builder_list);

			$builder_list = $this->parser->parse($template_form_path . 'form_add', $this->data, true);
			$path = $view_admin_path . $subject . '_add.php';
			@unlink($path);
			write_file($path, $builder_list);
			$this->aauth->create_perm($subject . '_add');

			$builder_list = $this->parser->parse($template_form_path . 'form_update', $this->data, true);
			$path = $view_admin_path . $subject . '_update.php';
			@unlink($path);
			write_file($path, $builder_list);
			$this->aauth->create_perm($subject . '_update');

			$builder_list = $this->parser->parse($template_form_path . 'form_view', $this->data, true);
			$path = $view_admin_path . $subject . '_view.php';
			@unlink($path);
			write_file($path, $builder_list);
			$this->aauth->create_perm($subject . '_view');

			$this->aauth->create_perm($subject . '_delete');

			$save_data = [
				'subject' 			=> $this->input->post('subject'),
				'title' 			=> $this->data['title'],
				'table_name'		=> $subject,
			];

			if ($id_form = $this->model_form->form_exist($this->input->post('id'))) {
				$this->model_form->change($id_form, $save_data);
			} else {
				$id_form = $this->model_form->store($save_data);
			}

			$save_data_field = [];
			$this->db->delete('form_field', ['form_id' => $id_form]);
			$this->db->delete('form_field_validation', ['form_id' => $id_form]);
			$this->db->delete('form_custom_option', ['form_id' => $id_form]);
			$this->db->delete('form_custom_attribute', ['form_id' => $id_form]);

			foreach ($this->input->post('form') as $val) {
				$field_name = array_keys($val)[0];
				$input_type = isset($val[$field_name]['input_type']) ? $val[$field_name]['input_type'] : '';
				$input_label = isset($val[$field_name]['input_label']) ? $val[$field_name]['input_label'] : '';
				$sort = isset($val[$field_name]['sort']) ? $val[$field_name]['sort'] : '';
				$placeholder = isset($val[$field_name]['placeholder']) ? $val[$field_name]['placeholder'] : '';
				$auto_generate_helpblock = isset($val[$field_name]['auto_generated_helpblock']) ? $val[$field_name]['auto_generated_helpblock'] : '';
				$help_block = isset($val[$field_name]['help_block']) ? $val[$field_name]['help_block'] : '';
				$input_name = isset($val[$field_name]['input_name']) ? $val[$field_name]['input_name'] : '';
				$relation_table = isset($val[$field_name]['relation_table']) ? $val[$field_name]['relation_table'] : '';
				$relation_value = isset($val[$field_name]['relation_value']) ? $val[$field_name]['relation_value'] : '';
				$relation_label = isset($val[$field_name]['relation_label']) ? $val[$field_name]['relation_label'] : '';

				$show_in_column = isset($val[$field_name]['show_in_column']) ? $val[$field_name]['show_in_column'] : '';
				$show_in_add_form = isset($val[$field_name]['show_in_add_form']) ? $val[$field_name]['show_in_add_form'] : '';
				$show_in_add_form = isset($val[$field_name]['show_in_add_form']) ? $val[$field_name]['show_in_add_form'] : '';
				$show_in_update_form = isset($val[$field_name]['show_in_update_form']) ? $val[$field_name]['show_in_update_form'] : '';
				$show_in_detail_page = isset($val[$field_name]['show_in_detail_page']) ? $val[$field_name]['show_in_detail_page'] : '';

				$save_data_field = [
					'form_id' 					=> $id_form,
					'field_name' 				=> $input_name,
					'field_label' 				=> $input_label,
					'input_type' 				=> $input_type,
					'sort' 						=> $sort,
					'placeholder' 				=> $placeholder,
					'auto_generate_help_block' 	=> $auto_generate_helpblock,
					'help_block' 				=> $help_block,
					'relation_table' 			=> $relation_table,
					'relation_value' 			=> $relation_value,
					'relation_label' 			=> $relation_label,
					'show_column' 			=> $show_in_column,
					'show_add_form' 		=> $show_in_add_form,
					'show_update_form' 		=> $show_in_update_form,
					'show_detail_page' 		=> $show_in_detail_page,
				];

				$this->db->insert('form_field', $save_data_field);

				$form_field_id = $this->db->insert_id();

				$save_data_rule = [];

				if (isset($val[$field_name]['validation']['rules'])) {
					foreach ($val[$field_name]['validation']['rules'] as $rule => $value) {
						$save_data_rule[] = [
							'form_field_id' 	=> $form_field_id,
							'form_id' 			=> $id_form,
							'validation_name' 	=> $rule,
							'validation_value'	=> $value
						];
					}
				}

				$save_data_option = [];

				if (isset($val[$field_name]['custom_option'])) {
					foreach ($val[$field_name]['custom_option'] as $option) {
						if (!empty($option['value']) or !empty($option['label'])) {
							$save_data_option[] = [
								'form_field_id' 	=> $form_field_id,
								'form_id' 			=> $id_form,
								'option_value' 		=> $option['value'],
								'option_label'		=> $option['label']
							];
						}
					}
				}

				$save_data_attribute = [];
				if (isset($val[$field_name]['custom_attributes'])) {
					foreach ($val[$field_name]['custom_attributes'] as $option) {
						if (!empty($option['value']) or !empty($option['label'])) {
							$save_data_attribute[] = [
								'form_field_id' 	=> $form_field_id,
								'form_id' 			=> $id_form,
								'attribute_value'	=> $option['value'],
								'attribute_label'	=> $option['label']
							];
						}
					}
				}

				if (count($save_data_rule)) {
					$this->db->insert_batch('form_field_validation', $save_data_rule);
				}
				if (count($save_data_option)) {
					$this->db->insert_batch('form_custom_option', $save_data_option);
				}
				if (count($save_data_attribute)) {
					$this->db->insert_batch('form_custom_attribute', $save_data_attribute);
				}
			}
			if ($this->input->post('save_type') == 'stay') {
				$this->response['success'] = true;
				$this->response['message'] = cclang('success_save_data_stay', [
					admin_anchor('/form', ' Go back to list'),
					admin_anchor('/' . $subject, ' View')
				]);
			} else {
				set_message(
					cclang('success_save_data_redirect', [
						admin_anchor('/' . $subject, ' View')
					]),
					'success'
				);

				$this->response['success'] = true;
				$this->response['redirect'] = admin_site_url('/form');
			}
		} else {
			$this->response['success'] = false;
			$this->response['message'] = validation_errors();
		}

		$this->response($this->response);
	}

	/**
	 * preview form builder
	 *
	 * @return JSON
	 */
	public function preview()
	{
		if (!$this->is_allowed('form_add', false)) {
			return $this->response([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);
			exit;
		}

		$this->load->library('parser');
		$this->load->helper('file');
		$this->load->library('crud_builder', [
			'crud' => $_POST['form']
		]);

		$template_form_path = 'core_template/form/';

		$this->data = [
			'php_open_tag' 				=> '<?php',
			'php_close_tag' 			=> '?>',
			'php_open_tag_echo' 		=> '<?=',
			'table_name'				=> $this->input->post('table_name'),
			'primary_key'				=> $this->input->post('primary_key'),
			'subject'					=> $this->input->post('subject'),
			'non_input_able_validation' => $this->crud_builder->getNonInputableValidation(),
		];

		$validate = $this->crud_builder->validateAll();

		if ($validate->isError()) {
			return $this->response([
				'success' => false,
				'message' => $validate->getErrorMessage()
			]);
			exit;
		}

		$preview = $this->parser->parse($template_form_path . 'form_preview', $this->data, true);

		return $this->response(['success' => true, 'html' => $preview]);
	}

	/**
	 * delete forms
	 *
	 * @var $id String
	 */
	public function delete($id = null)
	{
		$this->is_allowed('form_delete');

		$this->load->helper('file');

		$arr_id = $this->input->get('id');
		$remove = false;

		if (!empty($id)) {
			$remove = $this->_remove($id);
		} elseif (count($arr_id) > 0) {
			foreach ($arr_id as $id) {
				$remove = $this->_remove($id);
			}
		}

		if ($remove) {
			set_message(cclang('has_been_deleted', 'Form'), 'success');
		} else {
			set_message(cclang('error_delete', 'Form'), 'error');
		}

		redirect(ADMIN_NAMESPACE_URL . '/form');
	}

	/**
	 * delete forms
	 *
	 * @var $id String
	 */
	private function _remove($id)
	{
		$this->load->helper('file');
		$form = $this->model_form->find($id);

		$subject = $form->table_name;

		$this->db->delete('form_field', ['form_id' => $id]);
		$this->db->delete('form_field_validation', ['form_id' => $id]);
		$this->db->delete('form_custom_option', ['form_id' => $id]);
		$this->db->delete('form_custom_attribute', ['form_id' => $id]);


		$this->db
			->where_in('name', [
				$subject . '_list',
				$subject . '_add',
				$subject . '_update',
				$subject . '_delete',
				$subject . '_view',
			])
			->delete('aauth_perms');

		$subject = $form->table_name;
		$view_path = FCPATH . '/modules/form/views/public/' . $subject . '/';
		$public_path = FCPATH . '/modules/form/views/public/' . $subject;
		$controller_path = FCPATH . 'modules/form/controllers/' . ucwords($subject) . '.php';
		$model_path = FCPATH . '/modules/form/models/';

		$view_admin_path = FCPATH . '/modules/form/views/backend/standart/administrator/form_builder/' . $subject;
		$controller_admin_path = FCPATH . '/modules/form/controllers/backend/';

		delete_files($view_path, true, false, 2);

		delete_files($controller_path, false, false, 2);

		delete_files($public_path . $subject, true, false, 2);
		delete_files($view_admin_path, true, false, 2);

		$template_form_path = 'core_template/form/';

		if (is_file($template_form_path)) {
			unlink($template_form_path);
		}
		if (is_file($controller_path)) {
			unlink($controller_path);
		}

		$file_view_path = $view_path . $subject . '.php';
		if (is_file($file_view_path)) {
			unlink($file_view_path);
		}

		$file_controller_admin_path = $controller_admin_path . ucwords($subject) . '.php';
		if (is_file($file_controller_admin_path)) {
			unlink($file_controller_admin_path);
		}

		$file_controller_path = $controller_path . ucwords($subject) . '.php';
		if (is_file($file_controller_path)) {
			unlink($file_controller_path);
		}

		$file_model_path = $model_path . 'Model_' . $subject . '.php';
		if (is_file($file_model_path)) {
			unlink($file_model_path);
		}

		$this->load->dbforge();

		$this->dbforge->drop_table($subject, true);

		return $this->model_form->remove($id);
	}

	/**
	 * View view form
	 *
	 * @var $id String
	 */
	public function view($id)
	{
		$this->is_allowed('form_view');

		$this->data['form'] = $this->model_form->find($id);
		$form_name = strtolower($this->data['form']->table_name);
		$this->data['view'] = $this->load->view('public/' . $form_name . '/' . $form_name, [], true);

		$this->template->title('Form View');
		$this->render('backend/standart/administrator/form/form_view', $this->data);
	}

	/**
	 * View view form
	 *
	 * @var $id String
	 */
	public function preview_form($id)
	{
		$this->is_allowed('form_view');

		$this->data['form'] = $this->model_form->find($id);
		$form_name = strtolower($this->data['form']->table_name);
		$this->data['view'] = $this->load->view('public/' . $form_name . '/' . $form_name, [], true);

		$this->template->title('Form Preview');
		$this->load->view('backend/standart/administrator/form/form_preview', $this->data);
	}

	/**
	 * Export to excel
	 *
	 * @return Files Excel .xls
	 */
	public function export()
	{
		$this->is_allowed('form_export');

		$this->model_form->export('form', 'form');
	}

	/**
	 * Get field data
	 *
	 * @return html
	 */
	public function get_field_data($table)
	{
		$this->data['html'] = $this->load->view('backend/standart/administrator/form/form_field_data.php', ['table' => $table], true);
		$this->data['subject'] = ucwords(clean_snake_case($table));
		$this->data['success'] = true;

		return $this->response($this->data);
	}

	/**
	 * Get field table
	 *
	 * @return html
	 */
	public function get_list_field_id($table)
	{
		$this->data['html'] = $this->load->view('backend/standart/administrator/form/form_list_field.php', ['table' => $table], true);
		$this->data['success'] = true;

		return $this->response($this->data);
	}

	/**
	 * Get field table
	 *
	 * @return html
	 */
	public function get_list_field_label($table)
	{
		$this->data['html'] = $this->load->view('backend/standart/administrator/form/form_list_field_label.php', ['table' => $table], true);
		$this->data['success'] = true;

		return $this->response($this->data);
	}

	/**
	 * Get form select
	 *
	 * @return json
	 */
	public function get_form()
	{
		$this->data['forms'] = $this->model_form->find_all();
		$this->data['html'] = $this->load->view('backend/standart/administrator/form/form_list_data.php', $this->data, true);
		$this->data['success'] = true;

		return $this->response($this->data);
	}
}

/* End of file Form.php */
/* Location: ./application/controllers/administrator/Form.php */