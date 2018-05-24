<?php
	require_once __DIR__.'/autoload.php';
	require_once __DIR__.'/config.php';

	require_once __DIR__ . '/vendor/autoload.php';
	
	$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
	$twig = new Twig_Environment($loader);

	try {
	    	$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
	} catch (PDOException $e) {
	    	echo 'Подключение не удалось: ' . $e->getMessage();
	}

	session_start();

	function clearInput($input) 
	{
		return htmlspecialchars(strip_tags($input));
	}

	function checkPass($password) 
	{
		if ((strlen($password)>=8) && ((preg_match('/[a-z]/',$password))*(preg_match('/[A-Z]/',$password))*(preg_match('/[0-9]/',$password))==1)){
			return true;
		}
		return false;
	}

?>