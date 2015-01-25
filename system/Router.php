<?php

	class Router{
		
		public static $router;
		public static $params = array();
		public static $name = '';
		
		/**
		 * Import configuration file/array for all routes
		 */
		private static function config(){
			// get the config file for all routes
			include APPPATH.'config'.DS.'router.php';
			// save routes to local variable
			self::$router = $route;
		}
		
		/**
		 * Initialize route
		 */
		public static function init(){
			self::config();
			// get the current uri
			// remove slashes (/) from the start and the end of the string with trim function
			// parameter q come from .htaccess settings
			$q = isset($_GET['q']) ? trim($_GET['q'], '/') : '';
			// convert the string to array by '/' seperator
			$q = explode('/', $q);
			
			// Loop through all configured routes
			foreach(self::$router as $name => $localRoute){
				// convert the uri string to array by '/' seperator
				$insideArray = explode('/', $localRoute['uri']);
				// Check if number of elements in array match
				if(count($q) !== count($insideArray)){
					// start over this loop with next element
					continue;
				}
				
				$flag = FALSE;
				
				self::$params = array();

				foreach($insideArray as $k => $v){
					// Find the position (numeric or false) of wanted character/word
					if(strpos($v, ':') === 0){
						$temp = str_replace(':', '', $v);
						self::$params[$temp] = $q[$k];
						$flag = TRUE;
						continue;
					}
					
					// Check for exact string as wanted in our route config
					if($v == $q[$k]){
						$flag = TRUE;
						continue;
					}
					
					// if none of the above exist
					$flag = FALSE;
					break;
				}
				
				// Check if flag is true
				// if its true - we found what we want
				if($flag){
					self::$name = $name;
					break;
				}
			} // End foreach
			
			// Dont found anything
			if(self::$name == ''){
				die("Undefined route.<br>\nEdit your custom error message in system/Router.php");
				/**
				* Set an Error page for custom view (error.php)
				* For example: return View::load('error')->render();
				*/
			}
			
			$controller = 'Controller_'.ucfirst(self::$router[self::$name]['controller']);
			$method = self::$router[self::$name]['method'];
			
			$controller = new $controller;
			$controller->before(); // If you want to add some code before controller generates (security?)
			$controller->{$method}();
			$controller->after(); // If you want to add some code after controller finished
		
		}
	}