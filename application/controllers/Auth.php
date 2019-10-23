<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH.'/libraries/AuthTrait.php';


class Auth extends CI_Controller {

	use AuthTrait;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('form_validation');
	}

	public function index()
	{
		if ($this -> checkAuth())
			return redirect('/');

		$this->load->view('templates/header');
		$this->load->view('main/auth');
		$this->load->view('templates/footer');
	}


	public function login()
	{
		if (!$this->input->post())
			return redirect('/');

		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
		$this->form_validation->set_rules('password', 'Пароль','min_length[6]|trim');
		$this->form_validation->set_message('min_length', '{field} должен иметь как минимум {param} символов.');

		if ($this->form_validation->run() == FALSE)
		{
			$errors = $this->form_validation->error_string();
			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode(['error' => $errors]));
		}

		$data = [
			'email' => $this->input->post('email'),
			'password' => $this -> pass_encrypt($this->input->post('password'))
		];
		$user = $this->user->get_user($data);

		if(!$user)
		{
			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode(['error' => 'Вы ввели неверный email или пароль!']));
		}
		$session = [
			'id' => $user->id,
			'email' => $user->email,
			'authorized' => 1,
			'is_active' => $user -> is_active,
		];
		$this->session->set_userdata($session);

		if(!$user -> is_active)
		{
			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode(['msg' => 'activate']));
		}

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(['msg' => 'OK']));

	}

	public function logout()
	{
		if ($this -> checkAuth())
			$this->session->sess_destroy();
		return redirect('/auth');
	}
}
