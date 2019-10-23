<?php

class User extends CI_Model {

	protected $table = 'users';

	public function __construct()
	{
		$this->load->database();
	}

	public function register(array $data)
	{
		$this->db->insert($this->table, $data);
		$id = $this->db->insert_id();
		$this -> db -> select('id, name, email, is_active');
		$user = $this->db->get_where($this->table, ['id' => $id]) -> result();
		return (array_shift($user) ? : null);
	}
	public function activate($hash)
	{
		$this->db
			->where('activate_hash', $hash)
			->update($this->table, array('is_active' => 1));
		return $this->db->affected_rows();
	}
	public function get_user(array $data)
	{
		$this -> db -> select('id, name, email, is_active');
		$user = $this->db->get_where($this->table, ['email' => $data['email'],
			'password' => $data['password']]) -> result();
		return (array_shift($user) ? : null);
	}

	public function get_user_by_id($id)
	{
		$this -> db -> select('id, name, email, is_active');
		$user = $this->db->get_where($this->table, ['id' => $id]) -> result();
		return (array_shift($user) ? : null);
	}

	public function get_all_users()
	{
		$this -> db -> select('id, name, email, is_active');
		return $this->db->get($this->table) -> result();

	}

	public function delete($id)
	{
		$result = $this->db->delete($this->table, ['id' => $id]);
		return $this->db->affected_rows();
	}

	public function check_register($email)
	{
		$result =  $this -> db -> get_where($this->table,
			['email' => $email,])
			-> result();
		return array_shift($result);
	}

	public function edit_user(array $data, $id)
	{
		$this->db->where('id', $id);
		$this->db->update($this->table, $data);
		return $this -> get_user_by_id($id);
	}
}
