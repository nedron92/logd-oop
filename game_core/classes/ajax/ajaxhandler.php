<?php
namespace ajax;
defined('CORE_PATH') or die('No direct script access.');

/**
 * @file    ajaxhandler.php
 * @author  Daniel Becker   <becker_leinad@hotmail.com>
 * @date    26.02.2015
 * @package game_core
 * @subpackage  ajax
 *
 * @description
 * this class is an wrapper for all ajax-requests on this game
 */
class AjaxHandler
{

	/**
	 * The constructor need the method-name, because it will called it - if we have an ajax-request
	 *
	 * @param   string  $s_method   the name of the method to call
	 * @param   array   $a_args     an array with all arguments of the needed method
	 *
	 */
	public function __construct($s_method,$a_args=array())
	{
		if($this->is_ajax())
		{
			if($a_args === array()) {
				$this->$s_method();
			} else {
				$this->$s_method($a_args);
			}
		}

		exit(1);
	}

	/**
	 * This method checks, if we really have an AJAX-Request and return a boolean value
	 *
	 * @return bool     Return TRUE, if its a AJAX-Request and false otherwise
	 */
	private function is_ajax()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}


	/**
	 *
	 */
	private function is_session_expired()
	{
		$a_return_values = array();
		$b_session_valid = true;

		$o_session = \Session::get_session();

		if (!$o_session->is_session_valid()) {

			$s_message = \View::create('message')->render(false,true);

			$b_session_valid = false;
			$o_session->create_new_session();
			$a_return_values['message'] = $s_message;
		}

		$a_return_values['session_valid'] = $b_session_valid;

		echo json_encode($a_return_values);

	}

	/**
	 * @return bool
	 */
	private function fallback()
	{
		return false;
	}
}

