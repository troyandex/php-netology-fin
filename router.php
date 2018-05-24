<?php
if (!((isset($_GET['contr'])) && ($_GET['contr']!='') && (isset($_GET['act'])) && ($_GET['act']!=''))){
	$contr = 'admin';
	$methodName = 'manage'; // при первом запуске можно заменить на reg чтобы зарегестрировать первого админа
}
else {
	$contr = clearInput($_GET['contr']);
	$methodName = clearInput($_GET['act']);
}
$className = 'C'.ucfirst($contr);
$controller = new $className();
$controller->$methodName($pdo, $twig);
?>
