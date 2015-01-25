<?php
	class Upload{
		
		public static $destination = 'files';
		
		/**
		 * Check if the file is valid to upload
		 * @param array $file The $_FILES['field']
		 * @return boolean
		 */
		public static function valid(array $file){
			return 	isset($file['name']) &&
					isset($file['tmp_name']) &&
					isset($file['error']) &&
					isset($file['type']) &&
					isset($file['size']) &&
					$file['error'] === UPLOAD_ERR_OK &&
					is_uploaded_file($file['tmp_name']);
		}
		
		/**
		 * Check if the file is allowed to upload
		 * @param array $file The $_FILES['field']
		 * @param array $allowed Array of valid extension
		 * @return boolean
		 */
		public static function type(array $file, array $allowed){
			$name = $file['name'];
			$ext = pathinfo($name, PATHINFO_EXTENSION);
			$ext = strtolower($ext);
			return in_array($ext, $allowed);
		}
		
		/**
		 * Upload the file
		 * If $directory is NULL or not real dir upload to self::$directory 
		 * @param array $file The $_FILES['field']
		 * @param string $directory Directory to upload to.
		 * @return string unique File Name
		 */
		public static function save(array $file, $destination = NULL){
			if(! $destination){
				$destination = trim(self::$destination, '/');
			}
			
			$destination = realpath($destination).DS;
			$name = uniqid().'_'.$file['name']; // Can do what ever encryption you would like: $name = uniqid().'_'.md5($file['name']);
			$name .= '.'.strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
			move_uploaded_file($file['tmp_name'], $destination.$name);
			return $name;
		}
		
	}
