<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    Config.php
 * @author  Daniel Becker   <becker_leinad@hotmail.com>
 * @date    22.02.2015
 * @package game_core
 * @subpackage  LOGD
 *
 * @description
 * Configuration File of this project.
 * Enable the modules and return an array with all needed configs.
 */

class LOGD_Config
{
	/**
	 * Get an array of all configured settings
	 *
	 * @return array    hold all config settings
	 */
	public static function get_config()
	{
		return array(

			//Enable or disable the modules
			//Don't disable the Game and the Database modules ;)

			'modules' => array(
				//'Database' => MOD_PATH.'database',
			)
		);
	}

	/**
	 * Get only the configured modules, no more.
	 *
	 * @return mixed    get all configured modules
	 */
	public static function get_configured_modules()
	{
		return self::get_config()['modules'];
	}
}
