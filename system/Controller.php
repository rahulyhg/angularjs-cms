<?php
	class Controller{
		
		public function param($key){
			$params = Router::$params;
			if(isset($params[$key])){
				return $params[$key];
			}
			
			return FALSE;
		}
		
		public function before(){}
		
		public function after(){}
		
		public function redirect($url, $code = 302){
			$redirectTo = '';
			if(strpos($url, '://') !== FALSE){
				$redirectTo = $url;
			}
			else{
				$redirectTo = URL::base().trim($url, '/');
			}
			
			header('Location: '.$redirectTo, TRUE, $code);
			die;
		}
		
	}
