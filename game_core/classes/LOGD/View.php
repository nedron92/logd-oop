<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    View.php
 * @author  Daniel Becker   <becker_leinad@hotmail.com>
 * @date    27.02.2015
 * @package game_core
 * @subpackage  LOGD
 *
 * @description
 * the View Class of the game.
 * It search and render the given View
 */

class LOGD_View {

	/**
	 * @var  array   The directory in which the views are located
	 */
	protected static $s_views = 'views';

	/**
	 * this method will render the current required view
	 *
	 * @param string $file           the view-file to search for
	 * @param bool   $with_template  render view with the template output. DEFAULT: TRUE
	 */
	public static function render($file='start', $with_template=true)
	{
		$path = LOGD::find_file(self::$s_views,$file);

		// Load the view
		include $path;

		//Render the current template
		if($with_template) Template::get_instance()->render();
	}
} 