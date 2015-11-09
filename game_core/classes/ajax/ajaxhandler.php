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

	}

	public static function is_ajax()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

}

if(AjaxHandler::is_ajax())
{
	new AjaxHandler();
}