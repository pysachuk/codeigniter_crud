<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH.'/libraries/AuthTrait.php';


class Register extends CI_Controller
{

	use AuthTrait;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->database();
	}
	public function index()
	{
		if ($this -> checkAuth() && !$this -> checkActivation())
			return redirect('/register/activate');


		$this->load->view('templates/header');
		$this->load->view('main/register');
		$this->load->view('templates/footer');
	}

	public function add_user()
	{

		if(!$this->input->post())
		{
			return redirect('/register');
		}

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


		if($this->user -> check_register($this->input->post('email')))
		{
			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode(['error' => 'Пользователь с таки E-MAIL уже существует']));
		}

		$domain = substr(strrchr($this->input->post('email'), "@"), 1);
		if($this -> check_email_domain($domain))
		{
			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode(['error' => 'Запрещенный E-MAIL!']));
		}

		$activation_hash = md5($this->input->post('email').time());

		$data = [
			'name' => $this->input->post('name'),
			'email' => $this->input->post('email'),
			'password' => $this -> pass_encrypt($this->input->post('password')),
			'activate_hash' => $activation_hash
		];

		$user = $this->user->register($data);

		if(!$user)
		{
			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode(['error' => 'Ошибка регистрации']));
		}

		$session = [
			'id' => $user->id,
			'email' => $user->email,
			'authorized' => 1,
			'is_active' => $user->is_active,
		];

		$this->session->set_userdata($session);

		//Send Email
		$this->load->library('email');
		$this->email->from('admin@pysachuk.zzz.com.ua', 'Admin');
		$this->email->to($this->input->post('email'));
		$this->email->subject('Activate Account');
		$this->email->message('Ваш код активации: '.$activation_hash.' |
		Для активации перейдите по ссылке: http://pysachuk.zzz.com.ua/register/activate_user?activation_code='.$activation_hash);
		$this->email->send();

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(['msg' => 'OK']));
	}

	public function check_email_domain($domain)
	{
		$result = $this->db->get_where('forbidden_emails', ['email' => $domain])-> result();
		return (array_shift($result) ? true : false);
	}


	public function activate()
	{
		if ($this -> checkAuth() && !$this -> checkActivation())
		{
			$data['email'] = $this->session->userdata('email');
			$this->load->view('templates/header');
			$this->load->view('main/activate', $data);
			$this->load->view('templates/footer');
		}
		else
			return redirect('/');
	}
	public function activate_user()
	{
		if ($this -> checkActivation())
			return redirect('/');

		$this->form_validation->set_data($this->input->get());


		$this->form_validation->set_rules('activation_code', 'Код активации', 'required');
		$this->form_validation->set_message('required', 'Вы не ввели {field}');

		if ($this->form_validation->run() == FALSE)
		{
			$errors = $this->form_validation->error_string();
			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode(['error' => $errors]));
		}

		if(! $this->user->activate($this->input->get('activation_code')))
		{
			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode(['error' => "Ошибка. Неверный код активации."]));
		}
		else
		{
			$this->session->set_userdata(['is_active' => 1]);
			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode(['msg' => "OK"]));
		}


	}

}
