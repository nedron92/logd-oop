<?php
defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    htmlclass.php
 * @author  Daniel   <becker_leinad@hotmail.com>
 * @date    08.08.2015
 * @package game_core
 * @subpackage html
 *
 * @description
 * A wrapper to create html elements with attributes
 */

class HTML_HTMLCLASS {

	public static function create_element($s_element, $s_name, $a_attributes=array(), $s_styles=null,$b_self_close=false)
	{
		$s_html = '<'.$s_element.' ';
		if( $a_attributes !== array() ) {
			foreach($a_attributes as $s_attribute_name => $s_attribute_value)
			{
				$s_html .= $s_attribute_name.'="'.$s_attribute_value.'" ';
			}
		}

		if($b_self_close === false) {
			$s_html .= '>'.$s_name .'</'.$s_element.'>';
		}else{
			$s_html .= ' />';
		}

		return $s_html;
	}

	protected static function input($s_name, $a_attributes=array(), $s_id = null, $s_styles=null)
	{
		if(!is_null($s_id)) {
			$a_attributes['id'] = $s_id;
		}

		return self::create_element('input',$s_name,$a_attributes=array(),$s_styles,true);
	}

	public static function link($s_link, $s_title, $s_id = null, $s_target=null, $s_type = null, $b_is_download=false)
	{
		$a_attributes = array();
		if(!is_null($s_target)) {
			$a_attributes['target'] = $s_target;
		}

		if(!is_null($s_id)) {
			$a_attributes['id'] = $s_id;
		}

		if(!is_null($s_type)) {
			$a_attributes['type'] = $s_type;
		}

		if($b_is_download == true) {
			$a_attributes['download'] = $s_title;
		}

		$a_attributes['href'] = $s_link;

		return self::create_element('a',$s_title,$a_attributes);
	}


	public static function iframe($s_link,$s_id=null,$a_attributes=array())
	{
		if(!is_null($s_id)) {
			$a_attributes['id'] = $s_id;
		}

		$a_attributes['src'] = $s_link;

		return self::create_element('iframe',null,$a_attributes);
	}

	public static function embed($s_src,$s_type, $s_id=null, $a_attributes=array())
	{
		if(!is_null($s_id)) {
			$a_attributes['id'] = $s_id;
		}

		$a_attributes['src'] = $s_src;

		return self::create_element('embed',null,$a_attributes);
	}
}