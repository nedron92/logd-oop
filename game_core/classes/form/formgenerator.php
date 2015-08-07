<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    formgenerator.php
 * @author  Daniel   <becker_leinad@hotmail.com>
 * @date    06.08.2015
 * @package game_core
 * @subpackage form
 *
 * @description
 *
 */
class Form_Formgenerator {

	private static $s_form_html = null;

	public static function get_form_html()
	{
		return self::$s_form_html;
	}

	public static function open()
	{

	}
}