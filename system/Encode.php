<?php

	class Encode{
		/**
	     * Encrypts a string and returns an encrypted string that can be decoded.
	     *
	     *     $data = Encode::encrypt($data);
	     *
	     * The encrypted binary data is encoded using [base64](http://php.net/base64_encode)
	     * to convert it to a string. This string can be stored in a database,
	     * displayed, and passed using most other means without corruption.
	     *
	     * @param   string  $data   data to be encrypted
	     * @return  string
	     */
		public static function encrypt($data){
			return base64_encode($data);
		}
		
		/**
	     * Decrypts an encoded string back to its original value.
	     *
	     *     $data = Encode::decrypt($data);
	     *
	     * @param   string  $data   encoded string to be decrypted
	     * @return  string
	     */
		public static function decrypt($data){
			return base64_decode($data);
		}
		
	}
