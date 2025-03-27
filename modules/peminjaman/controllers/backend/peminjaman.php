<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 *| --------------------------------------------------------------------------
 *| Tb Master Transaksi Controller
 *| --------------------------------------------------------------------------
 *| Tb Master Transaksi site
 *|
 */
class peminjaman extends Admin
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('tb_master_aset/model_tb_master_aset');

		$this->load->model('model_peminjaman');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	/**
	 * show all Tb Master Transaksis
	 *
	 * @var $offset String
	 */
	public function index($offset = 0)
	{
		$this->is_allowed('peminjaman_list');
		
		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['peminjamans'] = $this->model_peminjaman->get($filter, $field, $this->limit_page, $offset);
		$this->data['peminjaman_counts'] = $this->model_peminjaman->count_all($filter, $field);

		$config = [
			'base_url'     => ADMIN_NAMESPACE_URL  . '/peminjaman/index/',
			'total_rows'   => $this->data['peminjaman_counts'],
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->data['tables'] = $this->load->view('backend/standart/administrator/peminjaman/tb_master_transaksi_data_table', $this->data, true);

		if ($this->input->get('ajax')) {
			$this->response([
				'tables' => $this->data['tables'],
				'pagination' => $this->data['pagination'],
				'total_row' => $this->data['peminjaman_counts']
			]);
		}

		$this->template->title('Peminjaman List');
		$this->render('backend/standart/administrator/peminjaman/peminjaman_list', $this->data);
	}

	/**
	 * Add new tb_master_transaksis
	 *
	 */
	public function add()
	{
		$this->is_allowed('peminjaman_add');

		$this->data['pengaturan_sistem'] = $this->model_peminjaman->getPengaturanSistem();
		$this->data['tb_master_asets'] = $this->model_tb_master_aset->get_aset();

		$this->template->title('Peminjaman Aset');
		$this->render('backend/standart/administrator/peminjaman/peminjaman_add', $this->data);
	}

	public function serverSideData()
    {
        
        $columns = array(
            0 => 'checkbox_id_master_aset',
            1 => 'id_aset',
            2 => 'nama_aset',
            3 => 'kode_aset',
            4 => 'nup',
			5 => 'kode_tid',
			6 => 'kode_epc'
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

        $totalData = $this->model_peminjaman->count_all_content();
        $totalFiltered = $totalData;

        if(empty($this->input->post('search')['value'])) {
            $contents = $this->model_peminjaman->get_content($limit, $start, $order, $dir, $select_all, $filter_data);
        } else {
            $search = $this->input->post('search')['value'];
            $contents =  $this->model_peminjaman->content_search($limit, $start, $search, $order, $dir, $select_all, $filter_data);
            $totalFiltered = $this->model_peminjaman->content_search_count($search, $select_all, $filter_data);
        }

        $data = array();
        if(!empty($contents)) {
            $autoNumber = $start + 1;
            foreach($contents as $row) {
                $nestedData['checkbox_id_master_aset'] = '<input type="checkbox" value="'.$row->id_aset.'" class="cekbok" data-id="'.$row->id_aset.'" data-kode-aset="'.$row->kode_aset.'" data-nup="'.$row->nup.'" data-nama-aset="'.$row->nama_aset.'" data-kode-tid="'.$row->kode_tid.'" data-kode-epc="'.$row->kode_epc.'">';
				$nestedData['auto_number'] = $autoNumber;
				$nestedData['id'] = $row->id_aset;
                $autoNumber++;
                $nestedData['nama_aset'] = $row->nama_aset;
                $nestedData['kode_aset'] = $row->kode_aset;
                $nestedData['nup'] = $row->nup;
                $nestedData['kode_tid'] = $row->kode_tid;
                $nestedData['kode_epc'] = $row->kode_epc;
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

		if (!$this->is_allowed('peminjaman_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);
			exit;
		}

		$this->form_validation->set_rules('tipe_transaksi', 'Tipe Transaksi', 'trim|required');
		$this->form_validation->set_rules('status_transaksi', 'Status Transaksi', 'trim|required');
		$this->form_validation->set_rules('tgl_awal_transaksi', 'Tgl Awal Transaksi', 'trim|required');
		$this->form_validation->set_rules('tgl_akhir_transaksi', 'Tgl Akhir Transaksi', 'trim|required');
		$this->form_validation->set_rules('ket_transaksi', 'Ket Transaksi', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('id_pegawai', 'Id Pegawai', 'trim|required');
		// $this->form_validation->set_rules('id_area', 'Id Area', 'trim|required');
		// $this->form_validation->set_rules('id_gedung', 'Id Gedung', 'trim|required');
		// $this->form_validation->set_rules('id_ruangan', 'Id Ruangan', 'trim|required');
		// $this->form_validation->set_rules('id_area2', 'Id Area2', 'trim|required');
		// $this->form_validation->set_rules('id_gedung2', 'Id Gedung2', 'trim|required');
		// $this->form_validation->set_rules('id_ruangan2', 'Id Ruangan2', 'trim|required');

		if ($this->form_validation->run()) {

			$save_data_master_transaksi = [
				// 'kode_transaksi' => $this->input->post('kode_transaksi'),
				'tipe_transaksi' => $this->input->post('tipe_transaksi'),
				'status_transaksi' => $this->input->post('status_transaksi'),
				'tgl_input' => date('Y-m-d H:i:s'),
				'tgl_awal_transaksi' => $this->input->post('tgl_awal_transaksi'),
				'tgl_akhir_transaksi' => $this->input->post('tgl_akhir_transaksi'),
				'id_pegawai_input' => $this->input->post('id_pegawai_input'),
				'nama_pegawai_input' => $this->input->post('nama_pegawai_input'),
				'id_pegawai' => $this->input->post('id_pegawai'),
				'nama_pegawai' => $this->input->post('nama_pegawai'),
				'ket_transaksi' => $this->input->post('ket_transaksi'),
				'id_area' => $this->input->post('id_area'),
				'id_gedung' => $this->input->post('id_gedung'),
				'id_ruangan' => $this->input->post('id_ruangan'),
				// 'id_area2' => $this->input->post('id_area2'),
				// 'id_gedung2' => $this->input->post('id_gedung2'),
				// 'id_ruangan2' => $this->input->post('id_ruangan2'),
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
			for ($i = 0; $i < count($array_data_aset); $i++) {
				$linked_data[] = array(
					'aset' => $array_data_aset[$i],
					'aset' => $uniqueDataArray[$i]
				);
			}

			// echo '<pre>';
			// print_r($array_data_aset);
			// echo '</pre>';
			// exit();

			$save_register_aset = $id = $this->model_peminjaman->saveRegisterAset($save_data_master_transaksi, $save_data_detail_transaksi, $array_data_aset);
			// $save_register_aset = $this->model_tb_master_transaksi->saveRegisterAset($save_data_master_transaksi, $save_data_detail_transaksi, $linked_data);

			// echo '<pre>';
			// print_r($save_register_aset);
			// echo '</pre>';
			// exit();

			if ($save_register_aset) {

				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_register_aset;
					$this->data['message'] = cclang('success_save_data_stay', [admin_anchor('/peminjaman', 'Go back to list')]);
				} else {
					set_message(cclang('success_save_data_redirect', [admin_anchor('/peminjaman/view/' . $save_register_aset, 'See detail')]), 'success');
					$this->data['success'] = true;
					$this->data['redirect'] = admin_base_url('/peminjaman');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = admin_base_url('/peminjaman');
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
		$this->is_allowed('peminjaman_update');

		$this->data['peminjaman'] = $this->model_peminjaman->find($id);

		$this->template->title('Peminjaman Update');
		$this->render('backend/standart/administrator/peminjaman/peminjaman_update', $this->data);
	}

	/**
	 * Update Tb Master Transaksis
	 *
	 * @var $id String
	 */
	public function edit_save($id)
	{
		if (!$this->is_allowed('peminjaman_update', false)) {
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








			$save_tb_master_transaksi = $this->model_peminjaman->change($id, $save_data);

			if ($save_tb_master_transaksi) {





				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						admin_anchor('/peminjaman', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', []),
						'success'
					);

					$this->data['success'] = true;
					$this->data['redirect'] = admin_base_url('/peminjaman');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = admin_base_url('/peminjaman');
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
		$this->is_allowed('peminjaman_delete');

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

		if ($this->input->get('ajax')) {
			if ($remove) {
				$this->response([
					"success" => true,
					"message" => cclang('has_been_deleted', 'peminjaman')
				]);
			} else {
				$this->response([
					"success" => true,
					"message" => cclang('error_delete', 'peminjaman')
				]);
			}
		} else {
			if ($remove) {
				set_message(cclang('has_been_deleted', 'peminjaman'), 'success');
			} else {
				set_message(cclang('error_delete', 'peminjaman'), 'error');
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
		$this->is_allowed('peminjaman_view');

		$this->data['tb_master_transaksi'] = $this->model_peminjaman->getTransaksiById($id);
		$this->data['tb_detail_transaksi'] = $this->model_peminjaman->getDetailTransaksiById($id);
		$this->data['pengaturan_sistem'] = $this->model_peminjaman->getPengaturanSistem();
		$this->template->title('Detail Peminjaman');
		$this->data['id'] = $id; // Pastikan ID diteruskan ke view
		$this->render('backend/standart/administrator/peminjaman/peminjaman_view', $this->data);
	}

	public function approve($id)
	{
		// Ambil detail aset berdasarkan ID peminjaman
		$this->load->model('model_peminjaman');
		$detail_aset = $this->model_peminjaman->getDetailTransaksiById($id);

		// Debugging $detail_aset
		if (!$detail_aset) {
			show_error('Detail aset tidak ditemukan untuk ID: ' . $id, 404);
		}

		// Ambil keterangan approve dari request POST
		$keterangan_approve = $this->input->post('keterangan_approve');
		if (!$keterangan_approve) {
			show_error('Keterangan approve tidak ditemukan!', 400);
		}

		// Simpan file foto approve
		if (!empty($_FILES['foto']['name'])) {
			$upload_dir = 'uploads/Peminjaman/';
			
			// Pastikan direktori ada
			if (!is_dir($upload_dir)) {
				if (!mkdir($upload_dir, 0755, true)) {
					show_error('Gagal membuat direktori unggahan: ' . $upload_dir, 500);
				}
			}
		
			$file_name = time() . '_' . basename($_FILES['foto']['name']);
			$file_path = $upload_dir . $file_name;
		
			// Simpan file ke direktori
			if (move_uploaded_file($_FILES['foto']['tmp_name'], $file_path)) {
				$response['foto_url'] = base_url($file_path);
			} else {
				// Tambahkan logging error
				log_message('error', 'Gagal mengunggah file: ' . $_FILES['foto']['error']);
				
				$response['success'] = false;
				$response['message'] = 'Gagal mengunggah foto.';
				echo json_encode($response);
				exit;
			}
		}

		// Mulai transaksi untuk memastikan atomicity
		$this->db->trans_start();

		// Update status transaksi menjadi 2 (approve) dan simpan keterangan approve
		$this->db->where('id', $id);
		$this->db->update('tb_master_transaksi', [
			'status_transaksi' => 2,    // Set status menjadi 2 (approve)
			'ket_transaksi3' => $keterangan_approve,  // Simpan keterangan approve
			'image_uri2' => $file_name		//menyimpan informasi nama foto
		]);

		// Perbarui status aset terkait dengan peminjaman
		foreach ($detail_aset as $aset) {
			// Update status aset menjadi 1 dan set borrow menjadi 0
			$this->db->where('id_aset', $aset->id_aset);
			$this->db->update('tb_master_aset', [
				'status' => 2,  // Aset sudah kembali
				'borrow' => 1,   // Aset dipinjam sudah di approve
				'tipe_moving' => 1,   // Aset ada izin moving
				'status_peminjaman' => 2 // di approve
			]);
		}

		// Selesaikan transaksi
		$this->db->trans_complete();

		// Cek apakah transaksi berhasil
		if ($this->db->trans_status() === FALSE) {
			log_message('error', 'Gagal melakukan update transaksi approve untuk ID: ' . $id);
			show_error('Terjadi kesalahan saat memproses permintaan. Silakan coba lagi.', 500);
		}

		// Berikan response sukses')
		echo json_encode(['success' => true]);
	}

	public function selesai($id)
	{
		// Ambil detail aset berdasarkan ID peminjaman
		$this->load->model('model_peminjaman');
		$detail_aset = $this->model_peminjaman->getDetailTransaksiById($id);

		// Debugging $detail_aset
		if (!$detail_aset) {
			show_error('Detail aset tidak ditemukan untuk ID: ' . $id, 404);
		}

		// Ambil keterangan selesai dari request POST
		$keterangan_selesai = $this->input->post('keterangan_selesai');
		if (!$keterangan_selesai) {
			show_error('Keterangan selesai tidak ditemukan!', 400);
		}

		// Simpan file foto selesai
		if (!empty($_FILES['foto']['name'])) {
			$upload_dir = 'uploads/Peminjaman/';
			
			// Pastikan direktori ada
			if (!is_dir($upload_dir)) {
				if (!mkdir($upload_dir, 0755, true)) {
					show_error('Gagal membuat direktori unggahan: ' . $upload_dir, 500);
				}
			}
		
			$file_name = time() . '_' . basename($_FILES['foto']['name']);
			$file_path = $upload_dir . $file_name;
		
			// Simpan file ke direktori
			if (move_uploaded_file($_FILES['foto']['tmp_name'], $file_path)) {
				$response['foto_url'] = base_url($file_path);
			} else {
				// Tambahkan logging error
				log_message('error', 'Gagal mengunggah file: ' . $_FILES['foto']['error']);
				
				$response['success'] = false;
				$response['message'] = 'Gagal mengunggah foto.';
				echo json_encode($response);
				exit;
			}
		}

		// Mulai transaksi untuk memastikan atomicity
		$this->db->trans_start();

		// Update status transaksi menjadi 3 (selesai) dan simpan keterangan selesai
		$this->db->where('id', $id);
		$this->db->update('tb_master_transaksi', [
			'tgl_akhir_transaksi' => date('Y-m-d H:i:s'),    // Set Tgl Hari Ini
			'status_transaksi' => 3,    // Set status menjadi 3 (selesai)
			'ket_transaksi2' => $keterangan_selesai,  // Simpan keterangan selesai
			'image_uri' => $file_name		//menyimpan informasi nama foto
		]);

		// Perbarui status aset terkait dengan peminjaman
		foreach ($detail_aset as $aset) {
			// Update status aset menjadi 1 dan set borrow menjadi 0
			$this->db->where('id_aset', $aset->id_aset);
			$this->db->update('tb_master_aset', [
				'status' => 1,  // Aset sudah kembali
				'borrow' => 0,   // Aset tidak dipinjam lagi
				'tipe_moving' => 0,   // Aset tidak ada izin moving
				'id_peminjam' => 0, // Id Peminjam Kosong
				// 'tgl_peminjaman' => "0000-00-00 00:00:00", // Tgl Peminjaman Kosong	
				'tgl_pengembalian' => date('Y-m-d H:i:s'),
				'status_peminjaman' => 3 // proses peminjaman selesai, aset sudah dikembalikan
			]);
		}

		// Selesaikan transaksi
		$this->db->trans_complete();

		// Cek apakah transaksi berhasil
		if ($this->db->trans_status() === FALSE) {
			log_message('error', 'Gagal melakukan update transaksi selesai untuk ID: ' . $id);
			show_error('Terjadi kesalahan saat memproses permintaan. Silakan coba lagi.', 500);
		}

		// Berikan response sukses')
		echo json_encode(['success' => true]);
	}

	public function batal($id)
	{
		// Ambil detail aset berdasarkan ID peminjaman
		$this->load->model('model_peminjaman');
		$detail_aset = $this->model_peminjaman->getDetailTransaksiById($id);

		// Debugging $detail_aset
		if (!$detail_aset) {
			show_error('Detail aset tidak ditemukan untuk ID: ' . $id, 404);
		}

		// Ambil keterangan batal dari request POST
		$keterangan_batal = $this->input->post('keterangan_batal');
		if (!$keterangan_batal) {
			show_error('Keterangan batal tidak ditemukan!', 400);
		}

		// Simpan file foto batal
		if (!empty($_FILES['foto']['name'])) {

			$upload_dir = 'uploads/Peminjaman/';
			
			// Pastikan direktori ada
			if (!is_dir($upload_dir)) {
				if (!mkdir($upload_dir, 0755, true)) {
					show_error('Gagal membuat direktori unggahan: ' . $upload_dir, 500);
				}
			}
		
			$file_name = time() . '_' . basename($_FILES['foto']['name']);
			$file_path = $upload_dir . $file_name;
		
			// Simpan file ke direktori
			if (move_uploaded_file($_FILES['foto']['tmp_name'], $file_path)) {
				$response['foto_url'] = base_url($file_path);
			} else {
				// Tambahkan logging error
				log_message('error', 'Gagal mengunggah file: ' . $_FILES['foto']['error']);
				
				$response['success'] = false;
				$response['message'] = 'Gagal mengunggah foto.';
				echo json_encode($response);
				exit;
			}
		}

		// Mulai transaksi untuk memastikan atomicity
		// $this->db->trans_start();

		// Update status transaksi menjadi 4 (batal) dan simpan keterangan batal
		$this->db->where('id', $id);
		$this->db->update('tb_master_transaksi', [
			'status_transaksi' => 4,    // Set status menjadi 4 (batal)
			'ket_transaksi2' => $keterangan_batal,  // Simpan keterangan batal
			'image_uri' => $file_name		//menyimpan informasi nama foto
		]);

		// Perbarui status aset terkait dengan peminjaman
		foreach ($detail_aset as $aset) {
			
			// Dapatkan status aset saat ini
			$this->db->select('status');
			$this->db->from('tb_master_aset');
			$this->db->where('id_aset', $aset->id_aset);
			$current_status = $this->db->get()->row()->status;

			// Tentukan pembaruan berdasarkan status saat ini
			if ($current_status == 4) {
				// Jika status saat ini adalah 4, hanya update borrow menjadi 0
				$this->db->where('id_aset', $aset->id_aset);
				$this->db->update('tb_master_aset', [
					'borrow' => 0,  // Aset tidak dipinjam lagi
					'tipe_moving' => 0,   // Aset tidak ada izin moving
					'id_peminjam' => 0, // Id Peminjam Kosong
					// 'tgl_peminjaman' => "0000-00-00 00:00:00", // Tgl Peminjaman Kosong
					'tgl_peminjaman' => null, // Tgl Peminjaman Kosong
					// 'tgl_pengembalian' => "0000-00-00 00:00:00", // Tgl Pengembalian Kosong
					'tgl_pengembalian' => null, // Tgl Pengembalian Kosong
					'status_peminjaman' => 0 // peminjaman dibatalkan sebelum di approve / direject by admin, reset aja datanya jadi 0
				]);

				log_message('info', 'Status aset tidak diubah karena status saat ini 4. Borrow diperbarui untuk ID Aset: ' . $aset->id_aset);
			} else {
				// Jika status saat ini bukan 4, update status menjadi 1 dan borrow menjadi 0
				$this->db->where('id_aset', $aset->id_aset);
				$this->db->update('tb_master_aset', [
					'status' => 1,  // Aset sudah kembali
					'borrow' => 0,  // Aset tidak dipinjam lagi
					'tipe_moving' => 0,   // Aset tidak ada izin moving
					'id_peminjam' => 0, // Id Peminjam Kosong
					// 'tgl_peminjaman' => "0000-00-00 00:00:00", // Tgl Peminjaman Kosong
					'tgl_peminjaman' => null, // Tgl Peminjaman Kosong
					// 'tgl_pengembalian' => "0000-00-00 00:00:00", // Tgl Pengembalian Kosong
					'tgl_pengembalian' => null, // Tgl Pengembalian Kosong
					'status_peminjaman' => 0 // peminjaman dibatalkan sebelum di approve / direject by admin, reset aja datanya jadi 0
				]);

				log_message('info', 'Status aset diperbarui menjadi 1 dan borrow diubah untuk ID Aset: ' . $aset->id_aset);
			}
		}

		// Selesaikan transaksi
		// $this->db->trans_complete();

		// Cek apakah transaksi berhasil
		// if ($this->db->trans_status() === FALSE) {
		// 	log_message('error', 'Gagal melakukan update transaksi pembatalan untuk ID: ' . $id);
		// 	show_error('Terjadi kesalahan saat memproses permintaan. Silakan coba lagi.', 500);
		// }

		// Berikan response sukses')
		echo json_encode(['success' => true]);
	}

	/**
	 * delete Tb Master Transaksis
	 *
	 * @var $id String
	 */
	private function _remove($id)
	{
		$tb_master_transaksi = $this->model_peminjaman->find($id);
		return $this->model_peminjaman->remove($id);
	}


	/**
	 * Export to excel
	 *
	 * @return Files Excel .xls
	 */
	public function export()
	{
		$this->is_allowed('peminjaman_export');

		$this->model_peminjaman->export(
			'peminjaman',
			'peminjaman',
			$this->model_peminjaman->field_search
		);
	}

	/**
	 * Export to PDF
	 *
	 * @return Files PDF .pdf
	 */
	public function export_pdf()
	{
		$this->is_allowed('peminjaman_export');

		$this->model_tb_master_transaksi->pdf('peminjaman', 'peminjaman');
	}


	public function single_pdf($id = null)
	{
		$this->is_allowed('peminjaman_export');

		$table = $title = 'peminjaman';
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
		$this->pdf->Output($table . '.pdf', 'H');
	}

	public function ajax_id_gedung($id = null)
	{
		if (!$this->is_allowed('peminjaman_list', false)) {
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
		if (!$this->is_allowed('peminjaman_list', false)) {
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
		$check = $this->db->get_where(
			'tb_master_tag_rfid',
			[
				'kode_tid' => $tid,
				'status_tag' => 'Y'
			]
		)->num_rows();

		$response = [
			'check' => $check
		];
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

		$filter_data = array();

		$filter_data = array(
			'id_area' => $id_area,
			'id_gedung' => $id_gedung,
			'id_ruangan' => $id_ruangan
		);

		$results = $this->model_peminjaman->get_all_aset($filter_data);
		
		$response = [
			'success' => true,
			'data' => $results
		];

		$this->response($response);
	}

	public function get_search_aset()
	{
		try {
			// Ambil parameter id dari query string
			$id = $this->input->get('id');

			// Validasi parameter id
			if (empty($id)) {
				throw new Exception('Parameter "id" is required. Received ID: ' . var_export($id, true)); // Menampilkan nilai id jika kosong
			}

			$filter_data = [
				'id_transaksi' => $id
			];

			// Panggil model untuk mendapatkan data
			$results = $this->model_peminjaman->get_all_search_aset($filter_data);

			// Periksa apakah data ditemukan
			if (empty($results)) {
				throw new Exception('No data found for the given ID: '. json_encode($filter_data));
			}

			// Berikan respons sukses
			$response = [
				'success' => true,
				'data' => $results
			];
			$this->response($response);

		} catch (Exception $e) {
			// Laporkan error melalui log dan kirim respons error
			log_message('error', 'Error dalam proses: ' . $e->getMessage());
			$response = [
				'success' => false,
				'message' => $e->getMessage()
			];
			$this->response($response, 500); // Kirim status code 500
		}
	}


}

/* End of file tb_master_transaksi.php */
/* Location: ./application/controllers/administrator/Tb Master Transaksi.php */