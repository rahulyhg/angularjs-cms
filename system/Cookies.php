<?php
	class Cookies{
		
		/**
	     * Sets a signed cookie. 
		 * Note that all cookie values must be strings and no
	     * automatic serialization will be performed!
	     *
	     *     // Set the "theme" cookie
	     *     Cookie::set('theme', 'red', 0);
	     *
	     * @param   string  $name       name of cookie
	     * @param   string  $value      value of cookie
	     * @param   integer $expiration lifetime in seconds
		 */
		public static function set($name, $value = '', $expire = 0, $path = NULL, $domain = NULL, $secure = FALSE, $httponly = TRUE){
			// $expire = (int) $expire;
			if(! is_int($expire)){
				$expire = 0;
			}
			
			if($expire !== 0){
				$expire = time() + $expire;
			}
			setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
		}
		
		/**
	     * Gets the value of a cookie.
	     *
	     *     // Get the "theme" cookie, or use "blue" if the cookie does not exist
	     *     $theme = Cookie::get('theme', 'blue');
	     *
	     * @param   string  $key        cookie name
	     * @param   mixed   $default    default value to return
	     * @return  string
	     */
		public static function get($key, $default = FALSE){
			if(isset($_COOKIE[$key])){
				return $_COOKIE[$key];
			}
			
			return $default;
		}
		
		/**
	     * Deletes a cookie by making the value NULL and expiring it.
	     *
	     *     Cookie::delete('theme');
	     *
	     * @param   string  $name   cookie name
	     */
		public static function delete($key){
			if(! isset($_COOKIE[$key])){
				return;
			}
			
			unset($_COOKIE[$key]);
			setcookie($key, '', time() - 86400);
		}
		
	}
