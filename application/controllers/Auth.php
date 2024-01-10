<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	//start construct
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_model');
		$this->load->library('form_validation');
	}
	//end construct

	// <=== start login ==>

	// start menampilkan page login
	public function index()
	{
		// $data['login'] = $this->m_model->get_karyawan();
		$this->load->view('auth/login');
	}
	//end menampilkan page login

	//start aksi untuk login
	public function aksi_login()
	{
		if ($this->session->userdata('logged_in') === TRUE) {
			if ($this->session->userdata('role') === 'Admin') {
				redirect(base_url('home'));
			}
			if ($this->session->userdata('role') === 'user') {
				redirect(base_url('home'));
			}
		}
		$this->data['username'] = array(
			'name' => 'username',
			'type' => 'text',
			'value' => $this->form_validation->set_value('username'),
		);
		$this->data['email'] = array(
			'name' => 'email',
			'type' => 'email',
			'value' => $this->form_validation->set_value('email'),
		);
		$this->data['password'] = array(
			'name' => 'password',
			'type' => 'password',
			'value' => $this->form_validation->set_value('password'),
			'minlength' => 3,
		);
		$this->load->view('auth/login', $this->data);
	}

	//end aksi login
	// <=== end login ===>

	//  <=== logout ===>
	function logout()
	{
		$this->session->sess_destroy(); // Menghapus sesi pengguna
		redirect(base_url('auth')); // Redirect kembali ke halaman home
	}

	//  <=== register karyawan ===>

	// start menampilkan page register karyawan
	public function register()
	{
		$this->load->view('auth/register');
	}
	// end menampilkan page register karyawan
	public function aksi_register()
	{
		$this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[akun.username]', [
			'is_unique' => 'Username Ini Sudah Ada'
		]);
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[akun.email]', [
			'is_unique' => 'Email Ini Sudah Ada'
		]);
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');

		if ($this->form_validation->run() == FALSE) {
			// Validasi gagal, tampilkan pesan kesalahan
			$this->load->view('auth/register'); // Gantilah 'form_register' dengan nama view Anda
		} else {
			$data = [
				'username' => $this->input->post('username', true),
				'email' => $this->input->post('email', true),
				'password' => md5($this->input->post('password')),
				'role' => 'user'
			];
			$this->db->insert('akun', $data);
			// $this->_sendEmail();
			// Validasi berhasil, lanjutkan dengan tindakan registrasi
			// Misalnya, simpan data pengguna ke database
			redirect('auth'); // Redirect ke halaman sukses registrasi
		}
	}

	// private function _sendEmail()
	// {
	// 	$config = [
	// 		'protocol' => 'smtp',
	// 		'smtp_host' => 'smtp.gmail.com', // Menggunakan 'smtp.gmail.com' untuk SMTP Gmail
	// 		'smtp_user' => 'nisaoolle@gmail.com',
	// 		'smtp_pass' => 'tffq qoxz guqk rlmo', // Gantilah dengan password akun Gmail Anda yang benar
	// 		'smtp_port' => 587, // Port SMTP Gmail biasanya 587
	// 		'smtp_crypto' => 'tls', // Gunakan TLS
	// 		'mailtype' => 'html',
	// 		'charset' => 'utf-8',
	// 		'newline' => "\r\n"
	// 	];
	// 	$this->load->library('email', $config);
	// 	$this->email->from('nisaoolle@gmail.com', 'Nisa');
	// 	$this->email->to('khoirulnisa2006@gmail.com');
	// 	$this->email->subject('Testing');
	// 	$this->email->message('Hellooooo');

	// 	if ($this->email->send()) {
	// 		return true;
	// 	} else {
	// 		echo $this->email->print_debugger();
	// 		die;
	// 	}
	// }


	// <=== end Register karyawan ===>

	// <=== start register admin ==>

	//start menampilkan page register admin
	public function register_admin()
	{
		$this->load->view('auth/register_admin');
	}
	//end menampilkan page register admin

	//start aksi register admin
	public function aksi_register_admin()
	{
		$email = $this->input->post('email');
		$username = $this->input->post('username');
		$role = $this->input->post('role');
		$password = $this->input->post('password');
		if ($this->m_model->EmailSudahAda($email)) {
			$this->session->set_flashdata('error_email', 'Email ini sudah ada. Gunakan email lainya');
			redirect(base_url('auth/register_admin'));
		} elseif ($this->m_model->usernameSudahAda($username)) {
			$this->session->set_flashdata('error_username', 'Username ini sudah ada. Gunakan username lainya');
			redirect(base_url('auth/register_admin'));
		} elseif (strlen($password) < 8 || !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $password)) {
			// Password tidak memenuhi persyaratan
			$this->session->set_flashdata('error_message', 'Password harus memiliki setidaknya 8 karakter, satu huruf besar, satu huruf kecil, dan angka.');
			redirect(base_url('auth/register_admin'));
		} else {
			// Hash password menggunakan MD5
			$hashed_password = md5($password);

			// Simpan data pengguna ke database
			$data = array(
				'username' => $username,
				'password' => $hashed_password,
				'email' => $email,
				'role' => $role,
			);

			$this->m_model->register($data); // Panggil model untuk menyimpan data

			$this->session->set_flashdata('berhasil_register_admin', 'Berhasil Register');
			redirect('auth');
		}
	}
	//end aksi register admin
	// <=== end register admin ==>

}