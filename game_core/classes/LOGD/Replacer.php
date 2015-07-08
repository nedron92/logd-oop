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
			$output = '<p class="nav-head">&#151;  '.$title.'  &#151;</p>';
			Template::get_instance()->set_output('navigation',$output);
		}
		else Template::get_instance()->set_output('navigation','<a href="'.$link.'">'.$title.'</a>');
	}

	/**
	 * Set/add new text to the game content
	 *
	 * @param string    $output     the new output of the game content
	 */
	public static function output($output)
	{
		Template::get_instance()->set_output('game',$output.'<br>');
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
			Template::get_instance()->set_output('stats-head',$title,true);
		}else
		{
			Template::get_instance()->set_output('stats-left',$title,true);
			Template::get_instance()->set_output('stats-right',$value,true);
		}

	}

	/**
	 * Setting the title of the game (head)
	 *
	 * @param string    $title  set the new game title
	 */
	public static function page_header($title)
	{
		Template::get_instance()->set_output('title',$title);
	}

	/**
	 * Setting the page footer of the game.
	 * Do a replacement of copyright/pagegen/version/source here.
	 */
	public static function page_footer()
	{
		Template::get_instance()->set_output('copyright',LOGD::LOGD_COPYRIGHT);
		Template::get_instance()->set_output('pagegen','Pagegen: '.round(microtime(true)-PAGE_START_TIME,3).'s');
		Template::get_instance()->set_output('version','Version: '.LOGD::LOGD_VERSION);
		Template::get_instance()->set_output('source','Sourcelink:');
	}

	/**
	 * Setting the links for motd, mail etc.
	 *
	 * @param array $a_links    the array of all needed links
	 */
	public static function set_links($a_links=array())
	{
		foreach($a_links as $key => $value)
		{
			Template::get_instance()->set_output($key,'TRANSLATE');
			Template::get_instance()->set_output($key.'_link',$value);
		}
	}
}