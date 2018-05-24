<?php

class Admin 
{
	private $id;
	private $name;
	private $password;
	private $email;

	public function getUserByLogin ($name, $pdo)
	{
		$sql = "SELECT * FROM `admin` WHERE `name`='".$name."'";
		$data = $pdo->query($sql);
		if ($data) {
			foreach ($data as $admin) {
				$this->id = $admin['id'];
				$this->name = $admin['name'];
				$this->password = $admin['password'];
				$this->email = $admin['email'];
				return true;
			}
		}
		return false;
	}

	public function getUserById ($id, $pdo)
	{
		$sql = "SELECT * FROM admin WHERE id=".$id;
		$data = $pdo->query($sql);
		if ($data) {
			foreach ($data as $admin) {
				$this->id = $admin['id'];
				$this->name = $admin['name'];
				$this->password = $admin['password'];
				$this->email = $admin['email'];
				return true;
			}
		}
		return false;
	}

	public function addUser ($name, $password, $email, $pdo)
	{
		$this->password = hash('sha256', $password);
		$this->name = $name;
		$this->email = $email;
		$sql = "INSERT INTO `admin` (`name`, `password`, `email`) VALUES ('".$this->name."', '".$this->password."', '".$this->email."')";
		return $pdo->exec($sql);
	}

	public function loginUser ($name, $password, $pdo)
	{
		$password = hash('sha256', $password);
		$sql = "SELECT * FROM `admin` WHERE `name`='".$name."' AND `password`='".$password."'";
		if ($pdo->query($sql)->rowCount()==1){
			$_SESSION['admin'] = $name;
			$_SESSION['password'] = $password;
			return true;
		}
		else {
			return false;
		}
	}

	public function isLogedin($pdo)
	{
		if ((isset($_SESSION['admin']))&&(isset($_SESSION['password']))) {
			$name = clearInput($_SESSION['admin']);
			$hash = clearInput($_SESSION['password']);
			if ($this->getUserByLogin($name, $pdo)){
				if ($this->password == $hash) {
					return true;
				}
			}
		}
		return false;
	}

	public function setPassword($password, $pdo)
	{
		$password = hash('sha256', $password);
		$sql = "UPDATE `admin` SET `password`='".$password."' WHERE `id`=".$this->id;
		if ($pdo->exec($sql)==1) {
			return true;
		}
		else {
			return false;
		}
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function printUserForInfo()
	{
		echo "<tr>
				<td>$this->name</td>
				<td>$this->email</td>
				<td><a href='index.php?contr=admin&act=resetpass&id=$this->id'>Задать пароль</a></td>
				<td><a href='index.php?contr=admin&act=delete&id=$this->id'>Удалить</a></td>
			</tr>";
	}

	public function deleteMe($pdo)
	{
		$sql = "DELETE FROM `admin` WHERE `id`=".$this->id;
		if ($pdo->exec($sql)==1) {
			return true;
		}
		else {
			return false;
		}
	}

	public function getAllAdmins($pdo)
	{
		$sql = 'SELECT id FROM admin';
		$data = $pdo->query($sql);
		if ($data) {
			$allAdmins = array();
			foreach ($data as $value) {
				$admin = new Admin();
				$admin->getUserById($value['id'], $pdo);
				$allAdmins[] = $admin;
			}
			return $allAdmins;
		}
		else {
			return false;
		}
	}
}
?>
