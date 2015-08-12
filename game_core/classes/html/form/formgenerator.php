<?php
namespace html;
defined('CORE_PATH') or die('No direct script access.');
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
class Form_Formgenerator extends HTMLCLASS{

	private static $s_form_html = null;

	public static function get_form_html()
	{
		return self::$s_form_html;
	}

	public static function open()
	{

	}

	public static function input($s_name, $a_attributes=array(), $s_id = null, $s_styles=null)
	{
		if(!is_null($s_id)) {
			$a_attributes['id'] = $s_id;
		}

		return self::create_element('input',$s_name,$a_attributes=array(),$s_styles,true);
	}
}