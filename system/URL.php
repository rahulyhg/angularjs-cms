<?php

	class URL{
		
		public static function base(){
						
			$uri = $_SERVER['SCRIPT_NAME'];
			
			$uri = pathinfo($uri, PATHINFO_DIRNAME);
						
			return self::host().$uri.'/';
		}
		
		public static function site(){
			$uri = $_SERVER['REQUEST_URI'];
			$pos = strpos($uri, '?');
			if($pos !== FALSE){
				$uri = substr($uri, 0, $pos);
			}
			
			return self::host().$uri.'/';
		}
		
		private static function host(){
			$url = $_SERVER['HTTP_HOST'];
			if(stristr($_SERVER['SERVER_PROTOCOL'], 'https') !== FALSE){
				$url = 'https://'.$url;
			}
			else{
				$url = 'http://'.$url;
			}
			
			return $url;
		}
		
	}
