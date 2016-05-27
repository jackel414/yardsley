<?php

class UserMapper
{
	protected $conn;

	public function __construct($db) {
		$this->conn = $db;
	}

	public function getUsers()
	{
		$sql = $this->conn->query("SELECT * FROM users");
		$sql->execute();
		$results = $sql->fetchAll();

		$users = array('users' => array());
		foreach($results as $row) {
			$users['users'][] = $row;
		}
		return $users;
	}

	public function getUserById($id)
	{
		$sql = $this->conn->prepare("SELECT * FROM users WHERE id = :id");
		$sql->bindValue(":id", $id);
		$sql->execute();
		$result = $sql->fetch();

		$user = array('user' => $result);

		return $user;		
	}

	public function addUser($data)
	{
		$sql = $this->conn->prepare("INSERT INTO users (name, email, created_at, updated_at) VALUES (:name, :email, :created_at, :updated_at)");
		$sql->bindValue(":name", $data['name']);
		$sql->bindValue(":email", $data['email']);
		$sql->bindValue(":created_at", date("Y-m-d H:i:s"));
		$sql->bindValue(":updated_at", date("Y-m-d H:i:s"));
		$sql->execute();

		$user_sql = $this->conn->prepare("SELECT * FROM users WHERE id = :id");
		$user_sql->bindValue(":id", $this->conn->lastInsertId());
		$user_sql->execute();
		$result = $user_sql->fetch();

		$user = array('user' => $result);

		return $user;		
	}

	public function deleteUser($id)
	{
		$sql = $this->conn->prepare("DELETE FROM users WHERE id = :id");
		$sql->bindValue(":id", $id);
		$sql->execute();

		return true;
	}
}