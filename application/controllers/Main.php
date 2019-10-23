<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH.'/libraries/AuthTrait.php';

class Main extends CI_Controller {

	use AuthTrait;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user');
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->library('form_validation');



		if($this -> checkAuth() && !$this -> checkActivation())
		{
			return redirect('/register/activate');
		}

		if (!$this -> checkAuth())
			return redirect('/auth');
	}

	public function index()
	{
		$data = [
			'users' => $this -> user -> get_all_users(),
			'user_email' => $this->session->userdata('email'),
		];

		$this->load->view('templates/header');
		$this->load->view('main/index', $data);
		$this->load->view('templates/footer');
	}

	public function delete_user($id)
	{
		if(!$this -> user -> delete($id))
			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode(['error' => 'Ошибка, пользователь с ID '.$id.' не удален']));
		else
			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode(['msg' => 'OK']));
	}

	public function edit_user($id)
	{
		if($this->input->post())
		{
			$this->form_validation->set_rules('name', 'Имя', 'required|min_length[2]|trim');
			$this->form_validation->set_rules('password', 'Пароль','min_length[6]|trim');
			$this->form_validation->set_rules('password_confirmation', 'Подтверждение пароля', 'matches[password]');
			$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
			$this->form_validation->set_message('min_length', '{field} должен иметь как минимум {param} символов.');
			$this->form_validation->set_message('matches', 'Пароли должны совпадать.');

			if ($this->form_validation->run() == FALSE)
			{
				$errors = $this->form_validation->error_string();
				return $this->output
					->set_content_type('application/json')
					->set_output(json_encode(['error' => $errors]));
			}

			$check_register_email = $this->user -> check_register($this->input->post('email'));
			if( $check_register_email && $check_register_email->id != $id )
			{
				return $this->output
					->set_content_type('application/json')
					->set_output(json_encode(['error' => 'Пользователь с таки E-MAIL уже существует']));
			}
			$data = [
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'is_active' => 0,

			];
			if($this->input->post('is_active') == 'on')
			{
				$data['is_active'] =  1;
			}

			if($this->input->post('password'))
			{
				$data['password'] =  $this -> pass_encrypt($this->input->post('password'));
			}

			$user = $this->user->edit_user($data, $id);

			if(!$user)
			{
				return $this->output
					->set_content_type('application/json')
					->set_output(json_encode(['error' => 'Ошибка редактирования']));
			}

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(['user' => $user]));
		}

		else
		{
			$user = $this -> user -> get_user_by_id($id);
			if(!$user)
				return $this->output
					->set_content_type('application/json')
					->set_output(json_encode(['error' => 'Ошибка, пользователь с ID '.$id.' не найден']));

			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode(['user' => $user]));
		}


	}

	public function add_user()
	{
		if(!$this->input->post())
			return redirect('/');

			$this->form_validation->set_rules('name', 'Имя', 'required|min_length[2]|trim');
			$this->form_validation->set_rules('password', 'Пароль','required|min_length[6]|trim');
			$this->form_validation->set_rules('password_confirmation', 'Подтверждение пароля', 'required|matches[password]');
			$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
			$this->form_validation->set_message('min_length', '{field} должен иметь как минимум {param} символов.');
			$this->form_validation->set_message('matches', 'Пароли должны совпадать.');

			if ($this->form_validation->run() == FALSE)
			{
				$errors = $this->form_validation->error_string();
				return $this->output
					->set_content_type('application/json')
					->set_output(json_encode(['error' => $errors]));
			}

			$check_register_email = $this->user -> check_register($this->input->post('email'));
			if( $check_register_email )
			{
				return $this->output
					->set_content_type('application/json')
					->set_output(json_encode(['error' => 'Пользователь с таки E-MAIL уже существует']));
			}
			$data = [
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'password' => $this -> pass_encrypt($this->input->post('password')),
				'is_active' => 0,

			];
			if($this->input->post('is_active') == 'on')
			{
				$data['is_active'] =  1;
			}

			$user = $this->user->register($data);

			if(!$user)
			{
				return $this->output
					->set_content_type('application/json')
					->set_output(json_encode(['error' => 'Ошибка. Пользователь не добавлен']));
			}

			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode(['user' => $user]));

	}




}
