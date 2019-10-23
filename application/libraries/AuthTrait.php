<?php

trait AuthTrait
{
	public function checkAuth()
	{

		if($this->session->userdata('authorized'))
		{
			return true;
		}

		return false;
	}

	public function checkActivation()
	{
		if($this->session->userdata('authorized') && $this->session->userdata('is_active'))
		{
			return true;
		}

		return false;
	}
	protected function pass_encrypt($password)
	{

		return crypt($password.time(), $this->config->item('password_salt'));
	}
}
