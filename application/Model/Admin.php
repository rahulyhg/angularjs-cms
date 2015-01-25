<?php
	class Model_Admin {
		
		/**
		*	Get user for any purpose, recieve boolean username name of false if user does not exist.
		*/
		public static function getUser($username, $password){
			$username = Encode::encrypt($username);
			$password = Encode::encrypt($password);
			
			$sql = "SELECT * FROM `users` 
					WHERE `username`='$username' 
					AND `password`='$password' 
					LIMIT 1";
			
			$data = DB::query($sql);
			
			$count = count($data);
			
			if($count === 0){
				
				return FALSE;
			}
			
			$data[0]['username'] = Encode::decrypt($data[0]['username']);
			
			return $data[0];
		}

		/**
		*	Valid function to use most calls from Createit controller, renders back "remember" for admin panel page
		*	and login page, will render boolean true if user is connected or boolean false if user is not connect
		*	or has not set "Remember me" as an option.
		*/		
		public static function valid(){
			$cookie = Cookies::get('token');
			$cookie = unserialize($cookie);
			
			if(!empty($cookie)){
			
				if($cookie['token'] != 'none'){
					$token = $cookie['token'];
					$id = $cookie['id'];
					
					$sql = "SELECT `id` FROM `users` WHERE `id`='$id' AND `token`='$token' LIMIT 1";
					$res = DB::query($sql);
					
					if(!empty($res)){
						
						return 'remember';
					}
					else{
						return FALSE;
					}
				}
				else{
					if(Encode::decrypt($cookie['valid']) == 'Ft#-Tre-H&(-8Hm'){
						$valid = Encode::encrypt('Wk@-Ynq-M*]-2px');
						$cookie['valid'] = $valid;
						Cookies::set('token', serialize($cookie), '2592000');
						return TRUE;
					}
					else{
						return FALSE;
					}
				}
			}
			else{
				
				return FALSE;
			}
		}
		
		/**
		*	If remember been set a random token will be put in DB
		*/
		public static function insertToken($id, $token){
			$sql = "UPDATE `users` SET `token`='$token' WHERE `id` = '$id'";
			$res = DB::query($sql);
			return $res;
		}
		
		/**
		*	Login to admin panel and set "token" if remember me been set, set valid key for checking in Valid function
		*/		
		public static function login($id, $remember){
			$info = array();
			
			$valid = Encode::encrypt('Ft#-Tre-H&(-8Hm');
			$info['valid'] = $valid;
			
			$token = "";
			if($remember == 'remember'){
				$token = md5(uniqid(rand(), true));
			}
			else{
				$token = 'none';
			}
			
			$info['token'] = $token;
			$info['id'] = $id;
			Cookies::set('token', serialize($info), '2592000');
			
			return $token;
		}
		
		/**
		*	##################################
		*	########## Manage files ##########
		*	##################################
		*/
		
		public static function insertFile($name){
			$sql = "INSERT INTO `files` (`file`)
					VALUES ('$name')";
			DB::query($sql);	
		}
		
		public static function getAllFiles(){
			$sql = "SELECT * FROM `files`";
			$res = DB::query($sql);
			
			return $res;
		}

		public static function getFileDetails($id){
			$sql = "SELECT `id`,`title`,`alt` FROM `files` WHERE id='$id' LIMIT 1";
			$res = DB::query($sql);

			return $res;
		}

		public static function saveFile($id, $title, $alt){
			$sql = "UPDATE `files` SET `title`='$title',`alt`='$alt' WHERE id='$id'";
			DB::query($sql);
		}
		
		public static function deleteFile($id){
			$sql = "SELECT `file` FROM `files` WHERE id='$id' LIMIT 1";
			$res = DB::query($sql);
			
			if(!isset($res[0])){

				return FALSE;
			}
			unlink(FILES.'images'.DS.$res[0]['file']);
			$sql = "DELETE FROM `files` WHERE `id`='$id'";
			DB::query($sql);

			return TRUE;
		}
		
		/**
		*	#####################################
		*	########## Manage settings ##########
		*	#####################################
		*/
		
		public static function getSettings(){
			$sql = "SELECT * FROM `settings`";
			$res = DB::query($sql);
			
			return $res;
		}
		
		public static function updateSettings($title, $desc, $key){
			$sql = "UPDATE `settings` SET `site_title`='$title',`site_description`='$desc',`site_keywords`='$key' WHERE id='1'";
			DB::query($sql);
		}
		
		public static function setWallpaper($wp){
			$sql = "UPDATE `settings` SET `wallpaper_id`='$wp' WHERE id='1'";
			DB::query($sql);
		}
		
		public static function getWp(){
			$sql = "SELECT files.id,files.file,settings.wallpaper_id FROM `files`,`settings` WHERE files.id=settings.wallpaper_id";
			$res = DB::query($sql);
			
			return $res;
		}
		
		/**
		*	##################################
		*	########## Manage pages ##########
		*	##################################
		*/
		
		public static function getAllPages(){
			$sql = "SELECT pages.id,pages.title,files.file FROM `pages`,`files` WHERE pages.lead_image_id = files.id AND pages.remove != 1";
			$res = DB::query($sql);
			
			return $res;
		}
		
		public static function getPageById($id){
			$sql = "SELECT `id`, `title`, `content`, `lead_image_id`, `template` FROM `pages` WHERE `id`='$id'";
			$res = DB::query($sql);
			
			return $res;
		}
		
		public static function setLeadImage($pageId, $imageId){
			$sql = "UPDATE `pages` SET `lead_image_id`='$imageId' WHERE `id`=$pageId";
			$res = DB::query($sql);
			
			return $res;
		}
		
		public static function getFileName($id){
			$sql = "SELECT `file` FROM `files` WHERE `id`='$id'";
			$res = DB::query($sql);
			
			return $res;
		}
		
		public static function insertNewPage($title, $date, $content, $template){
			$cookie = Cookies::get('token');
			$cookie = unserialize($cookie);
			$id = $cookie['id'];
			
			$sql = "INSERT INTO `pages`(`title`, `content`, `user_id`, `date`, `template`) VALUES ('$title','$content','$id','$date','$template')";
			DB::query($sql);
			
			$sql = "SELECT * FROM `pages` ORDER BY ID DESC LIMIT 1";
			$res = DB::query($sql);
			
			return $res;
		}
		
		public static function updatePage($id, $title, $date, $content, $template){
			$sql = "UPDATE `pages` SET `title`='$title',`content`='$content',`date`='$date',`template`='$template' WHERE `id`='$id'";
			DB::query($sql);
			
			$sql = "SELECT * FROM `pages` WHERE `id`='$id'";
			$res = DB::query($sql);
			
			return $res;
		}
		
		public static function deletePage($id){
			$sql = "UPDATE `pages` SET `remove`=1 WHERE `id`='$id'";
			DB::query($sql);
			
			return $res;
		}
		
		public static function unDelete($id){
			$sql = "UPDATE `pages` SET `remove`=0 WHERE `id`='$id'";
			DB::query($sql);
			
			return $res;
		}
		
		public static function deleteForever($id){
			$sql = "DELETE FROM `pages` WHERE `id`='$id'";
			DB::query($sql);
			
			return $res;
		}
		
		public static function deletedPages(){
			$sql = "SELECT pages.id,pages.title,files.file FROM `pages`,`files` WHERE pages.lead_image_id = files.id AND pages.remove != 0";
			$res = DB::query($sql);
			
			return $res;
		}
		
		/**
		*	#######################################
		*	########## Manage portfolios ##########
		*	#######################################
		*/
		
		public static function getAllPortfolios(){
			$sql = "SELECT portfolio.id,portfolio.title,files.file FROM `portfolio`,`files` WHERE portfolio.lead_image_id = files.id AND portfolio.remove != 1";
			$res = DB::query($sql);
			
			return $res;
		}
		
		public static function getPfById($id){
			$sql = "SELECT `id`, `title`, `description`, `link`, `lead_image_id`, `category` FROM `portfolio` WHERE `id`='$id'";
			$res = DB::query($sql);
			
			return $res;
		}
		
		public static function portfolioLeadImage($pageId, $imageId){
			$sql = "UPDATE `portfolio` SET `lead_image_id`='$imageId' WHERE `id`='$pageId'";
			$res = DB::query($sql);
			
			return $res;
		}
		
		public static function insertNewPortfolio($title, $desc, $category, $link){			
			$sql = "INSERT INTO `portfolio`(`title`,`description`, `link`, `category`) VALUES ('$title','$desc','$link','$category')";
			DB::query($sql);
			
			$sql = "SELECT * FROM `portfolio` ORDER BY ID DESC LIMIT 1";
			$res = DB::query($sql);
			
			return $res;
		}
		
		public static function updatePortfolio($id, $title, $desc, $category, $link){
			$sql = "UPDATE `portfolio` SET `id`='$id',`title`='$title',`description`='$desc',`category`='$category',`link`='$link' WHERE `id`='$id'";
			DB::query($sql);
			
			$sql = "SELECT * FROM `portfolio` WHERE `id`='$id'";
			$res = DB::query($sql);
			
			return $res;
		}
		
		public static function deletePortF($id){
			$sql = "UPDATE `portfolio` SET `remove`=1 WHERE `id`='$id'";
			DB::query($sql);
			
			return $res;
		}
		
		
		public static function deletedPortF(){
			$sql = "SELECT portfolio.id,portfolio.title,files.file FROM `portfolio`,`files` WHERE portfolio.lead_image_id = files.id AND portfolio.remove != 0";
			$res = DB::query($sql);
			
			return $res;
		}
		
		public static function deletePortfolioForever($id){
			$sql = "DELETE FROM `portfolio` WHERE `id`='$id'";
			DB::query($sql);
			
			return $res;
		}
		
		public static function unDeletePortF($id){
			$sql = "UPDATE `portfolio` SET `remove`=0 WHERE `id`='$id'";
			DB::query($sql);
			
			return $res;
		}
		
		/**
		*	##################################
		*	########## Manage users ##########
		*	##################################
		*/
		
		public static function getAllUsers(){
			$sql = "SELECT `id`,`username`,`email`,`profile_image` FROM `users` WHERE `remove`!=1";
			$res = DB::query($sql);
			
			return $res;
		}

				
	}










