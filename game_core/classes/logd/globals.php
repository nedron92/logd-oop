<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    Globals.php
 * @author  Daniel Becker   <becker_leinad@hotmail.com>
 * @date    22.02.2015
 * @package game_core
 * @subpackage LOGD
 *
 * @description
 * This class represent an access to $GLOBALS
 */

class LOGD_Globals
{
	/**
	 * Method to set a new value to the global variable or an global array
	 *
	 * @param string        $s_name   the name of the global variable
	 * @param string        $s_value  the value to set te requested global variable
	 * @param null|string   $s_index  optional, the index of an global array
	 */
	static public function set($s_name,$s_value,$s_index=null)
	{
		//check if we had an index so we can set the value to an global array
		//if not, set the value to the global-variable directly
		if (!is_null($s_index)) {
			$GLOBALS[$s_name][$s_index] = $s_value;
		}else{
			$GLOBALS[$s_name] = $s_value;
		}
	}

	/**
	 * Method to get a value of the specific global variable
	 *
	 * @param string    $s_name   the name of the global variable
	 *
	 * @return mixed    the value of the requested global variable
	 */
	static public function get($s_name)
	{
		return $GLOBALS[$s_name];
	}
} 