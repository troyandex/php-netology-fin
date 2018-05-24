<?php
class CAdmin
{
	public function __call($name, $arguments) {
		die('Неизвестное действие');
	}

	public function manage($pdo, $twig)
	{	
		$admin = new Admin();
		if (!$admin->isLogedin($pdo)) {
			$template = $twig ->loadTemplate('main.html');
			$params = array('themes' => Theme::getAllThemes($pdo));
			$template->display($params);
			exit();
		}
		$admins = $admin->getAllAdmins($pdo);
		if ($admins) {
			$template = $twig->loadTemplate('admin.html');
			$params = array('user'=>$_SESSION['admin'], 'content' => 'admin_manage.html', 'admins' => $admins);
			$template->display($params);
		}
		else {
			die("Не получена информация об администраторах.");
		}
	}

	public function reg($pdo, $twig)
	{
		$template = $twig->loadTemplate('admin.html');
		$params = array('user'=>$_SESSION['admin'], 'content' => 'registration.html');
		$template->display($params);
	}
	
	public function add($pdo, $twig)
	{
		if ((isset($_GET['login'])) && (isset($_GET['pass'])) && (isset($_GET['email']))) {
			$login = clearInput($_GET['login']);
			$password = clearInput($_GET['pass']);
			$email = clearInput($_GET['email']);
			$user = new Admin();
			if (!$user->getUserByLogin($login, $pdo)) {
				if (checkPass($password)) {
					if ($user->addUser($login, $password, $email, $pdo)) {
						header('Location: index.php?contr=admin&act=manage');
					}
					else {
						die ('Ошибка регистрации пользователя');
					}
				}
				else {
					die('Пароль не достаточно сложный<br>Пароль должен состоять минимум из 8 символов, содержать большие и маленькие буквы латинского алфавита и цифры');
				}
			}
			else {
				die('Пользователь с таким именем уже зарегистрирован');
			}
		}	
	}
	
	public function login($pdo, $twig)
	{
		$template = $twig->loadTemplate('login.html');
		$params = array();
		$template->display($params);
	}
	
	public function auth($pdo, $twig)
	{
		if ((isset($_GET['login'])) && (isset($_GET['pass']))) {
			$login = clearInput($_GET['login']);
			$password = clearInput($_GET['pass']);
			$admin = new Admin();
			if ($admin->loginUser($login,$password,$pdo)) {
				header('Location: index.php');
			}
			else {
				die('Неверное имя пользователя или пароль');
			}
		}
	}

	public function logout($pdo, $twig)
	{
		session_start();
		session_destroy();
		header('Location: index.php');
	}

	public function resetpass($pdo, $twig)
	{
		$admin = new Admin();
		if (!$admin->isLogedin($pdo)) header('Location: index.php?contr=admin&act=login');
		if (isset($_GET['id'])) {
			$id = clearInput($_GET['id']);
			$admin->getUserById($id,$pdo);
			$template = $twig->loadTemplate('admin.html');
			$params = array('user'=>$_SESSION['admin'], 'content' => 'admin_reset_password.html', 'admin' => $admin);
			$template->display($params);
		}
	}

	public function setpass($pdo, $twig)
	{
		$admin = new Admin();
		if (!$admin->isLogedin($pdo)) header('Location: index.php?contr=admin&act=login');
		if ((isset($_GET['login'])) && (isset($_GET['pass']))) {
			$login = clearInput($_GET['login']);
			$password = clearInput($_GET['pass']);
			if ($admin->getUserByLogin($login, $pdo)) {
				if (checkPass($password)) {
					if ($admin->setPassword($password, $pdo)) {
						header('Location: index.php?contr=admin&act=manage');
					}
					else {
						die ('Ошибка задания пароля');
					}
				}
				else {
					die('Пароль не достаточно сложный<br>Пароль должен состоять минимум из 8 символов, содержать большие и маленькие буквы латинского алфавита и цифры');
				}
			}
			else {
				die('Пользователь с таким именем не зарегистрирован');
			}
		}	
	}

	public function delete($pdo, $twig)
	{
		$admin = new Admin();
		if (!$admin->isLogedin($pdo)) header('Location: index.php?contr=admin&act=login');
		if (isset($_GET['id'])) {
			$id = clearInput($_GET['id']);
			$admin->getUserById($id,$pdo);
			$admin->deleteMe($pdo);
			header('Location: index.php?contr=admin&act=manage');
		}
		else {
			die('Не удалось удалить пользователя');
		}
	}
}	
?>
