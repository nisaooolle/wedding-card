<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Page extends CI_Controller
{
	//start construct
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_model');
		$this->load->helper('my_helper');
		$this->load->library('upload');
		if ($this->session->userData('logged_in') != true) {
			redirect(base_url() . 'auth');
		}
	}
	//end construct

	//start dashboard admin dan karyawan
	public function dashboard()
	{
		if ($this->session->userdata('role') === "admin") {
			$data['dashboard'] = $this->m_model->get_all_karyawan();
			$data['total_data'] = count($data['dashboard']);
			$data['get_user'] = $this->m_model->get_data('user')->result();
			$data['total_data_user'] = count($data['get_user']);
			$data['total_kerja'] = $this->m_model->getTotalJamMasuk();
			$data['total_cuti'] = $this->m_model->getTotalCuti();
			$data['total_telat'] = $this->m_model->jumlah_terlambat_masuk_all();
		} else {
			$idKaryawan = $this->session->userdata('id');
			$data['dashboard'] = $this->m_model->getAbsensiByIdKaryawan($idKaryawan);
			$data['total_data'] = count($data['dashboard']);
			$data['total_cuti'] = $this->m_model->getTotalCutiKaryawan($this->session->userdata('id'));
			$data['total_kerja'] = $this->m_model->getTotalJamMasukKaryawan($idKaryawan);
			$data['total_telat'] = $this->m_model->jumlah_terlambat_masuk($idKaryawan);
		}
		$this->load->view('page/dashboard', $data);
	}
	//end dashboard

	// start role karyawan 

	//start untuk menampilkan data absensi karyawan
	public function absensi_karyawan()
	{
		$keyword = $this->input->post('search_keyword');
		$data['karyawan'] = array(); // Inisialisasi $data['karyawan'] sebagai array kosong

		if ($this->session->userdata('role') === "admin") {
			if (!empty($keyword)) {
				$keyword = strtolower($keyword);
				$data['karyawan'] = $this->m_model->searchAbsensi($keyword);
			} else {
				$data['karyawan'] = $this->m_model->get_all_karyawan();
			}
		} else {
			if (!empty($keyword)) {
				$keyword = strtolower($keyword);
				$userId = $this->session->userdata('id');
				$data['karyawan'] = $this->m_model->searchAbsensiByid($keyword, $userId);
			} else {
				$idKaryawan = $this->session->userdata('id');
				$totalAbsensi = $this->m_model->getAbsensiByIdKaryawan($idKaryawan);
				$data['karyawan'] = $totalAbsensi;
			}
		}

		$this->load->view('page/karyawan/absensi_karyawan', $data);
	}

	//end untuk menampilkan data absensi karyawan
	//start pulang di tabel absensi
	public function pulang($id)
	{
		date_default_timezone_set('Asia/Jakarta');
		$absensi = $this->db->get_where('absensi', array('id' => $id))->row();

		if ($absensi) {
			$data = array(
				'jam_pulang' => date('H:i:s'),
				'status' => 'done',
			);

			$this->db->where('id', $id);
			$this->db->update('absensi', $data);
			$this->session->set_flashdata('berhasil_pulang', 'Berhasil Pulang');
			redirect(base_url('page/absensi_karyawan'));
		} else {
			echo 'Data absensi tidak ditemukan';
		}
	}
	//end pulang di tabel absensi

	// start hapus data absensi
	public function hapus($id)
	{
		$this->m_model->delete_relasi($id);
		$this->m_model->delete('user', 'id', $id);
		redirect(base_url('page/dataUser'));
	}
	//end hapus data absensi
	
	public function hapus_absensi_karyawan($id) {
		$this->m_model->delete('absensi', 'id', $id);
		redirect(base_url('page/absensi_karyawan'));
	}

	//start edit kegiatan di tabell absensi
	public function aksi_edit()
	{
		$data = [
			'kegiatan' => $this->input->post('kegiatan'),
		];
		$eksekusi = $this->m_model->ubah_data('absensi', $data, array('id' => $this->input->post('id')));
		if ($eksekusi) {
			$this->session->set_flashdata('success_message', 'Berhasil');
			redirect(base_url('page/absensi_karyawan'));
		} else {
			$this->session->set_flashdata('error', "Data Belum Di Edit");
			redirect(base_url('page/edit_kegiatan/' . $this->input->post('id')));
		}
	}
	//end edit kegiatan

	//start aksi keterangan izin untuk tabel absensi
	public function aksi_keterangan_izin()
	{
		$data = [
			'keterangan_izin' => $this->input->post('keterangan_izin'),
			"jam_pulang" => "00:00:00",
			"jam_masuk" => "00:00:00",
			"kegiatan" => "-",
			"status" => "done",
		];
		$eksekusi = $this->m_model->ubah_data('absensi', $data, array('id' => $this->input->post('id')));
		if ($eksekusi) {
			$this->session->set_flashdata('berhasil_izin', 'Berhasil untuk izin');
			redirect(base_url('page/absensi_karyawan'));
		} else {
			$this->session->set_flashdata('gagal_izin', "Gagal memberi keterangan izin");
			redirect(base_url('page/edit_kegiatan/' . $this->input->post('id')));
		}
	}
	//end aksi keterangan izin

	// start edit kegiatan
	public function edit_kegiatan($id)
	{
		$data['karyawan1'] = $this->m_model->get_by_id('absensi', 'id', $id)->result();
		// $data['user'] = $this->m_model->get_data('user')->result();
		$this->load->view('page/karyawan/edit_kegiatan', $data);
	}
	//end edit kegiatan

	// start upload image admin dan karyawan
	public function upload_image($value)
	{
		$kode = round(microtime(true) * 1000);
		$config['upload_path'] = './images/user/';
		$config['allowed_types'] = 'jpg|png|jpeg|webp|avif';
		$config['max_size'] = '30000';
		$config['file_name'] = $kode;
		$this->upload->initialize($config);
		if (!$this->upload->do_upload($value)) {
			return array(false, '');
		} else {
			$fn = $this->upload->data();
			$nama = $fn['file_name'];
			return array(true, $nama);
		}
	}
	// end upload image admin dan karyawan

	//start edit gambar profile admin dan karyawan 
	public function aksi_ubah_gambar()
	{
		$foto = $this->upload_image('foto');
		if ($foto[0] == false) {
			$data = [
				"image" => "user.avif"
			];
		} else {
			$data = [
				"image" => $foto[1]
			];
		}
		$this->session->set_userdata($data);
		$update_result = $this->m_model->ubah_data('user', $data, array('id' => $this->session->userdata('id')));
		if ($update_result) {
			$this->session->set_flashdata('berhasil_ganti_foto', 'Foto Berhasil Diubah');
			redirect(base_url('page/profile'));
		} else {
			$this->session->set_flashdata('gagal_ganti_foto', 'Foto Gagal Diubah');
			redirect(base_url('page/profile'));
		}
	}
	//end edit gambar profile admin dan karyawan 

	// start edit profile karyawan dan admin
	public function aksi_ubah_profile()
	{
		$data = [
			"username" => $this->input->post('username'),
			"email" => $this->input->post('email'),
			"nama_depan" => $this->input->post('nama_depan'),
			"nama_belakang" => $this->input->post('nama_belakang'),
		];

		$user_id = $this->session->userdata('id');

		$eksekusi = $this->m_model->ubah_data('user', $data, array('id' => $user_id));

		if ($eksekusi) {
			$this->session->set_flashdata('berhasil_edit_profile', 'Berhasil Untuk Mengedit Profile');
			$this->session->set_userdata($data);
			redirect(base_url('page/profile'));
		} else {
			$this->session->set_flashdata('gagal_edit_profile', 'Gagal Untuk Mengedit Profile');
			redirect(base_url('page/profile'));
		}
	}
	//edn edit profile karyawan dan admin

	//start profile karyawan
	public function profile()
	{
		$data['profile'] = $this->m_model->get_by_id('user', 'id', $this->session->userdata('id'))->result();
		$data['menu'] = "profile";
		$this->load->view('page/karyawan/profile', $data);
	}
	//end profile karyawan

	//start edit password karyawan dan admin 
	public function aksi_edit_password()
	{
		$password_lama = $this->input->post('password_lama', true);

		$user = $this->m_model->getWhere('user', ['id' => $this->session->userdata('id')])->row_array();

		if (md5($password_lama) === $user['password']) {
			$password_baru = $this->input->post('password_baru', true);
			$konfirmasi_password = $this->input->post('konfirmasi_password', true);

			// Pastikan password baru dan konfirmasi password sama
			if ($password_baru === $konfirmasi_password) {
				// Update password baru ke dalam database
				$data = ['password' => md5($password_baru)];
				$this->m_model->ubah_data('user', $data, ['id' => $this->session->userdata('id')]);

				$this->session->set_flashdata('berhasil_ganti_password', 'Password Berhasil Diubah');
				redirect(base_url('page/profile'));
			} else {
				$this->session->set_flashdata('konfirmasi_pass', 'Password Baru Dan Konfirmasi Password Harus Sama');
				redirect(base_url('page/profile'));
			}
		} else {
			$this->session->set_flashdata('pass_lama', 'Pastikan Anda Mengisi Password Lama Anda Dengan Benar');
			redirect(base_url('page/profile'));
		}
		redirect(base_url('page/profile'));
	}
	//end edit password karyawan dan admin

	// end role karyawan

	// start role admin

	//start profile admin
	public function profileAdmin()
	{
		$data['profile'] = $this->m_model->get_by_id('user', 'id', $this->session->userdata('id'))->result();
		$data['menu'] = "profile";
		$this->load->view('page/admin/profileAdmin', $data);
	}
	//end profile admin

	//start export data karyawan
	public function export()
	{

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$style_col = [
			'font' => ['bold' => true],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\style\Alignment::VERTICAL_CENTER
			],
			'borders' => [
				'top' => ['borderstyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
				'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
				'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
				'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN]
			]
		];

		$style_row = [
			'alignment' => [
				'vertical' => \PhpOffice\PhpSpreadsheet\style\Alignment::VERTICAL_CENTER
			],
			'borders' => [
				'top' => ['borderstyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
				'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
				'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
				'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN]
			]
		];

		$sheet->setCellValue('A1', "DATA ABSEN KARYAWAN");
		$sheet->mergeCells('A1:E1');
		$sheet->getStyle('A1')->getFont()->setBold(true);

		$sheet->setCellValue('A3', "ID");
		$sheet->setCellValue('B3', "USERNAME");
		$sheet->setCellValue('C3', "NAMA DEPAN");
		$sheet->setCellValue('D3', "NAMA BELAKANG");
		$sheet->setCellValue('E3', "EMAIL");

		$sheet->getStyle('A3')->applyFromArray($style_col);
		$sheet->getStyle('B3')->applyFromArray($style_col);
		$sheet->getStyle('C3')->applyFromArray($style_col);
		$sheet->getStyle('D3')->applyFromArray($style_col);
		$sheet->getStyle('E3')->applyFromArray($style_col);

		$karyawan = $this->m_model->hanya_karyawan();

		$no = 1;
		$numrow = 4;
		foreach ($karyawan as $data) {
			$sheet->setCellValue('A' . $numrow, $no);
			$sheet->setCellValue('B' . $numrow, $data->username);
			$sheet->setCellValue('C' . $numrow, $data->nama_depan);
			$sheet->setCellValue('D' . $numrow, $data->nama_belakang);
			$sheet->setCellValue('E' . $numrow, $data->email);

			$sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('C' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('D' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('E' . $numrow)->applyFromArray($style_row);

			$no++;
			$numrow++;
		}

		$sheet->getColumnDimension('A')->setWidth(5);
		$sheet->getColumnDimension('B')->setWidth(25);
		$sheet->getColumnDimension('C')->setWidth(50);
		$sheet->getColumnDimension('D')->setWidth(20);
		$sheet->getColumnDimension('E')->setWidth(30);

		$sheet->getDefaultRowDimension()->setRowHeight(-1);

		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

		$sheet->setTitle("LAPORAN DATA ABSEN KARYAWAN");
		header('Content-Type: aplication/vnd.openxmlformants-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="data_karyawan.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');

	}
	//end export data karyawan

	//start export semua data absensi
	public function export_absensi_all()
	{
		// $tanggal = date('Y-m-d');
		// $tanggal = $this->input->post('tanggal');
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// if (!empty($tanggal)) {
		$style_col = [
			'font' => ['bold' => true],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\style\Alignment::VERTICAL_CENTER
			],
			'borders' => [
				'top' => ['borderstyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
				'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
				'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
				'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN]
			]
		];

		$style_row = [
			'alignment' => [
				'vertical' => \PhpOffice\PhpSpreadsheet\style\Alignment::VERTICAL_CENTER
			],
			'borders' => [
				'top' => ['borderstyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
				'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
				'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
				'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN]
			]
		];

		$sheet->setCellValue('A1', "DATA ABSEN KARYAWAN");
		$sheet->mergeCells('A1:E1');
		$sheet->getStyle('A1')->getFont()->setBold(true);

		$sheet->setCellValue('A3', "ID");
		$sheet->setCellValue('B3', "NAMA KARYAWAN");
		$sheet->setCellValue('C3', "KEGIATAN");
		$sheet->setCellValue('D3', "TANGGAL MASUK");
		$sheet->setCellValue('E3', "JAM MASUK");
		$sheet->setCellValue('F3', "JAM PULANG");
		$sheet->setCellValue('G3', "KETERANGAN IZIN");
		$sheet->setCellValue('H3', "STATUS");

		$sheet->getStyle('A3')->applyFromArray($style_col);
		$sheet->getStyle('B3')->applyFromArray($style_col);
		$sheet->getStyle('C3')->applyFromArray($style_col);
		$sheet->getStyle('D3')->applyFromArray($style_col);
		$sheet->getStyle('E3')->applyFromArray($style_col);
		$sheet->getStyle('F3')->applyFromArray($style_col);
		$sheet->getStyle('G3')->applyFromArray($style_col);
		$sheet->getStyle('H3')->applyFromArray($style_col);

		$karyawan = $this->m_model->get_all_karyawan();

		$no = 1;
		$numrow = 4;
		foreach ($karyawan as $data) {
			$sheet->setCellValue('A' . $numrow, $no);
			$sheet->setCellValue('B' . $numrow, $data->nama_depan . ' ' . $data->nama_belakang);
			$sheet->setCellValue('C' . $numrow, $data->kegiatan);
			$sheet->setCellValue('D' . $numrow, $data->date);
			$sheet->setCellValue('E' . $numrow, $data->jam_masuk);
			$sheet->setCellValue('F' . $numrow, $data->jam_pulang);
			$sheet->setCellValue('G' . $numrow, $data->keterangan_izin);
			$sheet->setCellValue('H' . $numrow, $data->status);

			$sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('C' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('D' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('E' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('F' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('G' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('H' . $numrow)->applyFromArray($style_row);

			$no++;
			$numrow++;
		}

		$sheet->getColumnDimension('A')->setWidth(5);
		$sheet->getColumnDimension('B')->setWidth(25);
		$sheet->getColumnDimension('C')->setWidth(50);
		$sheet->getColumnDimension('D')->setWidth(20);
		$sheet->getColumnDimension('E')->setWidth(30);
		$sheet->getColumnDimension('F')->setWidth(30);
		$sheet->getColumnDimension('G')->setWidth(30);
		$sheet->getColumnDimension('H')->setWidth(30);

		$sheet->getDefaultRowDimension()->setRowHeight(-1);

		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

		$sheet->setTitle("LAPORAN DATA ABSEN KARYAWAN");
		header('Content-Type: aplication/vnd.openxmlformants-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="semua_data_absensi.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		// }

	}
	//end export semua data absensi

	//start export data absen perhari
	public function export_absensi()
	{
		$tanggal = $this->session->userdata('tanggal');
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		if (!empty($tanggal)) {
			$style_col = [
				'font' => ['bold' => true],
				'alignment' => [
					'horizontal' => \PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER,
					'vertical' => \PhpOffice\PhpSpreadsheet\style\Alignment::VERTICAL_CENTER
				],
				'borders' => [
					'top' => ['borderstyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
					'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
					'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
					'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN]
				]
			];

			$style_row = [
				'alignment' => [
					'vertical' => \PhpOffice\PhpSpreadsheet\style\Alignment::VERTICAL_CENTER
				],
				'borders' => [
					'top' => ['borderstyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
					'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
					'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
					'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN]
				]
			];

			$sheet->setCellValue('A1', "DATA ABSEN KARYAWAN");
			$sheet->mergeCells('A1:E1');
			$sheet->getStyle('A1')->getFont()->setBold(true);

			$sheet->setCellValue('A3', "ID");
			$sheet->setCellValue('B3', "NAMA KARYAWAN");
			$sheet->setCellValue('C3', "KEGIATAN");
			$sheet->setCellValue('D3', "TANGGAL MASUK");
			$sheet->setCellValue('E3', "JAM MASUK");
			$sheet->setCellValue('F3', "JAM PULANG");
			$sheet->setCellValue('G3', "KETERANGAN IZIN");
			$sheet->setCellValue('H3', "STATUS");

			$sheet->getStyle('A3')->applyFromArray($style_col);
			$sheet->getStyle('B3')->applyFromArray($style_col);
			$sheet->getStyle('C3')->applyFromArray($style_col);
			$sheet->getStyle('D3')->applyFromArray($style_col);
			$sheet->getStyle('E3')->applyFromArray($style_col);
			$sheet->getStyle('F3')->applyFromArray($style_col);
			$sheet->getStyle('G3')->applyFromArray($style_col);
			$sheet->getStyle('H3')->applyFromArray($style_col);

			$karyawan = $this->m_model->getHarianData($tanggal);

			$no = 1;
			$numrow = 4;
			foreach ($karyawan as $data) {
				$sheet->setCellValue('A' . $numrow, $no);
				$sheet->setCellValue('B' . $numrow, $data->nama_depan . ' ' . $data->nama_belakang);
				$sheet->setCellValue('C' . $numrow, $data->kegiatan);
				$sheet->setCellValue('D' . $numrow, $data->date);
				$sheet->setCellValue('E' . $numrow, $data->jam_masuk);
				$sheet->setCellValue('F' . $numrow, $data->jam_pulang);
				$sheet->setCellValue('G' . $numrow, $data->keterangan_izin);
				$sheet->setCellValue('H' . $numrow, $data->status);

				$sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('C' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('D' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('E' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('F' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('G' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('H' . $numrow)->applyFromArray($style_row);

				$no++;
				$numrow++;
			}

			$sheet->getColumnDimension('A')->setWidth(5);
			$sheet->getColumnDimension('B')->setWidth(25);
			$sheet->getColumnDimension('C')->setWidth(50);
			$sheet->getColumnDimension('D')->setWidth(20);
			$sheet->getColumnDimension('E')->setWidth(30);
			$sheet->getColumnDimension('F')->setWidth(30);
			$sheet->getColumnDimension('G')->setWidth(30);
			$sheet->getColumnDimension('H')->setWidth(30);

			$sheet->getDefaultRowDimension()->setRowHeight(-1);

			$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

			$sheet->setTitle("LAPORAN DATA ABSEN KARYAWAN");
			header('Content-Type: aplication/vnd.openxmlformants-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="absensi_perhari.xlsx"');
			header('Cache-Control: max-age=0');

			$writer = new Xlsx($spreadsheet);
			$writer->save('php://output');
		}

	}
	//end export data absen perhari

	//start export data absen mingguan
	public function export_absensi_mingguan()
	{
		$tanggal_akhir = date('Y-m-d');
		$tanggal_awal = date('Y-m-d', strtotime('-7 days', strtotime($tanggal_akhir)));
		$tanggal_awal = date('W', strtotime($tanggal_awal));
		$tanggal_akhir = date('W', strtotime($tanggal_akhir));
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		if (!empty($tanggal_awal && $tanggal_akhir)) {
			$style_col = [
				'font' => ['bold' => true],
				'alignment' => [
					'horizontal' => \PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER,
					'vertical' => \PhpOffice\PhpSpreadsheet\style\Alignment::VERTICAL_CENTER
				],
				'borders' => [
					'top' => ['borderstyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
					'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
					'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
					'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN]
				]
			];

			$style_row = [
				'alignment' => [
					'vertical' => \PhpOffice\PhpSpreadsheet\style\Alignment::VERTICAL_CENTER
				],
				'borders' => [
					'top' => ['borderstyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
					'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
					'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
					'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN]
				]
			];

			$sheet->setCellValue('A1', "DATA ABSEN KARYAWAN");
			$sheet->mergeCells('A1:E1');
			$sheet->getStyle('A1')->getFont()->setBold(true);

			$sheet->setCellValue('A3', "ID");
			$sheet->setCellValue('B3', "NAMA KARYAWAN");
			$sheet->setCellValue('C3', "KEGIATAN");
			$sheet->setCellValue('D3', "TANGGAL MASUK");
			$sheet->setCellValue('E3', "JAM MASUK");
			$sheet->setCellValue('F3', "JAM PULANG");
			$sheet->setCellValue('G3', "KETERANGAN IZIN");
			$sheet->setCellValue('H3', "STATUS");

			$sheet->getStyle('A3')->applyFromArray($style_col);
			$sheet->getStyle('B3')->applyFromArray($style_col);
			$sheet->getStyle('C3')->applyFromArray($style_col);
			$sheet->getStyle('D3')->applyFromArray($style_col);
			$sheet->getStyle('E3')->applyFromArray($style_col);
			$sheet->getStyle('F3')->applyFromArray($style_col);
			$sheet->getStyle('G3')->applyFromArray($style_col);
			$sheet->getStyle('H3')->applyFromArray($style_col);

			$karyawan = $this->m_model->getMingguanData($tanggal_awal, $tanggal_akhir);

			$no = 1;
			$numrow = 4;
			foreach ($karyawan as $data) {
				$sheet->setCellValue('A' . $numrow, $no);
				$sheet->setCellValue('B' . $numrow, $data->nama_depan . ' ' . $data->nama_belakang);
				$sheet->setCellValue('C' . $numrow, $data->kegiatan);
				$sheet->setCellValue('D' . $numrow, $data->date);
				$sheet->setCellValue('E' . $numrow, $data->jam_masuk);
				$sheet->setCellValue('F' . $numrow, $data->jam_pulang);
				$sheet->setCellValue('G' . $numrow, $data->keterangan_izin);
				$sheet->setCellValue('H' . $numrow, $data->status);

				$sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('C' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('D' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('E' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('F' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('G' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('H' . $numrow)->applyFromArray($style_row);

				$no++;
				$numrow++;
			}

			$sheet->getColumnDimension('A')->setWidth(5);
			$sheet->getColumnDimension('B')->setWidth(25);
			$sheet->getColumnDimension('C')->setWidth(50);
			$sheet->getColumnDimension('D')->setWidth(20);
			$sheet->getColumnDimension('E')->setWidth(30);
			$sheet->getColumnDimension('F')->setWidth(30);
			$sheet->getColumnDimension('G')->setWidth(30);
			$sheet->getColumnDimension('H')->setWidth(30);

			$sheet->getDefaultRowDimension()->setRowHeight(-1);

			$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

			$sheet->setTitle("LAPORAN DATA ABSEN KARYAWAN");
			header('Content-Type: aplication/vnd.openxmlformants-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="data_absensi_perminggu.xlsx"');
			header('Cache-Control: max-age=0');

			$writer = new Xlsx($spreadsheet);
			$writer->save('php://output');
		}

	}
	//end export data absen mingguan

	//start export data absen bulanan
	public function export_absensi_bulanan()
	{
		$bulan = $this->session->userdata('bulan');
		$absensi = $this->m_model->getBulananData($bulan);
		// $bulan = date('Y-m');
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		if (!empty($bulan) && !empty($absensi)) {
			$style_col = [
				'font' => ['bold' => true],
				'alignment' => [
					'horizontal' => \PhpOffice\PhpSpreadsheet\style\Alignment::HORIZONTAL_CENTER,
					'vertical' => \PhpOffice\PhpSpreadsheet\style\Alignment::VERTICAL_CENTER
				],
				'borders' => [
					'top' => ['borderstyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
					'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
					'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
					'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN]
				]
			];

			$style_row = [
				'alignment' => [
					'vertical' => \PhpOffice\PhpSpreadsheet\style\Alignment::VERTICAL_CENTER
				],
				'borders' => [
					'top' => ['borderstyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
					'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
					'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN],
					'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\style\Border::BORDER_THIN]
				]
			];

			$sheet->setCellValue('A1', "DATA ABSEN KARYAWAN");
			$sheet->mergeCells('A1:E1');
			$sheet->getStyle('A1')->getFont()->setBold(true);

			$sheet->setCellValue('A3', "ID");
			$sheet->setCellValue('B3', "NAMA KARYAWAN");
			$sheet->setCellValue('C3', "KEGIATAN");
			$sheet->setCellValue('D3', "TANGGAL MASUK");
			$sheet->setCellValue('E3', "JAM MASUK");
			$sheet->setCellValue('F3', "JAM PULANG");
			$sheet->setCellValue('G3', "KETERANGAN IZIN");
			$sheet->setCellValue('H3', "STATUS");

			$sheet->getStyle('A3')->applyFromArray($style_col);
			$sheet->getStyle('B3')->applyFromArray($style_col);
			$sheet->getStyle('C3')->applyFromArray($style_col);
			$sheet->getStyle('D3')->applyFromArray($style_col);
			$sheet->getStyle('E3')->applyFromArray($style_col);
			$sheet->getStyle('F3')->applyFromArray($style_col);
			$sheet->getStyle('G3')->applyFromArray($style_col);
			$sheet->getStyle('H3')->applyFromArray($style_col);

			$karyawan = $this->m_model->getBulananData($bulan);

			$no = 1;
			$numrow = 4;
			foreach ($karyawan as $data) {
				$sheet->setCellValue('A' . $numrow, $no);
				$sheet->setCellValue('B' . $numrow, $data->nama_depan . ' ' . $data->nama_belakang);
				$sheet->setCellValue('C' . $numrow, $data->kegiatan);
				$sheet->setCellValue('D' . $numrow, $data->date);
				$sheet->setCellValue('E' . $numrow, $data->jam_masuk);
				$sheet->setCellValue('F' . $numrow, $data->jam_pulang);
				$sheet->setCellValue('G' . $numrow, $data->keterangan_izin);
				$sheet->setCellValue('H' . $numrow, $data->status);

				$sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('C' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('D' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('E' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('F' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('G' . $numrow)->applyFromArray($style_row);
				$sheet->getStyle('H' . $numrow)->applyFromArray($style_row);

				$no++;
				$numrow++;
			}

			$sheet->getColumnDimension('A')->setWidth(5);
			$sheet->getColumnDimension('B')->setWidth(25);
			$sheet->getColumnDimension('C')->setWidth(50);
			$sheet->getColumnDimension('D')->setWidth(20);
			$sheet->getColumnDimension('E')->setWidth(30);
			$sheet->getColumnDimension('F')->setWidth(30);
			$sheet->getColumnDimension('G')->setWidth(30);
			$sheet->getColumnDimension('H')->setWidth(30);

			$sheet->getDefaultRowDimension()->setRowHeight(-1);

			$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

			$sheet->setTitle("LAPORAN DATA ABSEN KARYAWAN");
			header('Content-Type: aplication/vnd.openxmlformants-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="absensi_perbulan.xlsx"');
			header('Cache-Control: max-age=0');

			$writer = new Xlsx($spreadsheet);
			$writer->save('php://output');
		} else {
			$this->session->set_flashdata('error_export_perbulan', 'Data Tidak Ada Untuk Di Export');
			redirect(base_url('page/rekapBulanan'));
		}

	}
	//end export data absen bulanan

	//start menampilkan page rekap harian
	public function rekapHarian()
	{
		// $tanggal = date('Y-m-d');
		$tanggal = $this->input->post('tanggal');
		$this->session->set_userdata('tanggal', $tanggal);
		$data['rekapHarian'] = $this->m_model->getHarianData($tanggal);
		$this->load->view('page/admin/rekapHarian', $data);
	}
	//end menampilkan page rekap harian

	//start menampilkan page rekap mingguan
	public function rekapMingguan()
	{
		$tanggal_akhir = date('Y-m-d');
		$tanggal_awal = date('Y-m-d', strtotime('-7 days', strtotime($tanggal_akhir)));
		$tanggal_awal = date('W', strtotime($tanggal_awal));
		$tanggal_akhir = date('W', strtotime($tanggal_akhir));
		$data['rekapMingguan'] = $this->m_model->getMingguanData($tanggal_awal, $tanggal_akhir);
		$this->load->view('page/admin/rekapMingguan', $data);
	}
	//end menampilkan page rekap mingguan

	//start menampilkan page rekap bulanan
	public function rekapBulanan()
	{
		$bulan = $this->input->post('bulan');
		$this->session->set_userdata('bulan', $bulan);
		$data['rekapBulanan'] = array();

		if (!empty($bulan)) {
			$data['rekapBulanan'] = $this->m_model->getBulananData($bulan);
		}

		$this->load->view('page/admin/rekapBulanan', $data);
	}

	//end menampilkan page rekap bulanan

	//start menampilkan page data karyawan
	public function dataUser()
	{
		$keyword = $this->input->post('search_keyword');

		if (!empty($keyword)) {
			$keyword = strtolower($keyword);
			$data['get_karyawan'] = $this->m_model->searchKaryawan($keyword);
		} else {
			// Handle jika $keyword adalah null, misalnya tampilkan semua data
			$data['get_karyawan'] = $this->m_model->hanya_karyawan();
		}

		$this->load->view('page/admin/dataUser', $data);
	}
	//end menampilkan page data karyawan

	//end role admin


}