<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    Globals.php
 * @author  Daniel Becker   <becker_leinad@hotmail.com>
 * @date    22.02.2015
 * @package game_core
 * @subpackage LOGD
 *
 * @description
 * This class represent an Access to $GLOBALS
 */

class LOGD_Globals
{
	/**
	 * Function to set a new value to the global variable
	 *
	 * @param string    $name   the name of the global variable
	 * @param string    $value  the value to set te requested global variable
	 */
	static public function set($name, $value)
	{
		$GLOBALS[$name] = $value;
	}

	/**
	 * Function to get a value of the specific global variable
	 *
	 * @param string    $name   the name of the global variable
	 *
	 * @return mixed    the value of the requested global variable
	 */
	static public function get($name)
	{
		return $GLOBALS[$name];
	}
} 