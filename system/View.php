<?php

	class View{
		
		public $view_file = NULL;
		public $params = array();
		public static $global_vars = array();
		
		private function __construct($filename){
			$filename = APPPATH.'View'.DS.$filename.'.php';
			if(file_exists($filename)){
				$this->view_file = $filename;
			}
			else{
				// Your custom error view
				$this->view_file = 'error.php';
			}
		}
		
		public static function load($filename){
			return new View($filename);
		}
		
		public function set($key, $value){
			$this->params[$key] = $value;
			return $this;
		}
		
		public function render(){
			extract($this->params, EXTR_SKIP);
			extract(self::$global_vars, EXTR_SKIP);
			include $this->view_file;
		}
		
		public static function global_vars($key, $value){
			self::$global_vars[$key] = $value;
		}
		
	}
