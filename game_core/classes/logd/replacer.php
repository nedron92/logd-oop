<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    Replacer.php
 * @author  Daniel Becker   <becker_leinad@hotmail.com>
 * @date    10.03.2015
 * @package game_core
 * @subpackage LOGD
 *
 * @description
 * This class represents a layer between the Template-Class and replacement of the template-tags
 * It will be defined the old functions of the original LOGD 0.9.7 (such as addnav etc.)
 */

class LOGD_Replacer {

	/**
	 * Add a new navigation item or title, if $link is false.
	 *
	 * @param string        $title  the title/name of the navigation item
	 * @param null|string   $link   the link of the navigation item
	 */
	public static function addnav($title,$link=null)
	{
		if(!$link) {
			$output = '<p class="nav-head">&#151;  '.__($title).'  &#151;</p>';
			Template::get_instance()->set_output('navigation',__($output));
		}
		else Template::get_instance()->set_output('navigation','<a href="'.$link.'?'.LOGD_Core::create_random_uri_string().'">'.__($title).'</a>');
	}

	/**
	 * Set/add new text to the game content
	 *
	 * @param string    $output     the new output of the game content
	 */
	public static function output($output)
	{
		Template::get_instance()->set_output('game',__($output).'<br>');
	}

	/**
	 * Adding a new charstat to the statsbar or a new title if $value is null.
	 *
	 * @param string        $title  the title of the new charstat
	 * @param null|string   $value  the value of the charstat
	 */
	public static function addcharstat($title,$value=null)
	{
		if(is_null($value))
		{
			Template::get_instance()->set_output('stats-head',__($title),true);
		}else
		{
			Template::get_instance()->set_output('stats-left',__($title),true);
			Template::get_instance()->set_output('stats-right',__($value),true);
		}

	}

	/**
	 * Setting the title of the game (head)
	 *
	 * @param string    $title  set the new game title
	 */
	public static function page_header($title)
	{
		Template::get_instance()->set_output('title',__($title));
	}

	/**
	 * Setting the page footer of the game.
	 * Do a replacement of copyright/pagegen/version/source here.
	 */
	public static function page_footer()
	{
		Template::get_instance()->set_output('copyright',LOGD::LOGD_COPYRIGHT);
		Template::get_instance()->set_output('pagegen',__('pagegen').': '.round(microtime(true)-PAGE_START_TIME,3).'s');
		Template::get_instance()->set_output('version',__('version').': '.LOGD::LOGD_VERSION);
		Template::get_instance()->set_output('source',__('source').':');
	}

	/**
	 * Setting the links for motd, mail etc.
	 *
	 * @param array $a_links        the array of all needed links
	 */
	public static function set_links($a_links=array())
	{
		if($a_links === array())
		{ $a_links = Config::get_default_html('section_more'); }

		foreach($a_links as $key => $value)
		{
			$key_value = __($key);

			if(is_null($value))
			{ $key_value = null; }

			//todo do translation later
			Template::get_instance()->set_output($key,$key_value);
			if ( is_null($key_value) ) {
				Template::get_instance()->set_output($key.'-link',$value);
			}else{
				Template::get_instance()->set_output($key.'-link',$value.'?'.LOGD_Core::create_random_uri_string());
			}
		}
	}

	/**
	 * @param array $a_hidden_fields
	 */
	public static function set_hidden_fields($a_hidden_fields=array())
	{
		if($a_hidden_fields !== array()) {
			foreach($a_hidden_fields as $s_hidden_field) {
				$s_hidden_field.='-hidden';
				Template::get_instance()->set_output($s_hidden_field,'hidden');
			}
		}
	}
}