<?php
	class Autoload{

		public static function autoloader($className){
			$className .= '.php';
			$className = explode('_', $className);
			
			/*
foreach($className as &$cls){
				$cls = ucfirst($cls);
			}
*/
			
			$className = implode(DS, $className);			
			
			if(file_exists(APPPATH.$className)){
				include APPPATH.$className;
			}
			elseif(file_exists(SYSPATH.$className)){
				include SYSPATH.$className;
			}
			else{
				die("No such file as $className<br>\nEdit your custom error message in system/Autoload.php");
				/**
				* Set an Error page for custom view (error.php)
				* For example: return View::load('error')->render();
				*/
			}
		}
		
	}
