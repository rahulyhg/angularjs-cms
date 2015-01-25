<?php
	header('Content-type: text/html; charset=utf-8');

	class Controller_Createit extends Controller{
		
		public function home(){
			if(! $this->param('action')){
				return View::load('homepage')->render();
			}
			
			$action = $this->param('action');
			if(! method_exists($this, $action)){
				return View::load('error')->render();
			}
			
			$this->{$action}();
		}
		
		/**
		*	Login page
		*/
		
		private function aeLogin(){
			$valid = Model_Admin::valid();
			
			if($valid === 'remember'){
				return $this->redirect('aeadmin');
			}
			else{
				
				return View::load('aelogin')->render();
			}
		}

		/**
		*	Check for authentication in login page
		*/		
		private function check(){
			$username = $_POST['data'][0]['username'];
			$password = $_POST['data'][0]['password'];
			$remember = $_POST['data'][0]['remember'];
			
			$data = Model_Admin::getUser($username, $password);		
			if($data != false){
				if($remember == 'true'){
					$token = Model_Admin::login($data['id'], 'remember');
					Model_Admin::insertToken($data['id'], $token);
					echo 'yes';
				}
				else{
					$token = Model_Admin::login($data['id'], 'none');
					Model_Admin::insertToken($data['id'], $token);
					echo 'yes';
				}
			}
			else{
				echo 'no';
			}
		}
		
		/**
		*	Admin panel
		*/
		private function aeAdmin(){			
			$valid = Model_Admin::valid();
			if($valid || $valid === 'remember'){
				
				return View::load('aeadmin')->render();
			}
			else{
				
				return $this->redirect('logout');
			}
		}
		
		/**
		*	Logout from admin panel
		*/
		private function logout(){
			Cookies::delete('token');
			$this->redirect('aelogin');
		}
		
		/**
		*	########################################
		*	########## Manage files calls ##########
		*	########################################
		*/
		
		/**
		*	Upload file to server
		*/
		private function upload(){
			$valid = Model_Admin::valid();
			if($valid){
				if(!isset($_FILES['file'])){
					echo 'Add message for no file has been uploaded.';
					die;
				}
				$file = $_FILES['file'];
				if(Upload::valid($file) && Upload::type($file, array('jpg', 'jpeg', 'gif', 'png'))){
					$newname = Upload::save($file, 'files/images/');
					$file = Model_Admin::insertFile($newname);
					$files = Model_Admin::getAllFiles();
			
					echo json_encode($files);
				}
			}
			else{
				
				return $this->redirect('logout');
			}			
		}

		/**
		*	Gel all files to show
		*/		
		private function getFiles(){
			$valid = Model_Admin::valid();
			if($valid){
				$files = Model_Admin::getAllFiles();
			
				echo json_encode($files);
			}
			else{
				
				return $this->redirect('logout');
			}
		}

		/**
		*	Edit file by id
		*/
		private function editFile(){
			$valid = Model_Admin::valid();
			if($valid){
				$id = $_POST['data'];
				$data = Model_Admin::getFileDetails($id);
				
				echo json_encode($data);
			}
			else{
				
				return $this->redirect('logout');
			}
		}

		/**
		*	Save file by id
		*/
		private function saveFile(){
			$valid = Model_Admin::valid();
			if($valid){
				$id = $_POST['data']['id'];
				$title = $_POST['data']['title'];
				$alt = $_POST['data']['alt'];
	
				Model_Admin::saveFile($id, $title, $alt);
			}
			else{
				
				return $this->redirect('logout');
			}
		}
		
		/**
		*	Delete file by id
		*/
		private function deleteFile(){$valid = Model_Admin::valid();
			$valid = Model_Admin::valid();
			if($valid){
				$file = $_POST['data'];

				Model_Admin::deleteFile($file);
			}
			else{
				
				return $this->redirect('logout');
			}
		}
		
		/**
		*	###########################################
		*	########## Manage settings calls ##########
		*	###########################################
		*/
		
		/**
		*	Send settings to panel
		*/
		private function getSettings(){$valid = Model_Admin::valid();
			$valid = Model_Admin::valid();
			if($valid){
				
				$settings = Model_Admin::getSettings();
			
				echo json_encode($settings);
			}
			else{
				
				return $this->redirect('logout');
			}
		}
		
		/**
		*	Update settings
		*/
		private function updateSettings(){
			$valid = Model_Admin::valid();
			if($valid){
				$title = $_POST['data']['title'];
				$desc = $_POST['data']['desc'];
				$key = $_POST['data']['key'];
			
				Model_Admin::updateSettings($title, $desc, $key);
			}
			else{
				
				return $this->redirect('logout');
			}
		}
		
		/**
		*	Set wallpaper by image id
		*/
		private function setWallpaper(){
			$valid = Model_Admin::valid();
			if($valid){
				$wp = $_POST['data'];
	
				Model_Admin::setWallpaper($wp);
			}
			else{
				
				return $this->redirect('logout');
			}
		}
		
		/**
		*	Send wallpaper image
		*/
		private function getWp(){
			$wp = Model_Admin::getWp();
		
			echo json_encode($wp);
		}
		
		/**
		*	########################################
		*	########## Manage pages calls ##########
		*	########################################
		*/

		/**
		*	Send all pages
		*/
		private function getAllPages(){
			$valid = Model_Admin::valid();
			if($valid){
				$allPages = Model_Admin::getAllPages();
				
				echo json_encode($allPages);
			}
			else{
				
				return $this->redirect('logout');
			}
		}

		/**
		*	Edit page by id
		*/
		private function editPage(){
			$valid = Model_Admin::valid();
			if($valid){
				$id = $_POST['data'];
				$page = Model_Admin::getPageById($id);
				
				$fileId = $page[0]['lead_image_id'];
				$fileName = Model_Admin::getFileName($fileId);
				$page[0]['lead_image_file'] = $fileName[0]['file'];
				
				$dir = APPPATH.'View';
				$files = scandir($dir);
				unset($files[0]); unset($files[1]);
				$templates = str_replace('.php', '', $files);
				
				$key = array_search($page[0]['template'], $templates);
				$key = $key-1;
				$page[0]['template'] = $key;
				
				$data = array();
				$data['page'] = $page;
				$data['templates'] = $templates;
				
				echo json_encode($data);
			}
			else{
				
				return $this->redirect('logout');
			}
		}
		
		/**
		*	Send template on load
		*/
		private function getTemplates(){
			$valid = Model_Admin::valid();
			if($valid){
				$dir = APPPATH.'View';
				$files = scandir($dir);
				unset($files[0]); unset($files[1]);
				$templates = str_replace('.php', '', $files);
				
				echo json_encode($templates);
			}
			else{
				
				return $this->redirect('logout');
			}
		}
		
		/**
		*	Set lead image for page
		*/
		private function setLeadImage(){
			$valid = Model_Admin::valid();
			if($valid){
				$pageId = $_POST['data'][0]['pageId'];
				$imageId = $_POST['data'][0]['imageId'];
				
				$data = Model_Admin::setLeadImage($pageId, $imageId);
				if($data){
					$file = Model_Admin::getFileName($imageId);
					
					echo json_encode($file);
				}
				else{
					echo 'no';
				}
			}
			else{
				
				return $this->redirect('logout');
			}
		}
		
		/**
		*	Get file by id
		*/
		private function getFileName(){
			$valid = Model_Admin::valid();
			if($valid){
				$id = $_POST['data'];
				$fileName = Model_Admin::getFileName($id);
				
				echo json_encode($fileName);
			}
			else{
				
				return $this->redirect('logout');
			}
		}

		/**
		*	Save new page/existing one, and send it to panel
		*/		
		private function saveAndUpdate(){
			$valid = Model_Admin::valid();
			if($valid){
				if(empty($_POST['data'][0]['id'])){
					// Save new page
					$title = $_POST['data'][0]['title'];
					$date = $_POST['data'][0]['date'];
					$content = $_POST['data'][0]['content'];
					$template = $_POST['data'][0]['template'];
					
					$page = Model_Admin::insertNewPage($title, $date, $content, $template);
					
					$dir = APPPATH.'View';
					$files = scandir($dir);
					unset($files[0]); unset($files[1]);
					$files = str_replace('.php', '', $files);
					
					$key = array_search($page[0]['template'], $files);
					$key = $key-1;
					$page[0]['template'] = $key;
					
					echo json_encode($page);
				}
				else{
					// Update existing page
					$id = $_POST['data'][0]['id'];
					$title = $_POST['data'][0]['title'];
					$date = $_POST['data'][0]['date'];
					$content = $_POST['data'][0]['content'];
					$template = $_POST['data'][0]['template'];
					
					$page = Model_Admin::updatePage($id, $title, $date, $content, $template);
					
					$dir = APPPATH.'View';
					$files = scandir($dir);
					unset($files[0]); unset($files[1]);
					$files = str_replace('.php', '', $files);
					
					$key = array_search($page[0]['template'], $files);
					$key = $key-1;
					$page[0]['template'] = $key;
					
					echo json_encode($page);
				}
			}
			else{
				
				return $this->redirect('logout');
			}
		}
		
		/**
		*	Soft delete page by id
		*/
		private function deletePage(){
			$valid = Model_Admin::valid();
			if($valid){
				$id = $_POST['data'];
				Model_Admin::deletePage($id);
				
				$allPages = Model_Admin::getAllPages();
					
				echo json_encode($allPages);
			}
			else{
				
				return $this->redirect('logout');
			}
		}
		
		/**
		*	Undelete page by id
		*/
		private function unDelete(){
			$valid = Model_Admin::valid();
			if($valid){
				$id = $_POST['data'];
				Model_Admin::unDelete($id);
				
				$deleted = Model_Admin::deletedPages();
				
				echo json_encode($deleted);
			}
			else{
				
				return $this->redirect('logout');
			}
		}
		
		/**
		*	Delete page by id, forever
		*/
		private function deleteForever(){
			$valid = Model_Admin::valid();
			if($valid){
				$id = $_POST['data'];
				Model_Admin::deleteForever($id);
				
				$deleted = Model_Admin::deletedPages();
				
				echo json_encode($deleted);
			}
			else{
				
				return $this->redirect('logout');
			}
		}
		
		/**
		*	Get all deleted pages
		*/
		private function deletedPages(){
			$valid = Model_Admin::valid();
			if($valid){
				$deleted = Model_Admin::deletedPages();
				
				echo json_encode($deleted);
			}
			else{
				
				return $this->redirect('logout');
			}
		}

		/**
		*	#############################################
		*	########## Manage portfolios calls ##########
		*	#############################################
		*/
		
		/**
		*	Get all portfolios
		*/
		private function getAllPortfolios(){
			$valid = Model_Admin::valid();
			if($valid){
				$allPf = Model_Admin::getAllPortfolios();
				
				echo json_encode($allPf);
			}
			else{
				
				return $this->redirect('logout');
			}
		}
		
		/**
		*	Show portfolio by id
		*/
		private function editPortfolio(){
			$valid = Model_Admin::valid();
			if($valid){
				$id = $_POST['data'];
				$portF = Model_Admin::getPfById($id);
				
				$fileId = $portF[0]['lead_image_id'];
				$fileName = Model_Admin::getFileName($fileId);
				$portF[0]['lead_image_file'] = $fileName[0]['file'];
				
				echo json_encode($portF[0]);
			}
			else{
				
				return $this->redirect('logout');
			}
		}
		
		/**
		*	Set lead image for portfolio
		*/
		private function portfolioLeadImage(){
			$valid = Model_Admin::valid();
			if($valid){
				$pageId = $_POST['data'][0]['pageId'];
				$imageId = $_POST['data'][0]['imageId'];
				
				$data = Model_Admin::portfolioLeadImage($pageId, $imageId);
				if($data){
					$file = Model_Admin::getFileName($imageId);
					
					echo json_encode($file);
				}
				else{
					echo 'no';
				}
			}
			else{
				
				return $this->redirect('logout');
			}
		}
		
		/**
		*	Save new portfolio/existing one, and send it back to panel
		*/		
		private function saveAndUpdatePortfolio(){
			$valid = Model_Admin::valid();
			if($valid){
				if(empty($_POST['data']['id'])){
					// Save new portfolio
					$title = $_POST['data']['title'];
					$desc = $_POST['data']['description'];
					$category = $_POST['data']['category'];
					$link = $_POST['data']['link'];				
					
					$portF = Model_Admin::insertNewPortfolio($title, $desc, $category, $link);
					
					echo json_encode($portF);
				}
				else{
					// Update existing page
					$id = $_POST['data']['id'];
					$title = $_POST['data']['title'];
					$desc = $_POST['data']['description'];
					$category = $_POST['data']['category'];
					$link = $_POST['data']['link'];
					
					$portF = Model_Admin::updatePortfolio($id, $title, $desc, $category, $link);
										
					echo json_encode($portF);
				}
			}
			else{
				
				return $this->redirect('logout');
			}
		}
		
		/**
		*	Soft delete portfolio by id
		*/
		private function deletePortfolio(){
			$valid = Model_Admin::valid();
			if($valid){
				$id = $_POST['data'];
				Model_Admin::deletePortF($id);
				
				$allPf = Model_Admin::getAllPortfolios();
					
				echo json_encode($allPf);
			}
			else{
				
				return $this->redirect('logout');
			}
		}
		
		/**
		*	Get all deleted portfolios
		*/
		private function deletedPortF(){
			$valid = Model_Admin::valid();
			if($valid){
				$deleted = Model_Admin::deletedPortF();
			
				echo json_encode($deleted);
			}
			else{
				
				return $this->redirect('logout');
			}
		}
		
		/**
		*	Delete portfolio by id, forever.
		*/
		private function deletePortfolioForever(){
			$valid = Model_Admin::valid();
			if($valid){
				$id = $_POST['data'];
				Model_Admin::deletePortfolioForever($id);
				
				$deleted = Model_Admin::deletedPortF();
				
				echo json_encode($deleted);
			}
			else{
				
				return $this->redirect('logout');
			}
		}
		
		/**
		*	Undelete portfolio by id
		*/
		private function unDeletePortF(){
			$valid = Model_Admin::valid();
			if($valid){
				$id = $_POST['data'];
				Model_Admin::unDeletePortF($id);
				
				$deleted = Model_Admin::deletedPortF();
				
				echo json_encode($deleted);
			}
			else{
				
				return $this->redirect('logout');
			}
		}
		
		/**
		*	########################################
		*	########## Manage users calls ##########
		*	########################################
		*/
		
		/**
		*	Show all users
		*/
		private function getAllUsers(){
			$valid = Model_Admin::valid();
			if($valid){
				$users = Model_Admin::getAllUsers();
				
				foreach($users as &$user){
					$user['username'] = Encode::decrypt($user['username']);
				}
				
				echo json_encode($users);
			}
			else{
				
				return $this->redirect('logout');
			}
		}

	}







