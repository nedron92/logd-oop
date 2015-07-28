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

	/**
	 * @var mixed (int|string)  the exception code
	 */
	private $m_code;

	private $s_trace;

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
		$this->s_trace = $this->getTraceAsString();
	}

	/**
	 * Printed out the errors to the error view
	 */
	public function print_error() {

		if(strpos($this->m_code,'TEMPLATE') !== false) {
			View::create('error')
				->bind_by_name('s_error_message',$this->message)
				->bind_by_name('s_error_code'   ,$this->m_code)
				->render(false);
		}
		else {
			View::create('error')
				->bind_by_name('s_error_message',$this->message)
				->bind_by_name('s_error_code'   ,$this->m_code)
				->bind_by_name('s_error_file'   ,$this->file)
				->bind_by_name('s_error_line'   ,$this->line)
				->render();
		}

		exit(1);
	}
} 