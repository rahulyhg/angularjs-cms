<?php

	class DB{
		
		private static $env = array();
		private static $connect = NULL;
		
		private static function	config(){
			include APPPATH.'config'.DS.'database.php';
			self::$env = $db;
		}	
		
		private function __construct(){}
		
		private static function connect(){
			self::config();
			
			$host = self::$env['host'];
			$dbname = self::$env['dbname'];
			$user = self::$env['user'];
			$password = self::$env['pass'];
			
			self::$connect = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
		}
		
		public static function query($sql){
			
			if(! self::$connect){
				self::connect();
			}
			
			$res = array();

			if(stristr($sql, 'select')){
				$q = self::$connect->query($sql);
				$q->setFetchMode(PDO::FETCH_ASSOC);
				$res = $q->fetchAll();

			}
			else{
				$res = self::$connect->exec($sql);
			}
			
			return $res;

		}
	}