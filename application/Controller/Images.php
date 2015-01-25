<?php

	class Controller_Images extends Controller{
		
		public function before(){
			parent::before();
			if(! Model_Users::checkSession()){
				Session::destroy();
				$this->redirect('login');
			}
		}
		
		public function display(){
			$id = Session::get('id');
			$images = Model_Users::getImagesByUserId($id);
			return View::load('profile')->set('images', $images)->render();
		}
		
		public function upload(){
			if(! isset($_FILES['myfile'])){
				$this->redirect('user/'.Session::get('un'));
			}
			$file = $_FILES['myfile'];
			if(Upload::valid($file) && Upload::type($file, array('jpg', 'gif', 'png'))){
				$newname = Upload::save($file, 'files/images/');
				Model_Users::insertImage($newname, $file['name']);
			}
			
			$this->redirect('user/'.Session::get('un'));
		}
		
		public function delete(){
			$img_id = $this->param('imgid');
			$uid = Session::get('id');
			Model_Users::deleteImageByUserId($uid, $img_id);
			$this->redirect('user/'.Session::get('un'));
		}
		
	}
