<?php
namespace ajax;

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

//alpha
	public function __construct($s_method)
	{
		if(AjaxHandler::is_ajax())
		{
			$o_method_name = 'fallback';

			try{
				$o_method = new \ReflectionMethod(__CLASS__,$s_method);
				$o_method_name = $o_method->getName();
			}catch (\ReflectionException $e)
			{
				echo $e->getMessage();
			}

			return $this->$o_method_name();
		}

		exit(1);
	}

	public static function is_ajax()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}


	public function is_session_expired()
	{
		$b_session_valid = true;

		$o_session = \session\SessionClass::get_session();

		if (!$o_session->is_session_valid()) {
			$b_session_valid = false;
			$o_session->create_new_session();
		}

		$a_return_values = array();
		$a_return_values['session_valid'] = json_encode($b_session_valid);

		return json_encode($a_return_values);

	}

	public function fallback()
	{
		return false;
	}
}

