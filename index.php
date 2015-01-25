<?php
	
	error_reporting(E_ALL);
	
	define('DS', DIRECTORY_SEPARATOR);
	define('ROOT', __DIR__.DS);
	define('SYSPATH', ROOT.'system'.DS);
	define('APPPATH', ROOT.'application'.DS);
	define('FILES', ROOT.'files'.DS);
	
	include SYSPATH.'Autoload.php';
	
	spl_autoload_register('Autoload::autoloader');
	
	echo Router::init();