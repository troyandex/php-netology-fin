<?php
	spl_autoload_register(
		function($className){
			$fileName = str_replace('\\', DIRECTORY_SEPARATOR, $className);
			if (file_exists("./classes/$fileName.php")) {
				require_once "./classes/$fileName.php";
			}
			elseif (file_exists("./controllers/$fileName.php")) {
				require_once "./controllers/$fileName.php";
			}
			else
				die('Неизвестное действие');
		}
	);
?>