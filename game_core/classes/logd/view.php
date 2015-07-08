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

	protected $s_filename = null;

	/**
	 * @var  array   An array of all local variables for the current view
	 */
	protected $a_variables = array();

	/**
	 * The constructor will set the filename for the view
	 *
	 * @param null|string $s_file   the filename of the view
	 * @fallback set the view to start and log the failure
	 */
	public function __construct($s_file = null)
	{
		if($s_file === null)
		{
			$this->s_filename = 'start';
		}else{
			$this->s_filename = $s_file;
		}
	}

	/**
	 * This static method creates a new View -> Object and return it.
	 *
	 * @param null|string $s_file the filename of the view
	 * @return View
	 */
	public static function create($s_file = null)
	{
		return new View($s_file);
	}

	/**
	 * this method will render the current required view,
	 * without any local variables within the view
	 *
	 * @param string $file           the view-file to search for
	 * @param bool   $with_template  render view with the template output. DEFAULT: TRUE
	 */
	public static function render_statically($file='start', $with_template=true)
	{
		$path = LOGD::find_file(self::$s_views,$file);

		// Load the view
		include $path;

		//Render the current template
		if($with_template) Template::get_instance()->render();
	}

	public function render($with_template = true)
	{
		$path = LOGD::find_file(self::$s_views,$this->s_filename);

		//import all local variables to the view
		extract($this->a_variables, EXTR_SKIP);

		// Load the view
		include $path;

		//Render the current template
		if($with_template) Template::get_instance()->render();
	}

	public function bind_by_value($key, & $value)
	{
		$this->a_variables[$key] =& $value;

		return $this;
	}
	public function bind_by_name($key, $value)

	{
		$this->a_variables[$key] = $value;

		return $this;
	}
} 