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
	 * @var null|string the name of the view-file
	 */
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
		if($s_file === null) {
			$this->s_filename = 'start';
		}else{
			$this->s_filename = $s_file;
		}
	}

	/**
	 * this method will do code before the view will be rendered
	 */
	public function before()
	{
		Replacer::set_links();
		Replacer::page_footer();
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
	 * Method for rendering the view and output it to the browser.
	 *
	 * @param bool  $with_template      If true, the method will render also the full template, if not the output
	 *                                      will be without template.
	 *                                      Default: true
	 *
	 * @param bool  $b_return_output    If true, it will return the output and not printed it out
	 *
	 * @return string|null
	 */
	public function render($with_template = true, $b_return_output = false)
	{
		if($with_template) $this->before();

		$path = LOGD::find_file(self::$s_views,$this->s_filename);

		//import all local variables to the view
		extract($this->a_variables, EXTR_SKIP);

		if($b_return_output) ob_start();
		// Load the view
		include $path;

		if($b_return_output) return trim(ob_get_clean());

		//Render the current template
		if($with_template) Template::get_instance()->render();

		return null;
	}

	/**
	 * It can be bind a variable to the view by a given value (other variable, because of reference).
	 * In the view the variable with the name of 'key' will be able to us as "$key" variable.
	 *
	 * @param string    $key    the name of the new/binded variable
	 * @param mixed     $value  the value of the variable (must be a other variable)
	 * @return View     return the object itself
	 */
	public function bind_by_value($key, & $value)
	{
		$this->a_variables[$key] =& $value;

		return $this;
	}

	/**
	 * It can be bind a variable to the view by a given value (string here)
	 * In the view the variable with the name of 'key' will be able to us as "$key" variable.
	 *
	 * @param string    $key    the name of the new/binded variable
	 * @param string    $value  the value of the variable (must be a string here)
	 * @return View     return the object itself
	 */
	public function bind_by_name($key, $value)

	{
		$this->a_variables[$key] = $value;

		return $this;
	}

} 