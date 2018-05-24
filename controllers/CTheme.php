<?php
class CTheme
{
	public function __call($name, $arguments) {
		die('Неизвестное действие');
	}
	
	public function manage($pdo, $twig)
	{
		$admin = new Admin();
		if (!$admin->isLogedin($pdo)) header('Location: index.php?contr=admin&act=login');
		$themes = Theme::getAllThemes($pdo);
		$template = $twig->loadTemplate('admin.html');
		$params = array('user'=>$_SESSION['admin'], 'content' => 'theme_manage.html', 'themes' => $themes);
		$template->display($params);
	}

	public function add($pdo, $twig)
	{
		$admin = new Admin();
		if (!$admin->isLogedin($pdo)) header('Location: index.php?contr=admin&act=login');
		if (isset($_GET['name'])) {
			$name = clearInput($_GET['name']);
			$theme = new Theme();
			if ($theme->getThemeByName($name, $pdo)) {
				die("Тема с таким названием уже существует");
			}
			$theme->addTheme($name, $pdo);
			header('Location: index.php?contr=theme&act=manage');
		}
		else {
			die("Ошибка добавления темы");
		}	
	}

	public function delete($pdo, $twig)
	{
		$admin = new Admin();
		if (!$admin->isLogedin($pdo)) header('Location: index.php?contr=admin&act=login');
		if (isset($_GET['id'])) {
			$id = clearInput($_GET['id']);
			$theme = new Theme();
			$theme->getThemeById($id, $pdo);
			$theme->deleteMe($pdo);
			header('Location: index.php?contr=theme&act=manage');
		}
		else {
			die('Не удалось удалить тему');
		}
	}

	public function rename($pdo, $twig)
	{
		$admin = new Admin();
		if (!$admin->isLogedin($pdo)) header('Location: index.php?contr=admin&act=login');
		if (isset($_GET['id'])) {
			$id = clearInput($_GET['id']);
			$theme = new Theme();
			$theme->getThemeById($id, $pdo);
			$template = $twig->loadTemplate('admin.html');
			$params = array(
				'content' => 'theme_rename.html',
				'theme' => $theme
			);
			$template->display($params);
		}
	}

	public function update($pdo, $twig)
	{
		$admin = new Admin();
		if (!$admin->isLogedin($pdo)) header('Location: index.php?contr=admin&act=login');
		if ((isset($_GET['id'])) && (isset($_GET['name']))) {
			$id = clearInput($_GET['id']);
			$name = clearInput($_GET['name']);
			$theme = new Theme();
			$theme->getThemeById($id, $pdo);
			$theme->setName($name, $pdo);
			header('Location: index.php?contr=theme&act=manage');
		}
	}
	
}
?>
