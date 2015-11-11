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
	public function __construct()
	{
		$s_method = ltrim($_SERVER['PATH_INFO'],'/\\');
		$o_method = false;

		try{
			$o_method = new \ReflectionMethod(__CLASS__,$s_method);
		}catch (\ReflectionException $e)
		{
			$test = \session\SessionClass::get_session();
			var_dump($o_method);
		}

	}

	public static function is_ajax()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

	public static function index()
	{
		if(AjaxHandler::is_ajax())
		{
			new AjaxHandler();
			exit (1);
		}
	}

}

