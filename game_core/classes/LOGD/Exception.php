<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    Exception.php
 * @author  Daniel Becker   <becker_leinad@hotmail.com>
 * @date    27.02.2015
 * @package game_core
 * @subpackage LOGD
 *
 * @description
 * This specific Exception class extends the PHP Exception
 */

class LOGD_Exception extends Exception{

	private $m_code;
	/**
	 * Creates a new exception.
	 *
	 * @param   string      $message    error message
	 * @param   int|string  $code       the exception code
	 * @param   Exception   $previous   Previous exception
	 */
	public function __construct($message = "", $code = 0, Exception $previous = NULL)
	{
		// Pass the message and integer code to the parent
		parent::__construct($message, (int) $code, $previous);

		// Save the unmodified code
		// @link http://bugs.php.net/39615
		$this->m_code = $code;
	}

	/**
	 * Printed out the errors to the error view
	 */
	public function print_error(){
		define('ERROR_MESSAGE',$this->message);
		define('ERROR_CODE',$this->m_code);

		if(strpos(ERROR_CODE,'TEMPLATE') !== false) View::render('error',false);
		else View::render('error');

		exit(1);
	}
} 