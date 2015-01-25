<?php
	class Session{
		
		/**
	     * Sets a signed session.
	     *
	     *     // Set the "theme" session
	     *     Session::set('theme', 'red');
	     *
	     * @param   string  $name       key for session
	     * @param   string  $value      value for session
		 */
		public static function set($key, $value){
			if(! isset($_SESSION)){
				session_start();
			}
			$_SESSION[$key] = $value;
		}
		
		/**
	     * Gets the value of a session key.
	     *
	     *     // Get the "theme" session, or use "blue" if the cookie does not exist
	     *     $theme = Session::get('theme', 'blue');
	     *
	     * @param   string  $key        session key name
	     * @param   mixed   $default    default value to return
	     * @return  string
	     */
		public static function get($key, $default = FALSE){
			if(! isset($_SESSION)){
				session_start();
			}
			
			if(isset($_SESSION[$key])){
				return $_SESSION[$key];
			}
			
			return $default;
		}
		
		/**
	     * Deletes a session key by unset it.
	     *
	     *     Session::delete('theme');
		 * 
		 * @param   string  $key        session key name
	     *
	     */
		public static function delete($key){
			if(isset($_SESSION[$key])){
				unset($_SESSION[$key]);
			}
			
			// @unset($_SESSION[$key]);
		}
		
		/**
	     * Remove the session completely
	     *
	     *     Session::destroy();
	     *
	     */
		public static function destroy(){
			if(isset($_SESSION)){
				$_SESSION = array();
				session_destroy();
			}
		}
		
	}
